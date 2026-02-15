<?php

namespace App\Filament\Admin\Widgets;

use App\Models\FactorySubMaterialTransaction;
use App\Models\SupplierSubMaterialTransaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class MonthlyTransactionsChart extends ChartWidget
{
    protected ?string $heading = 'Monthly Transactions (Last 6 Months)';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = Cache::remember('admin_monthly_transactions', 300, function () {
            $months = collect();
            $factoryData = collect();
            $supplierData = collect();

            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $months->push($date->format('M Y'));

                $factoryData->push(
                    FactorySubMaterialTransaction::withoutGlobalScopes()
                        ->whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->count()
                );

                $supplierData->push(
                    SupplierSubMaterialTransaction::withoutGlobalScopes()
                        ->whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->count()
                );
            }

            return [
                'months' => $months->toArray(),
                'factory' => $factoryData->toArray(),
                'supplier' => $supplierData->toArray(),
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Factory Transactions',
                    'data' => $data['factory'],
                    'borderColor' => '#3B82F6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Supplier Transactions',
                    'data' => $data['supplier'],
                    'borderColor' => '#10B981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $data['months'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
