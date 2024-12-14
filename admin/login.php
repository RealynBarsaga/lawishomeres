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

// Initialize variables
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
        $stmt = $con->prepare("SELECT * FROM tbluser WHERE (username = ? OR email = ?) AND type = 'Administrator'");
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
                        'domain' => 'lawishomeresidences.com', // Domain
                        'secure' => true,                    // Secure (true for HTTPS)
                        'httponly' => true,                  // HttpOnly
                        'samesite' => 'Lax'                  // SameSite attribute
                    ]
                );
            } else {
                $_SESSION['login_attempts']++;
                if ($_SESSION[' login_attempts'] < $max_attempts) {
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
    <title>Madridejos Home Residence Management System</title>
    <link rel="icon" type="x-icon" href="../img/lg.png">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <meta http-equiv="Content-Security-Policy" content="
    default-src 'self'; 
    script-src 'self' https://code.jquery.com https://cdn.jsdelivr.net https://www.google.com/recaptcha/ https://www.gstatic.com/recaptcha/ 'unsafe-inline'; 
    object-src 'none'; 
    connect-src 'self'; 
    style-src 'self' https://fonts.googleapis.com https://cdnjs.cloudflare.com 'unsafe-inline'; 
    font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; 
    img-src 'self' data: https://*.googleapis.com https://*.ggpht.com https://cdnjs.cloudflare.com; 
    frame-src https://www.google.com/recaptcha/ https://www.google.com/maps/embed/; 
    frame-ancestors 'self'; 
    base-uri 'self'; 
    form-action 'self';">
    <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://www.google.com/recaptcha/api.js?render=6Lcr3pIqAAAAANKAObEg1g-qulpuutPCFOB59t9A"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
    <script src="script.js" defer></script>
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
        .panel {
            background: linear-gradient(176deg, rgba(203,1,42,1) 23%, rgba(246,248,255,1) 69%);
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
            width: 100%;
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
        @media (max-width: 768px) {
            .panel {
                width: 90%; 
                padding: 15px; 
            }
        }
        /* Style for the terms-checkbox container */
.terms-checkbox {
    display: flex;
    align-items: center;
    font-family: Arial, sans-serif;
    margin-top: 2px;
    float: left;
    margin-left: -9px;
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
    </style>
</head>
<body>
<div class="container">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel">
            <div class="panel-body">
                <div style="text-align:center;">
                    <img src="../img/lg.png" style="height:60px;"/>
                    <h3 class="panel-title">
                        <strong>Madridejos Household Management System</strong>
                    </h3>
                    <h7 style="font-family: Georgia, serif; font-size: 18px; color: white;">ADMIN LOGIN</h7>
                </div>
                <form role="form" method="post" onsubmit="return validateForm()">
                    <div class="form-group">
                        <label for="txt_username" style="color:#fff;">Email</label>
                        <input type="email" class="form-control" name="txt_username" placeholder="juan@sample.com" required value="<?php echo $username_or_email ?>">
                        <label for="txt_password" style="color:#fff;">Password</label>
                        <input type="password" class="form-control" name="txt_password" id="txt_password" placeholder="•••••••••••" required pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{10,}$" title="Password must be at least 10 characters long, contain at least one uppercase letter, one number, and one special character.">
                        <div class="terms-checkbox">
                            <input type="checkbox" id="termsCheck" name="terms" required>
                            <label for="termsCheck">I agree to the <span class="terms-link" onclick="openTerms()">Terms and Conditions</span></ label>
                        </div>
                    </div>
                    <input type="hidden" name="token_generate" id="token_generate">
                    <button type="submit" id="btn_login" class="btns" name="btn_login">Login</button>
                </form>
                <div class="forgot-password">
                    <a href="../admin/forgot_password_option">Forgot Password?</a>
                </div>
                <p class="error"><?php echo $error_attempts; ?></p>
                <?php if ($error): ?>
                <script>
                    swal("Error!", "Invalid account. Please try again.", "error");
                </script>
                <?php endif; ?>
                <?php if ($login_success): ?>
                <script>
                    swal("Success!", "Login Successfully!", "success").then(() => {
                        window.location.href = '../admin/dashboard/dashboard';
                    });
                </script>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php include 'termsModal.php'; ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("error-ok-button").addEventListener("click", function() {
            document.getElementById("error-modal").style.display = 'none';
        });
    });
  
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("error-ok-button1").addEventListener("click", function() {
            document.getElementById("error-modal1").style.display = 'none';
        });
    });
</script>
<script>
function openTerms() {
    document.getElementById("termsModal").style.display = "block";
}

function closeTerms() {
    document.getElementById("termsModal").style.display = "none";
}

function validateForm() {
    const termsCheck = document.getElementById("termsCheck");
    const termsError = document.getElementById("termsError");
    if (!termsCheck.checked) {
        termsError.style.display = "block";
        return false;
    }
    termsError.style.display = "none";
    return true;
}

window.onclick = function(event) {
    const modal = document.getElementById("termsModal");
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>
<script>
function togglePassword(inputId, icon) {
    const input = document.getElementById(inputId);
    const iconElement = icon.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        iconElement.classList.remove('fa-eye');
        iconElement.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        iconElement.classList.remove('fa-eye-slash');
        iconElement.classList.add('fa-eye');
    }
}
</script>
<script>
grecaptcha.ready(function() {
    grecaptcha.execute('6Lcr3pIqAAAAANKAObEg1g-qulpuutPCFOB59t9A', {action: 'submit'}).then(function(token) {
        var response = document.getElementById('token_generate');
        response.value = token;
    });
});
</script>
</body>
</html>