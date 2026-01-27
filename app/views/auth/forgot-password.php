<h4 class="mb-3 text-center fw-bold"><?= __('auth.forgot_password') ?></h4>

<p class="text-center text-muted mb-4 small">
    <?= Lang::getLocale() === 'ka' 
        ? 'შეიყვანეთ ნომერი და მიიღეთ დადასტურების კოდი' 
        : 'Enter your phone number to receive a verification code' ?>
</p>

<form method="POST" action="/forgot-password">
    <?= csrf_field() ?>

    <div class="mb-4">
        <label for="phone" class="form-label small text-muted"><?= __('auth.phone') ?></label>
        <div class="input-group input-group-lg">
            <span class="input-group-text bg-light border-end-0">
                <span class="text-muted">+995</span>
            </span>
            <input type="tel" 
                   class="form-control border-start-0 ps-0 <?= hasError('phone') ? 'is-invalid' : '' ?>"
                   id="phone" name="phone" value="<?= old('phone') ?>"
                   placeholder="5XX XXX XXX" 
                   inputmode="tel"
                   autocomplete="tel"
                   required>
        </div>
        <?php if ($error = error('phone')): ?>
            <div class="invalid-feedback d-block"><?= e($error) ?></div>
        <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-primary w-100 py-3 mb-3">
        <i class="bi bi-send me-2"></i><?= Lang::getLocale() === 'ka' ? 'კოდის გაგზავნა' : 'Send Verification Code' ?>
    </button>
</form>

<p class="text-center mb-0 small">
    <?= Lang::getLocale() === 'ka' ? 'გახსოვთ პაროლი?' : 'Remember your password?' ?>
    <a href="/login" class="text-decoration-none fw-bold"><?= __('auth.login') ?></a>
</p>

<script>
// Phone number formatting
const phoneInput = document.getElementById('phone');
phoneInput.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.startsWith('995')) {
        value = value.substring(3);
    }
    if (value.length > 9) value = value.slice(0, 9);
    
    // Format: XXX XXX XXX
    if (value.length > 6) {
        value = value.slice(0, 3) + ' ' + value.slice(3, 6) + ' ' + value.slice(6);
    } else if (value.length > 3) {
        value = value.slice(0, 3) + ' ' + value.slice(3);
    }
    e.target.value = value;
});
</script>
