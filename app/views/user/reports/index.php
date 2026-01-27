<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-file-earmark-text me-2"></i><?= __('report.my_reports') ?></h2>
        <a href="/reports/new" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i><?= __('report.new_report') ?>
        </a>
    </div>

    <?php if (empty($reports)): ?>
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-file-earmark-text display-1 text-muted"></i>
                <h4 class="mt-4"><?= __('report.no_reports') ?></h4>
                <p class="text-muted">Submit your first damage report to get an assessment.</p>
                <a href="/reports/new" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-lg me-2"></i><?= __('report.submit_report') ?>
                </a>
            </div>
        </div>
    <?php else: ?>
        <!-- Filter Tabs -->
        <ul class="nav nav-tabs mb-4" id="reportTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#all">
                    All <span class="badge bg-secondary ms-1"><?= count($reports) ?></span>
                </a>
            </li>
            <?php
            $statusCounts = [];
            foreach ($reports as $r) {
                $statusCounts[$r['status']] = ($statusCounts[$r['status']] ?? 0) + 1;
            }
            ?>
            <?php foreach ($statuses as $statusKey => $statusLabel): ?>
                <?php if (isset($statusCounts[$statusKey])): ?>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#<?= $statusKey ?>">
                            <?= $statusLabel ?>
                            <span class="badge <?= DamageReport::getStatusBadgeClass($statusKey) ?> ms-1">
                                <?= $statusCounts[$statusKey] ?>
                            </span>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>

        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th><?= __('report.ticket_number') ?></th>
                            <th>Vehicle</th>
                            <th><?= __('report.damage_location') ?></th>
                            <th><?= __('status') ?></th>
                            <th><?= __('date') ?></th>
                            <th>Assessment</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reports as $report): ?>
                            <tr data-status="<?= $report['status'] ?>">
                                <td>
                                    <strong><?= e($report['ticket_number']) ?></strong>
                                    <?php if ($report['urgency'] === 'urgent'): ?>
                                        <span class="badge bg-danger ms-1">
                                            <i class="bi bi-exclamation-triangle"></i>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= e($report['year']) ?> <?= e($report['make']) ?> <?= e($report['model']) ?>
                                </td>
                                <td>
                                    <?= __('report.location_' . $report['damage_location']) ?>
                                </td>
                                <td>
                                    <span class="badge <?= DamageReport::getStatusBadgeClass($report['status']) ?>">
                                        <?= __('report.status_' . $report['status']) ?>
                                    </span>
                                </td>
                                <td><?= formatDate($report['created_at']) ?></td>
                                <td>
                                    <?php if (isset($report['assessment'])): ?>
                                        <strong class="text-success">
                                            <?= formatMoney($report['assessment']['total_cost']) ?>
                                        </strong>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="/reports/<?= $report['id'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i><?= __('view') ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
// Filter table by status
document.querySelectorAll('#reportTabs .nav-link').forEach(tab => {
    tab.addEventListener('click', function(e) {
        const status = this.getAttribute('href').replace('#', '');
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            if (status === 'all' || row.dataset.status === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
</script>
