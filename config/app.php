<?php

/**
 * config/app.php — Application constants (loaded after phpdotenv).
 *
 * All sensitive values come from $_ENV (populated by .env).
 */

// ── Database ─────────────────────────────────────────────────────────────────
define('DB_HOST',       $_ENV['DB_HOST']       ?? 'localhost');
define('DB_PORT',       (int)($_ENV['DB_PORT'] ?? 3306));
define('DB_NAME',       $_ENV['DB_NAME']       ?? 'wanda_db');
define('DB_USER',       $_ENV['DB_USER']       ?? 'root');
define('DB_PASS',       $_ENV['DB_PASS']       ?? '');
define('DB_CHARSET',    $_ENV['DB_CHARSET']    ?? 'utf8mb4');
define('DB_PERSISTENT', filter_var($_ENV['DB_PERSISTENT'] ?? false, FILTER_VALIDATE_BOOLEAN));

// ── Site URL ─────────────────────────────────────────────────────────────────
// Prefer the .env APP_URL; fall back to host-based detection for local dev.
define(
    'BASE_URL',
    isset($_ENV['APP_URL']) && $_ENV['APP_URL'] !== ''
        ? rtrim($_ENV['APP_URL'], '/')
        : ((isset($_SERVER['HTTP_HOST']) && str_contains($_SERVER['HTTP_HOST'], 'localhost'))
            ? 'http://' . $_SERVER['HTTP_HOST']
            : 'https://wandacommunications.com')
);

// ── Filesystem paths ─────────────────────────────────────────────────────────
define('ROOT_PATH',   dirname(__DIR__));
define('UPLOAD_PATH', ROOT_PATH . '/public/uploads/');
define('UPLOAD_URL',  BASE_URL  . '/uploads/');

// ── Upload limits ────────────────────────────────────────────────────────────
define('MAX_UPLOAD_BYTES',  (int)($_ENV['MAX_UPLOAD_BYTES'] ?? 5 * 1024 * 1024));
define('ALLOWED_MIME_TYPES', ['image/jpeg', 'image/png', 'image/webp', 'image/gif']);
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'webp', 'gif']);

// ── Session ───────────────────────────────────────────────────────────────────
define('SESSION_NAME', $_ENV['SESSION_NAME'] ?? 'wanda_session');

// ── Content categories ────────────────────────────────────────────────────────
define('BLOG_CATEGORIES',      ['storytelling', 'advocacy', 'digital', 'strategy']);
define('PORTFOLIO_CATEGORIES', ['photography', 'videography', 'advocacy', 'reports']);
define('REPORT_CATEGORIES',    ['research', 'policy', 'advocacy', 'evaluation', 'annual']);

// ── Gradient presets (portfolio & team admin forms) ───────────────────────────
define('GRADIENT_OPTIONS', [
    'linear-gradient(135deg, #1a6fc4 0%, #0d3f70 100%)' => 'Blue',
    'linear-gradient(135deg, #e8b84b 0%, #c0891d 100%)' => 'Amber',
    'linear-gradient(135deg, #198754 0%, #0d4a2e 100%)' => 'Green',
    'linear-gradient(135deg, #7c3aed 0%, #3b0764 100%)' => 'Purple',
    'linear-gradient(135deg, #dc3545 0%, #7a0010 100%)' => 'Red',
    'linear-gradient(135deg, #0d9488 0%, #042f2e 100%)' => 'Teal',
]);
