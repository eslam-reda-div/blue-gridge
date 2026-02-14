<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
     * Get the material stock for this factory.
     */
    public function materials(): HasMany
    {
        return $this->hasMany(FactoryMaterial::class);
    }

    /**
     * Get all material transactions for this factory.
     */
    public function materialTransactions(): HasMany
    {
        return $this->hasMany(FactoryMaterialTransaction::class);
    }
}
