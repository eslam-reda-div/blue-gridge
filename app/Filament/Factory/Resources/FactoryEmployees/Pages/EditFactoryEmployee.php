<?php

namespace App\Filament\Factory\Resources\FactoryEmployees\Pages;

use App\Filament\Factory\Resources\FactoryEmployees\FactoryEmployeeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFactoryEmployee extends EditRecord
{
    protected static string $resource = FactoryEmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
