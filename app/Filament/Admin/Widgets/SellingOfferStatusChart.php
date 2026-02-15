<?php

namespace App\Filament\Admin\Widgets;

use App\Models\SellingOffer;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class SellingOfferStatusChart extends ChartWidget
{
    protected ?string $heading = 'Selling Offer Status';

    protected static ?int $sort = 3;

    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = Cache::remember('admin_selling_offer_status', 300, function () {
            return [
                'open' => SellingOffer::where('status', 'open')->count(),
                'accepted' => SellingOffer::where('status', 'accepted')->count(),
                'closed' => SellingOffer::where('status', 'closed')->count(),
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Selling Offers',
                    'data' => array_values($data),
                    'backgroundColor' => ['#60A5FA', '#34D399', '#9CA3AF'],
                    'borderColor' => ['#3B82F6', '#10B981', '#6B7280'],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => ['Open', 'Accepted', 'Closed'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
