<?php
/*
 * Update Tool Room Equipment List
 * 
 */

// Database info
require_once('db.php');
 
// Validate user input
require_once('util/test_input.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($db->connect_errno == 0) {
        //echo '<p>Connection to MySQL server successfully established.</p >';
        
        // validate & store input
        $count = test_input($_POST['Count']);
        $item = test_input($_POST['Item']);
        
        // update query
        $UpdateQuery = sprintf("INSERT INTO `ToolRoomEquipment`(`count`, `name`) VALUES (%s, %s)",
            $db->real_escape_string($count), $db->real_escape_string($name));
        
        // execute query
        $db->query($UpdateQuery) or die($db->error);
        
        // redirect user back to admin page
        header("Location: https://therogersmanufacturingcompany.com/admin/");
    }
}
?>
