<?php
session_start();
$session_id = session_id();

// Mark the session as logged out in the database
$query = "UPDATE user_sessions SET logged_out = 1 WHERE session_id = '$session_id'";
// Execute the query (use prepared statements to avoid SQL injection)

// Set the global logout cookie when the user logs out
setcookie('global_logout', '1', time() + 3600, '/'); // 1 hour expiry or make it longer

// Destroy the session on the current browser
session_unset();
session_destroy();

// Redirect the user to the login page
header("Location: login.php");
exit();

?>