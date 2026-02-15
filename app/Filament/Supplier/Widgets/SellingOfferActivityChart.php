<?php

namespace App\Filament\Supplier\Widgets;

use App\Models\SellingOffer;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SellingOfferActivityChart extends ChartWidget
{
    protected ?string $heading = 'Accepted Offers (Last 6 Months)';

    protected static ?int $sort = 5;

    protected ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $supplierId = Auth::guard('supplier')->user()->supplier_id;

        $data = Cache::remember("supplier_{$supplierId}_offer_activity", 300, function () use ($supplierId) {
            $months = collect();
            $accepted = collect();

            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $months->push($date->format('M'));

                $accepted->push(
                    SellingOffer::where('accepted_by_supplier_id', $supplierId)
                        ->whereNotNull('accepted_at')
                        ->whereYear('accepted_at', $date->year)
                        ->whereMonth('accepted_at', $date->month)
                        ->count()
                );
            }

            return [
                'months' => $months->toArray(),
                'accepted' => $accepted->toArray(),
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Accepted Offers',
                    'data' => $data['accepted'],
                    'backgroundColor' => 'rgba(16, 185, 129, 0.7)',
                    'borderColor' => '#10B981',
                    'borderRadius' => 6,
                ],
            ],
            'labels' => $data['months'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
