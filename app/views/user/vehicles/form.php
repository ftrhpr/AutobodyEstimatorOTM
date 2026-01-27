<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="bi bi-truck me-2"></i>
                        <?= $vehicle ? __('vehicle.edit_vehicle') : __('vehicle.add_vehicle') ?>
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= $vehicle ? '/vehicles/edit/' . $vehicle['id'] : '/vehicles/add' ?>">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="make" class="form-label"><?= __('vehicle.make') ?> *</label>
                            <select class="form-select <?= hasError('make') ? 'is-invalid' : '' ?>"
                                    id="make" name="make" required>
                                <option value="">Select manufacturer...</option>
                                <?php foreach ($makes as $make): ?>
                                    <option value="<?= e($make) ?>"
                                        <?= (old('make', $vehicle['make'] ?? '') === $make) ? 'selected' : '' ?>>
                                        <?= e($make) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($error = error('make')): ?>
                                <div class="invalid-feedback"><?= e($error) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="model" class="form-label"><?= __('vehicle.model') ?> *</label>
                            <input type="text" class="form-control <?= hasError('model') ? 'is-invalid' : '' ?>"
                                   id="model" name="model"
                                   value="<?= old('model', $vehicle['model'] ?? '') ?>"
                                   placeholder="e.g., Camry, Civic, 3 Series"
                                   required>
                            <?php if ($error = error('model')): ?>
                                <div class="invalid-feedback"><?= e($error) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="year" class="form-label"><?= __('vehicle.year') ?> *</label>
                            <select class="form-select <?= hasError('year') ? 'is-invalid' : '' ?>"
                                    id="year" name="year" required>
                                <option value="">Select year...</option>
                                <?php foreach ($years as $year): ?>
                                    <option value="<?= $year ?>"
                                        <?= (old('year', $vehicle['year'] ?? '') == $year) ? 'selected' : '' ?>>
                                        <?= $year ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($error = error('year')): ?>
                                <div class="invalid-feedback"><?= e($error) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="plate_number" class="form-label">
                                <?= __('vehicle.plate_number') ?>
                                <small class="text-muted">(optional)</small>
                            </label>
                            <input type="text" class="form-control <?= hasError('plate_number') ? 'is-invalid' : '' ?>"
                                   id="plate_number" name="plate_number"
                                   value="<?= old('plate_number', $vehicle['plate_number'] ?? '') ?>"
                                   placeholder="e.g., AA-123-BB">
                            <?php if ($error = error('plate_number')): ?>
                                <div class="invalid-feedback"><?= e($error) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-4">
                            <label for="vin" class="form-label">
                                <?= __('vehicle.vin') ?>
                                <small class="text-muted">(optional)</small>
                            </label>
                            <input type="text" class="form-control <?= hasError('vin') ? 'is-invalid' : '' ?>"
                                   id="vin" name="vin"
                                   value="<?= old('vin', $vehicle['vin'] ?? '') ?>"
                                   maxlength="17"
                                   placeholder="17-character VIN">
                            <div class="form-text">Vehicle Identification Number for precise identification</div>
                            <?php if ($error = error('vin')): ?>
                                <div class="invalid-feedback"><?= e($error) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="bi bi-check-lg me-2"></i><?= __('save') ?>
                            </button>
                            <a href="/vehicles" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg me-2"></i><?= __('cancel') ?>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
