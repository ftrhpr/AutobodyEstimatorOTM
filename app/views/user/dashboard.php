<div class="dashboard-header">
    <div class="container position-relative" style="z-index: 1;">
        <div class="d-flex align-items-center gap-4">
            <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 64px; height: 64px; background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); border: 2px solid rgba(255,255,255,0.3);">
                <i class="bi bi-person-fill text-white" style="font-size: 1.75rem;"></i>
            </div>
            <div>
                <p class="mb-1 opacity-75" style="font-size: 0.9375rem;"><?= Lang::getLocale() === 'ka' ? 'კეთილი იყოს თქვენი მობრძანება' : 'Welcome back' ?> 👋</p>
                <h1 class="mb-0" style="font-weight: 800; letter-spacing: -0.03em;"><?= e(explode(' ', $currentUser['name'])[0]) ?>!</h1>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- Statistics Cards -->
    <div class="row g-3 mb-4" style="margin-top: -2.5rem;">
        <div class="col-6 col-lg-3">
            <div class="stat-card h-100 slide-up">
                <div class="stat-icon stat-icon-primary">
                    <i class="bi bi-file-earmark-text-fill"></i>
                </div>
                <div class="stat-value"><?= $stats['total_reports'] ?></div>
                <div class="stat-label"><?= __('report.reports') ?></div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card warning h-100 slide-up" style="animation-delay: 50ms;">
                <div class="stat-icon stat-icon-warning">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div class="stat-value"><?= $stats['pending'] ?></div>
                <div class="stat-label"><?= __('report.status_pending') ?></div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card info h-100 slide-up" style="animation-delay: 100ms;">
                <div class="stat-icon stat-icon-info">
                    <i class="bi bi-search"></i>
                </div>
                <div class="stat-value"><?= $stats['under_review'] ?></div>
                <div class="stat-label"><?= __('report.status_under_review') ?></div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card success h-100 slide-up" style="animation-delay: 150ms;">
                <div class="stat-icon stat-icon-success">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="stat-value"><?= $stats['assessed'] ?></div>
                <div class="stat-label"><?= __('report.status_assessed') ?></div>
            </div>
        </div>
    </div>

    <!-- Quick Action Button (Mobile) -->
    <div class="d-lg-none mb-4">
        <a href="/reports/new" class="btn btn-primary btn-lg w-100 d-flex align-items-center justify-content-center gap-2">
            <i class="bi bi-plus-circle-fill"></i>
            <span><?= __('report.new_report') ?></span>
        </a>
    </div>

    <div class="row g-4">
        <!-- Recent Reports -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold d-flex align-items-center gap-2">
                        <span class="icon-box icon-box-sm bg-primary-subtle text-primary">
                            <i class="bi bi-file-earmark-text-fill"></i>
                        </span>
                        <?= __('report.my_reports') ?>
                    </h5>
                    <a href="/reports/new" class="btn btn-primary btn-sm d-none d-lg-inline-flex align-items-center gap-2">
                        <i class="bi bi-plus-lg"></i>
                        <span><?= __('report.new_report') ?></span>
                    </a>
                </div>
                <div class="card-body p-0 p-lg-4">
                    <?php if (empty($recentReports)): ?>
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="bi bi-inbox"></i>
                            </div>
                            <h5><?= __('report.no_reports') ?></h5>
                            <p><?= Lang::getLocale() === 'ka' ? 'შექმენით თქვენი პირველი განაცხადი დაზიანების შესაფასებლად' : 'Create your first damage report to get started' ?></p>
                            <a href="/reports/new" class="btn btn-primary">
                                <i class="bi bi-plus-lg me-2"></i><?= __('report.new_report') ?>
                            </a>
                        </div>
                    <?php else: ?>
                        <!-- Mobile: Card View -->
                        <div class="d-lg-none stagger-in p-3">
                            <?php foreach ($recentReports as $report): ?>
                                <a href="/reports/<?= $report['id'] ?>" class="report-card">
                                    <div class="report-card-header">
                                        <div class="report-card-title"><?= e($report['ticket_number']) ?></div>
                                        <span class="badge badge-<?= $report['status'] ?>">
                                            <?= __('report.status_' . $report['status']) ?>
                                        </span>
                                    </div>
                                    <div class="report-card-vehicle">
                                        <i class="bi bi-car-front-fill me-1"></i>
                                        <?= e($report['make']) ?> <?= e($report['model']) ?> (<?= e($report['year']) ?>)
                                    </div>
                                    <div class="report-card-footer">
                                        <span class="text-muted">
                                            <i class="bi bi-calendar3 me-1"></i><?= formatDate($report['created_at']) ?>
                                        </span>
                                        <i class="bi bi-arrow-right text-primary"></i>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Desktop: Table View -->
                        <div class="d-none d-lg-block table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th><?= __('report.ticket_number') ?></th>
                                        <th><?= __('vehicle.vehicles') ?></th>
                                        <th><?= __('status') ?></th>
                                        <th><?= __('date') ?></th>
                                        <th class="text-end"><?= Lang::getLocale() === 'ka' ? 'მოქმედება' : 'Action' ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentReports as $report): ?>
                                        <tr>
                                            <td>
                                                <span class="fw-bold text-primary"><?= e($report['ticket_number']) ?></span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="icon-box icon-box-sm bg-primary-subtle text-primary">
                                                        <i class="bi bi-car-front-fill"></i>
                                                    </span>
                                                    <div>
                                                        <div class="fw-semibold"><?= e($report['make']) ?> <?= e($report['model']) ?></div>
                                                        <div class="small text-muted"><?= e($report['year']) ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-<?= $report['status'] ?>">
                                                    <?= __('report.status_' . $report['status']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-muted"><?= formatDate($report['created_at']) ?></span>
                                            </td>
                                            <td class="text-end">
                                                <a href="/reports/<?= $report['id'] ?>" class="btn btn-sm btn-light">
                                                    <i class="bi bi-eye me-1"></i><?= Lang::getLocale() === 'ka' ? 'ნახვა' : 'View' ?>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <?php if ($stats['total_reports'] > 5): ?>
                            <div class="text-center p-4 border-top">
                                <a href="/reports" class="btn btn-outline-primary d-inline-flex align-items-center gap-2">
                                    <span><?= Lang::getLocale() === 'ka' ? 'ყველა განაცხადი' : 'View All Reports' ?></span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- My Vehicles & Quick Actions -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold d-flex align-items-center gap-2">
                        <span class="icon-box icon-box-sm bg-primary-subtle text-primary">
                            <i class="bi bi-car-front-fill"></i>
                        </span>
                        <?= __('vehicle.my_vehicles') ?>
                    </h5>
                    <a href="/vehicles/add" class="btn btn-primary btn-icon btn-sm">
                        <i class="bi bi-plus-lg"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($vehicles)): ?>
                        <div class="empty-state py-5">
                            <div class="empty-state-icon" style="width: 80px; height: 80px;">
                                <i class="bi bi-car-front" style="font-size: 2rem;"></i>
                            </div>
                            <p class="text-muted mb-3"><?= __('vehicle.no_vehicles') ?></p>
                            <a href="/vehicles/add" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-lg me-1"></i><?= __('vehicle.add_vehicle') ?>
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="stagger-in">
                            <?php foreach ($vehicles as $vehicle): ?>
                                <a href="/vehicles/edit/<?= $vehicle['id'] ?>" class="list-group-item list-group-item-action d-flex align-items-center gap-3 p-4">
                                    <div class="icon-box bg-primary-subtle text-primary">
                                        <i class="bi bi-car-front-fill"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold"><?= e($vehicle['make']) ?> <?= e($vehicle['model']) ?></div>
                                        <div class="small text-muted">
                                            <?= e($vehicle['year']) ?>
                                            <?php if ($vehicle['plate_number']): ?>
                                                &bull; <?= e($vehicle['plate_number']) ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <i class="bi bi-chevron-right text-muted"></i>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        <div class="p-4 border-top">
                            <a href="/vehicles" class="btn btn-outline-primary btn-sm w-100">
                                <?= Lang::getLocale() === 'ka' ? 'ყველა მანქანა' : 'Manage Vehicles' ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Actions (Desktop) -->
            <div class="card mt-4 d-none d-lg-block">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold d-flex align-items-center gap-2">
                        <span class="icon-box icon-box-sm bg-warning-subtle text-warning">
                            <i class="bi bi-lightning-charge-fill"></i>
                        </span>
                        <?= Lang::getLocale() === 'ka' ? 'სწრაფი მოქმედება' : 'Quick Actions' ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="/reports/new" class="btn btn-primary d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-plus-circle-fill"></i>
                            <span><?= __('report.new_report') ?></span>
                        </a>
                        <a href="/vehicles/add" class="btn btn-outline-primary d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-car-front-fill"></i>
                            <span><?= __('vehicle.add_vehicle') ?></span>
                        </a>
                        <a href="/profile" class="btn btn-light d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-person-gear"></i>
                            <span><?= __('profile.edit_profile') ?></span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Help Card -->
            <div class="help-card mt-4 d-none d-lg-block">
                <div class="help-card-icon">
                    <i class="bi bi-question-circle-fill"></i>
                </div>
                <h6><?= Lang::getLocale() === 'ka' ? 'გჭირდებათ დახმარება?' : 'Need Help?' ?></h6>
                <p><?= Lang::getLocale() === 'ka' ? 'დაგვიკავშირდით ნებისმიერ დროს' : 'Contact us anytime for support' ?></p>
                <a href="tel:+995555000000" class="btn btn-sm btn-outline-primary mt-2">
                    <i class="bi bi-telephone me-1"></i>
                    <?= Lang::getLocale() === 'ka' ? 'დარეკვა' : 'Call Us' ?>
                </a>
            </div>
        </div>
    </div>
</div>
