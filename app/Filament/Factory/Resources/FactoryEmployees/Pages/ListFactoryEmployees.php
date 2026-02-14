<?php

namespace App\Filament\Factory\Resources\FactoryEmployees\Pages;

use App\Filament\Factory\Resources\FactoryEmployees\FactoryEmployeeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFactoryEmployees extends ListRecords
{
    protected static string $resource = FactoryEmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
