<?php

/**
 * bootstrap/app.php
 *
 * Application bootstrap — loaded by public/index.php (front controller).
 * Sets up: output buffering, Composer autoloader, phpdotenv, constants,
 * Database singleton, session, HTTP security headers, Router + View init.
 */

// ── Output buffering ──────────────────────────────────────────────────────────
if (ob_get_level() === 0) {
    ob_start();
}

// ── Composer autoloader ───────────────────────────────────────────────────────
require_once dirname(__DIR__) . '/vendor/autoload.php';

// ── Load environment variables (.env) ─────────────────────────────────────────
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();
$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER'])->notEmpty();

// ── Application constants ─────────────────────────────────────────────────────
require_once dirname(__DIR__) . '/config/app.php';

// ── Database ──────────────────────────────────────────────────────────────────
// The legacy Database class uses constants defined in config/app.php.
// It is autoloaded via its namespace but we still need to verify it's
// available before the first request touches any model.
require_once dirname(__DIR__) . '/includes/Database.php';

// ── Session ───────────────────────────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_strict_mode', '1');
    session_start();
}

// ── HTTP security headers ─────────────────────────────────────────────────────
if (!headers_sent()) {
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');

    $isAdminPath = str_starts_with($_SERVER['REQUEST_URI'] ?? '', '/admin');
    if ($isAdminPath) {
        header('Cache-Control: no-store, no-cache, must-revalidate');
    } else {
        header('Cache-Control: public, max-age=30, s-maxage=60, stale-while-revalidate=300');
    }
}

// ── View init ─────────────────────────────────────────────────────────────────
use App\Core\View;

View::init(ROOT_PATH . '/app/Views');
