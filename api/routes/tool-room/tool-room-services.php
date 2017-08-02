<?php
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
?>
