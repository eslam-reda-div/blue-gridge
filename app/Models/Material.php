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
}
