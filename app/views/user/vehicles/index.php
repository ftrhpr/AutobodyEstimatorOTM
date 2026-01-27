<div class="container py-3 py-md-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <a href="/dashboard" class="btn btn-light btn-icon me-3 d-md-none">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-0 fw-bold"><?= __('vehicle.my_vehicles') ?></h4>
                <p class="text-muted small mb-0 d-none d-md-block"><?= Lang::getLocale() === 'ka' ? 'მართეთ თქვენი მანქანები' : 'Manage your vehicles' ?></p>
            </div>
        </div>
        <a href="/vehicles/add" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            <span class="d-none d-sm-inline ms-1"><?= __('vehicle.add_vehicle') ?></span>
        </a>
    </div>

    <?php if (empty($vehicles)): ?>
        <!-- Empty State -->
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="empty-state-icon mb-4">
                    <i class="bi bi-car-front text-primary" style="font-size: 4rem; opacity: 0.5;"></i>
                </div>
                <h5 class="fw-bold"><?= __('vehicle.no_vehicles') ?></h5>
                <p class="text-muted mb-4"><?= Lang::getLocale() === 'ka' ? 'დაამატეთ მანქანა შეფასების მისაღებად' : 'Add a vehicle to start getting damage assessments' ?></p>
                <a href="/vehicles/add" class="btn btn-primary btn-lg px-4">
                    <i class="bi bi-plus-lg me-2"></i><?= __('vehicle.add_vehicle') ?>
                </a>
            </div>
        </div>
    <?php else: ?>
        <!-- Vehicle Cards -->
        <div class="row g-3">
            <?php foreach ($vehicles as $vehicle): ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm vehicle-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="vehicle-icon">
                                    <i class="bi bi-car-front-fill text-primary"></i>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="/vehicles/edit/<?= $vehicle['id'] ?>">
                                                <i class="bi bi-pencil me-2"></i><?= __('edit') ?>
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" action="/vehicles/delete/<?= $vehicle['id'] ?>" class="d-inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="dropdown-item text-danger"
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
                            <p class="text-muted mb-3"><?= e($vehicle['year']) ?></p>
                            
                            <div class="vehicle-details">
                                <?php if ($vehicle['plate_number']): ?>
                                    <div class="detail-item">
                                        <span class="detail-label"><?= __('vehicle.plate_number') ?></span>
                                        <span class="detail-value fw-semibold"><?= e($vehicle['plate_number']) ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if ($vehicle['vin']): ?>
                                    <div class="detail-item">
                                        <span class="detail-label"><?= __('vehicle.vin') ?></span>
                                        <span class="detail-value text-truncate" style="max-width: 120px;"><?= e($vehicle['vin']) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 pt-0">
                            <a href="/reports/new?vehicle=<?= $vehicle['id'] ?>" class="btn btn-outline-primary w-100">
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
.vehicle-card {
    transition: transform 0.2s, box-shadow 0.2s;
}
.vehicle-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}
.vehicle-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(99, 102, 241, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}
.vehicle-details {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}
.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.875rem;
}
.detail-label {
    color: var(--bs-gray-500);
}
</style>
