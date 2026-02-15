<?php

namespace App\Filament\Factory\Resources\Suppliers\Pages;

use App\Filament\Factory\Resources\Suppliers\SupplierResource;
use App\Models\Factory;
use App\Models\Supplier;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListSuppliers extends ListRecords
{
    protected static string $resource = SupplierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('connectSupplier')
                ->label('Connect Supplier')
                ->icon(Heroicon::OutlinedLink)
                ->form([
                    Select::make('supplier_id')
                        ->label('Select Supplier')
                        ->options(function (): array {
                            $factoryId = Auth::guard('factory')->user()?->factory_id;

                            return Supplier::query()
                                ->whereDoesntHave(
                                    'factories',
                                    fn (Builder $query) => $query->where('factory_supplier.factory_id', $factoryId),
                                )
                                ->get()
                                ->mapWithKeys(fn (Supplier $supplier): array => [
                                    $supplier->id => "#{$supplier->id} — {$supplier->name} ({$supplier->location})",
                                ])
                                ->all();
                        })
                        ->required()
                        ->searchable()
                        ->preload(),
                ])
                ->action(function (array $data): void {
                    $factoryId = Auth::guard('factory')->user()?->factory_id;

                    Factory::findOrFail($factoryId)->suppliers()->attach($data['supplier_id']);

                    Notification::make()
                        ->title('Supplier connected successfully')
                        ->success()
                        ->send();
                }),
        ];
    }
}
