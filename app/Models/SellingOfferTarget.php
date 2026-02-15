<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellingOfferTarget extends Model
{
    protected $fillable = [
        'selling_offer_id',
        'supplier_id',
    ];

    /**
     * Get the selling offer.
     */
    public function sellingOffer(): BelongsTo
    {
        return $this->belongsTo(SellingOffer::class);
    }

    /**
     * Get the targeted supplier.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
