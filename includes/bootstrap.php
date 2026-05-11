<?php

/**
 * Bootstrap — required at the top of every public PHP page.
 * Loads config, starts session, includes Database and all classes.
 */

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
