/**
 * Auto Damage Assessment Platform
 * Main JavaScript File v3.0
 * Enhanced UI/UX interactions
 */

// CSRF Token
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

// Design System Colors
const colors = {
    primary: '#5b6cf2',
    success: '#10b981',
    warning: '#f59e0b',
    danger: '#ef4444',
    info: '#3b82f6'
};

// API Helper with loading states
const api = {
    async request(url, options = {}) {
        const defaults = {
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        };

        const config = { ...defaults, ...options };
        if (config.body && typeof config.body === 'object') {
            config.body = JSON.stringify(config.body);
        }

        const response = await fetch(url, config);
        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error || 'Request failed');
        }

        return data;
    },

    get(url) {
        return this.request(url);
    },

    post(url, data) {
        return this.request(url, { method: 'POST', body: data });
    },

    put(url, data) {
        return this.request(url, { method: 'PUT', body: data });
    },

    delete(url) {
        return this.request(url, { method: 'DELETE' });
    }
};

// Enhanced Toast Notifications with icons
const toast = {
    icons: {
        success: 'bi-check-circle-fill',
        danger: 'bi-x-circle-fill',
        warning: 'bi-exclamation-triangle-fill',
        info: 'bi-info-circle-fill'
    },
    
    show(message, type = 'info') {
        const container = document.getElementById('toast-container') || this.createContainer();
        const toastEl = document.createElement('div');
        const icon = this.icons[type] || this.icons.info;
        
        toastEl.className = `toast align-items-center border-0 shadow-lg`;
        toastEl.setAttribute('role', 'alert');
        toastEl.style.cssText = `
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            overflow: hidden;
        `;
        
        toastEl.innerHTML = `
            <div class="d-flex align-items-center p-3">
                <div class="me-3" style="width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; background: var(--bs-${type}-bg-subtle);">
                    <i class="bi ${icon} text-${type}" style="font-size: 1.25rem;"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="toast-body p-0 text-dark fw-medium">${message}</div>
                </div>
                <button type="button" class="btn-close ms-3" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-progress" style="height: 3px; background: var(--bs-${type}); animation: toastProgress 5s linear forwards;"></div>
        `;

        // Add progress bar animation style if not exists
        if (!document.getElementById('toast-progress-style')) {
            const style = document.createElement('style');
            style.id = 'toast-progress-style';
            style.textContent = `
                @keyframes toastProgress {
                    from { width: 100%; }
                    to { width: 0%; }
                }
            `;
            document.head.appendChild(style);
        }

        container.appendChild(toastEl);
        const bsToast = new bootstrap.Toast(toastEl, { autohide: true, delay: 5000 });
        bsToast.show();

        toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
    },

    createContainer() {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
        return container;
    },

    success(message) { this.show(message, 'success'); },
    error(message) { this.show(message, 'danger'); },
    warning(message) { this.show(message, 'warning'); },
    info(message) { this.show(message, 'info'); }
};

// File Upload Handler
class FileUploader {
    constructor(options) {
        this.input = document.querySelector(options.input);
        this.preview = document.querySelector(options.preview);
        this.dropzone = document.querySelector(options.dropzone);
        this.maxFiles = options.maxFiles || 10;
        this.maxSize = options.maxSize || 5 * 1024 * 1024;
        this.allowedTypes = options.allowedTypes || ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        this.files = [];
        this.onFilesChange = options.onFilesChange || (() => {});

        if (this.input && this.dropzone) {
            this.init();
        }
    }

    init() {
        // Click to upload
        this.dropzone.addEventListener('click', () => this.input.click());

        // File input change
        this.input.addEventListener('change', (e) => this.handleFiles(e.target.files));

        // Drag and drop
        this.dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            this.dropzone.classList.add('dragover');
        });

        this.dropzone.addEventListener('dragleave', () => {
            this.dropzone.classList.remove('dragover');
        });

        this.dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            this.dropzone.classList.remove('dragover');
            this.handleFiles(e.dataTransfer.files);
        });
    }

    handleFiles(fileList) {
        const newFiles = Array.from(fileList);

        for (const file of newFiles) {
            if (this.files.length >= this.maxFiles) {
                toast.warning(`Maximum ${this.maxFiles} files allowed`);
                break;
            }

            if (!this.allowedTypes.includes(file.type)) {
                toast.error(`${file.name}: File type not allowed`);
                continue;
            }

            if (file.size > this.maxSize) {
                toast.error(`${file.name}: File size exceeds ${this.formatSize(this.maxSize)}`);
                continue;
            }

            this.files.push(file);
        }

        this.renderPreviews();
        this.onFilesChange(this.files);
    }

    removeFile(index) {
        this.files.splice(index, 1);
        this.renderPreviews();
        this.onFilesChange(this.files);
    }

    renderPreviews() {
        if (!this.preview) return;

        this.preview.innerHTML = '';

        this.files.forEach((file, index) => {
            const item = document.createElement('div');
            item.className = 'file-preview-item';

            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'remove-btn';
            removeBtn.innerHTML = '&times;';
            removeBtn.onclick = () => this.removeFile(index);

            item.appendChild(img);
            item.appendChild(removeBtn);
            this.preview.appendChild(item);
        });
    }

    getFiles() {
        return this.files;
    }

    clear() {
        this.files = [];
        this.renderPreviews();
        this.onFilesChange(this.files);
    }

    formatSize(bytes) {
        const units = ['B', 'KB', 'MB', 'GB'];
        let i = 0;
        while (bytes >= 1024 && i < units.length - 1) {
            bytes /= 1024;
            i++;
        }
        return `${bytes.toFixed(1)} ${units[i]}`;
    }
}

// Enhanced Confirmation Dialog
function confirm(message, callback, options = {}) {
    const title = options.title || 'Confirm Action';
    const confirmText = options.confirmText || 'Confirm';
    const cancelText = options.cancelText || 'Cancel';
    const type = options.type || 'danger';
    
    const iconMap = {
        danger: 'bi-exclamation-triangle-fill',
        warning: 'bi-question-circle-fill',
        info: 'bi-info-circle-fill'
    };
    
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.innerHTML = `
        <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-body text-center p-4 pb-3">
                    <div style="width: 64px; height: 64px; border-radius: 16px; background: var(--bs-${type}-bg-subtle); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                        <i class="bi ${iconMap[type]} text-${type}" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="fw-bold mb-2">${title}</h5>
                    <p class="text-muted mb-0">${message}</p>
                </div>
                <div class="modal-footer border-0 justify-content-center gap-2 p-3 pt-0">
                    <button type="button" class="btn btn-light px-4 py-2" data-bs-dismiss="modal" style="border-radius: 10px; min-width: 100px;">
                        ${cancelText}
                    </button>
                    <button type="button" class="btn btn-${type} px-4 py-2" id="confirmBtn" style="border-radius: 10px; min-width: 100px;">
                        ${confirmText}
                    </button>
                </div>
            </div>
        </div>
    `;

    document.body.appendChild(modal);
    const bsModal = new bootstrap.Modal(modal);

    modal.querySelector('#confirmBtn').addEventListener('click', () => {
        bsModal.hide();
        callback();
    });

    modal.addEventListener('hidden.bs.modal', () => modal.remove());
    bsModal.show();
}

// Delete confirmation for forms
document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', function(e) {
        e.preventDefault();
        const message = this.dataset.confirm || 'Are you sure?';

        confirm(message, () => {
            if (this.tagName === 'A') {
                window.location.href = this.href;
            } else if (this.form) {
                this.form.submit();
            }
        });
    });
});

// Phone number formatting
document.querySelectorAll('input[type="tel"]').forEach(input => {
    input.addEventListener('input', function() {
        // Remove non-digits except +
        let value = this.value.replace(/[^\d+]/g, '');

        // Format Georgian number
        if (value.startsWith('5') && value.length <= 9) {
            value = value.replace(/(\d{3})(\d{3})(\d{3})/, '$1 $2 $3');
        }

        this.value = value;
    });
});

// Auto-hide alerts after 5 seconds
document.querySelectorAll('.alert-dismissible').forEach(alert => {
    setTimeout(() => {
        const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
        bsAlert.close();
    }, 5000);
});

// Form validation styling
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        this.classList.add('was-validated');
    });
});

// Enhanced Image Lightbox
class Lightbox {
    constructor() {
        this.createModal();
    }

    createModal() {
        const modal = document.createElement('div');
        modal.id = 'lightbox-modal';
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content border-0" style="background: rgba(0, 0, 0, 0.95); border-radius: 16px; overflow: hidden;">
                    <div class="modal-header border-0 position-absolute w-100" style="z-index: 10; background: linear-gradient(180deg, rgba(0,0,0,0.5) 0%, transparent 100%);">
                        <span class="text-white fw-medium" id="lightbox-counter" style="background: rgba(255,255,255,0.2); padding: 6px 12px; border-radius: 8px; font-size: 0.875rem;"></span>
                        <button type="button" class="btn btn-light btn-sm rounded-circle" data-bs-dismiss="modal" style="width: 36px; height: 36px; padding: 0;">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <div class="modal-body p-0 d-flex align-items-center justify-content-center" style="min-height: 60vh;">
                        <img src="" class="img-fluid" id="lightbox-image" style="max-height: 80vh; object-fit: contain;">
                    </div>
                    <div class="modal-footer border-0 position-absolute bottom-0 w-100 justify-content-between px-3" style="z-index: 10; background: linear-gradient(0deg, rgba(0,0,0,0.5) 0%, transparent 100%);">
                        <button type="button" class="btn btn-light rounded-pill px-4" id="lightbox-prev">
                            <i class="bi bi-chevron-left me-2"></i>Previous
                        </button>
                        <button type="button" class="btn btn-light rounded-pill px-4" id="lightbox-next">
                            Next<i class="bi bi-chevron-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);

        this.modal = new bootstrap.Modal(modal);
        this.imageEl = modal.querySelector('#lightbox-image');
        this.counterEl = modal.querySelector('#lightbox-counter');
        this.images = [];
        this.currentIndex = 0;

        modal.querySelector('#lightbox-prev').addEventListener('click', () => this.prev());
        modal.querySelector('#lightbox-next').addEventListener('click', () => this.next());
        
        // Keyboard navigation
        modal.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') this.prev();
            if (e.key === 'ArrowRight') this.next();
        });
    }

    open(images, index = 0) {
        this.images = images;
        this.currentIndex = index;
        this.showImage();
        this.modal.show();
    }

    showImage() {
        this.imageEl.style.opacity = '0';
        this.imageEl.src = this.images[this.currentIndex];
        this.counterEl.innerHTML = `<i class="bi bi-image me-2"></i>${this.currentIndex + 1} / ${this.images.length}`;
        
        // Fade in animation
        setTimeout(() => {
            this.imageEl.style.transition = 'opacity 0.3s ease';
            this.imageEl.style.opacity = '1';
        }, 50);
    }

    prev() {
        this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
        this.showImage();
    }

    next() {
        this.currentIndex = (this.currentIndex + 1) % this.images.length;
        this.showImage();
    }
}

const lightbox = new Lightbox();

// Initialize lightbox for photo galleries
document.querySelectorAll('.photo-gallery').forEach(gallery => {
    const images = Array.from(gallery.querySelectorAll('img')).map(img => img.src);

    gallery.querySelectorAll('.photo-item').forEach((item, index) => {
        item.addEventListener('click', () => lightbox.open(images, index));
    });
});

// Enhanced Button Loading States
const buttonLoader = {
    start(button) {
        button.disabled = true;
        button.dataset.originalText = button.innerHTML;
        button.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
            Loading...
        `;
    },
    
    stop(button) {
        button.disabled = false;
        button.innerHTML = button.dataset.originalText;
    }
};

// Smooth Scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});

// Add ripple effect to buttons
document.querySelectorAll('.btn-primary, .btn-success, .btn-danger, .btn-warning').forEach(button => {
    button.addEventListener('click', function(e) {
        const ripple = document.createElement('span');
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        
        ripple.style.cssText = `
            position: absolute;
            width: ${size}px;
            height: ${size}px;
            left: ${x}px;
            top: ${y}px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: scale(0);
            animation: ripple 0.6s linear;
            pointer-events: none;
        `;
        
        this.style.position = 'relative';
        this.style.overflow = 'hidden';
        this.appendChild(ripple);
        
        setTimeout(() => ripple.remove(), 600);
    });
});

// Add ripple animation style
if (!document.getElementById('ripple-style')) {
    const style = document.createElement('style');
    style.id = 'ripple-style';
    style.textContent = `
        @keyframes ripple {
            to { transform: scale(4); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
}

// Enhanced scroll-based animations
const observerOptions = {
    root: null,
    rootMargin: '0px',
    threshold: 0.1
};

const animateOnScroll = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('animated');
            animateOnScroll.unobserve(entry.target);
        }
    });
}, observerOptions);

document.querySelectorAll('.stagger-in > *').forEach(el => {
    animateOnScroll.observe(el);
});

// Export for use in other scripts
window.app = {
    api,
    toast,
    FileUploader,
    confirm,
    lightbox,
    buttonLoader,
    colors
};
