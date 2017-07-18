<?php
/**
 * Database connection info
 *
 * @author Patrick Kubiak
 */
 
$ini = parse_ini_file("../../private/db.ini");

$db_host = $ini['host'];
$db_name = $ini['name'];
$db_user = $ini['user'];
$db_pass = $ini['pass'];
?>
