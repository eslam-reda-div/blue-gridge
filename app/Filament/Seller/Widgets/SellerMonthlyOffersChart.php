<?php

namespace App\Filament\Seller\Widgets;

use App\Models\SellingOffer;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SellerMonthlyOffersChart extends ChartWidget
{
    protected ?string $heading = 'Monthly Offers Created';

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $sellerId = Auth::guard('seller')->id();

        $data = Cache::remember("seller_{$sellerId}_monthly_offers", 300, function () use ($sellerId) {
            $months = collect();
            $open = collect();
            $accepted = collect();
            $closed = collect();

            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $months->push($date->format('M Y'));

                $baseQuery = SellingOffer::where('seller_id', $sellerId)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month);

                $open->push((clone $baseQuery)->where('status', 'open')->count());
                $accepted->push((clone $baseQuery)->where('status', 'accepted')->count());
                $closed->push((clone $baseQuery)->where('status', 'closed')->count());
            }

            return [
                'months' => $months->toArray(),
                'open' => $open->toArray(),
                'accepted' => $accepted->toArray(),
                'closed' => $closed->toArray(),
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Open',
                    'data' => $data['open'],
                    'borderColor' => '#3B82F6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.15)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Accepted',
                    'data' => $data['accepted'],
                    'borderColor' => '#10B981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.15)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Closed',
                    'data' => $data['closed'],
                    'borderColor' => '#9CA3AF',
                    'backgroundColor' => 'rgba(156, 163, 175, 0.15)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $data['months'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
