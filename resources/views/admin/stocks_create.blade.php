@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-[#f6f7fb]">
    @include('admin.partials.sidebar')

    <main class="flex-1 px-10 py-10 relative">
        <div class="max-w-[900px] mx-auto">

            <div class="mb-6">
                <a href="{{ route('admin.inventory') }}"
                   class="text-[#64748b] hover:text-[#0f172a] flex items-center gap-2 font-medium transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Inventory
                </a>
            </div>

            <h1 class="text-[38px] font-extrabold text-[#0f172a] mb-2">
                Add New Stock
            </h1>

            <p class="text-[18px] text-[#64748b] mb-8">
                Add an ingredient or supply and choose which LemonHaus products will deduct it.
            </p>

            @if($errors->any())
                <div class="mb-6 rounded-2xl bg-red-100 text-red-700 px-5 py-4 font-semibold">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="bg-white rounded-[32px] border border-[#e9edf3] shadow-sm p-10">
                <form id="inventory-form" action="{{ route('admin.stocks.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-[15px] font-bold text-[#334155] mb-2 uppercase">
                            Stock Item Name
                        </label>

                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               class="w-full rounded-2xl border border-[#dbe3ec] px-5 py-4 focus:ring-2 focus:ring-[#f4c400] outline-none"
                               placeholder="Example: Lemon, Cup, Sugar, Strawberry Syrup"
                               required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[15px] font-bold text-[#334155] mb-2 uppercase">
                                Category
                            </label>

                            <select name="category"
                                    class="w-full rounded-2xl border border-[#dbe3ec] px-5 py-4 outline-none focus:ring-2 focus:ring-[#f4c400]"
                                    required>
                                <option value="Ingredients" {{ old('category') == 'Ingredients' ? 'selected' : '' }}>Ingredients</option>
                                <option value="Packaging" {{ old('category') == 'Packaging' ? 'selected' : '' }}>Packaging</option>
                                <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[15px] font-bold text-[#334155] mb-2 uppercase">
                                Unit Type
                            </label>

                            <input type="text"
                                   name="unit"
                                   value="{{ old('unit') }}"
                                   class="w-full rounded-2xl border border-[#dbe3ec] px-5 py-4 outline-none focus:ring-2 focus:ring-[#f4c400]"
                                   placeholder="pcs, kg, liters"
                                   required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-[15px] font-bold text-[#334155] mb-2 uppercase">
                                Quantity
                            </label>

                            <input type="number"
                                   name="stock_level"
                                   value="{{ old('stock_level') }}"
                                   min="0"
                                   class="w-full rounded-2xl border border-[#dbe3ec] px-5 py-4 outline-none focus:ring-2 focus:ring-[#f4c400]"
                                   placeholder="0"
                                   required>
                        </div>

                        <div>
                            <label class="block text-[15px] font-bold text-[#334155] mb-2 uppercase">
                                Min Alert
                            </label>

                            <input type="number"
                                   name="min_stock"
                                   value="{{ old('min_stock') }}"
                                   min="0"
                                   class="w-full rounded-2xl border border-[#dbe3ec] px-5 py-4 outline-none focus:ring-2 focus:ring-[#f4c400]"
                                   placeholder="10"
                                   required>
                        </div>

                        <div>
                            <label class="block text-[15px] font-bold text-[#334155] mb-2 uppercase">
                                Date Received
                            </label>

                            <input type="date"
                                   name="date_added"
                                   value="{{ old('date_added', date('Y-m-d')) }}"
                                   class="w-full rounded-2xl border border-[#dbe3ec] px-5 py-4 outline-none focus:ring-2 focus:ring-[#f4c400]"
                                   required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[15px] font-bold text-[#334155] mb-2 uppercase">
                            Expiration Date
                        </label>

                        <input type="date"
                               name="expiration_date"
                               value="{{ old('expiration_date') }}"
                               class="w-full rounded-2xl border border-[#dbe3ec] px-5 py-4 outline-none focus:ring-2 focus:ring-[#f4c400]">
                    </div>

                    <div class="border border-[#e9edf3] rounded-[26px] p-6 bg-[#f8fafc]">
                        <label class="block text-[15px] font-bold text-[#334155] mb-4 uppercase">
                            Used By Products
                        </label>

                        <div id="product-usage-container" class="space-y-4">
                            <div class="product-usage-row grid grid-cols-1 md:grid-cols-[1fr_180px_auto] gap-4 items-center">
                                <select name="product_ids[]"
                                        class="w-full rounded-2xl border border-[#dbe3ec] bg-white px-5 py-4 focus:ring-2 focus:ring-[#f4c400] outline-none">
                                    <option value="">Select Product</option>

                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <input type="number"
                                       name="quantity_used_per_order[]"
                                       value="1"
                                       min="1"
                                       class="w-full rounded-2xl border border-[#dbe3ec] bg-white px-5 py-4 outline-none focus:ring-2 focus:ring-[#f4c400]"
                                       placeholder="Used per order">

                                <button type="button"
                                        class="remove-product-usage hidden bg-red-100 text-red-600 font-bold px-5 py-4 rounded-2xl hover:bg-red-200 transition">
                                    Remove
                                </button>
                            </div>
                        </div>

                        <button type="button"
                                id="add-product-usage"
                                class="mt-4 bg-[#fff7cc] border border-[#f4c400]/40 text-[#8a6a00] font-bold px-5 py-3 rounded-2xl hover:bg-[#f4c400] hover:text-[#0f172a] transition">
                            + Add Another Product
                        </button>

                        <p class="text-sm text-[#64748b] mt-3">
                            Example: Lemon stock may be used by Classic Lemonade, Pink Lemonade, and Lemon Float.
                        </p>
                    </div>

                    <div class="bg-[#fff7cc] border border-[#f4c400]/40 rounded-2xl px-5 py-4 text-[#8a6a00] font-semibold">
                        If Classic Lemonade uses 1 lemon per order, select Classic Lemonade and set Used Per Order to 1.
                    </div>

                    <div class="pt-4">
                        <button type="button"
                                onclick="openModal()"
                                class="w-full py-5 bg-[#0f172a] text-white font-bold rounded-2xl shadow-lg hover:bg-slate-800 transition text-lg">
                            Save Item to Inventory
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <div id="custom-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm transition-opacity duration-300 opacity-0">
        <div id="modal-content" class="bg-white rounded-[32px] p-8 max-w-md w-full mx-4 shadow-2xl transform scale-95 transition-transform duration-300">

            <div class="w-20 h-20 rounded-full bg-yellow-50 flex items-center justify-center mx-auto mb-6 border-[8px] border-yellow-100/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>

            <h3 class="text-[26px] font-extrabold text-[#0f172a] text-center mb-3">
                Confirm New Stock
            </h3>

            <p class="text-[16px] text-[#64748b] text-center mb-8 px-4">
                Are you sure you want to add this item to the inventory?
            </p>

            <div class="flex gap-4">
                <button type="button"
                        onclick="closeModal()"
                        class="flex-1 py-4 rounded-2xl font-bold text-[#64748b] bg-slate-100 hover:bg-slate-200 transition text-[17px]">
                    Cancel
                </button>

                <button type="button"
                        onclick="submitForm()"
                        class="flex-1 py-4 rounded-2xl font-bold text-slate-900 bg-[#f4c400] hover:bg-[#eab308] shadow-md transition text-[17px]">
                    Yes, Save Item
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('custom-modal');
    const modalContent = document.getElementById('modal-content');
    const form = document.getElementById('inventory-form');

    document.getElementById('add-product-usage').addEventListener('click', function () {
        const container = document.getElementById('product-usage-container');
        const firstRow = container.querySelector('.product-usage-row');
        const newRow = firstRow.cloneNode(true);

        newRow.querySelector('select').value = '';
        newRow.querySelector('input').value = 1;
        newRow.querySelector('.remove-product-usage').classList.remove('hidden');

        container.appendChild(newRow);
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-product-usage')) {
            e.target.closest('.product-usage-row').remove();
        }
    });

    function openModal() {
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        modal.classList.remove('hidden');

        setTimeout(function () {
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);
    }

    function closeModal() {
        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');

        setTimeout(function () {
            modal.classList.add('hidden');
        }, 300);
    }

    function submitForm() {
        form.submit();
    }
</script>
@endsection