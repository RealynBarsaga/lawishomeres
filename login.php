<!DOCTYPE html>
<html lang="en">
<?php
// Set cookie parameters before starting the session
session_set_cookie_params([
    'lifetime' => 0,              // Session cookie (expires when the browser is closed)
    'path' => '/',                // Available across the entire domain
    'domain' => 'lawishomeresidences.com', // Change this to your domain
    'secure' => true,             // Set to true if using HTTPS
    'httponly' => true,           // Prevent JavaScript access to the cookie
    'samesite' => 'Lax'           // Use 'Lax' or 'Strict' based on your needs
]);

// Start the session
session_start();

// Regenerate session ID upon each new login to prevent session fixation
if (!isset($_SESSION['session_created'])) {
    session_regenerate_id(true);  // Regenerate session ID on login
    $_SESSION['session_created'] = time();
}

// Security headers
header("X-XSS-Protection: 1; mode=block");
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Strict-Transport-Security: max-age=63072000; includeSubDomains; preload");
header("Access-Control-Allow-Origin: https://lawishomeresidences.com"); // Change to your domain
header("Cross-Origin-Opener-Policy: same-origin");
header("Cross-Origin-Embedder-Policy: require-corp");
header("Cross-Origin-Resource-Policy: same-site");
header("Permissions-Policy: geolocation=(), camera=(), microphone=(), interest-cohort=()");
header("X-DNS-Prefetch-Control: off");

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
        include "pages/connection.php";

        // Retrieve input values
        $username_or_email = htmlspecialchars(stripslashes(trim($_POST['txt_username'])));
        $password = htmlspecialchars(stripslashes(trim($_POST['txt_password'])));

        // Initialize login attempts
        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = 0;
        }

        // Modify the query to allow login using either username or email
        $stmt = $con->prepare("SELECT * FROM tblstaff WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username_or_email, $username_or_email);
        $stmt->execute();
        $result = $stmt->get_result();
        $numrow_staff = $result->num_rows;

        if ($numrow_staff > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                // Reset login attempts upon successful login
                $_SESSION['login_attempts'] = 0;

                $_SESSION['role'] = "Staff";
                $_SESSION['staff'] = $row['name'];
                $_SESSION['userid'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION["barangay"] = $row["name"];
                $_SESSION['logo'] = $row['logo'];
                
                // Set login success flag to true
                $_SESSION['login_success'] = true;  // Set session flag to true when login is successful
                $login_success = true;

            } else {
                // Increment login attempts
                $_SESSION['login_attempts']++;
                if ($_SESSION['login_attempts'] < $max_attempts) {
                    $error = true;
                }
            }
        } else {
            // Increment login attempts for invalid username
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
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Madridejos Home Residence Management System</title>
    <link rel="icon" type="x-icon" href="../img/lg.png">
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
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js" defer></script>
</head>
<style>
body {
    background-image: url('img/received_1185064586170879.jpeg');
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
    transition: all 0.3s ease-in-out;
}
html {
    height: 100%; /* Ensures the HTML covers the full height */
}
.container {
    max-width: 1061px;
    width: 100%; /* Make sure the container is responsive */
    padding: 15px; /* Add padding to the container */
}
.panel {
    height: 455px;
    min-height: 370px;
    width: 345px;
    margin-left: 0px;
    background-image: url('img/bg.jpg');
    background-attachment: fixed;
    background-position: center center;
    background-repeat: no-repeat;
    background-size: 30% 100%; /* Ensures the background image covers the entire container */
    border-radius: 10px;
    background-color: rgba(0, 0, 0, 0.6); /* Optional: Add a dark overlay to improve readability */
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
    background-image: url('img/bg.jpg');
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

/* Modal styles for "Too many failed attempts" */
.modal {
    position: fixed;
    z-index: 1000; /* Ensure it's on top */
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Background overlay */
    display: flex;
    justify-content: center;
    align-items: center;
}
.modal-content {
    background: linear-gradient(135deg, #ffcccb, #f7f7f7); /* Soft red gradient for warning */
    padding: 30px; /* Same spacious padding */
    border-radius: 15px; /* Same rounded corners */
    text-align: center;
    width: 350px; /* Same width */
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3); /* Same shadow effect */
    position: relative;
    margin-left: auto;
    margin-right: auto;
    margin-top: 166px;
    animation: modalFadeIn 0.5s ease; /* Same smooth fade-in */
}
/* Fade-in animation */
@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: scale(0.95); /* Slight scaling for a zoom-in effect */
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
/* Add a subtle border */
.modal-content {
    border: 2px solid #e0e0e0;
}
.modal-title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 10px;
    color: #d9534f; /* Red color for warning */
}
.modal-content .btn-ok {
    background-color: #f0ad4e; /* Orange for warning */
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 25px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease, transform 0.2s ease;
}
.modal-content .btn-ok:hover {
    background-color: #ec971f;
    transform: scale(1.05);
}
.modal p {
    margin-bottom: 25px;
    font-size: 16px;
}
/* Optional: Add a subtle footer */
.modal-content::after {
    content: "Powered by Madridejos HRMS";
    display: block;
    font-size: 12px;
    color: #aaa;
    margin-top: 20px;
}


/* Modal styles */
.modal1 {
    position: fixed;
    z-index: 1000; /* Ensure it's on top */
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Background overlay */
    display: flex;
    justify-content: center;
    align-items: center;
}
.modal-content1 {
    background: linear-gradient(135deg, #ffdddd, #f7f7f7); /* Soft red gradient for error */
    padding: 30px; /* Same spacious padding */
    border-radius: 15px; /* Same rounded corners */
    text-align: center;
    width: 350px; /* Same width */
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3); /* Same shadow effect */
    position: relative;
    margin-left: auto;
    margin-right: auto;
    margin-top: 166px;
    animation: modalFadeIn 0.5s ease; /* Same smooth fade-in */
}
/* Fade-in animation */
@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: scale(0.95); /* Slight scaling for a zoom-in effect */
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
/* Add a subtle border */
.modal-content1 {
    border: 2px solid #e0e0e0; /* Soft border */
}
/* Optional: Close button */
.modal-content1 .close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    background: transparent;
    border: none;
    font-size: 18px;
    cursor: pointer;
    color: #666;
    transition: color 0.3s ease;
}
.modal-content1 .close-btn:hover {
    color: #ff5c5c; /* Change color on hover */
}
/* Optional: Increase spacing between elements */
.modal-content1 p {
    margin-bottom: 25px; /* Increased margin for better spacing */
    font-size: 16px; /* Slightly larger text */
}
.modal-content1 .btn-ok1 {
    background-color: #d9534f; /* Red color for error */
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 25px; /* More rounded button */
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease, transform 0.2s ease; /* Smooth transition for hover effects */
}
.modal-content1 .btn-ok1:hover {
    background-color: #c9302c; /* Darker red on hover */
    transform: scale(1.05); /* Slight zoom on hover */
}
/* Optional: Add a subtle footer */
.modal-content1::after {
    content: "Powered by Madridejos HRMS";
    display: block;
    font-size: 12px;
    color: #aaa;
    margin-top: 20px;
}
/* Error modal title */
.modal-title1 {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 10px;
    color: #d9534f; /* Red color for error */
}
.btn-ok1 {
    background-color: #d9534f; /* Red color for error */
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 25px; /* More rounded button */
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease, transform 0.2s ease;
}
.btn-ok1:hover {
    background-color: #c9302c;
    transform: scale(1.05); /* Slight zoom on hover */
}
/* Add some space between the text and the button */
.modal1 p {
    margin-bottom: 25px;
}


/* Modal styles */
.modal2 {
    position: fixed;
    z-index: 1000; /* Ensure it's on top */
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Background overlay */
    display: flex;
    justify-content: center;
    align-items: center;
}
.modal-content2 {
    background: linear-gradient(135deg, #ffffff, #f7f7f7); /* Soft gradient background */
    padding: 30px; /* Increased padding for a spacious look */
    border-radius: 15px; /* Slightly more rounded corners */
    text-align: center;
    width: 350px; /* Slightly wider */
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3); /* Deeper shadow for a more elevated effect */
    position: relative; /* Allows for positioning of close button */
    margin-left: auto;
    margin-right: auto;
    margin-top: 166px;
    animation: modalFadeIn 0.5s ease; /* Smooth fade-in animation */
}
/* Fade-in animation */
@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: scale(0.95); /* Slight scaling for a zoom-in effect */
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
/* Add a subtle border */
.modal-content2 {
    border: 2px solid #e0e0e0; /* Soft border */
}
/* Optional: Close button */
.modal-content2 .close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    background: transparent;
    border: none;
    font-size: 18px;
    cursor: pointer;
    color: #666;
    transition: color 0.3s ease;
} 
.modal-content2 .close-btn:hover {
    color: #ff5c5c; /* Change color on hover */
}
/* Optional: Increase spacing between elements */
.modal-content2 p {
    margin-bottom: 25px; /* Increased margin for better spacing */
    font-size: 16px; /* Slightly larger text */
}
.modal-content2 .btn-ok2 {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 25px; /* More rounded button */
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease, transform 0.2s ease; /* Smooth transition for hover effects */
}  
.modal-content2 .btn-ok2:hover {
    background-color: #45a049;
    transform: scale(1.05); /* Slight zoom on hover */
} 
/* Optional: Add a subtle footer */
.modal-content2::after {
    content: "Powered by Madridejos HRMS";
    display: block;
    font-size: 12px;
    color: #aaa;
    margin-top: 20px;
}
.modal-title2 {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 10px;
}
.btn-ok2 {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    margin-top: 10px;
    transition: background-color 0.3s ease;
}
.btn-ok2:hover {
    background-color: #45a049;
}
/* Add some space between the text and the button */
.modal2 p {
    margin-bottom: 20px;
}
.input-group {
    position: relative; /* Make sure input-group has relative positioning */
}
.input-group .form-control {
    padding-right: 40px; /* Add padding to the right for the input */
}
.input-group .input-group-text {
    position: absolute; /* Position the eye icon absolutely */
    right: 10px; /* Adjust the right position */
    top: 50%; /* Center vertically */
    transform: translateY(-50%); /* Adjust for centering */
    background-color: transparent; /* Make background transparent */
    border: none; /* Remove border */
    cursor: pointer; /* Change cursor to pointer */
}
.input-group-text i {
    opacity: 0.5; /* Set initial opacity */
    transition: opacity 0.3s; /* Smooth transition */
}  
.input-group-text:hover i {
    opacity: 1; /* Increase opacity on hover */
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

/* Cookie Consent Banner Styles */
.wrapper {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background-color: #333;
    color: #fff;
    padding: 15px 30px;
    font-family: Arial, sans-serif;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 1000;
}
.cookie-message h3 {
    margin: 0;
    font-size: 18px;
    font-weight: bold;
}
.cookie-message p {
    margin: 5px 0 0;
    font-size: 14px;
    color: #ddd;
}
.buttons {
    display: flex;
    gap: 15px;
}
#acceptBtn {
    padding: 10px 20px;
    background-image: url('img/bg.jpg');
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    transition: background-image 0.3s ease;
}
#acceptBtn:hover {
    background-color: #0056b3;
}
#acceptBtn:focus {
    outline: none;
}
#acceptBtn[disabled] {
    background-color: #777;
    cursor: not-allowed;
}
#rejectBtn {
    padding: 10px 20px;
    background-image: url('img/bg.jpg');
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    transition: background-image 0.3s ease;
}
#rejectBtn:hover {
    background-color: #0056b3;
}
#rejectBtn:focus {
    outline: none;
}
#rejectBtn[disabled] {
    background-color: #777;
    cursor: not-allowed;
}
</style>
<body class="skin-black">

<!-- Main Content -->
<div class="container" style="margin-top: -5px;">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel">
            <div class="panel-body">
            <div style="text-align:center;margin-top:-28px;">
                    <img src="img/lg.png" style="height:60px;"/>
                    <h3 class="panel-title">
                        <strong>
                            Madridejos Home Residence Management System
                        </strong>
                    </h3>
                    <br>
                    <center style="margin-top: -5px;">
                        <h7 style="margin-bottom: -42px;font-family: Georgia, serif;font-size: 18px;text-align: center;margin-bottom: -42px; color: white;">USER LOGIN</h7>
                    </center>
                </div>
                <form role="form" method="post" onsubmit="return validateRecaptcha() && validateForm()">
                    <div class="form-group" style="border-radius:1px; border: 25px;">
                        <label for="txt_username" style="color:#fff;margin-left: -8px;font-weight: lighter;">Email</label>
                        <input type="email" class="form-control" name="txt_username"
                               placeholder="juan@sample.com" required value="<?php echo $username_or_email ?>" style="margin-top: -5px;width: 300px;margin-left: -11px;">

                        <label for="txt_password" style="color:#fff;margin-left: -8px;font-weight: lighter;">Password</label>
                        <div style="position: relative; width: 300px; margin-left: -11px;">
                            <input type="password" class="form-control" name="txt_password" id="txt_password"
                                   placeholder="•••••••••••" required style="padding-right: 40px; margin-top: -4px; width: 100%;"
                                   pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{10,}$"
                                   title="Password must be at least 10 characters long, contain at least one uppercase letter, one number, and one special character.">
                            
                            <span class="input-group-text" onclick="togglePassword('txt_password', this)" 
                                  style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; background-color: transparent; border: none;">
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>
                        <div class="form-group" style="margin-top: 5px; width: 3px; margin-left: -11px; transform: scale(0.99); transform-origin: 0 0;">
                            <div class="g-recaptcha" data-sitekey="07b4afb6-707c-4b02-b6a8-9d5cc5324c6e"></div>
                        </div>
                        <p id="captcha-error" style="font-size:10px;margin-top: -17px;margin-left: -11px;color:#ed4337;display: none;">
                          Please verify that you are not a robot
                        </p>
                    </div>
                    <button type="submit" id="btn_login" class="btns" name="btn_login" style="margin-left: -12px;font-size: 18px;margin-top: -11px;">Login</button>
                </form>
                <div class="terms-checkbox">
                    <input type="checkbox" id="termsCheck" name="terms" required>
                    <label for="termsCheck">I agree to the <span class="terms-link" onclick="openTerms()">Terms and Conditions</span></label>
                </div>
                <!-- Forgot password link -->
                <div class="forgot-password" style="margin-top: -2.1px;margin-left: 84px;float: left;">
                    <a href="../forgot_password_option">Forgot Password?</a>
                </div>
            

                <!-- Horizontal rule -->
                <hr style="border: 1px solid gray; margin-top: 10px;margin-left: -9px;width: 292px;">
                
                
                <!-- Error attempts message -->
                <p id="termsError" style="font-size:12px;margin-top: -17px;margin-left: -8px;color:#ed4337;display: none;">
                    Please accept the terms and conditions to continue
                </p>
                <p style="font-size:12px;color:#ed4337;margin-top: -20px;margin-left: -10px;">
                    <?php echo $error_attempts; ?>
                </p>
              

                <?php if ($error_attempts): ?>
                <!-- Error Modal structure -->
                <div id="error-modal" class="modal" style="display: block;">
                    <div class="modal-content" style="margin-left:479px;">
                        <span class="modal-title">Error</span>
                        <p><?php echo $error_attempts; ?></p>
                        <button id="error-ok-button" class="btn-ok">OK</button>
                    </div>
                </div>  
                <?php endif; ?>
                <!-- Error message modal and JavaScript for dismiss -->
                <?php if ($error): ?>
                <!-- Error Modal structure -->
                <div id="error-modal1" class="modal1" style="display: block;">
                    <div class="modal-content1" style="margin-left:479px;">
                        <span class="modal-title1">Error</span>
                        <p>Invalid account. Please try again.</p>
                        <button id="error-ok-button1" class="btn-ok1">OK</button>
                    </div>
                </div>
                <?php endif; ?>
                <!-- Success message and JavaScript for redirection -->
                <?php if ($login_success): ?>
                <!-- Modal structure -->
                <div id="success-modal2" class="modal2" style="display: block;">
                    <div class="modal-content2" style="margin-left:479px;">
                        <span class="modal-title2">Success</span>
                        <p>Login Successfully!</p>
                        <button id="ok-button2" class="btn-ok2">OK</button>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- Terms and Conditions Modal -->
<div id="termsModal" class="modal4" style="display:none;">
    <div class="modal-content4">
        <span class="close" onclick="closeTerms()">&times;</span>
        <h2>Terms and Conditions</h2>
        <div class="terms-content">
            <h3>1. Introduction</h3>
            <p>Welcome to our e-commerce platform. By accessing or using our website, you agree to these terms and conditions.</p>
            <h3>2. Account Security</h3>
            <p>You are responsible for maintaining the confidentiality of your account credentials and for all activities under your account.</p>
            <h3>3. Privacy Policy</h3>
            <p>Your use of our platform is also governed by our Privacy Policy. By using our services, you consent to our collection and use of your data as described therein.</p>
            <h3>4. Prohibited Activities</h3>
            <p>You agree not to:</p>
            <ul>
                <li>Use the platform for any illegal purposes</li>
                <li>Attempt to gain unauthorized access to other user accounts</li>
                <li>Upload malicious content or viruses</li>
                <li>Engage in fraudulent activities</li>
            </ul>
            <h3>5. Termination</h3>
            <p>We reserve the right to terminate or suspend your account for violations of these terms.</p>
        </div>
    </div>
</div>

<!-- Cookie Consent Banner -->
<div class="wrapper">
    <div class="cookie-message">
        <h3>We use cookies!</h3>
        <p>This website uses essential cookies to ensure its proper operation and tracking cookies to understand how you interact with it. The latter will be set only after consent.</p>
    </div>
    <div class="buttons">
        <button id="acceptBtn">Accept</button>
        <button id="rejectBtn">Reject</button>
    </div>
</div>
<script>
     // Handle the OK button for modal
     document.addEventListener("DOMContentLoaded", function() {
        // Attach a click event to the OK button to redirect to the dashboard
        const okButton = document.getElementById("ok-button2");
        if (okButton) {
            okButton.addEventListener("click", function() {
                window.location.href = 'pages/dashboard/dashboard';
            });
        }
    });
</script>
<script>
// Cookie Consent Logic
document.addEventListener('DOMContentLoaded', function() {
    if (getCookie('cookieConsent') === 'accepted') {
        hideBanner();
    } else if (getCookie('cookieConsent') === 'rejected') {
        hideBanner();
    } else {
        showBanner();
    }

    // Accept button
    document.getElementById('acceptBtn').addEventListener('click', function() {
        setCookie('cookieConsent', 'accepted', 365);
        hideBanner();
    });

    // Reject button
    document.getElementById('rejectBtn').addEventListener('click', function() {
        setCookie('cookieConsent', 'rejected', 365);
        hideBanner();
    });

    function showBanner() {
        document.querySelector('.wrapper').style.display = 'flex';
    }

    function hideBanner() {
        document.querySelector('.wrapper').style.display = 'none';
    }

    function setCookie(name, value, days) {
        var expires = '';
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
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
   $(document).on('click', '#btn_login', function(){
       var response = g-recaptcha.getResponse();
       alert(response);
   })  
   
   //executeCodes function will be called on webpage load
   window.addEventListener("load", executeCodes);

  function validateRecaptcha() {
    var response = grecaptcha.getResponse();
    if (response.length === 0) {
        document.getElementById("captcha-error").style.display = "block";
        document.getElementById("termsError").style.display = "block";
        return false; // Prevent form submission
    }
    return true; // Allow form submission
  }

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
</body>
</html>