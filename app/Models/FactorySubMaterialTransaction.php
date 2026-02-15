<?php

namespace App\Models;

use App\Jobs\SendSupplyRequest;
use App\Models\Scopes\FactoryScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ScopedBy(FactoryScope::class)]
class FactorySubMaterialTransaction extends Model
{
    protected $fillable = [
        'factory_id',
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
     * Boot the model - auto-update stock on create.
     */
    protected static function booted(): void
    {
        static::created(function (FactorySubMaterialTransaction $transaction) {
            $stock = FactorySubMaterial::withoutGlobalScopes()
                ->firstOrCreate(
                    [
                        'factory_id' => $transaction->factory_id,
                        'material_sub_category_id' => $transaction->material_sub_category_id,
                    ],
                    ['quantity' => 0]
                );

            if ($transaction->type === 'insert') {
                $stock->increment('quantity', (float) $transaction->quantity);
            } elseif ($transaction->type === 'use') {
                $stock->decrement('quantity', (float) $transaction->quantity);
            }

            // Refresh stock to get updated quantity
            $stock->refresh();

            // Check if stock dropped below safe amount and dispatch supply request
            if (
                (float) $stock->safe_amount > 0
                && (float) $stock->quantity < (float) $stock->safe_amount
            ) {
                SendSupplyRequest::dispatch(
                    $transaction->factory_id,
                    $transaction->material_sub_category_id,
                    (float) $stock->safe_amount - (float) $stock->quantity,
                );
            }
        });
    }
}
