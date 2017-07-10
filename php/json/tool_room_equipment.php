<?php
/*
 * Tool Room Equipment list ordered by descending weight
 * 
 */

// Database info
require_once('../db.php');

$db = new mysqli($db_host, $db_user, $db_pass, $db_name);
$results = array();

if ($db->connect_errno == 0) {
    $myQuery = "SELECT * FROM `ToolRoomEquipment` ORDER BY `ToolRoomEquipment`.`count` DESC";
    $result = $db->query($myQuery) or die($db->error);
    
    while ($row = $result->fetch_assoc()){
        $results[] = $row;
    }
    
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    echo json_encode($results);
}
?>