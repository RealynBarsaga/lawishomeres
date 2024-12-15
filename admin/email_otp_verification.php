<?php
session_start();

// Initialize variables
$error_message = '';
$success_message = '';

// Validate and retrieve email and OTP from URL
if (isset($_GET['email'], $_GET['otp']) && filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)) {
    $email = htmlspecialchars($_GET['email']);
    $otp = htmlspecialchars($_GET['otp']);
} else {
    $error_message = 'Invalid request. Please try again.';
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_otp'])) {
    // Validate OTP
    $entered_otp = trim($_POST['otp']);

    if (empty($entered_otp)) {
        $error_message = 'Please enter the OTP.';
    } elseif (!ctype_digit($entered_otp) || strlen($entered_otp) != 6) {
        $error_message = 'Invalid OTP format. Please enter a 6-digit OTP.';
    } else {
        // Database connection (use environment variables or a secure config file in production)
        $db_host = 'localhost';
        $db_username = getenv('DB_USERNAME') ?: 'u510162695_db_barangay';
        $db_password = getenv('DB_PASSWORD') ?: '1Db_barangay';
        $db_name = getenv('DB_NAME') ?: 'u510162695_db_barangay';

        $con = new mysqli($db_host, $db_username, $db_password, $db_name);

        // Check database connection
        if ($con->connect_error) {
            die('Database connection failed: ' . $con->connect_error);
        }

        // Query to check OTP validity
        $stmt = $con->prepare("SELECT otp, otp_expiry FROM tbluser WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($stored_otp, $otp_expiry);
            $stmt->fetch();

            $current_time = date('Y-m-d H:i:s');

            // Validate OTP and expiry time
            if ($entered_otp === $stored_otp && $current_time <= $otp_expiry) {
                $_SESSION['email_for_reset'] = $email; // Store email for password reset
                $success_message = 'OTP is valid and not expired. You may now reset your password.';
            } elseif ($current_time > $otp_expiry) {
                $error_message = 'The OTP has expired. Please request a new OTP.';
            } else {
                $error_message = 'Invalid OTP entered. Please try again.';
            }
        } else {
            $error_message = 'Email not found. Please check your email.';
        }

        $stmt->close();
        $con->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Madridejos Household Management System</title>
    <link rel="icon" type="image/x-icon" href="../img/lg.png">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            background-image: url('../img/received_1185064586170879.jpeg');
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
            max-width: 400px;
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
            background-color: #007bff;
            border: none;
            color: #fff;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .back-link {
            text-align: center;
            margin-top: 10px;
        }

        .back-link a {
            text-decoration: none;
            color: #007bff;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        /* Responsive Styling */
        @media (max-width: 768px) {
            .container {
                padding: 30px;
            }

            h2 {
                font-size: 22px;
            }

            .form-control {
                font-size: 14px;
            }

            .btn {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>OTP Verification</h2>
        <form method="POST">
            <div class="form-group">
                <input type="text" name="otp" class="form-control" placeholder="Enter OTP" required>
            </div>
            <div class="form-group">
                <button type="submit" name="verify_otp" class="btn">Verify OTP</button>
            </div>
        </form>

        <div class="back-link">
            <a href="forgot_password_option.php">Back to Forgot Password Options</a>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php if (!empty($success_message)): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '<?php echo $success_message; ?>',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../admin/reset_password_otp';
                    }
                });
            <?php elseif (!empty($error_message)): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '<?php echo $error_message; ?>',
                    confirmButtonText: 'OK'
                });
            <?php endif; ?>
        });
    </script>
</body>
</html>