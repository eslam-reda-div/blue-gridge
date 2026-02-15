<?php

namespace App\Filament\Factory\Widgets;

use App\Models\FactorySubMaterial;
use App\Models\FactorySubMaterialTransaction;
use App\Models\SupplyRequest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class FactoryStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $factoryId = Auth::guard('factory')->user()->factory_id;

        $data = Cache::remember("factory_{$factoryId}_stats", 300, function () use ($factoryId) {
            $totalMaterials = FactorySubMaterial::count();
            $lowStockCount = FactorySubMaterial::whereColumn('quantity', '<', 'safe_amount')
                ->where('safe_amount', '>', 0)
                ->count();
            $connectedSuppliers = \App\Models\Factory::find($factoryId)?->suppliers()->count() ?? 0;
            $pendingRequests = SupplyRequest::where('factory_id', $factoryId)
                ->where('status', 'pending')
                ->count();
            $totalTransactions = FactorySubMaterialTransaction::count();
            $monthTransactions = FactorySubMaterialTransaction::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();

            return compact(
                'totalMaterials',
                'lowStockCount',
                'connectedSuppliers',
                'pendingRequests',
                'totalTransactions',
                'monthTransactions',
            );
        });

        return [
            Stat::make('Materials in Stock', $data['totalMaterials'])
                ->description('Unique sub-materials')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary'),

            Stat::make('Low Stock Alerts', $data['lowStockCount'])
                ->description('Below safe amount')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($data['lowStockCount'] > 0 ? 'danger' : 'success')
                ->chart([3, 5, 2, 4, $data['lowStockCount']]),

            Stat::make('Connected Suppliers', $data['connectedSuppliers'])
                ->description('Active partnerships')
                ->descriptionIcon('heroicon-m-truck')
                ->color('success'),

            Stat::make('Pending Requests', $data['pendingRequests'])
                ->description('Awaiting supplier response')
                ->descriptionIcon('heroicon-m-clock')
                ->color($data['pendingRequests'] > 5 ? 'warning' : 'info'),

            Stat::make('Total Transactions', $data['totalTransactions'])
                ->description($data['monthTransactions'].' this month')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('info'),
        ];
    }
}
