<?php

namespace App\Filament\Admin\Resources\FactorySubMaterialTransactions\Pages;

use App\Filament\Admin\Resources\FactorySubMaterialTransactions\FactorySubMaterialTransactionResource;
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
