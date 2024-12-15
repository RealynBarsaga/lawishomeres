<?php
session_start();

// Initialize variables
$error_message = '';
$success_message = '';

// Get email and OTP from URL
if (isset($_GET['email']) && isset($_GET['otp'])) {
    $email = $_GET['email'];
    $otp = $_GET['otp'];
} else {
    $error_message = 'Invalid request. Please try again.';
}

// Check if the form is submitted
if (isset($_POST['verify_otp'])) {
    // Validate OTP
    $entered_otp = trim($_POST['otp']);

    if (empty($entered_otp)) {
        $error_message = 'Please enter the OTP.';
    } elseif (!is_numeric($entered_otp) || strlen($entered_otp) != 6) {
        $error_message = 'Invalid OTP format. Please enter a 6-digit OTP.';
    } else {
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

        if ($con->connect_error) {
            $error_message = 'Database connection failed: ' . $con->connect_error;
        } else {
            // Query to check if the OTP exists and is valid
            $stmt = $con->prepare("SELECT otp, otp_expiry FROM tbluser WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($otp, $otp_expiry);
                $stmt->fetch();

                // Check if the OTP matches and is not expired
                if (trim((string)$otp) === trim((string)$entered_otp)) {
                    $current_time = date('Y-m-d H:i:s');
                    if ($current_time <= $otp_expiry) {
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
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Madridejos Household Management System</title>
    <link rel="icon" type="x-icon" href="../img/lg.png">
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <!-- SweetAlert JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <style>
        /* Your existing styles here */
    </style>
</head>
<body>

    <div class="container">
        <h2>OTP Verification</h2>

        <form method="POST" action="">
            <div class="form-group">
                <input type="text" name="otp" class="form-control" placeholder="Enter OTP" required>
            </div <div class="form-group">
                <button type="submit" name="verify_otp" class="btn">Verify OTP</button>
            </div>
        </form>

        <div class="back-link">
            <a href="forgot_password_option.php">
                Back to Forgot Password Options
            </a>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php if (!empty(($success_message)): ?>
                swal("Error", "<?php echo $error_message; ?>", "error");
            <?php elseif (!empty($success_message)): ?>
                swal("Success", "<?php echo $success_message; ?>", "success").then(() => {
                    window.location.href = '../admin/reset_password_otp';
                });
            <?php endif; ?>
        });
    </script>
</body>
</html>