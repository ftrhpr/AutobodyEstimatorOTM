<div class="container py-3 py-md-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <a href="/dashboard" class="btn btn-light btn-icon me-3 d-md-none">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-0 fw-bold"><?= __('report.my_reports') ?></h4>
                <p class="text-muted small mb-0 d-none d-md-block"><?= Lang::getLocale() === 'ka' ? 'თქვენი შეფასებების სია' : 'View your assessment requests' ?></p>
            </div>
        </div>
        <a href="/reports/new" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            <span class="d-none d-sm-inline ms-1"><?= __('report.new_report') ?></span>
        </a>
    </div>

    <?php if (empty($reports)): ?>
        <!-- Empty State -->
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="empty-state-icon mb-4">
                    <i class="bi bi-file-earmark-text text-primary" style="font-size: 4rem; opacity: 0.5;"></i>
                </div>
                <h5 class="fw-bold"><?= __('report.no_reports') ?></h5>
                <p class="text-muted mb-4"><?= Lang::getLocale() === 'ka' ? 'გაგზავნეთ პირველი მოთხოვნა შეფასების მისაღებად' : 'Submit your first request to get an assessment' ?></p>
                <a href="/reports/new" class="btn btn-primary btn-lg px-4">
                    <i class="bi bi-plus-lg me-2"></i><?= __('report.submit_report') ?>
                </a>
            </div>
        </div>
    <?php else: ?>
        <?php
        $statusCounts = [];
        foreach ($reports as $r) {
            $statusCounts[$r['status']] = ($statusCounts[$r['status']] ?? 0) + 1;
        }
        ?>
        
        <!-- Filter Pills (Mobile Scrollable) -->
        <div class="filter-pills-container mb-4">
            <div class="filter-pills">
                <button class="filter-pill active" data-filter="all">
                    <?= Lang::getLocale() === 'ka' ? 'ყველა' : 'All' ?>
                    <span class="badge"><?= count($reports) ?></span>
                </button>
                <?php foreach ($statuses as $statusKey => $statusLabel): ?>
                    <?php if (isset($statusCounts[$statusKey])): ?>
                        <button class="filter-pill" data-filter="<?= $statusKey ?>">
                            <?= $statusLabel ?>
                            <span class="badge"><?= $statusCounts[$statusKey] ?></span>
                        </button>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Mobile Card View -->
        <div class="d-md-none">
            <?php foreach ($reports as $report): ?>
                <a href="/reports/<?= $report['id'] ?>" class="report-card d-block mb-3 text-decoration-none" data-status="<?= $report['status'] ?>">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold text-dark"><?= e($report['ticket_number']) ?></span>
                                    <?php if ($report['urgency'] === 'urgent'): ?>
                                        <span class="badge bg-danger ms-2">
                                            <i class="bi bi-lightning-charge-fill"></i>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <span class="badge <?= DamageReport::getStatusBadgeClass($report['status']) ?>">
                                    <?= __('report.status_' . $report['status']) ?>
                                </span>
                            </div>
                            
                            <p class="text-muted small mb-2">
                                <?= e($report['year']) ?> <?= e($report['make']) ?> <?= e($report['model']) ?>
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small">
                                    <i class="bi bi-geo-alt me-1"></i><?= __('report.location_' . $report['damage_location']) ?>
                                </span>
                                <?php if (isset($report['assessment'])): ?>
                                    <span class="fw-bold text-success">
                                        <?= formatMoney($report['assessment']['total_cost']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted small"><?= formatDate($report['created_at'], 'd/m/Y') ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Desktop Table View -->
        <div class="card border-0 shadow-sm d-none d-md-block">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th><?= __('report.ticket_number') ?></th>
                            <th><?= Lang::getLocale() === 'ka' ? 'მანქანა' : 'Vehicle' ?></th>
                            <th><?= __('report.damage_location') ?></th>
                            <th><?= __('status') ?></th>
                            <th><?= __('date') ?></th>
                            <th><?= Lang::getLocale() === 'ka' ? 'შეფასება' : 'Assessment' ?></th>
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
                                            <i class="bi bi-lightning-charge-fill"></i>
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

<style>
.filter-pills-container {
    overflow-x: auto;
    margin: 0 -1rem;
    padding: 0 1rem;
    -webkit-overflow-scrolling: touch;
}
.filter-pills {
    display: flex;
    gap: 0.5rem;
    padding-bottom: 0.5rem;
}
.filter-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    border: 1px solid var(--bs-gray-300);
    background: white;
    font-size: 0.875rem;
    white-space: nowrap;
    transition: all 0.2s;
    cursor: pointer;
}
.filter-pill:hover {
    border-color: var(--bs-primary, #6366f1);
}
.filter-pill.active {
    background: var(--bs-primary, #6366f1);
    border-color: var(--bs-primary, #6366f1);
    color: white;
}
.filter-pill .badge {
    background: rgba(0,0,0,0.1);
    color: inherit;
    font-weight: 500;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 50px;
}
.filter-pill.active .badge {
    background: rgba(255,255,255,0.2);
}
.report-card:active .card {
    transform: scale(0.98);
}
</style>

<script>
// Filter functionality
document.querySelectorAll('.filter-pill').forEach(pill => {
    pill.addEventListener('click', function() {
        // Update active state
        document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('active'));
        this.classList.add('active');
        
        const filter = this.dataset.filter;
        
        // Filter mobile cards
        document.querySelectorAll('.report-card').forEach(card => {
            if (filter === 'all' || card.dataset.status === filter) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
        
        // Filter desktop table rows
        document.querySelectorAll('tbody tr').forEach(row => {
            if (filter === 'all' || row.dataset.status === filter) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
</script>
