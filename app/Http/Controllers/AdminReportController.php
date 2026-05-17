<?php

namespace App\Http\Controllers;

use App\Models\OrderRecord;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        $days = (int) $request->query('days', 7);

        if (!in_array($days, [7, 14, 30])) {
            $days = 7;
        }

        $startDate = today()->subDays($days - 1)->startOfDay();
        $endDate = now();

        $recordsQuery = OrderRecord::whereBetween('completed_at', [$startDate, $endDate]);

        $totalRevenue = (clone $recordsQuery)->sum('total');
        $totalOrders = (clone $recordsQuery)->count();
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        $weeklySalesData = collect(range($days - 1, 0))->map(function ($daysAgo) {
            $date = today()->subDays($daysAgo);

            $dailyTotal = OrderRecord::whereDate('completed_at', $date)->sum('total');

            return [
                'day' => $date->format('M d'),
                'total' => $dailyTotal,
            ];
        });

        $maxSales = $weeklySalesData->max('total');

        if ($maxSales <= 0) {
            $maxSales = 100;
        }

        $topItems = OrderRecord::whereBetween('completed_at', [$startDate, $endDate])
            ->selectRaw('product_name, SUM(quantity) as total_quantity')
            ->groupBy('product_name')
            ->orderByDesc('total_quantity')
            ->limit(3)
            ->get();

        return view('admin.reports', compact(
            'days',
            'totalRevenue',
            'totalOrders',
            'avgOrderValue',
            'weeklySalesData',
            'maxSales',
            'topItems'
        ));
    }

    public function export(Request $request): StreamedResponse
    {
        $days = (int) $request->query('days', 7);

        if (!in_array($days, [7, 14, 30])) {
            $days = 7;
        }

        $startDate = today()->subDays($days - 1)->startOfDay();
        $endDate = now();

        $records = OrderRecord::whereBetween('completed_at', [$startDate, $endDate])
            ->orderByDesc('completed_at')
            ->get();

        $filename = 'lemonhaus_sales_last_' . $days . '_days.csv';

        return response()->streamDownload(function () use ($records) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Record ID',
                'Original Order ID',
                'Customer Name',
                'Product',
                'Quantity',
                'Price',
                'Total',
                'Status',
                
            ]);

            foreach ($records as $record) {
                fputcsv($handle, [
                    $record->id,
                    $record->order_id,
                    $record->customer_name,
                    $record->product_name,
                    $record->quantity,
                    $record->price,
                    $record->total,
                    $record->status,
                   
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}