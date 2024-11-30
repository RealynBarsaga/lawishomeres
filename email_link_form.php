<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Madridejos Home Residence Management System</title>
    <link rel="icon" type="x-icon" href="img/lg.png">
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

        .res {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-top: 20px;
            margin-bottom: 10px;
            text-align: center;
        }

        p.text-center {
            font-size: 15px;
            color: #333;
            line-height: 1.5;
            margin-top: 0;
            margin-bottom: 30px;
            text-align: center;
        }

        .container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: 580px;
            width: 100%;
        }

        .container .form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn {
            background-image: url('img/bg.jpg');
            border: none;
            color: #fff;
            width: 100%;
            border-radius: 5px;
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
        }

        .btn:hover {
            border: none;
            color: #fff;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            width: 94%;
            padding: 10px 15px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #007bff;
            outline: none;
            background-color: #fff;
        }

        .back-link {
            text-align: right;
            margin-top: 20px;
        }

        .back-link button {
            background-color: #f0f2f5;
            border: 1px solid #ddd;
            padding: 0;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            height: 40px;
            border-radius: 5px;
        }

        .back-link button:hover {
            background-color: #e1e4e8;
        }

        .back-link a {
            color: #000000;
            text-decoration: none;
            font-size: 16px;
        }

        .back-link a:hover {
            text-decoration: none;
            color: #000000;
        }

        .modal {
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            display: flex;
            background-color: rgba(0, 0, 0, 0.5);
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
            position: relative;
            margin-left: 449px;
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
        <div class="row">
            <div class="col-md-4 offset-md-4 form">
                <form action="https://lawishomeresidences.com/email_link_process" method="POST" class="submit-once" autocomplete="off">
                    <p class="res">Reset via Email Link</p>
                    <p class="text-center">Enter the email address associated with your account and we will send you a link to reset your password.</p>
                    <br>
                    <?php if (!empty($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger" style="color: #a94442;">
                            <?php echo $_SESSION['error_message']; ?>
                        </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <input type="email" name="email" class="form-control" placeholder="Enter your email" required value="">
                    </div>
                    <div class="form-group">
                        <button type="submit" name="reset" class="btn">Send Reset Link</button>
                        <div class="back-link">
                            <a href="../forgot_password_option">
                                <button type="button" class="login-btn">Back</button>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php if (!empty($_SESSION['success_message'])): ?>
        <!-- Success Modal structure -->
        <div id="success-modal" class="modal" style="display: block;">
            <div class="modal-content">
                <span class="modal-title">Success</span>
                <p><?php echo $_SESSION['success_message']; ?></p>
                <button id="success-ok-button" class="btn-ok">OK</button>
            </div>
        </div>  
    <?php endif; ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("success-ok-button").addEventListener("click", function() {
            window.location.href = '../email_link_form';
        });
    });
</script>
</body>
</html>