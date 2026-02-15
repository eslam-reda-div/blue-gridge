<?php

namespace App\Filament\Admin\Resources\FactorySubMaterials\Pages;

use App\Filament\Admin\Resources\FactorySubMaterials\FactorySubMaterialResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFactorySubMaterial extends EditRecord
{
    protected static string $resource = FactorySubMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
