<?php

namespace App\Filament\Seller\Resources\SellingOffers\Pages;

use App\Filament\Seller\Resources\SellingOffers\SellingOfferResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSellingOffers extends ListRecords
{
    protected static string $resource = SellingOfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Selling Offer'),
        ];
    }
}
