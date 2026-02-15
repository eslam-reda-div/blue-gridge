<?php

namespace App\Filament\Admin\Resources\SupplierEmployees;

use App\Filament\Admin\Resources\SupplierEmployees\Pages\CreateSupplierEmployee;
use App\Filament\Admin\Resources\SupplierEmployees\Pages\EditSupplierEmployee;
use App\Filament\Admin\Resources\SupplierEmployees\Pages\ListSupplierEmployees;
use App\Filament\Admin\Resources\SupplierEmployees\Schemas\SupplierEmployeeForm;
use App\Filament\Admin\Resources\SupplierEmployees\Tables\SupplierEmployeesTable;
use App\Models\SupplierEmployee;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SupplierEmployeeResource extends Resource
{
    protected static ?string $model = SupplierEmployee::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $recordTitleAttribute = 'email';

    protected static ?string $navigationLabel = 'Supplier Employees';

    protected static ?string $modelLabel = 'Supplier Employee';

    protected static ?string $pluralModelLabel = 'Supplier Employees';

    protected static string|\UnitEnum|null $navigationGroup = 'Suppliers';

    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes();
    }

    public static function form(Schema $schema): Schema
    {
        return SupplierEmployeeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SupplierEmployeesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSupplierEmployees::route('/'),
            'create' => CreateSupplierEmployee::route('/create'),
            'edit' => EditSupplierEmployee::route('/{record}/edit'),
        ];
    }
}
