<?php
// Validate user input
require_once '../lib/test_input.php';

/**
 * Mail POST route.
 * 
 * @param  \Psr\Http\Message\ServerRequestInterface $req  PSR7 request
 * @param  \Psr\Http\Message\ResponseInterface      $res  PSR7 response
 * @param  array                                    $args Route parameters
 * 
 * @return \Psr\Http\Message\ResponseInterface
 */
$app->post('/post/mail/', function ($req, $res, $args) {
    // Get request body text
    $body = $req->getParsedBody();

    // Get input
    $email = test_input($_POST['email']);
    $subject = test_input($_POST['subject']);
    $message = test_input($_POST['message']);

    // Email headers
    $headers = "From: " . $email . PHP_EOL;
    
    if (mail('pk9948@g.rit.edu', $subject, $message, $headers)) {
        // Email sent successfully
        $response_array['status'] = 'success';
    } else {
        // Email failed, error!
        $response_array['status'] = 'error';
    }
    
    // Return JSON
    header('Access-Control-Allow-Origin: *');
    return $res->withJson($response_array);
});
?>
