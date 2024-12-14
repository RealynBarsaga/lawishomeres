<?php
ob_start(); // Ensure output buffering is started before any output

// Database connection (assuming $con is the database connection)
require_once('connection.php');

// Fetch the total number of notifications
$countQuery = mysqli_query($con, "SELECT COUNT(*) AS count FROM tbllogs");
$countResult = mysqli_fetch_assoc($countQuery);
$notificationCount = $countResult['count'];

// Fetch notifications
$current_time = time();
$new_notifications = [];
$earlier_notifications = [];

$squery = mysqli_query($con, "SELECT * FROM tbllogs ORDER BY logdate DESC");
while ($notif = mysqli_fetch_assoc($squery)) {
    $notif_time = strtotime($notif['logdate']);
    $is_new = ($current_time - $notif_time) <= 86400; // 86400 seconds in a day
    
    if ($is_new) {
        $new_notifications[] = $notif;
    } else {
        $earlier_notifications[] = $notif;
    }
}

if (isset($_POST['btn_saveeditProfile'])) {
    $username = mysqli_real_escape_string($con, (htmlspecialchars(stripslashes(trim($_POST['txt_username'])))));
    $password = mysqli_real_escape_string($con, password_hash(htmlspecialchars(stripslashes(trim($_POST['txt_password']))), PASSWORD_ARGON2ID));
    $email = mysqli_real_escape_string($con, (htmlspecialchars(stripslashes(trim($_POST['txt_email'])))));

    /* $hashedpassword = password_hash($password, PASSWORD_BCRYPT); */

    // Consider hashing the password before storing it
    $updadmin = mysqli_query($con, "UPDATE tbluser SET username = '$username', email = '$email', password = '$password' WHERE id = '".mysqli_real_escape_string($con, (htmlspecialchars(stripslashes(trim($_SESSION['userid'])))))."'") or die('Error: ' . mysqli_error($con));


    if ($updadmin) {
        $_SESSION['edited'] = 1;
        header("location: " . $_SERVER['REQUEST_URI']);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Madridejos Household Management System</title>
    <style>
        .footer {
            margin-top: -1px;
            text-align: center;
        }
        .footer a {
            text-decoration: none;
            color: #007bff;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        .notification {
            font-size: 13px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
            color: #333;
            padding: 7px;
            margin: 11px;
            display: block;
            width: 296px;
            margin-left: 1px;
            border-radius: 4px;
            word-wrap: break-word;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: -8px;
        }
        .notification:hover {
            background-color: #e0e0e0;
        }
        /* Styles for h2 to make the text smaller */
        .dropdown-menu h2 {
            font-size: 14px; /* Adjust this value as needed */
            margin: 10px 0;
            color: #333;
            margin-left: 6px;
        }
        .form-group {
            position: relative;
        }
        
        /* Style for the eye icon */
        .input-group-text {
            position: absolute;
            top: 70%;
            right: 28px; /* Adjust as needed */
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
    <a href="../../admin/dashboard/dashboard" class="logo" style="font-size: 11px; font-family: Source Sans Pro, sans-serif;">
        Madridejos Household Management
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
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="glyphicon glyphicon-bell"></i>
                        <span class="label label-warning"><?php echo htmlspecialchars($notificationCount); ?></span>
                    </a>
                    <ul class="dropdown-menu" style="width: 300px;">
                        <li class="header">You have <?php echo htmlspecialchars($notificationCount); ?> notifications</li>
                        <li>
                            <ul class="menu">
                                <h2>New</h2>
                                <?php
                                if (!empty($new_notifications)) {
                                    foreach ($new_notifications as $notif) {
                                        $user = isset($notif['user']) ? htmlspecialchars($notif['user']) : 'Unknown user';
                                        $logdate = isset($notif['logdate']) ? htmlspecialchars($notif['logdate']) : 'Unknown logdate';
                                        $action = isset($notif['action']) ? htmlspecialchars($notif['action']) : 'No action available';
                                        echo '<li style="margin-bottom: 2px;">
                                                <span class="notification">'.$user.' ('.$logdate.')<br>'.$action.'</span>
                                              </li>';
                                    }
                                } else {
                                    echo '<li style="color: #d9534f; text-align: center;">No new notifications.</li>';
                                }
                                ?>
                                
                                <h2>Earlier</h2>
                                <?php
                                if (!empty($earlier_notifications)) {
                                    foreach ($earlier_notifications as $notif) {
                                        $user = isset($notif['user']) ? htmlspecialchars($notif['user']) : 'Unknown user';
                                        $logdate = isset($notif['logdate']) ? htmlspecialchars($notif['logdate']) : 'Unknown logdate';
                                        $action = isset($notif['action']) ? htmlspecialchars($notif['action']) : 'No action available';
                                        echo '<li style="margin-bottom: 2px;">
                                                <span class="notification">'.$user.' ('.$logdate.')<br>'.$action.'</span>
                                              </li>';
                                    }
                                } else {
                                    echo '<li style="color: #d9534f; text-align: center;">No earlier notifications.</li>';
                                }
                                ?>
                            </ul>
                        </li>
                        <li class="footer"><a href="../view_all_notifications">View all</a></li>
                    </ul>
                </li>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="glyphicon glyphicon-user"></i><span>Administrator <i class="caret"></i></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header bg-light-blue">
                            <p>Administrator</p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="#" class="btn btn-default btn-flat" data-toggle="modal" data-target="#editProfileModal">Change Password</a>
                            </div>
                            <div class="pull-right">
                                <a href="../../admin/logout.php" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

<!-- Edit Profile Modal -->
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
                                $user = mysqli_query($con, "SELECT * FROM tbluser WHERE id = '".$_SESSION['userid']."'");
                                while ($row = mysqli_fetch_array($user)) {
                                    echo '
                                        <div class="form-group">
                                            <label>Username:</label>
                                            <input name="txt_username" id="txt_username" class="form-control input-sm" type="text" value="'.$row['username'].'" 
                                            pattern="^(?!\s)(?!.*<script>)(?!.*<\/script>).*[\w\s]*$" 
                                            title="Spaces and <script></script> tags are not allowed." style="width: 266px;"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Email:</label>
                                            <input name="txt_email" id="txt_email" class="form-control input-sm" type="email" placeholder="Ex: juan@sample.com" required
                                            pattern="^(?!\s)(?!.*<script>)(?!.*<\/script>).*[\w\s]*$" 
                                            title="Spaces and <script></script> tags are not allowed." style="width: 266px;"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Password:</label>
                                            <input name="txt_password" id="txt_password" class="form-control input-sm" type="password" placeholder="•••••••••••" required
                                            pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{10,}$" 
                                            title="Password must be at least 10 characters long, contain at least one uppercase letter, one number, and one special character." style="width: 266px;"/>
                                            <span class="input-group-text">
                                                <i class="fa fa-eye" id="togglePassword"></i>
                                            </span>
                                        </div>';
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

    
    // Handle dropdown toggle for notifications
    document.querySelectorAll('.dropdown-toggle').forEach(function(dropdown) {
        dropdown.addEventListener('click', function() {
            var menu = this.nextElementSibling;
            if (menu.style.display === 'block') {
                menu.style.display = 'none';
            } else {
                menu.style.display = 'block';
            }
        });
    });

    // Close dropdown if clicked outside
    window.onclick = function(event) {
        if (!event.target.matches('.dropdown-toggle')) {
            var dropdowns = document.getElementsByClassName('dropdown-menu');
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.style.display === 'block') {
                    openDropdown.style.display = 'none';
                }
            }
        }
    };

    // Initialize Bootstrap modal
    document.querySelectorAll('.modal').forEach(function(modal) {
        $(modal).modal({
            backdrop: 'static',
            keyboard: false
        });
    });
</script>
</body>
</html>