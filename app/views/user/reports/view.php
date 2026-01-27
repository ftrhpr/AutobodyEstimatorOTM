<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard"><?= __('dashboard') ?></a></li>
            <li class="breadcrumb-item"><a href="/reports"><?= __('report.reports') ?></a></li>
            <li class="breadcrumb-item active"><?= e($report['ticket_number']) ?></li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Report Details -->
        <div class="col-lg-8">
            <!-- Status Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="mb-1"><?= __('report.ticket_number') ?>: <?= e($report['ticket_number']) ?></h4>
                            <p class="text-muted mb-0">Submitted <?= formatDate($report['created_at']) ?></p>
                        </div>
                        <div class="text-end">
                            <span class="badge <?= DamageReport::getStatusBadgeClass($report['status']) ?> fs-6">
                                <?= __('report.status_' . $report['status']) ?>
                            </span>
                            <?php if ($report['urgency'] === 'urgent'): ?>
                                <br>
                                <span class="badge bg-danger mt-1">
                                    <i class="bi bi-exclamation-triangle me-1"></i>Urgent
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vehicle Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-truck me-2"></i>Vehicle Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Vehicle:</strong>
                                <?= e($vehicle['year']) ?> <?= e($vehicle['make']) ?> <?= e($vehicle['model']) ?>
                            </p>
                            <?php if ($vehicle['plate_number']): ?>
                                <p class="mb-2">
                                    <strong><?= __('vehicle.plate_number') ?>:</strong>
                                    <?= e($vehicle['plate_number']) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong><?= __('report.damage_location') ?>:</strong>
                                <?= $damageLocations[$report['damage_location']] ?? $report['damage_location'] ?>
                            </p>
                            <p class="mb-0">
                                <strong><?= __('report.urgency') ?>:</strong>
                                <?= __('report.urgency_' . $report['urgency']) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Damage Description -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-card-text me-2"></i><?= __('report.description') ?></h5>
                </div>
                <div class="card-body">
                    <p class="mb-0"><?= nl2br(e($report['description'])) ?></p>
                </div>
            </div>

            <!-- Photos -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-images me-2"></i><?= __('report.photos') ?></h5>
                    <span class="badge bg-secondary"><?= count($photos) ?> photos</span>
                </div>
                <div class="card-body">
                    <div class="photo-gallery">
                        <?php foreach ($photos as $photo): ?>
                            <div class="photo-item">
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
                <!-- Assessment Details -->
                <div class="card border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-clipboard-check me-2"></i><?= __('assessment.assessment') ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm mb-3">
                                <thead>
                                    <tr>
                                        <th><?= __('assessment.item_description') ?></th>
                                        <th class="text-end"><?= __('assessment.item_cost') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($assessmentItems as $item): ?>
                                        <tr>
                                            <td><?= e($item['description']) ?></td>
                                            <td class="text-end"><?= formatMoney($item['cost']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-success">
                                        <th><?= __('assessment.total_cost') ?></th>
                                        <th class="text-end"><?= formatMoney($assessment['total_cost']) ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <?php if ($assessment['estimated_days']): ?>
                            <p class="mb-2">
                                <i class="bi bi-clock me-2"></i>
                                <strong><?= __('assessment.estimated_days') ?>:</strong>
                                <?= $assessment['estimated_days'] ?> days
                            </p>
                        <?php endif; ?>

                        <?php if ($assessment['comments']): ?>
                            <div class="alert alert-info mb-0">
                                <strong><i class="bi bi-chat-dots me-2"></i><?= __('assessment.comments') ?>:</strong>
                                <p class="mb-0 mt-2"><?= nl2br(e($assessment['comments'])) ?></p>
                            </div>
                        <?php endif; ?>

                        <div class="mt-3 text-muted small">
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
            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><?= __('actions') ?></h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <?php if ($assessment): ?>
                            <a href="/reports/<?= $report['id'] ?>/pdf" class="btn btn-success">
                                <i class="bi bi-file-earmark-pdf me-2"></i><?= __('report.download_pdf') ?>
                            </a>
                        <?php endif; ?>

                        <a href="/reports" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i><?= __('back') ?>
                        </a>

                        <a href="/reports/new" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-2"></i><?= __('report.new_report') ?>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Status Timeline -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Status History</h5>
                </div>
                <div class="card-body">
                    <div class="report-timeline">
                        <div class="report-timeline-item">
                            <strong>Submitted</strong>
                            <p class="text-muted mb-0 small"><?= formatDate($report['created_at']) ?></p>
                        </div>

                        <?php if (in_array($report['status'], ['under_review', 'assessed', 'closed'])): ?>
                            <div class="report-timeline-item">
                                <strong>Under Review</strong>
                                <p class="text-muted mb-0 small">Report assigned to assessor</p>
                            </div>
                        <?php endif; ?>

                        <?php if (in_array($report['status'], ['assessed', 'closed'])): ?>
                            <div class="report-timeline-item">
                                <strong>Assessed</strong>
                                <p class="text-muted mb-0 small">
                                    <?= $report['assessed_at'] ? formatDate($report['assessed_at']) : '' ?>
                                </p>
                            </div>
                        <?php endif; ?>

                        <?php if ($report['status'] === 'closed'): ?>
                            <div class="report-timeline-item">
                                <strong>Closed</strong>
                                <p class="text-muted mb-0 small">Report archived</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Need Help -->
            <div class="card mt-4 bg-light">
                <div class="card-body text-center">
                    <i class="bi bi-question-circle display-4 text-muted"></i>
                    <h6 class="mt-3">Need Help?</h6>
                    <p class="text-muted small mb-0">
                        If you have questions about your assessment, please contact our support team.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
