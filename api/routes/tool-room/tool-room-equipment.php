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
?>
