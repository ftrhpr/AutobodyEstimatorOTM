<div class="container py-3 py-md-4">
    <!-- Profile Header -->
    <div class="profile-header mb-4">
        <div class="profile-header-bg"></div>
        <div class="profile-header-content">
            <a href="/dashboard" class="btn btn-light btn-icon me-3 d-md-none position-absolute" style="top: 1rem; left: 1rem;">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div class="profile-avatar-large mx-auto">
                <span class="avatar-text"><?= strtoupper(substr($user['name'], 0, 1)) ?></span>
            </div>
            <h4 class="fw-bold mb-1 mt-3"><?= e($user['name']) ?></h4>
            <p class="text-muted mb-2"><?= e($user['phone']) ?></p>
            <span class="badge bg-success px-3 py-2">
                <i class="bi bi-patch-check-fill me-1"></i>
                <?= Lang::getLocale() === 'ka' ? 'დადასტურებული' : ucfirst($user['status']) ?>
            </span>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Edit Profile -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-person-fill me-2 text-primary"></i><?= __('profile.edit_profile') ?>
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="/profile">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="name" class="form-label"><?= __('auth.name') ?></label>
                            <input type="text" class="form-control <?= hasError('name') ? 'is-invalid' : '' ?>"
                                   id="name" name="name"
                                   value="<?= old('name', $user['name']) ?>"
                                   required>
                            <?php if ($error = error('name')): ?>
                                <div class="invalid-feedback"><?= e($error) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label"><?= __('auth.phone') ?></label>
                            <input type="text" class="form-control bg-light" id="phone"
                                   value="<?= e($user['phone']) ?>" disabled>
                            <div class="form-text"><?= Lang::getLocale() === 'ka' ? 'ნომრის შეცვლა შეუძლებელია' : 'Phone number cannot be changed' ?></div>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label"><?= __('auth.email') ?></label>
                            <input type="email" class="form-control <?= hasError('email') ? 'is-invalid' : '' ?>"
                                   id="email" name="email"
                                   placeholder="<?= Lang::getLocale() === 'ka' ? 'არასავალდებულო' : 'Optional' ?>"
                                   value="<?= old('email', $user['email'] ?? '') ?>">
                            <?php if ($error = error('email')): ?>
                                <div class="invalid-feedback"><?= e($error) ?></div>
                            <?php endif; ?>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i><?= __('save') ?>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Change Password -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-shield-lock-fill me-2 text-primary"></i><?= __('profile.change_password') ?>
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="/profile/password">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="current_password" class="form-label">
                                <?= __('profile.current_password') ?>
                            </label>
                            <input type="password" class="form-control"
                                   id="current_password" name="current_password" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label"><?= __('auth.new_password') ?></label>
                            <input type="password" class="form-control <?= hasError('password') ? 'is-invalid' : '' ?>"
                                   id="password" name="password" minlength="8" required>
                            <div class="form-text"><?= Lang::getLocale() === 'ka' ? 'მინიმუმ 8 სიმბოლო' : 'Minimum 8 characters' ?></div>
                            <?php if ($error = error('password')): ?>
                                <div class="invalid-feedback"><?= e($error) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">
                                <?= __('auth.confirm_password') ?>
                            </label>
                            <input type="password" class="form-control"
                                   id="password_confirmation" name="password_confirmation"
                                   minlength="8" required>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-shield-lock me-2"></i><?= __('profile.change_password') ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Account Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-info-circle-fill me-2 text-primary"></i><?= Lang::getLocale() === 'ka' ? 'ანგარიშის ინფო' : 'Account Info' ?>
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                        <span class="text-muted"><?= Lang::getLocale() === 'ka' ? 'რეგისტრაცია' : 'Member since' ?></span>
                        <span class="fw-semibold"><?= formatDate($user['created_at'], 'd/m/Y') ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center p-3">
                        <span class="text-muted"><?= Lang::getLocale() === 'ka' ? 'სტატუსი' : 'Status' ?></span>
                        <span class="badge bg-success"><?= Lang::getLocale() === 'ka' ? 'აქტიური' : ucfirst($user['status']) ?></span>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-link-45deg me-2 text-primary"></i><?= Lang::getLocale() === 'ka' ? 'სწრაფი ბმულები' : 'Quick Links' ?>
                    </h6>
                </div>
                <div class="card-body p-0">
                    <a href="/vehicles" class="d-flex align-items-center p-3 border-bottom text-decoration-none text-dark">
                        <i class="bi bi-car-front-fill text-primary me-3"></i>
                        <span><?= __('vehicle.my_vehicles') ?></span>
                        <i class="bi bi-chevron-right ms-auto text-muted"></i>
                    </a>
                    <a href="/reports" class="d-flex align-items-center p-3 text-decoration-none text-dark">
                        <i class="bi bi-file-earmark-text-fill text-primary me-3"></i>
                        <span><?= __('report.my_reports') ?></span>
                        <i class="bi bi-chevron-right ms-auto text-muted"></i>
                    </a>
                </div>
            </div>

            <!-- Logout Button -->
            <a href="/logout" class="btn btn-outline-danger w-100 py-3">
                <i class="bi bi-box-arrow-right me-2"></i><?= __('auth.logout') ?>
            </a>
        </div>
    </div>
</div>

<style>
.profile-header {
    position: relative;
    text-align: center;
    padding: 2rem 1rem;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark, #4f46e5) 100%);
    border-radius: var(--radius-xl);
    color: white;
    margin: -1rem -0.75rem 1.5rem;
}
@media (min-width: 768px) {
    .profile-header {
        margin: 0 0 1.5rem;
    }
}
.profile-avatar-large {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    font-weight: 700;
    color: white;
    border: 4px solid rgba(255,255,255,0.3);
}
.profile-header h4 {
    color: white;
}
.profile-header .text-muted {
    color: rgba(255,255,255,0.8) !important;
}
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
