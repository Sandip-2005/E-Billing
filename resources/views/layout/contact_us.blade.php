@extends('layout.index')
@section('content')

<!-- === HERO SECTION === -->
<div class="contact-page">
    <header class="hero-section">
        <div class="container">
            <h1 class="display-4">We'd Love to Hear From You</h1>
            <p class="lead text-muted">
                Whether you have a question, a feature request, or just want to say hello—we're here to help.
            </p>
        </div>
    </header>

    <!-- === QUICK CONTACT INFO SECTION === -->
    <section class="info-section my-5">
        <div class="container">
            <div class="row g-4">
                <!-- Card 1: Email -->
                <div class="col-lg-4">
                    <div class="info-card">
                        <i class="bi bi-envelope-open-fill icon"></i>
                        <h4>Email Us Directly</h4>
                        <p class="text-muted">For general inquiries, support, and feedback. We aim to reply within 24 hours.</p>
                        <a href="mailto:sandipanbhunia18@gmail.com">sandipanbhunia18@gmail.com</a>
                    </div>
                </div>
                <!-- Card 2: Phone -->
                <div class="col-lg-4">
                    <div class="info-card">
                        <i class="bi bi-telephone-fill icon"></i>
                        <h4>Give Us a Call</h4>
                        <p class="text-muted">For urgent matters, you can reach us during business hours.<br> (Mon-Fri, 10 AM - 6 PM IST)</p>
                        <a href="tel:+919876543210">+91 8972966158</a>
                    </div>
                </div>
                <!-- Card 3: Social Media -->
                <div class="col-lg-4">
                    <div class="info-card">
                        <i class="bi bi-people-fill icon"></i>
                        <h4>Connect on Social</h4>
                        <p class="text-muted">Follow us for updates, tips, and to join our growing community of professionals.</p>
                        <div class="mt-4 social-icons">
                            <!-- <a href="#" target="_blank" title="Twitter/X"><i class="bi bi-twitter-x"></i></a> -->
                            <a href="#" target="_blank" title="Facebook"><i class="bi bi-facebook"></i></a>
                            <a href="https://www.linkedin.com/in/sandipan-bhunia/" target="_blank" title="LinkedIn"><i class="bi bi-linkedin"></i></a>
                            <a href="https://github.com/Sandip-2005" target="_blank" title="GitHub"><i class="bi bi-github"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- === CONTACT FORM SECTION === -->
    <section class="form-section my-5">
        <div class="container">
            <div class="row align-items-center g-2">
                <!-- Left Side: Text and Map -->
                <div class="col-lg-5">
                    <h2 class="fw-bold">Send Us a Message</h2>
                    <p class="text-muted mb-4">
                        Your feedback is the most valuable part of our development. Fill out the form, and our team will get back to you as soon as possible.
                    </p>
                    <div class="map-container">

                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d29640.6332565337!2d87.72926302641535!3d21.777197227330813!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a0326e5394d8237%3A0x7bb6b4d525857f71!2sContai%2C%20West%20Bengal!5e0!3m2!1sen!2sin!4v1758219175247!5m2!1sen!2sin" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" alt="contai"></iframe>
                        <!-- <img src="https://placehold.co/600x400/e9ecef/343a40?text=Map+of+Pune" alt="Map showing office location in Pune" class="img-fluid" style="width:100%; height:100%; object-fit:cover;"> -->
                    </div>
                </div>

                <!-- Right Side: The Form -->
                <div class="col-lg-7">
                    <form>
                        <div class="row g-2 mt-5">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="contactName" placeholder="Full Name" required>
                                    <label for="contactName">Full Name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="contactEmail" placeholder="Email Address" required>
                                    <label for="contactEmail">Email Address</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <select class="form-select" id="contactSubject">
                                        <option selected>General Question</option>
                                        <option value="1">Technical Support</option>
                                        <option value="2">Billing Inquiry</option>
                                        <option value="3">Feature Request</option>
                                        <option value="4">Feedback & Suggestions</option>
                                    </select>
                                    <label for="contactSubject">Subject</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Leave a message here" id="contactMessage" style="height: 150px" required></textarea>
                                    <label for="contactMessage">Your Message</label>
                                </div>
                            </div>
                            <div class="col-12 d-grid">
                                <button type="submit" class="btn btn-primary btn-lg btn-submit">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Call to Action -->
    <section class="faq-section my-5">
        <div class="container">
            <h2 class="fw-bold">Looking for a Quick Answer?</h2>
            <p class="lead text-muted my-3">Many common questions are answered in our FAQ section. Check it out before getting in touch!</p>
            <a href="#" class="btn btn-outline-primary btn-lg">Check our FAQs</a>
        </div>
    </section>
</div>

@endsection