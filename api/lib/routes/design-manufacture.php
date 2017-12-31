<?php
/**
 * Design manufacture services GET route.
 * 
 * @param  \Psr\Http\Message\ServerRequestInterface $req  PSR7 request
 * @param  \Psr\Http\Message\ResponseInterface      $res  PSR7 response
 * @param  array                                    $args Route parameters
 * 
 * @return \Psr\Http\Message\ResponseInterface
 */
$app->get('/get/design-manufacture', function ($req, $res, $args) {
    // Connect to MySQL database
    $db = $this->get('myDb');
    
    // Get design manufacture services in alphabetical order
    $results = queryAndFetchAll($db, "SELECT * FROM `DesignManufacture` ORDER BY `DesignManufacture`.`name` ASC");
    
    // Return JSON
    header('Access-Control-Allow-Origin: *');
    return $res->withJson($results);
});

/**
 * Design manufacture services PUT route.
 * 
 * @param  \Psr\Http\Message\ServerRequestInterface $req  PSR7 request
 * @param  \Psr\Http\Message\ResponseInterface      $res  PSR7 response
 * @param  array                                    $args Route parameters
 */
$app->put('/put/design-manufacture', function ($req, $res, $args) {
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
    $updateQuery = sprintf("INSERT INTO `DesignManufacture`(`name`) VALUES ('%s')",
        $db->real_escape_string($body['item']));
    
    // Execute query
    $db->query($updateQuery) or die($db->error);
});

/**
 * Design manufacture services DELETE route.
 * 
 * @param  \Psr\Http\Message\ServerRequestInterface $req  PSR7 request
 * @param  \Psr\Http\Message\ResponseInterface      $res  PSR7 response
 * @param  array                                    $args Route parameters
 */
$app->delete('/delete/design-manufacture', function ($req, $res, $args) {
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
    $deleteQuery = sprintf("DELETE FROM `DesignManufacture` WHERE `name` = '%s'",
        $db->real_escape_string($body['item']));
    
    // Execute query
    $db->query($deleteQuery) or die($db->error);
});
?>
