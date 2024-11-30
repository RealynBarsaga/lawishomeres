<?php
// Initialize variables
$error_message = '';
$success_message = '';
$email = '';

// Start session for error/success message display
session_start();

// Check if the form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['reset'])) {
        $email = trim($_POST['email']);
        
        // Validate the email
        if (empty($email)) {
            $error_message = 'Please enter your email.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message = 'Invalid email format.';
        }

        // If no validation errors, proceed with email processing
        if (empty($error_message)) {
            // Load PHPMailer classes manually
            require 'PHPMailer/src/Exception.php';
            require 'PHPMailer/src/PHPMailer.php';
            require 'PHPMailer/src/SMTP.php';

            // Use PHPMailer namespaces
            use PHPMailer\PHPMailer\PHPMailer;
            use PHPMailer\PHPMailer\SMTP;
            use PHPMailer\PHPMailer\Exception;
            
            // Set up PHPMailer
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'your-email@gmail.com';  // Replace with your Gmail account
                $mail->Password   = 'your-gmail-app-password';  // Replace with your Gmail app password
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                // Recipients
                $mail->setFrom('no-reply@example.com', 'Lawishomeresidences');
                $mail->addAddress($email);

                // Generate reset code
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
                            <a href="https://lawishomeresidences.com/reset-password.php?code=' . htmlspecialchars($code) . '" class="button" style="color: #fff;">Reset Password</a>
                            <p>If you did not request this, please ignore this email.</p>
                        </div>
                    </body>
                    </html>
                ';

                // Database connection
                $MySQL_username = "u510162695_db_barangay";
                $Password = "1Db_barangay";    
                $MySQL_database_name = "u510162695_db_barangay";
                $con = mysqli_connect('localhost', $MySQL_username, $Password, $MySQL_database_name);

                if (!$con) {
                    $error_message = 'Connection failed: ' . mysqli_connect_error();
                } else {
                    // Prepared statement for verifying email
                    $stmt = $con->prepare("SELECT * FROM tblstaff WHERE email = ?");
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $stmt->store_result();

                    if ($stmt->num_rows > 0) {
                        // Update reset code for the user
                        $stmt = $con->prepare("UPDATE tblstaff SET code = ? WHERE email = ?");
                        $stmt->bind_param("ss", $code, $email);

                        if ($stmt->execute()) {
                            // Send email
                            $mail->send();
                            $success_message = 'A password reset link has been sent to your email.';
                        } else {
                            $error_message = 'Failed to update the reset code.';
                        }
                    } else {
                        $error_message = 'Email not found.';
                    }

                    $stmt->close();
                    $con->close();
                }
            } catch (Exception $e) {
                $error_message = 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
            }
        }
    }
} else {
    $error_message = 'No form submitted.';
}

// Store messages in session and redirect
$_SESSION['error_message'] = $error_message;
$_SESSION['success_message'] = $success_message;
header("Location: email_link_form.php");
exit();
?>