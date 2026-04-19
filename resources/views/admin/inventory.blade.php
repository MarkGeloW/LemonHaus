{{-- resources/views/admin/inventory.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-[#f8fafc]">

    {{-- Sidebar --}}
    @include('admin.partials.sidebar')

    {{-- Main Content --}}
    <main class="flex-1 px-8 py-8">
        <div class="max-w-[1400px] mx-auto">

            {{-- Header --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-[#0f172a] tracking-tight">
                        Inventory Management
                    </h1>
                    <p class="text-slate-500 mt-1 font-medium">
                        Track stock levels and manage ingredients
                    </p>
                </div>

                <button class="bg-[#f4c400] hover:bg-[#eab308] text-white font-bold px-6 py-3 rounded-xl flex items-center gap-2 transition shadow-sm">
                    <span class="text-xl leading-none">+</span> Add New Item
                </button>
            </div>

            {{-- Search + Summary Cards --}}
            <div class="flex gap-4 mb-6">
                {{-- Search Bar --}}
                <div class="flex-1 bg-white h-[72px] rounded-2xl border border-slate-200 shadow-sm px-6 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-400 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 6.04 6.04a7.5 7.5 0 0 0 10.61 10.61Z"/>
                    </svg>
                    <input type="text" placeholder="Search items by name or category..." class="w-full text-[16px] text-slate-600 bg-transparent focus:outline-none placeholder:text-slate-400">
                </div>

                {{-- In Stock Card --}}
                <div class="w-64 bg-[#ecfdf5] h-[72px] rounded-2xl border border-[#d1fae5] shadow-sm px-5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-[#10b981] shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-[#065f46] leading-tight">In Stock</h3>
                            <p class="text-[12px] text-[#10b981] font-medium">Items available</p>
                        </div>
                    </div>
                    <span class="text-2xl font-bold text-[#065f46]">6</span>
                </div>

                {{-- Low Stock Card --}}
                <div class="w-64 bg-[#fff7ed] h-[72px] rounded-2xl border border-[#ffedd5] shadow-sm px-5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-[#f97316] shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-[#9a3412] leading-tight">Low Stock</h3>
                            <p class="text-[12px] text-[#ea580c] font-medium">Restock needed</p>
                        </div>
                    </div>
                    <span class="text-2xl font-bold text-[#9a3412]">0</span>
                </div>
            </div>

            {{-- Table Container --}}
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="text-slate-500 text-[13px] font-bold uppercase tracking-wider border-b border-slate-100">
                            <th class="px-6 py-5 text-left font-semibold">Item Name</th>
                            <th class="px-6 py-5 text-left font-semibold">Category</th>
                            <th class="px-6 py-5 text-left font-semibold">Stock Level</th>
                            <th class="px-6 py-5 text-left font-semibold">Unit</th>
                            <th class="px-6 py-5 text-left font-semibold">Min. Stock</th>
                            <th class="px-6 py-5 text-left font-semibold">Status</th>
                            <th class="px-6 py-5 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @php
                            $items = [
                                ['Lemons','Ingredients','150','pcs','50', 'text-green-600'],
                                ['Sugar','Ingredients','20','kg','5', 'text-green-600'],
                                ['Ice','Ingredients','50','kg','10', 'text-green-600'],
                                ['Plastic Cups','Packaging','400','pcs','100', 'text-green-600'],
                                ['Straws','Packaging','800','pcs','200', 'text-green-600'],
                                ['Mint Leaves','Ingredients','5','kg','2', 'text-green-600'],
                            ];
                        @endphp

                        @foreach($items as $item)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            {{-- Item Name --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="text-slate-300 group-hover:text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                                        </svg>
                                    </div>
                                    <span class="font-semibold text-slate-700">{{ $item[0] }}</span>
                                </div>
                            </td>

                            {{-- Category --}}
                            <td class="px-6 py-4">
                                @if($item[1] == 'Ingredients')
                                    <span class="px-3 py-1 rounded-lg bg-blue-50 text-blue-600 text-xs font-bold">Ingredients</span>
                                @else
                                    <span class="px-3 py-1 rounded-lg bg-indigo-50 text-indigo-600 text-xs font-bold">Packaging</span>
                                @endif
                            </td>

                            {{-- Stock Level --}}
                            <td class="px-6 py-4 font-bold {{ $item[5] }}">
                                {{ $item[2] }}
                            </td>

                            {{-- Unit --}}
                            <td class="px-6 py-4 text-slate-500 font-medium">
                                {{ $item[3] }}
                            </td>

                            {{-- Min Stock --}}
                            <td class="px-6 py-4 text-slate-400 font-medium">
                                {{ $item[4] }}
                            </td>

                            {{-- Status Badge --}}
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#ecfdf5] text-[#10b981] text-xs font-bold border border-[#d1fae5]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                    </svg>
                                    In Stock
                                </span>
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4 text-right">
                                <button class="text-slate-300 hover:text-slate-600 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 10.5a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm-6 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm12 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Z" />
                                    </svg>
                                </button>
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