<?php
/*
 * Feature Cards json data 
 * 
 */

$db_host = "db688129345.db.1and1.com";
$db_name = "db688129345";
$db_user = "dbo688129345";
$db_pass = "Superpatryk123";

$db = new mysqli($db_host, $db_user, $db_pass, $db_name);

$results = array();

if($db->connect_errno > 0){
    //die('<p>Failed to connect to MySQL db: ' . $db->connect_error . '</p>');
} else {
    //echo '<p>Connection to MySQL server successfully established.</p >';
    
    $myQuery = "SELECT * FROM FeatureCards";
    $result = $db->query($myQuery) or die($db->error);
    
    while ($row = $result->fetch_assoc()){
        $results[] = $row;
    }
    
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    echo json_encode($results);
}
?>
