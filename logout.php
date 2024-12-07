<?php
// Securely start the session
session_set_cookie_params([
    'lifetime' => 0,              // Session cookie (expires when the browser is closed)
    'path' => '/',                // Available across the entire domain
    'domain' => 'lawishomeresidences.com', // Change this to your domain
    'secure' => true,             // Set to true if using HTTPS
    'httponly' => true,           // Prevent JavaScript access to the cookie
    'samesite' => 'Lax'           // Use 'Lax' or 'Strict' based on your needs
]);

session_start(); // Start the session

// Destroy session variables and session
session_unset(); // Clear all session data
session_destroy(); // Completely destroy the session

// Redirect the user to the login page
header('Location: login.php');
exit();
?>
