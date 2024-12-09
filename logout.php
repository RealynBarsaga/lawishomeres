<?php
session_start(); // Start session

// Database credentials
$MySQL_username = "u510162695_db_barangay";
$Password = "1Db_barangay";    
$MySQL_database_name = "u510162695_db_barangay";

// Establish connection with server
$con = mysqli_connect('localhost', $MySQL_username, $Password, $MySQL_database_name);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve user details from session
$userid = $_SESSION['userid'];
$off_barangay = $_SESSION['barangay'];

// Update the user's status in the database to 'logged_out' if barangay matches
$query = "SELECT barangay FROM tblstaff WHERE userid = '$userid'";
$result = mysqli_query($con, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);

    // Only update the status if the barangay matches
    if ($row['barangay'] === $off_barangay) {
        $update_query = "UPDATE tblstaff SET status = 'logged_out' WHERE userid = '$userid'";
        mysqli_query($con, $update_query);
    }
}

// Destroy the session regardless
session_unset();
session_destroy();

// Redirect to the login page
header("Location: ../../login.php");
exit();
?>