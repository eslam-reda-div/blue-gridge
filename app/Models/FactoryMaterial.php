<?php

namespace App\Models;

use App\Models\Scopes\FactoryScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ScopedBy(FactoryScope::class)]
class FactoryMaterial extends Model
{
    protected $fillable = [
        'factory_id',
        'material_id',
        'quantity',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
        ];
    }

    /**
     * Get the factory.
     */
    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class);
    }

    /**
     * Get the material.
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    /**
     * Get the transactions for this factory-material pair.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(FactoryMaterialTransaction::class, 'factory_id', 'factory_id')
            ->where('material_id', $this->material_id);
    }
}
