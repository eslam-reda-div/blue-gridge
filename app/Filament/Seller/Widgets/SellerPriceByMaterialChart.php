<?php

namespace App\Filament\Seller\Widgets;

use App\Models\SellingOffer;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SellerPriceByMaterialChart extends ChartWidget
{
    protected ?string $heading = 'Average Price per Unit by Material';

    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $sellerId = Auth::guard('seller')->id();

        $data = Cache::remember("seller_{$sellerId}_price_by_material", 300, function () use ($sellerId) {
            return SellingOffer::where('selling_offers.seller_id', $sellerId)
                ->whereNotNull('selling_offers.price_per_unit')
                ->join('material_sub_categories', 'selling_offers.material_sub_category_id', '=', 'material_sub_categories.id')
                ->select(
                    'material_sub_categories.name',
                    DB::raw('ROUND(AVG(selling_offers.price_per_unit), 2) as avg_price'),
                    DB::raw('ROUND(SUM(selling_offers.quantity), 1) as total_qty'),
                )
                ->groupBy('material_sub_categories.id', 'material_sub_categories.name')
                ->orderByDesc('avg_price')
                ->take(10)
                ->get()
                ->toArray();
        });

        return [
            'datasets' => [
                [
                    'label' => 'Avg Price/Unit (EGP)',
                    'data' => array_column($data, 'avg_price'),
                    'backgroundColor' => 'rgba(244, 63, 94, 0.7)',
                    'borderColor' => '#F43F5E',
                    'borderWidth' => 1,
                    'borderRadius' => 4,
                ],
                [
                    'label' => 'Total Quantity',
                    'data' => array_column($data, 'total_qty'),
                    'backgroundColor' => 'rgba(139, 92, 246, 0.5)',
                    'borderColor' => '#8B5CF6',
                    'borderWidth' => 1,
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
