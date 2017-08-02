<?php
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
 * Tool room equipment PUT route.
 * 
 * @param  \Psr\Http\Message\ServerRequestInterface $req  PSR7 request
 * @param  \Psr\Http\Message\ResponseInterface      $res  PSR7 response
 * @param  array                                    $args Route parameters
 */
$app->put('/put/tool-room/equipment', function ($req, $res, $args) {
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
    $updateQuery = sprintf("INSERT INTO `ToolRoomEquipment`(`count`, `name`) VALUES (%d, '%s')",
        intval($body['count']), $db->real_escape_string($body['item']));
    
    // Execute query
    $db->query($updateQuery) or die($db->error);
});

/**
 * Tool room equipment DELETE route.
 * 
 * @param  \Psr\Http\Message\ServerRequestInterface $req  PSR7 request
 * @param  \Psr\Http\Message\ResponseInterface      $res  PSR7 response
 * @param  array                                    $args Route parameters
 */
$app->delete('/delete/tool-room/equipment', function ($req, $res, $args) {
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
    $deleteQuery = sprintf("DELETE FROM `ToolRoomEquipment` WHERE (`count` = %d AND `name` = '%s')",
        intval($body['count']), $db->real_escape_string($body['item']));
    
    // Execute query
    $db->query($deleteQuery) or die($db->error);
});
?>
