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
define('DB_NAME', 'wanda_db');
define('DB_USER', 'root');       // XAMPP default
define('DB_PASS', '');           // XAMPP default (no password)
define('DB_CHARSET', 'utf8mb4');

// ── Site URL ─────────────────────────────────────────────────────────────────
// No trailing slash. Change if the site lives at a different path.
define('BASE_URL', 'http://localhost:5000');

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
