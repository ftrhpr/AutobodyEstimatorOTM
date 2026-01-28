<div class="container py-3 py-md-4">
    <!-- Page Header -->
    <div class="dashboard-header mb-4">
        <div class="d-flex align-items-center position-relative" style="z-index: 1;">
            <a href="/vehicles" class="btn btn-light btn-icon me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-0 fw-bold text-white">
                    <?= $vehicle ? __('vehicle.edit_vehicle') : __('vehicle.add_vehicle') ?>
                </h4>
                <p class="opacity-75 small mb-0">
                    <?= Lang::getLocale() === 'ka' ? 'შეავსეთ მანქანის ინფორმაცია' : 'Fill in your vehicle information' ?>
                </p>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <!-- Progress Indicator -->
            <div class="d-flex align-items-center gap-2 mb-4 text-muted small">
                <span class="badge bg-primary rounded-pill">1</span>
                <span class="fw-medium"><?= Lang::getLocale() === 'ka' ? 'მანქანის დეტალები' : 'Vehicle Details' ?></span>
                <i class="bi bi-chevron-right"></i>
                <span class="badge bg-light text-muted rounded-pill">2</span>
                <span><?= Lang::getLocale() === 'ka' ? 'შეფასება' : 'Assessment' ?></span>
            </div>
            
            <form method="POST" action="<?= $vehicle ? '/vehicles/edit/' . $vehicle['id'] : '/vehicles/add' ?>">
                <?= csrf_field() ?>

                <!-- Make -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body p-4">
                        <label for="make" class="form-label fw-semibold d-flex align-items-center">
                            <span class="icon-box icon-box-sm bg-primary-subtle me-2">
                                <i class="bi bi-building text-primary"></i>
                            </span>
                            <?= __('vehicle.make') ?>
                            <span class="text-danger ms-1">*</span>
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
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body p-4">
                        <label for="model" class="form-label fw-semibold d-flex align-items-center">
                            <span class="icon-box icon-box-sm bg-primary-subtle me-2">
                                <i class="bi bi-car-front text-primary"></i>
                            </span>
                            <?= __('vehicle.model') ?>
                            <span class="text-danger ms-1">*</span>
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
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body p-4">
                        <label for="year" class="form-label fw-semibold d-flex align-items-center">
                            <span class="icon-box icon-box-sm bg-primary-subtle me-2">
                                <i class="bi bi-calendar text-primary"></i>
                            </span>
                            <?= __('vehicle.year') ?>
                            <span class="text-danger ms-1">*</span>
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

                <!-- Optional Fields Group -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light border-0 py-3">
                        <span class="fw-semibold">
                            <i class="bi bi-info-circle text-muted me-2"></i>
                            <?= Lang::getLocale() === 'ka' ? 'დამატებითი ინფორმაცია' : 'Additional Information' ?>
                        </span>
                        <span class="badge bg-light text-muted ms-2"><?= Lang::getLocale() === 'ka' ? 'არასავალდებულო' : 'Optional' ?></span>
                    </div>
                    <div class="card-body p-4">
                        <!-- Plate Number -->
                        <div class="mb-4">
                            <label for="plate_number" class="form-label fw-semibold d-flex align-items-center">
                                <span class="icon-box icon-box-sm bg-warning-subtle me-2">
                                    <i class="bi bi-card-text text-warning"></i>
                                </span>
                                <?= __('vehicle.plate_number') ?>
                            </label>
                            <input type="text" class="form-control form-control-lg <?= hasError('plate_number') ? 'is-invalid' : '' ?>"
                                   id="plate_number" name="plate_number"
                                   value="<?= old('plate_number', $vehicle['plate_number'] ?? '') ?>"
                                   placeholder="<?= Lang::getLocale() === 'ka' ? 'მაგ: AA-123-BB' : 'e.g., AA-123-BB' ?>"
                                   style="text-transform: uppercase;">
                            <?php if ($error = error('plate_number')): ?>
                                <div class="invalid-feedback"><?= e($error) ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- VIN -->
                        <div class="mb-0">
                            <label for="vin" class="form-label fw-semibold d-flex align-items-center">
                                <span class="icon-box icon-box-sm bg-info-subtle me-2">
                                    <i class="bi bi-upc text-info"></i>
                                </span>
                                <?= __('vehicle.vin') ?>
                            </label>
                            <input type="text" class="form-control form-control-lg font-monospace <?= hasError('vin') ? 'is-invalid' : '' ?>"
                                   id="vin" name="vin"
                                   value="<?= old('vin', $vehicle['vin'] ?? '') ?>"
                                   maxlength="17"
                                   placeholder="<?= Lang::getLocale() === 'ka' ? '17-სიმბოლოიანი VIN' : '17-character VIN' ?>"
                                   style="text-transform: uppercase; letter-spacing: 1px;">
                            <div class="form-text small mt-2">
                                <i class="bi bi-lightbulb me-1 text-warning"></i>
                                <?= Lang::getLocale() === 'ka' ? 'VIN კოდი მანქანის ზუსტი იდენტიფიკაციისთვის' : 'VIN helps with precise vehicle identification' ?>
                            </div>
                            <?php if ($error = error('vin')): ?>
                                <div class="invalid-feedback"><?= e($error) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg py-3">
                        <i class="bi bi-check-lg me-2"></i><?= __('save') ?>
                    </button>
                    <a href="/vehicles" class="btn btn-outline-secondary py-2">
                        <i class="bi bi-x-lg me-1"></i><?= __('cancel') ?>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
