<?php
session_start();
// Initialize variables
$error_message = '';
$success_message = '';

// Database credentials
$MySQL_username = "u510162695_db_barangay";
$Password = "1Db_barangay";    
$MySQL_database_name = "u510162695_db_barangay";

// Establishing connection with the server
$conn = mysqli_connect('localhost', $MySQL_username, $Password, $MySQL_database_name);

// Checking connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Setting the default timezone
date_default_timezone_set("Asia/Manila");

// Check if the 'code' parameter is present
if (isset($_GET['code'])) {
    // Sanitize 'code' from the URL to prevent XSS
    $code = htmlspecialchars(stripslashes(trim($_GET['code'])), ENT_QUOTES, 'UTF-8');

    // Verify if the code is valid
    $verifyQuery = $conn->prepare("SELECT * FROM tblstaff WHERE code = ?");
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
            $updateQuery = $conn->prepare("UPDATE tblstaff SET password = ?, code = NULL WHERE code = ?");
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
            font-family: Arial, sans-serif;
            transition: all 0.3s ease-in-out;
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

        .form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btns {
            background-image: url('img/bg.jpg');
            border: none;
            color: #fff;
            width: 100%;
            border-radius: 5px;
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
        }

        .btns:hover {
            border: none;
            color: #fff;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 0.5rem;
        }

        .input-group {
            position: relative;
        }
        
        .input-group .form-control {
            padding-right: 40px; /* Space for the icon */
        }
        
        .input-group .input-group-text {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background-color: transparent;
            border: none;
            cursor: pointer;
            z-index: 10; /* Ensure the icon is on top */
        }
        
        .input-group-text i {
            opacity: 0.5;
            transition: opacity 0.3s;
        }
        
        .input-group-text:hover i {
            opacity: 1;
        }
        /* Media Queries for Responsiveness */
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .form {
                width: 100%;
                padding: 15px;
            }

            .btns {
                font-size: 14px;
            }

            .input-group .form-control {
                width: 100%;
            }

            .input-group-text {
                padding: 0;
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
            width: 350px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
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
    </style>
</head>
<body>
    <div class="container">
    <h2 style="font-size: 22px;font-weight: bold;">Reset Your Password</h2>
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <form action="" method="POST" autocomplete="off">
            <div class="form-group">
                <label for="new_password" style="float: left;">New Password</label>
                <div class="input-group">
                    <input type="password" name="new_password" id="new_password" class="form-control" placeholder="•••••••••••" required pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{10,}$" title="Password must be at least 10 characters long, contain at least one uppercase letter, one number, and one special character.">
                    <span class="input-group-text" onclick="togglePassword('new_password', this)" style="cursor: pointer; background-color: transparent; border: none;">
                        <i class="fa fa-eye"></i>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <label for="con_password" style="float: left;">Confirm Password</label>
                <div class="input-group">
                    <input type="password" name="con_password" id="con_password" class="form-control" placeholder="•••••••••••" required pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{10,}$" title="Password must be at least 10 characters long, contain at least one uppercase letter, one number, and one special character.">
                    <span class="input-group-text" onclick="togglePassword('con_password', this)" style="cursor: pointer; background-color: transparent; border: none;">
                        <i class="fa fa-eye"></i>
                    </span>
                </div>
            </div>
            <button type="submit" name="change" class="btns">Reset Password</button>
        </form>
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
                    window.location.href = 'login.php';
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