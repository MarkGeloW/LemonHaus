@extends('layouts.app')

@section('content')
<style>
    .product-page {
        min-height: 100vh;
        display: flex;
        background:
            radial-gradient(circle at top right, rgba(244, 196, 0, 0.18), transparent 30%),
            radial-gradient(circle at bottom left, rgba(183, 230, 216, 0.35), transparent 35%),
            #f6f7fb;
    }

    .product-main {
        flex: 1;
        padding: 44px;
    }

    .product-wrapper {
        max-width: 980px;
        margin: 0 auto;
    }

    .page-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: #fff7cc;
        color: #8a6a00;
        padding: 10px 16px;
        border-radius: 999px;
        font-weight: 800;
        margin-bottom: 18px;
        border: 1px solid rgba(244, 196, 0, 0.35);
    }

    .badge-dot {
        width: 10px;
        height: 10px;
        background: #f4c400;
        border-radius: 999px;
    }

    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 30px;
    }

    .page-title {
        font-size: 42px;
        line-height: 1.1;
        font-weight: 900;
        color: #0f172a;
        letter-spacing: -1px;
    }

    .page-subtitle {
        font-size: 18px;
        color: #64748b;
        margin-top: 10px;
    }

    .form-card {
        background: #ffffff;
        border: 1px solid #e9edf3;
        border-radius: 34px;
        overflow: hidden;
        box-shadow: 0 24px 70px rgba(15, 23, 42, 0.08);
    }

    .form-card-header {
        position: relative;
        padding: 34px;
        background: linear-gradient(135deg, #fffdf2, #ffffff);
        border-bottom: 1px solid #eef2f7;
        overflow: hidden;
    }

    .form-card-header::before {
        content: "";
        position: absolute;
        width: 170px;
        height: 170px;
        background: rgba(244, 196, 0, 0.18);
        border-radius: 50%;
        top: -70px;
        right: -45px;
    }

    .form-card-header::after {
        content: "";
        position: absolute;
        width: 90px;
        height: 90px;
        background: rgba(183, 230, 216, 0.55);
        border-radius: 50%;
        bottom: -35px;
        right: 120px;
    }

    .form-card-header-content {
        position: relative;
        z-index: 2;
    }

    .form-card-title {
        font-size: 27px;
        font-weight: 900;
        color: #0f172a;
    }

    .form-card-text {
        color: #64748b;
        margin-top: 6px;
    }

    .product-form {
        padding: 34px;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 22px;
    }

    .form-label {
        display: block;
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 9px;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        border: 1px solid #dbe3ef;
        background: #fbfdff;
        border-radius: 20px;
        padding: 16px 18px;
        font-size: 17px;
        color: #0f172a;
        outline: none;
        transition: 0.2s ease;
    }

    .form-textarea {
        resize: vertical;
        min-height: 130px;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        border-color: #f4c400;
        box-shadow: 0 0 0 5px rgba(244, 196, 0, 0.16);
        background: #ffffff;
    }

    .price-wrap {
        position: relative;
    }

    .price-symbol {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        font-weight: 900;
        color: #64748b;
    }

    .price-input {
        padding-left: 42px;
    }

    .form-actions {
        display: flex;
        gap: 16px;
        padding-top: 10px;
        flex-wrap: wrap;
    }

    .btn-primary {
        background: #f4c400;
        color: #111827;
        border: none;
        border-radius: 20px;
        padding: 16px 28px;
        font-size: 18px;
        font-weight: 900;
        cursor: pointer;
        transition: 0.2s ease;
        box-shadow: 0 10px 22px rgba(244, 196, 0, 0.28);
    }

    .btn-primary:hover {
        background: #eab308;
        transform: translateY(-1px);
    }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #ffffff;
        border: 1px solid #dbe3ef;
        color: #0f172a;
        border-radius: 20px;
        padding: 16px 28px;
        font-size: 18px;
        font-weight: 900;
        text-decoration: none;
        transition: 0.2s ease;
    }

    .btn-secondary:hover {
        background: #f8fafc;
        transform: translateY(-1px);
    }

    .alert-success {
        margin-bottom: 22px;
        border-radius: 20px;
        border: 1px solid #bbf7d0;
        background: #f0fdf4;
        color: #15803d;
        padding: 16px 20px;
        font-weight: 800;
    }

    .alert-error {
        margin-bottom: 22px;
        border-radius: 20px;
        border: 1px solid #fecaca;
        background: #fef2f2;
        color: #b91c1c;
        padding: 16px 20px;
        font-weight: 700;
    }

    .helper-panel {
        margin-top: 28px;
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .helper-card {
        background: #ffffff;
        border: 1px solid #e9edf3;
        border-radius: 24px;
        padding: 22px;
        box-shadow: 0 14px 35px rgba(15, 23, 42, 0.05);
    }

    .helper-card h3 {
        font-size: 18px;
        font-weight: 900;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .helper-card p {
        color: #64748b;
        font-size: 15px;
        line-height: 1.5;
    }

    @media (max-width: 900px) {
        .product-main {
            padding: 28px;
        }

        .page-header {
            flex-direction: column;
        }

        .page-title {
            font-size: 34px;
        }

        .form-grid,
        .helper-panel {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="product-page">
    @include('admin.partials.sidebar')

    <main class="product-main">
        <div class="product-wrapper">

            <div class="page-header">
                <div>
                    <div class="page-badge">
                        <span class="badge-dot"></span>
                        LemonHaus Product Setup
                    </div>

                    <h1 class="page-title">
                        Add LemonHaus Product
                    </h1>

                    <p class="page-subtitle">
                        Add a selling product for cashier orders and product inventory.
                    </p>
                </div>

                <a href="{{ route('admin.inventory') }}" class="btn-secondary">
                    Back to Inventory
                </a>
            </div>

            @if(session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert-error">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-header-content">
                        <h2 class="form-card-title">
                            Product Information
                        </h2>
                        <p class="form-card-text">
                            Fill in the product details below.
                        </p>
                    </div>
                </div>

                <form action="{{ route('admin.products.store') }}" method="POST" class="product-form">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">
                            Product Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Example: Classic Lemonade"
                            class="form-input"
                            required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Category
                        </label>

                        <select name="category" class="form-select" required>
                            <option value="">Select category</option>
                            <option value="Drinks" {{ old('category') == 'Drinks' ? 'selected' : '' }}>Drinks</option>
                            <option value="Snacks" {{ old('category') == 'Snacks' ? 'selected' : '' }}>Snacks</option>
                            <option value="Dessert" {{ old('category') == 'Dessert' ? 'selected' : '' }}>Dessert</option>
                            <option value="Combo" {{ old('category') == 'Combo' ? 'selected' : '' }}>Combo</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Description
                        </label>

                        <textarea
                            name="description"
                            rows="4"
                            placeholder="Example: Fresh lemon drink with sweet and sour taste."
                            class="form-textarea">{{ old('description') }}</textarea>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                Price
                            </label>

                            <div class="price-wrap">
                                <span class="price-symbol">₱</span>

                                <input
                                    type="number"
                                    name="price"
                                    value="{{ old('price') }}"
                                    step="0.01"
                                    min="0"
                                    placeholder="39.00"
                                    class="form-input price-input"
                                    required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Initial Stock
                            </label>

                            <input
                                type="number"
                                name="stock"
                                value="{{ old('stock') }}"
                                min="0"
                                placeholder="Example: 50"
                                class="form-input"
                                required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Status
                        </label>

                        <select name="status" class="form-select" required>
                            <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="unavailable" {{ old('status') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">
                            Save Product
                        </button>

                        <a href="{{ route('admin.inventory') }}" class="btn-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

            <div class="helper-panel">
                <div class="helper-card">
                    <h3>Product</h3>
                    <p>Use this for items sold by the cashier, like lemonade, desserts, snacks, and combo meals.</p>
                </div>

                <div class="helper-card">
                    <h3>Stock</h3>
                    <p>This stock value decreases when cashier orders are saved.</p>
                </div>

                <div class="helper-card">
                    <h3>Status</h3>
                    <p>Choose Available for products shown in ordering. Choose Unavailable to hide the product.</p>
                </div>
            </div>

        </div>
    </main>
</div>
@endsection