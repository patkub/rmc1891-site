<?php
require '../vendor/autoload.php';

// Database configuration
const DB_INI_PATH = "db.ini";
//const DB_INI_PATH = "/../../../private/db.ini";

// Create and configure Slim app
$container = new \Slim\Container;
$app = new \Slim\App($container);

$container['myDb'] = function ($container) {
    $db_ini = parse_ini_file(DB_INI_PATH);
    $db = new mysqli($db_ini['host'], $db_ini['user'], $db_ini['pass'], $db_ini['name']);
    return $db;
};

// Define app routes
$app->get('/json/', function ($request, $response, $args) {
    $db = $this->get('myDb');
    
    $results = array();
    $myQuery = "SELECT * FROM `EquipmentList` ORDER BY `EquipmentList`.`force` DESC";
    $result = $db->query($myQuery) or die($db->error);
    
    while ($row = $result->fetch_assoc()){
        $results[] = $row;
    }
    
    header('Access-Control-Allow-Origin: *');
    return $response->withJson($results);
});

// Text route
$app->get('/text/{text}', function ($request, $response, $args) {
    $db = $this->get('myDb');
    
    $txtQuery = sprintf("SELECT `text` FROM `Text` WHERE `name` = '%s'",
        $db->real_escape_string($args['text']));
    $result = $db->query($txtQuery) or die($db->error);
    $row = $result->fetch_assoc();
    
    header('Access-Control-Allow-Origin: *');
    return $response->withJson($row['text']);
});

// Run app
$app->run();
?>
