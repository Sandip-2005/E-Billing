@extends('user_layout.user_index')
@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-2">My Shop Profile</h1>
                <p class="text-muted">Manage your shop details and business information</p>
            </div>
            <button class="btn btn-primary" onclick="window.location='{{ route('add_user_shop_profile') }}'">
                <i class="bi bi-plus-circle me-2"></i>Add/Edit Shop
            </button>
        </div>

        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($shops->count() > 0)
            @foreach ($shops as $shop)
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 text-center mb-3 mb-md-0">
                                @if ($shop->shop_logo)
                                    <img src="{{ asset($shop->shop_logo) }}" alt="Shop Logo" class="img-fluid rounded"
                                        style="max-width: 150px; height: auto;">
                                @else
                                    <div class="border rounded p-3 text-muted">
                                        <i class="bi bi-shop fs-1"></i>
                                        <p class="small mt-2">No logo uploaded</p>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-9">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h4 class="text-primary mb-0">{{ $shop->shop_name }}</h4>
                                    <div>
                                        <a href="{{ route('edit_user_shop_profile', $shop->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil me-1"></i>Edit
                                        </a>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="detail-item">
                                            <i class="bi bi-telephone me-2"></i>
                                            <strong>Contact:</strong>
                                            <span>{{ $shop->shop_contact }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="detail-item">
                                            <i class="bi bi-receipt me-2"></i>
                                            <strong>GSTIN:</strong>
                                            <span>{{ $shop->gst_number }}</span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="detail-item">
                                            <i class="bi bi-geo-alt me-2"></i>
                                            <strong>Address:</strong>
                                            <span>{{ $shop->shop_address }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-shop fs-1 text-muted"></i>
                </div>
                <h3>No Shop Profile Found</h3>
                <p class="text-muted">Create your shop profile to start managing your business</p>
            </div>
        @endif
    </div>

    <style>
        .detail-item {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-item strong {
            color: #6c757d;
            margin-right: 8px;
        }

        .card {
            border: none;
            border-radius: 10px;
        }

        .btn-primary {
            padding: 8px 20px;
            border-radius: 6px;
        }
    </style>
@endsection
