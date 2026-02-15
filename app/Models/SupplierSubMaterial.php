<?php

namespace App\Models;

use App\Models\Scopes\SupplierScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ScopedBy(SupplierScope::class)]
class SupplierSubMaterial extends Model
{
    protected $fillable = [
        'supplier_id',
        'material_sub_category_id',
        'quantity',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
        ];
    }

    /**
     * Get the supplier.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the material sub-category.
     */
    public function materialSubCategory(): BelongsTo
    {
        return $this->belongsTo(MaterialSubCategory::class);
    }

    /**
     * Get the transactions for this supplier-sub-material pair.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(SupplierSubMaterialTransaction::class, 'supplier_id', 'supplier_id')
            ->where('material_sub_category_id', $this->material_sub_category_id);
    }
}
