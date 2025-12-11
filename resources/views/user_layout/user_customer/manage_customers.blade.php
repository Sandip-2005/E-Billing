@extends('user_layout.user_index')

@section('content')
<div class="card">
    <div class="card-header">
        <h2 class="card-title mb-0">Manage Customers</h2>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped text-center">
                <thead>
                    <tr>
                        <th scope="col">Customer ID</th>
                        <th scope="col">Customer Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone Number</th>
                        <th scope="col">Address</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                    <tr>
                        <th scope="row">{{ $customer->id }}</th>
                        <td>{{ $customer->customer_name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->phone_number }}</td>
                        <td>{{ $customer->address }}</td>
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

@endsection
