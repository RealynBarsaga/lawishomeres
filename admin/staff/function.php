<?php
if (isset($_POST['btn_add'])) {
    $txt_name = htmlspecialchars(strip_tags(trim($_POST['txt_name'])), ENT_QUOTES, 'UTF-8');
    $txt_uname = htmlspecialchars(strip_tags(trim($_POST['txt_uname'])), ENT_QUOTES, 'UTF-8');
    $txt_email = htmlspecialchars(strip_tags(trim($_POST['txt_email'])), ENT_QUOTES, 'UTF-8');
    $txt_pass = htmlspecialchars(strip_tags(trim($_POST['txt_pass'])), ENT_QUOTES, 'UTF-8');
    $txt_compass = htmlspecialchars(strip_tags(trim($_POST['txt_compass'])), ENT_QUOTES, 'UTF-8');
    $filename = date("mdGis") . ".png";
    $tmp_name = $_FILES['logo']['tmp_name'];
    $folder = "./logo/" . $filename;

    // Check if the username already exists
    $su = mysqli_query($con, "SELECT * FROM tblstaff WHERE username = '$txt_uname'");
    $ct = mysqli_num_rows($su);

    if ($ct == 0) {
        $hashed = password_hash($txt_pass, PASSWORD_DEFAULT);
        $query = mysqli_query($con, "INSERT INTO tblstaff (name, username, email, password, compass,logo) 
            VALUES ('$txt_name', '$txt_uname', '$txt_email', '$hashed', '$hashed', '$filename')") or die('Error: ' . mysqli_error($con));
        if ($query) {
            move_uploaded_file($tmp_name, $folder);
            $_SESSION['added'] = 1;

            // Log the action if the user has the appropriate role
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'Administrator') {
                $action = 'Added Barangay ' . $txt_name;
                $iquery = mysqli_query($con, "INSERT INTO tbllogs (user, logdate, action) VALUES ('Administrator', NOW(), '$action')");
            }

            header("location: " . $_SERVER['REQUEST_URI']);
            exit();
        }
    } else {
        $_SESSION['duplicateuser'] = 1;
        header("location: " . $_SERVER['REQUEST_URI']);
        exit();
    }
}

if (isset($_POST['btn_save'])) {
    $txt_id = htmlspecialchars(strip_tags(trim($_POST['hidden_id'])), ENT_QUOTES, 'UTF-8');
    $txt_edit_name = htmlspecialchars(strip_tags(trim($_POST['txt_edit_name'])), ENT_QUOTES, 'UTF-8');
    $txt_edit_uname = htmlspecialchars(strip_tags(trim($_POST['txt_edit_uname'])), ENT_QUOTES, 'UTF-8');
    $txt_edit_email = htmlspecialchars(strip_tags(trim($_POST['txt_edit_email'])), ENT_QUOTES, 'UTF-8');
    $txt_edit_pass = htmlspecialchars(strip_tags(trim($_POST['txt_edit_pass'])), ENT_QUOTES, 'UTF-8');
    $txt_edit_compass = htmlspecialchars(strip_tags(trim($_POST['txt_edit_compass'])), ENT_QUOTES, 'UTF-8');


    // Check if the username already exists
    // $su = mysqli_query($con, "SELECT * FROM tblstaff WHERE username = '$txt_edit_uname'");
    // $ct = mysqli_num_rows($su);

    if ($_FILES['logo']['error'] > 0) {
        if (!empty($txt_edit_pass)) {
            $hashed = password_hash($txt_edit_pass, PASSWORD_DEFAULT);
        $update_query = mysqli_query($con, "UPDATE tblstaff 
            SET name = '$txt_edit_name', username = '$txt_edit_uname', email = '$txt_edit_email', password = '$hashed', compass = '$hashed' 
            WHERE id = '$txt_id'") or die('Error: ' . mysqli_error($con));
        }else{
            
        $update_query = mysqli_query($con, "UPDATE tblstaff 
            SET name = '$txt_edit_name', username = '$txt_edit_uname'
            WHERE id = '$txt_id'") or die('Error: ' . mysqli_error($con));
        }
    }else{
        $filename = date("mdGis") . ".png";
        $tmp_name = $_FILES['logo']['tmp_name'];
        $folder = "./logo/" . $filename;

        if (!empty($txt_edit_pass)) {
            $hashed = password_hash($txt_edit_pass, PASSWORD_DEFAULT);
            move_uploaded_file($tmp_name, $folder);
        $update_query = mysqli_query($con, "UPDATE tblstaff 
            SET name = '$txt_edit_name', username = '$txt_edit_uname', email = '$txt_edit_email', password = '$hashed', compass = '$hashed', logo = '$filename' 
            WHERE id = '$txt_id'") or die('Error: ' . mysqli_error($con));
           
        }else{
            move_uploaded_file($tmp_name, $folder);
        $update_query = mysqli_query($con, "UPDATE tblstaff 
            SET name = '$txt_edit_name', username = '$txt_edit_uname', logo = '$filename' 
            WHERE id = '$txt_id'") or die('Error: ' . mysqli_error($con));
            
        }
    }

    if ($update_query) {
        $_SESSION['edited'] = 1;

        // Log the action if the user has the appropriate role
        if (isset($_SESSION['role'])) {
            $action = 'Updated Barangay ' . $txt_edit_name;
            $iquery = mysqli_query($con, "INSERT INTO tbllogs (user, logdate, action) VALUES ('Administrator', NOW(), '$action')");
        }
        
        header("location: " . $_SERVER['REQUEST_URI']);
        exit();
    }
}

if (isset($_POST['btn_del'])) {
    if (isset($_POST['hidden_id'])) {
        $txt_id = $_POST['hidden_id'];

        $delete_query = mysqli_query($con, "DELETE FROM tblstaff WHERE id = '$txt_id'");

        if ($delete_query) {
            $_SESSION['delete'] = 1;
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        } else {
            die('Error: ' . mysqli_error($con));
        }
    } else {
        echo 'Error: ID not provided.';
    }
}

if(isset($_POST['btn_delete']))
{
    if(isset($_POST['chk_delete']))
    {
        foreach($_POST['chk_delete'] as $value)
        {
            $delete_query = mysqli_query($con,"DELETE from tblstaff where id = '$value' ") or die('Error: ' . mysqli_error($con));
                    
            if($delete_query == true)
            {
                $_SESSION['delete'] = 1;
                header("location: ".$_SERVER['REQUEST_URI']);
                exit();
            }
        }
    }
}

?>