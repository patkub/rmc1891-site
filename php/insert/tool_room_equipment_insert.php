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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($db->connect_errno == 0) {
        // successfully connected to MySQL server
        
        // validate & store input
        $count = test_input($_POST['count']);
        $machine = test_input($_POST['machine']);
        
        // update query
        $UpdateQuery = sprintf("INSERT INTO `ToolRoomEquipment`(`count`, `name`) VALUES (%d, '%s')",
            intval($count), $db->real_escape_string($machine));
        
        // execute query
        $db->query($UpdateQuery) or die($db->error);
    }
}
?>
