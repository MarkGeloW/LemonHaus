@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-[#f6f7fb]">
    @include('admin.partials.sidebar')

    <main class="flex-1 px-10 py-10">
        <div class="max-w-[1440px]">
            @if(session('success'))
                <div class="mb-5 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex justify-between items-start mb-8">
                <div>
                    <h1 class="text-[38px] font-extrabold text-[#0f172a]">Inventory Management</h1>
                    <p class="text-[18px] text-[#64748b] mt-2">Track stock levels and ingredient expiration dates</p>
                </div>

                <a href="{{ route('inventory.create') }}" class="bg-[#f4c400] hover:bg-[#eab308] text-black font-semibold px-7 py-4 rounded-2xl text-[18px] transition shadow-sm">
                    + Add New Item
                </a>
            </div>

            <div class="grid grid-cols-4 gap-5 mb-7">
                <div class="bg-white rounded-[22px] border border-[#e9edf3] shadow-sm p-7">
                    <p class="text-[16px] text-[#64748b] font-medium">Total Items</p>
                    <h2 class="text-[28px] font-extrabold text-[#0f172a] mt-2">{{ $items->count() }}</h2>
                </div>
                <div class="bg-white rounded-[22px] border border-[#e9edf3] shadow-sm p-7">
                    <p class="text-[16px] text-[#64748b] font-medium">Expiring Soon</p>
                    <h2 class="text-[28px] font-extrabold text-orange-500 mt-2">
                        {{ $items->filter(fn($i) => now()->diffInDays($i->expiration_date, false) <= 7 && now()->diffInDays($i->expiration_date, false) >= 0)->count() }}
                    </h2>
                </div>
            </div>

            <div class="bg-white rounded-[24px] border border-[#e9edf3] overflow-hidden shadow-sm">
                <table class="w-full">
                    <thead class="bg-[#f8fafc] text-[#64748b] text-left">
                        <tr>
                            <th class="px-8 py-6">ITEM NAME</th>
                            <th class="px-8 py-6">CATEGORY</th>
                            <th class="px-8 py-6">STOCK LEVEL</th>
                            <th class="px-8 py-6">DATE ADDED</th>
                            <th class="px-8 py-6">EXPIRATION</th>
                            <th class="px-8 py-6">STATUS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#eef2f7]">
                        @foreach($items as $item)
                        @php
                            $days = now()->diffInDays($item->expiration_date, false);
                            $isExpired = $days < 0;
                            $isSoon = !$isExpired && $days <= 7;
                        @endphp
                        <tr>
                            <td class="px-8 py-6 font-bold text-[#0f172a]">{{ $item->name }}</td>
                            <td class="px-8 py-6">{{ $item->category }}</td>
                            <td class="px-8 py-6 font-bold">{{ $item->stock_level }} <span class="text-gray-400 font-normal">{{ $item->unit }}</span></td>
                            <td class="px-8 py-6 text-gray-500">{{ $item->date_added->format('M d, Y') }}</td>
                            <td class="px-8 py-6 font-bold {{ $isExpired ? 'text-red-500' : ($isSoon ? 'text-orange-500' : '') }}">
                                {{ $item->expiration_date->format('M d, Y') }}
                            </td>
                            <td class="px-8 py-6">
                                @if($isExpired)
                                    <span class="px-4 py-1 rounded-full bg-red-100 text-red-700 font-medium">Expired</span>
                                @elseif($isSoon)
                                    <span class="px-4 py-1 rounded-full bg-orange-100 text-orange-700 font-medium">Expiring Soon</span>
                                @else
                                    <span class="px-4 py-1 rounded-full bg-green-100 text-green-700 font-medium">Good</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
@endsection