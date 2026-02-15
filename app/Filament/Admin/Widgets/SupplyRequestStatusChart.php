<?php

namespace App\Filament\Admin\Widgets;

use App\Models\SupplyRequest;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class SupplyRequestStatusChart extends ChartWidget
{
    protected ?string $heading = 'Supply Request Status';

    protected static ?int $sort = 2;

    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = Cache::remember('admin_supply_request_status', 300, function () {
            return [
                'pending' => SupplyRequest::where('status', 'pending')->count(),
                'accepted' => SupplyRequest::where('status', 'accepted')->count(),
                'rejected' => SupplyRequest::where('status', 'rejected')->count(),
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Supply Requests',
                    'data' => array_values($data),
                    'backgroundColor' => ['#FBBF24', '#34D399', '#F87171'],
                    'borderColor' => ['#F59E0B', '#10B981', '#EF4444'],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => ['Pending', 'Accepted', 'Rejected'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
