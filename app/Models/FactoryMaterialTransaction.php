<?php

namespace App\Models;

use App\Models\Scopes\FactoryScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ScopedBy(FactoryScope::class)]
class FactoryMaterialTransaction extends Model
{
    protected $fillable = [
        'factory_id',
        'material_id',
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
     * Get the material.
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    /**
     * Boot the model - auto-update stock on create.
     */
    protected static function booted(): void
    {
        static::created(function (FactoryMaterialTransaction $transaction) {
            $stock = FactoryMaterial::withoutGlobalScopes()
                ->firstOrCreate(
                    [
                        'factory_id' => $transaction->factory_id,
                        'material_id' => $transaction->material_id,
                    ],
                    ['quantity' => 0]
                );

            if ($transaction->type === 'insert') {
                $stock->increment('quantity', $transaction->quantity);
            } elseif ($transaction->type === 'use') {
                $stock->decrement('quantity', $transaction->quantity);
            }
        });
    }
}
