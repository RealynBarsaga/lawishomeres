<?php
if(isset($_POST['btn_add'])){
    // Sanitize inputs
    $txt_householdno = htmlspecialchars(strip_tags(trim($_POST['txt_householdno'])), ENT_QUOTES, 'UTF-8');
    $txt_totalmembers = htmlspecialchars(strip_tags(trim($_POST['txt_totalmembers'])), ENT_QUOTES, 'UTF-8');
    $txt_hof = htmlspecialchars(strip_tags(trim($_POST['txt_hof'])), ENT_QUOTES, 'UTF-8');
    $txt_brgy = htmlspecialchars(strip_tags(trim($_POST['txt_brgy'])), ENT_QUOTES, 'UTF-8');
    $txt_purok = htmlspecialchars(strip_tags(trim($_POST['txt_purok'])), ENT_QUOTES, 'UTF-8');
    $txt_members = htmlspecialchars(strip_tags(trim($_POST['txt_members'])), ENT_QUOTES, 'UTF-8');

    // Validate family members
    if (empty($txt_members)) {
        $_SESSION['error'] = "No family members were selected.";
        header("location: " . $_SERVER['REQUEST_URI']);
        exit();
    }

    $chkdup = mysqli_query($con, "SELECT * from tblhousehold where headoffamily = ".$txt_hof."");
    $rows = mysqli_num_rows($chkdup);


    if($rows == 0){
        $txt_totalmembers += 1;  // Add 1 to total members
        $query = mysqli_query($con,"INSERT INTO tblhousehold (householdno, totalhouseholdmembers, headoffamily, barangay, purok, membersname) 
            values ('$txt_householdno', '$txt_totalmembers', '$txt_hof', '$txt_brgy', '$txt_purok', '$txt_members')") or die('Error: ' . mysqli_error($con));
        if($query == true)
        {
            $_SESSION['added'] = 1;

            if (isset($_SESSION['role'])) {
                // Assuming $txt_hof is the ID of the head of family (which you're getting from the form)
                $hof_id = $txt_hof; 
            
                // Fetch the name of the Head of Family using the ID
                $q = mysqli_query($con, "SELECT CONCAT(lname, ', ', fname, ' ', mname) AS name FROM tbltabagak WHERE id = '$hof_id'");
                $row1 = mysqli_fetch_array($q);
                $hof_name = htmlspecialchars($row1['name'], ENT_QUOTES, 'UTF-8');  // Safe name
            
                // Log the action with the name of the Head of Family
                $action = 'Added Household Name ' . $hof_name;  // Use the name in the log
                $iquery = mysqli_query($con, "INSERT INTO tbllogs (user, logdate, action) VALUES ('Brgy." . $_SESSION['staff'] . "', NOW(), '" . $action . "')");
            }

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
   $txt_id = htmlspecialchars(strip_tags(trim($_POST['hidden_id'])), ENT_QUOTES, 'UTF-8');
   $txt_edit_householdno = htmlspecialchars(strip_tags(trim($_POST['txt_edit_householdno'])), ENT_QUOTES, 'UTF-8');
   $txt_edit_totalmembers = (int) $_POST['txt_edit_totalmembers']; // Cast to integer
   $txt_edit_name = htmlspecialchars(strip_tags(trim($_POST['txt_edit_name'])), ENT_QUOTES, 'UTF-8');
   $txt_edit_purok = htmlspecialchars(strip_tags(trim($_POST['txt_edit_purok'])), ENT_QUOTES, 'UTF-8');
   $txt_edit_brgy = htmlspecialchars(strip_tags(trim($_POST['txt_edit_brgy'])), ENT_QUOTES, 'UTF-8');

    // Make sure columns exist in tblhousehold table
    if (in_array('householdno', $valid_columns) && in_array('totalhouseholdmembers', $valid_columns) && in_array('barangay', $valid_columns) && in_array('purok', $valid_columns)) {
        // Update query using prepared statements
        $stmt = $con->prepare("UPDATE tblhousehold SET householdno = ?, totalhouseholdmembers = ?, barangay = ?, purok = ? WHERE id = ?");
        $stmt->bind_param("sssi", $txt_edit_householdno, $txt_edit_totalmembers, $txt_edit_brgy, $txt_edit_purok, $txt_id);
        $update_query = $stmt->execute();


        // Redirect if successful
        if ($update_query) {
            $_SESSION['edited'] = 1;

            // Log action
            if (isset($_SESSION['role'])) {
                $action = 'Update Household Name ' . $txt_edit_name;
                $iquery = mysqli_query($con, "INSERT INTO tbllogs (user, logdate, action) VALUES ('Brgy." . $_SESSION['staff'] . "', NOW(), '" . $action . "')");
            }

            header("location: " . $_SERVER['REQUEST_URI']);
            exit(); // Ensure no further execution after redirect
        } else {
            die('Error: ' . $stmt->error);
        }
    } else {
        die('Error: Invalid column names');
    }
}

if (isset($_POST['btn_del'])) {
    if (isset($_POST['hidden_id'])) {
        $txt_id = $_POST['hidden_id'];

        $delete_query = mysqli_query($con, "DELETE FROM tblhousehold WHERE id = '$txt_id'");

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
