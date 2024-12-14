<?php
// Set cookie parameters before starting the session
session_set_cookie_params([
    'lifetime' => 0,              // Session cookie (expires when the browser is closed)
    'path' => '/',                // Available across the entire domain
    'domain' => 'lawishomeresidences.com/admin/', // Change this to your domain
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
header("Access-Control-Allow-Origin: https://lawishomeresidences.com/admin/"); // Change to your domain
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
                        'domain' => 'lawishomeresidences.com/admin/', // Domain
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
    <title>Madridejos Home Residence Management System</title>
    <link rel="icon" type="x-icon" href="../img/lg.png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
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
    <!-- bootstrap 3.0.2 -->
    <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://www.google.com/recaptcha/api.js?render=6Lcr3pIqAAAAANKAObEg1g-qulpuutPCFOB59t9A"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js" defer></script>
</head>
<style>
body {
    background-image: url('../img/received_1185064586170879.jpeg');
    background-attachment: fixed;
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover; /* Ensures the background image covers the entire container */
    height: 100vh; /* Makes sure the body takes up the full height of the viewport */
    margin: 0;
    padding: 0;
    display: flex;
    align-items: center; /* Vertically centers the content */
    justify-content: center; /* Horizontally centers the content */
}
html {
    height: 100%; /* Ensures the HTML covers the full height */
}
.panel {
    height: 435px;
    min-height: 370px;
    width: 345px;
    margin-left: 0px;
    background-image: url('../img/bg.jpg');
    background-attachment: fixed;
    background-position: center center;
    background-repeat: no-repeat;
    background-size: 30% 100%; /* Ensures the background image covers the entire container */
    border-radius: 10px;
    background-color : rgba(0, 0, 0, 0.6); /* Optional: Add a dark overlay to improve readability */
    padding: 20px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3); /* Add shadow for a modern look */
}
.panel-title {
    color: white;
    text-align: center;
}
.form-control {
    border-radius: 8px !important;
    box-shadow: none;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
.btns {
    margin-left: -9px;
    width: 300px;
    height: 40px;
    border-radius: 5px;
    font-weight: 600;
    cursor: pointer;
    background-image: url('../img/bg.jpg');
    border: none;
    color: #fff;
}
.forgot-password {
    margin-top: -89px;
}
.forgot-password a {
    text-decoration: none;
    color: #000000;
}
.forgot-password a:hover {
    text-decoration: underline;
}
.error, .alert{
    color: white;
    font-size: 12px;
}
.alert {
    position: relative;
}
/* Responsive adjustments */
@media (max-width: 768px) {
    body {
        background-size: cover; /* Keep background image filling the screen */
    }

    .btn {
        margin-left: 0;
        width: 109%;
    }

    .container {
        padding: 10px;
    }

    .panel {
        padding: 10px;
        background-size: contain;
        width: 100%;
    }
}
body.swal2-height-auto {
    height: 100vh !important;
}
.form-group {
    margin-bottom: 15px; /* Add some space between form elements */
}

@media (max-width: 768px) {
    .form-group {
        margin-left: 0; /* Reset margin for smaller screens */
        margin-right: 0; /* Reset margin for smaller screens */
    }
}
</style>
<?php 
if(isset($_POST['submit']))
{
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $secret = '6Lcr3pIqAAAAAFSR3T0EfH8uFxIj3jYiWf-Pl_ET';
    $response = $_POST['token_generate'];
    $remoteip = $_SERVER['REMOTE_ADDR'];

    $request = file_get_contents($url.'?secret='.$secret.'&response='.$response);
    $result = json_decode($request);

    if($result->success == true)
    { ?>
      <script>
        Swal.fire({
          title: 'Success!',
          text: 'Data saved successfully!',
          icon: 'success',
          confirmButtonText: 'OK'
        });
      </script>
      <?php 
    }
    else {
        ?>
        <script>
          Swal.fire({
            title: 'Error!',
            text: 'Data not saved.',
            icon: 'error',
            confirmButtonText: 'OK'
          });
        </script>
       <?php
    }
}
?>
<body class="skin-black">
<!-- Main Content -->
<div class="container" style="margin-top: -5px;">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel">
            <div class="panel-body">
            <div style="text-align:center;margin-top:-20px;">
                    <img src="../img/lg.png" style="height:60px;"/>
                    <h3 class="panel-title">
                        <strong>
                            Madridejos Home Residence Management System
                        </strong>
                    </h3>
                    <br>
                    <center style="margin-top: 5px;">
                       <h7 style="margin-bottom: -42px;font-family: Georgia, serif;font-size: 18px;text-align: center;margin-bottom: -42px; color: white;">ADMIN LOGIN</h7>
                    </center>
                </div>
                <form role="form" method="post" onsubmit="return validateForm()">
                    <div class="form-group">
                        <label for="txt_username" style="color:#fff; font-weight: lighter;">Email</label>
                        <input type="email" class="form-control" name="txt_username"
                               placeholder="juan@sample.com" required value="<?php echo $username_or_email; ?>" style="margin-top: -5px;">
                
                        <label for="txt_password" style="color:#fff; font-weight: lighter;">Password</label>
                        <div style="position: relative;">
                            <input type="password" class="form-control" name="txt_password" id="txt_password"
                                   placeholder="•••••••••••" required style="padding-right: 40px; margin-top: -4px;"
                                   pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{10,}$"
                                   title="Password must be at least 10 characters long, contain at least one uppercase letter, one number, and one special character.">
                
                            <span class="input-group-text" onclick="togglePassword('txt_password', this)" 
                                  style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; background-color: transparent; border: none;">
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>
                        <div class="terms-checkbox" style="margin-top: 10px;">
                            <input type="checkbox" id="termsCheck" name="terms" required>
                            <label for="termsCheck" style="color: #fff;">I agree to the <span class="terms-link" onclick="openTerms()">Terms and Conditions</span></label>
                        </div>
                    </div>
                    <input type="hidden" name="token_generate" id="token_generate">
                    <button type="submit" id="btn_login" class="btns" name="btn_login">Login</button>
                </form>
               <!-- Forgot password link -->
               <div class="forgot-password" style="margin-top: -2.1px;margin-left: 84px;float: left;">
                    <a href="../admin/forgot_password_option">Forgot Password?</a>
                </div>
                <!-- Horizontal rule -->
                <hr style="border: 1px solid gray; margin-top: 10px;margin-left: -9px;width: 292px;">
                
                <p style="font-size:12px;color:#ed4337;margin-top: -17px;margin-left: -9px;">
                    <?php echo $error_attempts; ?>
                </p>
                <?php if ($error_attempts): ?>
                <script>
                    Swal.fire({
                        title: 'Error!',
                        text: '<?php echo $error_attempts; ?>',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                </script>
                <?php endif; ?>
                <?php if ($error): ?>
                <script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Invalid account. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                </script>
                <?php endif; ?>
                <?php if ($login_success): ?>
                <script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'Login Successfully!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '../admin/dashboard/dashboard';
                        }
                    });
                </script>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- Terms and Conditions Modal -->
<?php include 'termsModal.php'; ?>
<script>
     // Handle the OK button for modal
    document.addEventListener("DOMContentLoaded", function() {
        // Attach a click event to the OK button to redirect to the dashboard
        const okButton = document.getElementById("ok-button2");
        if (okButton) {
            okButton.addEventListener("click", function() {
                window.location.href = '../admin/dashboard/dashboard';
            });
        }
    });
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("error-ok-button").addEventListener("click", function() {
            document.getElementById("error-modal").style.display = 'none';
        });
    });
  
    // Wait for the DOM to load
    document.addEventListener("DOMContentLoaded", function() {
        // Attach a click event to the OK button
        document.getElementById("error-ok-button1").addEventListener("click", function() {
            // Close the error modal when OK is clicked
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

// Close modal when clicking outside of it
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
        // Add your logic to submit to your backend server here.
        var response = document.getElementById('token_generate');
        response.value = token;
    });
  });
</script>
</body>
</html>