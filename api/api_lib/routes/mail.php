<?php
/**
 * Mail POST route.
 * 
 * @param  \Psr\Http\Message\ServerRequestInterface $req  PSR7 request
 * @param  \Psr\Http\Message\ResponseInterface      $res  PSR7 response
 * @param  array                                    $args Route parameters
 * 
 * @return \Psr\Http\Message\ResponseInterface
 */
$app->post('/post/mail', function ($req, $res, $args) {
    // Get input
    $body = $req->getParsedBody();
    $email = test_input($body['email']);
    $subject = "[rmc1891.com] " . test_input($body['subject']);
    $message = test_input($body['message']);
    
    // Get site email address
    $site_email = $this->get('email');
    
    // Email headers
    $headers = "From: " . $email . PHP_EOL;
    
    // Send email
    if (mail($site_email, $subject, $message, $headers)) {
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
