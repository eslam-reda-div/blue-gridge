<?php

namespace App\Filament\Factory\Resources\FactorySubMaterialTransactions\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class FactorySubMaterialTransactionForm
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
                Select::make('type')
                    ->options([
                        'insert' => 'Stock In (Insert)',
                        'use' => 'Stock Out (Use)',
                    ])
                    ->required()
                    ->native(false),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->minValue(0.01)
                    ->step(0.01),
                Textarea::make('notes')
                    ->maxLength(1000)
                    ->columnSpanFull(),
            ]);
    }
}
