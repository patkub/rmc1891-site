<?php
/*
 * Feature Cards json data 
 * 
 */

$db_host = "db688129345.db.1and1.com";
$db_name = "db688129345";
$db_user = "dbo688129345";
$db_pass = "Superpatryk123";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $db = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if($db->connect_errno > 0){
        //die('<p>Failed to connect to MySQL db: ' . $db->connect_error . '</p>');
    } else {
        //echo '<p>Connection to MySQL server successfully established.</p >';
        
        // validate & store input
        $AboutText = test_input($_POST['AboutText']);
        $CapabilitiesText = test_input($_POST['CapabilitiesText']);
        $ContactText = test_input($_POST['ContactText']);
        
        // update queries
        $AboutQuery = sprintf("UPDATE FeatureCards SET text='%s' WHERE title='About'",
            mysqli_escape_string($AboutText));
        $CapabilitiesQuery = sprintf("UPDATE FeatureCards SET text='%s' WHERE title='Capabilities'",
            mysqli_escape_string($CapabilitiesText));
        $ContactQuery = sprintf("UPDATE FeatureCards SET text='%s' WHERE title='Contact'",
            mysqli_escape_string($ContactText));
        
        // execute queries
        mysql_query($AboutQuery);
        mysql_query($CapabilitiesQuery);
        mysql_query($ContactQuery);
    }
    
}

// validate input
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
