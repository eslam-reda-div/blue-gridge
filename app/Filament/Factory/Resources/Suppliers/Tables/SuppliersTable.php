<?php

namespace App\Filament\Factory\Resources\Suppliers\Tables;

use App\Models\Supplier;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class SuppliersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('location')
                    ->label('Location')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('connected_at')
                    ->label('Connected Since')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([])
            ->recordActions([
                ViewAction::make(),
                Action::make('disconnect')
                    ->label('Remove')
                    ->icon(Heroicon::OutlinedTrash)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Remove Supplier Connection')
                    ->modalDescription('Are you sure you want to disconnect this supplier? This will not delete the supplier, only remove the connection.')
                    ->action(function (Supplier $record): void {
                        $factoryId = Auth::guard('factory')->user()?->factory_id;
                        $record->factories()->detach($factoryId);
                    }),
            ])
            ->toolbarActions([]);
    }
}
