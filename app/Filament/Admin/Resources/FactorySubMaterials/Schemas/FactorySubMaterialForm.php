<?php

namespace App\Filament\Admin\Resources\FactorySubMaterials\Schemas;

use App\Models\Factory;
use App\Models\MaterialSubCategory;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FactorySubMaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('factory_id')
                    ->label('Factory')
                    ->options(Factory::pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('material_sub_category_id')
                    ->label('Material Sub-Category')
                    ->options(
                        MaterialSubCategory::query()
                            ->with('material')
                            ->get()
                            ->mapWithKeys(fn (MaterialSubCategory $sub): array => [
                                $sub->id => "{$sub->material->name} — {$sub->name}",
                            ])
                            ->all()
                    )
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
                TextInput::make('safe_amount')
                    ->label('Safe Amount')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->helperText('Minimum stock level before triggering supply requests'),
            ]);
    }
}
