<?php

namespace App\Filament\Factory\Resources\FactoryEmployees;

use App\Filament\Factory\Resources\FactoryEmployees\Pages\CreateFactoryEmployee;
use App\Filament\Factory\Resources\FactoryEmployees\Pages\EditFactoryEmployee;
use App\Filament\Factory\Resources\FactoryEmployees\Pages\ListFactoryEmployees;
use App\Filament\Factory\Resources\FactoryEmployees\Schemas\FactoryEmployeeForm;
use App\Filament\Factory\Resources\FactoryEmployees\Tables\FactoryEmployeesTable;
use App\Models\FactoryEmployee;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FactoryEmployeeResource extends Resource
{
    protected static ?string $model = FactoryEmployee::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'email';

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
        return [
            //
        ];
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
