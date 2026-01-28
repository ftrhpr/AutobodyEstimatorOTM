<div class="text-center mb-4">
    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 72px; height: 72px; background: linear-gradient(135deg, var(--primary-100) 0%, var(--primary-200) 100%);">
        <i class="bi bi-person-fill" style="font-size: 2rem; color: var(--primary);"></i>
    </div>
    <h4 class="fw-bold mb-1"><?= __('auth.login') ?></h4>
    <p class="text-muted mb-0"><?= Lang::getLocale() === 'ka' ? 'შედით თქვენს ანგარიშზე' : 'Sign in to your account' ?></p>
</div>

<form method="POST" action="/login" autocomplete="off">
    <?= csrf_field() ?>

    <div class="mb-4">
        <label for="phone" class="form-label"><?= __('auth.phone') ?></label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-phone"></i></span>
            <input type="tel" class="form-control form-control-lg <?= hasError('phone') ? 'is-invalid' : '' ?>"
                   id="phone" name="phone" value="<?= old('phone') ?>"
                   placeholder="5XX XXX XXX" inputmode="tel" autocomplete="tel" required>
        </div>
        <?php if ($error = error('phone')): ?>
            <div class="invalid-feedback d-block"><?= e($error) ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-4">
        <label for="password" class="form-label"><?= __('auth.password') ?></label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" class="form-control form-control-lg <?= hasError('password') ? 'is-invalid' : '' ?>"
                   id="password" name="password" autocomplete="current-password" required
                   style="border-right: none;">
            <button class="btn btn-light border toggle-password" type="button" style="border-left: none; border-radius: 0 var(--radius-xl) var(--radius-xl) 0;">
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
        <a href="/forgot-password" class="text-decoration-none fw-semibold" style="color: var(--primary);"><?= __('auth.forgot_password') ?></a>
    </div>

    <button type="submit" class="btn btn-primary btn-lg w-100 mb-4 d-flex align-items-center justify-content-center gap-2">
        <i class="bi bi-box-arrow-in-right"></i>
        <span><?= __('auth.login') ?></span>
    </button>
</form>

<div class="divider"><span><?= __('or') ?></span></div>

<p class="text-center mb-0" style="color: var(--gray-600);">
    <?= Lang::getLocale() === 'ka' ? 'არ გაქვთ ანგარიში?' : "Don't have an account?" ?>
    <a href="/register" class="text-decoration-none fw-bold" style="color: var(--primary);"><?= __('auth.register') ?></a>
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