<?php
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
 * Equipment list PUT route.
 * 
 * @param  \Psr\Http\Message\ServerRequestInterface $req  PSR7 request
 * @param  \Psr\Http\Message\ResponseInterface      $res  PSR7 response
 * @param  array                                    $args Route parameters
 */
$app->put('/put/equipment-list', function ($req, $res, $args) {
    // Check authentication
    if (!isset($_SESSION['auth']) || $_SESSION['auth'] != true) {
      // Unauthorized HTTP 401
      return adminAuthError($res);
    }
    
    // Connect to MySQL database
    $db = $this->get('myDb');
    
    // Get request body
    $body = $req->getParsedBody();
    
    // Format update query
    $updateQuery = sprintf("INSERT INTO `EquipmentList`(`count`, `force`, `name`) VALUES (%d, %d, '%s')",
        intval($body['count']), intval($body['force']), $db->real_escape_string($body['name']));
    
    // Execute query
    $db->query($updateQuery) or die($db->error);
});

/**
 * Equipment list DELETE route.
 * 
 * @param  \Psr\Http\Message\ServerRequestInterface $req  PSR7 request
 * @param  \Psr\Http\Message\ResponseInterface      $res  PSR7 response
 * @param  array                                    $args Route parameters
 */
$app->put('/delete/equipment-list', function ($req, $res, $args) {
    // Check authentication
    if (!isset($_SESSION['auth']) || $_SESSION['auth'] != true) {
      // Unauthorized HTTP 401
      return adminAuthError($res);
    }
    
    // Connect to MySQL database
    $db = $this->get('myDb');
    
    // Get request body
    $body = $req->getParsedBody();
    
    // Format delete query
    $deleteQuery = sprintf("DELETE FROM `EquipmentList` WHERE (`count` = %d AND `force` = %d AND `name` = '%s')",
        intval($body['count']), intval($body['force']), $db->real_escape_string($body['name']));
    
    // Execute query
    $db->query($deleteQuery) or die($db->error);
});
?>
