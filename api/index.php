<?php
require '../vendor/autoload.php';

// Create and configure Slim app
$config = ['settings' => [
    'addContentLengthHeader' => false,
]];
$app = new \Slim\App($config);

// Define app routes
$app->get('/json/list/{list}', function ($request, $response, $args) {
    return $response->write("Hello " . $args['list']);
});

// Run app
$app->run();
?>
