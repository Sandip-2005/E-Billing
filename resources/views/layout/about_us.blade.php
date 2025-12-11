@extends('layout.index')
@section('content'  )

<header class="hero-section">
    <div class="container">
        <h1 class="display-4">Billing Shouldn't Be a Chore.</h1>
        <p class="lead">
            We believe getting paid should be the easiest part of your job. E-Billing was born from a simple mission: to free creators, freelancers, and small businesses from the headache of invoicing, so you can focus on the work you love.
        </p>
    </div>
</header>

<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="fw-bold mb-3" style="color: #2c3e50;">The Spark Behind the Simplicity</h2>
                <p class="text-secondary">"Hi, I'm Sandipan Bhunia. Like many of you, I've experienced the frustration of juggling projects while wrestling with clunky spreadsheets and complicated billing software. I knew there had to be a more intuitive way."</p>
                <p class="text-secondary">"I wanted a tool that was clean, incredibly fast, and built with the user in mind—not a giant corporation. So, I decided to build it myself. E-Billing is the result: a straightforward, powerful platform designed to save you time and get you paid faster. It's the tool I always wished I had, and I'm excited to share it with you."</p>
            </div>
            <div class="col-lg-6 text-center d-none d-lg-block">
                <img src="{{asset('assets/image/sandip.png')}}" class="img-fluid rounded shadow-sm" alt="Workflow Illustration">
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container text-center">
        <h2 class="fw-bold mb-5" style="color: #2c3e50;">Our Philosophy is Simple</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <i class="bi bi-feather2 section-icon mb-3"></i>
                <h4 class="fw-bold">Radical Simplicity</h4>
                <p class="text-secondary">No clutter, no confusing menus. We're obsessed with making billing so easy that it feels effortless.</p>
            </div>
            <div class="col-md-4 mb-4">
                <i class="bi bi-rocket-takeoff section-icon mb-3"></i>
                <h4 class="fw-bold">Built for Speed</h4>
                <p class="text-secondary">Create and send a professional invoice in seconds, not minutes. Your time is your most valuable asset.</p>
            </div>
            <div class="col-md-4 mb-4">
                <i class="bi bi-shield-check section-icon mb-3"></i>
                <h4 class="fw-bold">Transparent & Trustworthy</h4>
                <p class="text-secondary">No hidden fees or complex tiers. Just straightforward billing you can count on as you grow your business.</p>
            </div>
        </div>
    </div>
</section>

<section class="py-5 founder-section">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <img src="{{asset('assets/image/sandip.png')}}" class="founder-img rounded-circle mb-4 shadow" alt="Sandipan Bhunia, Founder of E-Billing">
                <h2 class="fw-bold">A Note from the Founder</h2>
                <blockquote class="fs-5 fst-italic text-secondary my-4">
                    "E-Billing is more than just software; it's my commitment to supporting the vibrant community of entrepreneurs and freelancers in India and beyond. Your success is the driving force behind every feature we build."
                </blockquote>
                <p class="fw-bold mb-0">Sandipan Bhunia</p>
                <p>Creator of E-Billing</p>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <h2 class="display-5 fw-bold">Ready to Simplify Your Billing?</h2>
        <p class="lead my-3">Join hundreds of professionals who are spending less time on paperwork and more time doing what they love.</p>
        <a href="{{ route('login')}}" class="btn btn-light btn-lg mt-2">Create Your First Invoice</a>
        <a href="#" class="btn btn-outline-light btn-lg ms-2 mt-2">Explore Features</a>
    </div>
</section>

@endsection