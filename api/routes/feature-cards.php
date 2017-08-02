<?php
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
 * Feature cards PUT route.
 * 
 * @param  \Psr\Http\Message\ServerRequestInterface $req  PSR7 request
 * @param  \Psr\Http\Message\ResponseInterface      $res  PSR7 response
 * @param  array                                    $args Route parameters
 */
$app->put('/put/feature-cards', function ($req, $res, $args) {
    // Check authentication
    if (!isset($_SESSION['auth']) || $_SESSION['auth'] != true) {
      // Unauthorized HTTP 401
      return adminAuthError($res);
    }
    
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
?>
