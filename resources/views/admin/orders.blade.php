@extends('layouts.app')

@php
    $orders = $orders ?? \App\Models\Order::latest()->get();

    $products = $products ?? \App\Models\Product::where('status', 'available')
        ->where('stock', '>', 0)
        ->orderBy('name')
        ->get();
@endphp

@section('content')
<div class="min-h-screen flex bg-[#f6f7fb]">

    @include('admin.partials.sidebar')

    <main class="flex-1 w-full px-4 sm:px-6 lg:px-10 pt-[96px] lg:pt-10 pb-10 overflow-x-hidden">
        <div class="max-w-[1440px] mx-auto">

            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-[30px] sm:text-[38px] font-extrabold text-[#0f172a]">
                        Order Management
                    </h1>

                    <p class="text-[15px] sm:text-[18px] text-[#64748b] mt-2">
                        Full control for creating, updating, and deleting cashier orders
                    </p>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 rounded-xl bg-green-100 px-5 py-4 text-green-700 font-semibold shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 rounded-xl bg-red-100 px-5 py-4 text-red-700 font-semibold shadow-sm">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="bg-white rounded-[24px] border border-[#e9edf3] p-5 sm:p-7 mb-8 shadow-sm">
                <h2 class="text-[21px] sm:text-[24px] font-bold text-[#0f172a] mb-6">
                    New Order
                </h2>

                <form id="create-order-form" action="{{ route('admin.orders.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <input type="text"
                           name="customer_name"
                           value="{{ old('customer_name') }}"
                           placeholder="Customer Name"
                           required
                           class="border border-[#e5e7eb] rounded-2xl px-5 py-4 text-[15px] sm:text-[16px] w-full focus:outline-none focus:ring-2 focus:ring-[#f4c400]">

                    <div id="product-container" class="space-y-4">
                        <div class="product-item grid grid-cols-1 md:grid-cols-2 xl:grid-cols-[1.5fr_1fr_1fr_auto] gap-4 items-center">
                            <select name="product_id[]"
                                    class="product-select border border-[#e5e7eb] rounded-2xl px-5 py-4 text-[15px] sm:text-[16px] w-full focus:outline-none focus:ring-2 focus:ring-[#f4c400]"
                                    required>
                                <option value="">Select Product</option>

                                @foreach($products as $product)
                                    <option value="{{ $product->id }}"
                                            data-price="{{ $product->price }}"
                                            data-stock="{{ $product->stock }}">
                                        {{ $product->name }} | ₱{{ number_format($product->price, 2) }} | Stock: {{ $product->stock }}
                                    </option>
                                @endforeach
                            </select>

                            <input type="text"
                                   class="price-display border border-[#e5e7eb] rounded-2xl px-5 py-4 text-[15px] sm:text-[16px] w-full bg-slate-50 text-[#64748b]"
                                   placeholder="Price"
                                   readonly>

                            <input type="number"
                                   name="quantity[]"
                                   placeholder="Quantity"
                                   min="1"
                                   required
                                   class="quantity-input border border-[#e5e7eb] rounded-2xl px-5 py-4 text-[15px] sm:text-[16px] w-full focus:outline-none focus:ring-2 focus:ring-[#f4c400]">

                            <button type="button"
                                    class="remove-product hidden bg-red-100 text-red-600 font-bold px-5 py-4 rounded-2xl hover:bg-red-200 transition">
                                Remove
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <button type="button"
                                id="add-product"
                                class="w-full sm:w-auto text-[#0f172a] bg-[#fff7cc] border border-[#f4c400]/40 px-5 py-3 rounded-2xl font-bold hover:bg-[#f4c400] transition">
                            + Add Another Product
                        </button>

                        <button type="button"
                                onclick="openCreateModal()"
                                class="w-full sm:flex-1 bg-[#0f172a] hover:bg-[#1e293b] text-white font-bold px-7 py-4 rounded-2xl text-[16px] sm:text-[17px] transition shadow-md">
                            Create Order
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-[24px] border border-[#e9edf3] overflow-hidden shadow-sm">
                <div class="px-5 sm:px-8 py-5 sm:py-6 border-b border-[#eef2f7]">
                    <h2 class="text-[21px] sm:text-[24px] font-bold text-[#0f172a]">
                        Active Orders
                    </h2>

                    <p class="text-[14px] sm:text-[16px] text-[#64748b] mt-1">
                        Scroll sideways on small screens to view all columns.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[900px] text-left">
                        <thead class="bg-[#f8fafc] text-[#64748b]">
                            <tr>
                                <th class="px-6 sm:px-8 py-5 sm:py-6 font-semibold">ORDER ID</th>
                                <th class="px-6 sm:px-8 py-5 sm:py-6 font-semibold">CUSTOMER</th>
                                <th class="px-6 sm:px-8 py-5 sm:py-6 font-semibold">ITEM</th>
                                <th class="px-6 sm:px-8 py-5 sm:py-6 font-semibold">TOTAL</th>
                                <th class="px-6 sm:px-8 py-5 sm:py-6 font-semibold">STATUS</th>
                                <th class="px-6 sm:px-8 py-5 sm:py-6 font-semibold text-right">ACTIONS</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-[#eef2f7]">
                            @forelse($orders as $order)
                                @php
                                    $total = $order->price * $order->quantity;
                                @endphp

                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 sm:px-8 py-5 font-bold text-[#0f172a] whitespace-nowrap">
                                        #{{ str_pad($order->id, 2, '0', STR_PAD_LEFT) }}
                                    </td>

                                    <td class="px-6 sm:px-8 py-5 text-[#475569] font-medium whitespace-nowrap">
                                        {{ $order->customer_name }}
                                    </td>

                                    <td class="px-6 sm:px-8 py-5 text-[#0f172a] font-medium">
                                        {{ $order->quantity }}x {{ $order->product_name }}
                                    </td>

                                    <td class="px-6 sm:px-8 py-5 font-bold text-[#166534] whitespace-nowrap">
                                        ₱{{ number_format($total, 2) }}
                                    </td>

                                    <td class="px-6 sm:px-8 py-5 whitespace-nowrap">
                                        <span class="px-4 py-1.5 rounded-full text-[13px]
                                            @if($order->status == 'pending') bg-yellow-100 text-yellow-700
                                            @elseif($order->status == 'in_progress') bg-blue-100 text-blue-700
                                            @elseif($order->status == 'ready') bg-green-100 text-green-700
                                            @else bg-gray-100 text-gray-700
                                            @endif
                                            font-bold tracking-wide">
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </td>

                                    <td class="px-6 sm:px-8 py-5 text-right whitespace-nowrap">
                                        <div class="flex justify-end items-center gap-3">
                                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                            </form>

                                            <form id="delete-form-{{ $order->id }}" action="{{ route('admin.orders.destroy', $order->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')

                                                <button type="button"
                                                        onclick="openDeleteModal('{{ $order->id }}', @js($order->customer_name))"
                                                        class="text-[#94a3b8] hover:text-red-500 transition p-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.9">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3m-6 0h8"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-8 py-10 text-center text-[#64748b]">
                                        No active orders found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <div id="create-modal" class="fixed inset-0 z-[120] hidden flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm transition-opacity duration-300 opacity-0 px-4">
        <div id="create-modal-content" class="bg-white rounded-[28px] sm:rounded-[32px] p-6 sm:p-8 max-w-md w-full shadow-2xl transform scale-95 transition-transform duration-300">
            <h3 class="text-[22px] sm:text-[26px] font-extrabold text-[#0f172a] text-center mb-3">
                Confirm Order
            </h3>

            <p class="text-[15px] sm:text-[16px] text-[#64748b] text-center mb-8 px-2 sm:px-4">
                Are you sure you want to create this order?
            </p>

            <div class="flex flex-col sm:flex-row gap-4">
                <button type="button"
                        onclick="closeCreateModal()"
                        class="flex-1 py-4 rounded-2xl font-bold text-[#64748b] bg-slate-100 hover:bg-slate-200 transition text-[16px] sm:text-[17px]">
                    Cancel
                </button>

                <button type="button"
                        onclick="submitCreateForm()"
                        class="flex-1 py-4 rounded-2xl font-bold text-slate-900 bg-[#f4c400] hover:bg-[#eab308] shadow-md transition text-[16px] sm:text-[17px]">
                    Yes, Create
                </button>
            </div>
        </div>
    </div>

    <div id="delete-modal" class="fixed inset-0 z-[120] hidden flex items-center justify-center bg-[#0f172a]/70 backdrop-blur-sm transition-opacity duration-300 opacity-0 px-4">
        <div id="delete-modal-content" class="bg-white rounded-[28px] sm:rounded-[32px] p-6 sm:p-8 max-w-md w-full shadow-2xl transform scale-95 transition-transform duration-300">
            <h3 class="text-[22px] sm:text-[26px] font-extrabold text-[#0f172a] text-center mb-3">
                Delete Order?
            </h3>

            <p class="text-[15px] sm:text-[16px] text-[#64748b] text-center mb-8 px-2 sm:px-4">
                Are you sure you want to delete the order for
                <span id="delete-customer-name" class="font-bold text-slate-800"></span>?
            </p>

            <div class="flex flex-col sm:flex-row gap-4">
                <button type="button"
                        onclick="closeDeleteModal()"
                        class="flex-1 py-4 rounded-2xl font-bold text-[#64748b] bg-slate-100 hover:bg-slate-200 transition text-[16px] sm:text-[17px]">
                    Cancel
                </button>

                <button type="button"
                        onclick="submitDeleteForm()"
                        class="flex-1 py-4 rounded-2xl font-bold text-white bg-red-500 hover:bg-red-600 shadow-md transition text-[16px] sm:text-[17px]">
                    Yes, Delete
                </button>
            </div>
        </div>
    </div>

</div>

<script>
    function updateProductRow(row) {
        const select = row.querySelector('.product-select');
        const priceDisplay = row.querySelector('.price-display');
        const quantityInput = row.querySelector('.quantity-input');

        const selected = select.options[select.selectedIndex];
        const price = selected.dataset.price || '';
        const stock = selected.dataset.stock || '';

        priceDisplay.value = price ? '₱' + parseFloat(price).toFixed(2) : '';
        quantityInput.max = stock || '';

        if (stock && parseInt(quantityInput.value || 0) > parseInt(stock)) {
            quantityInput.value = stock;
        }
    }

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select')) {
            updateProductRow(e.target.closest('.product-item'));
        }
    });

    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('quantity-input')) {
            const row = e.target.closest('.product-item');
            const select = row.querySelector('.product-select');
            const selected = select.options[select.selectedIndex];
            const stock = parseInt(selected.dataset.stock || 0);
            const quantity = parseInt(e.target.value || 0);

            if (stock && quantity > stock) {
                e.target.value = stock;
            }
        }
    });

    document.getElementById('add-product').addEventListener('click', function() {
        const container = document.getElementById('product-container');
        const firstRow = container.querySelector('.product-item');
        const newRow = firstRow.cloneNode(true);

        newRow.querySelector('.product-select').value = '';
        newRow.querySelector('.price-display').value = '';
        newRow.querySelector('.quantity-input').value = '';
        newRow.querySelector('.quantity-input').removeAttribute('max');
        newRow.querySelector('.remove-product').classList.remove('hidden');

        container.appendChild(newRow);
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-product')) {
            e.target.closest('.product-item').remove();
        }
    });

    const createModal = document.getElementById('create-modal');
    const createModalContent = document.getElementById('create-modal-content');
    const createForm = document.getElementById('create-order-form');

    function openCreateModal() {
        if (!createForm.checkValidity()) {
            createForm.reportValidity();
            return;
        }

        createModal.classList.remove('hidden');

        setTimeout(function() {
            createModal.classList.remove('opacity-0');
            createModal.classList.add('opacity-100');
            createModalContent.classList.remove('scale-95');
            createModalContent.classList.add('scale-100');
        }, 10);
    }

    function closeCreateModal() {
        createModal.classList.remove('opacity-100');
        createModal.classList.add('opacity-0');
        createModalContent.classList.remove('scale-100');
        createModalContent.classList.add('scale-95');

        setTimeout(function() {
            createModal.classList.add('hidden');
        }, 300);
    }

    function submitCreateForm() {
        createForm.submit();
    }

    const deleteModal = document.getElementById('delete-modal');
    const deleteModalContent = document.getElementById('delete-modal-content');
    let currentDeleteFormId = null;

    function openDeleteModal(orderId, customerName) {
        currentDeleteFormId = 'delete-form-' + orderId;
        document.getElementById('delete-customer-name').innerText = customerName;

        deleteModal.classList.remove('hidden');

        setTimeout(function() {
            deleteModal.classList.remove('opacity-0');
            deleteModal.classList.add('opacity-100');
            deleteModalContent.classList.remove('scale-95');
            deleteModalContent.classList.add('scale-100');
        }, 10);
    }

    function closeDeleteModal() {
        deleteModal.classList.remove('opacity-100');
        deleteModal.classList.add('opacity-0');
        deleteModalContent.classList.remove('scale-100');
        deleteModalContent.classList.add('scale-95');

        setTimeout(function() {
            deleteModal.classList.add('hidden');
        }, 300);

        currentDeleteFormId = null;
    }

    function submitDeleteForm() {
        if (currentDeleteFormId) {
            document.getElementById(currentDeleteFormId).submit();
        }
    }
</script>
@endsection