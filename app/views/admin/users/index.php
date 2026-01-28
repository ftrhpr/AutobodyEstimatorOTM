<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1 fw-bold">User Management</h4>
        <p class="text-muted mb-0">View and manage platform users</p>
    </div>
    <div class="d-flex gap-2">
        <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
            <i class="bi bi-people me-1"></i>
            <?= $totalUsers ?? count($users) ?> Total Users
        </span>
    </div>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-medium text-muted">Search</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 ps-0" name="search"
                           value="<?= e($filters['search'] ?? '') ?>"
                           placeholder="Name, phone, or email...">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-medium text-muted">Status</label>
                <select class="form-select" name="status">
                    <option value="">All Statuses</option>
                    <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="pending" <?= ($filters['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="blocked" <?= ($filters['status'] ?? '') === 'blocked' ? 'selected' : '' ?>>Blocked</option>
                </select>
            </div>
            <div class="col-md-5 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-funnel me-1"></i>Apply Filters
                </button>
                <a href="/admin/users" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">User</th>
                        <th class="py-3">Phone</th>
                        <th class="py-3">Email</th>
                        <th class="py-3">Status</th>
                        <th class="py-3 text-center">Vehicles</th>
                        <th class="py-3 text-center">Reports</th>
                        <th class="py-3">Joined</th>
                        <th class="py-3 text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="icon-box icon-box-lg bg-light mx-auto mb-3" style="width: 64px; height: 64px; border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-people fs-2 text-muted"></i>
                                </div>
                                <p class="text-muted mb-0">No users found</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-3" style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, var(--bs-primary), var(--bs-indigo)); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                            <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                        </div>
                                        <strong><?= e($user['name']) ?></strong>
                                    </div>
                                </td>
                                <td>
                                    <a href="tel:<?= e($user['phone']) ?>" class="text-decoration-none"><?= e($user['phone']) ?></a>
                                </td>
                                <td>
                                    <?= $user['email'] ? e($user['email']) : '<span class="text-muted">—</span>' ?>
                                </td>
                                <td>
                                    <?php
                                    $statusConfig = match($user['status']) {
                                        'active' => ['class' => 'bg-success-subtle text-success', 'icon' => 'bi-check-circle'],
                                        'pending' => ['class' => 'bg-warning-subtle text-warning', 'icon' => 'bi-hourglass-split'],
                                        'blocked' => ['class' => 'bg-danger-subtle text-danger', 'icon' => 'bi-slash-circle'],
                                        default => ['class' => 'bg-secondary-subtle text-secondary', 'icon' => 'bi-question-circle']
                                    };
                                    ?>
                                    <span class="badge <?= $statusConfig['class'] ?> px-3 py-2">
                                        <i class="bi <?= $statusConfig['icon'] ?> me-1"></i>
                                        <?= ucfirst($user['status']) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark"><?= $user['vehicle_count'] ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary-subtle text-primary"><?= $user['report_count'] ?></span>
                                </td>
                                <td>
                                    <small class="text-muted"><?= formatDate($user['created_at'], 'd/m/Y') ?></small>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="/admin/users/<?= $user['id'] ?>" class="btn btn-sm btn-outline-primary" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <?php if ($user['status'] !== 'blocked'): ?>
                                            <form method="POST" action="/admin/users/<?= $user['id'] ?>/status" class="d-inline">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="action" value="block">
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Block User"
                                                        onclick="return confirm('Block this user?')">
                                                    <i class="bi bi-slash-circle"></i>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <form method="POST" action="/admin/users/<?= $user['id'] ?>/status" class="d-inline">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="action" value="unblock">
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Unblock User">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            </form>
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
    <?php if (!empty($users)): ?>
        <div class="card-footer bg-light border-0 text-muted py-3 px-4">
            <div class="d-flex align-items-center justify-content-between">
                <span>Showing <strong><?= count($users) ?></strong> users</span>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.bg-success-subtle { background-color: rgba(16, 185, 129, 0.12) !important; }
.bg-warning-subtle { background-color: rgba(245, 158, 11, 0.12) !important; }
.bg-danger-subtle { background-color: rgba(239, 68, 68, 0.12) !important; }
.bg-primary-subtle { background-color: rgba(91, 108, 242, 0.12) !important; }
.bg-secondary-subtle { background-color: rgba(108, 117, 125, 0.12) !important; }
</style>
        </div>
    <?php endif; ?>
</div>
