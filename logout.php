<?php
session_start();
include('connection.php'); // Make sure to have your DB connection here

if (isset($_SESSION['session_token'])) {
    $session_token = $_SESSION['session_token'];
    $userid = $_SESSION['userid'];
    
    // Mark the session as inactive in the database
    $stmt = $conn->prepare("UPDATE tblstaff SET status = 'logged_out' WHERE userid = ? AND session_token = ?");
    $stmt->bind_param("is", $userid, $session_token);
    $stmt->execute();
    $stmt->close();
    
    // Destroy the session in PHP
    session_unset();
    session_destroy();
    
    // Redirect to the login page
    header("Location: login.php");
    exit();
} else {
    // If no session is found, redirect to login
    header("Location: login.php");
    exit();
}
?>
