@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-[#f6f7fb]">

    @include('cashier.partials.sidebar')

    <main class="flex-1 px-10 py-10">

        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-[38px] font-extrabold text-[#0f172a]">
                    Order Management
                </h1>
                <p class="text-[18px] text-[#64748b] mt-2">
                    Track and manage customer orders
                </p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-xl bg-green-100 px-5 py-4 text-green-700 font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-[24px] border border-[#e9edf3] p-7 mb-8 shadow-sm">
            <h2 class="text-[24px] font-bold text-[#0f172a] mb-6">
                New Order
            </h2>

            <form action="{{ route('cashier.orders.store') }}" method="POST" class="space-y-6">
                @csrf

                <input type="text" name="customer_name" placeholder="Customer Name" required
                    class="border border-[#e5e7eb] rounded-2xl px-5 py-4 text-[16px] w-full">

                <div id="product-container">
                    <div class="product-item flex space-x-4">
                        <input type="text" name="product_name[]" placeholder="Product Name" required
                            class="border border-[#e5e7eb] rounded-2xl px-5 py-4 text-[16px] w-full">
                        <input type="number" name="quantity[]" placeholder="Quantity" min="1" required
                            class="border border-[#e5e7eb] rounded-2xl px-5 py-4 text-[16px] w-full">
                    </div>
                </div>

                <button type="button" id="add-product"
                    class="text-blue-500 hover:underline">Add Another Product</button>

                <button type="submit"
                    class="bg-[#f4c400] hover:bg-[#eab308] text-black font-bold px-7 py-4 rounded-2xl text-[17px]">
                    Create Order
                </button>
            </form>
        </div>

        <div class="bg-white rounded-[24px] border border-[#e9edf3] overflow-hidden shadow-sm">
            <table class="w-full">
                <thead class="bg-[#f8fafc] text-[#64748b] text-left">
                    <tr>
                        <th class="px-8 py-6">ORDER ID</th>
                        <th class="px-8 py-6">CUSTOMER</th>
                        <th class="px-8 py-6">ITEM</th>
                        <th class="px-8 py-6">STATUS</th>
                    </tr>
                </thead>

              <tbody class="divide-y divide-[#eef2f7]">
    @foreach($orders->groupBy('customer_name') as $customerName => $customerOrders)
        <tr class="bg-gray-100">
            <td colspan="4" class="px-8 py-6 font-semibold">{{ $customerName }}</td>
        </tr>
        @foreach($customerOrders as $order)
            <tr>
                <td class="px-8 py-6 font-bold">
                    #{{ str_pad($order->id, 2, '0', STR_PAD_LEFT) }}
                </td>

                <td class="px-8 py-6">
                    {{ $order->customer_name }}
                </td>

                <td class="px-8 py-6">
                    {{ $order->quantity }}x {{ $order->product_name }}
                </td>

                <td class="px-8 py-6">
                    <span class="px-4 py-1 rounded-full 
                        @if($order->status == 'pending') bg-yellow-100 text-yellow-700 
                        @elseif($order->status == 'in_progress') bg-blue-100 text-blue-700 
                        @else bg-green-100 text-green-700 @endif 
                        font-medium">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>

                <!-- Add delete button -->
                <td class="px-8 py-6">
                    <form action="{{ route('cashier.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this order?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    @endforeach
</tbody>
            </table>
        </div>

    </main>
</div>

<script>
    // Add another product field dynamically
    document.getElementById('add-product').addEventListener('click', function() {
        const container = document.getElementById('product-container');
        const newProductField = document.createElement('div');
        newProductField.classList.add('product-item', 'flex', 'space-x-4');

        newProductField.innerHTML = `
            <input type="text" name="product_name[]" placeholder="Product Name" required
                class="border border-[#e5e7eb] rounded-2xl px-5 py-4 text-[16px] w-full">
            <input type="number" name="quantity[]" placeholder="Quantity" min="1" required
                class="border border-[#e5e7eb] rounded-2xl px-5 py-4 text-[16px] w-full">
        `;
        container.appendChild(newProductField);
    });
</script>
@endsection