<?php
include "../connection.php";

if (isset($_POST['hhold_id']) && isset($_POST['barangay'])) {
    $hhold_id = $_POST['hhold_id'];
    $barangay = $_POST['barangay'];

    // Query filtering by household number and barangay
    $query = mysqli_query($con, "SELECT *, id as resID FROM tbltabagak WHERE householdnum = '$hhold_id' AND barangay = '$barangay'") or die('Error: ' . mysqli_error($con));
    $rowCount = mysqli_num_rows($query);

    if ($rowCount > 0) {
        echo '<option value="" disabled selected>-- Select Head of Family --</option>';
        while ($row = mysqli_fetch_array($query)) {
            echo '<option value="' . $row['resID'] . '">' . $row['lname'] . ', ' . $row['fname'] . ' ' . $row['mname'] . '</option>';
        }
    } else {
        echo '<option value="" disabled selected>-- No Existing Head of Family for Household # entered --</option>';
    }
}

if (isset($_POST['total_id']) && isset($_POST['barangay'])) {
    $total_id = $_POST['total_id'];
    $barangay = $_POST['barangay'];

    $query = mysqli_query($con, "SELECT * FROM tbltabagak WHERE id = '$total_id' AND barangay = '$barangay'") or die('Error: ' . mysqli_error($con));
    $rowCount = mysqli_num_rows($query);

    if ($rowCount > 0) {
        while ($row = mysqli_fetch_array($query)) {
            echo $row['totalhousehold'];
        }
    } else {
        echo '0';
    }
}

if (isset($_POST['brgy_id']) && isset($_POST['barangay'])) {
    $brgy_id = $_POST['brgy_id'];
    $barangay = $_POST['barangay'];

    $query = mysqli_query($con, "SELECT * FROM tbltabagak WHERE id = '$brgy_id' AND barangay = '$barangay'") or die('Error: ' . mysqli_error($con));
    $rowCount = mysqli_num_rows($query);

    if ($rowCount > 0) {
        while ($row = mysqli_fetch_array($query)) {
            echo $row['barangay'];
        }
    } else {
        echo '';
    }
}

if (isset($_POST['purok_id']) && isset($_POST['barangay'])) {
    $purok_id = $_POST['purok_id'];
    $barangay = $_POST['barangay'];

    $query = mysqli_query($con, "SELECT * FROM tbltabagak WHERE id = '$purok_id' AND barangay = '$barangay'") or die('Error: ' . mysqli_error($con));
    $rowCount = mysqli_num_rows($query);

    if ($rowCount > 0) {
        while ($row = mysqli_fetch_array($query)) {
            echo $row['purok'];
        }
    } else {
        echo '';
    }
}
?>