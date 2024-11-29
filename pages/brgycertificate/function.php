<?php
if(isset($_POST['btn_add'])){
    // Sanitize inputs
    $txt_name = htmlspecialchars(stripslashes(trim($_POST['txt_name'])), ENT_QUOTES, 'UTF-8');
    $txt_purpose = htmlspecialchars(stripslashes(trim($_POST['txt_purpose'])), ENT_QUOTES, 'UTF-8');
    $txt_age = (int) $_POST['txt_age']; // Cast age to integer
    $txt_bdate = htmlspecialchars(stripslashes(trim($_POST['txt_bdate'])), ENT_QUOTES, 'UTF-8');
    $txt_purok = htmlspecialchars(stripslashes(trim($_POST['txt_purok'])), ENT_QUOTES, 'UTF-8');
    $txt_cstatus = htmlspecialchars(stripslashes(trim($_POST['txt_cstatus'])), ENT_QUOTES, 'UTF-8');
    $date = date('Y-m-d'); // Current date in 'YYYY-MM-DD' format

    $chkdup = mysqli_query($con,"SELECT * from tblcertificate where name = '$txt_name'");
    $rows = mysqli_num_rows($chkdup);

    if(isset($_SESSION['role'])){
        $action = 'Added barangay certificate named of '.$txt_name;
        $iquery = mysqli_query($con,"INSERT INTO tbllogs (user,logdate,action) values ('Brgy.".$_SESSION['staff']."', NOW(), '".$action."')");
    }

    if($rows == 0){
        $query = mysqli_query($con,"INSERT INTO tblcertificate (Name, purpose, age, bdate, purok, civilstatus, barangay, dateRecorded, report_type) 
            values ('$txt_name', '$txt_purpose', '$txt_age', '$txt_bdate', '$txt_purok','$txt_cstatus',  '$off_barangay', '$date', 'Barangay Certificate') ") or die('Error: ' . mysqli_error($con));
        if($query == true)
        {
            $_SESSION['added'] = 1;
            header ("location: ".$_SERVER['REQUEST_URI']);
            exit();
        }   
    }
    else{
        $_SESSION['duplicate'] = 1;
        header ("location: ".$_SERVER['REQUEST_URI']);
        exit();
    }
}

if(isset($_POST['btn_save']))
{
    // Sanitize inputs
    $txt_edit_resident = htmlspecialchars(stripslashes(trim($_POST['txt_edit_resident'])), ENT_QUOTES, 'UTF-8');
    $txt_edit_purpose = htmlspecialchars(stripslashes(trim($_POST['txt_edit_purpose'])), ENT_QUOTES, 'UTF-8');
    $txt_edit_age = (int) $_POST['txt_edit_age']; // Cast to integer
    $txt_edit_bdate = htmlspecialchars(stripslashes(trim($_POST['txt_edit_bdate'])), ENT_QUOTES, 'UTF-8');
    $txt_edit_purok = htmlspecialchars(stripslashes(trim($_POST['txt_edit_purok'])), ENT_QUOTES, 'UTF-8');
    $txt_edit_cstatus = htmlspecialchars(stripslashes(trim($_POST['txt_edit_cstatus'])), ENT_QUOTES, 'UTF-8');

    $update_query = mysqli_query($con,"UPDATE tblcertificate set 
    purpose = '".$txt_edit_purpose."',
    age = '".$txt_edit_age."', 
    bdate = '".$txt_edit_bdate."', 
    purok = '".$txt_edit_purok."',
     civilstatus = '".$txt_edit_cstatus."' ") 
    or die('Error: ' . mysqli_error($con));

    if(isset($_SESSION['role'])){
        $action = 'Update Barangay Certificate named of '.$txt_edit_resident;
        $iquery = mysqli_query($con,"INSERT INTO tbllogs (user,logdate,action) values ('Brgy.".$_SESSION['staff']."', NOW(), '".$action."')");
    }

    if($update_query == true){
        $_SESSION['edited'] = 1;
        header("location: ".$_SERVER['REQUEST_URI']);
        exit();
    }
}

if(isset($_POST['btn_delete']))
{
    if(isset($_POST['chk_delete']))
    {
        foreach($_POST['chk_delete'] as $value)
        {
            $delete_query = mysqli_query($con,"DELETE from tblcertificate where id = '$value' ") or die('Error: ' . mysqli_error($con));
                    
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