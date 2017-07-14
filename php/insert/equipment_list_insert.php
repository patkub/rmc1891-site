<?php
/**
 * Insert into Tool Room Equipment List
 *
 * @author Patrick Kubiak
 */

// Database info
require_once('../db.php');
 
// Validate user input
require_once('../util/test_input.php');

// Start php session
require_once('../util/session_start.php');
start_session();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['auth']) {
    $db = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($db->connect_errno == 0) {
        // successfully connected to MySQL server
        
        // validate & store input
        $count = test_input($_POST['count']);
        $weight = test_input($_POST['weight']);
        $name = test_input($_POST['name']);
        
        // update query
        $UpdateQuery = sprintf("INSERT INTO `EquipmentList`(`count`, `weight`, `name`) VALUES (%d, %d, '%s')",
            intval($count), intval($weight), $db->real_escape_string($name));
        
        // execute query
        $db->query($UpdateQuery) or die($db->error);
    }
}
?>
