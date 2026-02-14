<?php

namespace App\Filament\Factory\Resources\FactoryMaterials\Pages;

use App\Filament\Factory\Resources\FactoryMaterials\FactoryMaterialResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFactoryMaterial extends CreateRecord
{
    protected static string $resource = FactoryMaterialResource::class;
}
