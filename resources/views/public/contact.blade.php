@extends('layouts.app')

@section('title', 'Contact Us - UNAS Fest 2025')

@section('content')
<div class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-4">Contact Us</h1>
                <p class="lead">Get in touch with us for any questions about UNAS Fest 2025</p>
            </div>
            <div class="col-lg-4 text-center">
                <i class="fas fa-phone-alt" style="font-size: 8rem; opacity: 0.3;"></i>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Contact Form -->
        <div class="col-lg-8 mb-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-4"><i class="fas fa-envelope me-2 text-primary"></i>Send us a Message</h3>
                    <form action="{{ route('public.contact.send') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                            <select class="form-select @error('subject') is-invalid @enderror" id="subject" name="subject" required>
                                <option value="">Select a subject</option>
                                <option value="General Inquiry" {{ old('subject') == 'General Inquiry' ? 'selected' : '' }}>General Inquiry</option>
                                <option value="Competition Information" {{ old('subject') == 'Competition Information' ? 'selected' : '' }}>Competition Information</option>
                                <option value="Registration Help" {{ old('subject') == 'Registration Help' ? 'selected' : '' }}>Registration Help</option>
                                <option value="Payment Issues" {{ old('subject') == 'Payment Issues' ? 'selected' : '' }}>Payment Issues</option>
                                <option value="Technical Support" {{ old('subject') == 'Technical Support' ? 'selected' : '' }}>Technical Support</option>
                                <option value="Partnership" {{ old('subject') == 'Partnership' ? 'selected' : '' }}>Partnership</option>
                                <option value="Media Inquiry" {{ old('subject') == 'Media Inquiry' ? 'selected' : '' }}>Media Inquiry</option>
                                <option value="Other" {{ old('subject') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                      id="message" name="message" rows="6" 
                                      placeholder="Please describe your inquiry in detail..." required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="col-lg-4">
            <!-- Contact Details -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4"><i class="fas fa-info-circle me-2 text-primary"></i>Contact Information</h5>
                    
                    <div class="mb-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                <i class="fas fa-map-marker-alt text-white"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Address</h6>
                                <p class="text-muted mb-0">
                                    Universitas Nasional<br>
                                    Jl. Sawo Manila, Pejaten<br>
                                    Jakarta Selatan 12560<br>
                                    Indonesia
                                </p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-start mb-3">
                            <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                <i class="fas fa-phone text-white"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Phone</h6>
                                <p class="text-muted mb-0">+62 21 7806 700</p>
                                <p class="text-muted mb-0">+62 21 1234 5678</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-start mb-3">
                            <div class="bg-info rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                <i class="fas fa-envelope text-white"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Email</h6>
                                <p class="text-muted mb-0">info@unasfest.ac.id</p>
                                <p class="text-muted mb-0">support@unasfest.ac.id</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-start">
                            <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                <i class="fas fa-clock text-white"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Office Hours</h6>
                                <p class="text-muted mb-0">Monday - Friday: 08:00 - 17:00</p>
                                <p class="text-muted mb-0">Saturday: 08:00 - 12:00</p>
                                <p class="text-muted mb-0">Sunday: Closed</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Media -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4"><i class="fas fa-share-alt me-2 text-primary"></i>Follow Us</h5>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <a href="#" class="btn btn-outline-primary w-100">
                                <i class="fab fa-facebook-f me-2"></i>Facebook
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="#" class="btn btn-outline-info w-100">
                                <i class="fab fa-twitter me-2"></i>Twitter
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="#" class="btn btn-outline-danger w-100">
                                <i class="fab fa-instagram me-2"></i>Instagram
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="#" class="btn btn-outline-primary w-100">
                                <i class="fab fa-linkedin me-2"></i>LinkedIn
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Quick Links -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4"><i class="fas fa-question-circle me-2 text-primary"></i>Quick Help</h5>
                    <div class="list-group list-group-flush">
                        <a href="#" class="list-group-item list-group-item-action border-0 px-0">
                            <i class="fas fa-chevron-right me-2 text-muted"></i>Registration Process
                        </a>
                        <a href="#" class="list-group-item list-group-item-action border-0 px-0">
                            <i class="fas fa-chevron-right me-2 text-muted"></i>Payment Methods
                        </a>
                        <a href="#" class="list-group-item list-group-item-action border-0 px-0">
                            <i class="fas fa-chevron-right me-2 text-muted"></i>Competition Rules
                        </a>
                        <a href="#" class="list-group-item list-group-item-action border-0 px-0">
                            <i class="fas fa-chevron-right me-2 text-muted"></i>Technical Requirements
                        </a>
                        <a href="#" class="list-group-item list-group-item-action border-0 px-0">
                            <i class="fas fa-chevron-right me-2 text-muted"></i>Submission Guidelines
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="bg-light p-4 text-center">
                        <h5 class="fw-bold mb-2"><i class="fas fa-map me-2 text-primary"></i>Find Our Location</h5>
                        <p class="text-muted mb-0">Visit us at Universitas Nasional, Jakarta Selatan</p>
                    </div>
                    <!-- Google Maps Embed -->
                    <div style="height: 300px; background: #f8f9fa;" class="d-flex align-items-center justify-content-center">
                        <div class="text-center">
                            <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Interactive map will be available soon</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Form validation
document.getElementById('contact-form')?.addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const subject = document.getElementById('subject').value;
    const message = document.getElementById('message').value.trim();
    
    if (!name || !email || !subject || !message) {
        e.preventDefault();
        alert('Please fill in all required fields.');
        return false;
    }
    
    // Basic email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        e.preventDefault();
        alert('Please enter a valid email address.');
        return false;
    }
});
</script>
@endpush
