<?php

namespace App\Filament\Admin\Resources\SupplyRequests\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class SupplyRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Request Details')
                    ->description('Overview of this supply request')
                    ->collapsible()
                    ->schema([
                        TextEntry::make('id')
                            ->label('Request ID')
                            ->badge()
                            ->color('gray'),
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pending' => 'warning',
                                'accepted' => 'success',
                                'rejected' => 'danger',
                                default => 'gray',
                            })
                            ->weight(FontWeight::Bold),
                        TextEntry::make('factory.name')
                            ->label('Factory')
                            ->badge()
                            ->color('warning'),
                        TextEntry::make('materialSubCategory.material.name')
                            ->label('Material')
                            ->badge()
                            ->color('primary'),
                        TextEntry::make('materialSubCategory.name')
                            ->label('Sub-Category')
                            ->badge()
                            ->color('info'),
                        TextEntry::make('quantity_needed')
                            ->label('Quantity Needed')
                            ->numeric(decimalPlaces: 2),
                        TextEntry::make('triggered_by')
                            ->label('Triggered By')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'auto' => 'info',
                                'manual' => 'primary',
                                default => 'gray',
                            }),
                        TextEntry::make('acceptedBySupplier.name')
                            ->label('Accepted By Supplier')
                            ->placeholder('Not yet accepted'),
                        TextEntry::make('created_at')
                            ->label('Requested At')
                            ->dateTime(),
                    ])
                    ->columns(2),

                Section::make('Supplier Responses')
                    ->description('Status of each supplier contacted for this request')
                    ->collapsible()
                    ->schema([
                        RepeatableEntry::make('suppliers')
                            ->label('')
                            ->schema([
                                TextEntry::make('supplier.name')
                                    ->label('Supplier')
                                    ->weight(FontWeight::Bold),
                                TextEntry::make('status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'pending' => 'warning',
                                        'accepted' => 'success',
                                        'rejected' => 'danger',
                                        'dismissed' => 'gray',
                                        default => 'gray',
                                    }),
                                TextEntry::make('rejection_reason')
                                    ->label('Rejection Reason')
                                    ->placeholder('—')
                                    ->columnSpan(2),
                                TextEntry::make('responded_at')
                                    ->label('Responded At')
                                    ->dateTime()
                                    ->placeholder('No response yet'),
                            ])
                            ->columns(2),
                    ]),

                Section::make('Activity Log')
                    ->description('Complete audit trail of all actions taken on this request')
                    ->collapsible()
                    ->schema([
                        RepeatableEntry::make('logs')
                            ->label('')
                            ->schema([
                                TextEntry::make('action')
                                    ->label('Action')
                                    ->badge()
                                    ->formatStateUsing(fn (string $state): string => match ($state) {
                                        'request_created' => 'Request Created',
                                        'sent_to_supplier' => 'Sent to Supplier',
                                        'supplier_accepted' => 'Supplier Accepted',
                                        'supplier_rejected' => 'Supplier Rejected',
                                        'supplier_dismissed' => 'Supplier Dismissed',
                                        'request_fulfilled' => 'Request Fulfilled',
                                        'all_rejected' => 'All Rejected',
                                        default => $state,
                                    })
                                    ->color(fn (string $state): string => match ($state) {
                                        'request_created' => 'info',
                                        'sent_to_supplier' => 'info',
                                        'supplier_accepted' => 'success',
                                        'supplier_rejected' => 'danger',
                                        'supplier_dismissed' => 'gray',
                                        'request_fulfilled' => 'success',
                                        'all_rejected' => 'danger',
                                        default => 'gray',
                                    }),
                                TextEntry::make('supplier.name')
                                    ->label('Supplier')
                                    ->placeholder('System'),
                                TextEntry::make('details')
                                    ->label('Details')
                                    ->columnSpan(2),
                                TextEntry::make('created_at')
                                    ->label('Time')
                                    ->dateTime(),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }
}
