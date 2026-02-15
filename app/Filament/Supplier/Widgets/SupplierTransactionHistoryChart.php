<?php

namespace App\Filament\Supplier\Widgets;

use App\Models\SupplierSubMaterialTransaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SupplierTransactionHistoryChart extends ChartWidget
{
    protected ?string $heading = 'Transaction History (Last 8 Weeks)';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $supplierId = Auth::guard('supplier')->user()->supplier_id;

        $data = Cache::remember("supplier_{$supplierId}_txn_history", 300, function () {
            $weeks = collect();
            $inserts = collect();
            $uses = collect();

            for ($i = 7; $i >= 0; $i--) {
                $start = Carbon::now()->subWeeks($i)->startOfWeek();
                $end = Carbon::now()->subWeeks($i)->endOfWeek();
                $weeks->push($start->format('M d'));

                $inserts->push(
                    SupplierSubMaterialTransaction::whereBetween('created_at', [$start, $end])
                        ->where('type', 'insert')
                        ->count()
                );

                $uses->push(
                    SupplierSubMaterialTransaction::whereBetween('created_at', [$start, $end])
                        ->where('type', 'use')
                        ->count()
                );
            }

            return [
                'weeks' => $weeks->toArray(),
                'inserts' => $inserts->toArray(),
                'uses' => $uses->toArray(),
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Stock In (Insert)',
                    'data' => $data['inserts'],
                    'borderColor' => '#10B981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.15)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Stock Out (Use)',
                    'data' => $data['uses'],
                    'borderColor' => '#EF4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.15)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $data['weeks'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
