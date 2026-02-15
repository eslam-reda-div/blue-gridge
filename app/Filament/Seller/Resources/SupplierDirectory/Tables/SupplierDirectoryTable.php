<?php

namespace App\Filament\Seller\Resources\SupplierDirectory\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SupplierDirectoryTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Supplier Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('location')
                    ->label('Location')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('allSubMaterials')
                    ->label('Materials')
                    ->state(fn ($record): string => $record->allSubMaterials
                        ->map(fn ($sub) => $sub->materialSubCategory?->name)
                        ->filter()
                        ->unique()
                        ->implode(', ') ?: '—')
                    ->wrap()
                    ->limit(80),
            ])
            ->filters([])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([]);
    }
}
