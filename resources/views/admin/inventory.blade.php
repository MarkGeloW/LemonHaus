@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-[#f6f7fb]">
    @include('admin.partials.sidebar')

    <main class="flex-1 w-full px-4 sm:px-6 lg:px-10 pt-[96px] lg:pt-10 pb-10 overflow-x-hidden">
        <div class="max-w-[1440px] mx-auto">

            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-5 mb-8">
                <div>
                    <h1 class="text-[30px] sm:text-[38px] font-extrabold text-[#0f172a] leading-tight">
                        Inventory Management
                    </h1>

                    <p class="text-[15px] sm:text-[18px] text-[#64748b] mt-2">
                        Track stock supplies and LemonHaus products
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                    <a href="{{ route('admin.products.create') }}"
                       class="w-full sm:w-auto text-center bg-[#f4c400] hover:bg-[#eab308] text-black font-semibold px-6 sm:px-7 py-4 rounded-2xl text-[16px] sm:text-[18px] transition shadow-sm">
                        + Add Product
                    </a>

                    <a href="{{ route('admin.stocks.create') }}"
                       class="w-full sm:w-auto text-center bg-[#B7E6D8] hover:bg-[#9ddcca] text-[#37554d] font-semibold px-6 sm:px-7 py-4 rounded-2xl text-[16px] sm:text-[18px] transition shadow-sm">
                        + Add Stock
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-7">
                <div class="bg-white rounded-[22px] border border-[#e9edf3] shadow-sm p-5 sm:p-7">
                    <p class="text-[15px] sm:text-[16px] text-[#64748b] font-medium">
                        Stock Items
                    </p>

                    <h2 class="text-[24px] sm:text-[28px] font-extrabold text-[#0f172a] mt-2">
                        {{ $stocks->count() }}
                    </h2>
                </div>

                <div class="bg-white rounded-[22px] border border-[#e9edf3] shadow-sm p-5 sm:p-7">
                    <p class="text-[15px] sm:text-[16px] text-[#64748b] font-medium">
                        LemonHaus Products
                    </p>

                    <h2 class="text-[24px] sm:text-[28px] font-extrabold text-[#0f172a] mt-2">
                        {{ $products->count() }}
                    </h2>
                </div>

                <div class="bg-white rounded-[22px] border border-[#e9edf3] shadow-sm p-5 sm:p-7">
                    <p class="text-[15px] sm:text-[16px] text-[#64748b] font-medium">
                        Low Stock
                    </p>

                    <h2 class="text-[24px] sm:text-[28px] font-extrabold text-orange-500 mt-2">
                        {{ $stocks->filter(fn($s) => $s->stock_level > 0 && $s->stock_level <= $s->min_stock)->count() }}
                    </h2>
                </div>

                <div class="bg-white rounded-[22px] border border-[#e9edf3] shadow-sm p-5 sm:p-7">
                    <p class="text-[15px] sm:text-[16px] text-[#64748b] font-medium">
                        Expired Stock
                    </p>

                    <h2 class="text-[24px] sm:text-[28px] font-extrabold text-red-500 mt-2">
                        {{ $stocks->filter(fn($s) => $s->expiration_date && now()->gt($s->expiration_date))->count() }}
                    </h2>
                </div>
            </div>

            <div class="bg-white rounded-[24px] border border-[#e9edf3] overflow-hidden shadow-sm mb-10">
                <div class="px-5 sm:px-8 py-5 sm:py-6 border-b border-[#eef2f7] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-[22px] sm:text-[26px] font-extrabold text-[#0f172a]">
                            Stocks
                        </h2>

                        <p class="text-[14px] sm:text-[16px] text-[#64748b] mt-1">
                            Ingredients and supplies used for LemonHaus products
                        </p>
                    </div>

                    <span class="w-fit bg-[#e5fbf5] text-[#37554d] px-5 py-2 rounded-full font-bold">
                        {{ $stocks->count() }} Stock Items
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1050px]">
                        <thead class="bg-[#f8fafc] text-[#64748b] text-left">
                            <tr>
                                <th class="px-6 sm:px-8 py-5 sm:py-6">STOCK NAME</th>
                                <th class="px-6 sm:px-8 py-5 sm:py-6">CATEGORY</th>
                                <th class="px-6 sm:px-8 py-5 sm:py-6">STOCK LEVEL</th>
                                <th class="px-6 sm:px-8 py-5 sm:py-6">MIN STOCK</th>
                                <th class="px-6 sm:px-8 py-5 sm:py-6">EXPIRATION DATE</th>
                                <th class="px-6 sm:px-8 py-5 sm:py-6">STATUS</th>
                                <th class="px-6 sm:px-8 py-5 sm:py-6">DATE ADDED</th>
                                <th class="px-6 sm:px-8 py-5 sm:py-6 text-right">ACTIONS</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-[#eef2f7]">
                            @forelse($stocks as $stock)
                                @php
                                    $isExpired = $stock->expiration_date && now()->gt($stock->expiration_date);
                                    $isOut = $stock->stock_level <= 0;
                                    $isLow = $stock->stock_level > 0 && $stock->stock_level <= $stock->min_stock;
                                @endphp

                                <tr class="hover:bg-[#f8fafc] transition">
                                    <td class="px-6 sm:px-8 py-5 sm:py-6 font-bold text-[#0f172a] whitespace-nowrap">
                                        {{ $stock->name }}
                                    </td>

                                    <td class="px-6 sm:px-8 py-5 sm:py-6 text-[#334155] whitespace-nowrap">
                                        {{ $stock->category }}
                                    </td>

                                    <td class="px-6 sm:px-8 py-5 sm:py-6 font-bold whitespace-nowrap {{ $isOut ? 'text-red-500' : ($isLow ? 'text-orange-500' : 'text-green-600') }}">
                                        {{ $stock->stock_level }}
                                        <span class="text-gray-400 font-normal">
                                            {{ $stock->unit }}
                                        </span>
                                    </td>

                                    <td class="px-6 sm:px-8 py-5 sm:py-6 text-[#334155] whitespace-nowrap">
                                        {{ $stock->min_stock }}
                                        <span class="text-gray-400">
                                            {{ $stock->unit }}
                                        </span>
                                    </td>

                                    <td class="px-6 sm:px-8 py-5 sm:py-6 font-bold whitespace-nowrap {{ $isExpired ? 'text-red-500' : 'text-[#334155]' }}">
                                        {{ $stock->expiration_date ? $stock->expiration_date->format('M d, Y') : 'No expiration' }}
                                    </td>

                                    <td class="px-6 sm:px-8 py-5 sm:py-6 whitespace-nowrap">
                                        @if($isExpired)
                                            <span class="px-4 py-1 rounded-full bg-red-100 text-red-700 font-medium">
                                                Expired
                                            </span>
                                        @elseif($isOut)
                                            <span class="px-4 py-1 rounded-full bg-red-100 text-red-700 font-medium">
                                                Out of Stock
                                            </span>
                                        @elseif($isLow)
                                            <span class="px-4 py-1 rounded-full bg-orange-100 text-orange-700 font-medium">
                                                Low Stock
                                            </span>
                                        @else
                                            <span class="px-4 py-1 rounded-full bg-green-100 text-green-700 font-medium">
                                                Good
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 sm:px-8 py-5 sm:py-6 text-gray-500 whitespace-nowrap">
                                        {{ $stock->date_added ? $stock->date_added->format('M d, Y') : $stock->created_at->format('M d, Y') }}
                                    </td>

                                    <td class="px-6 sm:px-8 py-5 sm:py-6 text-right whitespace-nowrap">
                                        <div class="flex justify-end gap-3">
                                            <a href="{{ route('admin.stocks.edit', $stock->id) }}"
                                               class="px-4 py-2 rounded-xl bg-blue-100 text-blue-700 font-bold hover:bg-blue-200 transition">
                                                Edit
                                            </a>

                                            <form action="{{ route('admin.stocks.destroy', $stock->id) }}"
                                                  method="POST"
                                                  class="delete-form"
                                                  data-title="Delete this stock item?"
                                                  data-text="This stock item will be removed from your inventory.">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="px-4 py-2 rounded-xl bg-red-100 text-red-700 font-bold hover:bg-red-200 transition">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-8 py-12 text-center text-[#64748b]">
                                        No stock items added yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-[24px] border border-[#e9edf3] overflow-hidden shadow-sm">
                <div class="px-5 sm:px-8 py-5 sm:py-6 border-b border-[#eef2f7] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-[22px] sm:text-[26px] font-extrabold text-[#0f172a]">
                            LemonHaus Products
                        </h2>

                        <p class="text-[14px] sm:text-[16px] text-[#64748b] mt-1">
                            Products sold by the cashier
                        </p>
                    </div>

                    <span class="w-fit bg-[#fff7cc] text-[#8a6a00] px-5 py-2 rounded-full font-bold">
                        {{ $products->count() }} Products
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[900px]">
                        <thead class="bg-[#f8fafc] text-[#64748b] text-left">
                            <tr>
                                <th class="px-6 sm:px-8 py-5 sm:py-6">PRODUCT NAME</th>
                                <th class="px-6 sm:px-8 py-5 sm:py-6">CATEGORY</th>
                                <th class="px-6 sm:px-8 py-5 sm:py-6">PRICE</th>
                                <th class="px-6 sm:px-8 py-5 sm:py-6">STOCK</th>
                                <th class="px-6 sm:px-8 py-5 sm:py-6">STATUS</th>
                                <th class="px-6 sm:px-8 py-5 sm:py-6">DATE ADDED</th>
                                <th class="px-6 sm:px-8 py-5 sm:py-6 text-right">ACTIONS</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-[#eef2f7]">
                            @forelse($products as $product)
                                @php
                                    $isOut = $product->stock <= 0;
                                    $isLow = $product->stock > 0 && $product->stock <= 10;
                                @endphp

                                <tr class="hover:bg-[#f8fafc] transition">
                                    <td class="px-6 sm:px-8 py-5 sm:py-6">
                                        <div class="font-bold text-[#0f172a] whitespace-nowrap">
                                            {{ $product->name }}
                                        </div>

                                        @if($product->description)
                                            <div class="text-sm text-[#64748b] mt-1 max-w-[260px]">
                                                {{ $product->description }}
                                            </div>
                                        @endif
                                    </td>

                                    <td class="px-6 sm:px-8 py-5 sm:py-6 text-[#334155] whitespace-nowrap">
                                        {{ $product->category }}
                                    </td>

                                    <td class="px-6 sm:px-8 py-5 sm:py-6 font-bold text-[#0f172a] whitespace-nowrap">
                                        ₱{{ number_format($product->price, 2) }}
                                    </td>

                                    <td class="px-6 sm:px-8 py-5 sm:py-6 font-bold whitespace-nowrap {{ $isOut ? 'text-red-500' : ($isLow ? 'text-orange-500' : 'text-green-600') }}">
                                        {{ $product->stock }}
                                    </td>

                                    <td class="px-6 sm:px-8 py-5 sm:py-6 whitespace-nowrap">
                                        @if($isOut)
                                            <span class="px-4 py-1 rounded-full bg-red-100 text-red-700 font-medium">
                                                Out of Stock
                                            </span>
                                        @elseif($isLow)
                                            <span class="px-4 py-1 rounded-full bg-orange-100 text-orange-700 font-medium">
                                                Low Stock
                                            </span>
                                        @elseif($product->status === 'unavailable')
                                            <span class="px-4 py-1 rounded-full bg-gray-100 text-gray-700 font-medium">
                                                Unavailable
                                            </span>
                                        @else
                                            <span class="px-4 py-1 rounded-full bg-green-100 text-green-700 font-medium">
                                                Available
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 sm:px-8 py-5 sm:py-6 text-gray-500 whitespace-nowrap">
                                        {{ $product->created_at->format('M d, Y') }}
                                    </td>

                                    <td class="px-6 sm:px-8 py-5 sm:py-6 text-right whitespace-nowrap">
                                        <div class="flex justify-end gap-3">
                                            <a href="{{ route('admin.products.edit', $product->id) }}"
                                               class="px-4 py-2 rounded-xl bg-blue-100 text-blue-700 font-bold hover:bg-blue-200 transition">
                                                Edit
                                            </a>

                                            <form action="{{ route('admin.products.destroy', $product->id) }}"
                                                  method="POST"
                                                  class="delete-form"
                                                  data-title="Delete this product?"
                                                  data-text="This product will be removed from LemonHaus products.">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="px-4 py-2 rounded-xl bg-red-100 text-red-700 font-bold hover:bg-red-200 transition">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-8 py-12 text-center text-[#64748b]">
                                        No LemonHaus products added yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
    <script>
        Swal.fire({
            title: 'Success!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonColor: '#0f172a',
            confirmButtonText: 'OK'
        });
    </script>
@endif

<script>
    document.querySelectorAll('.delete-form').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            Swal.fire({
                title: form.dataset.title,
                text: form.dataset.text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then(function (result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection