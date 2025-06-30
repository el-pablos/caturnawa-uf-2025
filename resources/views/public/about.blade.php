    gap: var(--space-2);
    font-weight: var(--font-medium);
    color: var(--primary);
    transition: var(--transition-normal);
}

.value-highlight i {
    font-size: var(--text-sm);
}

/* Team Section */
.team-section {
    background: var(--light);
}

.team-overview {
    background: var(--white);
    border-radius: var(--radius-2xl);
    padding: var(--space-10);
    box-shadow: var(--shadow-md);
    text-align: center;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.team-overview h3 {
    font-size: var(--text-2xl);
    font-weight: var(--font-bold);
    color: var(--primary);
    margin-bottom: var(--space-4);
}

.team-overview p {
    color: var(--text-secondary);
    line-height: 1.7;
    margin-bottom: var(--space-8);
    font-size: var(--text-lg);
}

.team-stats {
    display: flex;
    justify-content: center;
    gap: var(--space-8);
    flex-wrap: wrap;
}

.team-stat {
    text-align: center;
}

.team-stat .stat-number {
    display: block;
    font-size: var(--text-3xl);
    font-weight: var(--font-extrabold);
    color: var(--primary);
    margin-bottom: var(--space-2);
}

.team-stat .stat-label {
    font-size: var(--text-sm);
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Mobile Responsive */
@media (max-width: 991px) {
    .about-illustration {
        height: 300px;
    }
    
    .illustration-container {
        width: 250px;
        height: 250px;
    }
    
    .about-icon {
        width: 60px;
        height: 60px;
        font-size: var(--text-xl);
    }
    
    .visi-misi-card {
        padding: var(--space-8);
        margin-bottom: var(--space-6);
    }
    
    .team-stats {
        gap: var(--space-6);
    }
}

@media (max-width: 767px) {
    .about-illustration {
        height: 250px;
    }
    
    .illustration-container {
        width: 200px;
        height: 200px;
    }
    
    .about-icon {
        width: 50px;
        height: 50px;
        font-size: var(--text-lg);
    }
    
    .visi-misi-card {
        padding: var(--space-6);
    }
    
    .visi-misi-card .card-icon {
        width: 60px;
        height: 60px;
        font-size: var(--text-xl);
    }
    
    .visi-misi-card .card-title {
        font-size: var(--text-xl);
    }
    
    .visi-misi-card .card-description {
        font-size: var(--text-base);
    }
    
    .value-card {
        padding: var(--space-6);
        margin-bottom: var(--space-6);
    }
    
    .value-icon {
        width: 60px;
        height: 60px;
        font-size: var(--text-xl);
    }
    
    .value-title {
        font-size: var(--text-lg);
    }
    
    .team-overview {
        padding: var(--space-8);
    }
    
    .team-stats {
        flex-direction: column;
        gap: var(--space-4);
    }
    
    .team-stat .stat-number {
        font-size: var(--text-2xl);
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize counter animations
    const counters = document.querySelectorAll('.counter');
    
    if (counters.length > 0) {
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px 0px -100px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);
        
        counters.forEach(counter => observer.observe(counter));
    }
    
    function animateCounter(element) {
        const target = parseInt(element.getAttribute('data-target'));
        const duration = 2000; // 2 seconds
        const start = 0;
        const increment = target / (duration / 16); // 60fps
        let current = start;
        
        const timer = setInterval(() => {
            current += increment;
            
            if (current >= target) {
                element.textContent = target.toLocaleString('id-ID');
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(current).toLocaleString('id-ID');
            }
        }, 16);
    }
    
    // Progress bars animation
    const progressBars = document.querySelectorAll('.progress-fill');
    
    if (progressBars.length > 0) {
        const progressObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const progress = entry.target.getAttribute('data-progress');
                    entry.target.style.width = `${progress}%`;
                    progressObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        progressBars.forEach(bar => progressObserver.observe(bar));
    }
    
    // Smooth scroll for internal links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
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
});
</script>
@endpush