<?php
/*
 * Admin login 
 * 
 */

// Admin username
$admin_user = "admin";

// Database info
$db_host = "db688129345.db.1and1.com";
$db_name = "db688129345";
$db_user = "dbo688129345";
$db_pass = "Superpatryk123";

// Connect to database
$db = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($db->connect_errno == 0) {
    // Get admin password
    $admin_pass_query = sprintf("SELECT `admin_pass` FROM `Admin` WHERE `admin_user` = '%s'",
            $db->real_escape_string($admin_user));
    $result = $db->query($admin_pass_query) or die($db->error);
    $row = $result->fetch_assoc();
    $admin_pass = $row["admin_pass"];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get login username and password
    $user = test_input($_POST['username']);
	$pass = test_input($_POST['password']);
    
    // Check username and password
    if ($user === $admin_user && hash_equals($admin_pass, crypt($pass, $admin_pass))) {
        // logged successful!
        $_SESSION['auth'] = true;
        $response_array['status'] = 'success';
    } else {
        // login failed!
        $_SESSION['auth'] = false;
        $response_array['status'] = 'error';
    }
    
    // JSON response
    header('Content-type: application/json');
    echo json_encode($response_array);
}

// Validate input
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
