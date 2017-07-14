<?php
/**
 * Start php session.
 *
 * @author Patrick Kubiak
 */

// start php session
function start_session() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}
?>


