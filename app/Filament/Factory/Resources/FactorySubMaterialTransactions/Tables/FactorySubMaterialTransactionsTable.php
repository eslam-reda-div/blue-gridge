<?php

namespace App\Filament\Factory\Resources\FactorySubMaterialTransactions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FactorySubMaterialTransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('materialSubCategory.material.name')
                    ->label('Material')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('materialSubCategory.name')
                    ->label('Sub-Category')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'insert' => 'success',
                        'use' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'insert' => 'Stock In',
                        'use' => 'Stock Out',
                    }),
                TextColumn::make('quantity')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('notes')
                    ->limit(50)
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Date'),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'insert' => 'Stock In',
                        'use' => 'Stock Out',
                    ]),
                SelectFilter::make('material_sub_category_id')
                    ->relationship('materialSubCategory', 'name')
                    ->label('Sub-Category')
                    ->searchable()
                    ->preload(),
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
