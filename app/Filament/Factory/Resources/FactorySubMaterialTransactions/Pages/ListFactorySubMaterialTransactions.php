<?php

namespace App\Filament\Factory\Resources\FactorySubMaterialTransactions\Pages;

use App\Filament\Factory\Resources\FactorySubMaterialTransactions\FactorySubMaterialTransactionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFactorySubMaterialTransactions extends ListRecords
{
    protected static string $resource = FactorySubMaterialTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
