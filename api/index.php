<?php
// Composer autoload
require '../vendor/autoload.php';

// Custom libraries
require_once 'lib/auth.php';
require_once 'lib/fetch.php';

// Database configuration
const DB_INI_PATH = "../../private/db.ini";

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

// App routes
require 'routes/text.php';
require 'routes/feature-cards.php';
require 'routes/equipment-list.php';
require 'routes/manufacturing-services.php';
require 'routes/tool-room/tool-room-equipment.php';
require 'routes/tool-room/tool-room-services.php';

// Start php session
session_start();

// Run app
$app->run();
?>
