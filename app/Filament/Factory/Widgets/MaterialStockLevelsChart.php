<?php

namespace App\Filament\Factory\Widgets;

use App\Models\FactorySubMaterial;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class MaterialStockLevelsChart extends ChartWidget
{
    protected ?string $heading = 'Stock Levels vs Safe Amount';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '350px';

    protected function getData(): array
    {
        $factoryId = Auth::guard('factory')->user()->factory_id;

        $data = Cache::remember("factory_{$factoryId}_stock_levels", 300, function () {
            return FactorySubMaterial::with('materialSubCategory')
                ->orderByDesc('quantity')
                ->take(15)
                ->get()
                ->map(fn ($item) => [
                    'name' => $item->materialSubCategory?->name ?? 'Unknown',
                    'stock' => (float) $item->quantity,
                    'safe' => (float) $item->safe_amount,
                ])
                ->toArray();
        });

        return [
            'datasets' => [
                [
                    'label' => 'Current Stock',
                    'data' => array_column($data, 'stock'),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.7)',
                    'borderColor' => '#3B82F6',
                    'borderWidth' => 1,
                    'borderRadius' => 4,
                ],
                [
                    'label' => 'Safe Amount',
                    'data' => array_column($data, 'safe'),
                    'backgroundColor' => 'rgba(239, 68, 68, 0.3)',
                    'borderColor' => '#EF4444',
                    'borderWidth' => 2,
                    'borderDash' => [5, 5],
                    'borderRadius' => 4,
                    'type' => 'line',
                    'fill' => false,
                    'pointRadius' => 4,
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
