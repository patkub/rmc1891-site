<?php
/**
 * Update Feature Cards
 *
 * @author Patrick Kubiak
 */

// Start php session
session_start();
 
// Database info
require_once('../../db.php');
 
// Validate user input
require_once('../../util/test_input.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
    isset($_SESSION['auth']) && $_SESSION['auth'] == true) {
    $db = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($db->connect_errno == 0) {
        // successfully connected to MySQL server
        
        // validate & store input
        $text = test_input($_POST['text']);
        
        // update queries
        $UpdateQuery = sprintf("UPDATE Text SET text='%s' WHERE `name` = 'about'",
            $db->real_escape_string($text));
        
        // execute query
        $db->query($UpdateQuery) or die($db->error);
    }
}
?>
