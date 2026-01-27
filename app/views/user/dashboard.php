<div class="dashboard-header">
    <div class="container">
        <h1 class="mb-2"><?= __('welcome') ?>, <?= e($currentUser['name']) ?>!</h1>
        <p class="mb-0 opacity-75">Manage your damage reports and vehicles</p>
    </div>
</div>

<div class="container">
    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3 col-6">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="stat-value"><?= $stats['total_reports'] ?></div>
                    <div class="stat-label"><?= __('report.reports') ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card stat-card h-100" style="border-left-color: #ffc107;">
                <div class="card-body">
                    <div class="stat-value" style="color: #ffc107;"><?= $stats['pending'] ?></div>
                    <div class="stat-label"><?= __('report.status_pending') ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card stat-card h-100" style="border-left-color: #17a2b8;">
                <div class="card-body">
                    <div class="stat-value" style="color: #17a2b8;"><?= $stats['under_review'] ?></div>
                    <div class="stat-label"><?= __('report.status_under_review') ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card stat-card h-100" style="border-left-color: #28a745;">
                <div class="card-body">
                    <div class="stat-value" style="color: #28a745;"><?= $stats['assessed'] ?></div>
                    <div class="stat-label"><?= __('report.status_assessed') ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recent Reports -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i><?= __('report.my_reports') ?></h5>
                    <a href="/reports/new" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i><?= __('report.new_report') ?>
                    </a>
                </div>
                <div class="card-body">
                    <?php if (empty($recentReports)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-4 text-muted"></i>
                            <p class="text-muted mt-3"><?= __('report.no_reports') ?></p>
                            <a href="/reports/new" class="btn btn-primary">
                                <i class="bi bi-plus-lg me-2"></i>Submit Your First Report
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th><?= __('report.ticket_number') ?></th>
                                        <th>Vehicle</th>
                                        <th><?= __('status') ?></th>
                                        <th><?= __('date') ?></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentReports as $report): ?>
                                        <tr>
                                            <td>
                                                <strong><?= e($report['ticket_number']) ?></strong>
                                            </td>
                                            <td>
                                                <?= e($report['year']) ?> <?= e($report['make']) ?> <?= e($report['model']) ?>
                                            </td>
                                            <td>
                                                <span class="badge <?= DamageReport::getStatusBadgeClass($report['status']) ?>">
                                                    <?= __(('report.status_' . $report['status'])) ?>
                                                </span>
                                            </td>
                                            <td><?= formatDate($report['created_at']) ?></td>
                                            <td>
                                                <a href="/reports/<?= $report['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if ($stats['total_reports'] > 5): ?>
                            <div class="text-center mt-3">
                                <a href="/reports" class="btn btn-outline-primary">
                                    View All Reports <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- My Vehicles -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-truck me-2"></i><?= __('vehicle.my_vehicles') ?></h5>
                    <a href="/vehicles/add" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-plus-lg"></i>
                    </a>
                </div>
                <div class="card-body">
                    <?php if (empty($vehicles)): ?>
                        <div class="text-center py-4">
                            <i class="bi bi-truck display-4 text-muted"></i>
                            <p class="text-muted mt-2"><?= __('vehicle.no_vehicles') ?></p>
                            <a href="/vehicles/add" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-lg me-1"></i><?= __('vehicle.add_vehicle') ?>
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($vehicles as $vehicle): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <strong><?= e($vehicle['make']) ?> <?= e($vehicle['model']) ?></strong>
                                        <br>
                                        <small class="text-muted">
                                            <?= e($vehicle['year']) ?>
                                            <?php if ($vehicle['plate_number']): ?>
                                                &bull; <?= e($vehicle['plate_number']) ?>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                    <a href="/vehicles/edit/<?= $vehicle['id'] ?>" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-3">
                            <a href="/vehicles" class="btn btn-outline-primary btn-sm w-100">
                                Manage Vehicles
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="/reports/new" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i><?= __('report.new_report') ?>
                        </a>
                        <a href="/vehicles/add" class="btn btn-outline-primary">
                            <i class="bi bi-truck me-2"></i><?= __('vehicle.add_vehicle') ?>
                        </a>
                        <a href="/profile" class="btn btn-outline-secondary">
                            <i class="bi bi-person me-2"></i><?= __('profile.edit_profile') ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
