<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Factory;
use App\Models\Material;
use App\Models\Seller;
use App\Models\SellingOffer;
use App\Models\Supplier;
use App\Models\SupplyRequest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class AdminStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $data = Cache::remember('admin_stats_overview', 300, function () {
            $totalFactories = Factory::count();
            $totalSuppliers = Supplier::count();
            $totalSellers = Seller::count();
            $totalMaterials = Material::count();
            $pendingRequests = SupplyRequest::where('status', 'pending')->count();
            $openOffers = SellingOffer::where('status', 'open')->count();
            $acceptedRequests = SupplyRequest::where('status', 'accepted')->count();
            $acceptedOffers = SellingOffer::where('status', 'accepted')->count();

            return compact(
                'totalFactories',
                'totalSuppliers',
                'totalSellers',
                'totalMaterials',
                'pendingRequests',
                'openOffers',
                'acceptedRequests',
                'acceptedOffers',
            );
        });

        return [
            Stat::make('Factories', $data['totalFactories'])
                ->description('Registered factories')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('primary')
                ->chart([4, 3, 5, 4, 5, $data['totalFactories']]),

            Stat::make('Suppliers', $data['totalSuppliers'])
                ->description('Active suppliers')
                ->descriptionIcon('heroicon-m-truck')
                ->color('success')
                ->chart([6, 5, 7, 6, 8, $data['totalSuppliers']]),

            Stat::make('Sellers', $data['totalSellers'])
                ->description('Marketplace sellers')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning')
                ->chart([3, 4, 5, 6, 7, $data['totalSellers']]),

            Stat::make('Materials', $data['totalMaterials'])
                ->description('Material categories')
                ->descriptionIcon('heroicon-m-cube')
                ->color('info'),

            Stat::make('Pending Requests', $data['pendingRequests'])
                ->description($data['acceptedRequests'].' accepted')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color($data['pendingRequests'] > 10 ? 'danger' : 'warning'),

            Stat::make('Open Offers', $data['openOffers'])
                ->description($data['acceptedOffers'].' accepted')
                ->descriptionIcon('heroicon-m-megaphone')
                ->color('success'),
        ];
    }
}
