<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * Base admin controller.
 * Enforces authentication and (optionally) role-based access control.
 */
abstract class AdminBaseController extends BaseController
{
    public function __construct()
    {
        $this->requireAuth();
    }

    protected function requireAuth(): void
    {
        if (empty($_SESSION['admin_id'])) {
            $this->redirect(BASE_URL . '/admin/login');
        }

        // Always reload the role from the database on every request.
        // This ensures that if a role is ever corrected in the DB the session
        // reflects the change immediately instead of persisting a stale value.
        try {
            $stmt = \Database::getInstance()->prepare(
                'SELECT role FROM admins WHERE id = ? LIMIT 1'
            );
            $stmt->execute([$_SESSION['admin_id']]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            $_SESSION['admin_role'] = $row['role'] ?? 'member';
        } catch (\Throwable) {
            // If the DB is unreachable fall back to whatever is already in the
            // session (or default to 'member' to fail safe).
            $_SESSION['admin_role'] = $_SESSION['admin_role'] ?? 'member';
        }
    }

    /**
     * Restrict a controller action to the 'admin' role only.
     * Members are redirected to the dashboard with a flash message.
     */
    protected function requireAdminRole(): void
    {
        if (($this->sessionRole()) !== 'admin') {
            flashMessage('error', 'You do not have permission to access that page.');
            $this->redirect(BASE_URL . '/admin');
        }
    }

    /** Return the current user's role from the session. */
    protected function sessionRole(): string
    {
        return $_SESSION['admin_role'] ?? 'member';
    }

    /** Return true when the logged-in user is a super-admin. */
    protected function isAdmin(): bool
    {
        return $this->sessionRole() === 'admin';
    }

    /** Render an admin view through the admin layout. */
    protected function adminView(string $template, array $data = []): void
    {
        // Inject the current user's role so views can conditionally render nav items.
        $data['_role'] = $this->sessionRole();
        $this->view($template, $data, 'admin');
    }
}
