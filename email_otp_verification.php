<?php
session_start();

// Initialize variables
$error_message = '';
$success_message = '';

// Get email and OTP from URL
if (isset($_GET['email']) && isset($_GET['otp'])) {
    $email = filter_var($_GET['email'], FILTER_SANITIZE_EMAIL);
    $otp = $_GET['otp']; // We assume OTP is numeric, so we'll validate it later
} else {
    $error_message = 'Invalid request. Please try again.';
}

// Check if the form is submitted
if (isset($_POST['verify_otp'])) {
    // Sanitize and validate the entered OTP
    $entered_otp = trim($_POST['otp']);

    if (empty($entered_otp)) {
        $error_message = 'Please enter the OTP.';
    } elseif (!is_numeric($entered_otp) || strlen($entered_otp) != 6) {
        $error_message = 'Invalid OTP format. Please enter a 6-digit OTP.';
    } else {
        // Database connection details
        $MySQL_username = "u510162695_db_barangay";
        $Password = "1Db_barangay";    
        $MySQL_database_name = "u510162695_db_barangay";
        
        // Establishing connection with the database
        $con = mysqli_connect('localhost', $MySQL_username, $Password, $MySQL_database_name);

        // Checking connection
        if (mysqli_connect_errno()) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Setting the default timezone
        date_default_timezone_set("Asia/Manila");

        // Query to check if the OTP exists and is valid
        $stmt = $con->prepare("SELECT otp, otp_expiry FROM tblstaff WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($stored_otp, $otp_expiry);
            $stmt->fetch();

            // Check if the OTP matches and is not expired
            if (trim($stored_otp) === trim($entered_otp)) {
                $current_time = date('Y-m-d H:i:s');
                if ($current_time <= $otp_expiry) {
                    // OTP is valid and not expired, allow password reset
                    $_SESSION['email_for_reset'] = $email; // Store email in session for password reset
                    $success_message = 'OTP is valid and not expired, you may now reset your password.';
                } else {
                    $error_message = 'The OTP has expired. Please request a new OTP.';
                }
            } else {
                $error_message = 'Invalid OTP entered. Please try again.';
            }
        } else {
            $error_message = 'Email not found. Please check your email.';
        }

        $stmt->close();
        $con->close();
    }
    
// Display error or success message
if ($error_message) {
    echo "<div class='error'>$error_message</div>";
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Madridejos Household Management System</title>
    <link rel="icon" type="x-icon" href="img/lg.png">
    <style>
        /* General Reset and Styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            font-family: 'Poppins', sans-serif;
            background-image: url('img/received_1185064586170879.jpeg');
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            padding: 40px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: 500px;
            width: 100%;
            box-sizing: border-box;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            box-sizing: border-box;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background-image: url('img/bg.jpg');
            border: none;
            color: #fff;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }

        .success {
            color: green;
            margin-bottom: 10px;
        }

        .back-link {
            text-align: center;
            margin-top: -17px;
        }

        .back-link a {
            display: inline-block;
            padding: 12px 20px;
            background-color: #f0f2f5;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-decoration: none;
            color: #333;
            font-size: 16px;
            width: 100%;
            max-width: 500px;
            margin: 10px auto;
            cursor: pointer;
            text-align: center;
        }

        .back-link a:hover {
            background-color: #e1e4e8;
        }

        /* Media Queries for Responsiveness */
        @media (max-width: 768px) {
            .container {
                padding: 30px;
                width: 100%;
            }

            h2 {
                font-size: 22px;
            }

            .form-control {
                font-size: 14px;
                padding: 10px;
            }

            .btn {
                font-size: 14px;
                padding: 10px;
            }

            .back-link a {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 20px;
            }

            h2 {
                font-size: 20px;
            }

            .form-control {
                font-size: 14px;
                padding: 8px;
            }

            .btn {
                font-size: 14px;
                padding: 10px;
            }

            .back-link a {
                font-size: 14px;
                padding: 8px 16px;
            }
        }

        @media (max-width: 320px) {
            .container {
                padding: 15px;
            }

            h2 {
                font-size: 18px;
            }

            .form-control {
                font-size: 14px;
                padding: 8px;
            }

            .btn {
                font-size: 14px;
                padding: 10px;
            }

            .back-link a {
                font-size: 14px;
                padding: 8px 16px;
            }
        }
         /* Success Modal Styles */
         .modal {
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: linear-gradient(135deg, #d4edda, #f7f7f7);
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            width: 419px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            position: relative;
            margin-left: 440px;
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
            margin: auto;
            width: 100px;
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
    </style>
</head>
<body>

    <div class="container">
        <h2>OTP Verification</h2>

        <?php if (!empty($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <input type="text" name="otp" class="form-control" placeholder="Enter OTP" required>
            </div>
            <div class="form-group">
                <button type="submit" name="verify_otp" class="btn">Verify OTP</button>
            </div>
        </form>

        <div class="back-link">
            <a href="../forgot_password_option">
                Back to Forgot Password Options
            </a>
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
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                document.getElementById("success-ok-button").addEventListener("click", function() {
                    window.location.href = '../reset_password_otp';
                });
            });
        </script>
    <?php endif; ?>
</body>
</html>