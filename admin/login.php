<?php
require_once dirname(__DIR__) . '/includes/bootstrap.php';

// Already logged in
if (!empty($_SESSION['admin_id'])) {
    header('Location: ' . BASE_URL . '/admin/');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF check (login form uses its own token field)
    verifyCsrf();

    $username = trim($_POST['username'] ?? '');
    $password  = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Please enter both username and password.';
    } else {
        $db   = Database::getInstance();
        $stmt = $db->prepare('SELECT id, password_hash FROM admins WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $row  = $stmt->fetch();

        if ($row && password_verify($password, $row['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id']       = $row['id'];
            $_SESSION['admin_username'] = $username;
            header('Location: ' . BASE_URL . '/admin/');
            exit;
        } else {
            // Generic message to prevent username enumeration
            $error = 'Invalid credentials. Please try again.';
        }
    }
}

$adminPageTitle = 'Login';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Wanda Communications</title>
    <meta name="robots" content="noindex,nofollow">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/admin/css/admin.css">
</head>

<body>
    <div class="admin-login-wrap">
        <div class="admin-login-card">
            <div class="logo">Wanda <span>Admin</span></div>
            <?php if ($error) : ?>
                <div class="alert alert-error"><?= e($error) ?></div>
            <?php endif; ?>
            <form method="post" class="admin-form" autocomplete="off">
                <?= csrfField() ?>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username"
                        value="<?= e(htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES)) ?>"
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