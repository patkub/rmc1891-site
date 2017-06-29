<?php
/*
 * Admin login 
 * 
 */

$admin_user = "admin";
$admin_pass = "Superpatryk123";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $user = test_input($_POST['username']);
	$pass = test_input($_POST['password']);
    
    if ($user === $admin_user && $pass === $admin_pass) {
        // logged in!
        $_SESSION['auth'] = true;
        $response_array['status'] = 'success';
    } else {
        $_SESSION['auth'] = false;
        $response_array['status'] = 'error';
    }
    
    header('Content-type: application/json');
    echo json_encode($response_array);
}

// validate input
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
