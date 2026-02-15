<?php

namespace App\Filament\Seller\Resources\SellingOffers\Pages;

use App\Filament\Seller\Resources\SellingOffers\SellingOfferResource;
use App\Models\SellingOfferTarget;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateSellingOffer extends CreateRecord
{
    protected static string $resource = SellingOfferResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Extract target supplier IDs before creating the record
        $targetSupplierIds = $data['target_supplier_ids'] ?? [];
        unset($data['target_supplier_ids']);

        /** @var \App\Models\SellingOffer $offer */
        $offer = static::getModel()::create($data);

        // Create target entries for targeted offers
        if ($data['type'] === 'targeted' && ! empty($targetSupplierIds)) {
            foreach ($targetSupplierIds as $supplierId) {
                SellingOfferTarget::create([
                    'selling_offer_id' => $offer->id,
                    'supplier_id' => $supplierId,
                ]);
            }
        }

        return $offer;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
