<?php

namespace App\Filament\Admin\Resources\SellingOffers;

use App\Filament\Admin\Resources\SellingOffers\Pages\ListSellingOffers;
use App\Filament\Admin\Resources\SellingOffers\Pages\ViewSellingOffer;
use App\Filament\Admin\Resources\SellingOffers\Schemas\SellingOfferInfolist;
use App\Filament\Admin\Resources\SellingOffers\Tables\SellingOffersTable;
use App\Models\SellingOffer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SellingOfferResource extends Resource
{
    protected static ?string $model = SellingOffer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;

    protected static ?string $navigationLabel = 'Selling Offers';

    protected static ?string $modelLabel = 'Selling Offer';

    protected static ?string $pluralModelLabel = 'Selling Offers';

    protected static string|\UnitEnum|null $navigationGroup = 'Marketplace';

    protected static ?int $navigationSort = 2;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return SellingOffer::query()
            ->with(['seller', 'materialSubCategory.material', 'acceptedBySupplier']);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SellingOfferInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SellingOffersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSellingOffers::route('/'),
            'view' => ViewSellingOffer::route('/{record}'),
        ];
    }
}
