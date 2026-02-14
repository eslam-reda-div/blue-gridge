<?php

namespace App\Filament\Supplier\Resources\SupplierEmployees\Pages;

use App\Filament\Supplier\Resources\SupplierEmployees\SupplierEmployeeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSupplierEmployees extends ListRecords
{
    protected static string $resource = SupplierEmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
