<!DOCTYPE html>
<html id="clearance">
<head>
    <title>Madridejos Home Residence Management System</title>
    <link rel="icon" type="x-icon" href="../../img/lg.png">
    <style>
        /* Styles for print media */
        @media print {
            .noprint {
                visibility: hidden; /* Hide elements with class 'noprint' when printing */
            }
        }
        @page {
            size: auto;
            margin: 4mm; /* Set margin for printed page */
        }

        /* General body margin */
        body {
            margin: 20px; /* Adds margin around the entire body */
        }

        /* Margin for the header section */
        .header-section {
            margin-bottom: 30px; /* Adds margin below the header */
        }

        /* Main content margin */
        .main-content {
            margin-left: 30px;
            margin-right: 30px;
        }
        .header-section{
            margin-left: 40px;
            margin-right: 40px;
        }
    </style>
    <script>
        window.print();
        onafterprint = () => {
            window.location.href = "brgyclearance.php?page=brgyclearance";
        }
    </script>
</head>
<body class="skin-black">
    <?php
    session_start(); // Start session management

    // Redirect to login if the user role is not set
    if (!isset($_SESSION['role'])) {
        header("Location: ../../login.php");
        exit;
    }

    ob_start(); // Start output buffering
    $_SESSION['clr'] = $_GET['clearance']; // Store clearance ID in session
    include('../head_css.php'); // Include CSS file
    include "../connection.php"; // Include database connection
    ?>
    <!-- Header Section -->
    <div class="header-section" style="background: white; padding: 20px 0; margin-bottom: 30px;">
    <div class="col-xs-4 col-sm-3 col-md-2" style="background: white; margin-right: -124px; padding: 10px;">
        <img src="../../admin/staff/logo/<?= $_SESSION['logo'] ?>" style="width:70%; height:120px;" />
    </div>
    <div class="col-xs-7 col-sm-6 col-md-8" style="background: white; padding: 10px;">
        <div class="pull-left" style="font-size: 16px; margin-left: 100px; font-family: 'Courier New', Courier;">
            <center>
                Republic of the Philippines<br>
                Region VII-Northern Visayas<br>
                Province of Cebu<br>
                Municipality of Madridejos
                <b>
                    <p style="font-size: 22px; font-family: 'Courier New', Courier; text-transform: uppercase;">Barangay <?= $_SESSION['barangay'] ?></p>
                </b>
            </center>
            <hr style="border: 1px solid black; width: 257%; margin: 1px auto; position: relative; right: 205px;" />
        </div>
    </div>
    <div class="col-xs-4 col-sm-3 col-md-2" style="background: white; margin-left: -82px; position: relative; left: 85px; padding: 10px;">
        <img src="../../img/lg.png" style="width:70%; height:120px;" />
    </div>
    </div>
    <!-- Main Content -->
    <div class="main-content col-xs-12 col-md-12">
        <br><br>
        <p class="text-center" style="font-size: 20px; font-weight: bold; margin-right: 70px;">
            OFFICE OF THE BARANGAY CAPTAIN<br>
            <b style="font-size: 28px;">BARANGAY CLEARANCE</b>
        </p>
        <br>
        <p style="font-size: 12px; font-family: 'Courier New', Courier;">TO WHOM IT MAY CONCERN:</p>
        <p style="text-indent:40px; text-align: justify;">
        <?php
        // Get barangay from session
        $off_barangay = $_SESSION["barangay"] ?? "";
        // Get the resident's name from the URL parameter
        $name = $_GET['resident'];

        // Ensure proper escaping to avoid SQL injection
        $name = mysqli_real_escape_string($con, $name);
        $off_barangay = mysqli_real_escape_string($con, $off_barangay);

        // Query to select clearance details along with age, bdate, and purok from tbltabagak
        $squery = mysqli_query($con, "SELECT * FROM tblclearance WHERE name = '$name' AND barangay = '$off_barangay' LIMIT 1");
        
        // Loop through clearance details
        if ($row = mysqli_fetch_array($squery)) {
            echo "<p style='font-family: \"Courier New\"; text-align: justify; font-size: 15px;'>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that <strong><u>" . strtoupper($row['Name']) . "</u></strong>, 
            <strong><u>" . $row['age'] . "</u></strong> years of age, 
            born on <br><strong><u>" . $row['bdate'] . "</u></strong>, 
            a native of <strong><u>Madridejos</u></strong>, and presently residing at Purok " . 
            $row['purok'] . "<br>Barangay " . $row['barangay'] . ", Madridejos, Cebu.</p>";
        } else {
            echo "No clearance found for the selected resident in the current barangay.";
        }
        ?>
        </p>
        <br>
        <p style="font-size: 12px; font-family: 'Courier New', Courier;">
            <?php
            $name = $_GET['resident'];
            $squery = mysqli_query($con, "SELECT * FROM tblclearance WHERE name = '$name' LIMIT 1");

            if ($row = mysqli_fetch_array($squery)) {
                echo "<p style='font-family: \"Courier New\"; font-size: 17px;'> Purpose: " . strtoupper($row['purpose']) . "</p>";
            }
            ?> 
        </p>
        <br>
        <p style="text-indent:40px; text-align: justify;">
            <?php
            $off_barangay = $_SESSION["barangay"] ?? ""; // Get barangay from session
            $squery = mysqli_query($con, "SELECT * FROM tblclearance WHERE name = '$name' AND barangay = '$off_barangay' LIMIT 1");

            if ($row = mysqli_fetch_array($squery)) {
                echo "<p style='font-family: \"Courier New\"; text-align: justify; font-size: 15px;'>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certification is issued upon the request of <strong><u>" . strtoupper($row['Name']) . "</u></strong>
                for <br>any legal purpose it may serve.</p>";
            }
            ?> 
        </p>
        <br>
        <br>
        <p style="font-size: 17px; font-family: 'Courier New', Courier;"><b>Remarks: <u style="text-transform: uppercase;">EYY KA MUNA SA BRGY.<?= $_SESSION['barangay'] ?></u></b></p>
    </div> 

    <div class="col-xs-offset-6 col-xs-5 col-md-offset-6 col-md-4" style="top: 250px;">
        <p style="text-align: center;">
            <?php
                // Assuming a session has already been started somewhere in your code
                $off_barangay = $_SESSION["barangay"] ?? ""; // Get barangay from session
                // Adjust the query to filter by barangay
                $qry = mysqli_query($con, "SELECT * FROM tblbrgyofficial WHERE barangay = '$off_barangay'");
                while($row = mysqli_fetch_array($qry)){
                    if($row['sPosition'] == "Captain"){
                        echo '
                        <strong style="font-size: 18px; margin-right: 5px;">'.strtoupper($row['completeName']).'</strong><br>
                        <hr style="border: 1px solid black; width: 90%; margin: 1px auto;" />
                        <span style="margin-left: 85px;">Punong Barangay</span>
                        ';
                    }
                }
            ?>
        </p>
    </div>
</body>
</html>