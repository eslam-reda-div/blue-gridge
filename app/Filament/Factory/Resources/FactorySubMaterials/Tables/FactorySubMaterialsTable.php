<?php

namespace App\Filament\Factory\Resources\FactorySubMaterials\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FactorySubMaterialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('materialSubCategory.material.name')
                    ->label('Material')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('materialSubCategory.name')
                    ->label('Sub-Category')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('quantity')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->color(fn ($state) => $state <= 0 ? 'danger' : ($state < 10 ? 'warning' : 'success')),
                TextColumn::make('safe_amount')
                    ->label('Safe Threshold')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->color('info'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
