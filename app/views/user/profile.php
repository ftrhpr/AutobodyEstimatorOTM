<div class="container py-3 py-md-4">
    <!-- Page Header -->
    <div class="d-flex align-items-center mb-4">
        <a href="/dashboard" class="btn btn-light btn-icon me-3 d-md-none">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="mb-0 fw-bold"><?= __('profile.my_profile') ?></h4>
            <p class="text-muted small mb-0 d-none d-md-block"><?= Lang::getLocale() === 'ka' ? 'მართეთ თქვენი ანგარიში' : 'Manage your account' ?></p>
        </div>
    </div>

    <!-- Profile Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body text-center py-4">
            <div class="profile-avatar mx-auto mb-3">
                <span class="avatar-text"><?= strtoupper(substr($user['name'], 0, 1)) ?></span>
            </div>
            <h5 class="fw-bold mb-1"><?= e($user['name']) ?></h5>
            <p class="text-muted mb-2"><?= e($user['phone']) ?></p>
            <span class="badge bg-success-subtle text-success px-3 py-2">
                <i class="bi bi-check-circle me-1"></i>
                <?= Lang::getLocale() === 'ka' ? 'დადასტურებული' : ucfirst($user['status']) ?>
            </span>
        </div>
    </div>

    <!-- Edit Profile -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-transparent border-0 py-3">
            <h6 class="mb-0 fw-semibold">
                <i class="bi bi-person me-2 text-primary"></i><?= __('profile.edit_profile') ?>
            </h6>
        </div>
        <div class="card-body pt-0">
            <form method="POST" action="/profile">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="name" class="form-label small text-muted"><?= __('auth.name') ?></label>
                    <input type="text" class="form-control form-control-lg <?= hasError('name') ? 'is-invalid' : '' ?>"
                           id="name" name="name"
                           value="<?= old('name', $user['name']) ?>"
                           required>
                    <?php if ($error = error('name')): ?>
                        <div class="invalid-feedback"><?= e($error) ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label small text-muted"><?= __('auth.phone') ?></label>
                    <input type="text" class="form-control form-control-lg bg-light" id="phone"
                           value="<?= e($user['phone']) ?>" disabled>
                    <div class="form-text small"><?= Lang::getLocale() === 'ka' ? 'ნომრის შეცვლა შეუძლებელია' : 'Phone number cannot be changed' ?></div>
                </div>

                <div class="mb-4">
                    <label for="email" class="form-label small text-muted"><?= __('auth.email') ?></label>
                    <input type="email" class="form-control form-control-lg <?= hasError('email') ? 'is-invalid' : '' ?>"
                           id="email" name="email"
                           placeholder="<?= Lang::getLocale() === 'ka' ? 'არასავალდებულო' : 'Optional' ?>"
                           value="<?= old('email', $user['email'] ?? '') ?>">
                    <?php if ($error = error('email')): ?>
                        <div class="invalid-feedback"><?= e($error) ?></div>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2">
                    <i class="bi bi-check-lg me-2"></i><?= __('save') ?>
                </button>
            </form>
        </div>
    </div>

    <!-- Change Password -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-transparent border-0 py-3">
            <h6 class="mb-0 fw-semibold">
                <i class="bi bi-shield-lock me-2 text-primary"></i><?= __('profile.change_password') ?>
            </h6>
        </div>
        <div class="card-body pt-0">
            <form method="POST" action="/profile/password">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="current_password" class="form-label small text-muted">
                        <?= __('profile.current_password') ?>
                    </label>
                    <input type="password" class="form-control form-control-lg"
                           id="current_password" name="current_password" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label small text-muted"><?= __('auth.new_password') ?></label>
                    <input type="password" class="form-control form-control-lg <?= hasError('password') ? 'is-invalid' : '' ?>"
                           id="password" name="password" minlength="8" required>
                    <div class="form-text small"><?= Lang::getLocale() === 'ka' ? 'მინიმუმ 8 სიმბოლო' : 'Minimum 8 characters' ?></div>
                    <?php if ($error = error('password')): ?>
                        <div class="invalid-feedback"><?= e($error) ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label small text-muted">
                        <?= __('auth.confirm_password') ?>
                    </label>
                    <input type="password" class="form-control form-control-lg"
                           id="password_confirmation" name="password_confirmation"
                           minlength="8" required>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2">
                    <i class="bi bi-shield-lock me-2"></i><?= __('profile.change_password') ?>
                </button>
            </form>
        </div>
    </div>

    <!-- Account Info -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent border-0 py-3">
            <h6 class="mb-0 fw-semibold">
                <i class="bi bi-info-circle me-2 text-primary"></i><?= Lang::getLocale() === 'ka' ? 'ანგარიშის ინფორმაცია' : 'Account Information' ?>
            </h6>
        </div>
        <div class="card-body pt-0">
            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <span class="text-muted"><?= Lang::getLocale() === 'ka' ? 'რეგისტრაცია' : 'Member since' ?></span>
                <span class="fw-semibold"><?= formatDate($user['created_at'], 'd/m/Y') ?></span>
            </div>
            <div class="d-flex justify-content-between align-items-center py-2">
                <span class="text-muted"><?= Lang::getLocale() === 'ka' ? 'სტატუსი' : 'Status' ?></span>
                <span class="badge bg-success-subtle text-success"><?= Lang::getLocale() === 'ka' ? 'აქტიური' : ucfirst($user['status']) ?></span>
            </div>
        </div>
    </div>

    <!-- Logout Button (Mobile) -->
    <div class="d-grid d-md-none mb-4">
        <a href="/logout" class="btn btn-outline-danger py-3">
            <i class="bi bi-box-arrow-right me-2"></i><?= __('auth.logout') ?>
        </a>
    </div>
</div>

<style>
.profile-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--bs-primary, #6366f1), #8b5cf6);
    display: flex;
    align-items: center;
    justify-content: center;
}
.avatar-text {
    font-size: 2rem;
    font-weight: 700;
    color: white;
}
.bg-success-subtle {
    background-color: rgba(34, 197, 94, 0.1) !important;
}
</style>
