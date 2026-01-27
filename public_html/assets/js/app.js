/**
 * Auto Damage Assessment Platform
 * Main JavaScript File
 */

// CSRF Token
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

// API Helper
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

// Toast Notifications
const toast = {
    show(message, type = 'info') {
        const container = document.getElementById('toast-container') || this.createContainer();
        const toastEl = document.createElement('div');
        toastEl.className = `toast align-items-center text-white bg-${type} border-0`;
        toastEl.setAttribute('role', 'alert');
        toastEl.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;

        container.appendChild(toastEl);
        const bsToast = new bootstrap.Toast(toastEl, { autohide: true, delay: 5000 });
        bsToast.show();

        toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
    },

    createContainer() {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
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

// Confirmation Dialog
function confirm(message, callback) {
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.innerHTML = `
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>${message}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmBtn">Confirm</button>
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

// Image lightbox
class Lightbox {
    constructor() {
        this.createModal();
    }

    createModal() {
        const modal = document.createElement('div');
        modal.id = 'lightbox-modal';
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content bg-dark">
                    <div class="modal-header border-0">
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-0 text-center">
                        <img src="" class="img-fluid" id="lightbox-image">
                    </div>
                    <div class="modal-footer border-0 justify-content-between">
                        <button type="button" class="btn btn-outline-light" id="lightbox-prev">
                            <i class="bi bi-chevron-left"></i> Previous
                        </button>
                        <span class="text-white" id="lightbox-counter"></span>
                        <button type="button" class="btn btn-outline-light" id="lightbox-next">
                            Next <i class="bi bi-chevron-right"></i>
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
    }

    open(images, index = 0) {
        this.images = images;
        this.currentIndex = index;
        this.showImage();
        this.modal.show();
    }

    showImage() {
        this.imageEl.src = this.images[this.currentIndex];
        this.counterEl.textContent = `${this.currentIndex + 1} / ${this.images.length}`;
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

// Export for use in other scripts
window.app = {
    api,
    toast,
    FileUploader,
    confirm,
    lightbox
};
