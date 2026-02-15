<?php

namespace App\Models;

use App\Models\Scopes\FactoryScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ScopedBy(FactoryScope::class)]
class FactorySubMaterial extends Model
{
    protected $fillable = [
        'factory_id',
        'material_sub_category_id',
        'quantity',
        'safe_amount',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'safe_amount' => 'decimal:2',
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
     * Get the material sub-category.
     */
    public function materialSubCategory(): BelongsTo
    {
        return $this->belongsTo(MaterialSubCategory::class);
    }

    /**
     * Get the transactions for this factory-sub-material pair.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(FactorySubMaterialTransaction::class, 'factory_id', 'factory_id')
            ->where('material_sub_category_id', $this->material_sub_category_id);
    }
}
