<?php

namespace App\Filament\Factory\Resources\Suppliers;

use App\Filament\Factory\Resources\Suppliers\Pages\ListSuppliers;
use App\Filament\Factory\Resources\Suppliers\Pages\ViewSupplier;
use App\Filament\Factory\Resources\Suppliers\Schemas\SupplierInfolist;
use App\Filament\Factory\Resources\Suppliers\Tables\SuppliersTable;
use App\Models\Supplier;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'Suppliers';

    protected static ?string $modelLabel = 'Supplier';

    protected static ?string $pluralModelLabel = 'Suppliers';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        $factoryId = Auth::guard('factory')->user()?->factory_id;

        return Supplier::query()
            ->join('factory_supplier', 'suppliers.id', '=', 'factory_supplier.supplier_id')
            ->where('factory_supplier.factory_id', $factoryId)
            ->select('suppliers.*', 'factory_supplier.created_at as connected_at');
    }

    public static function infolist(Schema $schema): Schema
    {
        return SupplierInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SuppliersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSuppliers::route('/'),
            'view' => ViewSupplier::route('/{record}'),
        ];
    }
}
