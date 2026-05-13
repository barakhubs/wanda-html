<?php

/**
 * Bootstrap — required at the top of every public PHP page.
 * Loads config, starts session, includes Database and all classes.
 */

// ── Output buffering ──────────────────────────────────────────────────────────
// Buffer the entire response so PHP writes to the socket in one large chunk
// rather than many small flushes. Also lets us set headers freely at any point.
if (ob_get_level() === 0) {
    ob_start();
}

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/../classes/BlogPost.php';
require_once __DIR__ . '/../classes/Portfolio.php';
require_once __DIR__ . '/../classes/TeamMember.php';
require_once __DIR__ . '/../classes/Testimonial.php';
require_once __DIR__ . '/../classes/HomeGallery.php';
require_once __DIR__ . '/../classes/SiteSettings.php';

// Start session (public pages may use it for flash messages from contact form, etc.)
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_strict_mode', '1');
    session_start();
}

// ── HTTP security headers ─────────────────────────────────────────────────────
// Send once per response before any output.  These cover the OWASP baseline:
// clickjacking, MIME sniffing, and cross-site information leakage.
if (!headers_sent()) {
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');

    // ── Cache-Control ─────────────────────────────────────────────────────────
    // Admin paths must never be cached; all other (public read-only) pages can
    // be held by shared caches (CDN / reverse proxy) for 60 s and by browsers
    // for 30 s. This alone cuts origin hits dramatically under real load.
    $isAdminPath = str_starts_with($_SERVER['REQUEST_URI'] ?? '', '/admin');
    if ($isAdminPath) {
        header('Cache-Control: no-store, no-cache, must-revalidate');
    } else {
        header('Cache-Control: public, max-age=30, s-maxage=60, stale-while-revalidate=300');
    }
}
