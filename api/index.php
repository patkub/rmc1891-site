<?php
require '../vendor/autoload.php';
require 'fetch.php';

// Database configuration
const DB_INI_PATH = "db.ini";
//const DB_INI_PATH = "/../../../private/db.ini";

// Create and configure Slim app
$container = new \Slim\Container;
$app = new \Slim\App($container);

// Connect to MySQL database
$container['myDb'] = function ($container) {
    $db_ini = parse_ini_file(DB_INI_PATH);
    $db = new mysqli($db_ini['host'], $db_ini['user'], $db_ini['pass'], $db_ini['name']);
    return $db;
};

// Text route
// {text} - 'about', 'capabilities', or 'contact'
// http://beta.therogersmanufacturingcompany.com/api/get/text/about
$app->get('/get/text/{text}', function ($request, $response, $args) {
    // Connect to MySQL database
    $db = $this->get('myDb');
    
    // Get text
    $txtQuery = sprintf("SELECT `text` FROM `Text` WHERE `name` = '%s'",
        $db->real_escape_string($args['text']));
    $row = queryAndFetch($db, $txtQuery);
    
    // Return JSON
    header('Access-Control-Allow-Origin: *');
    return $response->withJson($row['text']);
});

// Feature cards route
// http://beta.therogersmanufacturingcompany.com/api/get/feature-cards
$app->get('/get/feature-cards', function ($request, $response, $args) {
    // Connect to MySQL database
    $db = $this->get('myDb');
    
    // Get feature cards
    $results = queryAndFetchAll($db, "SELECT * FROM `FeatureCards`");
    
    // Return JSON
    header('Access-Control-Allow-Origin: *');
    return $response->withJson($results);
});

// Equipment list route
// http://beta.therogersmanufacturingcompany.com/api/get/equipment-list
$app->get('/get/equipment-list', function ($request, $response, $args) {
    // Connect to MySQL database
    $db = $this->get('myDb');
    
    // Get equipment ordered by descending clamping force
    $results = queryAndFetchAll($db, "SELECT * FROM `EquipmentList` ORDER BY `EquipmentList`.`force` DESC");
    
    // Return JSON
    header('Access-Control-Allow-Origin: *');
    return $response->withJson($results);
});

// Manufacturing services route
// http://beta.therogersmanufacturingcompany.com/api/get/manufacturing-services
$app->get('/get/manufacturing-services', function ($request, $response, $args) {
    // Connect to MySQL database
    $db = $this->get('myDb');
    
    // Get manufacturing services in alphabetical order
    $results = queryAndFetchAll($db, "SELECT * FROM `ManufacturingServices` ORDER BY `ManufacturingServices`.`name` ASC");
    
    // Return JSON
    header('Access-Control-Allow-Origin: *');
    return $response->withJson($results);
});

// Tool room equipment route
// http://beta.therogersmanufacturingcompany.com/api/get/tool-room/equipment
$app->get('/get/tool-room/equipment', function ($request, $response, $args) {
    // Connect to MySQL database
    $db = $this->get('myDb');
    
    // Get tool room equipment list ordered by descending weight
    $results = queryAndFetchAll($db, "SELECT * FROM `ToolRoomEquipment` ORDER BY `ToolRoomEquipment`.`count` DESC");
    
    // Return JSON
    header('Access-Control-Allow-Origin: *');
    return $response->withJson($results);
});

// Tool room services route
// http://beta.therogersmanufacturingcompany.com/api/get/tool-room/services
$app->get('/get/tool-room/services', function ($request, $response, $args) {
    // Connect to MySQL database
    $db = $this->get('myDb');
    
    // Get tool room services list in alphabetical order
    $results = queryAndFetchAll($db, "SELECT * FROM `ToolRoomServices` ORDER BY `ToolRoomServices`.`name` ASC");
    
    // Return JSON
    header('Access-Control-Allow-Origin: *');
    return $response->withJson($results);
});

// Run app
$app->run();
?>
