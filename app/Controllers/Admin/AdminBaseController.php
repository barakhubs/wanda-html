<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * Base admin controller.
 * Enforces authentication; routes view rendering through the admin layout.
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
    }

    /** Render an admin view through the admin layout. */
    protected function adminView(string $template, array $data = []): void
    {
        $this->view($template, $data, 'admin');
    }
}
