@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-[#f6f7fb]">
    @include('admin.partials.sidebar')

    <main class="flex-1 px-10 py-10">
        <div class="max-w-[800px] mx-auto">
            
            <div class="mb-6">
                <a href="{{ route('inventory.index') }}" class="text-[#64748b] hover:text-[#0f172a] flex items-center gap-2 font-medium transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Inventory
                </a>
            </div>

            <h1 class="text-[38px] font-extrabold text-[#0f172a] mb-8">Add New Stock</h1>

            <div class="bg-white rounded-[32px] border border-[#e9edf3] shadow-sm p-10">
                <form action="{{ route('inventory.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-[15px] font-bold text-[#334155] mb-2 uppercase">Item Name</label>
                        <input type="text" name="name" class="w-full rounded-2xl border border-[#dbe3ec] px-5 py-4 focus:ring-2 focus:ring-[#f4c400] outline-none" placeholder="e.g. Strawberry Syrup" required>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[15px] font-bold text-[#334155] mb-2 uppercase">Category</label>
                            <select name="category" class="w-full rounded-2xl border border-[#dbe3ec] px-5 py-4 outline-none">
                                <option value="Ingredients">Ingredients</option>
                                <option value="Packaging">Packaging</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[15px] font-bold text-[#334155] mb-2 uppercase">Unit Type</label>
                            <input type="text" name="unit" class="w-full rounded-2xl border border-[#dbe3ec] px-5 py-4 outline-none" placeholder="pcs, kg, liters" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[15px] font-bold text-[#334155] mb-2 uppercase">Quantity</label>
                            <input type="number" name="stock_level" class="w-full rounded-2xl border border-[#dbe3ec] px-5 py-4 outline-none" placeholder="0" required>
                        </div>
                        <div>
                            <label class="block text-[15px] font-bold text-[#334155] mb-2 uppercase">Min. Alert Level</label>
                            <input type="number" name="min_stock" class="w-full rounded-2xl border border-[#dbe3ec] px-5 py-4 outline-none" placeholder="10" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[15px] font-bold text-[#334155] mb-2 uppercase">Date Received</label>
                            <input type="date" name="date_added" value="{{ date('Y-m-d') }}" class="w-full rounded-2xl border border-[#dbe3ec] px-5 py-4 outline-none">
                        </div>
                        <div>
                            <label class="block text-[15px] font-bold text-[#334155] mb-2 uppercase">Expiration Date</label>
                            <input type="date" name="expiration_date" class="w-full rounded-2xl border border-[#dbe3ec] px-5 py-4 outline-none" required>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full py-5 bg-[#0f172a] text-white font-bold rounded-2xl shadow-lg hover:bg-slate-800 transition text-lg">
                            Save Item to Inventory
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection