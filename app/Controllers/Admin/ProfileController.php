<?php

namespace App\Controllers\Admin;

use App\Models\Admin;

/**
 * Handles admin profile view and update actions.
 *
 * Routes:
 *   GET  /admin/profile           → index()       (show profile form)
 *   POST /admin/profile           → update()      (update profile info + optional avatar)
 *   POST /admin/profile/password  → password()    (change password)
 *   POST /admin/profile/username  → username()    (change username)
 */
class ProfileController extends AdminBaseController
{
    // ── Display ───────────────────────────────────────────────────────────────

    public function index(): void
    {
        $admin = $this->currentAdmin();

        $this->adminView('admin/profile/index', [
            'adminPageTitle' => 'My Profile',
            'flash'          => getFlash(),
            'errors'         => [],
            'admin'          => $admin,
        ]);
    }

    // ── Update profile info ───────────────────────────────────────────────────

    public function update(): void
    {
        verifyCsrf();

        $id       = (int) $_SESSION['admin_id'];
        $model    = new Admin();
        $errors   = [];

        $fullName = trim($_POST['full_name'] ?? '');
        $email    = trim($_POST['email']     ?? '');
        $bio      = trim($_POST['bio']       ?? '');

        // Validate
        if ($fullName === '') {
            $errors[] = 'Full name is required.';
        } elseif (mb_strlen($fullName) > 120) {
            $errors[] = 'Full name must be 120 characters or fewer.';
        }

        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }

        if (mb_strlen($bio) > 1000) {
            $errors[] = 'Bio must be 1000 characters or fewer.';
        }

        // Handle avatar upload
        $avatar = null;
        if (!empty($_FILES['avatar']['name'])) {
            try {
                $avatar = handleUpload($_FILES['avatar'], 'profile');
            } catch (\RuntimeException $e) {
                $errors[] = 'Avatar upload failed: ' . $e->getMessage();
            }
        }

        if (!empty($errors)) {
            $this->adminView('admin/profile/index', [
                'adminPageTitle' => 'My Profile',
                'flash'          => null,
                'errors'         => $errors,
                'admin'          => $this->currentAdmin(),
            ]);
            return;
        }

        $model->updateProfile($id, $fullName, $email, $bio, $avatar);

        flashMessage('success', 'Profile updated successfully.');
        $this->redirect(BASE_URL . '/admin/profile');
    }

    // ── Change password ───────────────────────────────────────────────────────

    public function password(): void
    {
        verifyCsrf();

        $id      = (int) $_SESSION['admin_id'];
        $model   = new Admin();
        $errors  = [];

        $current  = $_POST['current_password']      ?? '';
        $new      = $_POST['new_password']           ?? '';
        $confirm  = $_POST['confirm_password']       ?? '';

        if ($current === '' || $new === '' || $confirm === '') {
            $errors[] = 'All password fields are required.';
        } elseif (strlen($new) < 8) {
            $errors[] = 'New password must be at least 8 characters.';
        } elseif ($new !== $confirm) {
            $errors[] = 'New passwords do not match.';
        }

        if (empty($errors)) {
            $result = $model->changePassword($id, $current, $new);
            if ($result !== true) {
                $errors[] = $result;
            }
        }

        if (!empty($errors)) {
            $this->adminView('admin/profile/index', [
                'adminPageTitle' => 'My Profile',
                'flash'          => null,
                'errors'         => $errors,
                'admin'          => $this->currentAdmin(),
            ]);
            return;
        }

        flashMessage('success', 'Password changed successfully.');
        $this->redirect(BASE_URL . '/admin/profile');
    }

    // ── Change username ───────────────────────────────────────────────────────

    public function username(): void
    {
        verifyCsrf();

        $id       = (int) $_SESSION['admin_id'];
        $model    = new Admin();
        $errors   = [];

        $username = trim($_POST['username'] ?? '');

        if ($username === '') {
            $errors[] = 'Username is required.';
        } elseif (!preg_match('/^[a-zA-Z0-9_\-]{3,80}$/', $username)) {
            $errors[] = 'Username may only contain letters, numbers, underscores and hyphens (3–80 chars).';
        }

        if (empty($errors)) {
            $result = $model->updateUsername($id, $username);
            if ($result !== true) {
                $errors[] = $result;
            }
        }

        if (!empty($errors)) {
            $this->adminView('admin/profile/index', [
                'adminPageTitle' => 'My Profile',
                'flash'          => null,
                'errors'         => $errors,
                'admin'          => $this->currentAdmin(),
            ]);
            return;
        }

        // Refresh session username so the topbar stays in sync
        $_SESSION['admin_username'] = $username;

        flashMessage('success', 'Username updated successfully.');
        $this->redirect(BASE_URL . '/admin/profile');
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    /** Fetch the currently-logged-in admin record. */
    private function currentAdmin(): array
    {
        $admin = (new Admin())->getById((int) $_SESSION['admin_id']);
        if ($admin === null) {
            $this->redirect(BASE_URL . '/admin/login');
        }
        return $admin;
    }
}
