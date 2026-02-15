<?php

namespace App\Filament\Seller\Resources\SellingOffers\Schemas;

use App\Models\MaterialSubCategory;
use App\Models\SupplierSubMaterial;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class SellingOfferForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('seller_id')
                    ->default(fn () => Auth::guard('seller')->id()),

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
                    ->preload()
                    ->live()
                    ->helperText('Select the material you want to sell'),

                TextInput::make('quantity')
                    ->label('Quantity Available')
                    ->required()
                    ->numeric()
                    ->minValue(0.01)
                    ->helperText('How much of this material do you have to sell?'),

                TextInput::make('price_per_unit')
                    ->label('Price per Unit (optional)')
                    ->numeric()
                    ->minValue(0)
                    ->helperText('Leave empty if open to negotiation'),

                MarkdownEditor::make('notes')
                    ->label('Additional Notes')
                    ->helperText('Any details about the material condition, pickup location, etc.')
                    ->columnSpanFull(),

                Radio::make('type')
                    ->label('Who should see this offer?')
                    ->options([
                        'broadcast' => 'All Suppliers — Every supplier on the platform can see this offer',
                        'targeted' => 'Specific Suppliers — Only suppliers you choose will see this offer',
                    ])
                    ->default('broadcast')
                    ->required()
                    ->live(),

                Select::make('target_supplier_ids')
                    ->label('Select Target Suppliers')
                    ->multiple()
                    ->options(function (Get $get): array {
                        $subCategoryId = $get('material_sub_category_id');

                        if (! $subCategoryId) {
                            return [];
                        }

                        // Find suppliers who deal with this material
                        $supplierIds = SupplierSubMaterial::withoutGlobalScopes()
                            ->where('material_sub_category_id', $subCategoryId)
                            ->pluck('supplier_id')
                            ->unique();

                        return \App\Models\Supplier::whereIn('id', $supplierIds)
                            ->get()
                            ->mapWithKeys(fn (\App\Models\Supplier $supplier): array => [
                                $supplier->id => "#{$supplier->id} — {$supplier->name} ({$supplier->location})",
                            ])
                            ->all();
                    })
                    ->searchable()
                    ->preload()
                    ->visible(fn (Get $get): bool => $get('type') === 'targeted')
                    ->requiredIf('type', 'targeted')
                    ->helperText('Only suppliers who handle this material are shown'),
            ]);
    }
}
