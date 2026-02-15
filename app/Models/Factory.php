<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Factory extends Model
{
    protected $table = 'factories';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'location',
    ];

    /**
     * Get the employees for the factory.
     */
    public function employees(): HasMany
    {
        return $this->hasMany(FactoryEmployee::class);
    }

    /**
     * Get the sub-material stock for this factory.
     */
    public function subMaterials(): HasMany
    {
        return $this->hasMany(FactorySubMaterial::class);
    }

    /**
     * Get all sub-material transactions for this factory.
     */
    public function subMaterialTransactions(): HasMany
    {
        return $this->hasMany(FactorySubMaterialTransaction::class);
    }

    /**
     * Get the suppliers connected to this factory.
     */
    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(Supplier::class)->withTimestamps();
    }

    /**
     * Get all sub-materials for this factory (bypasses global scopes).
     */
    public function allSubMaterials(): HasMany
    {
        return $this->hasMany(FactorySubMaterial::class)->withoutGlobalScopes();
    }

    /**
     * Get all supply requests for this factory.
     */
    public function supplyRequests(): HasMany
    {
        return $this->hasMany(SupplyRequest::class);
    }
}
