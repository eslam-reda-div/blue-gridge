<?php

namespace App\Filament\Factory\Resources\FactorySubMaterialTransactions\Pages;

use App\Filament\Factory\Resources\FactorySubMaterialTransactions\FactorySubMaterialTransactionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFactorySubMaterialTransaction extends EditRecord
{
    protected static string $resource = FactorySubMaterialTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
