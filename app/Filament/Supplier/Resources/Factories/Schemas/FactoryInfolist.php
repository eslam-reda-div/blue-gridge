<?php

namespace App\Filament\Supplier\Resources\Factories\Schemas;

use App\Models\Factory;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FactoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Factory Information')
                    ->description('General details about this factory')
                    ->collapsible()
                    ->schema([
                        TextEntry::make('id')
                            ->label('Factory ID')
                            ->badge()
                            ->color('gray'),
                        TextEntry::make('name')
                            ->label('Name')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('location')
                            ->label('Location'),
                        TextEntry::make('connected_at')
                            ->label('Connected Since')
                            ->state(function (Factory $record): ?string {
                                $supplierId = Auth::guard('supplier')->user()?->supplier_id;

                                return DB::table('factory_supplier')
                                    ->where('supplier_id', $supplierId)
                                    ->where('factory_id', $record->id)
                                    ->value('created_at');
                            })
                            ->dateTime(),
                    ])
                    ->columns(2),

                Section::make('Materials Overview')
                    ->description('Materials used by this factory')
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
                            ])
                            ->columns(2),
                    ]),
            ]);
    }
}
