@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-[#f6f7fb]">

    @if(auth()->user()->role === 'admin')
        @include('admin.partials.sidebar')
    @elseif(auth()->user()->role === 'cashier')
        @include('cashier.partials.sidebar')
    @elseif(auth()->user()->role === 'kitchen')
        @include('kitchen.partials.sidebar')
    @else
        @include('admin.partials.sidebar')
    @endif

    <main class="flex-1 w-full px-4 sm:px-6 lg:px-10 pt-[96px] lg:pt-8 pb-10 overflow-x-hidden">
        <div class="max-w-[1420px] mx-auto">

            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-[30px] sm:text-[38px] font-extrabold leading-none text-[#0f172a]">
                        Dashboard
                    </h1>
                </div>

                <p class="text-[14px] sm:text-[16px] text-[#64748b]">
                    Last updated: {{ now()->format('M d, Y g:i A') }}
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-7">

                <div class="bg-white rounded-[22px] border border-[#e9edf3] shadow-sm p-5 sm:p-7">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-[15px] sm:text-[16px] text-[#64748b] font-medium">
                                Sales on {{ $salesDateLabel ?? now()->format('M d, Y') }}
                            </p>

                            <h2 class="text-[24px] sm:text-[28px] font-extrabold text-[#0f172a] mt-2 break-words">
                                ₱{{ number_format($todaysSales ?? 0, 2) }}
                            </h2>
                        </div>

                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-[#edf7ee] flex items-center justify-center text-[#16a34a] text-[20px] font-bold shrink-0">
                            ₱
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[22px] border border-[#e9edf3] shadow-sm p-5 sm:p-7">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-[15px] sm:text-[16px] text-[#64748b] font-medium">
                                Active Orders
                            </p>

                            <h2 class="text-[24px] sm:text-[28px] font-extrabold text-[#0f172a] mt-2">
                                {{ $activeOrdersCount ?? 0 }}
                            </h2>
                        </div>

                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-[#eef4ff] flex items-center justify-center text-[#2563eb] shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 sm:w-7 sm:h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.9">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2m0 0 1.6 8h10.6l1.6-6H6.2M6 5h14M9 19a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm8 1a1 1 0 1 1 0-2 1 1 0 0 1 0 2Z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[22px] border border-[#e9edf3] shadow-sm p-5 sm:p-7">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-[15px] sm:text-[16px] text-[#64748b] font-medium">
                                Low Stock Items
                            </p>

                            <h2 class="text-[24px] sm:text-[28px] font-extrabold text-[#0f172a] mt-2">
                                {{ $lowStockCount ?? 0 }}
                            </h2>
                        </div>

                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-[#fff4eb] flex items-center justify-center text-[#f97316] shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 sm:w-7 sm:h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.9">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m20 7-8-4-8 4m16 0-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[22px] border border-[#e9edf3] shadow-sm p-5 sm:p-7">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-[15px] sm:text-[16px] text-[#64748b] font-medium">
                                Avg Prep Time
                            </p>

                            <h2 class="text-[24px] sm:text-[28px] font-extrabold text-[#0f172a] mt-2">
                                {{ $avgPrepTime ?? 0 }}m
                            </h2>
                        </div>

                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-[#f7efff] flex items-center justify-center text-[#9333ea] shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 sm:w-7 sm:h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.9">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z"/>
                            </svg>
                        </div>
                    </div>
                </div>

            </div>

            <div class="grid grid-cols-1 2xl:grid-cols-[1.9fr_0.9fr] gap-6">

                <div class="bg-white rounded-[24px] border border-[#e9edf3] shadow-sm p-5 sm:p-7 overflow-hidden">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-8">
                        <h2 class="text-[20px] sm:text-[22px] font-bold text-[#0f172a]">
                            Weekly Sales Overview
                        </h2>

                        <p class="text-sm text-[#64748b]">
                            Based on completed order records
                        </p>
                    </div>

                    <div class="overflow-x-auto pb-2">
                        <div class="relative h-[340px] sm:h-[380px] min-w-[720px]">
                            @php
                                $topAxis = ceil(($maxSales ?? 100) / 100) * 100;

                                if ($topAxis < 100) {
                                    $topAxis = 100;
                                }
                            @endphp

                            <div class="absolute inset-0 flex flex-col justify-between text-[#94a3b8] text-[13px]">
                                <div class="border-b border-dashed border-[#d1d5db] pb-1 pl-10">
                                    ₱{{ number_format($topAxis) }}
                                </div>

                                <div class="border-b border-dashed border-[#d1d5db] pb-1 pl-10">
                                    ₱{{ number_format($topAxis * 0.75) }}
                                </div>

                                <div class="border-b border-dashed border-[#d1d5db] pb-1 pl-10">
                                    ₱{{ number_format($topAxis * 0.50) }}
                                </div>

                                <div class="border-b border-dashed border-[#d1d5db] pb-1 pl-10">
                                    ₱{{ number_format($topAxis * 0.25) }}
                                </div>

                                <div class="pl-10">
                                    ₱0
                                </div>
                            </div>

                            <div class="absolute inset-0 flex items-end justify-between px-10 lg:px-16 pb-10 pt-10">
                                @forelse($weeklySalesData ?? collect() as $bar)
                                    <div class="flex flex-col items-center justify-end gap-3 group relative w-full">
                                        <div class="absolute -top-10 opacity-0 group-hover:opacity-100 transition-opacity bg-[#0f172a] text-white text-xs py-1.5 px-3 rounded-lg pointer-events-none whitespace-nowrap shadow-lg z-10">
                                            ₱{{ number_format($bar['total'], 2) }}
                                        </div>

                                        <div style="height: {{ ($bar['total'] / $topAxis) * 260 }}px; min-height: 4px;"
                                             class="w-[42px] sm:w-[58px] lg:w-[78px] bg-[#f4c400] rounded-[6px] transition-all duration-300 hover:bg-[#eab308] cursor-pointer">
                                        </div>

                                        <span class="text-[14px] sm:text-[16px] text-[#64748b] font-medium">
                                            {{ $bar['day'] }}
                                        </span>
                                    </div>
                                @empty
                                    <div class="w-full h-full flex items-center justify-center text-[#94a3b8] text-[16px]">
                                        No sales data yet.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[24px] border border-[#e9edf3] shadow-sm p-5 sm:p-7">
                    <h2 class="text-[20px] sm:text-[22px] font-bold text-[#0f172a] mb-6">
                        Low Stock Alerts
                    </h2>

                    @if(($lowStockItems ?? collect())->isEmpty())
                        <p class="text-[15px] sm:text-[16px] text-[#16a34a] mb-10 sm:mb-12 font-semibold">
                            All inventory levels are healthy.
                        </p>
                    @else
                        <div class="space-y-4 mb-10">
                            @foreach($lowStockItems as $item)
                                <div class="rounded-2xl bg-orange-50 border border-orange-100 px-4 py-3">
                                    <p class="font-bold text-[#0f172a]">
                                        {{ $item->name }}
                                    </p>

                                    <p class="text-sm text-orange-700">
                                        Stock: {{ $item->stock_level }} {{ $item->unit }} | Min: {{ $item->min_stock }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <h3 class="text-[18px] sm:text-[20px] font-bold text-[#0f172a] mb-5">
                        Recent Activity
                    </h3>

                    <div class="space-y-5">
                        @forelse($recentRecords ?? collect() as $record)
                            <div class="flex items-start gap-4">
                                <div class="w-3 h-3 rounded-full bg-[#16a34a] mt-2 shrink-0"></div>

                                <div class="min-w-0">
                                    <p class="text-[16px] sm:text-[18px] font-semibold text-[#0f172a]">
                                        Order Recorded
                                    </p>

                                    <p class="text-[15px] sm:text-[16px] text-[#64748b] break-words">
                                        {{ $record->customer_name }} ordered {{ $record->quantity }}x {{ $record->product_name }}
                                    </p>

                                    <p class="text-[13px] sm:text-[14px] text-[#94a3b8] mt-1">
                                        {{ $record->completed_at ? $record->completed_at->diffForHumans() : $record->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="flex items-start gap-4">
                                <div class="w-3 h-3 rounded-full bg-[#d1d5db] mt-2 shrink-0"></div>

                                <div class="min-w-0">
                                    <p class="text-[16px] sm:text-[18px] font-semibold text-[#0f172a]">
                                        Login
                                    </p>

                                    <p class="text-[15px] sm:text-[16px] text-[#64748b] break-words">
                                        {{ auth()->user()->name }} logged in
                                    </p>

                                    <p class="text-[13px] sm:text-[14px] text-[#94a3b8] mt-1">
                                        Just now
                                    </p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>
    </main>
</div>
@endsection