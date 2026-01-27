<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" class="form-control" name="search"
                       value="<?= e($filters['search'] ?? '') ?>"
                       placeholder="Ticket, phone, name, car...">
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
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
                <label class="form-label">From Date</label>
                <input type="date" class="form-control" name="date_from"
                       value="<?= e($filters['date_from'] ?? '') ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">To Date</label>
                <input type="date" class="form-control" name="date_to"
                       value="<?= e($filters['date_to'] ?? '') ?>">
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
                <a href="/admin/reports" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Reports Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Ticket #</th>
                        <th>User</th>
                        <th>Vehicle</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($reports)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                No reports found matching your criteria
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($reports as $report): ?>
                            <tr>
                                <td>
                                    <strong><?= e($report['ticket_number']) ?></strong>
                                    <?php if ($report['urgency'] === 'urgent'): ?>
                                        <span class="badge bg-danger ms-1">
                                            <i class="bi bi-exclamation-triangle"></i>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="/admin/users/<?= $report['user_id'] ?>" class="text-decoration-none">
                                        <?= e($report['user_name']) ?>
                                    </a>
                                    <br>
                                    <small class="text-muted"><?= e($report['user_phone']) ?></small>
                                </td>
                                <td>
                                    <?= e($report['year']) ?> <?= e($report['make']) ?> <?= e($report['model']) ?>
                                    <?php if ($report['plate_number']): ?>
                                        <br><small class="text-muted"><?= e($report['plate_number']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?= __('report.location_' . $report['damage_location']) ?></td>
                                <td>
                                    <span class="badge <?= \DamageReport::getStatusBadgeClass($report['status']) ?>">
                                        <?= __('report.status_' . $report['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <small><?= formatDate($report['created_at']) ?></small>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="/admin/reports/<?= $report['id'] ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <?php if ($report['status'] === 'pending'): ?>
                                            <a href="/admin/reports/<?= $report['id'] ?>" class="btn btn-sm btn-warning">
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
        <div class="card-footer text-muted">
            Showing <?= count($reports) ?> reports
        </div>
    <?php endif; ?>
</div>
