<div class="container py-3 py-md-4">
    <!-- Page Header with gradient -->
    <div class="dashboard-header mb-4">
        <div class="d-flex align-items-center justify-content-between position-relative" style="z-index: 1;">
            <div class="d-flex align-items-center">
                <a href="/reports" class="btn btn-light btn-icon me-3">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h5 class="mb-0 fw-bold text-white"><?= e($report['ticket_number']) ?></h5>
                        <span class="badge badge-<?= $report['status'] ?> px-3 py-2">
                            <?= __('report.status_' . $report['status']) ?>
                        </span>
                    </div>
                    <p class="opacity-75 small mb-0">
                        <i class="bi bi-calendar3 me-1"></i>
                        <?= Lang::getLocale() === 'ka' ? 'გაგზავნილია' : 'Submitted' ?> <?= formatDate($report['created_at']) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Report Details -->
        <div class="col-lg-8">
            <!-- Status Card (Urgent indicator if applicable) -->
            <?php if ($report['urgency'] === 'urgent'): ?>
                <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center mb-3" role="alert" style="border-radius: 12px;">
                    <div class="icon-box icon-box-sm bg-danger me-3">
                        <i class="bi bi-lightning-charge-fill text-white"></i>
                    </div>
                    <div>
                        <strong><?= Lang::getLocale() === 'ka' ? 'სასწრაფო მოთხოვნა' : 'Urgent Request' ?></strong>
                        <span class="d-none d-sm-inline text-danger-emphasis opacity-75"> — <?= Lang::getLocale() === 'ka' ? 'პრიორიტეტული განხილვა' : 'Priority Processing' ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Vehicle Info Card -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box icon-box-sm bg-primary-subtle me-3">
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
                                <div class="p-3 rounded-3" style="background: var(--bs-light);">
                                    <p class="text-muted small mb-1"><?= __('vehicle.plate_number') ?></p>
                                    <p class="mb-0 fw-semibold font-monospace"><?= e($vehicle['plate_number']) ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="col-6">
                            <div class="p-3 rounded-3" style="background: var(--bs-light);">
                                <p class="text-muted small mb-1"><?= __('report.damage_location') ?></p>
                                <p class="mb-0 fw-semibold"><?= $damageLocations[$report['damage_location']] ?? $report['damage_location'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Damage Description Card -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent border-0 py-3 px-4">
                    <h6 class="mb-0 fw-semibold d-flex align-items-center">
                        <span class="icon-box icon-box-sm bg-success-subtle me-2">
                            <i class="bi bi-chat-text text-success"></i>
                        </span>
                        <?= __('report.description') ?>
                    </h6>
                </div>
                <div class="card-body pt-0 px-4 pb-4">
                    <p class="mb-0 text-secondary"><?= nl2br(e($report['description'])) ?></p>
                </div>
            </div>

            <!-- Photos Card -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold d-flex align-items-center">
                        <span class="icon-box icon-box-sm bg-warning-subtle me-2">
                            <i class="bi bi-images text-warning"></i>
                        </span>
                        <?= __('report.photos') ?>
                    </h6>
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3"><?= count($photos) ?> <?= Lang::getLocale() === 'ka' ? 'ფოტო' : 'photos' ?></span>
                </div>
                <div class="card-body pt-0 px-4 pb-4">
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
                <div class="card-header bg-transparent border-0 py-3 px-4">
                    <h6 class="mb-0 fw-semibold d-flex align-items-center">
                        <i class="bi bi-lightning me-2 text-primary"></i>
                        <?= __('actions') ?>
                    </h6>
                </div>
                <div class="card-body pt-0 px-4 pb-4">
                    <div class="d-grid gap-2">
                        <?php if ($assessment): ?>
                            <a href="/reports/<?= $report['id'] ?>/pdf" class="btn btn-success py-3" style="border-radius: 12px;">
                                <i class="bi bi-file-earmark-pdf-fill me-2"></i><?= __('report.download_pdf') ?>
                            </a>
                        <?php endif; ?>

                        <a href="/reports/new" class="btn btn-primary py-2" style="border-radius: 12px;">
                            <i class="bi bi-plus-lg me-2"></i><?= __('report.new_report') ?>
                        </a>

                        <a href="/reports" class="btn btn-outline-secondary py-2" style="border-radius: 12px;">
                            <i class="bi bi-arrow-left me-2"></i><?= __('back') ?>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Status Timeline Card -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent border-0 py-3 px-4">
                    <h6 class="mb-0 fw-semibold d-flex align-items-center">
                        <i class="bi bi-clock-history me-2 text-info"></i>
                        <?= Lang::getLocale() === 'ka' ? 'სტატუსის ისტორია' : 'Status History' ?>
                    </h6>
                </div>
                <div class="card-body pt-0 px-4 pb-4">
                    <div class="report-timeline">
                        <div class="report-timeline-item active">
                            <div class="timeline-icon bg-primary">
                                <i class="bi bi-send-fill text-white"></i>
                            </div>
                            <div class="timeline-content">
                                <strong><?= Lang::getLocale() === 'ka' ? 'გაგზავნილი' : 'Submitted' ?></strong>
                                <p class="text-muted mb-0 small"><?= formatDate($report['created_at']) ?></p>
                            </div>
                        </div>

                        <?php if (in_array($report['status'], ['under_review', 'assessed', 'closed'])): ?>
                            <div class="report-timeline-item active">
                                <div class="timeline-icon bg-info">
                                    <i class="bi bi-search text-white"></i>
                                </div>
                                <div class="timeline-content">
                                    <strong><?= Lang::getLocale() === 'ka' ? 'განხილვაშია' : 'Under Review' ?></strong>
                                    <p class="text-muted mb-0 small"><?= Lang::getLocale() === 'ka' ? 'მინიჭებულია შემფასებელს' : 'Assigned to assessor' ?></p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (in_array($report['status'], ['assessed', 'closed'])): ?>
                            <div class="report-timeline-item active">
                                <div class="timeline-icon bg-success">
                                    <i class="bi bi-check-lg text-white"></i>
                                </div>
                                <div class="timeline-content">
                                    <strong><?= Lang::getLocale() === 'ka' ? 'შეფასებული' : 'Assessed' ?></strong>
                                    <p class="text-muted mb-0 small">
                                        <?= $report['assessed_at'] ? formatDate($report['assessed_at']) : '' ?>
                                    </p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($report['status'] === 'closed'): ?>
                            <div class="report-timeline-item active">
                                <div class="timeline-icon bg-secondary">
                                    <i class="bi bi-archive text-white"></i>
                                </div>
                                <div class="timeline-content">
                                    <strong><?= Lang::getLocale() === 'ka' ? 'დახურული' : 'Closed' ?></strong>
                                    <p class="text-muted mb-0 small"><?= Lang::getLocale() === 'ka' ? 'არქივში' : 'Archived' ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Need Help Card -->
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, rgba(91, 108, 242, 0.1), rgba(91, 108, 242, 0.05));">
                <div class="card-body text-center py-4">
                    <div class="icon-box icon-box-lg bg-primary-subtle mx-auto mb-3" style="width: 64px; height: 64px; border-radius: 16px;">
                        <i class="bi bi-headset fs-3 text-primary"></i>
                    </div>
                    <h6 class="fw-bold mb-2"><?= Lang::getLocale() === 'ka' ? 'გჭირდებათ დახმარება?' : 'Need Help?' ?></h6>
                    <p class="text-muted small mb-3">
                        <?= Lang::getLocale() === 'ka' ? 'კითხვების შემთხვევაში დაგვიკავშირდით' : 'Contact our support team for questions' ?>
                    </p>
                    <a href="#" class="btn btn-outline-primary btn-sm rounded-pill px-4">
                        <i class="bi bi-chat-dots me-1"></i>
                        <?= Lang::getLocale() === 'ka' ? 'მხარდაჭერა' : 'Contact Support' ?>
                    </a>
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
.icon-box-sm {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}
.icon-box-lg {
    display: flex;
    align-items: center;
    justify-content: center;
}
.bg-primary-subtle {
    background-color: rgba(91, 108, 242, 0.12) !important;
}
.bg-success-subtle {
    background-color: rgba(16, 185, 129, 0.12) !important;
}
.bg-warning-subtle {
    background-color: rgba(245, 158, 11, 0.12) !important;
}
.bg-danger-subtle {
    background-color: rgba(239, 68, 68, 0.12) !important;
}
.bg-info-subtle {
    background-color: rgba(59, 130, 246, 0.12) !important;
}
.bg-secondary-subtle {
    background-color: rgba(108, 117, 125, 0.12) !important;
}

/* Enhanced Timeline */
.report-timeline {
    position: relative;
}
.report-timeline-item {
    position: relative;
    display: flex;
    align-items: flex-start;
    padding-bottom: 1.5rem;
    margin-left: 0;
}
.report-timeline-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 17px;
    top: 36px;
    width: 2px;
    height: calc(100% - 24px);
    background: var(--bs-gray-200);
}
.report-timeline-item.active:not(:last-child)::after {
    background: linear-gradient(180deg, var(--bs-primary) 0%, var(--bs-gray-200) 100%);
}
.report-timeline-item:last-child {
    padding-bottom: 0;
}
.timeline-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-right: 1rem;
    font-size: 0.875rem;
}
.timeline-content {
    padding-top: 4px;
}

/* Badge Styles */
.badge-pending {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
}
.badge-under_review {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
}
.badge-assessed {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}
.badge-closed {
    background: linear-gradient(135deg, #6b7280, #4b5563);
    color: white;
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
