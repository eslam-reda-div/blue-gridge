<?php

namespace App\Filament\Seller\Widgets;

use App\Models\SellingOffer;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SellerOffersByMaterialChart extends ChartWidget
{
    protected ?string $heading = 'Offers by Material Category';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $sellerId = Auth::guard('seller')->id();

        $data = Cache::remember("seller_{$sellerId}_offers_by_material", 300, function () use ($sellerId) {
            return SellingOffer::where('selling_offers.seller_id', $sellerId)
                ->join('material_sub_categories', 'selling_offers.material_sub_category_id', '=', 'material_sub_categories.id')
                ->join('materials', 'material_sub_categories.material_id', '=', 'materials.id')
                ->select('materials.name', DB::raw('COUNT(*) as count'), DB::raw('SUM(selling_offers.quantity) as total_qty'))
                ->groupBy('materials.id', 'materials.name')
                ->orderByDesc('count')
                ->get()
                ->toArray();
        });

        $colors = ['#F43F5E', '#EC4899', '#D946EF', '#A855F7', '#8B5CF6', '#6366F1', '#3B82F6', '#0EA5E9', '#06B6D4', '#14B8A6'];

        return [
            'datasets' => [
                [
                    'label' => 'Number of Offers',
                    'data' => array_column($data, 'count'),
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                    'borderRadius' => 4,
                ],
            ],
            'labels' => array_column($data, 'name'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
