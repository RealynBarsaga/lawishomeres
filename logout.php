<?php
session_start();

// Check the user's status before proceeding with the session
include "connection.php";
$query = "SELECT status FROM tblstaff WHERE id = ?";
$stmt = $con->prepare($query);
$stmt->execute([$_SESSION['userid']]);
$status = $stmt->fetchColumn();

if ($status !== 'active') {
    // Redirect the user to the login page if they're not active
    session_unset();
    session_destroy();
    header('Location: ../../login.php');
    exit();
}

// Destroy all sessions
session_unset();
session_destroy();

// Redirect to login page
header("Location: login.php");
exit();
?>
