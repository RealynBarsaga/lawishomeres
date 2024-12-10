<?php
include "connection.php"; // Ensure you include your database connection

// Handle single deletion
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_query = mysqli_query($con, "DELETE FROM tbllogs WHERE id = $delete_id");
    if ($delete_query) {
        header("Location: view_all_notifications");
        exit();
    } else {
        echo "Error deleting notification.";
    }
}

// Handle delete all
if (isset($_GET['delete_all'])) {
    $delete_all_query = mysqli_query($con, "DELETE FROM tbllogs");
    if ($delete_all_query) {
        header("Location: view_all_notifications");
        exit();
    } else {
        echo "Error deleting all notifications.";
    }
}

// Fetch notifications from the database
$squery = mysqli_query($con, "SELECT * FROM tbllogs ORDER BY logdate DESC");

// Separate notifications into 'New' and 'Earlier' categories
$new_notifications = [];
$earlier_notifications = [];

$current_time = time();

while ($notif = mysqli_fetch_assoc($squery)) {
    $notif_time = strtotime($notif['logdate']);
    $is_new = ($current_time - $notif_time) <= 86400; // 86400 seconds in a day

    if ($is_new) {
        $new_notifications[] = $notif;
    } else {
        $earlier_notifications[] = $notif;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Madridejos Home Residence Management System</title>
    <link rel="icon" type="x-icon" href="../img/lg.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 40%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }
        h2 {
            font-size: 18px;
            color: #333;
            margin-bottom: 10px;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin-bottom: 3px;
            position: relative;
        }
        .notification {
            font-size: 13px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
            color: #333;
            padding: 7px;
            display: block;
            max-width: 100%;
            border-radius: 4px;
            word-wrap: break-word;
            position: relative;
            margin-top: -8px;
        }
        .menu-button {
            background: none;
            border: none;
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 10px;
            font-size: 16px;
            color: #333;
        }
        .menu-button:after {
            content: "\22EE";
        }
        .dropdown-menu {
            display: none;
            position: absolute;
            top: 30px;
            right: 10px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        .dropdown-menu a {
            display: block;
            padding: 5px 10px;
            color: #333;
            text-decoration: none;
        }
        .dropdown-menu a:hover {
            background-color: #f4f4f4;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
        }
        .footer a {
            text-decoration: none;
            color: #007bff;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        /* Styles for h2 to make the text smaller */
        .menu,.h2 {
            font-size: 15px; /* Adjust this value as needed */
            margin: 10px 0;
            color: #333;
            margin-left: 6px;
        }
    </style>
</head>
<body>
    <div class="container">
    <h1>All Notifications</h1>

    <!-- Delete All Button -->
    <div style="text-align: right; margin-top: -42px;">
        <a href="view_all_notifications?delete_all=true" 
           style="text-decoration: none; color: gray;
           padding: 8px 12px; border-radius: 4px; font-size: 14px;">
            Delete All/<a href="dashboard/dashboard" style="text-decoration: none; color: gray;
           padding: 8px 12px; border-radius: 4px; font-size: 14px;">Dashboard</a>
        </a>
    </div>

        <!-- Display New Notifications -->
        <ul>
        <h2 class="menu">New</h2>
            <?php
            if (!empty($new_notifications)) {
                foreach ($new_notifications as $notif) {
                    $user = isset($notif['user']) ? htmlspecialchars($notif['user']) : 'Unknown user';
                    $logdate = isset($notif['logdate']) ? htmlspecialchars($notif['logdate']) : 'Unknown logdate';
                    $action = isset($notif['action']) ? htmlspecialchars($notif['action']) : 'No action available';
                    
                    $message = sprintf(
                        '<span class="notification">%s (%s)<br> %s.</span>',
                        $user,
                        $logdate,
                        $action
                    );
                    
                    echo '<li>' . $message . '
                            <button class="menu-button" onclick="toggleMenu('.$notif['id'].')"></button>
                            <div id="menu-'.$notif['id'].'" class="dropdown-menu">
                                <a href="view_all_notifications?delete_id='.$notif['id'].'">Delete</a>
                            </div>
                          </li>';
                }
            } else {
                echo '<li style="color: #d9534f; text-align: center;">No new notifications.</li>';
            }
            ?>
        </ul>

        <!-- Display Earlier Notifications -->
        <ul>
        <h2 class="menu">Earlier</h2>
            <?php
            if (!empty($earlier_notifications)) {
                foreach ($earlier_notifications as $notif) {
                    $user = isset($notif['user']) ? htmlspecialchars($notif['user']) : 'Unknown user';
                    $logdate = isset($notif['logdate']) ? htmlspecialchars($notif['logdate']) : 'Unknown logdate';
                    $action = isset($notif['action']) ? htmlspecialchars($notif['action']) : 'No action available';
                    
                    $message = sprintf(
                        '<span class="notification">%s (%s)<br> %s.</span>',
                        $user,
                        $logdate,
                        $action
                    );
                    
                    echo '<li>' . $message . '
                            <button class="menu-button" onclick="toggleMenu('.$notif['id'].')"></button>
                            <div id="menu-'.$notif['id'].'" class="dropdown-menu">
                                <a href="view_all_notifications?delete_id='.$notif['id'].'">Delete</a>
                            </div>
                          </li>';
                }
            } else {
                echo '<li style="color: #d9534f; text-align: center;">No earlier notifications.</li>';
            }
            ?>
        </ul>
    </div>
    
    <script>
        function toggleMenu(id) {
            var menu = document.getElementById("menu-" + id);
            menu.style.display = menu.style.display === "block" ? "none" : "block";
        }

        // Close dropdown menus when clicking outside
        document.addEventListener('click', function(e) {
            var menus = document.querySelectorAll('.dropdown-menu');
            menus.forEach(function(menu) {
                if (!menu.contains(e.target) && !e.target.classList.contains('menu-button')) {
                    menu.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
