<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'location',
    ];

    /**
     * Get the employees for the supplier.
     */
    public function employees(): HasMany
    {
        return $this->hasMany(SupplierEmployee::class);
    }

    /**
     * Get the sub-material stock for this supplier.
     */
    public function subMaterials(): HasMany
    {
        return $this->hasMany(SupplierSubMaterial::class);
    }

    /**
     * Get all sub-material transactions for this supplier.
     */
    public function subMaterialTransactions(): HasMany
    {
        return $this->hasMany(SupplierSubMaterialTransaction::class);
    }

    /**
     * Get the factories connected to this supplier.
     */
    public function factories(): BelongsToMany
    {
        return $this->belongsToMany(Factory::class)->withTimestamps();
    }

    /**
     * Get all sub-materials for this supplier (bypasses global scopes).
     */
    public function allSubMaterials(): HasMany
    {
        return $this->hasMany(SupplierSubMaterial::class)->withoutGlobalScopes();
    }

    /**
     * Get selling offers accepted by this supplier.
     */
    public function acceptedSellingOffers(): HasMany
    {
        return $this->hasMany(SellingOffer::class, 'accepted_by_supplier_id');
    }
}
