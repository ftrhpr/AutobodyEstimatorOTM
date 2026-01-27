<div class="container py-3 py-md-4">
    <!-- Mobile Header -->
    <div class="d-flex align-items-center mb-4">
        <a href="/reports" class="btn btn-light btn-icon me-3 d-md-none">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div class="flex-grow-1">
            <h5 class="mb-0 fw-bold"><?= e($report['ticket_number']) ?></h5>
            <p class="text-muted small mb-0"><?= Lang::getLocale() === 'ka' ? 'გაგზავნილია' : 'Submitted' ?> <?= formatDate($report['created_at']) ?></p>
        </div>
        <span class="badge <?= DamageReport::getStatusBadgeClass($report['status']) ?> px-3 py-2">
            <?= __('report.status_' . $report['status']) ?>
        </span>
    </div>

    <!-- Desktop Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4 d-none d-md-block">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard"><?= __('dashboard') ?></a></li>
            <li class="breadcrumb-item"><a href="/reports"><?= __('report.reports') ?></a></li>
            <li class="breadcrumb-item active"><?= e($report['ticket_number']) ?></li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Report Details -->
        <div class="col-lg-8">
            <!-- Status Card (Urgent indicator if applicable) -->
            <?php if ($report['urgency'] === 'urgent'): ?>
                <div class="alert alert-danger d-flex align-items-center mb-3" role="alert">
                    <i class="bi bi-lightning-charge-fill me-2 fs-5"></i>
                    <div>
                        <strong><?= Lang::getLocale() === 'ka' ? 'სასწრაფო' : 'Urgent Request' ?></strong>
                        <span class="d-none d-sm-inline"> - <?= Lang::getLocale() === 'ka' ? 'პრიორიტეტული განხილვა' : 'Priority Processing' ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Vehicle Info Card -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-primary-subtle me-3">
                            <i class="bi bi-car-front-fill text-primary"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0"><?= Lang::getLocale() === 'ka' ? 'მანქანა' : 'Vehicle' ?></p>
                            <h6 class="mb-0 fw-bold"><?= e($vehicle['year']) ?> <?= e($vehicle['make']) ?> <?= e($vehicle['model']) ?></h6>
                        </div>
                    </div>
                    
                    <div class="row g-3">
                        <?php if ($vehicle['plate_number']): ?>
                            <div class="col-6">
                                <p class="text-muted small mb-1"><?= __('vehicle.plate_number') ?></p>
                                <p class="mb-0 fw-semibold"><?= e($vehicle['plate_number']) ?></p>
                            </div>
                        <?php endif; ?>
                        <div class="col-6">
                            <p class="text-muted small mb-1"><?= __('report.damage_location') ?></p>
                            <p class="mb-0 fw-semibold"><?= $damageLocations[$report['damage_location']] ?? $report['damage_location'] ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Damage Description Card -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-chat-text me-2 text-primary"></i><?= __('report.description') ?>
                    </h6>
                </div>
                <div class="card-body pt-0">
                    <p class="mb-0"><?= nl2br(e($report['description'])) ?></p>
                </div>
            </div>

            <!-- Photos Card -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent border-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-images me-2 text-primary"></i><?= __('report.photos') ?>
                    </h6>
                    <span class="badge bg-secondary-subtle text-secondary"><?= count($photos) ?> <?= Lang::getLocale() === 'ka' ? 'ფოტო' : 'photos' ?></span>
                </div>
                <div class="card-body pt-0">
                    <div class="photo-gallery">
                        <?php foreach ($photos as $photo): ?>
                            <div class="photo-item">
                                <img src="/<?= e($photo['file_path']) ?>" alt="<?= Lang::getLocale() === 'ka' ? 'დაზიანების ფოტო' : 'Damage photo' ?>">
                                <div class="photo-overlay">
                                    <i class="bi bi-zoom-in text-white fs-3"></i>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <?php if ($assessment): ?>
                <!-- Assessment Details Card -->
                <div class="card border-0 shadow-sm border-start border-success border-4">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h6 class="mb-0 fw-semibold text-success">
                            <i class="bi bi-clipboard-check me-2"></i><?= __('assessment.assessment') ?>
                        </h6>
                    </div>
                    <div class="card-body pt-0">
                        <!-- Assessment Items -->
                        <div class="mb-4">
                            <?php foreach ($assessmentItems as $item): ?>
                                <div class="d-flex justify-content-between py-2 border-bottom">
                                    <span><?= e($item['description']) ?></span>
                                    <span class="fw-semibold"><?= formatMoney($item['cost']) ?></span>
                                </div>
                            <?php endforeach; ?>
                            <div class="d-flex justify-content-between py-3 bg-success-subtle rounded mt-2 px-3">
                                <strong><?= __('assessment.total_cost') ?></strong>
                                <strong class="text-success fs-5"><?= formatMoney($assessment['total_cost']) ?></strong>
                            </div>
                        </div>

                        <?php if ($assessment['estimated_days']): ?>
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-clock text-muted me-2"></i>
                                <span class="text-muted"><?= __('assessment.estimated_days') ?>:</span>
                                <span class="fw-semibold ms-2"><?= $assessment['estimated_days'] ?> <?= Lang::getLocale() === 'ka' ? 'დღე' : 'days' ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if ($assessment['comments']): ?>
                            <div class="alert alert-info border-0 mb-3">
                                <strong class="d-block mb-2"><i class="bi bi-chat-dots me-2"></i><?= __('assessment.comments') ?></strong>
                                <p class="mb-0"><?= nl2br(e($assessment['comments'])) ?></p>
                            </div>
                        <?php endif; ?>

                        <div class="text-muted small">
                            <i class="bi bi-person me-1"></i>
                            <?= __('assessment.assessed_by') ?>: <?= e($assessment['admin_name']) ?>
                            &bull;
                            <?= formatDate($assessment['created_at']) ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Actions Card -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="mb-0 fw-semibold"><?= __('actions') ?></h6>
                </div>
                <div class="card-body pt-0">
                    <div class="d-grid gap-2">
                        <?php if ($assessment): ?>
                            <a href="/reports/<?= $report['id'] ?>/pdf" class="btn btn-success py-2">
                                <i class="bi bi-file-earmark-pdf me-2"></i><?= __('report.download_pdf') ?>
                            </a>
                        <?php endif; ?>

                        <a href="/reports/new" class="btn btn-primary py-2">
                            <i class="bi bi-plus-lg me-2"></i><?= __('report.new_report') ?>
                        </a>

                        <a href="/reports" class="btn btn-outline-secondary py-2 d-none d-md-block">
                            <i class="bi bi-arrow-left me-2"></i><?= __('back') ?>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Status Timeline Card -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="mb-0 fw-semibold"><?= Lang::getLocale() === 'ka' ? 'სტატუსის ისტორია' : 'Status History' ?></h6>
                </div>
                <div class="card-body pt-0">
                    <div class="report-timeline">
                        <div class="report-timeline-item active">
                            <strong><?= Lang::getLocale() === 'ka' ? 'გაგზავნილი' : 'Submitted' ?></strong>
                            <p class="text-muted mb-0 small"><?= formatDate($report['created_at']) ?></p>
                        </div>

                        <?php if (in_array($report['status'], ['under_review', 'assessed', 'closed'])): ?>
                            <div class="report-timeline-item active">
                                <strong><?= Lang::getLocale() === 'ka' ? 'განხილვაშია' : 'Under Review' ?></strong>
                                <p class="text-muted mb-0 small"><?= Lang::getLocale() === 'ka' ? 'მინიჭებულია შემფასებელს' : 'Assigned to assessor' ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if (in_array($report['status'], ['assessed', 'closed'])): ?>
                            <div class="report-timeline-item active">
                                <strong><?= Lang::getLocale() === 'ka' ? 'შეფასებული' : 'Assessed' ?></strong>
                                <p class="text-muted mb-0 small">
                                    <?= $report['assessed_at'] ? formatDate($report['assessed_at']) : '' ?>
                                </p>
                            </div>
                        <?php endif; ?>

                        <?php if ($report['status'] === 'closed'): ?>
                            <div class="report-timeline-item active">
                                <strong><?= Lang::getLocale() === 'ka' ? 'დახურული' : 'Closed' ?></strong>
                                <p class="text-muted mb-0 small"><?= Lang::getLocale() === 'ka' ? 'არქივში' : 'Archived' ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Need Help Card -->
            <div class="card border-0 bg-light">
                <div class="card-body text-center py-4">
                    <div class="mb-3">
                        <i class="bi bi-headset text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                    <h6 class="fw-bold"><?= Lang::getLocale() === 'ka' ? 'გჭირდებათ დახმარება?' : 'Need Help?' ?></h6>
                    <p class="text-muted small mb-0">
                        <?= Lang::getLocale() === 'ka' ? 'კითხვების შემთხვევაში დაგვიკავშირდით' : 'Contact our support team for questions' ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.icon-box {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}
.bg-primary-subtle {
    background-color: rgba(99, 102, 241, 0.1) !important;
}
.bg-success-subtle {
    background-color: rgba(34, 197, 94, 0.1) !important;
}
.bg-secondary-subtle {
    background-color: rgba(108, 117, 125, 0.1) !important;
}
.report-timeline-item {
    position: relative;
    padding-left: 24px;
    padding-bottom: 16px;
    border-left: 2px solid var(--bs-gray-300);
    margin-left: 8px;
}
.report-timeline-item:last-child {
    border-left-color: transparent;
    padding-bottom: 0;
}
.report-timeline-item::before {
    content: '';
    position: absolute;
    left: -6px;
    top: 4px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: var(--bs-gray-300);
}
.report-timeline-item.active::before {
    background: var(--bs-primary, #6366f1);
}
.report-timeline-item.active {
    border-left-color: var(--bs-primary, #6366f1);
}
</style>

<!-- Photo Lightbox Modal -->
<div class="photo-lightbox" id="photoLightbox">
    <button class="photo-lightbox-close" onclick="closeLightbox()">
        <i class="bi bi-x-lg"></i>
    </button>
    <button class="photo-lightbox-nav photo-lightbox-prev" onclick="navigateLightbox(-1)">
        <i class="bi bi-chevron-left"></i>
    </button>
    <img src="" alt="<?= Lang::getLocale() === 'ka' ? 'დაზიანების ფოტო' : 'Damage photo' ?>" id="lightboxImage">
    <button class="photo-lightbox-nav photo-lightbox-next" onclick="navigateLightbox(1)">
        <i class="bi bi-chevron-right"></i>
    </button>
    <div class="photo-lightbox-counter">
        <span id="lightboxCounter">1</span> / <?= count($photos) ?>
    </div>
</div>

<script>
const photos = <?= json_encode(array_map(fn($p) => '/' . $p['file_path'], $photos)) ?>;
let currentIndex = 0;

// Open lightbox
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

// Keyboard navigation
document.addEventListener('keydown', (e) => {
    const lightbox = document.getElementById('photoLightbox');
    if (!lightbox.classList.contains('active')) return;
    
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowLeft') navigateLightbox(-1);
    if (e.key === 'ArrowRight') navigateLightbox(1);
});

// Close on background click
document.getElementById('photoLightbox').addEventListener('click', (e) => {
    if (e.target.id === 'photoLightbox') closeLightbox();
});

// Swipe support for mobile
let touchStartX = 0;
document.getElementById('photoLightbox').addEventListener('touchstart', (e) => {
    touchStartX = e.changedTouches[0].screenX;
});

document.getElementById('photoLightbox').addEventListener('touchend', (e) => {
    const diff = e.changedTouches[0].screenX - touchStartX;
    if (Math.abs(diff) > 50) {
        navigateLightbox(diff > 0 ? -1 : 1);
    }
});
</script>
