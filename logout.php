<?php
session_start();

// Example user ID
$userid = $_SESSION['userid'];

// Database credentials
$MySQL_username = "u510162695_db_barangay";
$Password = "1Db_barangay";
$MySQL_database_name = "u510162695_db_barangay";

// Establishing connection with server
$con = mysqli_connect('localhost', $MySQL_username, $Password, $MySQL_database_name);

// Checking connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Setting the default timezone
date_default_timezone_set("Asia/Manila");

// Update user session status to logged_out
$query = "UPDATE tblstaff SET status = 'logged_out' WHERE userid = ?";
$stmt = mysqli_prepare($con, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $userid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
} else {
    die("Error preparing statement: " . mysqli_error($con));
}

// Close the database connection
mysqli_close($con);

// Destroy the current session
session_unset();
session_destroy();

// Redirect to login page
header("Location: login.php");
exit();
?>