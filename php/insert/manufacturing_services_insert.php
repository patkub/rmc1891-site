<?php
/**
 * Insert into Manufacturing Services List
 *
 * @author Patrick Kubiak
 */

// Database info
require_once('../db.php');
 
// Validate user input
require_once('../util/test_input.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['auth']) {
    $db = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($db->connect_errno == 0) {
        // successfully connected to MySQL server
        
        // validate & store input
        $service = test_input($_POST['item']);
        
        // update query
        $UpdateQuery = sprintf("INSERT INTO `ManufacturingServices`(`name`) VALUES ('%s')",
            $db->real_escape_string($service));
        
        // execute query
        $db->query($UpdateQuery) or die($db->error);
    }
}
?>
