<div class="container py-3 py-md-4">
    <!-- Page Header -->
    <div class="d-flex align-items-center mb-4">
        <a href="/reports" class="btn btn-light btn-icon me-3 d-md-none">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="mb-0 fw-bold"><?= __('report.new_report') ?></h4>
            <p class="text-muted small mb-0 d-none d-md-block"><?= Lang::getLocale() === 'ka' ? 'შეავსეთ ფორმა დაზიანების შეფასებისთვის' : 'Fill in the form to get a damage assessment' ?></p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form method="POST" action="/reports/new" enctype="multipart/form-data" id="reportForm">
                <?= csrf_field() ?>

                <!-- Step indicator for mobile -->
                <div class="d-flex justify-content-between mb-4 d-md-none">
                    <div class="step-indicator active" data-step="1">
                        <span class="step-number">1</span>
                        <span class="step-label"><?= Lang::getLocale() === 'ka' ? 'მანქანა' : 'Vehicle' ?></span>
                    </div>
                    <div class="step-indicator" data-step="2">
                        <span class="step-number">2</span>
                        <span class="step-label"><?= Lang::getLocale() === 'ka' ? 'დაზიანება' : 'Damage' ?></span>
                    </div>
                    <div class="step-indicator" data-step="3">
                        <span class="step-number">3</span>
                        <span class="step-label"><?= Lang::getLocale() === 'ka' ? 'ფოტოები' : 'Photos' ?></span>
                    </div>
                </div>

                <!-- Vehicle Selection Card -->
                <div class="card mb-3">
                    <div class="card-body">
                        <label for="vehicle_id" class="form-label fw-semibold">
                            <i class="bi bi-car-front me-2 text-primary"></i><?= __('vehicle.select_vehicle') ?>
                        </label>
                        <select class="form-select form-select-lg <?= hasError('vehicle_id') ? 'is-invalid' : '' ?>"
                                id="vehicle_id" name="vehicle_id" required>
                            <option value=""><?= Lang::getLocale() === 'ka' ? 'აირჩიეთ მანქანა...' : 'Choose your vehicle...' ?></option>
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
                        <div class="mt-2">
                            <a href="/vehicles/add" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-plus-circle me-1"></i><?= __('vehicle.add_new') ?>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Damage Location Card -->
                <div class="card mb-3">
                    <div class="card-body">
                        <label class="form-label fw-semibold mb-3">
                            <i class="bi bi-geo-alt me-2 text-primary"></i><?= __('report.damage_location') ?>
                        </label>
                        <div class="row g-2">
                            <?php foreach ($damageLocations as $key => $label): ?>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="damage_location" 
                                           value="<?= $key ?>" id="location_<?= $key ?>"
                                           <?= old('damage_location') === $key ? 'checked' : '' ?> required>
                                    <label class="btn btn-outline-secondary w-100 text-start py-3" for="location_<?= $key ?>">
                                        <i class="bi bi-check-circle me-2 d-none"></i><?= $label ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if ($error = error('damage_location')): ?>
                            <div class="text-danger small mt-2"><?= e($error) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Urgency Card -->
                <div class="card mb-3">
                    <div class="card-body">
                        <label class="form-label fw-semibold mb-3">
                            <i class="bi bi-clock me-2 text-primary"></i><?= __('report.urgency') ?>
                        </label>
                        <div class="d-flex gap-2 flex-wrap">
                            <?php foreach ($urgencyLevels as $key => $label): ?>
                                <input type="radio" class="btn-check" name="urgency" value="<?= $key ?>"
                                       id="urgency_<?= $key ?>" <?= (old('urgency', 'standard') === $key) ? 'checked' : '' ?> required>
                                <label class="btn btn-outline-<?= $key === 'urgent' ? 'danger' : 'secondary' ?> flex-fill" for="urgency_<?= $key ?>">
                                    <?php if ($key === 'urgent'): ?>
                                        <i class="bi bi-lightning-charge me-1"></i>
                                    <?php endif; ?>
                                    <?= $label ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Description Card -->
                <div class="card mb-3">
                    <div class="card-body">
                        <label for="description" class="form-label fw-semibold">
                            <i class="bi bi-chat-text me-2 text-primary"></i><?= __('report.description') ?>
                        </label>
                        <textarea class="form-control <?= hasError('description') ? 'is-invalid' : '' ?>"
                                  id="description" name="description" rows="4"
                                  placeholder="<?= Lang::getLocale() === 'ka' ? 'აღწერეთ დაზიანება: რა მოხდა, რა ნაწილებია დაზიანებული...' : 'Describe the damage: what happened, which parts are affected...' ?>"
                                  required minlength="10"><?= old('description') ?></textarea>
                        <div class="form-text d-flex justify-content-between">
                            <span><span id="charCount">0</span>/2000</span>
                            <span class="text-muted"><?= Lang::getLocale() === 'ka' ? 'მინ. 10 სიმბოლო' : 'min. 10 chars' ?></span>
                        </div>
                        <?php if ($error = error('description')): ?>
                            <div class="invalid-feedback"><?= e($error) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Photo Upload Card -->
                <div class="card mb-3">
                    <div class="card-body">
                        <label class="form-label fw-semibold mb-3">
                            <i class="bi bi-camera me-2 text-primary"></i><?= __('report.photos') ?>
                        </label>
                        <div class="file-upload-area" id="uploadArea">
                            <i class="bi bi-camera-fill text-primary" style="font-size: 2.5rem;"></i>
                            <p class="mb-1 mt-2">
                                <strong><?= Lang::getLocale() === 'ka' ? 'დააჭირეთ ან ჩააგდეთ ფოტოები' : 'Tap or drag photos here' ?></strong>
                            </p>
                            <small class="text-muted">
                                <?= __('report.max_photos', ['count' => $maxPhotos]) ?> &bull;
                                <?= __('report.max_size', ['size' => $maxSize / 1024 / 1024]) ?>
                            </small>
                        </div>
                        <input type="file" id="photos" name="photos[]" multiple accept="image/*"
                               class="d-none" capture="environment" required>
                        <div class="file-preview" id="photoPreview"></div>
                        <?php if ($error = error('photos')): ?>
                            <div class="text-danger small mt-1"><?= e($error) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Tips Card -->
                <div class="card bg-light border-0 mb-4">
                    <div class="card-body py-3">
                        <p class="fw-semibold mb-2 small">
                            <i class="bi bi-lightbulb me-2 text-warning"></i>
                            <?= Lang::getLocale() === 'ka' ? 'რჩევები:' : 'Tips:' ?>
                        </p>
                        <ul class="mb-0 ps-4 small text-muted">
                            <li><?= Lang::getLocale() === 'ka' ? 'გადაიღეთ კარგად განათებული ფოტოები სხვადასხვა კუთხიდან' : 'Take well-lit photos from multiple angles' ?></li>
                            <li><?= Lang::getLocale() === 'ka' ? 'გადაიღეთ როგორც ახლო, ისე შორიდან' : 'Include close-ups and wider shots' ?></li>
                            <li><?= Lang::getLocale() === 'ka' ? 'ყველა დაზიანებული ადგილი უნდა ჩანდეს' : 'Make sure all damage is visible' ?></li>
                        </ul>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-grid mb-4">
                    <button type="submit" class="btn btn-primary btn-lg py-3" id="submitBtn">
                        <i class="bi bi-send me-2"></i><?= __('report.submit_report') ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.step-indicator {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
    opacity: 0.5;
}
.step-indicator.active {
    opacity: 1;
}
.step-number {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--bs-primary, #6366f1);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}
.step-indicator:not(.active) .step-number {
    background: var(--bs-gray-300);
}
.step-label {
    font-size: 0.75rem;
    color: var(--bs-gray-600);
}
.btn-check:checked + .btn-outline-secondary {
    background: var(--bs-primary, #6366f1);
    border-color: var(--bs-primary, #6366f1);
    color: white;
}
.btn-check:checked + .btn-outline-secondary .bi-check-circle {
    display: inline-block !important;
}
</style>

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
        alert('<?= Lang::getLocale() === "ka" ? "გთხოვთ ატვირთოთ მინიმუმ ერთი ფოტო" : "Please upload at least one photo" ?>');
        return;
    }

    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="loading-spinner me-2"></span><?= Lang::getLocale() === "ka" ? "იგზავნება..." : "Submitting..." ?>';
});
</script>
