<div class="row g-4">
    <!-- User Info -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-person me-2"></i>User Information</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center"
                         style="width: 80px; height: 80px;">
                        <i class="bi bi-person text-primary" style="font-size: 2rem;"></i>
                    </div>
                </div>

                <h5 class="text-center mb-1"><?= e($user['name']) ?></h5>
                <p class="text-center text-muted mb-3"><?= e($user['phone']) ?></p>

                <hr>

                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="bi bi-envelope text-muted me-2"></i>
                        <strong>Email:</strong>
                        <?= $user['email'] ? e($user['email']) : '<span class="text-muted">Not provided</span>' ?>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-calendar text-muted me-2"></i>
                        <strong>Joined:</strong>
                        <?= formatDate($user['created_at']) ?>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-shield text-muted me-2"></i>
                        <strong>Status:</strong>
                        <?php
                        $statusClass = match($user['status']) {
                            'active' => 'bg-success',
                            'pending' => 'bg-warning',
                            'blocked' => 'bg-danger',
                            default => 'bg-secondary'
                        };
                        ?>
                        <span class="badge <?= $statusClass ?>"><?= ucfirst($user['status']) ?></span>
                    </li>
                </ul>

                <hr>

                <div class="d-grid gap-2">
                    <?php if ($user['status'] !== 'blocked'): ?>
                        <form method="POST" action="/admin/users/<?= $user['id'] ?>/status">
                            <?= csrf_field() ?>
                            <input type="hidden" name="action" value="block">
                            <button type="submit" class="btn btn-danger w-100"
                                    onclick="return confirm('Block this user?')">
                                <i class="bi bi-slash-circle me-2"></i>Block User
                            </button>
                        </form>
                    <?php else: ?>
                        <form method="POST" action="/admin/users/<?= $user['id'] ?>/status">
                            <?= csrf_field() ?>
                            <input type="hidden" name="action" value="unblock">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-circle me-2"></i>Unblock User
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Vehicles -->
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-truck me-2"></i>Vehicles</h6>
                <span class="badge bg-secondary"><?= count($vehicles) ?></span>
            </div>
            <div class="card-body p-0">
                <?php if (empty($vehicles)): ?>
                    <div class="text-center py-3 text-muted">
                        No vehicles registered
                    </div>
                <?php else: ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($vehicles as $vehicle): ?>
                            <li class="list-group-item">
                                <strong><?= e($vehicle['make']) ?> <?= e($vehicle['model']) ?></strong>
                                <br>
                                <small class="text-muted">
                                    <?= e($vehicle['year']) ?>
                                    <?php if ($vehicle['plate_number']): ?>
                                        &bull; <?= e($vehicle['plate_number']) ?>
                                    <?php endif; ?>
                                </small>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Reports -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i>Damage Reports</h5>
                <span class="badge bg-primary"><?= count($reports) ?> reports</span>
            </div>
            <div class="card-body p-0">
                <?php if (empty($reports)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox display-4"></i>
                        <p class="mt-2">No reports submitted</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Ticket</th>
                                    <th>Vehicle</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reports as $report): ?>
                                    <tr>
                                        <td>
                                            <strong><?= e($report['ticket_number']) ?></strong>
                                            <?php if ($report['urgency'] === 'urgent'): ?>
                                                <span class="badge bg-danger">!</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?= e($report['year']) ?> <?= e($report['make']) ?> <?= e($report['model']) ?>
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
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="/admin/users" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Users
    </a>
</div>
