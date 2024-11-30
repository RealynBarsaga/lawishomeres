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

// Load PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['sendotp'])) {
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

        // Generate a 6-digit OTP
        $otp = mt_rand(100000, 999999);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body    = '<b>Dear User</b>
        <p>We received a request to reset your password.</p>
        <p>Your One-Time Password (OTP) is: <strong>' . $otp . '</strong></p>
        <p>To reset your password, please click the following link:
        <p>The OTP will expire in 5 minutes.</p>
        <p>To verify your OTP, click the link below:</p><br>
        <a href="http://lawishomeresidences.com/email_otp_verification.php?email=' . htmlspecialchars(stripslashes(trim($email))) . '&otp=' . htmlspecialchars(stripslashes(trim($otp))) . '">Reset Password</a></p>
        <p>If you did not request this, please ignore this email.</p>';


        // Check if the email exists in the database
        $stmt = $con->prepare("SELECT * FROM tblstaff WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
 
        if ($stmt->num_rows > 0) {
            // Generate OTP and store it in the database with expiry time (5 minutes)
            $otp_expiry = date('Y-m-d H:i:s', time() + 300);  // 5 minutes expiry
            $stmt = $con->prepare("UPDATE tblstaff SET otp = ?, otp_expiry = ? WHERE email = ?");
            $stmt->bind_param("sss", $otp, $otp_expiry, $email);
 
            if ($stmt->execute()) {
                // Send OTP email
                $mail->send();
                $success_message = 'OTP has been sent to your email - ' . htmlspecialchars(stripslashes(trim($email)));
            } else {
                $error_message = 'Failed to store OTP in the database.';
            }
        } else {
            $error_message = 'Email not found.';
        }

        $stmt->close();
    } catch (Exception $e) {
        $error_message = "Message could not be sent. Mailer Error: " . htmlspecialchars($mail->ErrorInfo);
    }

$_SESSION['error_message'] = $error_message;
$_SESSION['success_message'] = $success_message;

// Use JavaScript for redirect
echo "<script>
    window.location.href = 'email_otp_form.php';
</script>";
exit();
}
?>