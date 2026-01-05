@extends('user_layout.user_index')

@section('content')
    <div class="container py-4">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="mb-0"><i class="bi bi-box-seam"></i> Manage Products</h3>
                <a href="{{ route('add_products') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-plus-circle"></i> Add Product
                </a>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('stock_alert') }}" class="mb-3">
                    <label for="shop_id" class="form-label">Filter by Shop:</label>
                    <select name="shop_id" id="shop_id" class="form-control" onchange="this.form.submit()">
                        <option value="">All Shops</option>
                        @foreach ($shops as $shop)
                            <option value="{{ $shop->id }}" {{ $shop_id == $shop->id ? 'selected' : '' }}>
                                {{ $shop->shop_name }}
                            </option>
                        @endforeach
                    </select>
                </form>
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Shop</th>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Price (₹)</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->shop->shop_name ?? '-' }}</td>
                                <td>{{ $product->product_id ?? '-' }}</td>
                                <td>{{ $product->product_name ?? '-' }}</td>
                                <td>₹{{ number_format($product->price, 2) }}</td>
                                <td>{{ $product->quantity }}</td>
                                <td>
                                    <a href="{{ route('edit_products', $product->id) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <a href="{{ route('delete_products', $product->id) }}" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Delete {{ $product->product_name }}?');">
                                        <i class="bi bi-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No products found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
