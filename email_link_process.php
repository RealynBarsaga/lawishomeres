<?php
// Start session at the very beginning of the file
session_start();

// Initialize variables
$error_message = '';
$success_message = '';
$email = '';

// Database credentials
$MySQL_username = "u510162695_db_barangay";
$Password = "1Db_barangay";    
$MySQL_database_name = "u510162695_db_barangay";

// Establishing connection with the server
$con = mysqli_connect('localhost', $MySQL_username, $Password, $MySQL_database_name);

// Checking connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Setting the default timezone
date_default_timezone_set("Asia/Manila");

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Load PHPMailer classes (correct file paths and namespaces)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


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

// Check for any error message
if (empty($error_message)) {

    require 'vendor/autoload.php'; // Make sure to load Composer autoloader
    
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
        $mail->setFrom('no-reply@example.com', 'lawishomeresidences');
        $mail->addAddress($email);

        // Generate the reset code
        $code = substr(str_shuffle('1234567890QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm'), 0, 10);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body    = '<b>Dear User</b>
        <p>We received a request to reset your password.</p>
        <p>To reset your password, please click the following link: 
        <a href="https://lawishomeresidences.com/reset-password.php?code=' . htmlspecialchars($code) . '">Reset Password</a></p>
        <p>If you did not request this, please ignore this email.</p>';
        
        // Prepared statement for verifying if the email exists
        $stmt = $con->prepare("SELECT * FROM tblstaff WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Prepared statement for updating the code
            $stmt = $con->prepare("UPDATE tblstaff SET code = ? WHERE email = ?");
            $stmt->bind_param("ss", $code, $email);

            if ($stmt->execute()) {
                // Send the email
                $mail->send();
                $success_message = 'Message has been sent. Please check your email - ' . htmlspecialchars($email);
            } else {
                $error_message = 'Failed to update the reset code.';
            }
        } else {
            $error_message = 'Email not found.';
        }

        $stmt->close();
    } catch (Exception $e) {
        $error_message = "Message could not be sent. Mailer Error: " . htmlspecialchars($mail->ErrorInfo);
    }
}

// Use JavaScript for redirect
echo "<script>
    window.location.href = 'email_link_form.php';
</script>";
exit();
?>