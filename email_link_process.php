<?php
session_start();

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


// Initialize variables
$error_message = '';
$success_message = '';
$email = '';

// Check if the form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the 'reset' button was clicked (this will indicate the form submission)
    if (isset($_POST['reset'])) {
        $email = trim($_POST['email']);
        
        // Validate the email
        if (empty($email)) {
            $error_message = 'Please enter your email.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message = 'Invalid email format.';
        }
        
        // If no validation errors, you can proceed with processing the email
        // For example, you could send a reset link or save data to the database
        if (empty($error_message)) {
            // Proceed with the email processing, e.g., sending a password reset link
            // Example:
            // mail($email, "Password Reset Link", "Here is your reset link...");
            
            // Optionally, set a success message
            $success_message = 'A password reset link has been sent to your email address.';
        }
    }
} else {
    // This block is executed if no POST request was made
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

        $code = substr(str_shuffle('1234567890QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm'), 0, 10);

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
                    <p>To reset your password, please click the button below:</p>
                    <a href="http://lawishomeres.com/reset-password.php?code=' . htmlspecialchars(stripslashes(trim($code))) . '" class="button" style="color: #fff;">Reset Password</a>
                    <p>If you did not request this, please ignore this email.</p>
                </div>
            </body>
            </html>
        ';

        // Database query to check if the email exists
        $stmt = $con->prepare("SELECT * FROM tblstaff WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Prepared statement for updating the reset code
            $stmt = $con->prepare("UPDATE tblstaff SET code = ? WHERE email = ?");
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
        $con->close();
    } catch (Exception $e) {
        $error_message = "Message could not be sent. Mailer Error: " . htmlspecialchars(stripslashes(trim($mail->ErrorInfo)));
    }
}

// Redirect with success or error message
session_start();
$_SESSION['error_message'] = $error_message;
$_SESSION['success_message'] = $success_message;
header("Location: email_link_form.php");
exit();
?>