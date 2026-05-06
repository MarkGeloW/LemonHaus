@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-[#f6f7fb]">
    @include('kitchen.partials.sidebar')

    <main class="flex-1 px-10 py-10 relative">
        <div class="max-w-[800px] mx-auto">
            <div class="mb-6">
                <a href="{{ route('kitchen.inventory') }}" class="text-[#64748b] hover:text-[#0f172a] flex items-center gap-2 font-medium transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Back to Inventory
                </a>
            </div>

            <h1 class="text-[38px] font-extrabold text-[#0f172a] mb-8">Add New Stock Arrival</h1>

            <div class="bg-white rounded-[32px] border border-[#e9edf3] shadow-sm p-10">
                <form id="kitchen-inventory-form" action="{{ route('kitchen.inventory.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-[15px] font-bold text-[#334155] mb-2 uppercase">Item Name</label>
                        <input type="text" name="name" class="w-full rounded-2xl border border-[#dbe3ec] px-5 py-4 focus:ring-2 focus:ring-[#f4c400] outline-none" placeholder="e.g. Fresh Lemons" required>
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
                            <label class="block text-[15px] font-bold text-[#334155] mb-2 uppercase">Quantity Received</label>
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
                        <button type="button" onclick="openKitchenInventoryModal()" class="w-full py-5 bg-[#0f172a] text-white font-bold rounded-2xl shadow-lg hover:bg-slate-800 transition text-lg">
                            Log New Inventory
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- MODAL -->
    <div id="kitchen-inventory-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm transition-opacity duration-300 opacity-0">
        <div id="kitchen-inventory-content" class="bg-white rounded-[32px] p-8 max-w-md w-full mx-4 shadow-2xl transform scale-95 transition-transform duration-300">
            <div class="w-20 h-20 rounded-full bg-yellow-50 flex items-center justify-center mx-auto mb-6 border-[8px] border-yellow-100/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            </div>
            <h3 class="text-[26px] font-extrabold text-[#0f172a] text-center mb-3">Confirm Stock Arrival</h3>
            <p class="text-[16px] text-[#64748b] text-center mb-8 px-4">Are you sure you want to add this new stock to the inventory system?</p>
            <div class="flex gap-4">
                <button type="button" onclick="closeKitchenInventoryModal()" class="flex-1 py-4 rounded-2xl font-bold text-[#64748b] bg-slate-100 hover:bg-slate-200 transition text-[17px]">Cancel</button>
                <button type="button" onclick="submitKitchenInventoryForm()" class="flex-1 py-4 rounded-2xl font-bold text-slate-900 bg-[#f4c400] hover:bg-[#eab308] shadow-md transition text-[17px]">Yes, Log Item</button>
            </div>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('kitchen-inventory-modal'), modalContent = document.getElementById('kitchen-inventory-content'), form = document.getElementById('kitchen-inventory-form');
    function openKitchenInventoryModal() { if (!form.checkValidity()) { form.reportValidity(); return; } modal.classList.remove('hidden'); setTimeout(() => { modal.classList.remove('opacity-0'); modal.classList.add('opacity-100'); modalContent.classList.remove('scale-95'); modalContent.classList.add('scale-100'); }, 10); }
    function closeKitchenInventoryModal() { modal.classList.remove('opacity-100'); modal.classList.add('opacity-0'); modalContent.classList.remove('scale-100'); modalContent.classList.add('scale-95'); setTimeout(() => { modal.classList.add('hidden'); }, 300); }
    function submitKitchenInventoryForm() { form.submit(); }
</script>
@endsection