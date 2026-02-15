<?php

namespace App\Filament\Seller\Widgets;

use App\Models\SellingOffer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SellerStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $sellerId = Auth::guard('seller')->id();

        $data = Cache::remember("seller_{$sellerId}_stats", 300, function () use ($sellerId) {
            $totalOffers = SellingOffer::where('seller_id', $sellerId)->count();
            $openOffers = SellingOffer::where('seller_id', $sellerId)->where('status', 'open')->count();
            $acceptedOffers = SellingOffer::where('seller_id', $sellerId)->where('status', 'accepted')->count();
            $closedOffers = SellingOffer::where('seller_id', $sellerId)->where('status', 'closed')->count();
            $totalQuantity = (float) SellingOffer::where('seller_id', $sellerId)->sum('quantity');
            $totalRevenuePotential = (float) SellingOffer::where('seller_id', $sellerId)
                ->whereNotNull('price_per_unit')
                ->selectRaw('SUM(quantity * price_per_unit) as total')
                ->value('total') ?? 0;

            return compact(
                'totalOffers',
                'openOffers',
                'acceptedOffers',
                'closedOffers',
                'totalQuantity',
                'totalRevenuePotential',
            );
        });

        return [
            Stat::make('Total Offers', $data['totalOffers'])
                ->description('All time')
                ->descriptionIcon('heroicon-m-megaphone')
                ->color('primary')
                ->chart([2, 4, 3, 5, $data['totalOffers']]),

            Stat::make('Open Offers', $data['openOffers'])
                ->description('Awaiting response')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Accepted Offers', $data['acceptedOffers'])
                ->description('Successfully matched')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Closed Offers', $data['closedOffers'])
                ->description('Archived')
                ->descriptionIcon('heroicon-m-archive-box')
                ->color('gray'),

            Stat::make('Total Quantity', number_format($data['totalQuantity'], 1))
                ->description('Units offered')
                ->descriptionIcon('heroicon-m-scale')
                ->color('info'),

            Stat::make('Revenue Potential', 'EGP '.number_format($data['totalRevenuePotential'], 0))
                ->description('Based on priced offers')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
        ];
    }
}
