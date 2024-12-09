<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Optionally, delete session cookies across all subdomains and paths
$cookieParams = session_get_cookie_params();
$domain = 'lawishomeresidences.com'; // Set for the entire domain (including subdomains)

// Set the session cookie to expire
setcookie(session_name(), '', time() - 3600, $cookieParams['path'], $domain, $cookieParams['secure'], $cookieParams['httponly']);

// You can also clear other cookies if you are using different login systems or third-party cookies
// Example: Clearing a "remember me" cookie or similar
setcookie("user_session", "", time() - 3600, "/", $domain, true, true); // Make sure to clear custom cookies too

// Redirect to login page after successful logout
header("Location: login.php");
exit();
?>
