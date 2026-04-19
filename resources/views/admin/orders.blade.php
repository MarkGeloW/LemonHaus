{{-- resources/views/admin/orders.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-[#f6f7fb]">

    {{-- SHARED SIDEBAR --}}
    @include('admin.partials.sidebar')

    {{-- MAIN CONTENT --}}
    <main class="flex-1 px-10 py-10">

        <!-- Header -->
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-[38px] font-extrabold text-[#0f172a]">
                    Order Management
                </h1>

                <p class="text-[18px] text-[#64748b] mt-2">
                    Track and manage customer orders
                </p>
            </div>

            <button class="bg-[#f4c400] hover:bg-[#eab308] text-black font-semibold px-7 py-4 rounded-2xl text-[18px]">
                + New Order
            </button>
        </div>

        <!-- Search / Filter -->
        <div class="bg-white rounded-[24px] border border-[#e9edf3] p-5 mb-7 shadow-sm">

            <div class="flex gap-4 items-center flex-wrap">

                <input type="text"
                       placeholder="Search by customer or order ID..."
                       class="flex-1 min-w-[320px] border border-[#e5e7eb] rounded-2xl px-6 py-4 text-[17px] focus:outline-none">

                <button class="px-6 py-3 rounded-2xl bg-[#0f172a] text-white font-medium">
                    All
                </button>

                <button class="px-5 py-3 rounded-2xl bg-[#f8fafc] text-[#334155]">
                    Pending
                </button>

                <button class="px-5 py-3 rounded-2xl bg-[#f8fafc] text-[#334155]">
                    In-Progress
                </button>

                <button class="px-5 py-3 rounded-2xl bg-[#f8fafc] text-[#334155]">
                    Ready
                </button>

                <button class="px-5 py-3 rounded-2xl bg-[#f8fafc] text-[#334155]">
                    Completed
                </button>

            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-[24px] border border-[#e9edf3] overflow-hidden shadow-sm">

            <table class="w-full">

                <thead class="bg-[#f8fafc] text-[#64748b] text-left">
                    <tr>
                        <th class="px-8 py-6">ORDER ID</th>
                        <th class="px-8 py-6">CUSTOMER</th>
                        <th class="px-8 py-6">ITEMS</th>
                        <th class="px-8 py-6">TOTAL</th>
                        <th class="px-8 py-6">STATUS</th>
                        <th class="px-8 py-6 text-right">ACTIONS</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#eef2f7]">

                    <tr>
                        <td class="px-8 py-6 font-bold">#01</td>
                        <td class="px-8 py-6">John Doe</td>
                        <td class="px-8 py-6">2x Classic Lemonade</td>
                        <td class="px-8 py-6 font-bold">$10.00</td>

                        <td class="px-8 py-6">
                            <span class="px-4 py-1 rounded-full bg-green-100 text-green-700 font-medium">
                                Completed
                            </span>
                        </td>

                        <td class="px-8 py-6 text-right"></td>
                    </tr>

                    <tr>
                        <td class="px-8 py-6 font-bold">#02</td>
                        <td class="px-8 py-6">Jane Smith</td>

                        <td class="px-8 py-6">
                            1x Mint Lemonade <br>
                            1x Classic Lemonade
                        </td>

                        <td class="px-8 py-6 font-bold">$11.00</td>

                        <td class="px-8 py-6">
                            <span class="px-4 py-1 rounded-full bg-blue-100 text-blue-700 font-medium">
                                Ready
                            </span>
                        </td>

                        <td class="px-8 py-6 text-right">
                            <select class="border border-[#e5e7eb] rounded-xl px-4 py-2">
                                <option>Ready</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td class="px-8 py-6 font-bold">#03</td>
                        <td class="px-8 py-6">Bob Brown</td>

                        <td class="px-8 py-6">
                            3x Strawberry Lemonade
                        </td>

                        <td class="px-8 py-6 font-bold">$19.50</td>

                        <td class="px-8 py-6">
                            <span class="px-4 py-1 rounded-full bg-yellow-100 text-yellow-700 font-medium">
                                Pending
                            </span>
                        </td>

                        <td class="px-8 py-6 text-right">
                            <select class="border border-[#e5e7eb] rounded-xl px-4 py-2">
                                <option>Pending</option>
                            </select>
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </main>
</div>
@endsection