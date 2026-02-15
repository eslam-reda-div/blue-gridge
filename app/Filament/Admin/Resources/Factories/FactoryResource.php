<?php

namespace App\Filament\Admin\Resources\Factories;

use App\Filament\Admin\Resources\Factories\Pages\CreateFactory;
use App\Filament\Admin\Resources\Factories\Pages\EditFactory;
use App\Filament\Admin\Resources\Factories\Pages\ListFactories;
use App\Filament\Admin\Resources\Factories\Schemas\FactoryForm;
use App\Filament\Admin\Resources\Factories\Tables\FactoriesTable;
use App\Models\Factory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FactoryResource extends Resource
{
    protected static ?string $model = Factory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|\UnitEnum|null $navigationGroup = 'Factories';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return FactoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FactoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFactories::route('/'),
            'create' => CreateFactory::route('/create'),
            'edit' => EditFactory::route('/{record}/edit'),
        ];
    }
}
