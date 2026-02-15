<?php

namespace App\Filament\Admin\Resources\FactoryEmployees\Pages;

use App\Filament\Admin\Resources\FactoryEmployees\FactoryEmployeeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFactoryEmployee extends CreateRecord
{
    protected static string $resource = FactoryEmployeeResource::class;
}
