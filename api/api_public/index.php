<?php
// Root path
const ROOT_PATH = '../../';

// Composer autoload
require ROOT_PATH . 'vendor/autoload.php';

// Custom libraries
require_once ROOT_PATH . 'api/lib/auth.php';
require_once ROOT_PATH . 'api/lib/fetch.php';
require_once ROOT_PATH . 'api/lib/test_input.php';

// Database configuration
const DB_INI_PATH = ROOT_PATH . 'api/db.ini';

// Create and configure Slim app
$container = new \Slim\Container;
$app = new \Slim\App($container);

/**
 * Connects to the MySQL database.
 *
 * @param \Slim\Container $container slim dependency container
 * @return mysqli database connection
 */
$container['myDb'] = function ($container) {
    $db_ini = parse_ini_file(DB_INI_PATH);
    $db = new mysqli($db_ini['host'], $db_ini['user'], $db_ini['pass'], $db_ini['name']);
    return $db;
};

/**
 * Parses the admin username from the database configuration.
 *
 * @param \Slim\Container $container slim dependency container
 * @return string admin username
 */
$container['admin_user'] = function ($container) {
    $db_ini = parse_ini_file(DB_INI_PATH);
    return $db_ini['admin_user'];
};

/**
 * Parses the site email from the database configuration.
 *
 * @param \Slim\Container $container slim dependency container
 * @return string email
 */
$container['email'] = function ($container) {
    $db_ini = parse_ini_file(DB_INI_PATH);
    return $db_ini['email'];
};

// App routes
require ROOT_PATH . 'api/routes/text.php';
require ROOT_PATH . 'api/routes/feature-cards.php';
require ROOT_PATH . 'api/routes/equipment-list.php';
require ROOT_PATH . 'api/routes/manufacturing-services.php';
require ROOT_PATH . 'api/routes/tool-room/tool-room-equipment.php';
require ROOT_PATH . 'api/routes/tool-room/tool-room-services.php';
require ROOT_PATH . 'api/routes/mail.php';
require ROOT_PATH . 'api/routes/auth.php';

// Start php session
session_start();

// Run app
$app->run();
?>
