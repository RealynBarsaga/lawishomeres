<?php
// Initialize variables
$error_message = '';
$success_message = '';
$email = '';

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

// Load PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Check for any error message
if (empty($error_message)) {
    // Load Composer's autoloader for PHPMailer
    require 'vendor/autoload.php';

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
        $mail->Subject = 'Password Reset';
        $mail->Body = '
            <html>
            <head>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f4f4f4;
                        margin: 0;
                        padding: 0;
                    }
                    .email-container {
                        background-color: #ffffff;
                        width: 100%;
                        max-width: 600px;
                        padding: 20px;
                        border: 1px solid #ddd;
                        border-radius: 5px;
                        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    }
                    h2 {
                        color: #333;
                        font-size: 24px;
                        margin-top: 0;
                    }
                    p {
                        color: #555;
                        font-size: 15px;
                        line-height: 1.6;
                        margin-bottom: 20px;
                    }
                    .button {
                        background-color: #dc3545;
                        border-color: #dc3545;
                        padding: 12px 20px;
                        text-decoration: none;
                        border-radius: 5px;
                        display: inline-block;
                        font-size: 16px;
                        margin: 10px 0;
                    }
                    .button:hover {
                        background-color: #dc3545;
                    }
                </style>
            </head>
            <body>
                <div class="email-container">
                    <h2>Password Reset Request</h2>
                    <h3 style="font-weight: bold;">Dear User,</h3>
                    <p>We received a request to reset your password.</p>
                    <p>Your One-Time Password (OTP) is: <strong>' . $otp . '</strong></p>
                    <p>The OTP will expire in 5 minutes.</p>
                    <p>To verify your OTP, click the link below:</p>
                    <p><a href="http://localhost/mhrmsystem/email_otp_verification.php?email=' . htmlspecialchars(stripslashes(trim($email))) . '&otp=' . htmlspecialchars(stripslashes(trim($otp))) . '" class="button" style="color: #fff;">Verify OTP</a></p>
                    <p>If you did not request this, please ignore this email.</p>
                </div>
            </body>
            </html>
        ';

        // Database connection
        $host = 'localhost';
        $username = 'root';
        $password = '';
        $database = 'db_barangay';

        $conn = new mysqli($host, $username, $password, $database);

        if ($conn->connect_error) {
            $error_message = 'Connection failed: ' . htmlspecialchars($conn->connect_error);
        } else {
            // Check if the email exists in the database
            $stmt = $conn->prepare("SELECT * FROM tblstaff WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // Generate OTP and store it in the database with expiry time (5 minutes)
                $otp_expiry = date('Y-m-d H:i:s', time() + 300);  // 5 minutes expiry
                $stmt = $conn->prepare("UPDATE tblstaff SET otp = ?, otp_expiry = ? WHERE email = ?");
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
            $conn->close();
        }
    } catch (Exception $e) {
        $error_message = "Message could not be sent. Mailer Error: " . htmlspecialchars($mail->ErrorInfo);
    }
}

// Redirect with success or error message
session_start();
$_SESSION['error_message'] = $error_message;
$_SESSION['success_message'] = $success_message;
header("Location: email_otp_form.php");
exit();
?>