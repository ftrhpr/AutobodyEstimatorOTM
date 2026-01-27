<div class="register-section">
    <div class="register-header text-center mb-4">
        <div class="register-icon mx-auto mb-3">
            <i class="bi bi-person-plus-fill"></i>
        </div>
        <h2 class="fw-bold mb-2"><?= Lang::getLocale() === 'ka' ? 'შექმენით ანგარიში' : 'Create Your Account' ?></h2>
        <p class="text-muted mb-0">
            <?= Lang::getLocale() === 'ka' 
                ? 'დაასრულეთ რეგისტრაცია შეფასების მისაღებად' 
                : 'Complete registration to get your estimate' ?>
        </p>
    </div>

    <!-- Photo Preview -->
    <div class="photos-preview mb-4">
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-success rounded-pill px-3">
                <i class="bi bi-check-circle me-1"></i>
                <?= count($photos ?? []) ?> <?= Lang::getLocale() === 'ka' ? 'ფოტო ატვირთული' : 'photos uploaded' ?>
            </span>
            <a href="/estimate" class="small text-decoration-none">
                <i class="bi bi-pencil me-1"></i><?= Lang::getLocale() === 'ka' ? 'შეცვლა' : 'Edit' ?>
            </a>
        </div>
        <div class="photo-preview-row">
            <?php foreach (array_slice($photos ?? [], 0, 4) as $photo): ?>
                <img src="<?= $photo['path'] ?>" alt="Uploaded photo" class="photo-preview-thumb">
            <?php endforeach; ?>
            <?php if (count($photos ?? []) > 4): ?>
                <div class="photo-preview-more">+<?= count($photos) - 4 ?></div>
            <?php endif; ?>
        </div>
    </div>

    <form method="POST" action="/estimate/register" autocomplete="off">
        <?= csrf_field() ?>

        <!-- Personal Info -->
        <div class="form-section mb-4">
            <h6 class="form-section-title mb-3">
                <i class="bi bi-person me-2"></i><?= Lang::getLocale() === 'ka' ? 'პირადი ინფორმაცია' : 'Personal Information' ?>
            </h6>

            <div class="mb-3">
                <label for="name" class="form-label"><?= __('auth.name') ?></label>
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                    <input type="text" class="form-control border-start-0 <?= hasError('name') ? 'is-invalid' : '' ?>"
                           id="name" name="name" value="<?= old('name') ?>"
                           placeholder="<?= Lang::getLocale() === 'ka' ? 'სახელი გვარი' : 'Full Name' ?>" required>
                </div>
                <?php if ($error = error('name')): ?>
                    <div class="invalid-feedback d-block"><?= e($error) ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label"><?= __('auth.phone') ?></label>
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-phone text-muted"></i></span>
                    <input type="tel" class="form-control border-start-0 <?= hasError('phone') ? 'is-invalid' : '' ?>"
                           id="phone" name="phone" value="<?= old('phone') ?>"
                           placeholder="5XX XXX XXX" inputmode="tel" autocomplete="tel" required>
                </div>
                <?php if ($error = error('phone')): ?>
                    <div class="invalid-feedback d-block"><?= e($error) ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label"><?= __('auth.email') ?> <span class="text-muted small">(<?= Lang::getLocale() === 'ka' ? 'არასავალდებულო' : 'optional' ?>)</span></label>
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                    <input type="email" class="form-control border-start-0 <?= hasError('email') ? 'is-invalid' : '' ?>"
                           id="email" name="email" value="<?= old('email') ?>"
                           placeholder="email@example.com">
                </div>
                <?php if ($error = error('email')): ?>
                    <div class="invalid-feedback d-block"><?= e($error) ?></div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Password -->
        <div class="form-section mb-4">
            <h6 class="form-section-title mb-3">
                <i class="bi bi-shield-lock me-2"></i><?= Lang::getLocale() === 'ka' ? 'პაროლი' : 'Password' ?>
            </h6>

            <div class="mb-3">
                <label for="password" class="form-label"><?= __('auth.password') ?></label>
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" class="form-control border-start-0 border-end-0 <?= hasError('password') ? 'is-invalid' : '' ?>"
                           id="password" name="password" autocomplete="new-password" required>
                    <button class="btn btn-light border border-start-0 toggle-password" type="button" data-target="password">
                        <i class="bi bi-eye text-muted"></i>
                    </button>
                </div>
                <?php if ($error = error('password')): ?>
                    <div class="invalid-feedback d-block"><?= e($error) ?></div>
                <?php endif; ?>
                <div class="password-strength mt-2"></div>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label"><?= __('auth.password_confirmation') ?></label>
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock-fill text-muted"></i></span>
                    <input type="password" class="form-control border-start-0 border-end-0"
                           id="password_confirmation" name="password_confirmation" autocomplete="new-password" required>
                    <button class="btn btn-light border border-start-0 toggle-password" type="button" data-target="password_confirmation">
                        <i class="bi bi-eye text-muted"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Vehicle Info (Optional) -->
        <div class="form-section mb-4">
            <h6 class="form-section-title mb-3">
                <i class="bi bi-car-front me-2"></i><?= Lang::getLocale() === 'ka' ? 'მანქანის ინფორმაცია' : 'Vehicle Information' ?>
                <span class="text-muted small">(<?= Lang::getLocale() === 'ka' ? 'არასავალდებულო' : 'optional' ?>)</span>
            </h6>

            <div class="row g-3">
                <div class="col-6">
                    <label for="vehicle_make" class="form-label"><?= Lang::getLocale() === 'ka' ? 'მწარმოებელი' : 'Make' ?></label>
                    <input type="text" class="form-control" id="vehicle_make" name="vehicle_make" 
                           placeholder="<?= Lang::getLocale() === 'ka' ? 'მაგ. Toyota' : 'e.g. Toyota' ?>">
                </div>
                <div class="col-6">
                    <label for="vehicle_model" class="form-label"><?= Lang::getLocale() === 'ka' ? 'მოდელი' : 'Model' ?></label>
                    <input type="text" class="form-control" id="vehicle_model" name="vehicle_model"
                           placeholder="<?= Lang::getLocale() === 'ka' ? 'მაგ. Camry' : 'e.g. Camry' ?>">
                </div>
                <div class="col-12">
                    <label for="vehicle_year" class="form-label"><?= Lang::getLocale() === 'ka' ? 'წელი' : 'Year' ?></label>
                    <input type="text" class="form-control" id="vehicle_year" name="vehicle_year"
                           placeholder="<?= Lang::getLocale() === 'ka' ? 'მაგ. 2020' : 'e.g. 2020' ?>">
                </div>
            </div>
        </div>

        <!-- Damage Description -->
        <div class="form-section mb-4">
            <h6 class="form-section-title mb-3">
                <i class="bi bi-chat-left-text me-2"></i><?= Lang::getLocale() === 'ka' ? 'დაზიანების აღწერა' : 'Damage Description' ?>
                <span class="text-muted small">(<?= Lang::getLocale() === 'ka' ? 'არასავალდებულო' : 'optional' ?>)</span>
            </h6>
            <textarea class="form-control" id="damage_description" name="damage_description" rows="3"
                      placeholder="<?= Lang::getLocale() === 'ka' ? 'აღწერეთ რა დაზიანდა...' : 'Describe the damage...' ?>"></textarea>
        </div>

        <!-- Terms -->
        <div class="form-check mb-4">
            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
            <label class="form-check-label" for="terms">
                <?= Lang::getLocale() === 'ka' 
                    ? 'ვეთანხმები <a href="#" class="text-primary">წესებსა და პირობებს</a>' 
                    : 'I agree to the <a href="#" class="text-primary">Terms & Conditions</a>' ?>
            </label>
        </div>

        <!-- Submit -->
        <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
            <i class="bi bi-check-circle me-2"></i><?= Lang::getLocale() === 'ka' ? 'რეგისტრაცია და შეფასების მოთხოვნა' : 'Register & Get Estimate' ?>
        </button>
    </form>

    <div class="divider"><span><?= __('or') ?></span></div>

    <p class="text-center mb-0">
        <?= Lang::getLocale() === 'ka' ? 'უკვე გაქვთ ანგარიში?' : 'Already have an account?' ?>
        <a href="/login" class="text-decoration-none fw-bold text-primary"><?= __('auth.login') ?></a>
    </p>
</div>

<script>
// Toggle password visibility
document.querySelectorAll('.toggle-password').forEach(btn => {
    btn.addEventListener('click', function() {
        const target = document.getElementById(this.dataset.target);
        const icon = this.querySelector('i');

        if (target.type === 'password') {
            target.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            target.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    });
});

// Auto-format phone number
document.getElementById('phone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 9) value = value.slice(0, 9);
    if (value.length > 6) {
        value = value.slice(0, 3) + ' ' + value.slice(3, 6) + ' ' + value.slice(6);
    } else if (value.length > 3) {
        value = value.slice(0, 3) + ' ' + value.slice(3);
    }
    e.target.value = value;
});
</script>
