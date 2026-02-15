<?php

namespace App\Filament\Factory\Resources\SupplyRequests\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SupplyRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('materialSubCategory.material.name')
                    ->label('Material')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('materialSubCategory.name')
                    ->label('Sub-Category')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('quantity_needed')
                    ->label('Qty Needed')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'accepted' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('triggered_by')
                    ->label('Trigger')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'auto' => 'info',
                        'manual' => 'primary',
                        default => 'gray',
                    }),
                TextColumn::make('acceptedBySupplier.name')
                    ->label('Accepted By')
                    ->placeholder('—'),
                TextColumn::make('created_at')
                    ->label('Requested At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([]);
    }
}
