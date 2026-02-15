<?php

namespace App\Filament\Supplier\Resources\Factories\Pages;

use App\Filament\Supplier\Resources\Factories\FactoryResource;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;

class ViewFactory extends ViewRecord
{
    protected static string $resource = FactoryResource::class;

    protected function resolveRecord(int|string $key): Model
    {
        return static::getResource()::getEloquentQuery()
            ->with(['allSubMaterials.materialSubCategory.material'])
            ->findOrFail($key);
    }
}
