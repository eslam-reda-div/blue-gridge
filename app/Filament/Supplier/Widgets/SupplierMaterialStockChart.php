<?php

namespace App\Filament\Supplier\Widgets;

use App\Models\SupplierSubMaterial;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SupplierMaterialStockChart extends ChartWidget
{
    protected ?string $heading = 'Material Stock Levels';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '350px';

    protected function getData(): array
    {
        $supplierId = Auth::guard('supplier')->user()->supplier_id;

        $data = Cache::remember("supplier_{$supplierId}_stock", 300, function () {
            return SupplierSubMaterial::with('materialSubCategory')
                ->orderByDesc('quantity')
                ->take(15)
                ->get()
                ->map(fn ($item) => [
                    'name' => $item->materialSubCategory?->name ?? 'Unknown',
                    'stock' => (float) $item->quantity,
                ])
                ->toArray();
        });

        $colors = ['#10B981', '#14B8A6', '#06B6D4', '#0EA5E9', '#3B82F6', '#6366F1', '#8B5CF6', '#A855F7', '#D946EF', '#EC4899', '#F43F5E', '#F97316', '#FBBF24', '#84CC16', '#22D3EE'];

        return [
            'datasets' => [
                [
                    'label' => 'Stock Quantity',
                    'data' => array_column($data, 'stock'),
                    'backgroundColor' => array_slice($colors, 0, count($data)),
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
