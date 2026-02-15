<?php

namespace App\Filament\Admin\Resources\FactorySubMaterials;

use App\Filament\Admin\Resources\FactorySubMaterials\Pages\CreateFactorySubMaterial;
use App\Filament\Admin\Resources\FactorySubMaterials\Pages\EditFactorySubMaterial;
use App\Filament\Admin\Resources\FactorySubMaterials\Pages\ListFactorySubMaterials;
use App\Filament\Admin\Resources\FactorySubMaterials\Schemas\FactorySubMaterialForm;
use App\Filament\Admin\Resources\FactorySubMaterials\Tables\FactorySubMaterialsTable;
use App\Models\FactorySubMaterial;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FactorySubMaterialResource extends Resource
{
    protected static ?string $model = FactorySubMaterial::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    protected static ?string $navigationLabel = 'Factory Inventory';

    protected static ?string $modelLabel = 'Factory Inventory';

    protected static ?string $pluralModelLabel = 'Factory Inventory';

    protected static string|\UnitEnum|null $navigationGroup = 'Factories';

    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes();
    }

    public static function form(Schema $schema): Schema
    {
        return FactorySubMaterialForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FactorySubMaterialsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFactorySubMaterials::route('/'),
            'create' => CreateFactorySubMaterial::route('/create'),
            'edit' => EditFactorySubMaterial::route('/{record}/edit'),
        ];
    }
}
