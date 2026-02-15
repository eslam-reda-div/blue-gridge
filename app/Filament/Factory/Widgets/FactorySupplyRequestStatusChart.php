<?php

namespace App\Filament\Factory\Widgets;

use App\Models\SupplyRequest;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class FactorySupplyRequestStatusChart extends ChartWidget
{
    protected ?string $heading = 'Supply Request Status';

    protected static ?int $sort = 4;

    protected ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $factoryId = Auth::guard('factory')->user()->factory_id;

        $data = Cache::remember("factory_{$factoryId}_request_status", 300, function () use ($factoryId) {
            return [
                'pending' => SupplyRequest::where('factory_id', $factoryId)->where('status', 'pending')->count(),
                'accepted' => SupplyRequest::where('factory_id', $factoryId)->where('status', 'accepted')->count(),
                'rejected' => SupplyRequest::where('factory_id', $factoryId)->where('status', 'rejected')->count(),
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Requests',
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
