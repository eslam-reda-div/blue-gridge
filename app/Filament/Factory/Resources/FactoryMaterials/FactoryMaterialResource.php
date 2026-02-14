<?php

namespace App\Filament\Factory\Resources\FactoryMaterials;

use App\Filament\Factory\Resources\FactoryMaterials\Pages\CreateFactoryMaterial;
use App\Filament\Factory\Resources\FactoryMaterials\Pages\EditFactoryMaterial;
use App\Filament\Factory\Resources\FactoryMaterials\Pages\ListFactoryMaterials;
use App\Filament\Factory\Resources\FactoryMaterials\Schemas\FactoryMaterialForm;
use App\Filament\Factory\Resources\FactoryMaterials\Tables\FactoryMaterialsTable;
use App\Models\FactoryMaterial;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FactoryMaterialResource extends Resource
{
    protected static ?string $model = FactoryMaterial::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    protected static ?string $navigationLabel = 'Inventory';

    protected static ?string $modelLabel = 'Inventory';

    protected static ?string $pluralModelLabel = 'Inventory';

    protected static string|\UnitEnum|null $navigationGroup = 'Materials';

    public static function form(Schema $schema): Schema
    {
        return FactoryMaterialForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FactoryMaterialsTable::configure($table);
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
            'index' => ListFactoryMaterials::route('/'),
            'create' => CreateFactoryMaterial::route('/create'),
            'edit' => EditFactoryMaterial::route('/{record}/edit'),
        ];
    }
}
