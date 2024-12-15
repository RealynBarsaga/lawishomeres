<?php
session_start();

// Check if the user has a valid session for password reset
if (!isset($_SESSION['email_for_reset'])) {
    header("Location: ../admin/forgot_password_option"); // Redirect to the forgot password options page if no valid session
    exit();
}

// Initialize variables
$error_message = '';
$success_message = '';

// Check if the form is submitted
if (isset($_POST['reset_password'])) {
    // Get the new password and confirmation password from the form
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate passwords
    if (empty($new_password) || empty($confirm_password)) {
        $error_message = 'Please fill in both fields.';
    } elseif (strlen($new_password) < 10) {
        $error_message = 'Password must be at least 10 characters long.';
    } elseif ($new_password !== $confirm_password) {
        $error_message = 'Passwords do not match.';
    } else {
        // Proceed with updating the password
        $email = $_SESSION['email_for_reset']; // Get email from session

        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_ARGON2ID);

        // Database credentials (consider using environment variables)
        $MySQL_username = "u510162695_db_barangay";
        $Password = "1Db_barangay";    
        $MySQL_database_name = "u510162695_db_barangay";
        
        // Establishing connection with server
        $con = mysqli_connect('localhost', $MySQL_username, $Password, $MySQL_database_name);
        
        // Checking connection
        if (!$con) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        // Prepare the SQL query to update the user's password
        $stmt = $con->prepare("UPDATE tbluser SET password = ? WHERE email = ?");
        if ($stmt) {
            $stmt->bind_param("ss", $hashed_password, $email);

            if ($stmt->execute()) {
                // Password successfully updated
                $success_message = 'Your password has been reset successfully. You may now log in.';
                unset($_SESSION['email_for_reset']); // Clear the email session variable after successful reset
            } else {
                $error_message = 'Failed to reset password. Please try again later.';
            }

            $stmt->close();
        } else {
            $error_message = 'Failed to prepare the SQL statement.';
        }
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
    <link rel="icon" type="x-icon" href="../img/lg.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
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
                        <label for="confirm_password">Confirm Password</label ```html
                        <div class="input-group">
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="•••••••••••" required>
                            <span class="input-group-text" onclick="togglePassword('confirm_password', this)">
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>
                    </div>
                    <button type="submit" name="reset_password" class="btns">Reset Password</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php if (!empty($error_message)): ?>
                swal("Error", "<?php echo $error_message; ?>", "error");
            <?php endif; ?>
            <?php if (!empty($success_message)): ?>
                swal("Success", "<?php echo $success_message; ?>", "success").then(() => {
                    window.location.href = 'login.php';
                });
            <?php endif; ?>
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

        function checkPassword() {
            const passwordInput = document.getElementById('new_password');
            const lengthRequirement = document.getElementById('length');
            const uppercaseRequirement = document.getElementById('uppercase');
            const numberRequirement = document.getElementById('number');
            const specialRequirement = document.getElementById('special');

            const password = passwordInput.value;

            // Check length
            if (password.length >= 10) {
                lengthRequirement.classList.remove('invalid');
                lengthRequirement.classList.add('valid');
                lengthRequirement.style.display = 'block';
                lengthRequirement.textContent = '✔️ At least 10 characters';
            } else {
                lengthRequirement.classList.remove('valid');
                lengthRequirement.classList.add('invalid');
                lengthRequirement.style.display = 'block';
                lengthRequirement.textContent = '❌ At least 10 characters';
            }

            // Check uppercase
            if (/[A-Z]/.test(password)) {
                uppercaseRequirement.classList.remove('invalid');
                uppercaseRequirement.classList.add('valid');
                uppercaseRequirement.style.display = 'block';
                uppercaseRequirement.textContent = '✔️ At least one uppercase letter';
            } else {
                uppercaseRequirement.classList.remove('valid');
                uppercaseRequirement.classList.add('invalid');
                uppercaseRequirement.style.display = 'block';
                uppercaseRequirement.textContent = '❌ At least one uppercase letter';
            }

            // Check number
            if (/\d/.test(password)) {
                numberRequirement.classList.remove('invalid');
                numberRequirement.classList.add('valid');
                numberRequirement.style.display = 'block';
                numberRequirement.textContent = '✔️ At least one number';
            } else {
                numberRequirement.classList.remove('valid');
                numberRequirement.classList.add('invalid');
                numberRequirement.style.display = 'block';
                numberRequirement.textContent = '❌ At least one number';
            }

            // Check special character
            if (/[!@#$%^&*]/.test(password)) {
                specialRequirement.classList.remove('invalid');
                specialRequirement.classList.add('valid');
                specialRequirement.style.display = 'block';
                specialRequirement.textContent = '✔️ At least one special character (!@#$%^&*)';
            } else {
                specialRequirement.classList.remove('valid');
                specialRequirement.classList.add('invalid');
                specialRequirement.style.display = 'block';
                specialRequirement.textContent = '❌ At least one special character (!@#$%^&*)';
            }
        }
    </script>
</body>
</html>