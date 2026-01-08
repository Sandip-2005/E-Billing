@extends('user_layout.user_index')

@section('content')
    <div class="container py-4">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="mb-0" style="color: #ffffff;"><i class="bi bi-plus-circle"></i> Add Product</h3>
                <a href="{{ route('manage_products') }}" class="btn btn-light btn-sm" style="color: #007bff;">
                    <i class="bi bi-plus-circle"></i> Manage Products
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('store_products') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="shop_id" class="form-label" style="color: #495057;">Select Shop</label>
                        <select name="shop_id" id="shop_id" class="form-control" required>
                            <option value="">Choose Shop</option>
                            @foreach ($shops as $shop)
                                <option value="{{ $shop->id }}">{{ $shop->shop_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="product_id" class="form-label" style="color: #495057;">Product ID</label>
                        <input type="text" name="product_id" id="product_id" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="product_name" class="form-label" style="color: #495057;">Product Name</label>
                        <input type="text" name="product_name" id="product_name" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="batch_no" class="form-label" style="color: #495057;">Batch No</label>
                        <input type="text" name="batch_no" id="batch_no" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="expiry_date" class="form-label" style="color: #495057;">Expiry Date</label>
                        <input type="date" name="expiry_date" id="expiry_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="purchase_price" class="form-label" style="color: #495057;">Purchase Price (₹)</label>
                        <input type="number" name="purchase_price" id="purchase_price" class="form-control" step="0.01">
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label" style="color: #495057;">Selling Price (₹)</label>
                        <input type="number" name="price" id="price" class="form-control" step="0.01">
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label" style="color: #495057;">Quantity in Stock</label>
                        <input type="number" name="quantity" id="quantity" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </form>
            </div>
        </div>
    </div>
@endsection
