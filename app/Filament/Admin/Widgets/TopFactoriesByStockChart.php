<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Factory;
use App\Models\FactorySubMaterial;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class TopFactoriesByStockChart extends ChartWidget
{
    protected ?string $heading = 'Top Factories by Total Stock';

    protected static ?int $sort = 5;

    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = Cache::remember('admin_top_factories_stock', 300, function () {
            $factories = Factory::all();

            return $factories->map(function ($factory) {
                return [
                    'name' => $factory->name,
                    'stock' => (float) FactorySubMaterial::withoutGlobalScopes()
                        ->where('factory_id', $factory->id)
                        ->sum('quantity'),
                ];
            })
                ->sortByDesc('stock')
                ->take(8)
                ->values()
                ->toArray();
        });

        return [
            'datasets' => [
                [
                    'label' => 'Total Stock Quantity',
                    'data' => array_column($data, 'stock'),
                    'backgroundColor' => ['#3B82F6', '#6366F1', '#8B5CF6', '#A855F7', '#D946EF', '#EC4899', '#F43F5E', '#F97316'],
                    'borderRadius' => 4,
                ],
            ],
            'labels' => array_map(fn ($item) => str($item['name'])->limit(20)->toString(), $data),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
