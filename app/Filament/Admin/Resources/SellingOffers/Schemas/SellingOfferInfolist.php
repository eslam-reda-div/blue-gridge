<?php

namespace App\Filament\Admin\Resources\SellingOffers\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class SellingOfferInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Offer Details')
                    ->description('Overview of this selling offer')
                    ->collapsible()
                    ->schema([
                        TextEntry::make('id')
                            ->label('Offer ID')
                            ->badge()
                            ->color('gray'),
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'open' => 'warning',
                                'accepted' => 'success',
                                'closed' => 'gray',
                                default => 'gray',
                            })
                            ->weight(FontWeight::Bold),
                        TextEntry::make('seller.name')
                            ->label('Seller')
                            ->badge()
                            ->color('primary'),
                        TextEntry::make('materialSubCategory.material.name')
                            ->label('Material')
                            ->badge()
                            ->color('info'),
                        TextEntry::make('materialSubCategory.name')
                            ->label('Sub-Category')
                            ->badge()
                            ->color('info'),
                        TextEntry::make('quantity')
                            ->label('Quantity')
                            ->numeric(decimalPlaces: 2),
                        TextEntry::make('price_per_unit')
                            ->label('Price per Unit')
                            ->numeric(decimalPlaces: 2)
                            ->placeholder('Negotiable'),
                        TextEntry::make('type')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'broadcast' => 'info',
                                'targeted' => 'primary',
                                default => 'gray',
                            }),
                        TextEntry::make('notes')
                            ->label('Notes')
                            ->placeholder('No notes')
                            ->columnSpanFull(),
                        TextEntry::make('acceptedBySupplier.name')
                            ->label('Accepted By Supplier')
                            ->placeholder('Not yet accepted'),
                        TextEntry::make('accepted_at')
                            ->label('Accepted At')
                            ->dateTime()
                            ->placeholder('—'),
                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime(),
                    ])
                    ->columns(2),

                Section::make('Targeted Suppliers')
                    ->description('Suppliers this offer was targeted to (if targeted type)')
                    ->collapsible()
                    ->schema([
                        RepeatableEntry::make('targets')
                            ->label('')
                            ->schema([
                                TextEntry::make('supplier.name')
                                    ->label('Supplier')
                                    ->weight(FontWeight::Bold),
                            ]),
                    ]),
            ]);
    }
}
