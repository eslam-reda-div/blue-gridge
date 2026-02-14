<?php

namespace App\Filament\Factory\Resources\FactoryMaterialTransactions\Pages;

use App\Filament\Factory\Resources\FactoryMaterialTransactions\FactoryMaterialTransactionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFactoryMaterialTransaction extends EditRecord
{
    protected static string $resource = FactoryMaterialTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
