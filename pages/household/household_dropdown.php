<?php
include "../connection.php";

// This section handles fetching the Head of Family for a given Household #
if (isset($_POST['hhold_id']) && isset($_POST['barangay'])) {
    $hhold_id = $_POST['hhold_id'];
    $barangay = $_POST['barangay'];

    // Query filtering by household number, barangay, and role as Head of Family
    $query = mysqli_query($con, "SELECT *, id as resID FROM tbltabagak WHERE householdnum = '$hhold_id' AND barangay = '$barangay' AND role = 'Head of Family'") or die('Error: ' . mysqli_error($con));
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

// This section handles returning the Barangay
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

// This section handles returning the Purok
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

// This section handles returning the total number of household members
if (isset($_POST['total_id']) && isset($_POST['barangay'])) {
    $total_id = $_POST['total_id'];
    $barangay = $_POST['barangay'];

    $query = mysqli_query($con, "SELECT * FROM tbltabagak WHERE id = '$total_id' AND barangay = '$barangay'") or die('Error: ' . mysqli_error($con));
    $rowCount = mysqli_num_rows($query);

    if ($rowCount > 0) {
        while ($row = mysqli_fetch_array($query)) {
            echo $row['totalhouseholdmembers'];
        }
    } else {
        echo '0';
    }
}

if (isset($_POST['headoffamily']) && isset($_POST['barangay'])) {
    $headoffamily = $_POST['headoffamily'];
    $barangay = $_POST['barangay'];

    // Ensure database connection is successful
    if ($con) {
        // SQL Query to fetch members
        $stmt = $con->prepare("SELECT * FROM tbltabagak WHERE role = 'Members' AND headoffamily = ? AND barangay = ?");
        
        // Check if the query is prepared correctly
        if ($stmt) {
            $stmt->bind_param("ss", $headoffamily, $barangay);
            $stmt->execute();
            
            $result = $stmt->get_result();
            $members = [];
            
            // Check if we have results
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Constructing full name
                    $fullName = htmlspecialchars($row['lname'] . ', ' . $row['fname'] . ' ' . $row['mname']);
                    $members[] = [
                        'id' => $row['id'],
                        'fullName' => $fullName
                    ];
                }
            } else {
                $members[] = ['fullName' => 'No Members Found'];  // If no members found
            }
            
            $stmt->close();
            // Return the JSON-encoded members array
            echo json_encode($members);
        } else {
            // Query preparation failed
            echo json_encode(['error' => 'Query preparation failed.']);
        }
    } else {
        // Database connection failed
        echo json_encode(['error' => 'Database connection failed.']);
    }
} else {
    // Missing parameters
    echo json_encode(['error' => 'Missing headoffamily or barangay parameters.']);
}
?>