<?php

namespace App\Filament\Seller\Resources\SupplierDirectory;

use App\Filament\Seller\Resources\SupplierDirectory\Pages\ListSupplierDirectory;
use App\Filament\Seller\Resources\SupplierDirectory\Pages\ViewSupplierDirectory;
use App\Filament\Seller\Resources\SupplierDirectory\Schemas\SupplierDirectoryInfolist;
use App\Filament\Seller\Resources\SupplierDirectory\Tables\SupplierDirectoryTable;
use App\Models\Supplier;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SupplierDirectoryResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    protected static ?string $navigationLabel = 'Suppliers Directory';

    protected static ?string $modelLabel = 'Supplier';

    protected static ?string $pluralModelLabel = 'Suppliers Directory';

    protected static ?string $slug = 'suppliers-directory';

    public static function getEloquentQuery(): Builder
    {
        return Supplier::query()
            ->with(['allSubMaterials.materialSubCategory.material']);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function infolist(Schema $schema): Schema
    {
        return SupplierDirectoryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SupplierDirectoryTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSupplierDirectory::route('/'),
            'view' => ViewSupplierDirectory::route('/{record}'),
        ];
    }
}
