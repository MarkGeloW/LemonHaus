@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-[#f6f7fb]">
    @include('kitchen.partials.sidebar')

    <main class="flex-1 px-10 py-10 relative">
        <div class="max-w-[1440px] mx-auto">
            
            @if(session('success'))
                <div class="mb-6 rounded-xl bg-green-100 px-5 py-4 text-green-700 font-semibold shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex justify-between items-start mb-8">
                <div>
                    <h1 class="text-[38px] font-extrabold text-[#0f172a]">Inventory Status</h1>
                    <p class="text-[18px] text-[#64748b] mt-2">View current stock levels and add new arrivals</p>
                </div>
                
                <a href="{{ route('kitchen.inventory.create') }}" class="inline-flex items-center gap-2 bg-[#f4c400] hover:bg-[#eab308] text-slate-900 font-bold px-7 py-4 rounded-2xl text-[17px] transition shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                    Add New Stock
                </a>
            </div>

            <div class="grid grid-cols-3 gap-5 mb-7">
                <div class="bg-white rounded-[22px] border border-[#e9edf3] shadow-sm p-7">
                    <p class="text-[16px] text-[#64748b] font-medium">Total Items</p>
                    <h2 class="text-[28px] font-extrabold text-[#0f172a] mt-2">{{ $items->count() }}</h2>
                </div>
                
                <div class="bg-white rounded-[22px] border border-[#e9edf3] shadow-sm p-7">
                    <p class="text-[16px] text-[#64748b] font-medium">Low Stock Items</p>
                    <h2 class="text-[28px] font-extrabold text-orange-500 mt-2">{{ $items->filter(fn($i) => $i->stock_level <= $i->min_stock && $i->stock_level > 0)->count() }}</h2>
                </div>
                
                <div class="bg-white rounded-[22px] border border-[#e9edf3] shadow-sm p-7">
                    <p class="text-[16px] text-[#64748b] font-medium">Out of Stock</p>
                    <h2 class="text-[28px] font-extrabold text-red-500 mt-2">{{ $items->filter(fn($i) => $i->stock_level <= 0)->count() }}</h2>
                </div>
            </div>

            <div class="bg-white rounded-[24px] border border-[#e9edf3] overflow-hidden shadow-sm">
                <table class="w-full text-left">
                    <thead class="bg-[#f8fafc] text-[#64748b]">
                        <tr>
                            <th class="px-8 py-6 font-semibold">ITEM NAME</th>
                            <th class="px-8 py-6 font-semibold">CATEGORY</th>
                            <th class="px-8 py-6 font-semibold">STOCK LEVEL</th>
                            <th class="px-8 py-6 font-semibold">MIN. REQUIRED</th>
                            <th class="px-8 py-6 font-semibold text-right">STATUS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#eef2f7]">
                        @forelse($items as $item)
                            @php
                                $isOut = $item->stock_level <= 0;
                                $isLow = !$isOut && $item->stock_level <= $item->min_stock;
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-8 py-5 font-bold text-[#0f172a]">{{ $item->name }}</td>
                                <td class="px-8 py-5 text-[#475569] font-medium">{{ $item->category }}</td>
                                <td class="px-8 py-5 font-bold {{ $isOut ? 'text-red-500' : ($isLow ? 'text-orange-500' : 'text-[#0f172a]') }}">
                                    {{ $item->stock_level }} <span class="text-sm font-normal text-[#64748b]">{{ $item->unit }}</span>
                                </td>
                                <td class="px-8 py-5 text-[#64748b]">{{ $item->min_stock }} {{ $item->unit }}</td>
                                <td class="px-8 py-5 text-right">
                                    @if($isOut)
                                        <span class="px-4 py-1.5 rounded-full text-[13px] bg-red-100 text-red-700 font-bold tracking-wide">Out of Stock</span>
                                    @elseif($isLow)
                                        <span class="px-4 py-1.5 rounded-full text-[13px] bg-orange-100 text-orange-700 font-bold tracking-wide">Low Stock</span>
                                    @else
                                        <span class="px-4 py-1.5 rounded-full text-[13px] bg-green-100 text-green-700 font-bold tracking-wide">In Stock</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-8 py-10 text-center text-[#64748b]">No inventory items found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
@endsection