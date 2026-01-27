<h4 class="mb-3 text-center fw-bold"><?= __('auth.verify_otp') ?></h4>

<p class="text-center text-muted mb-4">
    <?= Lang::getLocale() === 'ka' ? 'კოდი გაიგზავნა ნომერზე' : 'Code sent to' ?><br>
    <strong class="text-dark"><?= e($phone) ?></strong>
</p>

<form method="POST" action="/verify-otp" id="otpForm">
    <?= csrf_field() ?>

    <div class="mb-4">
        <div class="otp-inputs">
            <input type="text" class="otp-digit" maxlength="1" pattern="[0-9]" inputmode="numeric" autofocus>
            <input type="text" class="otp-digit" maxlength="1" pattern="[0-9]" inputmode="numeric">
            <input type="text" class="otp-digit" maxlength="1" pattern="[0-9]" inputmode="numeric">
            <input type="text" class="otp-digit" maxlength="1" pattern="[0-9]" inputmode="numeric">
            <input type="text" class="otp-digit" maxlength="1" pattern="[0-9]" inputmode="numeric">
            <input type="text" class="otp-digit" maxlength="1" pattern="[0-9]" inputmode="numeric">
        </div>
        <input type="hidden" name="otp" id="otpValue">
    </div>

    <button type="submit" class="btn btn-primary w-100" id="verifyBtn">
        <i class="bi bi-check-circle me-2"></i><?= Lang::getLocale() === 'ka' ? 'დადასტურება' : 'Verify Code' ?>
    </button>
</form>

<div class="text-center mt-4">
    <p class="text-muted mb-2 small"><?= Lang::getLocale() === 'ka' ? 'არ მიიღეთ კოდი?' : "Didn't receive the code?" ?></p>
    <button type="button" class="btn btn-link p-0 fw-semibold" id="resendBtn">
        <i class="bi bi-arrow-clockwise me-1"></i><?= __('auth.resend_otp') ?>
    </button>
    <p class="text-muted small mt-2" id="resendTimer" style="display: none;">
        <?= Lang::getLocale() === 'ka' ? 'დაელოდეთ' : 'Resend available in' ?> <span id="countdown" class="fw-bold">60</span><?= Lang::getLocale() === 'ka' ? ' წმ' : 's' ?>
    </p>
</div>

<style>
.otp-inputs {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
}
.otp-digit {
    width: 48px;
    height: 56px;
    text-align: center;
    font-size: 1.5rem;
    font-weight: 700;
    border: 2px solid #e5e7eb;
    border-radius: 0.75rem;
    transition: all 0.2s;
    background: #fff;
}
.otp-digit:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
    outline: none;
}
.otp-digit.filled {
    border-color: #10b981;
    background: #f0fdf4;
}
@media (min-width: 400px) {
    .otp-digit {
        width: 52px;
        height: 60px;
    }
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

        // Add visual feedback
        if (value) {
            digit.classList.add('filled');
        } else {
            digit.classList.remove('filled');
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
                digits[i].classList.add('filled');
            }
        });

        updateOtpValue();

        if (numbers.length === 6) {
            form.submit();
        }
    });
    
    // Focus handling
    digit.addEventListener('focus', () => {
        digit.select();
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
    resendBtn.innerHTML = '<span class="loading-spinner me-2"></span><?= Lang::getLocale() === "ka" ? "იგზავნება..." : "Sending..." ?>';

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
            // Clear previous inputs
            digits.forEach(d => {
                d.value = '';
                d.classList.remove('filled');
            });
            digits[0].focus();
        } else {
            alert(data.error || '<?= Lang::getLocale() === "ka" ? "შეცდომა. სცადეთ თავიდან." : "Failed to resend code" ?>');
        }
    } catch (error) {
        alert('<?= Lang::getLocale() === "ka" ? "შეცდომა. სცადეთ თავიდან." : "An error occurred. Please try again." ?>');
    }

    resendBtn.disabled = false;
    resendBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-1"></i><?= __("auth.resend_otp") ?>';
});

// Start initial cooldown
startCooldown(60);

// Focus first input on load
digits[0].focus();
</script>
