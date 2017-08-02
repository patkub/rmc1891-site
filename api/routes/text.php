<?php
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
 * Text PUT route.
 * 
 * @param  \Psr\Http\Message\ServerRequestInterface $req  PSR7 request
 * @param  \Psr\Http\Message\ResponseInterface      $res  PSR7 response
 * @param  array                                    $args Route parameters
 * args['text'] array Defines the text to get, 'about', 'capabilities', or 'contact' text.
 * 
 * @return \Psr\Http\Message\ResponseInterface
 */
$app->put('/put/text/{text}', function ($req, $res, $args) {
    // Check authentication
    if (!isset($_SESSION['auth']) || $_SESSION['auth'] != true) {
      // Unauthorized HTTP 401
      return adminAuthError($res);
    }
    
    // Connect to MySQL database
    $db = $this->get('myDb');
    
    // Get request body text
    $body = $req->getParsedBody();
    
    // Format update query
    $updateQuery = sprintf("UPDATE `Text` SET text='%s' WHERE `name`='%s'",
        $db->real_escape_string($body['text']), $db->real_escape_string($args['text']));
    
    // Execute query
    $db->query($updateQuery) or die($db->error);
});
?>
