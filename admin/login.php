<?php
// Set cookie parameters before starting the session
session_set_cookie_params([
    'lifetime' => 0,              // Session cookie (expires when the browser is closed)
    'path' => '/',                // Available across the entire domain
    'domain' => 'lawishomeresidences.com', // Change this to your domain
    'secure' => true,             // Set to true if using HTTPS
    'httponly' => true,           // Prevent JavaScript access to the cookie
    'samesite' => 'Lax'          // Use 'Lax' or 'Strict' based on your needs
]);

// Start the session
session_start();

// Security headers
header("X-XSS-Protection: 1; mode=block");
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Strict-Transport-Security: max-age=63072000; includeSubDomains; preload");
header("Access-Control-Allow-Origin: https://lawishomeresidences.com"); // Change to your domain
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Cross-Origin-Opener-Policy: same-origin");
header("Cross-Origin-Embedder-Policy: require-corp");
header("Cross-Origin-Resource-Policy: same-site");
header("Permissions-Policy: geolocation=(), camera=(), microphone=(), interest-cohort=()");
header("X-DNS-Prefetch-Control: off");

// Rest of your PHP script goes here
$error = false;
$login_success = false;
$error_attempts = false;

$username_or_email = "";

// Set a limit for the number of allowed attempts and lockout time (in seconds)
$max_attempts = 3;
$lockout_time = 300; // 5 minutes (300 seconds)

// Check if the user has been locked out
if (isset($_SESSION['lockout_time']) && time() < $_SESSION['lockout_time']) {
    $remaining_lockout = $_SESSION['lockout_time'] - time();
    $error_attempts = "Too many failed attempts. Please try again in " . ceil($remaining_lockout / 60) . " minute(s).";
} else {
    // Reset attempts after lockout period ends
    if (isset($_SESSION['lockout_time']) && time() > $_SESSION['lockout_time']) {
        unset($_SESSION['login_attempts']);
        unset($_SESSION['lockout_time']);
    }

    // Process login attempt
    if (isset($_POST['btn_login'])) {
        include "connection.php";

        // Retrieve and sanitize input values
        $username_or_email = htmlspecialchars(stripslashes(trim($_POST['txt_username'])));
        $password = htmlspecialchars(stripslashes(trim($_POST['txt_password'])));

        // Check the number of login attempts
        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = 0;
        }

        // Use prepared statements to prevent SQL injection
        $stmt = $con->prepare("SELECT * FROM tbluser WHERE (username = ? OR email = ?) AND type = 'administrator'");
        $stmt->bind_param('ss', $username_or_email, $username_or_email);
        $stmt->execute();
        $result = $stmt->get_result();
        $numrow_admin = $result->num_rows;

        if ($numrow_admin > 0) {
            $row = $result->fetch_assoc();

            if (password_verify($password, $row['password'])) {
                // Reset login attempts upon successful login
                $_SESSION['login_attempts'] = 0;

                // Store user details in session
                $_SESSION['role'] = "Administrator";
                $_SESSION['userid'] = $row['id'];
                $_SESSION['username'] = $row['username'];

                // Set login success flag to true
                $login_success = true;

                setcookie(
                    "user_session",           // Cookie name
                    session_id(),             // Cookie value
                    [
                        'expires' => time() + (86400 * 30),  // Expiration time (30 days)
                        'path' => '/',                       // Path
                        'domain' =>'lawishomeresidences.com', // Domain
                        'secure' => true,                    // Secure (true for HTTPS)
                        'httponly' => true,                  // HttpOnly
                        'samesite' => 'Lax'                  // SameSite attribute
                    ]
                );
            } else {
                $_SESSION['login_attempts']++;
                if ($_SESSION['login_attempts'] < $max_attempts) {
                    $error = true;
                }
            }
        } else {
            $_SESSION['login_attempts']++;
            if ($_SESSION['login_attempts'] < $max_attempts) {
                $error = true;
            }
        }

        // Lock out the user after the max number of attempts
        if ($_SESSION['login_attempts'] >= $max_attempts) {
            $_SESSION['lockout_time'] = time() + $lockout_time;
            $error_attempts = "Too many failed attempts. Please try again in 5 minute(s).";
            $error = false; // Stop showing the "Invalid account" message
        }
    }
}

// Optional: Clear the error after showing it to avoid repetition on refresh
if ($error || $error_attempts) {
    $error_message = "Invalid account. Please try again.";
} else {
    $error_message = ""; // Reset error message if login attempt is successful
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Madridejos Household Management System</title>
    <link rel="icon" type="x-icon" href="../img/lg.png">
    <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-image: url('../img/received_1185064586170879.jpeg');
            background-attachment: fixed;
            background-position: center center;
            background-repeat: no-repeat;
            background-size: cover;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            max-width: 400px; /* Set a max width for the container */
            width: 90%; /* Make it responsive */
            padding: 15px;
        }
        .panel {
            background: rgb(252,0,51);
            background: linear-gradient(90deg, rgba(252,0,51,1) 0%, rgba(246,248,255,1) 83%);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
        }
        .panel-title {
            color: white;
            text-align: center;
        }
        .form-control {
            border-radius: 8px !important;
            box-shadow: none;
        }
        .btns {
            width: 100%; /* Make button full width */
            height: 40px;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            background-image: url('../img/bg.jpg');
            border: none;
            color: #fff;
        }
        .error, .alert {
            color: white;
            font-size: 12px;
        }
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .panel {
                padding: 10px;
            }
            .btns {
                margin-top: 10px; /* Add some margin for smaller screens */
            }
        }
    </style>
</head>
<body class="skin-black">
    <div class="container">
        <div class="panel">
            <div class="panel-body">
                <div style="text-align:center;">
                    <img src="../img/lg.png" style="height:60px;"/>
                    <h3 class="panel-title">
                        <strong>Madridejos Household Management System</strong>
                    </h3>
                    <h7 style="color: white;">ADMIN LOGIN</h7>
                </div>
                <form role="form" method="post" onsubmit="return validateForm()">
                    <div class="form-group">
                        <label for="txt_username" style="color:#fff;">Email</label>
                        <input type="email" class="form-control" name="txt_username" placeholder="juan@sample.com" required value="<?php echo $username_or_email ?>">
                        <label for="txt_password" style="color:#fff;">Password</label>
                        <input type="password" class="form-control" name="txt_password" id="txt_password" placeholder="•••••••••••" required>
                        <div class="terms-checkbox">
                            <input type="checkbox" id="termsCheck" name="terms" required>
                            <label for="termsCheck">I agree to the <span class="terms-link" onclick="openTerms()">Terms and Conditions</span></label>
                        </div>
                    </div>
                    <button type="submit" id="btn_login" class="btns" name="btn_login">Login</button>
                </form>
                <div class="forgot-password">
                    <a href="../admin/forgot_password_option">Forgot Password?</a>
                </div>
                <p class="error"><?php echo $error_attempts; ?></p>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const loginSuccess = <?php echo json_encode($login_success); ?>;
            const error = <?php echo json_encode($error); ?>;
            const errorAttempts = <?php echo json_encode($error_attempts); ?>;

            if (loginSuccess) {
                Swal.fire({
                    title: 'Success!',
                    text: 'Login Successfully!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = '../admin/dashboard/dashboard';
                });
            } else if (error) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Invalid account. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            } else if (errorAttempts) {
                Swal.fire({
                    title: 'Error!',
                    text: errorAttempts,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });

        function openTerms() {
            document.getElementById("termsModal").style.display = "block";
        }

        function closeTerms() {
            document.getElementById("termsModal").style.display = "none";
        }

        function validateForm() {
            const termsCheck = document.getElementById("termsCheck");
            if (!termsCheck.checked) {
                Swal.fire({
                    title: 'Warning!',
                    text: 'You must agree to the terms and conditions.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return false;
            }
            return true;
        }

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById("termsModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>