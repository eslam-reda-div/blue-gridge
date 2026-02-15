<?php

namespace App\Filament\Supplier\Resources\SupplierSubMaterials\Pages;

use App\Filament\Supplier\Resources\SupplierSubMaterials\SupplierSubMaterialResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSupplierSubMaterial extends EditRecord
{
    protected static string $resource = SupplierSubMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
