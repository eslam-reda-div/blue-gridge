<?php

namespace App\Filament\Admin\Resources\SupplierSubMaterialTransactions\Schemas;

use App\Models\MaterialSubCategory;
use App\Models\Supplier;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SupplierSubMaterialTransactionForm
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
                Select::make('type')
                    ->options([
                        'insert' => 'Insert (Stock In)',
                        'use' => 'Use (Stock Out)',
                    ])
                    ->required(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->minValue(0.01),
                Textarea::make('notes')
                    ->maxLength(1000)
                    ->columnSpanFull(),
            ]);
    }
}
