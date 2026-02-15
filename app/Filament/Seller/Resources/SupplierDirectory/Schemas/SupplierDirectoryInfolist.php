<?php

namespace App\Filament\Seller\Resources\SupplierDirectory\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class SupplierDirectoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Supplier Information')
                    ->description('General details about this supplier')
                    ->collapsible()
                    ->schema([
                        TextEntry::make('id')
                            ->label('Supplier ID')
                            ->badge()
                            ->color('gray'),
                        TextEntry::make('name')
                            ->label('Name')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('location')
                            ->label('Location'),
                    ])
                    ->columns(3),

                Section::make('Materials & Stock')
                    ->description('Materials this supplier works with and their current stock levels')
                    ->collapsible()
                    ->schema([
                        RepeatableEntry::make('allSubMaterials')
                            ->label('')
                            ->schema([
                                TextEntry::make('materialSubCategory.material.name')
                                    ->label('Material')
                                    ->badge()
                                    ->color('primary'),
                                TextEntry::make('materialSubCategory.name')
                                    ->label('Sub-Category')
                                    ->badge()
                                    ->color('gray'),
                                TextEntry::make('quantity')
                                    ->label('Current Stock')
                                    ->numeric(decimalPlaces: 2)
                                    ->badge()
                                    ->color(fn ($state): string => $state <= 0 ? 'danger' : ($state < 10 ? 'warning' : 'success')),
                            ])
                            ->columns(3),
                    ]),
            ]);
    }
}
