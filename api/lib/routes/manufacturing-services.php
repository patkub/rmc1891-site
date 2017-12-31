<?php
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
 * Manufacturing services PUT route.
 * 
 * @param  \Psr\Http\Message\ServerRequestInterface $req  PSR7 request
 * @param  \Psr\Http\Message\ResponseInterface      $res  PSR7 response
 * @param  array                                    $args Route parameters
 */
$app->put('/put/manufacturing-services', function ($req, $res, $args) {
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
    $updateQuery = sprintf("INSERT INTO `ManufacturingServices`(`name`) VALUES ('%s')",
        $db->real_escape_string($body['item']));
    
    // Execute query
    $db->query($updateQuery) or die($db->error);
});

/**
 * Manufacturing services DELETE route.
 * 
 * @param  \Psr\Http\Message\ServerRequestInterface $req  PSR7 request
 * @param  \Psr\Http\Message\ResponseInterface      $res  PSR7 response
 * @param  array                                    $args Route parameters
 */
$app->delete('/delete/manufacturing-services', function ($req, $res, $args) {
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
    $deleteQuery = sprintf("DELETE FROM `ManufacturingServices` WHERE `name` = '%s'",
        $db->real_escape_string($body['item']));
    
    // Execute query
    $db->query($deleteQuery) or die($db->error);
});
?>
