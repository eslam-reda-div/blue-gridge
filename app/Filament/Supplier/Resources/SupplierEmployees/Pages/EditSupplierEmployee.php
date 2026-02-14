<?php

namespace App\Filament\Supplier\Resources\SupplierEmployees\Pages;

use App\Filament\Supplier\Resources\SupplierEmployees\SupplierEmployeeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSupplierEmployee extends EditRecord
{
    protected static string $resource = SupplierEmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
