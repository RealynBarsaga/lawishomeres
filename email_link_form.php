<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Madridejos Home Residence Management System</title>
    <link rel="icon" type="x-icon" href="img/lg.png">
    <style>
        /* Add your CSS here */
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form">
                <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
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
                            <a href="forgot_password_option.php">
                                <button type="button" class="login-btn">Back</button>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php if (!empty($_SESSION['success_message'])): ?>
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
            window.location.href = 'email_link_form.php'; // Redirect on success
        });
    });
</script>
</body>
</html>