<?php

namespace App\Filament\Supplier\Widgets;

use App\Models\SellingOffer;
use App\Models\SupplierSubMaterial;
use App\Models\SupplierSubMaterialTransaction;
use App\Models\SupplyRequestSupplier;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SupplierStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $supplierId = Auth::guard('supplier')->user()->supplier_id;

        $data = Cache::remember("supplier_{$supplierId}_stats", 300, function () use ($supplierId) {
            $totalMaterials = SupplierSubMaterial::count();
            $connectedFactories = \App\Models\Supplier::find($supplierId)?->factories()->count() ?? 0;
            $pendingRequests = SupplyRequestSupplier::where('supplier_id', $supplierId)
                ->where('status', 'pending')
                ->count();
            $acceptedOffers = SellingOffer::where('accepted_by_supplier_id', $supplierId)->count();
            $totalTransactions = SupplierSubMaterialTransaction::count();
            $monthTransactions = SupplierSubMaterialTransaction::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();

            return compact(
                'totalMaterials',
                'connectedFactories',
                'pendingRequests',
                'acceptedOffers',
                'totalTransactions',
                'monthTransactions',
            );
        });

        return [
            Stat::make('Materials in Stock', $data['totalMaterials'])
                ->description('Unique sub-materials')
                ->descriptionIcon('heroicon-m-cube')
                ->color('success'),

            Stat::make('Connected Factories', $data['connectedFactories'])
                ->description('Active partnerships')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('primary'),

            Stat::make('Pending Requests', $data['pendingRequests'])
                ->description('Awaiting your response')
                ->descriptionIcon('heroicon-m-clock')
                ->color($data['pendingRequests'] > 0 ? 'warning' : 'success')
                ->chart([2, 3, 4, 2, $data['pendingRequests']]),

            Stat::make('Accepted Offers', $data['acceptedOffers'])
                ->description('From marketplace')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('info'),

            Stat::make('Total Transactions', $data['totalTransactions'])
                ->description($data['monthTransactions'].' this month')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('success'),
        ];
    }
}
