@extends('admin_layout.admin_index')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">

        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Manage Users</h2>
            <a href="{{ route('admin_add_user') }}" class="btn btn-primary">Add New User</a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">User</th>
                            <th scope="col">Username</th>
                            <th scope="col">Contact</th>
                            <th scope="col">Tax IDs</th>
                            <th scope="col">Address</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <th scope="row">{{ $user->id }}</th>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset($user->user_image) }}" alt="User"
                                            class="profile-pictures rounded-circle"
                                            style="object-fit: cover; width: 50px; height: 50px; margin-right: 10px;">
                                        <div>
                                            <div class="fw-bold">{{ $user->name }}</div>
                                            {{-- <div class="text-muted small">{{ $user->email }}</div> --}}
                                        </div>
                                        </img>
                                </td>

                                <td>{{ $user->username }}</td>
                                <td>
                                    <div class="small">
                                        <strong>Phone:</strong> {{ $user->phone }} <br>
                                        <strong>Email:</strong> {{ $user->email }}
                                    </div>
                                </td>

                                <td>
                                    <div class="small">
                                        <strong>PAN:</strong> {{ $user->pancard ?? 'N/A' }} <br>
                                        <strong>GSTIN:</strong> {{ $user->gstin ?? 'N/A' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="small">
                                        {{ $user->address }}, {{ $user->ps }} <br>
                                        {{ $user->district }}, {{ $user->state }} - {{ $user->pincode }}
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('admin_edit_user', $user->id) }}"
                                        class="btn btn-sm btn-outline-primary me-1">Edit</a>

                                    <a href="{{ route('admin_delete_user', $user->id) }}" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endsection
