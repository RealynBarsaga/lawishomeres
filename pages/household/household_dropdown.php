<?php
include "../connection.php"; // Ensure your connection is correct

// Helper function to sanitize inputs
function sanitize_input($input) {
    global $con;
    return mysqli_real_escape_string($con, $input);
}

// Fetch Head of Family
if (isset($_POST['hhold_id']) && isset($_POST['barangay'])) {
    $hhold_id = sanitize_input($_POST['hhold_id']);
    $barangay = sanitize_input($_POST['barangay']);

    // Query to fetch the head of family
    $query = mysqli_query($con, "SELECT *, id as resID FROM tbltabagak WHERE householdnum = '$hhold_id' AND barangay = '$barangay' AND role = 'Head of Family'");

    if ($query && mysqli_num_rows($query) > 0) {
        echo '<option value="" disabled selected>-- Select Head of Family --</option>';
        while ($row = mysqli_fetch_assoc($query)) {
            // Sanitize and output each head of family option
            echo '<option value="' . htmlspecialchars($row['resID']) . '">' . htmlspecialchars($row['lname']) . ', ' . htmlspecialchars($row['fname']) . ' ' . htmlspecialchars($row['mname']) . '</option>';
        }
    } else {
        echo '<option value="" disabled selected>-- No Existing Head of Family for Household # --</option>';
    }
}

// Fetch Barangay
if (isset($_POST['brgy_id']) && isset($_POST['barangay'])) {
    $brgy_id = sanitize_input($_POST['brgy_id']);
    $barangay = sanitize_input($_POST['barangay']);

    // Query to fetch barangay name
    $query = mysqli_query($con, "SELECT barangay FROM tbltabagak WHERE id = '$brgy_id' AND barangay = '$barangay'");
    if ($query && $row = mysqli_fetch_assoc($query)) {
        echo $row['barangay'];
    } else {
        echo ''; // Handle case where no barangay is found
    }
}

// Fetch Purok
if (isset($_POST['purok_id']) && isset($_POST['barangay'])) {
    $purok_id = sanitize_input($_POST['purok_id']);
    $barangay = sanitize_input($_POST['barangay']);

    // Query to fetch purok
    $query = mysqli_query($con, "SELECT purok FROM tbltabagak WHERE id = '$purok_id' AND barangay = '$barangay'");
    if ($query && $row = mysqli_fetch_assoc($query)) {
        echo $row['purok'];
    } else {
        echo ''; // Handle case where no purok is found
    }
}

if (isset($_POST['hof_id']) && isset($_POST['barangay'])) {
    $hof_id = sanitize_input($_POST['hof_id']);
    $barangay = sanitize_input($_POST['barangay']);

    $query = mysqli_query($con, "SELECT lname, fname, mname FROM tbltabagak 
        WHERE householdnum = (SELECT householdnum FROM tbltabagak WHERE id = '$hof_id') 
        AND barangay = '$barangay' AND role != 'Head of Family'");

    $familyMembers = [];
    if ($query && mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            $familyMembers[] = htmlspecialchars($row['lname']) . ', ' . htmlspecialchars($row['fname']) . ' ' . htmlspecialchars($row['mname']);
        }
    }
    echo json_encode($familyMembers);
}
?>