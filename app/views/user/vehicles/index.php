<div class="container py-3 py-md-4">
    <!-- Page Header with gradient -->
    <div class="dashboard-header mb-4">
        <div class="d-flex justify-content-between align-items-center position-relative" style="z-index: 1;">
            <div class="d-flex align-items-center">
                <a href="/dashboard" class="btn btn-light btn-icon me-3 d-md-none">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h4 class="mb-0 fw-bold text-white"><?= __('vehicle.my_vehicles') ?></h4>
                    <p class="opacity-75 small mb-0"><?= Lang::getLocale() === 'ka' ? 'მართეთ თქვენი მანქანები' : 'Manage your vehicles' ?></p>
                </div>
            </div>
            <a href="/vehicles/add" class="btn btn-light shadow-sm">
                <i class="bi bi-plus-lg"></i>
                <span class="d-none d-sm-inline ms-1"><?= __('vehicle.add_vehicle') ?></span>
            </a>
        </div>
    </div>

    <?php if (empty($vehicles)): ?>
        <!-- Empty State -->
        <div class="card border-0 shadow-sm">
            <div class="card-body py-5">
                <div class="empty-state">
                    <div class="icon-box icon-box-lg bg-primary-subtle mx-auto mb-4">
                        <i class="bi bi-car-front fs-1 text-primary"></i>
                    </div>
                    <h5 class="fw-bold mb-2"><?= __('vehicle.no_vehicles') ?></h5>
                    <p class="text-muted mb-4"><?= Lang::getLocale() === 'ka' ? 'დაამატეთ მანქანა შეფასების მისაღებად' : 'Add a vehicle to start getting damage assessments' ?></p>
                    <a href="/vehicles/add" class="btn btn-primary btn-lg px-4">
                        <i class="bi bi-plus-lg me-2"></i><?= __('vehicle.add_vehicle') ?>
                    </a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Vehicle Count Summary -->
        <div class="d-flex align-items-center gap-2 mb-3">
            <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
                <i class="bi bi-car-front-fill me-1"></i>
                <?= count($vehicles) ?> <?= Lang::getLocale() === 'ka' ? 'მანქანა' : 'vehicle' ?><?= count($vehicles) > 1 ? 's' : '' ?>
            </span>
        </div>
        
        <!-- Vehicle Cards -->
        <div class="row g-3 stagger-in">
            <?php foreach ($vehicles as $index => $vehicle): ?>
                <div class="col-12 col-md-6 col-lg-4" style="animation-delay: <?= $index * 0.05 ?>s;">
                    <div class="card h-100 border-0 shadow-sm vehicle-card-item">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="icon-box icon-box-sm" style="background: linear-gradient(135deg, var(--bs-primary), var(--bs-indigo));">
                                    <i class="bi bi-car-front-fill text-white"></i>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light rounded-circle" style="width: 32px; height: 32px; padding: 0;" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                        <li class="dropdown-header small text-muted"><?= Lang::getLocale() === 'ka' ? 'მოქმედებები' : 'Actions' ?></li>
                                        <li>
                                            <a class="dropdown-item py-2" href="/vehicles/edit/<?= $vehicle['id'] ?>">
                                                <i class="bi bi-pencil me-2 text-primary"></i><?= __('edit') ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item py-2" href="/reports/new?vehicle=<?= $vehicle['id'] ?>">
                                                <i class="bi bi-file-earmark-plus me-2 text-success"></i><?= Lang::getLocale() === 'ka' ? 'ახალი შეფასება' : 'New Assessment' ?>
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider my-2"></li>
                                        <li>
                                            <form method="POST" action="/vehicles/delete/<?= $vehicle['id'] ?>" class="d-inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="dropdown-item py-2 text-danger"
                                                        data-confirm="<?= __('vehicle.confirm_delete') ?>">
                                                    <i class="bi bi-trash me-2"></i><?= __('delete') ?>
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            <h5 class="card-title fw-bold mb-1">
                                <?= e($vehicle['make']) ?> <?= e($vehicle['model']) ?>
                            </h5>
                            <span class="badge bg-light text-dark mb-3"><?= e($vehicle['year']) ?></span>
                            
                            <div class="vehicle-details">
                                <?php if ($vehicle['plate_number']): ?>
                                    <div class="detail-item">
                                        <span class="detail-label">
                                            <i class="bi bi-card-text me-1"></i><?= __('vehicle.plate_number') ?>
                                        </span>
                                        <span class="detail-value fw-semibold font-monospace"><?= e($vehicle['plate_number']) ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if ($vehicle['vin']): ?>
                                    <div class="detail-item">
                                        <span class="detail-label">
                                            <i class="bi bi-upc me-1"></i><?= __('vehicle.vin') ?>
                                        </span>
                                        <span class="detail-value font-monospace text-truncate" style="max-width: 120px; font-size: 0.8rem;"><?= e($vehicle['vin']) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top pt-3">
                            <a href="/reports/new?vehicle=<?= $vehicle['id'] ?>" class="btn btn-primary w-100">
                                <i class="bi bi-file-earmark-plus me-2"></i><?= Lang::getLocale() === 'ka' ? 'ახალი შეფასება' : 'New Assessment' ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.vehicle-card-item {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.vehicle-card-item:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(91, 108, 242, 0.15) !important;
}
.vehicle-details {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    padding: 0.75rem;
    background: var(--bs-light);
    border-radius: 0.5rem;
}
.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.85rem;
}
.detail-label {
    color: var(--bs-gray-600);
    font-size: 0.8rem;
}
.icon-box-lg {
    width: 80px;
    height: 80px;
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
