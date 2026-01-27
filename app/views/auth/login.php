<div class="text-center mb-4">
    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 mb-3" style="width: 64px; height: 64px;">
        <i class="bi bi-person-fill text-primary fs-2"></i>
    </div>
    <h4 class="fw-bold mb-1"><?= __('auth.login') ?></h4>
    <p class="text-muted small mb-0"><?= Lang::getLocale() === 'ka' ? 'შედით თქვენს ანგარიშზე' : 'Sign in to your account' ?></p>
</div>

<form method="POST" action="/login" autocomplete="off">
    <?= csrf_field() ?>

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
        <label for="password" class="form-label"><?= __('auth.password') ?></label>
        <div class="input-group input-group-lg">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
            <input type="password" class="form-control border-start-0 border-end-0 <?= hasError('password') ? 'is-invalid' : '' ?>"
                   id="password" name="password" autocomplete="current-password" required>
            <button class="btn btn-light border border-start-0 toggle-password" type="button">
                <i class="bi bi-eye text-muted"></i>
            </button>
        </div>
        <?php if ($error = error('password')): ?>
            <div class="invalid-feedback d-block"><?= e($error) ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember">
            <label class="form-check-label" for="remember"><?= __('auth.remember_me') ?></label>
        </div>
        <a href="/forgot-password" class="text-decoration-none small text-primary fw-medium"><?= __('auth.forgot_password') ?></a>
    </div>

    <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
        <i class="bi bi-box-arrow-in-right me-2"></i><?= __('auth.login') ?>
    </button>
</form>

<div class="divider"><span><?= __('or') ?></span></div>

<p class="text-center mb-0">
    <?= Lang::getLocale() === 'ka' ? 'არ გაქვთ ანგარიში?' : "Don't have an account?" ?>
    <a href="/register" class="text-decoration-none fw-bold text-primary"><?= __('auth.register') ?></a>
</p>

<script>
document.querySelector('.toggle-password').addEventListener('click', function() {
    const input = document.getElementById('password');
    const icon = this.querySelector('i');

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
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