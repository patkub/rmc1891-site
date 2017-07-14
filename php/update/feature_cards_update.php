<?php
/**
 * Update Feature Cards
 *
 * @author Patrick Kubiak
 */

// Start php session
session_start();
 
// Database info
require_once('../db.php');
 
// Validate user input
require_once('../util/test_input.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
    isset($_SESSION['auth']) && $_SESSION['auth'] == true) {
    $db = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($db->connect_errno == 0) {
        // successfully connected to MySQL server
        
        // validate & store input
        $AboutText = test_input($_POST['AboutText']);
        $CapabilitiesText = test_input($_POST['CapabilitiesText']);
        $ContactText = test_input($_POST['ContactText']);
        
        // update queries
        $AboutQuery = sprintf("UPDATE FeatureCards SET text='%s' WHERE title='About'",
            $db->real_escape_string($AboutText));
        $CapabilitiesQuery = sprintf("UPDATE FeatureCards SET text='%s' WHERE title='Capabilities'",
            $db->real_escape_string($CapabilitiesText));
        $ContactQuery = sprintf("UPDATE FeatureCards SET text='%s' WHERE title='Contact'",
            $db->real_escape_string($ContactText));
        
        // execute queries
        $db->query($AboutQuery) or die($db->error);
        $db->query($CapabilitiesQuery) or die($db->error);
        $db->query($ContactQuery) or die($db->error);
        
        // redirect user back to admin page
        header("Location: https://therogersmanufacturingcompany.com/admin/");
    }
}
?>
