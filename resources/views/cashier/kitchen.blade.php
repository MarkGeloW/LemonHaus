@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-[#f6f7fb]">

    @include('cashier.partials.sidebar')

    <main class="flex-1 w-full px-4 sm:px-6 lg:px-10 pt-[96px] lg:pt-10 pb-10 overflow-x-hidden">
        <div class="max-w-[1440px] mx-auto">

            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-[30px] sm:text-[38px] font-extrabold text-[#0f172a] leading-tight">
                        Kitchen Display System
                    </h1>

                    <p class="text-[15px] sm:text-[16px] text-[#64748b] mt-2">
                        View kitchen order status
                    </p>
                </div>

                <div class="bg-white border border-[#e9edf3] rounded-2xl px-5 py-3 shadow-sm w-fit">
                    <p class="text-[15px] sm:text-[16px] text-[#64748b]">
                        Active Orders:
                        <span class="font-extrabold text-[#0f172a]">
                            {{ $pendingOrders->count() + $inProgressOrders->count() + $readyOrders->count() }}
                        </span>
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 lg:gap-7">

                <div class="rounded-[22px] border border-[#e6ebf2] bg-white shadow-sm overflow-hidden flex flex-col min-h-[420px] xl:h-[calc(100vh-180px)]">
                    <div class="bg-[#fff5be] px-5 sm:px-6 py-5 flex items-center justify-between shrink-0">
                        <h2 class="text-[19px] sm:text-[20px] font-bold text-[#9a6700]">
                            Pending
                        </h2>

                        <span class="w-9 h-9 rounded-full bg-[#fff9df] flex items-center justify-center text-[16px] font-bold text-[#9a6700]">
                            {{ $pendingOrders->count() }}
                        </span>
                    </div>

                    <div class="p-4 sm:p-5 space-y-5 overflow-y-auto flex-1">
                        @forelse($pendingOrders as $order)
                            <div class="rounded-[22px] border border-[#e9edf3] bg-white shadow-sm p-5 sm:p-7">
                                <div class="flex items-start justify-between gap-4 mb-4">
                                    <h3 class="text-[20px] sm:text-[22px] font-extrabold text-[#0f172a]">
                                        #{{ str_pad($order->id, 2, '0', STR_PAD_LEFT) }}
                                    </h3>

                                    <span class="text-[13px] sm:text-[14px] text-[#94a3b8] whitespace-nowrap">
                                        {{ $order->created_at->format('h:i A') }}
                                    </span>
                                </div>

                                <p class="text-[16px] sm:text-[18px] text-[#475569] mb-4 break-words">
                                    {{ $order->customer_name }}
                                </p>

                                <p class="text-[16px] sm:text-[18px] font-semibold text-[#0f172a] break-words">
                                    {{ $order->quantity }}x {{ $order->product_name }}
                                </p>
                            </div>
                        @empty
                            <div class="h-[155px] rounded-[22px] border-2 border-dashed border-[#d7dde7] flex items-center justify-center text-[16px] sm:text-[18px] text-[#94a3b8]">
                                No orders
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-[22px] border border-[#e6ebf2] bg-white shadow-sm overflow-hidden flex flex-col min-h-[420px] xl:h-[calc(100vh-180px)]">
                    <div class="bg-[#dcecff] px-5 sm:px-6 py-5 flex items-center justify-between shrink-0">
                        <h2 class="text-[19px] sm:text-[20px] font-bold text-[#1d4ed8]">
                            In Progress
                        </h2>

                        <span class="w-9 h-9 rounded-full bg-[#edf4ff] flex items-center justify-center text-[16px] font-bold text-[#1d4ed8]">
                            {{ $inProgressOrders->count() }}
                        </span>
                    </div>

                    <div class="p-4 sm:p-5 space-y-5 overflow-y-auto flex-1">
                        @forelse($inProgressOrders as $order)
                            <div class="rounded-[22px] border border-[#e9edf3] bg-white shadow-sm p-5 sm:p-7">
                                <div class="flex items-start justify-between gap-4 mb-4">
                                    <h3 class="text-[20px] sm:text-[22px] font-extrabold text-[#0f172a]">
                                        #{{ str_pad($order->id, 2, '0', STR_PAD_LEFT) }}
                                    </h3>

                                    <span class="text-[13px] sm:text-[14px] text-[#94a3b8] whitespace-nowrap">
                                        {{ $order->updated_at->format('h:i A') }}
                                    </span>
                                </div>

                                <p class="text-[16px] sm:text-[18px] text-[#475569] mb-4 break-words">
                                    {{ $order->customer_name }}
                                </p>

                                <p class="text-[16px] sm:text-[18px] font-semibold text-[#0f172a] break-words">
                                    {{ $order->quantity }}x {{ $order->product_name }}
                                </p>
                            </div>
                        @empty
                            <div class="h-[155px] rounded-[22px] border-2 border-dashed border-[#d7dde7] flex items-center justify-center text-[16px] sm:text-[18px] text-[#94a3b8]">
                                No orders
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-[22px] border border-[#e6ebf2] bg-white shadow-sm overflow-hidden flex flex-col min-h-[420px] xl:h-[calc(100vh-180px)]">
                    <div class="bg-[#dff5e4] px-5 sm:px-6 py-5 flex items-center justify-between shrink-0">
                        <h2 class="text-[19px] sm:text-[20px] font-bold text-[#166534]">
                            Ready for Pickup
                        </h2>

                        <span class="w-9 h-9 rounded-full bg-[#ecfbef] flex items-center justify-center text-[16px] font-bold text-[#166534]">
                            {{ $readyOrders->count() }}
                        </span>
                    </div>

                    <div class="p-4 sm:p-5 space-y-5 overflow-y-auto flex-1">
                        @forelse($readyOrders as $order)
                            <div class="rounded-[22px] border border-[#e9edf3] bg-white shadow-sm p-5 sm:p-7">
                                <div class="flex items-start justify-between gap-4 mb-4">
                                    <h3 class="text-[20px] sm:text-[22px] font-extrabold text-[#0f172a]">
                                        #{{ str_pad($order->id, 2, '0', STR_PAD_LEFT) }}
                                    </h3>

                                    <span class="text-[13px] sm:text-[14px] text-[#94a3b8] whitespace-nowrap">
                                        {{ $order->updated_at->format('h:i A') }}
                                    </span>
                                </div>

                                <p class="text-[16px] sm:text-[18px] text-[#475569] mb-4 break-words">
                                    {{ $order->customer_name }}
                                </p>

                                <p class="text-[16px] sm:text-[18px] font-semibold text-[#0f172a] mb-4 break-words">
                                    {{ $order->quantity }}x {{ $order->product_name }}
                                </p>

                                <p class="text-[15px] sm:text-[16px] font-bold text-[#166534]">
                                    Ready for pickup
                                </p>
                            </div>
                        @empty
                            <div class="h-[155px] rounded-[22px] border-2 border-dashed border-[#d7dde7] flex items-center justify-center text-[16px] sm:text-[18px] text-[#94a3b8]">
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