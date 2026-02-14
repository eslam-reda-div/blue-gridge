<?php

namespace App\Filament\Factory\Resources\FactoryMaterialTransactions\Pages;

use App\Filament\Factory\Resources\FactoryMaterialTransactions\FactoryMaterialTransactionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFactoryMaterialTransactions extends ListRecords
{
    protected static string $resource = FactoryMaterialTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
