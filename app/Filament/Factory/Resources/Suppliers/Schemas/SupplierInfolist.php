<?php

namespace App\Filament\Factory\Resources\Suppliers\Schemas;

use App\Models\Supplier;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupplierInfolist
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
                        TextEntry::make('connected_at')
                            ->label('Connected Since')
                            ->state(function (Supplier $record): ?string {
                                $factoryId = Auth::guard('factory')->user()?->factory_id;

                                return DB::table('factory_supplier')
                                    ->where('factory_id', $factoryId)
                                    ->where('supplier_id', $record->id)
                                    ->value('created_at');
                            })
                            ->dateTime(),
                    ])
                    ->columns(2),

                Section::make('Materials & Stock Overview')
                    ->description('Current inventory levels for this supplier')
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
                                    ->label('Stock')
                                    ->numeric(decimalPlaces: 2)
                                    ->badge()
                                    ->color(fn ($state): string => $state <= 0 ? 'danger' : ($state < 10 ? 'warning' : 'success')),
                            ])
                            ->columns(3),
                    ]),
            ]);
    }
}
