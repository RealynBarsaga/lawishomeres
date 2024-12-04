<?php
// Handle form submission for adding a resident
if (isset($_POST['btn_add'])) {
    // Sanitize input data
    $txt_lname = htmlspecialchars(strip_tags(trim($_POST['txt_lname'])), ENT_QUOTES, 'UTF-8');
    $txt_fname = htmlspecialchars(strip_tags(trim($_POST['txt_fname'])), ENT_QUOTES, 'UTF-8');
    $txt_mname = htmlspecialchars(strip_tags(trim($_POST['txt_mname'])), ENT_QUOTES, 'UTF-8');
    $ddl_gender = htmlspecialchars(strip_tags(trim($_POST['ddl_gender'])), ENT_QUOTES, 'UTF-8');
    $txt_bdate = htmlspecialchars(strip_tags(trim($_POST['txt_bdate'])), ENT_QUOTES, 'UTF-8');
    $txt_bplace = htmlspecialchars(strip_tags(trim($_POST['txt_bplace'])), ENT_QUOTES, 'UTF-8');

    // Calculate age based on birthdate
    $dateOfBirth = DateTime::createFromFormat('Y-m-d', $txt_bdate);
    $today = new DateTime('today');

    if ($dateOfBirth && $dateOfBirth <= $today) { // Ensure valid date and not in the future
        $diff = $today->diff($dateOfBirth);
        $txt_age = $diff->y; // Get the age in years
    } else {
        $txt_age = 0; // Invalid or future birthdate
    }

    // Continue with other sanitized form data
    $txt_brgy = htmlspecialchars(strip_tags(trim($_POST['txt_brgy'])), ENT_QUOTES, 'UTF-8');
    $txt_purok = htmlspecialchars(strip_tags(trim($_POST['txt_purok'])), ENT_QUOTES, 'UTF-8');
    $txt_cstatus = htmlspecialchars(strip_tags(trim($_POST['txt_cstatus'])), ENT_QUOTES, 'UTF-8');
    $txt_householdnum = htmlspecialchars(strip_tags(trim($_POST['txt_householdnum'])), ENT_QUOTES, 'UTF-8');
    $txt_religion = htmlspecialchars(strip_tags(trim($_POST['txt_religion'])), ENT_QUOTES, 'UTF-8');
    $txt_national = htmlspecialchars(strip_tags(trim($_POST['txt_national'])), ENT_QUOTES, 'UTF-8');
    $ddl_hos = htmlspecialchars(strip_tags(trim($_POST['ddl_hos'])), ENT_QUOTES, 'UTF-8');
    $ddl_los = htmlspecialchars(strip_tags(trim($_POST['ddl_los'])), ENT_QUOTES, 'UTF-8');
    $txt_lightning = htmlspecialchars(strip_tags(trim($_POST['txt_lightning'])), ENT_QUOTES, 'UTF-8');
    $txt_faddress = htmlspecialchars(strip_tags(trim($_POST['txt_faddress'])), ENT_QUOTES, 'UTF-8');
    $txt_role = htmlspecialchars(strip_tags(trim($_POST['txt_role'])), ENT_QUOTES, 'UTF-8');
    $txt_head_of_family = htmlspecialchars(strip_tags(trim($_POST['txt_head_of_family'])), ENT_QUOTES, 'UTF-8');
    
    $name = basename($_FILES['txt_image']['name']);
    $temp = $_FILES['txt_image']['tmp_name'];
    $imagetype = $_FILES['txt_image']['type'];

    $milliseconds = round(microtime(true) * 1000);
    $txt_image = $milliseconds . '_' . $name;


    $su = mysqli_query($con, "SELECT * FROM tbltabagak WHERE lname='$txt_lname' AND fname='$txt_fname' AND mname='$txt_mname'");
    $ct = mysqli_num_rows($su);

    if ($ct == 0) {
        if ($name != "") {
            if (($imagetype == "image/jpeg" || $imagetype == "image/png" || $imagetype == "image/bmp")) {
                if (move_uploaded_file($temp, 'image/' . $txt_image)) {
                    // Insert resident's data
                    $query = mysqli_query($con, "INSERT INTO tbltabagak (
                        lname, fname, mname, bdate, bplace, age, barangay, purok, 
                        civilstatus, householdnum, religion, nationality, 
                        gender, houseOwnershipStatus, landOwnershipStatus, lightningFacilities, 
                        formerAddress, image, role, headoffamily) VALUES (
                        '$txt_lname', '$txt_fname', '$txt_mname', '$txt_bdate', '$txt_bplace', 
                        '$txt_age', '$txt_brgy', '$txt_purok', 
                        '$txt_cstatus', '$txt_householdnum', '$txt_religion', '$txt_national', 
                        '$ddl_gender', '$ddl_hos', '$ddl_los', '$txt_lightning', '$txt_faddress', 
                        '$txt_image', '$txt_role', '$txt_head_of_family')") or die('Error: ' . mysqli_error($con));
                } else {
                    // Handle file move error
                }
            } else {
                // Handle file move error
            }
        } else {
            $txt_image = 'default.png';
            // Insert resident's data without image
            $query = mysqli_query($con, "INSERT INTO tbltabagak (
                lname, fname, mname, bdate, bplace, age, barangay, purok, 
                civilstatus, householdnum, religion, nationality, 
                gender, houseOwnershipStatus, landOwnershipStatus, lightningFacilities, 
                formerAddress, image, role, headoffamily) VALUES (
                '$txt_lname', '$txt_fname', '$txt_mname', '$txt_bdate', '$txt_bplace', 
                '$txt_age', '$txt_brgy', '$txt_purok', 
                '$txt_cstatus', '$txt_householdnum', '$txt_religion', '$txt_national', 
                '$ddl_gender', '$ddl_hos', '$ddl_los', '$txt_lightning', '$txt_faddress', 
                '$txt_image', '$txt_role', '$txt_head_of_family')") or die('Error: ' . mysqli_error($con));
        }

        if ($query == true) {
            $_SESSION['added'] = 1;

            if (isset($_SESSION['role'])) {
                $action = 'Added Resident named of ' . $txt_fname . ' ' . $txt_mname . ' ' . $txt_lname;
                $iquery = mysqli_query($con, "INSERT INTO tbllogs (user, logdate, action) VALUES ('Brgy." . $_SESSION['staff'] . "', NOW(), '" . $action . "')");
            }
            
            header("location: " . $_SERVER['REQUEST_URI']);
            exit();
        }
    } else {
        $_SESSION['duplicateuser'] = 1;
        header("location: " . $_SERVER['REQUEST_URI']);
    }
}


// Handle form submission for editing a resident
if (isset($_POST['btn_save'])) {
    // Get form data with sanitization
    $id = htmlspecialchars(strip_tags(trim($_POST['hidden_id'])), ENT_QUOTES, 'UTF-8'); 
    $lname = htmlspecialchars(strip_tags(trim($_POST['txt_edit_lname'])), ENT_QUOTES, 'UTF-8');
    $fname = htmlspecialchars(strip_tags(trim($_POST['txt_edit_fname'])), ENT_QUOTES, 'UTF-8');
    $mname = htmlspecialchars(strip_tags(trim($_POST['txt_edit_mname'])), ENT_QUOTES, 'UTF-8');
    $age = (int) $_POST['txt_edit_age']; // cast age to an integer
    $bdate = htmlspecialchars(strip_tags(trim($_POST['txt_edit_bdate'])), ENT_QUOTES, 'UTF-8');
    $barangay = htmlspecialchars(strip_tags(trim($_POST['txt_edit_brgy'])), ENT_QUOTES, 'UTF-8');
    $purok = htmlspecialchars(strip_tags(trim($_POST['txt_edit_purok'])), ENT_QUOTES, 'UTF-8');
    $householdnum = htmlspecialchars(strip_tags(trim($_POST['txt_edit_householdnum'])), ENT_QUOTES, 'UTF-8');
    $cstatus = htmlspecialchars(strip_tags(trim($_POST['txt_edit_cstatus'])), ENT_QUOTES, 'UTF-8');
    $nationality = htmlspecialchars(strip_tags(trim($_POST['txt_edit_national'])), ENT_QUOTES, 'UTF-8');
    $landOwnershipStatus = htmlspecialchars(strip_tags(trim($_POST['ddl_edit_los'])), ENT_QUOTES, 'UTF-8');
    $gender = htmlspecialchars(strip_tags(trim($_POST['ddl_edit_gender'])), ENT_QUOTES, 'UTF-8');
    $bplace = htmlspecialchars(strip_tags(trim($_POST['txt_edit_bplace'])), ENT_QUOTES, 'UTF-8');
    $religion = htmlspecialchars(strip_tags(trim($_POST['txt_edit_religion'])), ENT_QUOTES, 'UTF-8');
    $houseOwnershipStatus = htmlspecialchars(strip_tags(trim($_POST['ddl_edit_hos'])), ENT_QUOTES, 'UTF-8');
    $lightning = htmlspecialchars(strip_tags(trim($_POST['txt_edit_lightning'])), ENT_QUOTES, 'UTF-8');
    $formerAddress = htmlspecialchars(strip_tags(trim($_POST['txt_edit_faddress'])), ENT_QUOTES, 'UTF-8');
    
    // Handle image upload
    $image = $_FILES['txt_edit_image']['name'];
    if ($image) {
        $target_dir = "image/";
        $target_file = $target_dir . basename($image);
        if (move_uploaded_file($_FILES["txt_edit_image"]["tmp_name"], $target_file)) {
            // File upload successful
        } else {
            // Handle file upload error if necessary
        }
    } else {
        // If no new image is uploaded, retrieve the existing image
        $edit_query = mysqli_query($con, "SELECT image FROM tbltabagak WHERE id='$id'");
        $erow = mysqli_fetch_array($edit_query);
        $image = $erow['image'];
    }

    // Update resident's information
    $update_query = mysqli_query($con, "UPDATE tbltabagak SET 
              lname = '$lname', 
              fname = '$fname', 
              mname = '$mname', 
              age = '$age',
              bdate = '$bdate', 
              barangay = '$barangay',
              purok = '$purok', 
              householdnum = '$householdnum', 
              civilstatus = '$cstatus', 
              nationality= '$nationality', 
              landOwnershipStatus = '$landOwnershipStatus', 
              gender = '$gender', 
              bplace = '$bplace',  
              religion = '$religion', 
              houseOwnershipStatus = '$houseOwnershipStatus', 
              lightningFacilities = '$lightning', 
              formerAddress = '$formerAddress', 
              image = '$image' 
              WHERE id = '$id'") or die('Error: ' . mysqli_error($con));

    // Redirect after successful edited
    if ($update_query) {
        $_SESSION['edited'] = 1;

        // Log action
        if (isset($_SESSION['role'])) {
            $action = 'Update Resident info of ' . $fname . ' ' . $mname . ' ' . $lname;
            $iquery = mysqli_query($con, "INSERT INTO tbllogs (user, logdate, action) VALUES ('Brgy." . $_SESSION['staff'] . "', NOW(), '" . $action . "')");
        }

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }
}

if (isset($_POST['btn_del'])) {
    if (isset($_POST['hidden_id'])) {
        $txt_id = $_POST['hidden_id'];

        $delete_query = mysqli_query($con, "DELETE FROM tbltabagak WHERE id = '$txt_id'");

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


// Handle deletion of residents
if (isset($_POST['btn_delete'])) {
    if (isset($_POST['chk_delete'])) {
        foreach ($_POST['chk_delete'] as $value) {
            $delete_query = mysqli_query($con, "DELETE FROM tbltabagak WHERE id = '$value' ") or die('Error: ' . mysqli_error($con));

            if ($delete_query == true) {
                $_SESSION['delete'] = 1;
                header("location: " . $_SERVER['REQUEST_URI']);
                exit();
            }
        }
    }
}
?>