<?php

namespace App\Filament\Supplier\Resources\Factories\Pages;

use App\Filament\Supplier\Resources\Factories\FactoryResource;
use Filament\Resources\Pages\ListRecords;

class ListFactories extends ListRecords
{
    protected static string $resource = FactoryResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
