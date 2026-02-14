<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    protected $fillable = [
        'name',
        'image',
    ];

    /**
     * Get the sub-categories for this material.
     */
    public function subCategories(): HasMany
    {
        return $this->hasMany(MaterialSubCategory::class);
    }

    /**
     * Get the factory materials (inventory) for this material.
     */
    public function factoryMaterials(): HasMany
    {
        return $this->hasMany(FactoryMaterial::class);
    }

    /**
     * Get all transactions for this material.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(FactoryMaterialTransaction::class);
    }
}
