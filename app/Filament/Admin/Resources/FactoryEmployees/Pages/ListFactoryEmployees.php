<?php

namespace App\Filament\Admin\Resources\FactoryEmployees\Pages;

use App\Filament\Admin\Resources\FactoryEmployees\FactoryEmployeeResource;
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
