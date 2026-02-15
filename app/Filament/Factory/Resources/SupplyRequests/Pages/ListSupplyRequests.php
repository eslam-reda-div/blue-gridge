<?php

namespace App\Filament\Factory\Resources\SupplyRequests\Pages;

use App\Filament\Factory\Resources\SupplyRequests\SupplyRequestResource;
use App\Jobs\SendSupplyRequest;
use App\Models\FactorySubMaterial;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class ListSupplyRequests extends ListRecords
{
    protected static string $resource = SupplyRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('manualRequest')
                ->label('New Supply Request')
                ->icon(Heroicon::OutlinedPaperAirplane)
                ->form([
                    Select::make('factory_sub_material_id')
                        ->label('Sub-Material')
                        ->options(function (): array {
                            $factoryId = Auth::guard('factory')->user()?->factory_id;

                            return FactorySubMaterial::withoutGlobalScopes()
                                ->where('factory_id', $factoryId)
                                ->with('materialSubCategory.material')
                                ->get()
                                ->mapWithKeys(fn (FactorySubMaterial $item): array => [
                                    $item->id => "{$item->materialSubCategory->material->name} — {$item->materialSubCategory->name} (Stock: {$item->quantity})",
                                ])
                                ->all();
                        })
                        ->required()
                        ->searchable()
                        ->preload(),
                    TextInput::make('quantity_needed')
                        ->label('Quantity Needed')
                        ->required()
                        ->numeric()
                        ->minValue(0.01),
                ])
                ->action(function (array $data): void {
                    $factoryId = Auth::guard('factory')->user()?->factory_id;

                    $subMaterial = FactorySubMaterial::withoutGlobalScopes()
                        ->where('factory_id', $factoryId)
                        ->findOrFail($data['factory_sub_material_id']);

                    SendSupplyRequest::dispatch(
                        $factoryId,
                        $subMaterial->material_sub_category_id,
                        (float) $data['quantity_needed'],
                        'manual',
                    );

                    Notification::make()
                        ->title('Supply request dispatched')
                        ->body('The request is being sent to eligible suppliers.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
