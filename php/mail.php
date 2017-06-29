<?php

// Validate user input
require_once('test_input.php');

//
// dshoemaker@rmc1891.com
// ebitel@rmc1891.com
//

// recipient address
$to = 'pk9948@g.rit.edu';
$email = $subject = $message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$email = test_input($_POST['email']);
	$subject = test_input($_POST['subject']);
	$message = test_input($_POST['message']);
	
	// email headers
	$headers = "From: " . $email . PHP_EOL;
	
	// send the email
	mail($to, $subject, $message, $headers) or die('Error!');
}
?>
