@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-[#f6f7fb]">
    @include('cashier.partials.sidebar')

    <main class="flex-1 px-10 py-10 relative">
        <div class="max-w-[1440px] mx-auto">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h1 class="text-[38px] font-extrabold text-[#0f172a]">Order Management</h1>
                    <p class="text-[18px] text-[#64748b] mt-2">Track and manage customer orders</p>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 rounded-xl bg-green-100 px-5 py-4 text-green-700 font-semibold shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-[24px] border border-[#e9edf3] p-7 mb-8 shadow-sm">
                <h2 class="text-[24px] font-bold text-[#0f172a] mb-6">New Order</h2>

                <form id="create-order-form" action="{{ route('cashier.orders.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="text" name="customer_name" placeholder="Customer Name" required class="border border-[#e5e7eb] rounded-2xl px-5 py-4 text-[16px] w-full focus:outline-none focus:ring-2 focus:ring-[#f4c400]">

                    <div id="product-container" class="space-y-4">
                        <div class="product-item flex space-x-4">
                            <select name="product_name[]" required class="border border-[#e5e7eb] bg-white rounded-2xl px-5 py-4 text-[16px] w-full focus:outline-none focus:ring-2 focus:ring-[#f4c400] text-[#334155] cursor-pointer">
                                <option value="" disabled selected>Select a Drink...</option>
                                <option value="Classic Lemonade">Classic Lemonade</option>
                                <option value="Mint Lemonade">Mint Lemonade</option>
                                <option value="Strawberry Lemonade">Strawberry Lemonade</option>
                                <option value="Grape Lemonade">Grape Lemonade</option>
                            </select>
                            <input type="number" name="price[]" placeholder="Price" min="0" step="0.01" required class="border border-[#e5e7eb] rounded-2xl px-5 py-4 text-[16px] w-full focus:outline-none focus:ring-2 focus:ring-[#f4c400]">
                            <input type="number" name="quantity[]" placeholder="Quantity" min="1" required class="border border-[#e5e7eb] rounded-2xl px-5 py-4 text-[16px] w-full focus:outline-none focus:ring-2 focus:ring-[#f4c400]">
                        </div>
                    </div>

                    <button type="button" id="add-product" class="text-blue-500 font-medium hover:underline">+ Add Another Product</button>

                    <button type="button" onclick="openCreateModal()" class="bg-[#0f172a] hover:bg-[#1e293b] text-white font-bold px-7 py-4 rounded-2xl text-[17px] w-full mt-4 transition shadow-md">
                        Create Order
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-[24px] border border-[#e9edf3] overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-[#f8fafc] text-[#64748b]">
                            <tr>
                                <th class="px-8 py-6 font-semibold">ORDER ID</th>
                                <th class="px-8 py-6 font-semibold">CUSTOMER</th>
                                <th class="px-8 py-6 font-semibold">ITEM</th>
                                <th class="px-8 py-6 font-semibold">STATUS</th>
                                <th class="px-8 py-6 font-semibold text-right">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#eef2f7]">
                            @foreach($orders->groupBy('customer_name') as $customerName => $customerOrders)
                                <tr class="bg-slate-50/50"><td colspan="5" class="px-8 py-4 font-bold text-[#0f172a] border-b border-[#eef2f7] uppercase tracking-wide text-sm">{{ $customerName }}</td></tr>
                                @foreach($customerOrders as $order)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-8 py-5 font-bold text-[#0f172a]">#{{ str_pad($order->id, 2, '0', STR_PAD_LEFT) }}</td>
                                        <td class="px-8 py-5 text-[#475569] font-medium">{{ $order->customer_name }}</td>
                                        <td class="px-8 py-5 text-[#0f172a] font-medium">{{ $order->quantity }}x {{ $order->product_name }}</td>
                                        <td class="px-8 py-5">
                                            <span class="px-4 py-1.5 rounded-full text-[13px] @if($order->status == 'pending') bg-yellow-100 text-yellow-700 @elseif($order->status == 'in_progress') bg-blue-100 text-blue-700 @else bg-green-100 text-green-700 @endif font-bold tracking-wide">{{ ucfirst($order->status) }}</span>
                                        </td>
                                        <td class="px-8 py-5 text-right">
                                            <form id="delete-form-{{ $order->id }}" action="{{ route('cashier.orders.destroy', $order->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="button" onclick="openDeleteModal('{{ $order->id }}', '{{ addslashes($order->customer_name) }}')" class="text-[#94a3b8] hover:text-red-500 transition p-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.9"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3m-6 0h8"/></svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                            @if($orders->isEmpty())
                                <tr><td colspan="5" class="px-8 py-10 text-center text-[#64748b]">No active orders found.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- CREATE MODAL -->
    <div id="create-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm transition-opacity duration-300 opacity-0">
        <div id="create-modal-content" class="bg-white rounded-[32px] p-8 max-w-md w-full mx-4 shadow-2xl transform scale-95 transition-transform duration-300">
            <div class="w-20 h-20 rounded-full bg-yellow-50 flex items-center justify-center mx-auto mb-6 border-[8px] border-yellow-100/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
            </div>
            <h3 class="text-[26px] font-extrabold text-[#0f172a] text-center mb-3">Confirm Order</h3>
            <p class="text-[16px] text-[#64748b] text-center mb-8 px-4">Are you sure you want to send this order to the kitchen?</p>
            <div class="flex gap-4">
                <button type="button" onclick="closeCreateModal()" class="flex-1 py-4 rounded-2xl font-bold text-[#64748b] bg-slate-100 hover:bg-slate-200 transition text-[17px]">Cancel</button>
                <button type="button" onclick="submitCreateForm()" class="flex-1 py-4 rounded-2xl font-bold text-slate-900 bg-[#f4c400] hover:bg-[#eab308] shadow-md transition text-[17px]">Yes, Create</button>
            </div>
        </div>
    </div>

    <!-- DELETE MODAL -->
    <div id="delete-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-[#0f172a]/70 backdrop-blur-sm transition-opacity duration-300 opacity-0">
        <div id="delete-modal-content" class="bg-white rounded-[32px] p-8 max-w-md w-full mx-4 shadow-2xl transform scale-95 transition-transform duration-300">
            <div class="w-20 h-20 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-6 border-[8px] border-red-100/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3m-6 0h8" /></svg>
            </div>
            <h3 class="text-[26px] font-extrabold text-[#0f172a] text-center mb-3">Delete Order?</h3>
            <p class="text-[16px] text-[#64748b] text-center mb-8 px-4">Are you sure you want to permanently delete the order for <span id="delete-customer-name" class="font-bold text-slate-800"></span>? This cannot be undone.</p>
            <div class="flex gap-4">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 py-4 rounded-2xl font-bold text-[#64748b] bg-slate-100 hover:bg-slate-200 transition text-[17px]">Cancel</button>
                <button type="button" onclick="submitDeleteForm()" class="flex-1 py-4 rounded-2xl font-bold text-white bg-red-500 hover:bg-red-600 shadow-md transition text-[17px]">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('add-product').addEventListener('click', function() {
        const container = document.getElementById('product-container');
        const newProductField = document.createElement('div');
        newProductField.classList.add('product-item', 'flex', 'space-x-4', 'mt-4');
        newProductField.innerHTML = `
            <select name="product_name[]" required class="border border-[#e5e7eb] bg-white rounded-2xl px-5 py-4 text-[16px] w-full focus:outline-none focus:ring-2 focus:ring-[#f4c400] text-[#334155] cursor-pointer">
                <option value="" disabled selected>Select a Drink...</option>
                <option value="Classic Lemonade">Classic Lemonade</option>
                <option value="Mint Lemonade">Mint Lemonade</option>
                <option value="Strawberry Lemonade">Strawberry Lemonade</option>
                <option value="Grape Lemonade">Grape Lemonade</option>
            </select>
            <input type="number" name="price[]" placeholder="Price" min="0" step="0.01" required class="border border-[#e5e7eb] rounded-2xl px-5 py-4 text-[16px] w-full focus:outline-none focus:ring-2 focus:ring-[#f4c400]">
            <input type="number" name="quantity[]" placeholder="Quantity" min="1" required class="border border-[#e5e7eb] rounded-2xl px-5 py-4 text-[16px] w-full focus:outline-none focus:ring-2 focus:ring-[#f4c400]">
        `;
        container.appendChild(newProductField);
    });

    const createModal = document.getElementById('create-modal'), createModalContent = document.getElementById('create-modal-content'), createForm = document.getElementById('create-order-form');
    function openCreateModal() { if (!createForm.checkValidity()) { createForm.reportValidity(); return; } createModal.classList.remove('hidden'); setTimeout(() => { createModal.classList.remove('opacity-0'); createModal.classList.add('opacity-100'); createModalContent.classList.remove('scale-95'); createModalContent.classList.add('scale-100'); }, 10); }
    function closeCreateModal() { createModal.classList.remove('opacity-100'); createModal.classList.add('opacity-0'); createModalContent.classList.remove('scale-100'); createModalContent.classList.add('scale-95'); setTimeout(() => { createModal.classList.add('hidden'); }, 300); }
    function submitCreateForm() { createForm.submit(); }

    const deleteModal = document.getElementById('delete-modal'), deleteModalContent = document.getElementById('delete-modal-content'); let currentDeleteFormId = null;
    function openDeleteModal(orderId, customerName) { currentDeleteFormId = 'delete-form-' + orderId; document.getElementById('delete-customer-name').innerText = customerName; deleteModal.classList.remove('hidden'); setTimeout(() => { deleteModal.classList.remove('opacity-0'); deleteModal.classList.add('opacity-100'); deleteModalContent.classList.remove('scale-95'); deleteModalContent.classList.add('scale-100'); }, 10); }
    function closeDeleteModal() { deleteModal.classList.remove('opacity-100'); deleteModal.classList.add('opacity-0'); deleteModalContent.classList.remove('scale-100'); deleteModalContent.classList.add('scale-95'); setTimeout(() => { deleteModal.classList.add('hidden'); }, 300); currentDeleteFormId = null; }
    function submitDeleteForm() { if(currentDeleteFormId) document.getElementById(currentDeleteFormId).submit(); }
</script>
@endsection