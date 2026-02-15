<?php

namespace App\Filament\Supplier\Resources\Factories;

use App\Filament\Supplier\Resources\Factories\Pages\ListFactories;
use App\Filament\Supplier\Resources\Factories\Pages\ViewFactory;
use App\Filament\Supplier\Resources\Factories\Schemas\FactoryInfolist;
use App\Filament\Supplier\Resources\Factories\Tables\FactoriesTable;
use App\Models\Factory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class FactoryResource extends Resource
{
    protected static ?string $model = Factory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice;

    protected static ?string $navigationLabel = 'Factories';

    protected static ?string $modelLabel = 'Factory';

    protected static ?string $pluralModelLabel = 'Factories';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        $supplierId = Auth::guard('supplier')->user()?->supplier_id;

        return Factory::query()
            ->join('factory_supplier', 'factories.id', '=', 'factory_supplier.factory_id')
            ->where('factory_supplier.supplier_id', $supplierId)
            ->select('factories.*', 'factory_supplier.created_at as connected_at');
    }

    public static function infolist(Schema $schema): Schema
    {
        return FactoryInfolist::configure($schema);
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
            'view' => ViewFactory::route('/{record}'),
        ];
    }
}
