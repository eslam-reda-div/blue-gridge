<?php

namespace App\Filament\Supplier\Resources\Factories\Tables;

use App\Models\Factory;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class FactoriesTable
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
                    ->modalHeading('Remove Factory Connection')
                    ->modalDescription('Are you sure you want to disconnect this factory? This will not delete the factory, only remove the connection.')
                    ->action(function (Factory $record): void {
                        $supplierId = Auth::guard('supplier')->user()?->supplier_id;
                        $record->suppliers()->detach($supplierId);
                    }),
            ])
            ->toolbarActions([]);
    }
}
