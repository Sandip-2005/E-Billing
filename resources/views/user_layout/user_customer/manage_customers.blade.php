@extends('user_layout.user_index')

@section('content')
<div class="container py-4">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0" style="color: #ffffff;"><i class="bi bi-people-fill"></i> Manage Customers</h3>
            <a href="{{ route('add_customer') }}" class="btn btn-light btn-sm" style="color: #007bff;">
                <i class="bi bi-plus-circle"></i> Add Customer
            </a>
        </div>
        <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="color: #007bff;">Customer ID</th>
                        <th scope="col" style="color: #007bff;">Customer Name</th>
                        <th scope="col" style="color: #007bff;">Email</th>
                        <th scope="col" style="color: #007bff;">Phone Number</th>
                        <th scope="col" style="color: #007bff;">Address</th>
                        <th scope="col" style="color: #007bff;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                    <tr>
                        <td style="color: #495057;">{{ $customer->id }}</td>
                        <td style="color: #495057;">{{ $customer->customer_name }}</td>
                        <td style="color: #495057;">{{ $customer->email }}</td>
                        <td style="color: #495057;">{{ $customer->phone_number }}</td>
                        <td style="color: #495057;">{{ $customer->address }}</td>
                        <td>
                            <a href="{{ route('edit_customer', $customer->id) }}" class="btn btn-sm btn-primary mt-1 mb-1">Edit</a>
                            <a href="{{ route('delete_customer', $customer->id) }}" class="btn btn-sm btn-danger mt-1 mb-1"
                            onclick="return confirm('Are you sure you want to delete this customer?')">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
@endsection
