<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-truck me-2"></i><?= __('vehicle.my_vehicles') ?></h2>
        <a href="/vehicles/add" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i><?= __('vehicle.add_vehicle') ?>
        </a>
    </div>

    <?php if (empty($vehicles)): ?>
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-truck display-1 text-muted"></i>
                <h4 class="mt-4"><?= __('vehicle.no_vehicles') ?></h4>
                <p class="text-muted">Add your first vehicle to start submitting damage reports.</p>
                <a href="/vehicles/add" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-lg me-2"></i><?= __('vehicle.add_vehicle') ?>
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($vehicles as $vehicle): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="card-title mb-1">
                                        <?= e($vehicle['make']) ?> <?= e($vehicle['model']) ?>
                                    </h5>
                                    <p class="text-muted mb-2"><?= e($vehicle['year']) ?></p>
                                </div>
                                <span class="badge bg-primary">
                                    <i class="bi bi-car-front"></i>
                                </span>
                            </div>

                            <ul class="list-unstyled mb-0">
                                <?php if ($vehicle['plate_number']): ?>
                                    <li>
                                        <i class="bi bi-hash text-muted me-2"></i>
                                        <strong><?= __('vehicle.plate_number') ?>:</strong>
                                        <?= e($vehicle['plate_number']) ?>
                                    </li>
                                <?php endif; ?>
                                <?php if ($vehicle['vin']): ?>
                                    <li>
                                        <i class="bi bi-upc text-muted me-2"></i>
                                        <strong><?= __('vehicle.vin') ?>:</strong>
                                        <?= e($vehicle['vin']) ?>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="d-flex gap-2">
                                <a href="/vehicles/edit/<?= $vehicle['id'] ?>" class="btn btn-sm btn-outline-primary flex-fill">
                                    <i class="bi bi-pencil me-1"></i><?= __('edit') ?>
                                </a>
                                <form method="POST" action="/vehicles/delete/<?= $vehicle['id'] ?>" class="flex-fill">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100"
                                            data-confirm="<?= __('vehicle.confirm_delete') ?>">
                                        <i class="bi bi-trash me-1"></i><?= __('delete') ?>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
