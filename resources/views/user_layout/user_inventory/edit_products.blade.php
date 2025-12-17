@extends('user_layout.user_index')

@section('content')
    <div class="container py-4">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0"><i class="bi bi-pencil-square"></i> Edit Product</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('update_products', $product->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="shop_id" class="form-label">Select Shop</label>
                        <select name="shop_id" id="shop_id" class="form-control" required>
                            <option value="">Choose Shop</option>
                            @foreach ($shops as $shop)
                                <option value="{{ $shop->id }}" {{ $product->shop_id == $shop->id ? 'selected' : '' }}>{{ $shop->shop_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="product_id" class="form-label">Product ID</label>
                        <input type="text" name="product_id" id="product_id" class="form-control" value="{{ $product->product_id }}">
                    </div>
                    <div class="mb-3">
                        <label for="product_name" class="form-label">Product Name</label>
                        <input type="text" name="product_name" id="product_name" class="form-control" value="{{ $product->product_name }}">
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price (₹)</label>
                        <input type="number" name="price" id="price" class="form-control" step="0.01" value="{{ $product->price }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity in Stock</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" value="{{ $product->quantity }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Product</button>
                </form>
            </div>
        </div>
    </div>
@endsection
