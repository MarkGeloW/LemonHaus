@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-[#f6f7fb]">

    {{-- SHARED SIDEBAR --}}
    @include('admin.partials.sidebar')

    <main class="flex-1 px-10 py-8">
        <div class="max-w-[1430px]">

            <!-- Header -->
            <div class="flex items-start justify-between mb-8">
                <div>
                    <h1 class="text-[38px] font-extrabold leading-none text-[#0f172a]">
                        Reports & Analytics
                    </h1>
                    <p class="text-[18px] text-[#64748b] mt-3">
                        Sales performance and inventory insights (Last {{ $days }} Days)
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <!-- DATE FILTER -->
                    <form action="{{ route('reports.index') }}" method="GET" class="inline-flex items-center gap-2 rounded-2xl border border-[#e5e7eb] bg-white px-5 py-2 shadow-sm hover:bg-slate-50 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#64748b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.9">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2Z"/>
                        </svg>
                        <select name="days" onchange="this.form.submit()" class="bg-transparent border-none text-[16px] font-semibold text-[#334155] focus:ring-0 cursor-pointer outline-none py-2">
                            <option value="7" {{ $days == 7 ? 'selected' : '' }}>Last 7 Days</option>
                            <option value="14" {{ $days == 14 ? 'selected' : '' }}>Last 14 Days</option>
                            <option value="30" {{ $days == 30 ? 'selected' : '' }}>Last 30 Days</option>
                        </select>
                    </form>

                    <!-- EXPORT CSV -->
                    <a href="{{ route('reports.export', ['days' => $days]) }}" class="inline-flex items-center gap-3 rounded-2xl bg-[#f4c400] px-7 py-4 text-[18px] font-semibold text-white shadow-md hover:bg-[#eab308] transition duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.9">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16V4m0 12 4-4m-4 4-4-4M4 20h16"/>
                        </svg>
                        Export CSV
                    </a>
                </div>
            </div>

            <!-- Top cards -->
            <div class="grid grid-cols-3 gap-5 mb-7">
                <div class="bg-white rounded-[22px] border border-[#e9edf3] shadow-sm p-7">
                    <p class="text-[16px] text-[#64748b] font-medium">Total Revenue</p>
                    <h2 class="text-[28px] font-extrabold text-[#0f172a] mt-3">₱{{ number_format($totalRevenue, 2) }}</h2>
                </div>

                <div class="bg-white rounded-[22px] border border-[#e9edf3] shadow-sm p-7">
                    <p class="text-[16px] text-[#64748b] font-medium">Total Orders</p>
                    <h2 class="text-[28px] font-extrabold text-[#0f172a] mt-3">{{ number_format($totalOrders) }}</h2>
                </div>

                <div class="bg-white rounded-[22px] border border-[#e9edf3] shadow-sm p-7">
                    <p class="text-[16px] text-[#64748b] font-medium">Avg. Order Value</p>
                    <h2 class="text-[28px] font-extrabold text-[#0f172a] mt-3">₱{{ number_format($avgOrderValue, 2) }}</h2>
                </div>
            </div>

            <!-- Charts Area -->
            <div class="grid grid-cols-[1.9fr_1fr] gap-6 items-start">

                <!-- Sales Overview (Bar Chart) -->
                <div class="bg-white rounded-[24px] border border-[#e9edf3] shadow-sm p-7 overflow-x-auto">
                    <h2 class="text-[22px] font-bold text-[#0f172a] mb-8">Sales Overview</h2>

                    <div class="relative h-[360px] min-w-[600px]">
                        @php
                            $topAxis = ceil($maxSales / 100) * 100;
                            if($topAxis < 100) $topAxis = 100; 
                            
                            $barWidthClass = $days > 14 ? 'w-[14px] md:w-[20px] lg:w-[28px]' : 'w-[40px] md:w-[60px] lg:w-[88px]';
                            $fontSizeClass = $days > 14 ? 'text-[10px] md:text-[12px]' : 'text-[14px] md:text-[16px]';
                        @endphp

                        <!-- Y-Axis Lines -->
                        <div class="absolute inset-0 flex flex-col justify-between text-[#94a3b8] text-[13px] pointer-events-none">
                            <div class="border-b border-dashed border-[#d1d5db] pb-1 pl-12">₱{{ number_format($topAxis) }}</div>
                            <div class="border-b border-dashed border-[#d1d5db] pb-1 pl-12">${{ number_format($topAxis * 0.75) }}</div>
                            <div class="border-b border-dashed border-[#d1d5db] pb-1 pl-12">${{ number_format($topAxis * 0.50) }}</div>
                            <div class="border-b border-dashed border-[#d1d5db] pb-1 pl-12">${{ number_format($topAxis * 0.25) }}</div>
                            <div class="pl-12">$0</div>
                        </div>

                        <!-- Dynamic Bars -->
                        <div class="absolute inset-0 flex items-end justify-between px-12 pb-10 pt-10">
                            @foreach($weeklySalesData as $bar)
                                <div class="flex flex-col items-center justify-end gap-3 group relative h-full flex-1">
                                    <div class="absolute -top-10 opacity-0 group-hover:opacity-100 transition-opacity bg-[#0f172a] text-white text-xs py-1.5 px-3 rounded-lg pointer-events-none whitespace-nowrap shadow-lg z-10">
                                        {{ $bar['day'] }}: ₱{{ number_format($bar['total'], 2) }}
                                    </div>
                                    <div style="height: {{ ($bar['total'] / $topAxis) * 270 }}px; min-height: 4px;" 
                                         class="{{ $barWidthClass }} bg-[#f4c400] rounded-[4px] transition-all duration-300 hover:bg-[#eab308] cursor-pointer">
                                    </div>
                                    <span class="{{ $fontSizeClass }} text-[#64748b] font-medium absolute -bottom-8 whitespace-nowrap">{{ $bar['day'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Top Selling Items (Donut Chart) -->
                <div class="bg-white rounded-[24px] border border-[#e9edf3] shadow-sm p-7 h-full flex flex-col">
                    <h2 class="text-[22px] font-bold text-[#0f172a] mb-6">Top Selling Items</h2>

                    @if($topItems->isEmpty())
                        <div class="flex-1 flex items-center justify-center text-[#94a3b8] text-[16px]">
                            No sales data available yet.
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center flex-1">
                            <div class="relative w-[220px] h-[220px] mb-10">
                                
                                @php
                                    $circumference = 75.398;
                                    $totalTopQty = $topItems->sum('total_quantity');
                                    $colors = ['#f4c400', '#ec4899', '#10b981'];
                                    $currentOffset = 0;
                                @endphp

                                <svg viewBox="0 0 36 36" class="w-full h-full -rotate-90 drop-shadow-sm">
                                    @foreach($topItems as $index => $item)
                                        @php
                                            $percent = ($item->total_quantity / $totalTopQty) * 100;
                                            $dashLength = ($percent / 100) * $circumference;
                                        @endphp
                                        <circle cx="18" cy="18" r="12" fill="transparent" 
                                                stroke="{{ $colors[$index % count($colors)] }}" stroke-width="6"
                                                stroke-dasharray="{{ $dashLength }} {{ $circumference }}" 
                                                stroke-dashoffset="{{ $currentOffset }}"></circle>
                                        @php
                                            $currentOffset -= $dashLength;
                                        @endphp
                                    @endforeach
                                </svg>
                                
                                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                    <span class="text-[28px] font-extrabold text-[#0f172a]">{{ $totalTopQty }}</span>
                                    <span class="text-[13px] text-[#64748b] font-medium -mt-1">Items Sold</span>
                                </div>
                            </div>

                            <!-- Legend -->
                            <div class="w-full space-y-4">
                                @foreach($topItems as $index => $item)
                                    <div class="flex items-center justify-between text-[15px] font-medium">
                                        <div class="flex items-center gap-3">
                                            <div class="w-4 h-4 rounded-md" style="background-color: {{ $colors[$index % count($colors)] }}"></div>
                                            <span class="text-[#334155]">{{ $item->product_name }}</span>
                                        </div>
                                        <span class="text-[#0f172a] font-bold">{{ $item->total_quantity }} qty</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </main>
</div>
@endsection