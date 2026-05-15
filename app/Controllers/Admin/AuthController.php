<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class AuthController extends BaseController
{
    public function showLogin(): void
    {
        if (!empty($_SESSION['admin_id'])) {
            $this->redirect(BASE_URL . '/admin');
        }

        $this->view('admin/auth/login', ['error' => ''], null);
    }

    public function login(): void
    {
        if (!empty($_SESSION['admin_id'])) {
            $this->redirect(BASE_URL . '/admin');
        }

        verifyCsrf();

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            $this->view('admin/auth/login', ['error' => 'Please enter both username and password.'], null);
            return;
        }

        $db   = \Database::getInstance();
        $stmt = $db->prepare('SELECT id, role, password_hash FROM admins WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $row = $stmt->fetch();

        if ($row && password_verify($password, $row['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id']       = $row['id'];
            $_SESSION['admin_username'] = $username;
            $_SESSION['admin_role']     = $row['role'];
            $this->redirect(BASE_URL . '/admin');
        }

        $this->view('admin/auth/login', ['error' => 'Invalid credentials. Please try again.'], null);
    }

    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
        $this->redirect(BASE_URL . '/admin/login');
    }
}
