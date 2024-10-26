<?php
session_start();
// Initialize variables
$error_message = '';
$success_message = '';

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

// Check if the 'code' parameter is present
if (isset($_GET['code'])) {
    // Sanitize 'code' from the URL to prevent XSS
    $code = htmlspecialchars($_GET['code'], ENT_QUOTES, 'UTF-8');

    // Verify if the code is valid
    $verifyQuery = $con->prepare("SELECT * FROM tblstaff WHERE code = ?");
    $verifyQuery->bind_param("s", $code);
    $verifyQuery->execute();
    $result = $verifyQuery->get_result();

    // Handle invalid code
    if ($result->num_rows == 0) {
        $error_message = "Invalid code. Please try again.";
    }

    // Handle password reset form submission
    if (isset($_POST['change'])) {
        $new_password = htmlspecialchars(stripslashes(trim($_POST['new_password'])), ENT_QUOTES, 'UTF-8');
        $con_password = htmlspecialchars(stripslashes(trim($_POST['con_password'])), ENT_QUOTES, 'UTF-8');

        // Check if passwords match
        if ($new_password === $con_password) {
            // Hash the password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password and clear the code
            $updateQuery = $con->prepare("UPDATE tblstaff SET password = ?, code = NULL WHERE code = ?");
            $updateQuery->bind_param("ss", $hashed_password, $code);

            if ($updateQuery->execute()) {
                $success_message = "Password has been reset successfully. You may now log in.";
            } else {
                $error_message = "Failed to update the password. Please try again.";
            }
        } else {
            $error_message = "Passwords do not match.";
        }
    }
} else {
    $error_message = "Invalid request.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Madridejos Home Residence Management System</title>
    <link rel="icon" type="x-icon" href="img/lg.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<style>
    @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');
    html,body{
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
    .container{
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    .container .form{
        background: #fff;
        padding: 30px 35px;
        border-radius: 5px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    }
    .btn {
        background: linear-gradient(184deg, rgba(200, 0, 0, 1) 40%, rgba(50, 50, 50, 1) 92%);
        border: none;
        color: white;
        width: 100%;
        border-radius: 15px;
        padding: 10px;
        font-size: 16px;
        cursor: pointer;
    }
    .btn:hover {
        background: linear-gradient(184deg, rgba(200,0,0,1) 40%, rgba(50,50,50,1) 92%);
        border: none;
        color: white;
        cursor: pointer;
    }
    .form-group {
        margin-bottom: 0.5rem;
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
</style>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form">
                <h2 class="text-center" style="font-size:25px;">Reset Your Password</h2>
                <br>
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                <form action="" method="POST" autocomplete="off">
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <div class="input-group">
                        <div class="input-group-append">
                        <input type="password" name="new_password" id="new_password" class="form-control" required pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{10,}$" title="Password must be at least 10 characters long, contain at least one uppercase letter, one number, and one special character." style="width: 312px;">
                            <span class="input-group-text" onclick="togglePassword('new_password', this)" style="cursor: pointer; background-color: transparent; border: none;">
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="con_password">Confirm Password</label>
                    <div class="input-group">
                        <div class="input-group-append">
                        <input type="password" name="con_password" id="con_password" class="form-control" required pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{10,}$" title="Password must be at least 10 characters long, contain at least one uppercase letter, one number, and one special character." style="width: 312px;">
                            <span class="input-group-text" onclick="togglePassword('con_password', this)" style="cursor: pointer; background-color: transparent; border: none;">
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>
                    </div>
                </div>
                    <button type="submit" name="change" class="btn">Reset Password</button>
                </form>
            </div>
        </div>
    </div>
    <?php if (!empty($success_message)): ?>
        <!-- Success Modal structure -->
        <div id="success-modal" class="modal" style="display: block;">
            <div class="modal-content" style="margin-left: 465px;">
                <span class="modal-title">Success</span>
                <p><?php echo $success_message; ?></p>
                <button id="success-ok-button" class="btn-ok">OK</button>
            </div>
        </div>  
        <!-- Add the modal styles -->
        <style>
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
                background: linear-gradient(135deg, #d4edda, #f7f7f7); /* Soft green for success */
                padding: 30px;
                border-radius: 15px;
                text-align: center;
                width: 350px;
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
                position: relative;
                margin-left: auto;
                margin-right: auto;
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
                color: #28a745; /* Green for success */
            }
            .modal-content .btn-ok {
                background-color: #5cb85c; /* Success button */
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
        </style>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                document.getElementById("success-ok-button").addEventListener("click", function() {
                    window.location.href = '../login.php?pages=login';
                });
            });
        </script>
    <?php endif; ?>
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
</body>
</html>