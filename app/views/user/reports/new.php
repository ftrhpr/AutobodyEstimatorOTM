<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="bi bi-file-earmark-plus me-2"></i><?= __('report.new_report') ?>
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="/reports/new" enctype="multipart/form-data" id="reportForm">
                        <?= csrf_field() ?>

                        <!-- Vehicle Selection -->
                        <div class="mb-4">
                            <label for="vehicle_id" class="form-label">
                                <?= __('vehicle.select_vehicle') ?> *
                            </label>
                            <select class="form-select <?= hasError('vehicle_id') ? 'is-invalid' : '' ?>"
                                    id="vehicle_id" name="vehicle_id" required>
                                <option value="">Choose your vehicle...</option>
                                <?php foreach ($vehicles as $vehicle): ?>
                                    <option value="<?= $vehicle['id'] ?>"
                                        <?= old('vehicle_id') == $vehicle['id'] ? 'selected' : '' ?>>
                                        <?= e($vehicle['year']) ?> <?= e($vehicle['make']) ?> <?= e($vehicle['model']) ?>
                                        <?php if ($vehicle['plate_number']): ?>
                                            (<?= e($vehicle['plate_number']) ?>)
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($error = error('vehicle_id')): ?>
                                <div class="invalid-feedback"><?= e($error) ?></div>
                            <?php endif; ?>
                            <div class="form-text">
                                <a href="/vehicles/add" class="text-decoration-none">
                                    <i class="bi bi-plus-circle me-1"></i><?= __('vehicle.add_new') ?>
                                </a>
                            </div>
                        </div>

                        <!-- Damage Location -->
                        <div class="mb-4">
                            <label class="form-label"><?= __('report.damage_location') ?> *</label>
                            <div class="row g-2">
                                <?php foreach ($damageLocations as $key => $label): ?>
                                    <div class="col-6 col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio"
                                                   name="damage_location" value="<?= $key ?>"
                                                   id="location_<?= $key ?>"
                                                   <?= old('damage_location') === $key ? 'checked' : '' ?>
                                                   required>
                                            <label class="form-check-label" for="location_<?= $key ?>">
                                                <?= $label ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php if ($error = error('damage_location')): ?>
                                <div class="text-danger small mt-1"><?= e($error) ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Urgency -->
                        <div class="mb-4">
                            <label class="form-label"><?= __('report.urgency') ?> *</label>
                            <div class="d-flex gap-3">
                                <?php foreach ($urgencyLevels as $key => $label): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                               name="urgency" value="<?= $key ?>"
                                               id="urgency_<?= $key ?>"
                                               <?= (old('urgency', 'standard') === $key) ? 'checked' : '' ?>
                                               required>
                                        <label class="form-check-label" for="urgency_<?= $key ?>">
                                            <?= $label ?>
                                            <?php if ($key === 'urgent'): ?>
                                                <span class="badge bg-danger">Priority</span>
                                            <?php endif; ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label">
                                <?= __('report.description') ?> *
                            </label>
                            <textarea class="form-control <?= hasError('description') ? 'is-invalid' : '' ?>"
                                      id="description" name="description" rows="4"
                                      placeholder="Describe the damage in detail: what happened, visible damage, affected parts..."
                                      required minlength="10"><?= old('description') ?></textarea>
                            <div class="form-text">
                                <span id="charCount">0</span>/2000 characters (minimum 10)
                            </div>
                            <?php if ($error = error('description')): ?>
                                <div class="invalid-feedback"><?= e($error) ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Photo Upload -->
                        <div class="mb-4">
                            <label class="form-label"><?= __('report.photos') ?> *</label>
                            <div class="file-upload-area" id="uploadArea">
                                <i class="bi bi-cloud-upload"></i>
                                <p class="mb-2">
                                    <strong>Drag and drop photos here</strong><br>
                                    or click to browse
                                </p>
                                <small class="text-muted">
                                    <?= __('report.max_photos', ['count' => $maxPhotos]) ?> &bull;
                                    <?= __('report.max_size', ['size' => $maxSize / 1024 / 1024]) ?>
                                </small>
                            </div>
                            <input type="file" id="photos" name="photos[]" multiple accept="image/*"
                                   class="d-none" required>
                            <div class="file-preview" id="photoPreview"></div>
                            <?php if ($error = error('photos')): ?>
                                <div class="text-danger small mt-1"><?= e($error) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Tips for better assessment:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Take clear, well-lit photos from multiple angles</li>
                                <li>Include close-ups of the damage and wider shots showing context</li>
                                <li>Make sure all damaged areas are visible in the photos</li>
                            </ul>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100" id="submitBtn">
                            <i class="bi bi-send me-2"></i><?= __('report.submit_report') ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Character counter
const description = document.getElementById('description');
const charCount = document.getElementById('charCount');

description.addEventListener('input', function() {
    charCount.textContent = this.value.length;
});

// File upload handler
const uploadArea = document.getElementById('uploadArea');
const photosInput = document.getElementById('photos');
const photoPreview = document.getElementById('photoPreview');
const maxFiles = <?= $maxPhotos ?>;
const maxSize = <?= $maxSize ?>;
let selectedFiles = [];

uploadArea.addEventListener('click', () => photosInput.click());

uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.classList.add('dragover');
});

uploadArea.addEventListener('dragleave', () => {
    uploadArea.classList.remove('dragover');
});

uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.classList.remove('dragover');
    handleFiles(e.dataTransfer.files);
});

photosInput.addEventListener('change', (e) => {
    handleFiles(e.target.files);
});

function handleFiles(fileList) {
    const files = Array.from(fileList);

    for (const file of files) {
        if (selectedFiles.length >= maxFiles) {
            alert(`Maximum ${maxFiles} photos allowed`);
            break;
        }

        if (!file.type.startsWith('image/')) {
            alert(`${file.name}: Only image files are allowed`);
            continue;
        }

        if (file.size > maxSize) {
            alert(`${file.name}: File size exceeds ${maxSize / 1024 / 1024}MB limit`);
            continue;
        }

        selectedFiles.push(file);
    }

    updatePreview();
    updateFileInput();
}

function removeFile(index) {
    selectedFiles.splice(index, 1);
    updatePreview();
    updateFileInput();
}

function updatePreview() {
    photoPreview.innerHTML = '';

    selectedFiles.forEach((file, index) => {
        const item = document.createElement('div');
        item.className = 'file-preview-item';

        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);

        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'remove-btn';
        removeBtn.innerHTML = '&times;';
        removeBtn.onclick = () => removeFile(index);

        item.appendChild(img);
        item.appendChild(removeBtn);
        photoPreview.appendChild(item);
    });
}

function updateFileInput() {
    const dt = new DataTransfer();
    selectedFiles.forEach(file => dt.items.add(file));
    photosInput.files = dt.files;
}

// Form submission
document.getElementById('reportForm').addEventListener('submit', function(e) {
    if (selectedFiles.length === 0) {
        e.preventDefault();
        alert('Please upload at least one photo of the damage');
        return;
    }

    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="loading-spinner me-2"></span>Submitting...';
});
</script>
