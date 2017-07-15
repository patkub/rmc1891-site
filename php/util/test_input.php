<?php
/**
 * Validate user input.
 *
 * @author Patrick Kubiak
 */

// validate input
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;  
}
?>
