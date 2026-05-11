<?php

/**
 * admin/auth.php — Include at top of every protected admin page.
 * Redirects to login if the session is not authenticated.
 */
require_once dirname(__DIR__) . '/includes/bootstrap.php';

if (empty($_SESSION['admin_id'])) {
    header('Location: ' . BASE_URL . '/admin/login.php');
    exit;
}
