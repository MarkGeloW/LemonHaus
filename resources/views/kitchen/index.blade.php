resources/views/kitchen/index.blade.php
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-[#f6f7fb]">

    {{-- SHARED SIDEBAR --}}
    @include('kitchen.partials.sidebar')

    <!-- Main -->
    <main class="flex-1 px-10 pt-8 pb-10">
        <div class="max-w-[1420px]">

            <!-- Top Header -->
            <div class="flex items-start justify-between mb-8">
                <div>
                    <h1 class="text-[38px] font-extrabold leading-none text-[#0f172a]">Dashboard</h1>
                </div>

                <p class="text-[16px] text-[#64748b]">
                    Last updated: {{ now()->format('M d, Y g:i A') }}
                </p>
            </div>

            <!-- Top Cards -->
            <div class="grid grid-cols-4 gap-5 mb-7">

                <div class="bg-white rounded-[22px] border border-[#e9edf3] shadow-sm p-7">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-[16px] text-[#64748b] font-medium">Today's Sales</p>
                            <h2 class="text-[28px] font-extrabold text-[#0f172a] mt-2">${{ number_format($todaysSales, 2) }}</h2>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-[#edf7ee] flex items-center justify-center text-[#16a34a] text-[20px] font-bold">
                            $
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[22px] border border-[#e9edf3] shadow-sm p-7">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-[16px] text-[#64748b] font-medium">Active Orders</p>
                            <h2 class="text-[28px] font-extrabold text-[#0f172a] mt-2">{{ $activeOrdersCount }}</h2>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-[#eef4ff] flex items-center justify-center text-[#2563eb]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.9">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2m0 0 1.6 8h10.6l1.6-6H6.2M6 5h14M9 19a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm8 1a1 1 0 1 1 0-2 1 1 0 0 1 0 2Z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[22px] border border-[#e9edf3] shadow-sm p-7">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-[16px] text-[#64748b] font-medium">Low Stock Items</p>
                            <h2 class="text-[28px] font-extrabold text-[#0f172a] mt-2">{{ $lowStockCount }}</h2>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-[#fff4eb] flex items-center justify-center text-[#f97316]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.9">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m20 7-8-4-8 4m16 0-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[22px] border border-[#e9edf3] shadow-sm p-7">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-[16px] text-[#64748b] font-medium">Avg Prep Time</p>
                            <h2 class="text-[28px] font-extrabold text-[#0f172a] mt-2">12m</h2>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-[#f7efff] flex items-center justify-center text-[#9333ea]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.9">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z"/>
                            </svg>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Bottom Section -->
            <div class="grid grid-cols-[1.9fr_0.9fr] gap-6">

                <!-- Weekly Sales Overview -->
                <div class="bg-white rounded-[24px] border border-[#e9edf3] shadow-sm p-7">
                    <h2 class="text-[22px] font-bold text-[#0f172a] mb-8">Weekly Sales Overview</h2>

                    <div class="relative h-[380px]">
                        @php
                            // Calculate dynamic Y-axis labels
                            $topAxis = ceil($maxSales / 100) * 100;
                            if($topAxis < 100) $topAxis = 100; 
                        @endphp

                        <!-- Dynamic Y-Axis Lines -->
                        <div class="absolute inset-0 flex flex-col justify-between text-[#94a3b8] text-[13px]">
                            <div class="border-b border-dashed border-[#d1d5db] pb-1 pl-10">${{ number_format($topAxis) }}</div>
                            <div class="border-b border-dashed border-[#d1d5db] pb-1 pl-10">${{ number_format($topAxis * 0.75) }}</div>
                            <div class="border-b border-dashed border-[#d1d5db] pb-1 pl-10">${{ number_format($topAxis * 0.50) }}</div>
                            <div class="border-b border-dashed border-[#d1d5db] pb-1 pl-10">${{ number_format($topAxis * 0.25) }}</div>
                            <div class="pl-10">$0</div>
                        </div>

                        <!-- Dynamic Bars -->
                        <div class="absolute inset-0 flex items-end justify-between px-10 lg:px-20 pb-10 pt-10">
                            @foreach($weeklySalesData as $bar)
                                <div class="flex flex-col items-center justify-end gap-3 group relative w-full">
                                    <div class="absolute -top-10 opacity-0 group-hover:opacity-100 transition-opacity bg-[#0f172a] text-white text-xs py-1.5 px-3 rounded-lg pointer-events-none whitespace-nowrap shadow-lg z-10">
                                        ${{ number_format($bar['total'], 2) }}
                                    </div>
                                    <div style="height: {{ ($bar['total'] / $topAxis) * 280 }}px; min-height: 4px;" 
                                         class="w-[40px] md:w-[60px] lg:w-[88px] bg-[#f4c400] rounded-[6px] transition-all duration-300 hover:bg-[#eab308] cursor-pointer">
                                    </div>
                                    <span class="text-[14px] md:text-[16px] text-[#64748b] font-medium">{{ $bar['day'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right Panel -->
                <div class="bg-white rounded-[24px] border border-[#e9edf3] shadow-sm p-7">
                    <h2 class="text-[22px] font-bold text-[#0f172a] mb-6">Low Stock Alerts</h2>
                    <p class="text-[16px] text-[#64748b] mb-12">Inventory levels are being monitored.</p>

                    <h3 class="text-[20px] font-bold text-[#0f172a] mb-5">Recent Activity</h3>

                    <div class="flex items-start gap-4">
                        <div class="w-3 h-3 rounded-full bg-[#d1d5db] mt-2"></div>
                        <div>
                            <p class="text-[18px] font-semibold text-[#0f172a]">Login</p>
                            <p class="text-[16px] text-[#64748b]">User logged in</p>
                            <p class="text-[14px] text-[#94a3b8] mt-1">Just now</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </main>
</div>
@endsection