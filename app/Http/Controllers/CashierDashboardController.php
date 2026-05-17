<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderRecord;
use App\Models\Inventory;
use Carbon\Carbon;

class CashierDashboardController extends Controller
{
    public function index()
    {
        $latestRecordDate = OrderRecord::whereNotNull('completed_at')
            ->latest('completed_at')
            ->value('completed_at');

        $salesDate = $latestRecordDate
            ? Carbon::parse($latestRecordDate)
            : today();

        $activeOrdersCount = Order::whereIn('status', [
            'pending',
            'in_progress',
            'ready'
        ])->count();

        $todaysSales = OrderRecord::whereBetween('completed_at', [
            $salesDate->copy()->startOfDay(),
            $salesDate->copy()->endOfDay()
        ])->sum('total');

        $lowStockCount = Inventory::whereColumn('stock_level', '<=', 'min_stock')
            ->count();

        $weeklySalesData = collect(range(6, 0))->map(function ($daysAgo) use ($salesDate) {
            $date = $salesDate->copy()->subDays($daysAgo);

            $dailyTotal = OrderRecord::whereBetween('completed_at', [
                $date->copy()->startOfDay(),
                $date->copy()->endOfDay()
            ])->sum('total');

            return [
                'day' => $date->format('D'),
                'total' => $dailyTotal,
            ];
        });

        $maxSales = $weeklySalesData->max('total');

        if ($maxSales <= 0) {
            $maxSales = 100;
        }

        $avgPrepTime = OrderRecord::whereNotNull('completed_at')
            ->get()
            ->avg(function ($record) {
                return $record->created_at->diffInMinutes($record->completed_at);
            });

        $avgPrepTime = $avgPrepTime ? round($avgPrepTime) : 0;

        $salesDateLabel = $salesDate->format('M d, Y');

        return view('cashier.index', compact(
            'activeOrdersCount',
            'todaysSales',
            'lowStockCount',
            'weeklySalesData',
            'maxSales',
            'avgPrepTime',
            'salesDateLabel'
        ));
    }
}