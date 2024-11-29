<?php
session_start();

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

// Handle form submission
if (isset($_POST['reset'])) {
    $email = trim($_POST['email']);
    
    // Validate email
    if (empty($email)) {
        $error_message = 'Please enter your email.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Invalid email format.';
    }

    if (empty($error_message)) {
        // Load Composer's autoloader for PHPMailer
        require 'vendor/autoload.php';
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;

        // Initialize PHPMailer
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
}

// Display the page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Madridejos Home Residence Management System</title>
    <link rel="icon" type="x-icon" href="img/lg.png">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');
        html, body {
            background-image: url('img/received_1185064586170879.jpeg');
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
        }

        /* Styling for the header */
        .res {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-top: 20px;
            margin-bottom: 10px;
            text-align: center;
        }

        /* Styling for the paragraph */
        p.text-center {
            font-size: 15px;
            color: #333;
            line-height: 1.5;
            margin-top: 0;
            margin-bottom: 30px;
            text-align: center;
        }

        .container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: 580px;
            width: 100%;
        }

        .container .form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn {
            background-image: url('img/bg.jpg');
            border: none;
            color: #fff;
            width: 100%;
            border-radius: 5px;
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
        }

        .btn:hover {
            border: none;
            color: #fff;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            width: 94%;
            padding: 10px 15px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #007bff;
            outline: none;
            background-color: #fff;
        }

        .back-link {
            text-align: right;
            margin-top: 20px;
        }

        .back-link button {
            background-color: #f0f2f5;
            border: 1px solid #ddd;
            padding: 0;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            height: 40px;
            border-radius: 5px;
        }

        .back-link button:hover {
            background-color: #e1e4e8;
        }

        .back-link a {
            color: #000000;
            text-decoration: none;
            font-size: 16px;
        }

        .back-link a:hover {
            text-decoration: none;
            color: #000000;
        }

        /* Success Modal styles */
        .modal {
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            display: flex;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: linear-gradient(135deg, #d4edda, #f7f7f7);
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            width: 350px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            position: relative;
            margin-left: 449px;
            margin-top: 160px;
            animation: modalFadeIn 0.5s ease;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .modal-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #28a745;
        }

        .modal-content .btn-ok {
            background-color: #5cb85c;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .modal-content .btn-ok:hover {
            background-color: #4cae4c;
            transform: scale(1.05);
        }

        .modal p {
            margin-bottom: 25px;
            font-size: 16px;
        }

        .modal-content::after {
            content: "Powered by Madridejos HRMS";
            display: block;
            font-size: 12px;
            color: #aaa;
            margin-top: 20px;
        }

        /* Media Queries for responsiveness */
        @media screen and (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .form {
                padding: 25px;
            }

            .res {
                font-size: 18px;
            }

            p.text-center {
                font-size: 14px;
                margin-bottom: 20px;
            }

            .form-control {
                font-size: 14px;
                padding: 8px 12px;
            }

            .btn {
                font-size: 14px;
                padding: 8px 12px;
            }

            .back-link button {
                font-size: 14px;
                padding: 8px;
            }

            .modal-content {
                width: 80%; /* Adjust modal width for smaller screens */
                padding: 20px;
            }

            .modal-title {
                font-size: 16px; /* Adjust modal title font size */
            }

            .modal-content .btn-ok {
                font-size: 14px;
                padding: 10px 20px;
            }

            .modal p {
                font-size: 14px; /* Adjust modal text font size */
            }
        }

        @media screen and (max-width: 470px) {
            .container {
                width: 90%;
            }

            .modal-content {
                left: 457px;
                width: 85%; /* Adjust modal width even more for very small screens */
                padding: 15px;
            }

            .modal-title {
                font-size: 14px; /* Adjust modal title font size */
            }

            .modal-content .btn-ok {
                font-size: 14px;
                padding: 10px 20px;
            }

            .modal p {
                font-size: 12px; /* Adjust modal text font size */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form">
                <form action="../email_link_form" method="POST" autocomplete="off">
                    <p class="res">Reset via Email Link</p>
                    <p class="text-center">Enter the email address associated with your account and we will send you a link to reset your password.</p>
                    <br>
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger" style="color: #a94442;">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <input type="email" name="email" class="form-control" placeholder="Enter your email" required value="<?php echo htmlspecialchars($email); ?>">
                    </div>
                    <div class="form-group">
                        <button type="submit" name="reset" class="btn">Send Reset Link</button>
                        <div class="back-link">
                            <a href="../forgot_password_option">
                                <button type="button" class="login-btn">Back</button>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php if (!empty($success_message)): ?>
        <!-- Success Modal structure -->
        <div id="success-modal" class="modal" style="display: block;">
            <div class="modal-content">
                <span class="modal-title">Success</span>
                <p><?php echo $success_message; ?></p>
                <button id="success-ok-button" class="btn-ok">OK</button>
            </div>
        </div>  
    <?php endif; ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("success-ok-button").addEventListener("click", function() {
                window.location.href = '../email_link_form';
            });
        });
    </script>
</body>
</html>

<?php
// Clear session messages after displaying them
unset($_SESSION['error_message']);
unset($_SESSION['success_message']);
?>