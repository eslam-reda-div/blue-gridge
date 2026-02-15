<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Supplier;
use App\Models\SupplierSubMaterial;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class TopSuppliersByStockChart extends ChartWidget
{
    protected ?string $heading = 'Top Suppliers by Total Stock';

    protected static ?int $sort = 6;

    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = Cache::remember('admin_top_suppliers_stock', 300, function () {
            $suppliers = Supplier::all();

            return $suppliers->map(function ($supplier) {
                return [
                    'name' => $supplier->name,
                    'stock' => (float) SupplierSubMaterial::withoutGlobalScopes()
                        ->where('supplier_id', $supplier->id)
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
                    'backgroundColor' => ['#10B981', '#14B8A6', '#06B6D4', '#0EA5E9', '#3B82F6', '#6366F1', '#8B5CF6', '#A855F7'],
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
