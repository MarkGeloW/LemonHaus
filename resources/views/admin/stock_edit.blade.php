@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-[#f6f7fb]">
    @include('admin.partials.sidebar')

    <main class="flex-1 px-10 py-10">
        <div class="max-w-[800px] mx-auto">
            <a href="{{ route('admin.inventory') }}" class="text-[#64748b] hover:text-[#0f172a] font-bold">
                Back to Inventory
            </a>

            <h1 class="text-[38px] font-extrabold text-[#0f172a] mt-6 mb-8">
                Edit Stock
            </h1>

            @if($errors->any())
                <div class="mb-6 rounded-2xl bg-red-100 text-red-700 px-5 py-4 font-semibold">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="bg-white rounded-[30px] border border-[#e9edf3] shadow-sm p-8">
                <form action="{{ route('admin.stocks.update', $stock->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <input type="text" name="name" value="{{ old('name', $stock->name) }}" required
                           class="w-full rounded-2xl border border-[#dbe3ec] px-5 py-4 outline-none focus:ring-2 focus:ring-[#f4c400]"
                           placeholder="Stock Name">

                    <input type="text" name="category" value="{{ old('category', $stock->category) }}" required
                           class="w-full rounded-2xl border border-[#dbe3ec] px-5 py-4 outline-none focus:ring-2 focus:ring-[#f4c400]"
                           placeholder="Category">

                    <input type="number" name="stock_level" value="{{ old('stock_level', $stock->stock_level) }}" required
                           class="w-full rounded-2xl border border-[#dbe3ec] px-5 py-4 outline-none focus:ring-2 focus:ring-[#f4c400]"
                           placeholder="Stock Level">

                    <input type="text" name="unit" value="{{ old('unit', $stock->unit) }}" required
                           class="w-full rounded-2xl border border-[#dbe3ec] px-5 py-4 outline-none focus:ring-2 focus:ring-[#f4c400]"
                           placeholder="Unit">

                    <input type="number" name="min_stock" value="{{ old('min_stock', $stock->min_stock) }}" required
                           class="w-full rounded-2xl border border-[#dbe3ec] px-5 py-4 outline-none focus:ring-2 focus:ring-[#f4c400]"
                           placeholder="Minimum Stock">

                    <input type="date" name="date_added"
                           value="{{ old('date_added', $stock->date_added ? $stock->date_added->format('Y-m-d') : $stock->created_at->format('Y-m-d')) }}"
                           required
                           class="w-full rounded-2xl border border-[#dbe3ec] px-5 py-4 outline-none focus:ring-2 focus:ring-[#f4c400]">

                    <input type="date" name="expiration_date"
                           value="{{ old('expiration_date', $stock->expiration_date ? $stock->expiration_date->format('Y-m-d') : '') }}"
                           class="w-full rounded-2xl border border-[#dbe3ec] px-5 py-4 outline-none focus:ring-2 focus:ring-[#f4c400]">

                    <button type="submit"
                            class="w-full rounded-2xl bg-[#0f172a] text-white font-bold py-5 hover:bg-[#1e293b] transition">
                        Update Stock
                    </button>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection