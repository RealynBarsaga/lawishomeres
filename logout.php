<?php
session_start();

// Destroy all sessions
session_unset();
session_destroy();

// Optionally, delete the session cookie as well
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Redirect to login page
header("Location: login.php");
exit();
?>
