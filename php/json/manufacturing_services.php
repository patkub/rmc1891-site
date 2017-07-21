<?php
/**
 * Manufacturing Services Json Data
 * Sorted in alphabetical order
 *
 * @author Patrick Kubiak
 */

// Database info
require_once('../db.php');

$db = new mysqli($db_host, $db_user, $db_pass, $db_name);
$results = array();

if ($db->connect_errno == 0) {
    // successfully connected to MySQL server
    
    $myQuery = "SELECT * FROM `ManufacturingServices` ORDER BY `ManufacturingServices`.`name` ASC";
    $result = $db->query($myQuery) or die($db->error);
    
    while ($row = $result->fetch_assoc()){
        $results[] = $row;
    }
    
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    echo json_encode($results);
}
?>