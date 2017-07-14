<?php
/*
 * Capabilities text
 * 
 */

// Database info
require_once('../../db.php');

$db = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($db->connect_errno == 0) {
    // successfully connected to MySQL server
    
    $myQuery = "SELECT `text` FROM `Text` WHERE `name` = 'capabilities'";
    $result = $db->query($myQuery) or die($db->error);
    $row = $result->fetch_assoc();
    
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    echo json_encode($row['text']);
}
?>
