<?php
session_start();

// Destroy all sessions
session_unset();
session_destroy();

// Invalidate session cookie
setcookie(session_name(), '', time() - 3600, '/', 'lawishomeresidences.com', true, true);

header("Location: login.php");
exit();
?>
