@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-[#f6f7fb]">

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
                        Sales performance and inventory insights
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <button class="inline-flex items-center gap-3 rounded-2xl border border-[#e5e7eb] bg-white px-6 py-4 text-[18px] font-semibold text-[#334155] shadow-sm hover:bg-slate-50 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.9">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2Z"/>
                        </svg>
                        Last 7 Days
                    </button>

                   <button class="inline-flex items-center gap-3 rounded-2xl bg-[#f4c400] px-7 py-4 text-[18px] font-semibold text-white shadow-md hover:bg-[#eab308] transition duration-200">
    <svg xmlns="http://www.w3.org/2000/svg"
         class="w-5 h-5 text-white"
         fill="none"
         viewBox="0 0 24 24"
         stroke="currentColor"
         stroke-width="1.9">
        <path stroke-linecap="round"
              stroke-linejoin="round"
              d="M12 16V4m0 12 4-4m-4 4-4-4M4 20h16"/>
    </svg>

    Export CSV
</button>
                </div>
            </div>

            <!-- Top cards -->
            <div class="grid grid-cols-3 gap-5 mb-7">
                <div class="bg-white rounded-[22px] border border-[#e9edf3] shadow-sm p-7">
                    <p class="text-[16px] text-[#64748b] font-medium">Total Revenue (Weekly)</p>
                    <h2 class="text-[28px] font-extrabold text-[#0f172a] mt-3">$14,000</h2>
                    <p class="text-[16px] text-[#16a34a] mt-3">+12.5% vs last week</p>
                </div>

                <div class="bg-white rounded-[22px] border border-[#e9edf3] shadow-sm p-7">
                    <p class="text-[16px] text-[#64748b] font-medium">Total Orders</p>
                    <h2 class="text-[28px] font-extrabold text-[#0f172a] mt-3">496</h2>
                    <p class="text-[16px] text-[#16a34a] mt-3">+8.2% vs last week</p>
                </div>

                <div class="bg-white rounded-[22px] border border-[#e9edf3] shadow-sm p-7">
                    <p class="text-[16px] text-[#64748b] font-medium">Avg. Order Value</p>
                    <h2 class="text-[28px] font-extrabold text-[#0f172a] mt-3">$28.22</h2>
                    <p class="text-[16px] text-[#ef4444] mt-3">-2.1% vs last week</p>
                </div>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 gap-6 items-start">

                <!-- Top Selling Items -->
                <div class="bg-white rounded-[24px] border border-[#e9edf3] shadow-sm p-7 overflow-hidden">
                    <h2 class="text-[22px] font-bold text-[#0f172a] mb-6">Top Selling Items</h2>

                    <div class="flex flex-col items-center justify-center h-[360px]">
                        <div class="relative w-[220px] h-[220px] mb-10">
                            <svg viewBox="0 0 36 36" class="w-full h-full -rotate-90">
                                <circle cx="18" cy="18" r="12" fill="transparent" stroke="#f4c400" stroke-width="6"
                                        stroke-dasharray="43 100" stroke-dashoffset="0"></circle>
                                <circle cx="18" cy="18" r="12" fill="transparent" stroke="#ec4899" stroke-width="6"
                                        stroke-dasharray="15 100" stroke-dashoffset="-45"></circle>
                                <circle cx="18" cy="18" r="12" fill="transparent" stroke="#10b981" stroke-width="6"
                                        stroke-dasharray="36 100" stroke-dashoffset="-62"></circle>
                            </svg>
                            <div class="absolute inset-[48px] rounded-full bg-white"></div>
                        </div>

                        <div class="flex flex-wrap justify-center gap-x-6 gap-y-3 text-[13px] font-medium text-slate-600">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-[#f4c400]"></div>
                                <span>Classic Lemonade</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-[#ec4899]"></div>
                                <span>Strawberry Lemonade</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-[#10b981]"></div>
                                <span>Mint Lemonade</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </main>
</div>
@endsection