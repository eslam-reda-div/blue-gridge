<?php

namespace App\Filament\Supplier\Widgets;

use App\Models\SupplyRequestSupplier;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SupplyRequestResponseChart extends ChartWidget
{
    protected ?string $heading = 'Supply Request Responses';

    protected static ?int $sort = 4;

    protected ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $supplierId = Auth::guard('supplier')->user()->supplier_id;

        $data = Cache::remember("supplier_{$supplierId}_request_responses", 300, function () use ($supplierId) {
            return [
                'pending' => SupplyRequestSupplier::where('supplier_id', $supplierId)->where('status', 'pending')->count(),
                'accepted' => SupplyRequestSupplier::where('supplier_id', $supplierId)->where('status', 'accepted')->count(),
                'rejected' => SupplyRequestSupplier::where('supplier_id', $supplierId)->where('status', 'rejected')->count(),
                'dismissed' => SupplyRequestSupplier::where('supplier_id', $supplierId)->where('status', 'dismissed')->count(),
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Responses',
                    'data' => array_values($data),
                    'backgroundColor' => ['#FBBF24', '#34D399', '#F87171', '#9CA3AF'],
                    'borderColor' => ['#F59E0B', '#10B981', '#EF4444', '#6B7280'],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => ['Pending', 'Accepted', 'Rejected', 'Dismissed'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
