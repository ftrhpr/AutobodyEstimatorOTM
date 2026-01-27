<h4 class="mb-4 text-center"><?= __('auth.verify_otp') ?></h4>

<p class="text-center text-muted mb-4">
    We've sent a verification code to<br>
    <strong><?= e($phone) ?></strong>
</p>

<form method="POST" action="/verify-otp" id="otpForm">
    <?= csrf_field() ?>

    <div class="mb-4">
        <label for="otp" class="form-label"><?= __('auth.otp_code') ?></label>
        <div class="d-flex gap-2 justify-content-center otp-inputs">
            <input type="text" class="form-control form-control-lg text-center otp-digit"
                   maxlength="1" pattern="[0-9]" inputmode="numeric" autofocus>
            <input type="text" class="form-control form-control-lg text-center otp-digit"
                   maxlength="1" pattern="[0-9]" inputmode="numeric">
            <input type="text" class="form-control form-control-lg text-center otp-digit"
                   maxlength="1" pattern="[0-9]" inputmode="numeric">
            <input type="text" class="form-control form-control-lg text-center otp-digit"
                   maxlength="1" pattern="[0-9]" inputmode="numeric">
            <input type="text" class="form-control form-control-lg text-center otp-digit"
                   maxlength="1" pattern="[0-9]" inputmode="numeric">
            <input type="text" class="form-control form-control-lg text-center otp-digit"
                   maxlength="1" pattern="[0-9]" inputmode="numeric">
        </div>
        <input type="hidden" name="otp" id="otpValue">
    </div>

    <button type="submit" class="btn btn-primary w-100 py-2" id="verifyBtn">
        <i class="bi bi-check-circle me-2"></i>Verify Code
    </button>
</form>

<div class="text-center mt-4">
    <p class="text-muted mb-2">Didn't receive the code?</p>
    <button type="button" class="btn btn-link p-0" id="resendBtn">
        <i class="bi bi-arrow-clockwise me-1"></i><?= __('auth.resend_otp') ?>
    </button>
    <p class="text-muted small mt-2" id="resendTimer" style="display: none;">
        Resend available in <span id="countdown">60</span>s
    </p>
</div>

<style>
.otp-inputs {
    gap: 8px;
}
.otp-digit {
    width: 50px;
    height: 60px;
    font-size: 24px;
    font-weight: bold;
}
.otp-digit:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}
</style>

<script>
const digits = document.querySelectorAll('.otp-digit');
const otpValue = document.getElementById('otpValue');
const form = document.getElementById('otpForm');
const resendBtn = document.getElementById('resendBtn');
const resendTimer = document.getElementById('resendTimer');
const countdown = document.getElementById('countdown');

// Handle OTP input
digits.forEach((digit, index) => {
    digit.addEventListener('input', (e) => {
        const value = e.target.value;

        // Only allow numbers
        if (!/^\d*$/.test(value)) {
            e.target.value = '';
            return;
        }

        // Move to next input
        if (value && index < digits.length - 1) {
            digits[index + 1].focus();
        }

        updateOtpValue();

        // Auto-submit when all digits are filled
        if (index === digits.length - 1 && value) {
            const allFilled = Array.from(digits).every(d => d.value);
            if (allFilled) {
                form.submit();
            }
        }
    });

    digit.addEventListener('keydown', (e) => {
        // Handle backspace
        if (e.key === 'Backspace' && !digit.value && index > 0) {
            digits[index - 1].focus();
        }
    });

    digit.addEventListener('paste', (e) => {
        e.preventDefault();
        const paste = (e.clipboardData || window.clipboardData).getData('text');
        const numbers = paste.replace(/\D/g, '').slice(0, 6);

        numbers.split('').forEach((num, i) => {
            if (digits[i]) {
                digits[i].value = num;
            }
        });

        updateOtpValue();

        if (numbers.length === 6) {
            form.submit();
        }
    });
});

function updateOtpValue() {
    otpValue.value = Array.from(digits).map(d => d.value).join('');
}

// Resend OTP functionality
let resendCooldown = 0;

function startCooldown(seconds) {
    resendCooldown = seconds;
    resendBtn.style.display = 'none';
    resendTimer.style.display = 'block';

    const interval = setInterval(() => {
        resendCooldown--;
        countdown.textContent = resendCooldown;

        if (resendCooldown <= 0) {
            clearInterval(interval);
            resendBtn.style.display = 'inline';
            resendTimer.style.display = 'none';
        }
    }, 1000);
}

resendBtn.addEventListener('click', async () => {
    resendBtn.disabled = true;

    try {
        const response = await fetch('/resend-otp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': '<?= csrf_token() ?>'
            },
            body: '_csrf_token=<?= csrf_token() ?>'
        });

        const data = await response.json();

        if (data.success) {
            startCooldown(60);
            alert(data.message || 'Code sent successfully');
        } else {
            alert(data.error || 'Failed to resend code');
        }
    } catch (error) {
        alert('An error occurred. Please try again.');
    }

    resendBtn.disabled = false;
});

// Start initial cooldown
startCooldown(60);
</script>
