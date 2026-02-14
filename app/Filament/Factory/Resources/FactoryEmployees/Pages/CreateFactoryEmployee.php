<?php

namespace App\Filament\Factory\Resources\FactoryEmployees\Pages;

use App\Filament\Factory\Resources\FactoryEmployees\FactoryEmployeeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFactoryEmployee extends CreateRecord
{
    protected static string $resource = FactoryEmployeeResource::class;
}
