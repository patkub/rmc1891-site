<?php
/**
 * Delete from Equipment List
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
        $count = test_input($_POST['count']);
        $weight = test_input($_POST['weight']);
        $name = test_input($_POST['name']);
        
        // update query
        $UpdateQuery = sprintf("DELETE FROM `EquipmentList` WHERE (`count` = %d AND `weight` = %d AND `name` = '%s')",
            intval($count), intval($weight), $db->real_escape_string($name));
        
        // execute query
        $db->query($UpdateQuery) or die($db->error);
    }
}
?>
