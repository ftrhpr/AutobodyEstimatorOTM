<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value"><?= $stats['total_reports'] ?></div>
                        <div class="stat-label"><?= __('admin.total_reports') ?></div>
                    </div>
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                        <i class="bi bi-file-earmark-text text-primary fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card stat-card h-100" style="border-left-color: #ffc107;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value" style="color: #ffc107;"><?= $stats['pending_reports'] ?></div>
                        <div class="stat-label"><?= __('admin.pending_reports') ?></div>
                    </div>
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                        <i class="bi bi-hourglass-split text-warning fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card stat-card h-100" style="border-left-color: #17a2b8;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value" style="color: #17a2b8;"><?= $stats['today_reports'] ?></div>
                        <div class="stat-label"><?= __('admin.today_reports') ?></div>
                    </div>
                    <div class="rounded-circle bg-info bg-opacity-10 p-3">
                        <i class="bi bi-calendar-check text-info fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card stat-card h-100" style="border-left-color: #28a745;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value" style="color: #28a745;"><?= $stats['total_users'] ?></div>
                        <div class="stat-label"><?= __('admin.total_users') ?></div>
                    </div>
                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="bi bi-people text-success fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Pending Reports -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-hourglass-split me-2"></i>Pending Reviews
                </h5>
                <span class="badge bg-dark"><?= count($pendingReports) ?></span>
            </div>
            <div class="card-body p-0">
                <?php if (empty($pendingReports)): ?>
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-check-circle display-4"></i>
                        <p class="mt-2 mb-0">All caught up!</p>
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($pendingReports as $report): ?>
                            <a href="/admin/reports/<?= $report['id'] ?>" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?= e($report['ticket_number']) ?></strong>
                                        <?php if ($report['urgency'] === 'urgent'): ?>
                                            <span class="badge bg-danger ms-1">Urgent</span>
                                        <?php endif; ?>
                                        <br>
                                        <small class="text-muted">
                                            <?= e($report['user_name']) ?> &bull;
                                            <?= e($report['make']) ?> <?= e($report['model']) ?>
                                        </small>
                                    </div>
                                    <small class="text-muted">
                                        <?= formatDate($report['created_at'], 'd/m H:i') ?>
                                    </small>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php if (!empty($pendingReports)): ?>
                <div class="card-footer">
                    <a href="/admin/reports?status=pending" class="btn btn-sm btn-warning w-100">
                        View All Pending <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recent Reports -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Recent Reports</h5>
                <a href="/admin/reports" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Ticket</th>
                                <th>User</th>
                                <th>Vehicle</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentReports as $report): ?>
                                <tr>
                                    <td>
                                        <strong><?= e($report['ticket_number']) ?></strong>
                                        <?php if ($report['urgency'] === 'urgent'): ?>
                                            <span class="badge bg-danger">!</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small>
                                            <?= e($report['user_name']) ?><br>
                                            <span class="text-muted"><?= e($report['user_phone']) ?></span>
                                        </small>
                                    </td>
                                    <td>
                                        <?= e($report['year']) ?> <?= e($report['make']) ?><br>
                                        <small class="text-muted"><?= e($report['model']) ?></small>
                                    </td>
                                    <td>
                                        <span class="badge <?= \DamageReport::getStatusBadgeClass($report['status']) ?>">
                                            <?= __('report.status_' . $report['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small><?= formatDate($report['created_at']) ?></small>
                                    </td>
                                    <td>
                                        <a href="/admin/reports/<?= $report['id'] ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
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
