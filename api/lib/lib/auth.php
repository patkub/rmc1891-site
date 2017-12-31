<?php
// Unauthorized HTTP response for admin functionality
function adminAuthError($res) {
  // Unauthorized HTTP 401
  $newRes = $res->withHeader('WWW-Authenticate', 'Basic realm="Access to admin functionality"');
  return $newRes->withStatus(401);
}
?>
