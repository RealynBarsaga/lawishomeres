<?php
// Initialize variables
$error_message = '';
$success_message = '';
$email = '';

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

if (isset($_POST['reset'])) {
    $email = trim($_POST['email']);
    
    // Validate email
    if (empty($email)) {
        $error_message = 'Please enter your email.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Invalid email format.';
    }
} else {
    $error_message = 'No form submitted.';
}

// Load PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

// Check for any error message
if (empty($error_message)) {
    // Load Composer's autoloader for PHPMailer
    require '../vendor/autoload.php';

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'sevillejogilbert15@gmail.com';
        $mail->Password   = 'pbgfszjxplakhcxb';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('sevillejogilbert15@gmail.com', 'lawishomeresidences.com');
        $mail->addAddress($email);

        $code = substr(str_shuffle('1234567890QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm'), 0, 10);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset';
        $mail->Body    = '<b>Dear User</b>
        <p>We received a request to reset your password.</p>
        <p>To reset your password, please click the following link: 
        <a href="https://lawishomeres.com/admin/reset-password.php?code=' . htmlspecialchars(stripslashes(trim($code))) . '">Reset Password</a></p>';

        // Prepared statement for verifying if the email exists
        $stmt = $con->prepare("SELECT * FROM tbluser WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Prepared statement for updating the code
            $stmt = $con->prepare("UPDATE tbluser SET code = ? WHERE email = ?");
            $stmt->bind_param("ss", $code, $email);

            if ($stmt->execute()) {
                $mail->send();
                $success_message = 'Message has been sent, please check your email - ' . htmlspecialchars(stripslashes(trim($email)));
            } else {
                $error_message = 'Failed to update the reset code.';
            }
        } else {
            $error_message = 'Email not found.';
        }

        $stmt->close();
    } catch (Exception $e) {
        $error_message = "Message could not be sent. Mailer Error: " . htmlspecialchars(stripslashes(trim($mail->ErrorInfo)));
    }
}

// Redirect with success or error message
session_start();
$_SESSION['error_message'] = $error_message;
$_SESSION['success_message'] = $success_message;
header("Location: forgot_password.php");
exit();
?>