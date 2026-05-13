<?php

/**
 * PHPUnit bootstrap — loads the MVC application bootstrap so that
 * environment variables, constants, autoloading, and the database
 * connection are all available inside tests.
 */

define('APP_ROOT', dirname(__DIR__));

require APP_ROOT . '/vendor/autoload.php';
require APP_ROOT . '/bootstrap/app.php';
