<div class="upload-section">
    <div class="upload-header text-center mb-4">
        <div class="upload-icon mx-auto mb-3">
            <i class="bi bi-camera-fill"></i>
        </div>
        <h2 class="fw-bold mb-2"><?= Lang::getLocale() === 'ka' ? 'ატვირთეთ დაზიანების ფოტოები' : 'Upload Damage Photos' ?></h2>
        <p class="text-muted mb-0">
            <?= Lang::getLocale() === 'ka' 
                ? 'გადაუღეთ ფოტო დაზიანებულ ადგილებს. მაქსიმუმ 10 ფოტო.' 
                : 'Take photos of the damaged areas. Maximum 10 photos.' ?>
        </p>
    </div>

    <!-- Upload Area -->
    <div class="upload-dropzone" id="dropzone">
        <input type="file" id="photoInput" name="photos[]" accept="image/*" multiple capture="environment" class="d-none">
        
        <div class="dropzone-content">
            <div class="dropzone-icon">
                <i class="bi bi-cloud-arrow-up-fill"></i>
            </div>
            <h5 class="fw-semibold mb-2"><?= Lang::getLocale() === 'ka' ? 'გადაათრიეთ ფოტოები აქ' : 'Drag & drop photos here' ?></h5>
            <p class="text-muted small mb-3"><?= Lang::getLocale() === 'ka' ? 'ან' : 'or' ?></p>
            <button type="button" class="btn btn-primary btn-lg px-4" onclick="document.getElementById('photoInput').click()">
                <i class="bi bi-camera-fill me-2"></i><?= Lang::getLocale() === 'ka' ? 'ფოტოს გადაღება' : 'Take Photo' ?>
            </button>
            <button type="button" class="btn btn-outline-primary btn-lg px-4 ms-2" onclick="document.getElementById('photoInput').click()">
                <i class="bi bi-folder2-open me-2"></i><?= Lang::getLocale() === 'ka' ? 'ფაილის არჩევა' : 'Choose Files' ?>
            </button>
        </div>

        <div class="dropzone-loading d-none">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 mb-0 text-muted"><?= Lang::getLocale() === 'ka' ? 'ფოტოები იტვირთება...' : 'Uploading photos...' ?></p>
        </div>
    </div>

    <!-- Photo Counter -->
    <div class="photo-counter text-center mt-3 mb-4">
        <span id="photoCount"><?= count($photos ?? []) ?></span> / 10 <?= Lang::getLocale() === 'ka' ? 'ფოტო' : 'photos' ?>
    </div>

    <!-- Photo Grid -->
    <div class="photo-grid" id="photoGrid">
        <?php foreach ($photos ?? [] as $photo): ?>
            <div class="photo-item" data-id="<?= $photo['id'] ?>">
                <img src="<?= $photo['path'] ?>" alt="Uploaded photo">
                <button type="button" class="photo-remove" onclick="removePhoto('<?= $photo['id'] ?>')">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Tips Section -->
    <div class="upload-tips mt-4">
        <h6 class="fw-semibold mb-3">
            <i class="bi bi-lightbulb-fill text-warning me-2"></i>
            <?= Lang::getLocale() === 'ka' ? 'რჩევები' : 'Tips for better estimates' ?>
        </h6>
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="tip-card">
                    <i class="bi bi-sun-fill"></i>
                    <span><?= Lang::getLocale() === 'ka' ? 'კარგი განათება' : 'Good lighting' ?></span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="tip-card">
                    <i class="bi bi-arrows-angle-expand"></i>
                    <span><?= Lang::getLocale() === 'ka' ? 'ახლო ფოტო' : 'Close-up shots' ?></span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="tip-card">
                    <i class="bi bi-grid-fill"></i>
                    <span><?= Lang::getLocale() === 'ka' ? 'მთლიანი ხედი' : 'Full view' ?></span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="tip-card">
                    <i class="bi bi-arrows-move"></i>
                    <span><?= Lang::getLocale() === 'ka' ? 'ყველა კუთხით' : 'Multiple angles' ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Continue Button -->
    <div class="mt-4 pt-3">
        <button type="button" class="btn btn-primary btn-lg w-100 continue-btn" id="continueBtn" <?= empty($photos) ? 'disabled' : '' ?> onclick="window.location.href='/estimate/register'">
            <span><?= Lang::getLocale() === 'ka' ? 'გაგრძელება' : 'Continue to Register' ?></span>
            <i class="bi bi-arrow-right ms-2"></i>
        </button>
        <p class="text-center text-muted small mt-3 mb-0">
            <i class="bi bi-shield-check me-1"></i>
            <?= Lang::getLocale() === 'ka' ? 'თქვენი ფოტოები დაცულია' : 'Your photos are secure' ?>
        </p>
    </div>
</div>

<script>
const dropzone = document.getElementById('dropzone');
const photoInput = document.getElementById('photoInput');
const photoGrid = document.getElementById('photoGrid');
const photoCount = document.getElementById('photoCount');
const continueBtn = document.getElementById('continueBtn');
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// Update photo count
function updateCount(count) {
    photoCount.textContent = count;
    continueBtn.disabled = count === 0;
}

// Drag and drop
dropzone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropzone.classList.add('dragover');
});

dropzone.addEventListener('dragleave', () => {
    dropzone.classList.remove('dragover');
});

dropzone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropzone.classList.remove('dragover');
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        uploadFiles(files);
    }
});

// File input change
photoInput.addEventListener('change', (e) => {
    if (e.target.files.length > 0) {
        uploadFiles(e.target.files);
    }
});

// Upload files
async function uploadFiles(files) {
    const formData = new FormData();
    formData.append('_token', csrfToken);
    
    for (let i = 0; i < files.length; i++) {
        formData.append('photos[]', files[i]);
    }

    // Show loading
    dropzone.querySelector('.dropzone-content').classList.add('d-none');
    dropzone.querySelector('.dropzone-loading').classList.remove('d-none');

    try {
        const response = await fetch('/estimate/upload', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            // Add new photos to grid
            data.photos.forEach(photo => {
                const photoItem = document.createElement('div');
                photoItem.className = 'photo-item';
                photoItem.dataset.id = photo.id;
                photoItem.innerHTML = `
                    <img src="${photo.path}" alt="Uploaded photo">
                    <button type="button" class="photo-remove" onclick="removePhoto('${photo.id}')">
                        <i class="bi bi-x-lg"></i>
                    </button>
                `;
                photoGrid.appendChild(photoItem);
            });
            updateCount(data.total);
        } else {
            alert(data.message || 'Upload failed');
        }
    } catch (error) {
        console.error('Upload error:', error);
        alert('<?= Lang::getLocale() === 'ka' ? 'ატვირთვა ვერ მოხერხდა' : 'Upload failed' ?>');
    } finally {
        // Hide loading
        dropzone.querySelector('.dropzone-content').classList.remove('d-none');
        dropzone.querySelector('.dropzone-loading').classList.add('d-none');
        photoInput.value = '';
    }
}

// Remove photo
async function removePhoto(photoId) {
    try {
        const response = await fetch('/estimate/remove-photo', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `_token=${csrfToken}&photo_id=${photoId}`
        });

        const data = await response.json();

        if (data.success) {
            const photoItem = document.querySelector(`.photo-item[data-id="${photoId}"]`);
            if (photoItem) {
                photoItem.remove();
            }
            updateCount(data.total);
        }
    } catch (error) {
        console.error('Remove error:', error);
    }
}

// Initial count
updateCount(<?= count($photos ?? []) ?>);
</script>
