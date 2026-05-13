<?php

/**
 * Application Configuration
 * Wanda Communications Uganda
 *
 * IMPORTANT: Keep this file outside public web root in production.
 * On XAMPP: site is served from htdocs/wanda-html  →  BASE_URL = http://localhost/wanda-html
 */

// ── Database ─────────────────────────────────────────────────────────────────
define('DB_HOST', 'localhost');
define('DB_PORT', 3306);
define('DB_NAME', 'wanda_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');
// Set true in production when running PHP-FPM + a long-lived connection pool
// (e.g. ProxySQL or PgBouncer equivalent). Keep false on shared hosting.
define('DB_PERSISTENT', false);

// ── Site URL ─────────────────────────────────────────────────────────────────
// No trailing slash.
// Auto-detect: use localhost when running locally, production URL otherwise.
define('BASE_URL', (isset($_SERVER['HTTP_HOST']) && str_contains($_SERVER['HTTP_HOST'], 'localhost'))
    ? 'http://' . $_SERVER['HTTP_HOST']
    : 'https://wandacommunications.com');

// ── Filesystem paths ─────────────────────────────────────────────────────────
define('ROOT_PATH',   dirname(__DIR__));           // d:\wanda-html
define('UPLOAD_PATH', ROOT_PATH . '/uploads/');    // filesystem path to uploads/
define('UPLOAD_URL',  BASE_URL  . '/uploads/');    // public URL prefix for uploads

// ── Upload limits ────────────────────────────────────────────────────────────
define('MAX_UPLOAD_BYTES',  5 * 1024 * 1024);      // 5 MB
define('ALLOWED_MIME_TYPES', ['image/jpeg', 'image/png', 'image/webp', 'image/gif']);
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'webp', 'gif']);

// ── Session ───────────────────────────────────────────────────────────────────
define('SESSION_NAME', 'wanda_session');

// ── Content categories ────────────────────────────────────────────────────────
// These must stay in sync with the ENUM definitions in database/schema.sql.
define('BLOG_CATEGORIES',      ['storytelling', 'advocacy', 'digital', 'strategy']);
define('PORTFOLIO_CATEGORIES', ['photography', 'videography', 'advocacy', 'reports']);

// ── Gradient presets (used in portfolio and team admin forms) ─────────────────
define('GRADIENT_OPTIONS', [
    'linear-gradient(135deg, #1a6fc4 0%, #0d3f70 100%)' => 'Blue',
    'linear-gradient(135deg, #e8b84b 0%, #c0891d 100%)' => 'Amber',
    'linear-gradient(135deg, #198754 0%, #0d4a2e 100%)' => 'Green',
    'linear-gradient(135deg, #7c3aed 0%, #3b0764 100%)' => 'Purple',
    'linear-gradient(135deg, #dc3545 0%, #7a0010 100%)' => 'Red',
    'linear-gradient(135deg, #0d9488 0%, #042f2e 100%)' => 'Teal',
]);
