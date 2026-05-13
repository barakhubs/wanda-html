<?php

/**
 * public/index.php — Front Controller
 *
 * This is the ONLY PHP file exposed to the web.
 * All requests are routed here via .htaccess and dispatched to the
 * appropriate controller.
 */

define('APP_ROOT', dirname(__DIR__));

// Bootstrap the application
require APP_ROOT . '/bootstrap/app.php';

// Build the router and register all routes
use App\Core\Router;

$router = new Router();
require APP_ROOT . '/routes/web.php';

// Dispatch
$router->dispatch(
    $_SERVER['REQUEST_METHOD'],
    $_SERVER['REQUEST_URI']
);

// Flush output buffer
if (ob_get_level() > 0) {
    ob_end_flush();
}
