<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Material;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class MaterialCategoryDistributionChart extends ChartWidget
{
    protected ?string $heading = 'Sub-Categories per Material';

    protected static ?int $sort = 7;

    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = Cache::remember('admin_material_category_dist', 300, function () {
            return Material::withCount('subCategories')
                ->orderByDesc('sub_categories_count')
                ->get()
                ->map(fn ($m) => ['name' => $m->name, 'count' => $m->sub_categories_count])
                ->toArray();
        });

        return [
            'datasets' => [
                [
                    'label' => 'Sub-Categories',
                    'data' => array_column($data, 'count'),
                    'backgroundColor' => [
                        '#F59E0B', '#EF4444', '#3B82F6', '#10B981', '#8B5CF6',
                        '#EC4899', '#F97316', '#06B6D4', '#84CC16', '#6366F1',
                    ],
                ],
            ],
            'labels' => array_column($data, 'name'),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
