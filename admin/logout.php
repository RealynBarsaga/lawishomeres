<?php
session_start();

// Retrieve user ID from session
$userid = $_SESSION['userid'];

// Database credentials
$MySQL_username = "u510162695_db_barangay";
$Password = "1Db_barangay";
$MySQL_database_name = "u510162695_db_barangay";

// Establish connection with the server
$con = mysqli_connect('localhost', $MySQL_username, $Password, $MySQL_database_name);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set the default timezone
date_default_timezone_set("Asia/Manila");

// Update user session status to 'logged_out'
$query = "UPDATE tbluser SET status = 'logged_out' WHERE userid = ?";
$stmt = mysqli_prepare($con, $query);

if ($stmt) {
    // Bind the parameter and execute the statement
    mysqli_stmt_bind_param($stmt, "s", $userid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
} else {
    // Handle errors during statement preparation
    die("Error preparing statement: " . mysqli_error($con));
}

// Close the database connection
mysqli_close($con);

// Destroy the current session
session_unset();
session_destroy();

// Redirect the user to the login page
header("Location: login.php");
exit();
?>
