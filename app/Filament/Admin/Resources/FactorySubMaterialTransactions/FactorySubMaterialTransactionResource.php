<?php

namespace App\Filament\Admin\Resources\FactorySubMaterialTransactions;

use App\Filament\Admin\Resources\FactorySubMaterialTransactions\Pages\CreateFactorySubMaterialTransaction;
use App\Filament\Admin\Resources\FactorySubMaterialTransactions\Pages\EditFactorySubMaterialTransaction;
use App\Filament\Admin\Resources\FactorySubMaterialTransactions\Pages\ListFactorySubMaterialTransactions;
use App\Filament\Admin\Resources\FactorySubMaterialTransactions\Schemas\FactorySubMaterialTransactionForm;
use App\Filament\Admin\Resources\FactorySubMaterialTransactions\Tables\FactorySubMaterialTransactionsTable;
use App\Models\FactorySubMaterialTransaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FactorySubMaterialTransactionResource extends Resource
{
    protected static ?string $model = FactorySubMaterialTransaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Factory Transactions';

    protected static ?string $modelLabel = 'Factory Transaction';

    protected static ?string $pluralModelLabel = 'Factory Transactions';

    protected static string|\UnitEnum|null $navigationGroup = 'Factories';

    protected static ?int $navigationSort = 4;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes();
    }

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
        return [];
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
