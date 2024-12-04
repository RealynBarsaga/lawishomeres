<?php
if (isset($_POST['btn_add'])) {
    // Sanitize inputs
    $txt_cnum = htmlspecialchars(strip_tags(trim($_POST['txt_cnum'])), ENT_QUOTES, 'UTF-8');
    $txt_name = htmlspecialchars(strip_tags(trim($_POST['txt_name'])), ENT_QUOTES, 'UTF-8');
    $txt_purpose = htmlspecialchars(strip_tags(trim($_POST['txt_purpose'])), ENT_QUOTES, 'UTF-8');
    $txt_ornum = htmlspecialchars(strip_tags(trim($_POST['txt_ornum'])), ENT_QUOTES, 'UTF-8');
    $txt_amount = htmlspecialchars(strip_tags(trim($_POST['txt_amount'])), ENT_QUOTES, 'UTF-8');
    $txt_age = (int) $_POST['txt_age']; // Cast age to integer
    $txt_bdate = htmlspecialchars(strip_tags(trim($_POST['txt_bdate'])), ENT_QUOTES, 'UTF-8');
    $txt_purok = htmlspecialchars(strip_tags(trim($_POST['txt_purok'])), ENT_QUOTES, 'UTF-8');
    $txt_bplace = htmlspecialchars(strip_tags(trim($_POST['txt_bplace'])), ENT_QUOTES, 'UTF-8');
    $txt_cstatus = htmlspecialchars(strip_tags(trim($_POST['txt_cstatus'])), ENT_QUOTES, 'UTF-8');
    $date = date('Y-m-d'); // Current date in 'YYYY-MM-DD' format
    $off_barangay = $_SESSION["barangay"] ?? "";

    // Check for duplicate clearance
    $chkdup = mysqli_query($con, "SELECT * from tblclearance where name = '$txt_name'");
    $rows = mysqli_num_rows($chkdup);


    // Insert clearance if no duplicates
    if ($rows == 0) {
        $query = mysqli_query($con, "INSERT INTO tblclearance 
            (clearanceNo, name, purpose, orNo, samount, dateRecorded, recordedBy, barangay, age, bdate, purok, bplace, civilstatus, report_type) 
            values ('$txt_cnum','$txt_name', '$txt_purpose', '$txt_ornum', '$txt_amount', '$date', '".$_SESSION['username']."', '$off_barangay', '$txt_age', '$txt_bdate', '$txt_purok', '$txt_bplace', '$txt_cstatus', 'Clearance')") 
            or die('Error: ' . mysqli_error($con));

        // Handle successful insert
        if ($query == true) {
            $_SESSION['added'] = 1;

            // Log the action if the session role is set
            if (isset($_SESSION['role'])) {
                $action = 'Added Clearance named of ' . $txt_name;
                $iquery = mysqli_query($con, "INSERT INTO tbllogs (user, logdate, action) values ('Brgy." . $_SESSION['staff'] . "', NOW(), '" . $action . "')");
            }

            header("location: " . $_SERVER['REQUEST_URI']);
            exit();
        }   
    } else {
        $_SESSION['duplicate'] = 1;
        header("location: " . $_SERVER['REQUEST_URI']);
        exit();
    }
}


if(isset($_POST['btn_req'])){
    $chkblot = mysqli_query($con,"select * from tblresident where '".$_SESSION['userid']."' not in (select complainant from tblblotter)");
    $num_row = mysqli_num_rows($chkblot);
    if($num_row > 0)
    {
        $chk = mysqli_query($con,"select * from tblresident where id = '".$_SESSION['userid']."' ");
        while($row = mysqli_fetch_array($chk)){

            if($row['lengthofstay'] < 6){
                $_SESSION['lengthofstay'] = 1;
                header ("location: ".$_SERVER['REQUEST_URI']);
                exit();
            }
            else{
                $txt_purpose = $_POST['txt_purpose'];
                $date = date('Y-m-d');
                $reqquery = mysqli_query($con,"INSERT INTO tblclearance (clearanceNo,residentid,purpose,orNo,samount,dateRecorded,recordedBy,status) 
                    values ('','".$_SESSION['userid']."','','','$date','".$_SESSION['role']."','New') ")or die('Error: ' . mysqli_error($con));

                if($reqquery == true)
                {
                    header ("location: ".$_SERVER['REQUEST_URI']);
                    exit();
                } 
            }
        }
    } 
    else{
        $_SESSION['blotter'] = 1;
        header ("location: ".$_SERVER['REQUEST_URI']);
        exit();
    }
}

if(isset($_POST['btn_approve']))
{
    $txt_id = $_POST['hidden_id'];
    $txt_cnum = $_POST['txt_cnum'];
    $txt_findings = $_POST['txt_findings'];
    $txt_ornum = $_POST['txt_ornum'];
    $txt_amount = $_POST['txt_amount'];

    $approve_query = mysqli_query($con,"UPDATE tblclearance set clearanceNo= '".$txt_cnum."', findings = '".$txt_findings."', orNo = '".$txt_ornum."', samount = '".$txt_amount."', status='Approved' where id = '".$txt_id."' ") or die('Error: ' . mysqli_error($con));

    if($approve_query == true){
        header("location: ".$_SERVER['REQUEST_URI']);
        exit();
    }
}

if(isset($_POST['btn_disapprove']))
{

    $txt_id = $_POST['hidden_id'];
    $txt_findings = $_POST['txt_findings'];
    $disapprove_query = mysqli_query($con,"UPDATE tblclearance set findings = '".$txt_findings."' , status='Disapproved' where id = '".$txt_id."' ") or die('Error: ' . mysqli_error($con));

    if($disapprove_query == true){
        header("location: ".$_SERVER['REQUEST_URI']);
        exit();
    }
}

if(isset($_POST['btn_save'])) {
    // Sanitize inputs
    $txt_id = htmlspecialchars(strip_tags(trim($_POST['hidden_id'])), ENT_QUOTES, 'UTF-8');
    $txt_edit_residentname = htmlspecialchars(strip_tags(trim($_POST['txt_edit_residentname'])), ENT_QUOTES, 'UTF-8');
    $txt_edit_cnum = htmlspecialchars(strip_tags(trim($_POST['txt_edit_cnum'])), ENT_QUOTES, 'UTF-8');
    $txt_edit_purpose = htmlspecialchars(strip_tags(trim($_POST['txt_edit_purpose'])), ENT_QUOTES, 'UTF-8');
    $txt_edit_ornum = htmlspecialchars(strip_tags(trim($_POST['txt_edit_ornum'])), ENT_QUOTES, 'UTF-8');
    $txt_edit_amount = htmlspecialchars(strip_tags(trim($_POST['txt_edit_amount'])), ENT_QUOTES, 'UTF-8');
    $txt_edit_age = (int) $_POST['txt_edit_age']; // Cast to integer
    $txt_edit_bdate = htmlspecialchars(strip_tags(trim($_POST['txt_edit_bdate'])), ENT_QUOTES, 'UTF-8');
    $txt_edit_purok = htmlspecialchars(strip_tags(trim($_POST['txt_edit_purok'])), ENT_QUOTES, 'UTF-8');
    $txt_edit_bplace = htmlspecialchars(strip_tags(trim($_POST['txt_edit_bplace'])), ENT_QUOTES, 'UTF-8');
    $txt_edit_cstatus = htmlspecialchars(strip_tags(trim($_POST['txt_edit_cstatus'])), ENT_QUOTES, 'UTF-8');
   

    // Update query including the new fields
    $update_query = mysqli_query($con,"UPDATE tblclearance SET 
    Name = '".$txt_edit_residentname."',
    clearanceNo = '".$txt_edit_cnum."', 
    purpose = '".$txt_edit_purpose."', 
    orNo = '".$txt_edit_ornum."', 
    samount = '".$txt_edit_amount."', 
    age = '".$txt_edit_age."', 
    bdate = '".$txt_edit_bdate."', 
    purok = '".$txt_edit_purok."',
    bplace = '".$txt_edit_bplace."',
    civilstatus = '".$txt_edit_cstatus."' 
    WHERE id = '".$txt_id."' ") or die('Error: ' . mysqli_error($con));


    if($update_query == true){
        $_SESSION['edited'] = 1;

        if(isset($_SESSION['role'])){
            $action = 'Update Clearance name of '.$txt_edit_residentname;
            $iquery = mysqli_query($con,"INSERT INTO tbllogs (user, logdate, action) values ('Brgy.".$_SESSION['staff']."', NOW(), '".$action."')");
        }
        
        header("location: ".$_SERVER['REQUEST_URI']);
        exit();
    }
}

if (isset($_POST['btn_del'])) {
    if (isset($_POST['hidden_id'])) {
        $txt_id = $_POST['hidden_id'];

        // Delete the record based on the hidden_id from the modal
        $delete_query = mysqli_query($con, "DELETE FROM tblclearance WHERE id = '$txt_id'");

        if ($delete_query) {
            $_SESSION['delete'] = 1;
            header("Location: " . $_SERVER['REQUEST_URI']); // Reload the page to reflect the changes
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
            $delete_query = mysqli_query($con,"DELETE from tblclearance where id = '$value' ") or die('Error: ' . mysqli_error($con));
                    
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