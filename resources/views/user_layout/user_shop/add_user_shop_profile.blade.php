@extends('user_layout.user_index')
@section('content')
    <div class="container">
        <h1 class="mt-4">Shop Profile</h1>
        <form method="POST" action="{{ route('store_user_shop_profile') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="shop_name" class="form-label">Shop Name</label>
                <input type="text" class="form-control" id="shop_name" name="shop_name" value="{{ old('shop_name') }}">
            </div>
            
            <div class="mb-3">
                <label for="shop_contact" class="form-label">Shop Contact Number</label>
                <input type="text" class="form-control" id="shop_contact" name="shop_contact" value="{{ old('shop_contact') }}">
            </div>
            <div class="mb-3">
                <label for="gst_number" class="form-label">GSTIN</label>
                <input type="text" class="form-control" id="gst_number" name="gst_number" value="{{ old('gst_number') }}">
            </div>
            <div class="mb-3">
                <label for="shop_address" class="form-label">Shop Address</label>
                <input type="text" class="form-control" id="shop_address" name="shop_address" value="{{ old('shop_address') }}">
            </div>
            <div class="mb-3">
                <label for="shop_logo" class="form-label">Shop Logo/image</label>
                <input type="file" class="form-control" id="shop_logo" name="shop_logo">
            </div>
            <button type="submit" class="btn btn-primary">Save Profile</button>
            <a href="{{ route('user_shop_profile') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection