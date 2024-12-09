<?php
session_start();

// Destroy all sessions
session_unset();
session_destroy();

// Optionally, delete the session cookie across all subdomains
$cookieParams = session_get_cookie_params();

// If you're working across subdomains, ensure the cookie is valid for the entire domain
setcookie(session_name(), '', time() - 3600, $cookieParams['path'], '.lawishomeresidences.com', $cookieParams['secure'], $cookieParams['httponly']);

// You can also log out from other services if needed by destroying their cookies

// Redirect to login page after logout
header("Location: login.php");
exit();
?>
