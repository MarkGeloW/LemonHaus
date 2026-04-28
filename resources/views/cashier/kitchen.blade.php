@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-[#f6f7fb]">

    @include('cashier.partials.sidebar')

    <main class="flex-1 px-10 py-10">
        <div class="max-w-[1440px]">

            <!-- Header -->
            <div class="flex items-start justify-between mb-8">
                <h1 class="text-[38px] font-extrabold text-[#0f172a]">
                    Kitchen Display System
                </h1>

                <p class="text-[16px] text-[#64748b] mt-1">
                    Active Orders: {{ $pendingOrders->count() + $inProgressOrders->count() + $readyOrders->count() }}
                </p>
            </div>

            <!-- COLUMNS -->
            <div class="grid grid-cols-3 gap-7">

                <!-- Pending -->
                <div class="rounded-[22px] border border-[#e6ebf2] bg-white shadow-sm overflow-hidden">
                    <div class="bg-[#fff5be] px-6 py-5 flex items-center justify-between">
                        <h2 class="text-[20px] font-bold text-[#9a6700]">
                            Pending
                        </h2>

                        <span class="w-9 h-9 rounded-full bg-[#fff9df] flex items-center justify-center text-[16px] font-bold text-[#9a6700]">
                            {{ $pendingOrders->count() }}
                        </span>
                    </div>

                    <div class="p-5">
                        @foreach($pendingOrders as $order)
                            <div class="rounded-[22px] border border-[#e9edf3] bg-white shadow-sm p-7 mb-4">
                                <div class="flex items-start justify-between mb-4">
                                    <h3 class="text-[22px] font-extrabold text-[#0f172a]">
                                        #{{ str_pad($order->id, 2, '0', STR_PAD_LEFT) }}
                                    </h3>
                                    <span class="text-[14px] text-[#94a3b8]">{{ $order->created_at->format('h:i A') }}</span>
                                </div>

                                <p class="text-[18px] text-[#475569] mb-4">{{ $order->customer_name }}</p>

                                <p class="text-[18px] font-semibold text-[#0f172a] mb-7">
                                    {{ $order->quantity }}x {{ $order->product_name }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- In Progress -->
                <div class="rounded-[22px] border border-[#e6ebf2] bg-white shadow-sm overflow-hidden">
                    <div class="bg-[#dcecff] px-6 py-5 flex items-center justify-between">
                        <h2 class="text-[20px] font-bold text-[#1d4ed8]">
                            In Progress
                        </h2>
                        <span class="w-9 h-9 rounded-full bg-[#edf4ff] flex items-center justify-center text-[16px] font-bold text-[#1d4ed8]">
                            {{ $inProgressOrders->count() }}
                        </span>
                    </div>

                    <div class="p-5">
                        @foreach($inProgressOrders as $order)
                            <div class="rounded-[22px] border border-[#e9edf3] bg-white shadow-sm p-7 mb-4">
                                <div class="flex items-start justify-between mb-4">
                                    <h3 class="text-[22px] font-extrabold text-[#0f172a]">
                                        #{{ str_pad($order->id, 2, '0', STR_PAD_LEFT) }}
                                    </h3>
                                    <span class="text-[14px] text-[#94a3b8]">{{ $order->created_at->format('h:i A') }}</span>
                                </div>

                                <p class="text-[18px] text-[#475569] mb-4">{{ $order->customer_name }}</p>

                                <p class="text-[18px] font-semibold text-[#0f172a] mb-7">
                                    {{ $order->quantity }}x {{ $order->product_name }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Ready -->
                <div class="rounded-[22px] border border-[#e6ebf2] bg-white shadow-sm overflow-hidden">
                    <div class="bg-[#dff5e4] px-6 py-5 flex items-center justify-between">
                        <h2 class="text-[20px] font-bold text-[#166534]">
                            Ready for Pickup
                        </h2>

                        <span class="w-9 h-9 rounded-full bg-[#ecfbef] flex items-center justify-center text-[16px] font-bold text-[#166534]">
                            {{ $readyOrders->count() }}
                        </span>
                    </div>

                    <div class="p-5">
                        @foreach($readyOrders as $order)
                            <div class="rounded-[22px] border border-[#e9edf3] bg-white shadow-sm p-7 mb-4">
                                <div class="flex items-start justify-between mb-4">
                                    <h3 class="text-[22px] font-extrabold text-[#0f172a]">
                                        #{{ str_pad($order->id, 2, '0', STR_PAD_LEFT) }}
                                    </h3>
                                    <span class="text-[14px] text-[#94a3b8]">{{ $order->created_at->format('h:i A') }}</span>
                                </div>

                                <p class="text-[18px] text-[#475569] mb-4">{{ $order->customer_name }}</p>

                                <p class="text-[18px] font-semibold text-[#0f172a] mb-7">
                                    {{ $order->quantity }}x {{ $order->product_name }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

        </div>
    </main>

</div>
@endsection