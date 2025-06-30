wrap: wrap;
}

.response-item {
    display: flex;
    align-items: center;
    gap: var(--space-3);
}

.response-item i {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--accent);
    font-size: var(--text-lg);
}

.response-label {
    display: block;
    font-size: var(--text-sm);
    opacity: 0.8;
    color: var(--white);
}

.response-value {
    display: block;
    font-weight: var(--font-semibold);
    font-size: var(--text-base);
    color: var(--white);
}

.contact-stats {
    position: relative;
    height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.contact-stats .stat-card {
    position: absolute;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: var(--radius-xl);
    padding: var(--space-5);
    box-shadow: var(--shadow-lg);
    min-width: 120px;
    text-align: center;
    transition: var(--transition-normal);
}

.contact-stats .stat-1 {
    top: 20%;
    right: 10%;
    animation: float 6s ease-in-out infinite;
}

.contact-stats .stat-2 {
    bottom: 30%;
    right: 5%;
    animation: float 8s ease-in-out infinite;
    animation-delay: 2s;
}

.contact-stats .stat-3 {
    top: 50%;
    left: 15%;
    animation: float 7s ease-in-out infinite;
    animation-delay: 4s;
}

/* Contact Section */
.contact-section {
    background: var(--light);
}

.contact-form-card {
    background: var(--white);
    border-radius: var(--radius-3xl);
    padding: var(--space-10);
    box-shadow: var(--shadow-md);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.form-header {
    display: flex;
    align-items: flex-start;
    gap: var(--space-4);
    margin-bottom: var(--space-8);
}

.form-icon {
    width: 60px;
    height: 60px;
    background: var(--gradient-primary);
    color: var(--white);
    border-radius: var(--radius-xl);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: var(--text-xl);
    flex-shrink: 0;
}

.form-title h2 {
    font-size: var(--text-2xl);
    font-weight: var(--font-bold);
    color: var(--text-primary);
    margin-bottom: var(--space-2);
}

.form-title p {
    color: var(--text-secondary);
    margin-bottom: 0;
    line-height: 1.6;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--space-6);
    margin-bottom: var(--space-8);
}

.form-group-full {
    grid-column: 1 / -1;
}

.form-label {
    display: flex;
    align-items: center;
    gap: var(--space-2);
    margin-bottom: var(--space-3);
    font-weight: var(--font-medium);
    color: var(--text-primary);
}

.form-label i {
    color: var(--primary);
    font-size: var(--text-sm);
}

.form-actions {
    text-align: center;
}

/* Contact Sidebar */
.contact-sidebar {
    position: sticky;
    top: 100px;
}

.contact-info-card,
.quick-actions-card,
.office-hours-card {
    background: var(--white);
    border-radius: var(--radius-2xl);
    padding: var(--space-8);
    box-shadow: var(--shadow-md);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.card-header {
    margin-bottom: var(--space-6);
}

.card-header h3 {
    font-size: var(--text-xl);
    font-weight: var(--font-bold);
    color: var(--text-primary);
    margin-bottom: var(--space-2);
}

.card-header p {
    color: var(--text-secondary);
    margin-bottom: 0;
    font-size: var(--text-sm);
}

.contact-items {
    display: flex;
    flex-direction: column;
    gap: var(--space-6);
}

.contact-item {
    display: flex;
    align-items: flex-start;
    gap: var(--space-4);
}

.contact-icon {
    width: 50px;
    height: 50px;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: var(--text-lg);
    color: var(--white);
    flex-shrink: 0;
}

.contact-icon.email {
    background: var(--gradient-primary);
}

.contact-icon.phone {
    background: var(--gradient-secondary);
}

.contact-icon.whatsapp {
    background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
}

.contact-icon.location {
    background: var(--gradient-accent);
}

.contact-details h4 {
    font-size: var(--text-base);
    font-weight: var(--font-semibold);
    color: var(--text-primary);
    margin-bottom: var(--space-1);
}

.contact-details a {
    color: var(--primary);
    text-decoration: none;
    font-weight: var(--font-medium);
    display: block;
    margin-bottom: var(--space-1);
    transition: var(--transition-fast);
}

.contact-details a:hover {
    color: var(--primary-dark);
    text-decoration: underline;
}

.contact-details small {
    color: var(--text-muted);
    font-size: var(--text-xs);
}

.contact-details span {
    color: var(--text-secondary);
    line-height: 1.5;
}

/* Quick Actions */
.quick-actions {
    display: flex;
    flex-direction: column;
    gap: var(--space-3);
}

.quick-action {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    padding: var(--space-4);
    background: var(--light);
    border-radius: var(--radius-lg);
    text-decoration: none;
    color: var(--text-primary);
    font-weight: var(--font-medium);
    transition: var(--transition-normal);
    border: 1px solid transparent;
}

.quick-action:hover {
    color: var(--white);
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.quick-action.whatsapp:hover {
    background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
    border-color: #25D366;
}

.quick-action.faq:hover {
    background: var(--gradient-primary);
    border-color: var(--primary);
}

.quick-action.competitions:hover {
    background: var(--gradient-accent);
    border-color: var(--accent);
}

.quick-action i:first-child {
    font-size: var(--text-lg);
}

.quick-action i:last-child {
    margin-left: auto;
    font-size: var(--text-sm);
}

/* Office Hours */
.office-hours {
    display: flex;
    flex-direction: column;
    gap: var(--space-3);
    margin-bottom: var(--space-4);
}

.hour-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--space-3);
    background: var(--light);
    border-radius: var(--radius-md);
}

.hour-item .day {
    color: var(--text-primary);
    font-weight: var(--font-medium);
}

.hour-item .time {
    color: var(--text-secondary);
    font-weight: var(--font-medium);
}

.hour-item .time.active {
    color: var(--secondary);
}

.hour-item .time.closed {
    color: var(--danger);
}

.office-note {
    display: flex;
    align-items: center;
    gap: var(--space-2);
    padding: var(--space-3);
    background: rgba(30, 64, 175, 0.05);
    border-radius: var(--radius-md);
    border-left: 4px solid var(--primary);
}

.office-note i {
    color: var(--primary);
}

.office-note span {
    color: var(--text-primary);
    font-size: var(--text-sm);
    font-weight: var(--font-medium);
}

/* Map Section */
.map-section {
    background: var(--white);
}

.map-container {
    position: relative;
    border-radius: var(--radius-2xl);
    overflow: hidden;
    box-shadow: var(--shadow-xl);
    height: 500px;
}

.map-frame {
    width: 100%;
    height: 100%;
}

.map-frame iframe {
    width: 100%;
    height: 100%;
    border: none;
}

.map-overlay {
    position: absolute;
    top: var(--space-6);
    left: var(--space-6);
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: var(--radius-xl);
    padding: var(--space-6);
    box-shadow: var(--shadow-lg);
    border: 1px solid rgba(255, 255, 255, 0.2);
    max-width: 300px;
}

.map-info {
    display: flex;
    align-items: flex-start;
    gap: var(--space-4);
}

.map-icon {
    width: 50px;
    height: 50px;
    background: var(--gradient-primary);
    color: var(--white);
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: var(--text-lg);
    flex-shrink: 0;
}

.map-details h4 {
    font-size: var(--text-lg);
    font-weight: var(--font-bold);
    color: var(--text-primary);
    margin-bottom: var(--space-2);
}

.map-details p {
    color: var(--text-secondary);
    line-height: 1.5;
    margin-bottom: var(--space-3);
}

.map-link {
    display: flex;
    align-items: center;
    gap: var(--space-2);
    color: var(--primary);
    text-decoration: none;
    font-weight: var(--font-medium);
    font-size: var(--text-sm);
    transition: var(--transition-fast);
}

.map-link:hover {
    color: var(--primary-dark);
    text-decoration: underline;
}

/* Mobile Responsive */
@media (max-width: 991px) {
    .contact-methods {
        justify-content: center;
    }
    
    .response-info {
        justify-content: center;
    }
    
    .contact-sidebar {
        position: relative;
        top: auto;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
        gap: var(--space-5);
    }
    
    .contact-form-card {
        padding: var(--space-8);
    }
    
    .map-overlay {
        position: relative;
        top: auto;
        left: auto;
        margin: var(--space-6);
        max-width: none;
    }
}

@media (max-width: 767px) {
    .contact-methods {
        flex-direction: column;
        align-items: center;
    }
    
    .contact-method {
        width: 100%;
        max-width: 250px;
    }
    
    .response-info {
        flex-direction: column;
        gap: var(--space-4);
        text-align: center;
    }
    
    .contact-stats .stat-card {
        position: relative;
        display: inline-block;
        margin: var(--space-2);
    }
    
    .contact-stats .stat-1,
    .contact-stats .stat-2,
    .contact-stats .stat-3 {
        position: relative;
        top: auto;
        right: auto;
        bottom: auto;
        left: auto;
        animation: none;
    }
    
    .form-header {
        flex-direction: column;
        text-align: center;
    }
    
    .contact-form-card {
        padding: var(--space-6);
    }
    
    .contact-info-card,
    .quick-actions-card,
    .office-hours-card {
        padding: var(--space-6);
        margin-bottom: var(--space-4);
    }
    
    .contact-item {
        flex-direction: column;
        text-align: center;
    }
    
    .map-container {
        height: 300px;
    }
    
    .map-overlay {
        margin: var(--space-4);
    }
    
    .map-info {
        flex-direction: column;
        text-align: center;
    }
}

@media (max-width: 575px) {
    .contact-stats {
        height: auto;
        flex-direction: column;
        gap: var(--space-4);
    }
    
    .hero-title {
        font-size: 2.5rem;
    }
    
    .contact-method {
        padding: var(--space-3) var(--space-5);
    }
    
    .form-grid {
        gap: var(--space-4);
    }
    
    .contact-form-card {
        padding: var(--space-5);
    }
    
    .office-hours {
        gap: var(--space-2);
    }
    
    .hour-item {
        padding: var(--space-2);
        font-size: var(--text-sm);
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll for contact form
    const contactMethods = document.querySelectorAll('.contact-method[href^="#"]');
    contactMethods.forEach(method => {
        method.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                const navbarHeight = document.getElementById('navbar')?.offsetHeight || 0;
                const targetPosition = targetElement.offsetTop - navbarHeight - 20;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Form validation enhancements
    const form = document.getElementById('contact-form');
    if (form) {
        // Real-time validation feedback
        const inputs = form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    validateField(this);
                }
            });
        });
        
        // Enhanced form submission
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            inputs.forEach(input => {
                if (!validateField(input)) {
                    isValid = false;
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                
                // Show error toast
                if (window.caturnawaApp && window.caturnawaApp.showToast) {
                    window.caturnawaApp.showToast('Mohon lengkapi semua field yang diperlukan', 'error');
                }
                
                // Focus on first invalid field
                const firstInvalid = form.querySelector('.is-invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                }
            }
        });
    }
    
    function validateField(field) {
        const value = field.value.trim();
        const required = field.hasAttribute('required');
        let isValid = true;
        let errorMessage = '';
        
        // Clear previous validation
        field.classList.remove('is-invalid');
        const existingFeedback = field.parentNode.querySelector('.invalid-feedback');
        if (existingFeedback) {
            existingFeedback.remove();
        }
        
        // Required validation
        if (required && !value) {
            isValid = false;
            errorMessage = 'Field ini wajib diisi';
        }
        
        // Email validation
        if (field.type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                isValid = false;
                errorMessage = 'Format email tidak valid';
            }
        }
        
        // Name validation
        if (field.name === 'name' && value && value.length < 2) {
            isValid = false;
            errorMessage = 'Nama minimal 2 karakter';
        }
        
        // Message validation
        if (field.name === 'message' && value && value.length < 10) {
            isValid = false;
            errorMessage = 'Pesan minimal 10 karakter';
        }
        
        // Show error if invalid
        if (!isValid) {
            field.classList.add('is-invalid');
            
            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            feedback.textContent = errorMessage;
            field.parentNode.appendChild(feedback);
        }
        
        return isValid;
    }
    
    // Auto-dismiss alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 300);
        }, 5000);
    });
    
    // Track contact method clicks
    document.querySelectorAll('.contact-method, .quick-action').forEach(element => {
        element.addEventListener('click', function() {
            const action = this.querySelector('span')?.textContent || 'unknown';
            
            if (typeof gtag !== 'undefined') {
                gtag('event', 'contact_method_click', {
                    method: action,
                    location: 'contact_page'
                });
            }
        });
    });
});
</script>
@endpush