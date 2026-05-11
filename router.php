<?php
/**
 * router.php — PHP built-in server router
 *
 * Usage:  php -S localhost:5000 router.php
 *
 * Mirrors all mod_rewrite rules from .htaccess so clean URLs work
 * identically under the built-in dev server.
 */

$uri  = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$root = __DIR__;

// Serve real files and directories directly (CSS, JS, images, etc.)
if ($uri !== '/' && file_exists($root . $uri)) {
    return false;
}

// Strip leading slash, strip trailing slash
$path = trim($uri, '/');

// ── Route table ───────────────────────────────────────────────────────────────

// Home
if ($path === '') {
    require $root . '/index.php';
    exit;
}

// Static pages
if ($path === 'about')    { require $root . '/about.php';    exit; }
if ($path === 'services') { require $root . '/services.php'; exit; }
if ($path === 'contact')  { require $root . '/contact.php';  exit; }

// Blog list
if ($path === 'blog') {
    require $root . '/blog.php';
    exit;
}

// Blog post: /blog/<slug>
if (preg_match('#^blog/([a-z0-9\-]+)$#', $path, $m)) {
    $_GET['slug'] = $m[1];
    require $root . '/blog-post.php';
    exit;
}

// Portfolio
if ($path === 'portfolio') {
    require $root . '/portfolio.php';
    exit;
}

// Team
if ($path === 'team') {
    require $root . '/team.php';
    exit;
}

// Admin — let PHP serve the real file tree normally
if (str_starts_with($path, 'admin')) {
    return false;
}

// 404 — nothing matched
http_response_code(404);
require $root . '/404.php' ?: echo '<h1>404 Not Found</h1>';
