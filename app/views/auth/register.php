<div class="text-center mb-4">
    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 mb-3" style="width: 64px; height: 64px;">
        <i class="bi bi-person-plus-fill text-primary fs-2"></i>
    </div>
    <h4 class="fw-bold mb-1"><?= __('auth.register') ?></h4>
    <p class="text-muted small mb-0"><?= Lang::getLocale() === 'ka' ? 'შექმენით ახალი ანგარიში' : 'Create your new account' ?></p>
</div>

<form method="POST" action="/register" autocomplete="off">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label for="name" class="form-label"><?= __('auth.name') ?></label>
        <div class="input-group input-group-lg">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
            <input type="text" class="form-control border-start-0 <?= hasError('name') ? 'is-invalid' : '' ?>"
                   id="name" name="name" value="<?= old('name') ?>" 
                   placeholder="<?= Lang::getLocale() === 'ka' ? 'მაგ: გიორგი გიორგაძე' : 'e.g. John Smith' ?>"
                   autocomplete="name" required>
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
        <div class="form-text">
            <i class="bi bi-info-circle me-1"></i>
            <?= Lang::getLocale() === 'ka' ? 'SMS-ით მიიღებთ დადასტურების კოდს' : 'You will receive SMS verification code' ?>
        </div>
        <?php if ($error = error('phone')): ?>
            <div class="invalid-feedback d-block"><?= e($error) ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">
            <?= __('auth.email') ?>
            <span class="text-muted fw-normal small">(<?= Lang::getLocale() === 'ka' ? 'არასავალდებულო' : 'optional' ?>)</span>
        </label>
        <div class="input-group input-group-lg">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
            <input type="email" class="form-control border-start-0 <?= hasError('email') ? 'is-invalid' : '' ?>"
                   id="email" name="email" value="<?= old('email') ?>"
                   placeholder="email@example.com" inputmode="email" autocomplete="email">
        </div>
        <?php if ($error = error('email')): ?>
            <div class="invalid-feedback d-block"><?= e($error) ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label"><?= __('auth.password') ?></label>
        <div class="input-group input-group-lg">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
            <input type="password" class="form-control border-start-0 border-end-0 <?= hasError('password') ? 'is-invalid' : '' ?>"
                   id="password" name="password" minlength="8" autocomplete="new-password" required>
            <button class="btn btn-light border border-start-0 toggle-password" type="button" data-target="password">
                <i class="bi bi-eye text-muted"></i>
            </button>
        </div>
        <div class="form-text"><?= Lang::getLocale() === 'ka' ? 'მინიმუმ 8 სიმბოლო' : 'Minimum 8 characters' ?></div>
        <?php if ($error = error('password')): ?>
            <div class="invalid-feedback d-block"><?= e($error) ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-4">
        <label for="password_confirmation" class="form-label"><?= __('auth.confirm_password') ?></label>
        <div class="input-group input-group-lg">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock-fill text-muted"></i></span>
            <input type="password" class="form-control border-start-0 border-end-0"
                   id="password_confirmation" name="password_confirmation" 
                   minlength="8" autocomplete="new-password" required>
            <button class="btn btn-light border border-start-0 toggle-password" type="button" data-target="password_confirmation">
                <i class="bi bi-eye text-muted"></i>
            </button>
        </div>
    </div>

    <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
        <i class="bi bi-person-plus me-2"></i><?= __('auth.register') ?>
    </button>
</form>

<div class="divider"><span><?= __('or') ?></span></div>

<p class="text-center mb-0">
    <?= Lang::getLocale() === 'ka' ? 'გაქვთ ანგარიში?' : 'Already have an account?' ?>
    <a href="/login" class="text-decoration-none fw-bold text-primary"><?= __('auth.login') ?></a>
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
