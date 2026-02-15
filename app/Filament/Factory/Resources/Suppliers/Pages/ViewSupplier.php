<?php

namespace App\Filament\Factory\Resources\Suppliers\Pages;

use App\Filament\Factory\Resources\Suppliers\SupplierResource;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;

class ViewSupplier extends ViewRecord
{
    protected static string $resource = SupplierResource::class;

    protected function resolveRecord(int|string $key): Model
    {
        return static::getResource()::getEloquentQuery()
            ->with(['allSubMaterials.materialSubCategory.material'])
            ->findOrFail($key);
    }
}
