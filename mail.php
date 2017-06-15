<?php

//
// dshoemaker@rmc1891.com
// ebitel@rmc1891.com
//
// reCAPTCHA
// Site key: 6LclliUUAAAAAPfZNA39V6DGentmvLWm4ySabVVx
// Secret key: 6LclliUUAAAAAP1LPJJa81I6yrW4V0B43fMo4Ouk
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

// validate input
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
