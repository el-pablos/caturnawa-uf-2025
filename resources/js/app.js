// Bootstrap JavaScript
import 'bootstrap';

// Custom JavaScript for UNAS Fest 2025
document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
        alerts.forEach(function(alert) {
            if (alert.classList.contains('show')) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        });
    }, 5000);

    // Form validation enhancement
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    // Loading button handler
    document.querySelectorAll('.btn-submit').forEach(function(btn) {
        btn.addEventListener('click', function() {
            this.classList.add('btn-loading');
            this.disabled = true;
            
            // Re-enable after 10 seconds as fallback
            setTimeout(() => {
                this.classList.remove('btn-loading');
                this.disabled = false;
            }, 10000);
        });
    });

    // File upload preview
    document.querySelectorAll('input[type="file"]').forEach(function(input) {
        input.addEventListener('change', function(e) {
            const files = e.target.files;
            const preview = document.getElementById(this.id + '-preview');
            
            if (preview && files.length > 0) {
                preview.innerHTML = '';
                
                Array.from(files).forEach(function(file) {
                    const div = document.createElement('div');
                    div.className = 'file-preview-item';
                    div.innerHTML = `
                        <i class="bi bi-file-earmark"></i>
                        <span>${file.name}</span>
                        <small>(${formatFileSize(file.size)})</small>
                    `;
                    preview.appendChild(div);
                });
            }
        });
    });

    // Search functionality
    const searchInputs = document.querySelectorAll('.search-input');
    searchInputs.forEach(function(input) {
        input.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const targetSelector = this.dataset.target;
            const targets = document.querySelectorAll(targetSelector);
            
            targets.forEach(function(target) {
                const text = target.textContent.toLowerCase();
                target.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    });

    // Confirmation dialogs
    document.querySelectorAll('.confirm-action').forEach(function(element) {
        element.addEventListener('click', function(e) {
            e.preventDefault();
            
            const message = this.dataset.message || 'Apakah Anda yakin?';
            const action = this.href || this.dataset.action;
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, lanjutkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (this.tagName === 'A') {
                            window.location.href = action;
                        } else if (this.tagName === 'BUTTON' && this.form) {
                            this.form.submit();
                        }
                    }
                });
            } else {
                if (confirm(message)) {
                    if (this.tagName === 'A') {
                        window.location.href = action;
                    } else if (this.tagName === 'BUTTON' && this.form) {
                        this.form.submit();
                    }
                }
            }
        });
    });
});

// Utility functions
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function showLoading(element) {
    if (typeof element === 'string') {
        element = document.querySelector(element);
    }
    if (element) {
        element.classList.add('btn-loading');
        element.disabled = true;
    }
}

function hideLoading(element) {
    if (typeof element === 'string') {
        element = document.querySelector(element);
    }
    if (element) {
        element.classList.remove('btn-loading');
        element.disabled = false;
    }
}

function showNotification(message, type = 'success') {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: type,
            title: type === 'success' ? 'Berhasil!' : 'Oops...',
            text: message,
            timer: 3000,
            showConfirmButton: false
        });
    } else {
        alert(message);
    }
}

// Export functions for global use
window.showLoading = showLoading;
window.hideLoading = hideLoading;
window.showNotification = showNotification;
window.formatFileSize = formatFileSize;
