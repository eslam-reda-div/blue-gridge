<?php

namespace App\Filament\Factory\Resources\FactoryMaterials\Pages;

use App\Filament\Factory\Resources\FactoryMaterials\FactoryMaterialResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFactoryMaterials extends ListRecords
{
    protected static string $resource = FactoryMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
