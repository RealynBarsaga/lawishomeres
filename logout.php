<?php
session_start();

// Ensure the session is active before attempting to destroy it
if (isset($_SESSION['userid'])) {
    // Clear all session variables
    session_unset();

    // Destroy the session itself
    session_destroy();

    // Clear any session cookies (optional but recommended)
    if (isset($_COOKIE[session_name()])) {
        // Expire the session cookie by setting the expiration to one hour ago
        setcookie(session_name(), '', time() - 3600, '/'); // Make sure the path is set correctly
    }

    // Optionally, invalidate the session token (if used)
    unset($_SESSION['session_token']);
}

// Redirect to the login page
header("Location: login.php");
exit();
?>
