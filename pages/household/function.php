<?php
if(isset($_POST['btn_add'])){
    // Sanitize inputs
    $txt_householdno = htmlspecialchars(stripslashes(trim($_POST['txt_householdno'])), ENT_QUOTES, 'UTF-8');
    $txt_totalmembers = (int) $_POST['txt_totalmembers']; // Cast to integer
    $txt_hof = htmlspecialchars(stripslashes(trim($_POST['txt_hof'])), ENT_QUOTES, 'UTF-8');
    $txt_brgy = htmlspecialchars(stripslashes(trim($_POST['txt_brgy'])), ENT_QUOTES, 'UTF-8');
    $txt_purok = htmlspecialchars(stripslashes(trim($_POST['txt_purok'])), ENT_QUOTES, 'UTF-8');

    $chkdup = mysqli_query($con, "SELECT * from tblhousehold where headoffamily = ".$txt_hof."");
    $rows = mysqli_num_rows($chkdup);

    if(isset($_SESSION['role'])){
        $action = 'Added Household Number '.$txt_householdno;
        $iquery = mysqli_query($con,"INSERT INTO tbllogs (user,logdate,action) values ('Brgy.".$_SESSION['staff']."', NOW(), '".$action."')");
    }

    if($rows == 0){
        $query = mysqli_query($con,"INSERT INTO tblhousehold (householdno, totalhouseholdmembers, headoffamily, barangay, purok) 
            values ('$txt_householdno', '$txt_totalmembers', '$txt_hof', '$txt_brgy', '$txt_purok')") or die('Error: ' . mysqli_error($con));
        if($query == true)
        {
            $_SESSION['added'] = 1;
            header("location: ".$_SERVER['REQUEST_URI']);
            exit();
        }     
    }
    else {
        $_SESSION['duplicate'] = 1;
        header("location: ".$_SERVER['REQUEST_URI']);
        exit();
    }
}

if (isset($_POST['btn_save'])) {

   // Sanitize inputs
   $txt_id = htmlspecialchars(stripslashes(trim($_POST['hidden_id'])), ENT_QUOTES, 'UTF-8');
   $txt_edit_householdno = htmlspecialchars(stripslashes(trim($_POST['txt_edit_householdno'])), ENT_QUOTES, 'UTF-8');
   $txt_edit_totalmembers = (int) $_POST['txt_edit_totalmembers']; // Cast to integer
   $txt_edit_name = htmlspecialchars(stripslashes(trim($_POST['txt_edit_name'])), ENT_QUOTES, 'UTF-8');
   $txt_edit_purok = htmlspecialchars(stripslashes(trim($_POST['txt_edit_purok'])), ENT_QUOTES, 'UTF-8');
   $txt_edit_brgy = htmlspecialchars(stripslashes(trim($_POST['txt_edit_brgy'])), ENT_QUOTES, 'UTF-8');

    // Make sure columns exist in tblhousehold table
    if (in_array('householdno', $valid_columns) && in_array('totalhouseholdmembers', $valid_columns) && in_array('barangay', $valid_columns) && in_array('purok', $valid_columns)) {
        // Update query using prepared statements
        $stmt = $con->prepare("UPDATE tblhousehold SET householdno = ?, totalhouseholdmembers = ?, barangay = ?, purok = ? WHERE id = ?");
        $stmt->bind_param("sssi", $txt_edit_householdno, $txt_edit_totalmembers, $txt_edit_brgy, $txt_edit_purok, $txt_id);
        $update_query = $stmt->execute();

        // Log action
        if (isset($_SESSION['role'])) {
            $action = 'Update Household Number ' . $txt_edit_householdno;
            $iquery = mysqli_query($con, "INSERT INTO tbllogs (user, logdate, action) VALUES ('Brgy." . $_SESSION['staff'] . "', NOW(), '" . $action . "')");
        }

        // Redirect if successful
        if ($update_query) {
            $_SESSION['edited'] = 1;
            header("location: " . $_SERVER['REQUEST_URI']);
            exit(); // Ensure no further execution after redirect
        } else {
            die('Error: ' . $stmt->error);
        }
    } else {
        die('Error: Invalid column names');
    }
}


if(isset($_POST['btn_delete']))
{
    if(isset($_POST['chk_delete']))
    {
        foreach($_POST['chk_delete'] as $value)
        {
            $delete_query = mysqli_query($con,"DELETE from tblhousehold where id = '$value' ") or die('Error: ' . mysqli_error($con));
                    
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
