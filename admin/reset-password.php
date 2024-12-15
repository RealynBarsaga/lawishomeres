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
</head>
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
        max-width: 1243%;
        width: 100%;
        padding: 15px;
    }

    .container .form {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
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

    .password-checklist {
        margin-top: 10px;
    }

    .symbol {
        font-size: 20px;
    }

    .modal {
        position: fixed top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
</style>
<body>
    <div class="container">
        <div class="form">
            <h2>Password Reset</h2>
            <?php if ($error_message): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <?php if ($success_message): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Enter your new password" oninput="validatePassword()" required>
                </div>
                <div class="form-group">
                    <label for="con_password">Confirm Password</label>
                    <input type="password" id="con_password" name="con_password" class="form-control" placeholder="Confirm your new password" required>
                </div>
                <div class="password-checklist">
                    <h5>Password Requirements:</h5>
                    <ul>
                        <li id="length" class="invalid">At least 10 characters <span class="symbol" id="length-symbol">❌</span></li>
                        <li id="uppercase" class="invalid">At least one uppercase letter <span class="symbol" id="uppercase-symbol">❌</span></li>
                        <li id="number" class="invalid">At least one number <span class="symbol" id="number-symbol">❌</span></li>
                        <li id="special" class="invalid">At least one special character (!@#$%^&*) <span class="symbol" id="special-symbol">❌</span></li>
                    </ul>
                </div>
                <button type="submit" name="change" class="btns">Change Password</button>
            </form>
        </div>
    </div>

    <script>
        function validatePassword() {
            const password = document.getElementById('new_password').value;
            const lengthCheck = document.getElementById('length');
            const uppercaseCheck = document.getElementById('uppercase');
            const numberCheck = document.getElementById('number');
            const specialCheck = document.getElementById('special');

            const lengthSymbol = document.getElementById('length-symbol');
            const uppercaseSymbol = document.getElementById('uppercase-symbol');
            const numberSymbol = document.getElementById('number-symbol');
            const specialSymbol = document.getElementById('special-symbol');

            // Check length
            if (password.length >= 10) {
                lengthCheck.classList.remove('invalid');
                lengthCheck.classList.add('valid');
                lengthSymbol.textContent = '✔️';
            } else {
                lengthCheck.classList.remove('valid');
                lengthCheck.classList.add('invalid');
                lengthSymbol.textContent = '❌';
            }

            // Check for uppercase letter
            if (/[A-Z]/.test(password)) {
                uppercaseCheck.classList.remove('invalid');
                uppercaseCheck.classList.add('valid');
                uppercaseSymbol.textContent = '✔️';
            } else {
                uppercaseCheck.classList.remove('valid');
                uppercaseCheck.classList.add('invalid');
                uppercaseSymbol.textContent = '❌';
            }

            // Check for number
            if (/\d/.test(password)) {
                numberCheck.classList.remove('invalid');
                numberCheck.classList.add('valid');
                numberSymbol.textContent = '✔️';
            } else {
                numberCheck.classList.remove('valid');
                numberCheck.classList.add('invalid');
                numberSymbol.textContent = '❌';
            }

            // Check for special character
            if (/[!@#$%^&*]/.test(password)) {
                specialCheck.classList.remove('invalid');
                specialCheck.classList.add('valid');
                specialSymbol.textContent = '✔️';
            } else {
                specialCheck.classList.remove('valid');
                specialCheck.classList.add('invalid');
                specialSymbol.textContent = '❌';
            }
        }
    </script>
</body>
</html>