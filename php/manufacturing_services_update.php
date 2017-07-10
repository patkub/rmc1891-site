<?php
/*
 * Update Manufacturing Services
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
        $service = test_input($_POST['ManufacturingService']);
        
        // update query
        $UpdateQuery = sprintf("INSERT INTO `ManufacturingServices`(`name`) VALUES ('%s')",
            $db->real_escape_string($service));
        
        // execute query
        $db->query($UpdateQuery) or die($db->error);
        
        // redirect user back to admin page
        header("Location: https://therogersmanufacturingcompany.com/admin/");
    }
}
?>
