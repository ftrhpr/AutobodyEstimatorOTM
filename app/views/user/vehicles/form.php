<div class="container py-3 py-md-4">
    <!-- Page Header -->
    <div class="d-flex align-items-center mb-4">
        <a href="/vehicles" class="btn btn-light btn-icon me-3 d-md-none">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="mb-0 fw-bold">
                <?= $vehicle ? __('vehicle.edit_vehicle') : __('vehicle.add_vehicle') ?>
            </h4>
            <p class="text-muted small mb-0 d-none d-md-block">
                <?= Lang::getLocale() === 'ka' ? 'შეავსეთ მანქანის ინფორმაცია' : 'Fill in your vehicle information' ?>
            </p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <form method="POST" action="<?= $vehicle ? '/vehicles/edit/' . $vehicle['id'] : '/vehicles/add' ?>">
                <?= csrf_field() ?>

                <!-- Make -->
                <div class="card mb-3">
                    <div class="card-body">
                        <label for="make" class="form-label fw-semibold">
                            <i class="bi bi-building me-2 text-primary"></i><?= __('vehicle.make') ?>
                        </label>
                        <select class="form-select form-select-lg <?= hasError('make') ? 'is-invalid' : '' ?>"
                                id="make" name="make" required>
                            <option value=""><?= Lang::getLocale() === 'ka' ? 'აირჩიეთ მწარმოებელი...' : 'Select manufacturer...' ?></option>
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
                </div>

                <!-- Model -->
                <div class="card mb-3">
                    <div class="card-body">
                        <label for="model" class="form-label fw-semibold">
                            <i class="bi bi-car-front me-2 text-primary"></i><?= __('vehicle.model') ?>
                        </label>
                        <input type="text" class="form-control form-control-lg <?= hasError('model') ? 'is-invalid' : '' ?>"
                               id="model" name="model"
                               value="<?= old('model', $vehicle['model'] ?? '') ?>"
                               placeholder="<?= Lang::getLocale() === 'ka' ? 'მაგ: Camry, Civic, X5' : 'e.g., Camry, Civic, X5' ?>"
                               required>
                        <?php if ($error = error('model')): ?>
                            <div class="invalid-feedback"><?= e($error) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Year -->
                <div class="card mb-3">
                    <div class="card-body">
                        <label for="year" class="form-label fw-semibold">
                            <i class="bi bi-calendar me-2 text-primary"></i><?= __('vehicle.year') ?>
                        </label>
                        <select class="form-select form-select-lg <?= hasError('year') ? 'is-invalid' : '' ?>"
                                id="year" name="year" required>
                            <option value=""><?= Lang::getLocale() === 'ka' ? 'აირჩიეთ წელი...' : 'Select year...' ?></option>
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
                </div>

                <!-- Plate Number (Optional) -->
                <div class="card mb-3">
                    <div class="card-body">
                        <label for="plate_number" class="form-label fw-semibold">
                            <i class="bi bi-card-text me-2 text-primary"></i><?= __('vehicle.plate_number') ?>
                            <span class="badge bg-light text-muted fw-normal ms-2"><?= Lang::getLocale() === 'ka' ? 'არასავალდებულო' : 'optional' ?></span>
                        </label>
                        <input type="text" class="form-control form-control-lg <?= hasError('plate_number') ? 'is-invalid' : '' ?>"
                               id="plate_number" name="plate_number"
                               value="<?= old('plate_number', $vehicle['plate_number'] ?? '') ?>"
                               placeholder="<?= Lang::getLocale() === 'ka' ? 'მაგ: AA-123-BB' : 'e.g., AA-123-BB' ?>">
                        <?php if ($error = error('plate_number')): ?>
                            <div class="invalid-feedback"><?= e($error) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- VIN (Optional) -->
                <div class="card mb-4">
                    <div class="card-body">
                        <label for="vin" class="form-label fw-semibold">
                            <i class="bi bi-upc me-2 text-primary"></i><?= __('vehicle.vin') ?>
                            <span class="badge bg-light text-muted fw-normal ms-2"><?= Lang::getLocale() === 'ka' ? 'არასავალდებულო' : 'optional' ?></span>
                        </label>
                        <input type="text" class="form-control form-control-lg <?= hasError('vin') ? 'is-invalid' : '' ?>"
                               id="vin" name="vin"
                               value="<?= old('vin', $vehicle['vin'] ?? '') ?>"
                               maxlength="17"
                               placeholder="<?= Lang::getLocale() === 'ka' ? '17-სიმბოლოიანი VIN' : '17-character VIN' ?>"
                               style="font-family: monospace; text-transform: uppercase;">
                        <div class="form-text small">
                            <?= Lang::getLocale() === 'ka' ? 'მანქანის იდენტიფიკაციის ნომერი ზუსტი ინფორმაციისთვის' : 'Vehicle Identification Number for precise identification' ?>
                        </div>
                        <?php if ($error = error('vin')): ?>
                            <div class="invalid-feedback"><?= e($error) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg py-3">
                        <i class="bi bi-check-lg me-2"></i><?= __('save') ?>
                    </button>
                    <a href="/vehicles" class="btn btn-outline-secondary">
                        <?= __('cancel') ?>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
