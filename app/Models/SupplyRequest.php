<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupplyRequest extends Model
{
    protected $fillable = [
        'factory_id',
        'material_sub_category_id',
        'quantity_needed',
        'status',
        'accepted_by_supplier_id',
        'triggered_by',
    ];

    protected function casts(): array
    {
        return [
            'quantity_needed' => 'decimal:2',
        ];
    }

    /**
     * Get the factory that created this request.
     */
    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class);
    }

    /**
     * Get the material sub-category requested.
     */
    public function materialSubCategory(): BelongsTo
    {
        return $this->belongsTo(MaterialSubCategory::class);
    }

    /**
     * Get the supplier who accepted this request.
     */
    public function acceptedBySupplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'accepted_by_supplier_id');
    }

    /**
     * Get the supplier entries for this request.
     */
    public function suppliers(): HasMany
    {
        return $this->hasMany(SupplyRequestSupplier::class);
    }

    /**
     * Get the audit logs for this request.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(SupplyRequestLog::class)->orderBy('created_at');
    }

    /**
     * Check if this request is still pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if this request has been accepted.
     */
    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    /**
     * Check if this request has been rejected by all suppliers.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
