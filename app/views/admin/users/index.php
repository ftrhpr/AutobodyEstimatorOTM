<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Search</label>
                <input type="text" class="form-control" name="search"
                       value="<?= e($filters['search'] ?? '') ?>"
                       placeholder="Name, phone, or email...">
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select class="form-select" name="status">
                    <option value="">All Statuses</option>
                    <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="pending" <?= ($filters['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="blocked" <?= ($filters['status'] ?? '') === 'blocked' ? 'selected' : '' ?>>Blocked</option>
                </select>
            </div>
            <div class="col-md-5 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
                <a href="/admin/users" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Vehicles</th>
                        <th>Reports</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                No users found
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td>
                                    <strong><?= e($user['name']) ?></strong>
                                </td>
                                <td>
                                    <a href="tel:<?= e($user['phone']) ?>"><?= e($user['phone']) ?></a>
                                </td>
                                <td>
                                    <?= $user['email'] ? e($user['email']) : '<span class="text-muted">-</span>' ?>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = match($user['status']) {
                                        'active' => 'bg-success',
                                        'pending' => 'bg-warning',
                                        'blocked' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                    ?>
                                    <span class="badge <?= $statusClass ?>">
                                        <?= ucfirst($user['status']) ?>
                                    </span>
                                </td>
                                <td><?= $user['vehicle_count'] ?></td>
                                <td><?= $user['report_count'] ?></td>
                                <td>
                                    <small><?= formatDate($user['created_at'], 'd/m/Y') ?></small>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="/admin/users/<?= $user['id'] ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <?php if ($user['status'] !== 'blocked'): ?>
                                            <form method="POST" action="/admin/users/<?= $user['id'] ?>/status" class="d-inline">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="action" value="block">
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Block this user?')">
                                                    <i class="bi bi-slash-circle"></i>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <form method="POST" action="/admin/users/<?= $user['id'] ?>/status" class="d-inline">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="action" value="unblock">
                                                <button type="submit" class="btn btn-sm btn-outline-success">
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
        <div class="card-footer text-muted">
            Showing <?= count($users) ?> users
        </div>
    <?php endif; ?>
</div>
