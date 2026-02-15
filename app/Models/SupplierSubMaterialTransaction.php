<?php

namespace App\Models;

use App\Models\Scopes\SupplierScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ScopedBy(SupplierScope::class)]
class SupplierSubMaterialTransaction extends Model
{
    protected $fillable = [
        'supplier_id',
        'material_sub_category_id',
        'type',
        'quantity',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'type' => 'string',
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
     * Boot the model - auto-update stock on create.
     */
    protected static function booted(): void
    {
        static::created(function (SupplierSubMaterialTransaction $transaction) {
            $stock = SupplierSubMaterial::withoutGlobalScopes()
                ->firstOrCreate(
                    [
                        'supplier_id' => $transaction->supplier_id,
                        'material_sub_category_id' => $transaction->material_sub_category_id,
                    ],
                    ['quantity' => 0]
                );

            if ($transaction->type === 'insert') {
                $stock->increment('quantity', (float) $transaction->quantity);
            } elseif ($transaction->type === 'use') {
                $stock->decrement('quantity', (float) $transaction->quantity);
            }
        });
    }
}
