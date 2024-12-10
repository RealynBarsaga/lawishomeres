<?php
include "../connection.php";

// Fetch Head of Family
if (isset($_POST['hhold_id']) && isset($_POST['barangay'])) {
    $hhold_id = $_POST['hhold_id'];
    $barangay = $_POST['barangay'];

    $query = mysqli_query($con, "SELECT *, id as resID FROM tbltabagak WHERE householdnum = '$hhold_id' AND barangay = '$barangay' AND role = 'Head of Family'");
    if (mysqli_num_rows($query) > 0) {
        echo '<option value="" disabled selected>-- Select Head of Family --</option>';
        while ($row = mysqli_fetch_assoc($query)) {
            echo '<option value="' . $row['resID'] . '">' . $row['lname'] . ', ' . $row['fname'] . ' ' . $row['mname'] . '</option>';
        }
    } else {
        echo '<option value="" disabled selected>-- No Existing Head of Family for Household # --</option>';
    }
}

// Fetch Barangay
if (isset($_POST['brgy_id']) && isset($_POST['barangay'])) {
    $brgy_id = $_POST['brgy_id'];
    $barangay = $_POST['barangay'];

    $query = mysqli_query($con, "SELECT barangay FROM tbltabagak WHERE id = '$brgy_id' AND barangay = '$barangay'");
    echo ($row = mysqli_fetch_assoc($query)) ? $row['barangay'] : '';
}

// Fetch Purok
if (isset($_POST['purok_id']) && isset($_POST['barangay'])) {
    $purok_id = $_POST['purok_id'];
    $barangay = $_POST['barangay'];

    $query = mysqli_query($con, "SELECT purok FROM tbltabagak WHERE id = '$purok_id' AND barangay = '$barangay'");
    echo ($row = mysqli_fetch_assoc($query)) ? $row['purok'] : '';
}
?>
