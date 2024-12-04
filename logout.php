<?php
session_start();
// Destroy the session on the current browser
session_unset();
session_destroy();

// Redirect the user to the login page
header("Location: login.php");
exit();

?>