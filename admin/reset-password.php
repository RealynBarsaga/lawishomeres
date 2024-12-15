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
    $verifyQuery = $conn->prepare("SELECT * FROM tbluser WHERE code = ?");
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
            $hashed_password = password_hash($new_password, PASSWORD_ARGON2ID);

            // Update the password and clear the code
            $updateQuery = $conn->prepare("UPDATE tbluser SET password = ?, code = NULL WHERE code = ?");
            $updateQuery->bind_param("ss", $hashed_password, $code);

            if ($updateQuery->execute()) {
                $success_message = "Your password has been reset successfully. You may now log in.";
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
    <title>Madridejos Household Management System</title>
    <link rel="icon" type="x-icon" href="../img/lg.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');

        html, body {
            background-image: url('../img/received_1185064586170879.jpeg');
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

        .container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: 400px;
            width: 100%;
            padding: 15px;
        }

        .container .form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,  0, 0, 0.2);
        }

        .btns {
            background-image: url('../img/bg.jpg');
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
        .password-checklist {
            margin-top: 10px;
            display: none; /* Initially hidden */
            font-size: 13px;
        }

        .password-checklist div {
            margin: 5px 0;
        }

        .valid {
            color: green;
        }

        .invalid {
            color: red;
        }

        /* Media Queries for responsiveness */
        @media (max-width: 767px) {
            .container {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12 form">
                <h2 class="text-center" style="font-size:25px;">Reset Your Password</h2>
                <br>
                <form action="" method="POST" autocomplete="off">
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <div class="input-group">
                            <input type="password" name="new_password" id="new_password" class="form-control" placeholder="•••••••••••" required oninput="checkPassword()">
                            <span class="input-group-text" onclick="togglePassword('new_password', this)">
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>
                    </div>
                    <div class="password-checklist" id="password-checklist">
                        <h5>Password Requirements:</h5>
                        <div id="length" class="invalid" style="display: none;">❌ At least 10 characters</div>
                        <div id="uppercase" class="invalid" style="display: none;">❌ At least one uppercase letter</div>
                        <div id="number" class="invalid" style="display: none;">❌ At least one number</div>
                        <div id="special" class="invalid" style="display: none;">❌ At least one special character (!@#$%^&*)</div>
                    </div>
                    <div class="form-group">
                        <label for="con_password">Confirm Password</label>
                        <div class="input-group">
                            <input type="password" name="con_password" id="con_password" class="form-control" placeholder="•••••••••••" required>
                            <span class="input-group-text" onclick="togglePassword('con_password', this)">
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>
                    </div>
                    <button type="submit" name="change" class="btns">Reset Password</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        document.addEventListener("DOMContentLoaded", function() {
            <?php if (!empty($success_message)): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '<?php echo $success_message; ?>',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../admin/login.php';
                    }
                });
            <?php endif; ?>

            <?php if (!empty($error_message )): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '<?php echo $error_message; ?>',
                    confirmButtonText: 'OK'
                });
            <?php endif; ?>
        });

        function checkPassword() {
            const password = document.getElementById('new_password').value;
            const checklist = document.getElementById('password-checklist');
            const lengthCheck = document.getElementById('length');
            const uppercaseCheck = document.getElementById('uppercase');
            const numberCheck = document.getElementById('number');
            const specialCheck = document.getElementById('special');

            checklist.style.display = 'block';

            // Check length
            if (password.length >= 10) {
                lengthCheck.classList.remove('invalid');
                lengthCheck.classList.add('valid');
                lengthCheck.textContent = '✔️ At least 10 characters';
                lengthCheck.style.display = 'block';
            } else {
                lengthCheck.classList.remove('valid');
                lengthCheck.classList.add('invalid');
                lengthCheck.textContent = '❌ At least 10 characters';
                lengthCheck.style.display = 'block';
            }

            // Check uppercase
            if (/[A-Z]/.test(password)) {
                uppercaseCheck.classList.remove('invalid');
                uppercaseCheck.classList.add('valid');
                uppercaseCheck.textContent = '✔️ At least one uppercase letter';
                uppercaseCheck.style.display = 'block';
            } else {
                uppercaseCheck.classList.remove('valid');
                uppercaseCheck.classList.add('invalid');
                uppercaseCheck.textContent = '❌ At least one uppercase letter';
                uppercaseCheck.style.display = 'block';
            }

            // Check number
            if (/\d/.test(password)) {
                numberCheck.classList.remove('invalid');
                numberCheck.classList.add('valid');
                numberCheck.textContent = '✔️ At least one number';
                numberCheck.style.display = 'block';
            } else {
                numberCheck.classList.remove('valid');
                numberCheck.classList.add('invalid');
                numberCheck.textContent = '❌ At least one number';
                numberCheck.style.display = 'block';
            }

            // Check special character
            if (/[!@#$%^&*]/.test(password)) {
                specialCheck.classList.remove('invalid');
                specialCheck.classList.add('valid');
                specialCheck.textContent = '✔️ At least one special character (!@#$%^&*)';
                specialCheck.style.display = 'block';
            } else {
                specialCheck.classList.remove('valid');
                specialCheck.classList.add('invalid');
                specialCheck.textContent = '❌ At least one special character (!@#$%^&*)';
                specialCheck.style.display = 'block';
            }
        }
    </script>
</body>
</html>