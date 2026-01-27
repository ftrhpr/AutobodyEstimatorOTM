<div class="container">
    <h2 class="mb-4"><i class="bi bi-person-circle me-2"></i><?= __('profile.my_profile') ?></h2>

    <div class="row g-4">
        <!-- Profile Information -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><?= __('profile.edit_profile') ?></h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/profile">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="name" class="form-label"><?= __('auth.name') ?> *</label>
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
                            <input type="text" class="form-control" id="phone"
                                   value="<?= e($user['phone']) ?>" disabled>
                            <div class="form-text">Phone number cannot be changed</div>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label"><?= __('auth.email') ?></label>
                            <input type="email" class="form-control <?= hasError('email') ? 'is-invalid' : '' ?>"
                                   id="email" name="email"
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
        </div>

        <!-- Change Password -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><?= __('profile.change_password') ?></h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/profile/password">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="current_password" class="form-label">
                                <?= __('profile.current_password') ?> *
                            </label>
                            <input type="password" class="form-control"
                                   id="current_password" name="current_password" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label"><?= __('auth.new_password') ?> *</label>
                            <input type="password" class="form-control <?= hasError('password') ? 'is-invalid' : '' ?>"
                                   id="password" name="password" minlength="8" required>
                            <div class="form-text">Minimum 8 characters</div>
                            <?php if ($error = error('password')): ?>
                                <div class="invalid-feedback"><?= e($error) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">
                                <?= __('auth.confirm_password') ?> *
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

            <!-- Account Info -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Account Information</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="bi bi-calendar text-muted me-2"></i>
                            <strong>Member since:</strong>
                            <?= formatDate($user['created_at'], 'd/m/Y') ?>
                        </li>
                        <li>
                            <i class="bi bi-shield-check text-muted me-2"></i>
                            <strong>Status:</strong>
                            <span class="badge bg-success"><?= ucfirst($user['status']) ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
