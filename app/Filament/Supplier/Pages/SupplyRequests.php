<?php

namespace App\Filament\Supplier\Pages;

use App\Models\SupplyRequestLog;
use App\Models\SupplyRequestSupplier;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class SupplyRequests extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $title = 'Supply Requests';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxArrowDown;

    protected static ?string $navigationLabel = 'Supply Requests';

    protected string $view = 'filament.supplier.pages.supply-requests';

    public function table(Table $table): Table
    {
        $supplierId = Auth::guard('supplier')->user()?->supplier_id;

        return $table
            ->query(
                SupplyRequestSupplier::query()
                    ->where('supplier_id', $supplierId)
                    ->whereIn('status', ['pending', 'accepted', 'rejected'])
                    ->with([
                        'supplyRequest.factory',
                        'supplyRequest.materialSubCategory.material',
                    ])
            )
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('supplyRequest.id')
                    ->label('Request ID')
                    ->sortable(),
                TextColumn::make('supplyRequest.factory.name')
                    ->label('Factory')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('supplyRequest.materialSubCategory.material.name')
                    ->label('Material')
                    ->searchable(),
                TextColumn::make('supplyRequest.materialSubCategory.name')
                    ->label('Sub-Category')
                    ->searchable(),
                TextColumn::make('supplyRequest.quantity_needed')
                    ->label('Qty Needed')
                    ->numeric(decimalPlaces: 2),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'accepted' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('rejection_reason')
                    ->label('Rejection Reason')
                    ->limit(30)
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Received At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->recordActions([
                \Filament\Actions\Action::make('accept')
                    ->label('Accept')
                    ->icon(Heroicon::OutlinedCheck)
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Accept Supply Request')
                    ->modalDescription('Are you sure you want to accept this supply request? This will mark you as the fulfilling supplier.')
                    ->visible(fn (SupplyRequestSupplier $record): bool => $record->isPending() && $record->supplyRequest->isPending())
                    ->action(function (SupplyRequestSupplier $record): void {
                        $this->acceptRequest($record);
                    }),
                \Filament\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon(Heroicon::OutlinedXMark)
                    ->color('danger')
                    ->form([
                        Textarea::make('rejection_reason')
                            ->label('Reason for Rejection')
                            ->required()
                            ->maxLength(1000)
                            ->rows(3),
                    ])
                    ->visible(fn (SupplyRequestSupplier $record): bool => $record->isPending() && $record->supplyRequest->isPending())
                    ->action(function (SupplyRequestSupplier $record, array $data): void {
                        $this->rejectRequest($record, $data['rejection_reason']);
                    }),
            ])
            ->toolbarActions([]);
    }

    /**
     * Handle accepting a supply request.
     */
    protected function acceptRequest(SupplyRequestSupplier $entry): void
    {
        $supplyRequest = $entry->supplyRequest;

        // Double-check the request is still pending
        if (! $supplyRequest->isPending()) {
            Notification::make()
                ->title('This request has already been fulfilled')
                ->warning()
                ->send();

            return;
        }

        $supplier = $entry->supplier;

        // Mark this supplier as accepted
        $entry->update([
            'status' => 'accepted',
            'responded_at' => now(),
        ]);

        // Mark the request as accepted
        $supplyRequest->update([
            'status' => 'accepted',
            'accepted_by_supplier_id' => $entry->supplier_id,
        ]);

        // Dismiss all other pending supplier entries
        SupplyRequestSupplier::query()
            ->where('supply_request_id', $supplyRequest->id)
            ->where('id', '!=', $entry->id)
            ->where('status', 'pending')
            ->each(function (SupplyRequestSupplier $dismissedEntry) use ($supplyRequest): void {
                $dismissedEntry->update(['status' => 'dismissed']);

                SupplyRequestLog::create([
                    'supply_request_id' => $supplyRequest->id,
                    'supplier_id' => $dismissedEntry->supplier_id,
                    'action' => 'supplier_dismissed',
                    'details' => "Auto-dismissed: {$dismissedEntry->supplier->name} — another supplier accepted the request",
                ]);
            });

        // Log: supplier accepted
        SupplyRequestLog::create([
            'supply_request_id' => $supplyRequest->id,
            'supplier_id' => $entry->supplier_id,
            'action' => 'supplier_accepted',
            'details' => "Supplier {$supplier->name} accepted the supply request",
        ]);

        // Log: request fulfilled
        SupplyRequestLog::create([
            'supply_request_id' => $supplyRequest->id,
            'supplier_id' => $entry->supplier_id,
            'action' => 'request_fulfilled',
            'details' => "Supply request fulfilled by {$supplier->name}",
        ]);

        Notification::make()
            ->title('Supply request accepted')
            ->body("You have accepted the supply request for {$supplyRequest->materialSubCategory->name}.")
            ->success()
            ->send();
    }

    /**
     * Handle rejecting a supply request.
     */
    protected function rejectRequest(SupplyRequestSupplier $entry, string $reason): void
    {
        $supplyRequest = $entry->supplyRequest;
        $supplier = $entry->supplier;

        // Mark this supplier as rejected
        $entry->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'responded_at' => now(),
        ]);

        // Log: supplier rejected
        SupplyRequestLog::create([
            'supply_request_id' => $supplyRequest->id,
            'supplier_id' => $entry->supplier_id,
            'action' => 'supplier_rejected',
            'details' => "Supplier {$supplier->name} rejected the request. Reason: {$reason}",
        ]);

        // Check if ALL suppliers have now rejected (none pending, none accepted)
        $anyPendingOrAccepted = SupplyRequestSupplier::query()
            ->where('supply_request_id', $supplyRequest->id)
            ->whereIn('status', ['pending', 'accepted'])
            ->exists();

        if (! $anyPendingOrAccepted) {
            $supplyRequest->update(['status' => 'rejected']);

            SupplyRequestLog::create([
                'supply_request_id' => $supplyRequest->id,
                'action' => 'all_rejected',
                'details' => 'All suppliers have rejected this supply request',
            ]);
        }

        Notification::make()
            ->title('Supply request rejected')
            ->body("You have rejected the request with reason: {$reason}")
            ->danger()
            ->send();
    }
}
