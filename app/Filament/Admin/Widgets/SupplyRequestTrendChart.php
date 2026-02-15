<?php

namespace App\Filament\Admin\Widgets;

use App\Models\SupplyRequest;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class SupplyRequestTrendChart extends ChartWidget
{
    protected ?string $heading = 'Supply Request Trend (Last 6 Months)';

    protected static ?int $sort = 8;

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $data = Cache::remember('admin_supply_request_trend', 300, function () {
            $months = collect();
            $pending = collect();
            $accepted = collect();
            $rejected = collect();

            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $months->push($date->format('M Y'));

                $pending->push(
                    SupplyRequest::where('status', 'pending')
                        ->whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->count()
                );

                $accepted->push(
                    SupplyRequest::where('status', 'accepted')
                        ->whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->count()
                );

                $rejected->push(
                    SupplyRequest::where('status', 'rejected')
                        ->whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->count()
                );
            }

            return [
                'months' => $months->toArray(),
                'pending' => $pending->toArray(),
                'accepted' => $accepted->toArray(),
                'rejected' => $rejected->toArray(),
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Pending',
                    'data' => $data['pending'],
                    'borderColor' => '#F59E0B',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Accepted',
                    'data' => $data['accepted'],
                    'borderColor' => '#10B981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Rejected',
                    'data' => $data['rejected'],
                    'borderColor' => '#EF4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
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
