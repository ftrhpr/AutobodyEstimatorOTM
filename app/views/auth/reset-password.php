<h4 class="mb-4 text-center"><?= __('auth.reset_password') ?></h4>

<p class="text-center text-muted mb-4">
    Enter your new password below.
</p>

<form method="POST" action="/reset-password">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label for="password" class="form-label"><?= __('auth.new_password') ?></label>
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
        <i class="bi bi-check-circle me-2"></i><?= __('auth.reset_password') ?>
    </button>
</form>

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
