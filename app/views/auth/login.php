<h4 class="mb-4 text-center"><?= __('auth.login') ?></h4>

<form method="POST" action="/login">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label for="phone" class="form-label"><?= __('auth.phone') ?></label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-phone"></i></span>
            <input type="tel" class="form-control <?= hasError('phone') ? 'is-invalid' : '' ?>"
                   id="phone" name="phone" value="<?= old('phone') ?>"
                   placeholder="5XX XXX XXX" required>
        </div>
        <?php if ($error = error('phone')): ?>
            <div class="invalid-feedback d-block"><?= e($error) ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label"><?= __('auth.password') ?></label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" class="form-control <?= hasError('password') ? 'is-invalid' : '' ?>"
                   id="password" name="password" required>
            <button class="btn btn-outline-secondary toggle-password" type="button">
                <i class="bi bi-eye"></i>
            </button>
        </div>
        <?php if ($error = error('password')): ?>
            <div class="invalid-feedback d-block"><?= e($error) ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember">
            <label class="form-check-label" for="remember"><?= __('auth.remember_me') ?></label>
        </div>
        <a href="/forgot-password" class="text-decoration-none"><?= __('auth.forgot_password') ?></a>
    </div>

    <button type="submit" class="btn btn-primary w-100 py-2">
        <i class="bi bi-box-arrow-in-right me-2"></i><?= __('auth.login') ?>
    </button>
</form>

<hr class="my-4">

<p class="text-center mb-0">
    Don't have an account?
    <a href="/register" class="text-decoration-none fw-bold"><?= __('auth.register') ?></a>
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
</script>
