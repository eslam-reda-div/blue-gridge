<?php

namespace App\Filament\Factory\Resources\FactoryMaterialTransactions;

use App\Filament\Factory\Resources\FactoryMaterialTransactions\Pages\CreateFactoryMaterialTransaction;
use App\Filament\Factory\Resources\FactoryMaterialTransactions\Pages\EditFactoryMaterialTransaction;
use App\Filament\Factory\Resources\FactoryMaterialTransactions\Pages\ListFactoryMaterialTransactions;
use App\Filament\Factory\Resources\FactoryMaterialTransactions\Schemas\FactoryMaterialTransactionForm;
use App\Filament\Factory\Resources\FactoryMaterialTransactions\Tables\FactoryMaterialTransactionsTable;
use App\Models\FactoryMaterialTransaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FactoryMaterialTransactionResource extends Resource
{
    protected static ?string $model = FactoryMaterialTransaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Transactions';

    protected static ?string $modelLabel = 'Transaction';

    protected static ?string $pluralModelLabel = 'Transactions';

    protected static string|\UnitEnum|null $navigationGroup = 'Materials';

    public static function form(Schema $schema): Schema
    {
        return FactoryMaterialTransactionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FactoryMaterialTransactionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFactoryMaterialTransactions::route('/'),
            'create' => CreateFactoryMaterialTransaction::route('/create'),
            'edit' => EditFactoryMaterialTransaction::route('/{record}/edit'),
        ];
    }
}
