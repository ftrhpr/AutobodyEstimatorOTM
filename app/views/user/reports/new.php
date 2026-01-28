<div class="container py-3 py-md-4">
    <!-- Page Header with gradient -->
    <div class="dashboard-header mb-4">
        <div class="d-flex align-items-center position-relative" style="z-index: 1;">
            <a href="/reports" class="btn btn-light btn-icon me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-0 fw-bold text-white"><?= __('report.new_report') ?></h4>
                <p class="opacity-75 small mb-0"><?= Lang::getLocale() === 'ka' ? 'შეავსეთ ფორმა დაზიანების შეფასებისთვის' : 'Fill in the form to get a damage assessment' ?></p>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Progress Steps -->
            <div class="d-flex justify-content-between mb-4 position-relative">
                <div class="position-absolute top-50 start-0 end-0" style="height: 2px; background: var(--bs-gray-200); z-index: 0; transform: translateY(-50%);"></div>
                <div class="step-indicator active" data-step="1">
                    <span class="step-number">
                        <i class="bi bi-car-front"></i>
                    </span>
                    <span class="step-label"><?= Lang::getLocale() === 'ka' ? 'მანქანა' : 'Vehicle' ?></span>
                </div>
                <div class="step-indicator" data-step="2">
                    <span class="step-number">
                        <i class="bi bi-geo-alt"></i>
                    </span>
                    <span class="step-label"><?= Lang::getLocale() === 'ka' ? 'დაზიანება' : 'Damage' ?></span>
                </div>
                <div class="step-indicator" data-step="3">
                    <span class="step-number">
                        <i class="bi bi-camera"></i>
                    </span>
                    <span class="step-label"><?= Lang::getLocale() === 'ka' ? 'ფოტოები' : 'Photos' ?></span>
                </div>
            </div>
            
            <form method="POST" action="/reports/new" enctype="multipart/form-data" id="reportForm">
                <?= csrf_field() ?>

                <!-- Vehicle Selection Card -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body p-4">
                        <label for="vehicle_id" class="form-label fw-semibold d-flex align-items-center">
                            <span class="icon-box icon-box-sm bg-primary-subtle me-2">
                                <i class="bi bi-car-front text-primary"></i>
                            </span>
                            <?= __('vehicle.select_vehicle') ?>
                            <span class="text-danger ms-1">*</span>
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
                        <div class="mt-3">
                            <a href="/vehicles/add" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                <i class="bi bi-plus-circle me-1"></i><?= __('vehicle.add_new') ?>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Damage Location Card -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body p-4">
                        <label class="form-label fw-semibold mb-3 d-flex align-items-center">
                            <span class="icon-box icon-box-sm bg-warning-subtle me-2">
                                <i class="bi bi-geo-alt text-warning"></i>
                            </span>
                            <?= __('report.damage_location') ?>
                            <span class="text-danger ms-1">*</span>
                        </label>
                        <div class="row g-2">
                            <?php foreach ($damageLocations as $key => $label): ?>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="damage_location" 
                                           value="<?= $key ?>" id="location_<?= $key ?>"
                                           <?= old('damage_location') === $key ? 'checked' : '' ?> required>
                                    <label class="btn btn-outline-secondary w-100 text-start py-3 d-flex align-items-center" for="location_<?= $key ?>" style="border-radius: 12px;">
                                        <i class="bi bi-check-circle-fill me-2 text-success check-icon" style="display: none;"></i>
                                        <span><?= $label ?></span>
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
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body p-4">
                        <label class="form-label fw-semibold mb-3 d-flex align-items-center">
                            <span class="icon-box icon-box-sm bg-info-subtle me-2">
                                <i class="bi bi-clock text-info"></i>
                            </span>
                            <?= __('report.urgency') ?>
                        </label>
                        <div class="d-flex gap-2 flex-wrap">
                            <?php foreach ($urgencyLevels as $key => $label): ?>
                                <input type="radio" class="btn-check" name="urgency" value="<?= $key ?>"
                                       id="urgency_<?= $key ?>" <?= (old('urgency', 'standard') === $key) ? 'checked' : '' ?> required>
                                <label class="btn btn-outline-<?= $key === 'urgent' ? 'danger' : 'secondary' ?> flex-fill py-3" for="urgency_<?= $key ?>" style="border-radius: 12px;">
                                    <?php if ($key === 'urgent'): ?>
                                        <i class="bi bi-lightning-charge-fill me-1"></i>
                                    <?php elseif ($key === 'standard'): ?>
                                        <i class="bi bi-clock-history me-1"></i>
                                    <?php endif; ?>
                                    <?= $label ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Description Card -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body p-4">
                        <label for="description" class="form-label fw-semibold d-flex align-items-center">
                            <span class="icon-box icon-box-sm bg-success-subtle me-2">
                                <i class="bi bi-chat-text text-success"></i>
                            </span>
                            <?= __('report.description') ?>
                            <span class="text-danger ms-1">*</span>
                        </label>
                        <textarea class="form-control <?= hasError('description') ? 'is-invalid' : '' ?>"
                                  id="description" name="description" rows="4"
                                  placeholder="<?= Lang::getLocale() === 'ka' ? 'აღწერეთ დაზიანება: რა მოხდა, რა ნაწილებია დაზიანებული...' : 'Describe the damage: what happened, which parts are affected...' ?>"
                                  required minlength="10" style="border-radius: 12px;"><?= old('description') ?></textarea>
                        <div class="form-text d-flex justify-content-between mt-2">
                            <span><span id="charCount" class="fw-medium">0</span>/2000</span>
                            <span class="text-muted"><?= Lang::getLocale() === 'ka' ? 'მინ. 10 სიმბოლო' : 'min. 10 chars' ?></span>
                        </div>
                        <?php if ($error = error('description')): ?>
                            <div class="invalid-feedback"><?= e($error) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Photo Upload Card -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body p-4">
                        <label class="form-label fw-semibold mb-3 d-flex align-items-center">
                            <span class="icon-box icon-box-sm bg-danger-subtle me-2">
                                <i class="bi bi-camera text-danger"></i>
                            </span>
                            <?= __('report.photos') ?>
                            <span class="text-danger ms-1">*</span>
                        </label>
                        <div class="file-upload-area" id="uploadArea" style="border-radius: 16px; border: 2px dashed var(--bs-gray-300); padding: 2rem; text-align: center; cursor: pointer; transition: all 0.2s ease; background: var(--bs-light);">
                            <div class="icon-box icon-box-lg bg-primary-subtle mx-auto mb-3" style="width: 64px; height: 64px; border-radius: 16px;">
                                <i class="bi bi-camera-fill text-primary fs-3"></i>
                            </div>
                            <p class="mb-1 fw-medium">
                                <?= Lang::getLocale() === 'ka' ? 'დააჭირეთ ან ჩააგდეთ ფოტოები' : 'Tap or drag photos here' ?>
                            </p>
                            <small class="text-muted d-block">
                                <?= __('report.max_photos', ['count' => $maxPhotos]) ?> &bull;
                                <?= __('report.max_size', ['size' => $maxSize / 1024 / 1024]) ?>
                            </small>
                        </div>
                        <input type="file" id="photos" name="photos[]" multiple accept="image/*"
                               class="d-none" capture="environment" required>
                        <div class="file-preview mt-3" id="photoPreview" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 0.75rem;"></div>
                        <?php if ($error = error('photos')): ?>
                            <div class="text-danger small mt-1"><?= e($error) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Tips Card -->
                <div class="card bg-gradient border-0 mb-4" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(245, 158, 11, 0.05));">
                    <div class="card-body py-3 px-4">
                        <p class="fw-semibold mb-2 small d-flex align-items-center">
                            <span class="icon-box icon-box-sm bg-warning me-2">
                                <i class="bi bi-lightbulb-fill text-white"></i>
                            </span>
                            <?= Lang::getLocale() === 'ka' ? 'რჩევები უკეთესი შედეგისთვის:' : 'Tips for better results:' ?>
                        </p>
                        <ul class="mb-0 ps-4 small text-muted">
                            <li class="mb-1"><?= Lang::getLocale() === 'ka' ? 'გადაიღეთ კარგად განათებული ფოტოები სხვადასხვა კუთხიდან' : 'Take well-lit photos from multiple angles' ?></li>
                            <li class="mb-1"><?= Lang::getLocale() === 'ka' ? 'გადაიღეთ როგორც ახლო, ისე შორიდან' : 'Include close-ups and wider shots' ?></li>
                            <li><?= Lang::getLocale() === 'ka' ? 'ყველა დაზიანებული ადგილი უნდა ჩანდეს' : 'Make sure all damage is visible' ?></li>
                        </ul>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-grid gap-2 mb-4">
                    <button type="submit" class="btn btn-primary btn-lg py-3" id="submitBtn" style="border-radius: 12px;">
                        <i class="bi bi-send-fill me-2"></i><?= __('report.submit_report') ?>
                    </button>
                    <a href="/reports" class="btn btn-outline-secondary py-2" style="border-radius: 12px;">
                        <i class="bi bi-x-lg me-1"></i><?= Lang::getLocale() === 'ka' ? 'გაუქმება' : 'Cancel' ?>
                    </a>
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
    position: relative;
    z-index: 1;
}
.step-indicator.active {
    opacity: 1;
}
.step-number {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: var(--bs-primary);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
    box-shadow: 0 4px 12px rgba(91, 108, 242, 0.3);
}
.step-indicator:not(.active) .step-number {
    background: white;
    color: var(--bs-gray-500);
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.step-label {
    font-size: 0.8rem;
    font-weight: 500;
    color: var(--bs-gray-700);
}
.btn-check:checked + .btn-outline-secondary {
    background: var(--bs-primary);
    border-color: var(--bs-primary);
    color: white;
}
.btn-check:checked + .btn-outline-secondary .check-icon {
    display: inline-block !important;
}
.file-upload-area:hover,
.file-upload-area.dragover {
    border-color: var(--bs-primary);
    background: rgba(91, 108, 242, 0.05);
}
.file-upload-area.dragover {
    transform: scale(1.01);
}
.icon-box-lg {
    display: flex;
    align-items: center;
    justify-content: center;
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
