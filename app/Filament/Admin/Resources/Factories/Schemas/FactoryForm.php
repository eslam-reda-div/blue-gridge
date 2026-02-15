<?php

namespace App\Filament\Admin\Resources\Factories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FactoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('location')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
