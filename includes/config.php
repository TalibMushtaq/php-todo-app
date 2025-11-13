<?php
/**
 * Database Configuration
 * 
 * Update these values according to your database setup
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'todo_app');

// Application settings
define('APP_NAME', 'Todo App');
define('APP_VERSION', '1.0.0');

// Error reporting (set to 0 in production)
define('DEBUG_MODE', true);

if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

