<?php

namespace App\Filament\Supplier\Resources\SupplierSubMaterials\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class SupplierSubMaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('supplier_id')
                    ->default(fn () => Auth::guard('supplier')->user()?->supplier_id),
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
            ]);
    }
}
