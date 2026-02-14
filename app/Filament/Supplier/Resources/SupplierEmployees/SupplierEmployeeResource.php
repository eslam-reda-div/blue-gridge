<?php

namespace App\Filament\Supplier\Resources\SupplierEmployees;

use App\Filament\Supplier\Resources\SupplierEmployees\Pages\CreateSupplierEmployee;
use App\Filament\Supplier\Resources\SupplierEmployees\Pages\EditSupplierEmployee;
use App\Filament\Supplier\Resources\SupplierEmployees\Pages\ListSupplierEmployees;
use App\Filament\Supplier\Resources\SupplierEmployees\Schemas\SupplierEmployeeForm;
use App\Filament\Supplier\Resources\SupplierEmployees\Tables\SupplierEmployeesTable;
use App\Models\SupplierEmployee;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SupplierEmployeeResource extends Resource
{
    protected static ?string $model = SupplierEmployee::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'email';

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
        return [
            //
        ];
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
