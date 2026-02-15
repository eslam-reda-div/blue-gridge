<?php

namespace App\Filament\Seller\Widgets;

use App\Models\SellingOffer;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SellerOfferTypeChart extends ChartWidget
{
    protected ?string $heading = 'Offer Type (Broadcast vs Targeted)';

    protected static ?int $sort = 3;

    protected ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $sellerId = Auth::guard('seller')->id();

        $data = Cache::remember("seller_{$sellerId}_offer_type", 300, function () use ($sellerId) {
            return [
                'broadcast' => SellingOffer::where('seller_id', $sellerId)->where('type', 'broadcast')->count(),
                'targeted' => SellingOffer::where('seller_id', $sellerId)->where('type', 'targeted')->count(),
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Offer Types',
                    'data' => array_values($data),
                    'backgroundColor' => ['#8B5CF6', '#F59E0B'],
                    'borderColor' => ['#7C3AED', '#D97706'],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => ['Broadcast', 'Targeted'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
