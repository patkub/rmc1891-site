<?php
/**
 * Login POST route.
 * 
 * @param  \Psr\Http\Message\ServerRequestInterface $req  PSR7 request
 * @param  \Psr\Http\Message\ResponseInterface      $res  PSR7 response
 * @param  array                                    $args Route parameters
 * 
 * @return \Psr\Http\Message\ResponseInterface
 */
$app->post('/post/login', function ($req, $res, $args) {
  // Connect to MySQL database
  $db = $this->get('myDb');
	
  // Get admin username
  $admin_user = $this->get('admin_user');
  
	// Get admin password
  $query = sprintf("SELECT `admin_pass` FROM `Admin` WHERE `admin_user` = '%s'",
      $db->real_escape_string($admin_user));
	$row = queryAndFetch($db, $query);
  $admin_pass = $row["admin_pass"];
  
	// Get attempted username and password
  $body = $req->getParsedBody();
	$user = $body['username'];
	$pass = $body['password'];
	
	// Check username and password
  if ($user === $admin_user && hash_equals($admin_pass, crypt($pass, $admin_pass))) {
      // logged in successfully
      $_SESSION['auth'] = true;
      $response_array['status'] = 'success';
  } else {
      // login failed!
      $_SESSION['auth'] = false;
      $response_array['status'] = 'error';
  }

  // Return JSON
  header('Access-Control-Allow-Origin: *');
  return $res->withJson($response_array);
});

/**
 * Logout POST route.
 * 
 * @param  \Psr\Http\Message\ServerRequestInterface $req  PSR7 request
 * @param  \Psr\Http\Message\ResponseInterface      $res  PSR7 response
 * @param  array                                    $args Route parameters
 * 
 * @return \Psr\Http\Message\ResponseInterface
 */
$app->post('/post/logout', function ($req, $res, $args) {
  // Logout the user
  $_SESSION['auth'] = false;
});
?>
