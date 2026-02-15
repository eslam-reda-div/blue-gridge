<?php

namespace App\Filament\Supplier\Pages;

use App\Models\SellingOffer;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class SellerOffers extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $title = 'Seller Offers';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;

    protected static ?string $navigationLabel = 'Seller Offers';

    protected string $view = 'filament.supplier.pages.seller-offers';

    public function table(Table $table): Table
    {
        $supplierId = Auth::guard('supplier')->user()?->supplier_id;

        return $table
            ->query(
                SellingOffer::query()
                    ->where(function (Builder $query) use ($supplierId): void {
                        $query->where('type', 'broadcast')
                            ->orWhere(function (Builder $sub) use ($supplierId): void {
                                $sub->where('type', 'targeted')
                                    ->whereHas('targets', function (Builder $targetQuery) use ($supplierId): void {
                                        $targetQuery->where('supplier_id', $supplierId);
                                    });
                            });
                    })
                    ->with(['seller', 'materialSubCategory.material', 'acceptedBySupplier'])
            )
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('Offer ID')
                    ->sortable(),
                TextColumn::make('seller.name')
                    ->label('Seller')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('materialSubCategory.material.name')
                    ->label('Material')
                    ->searchable(),
                TextColumn::make('materialSubCategory.name')
                    ->label('Sub-Category')
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label('Quantity')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('price_per_unit')
                    ->label('Price/Unit')
                    ->numeric(decimalPlaces: 2)
                    ->placeholder('Negotiable')
                    ->sortable(),
                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'broadcast' => 'info',
                        'targeted' => 'primary',
                        default => 'gray',
                    }),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'warning',
                        'accepted' => 'success',
                        'closed' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('acceptedBySupplier.name')
                    ->label('Accepted By')
                    ->placeholder('—'),
                TextColumn::make('created_at')
                    ->label('Posted At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'open' => 'Open',
                        'accepted' => 'Accepted',
                        'closed' => 'Closed',
                    ]),
                SelectFilter::make('type')
                    ->options([
                        'broadcast' => 'Broadcast',
                        'targeted' => 'Targeted',
                    ]),
            ])
            ->recordActions([
                Action::make('view_details')
                    ->label('View Details')
                    ->icon(Heroicon::OutlinedEye)
                    ->color('info')
                    ->modalHeading(fn (SellingOffer $record): string => "Offer #{$record->id} — {$record->materialSubCategory?->name}")
                    ->modalContent(fn (SellingOffer $record) => view('filament.supplier.pages.seller-offer-details', [
                        'offer' => $record->load(['seller', 'materialSubCategory.material', 'acceptedBySupplier']),
                    ]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),
                Action::make('accept')
                    ->label('Accept Offer')
                    ->icon(Heroicon::OutlinedCheck)
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Accept Selling Offer')
                    ->modalDescription('Are you sure you want to accept this offer? You are committing to buy this material from the seller.')
                    ->visible(fn (SellingOffer $record): bool => $record->isOpen())
                    ->action(function (SellingOffer $record): void {
                        $this->acceptOffer($record);
                    }),
            ])
            ->toolbarActions([]);
    }

    protected function acceptOffer(SellingOffer $offer): void
    {
        $supplierId = Auth::guard('supplier')->user()?->supplier_id;

        if (! $offer->isOpen()) {
            Notification::make()
                ->title('Offer No Longer Available')
                ->body('This offer has already been accepted or closed.')
                ->danger()
                ->send();

            return;
        }

        $offer->update([
            'status' => 'accepted',
            'accepted_by_supplier_id' => $supplierId,
            'accepted_at' => now(),
        ]);

        Notification::make()
            ->title('Offer Accepted')
            ->body("You have accepted the offer #{$offer->id} for {$offer->quantity} units of {$offer->materialSubCategory?->name}.")
            ->success()
            ->send();
    }
}
