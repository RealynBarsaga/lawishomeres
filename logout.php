<?php
session_start();
require 'connection.php'; // Include your database connection

if (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid'];

    // Clear the session token in the database
    $stmt = $pdo->prepare("UPDATE tblstaff SET session_token = NULL, barangay = NULL WHERE id = ?");
    $stmt->execute([$userid]);
}

// Destroy the session
session_destroy();

// Redirect to the login page
header("Location: login.php");
exit;
?>
