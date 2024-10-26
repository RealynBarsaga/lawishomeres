<?php
session_start();
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$email = ''; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Madridejos Home Residence Management System</title>
    <link rel="icon" type="x-icon" href="img/lg.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
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
        font-family: 'Poppins', sans-serif;
    }
    .container {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    .container .form {
        background: #fff;
        padding: 30px 35px;
        border-radius: 5px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    }
    .btn {
        background-image: url('img/bg.jpg');
        border: 1px solid #000;
        color: #fff;
        width: 100%;
        border-radius: 15px;
        padding: 10px;
        font-size: 16px;
        cursor: pointer;
    }
    .btn:hover {
        border: 1px solid #000;
        color: #fff;
        cursor: pointer;
    }
    .back-link {
        text-align: right;
        margin-top: 10px;
        margin-right: 3px;
    }
    .back-link a {
        color: black;
        text-decoration: none;
    }
    .back-link a:hover {
        text-decoration: underline;
    }
</style>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form">
                <form action="forgot_password_process.php" method="POST" autocomplete="off">
                    <h2 class="text-center">Forgot Password</h2>
                    <p class="text-center">Enter your email address</p>
                    <br>
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <input type="email" name="email" class="form-control" placeholder="Enter your email" required value="<?php echo htmlspecialchars($email); ?>">
                    </div>
                    <div class="form-group">
                        <button type="submit" name="reset" class="btn">Send Reset Link</button>
                    </div>
                    <div class="back-link">
                        <a href="login.php?pages=login">Login</a>
                    </div>
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
        <!-- Add the script to handle redirection -->
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                document.getElementById("success-ok-button").addEventListener("click", function() {
                    window.location.href = 'forgot_password.php?pages=forgot_password';
                });
            });
        </script>
    <?php endif; ?>
</body>
</html>
<?php
// Clear session messages after displaying them
unset($_SESSION['error_message']);
unset($_SESSION['success_message']);
?>