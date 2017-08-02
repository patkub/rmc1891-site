<?php
/**
 * Send Email
 *
 * @author Patrick Kubiak
 */

// Validate user input
require_once('util/test_input.php');

$email = $subject = $message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // get input
	$email = test_input($_POST['email']);
	$subject = test_input($_POST['subject']);
	$message = test_input($_POST['message']);
	
	// email headers
	$headers = "From: " . $email . PHP_EOL;
	
	// send the email
    //
    // TODO: change recipients
    //
    // dshoemaker@rmc1891.com
    // ebitel@rmc1891.com
    //
	if (mail('pk9948@g.rit.edu', $subject, $message, $headers)) {
        // email sent successfully
        $response_array['status'] = 'success';
    } else {
        // email failed, error!
        $response_array['status'] = 'error';
    }
    
    // JSON response
    header('Content-type: application/json');
    echo json_encode($response_array);
}
?>
