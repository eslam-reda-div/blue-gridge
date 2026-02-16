<?php

namespace App\Filament\Seller\Resources\SellingOffers\Tables;

use App\Models\SellingOffer;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SellingOffersTable
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
                TextColumn::make('quantity')
                    ->label('Quantity')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('price_per_unit')
                    ->label('Price/Unit')
                    ->numeric(decimalPlaces: 2)
                    ->placeholder('Negotiable')
                    ->sortable(),
                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'broadcast' => 'info',
                        'targeted' => 'primary',
                        default => 'gray',
                    }),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'warning',
                        'accepted' => 'success',
                        'closed' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('acceptedBySupplier.name')
                    ->label('Accepted By')
                    ->placeholder('—'),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'open' => 'Open',
                        'accepted' => 'Accepted',
                        'closed' => 'Closed',
                    ]),
                SelectFilter::make('type')
                    ->options([
                        'broadcast' => 'Broadcast',
                        'targeted' => 'Targeted',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()
                    ->visible(fn (SellingOffer $record): bool => $record->isOpen()),
                Action::make('close')
                    ->label('Close')
                    ->icon(Heroicon::OutlinedXCircle)
                    ->color('gray')
                    ->requiresConfirmation()
                    ->modalHeading('Close Offer')
                    ->modalDescription('Are you sure you want to close this offer? Suppliers will no longer be able to accept it.')
                    ->visible(fn (SellingOffer $record): bool => $record->isOpen())
                    ->action(fn (SellingOffer $record) => $record->update(['status' => 'closed'])),
                Action::make('reopen')
                    ->label('Reopen')
                    ->icon(Heroicon::OutlinedArrowPath)
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Reopen Offer')
                    ->modalDescription('This will reopen the offer so suppliers can see and accept it again.')
                    ->visible(fn (SellingOffer $record): bool => $record->isClosed())
                    ->action(fn (SellingOffer $record) => $record->update(['status' => 'open', 'accepted_by_supplier_id' => null, 'accepted_at' => null])),
            ]);
    }
}
