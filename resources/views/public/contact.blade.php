@extends('layouts.simple')

@php
    $seoPage = 'contact';
@endphp

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-primary">Hubungi Kami</h1>
                <p class="lead text-muted">Punya pertanyaan tentang UNAS Fest 2025? Kami siap membantu Anda!</p>
            </div>
            
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <form>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subjek</label>
                            <input type="text" class="form-control" id="subject" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Pesan</label>
                            <textarea class="form-control" id="message" rows="5" required></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="bi bi-send me-2"></i>Kirim Pesan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="row mt-5">
                <div class="col-md-4 text-center mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-envelope-fill text-primary fs-1 mb-3"></i>
                            <h5>Email</h5>
                            <p class="text-muted">info@unasfest2025.com</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-telephone-fill text-primary fs-1 mb-3"></i>
                            <h5>Telepon</h5>
                            <p class="text-muted">+62 21 7806700</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-geo-alt-fill text-primary fs-1 mb-3"></i>
                            <h5>Alamat</h5>
                            <p class="text-muted">Universitas Nasional<br>Jakarta Selatan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
    .map-container {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        margin-bottom: 50px;
    }

    #contact-map {
        height: 450px;
        width: 100%;
    }

    .map-overlay {
        position: absolute;
        top: 30px;
        left: 30px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 25px;
        max-width: 350px;
        border: 1px solid rgba(255, 255, 255, 0.8);
    }

    .map-info h5 {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 10px;
    }

    .map-info p {
        color: #64748b;
        margin-bottom: 20px;
        line-height: 1.5;
    }

    .map-actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .map-btn {
        padding: 12px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .map-btn:not(.secondary) {
        background: var(--primary-color);
        color: white;
    }

    .map-btn.secondary {
        background: transparent;
        color: var(--primary-color);
        border: 2px solid var(--primary-color);
    }

    .map-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(37, 99, 235, 0.3);
    }

    .map-btn.secondary:hover {
        background: var(--primary-color);
        color: white;
    }

    /* Location Features */
    .location-features {
        margin-top: 50px;
    }

    .feature-item {
        text-align: center;
        padding: 25px;
        background: #f8fafc;
        border-radius: 15px;
        transition: all 0.3s ease;
        height: 100%;
        border: 2px solid transparent;
    }

    .feature-item:hover {
        background: white;
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border-color: var(--primary-color);
    }

    .feature-item i {
        font-size: 2.5rem;
        color: var(--primary-color);
        margin-bottom: 15px;
    }

    .feature-item h6 {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 10px;
    }

    .feature-item p {
        color: #64748b;
        margin: 0;
        font-size: 0.9rem;
    }

    /* FAQ Section */
    .contact-faq {
        padding: 80px 0;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    }

    .faq-accordion .accordion-item {
        border: none;
        margin-bottom: 15px;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }

    .faq-accordion .accordion-button {
        background: white;
        color: var(--primary-color);
        font-weight: 600;
        padding: 20px 25px;
        border: none;
        box-shadow: none;
    }

    .faq-accordion .accordion-button:not(.collapsed) {
        background: var(--primary-color);
        color: white;
    }

    .faq-accordion .accordion-button:focus {
        box-shadow: none;
        border-color: transparent;
    }

    .faq-accordion .accordion-body {
        padding: 20px 25px;
        background: white;
        color: #64748b;
        line-height: 1.6;
    }

    .faq-cta p {
        color: #64748b;
        margin-bottom: 20px;
        font-size: 1.1rem;
    }

    .btn-outline {
        background: transparent;
        color: var(--primary-color);
        border: 2px solid var(--primary-color);
        padding: 12px 30px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
    }

    .btn-outline:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .map-overlay {
            position: static;
            max-width: none;
            margin-top: 20px;
            background: white;
        }

        #contact-map {
            height: 350px;
        }
    }

    @media (max-width: 768px) {
        .contact-hero {
            min-height: 60vh;
        }

        .hero-title {
            font-size: 2.5rem;
        }

        .hero-subtitle {
            font-size: 1rem;
        }

        .contact-methods {
            flex-direction: column;
            align-items: center;
        }

        .contact-method {
            width: 200px;
        }

        .hero-actions {
            flex-direction: column;
            align-items: center;
        }

        .btn-primary-custom,
        .btn-secondary-custom {
            width: 250px;
            justify-content: center;
        }

        .contact-form-card {
            padding: 25px;
        }

        .form-header h3 {
            font-size: 1.5rem;
        }

        .contact-info-card {
            padding: 20px;
        }

        .info-icon {
            width: 50px;
            height: 50px;
            font-size: 1.2rem;
        }

        .map-overlay {
            padding: 20px;
        }

        .map-actions {
            flex-direction: column;
        }

        .section-header h2 {
            font-size: 2rem;
        }
    }

    @media (max-width: 576px) {
        .contact-section,
        .map-section,
        .contact-faq {
            padding: 40px 0;
        }

        .hero-title {
            font-size: 2rem;
        }

        .contact-method {
            width: 100%;
            max-width: 200px;
        }

        .btn-primary-custom,
        .btn-secondary-custom {
            width: 100%;
            max-width: 280px;
        }

        .contact-form-card {
            padding: 20px;
        }

        .form-control {
            padding: 12px 15px;
        }

        .btn-submit {
            width: 100%;
            justify-content: center;
        }

        .contact-info-card {
            flex-direction: column;
            text-align: center;
            align-items: center;
        }

        .feature-item {
            padding: 20px;
        }

        .feature-item i {
            font-size: 2rem;
        }

        #contact-map {
            height: 300px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
// Initialize Contact Page Map
function initContactMap() {
    const universitas = { lat: -6.2697, lng: 106.8049 }; // Universitas Nasional Jakarta coordinates

    const map = new google.maps.Map(document.getElementById('contact-map'), {
        zoom: 16,
        center: universitas,
        styles: [
            {
                "featureType": "all",
                "elementType": "labels.text.fill",
                "stylers": [{"color": "#ffffff"}]
            },
            {
                "featureType": "all",
                "elementType": "labels.text.stroke",
                "stylers": [{"color": "#000000"}, {"lightness": 13}]
            },
            {
                "featureType": "administrative",
                "elementType": "geometry.fill",
                "stylers": [{"color": "#000000"}]
            },
            {
                "featureType": "administrative",
                "elementType": "geometry.stroke",
                "stylers": [{"color": "#144b53"}, {"lightness": 14}, {"weight": 1.4}]
            },
            {
                "featureType": "landscape",
                "elementType": "all",
                "stylers": [{"color": "#08304b"}]
            },
            {
                "featureType": "poi",
                "elementType": "geometry",
                "stylers": [{"color": "#0c4152"}, {"lightness": 5}]
            },
            {
                "featureType": "road.highway",
                "elementType": "geometry.fill",
                "stylers": [{"color": "#000000"}]
            },
            {
                "featureType": "road.highway",
                "elementType": "geometry.stroke",
                "stylers": [{"color": "#0b434f"}, {"lightness": 25}]
            },
            {
                "featureType": "road.arterial",
                "elementType": "geometry.fill",
                "stylers": [{"color": "#000000"}]
            },
            {
                "featureType": "road.arterial",
                "elementType": "geometry.stroke",
                "stylers": [{"color": "#0b3d51"}, {"lightness": 16}]
            },
            {
                "featureType": "road.local",
                "elementType": "geometry",
                "stylers": [{"color": "#000000"}]
            },
            {
                "featureType": "transit",
                "elementType": "all",
                "stylers": [{"color": "#146474"}]
            },
            {
                "featureType": "water",
                "elementType": "all",
                "stylers": [{"color": "#021019"}]
            }
        ],
        disableDefaultUI: false,
        zoomControl: true,
        mapTypeControl: false,
        scaleControl: false,
        streetViewControl: true,
        rotateControl: false,
        fullscreenControl: true
    });

    const marker = new google.maps.Marker({
        position: universitas,
        map: map,
        title: 'Universitas Nasional Jakarta',
        icon: {
            url: 'data:image/svg+xml;charset=UTF-8,<svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><circle cx="20" cy="20" r="20" fill="%232563eb"/><circle cx="20" cy="20" r="12" fill="white"/><circle cx="20" cy="20" r="6" fill="%232563eb"/></svg>',
            scaledSize: new google.maps.Size(40, 40),
            anchor: new google.maps.Point(20, 20)
        },
        animation: google.maps.Animation.BOUNCE
    });

    const infoWindow = new google.maps.InfoWindow({
        content: `
            <div style="padding: 15px; color: #333; max-width: 250px;">
                <h6 style="color: #2563eb; font-weight: bold; margin-bottom: 10px;">Universitas Nasional Jakarta</h6>
                <p style="margin-bottom: 10px; line-height: 1.4;">Jl. Sawo Manila No.61, Pejaten<br>Jakarta Selatan 12560</p>
                <div style="margin-top: 10px;">
                    <a href="https://maps.google.com/maps?q=-6.2697,106.8049" target="_blank" 
                       style="color: #2563eb; text-decoration: none; font-weight: 600;">
                        📍 Lihat di Google Maps
                    </a>
                </div>
            </div>
        `
    });

    marker.addListener('click', () => {
        infoWindow.open(map, marker);
    });

    // Auto-open info window
    setTimeout(() => {
        infoWindow.open(map, marker);
    }, 1000);
}

// Initialize map when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Load Google Maps API if not already loaded
    if (typeof google === 'undefined') {
        const script = document.createElement('script');
        script.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dO9O8nce6hq9qU&callback=initContactMap';
        script.async = true;
        script.defer = true;
        document.head.appendChild(script);
    } else {
        initContactMap();
    }

    // Form validation and submission
    const contactForm = document.querySelector('.contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(this);
            const submitBtn = this.querySelector('.btn-submit');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i><span>Mengirim...</span>';
            submitBtn.disabled = true;
            
            // Simulate form submission (replace with actual AJAX call)
            setTimeout(() => {
                // Reset form
                this.reset();
                
                // Show success message
                submitBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i><span>Terkirim!</span>';
                submitBtn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
                
                // Reset after 3 seconds
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    submitBtn.style.background = '';
                }, 3000);
                
                // Show success notification
                showNotification('Pesan Anda telah terkirim! Kami akan merespons dalam 24 jam.', 'success');
            }, 2000);
        });
    }
});

// Notification function
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="bi bi-${type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
            <span>${message}</span>
            <button class="notification-close" onclick="this.parentElement.parentElement.remove()">
                <i class="bi bi-x"></i>
            </button>
        </div>
    `;
    
    // Add notification styles
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: ${type === 'success' ? '#10b981' : '#2563eb'};
        color: white;
        padding: 15px 20px;
        border-radius: 10px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        z-index: 9999;
        max-width: 400px;
        animation: slideIn 0.3s ease;
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}

// Add CSS for notification animation
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .notification-content {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .notification-close {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        margin-left: auto;
        padding: 0;
        font-size: 1.2rem;
    }
`;
document.head.appendChild(style);
</script>
@endpush