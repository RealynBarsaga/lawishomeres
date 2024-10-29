<?php
if (isset($_POST['btn_add'])) {
    // Set Content Security Policy
    header("Content-Security-Policy: script-src 'self';");
    
    // Sanitize inputs
    $txt_cnum = htmlspecialchars(stripslashes(trim($_POST['txt_cnum'])), ENT_QUOTES, 'UTF-8');
    $txt_name = htmlspecialchars(stripslashes(trim($_POST['txt_name'])), ENT_QUOTES, 'UTF-8');
    $txt_purpose = htmlspecialchars(stripslashes(trim($_POST['txt_purpose'])), ENT_QUOTES, 'UTF-8');
    $txt_ornum = htmlspecialchars(stripslashes(trim($_POST['txt_ornum'])), ENT_QUOTES, 'UTF-8');
    $txt_amount = htmlspecialchars(stripslashes(trim($_POST['txt_amount'])), ENT_QUOTES, 'UTF-8');
    $txt_age = (int) $_POST['txt_age']; // Cast age to integer
    $txt_bdate = htmlspecialchars(stripslashes(trim($_POST['txt_bdate'])), ENT_QUOTES, 'UTF-8');
    $txt_purok = htmlspecialchars(stripslashes(trim($_POST['txt_purok'])), ENT_QUOTES, 'UTF-8');
    $date = date('Y-m-d'); // Current date in 'YYYY-MM-DD' format
    $off_barangay = $_SESSION["barangay"] ?? "";
    
    // Basic Validation
    if (empty($txt_cnum) || empty($txt_name) || empty($txt_purpose) || empty($txt_ornum) || 
        empty($txt_amount) || empty($txt_age) || empty($txt_bdate) || empty($txt_purok)) {
        die('Required fields are missing.');
    }
    
    // Validate contact number (example: numeric and possibly length validation)
    if (!preg_match('/^[0-9]{10,12}$/', $txt_cnum)) {
        die('Invalid contact number. It must be between 10 and 12 digits.');
    }
    
    // Validate name (letters and spaces allowed)
    if (!preg_match('/^[a-zA-Z\s]+$/', $txt_name)) {
        die('Invalid name format. Only letters and spaces are allowed.');
    }
    
    // Validate OR number (assuming it's numeric)
    if (!preg_match('/^[0-9]+$/', $txt_ornum)) {
        die('Invalid OR number. It must be numeric.');
    }
    
    // Validate amount (assuming it's a positive decimal)
    if (!preg_match('/^\d+(\.\d{1,2})?$/', $txt_amount)) {
        die('Invalid amount format. Only positive numbers are allowed with up to two decimal places.');
    }
    
    // Validate age (must be a positive integer)
    if ($txt_age <= 0) {
        die('Invalid age. It must be a positive number.');
    }
    
    // Validate birthdate (check if it's a valid date in 'YYYY-MM-DD' format)
    if (!DateTime::createFromFormat('Y-m-d', $txt_bdate)) {
        die('Invalid birthdate format. Please use YYYY-MM-DD.');
    }
    
    // Validate purok (assuming it's numeric)
    if (!preg_match('/^[0-9]+$/', $txt_purok)) {
        die('Invalid purok. Only numbers are allowed.');
    }

    // Check for duplicate clearance
    $chkdup = mysqli_query($con, "SELECT * from tblclearance where name = '$txt_name'");
    $rows = mysqli_num_rows($chkdup);

    // Log the action if the session role is set
    if (isset($_SESSION['role'])) {
        $action = 'Added Clearance named of ' . $txt_name;
        $iquery = mysqli_query($con, "INSERT INTO tbllogs (user, logdate, action) values ('Brgy." . $_SESSION['staff'] . "', NOW(), '" . $action . "')");
    }

    // Insert clearance if no duplicates
    if ($rows == 0) {
        $query = mysqli_query($con, "INSERT INTO tblclearance 
            (clearanceNo, name, purpose, orNo, samount, dateRecorded, recordedBy, barangay, age, bdate, purok, report_type) 
            values ('$txt_cnum','$txt_name', '$txt_purpose', '$txt_ornum', '$txt_amount', '$date', '".$_SESSION['username']."', '$off_barangay', '$txt_age', '$txt_bdate', '$txt_purok', 'Clearance')") 
            or die('Error: ' . mysqli_error($con));

        // Handle successful insert
        if ($query == true) {
            $_SESSION['added'] = 1;
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
   // Set Content Security Policy
   header("Content-Security-Policy: script-src 'self';");
   
    // Sanitize inputs
   $txt_edit_residentname = htmlspecialchars(stripslashes(trim($_POST['txt_edit_residentname'])), ENT_QUOTES, 'UTF-8');
   $txt_id = htmlspecialchars(stripslashes(trim($_POST['hidden_id'])), ENT_QUOTES, 'UTF-8');
   $txt_edit_cnum = htmlspecialchars(stripslashes(trim($_POST['txt_edit_cnum'])), ENT_QUOTES, 'UTF-8');
   $txt_edit_purpose = htmlspecialchars(stripslashes(trim($_POST['txt_edit_purpose'])), ENT_QUOTES, 'UTF-8');
   $txt_edit_ornum = htmlspecialchars(stripslashes(trim($_POST['txt_edit_ornum'])), ENT_QUOTES, 'UTF-8');
   $txt_edit_amount = htmlspecialchars(stripslashes(trim($_POST['txt_edit_amount'])), ENT_QUOTES, 'UTF-8');
   $txt_edit_age = (int) $_POST['txt_edit_age']; // Cast to integer
   $txt_edit_bdate = htmlspecialchars(stripslashes(trim($_POST['txt_edit_bdate'])), ENT_QUOTES, 'UTF-8');
   $txt_edit_purok = htmlspecialchars(stripslashes(trim($_POST['txt_edit_purok'])), ENT_QUOTES, 'UTF-8');
   
   // Basic Validation
   if (empty($txt_edit_residentname) || empty($txt_id) || empty($txt_edit_cnum) || 
       empty($txt_edit_purpose) || empty($txt_edit_ornum) || empty($txt_edit_amount) || 
       empty($txt_edit_age) || empty($txt_edit_bdate) || empty($txt_edit_purok)) {
       die('Required fields are missing.');
   }
   
   // Validate resident name (letters and spaces allowed)
   if (!preg_match('/^[a-zA-Z\s]+$/', $txt_edit_residentname)) {
       die('Invalid resident name format. Only letters and spaces are allowed.');
   }
   
   // Validate contact number (example: numeric, 10-12 digits)
   if (!preg_match('/^[0-9]{10,12}$/', $txt_edit_cnum)) {
       die('Invalid contact number. It must be between 10 and 12 digits.');
   }
   
   // Validate OR number (assuming it's numeric)
   if (!preg_match('/^[0-9]+$/', $txt_edit_ornum)) {
       die('Invalid OR number. It must be numeric.');
   }
   
   // Validate amount (assuming it's a positive decimal)
   if (!preg_match('/^\d+(\.\d{1,2})?$/', $txt_edit_amount)) {
       die('Invalid amount format. Only positive numbers are allowed with up to two decimal places.');
   }
   
   // Validate age (must be a positive integer)
   if ($txt_edit_age <= 0) {
       die('Invalid age. It must be a positive number.');
   }
   
   // Validate birthdate (check if it's a valid date in 'YYYY-MM-DD' format)
   if (!DateTime::createFromFormat('Y-m-d', $txt_edit_bdate)) {
       die('Invalid birthdate format. Please use YYYY-MM-DD.');
   }
   
   // Validate purok (assuming it's numeric)
   if (!preg_match('/^[0-9]+$/', $txt_edit_purok)) {
       die('Invalid purok. Only numbers are allowed.');
   }

    // Update query including the new fields
    $update_query = mysqli_query($con,"UPDATE tblclearance SET 
    Name = '".$txt_edit_residentname."',
    clearanceNo = '".$txt_edit_cnum."', 
    purpose = '".$txt_edit_purpose."', 
    orNo = '".$txt_edit_ornum."', 
    samount = '".$txt_edit_amount."', 
    age = '".$txt_edit_age."', 
    bdate = '".$txt_edit_bdate."', 
    purok = '".$txt_edit_purok."' 
    WHERE id = '".$txt_id."' ") or die('Error: ' . mysqli_error($con));

    if(isset($_SESSION['role'])){
        $action = 'Update Clearance name of '.$txt_edit_residentname;
        $iquery = mysqli_query($con,"INSERT INTO tbllogs (user, logdate, action) values ('Brgy.".$_SESSION['staff']."', NOW(), '".$action."')");
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