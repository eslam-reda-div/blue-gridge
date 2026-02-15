<?php

namespace App\Filament\Supplier\Resources\SupplierSubMaterialTransactions;

use App\Filament\Supplier\Resources\SupplierSubMaterialTransactions\Pages\CreateSupplierSubMaterialTransaction;
use App\Filament\Supplier\Resources\SupplierSubMaterialTransactions\Pages\EditSupplierSubMaterialTransaction;
use App\Filament\Supplier\Resources\SupplierSubMaterialTransactions\Pages\ListSupplierSubMaterialTransactions;
use App\Filament\Supplier\Resources\SupplierSubMaterialTransactions\Schemas\SupplierSubMaterialTransactionForm;
use App\Filament\Supplier\Resources\SupplierSubMaterialTransactions\Tables\SupplierSubMaterialTransactionsTable;
use App\Models\SupplierSubMaterialTransaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SupplierSubMaterialTransactionResource extends Resource
{
    protected static ?string $model = SupplierSubMaterialTransaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Transactions';

    protected static ?string $modelLabel = 'Transaction';

    protected static ?string $pluralModelLabel = 'Transactions';

    protected static string|\UnitEnum|null $navigationGroup = 'Materials';

    public static function form(Schema $schema): Schema
    {
        return SupplierSubMaterialTransactionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SupplierSubMaterialTransactionsTable::configure($table);
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
            'index' => ListSupplierSubMaterialTransactions::route('/'),
            'create' => CreateSupplierSubMaterialTransaction::route('/create'),
            'edit' => EditSupplierSubMaterialTransaction::route('/{record}/edit'),
        ];
    }
}
