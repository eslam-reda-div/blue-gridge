<?php

namespace App\Filament\Seller\Resources\SellingOffers\Pages;

use App\Filament\Seller\Resources\SellingOffers\SellingOfferResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewSellingOffer extends ViewRecord
{
    protected static string $resource = SellingOfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->visible(fn (): bool => $this->record->isOpen()),
            Action::make('close')
                ->label('Close Offer')
                ->icon(Heroicon::OutlinedXCircle)
                ->color('gray')
                ->requiresConfirmation()
                ->modalHeading('Close Offer')
                ->modalDescription('Are you sure you want to close this offer? Suppliers will no longer be able to accept it.')
                ->visible(fn (): bool => $this->record->isOpen())
                ->action(function (): void {
                    $this->record->update(['status' => 'closed']);
                    $this->refreshFormData(['status']);
                }),
            Action::make('reopen')
                ->label('Reopen Offer')
                ->icon(Heroicon::OutlinedArrowPath)
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Reopen Offer')
                ->modalDescription('This will reopen the offer so suppliers can see and accept it again.')
                ->visible(fn (): bool => $this->record->isClosed())
                ->action(function (): void {
                    $this->record->update([
                        'status' => 'open',
                        'accepted_by_supplier_id' => null,
                        'accepted_at' => null,
                    ]);
                    $this->refreshFormData(['status', 'accepted_by_supplier_id', 'accepted_at']);
                }),
            DeleteAction::make()
                ->visible(fn (): bool => $this->record->isOpen()),
        ];
    }
}
