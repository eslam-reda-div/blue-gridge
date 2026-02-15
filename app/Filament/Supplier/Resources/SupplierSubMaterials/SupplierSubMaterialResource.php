<?php

namespace App\Filament\Supplier\Resources\SupplierSubMaterials;

use App\Filament\Supplier\Resources\SupplierSubMaterials\Pages\CreateSupplierSubMaterial;
use App\Filament\Supplier\Resources\SupplierSubMaterials\Pages\EditSupplierSubMaterial;
use App\Filament\Supplier\Resources\SupplierSubMaterials\Pages\ListSupplierSubMaterials;
use App\Filament\Supplier\Resources\SupplierSubMaterials\Schemas\SupplierSubMaterialForm;
use App\Filament\Supplier\Resources\SupplierSubMaterials\Tables\SupplierSubMaterialsTable;
use App\Models\SupplierSubMaterial;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SupplierSubMaterialResource extends Resource
{
    protected static ?string $model = SupplierSubMaterial::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    protected static ?string $navigationLabel = 'Inventory';

    protected static ?string $modelLabel = 'Inventory';

    protected static ?string $pluralModelLabel = 'Inventory';

    protected static string|\UnitEnum|null $navigationGroup = 'Materials';

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
        return [
            //
        ];
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
