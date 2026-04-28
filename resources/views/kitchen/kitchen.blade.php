@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-[#f6f7fb]">

    @include('kitchen.partials.sidebar')

    <main class="flex-1 px-10 py-10">
        <div class="max-w-[1440px]">

            <div class="flex items-start justify-between mb-8">
                <h1 class="text-[38px] font-extrabold text-[#0f172a]">
                    Kitchen Display System
                </h1>

                <p class="text-[16px] text-[#64748b] mt-1">
                    Active Orders: {{ $pendingOrders->count() + $inProgressOrders->count() }}
                </p>
            </div>

            @if(session('success'))
                <div class="mb-6 rounded-xl bg-green-100 px-5 py-4 text-green-700 font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-3 gap-7">

                {{-- PENDING --}}
                <div class="rounded-[22px] border border-[#e6ebf2] bg-white shadow-sm overflow-hidden">
                    <div class="bg-[#fff5be] px-6 py-5 flex items-center justify-between">
                        <h2 class="text-[20px] font-bold text-[#9a6700]">Pending</h2>

                        <span class="w-9 h-9 rounded-full bg-[#fff9df] flex items-center justify-center text-[16px] font-bold text-[#9a6700]">
                            {{ $pendingOrders->count() }}
                        </span>
                    </div>

                    <div class="p-5 space-y-5">
                        @forelse($pendingOrders as $order)
                            <div class="rounded-[22px] border border-[#e9edf3] bg-white shadow-sm p-7">
                                <div class="flex items-start justify-between mb-4">
                                    <h3 class="text-[22px] font-extrabold text-[#0f172a]">
                                        #{{ str_pad($order->id, 2, '0', STR_PAD_LEFT) }}
                                    </h3>

                                    <span class="text-[14px] text-[#94a3b8]">
                                        {{ $order->created_at->format('h:i A') }}
                                    </span>
                                </div>

                                <p class="text-[18px] text-[#475569] mb-4">
                                    {{ $order->customer_name }}
                                </p>

                                <p class="text-[18px] font-semibold text-[#0f172a] mb-7">
                                    {{ $order->quantity }}x {{ $order->product_name }}
                                </p>

                                <form action="{{ route('kitchen.orders.accept', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit"
                                        class="w-full rounded-2xl bg-[#eef4ff] py-4 text-[18px] font-bold text-[#2563eb] hover:bg-[#e3edff] transition">
                                        Accept Order
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div class="h-[155px] rounded-[22px] border-2 border-dashed border-[#d7dde7] flex items-center justify-center text-[18px] text-[#94a3b8]">
                                No orders
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- IN PROGRESS --}}
                <div class="rounded-[22px] border border-[#e6ebf2] bg-white shadow-sm overflow-hidden">
                    <div class="bg-[#dcecff] px-6 py-5 flex items-center justify-between">
                        <h2 class="text-[20px] font-bold text-[#1d4ed8]">In Progress</h2>

                        <span class="w-9 h-9 rounded-full bg-[#edf4ff] flex items-center justify-center text-[16px] font-bold text-[#1d4ed8]">
                            {{ $inProgressOrders->count() }}
                        </span>
                    </div>

                    <div class="p-5 space-y-5">
                        @forelse($inProgressOrders as $order)
                            <div class="rounded-[22px] border border-[#e9edf3] bg-white shadow-sm p-7">
                                <div class="flex items-start justify-between mb-4">
                                    <h3 class="text-[22px] font-extrabold text-[#0f172a]">
                                        #{{ str_pad($order->id, 2, '0', STR_PAD_LEFT) }}
                                    </h3>

                                    <span class="text-[14px] text-[#94a3b8]">
                                        {{ $order->updated_at->format('h:i A') }}
                                    </span>
                                </div>

                                <p class="text-[18px] text-[#475569] mb-4">
                                    {{ $order->customer_name }}
                                </p>

                                <p class="text-[18px] font-semibold text-[#0f172a] mb-7">
                                    {{ $order->quantity }}x {{ $order->product_name }}
                                </p>

                                <form action="{{ route('kitchen.orders.ready', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit"
                                        class="w-full rounded-2xl bg-[#dcfce7] py-4 text-[18px] font-bold text-[#166534] hover:bg-[#bbf7d0] transition">
                                        Mark as Ready
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div class="h-[155px] rounded-[22px] border-2 border-dashed border-[#d7dde7] flex items-center justify-center text-[18px] text-[#94a3b8]">
                                No orders
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- READY --}}
                <div class="rounded-[22px] border border-[#e6ebf2] bg-white shadow-sm overflow-hidden">
                    <div class="bg-[#dff5e4] px-6 py-5 flex items-center justify-between">
                        <h2 class="text-[20px] font-bold text-[#166534]">Ready for Pickup</h2>

                        <span class="w-9 h-9 rounded-full bg-[#ecfbef] flex items-center justify-center text-[16px] font-bold text-[#166534]">
                            {{ $readyOrders->count() }}
                        </span>
                    </div>

                    <div class="p-5 space-y-5">
                        @forelse($readyOrders as $order)
                            <div class="rounded-[22px] border border-[#e9edf3] bg-white shadow-sm p-7">
                                <div class="flex items-start justify-between mb-4">
                                    <h3 class="text-[22px] font-extrabold text-[#0f172a]">
                                        #{{ str_pad($order->id, 2, '0', STR_PAD_LEFT) }}
                                    </h3>

                                    <span class="text-[14px] text-[#94a3b8]">
                                        {{ $order->updated_at->format('h:i A') }}
                                    </span>
                                </div>

                                <p class="text-[18px] text-[#475569] mb-4">
                                    {{ $order->customer_name }}
                                </p>

                                <p class="text-[18px] font-semibold text-[#0f172a]">
                                    {{ $order->quantity }}x {{ $order->product_name }}
                                </p>
                            </div>
                        @empty
                            <div class="h-[155px] rounded-[22px] border-2 border-dashed border-[#d7dde7] flex items-center justify-center text-[18px] text-[#94a3b8]">
                                No orders
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>
@endsection