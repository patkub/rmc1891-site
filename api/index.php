<?php
require '../vendor/autoload.php';
require 'fetch.php';

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

/**
 * Text GET route.
 * 
 * @param  \Psr\Http\Message\ServerRequestInterface $req  PSR7 request
 * @param  \Psr\Http\Message\ResponseInterface      $res  PSR7 response
 * @param  array                                    $args Route parameters
 * args['text'] array Defines the text to get, 'about', 'capabilities', or 'contact' text.
 * 
 * @return \Psr\Http\Message\ResponseInterface
 */
$app->get('/get/text/{text}', function ($req, $res, $args) {
    // Connect to MySQL database
    $db = $this->get('myDb');
    
    // Get text
    $txtQuery = sprintf("SELECT `text` FROM `Text` WHERE `name` = '%s'",
        $db->real_escape_string($args['text']));
    $row = queryAndFetch($db, $txtQuery);
    
    // Return JSON
    header('Access-Control-Allow-Origin: *');
    return $res->withJson($row['text']);
});

/**
 * Feature cards GET route.
 * 
 * @param  \Psr\Http\Message\ServerRequestInterface $req  PSR7 request
 * @param  \Psr\Http\Message\ResponseInterface      $res  PSR7 response
 * @param  array                                    $args Route parameters
 * 
 * @return \Psr\Http\Message\ResponseInterface
 */
$app->get('/get/feature-cards', function ($req, $res, $args) {
    // Connect to MySQL database
    $db = $this->get('myDb');
    
    // Get feature cards
    $results = queryAndFetchAll($db, "SELECT * FROM `FeatureCards`");
    
    // Return JSON
    header('Access-Control-Allow-Origin: *');
    return $res->withJson($results);
});

/**
 * Equipment list GET route.
 * 
 * @param  \Psr\Http\Message\ServerRequestInterface $req  PSR7 request
 * @param  \Psr\Http\Message\ResponseInterface      $res  PSR7 response
 * @param  array                                    $args Route parameters
 * 
 * @return \Psr\Http\Message\ResponseInterface
 */
$app->get('/get/equipment-list', function ($req, $res, $args) {
    // Connect to MySQL database
    $db = $this->get('myDb');
    
    // Get equipment ordered by descending clamping force
    $results = queryAndFetchAll($db, "SELECT * FROM `EquipmentList` ORDER BY `EquipmentList`.`force` DESC");
    
    // Return JSON
    header('Access-Control-Allow-Origin: *');
    return $res->withJson($results);
});

/**
 * Manufacturing services GET route.
 * 
 * @param  \Psr\Http\Message\ServerRequestInterface $req  PSR7 request
 * @param  \Psr\Http\Message\ResponseInterface      $res  PSR7 response
 * @param  array                                    $args Route parameters
 * 
 * @return \Psr\Http\Message\ResponseInterface
 */
$app->get('/get/manufacturing-services', function ($req, $res, $args) {
    // Connect to MySQL database
    $db = $this->get('myDb');
    
    // Get manufacturing services in alphabetical order
    $results = queryAndFetchAll($db, "SELECT * FROM `ManufacturingServices` ORDER BY `ManufacturingServices`.`name` ASC");
    
    // Return JSON
    header('Access-Control-Allow-Origin: *');
    return $res->withJson($results);
});

/**
 * Tool room equipment GET route.
 * 
 * @param  \Psr\Http\Message\ServerRequestInterface $req  PSR7 request
 * @param  \Psr\Http\Message\ResponseInterface      $res  PSR7 response
 * @param  array                                    $args Route parameters
 * 
 * @return \Psr\Http\Message\ResponseInterface
 */
$app->get('/get/tool-room/equipment', function ($req, $res, $args) {
    // Connect to MySQL database
    $db = $this->get('myDb');
    
    // Get tool room equipment list ordered by descending weight
    $results = queryAndFetchAll($db, "SELECT * FROM `ToolRoomEquipment` ORDER BY `ToolRoomEquipment`.`count` DESC");
    
    // Return JSON
    header('Access-Control-Allow-Origin: *');
    return $res->withJson($results);
});

/**
 * Tool room services GET route.
 * 
 * @param  \Psr\Http\Message\ServerRequestInterface $req  PSR7 request
 * @param  \Psr\Http\Message\ResponseInterface      $res  PSR7 response
 * @param  array                                    $args Route parameters
 * 
 * @return \Psr\Http\Message\ResponseInterface
 */
$app->get('/get/tool-room/services', function ($req, $res, $args) {
    // Connect to MySQL database
    $db = $this->get('myDb');
    
    // Get tool room services list in alphabetical order
    $results = queryAndFetchAll($db, "SELECT * FROM `ToolRoomServices` ORDER BY `ToolRoomServices`.`name` ASC");
    
    // Return JSON
    header('Access-Control-Allow-Origin: *');
    return $res->withJson($results);
});

/**
 * Feature cards PUT route.
 * 
 * @param  \Psr\Http\Message\ServerRequestInterface $req  PSR7 request
 * @param  \Psr\Http\Message\ResponseInterface      $res  PSR7 response
 * @param  array                                    $args Route parameters
 */
$app->put('/put/feature-cards', function ($req, $res, $args) {
    // Connect to MySQL database
    $db = $this->get('myDb');
    
    // Get request body text
    $body = $req->getParsedBody();
    $aboutText = $body['AboutText'];
    $capabilitiesText = $body['CapabilitiesText'];
    $contactText = $body['ContactText'];
    
    // Format update queries
    $aboutQuery = sprintf("UPDATE FeatureCards SET text='%s' WHERE title='About'",
        $db->real_escape_string($aboutText));
    $capabilitiesQuery = sprintf("UPDATE FeatureCards SET text='%s' WHERE title='Capabilities'",
        $db->real_escape_string($capabilitiesText));
    $contactQuery = sprintf("UPDATE FeatureCards SET text='%s' WHERE title='Contact'",
        $db->real_escape_string($contactText));
    
    // Execute queries
    $db->query($aboutQuery) or die($db->error);
    $db->query($capabilitiesQuery) or die($db->error);
    $db->query($contactQuery) or die($db->error);
});

// Run app
$app->run();
?>
