<h4 class="mb-4 text-center"><?= __('auth.register') ?></h4>

<form method="POST" action="/register">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label for="name" class="form-label"><?= __('auth.name') ?></label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person"></i></span>
            <input type="text" class="form-control <?= hasError('name') ? 'is-invalid' : '' ?>"
                   id="name" name="name" value="<?= old('name') ?>" required>
        </div>
        <?php if ($error = error('name')): ?>
            <div class="invalid-feedback d-block"><?= e($error) ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="phone" class="form-label"><?= __('auth.phone') ?></label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-phone"></i></span>
            <input type="tel" class="form-control <?= hasError('phone') ? 'is-invalid' : '' ?>"
                   id="phone" name="phone" value="<?= old('phone') ?>"
                   placeholder="5XX XXX XXX" required>
        </div>
        <div class="form-text">Enter your mobile number for SMS verification</div>
        <?php if ($error = error('phone')): ?>
            <div class="invalid-feedback d-block"><?= e($error) ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label"><?= __('auth.email') ?></label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" class="form-control <?= hasError('email') ? 'is-invalid' : '' ?>"
                   id="email" name="email" value="<?= old('email') ?>">
        </div>
        <?php if ($error = error('email')): ?>
            <div class="invalid-feedback d-block"><?= e($error) ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label"><?= __('auth.password') ?></label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" class="form-control <?= hasError('password') ? 'is-invalid' : '' ?>"
                   id="password" name="password" minlength="8" required>
            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password">
                <i class="bi bi-eye"></i>
            </button>
        </div>
        <div class="form-text">Minimum 8 characters</div>
        <?php if ($error = error('password')): ?>
            <div class="invalid-feedback d-block"><?= e($error) ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-4">
        <label for="password_confirmation" class="form-label"><?= __('auth.confirm_password') ?></label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            <input type="password" class="form-control"
                   id="password_confirmation" name="password_confirmation" minlength="8" required>
            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password_confirmation">
                <i class="bi bi-eye"></i>
            </button>
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-100 py-2">
        <i class="bi bi-person-plus me-2"></i><?= __('auth.register') ?>
    </button>
</form>

<hr class="my-4">

<p class="text-center mb-0">
    Already have an account?
    <a href="/login" class="text-decoration-none fw-bold"><?= __('auth.login') ?></a>
</p>

<script>
document.querySelectorAll('.toggle-password').forEach(function(btn) {
    btn.addEventListener('click', function() {
        const targetId = this.getAttribute('data-target');
        const input = document.getElementById(targetId);
        const icon = this.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    });
});
</script>
