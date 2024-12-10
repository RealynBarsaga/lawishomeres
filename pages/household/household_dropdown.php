<?php
include '../connection.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['hhold_id']) && isset($_POST['barangay'])) {
        // Fetch Head of Family dropdown options based on Household ID and Barangay
        $hhold_id = $_POST['hhold_id'];
        $barangay = $_POST['barangay'];

        $stmt = $con->prepare("SELECT id, lname, fname, mname FROM tbltabagak WHERE household_no = ? AND barangay = ? AND role = 'Head'");
        $stmt->bind_param("ss", $hhold_id, $barangay);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo '<option disabled selected>-- Select Head of Family --</option>';
            while ($row = $result->fetch_assoc()) {
                $fullName = htmlspecialchars($row['lname'] . ', ' . $row['fname'] . ' ' . $row['mname']);
                echo '<option value="' . $row['id'] . '">' . $fullName . '</option>';
            }
        } else {
            echo '<option disabled selected>No Head of Family Found</option>';
        }
        $stmt->close();
    }

    // Fetch Barangay based on selected Head of Family
    if (isset($_POST['brgy_id']) && isset($_POST['barangay'])) {
        $hof_id = $_POST['brgy_id'];
        $stmt = $con->prepare("SELECT barangay FROM tbltabagak WHERE id = ? AND barangay = ?");
        $stmt->bind_param("ss", $hof_id, $_POST['barangay']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            echo htmlspecialchars($row['barangay']);
        } else {
            echo "No Barangay Found";
        }
        $stmt->close();
    }

    // Fetch Purok based on selected Head of Family
    if (isset($_POST['purok_id']) && isset($_POST['barangay'])) {
        $hof_id = $_POST['purok_id'];
        $stmt = $con->prepare("SELECT purok FROM tbltabagak WHERE id = ? AND barangay = ?");
        $stmt->bind_param("ss", $hof_id, $_POST['barangay']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            echo htmlspecialchars($row['purok']);
        } else {
            echo "No Purok Found";
        }
        $stmt->close();
    }

    // Fetch family members based on Head of Family
    if (isset($_POST['headoffamily']) && isset($_POST['barangay'])) {
        $hof_id = $_POST['headoffamily'];
        $barangay = $_POST['barangay'];

        $stmt = $con->prepare("SELECT id, lname, fname, mname FROM tbltabagak WHERE role = 'Members' AND headoffamily = ? AND barangay = ?");
        $stmt->bind_param("ss", $hof_id, $barangay);
        $stmt->execute();
        $result = $stmt->get_result();

        $members = [];
        while ($row = $result->fetch_assoc()) {
            $fullName = htmlspecialchars($row['lname'] . ', ' . $row['fname'] . ' ' . $row['mname']);
            $members[] = ['id' => $row['id'], 'fullName' => $fullName];
        }

        $stmt->close();
        echo json_encode($members);
    }
}
?>
