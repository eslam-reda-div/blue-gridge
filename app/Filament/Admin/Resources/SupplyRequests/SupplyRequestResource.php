<?php

namespace App\Filament\Admin\Resources\SupplyRequests;

use App\Filament\Admin\Resources\SupplyRequests\Pages\ListSupplyRequests;
use App\Filament\Admin\Resources\SupplyRequests\Pages\ViewSupplyRequest;
use App\Filament\Admin\Resources\SupplyRequests\Schemas\SupplyRequestInfolist;
use App\Filament\Admin\Resources\SupplyRequests\Tables\SupplyRequestsTable;
use App\Models\SupplyRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SupplyRequestResource extends Resource
{
    protected static ?string $model = SupplyRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Supply Requests';

    protected static ?string $modelLabel = 'Supply Request';

    protected static ?string $pluralModelLabel = 'Supply Requests';

    protected static string|\UnitEnum|null $navigationGroup = 'Supply Chain';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return SupplyRequest::query()
            ->with(['factory', 'materialSubCategory.material', 'acceptedBySupplier']);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SupplyRequestInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SupplyRequestsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSupplyRequests::route('/'),
            'view' => ViewSupplyRequest::route('/{record}'),
        ];
    }
}
