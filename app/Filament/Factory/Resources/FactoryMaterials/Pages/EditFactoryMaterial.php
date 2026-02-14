<?php

namespace App\Filament\Factory\Resources\FactoryMaterials\Pages;

use App\Filament\Factory\Resources\FactoryMaterials\FactoryMaterialResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFactoryMaterial extends EditRecord
{
    protected static string $resource = FactoryMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
