<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Wanda Communications</title>
    <meta name="robots" content="noindex,nofollow">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/admin/css/admin.css">
    <?php $faviconPath = setting('favicon_path');
    if ($faviconPath !== '') : ?>
        <link rel="icon" type="image/png" href="<?= e(BASE_URL . '/' . $faviconPath) ?>">
    <?php endif; ?>
</head>

<body>
    <div class="admin-login-wrap">
        <div class="admin-login-card">
            <?php $loginLogo = setting('logo_path');
            if ($loginLogo !== '') : ?>
                <div class="logo" style="background:none;padding:.5rem 0">
                    <img src="<?= e(BASE_URL . '/' . $loginLogo) ?>"
                        alt="Logo"
                        style="max-height:52px;max-width:200px;object-fit:contain;display:block;margin:0 auto">
                </div>
            <?php else : ?>
                <div class="logo">Wanda <span>Admin</span></div>
            <?php endif; ?>
            <?php if (!empty($error)) : ?>
                <div class="alert alert-error"><?= e($error) ?></div>
            <?php endif; ?>
            <form method="post" class="admin-form" autocomplete="off">
                <?= csrfField() ?>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username"
                        value="<?= e($_POST['username'] ?? '') ?>"
                        required autocomplete="username" autofocus>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password">
                </div>
                <button type="submit" class="btn-adm btn-adm-primary btn-adm-lg" style="width:100%">
                    <i class="bi bi-box-arrow-in-right"></i> Sign In
                </button>
            </form>
        </div>
    </div>
</body>

</html>