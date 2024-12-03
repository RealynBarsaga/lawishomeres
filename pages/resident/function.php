<?php
// Handle form submission for adding a resident
if (isset($_POST['btn_add'])) {
    // Sanitize input data (handle spaces and special characters)
    $txt_lname = htmlspecialchars(stripslashes(trim($_POST['txt_lname'])), ENT_QUOTES, 'UTF-8');
    $txt_fname = htmlspecialchars(stripslashes(trim($_POST['txt_fname'])), ENT_QUOTES, 'UTF-8');
    $txt_mname = htmlspecialchars(stripslashes(trim($_POST['txt_mname'])), ENT_QUOTES, 'UTF-8');
    $ddl_gender = htmlspecialchars(stripslashes(trim($_POST['ddl_gender'])), ENT_QUOTES, 'UTF-8');
    $txt_bdate = htmlspecialchars(stripslashes(trim($_POST['txt_bdate'])), ENT_QUOTES, 'UTF-8');
    $txt_bplace = htmlspecialchars(stripslashes(trim($_POST['txt_bplace'])), ENT_QUOTES, 'UTF-8');

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
    $txt_brgy = htmlspecialchars(stripslashes(trim($_POST['txt_brgy'])), ENT_QUOTES, 'UTF-8');
    $txt_purok = htmlspecialchars(stripslashes(trim($_POST['txt_purok'])), ENT_QUOTES, 'UTF-8');
    $txt_cstatus = htmlspecialchars(stripslashes(trim($_POST['txt_cstatus'])), ENT_QUOTES, 'UTF-8');
    $txt_householdnum = htmlspecialchars(stripslashes(trim($_POST['txt_householdnum'])), ENT_QUOTES, 'UTF-8');
    $txt_religion = htmlspecialchars(stripslashes(trim($_POST['txt_religion'])), ENT_QUOTES, 'UTF-8');
    $txt_national = htmlspecialchars(stripslashes(trim($_POST['txt_national'])), ENT_QUOTES, 'UTF-8');
    $ddl_hos = htmlspecialchars(stripslashes(trim($_POST['ddl_hos'])), ENT_QUOTES, 'UTF-8');
    $ddl_los = htmlspecialchars(stripslashes(trim($_POST['ddl_los'])), ENT_QUOTES, 'UTF-8');
    $txt_lightning = htmlspecialchars(stripslashes(trim($_POST['txt_lightning'])), ENT_QUOTES, 'UTF-8');
    $txt_faddress = htmlspecialchars(stripslashes(trim($_POST['txt_faddress'])), ENT_QUOTES, 'UTF-8');
    $txt_role = htmlspecialchars(stripslashes(trim($_POST['txt_role'])), ENT_QUOTES, 'UTF-8');
    $txt_head_of_family = htmlspecialchars(stripslashes(trim($_POST['txt_head_of_family'])), ENT_QUOTES, 'UTF-8');
    
    $name = basename($_FILES['txt_image']['name']);
    $temp = $_FILES['txt_image']['tmp_name'];
    $imagetype = $_FILES['txt_image']['type'];
    $size = $_FILES['txt_image']['size'];

    $milliseconds = round(microtime(true) * 1000);
    $txt_image = $milliseconds . '_' . $name;

    // Check for existing user (to prevent duplicate)
    $su = mysqli_query($con, "SELECT * FROM tbltabagak WHERE lname='$txt_lname' AND fname='$txt_fname' AND mname='$txt_mname'");
    $ct = mysqli_num_rows($su);

    if ($ct == 0) {
        if ($name != "") {
            // Validate image type and size
            $allowedTypes = ['image/jpeg', 'image/png', 'image/bmp'];
            if (in_array($imagetype, $allowedTypes) && $size <= 2048000) {
                // Ensure it's a valid image file
                if (getimagesize($temp) !== false) {
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
                        $_SESSION['file_move_error'] = 1;
                        header("location: " . $_SERVER['REQUEST_URI']);
                        exit();
                    }
                } else {
                    // Not a valid image
                    $_SESSION['invalid_image'] = 1;
                    header("location: " . $_SERVER['REQUEST_URI']);
                    exit();
                }
            } else {
                // Invalid file type or file too large
                $_SESSION['invalid_file'] = 1;
                header("location: " . $_SERVER['REQUEST_URI']);
                exit();
            }
        } else {
            // If no image is uploaded, use default image
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
        exit();
    }
}


// Handle form submission for editing a resident
if (isset($_POST['btn_save'])) {
    // Get form data with sanitization
    $id = htmlspecialchars(stripslashes(trim($_POST['hidden_id'])), ENT_QUOTES, 'UTF-8');
    $lname = htmlspecialchars(stripslashes(trim($_POST['txt_edit_lname'])), ENT_QUOTES, 'UTF-8');
    $fname = htmlspecialchars(stripslashes(trim($_POST['txt_edit_fname'])), ENT_QUOTES, 'UTF-8');
    $mname = htmlspecialchars(stripslashes(trim($_POST['txt_edit_mname'])), ENT_QUOTES, 'UTF-8');
    $age = (int) $_POST['txt_edit_age']; // cast age to an integer
    $bdate = htmlspecialchars(stripslashes(trim($_POST['txt_edit_bdate'])), ENT_QUOTES, 'UTF-8');
    $barangay = htmlspecialchars(stripslashes(trim($_POST['txt_edit_brgy'])), ENT_QUOTES, 'UTF-8');
    $purok = htmlspecialchars(stripslashes(trim($_POST['txt_edit_purok'])), ENT_QUOTES, 'UTF-8');
    $householdnum = htmlspecialchars(stripslashes(trim($_POST['txt_edit_householdnum'])), ENT_QUOTES, 'UTF-8');
    $cstatus = htmlspecialchars(stripslashes(trim($_POST['txt_edit_cstatus'])), ENT_QUOTES, 'UTF-8');
    $nationality = htmlspecialchars(stripslashes(trim($_POST['txt_edit_national'])), ENT_QUOTES, 'UTF-8');
    $landOwnershipStatus = htmlspecialchars(stripslashes(trim($_POST['ddl_edit_los'])), ENT_QUOTES, 'UTF-8');
    $gender = htmlspecialchars(stripslashes(trim($_POST['ddl_edit_gender'])), ENT_QUOTES, 'UTF-8');
    $bplace = htmlspecialchars(stripslashes(trim($_POST['txt_edit_bplace'])), ENT_QUOTES, 'UTF-8');
    $religion = htmlspecialchars(stripslashes(trim($_POST['txt_edit_religion'])), ENT_QUOTES, 'UTF-8');
    $houseOwnershipStatus = htmlspecialchars(stripslashes(trim($_POST['ddl_edit_hos'])), ENT_QUOTES, 'UTF-8');
    $lightning = htmlspecialchars(stripslashes(trim($_POST['txt_edit_lightning'])), ENT_QUOTES, 'UTF-8');
    $formerAddress = htmlspecialchars(stripslashes(trim($_POST['txt_edit_faddress'])), ENT_QUOTES, 'UTF-8');
    
    // Handle image upload
    $image = $_FILES['txt_edit_image']['name'];
    $imageFileType = strtolower(pathinfo($image, PATHINFO_EXTENSION)); // Get file extension
    $allowedTypes = ['jpeg', 'png', 'bmp'];
    $maxFileSize = 2048000; // 2MB

    if ($image) {
        // Validate image type and size
        if (in_array($imageFileType, $allowedTypes) && $_FILES['txt_edit_image']['size'] <= $maxFileSize) {
            // Check if the uploaded file is a valid image
            if (getimagesize($_FILES["txt_edit_image"]["tmp_name"]) !== false) {
                $target_dir = "image/";
                $target_file = $target_dir . basename($image);
                
                // Check if the file already exists (optional)
                if (!file_exists($target_file)) {
                    if (move_uploaded_file($_FILES["txt_edit_image"]["tmp_name"], $target_file)) {
                        // File upload successful
                        // You can store the image name/path in the database
                    } else {
                        // File upload failed
                        $_SESSION['file_upload_error'] = "Sorry, there was an error uploading your file.";
                        header("Location: " . $_SERVER['REQUEST_URI']);
                        exit();
                    }
                } else {
                    // If file already exists
                    $_SESSION['file_exists'] = "Sorry, file already exists.";
                    header("Location: " . $_SERVER['REQUEST_URI']);
                    exit();
                }
            } else {
                // Not a valid image
                $_SESSION['invalid_image'] = "File is not a valid image.";
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();
            }
        } else {
            // Invalid file type or size
            $_SESSION['invalid_file'] = "Invalid file type or size.";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        }
    } else {
        // If no new image is uploaded, retain the existing image
        $edit_query = mysqli_query($con, "SELECT image FROM tbltabagak WHERE id='$id'");
        $erow = mysqli_fetch_array($edit_query);
        $image = $erow['image'];
    }

    // Update resident's information in the database
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
              nationality = '$nationality', 
              landOwnershipStatus = '$landOwnershipStatus', 
              gender = '$gender', 
              bplace = '$bplace',  
              religion = '$religion', 
              houseOwnershipStatus = '$houseOwnershipStatus', 
              lightningFacilities = '$lightning', 
              formerAddress = '$formerAddress', 
              image = '$image' 
              WHERE id = '$id'") or die('Error: ' . mysqli_error($con));

    // Redirect after successful edit
    if ($update_query) {
        $_SESSION['edited'] = 1;

        // Log action
        if (isset($_SESSION['role'])) {
            $action = 'Updated Resident info for ' . $fname . ' ' . $mname . ' ' . $lname;
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
