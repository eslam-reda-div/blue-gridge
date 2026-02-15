<?php

namespace App\Filament\Supplier\Resources\SupplierSubMaterialTransactions\Pages;

use App\Filament\Supplier\Resources\SupplierSubMaterialTransactions\SupplierSubMaterialTransactionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSupplierSubMaterialTransaction extends EditRecord
{
    protected static string $resource = SupplierSubMaterialTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
