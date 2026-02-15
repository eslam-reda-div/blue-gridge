<?php

namespace App\Filament\Factory\Widgets;

use App\Models\FactorySubMaterialTransaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class FactoryTransactionTypeChart extends ChartWidget
{
    protected ?string $heading = 'Transaction Type Distribution';

    protected static ?int $sort = 5;

    protected ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $factoryId = Auth::guard('factory')->user()->factory_id;

        $data = Cache::remember("factory_{$factoryId}_txn_type", 300, function () {
            return [
                'insert' => FactorySubMaterialTransaction::where('type', 'insert')->count(),
                'use' => FactorySubMaterialTransaction::where('type', 'use')->count(),
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
