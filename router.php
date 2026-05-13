<?php

/**
 * router.php — PHP built-in server router script.
 *
 * Usage (from project root):
 *   php -S localhost:8000 -t public router.php
 *
 * The -t public flag sets the document root to public/, so that:
 *   - returning false serves the file correctly from public/
 *   - /admin/* routes are not blocked by the public/admin/ directory
 *
 * Do NOT use:  php -S localhost:8000 router.php          (no -t → CSS 404s)
 * Do NOT use:  php -S localhost:8000 -t public           (no router → all routes 404)
 */

$uri  = $_SERVER['REQUEST_URI'];
$file = __DIR__ . '/public' . urldecode(parse_url($uri, PHP_URL_PATH));

// Let the server handle real files (assets) natively
if (is_file($file)) {
    return false;
}

// Everything else goes through the front-controller
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/public/index.php';
require __DIR__ . '/public/index.php';
