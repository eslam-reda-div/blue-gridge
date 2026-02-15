<?php

namespace App\Filament\Seller\Resources\SellingOffers;

use App\Filament\Seller\Resources\SellingOffers\Pages\CreateSellingOffer;
use App\Filament\Seller\Resources\SellingOffers\Pages\ListSellingOffers;
use App\Filament\Seller\Resources\SellingOffers\Pages\ViewSellingOffer;
use App\Filament\Seller\Resources\SellingOffers\Schemas\SellingOfferForm;
use App\Filament\Seller\Resources\SellingOffers\Schemas\SellingOfferInfolist;
use App\Filament\Seller\Resources\SellingOffers\Tables\SellingOffersTable;
use App\Models\SellingOffer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class SellingOfferResource extends Resource
{
    protected static ?string $model = SellingOffer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static ?string $navigationLabel = 'My Offers';

    protected static ?string $modelLabel = 'Selling Offer';

    protected static ?string $pluralModelLabel = 'My Offers';

    public static function getEloquentQuery(): Builder
    {
        return SellingOffer::query()
            ->where('seller_id', Auth::guard('seller')->id())
            ->with(['materialSubCategory.material', 'acceptedBySupplier', 'targets.supplier']);
    }

    public static function form(Schema $schema): Schema
    {
        return SellingOfferForm::configure($schema);
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
            'create' => CreateSellingOffer::route('/create'),
            'view' => ViewSellingOffer::route('/{record}'),
        ];
    }
}
