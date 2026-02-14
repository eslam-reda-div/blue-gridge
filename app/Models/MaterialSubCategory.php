<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
