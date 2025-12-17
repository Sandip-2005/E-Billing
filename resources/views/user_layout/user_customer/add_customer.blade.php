@extends('user_layout.user_index')

@section('content')
<div class="add-customer-body">
    <div class="form-container">
        <div class="card form-card">
            <div class="card-body">

                {{-- Form Header --}}
                <div class="form-header">
                    <h2 class="card-title">Add New Customer</h2>
                    <p class="card-subtitle mb-4">Fill out the details below to create a new customer profile.</p>
                </div>

                {{-- Add Customer Form --}}
                <form action="{{ route('save_customer') }}" method="POST">
                    @csrf

                    <!-- Customer Name -->
                    <div class="form-group mb-3">
                        <label for="customerName" class="form-label">Customer Name</label>
                        <div class="input-group-icon">
                            <i class="fa-solid fa-user"></i>
                            <input type="text" class="form-control" id="customerName" name="customer_name" placeholder="e.g., John Doe" required>
                        </div>
                    </div>

                    <!-- Email Address -->
                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group-icon">
                            <i class="fa-solid fa-envelope"></i>
                            <input type="email" class="form-control" id="email" name="email" placeholder="e.g., john.doe@example.com">
                        </div>
                    </div>

                    <!-- Phone Number -->
                    <div class="form-group mb-3">
                        <label for="phoneNumber" class="form-label">Phone Number</label>
                        <div class="input-group-icon">
                            <i class="fa-solid fa-phone"></i>
                            <input type="tel" class="form-control" id="phoneNumber" name="phone_number" placeholder="e.g., +91 98765 43210" required>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="form-group mb-3">
                        <label for="address" class="form-label">Address</label>
                        <div class="input-group-icon">
                            <i class="fa-solid fa-map-marker-alt"></i>
                            <input type="text" class="form-control" id="address" name="address" placeholder="e.g., 123 Main Street, Anytown" required>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions mt-4">
                        <button class="btn btn-secondary"><a style="text-decoration: none; color: inherit;" href="{{ route('user_dashboard') }}">Cancel</a></button>
                        <button type="submit" class="btn btn-primary">Save Customer</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection