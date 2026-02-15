<?php

namespace App\Filament\Factory\Resources\FactorySubMaterials\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class FactorySubMaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('factory_id')
                    ->default(fn () => Auth::guard('factory')->user()?->factory_id),
                Select::make('material_sub_category_id')
                    ->relationship('materialSubCategory', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
                TextInput::make('safe_amount')
                    ->label('Safe Stock Threshold')
                    ->helperText('Minimum stock level before auto-requesting supply from suppliers')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
            ]);
    }
}
