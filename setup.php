<?php

/**
 * setup.php — One-time admin account creation.
 *
 * IMPORTANT: Delete or restrict access to this file immediately
 * after creating the admin account.
 *
 * Access: http://localhost/wanda-html/setup.php
 */
require_once __DIR__ . '/includes/bootstrap.php';

// Safety: if an admin already exists, refuse to run
$db     = Database::getInstance();
$count  = (int)$db->query('SELECT COUNT(*) FROM admins')->fetchColumn();

if ($count > 0) {
    die('<p style="font-family:sans-serif;padding:2rem;color:#991b1b">
        <strong>Setup already complete.</strong>
        An admin account exists. Delete setup.php for security.
    </p>');
}

$error   = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();

    $username  = trim($_POST['username'] ?? '');
    $password  = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if (strlen($username) < 3) {
        $error = 'Username must be at least 3 characters.';
    } elseif (strlen($password) < 10) {
        $error = 'Password must be at least 10 characters.';
    } elseif ($password !== $password2) {
        $error = 'Passwords do not match.';
    } else {
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt = $db->prepare("INSERT INTO admins (username, password_hash, role) VALUES (?, ?, 'admin')");
        $stmt->execute([$username, $hash]);
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup — Wanda Admin</title>
    <meta name="robots" content="noindex,nofollow">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #0d1b2a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 8px 40px rgba(0, 0, 0, .35);
        }

        h1 {
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
            color: #0d1b2a;
        }

        h1 span {
            color: #e8b84b;
        }

        .form-group {
            margin-bottom: 1.1rem;
        }

        label {
            display: block;
            font-size: .84rem;
            font-weight: 600;
            margin-bottom: .35rem;
            color: #1e2d3d;
        }

        input {
            width: 100%;
            padding: .55rem .85rem;
            border: 1px solid #dde1e7;
            border-radius: 6px;
            font-size: .9rem;
            font-family: inherit;
        }

        input:focus {
            outline: none;
            border-color: #1a6fc4;
            box-shadow: 0 0 0 3px rgba(26, 111, 196, .15);
        }

        .btn {
            width: 100%;
            padding: .65rem;
            background: #1a6fc4;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: .95rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: .5rem;
        }

        .btn:hover {
            background: #155fa0;
        }

        .alert {
            padding: .8rem 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            font-size: .88rem;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .hint {
            font-size: .78rem;
            color: #6b7a8d;
            margin-top: .25rem;
        }

        .security-note {
            margin-top: 1.25rem;
            padding: .75rem;
            background: #fef3c7;
            border: 1px solid #fcd34d;
            border-radius: 6px;
            font-size: .8rem;
            color: #78350f;
        }
    </style>
</head>

<body>
    <div class="card">
        <h1>Wanda <span>Setup</span></h1>

        <?php if ($success) : ?>
            <div class="alert alert-success">
                <strong>Admin account created!</strong><br>
                You can now <a href="<?= BASE_URL ?>/admin/login.php">log in</a>.<br><br>
                <strong>⚠ Delete setup.php immediately for security.</strong>
            </div>
        <?php else : ?>

            <?php if ($error) : ?>
                <div class="alert alert-error"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="post">
                <?= csrfField() ?>
                <div class="form-group">
                    <label for="username">Admin Username</label>
                    <input type="text" id="username" name="username"
                        value="<?= e($_POST['username'] ?? '') ?>"
                        autocomplete="off" required minlength="3">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required minlength="10"
                        autocomplete="new-password">
                    <div class="hint">Minimum 10 characters. Use a strong, unique password.</div>
                </div>
                <div class="form-group">
                    <label for="password2">Confirm Password</label>
                    <input type="password" id="password2" name="password2" required
                        autocomplete="new-password">
                </div>
                <button type="submit" class="btn">Create Admin Account</button>
            </form>

            <div class="security-note">
                ⚠ <strong>Security Notice:</strong> Delete <code>setup.php</code> immediately after creating your admin account. Never leave this file accessible on a production server.
            </div>

        <?php endif; ?>
    </div>
</body>

</html>