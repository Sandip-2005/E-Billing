@extends('layout.index')
@section('content')

<!-- Hero Section -->
<section class="hero-section py-5" style="background-color: #f8f9fa;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 text-lg-start text-center">
                <h1 class="display-4 fw-bold mb-3">Smart Billing Solutions for Your Business</h1>
                <p class="lead text-muted mb-4">Streamline your invoicing, track payments, and manage your business finances with ease. The perfect tool for freelancers and small businesses.</p>
                <div class="d-grid gap-2 d-md-flex justify-content-md-start justify-content-center">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4 me-md-2">Get Started</a>
                    <a href="{{ route('about_us') }}" class="btn btn-outline-secondary btn-lg px-4">Learn More</a>
                </div>
            </div>
            <div class="col-lg-6 mt-5 mt-lg-0 text-center">
                <img src="https://placehold.co/600x400/7af895/ffffff?text=E-Billing+Dashboard" alt="E-Billing Dashboard" class="img-fluid rounded shadow-lg">
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Why Choose E-Billing?</h2>
            <p class="text-muted">Everything you need to manage your billing efficiently.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-primary bg-gradient text-white rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-receipt fs-3"></i>
                        </div>
                        <h4 class="card-title">Easy Invoicing</h4>
                        <p class="card-text text-muted">Create professional invoices in seconds. Customize templates to match your brand identity.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-success bg-gradient text-white rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-graph-up-arrow fs-3"></i>
                        </div>
                        <h4 class="card-title">Insightful Reports</h4>
                        <p class="card-text text-muted">Track your income and expenses with detailed reports. Make informed business decisions.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-warning bg-gradient text-white rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-shield-lock fs-3"></i>
                        </div>
                        <h4 class="card-title">Secure Data</h4>
                        <p class="card-text text-muted">Your financial data is safe with us. We use industry-standard encryption to protect your information.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="cta-section py-5 bg-dark text-white text-center">
    <div class="container">
        <h2 class="fw-bold mb-3">Ready to Simplify Your Billing?</h2>
        <p class="lead mb-4">Join thousands of businesses who trust E-Billing for their financial management.</p>
        <a href="{{ route('signup') }}" class="btn btn-light btn-lg px-5">Create Free Account</a>
    </div>
</section>

@endsection