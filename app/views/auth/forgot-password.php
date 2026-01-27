<h4 class="mb-4 text-center"><?= __('auth.forgot_password') ?></h4>

<p class="text-center text-muted mb-4">
    Enter your phone number and we'll send you a verification code to reset your password.
</p>

<form method="POST" action="/forgot-password">
    <?= csrf_field() ?>

    <div class="mb-4">
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

    <button type="submit" class="btn btn-primary w-100 py-2">
        <i class="bi bi-send me-2"></i>Send Verification Code
    </button>
</form>

<hr class="my-4">

<p class="text-center mb-0">
    Remember your password?
    <a href="/login" class="text-decoration-none fw-bold"><?= __('auth.login') ?></a>
</p>
