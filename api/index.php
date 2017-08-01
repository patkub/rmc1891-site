<?php
require '../vendor/autoload.php';

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
// http://beta.therogersmanufacturingcompany.com/api/index.php/get/text/about
$app->get('/get/text/{text}', function ($request, $response, $args) {
    // Connect to MySQL database
    $db = $this->get('myDb');
    
    // Get text
    $txtQuery = sprintf("SELECT `text` FROM `Text` WHERE `name` = '%s'",
        $db->real_escape_string($args['text']));
    $result = $db->query($txtQuery) or die($db->error);
    $row = $result->fetch_assoc();
    
    // Return JSON
    header('Access-Control-Allow-Origin: *');
    return $response->withJson($row['text']);
});

// Feature cards route
// http://beta.therogersmanufacturingcompany.com/api/index.php/get/feature-cards
$app->get('/get/feature-cards', function ($request, $response, $args) {
    // Connect to MySQL database
    $db = $this->get('myDb');
    
    // query all feature cards
    $myQuery = "SELECT * FROM `FeatureCards`";
    $result = $db->query($myQuery) or die($db->error);
    
    // store each feature card
    $results = array();
    while ($row = $result->fetch_assoc()){
        $results[] = $row;
    }
    
    // Return JSON
    header('Access-Control-Allow-Origin: *');
    return $response->withJson($results);
});

// Run app
$app->run();
?>
