<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SellingOffer extends Model
{
    protected $fillable = [
        'seller_id',
        'material_sub_category_id',
        'quantity',
        'price_per_unit',
        'notes',
        'type',
        'status',
        'accepted_by_supplier_id',
        'accepted_at',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'price_per_unit' => 'decimal:2',
            'accepted_at' => 'datetime',
        ];
    }

    /**
     * Get the seller who created this offer.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    /**
     * Get the material sub-category being offered.
     */
    public function materialSubCategory(): BelongsTo
    {
        return $this->belongsTo(MaterialSubCategory::class);
    }

    /**
     * Get the supplier who accepted this offer.
     */
    public function acceptedBySupplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'accepted_by_supplier_id');
    }

    /**
     * Get the targeted suppliers for this offer.
     */
    public function targets(): HasMany
    {
        return $this->hasMany(SellingOfferTarget::class);
    }

    /**
     * Check if this offer is still open.
     */
    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    /**
     * Check if this offer was accepted.
     */
    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    /**
     * Check if this offer was closed by the seller.
     */
    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }

    /**
     * Check if this is a broadcast offer.
     */
    public function isBroadcast(): bool
    {
        return $this->type === 'broadcast';
    }

    /**
     * Check if a given supplier can see this offer.
     */
    public function isVisibleToSupplier(int $supplierId): bool
    {
        if ($this->isBroadcast()) {
            return true;
        }

        return $this->targets()->where('supplier_id', $supplierId)->exists();
    }
}
