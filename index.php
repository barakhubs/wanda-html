<?php

/**
 * Root index.php — shared-hosting fallback front controller.
 *
 * cPanel's default document root is public_html/, but this application's
 * real entry point is public/index.php.  When the document root cannot be
 * changed to public_html/public/, placing this file here ensures Apache
 * finds a DirectoryIndex for the domain root and routes all traffic
 * correctly.
 *
 * PREFERRED setup: point the cPanel document root to public_html/public
 * (cPanel → Domains → wandacommunications.com → Edit → Document Root).
 */

// Normalise the URI if Apache's mod_dir changed "/" → "/index.php"
// via its internal DirectoryIndex mapping before mod_rewrite ran.
$_uri = $_SERVER['REQUEST_URI'] ?? '/';
if ($_uri === '/index.php' || str_starts_with($_uri, '/index.php?')) {
    $_SERVER['REQUEST_URI'] = '/' . (str_contains($_uri, '?') ? '?' . explode('?', $_uri, 2)[1] : '');
}
unset($_uri);

require __DIR__ . '/public/index.php';
