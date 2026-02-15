<?php

namespace App\Filament\Admin\Resources\SupplierSubMaterialTransactions;

use App\Filament\Admin\Resources\SupplierSubMaterialTransactions\Pages\CreateSupplierSubMaterialTransaction;
use App\Filament\Admin\Resources\SupplierSubMaterialTransactions\Pages\EditSupplierSubMaterialTransaction;
use App\Filament\Admin\Resources\SupplierSubMaterialTransactions\Pages\ListSupplierSubMaterialTransactions;
use App\Filament\Admin\Resources\SupplierSubMaterialTransactions\Schemas\SupplierSubMaterialTransactionForm;
use App\Filament\Admin\Resources\SupplierSubMaterialTransactions\Tables\SupplierSubMaterialTransactionsTable;
use App\Models\SupplierSubMaterialTransaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SupplierSubMaterialTransactionResource extends Resource
{
    protected static ?string $model = SupplierSubMaterialTransaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Supplier Transactions';

    protected static ?string $modelLabel = 'Supplier Transaction';

    protected static ?string $pluralModelLabel = 'Supplier Transactions';

    protected static string|\UnitEnum|null $navigationGroup = 'Suppliers';

    protected static ?int $navigationSort = 4;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes();
    }

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
        return [];
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
