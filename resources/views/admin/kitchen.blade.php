@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-[#f6f7fb]">
    @include('admin.partials.sidebar')

    <main class="flex-1 w-full px-4 sm:px-6 lg:px-10 pt-[96px] lg:pt-8 pb-10 overflow-x-hidden">
        <div class="max-w-[1420px] mx-auto">

            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-[30px] sm:text-[38px] font-extrabold text-[#0f172a] leading-tight">
                        Kitchen Display System
                    </h1>

                    <p class="text-[15px] sm:text-[16px] text-[#64748b] mt-2">
                        Manage pending, in-progress, and ready orders
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

            @if(session('success'))
                <div class="mb-6 rounded-xl bg-green-100 px-5 py-4 text-green-700 font-semibold shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 lg:gap-7">

                {{-- PENDING --}}
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

                                <p class="text-[16px] sm:text-[18px] font-semibold text-[#0f172a] mb-7 break-words">
                                    {{ $order->quantity }}x {{ $order->product_name }}
                                </p>

                                <form id="accept-form-{{ $order->id }}"
                                      action="{{ route('admin.kitchen.orders.accept', $order->id) }}"
                                      method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <button type="button"
                                            onclick="openAcceptModal('{{ $order->id }}', '{{ str_pad($order->id, 2, '0', STR_PAD_LEFT) }}', @js($order->customer_name))"
                                            class="w-full rounded-2xl bg-[#eef4ff] py-4 text-[16px] sm:text-[18px] font-bold text-[#2563eb] hover:bg-[#e3edff] transition shadow-sm">
                                        Accept Order
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div class="h-[155px] rounded-[22px] border-2 border-dashed border-[#d7dde7] flex items-center justify-center text-[16px] sm:text-[18px] text-[#94a3b8]">
                                No orders
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- IN PROGRESS --}}
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

                                <p class="text-[16px] sm:text-[18px] font-semibold text-[#0f172a] mb-7 break-words">
                                    {{ $order->quantity }}x {{ $order->product_name }}
                                </p>

                                <form id="ready-form-{{ $order->id }}"
                                      action="{{ route('admin.kitchen.orders.ready', $order->id) }}"
                                      method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <button type="button"
                                            onclick="openReadyModal('{{ $order->id }}', '{{ str_pad($order->id, 2, '0', STR_PAD_LEFT) }}', @js($order->customer_name))"
                                            class="w-full rounded-2xl bg-[#dcfce7] py-4 text-[16px] sm:text-[18px] font-bold text-[#166534] hover:bg-[#bbf7d0] transition shadow-sm">
                                        Mark as Ready
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div class="h-[155px] rounded-[22px] border-2 border-dashed border-[#d7dde7] flex items-center justify-center text-[16px] sm:text-[18px] text-[#94a3b8]">
                                No orders
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- READY --}}
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

                                <p class="text-[16px] sm:text-[18px] font-semibold text-[#0f172a] mb-5 break-words">
                                    {{ $order->quantity }}x {{ $order->product_name }}
                                </p>

                                <p class="text-[15px] sm:text-[16px] font-bold text-[#166534] mb-5">
                                    Total: ₱{{ number_format($order->price * $order->quantity, 2) }}
                                </p>

                                <form id="archive-form-{{ $order->id }}"
                                      action="{{ route('admin.kitchen.orders.complete', $order->id) }}"
                                      method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <button type="button"
                                            onclick="confirmArchive('{{ $order->id }}')"
                                            class="w-full rounded-2xl bg-[#0f172a] py-4 text-[16px] sm:text-[18px] font-bold text-white hover:bg-[#1e293b] transition shadow-sm">
                                        Archive to Records
                                    </button>
                                </form>
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

    {{-- ACCEPT MODAL --}}
    <div id="accept-modal" class="fixed inset-0 z-[120] hidden flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm opacity-0 px-4">
        <div id="accept-modal-content" class="bg-white rounded-[28px] sm:rounded-[32px] p-6 sm:p-8 max-w-md w-full shadow-2xl transform scale-95 transition-transform duration-300">
            <h3 class="text-[22px] sm:text-[26px] font-extrabold text-[#0f172a] text-center mb-3">
                Accept Order?
            </h3>

            <p class="text-[15px] sm:text-[16px] text-[#64748b] text-center mb-8 px-2 sm:px-4">
                Start preparing order
                <span id="accept-order-num" class="font-bold text-slate-800"></span>
                for
                <span id="accept-customer-name" class="font-bold text-slate-800"></span>?
            </p>

            <div class="flex flex-col sm:flex-row gap-4">
                <button type="button"
                        onclick="closeAcceptModal()"
                        class="flex-1 py-4 rounded-2xl font-bold text-[#64748b] bg-slate-100 hover:bg-slate-200 transition text-[16px] sm:text-[17px]">
                    Cancel
                </button>

                <button type="button"
                        onclick="submitAcceptForm()"
                        class="flex-1 py-4 rounded-2xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md transition text-[16px] sm:text-[17px]">
                    Yes, Start
                </button>
            </div>
        </div>
    </div>

    {{-- READY MODAL --}}
    <div id="ready-modal" class="fixed inset-0 z-[120] hidden flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm opacity-0 px-4">
        <div id="ready-modal-content" class="bg-white rounded-[28px] sm:rounded-[32px] p-6 sm:p-8 max-w-md w-full shadow-2xl transform scale-95 transition-transform duration-300">
            <h3 class="text-[22px] sm:text-[26px] font-extrabold text-[#0f172a] text-center mb-3">
                Order Ready?
            </h3>

            <p class="text-[15px] sm:text-[16px] text-[#64748b] text-center mb-8 px-2 sm:px-4">
                Mark order
                <span id="ready-order-num" class="font-bold text-slate-800"></span>
                for
                <span id="ready-customer-name" class="font-bold text-slate-800"></span>
                as ready?
            </p>

            <div class="flex flex-col sm:flex-row gap-4">
                <button type="button"
                        onclick="closeReadyModal()"
                        class="flex-1 py-4 rounded-2xl font-bold text-[#64748b] bg-slate-100 hover:bg-slate-200 transition text-[16px] sm:text-[17px]">
                    Cancel
                </button>

                <button type="button"
                        onclick="submitReadyForm()"
                        class="flex-1 py-4 rounded-2xl font-bold text-white bg-green-600 hover:bg-green-700 shadow-md transition text-[16px] sm:text-[17px]">
                    Yes, Ready
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('archive_success'))
    <script>
        Swal.fire({
            title: 'Recorded!',
            text: "{{ session('archive_success') }}",
            icon: 'success',
            confirmButtonColor: '#0f172a',
            confirmButtonText: 'OK'
        });
    </script>
@endif

<script>
    const acceptModal = document.getElementById('accept-modal');
    const acceptModalContent = document.getElementById('accept-modal-content');
    let currentAcceptFormId = null;

    function openAcceptModal(orderId, orderNum, customerName) {
        currentAcceptFormId = 'accept-form-' + orderId;
        document.getElementById('accept-order-num').innerText = '#' + orderNum;
        document.getElementById('accept-customer-name').innerText = customerName;

        acceptModal.classList.remove('hidden');

        setTimeout(function () {
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

        setTimeout(function () {
            acceptModal.classList.add('hidden');
        }, 300);

        currentAcceptFormId = null;
    }

    function submitAcceptForm() {
        if (currentAcceptFormId) {
            document.getElementById(currentAcceptFormId).submit();
        }
    }

    const readyModal = document.getElementById('ready-modal');
    const readyModalContent = document.getElementById('ready-modal-content');
    let currentReadyFormId = null;

    function openReadyModal(orderId, orderNum, customerName) {
        currentReadyFormId = 'ready-form-' + orderId;
        document.getElementById('ready-order-num').innerText = '#' + orderNum;
        document.getElementById('ready-customer-name').innerText = customerName;

        readyModal.classList.remove('hidden');

        setTimeout(function () {
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

        setTimeout(function () {
            readyModal.classList.add('hidden');
        }, 300);

        currentReadyFormId = null;
    }

    function submitReadyForm() {
        if (currentReadyFormId) {
            document.getElementById(currentReadyFormId).submit();
        }
    }

    function confirmArchive(orderId) {
        Swal.fire({
            title: 'Archive this order?',
            text: 'This will save the order into records and remove it from the kitchen display.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0f172a',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Yes, archive',
            cancelButtonText: 'Cancel'
        }).then(function (result) {
            if (result.isConfirmed) {
                document.getElementById('archive-form-' + orderId).submit();
            }
        });
    }
</script>
@endsection