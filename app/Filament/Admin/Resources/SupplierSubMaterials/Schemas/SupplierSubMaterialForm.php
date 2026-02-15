<?php

namespace App\Filament\Admin\Resources\SupplierSubMaterials\Schemas;

use App\Models\MaterialSubCategory;
use App\Models\Supplier;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SupplierSubMaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('supplier_id')
                    ->label('Supplier')
                    ->options(Supplier::pluck('name', 'id'))
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
            ]);
    }
}
