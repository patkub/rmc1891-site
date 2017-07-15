<?php
/**
 * Update Capabilities text
 *
 * @author Patrick Kubiak
 */

// Start php session
session_start();
 
// Database info
require_once('../../db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
    isset($_SESSION['auth']) && $_SESSION['auth'] == true) {
    $db = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($db->connect_errno == 0) {
        // successfully connected to MySQL server
        
        // validate & store input
        $text = $_POST['text'];
        
        // update queries
        $UpdateQuery = sprintf("UPDATE Text SET text='%s' WHERE `name` = 'capabilities'",
            $db->real_escape_string($text));
        
        // execute query
        $db->query($UpdateQuery) or die($db->error);
    }
}
?>
