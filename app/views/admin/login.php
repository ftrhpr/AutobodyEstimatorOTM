<h4 class="mb-4 text-center"><?= __('admin.admin_panel') ?></h4>

<form method="POST" action="/admin/login">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person"></i></span>
            <input type="text" class="form-control" id="username" name="username" required autofocus>
        </div>
    </div>

    <div class="mb-4">
        <label for="password" class="form-label"><?= __('auth.password') ?></label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-100 py-2">
        <i class="bi bi-box-arrow-in-right me-2"></i><?= __('auth.login') ?>
    </button>
</form>
