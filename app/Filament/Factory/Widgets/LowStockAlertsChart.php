<?php

namespace App\Filament\Factory\Widgets;

use App\Models\FactorySubMaterial;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class LowStockAlertsChart extends ChartWidget
{
    protected ?string $heading = 'Low Stock Alerts (Below Safe Amount)';

    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $factoryId = Auth::guard('factory')->user()->factory_id;

        $data = Cache::remember("factory_{$factoryId}_low_stock", 300, function () {
            return FactorySubMaterial::with('materialSubCategory')
                ->whereColumn('quantity', '<', 'safe_amount')
                ->where('safe_amount', '>', 0)
                ->orderByRaw('safe_amount - quantity DESC')
                ->take(10)
                ->get()
                ->map(fn ($item) => [
                    'name' => $item->materialSubCategory?->name ?? 'Unknown',
                    'stock' => (float) $item->quantity,
                    'safe' => (float) $item->safe_amount,
                    'deficit' => round((float) $item->safe_amount - (float) $item->quantity, 2),
                ])
                ->toArray();
        });

        if (empty($data)) {
            return [
                'datasets' => [
                    ['label' => 'No low stock alerts', 'data' => [0], 'backgroundColor' => '#34D399'],
                ],
                'labels' => ['All materials are above safe levels'],
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Current Stock',
                    'data' => array_column($data, 'stock'),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.7)',
                    'borderRadius' => 4,
                ],
                [
                    'label' => 'Deficit (Need to Order)',
                    'data' => array_column($data, 'deficit'),
                    'backgroundColor' => 'rgba(239, 68, 68, 0.7)',
                    'borderRadius' => 4,
                ],
            ],
            'labels' => array_map(fn ($item) => str($item['name'])->limit(18)->toString(), $data),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
