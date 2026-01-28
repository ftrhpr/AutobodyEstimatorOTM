<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card h-100 slide-up">
            <div class="stat-icon stat-icon-primary">
                <i class="bi bi-file-earmark-text-fill"></i>
            </div>
            <div class="stat-value"><?= $stats['total_reports'] ?></div>
            <div class="stat-label"><?= __('admin.total_reports') ?></div>
            <div class="stat-change positive">
                <i class="bi bi-arrow-up"></i>
                <?= Lang::getLocale() === 'ka' ? 'სულ' : 'Total' ?>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="stat-card warning h-100 slide-up" style="animation-delay: 50ms;">
            <div class="stat-icon stat-icon-warning">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="stat-value"><?= $stats['pending_reports'] ?></div>
            <div class="stat-label"><?= __('admin.pending_reports') ?></div>
            <?php if ($stats['pending_reports'] > 0): ?>
                <div class="stat-change negative">
                    <i class="bi bi-exclamation-circle"></i>
                    <?= Lang::getLocale() === 'ka' ? 'საჭიროებს ყურადღებას' : 'Needs attention' ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="stat-card info h-100 slide-up" style="animation-delay: 100ms;">
            <div class="stat-icon stat-icon-info">
                <i class="bi bi-calendar-check-fill"></i>
            </div>
            <div class="stat-value"><?= $stats['today_reports'] ?></div>
            <div class="stat-label"><?= __('admin.today_reports') ?></div>
            <div class="stat-change positive">
                <i class="bi bi-calendar3"></i>
                <?= Lang::getLocale() === 'ka' ? 'დღეს' : 'Today' ?>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="stat-card success h-100 slide-up" style="animation-delay: 150ms;">
            <div class="stat-icon stat-icon-success">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-value"><?= $stats['total_users'] ?></div>
            <div class="stat-label"><?= __('admin.total_users') ?></div>
            <div class="stat-change positive">
                <i class="bi bi-person-check"></i>
                <?= Lang::getLocale() === 'ka' ? 'რეგისტრირებული' : 'Registered' ?>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Pending Reports -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);">
                <h6 class="mb-0 fw-bold d-flex align-items-center gap-2" style="color: #92400e;">
                    <span class="icon-box icon-box-sm" style="background: rgba(245, 158, 11, 0.2);">
                        <i class="bi bi-hourglass-split" style="color: #d97706;"></i>
                    </span>
                    <?= Lang::getLocale() === 'ka' ? 'მომლოდინე' : 'Pending Reviews' ?>
                </h6>
                <span class="badge" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white;"><?= count($pendingReports) ?></span>
            </div>
            <div class="card-body p-0">
                <?php if (empty($pendingReports)): ?>
                    <div class="empty-state py-5">
                        <div class="empty-state-icon" style="width: 72px; height: 72px; background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);">
                            <i class="bi bi-check-circle-fill" style="font-size: 2rem; color: #16a34a;"></i>
                        </div>
                        <h6 class="fw-bold text-success mt-3"><?= Lang::getLocale() === 'ka' ? 'ყველა შესრულებულია!' : 'All caught up!' ?></h6>
                        <p class="text-muted mb-0"><?= Lang::getLocale() === 'ka' ? 'მომლოდინე განაცხადები არ არის' : 'No pending reports' ?></p>
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush stagger-in">
                        <?php foreach ($pendingReports as $report): ?>
                            <a href="/admin/reports/<?= $report['id'] ?>" class="list-group-item list-group-item-action border-0 py-3 px-4">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-bold text-primary mb-1"><?= e($report['ticket_number']) ?></div>
                                        <?php if ($report['urgency'] === 'urgent'): ?>
                                            <span class="badge badge-lg" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white;">
                                                <i class="bi bi-lightning-charge-fill me-1"></i><?= Lang::getLocale() === 'ka' ? 'სასწრაფო' : 'Urgent' ?>
                                            </span>
                                        <?php endif; ?>
                                        <div class="small text-muted mt-1">
                                            <i class="bi bi-person me-1"></i><?= e($report['user_name']) ?>
                                        </div>
                                        <div class="small text-muted">
                                            <i class="bi bi-car-front me-1"></i><?= e($report['make']) ?> <?= e($report['model']) ?>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="small text-muted"><?= formatDate($report['created_at'], 'd/m') ?></span>
                                        <div class="small text-muted"><?= formatDate($report['created_at'], 'H:i') ?></div>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php if (!empty($pendingReports)): ?>
                <div class="card-footer bg-transparent border-top">
                    <a href="/admin/reports?status=pending" class="btn btn-warning w-100 d-flex align-items-center justify-content-center gap-2">
                        <span><?= Lang::getLocale() === 'ka' ? 'ყველა მომლოდინე' : 'View All Pending' ?></span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recent Reports -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold d-flex align-items-center gap-2">
                    <span class="icon-box icon-box-sm bg-primary-subtle text-primary">
                        <i class="bi bi-clock-history"></i>
                    </span>
                    <?= Lang::getLocale() === 'ka' ? 'ბოლო განაცხადები' : 'Recent Reports' ?>
                </h5>
                <a href="/admin/reports" class="btn btn-sm btn-primary d-flex align-items-center gap-2">
                    <span><?= Lang::getLocale() === 'ka' ? 'ყველა' : 'View All' ?></span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th><?= Lang::getLocale() === 'ka' ? 'ბილეთი' : 'Ticket' ?></th>
                                <th><?= Lang::getLocale() === 'ka' ? 'მომხმარებელი' : 'User' ?></th>
                                <th><?= Lang::getLocale() === 'ka' ? 'მანქანა' : 'Vehicle' ?></th>
                                <th><?= Lang::getLocale() === 'ka' ? 'სტატუსი' : 'Status' ?></th>
                                <th><?= Lang::getLocale() === 'ka' ? 'თარიღი' : 'Date' ?></th>
                                <th class="text-end"><?= Lang::getLocale() === 'ka' ? 'მოქმედება' : 'Action' ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentReports as $report): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="fw-bold text-primary"><?= e($report['ticket_number']) ?></span>
                                            <?php if ($report['urgency'] === 'urgent'): ?>
                                                <span class="badge bg-danger" title="Urgent"><i class="bi bi-lightning-charge-fill"></i></span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="icon-box icon-box-sm bg-primary-subtle text-primary">
                                                <i class="bi bi-person-fill"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold"><?= e($report['user_name']) ?></div>
                                                <div class="small text-muted"><?= e($report['user_phone']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold"><?= e($report['year']) ?> <?= e($report['make']) ?></div>
                                        <div class="small text-muted"><?= e($report['model']) ?></div>
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
                                        <a href="/admin/reports/<?= $report['id'] ?>" class="btn btn-sm btn-light d-inline-flex align-items-center gap-1">
                                            <i class="bi bi-eye"></i>
                                            <span class="d-none d-xl-inline"><?= Lang::getLocale() === 'ka' ? 'ნახვა' : 'View' ?></span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
