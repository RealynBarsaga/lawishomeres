<?php
session_start();

// Ensure the session is active before attempting to destroy it
if (isset($_SESSION['userid'])) {
    // Destroy all session variables
    session_unset();

    // Destroy the session itself
    session_destroy();

    // Clear any session cookies (optional but recommended)
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/'); // Expire the cookie
    }

    // Optionally, invalidate the session token (if used)
    // This is handled by session destruction, but explicitly nullifying the session token is a good practice.
    unset($_SESSION['session_token']);
}

// Redirect to login page
header("Location: login.php");
exit();
?>
