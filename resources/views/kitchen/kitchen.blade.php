@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-[#f6f7fb]">

    {{-- SHARED SIDEBAR --}}
    @include('kitchen.partials.sidebar')

    <!-- Main -->
    <main class="relative flex-1 px-10 pt-8 pb-10">
        <div class="max-w-[1420px]">

            <div class="flex items-start justify-between mb-8">
                <h1 class="text-[38px] font-extrabold text-[#0f172a]">
                    Kitchen Display System
                </h1>

                <p class="text-[16px] text-[#64748b] mt-1">
                    Active Orders: {{ $pendingOrders->count() + $inProgressOrders->count() }}
                </p>
            </div>

            @if(session('success'))
                <div class="px-5 py-4 mb-6 font-semibold text-green-700 bg-green-100 shadow-sm rounded-xl">
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

                                <!-- TRIGGER ACCEPT MODAL -->
                                <form id="accept-form-{{ $order->id }}" action="{{ route('kitchen.orders.accept', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <button type="button" onclick="openAcceptModal('{{ $order->id }}', '{{ str_pad($order->id, 2, '0', STR_PAD_LEFT) }}', '{{ addslashes($order->customer_name) }}')"
                                        class="w-full rounded-2xl bg-[#eef4ff] py-4 text-[18px] font-bold text-[#2563eb] hover:bg-[#e3edff] transition shadow-sm">
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

                                <!-- TRIGGER READY MODAL -->
                                <form id="ready-form-{{ $order->id }}" action="{{ route('kitchen.orders.ready', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <button type="button" onclick="openReadyModal('{{ $order->id }}', '{{ str_pad($order->id, 2, '0', STR_PAD_LEFT) }}', '{{ addslashes($order->customer_name) }}')"
                                        class="w-full rounded-2xl bg-[#dcfce7] py-4 text-[18px] font-bold text-[#166534] hover:bg-[#bbf7d0] transition shadow-sm">
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

    <!-- CUSTOM ACCEPT ORDER MODAL (BLUE) -->
    <div id="accept-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm transition-opacity duration-300 opacity-0">
        <div id="accept-modal-content" class="bg-white rounded-[32px] p-8 max-w-md w-full mx-4 shadow-2xl transform scale-95 transition-transform duration-300">
            <div class="w-20 h-20 rounded-full bg-blue-50 flex items-center justify-center mx-auto mb-6 border-[8px] border-blue-100/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-[26px] font-extrabold text-[#0f172a] text-center mb-3">Accept Order?</h3>
            <p class="text-[16px] text-[#64748b] text-center mb-8 px-4">
                Are you sure you want to start preparing order <span id="accept-order-num" class="font-bold text-slate-800"></span> for <span id="accept-customer-name" class="font-bold text-slate-800"></span>?
            </p>
            <div class="flex gap-4">
                <button type="button" onclick="closeAcceptModal()" class="flex-1 py-4 rounded-2xl font-bold text-[#64748b] bg-slate-100 hover:bg-slate-200 transition text-[17px]">
                    Cancel
                </button>
                <button type="button" onclick="submitAcceptForm()" class="flex-1 py-4 rounded-2xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md transition text-[17px]">
                    Yes, Start
                </button>
            </div>
        </div>
    </div>

    <!-- CUSTOM MARK AS READY MODAL (GREEN) -->
    <div id="ready-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm transition-opacity duration-300 opacity-0">
        <div id="ready-modal-content" class="bg-white rounded-[32px] p-8 max-w-md w-full mx-4 shadow-2xl transform scale-95 transition-transform duration-300">
            <div class="w-20 h-20 rounded-full bg-green-50 flex items-center justify-center mx-auto mb-6 border-[8px] border-green-100/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-[26px] font-extrabold text-[#0f172a] text-center mb-3">Order Ready?</h3>
            <p class="text-[16px] text-[#64748b] text-center mb-8 px-4">
                Is order <span id="ready-order-num" class="font-bold text-slate-800"></span> for <span id="ready-customer-name" class="font-bold text-slate-800"></span> completely finished and ready for pickup?
            </p>
            <div class="flex gap-4">
                <button type="button" onclick="closeReadyModal()" class="flex-1 py-4 rounded-2xl font-bold text-[#64748b] bg-slate-100 hover:bg-slate-200 transition text-[17px]">
                    Cancel
                </button>
                <button type="button" onclick="submitReadyForm()" class="flex-1 py-4 rounded-2xl font-bold text-white bg-green-600 hover:bg-green-700 shadow-md transition text-[17px]">
                    Yes, Ready
                </button>
            </div>
        </div>
    </div>

</div>

<!-- JAVASCRIPT LOGIC -->
<script>
    // -----------------------------------------
    // ACCEPT ORDER MODAL LOGIC
    // -----------------------------------------
    const acceptModal = document.getElementById('accept-modal');
    const acceptModalContent = document.getElementById('accept-modal-content');
    let currentAcceptFormId = null;

    function openAcceptModal(orderId, orderNum, customerName) {
        currentAcceptFormId = 'accept-form-' + orderId;
        document.getElementById('accept-order-num').innerText = '#' + orderNum;
        document.getElementById('accept-customer-name').innerText = customerName;

        acceptModal.classList.remove('hidden');
        setTimeout(() => {
            acceptModal.classList.remove('opacity-0');
            acceptModal.classList.add('opacity-100');
            acceptModalContent.classList.remove('scale-95');
            acceptModalContent.classList.add('scale-100');
        }, 10);
    }

    function closeAcceptModal() {
        acceptModal.classList.remove('opacity-100');
        acceptModal.classList.add('opacity-0');
        acceptModalContent.classList.remove('scale-100');
        acceptModalContent.classList.add('scale-95');
        setTimeout(() => { acceptModal.classList.add('hidden'); }, 300);
        currentAcceptFormId = null;
    }

    function submitAcceptForm() {
        if(currentAcceptFormId) {
            document.getElementById(currentAcceptFormId).submit();
        }
    }

    // -----------------------------------------
    // READY ORDER MODAL LOGIC
    // -----------------------------------------
    const readyModal = document.getElementById('ready-modal');
    const readyModalContent = document.getElementById('ready-modal-content');
    let currentReadyFormId = null;

    function openReadyModal(orderId, orderNum, customerName) {
        currentReadyFormId = 'ready-form-' + orderId;
        document.getElementById('ready-order-num').innerText = '#' + orderNum;
        document.getElementById('ready-customer-name').innerText = customerName;

        readyModal.classList.remove('hidden');
        setTimeout(() => {
            readyModal.classList.remove('opacity-0');
            readyModal.classList.add('opacity-100');
            readyModalContent.classList.remove('scale-95');
            readyModalContent.classList.add('scale-100');
        }, 10);
    }

    function closeReadyModal() {
        readyModal.classList.remove('opacity-100');
        readyModal.classList.add('opacity-0');
        readyModalContent.classList.remove('scale-100');
        readyModalContent.classList.add('scale-95');
        setTimeout(() => { readyModal.classList.add('hidden'); }, 300);
        currentReadyFormId = null;
    }

    function submitReadyForm() {
        if(currentReadyFormId) {
            document.getElementById(currentReadyFormId).submit();
        }
    }
</script>
@endsection
