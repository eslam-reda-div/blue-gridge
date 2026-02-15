<?php

namespace App\Filament\Admin\Resources\SupplierSubMaterials;

use App\Filament\Admin\Resources\SupplierSubMaterials\Pages\CreateSupplierSubMaterial;
use App\Filament\Admin\Resources\SupplierSubMaterials\Pages\EditSupplierSubMaterial;
use App\Filament\Admin\Resources\SupplierSubMaterials\Pages\ListSupplierSubMaterials;
use App\Filament\Admin\Resources\SupplierSubMaterials\Schemas\SupplierSubMaterialForm;
use App\Filament\Admin\Resources\SupplierSubMaterials\Tables\SupplierSubMaterialsTable;
use App\Models\SupplierSubMaterial;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SupplierSubMaterialResource extends Resource
{
    protected static ?string $model = SupplierSubMaterial::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    protected static ?string $navigationLabel = 'Supplier Inventory';

    protected static ?string $modelLabel = 'Supplier Inventory';

    protected static ?string $pluralModelLabel = 'Supplier Inventory';

    protected static string|\UnitEnum|null $navigationGroup = 'Suppliers';

    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes();
    }

    public static function form(Schema $schema): Schema
    {
        return SupplierSubMaterialForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SupplierSubMaterialsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSupplierSubMaterials::route('/'),
            'create' => CreateSupplierSubMaterial::route('/create'),
            'edit' => EditSupplierSubMaterial::route('/{record}/edit'),
        ];
    }
}
