@extends('layout.index')
@section('content')

<!-- Hero Section -->
<section class="services-hero py-5 bg-light text-center">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">Our Services</h1>
        <p class="lead text-muted mx-auto" style="max-width: 700px;">
            Discover the comprehensive suite of tools designed to streamline your billing, inventory, and customer management processes.
        </p>
    </div>
</section>

<!-- Services Grid -->
<section class="services-list py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Service 1: Smart Invoicing -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm service-card">
                    <div class="card-body p-4 text-center">
                        <div class="icon-wrapper mb-3 text-primary">
                            <i class="bi bi-receipt-cutoff fs-1"></i>
                        </div>
                        <h3 class="h4 card-title fw-bold">Smart Invoicing</h3>
                        <p class="card-text text-muted">
                            Create professional, GST-compliant invoices in seconds. Support for multiple payment modes, tax calculations, and discounts.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Service 2: Inventory Management -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm service-card">
                    <div class="card-body p-4 text-center">
                        <div class="icon-wrapper mb-3 text-success">
                            <i class="bi bi-box-seam fs-1"></i>
                        </div>
                        <h3 class="h4 card-title fw-bold">Inventory Control</h3>
                        <p class="card-text text-muted">
                            Track stock levels in real-time with batch-wise management. Automatically deduct stock upon sales and get low-stock alerts.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Service 3: Customer Management -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm service-card">
                    <div class="card-body p-4 text-center">
                        <div class="icon-wrapper mb-3 text-info">
                            <i class="bi bi-people fs-1"></i>
                        </div>
                        <h3 class="h4 card-title fw-bold">Customer CRM</h3>
                        <p class="card-text text-muted">
                            Maintain a detailed database of your customers. Track purchase history, contact details, and outstanding dues effortlessly.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Service 4: Reports & Analytics -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm service-card">
                    <div class="card-body p-4 text-center">
                        <div class="icon-wrapper mb-3 text-warning">
                            <i class="bi bi-graph-up fs-1"></i>
                        </div>
                        <h3 class="h4 card-title fw-bold">Reports & Analytics</h3>
                        <p class="card-text text-muted">
                            Gain insights into your business performance. View daily sales, payment reports, and track due amounts to maintain healthy cash flow.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Service 5: Multi-Shop Support -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm service-card">
                    <div class="card-body p-4 text-center">
                        <div class="icon-wrapper mb-3 text-danger">
                            <i class="bi bi-shop fs-1"></i>
                        </div>
                        <h3 class="h4 card-title fw-bold">Multi-Shop Management</h3>
                        <p class="card-text text-muted">
                            Manage multiple business locations or shops from a single account. Switch between profiles seamlessly.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Service 6: Digital Delivery -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm service-card">
                    <div class="card-body p-4 text-center">
                        <div class="icon-wrapper mb-3 text-primary">
                            <i class="bi bi-envelope-paper fs-1"></i>
                        </div>
                        <h3 class="h4 card-title fw-bold">Digital Delivery</h3>
                        <p class="card-text text-muted">
                            Go paperless. Generate PDF invoices instantly and email them directly to your customers with a single click.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Process Section -->
<section class="process-section py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">How It Works</h2>
            <p class="text-muted">Simple steps to get your billing sorted.</p>
        </div>
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="process-step">
                    <span class="display-4 fw-bold text-light bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">1</span>
                    <h4>Add Inventory</h4>
                    <p class="text-muted px-4">Input your products, set prices, and manage batches.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="process-step">
                    <span class="display-4 fw-bold text-light bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">2</span>
                    <h4>Create Invoice</h4>
                    <p class="text-muted px-4">Select customer, add items, and generate the bill.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="process-step">
                    <span class="display-4 fw-bold text-light bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">3</span>
                    <h4>Track & Grow</h4>
                    <p class="text-muted px-4">Monitor payments, analyze reports, and grow your business.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section py-5 bg-dark text-white text-center">
    <div class="container">
        <h2 class="fw-bold mb-3">Start Managing Your Business Better</h2>
        <p class="lead mb-4">Join E-Billing today and experience the difference.</p>
        <a href="{{ route('signup') }}" class="btn btn-primary btn-lg px-5">Get Started Now</a>
    </div>
</section>

@endsection