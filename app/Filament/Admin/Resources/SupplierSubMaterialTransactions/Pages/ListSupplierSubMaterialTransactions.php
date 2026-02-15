<?php

namespace App\Filament\Admin\Resources\SupplierSubMaterialTransactions\Pages;

use App\Filament\Admin\Resources\SupplierSubMaterialTransactions\SupplierSubMaterialTransactionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSupplierSubMaterialTransactions extends ListRecords
{
    protected static string $resource = SupplierSubMaterialTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
