<?php

namespace App\Filament\Seller\Resources\SellingOffers\Pages;

use App\Filament\Seller\Resources\SellingOffers\SellingOfferResource;
use App\Models\SellingOffer;
use App\Models\SellingOfferTarget;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditSellingOffer extends EditRecord
{
    protected static string $resource = SellingOfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
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
                    $this->redirect($this->getResource()::getUrl('view', ['record' => $this->record]));
                }),
            DeleteAction::make()
                ->visible(fn (): bool => $this->record->isOpen()),
        ];
    }

    /**
     * Only allow editing open offers.
     */
    protected function authorizeAccess(): void
    {
        parent::authorizeAccess();

        /** @var SellingOffer $record */
        $record = $this->getRecord();

        abort_unless($record->isOpen(), 403, 'You can only edit open offers.');
    }

    /**
     * Pre-fill the target_supplier_ids from existing targets when loading the form.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        /** @var SellingOffer $record */
        $record = $this->getRecord();

        $data['target_supplier_ids'] = $record->targets()->pluck('supplier_id')->toArray();

        return $data;
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        // Extract target supplier IDs before updating the record
        $targetSupplierIds = $data['target_supplier_ids'] ?? [];
        unset($data['target_supplier_ids']);

        $record->update($data);

        // Sync targets: delete old, create new
        $record->targets()->delete();

        if ($data['type'] === 'targeted' && ! empty($targetSupplierIds)) {
            foreach ($targetSupplierIds as $supplierId) {
                SellingOfferTarget::create([
                    'selling_offer_id' => $record->id,
                    'supplier_id' => $supplierId,
                ]);
            }
        }

        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
