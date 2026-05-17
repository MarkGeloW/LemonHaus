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
                Edit Product
            </h1>

            @if($errors->any())
                <div class="mb-6 rounded-2xl bg-red-100 text-red-700 px-5 py-4 font-semibold">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="bg-white rounded-[30px] border border-[#e9edf3] shadow-sm p-8">
                <form action="{{ route('admin.products.update', $product->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                           class="w-full rounded-2xl border border-[#dbe3ec] px-5 py-4 outline-none focus:ring-2 focus:ring-[#f4c400]"
                           placeholder="Product Name">

                    <input type="text" name="category" value="{{ old('category', $product->category) }}" required
                           class="w-full rounded-2xl border border-[#dbe3ec] px-5 py-4 outline-none focus:ring-2 focus:ring-[#f4c400]"
                           placeholder="Category">

                    <textarea name="description"
                              class="w-full rounded-2xl border border-[#dbe3ec] px-5 py-4 outline-none focus:ring-2 focus:ring-[#f4c400]"
                              placeholder="Description">{{ old('description', $product->description) }}</textarea>

                    <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required
                           class="w-full rounded-2xl border border-[#dbe3ec] px-5 py-4 outline-none focus:ring-2 focus:ring-[#f4c400]"
                           placeholder="Price">

                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required
                           class="w-full rounded-2xl border border-[#dbe3ec] px-5 py-4 outline-none focus:ring-2 focus:ring-[#f4c400]"
                           placeholder="Stock">

                    <select name="status" required
                            class="w-full rounded-2xl border border-[#dbe3ec] px-5 py-4 outline-none focus:ring-2 focus:ring-[#f4c400]">
                        <option value="available" {{ old('status', $product->status) === 'available' ? 'selected' : '' }}>
                            Available
                        </option>

                        <option value="unavailable" {{ old('status', $product->status) === 'unavailable' ? 'selected' : '' }}>
                            Unavailable
                        </option>
                    </select>

                    <button type="submit"
                            class="w-full rounded-2xl bg-[#0f172a] text-white font-bold py-5 hover:bg-[#1e293b] transition">
                        Update Product
                    </button>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection