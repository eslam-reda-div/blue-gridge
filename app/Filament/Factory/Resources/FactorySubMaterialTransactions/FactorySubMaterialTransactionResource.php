<?php

namespace App\Filament\Factory\Resources\FactorySubMaterialTransactions;

use App\Filament\Factory\Resources\FactorySubMaterialTransactions\Pages\CreateFactorySubMaterialTransaction;
use App\Filament\Factory\Resources\FactorySubMaterialTransactions\Pages\EditFactorySubMaterialTransaction;
use App\Filament\Factory\Resources\FactorySubMaterialTransactions\Pages\ListFactorySubMaterialTransactions;
use App\Filament\Factory\Resources\FactorySubMaterialTransactions\Schemas\FactorySubMaterialTransactionForm;
use App\Filament\Factory\Resources\FactorySubMaterialTransactions\Tables\FactorySubMaterialTransactionsTable;
use App\Models\FactorySubMaterialTransaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FactorySubMaterialTransactionResource extends Resource
{
    protected static ?string $model = FactorySubMaterialTransaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Transactions';

    protected static ?string $modelLabel = 'Transaction';

    protected static ?string $pluralModelLabel = 'Transactions';

    protected static string|\UnitEnum|null $navigationGroup = 'Materials';

    public static function form(Schema $schema): Schema
    {
        return FactorySubMaterialTransactionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FactorySubMaterialTransactionsTable::configure($table);
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
            'index' => ListFactorySubMaterialTransactions::route('/'),
            'create' => CreateFactorySubMaterialTransaction::route('/create'),
            'edit' => EditFactorySubMaterialTransaction::route('/{record}/edit'),
        ];
    }
}
