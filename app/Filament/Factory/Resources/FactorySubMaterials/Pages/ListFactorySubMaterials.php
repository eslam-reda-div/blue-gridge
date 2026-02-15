<?php

namespace App\Filament\Factory\Resources\FactorySubMaterials\Pages;

use App\Filament\Factory\Resources\FactorySubMaterials\FactorySubMaterialResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFactorySubMaterials extends ListRecords
{
    protected static string $resource = FactorySubMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
