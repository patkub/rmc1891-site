<?php
/**
 * Database connection info
 *
 * @author Patrick Kubiak
 */

const DB_INI_PATH = "/../../private/db.ini";

$ini = parse_ini_file(__DIR__ . DB_INI_PATH);
$db_host = $ini['host'];
$db_name = $ini['name'];
$db_user = $ini['user'];
$db_pass = $ini['pass'];
?>
