<?php

namespace App\Filament\Seller\Resources\SellingOffers\Pages;

use App\Filament\Seller\Resources\SellingOffers\SellingOfferResource;
use Filament\Resources\Pages\ListRecords;

class ListSellingOffers extends ListRecords
{
    protected static string $resource = SellingOfferResource::class;
}
