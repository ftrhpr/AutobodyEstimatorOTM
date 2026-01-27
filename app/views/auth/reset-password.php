<h4 class="mb-3 text-center fw-bold"><?= __('auth.reset_password') ?></h4>

<p class="text-center text-muted mb-4 small">
    <?= Lang::getLocale() === 'ka' ? 'შეიყვანეთ ახალი პაროლი' : 'Enter your new password below' ?>
</p>

<form method="POST" action="/reset-password">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label for="password" class="form-label small text-muted"><?= __('auth.new_password') ?></label>
        <div class="input-group input-group-lg">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
            <input type="password" 
                   class="form-control border-start-0 border-end-0 ps-0 <?= hasError('password') ? 'is-invalid' : '' ?>"
                   id="password" name="password" 
                   placeholder="<?= Lang::getLocale() === 'ka' ? 'ახალი პაროლი' : 'New password' ?>"
                   minlength="8" required>
            <button class="btn btn-light border border-start-0 toggle-password" type="button" data-target="password">
                <i class="bi bi-eye text-muted"></i>
            </button>
        </div>
        <div class="form-text small"><?= Lang::getLocale() === 'ka' ? 'მინ. 8 სიმბოლო' : 'Min. 8 characters' ?></div>
        <?php if ($error = error('password')): ?>
            <div class="invalid-feedback d-block"><?= e($error) ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-4">
        <label for="password_confirmation" class="form-label small text-muted"><?= __('auth.confirm_password') ?></label>
        <div class="input-group input-group-lg">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock-fill text-muted"></i></span>
            <input type="password" 
                   class="form-control border-start-0 border-end-0 ps-0"
                   id="password_confirmation" name="password_confirmation" 
                   placeholder="<?= Lang::getLocale() === 'ka' ? 'დაადასტურეთ პაროლი' : 'Confirm password' ?>"
                   minlength="8" required>
            <button class="btn btn-light border border-start-0 toggle-password" type="button" data-target="password_confirmation">
                <i class="bi bi-eye text-muted"></i>
            </button>
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-100 py-3">
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
