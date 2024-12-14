<?php
$off_barangay = $_SESSION["barangay"] ?? "";
// Start output buffering
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Madridejos Household Management System</title>
    <style>
        .form-group {
            position: relative;
        }
        
        /* Style for the eye icon */
        .input-group-text {
            position: absolute;
            top: 70%;
            right: 10px; /* Adjust as needed */
            transform: translateY(-50%);
            background: transparent;
            border: none;
            cursor: pointer;
            font-size: 16px; /* Adjust size as needed */
            color: #aaa; /* Light color for the icon */
        }
    </style>
</head>
<body>
<header class="header">
    <a href="../dashboard/dashboard" class="logo" style="font-size: 11px; font-family: Source Sans Pro, sans-serif;">
        Mad Household Management System
    </a>
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>
        <div class="navbar-right">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="glyphicon glyphicon-user"></i><span>Brgy. <?= $_SESSION['staff'] ?> <i class="caret"></i></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header bg-light-blue">
                            <p>Brgy. <?= $_SESSION['staff'] ?></p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="#" class="btn btn-default btn-flat" data-toggle="modal" data-target="#editProfileModal">Change Password</a>
                            </div>
                            <div class="pull-right">
                                <a href="../../logout.php" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
<div id="editProfileModal" class="modal fade">
    <form method="post">
        <div class="modal-dialog modal-sm" style="width:300px !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Change Password</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            if($_SESSION['role'] == "Staff"){
                                $user = mysqli_query($con, "SELECT * FROM tblstaff WHERE id = '".$_SESSION['userid']."'");
                                while($row = mysqli_fetch_array($user)){
                                    echo '
                                        <div class="form-group">
                                            <label>Username:</label>
                                            <input name="txt_username" id="txt_username" class="form-control input-sm" type="text" value="'.$row['username'].'" 
                                            pattern="^(?!\s)(?!.*<script>)(?!.*<\/script>).*[\w\s]*$" 
                                            title="Spaces and <script></script> tags are not allowed."/>
                                        </div>
                                        <div class="form-group">
                                            <label>Email:</label>
                                            <input name="txt_email" id="txt_email" class="form-control input-sm" type="email" placeholder="Ex: juan@sample.com" required
                                            pattern="^(?!\s)(?!.*<script>)(?!.*<\/script>).*[\w\s]*$" 
                                            title="Spaces and <script></script> tags are not allowed."/>
                                        </div>
                                        <div class="form-group">
                                            <label>Password:</label>
                                            <input name="txt_password" id="txt_password" class="form-control input-sm" type="password" placeholder="•••••••••••" required
                                            pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{10,}$" 
                                            title="Password must be at least 10 characters long, contain at least one uppercase letter, one number, and one special character."/>
                                            <span class="input-group-text">
                                                <i class="fa fa-eye" id="togglePassword"></i>
                                            </span>
                                        </div>';
                                } 
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default btn-sm" data-dismiss="modal" value="Cancel"/>
                    <input type="submit" class="btn btn-primary btn-sm" id="btn_saveeditProfile" name="btn_saveeditProfile" value="Save"/>
                </div>
            </div>
        </div>
    </form>
</div>
<?php include "../edit_notif.php"; ?>


<script>
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('txt_password');
    togglePassword.addEventListener('click', function (e) {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
    });
</script>

<?php
// Handle form submission
if(isset($_POST['btn_saveeditProfile'])){
    $username = htmlspecialchars(stripslashes(trim($_POST['txt_username'])));
    $password = htmlspecialchars(stripslashes(trim($_POST['txt_password'])));
    $email = htmlspecialchars(stripslashes(trim($_POST['txt_email'])));

    $hashed = password_hash($password, PASSWORD_ARGON2ID);
    $updstaff = mysqli_query($con, "UPDATE tblstaff SET username = '$username', email = '$email', password = '$hashed' WHERE id = '".$_SESSION['userid']."'");
    
    if($updstaff){
        // Redirect after update
        $_SESSION['edited'] = 1;
        header("Location: ".$_SERVER['REQUEST_URI']);
        exit();
    }
}
?>
</body>
</html>