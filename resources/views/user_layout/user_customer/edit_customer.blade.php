@extends('user_layout.user_index')

@section('content')

<div>
    <form method="POST" action="{{ route('update_customer', $customer->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="customer_name" class="form-label">Customer Name</label>
            <input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ $customer->customer_name }}">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $customer->email }}">
        </div>
        <div class="mb-3">
            <label for="phone_number" class="form-label">Phone Number</label>
            <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ $customer->phone_number }}">
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" id="address" name="address" value="{{ $customer->address }}">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('manage_customers') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

@endsection