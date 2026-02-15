<?php

namespace App\Filament\Supplier\Widgets;

use App\Models\SupplierSubMaterialTransaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SupplierTransactionTypeChart extends ChartWidget
{
    protected ?string $heading = 'Transaction Type Breakdown';

    protected static ?int $sort = 6;

    protected ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $supplierId = Auth::guard('supplier')->user()->supplier_id;

        $data = Cache::remember("supplier_{$supplierId}_txn_type", 300, function () {
            return [
                'insert' => SupplierSubMaterialTransaction::where('type', 'insert')->count(),
                'use' => SupplierSubMaterialTransaction::where('type', 'use')->count(),
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Transactions',
                    'data' => array_values($data),
                    'backgroundColor' => ['#34D399', '#F87171'],
                    'borderColor' => ['#10B981', '#EF4444'],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => ['Stock In (Insert)', 'Stock Out (Use)'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
