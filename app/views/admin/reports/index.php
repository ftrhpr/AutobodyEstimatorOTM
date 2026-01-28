<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1 fw-bold">Damage Reports</h4>
        <p class="text-muted mb-0">Manage and assess all damage reports</p>
    </div>
    <div class="d-flex gap-2">
        <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill">
            <i class="bi bi-hourglass-split me-1"></i>
            <?= $pendingCount ?? 0 ?> Pending
        </span>
    </div>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-medium text-muted">Search</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 ps-0" name="search"
                           value="<?= e($filters['search'] ?? '') ?>"
                           placeholder="Ticket, phone, name, car...">
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-medium text-muted">Status</label>
                <select class="form-select" name="status">
                    <option value="">All Statuses</option>
                    <?php foreach ($statuses as $key => $label): ?>
                        <option value="<?= $key ?>" <?= ($filters['status'] ?? '') === $key ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-medium text-muted">From Date</label>
                <input type="date" class="form-control" name="date_from"
                       value="<?= e($filters['date_from'] ?? '') ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-medium text-muted">To Date</label>
                <input type="date" class="form-control" name="date_to"
                       value="<?= e($filters['date_to'] ?? '') ?>">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-funnel me-1"></i>Apply Filters
                </button>
                <a href="/admin/reports" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Reports Table -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Ticket #</th>
                        <th class="py-3">User</th>
                        <th class="py-3">Vehicle</th>
                        <th class="py-3">Location</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Date</th>
                        <th class="py-3 text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($reports)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="icon-box icon-box-lg bg-light mx-auto mb-3" style="width: 64px; height: 64px; border-radius: 16px;">
                                    <i class="bi bi-inbox fs-2 text-muted"></i>
                                </div>
                                <p class="text-muted mb-0">No reports found matching your criteria</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($reports as $report): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <strong class="text-primary"><?= e($report['ticket_number']) ?></strong>
                                        <?php if ($report['urgency'] === 'urgent'): ?>
                                            <span class="badge bg-danger rounded-pill">
                                                <i class="bi bi-lightning-charge-fill"></i>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <a href="/admin/users/<?= $report['user_id'] ?>" class="text-decoration-none fw-medium">
                                        <?= e($report['user_name']) ?>
                                    </a>
                                    <br>
                                    <small class="text-muted"><?= e($report['user_phone']) ?></small>
                                </td>
                                <td>
                                    <span class="fw-medium"><?= e($report['year']) ?> <?= e($report['make']) ?> <?= e($report['model']) ?></span>
                                    <?php if ($report['plate_number']): ?>
                                        <br><code class="small"><?= e($report['plate_number']) ?></code>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark"><?= __('report.location_' . $report['damage_location']) ?></span>
                                </td>
                                <td>
                                    <span class="badge badge-<?= $report['status'] ?>">
                                        <?= __('report.status_' . $report['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted"><?= formatDate($report['created_at']) ?></small>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="/admin/reports/<?= $report['id'] ?>" class="btn btn-sm btn-outline-primary" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <?php if ($report['status'] === 'pending'): ?>
                                            <a href="/admin/reports/<?= $report['id'] ?>" class="btn btn-sm btn-warning" title="Assess Report">
                                                <i class="bi bi-clipboard-check"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if (!empty($reports)): ?>
        <div class="card-footer bg-light border-0 text-muted py-3 px-4">
            <div class="d-flex align-items-center justify-content-between">
                <span>Showing <strong><?= count($reports) ?></strong> reports</span>
                <nav aria-label="Page navigation">
                    <!-- Pagination would go here -->
                </nav>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
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
.icon-box-lg {
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
