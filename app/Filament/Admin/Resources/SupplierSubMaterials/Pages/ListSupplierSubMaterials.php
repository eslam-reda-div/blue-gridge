<?php

namespace App\Filament\Admin\Resources\SupplierSubMaterials\Pages;

use App\Filament\Admin\Resources\SupplierSubMaterials\SupplierSubMaterialResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSupplierSubMaterials extends ListRecords
{
    protected static string $resource = SupplierSubMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
