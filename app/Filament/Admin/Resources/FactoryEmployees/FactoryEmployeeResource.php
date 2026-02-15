<?php

namespace App\Filament\Admin\Resources\FactoryEmployees;

use App\Filament\Admin\Resources\FactoryEmployees\Pages\CreateFactoryEmployee;
use App\Filament\Admin\Resources\FactoryEmployees\Pages\EditFactoryEmployee;
use App\Filament\Admin\Resources\FactoryEmployees\Pages\ListFactoryEmployees;
use App\Filament\Admin\Resources\FactoryEmployees\Schemas\FactoryEmployeeForm;
use App\Filament\Admin\Resources\FactoryEmployees\Tables\FactoryEmployeesTable;
use App\Models\FactoryEmployee;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FactoryEmployeeResource extends Resource
{
    protected static ?string $model = FactoryEmployee::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $recordTitleAttribute = 'email';

    protected static ?string $navigationLabel = 'Factory Employees';

    protected static ?string $modelLabel = 'Factory Employee';

    protected static ?string $pluralModelLabel = 'Factory Employees';

    protected static string|\UnitEnum|null $navigationGroup = 'Factories';

    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes();
    }

    public static function form(Schema $schema): Schema
    {
        return FactoryEmployeeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FactoryEmployeesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFactoryEmployees::route('/'),
            'create' => CreateFactoryEmployee::route('/create'),
            'edit' => EditFactoryEmployee::route('/{record}/edit'),
        ];
    }
}
