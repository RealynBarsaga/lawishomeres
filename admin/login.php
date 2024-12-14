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
            height: 461px;
            background: linear-gradient(176deg, rgba(203,1,42,1) 23%, rgba(246,248,255,1) 69%);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s; /* Smooth hover effect */
        }
        .panel:hover {
            transform: translateY(-5px); /* Lift effect on hover */
        }
        .panel-title {
            color: #333; /* Darker title color */
            text-align: center;
            margin-bottom: 15px;
        }
        .form-control {
            border-radius: 8px !important;
            box-shadow: none;
            border: 1px solid #ccc; /* Light border */
            transition: border-color 0.3s; /* Smooth border color transition */
        }
        .form-control:focus {
            border-color: #007bff; /* Change border color on focus */
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Add shadow on focus */
        }
        .btns {
            width: 100%; /* Make button full width */
            height: 40px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            background-image: url('../img/bg.jpg');
            border: none;
            color: #fff;
        }
        .btns:hover {
            background-color: #0056b3; /* Darker shade on hover */
        }
        .error, .alert {
            color: red; /* Error message color */
            font-size: 12px;
            text-align: center; /* Center error messages */
        }
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .panel {
                padding: 10px;
            }
            .btns {
                margin-top: 26px; /* Add some margin for smaller screens */
            }
        }
        /* Modal Styles */
.modal4 {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    border-radius: 5px;
    height: 100%; /* Full height */
    background-color: rgba(0, 0, 0, 0.4); /* Black background with transparency */
    overflow: auto; /* Enable scroll if needed */
}

/* Modal Content */
.modal-content4 {
    background-color: #fff;
    margin: 10% auto; /* Center the modal */
    padding: 20px;
    border-radius: 8px;
    width: 60%; /* Adjust as needed */
    max-width: 450px; /* Maximum width */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Close Button */
.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

/* Title */
h2 {
    font-size: 24px;
    color: #333;
    margin-bottom: 15px;
}

/* Content Section */
.terms-content {
    font-size: 16px;
    line-height: 1.6;
    color: #555;
}

h3 {
    font-size: 20px;
    margin-top: 15px;
    color: #333;
}

/* Paragraph Styling */
p {
    margin: 10px 0;
    font-size: 13px;
}

/* List Style */
ul {
    padding-left: 20px;
}

ul li {
    margin-bottom: 8px;
    font-size: 16px;
    color: #555;
}

/* Responsive Design for Small Screens */
@media screen and (max-width: 768px) {
    .modal-content4 {
        width: 70%; /* Full width for mobile */
    }
    h2 {
        font-size: 20px;
    }
    h3 {
        font-size: 18px;
    }
    p, ul li {
        font-size: 14px;
    }
}
/* Style for the terms-checkbox container */
.terms-checkbox {
    display: flex;
    align-items: center;
    font-family: Arial, sans-serif;
    margin-top: 2px;
    float: left;
}

/* Style for the checkbox */
.terms-checkbox input[type="checkbox"] {
    margin-right: 5px;
    width: 18px;
    height: 13px;
    cursor: pointer;
    margin-top: -6px;
}

/* Style for the label text */
.terms-checkbox label {
    font-size: 12px;
    color: #333;
}

/* Style for the terms link */
.terms-checkbox .terms-link {
    color: #0066cc;
    cursor: pointer;
    text-decoration: underline;
}

.terms-checkbox .terms-link:hover {
    color: #004c99;
}

/* Style for the error message */
.terms-checkbox .error-message {
    display: none;
    color: red;
    font-size: 12px;
    margin-left: 10px;
}

/* Style when error is displayed */
.terms-checkbox .error-message.active {
    display: inline;
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
                        <strong style="color: white;margin-top: -14px;">Madridejos Household Management System</strong>
                    </h3>
                    <h7 style="color: white;">ADMIN LOGIN</h7>
                </div>
                <form role="form" method="post" onsubmit="return validateForm()">
                    <div class="form-group" style="margin-bottom: 72px;">
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
    <!-- Terms and Conditions Modal -->
    <?php include 'termsModal.php'; ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const loginSuccess = <?php echo json_encode($login_success); ?>;
            const error = <?php echo json_encode($error); ?>;
            const errorAttempts = <?php echo json_encode($error_attempts); ?>;

            const form = document.querySelector('form');
            const body = document.body; // Reference to the body element

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
                }).then(() => {
                    body.classList.remove('no-pointer-events'); // Re-enable pointer events
                    form.elements['txt_username'].focus(); // Focus on the username field
                });
            } else if (errorAttempts) {
                Swal.fire({
                    title: 'Error!',
                    text: errorAttempts,
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then(() => {
                    body.classList.remove('no-pointer-events'); // Re-enable pointer events
                    form.elements['txt_username'].focus(); // Focus on the username field
                });
            }

            // Disable the form when the alert is shown
            form.addEventListener('submit', function(event) {
                if (loginSuccess || error || errorAttempts) {
                    event.preventDefault(); // Prevent form submission
                }
            });

            // Add a class to the body to disable pointer events when the alert is shown
            if (error || errorAttempts) {
                body.classList.add('no-pointer-events');
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