<div class="row g-4">
    <!-- Report Details -->
    <div class="col-lg-8">
        <!-- Header -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h4 class="mb-1">Report #<?= e($report['ticket_number']) ?></h4>
                        <p class="text-muted mb-0">Submitted <?= formatDate($report['created_at']) ?></p>
                    </div>
                    <div class="text-end">
                        <span class="badge <?= \DamageReport::getStatusBadgeClass($report['status']) ?> fs-6">
                            <?= __('report.status_' . $report['status']) ?>
                        </span>
                        <?php if ($report['urgency'] === 'urgent'): ?>
                            <br>
                            <span class="badge bg-danger mt-1">
                                <i class="bi bi-exclamation-triangle me-1"></i>URGENT
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- User & Vehicle Info -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-person me-2"></i>Customer Information</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-1"><strong><?= e($report['user_name']) ?></strong></p>
                        <p class="mb-1">
                            <i class="bi bi-phone me-1 text-muted"></i>
                            <a href="tel:<?= e($report['user_phone']) ?>"><?= e($report['user_phone']) ?></a>
                        </p>
                        <a href="/admin/users/<?= $report['user_id'] ?>" class="btn btn-sm btn-outline-primary mt-2">
                            View Profile
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-truck me-2"></i>Vehicle Information</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-1">
                            <strong><?= e($vehicle['year']) ?> <?= e($vehicle['make']) ?> <?= e($vehicle['model']) ?></strong>
                        </p>
                        <?php if ($vehicle['plate_number']): ?>
                            <p class="mb-1">
                                <i class="bi bi-hash me-1 text-muted"></i><?= e($vehicle['plate_number']) ?>
                            </p>
                        <?php endif; ?>
                        <?php if ($vehicle['vin']): ?>
                            <p class="mb-0">
                                <i class="bi bi-upc me-1 text-muted"></i>VIN: <?= e($vehicle['vin']) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Damage Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-exclamation-diamond me-2"></i>Damage Details</h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-4">
                        <strong>Location:</strong>
                        <span class="badge bg-secondary ms-1">
                            <?= $damageLocations[$report['damage_location']] ?? $report['damage_location'] ?>
                        </span>
                    </div>
                    <div class="col-sm-4">
                        <strong>Urgency:</strong>
                        <span class="badge <?= $report['urgency'] === 'urgent' ? 'bg-danger' : 'bg-info' ?> ms-1">
                            <?= ucfirst($report['urgency']) ?>
                        </span>
                    </div>
                </div>
                <hr>
                <h6>Description:</h6>
                <p class="mb-0"><?= nl2br(e($report['description'])) ?></p>
            </div>
        </div>

        <!-- Photos -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-images me-2"></i>Photos</h6>
                <span class="badge bg-secondary"><?= count($photos) ?></span>
            </div>
            <div class="card-body">
                <div class="photo-gallery">
                    <?php foreach ($photos as $index => $photo): ?>
                        <div class="photo-item" data-index="<?= $index ?>">
                            <img src="/<?= e($photo['file_path']) ?>" alt="Damage photo">
                            <div class="photo-overlay">
                                <i class="bi bi-zoom-in text-white fs-3"></i>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <?php if ($assessment): ?>
            <!-- Existing Assessment -->
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="bi bi-clipboard-check me-2"></i>Current Assessment</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-3">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th class="text-end">Cost</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($assessment['items'] ?? [] as $item): ?>
                                <tr>
                                    <td><?= e($item['description']) ?></td>
                                    <td class="text-end"><?= formatMoney($item['cost']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-success">
                                <th>TOTAL</th>
                                <th class="text-end"><?= formatMoney($assessment['total_cost']) ?></th>
                            </tr>
                        </tfoot>
                    </table>

                    <?php if ($assessment['estimated_days']): ?>
                        <p class="mb-2">
                            <strong>Estimated Time:</strong> <?= $assessment['estimated_days'] ?> days
                        </p>
                    <?php endif; ?>

                    <?php if ($assessment['comments']): ?>
                        <div class="alert alert-info mb-0">
                            <strong>Comments:</strong><br>
                            <?= nl2br(e($assessment['comments'])) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar - Assessment Form -->
    <div class="col-lg-4">
        <!-- Status Update -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Update Status</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="/admin/reports/<?= $report['id'] ?>/status">
                    <?= csrf_field() ?>
                    <div class="d-flex gap-2">
                        <select name="status" class="form-select">
                            <?php foreach ($statuses as $key => $label): ?>
                                <option value="<?= $key ?>" <?= $report['status'] === $key ? 'selected' : '' ?>>
                                    <?= $label ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Assessment Form -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0">
                    <i class="bi bi-clipboard-check me-2"></i>
                    <?= $assessment ? 'Update Assessment' : 'Create Assessment' ?>
                </h6>
            </div>
            <div class="card-body">
                <form method="POST" action="/admin/reports/<?= $report['id'] ?>/assess" id="assessmentForm">
                    <?= csrf_field() ?>

                    <!-- Repair Items -->
                    <div class="mb-3">
                        <label class="form-label">Repair Items *</label>
                        <div id="repairItems">
                            <?php if ($assessment && !empty($assessment['items'])): ?>
                                <?php foreach ($assessment['items'] as $index => $item): ?>
                                    <div class="repair-item row g-2 mb-2">
                                        <div class="col-7">
                                            <input type="text" class="form-control form-control-sm"
                                                   name="item_description[]"
                                                   value="<?= e($item['description']) ?>"
                                                   placeholder="Description" required>
                                        </div>
                                        <div class="col-4">
                                            <input type="number" class="form-control form-control-sm"
                                                   name="item_cost[]" step="0.01" min="0"
                                                   value="<?= $item['cost'] ?>"
                                                   placeholder="Cost" required>
                                        </div>
                                        <div class="col-1">
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-item">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="repair-item row g-2 mb-2">
                                    <div class="col-7">
                                        <input type="text" class="form-control form-control-sm"
                                               name="item_description[]" placeholder="Description" required>
                                    </div>
                                    <div class="col-4">
                                        <input type="number" class="form-control form-control-sm"
                                               name="item_cost[]" step="0.01" min="0" placeholder="Cost" required>
                                    </div>
                                    <div class="col-1">
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-item" disabled>
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addItem">
                            <i class="bi bi-plus me-1"></i>Add Item
                        </button>
                    </div>

                    <!-- Total Display -->
                    <div class="alert alert-primary py-2 mb-3">
                        <strong>Total: <span id="totalCost">0.00</span> GEL</strong>
                    </div>

                    <!-- Estimated Days -->
                    <div class="mb-3">
                        <label class="form-label">Estimated Repair Time (days)</label>
                        <input type="number" class="form-control" name="estimated_days" min="1"
                               value="<?= $assessment['estimated_days'] ?? '' ?>"
                               placeholder="e.g., 5">
                    </div>

                    <!-- Comments -->
                    <div class="mb-3">
                        <label class="form-label">Comments / Recommendations</label>
                        <textarea class="form-control" name="comments" rows="3"
                                  placeholder="Additional notes for the customer..."><?= e($assessment['comments'] ?? '') ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-check-lg me-2"></i>Save Assessment
                    </button>
                </form>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mt-4">
            <div class="card-body">
                <a href="/admin/reports" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-arrow-left me-2"></i>Back to Reports
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Lightbox Modal -->
<div class="modal fade" id="lightboxModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark">
            <div class="modal-header border-0">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img src="" id="lightboxImage" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script>
// Lightbox
function openLightbox(src) {
    document.getElementById('lightboxImage').src = src;
    new bootstrap.Modal(document.getElementById('lightboxModal')).show();
}

// Repair items management
const repairItems = document.getElementById('repairItems');
const addItemBtn = document.getElementById('addItem');

addItemBtn.addEventListener('click', () => {
    const itemHtml = `
        <div class="repair-item row g-2 mb-2">
            <div class="col-7">
                <input type="text" class="form-control form-control-sm"
                       name="item_description[]" placeholder="Description" required>
            </div>
            <div class="col-4">
                <input type="number" class="form-control form-control-sm"
                       name="item_cost[]" step="0.01" min="0" placeholder="Cost" required>
            </div>
            <div class="col-1">
                <button type="button" class="btn btn-sm btn-outline-danger remove-item">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        </div>
    `;
    repairItems.insertAdjacentHTML('beforeend', itemHtml);
    updateRemoveButtons();
    calculateTotal();
});

repairItems.addEventListener('click', (e) => {
    if (e.target.closest('.remove-item')) {
        e.target.closest('.repair-item').remove();
        updateRemoveButtons();
        calculateTotal();
    }
});

repairItems.addEventListener('input', (e) => {
    if (e.target.name === 'item_cost[]') {
        calculateTotal();
    }
});

function updateRemoveButtons() {
    const items = repairItems.querySelectorAll('.repair-item');
    items.forEach((item, index) => {
        const btn = item.querySelector('.remove-item');
        btn.disabled = items.length === 1;
    });
}

function calculateTotal() {
    const costs = repairItems.querySelectorAll('input[name="item_cost[]"]');
    let total = 0;
    costs.forEach(input => {
        total += parseFloat(input.value) || 0;
    });
    document.getElementById('totalCost').textContent = total.toFixed(2);
}

// Initialize
updateRemoveButtons();
calculateTotal();

// Photo Lightbox
const photos = <?= json_encode(array_map(fn($p) => '/' . $p['file_path'], $photos)) ?>;
let currentIndex = 0;

document.querySelectorAll('.photo-item').forEach((item, index) => {
    item.addEventListener('click', () => {
        currentIndex = index;
        showPhoto();
        document.getElementById('photoLightbox').classList.add('active');
        document.body.style.overflow = 'hidden';
    });
});

function showPhoto() {
    document.getElementById('lightboxImage').src = photos[currentIndex];
    document.getElementById('lightboxCounter').textContent = currentIndex + 1;
}

function closeLightbox() {
    document.getElementById('photoLightbox').classList.remove('active');
    document.body.style.overflow = '';
}

function navigateLightbox(direction) {
    currentIndex += direction;
    if (currentIndex < 0) currentIndex = photos.length - 1;
    if (currentIndex >= photos.length) currentIndex = 0;
    showPhoto();
}

document.addEventListener('keydown', (e) => {
    const lightbox = document.getElementById('photoLightbox');
    if (!lightbox.classList.contains('active')) return;
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowLeft') navigateLightbox(-1);
    if (e.key === 'ArrowRight') navigateLightbox(1);
});

document.getElementById('photoLightbox').addEventListener('click', (e) => {
    if (e.target.id === 'photoLightbox') closeLightbox();
});
</script>

<!-- Photo Lightbox Modal -->
<div class="photo-lightbox" id="photoLightbox">
    <button class="photo-lightbox-close" onclick="closeLightbox()">
        <i class="bi bi-x-lg"></i>
    </button>
    <button class="photo-lightbox-nav photo-lightbox-prev" onclick="navigateLightbox(-1)">
        <i class="bi bi-chevron-left"></i>
    </button>
    <img src="" alt="Damage photo" id="lightboxImage">
    <button class="photo-lightbox-nav photo-lightbox-next" onclick="navigateLightbox(1)">
        <i class="bi bi-chevron-right"></i>
    </button>
    <div class="photo-lightbox-counter">
        <span id="lightboxCounter">1</span> / <?= count($photos) ?>
    </div>
</div>
