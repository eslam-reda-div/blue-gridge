<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaterialSubCategory extends Model
{
    protected $fillable = [
        'material_id',
        'name',
        'image',
    ];

    /**
     * Get the parent material.
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    /**
     * Get the factory sub-material stock for this sub-category.
     */
    public function factorySubMaterials(): HasMany
    {
        return $this->hasMany(FactorySubMaterial::class);
    }

    /**
     * Get the factory sub-material transactions for this sub-category.
     */
    public function factorySubMaterialTransactions(): HasMany
    {
        return $this->hasMany(FactorySubMaterialTransaction::class);
    }

    /**
     * Get the supplier sub-material stock for this sub-category.
     */
    public function supplierSubMaterials(): HasMany
    {
        return $this->hasMany(SupplierSubMaterial::class);
    }

    /**
     * Get the supplier sub-material transactions for this sub-category.
     */
    public function supplierSubMaterialTransactions(): HasMany
    {
        return $this->hasMany(SupplierSubMaterialTransaction::class);
    }
}
