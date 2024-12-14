<!DOCTYPE html>
<html>
<head>
    <title>Madridejos Household Management System</title>
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
            overflow: hidden; /* Prevents body from scrolling */
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
        /* Overlay Image Styles */
        .overlay-image {
            position: fixed; /* Fixed position relative to the page */
            top: 50%; /* Position it vertically at the center */
            left: 50%; /* Position it horizontally at the center */
            width: 100%; /* Make the image span across the page */
            height: 72%; /* Make the image cover the full page */
            z-index: -1; /* Ensure the image is behind the text */
            opacity: 0.1; /* Make the image semi-transparent */
            pointer-events: none; /* Disable interactions with the image */
            object-fit: cover; /* Ensures the image scales nicely */
            transform: translate(-50%, -50%); /* Adjusts the image to be truly centered */
        }
    </style>
    <script>
        window.print();
        onafterprint = () => {
            window.location.href = "certofindigency";
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
                Province of Cebu<br>
                Municipality of Madridejos
                <b>
                    <p style="font-size: 22px; font-family: 'Courier New', Courier; text-transform: uppercase;color: dodgerblue !important;">Barangay <?= $_SESSION['barangay'] ?></p>
                </b>
            </center>
            <p style="text-transform: uppercase;margin-left:-10px;">OFFICE OF THE BARANGAY CAPTAIN</p>
            <hr style="border: 1px solid black; width: 240%; margin: 1px auto; position: relative; right: 215px;" />
        </div>
    </div>
    <div class="col-xs-4 col-sm-3 col-md-2" style="background: white; margin-left: -82px; position: relative; left: 85px; padding: 10px;">
        <img src="../../img/lg.png" style="width:70%; height:120px;" />
    </div>
    <!-- Overlay Image -->
    <img src="../../admin/staff/logo/<?= $_SESSION['logo'] ?>" class="overlay-image" />
    </div>
    <!-- Main Content -->
    <div class="main-content col-xs-12 col-md-12">
        <p class="text-center" style="font-size: 20px; font-weight: bold; margin-right: 40px;">
            <b style="font-size: 28px;">CERTIFICATE OF INDIGENCY</b>
        </p>
        <br>
        <p style="font-size: 12px; font-family: 'Courier New', Courier;">TO WHOM IT MAY CONCERN:</p>
        <br>
        <p>
        <?php
        // Get barangay from session
        $off_barangay = $_SESSION["barangay"] ?? "";
        // Get the resident's name from the URL parameter
        $name = $_GET['resident'];

        // Ensure proper escaping to avoid SQL injection
        $name = mysqli_real_escape_string($con, $name);
        $off_barangay = mysqli_real_escape_string($con, $off_barangay);

        // Query to select clearance details along with age, bdate, and purok from tbltabagak
        $squery = mysqli_query($con, "SELECT * FROM tblindigency WHERE name = '$name' AND barangay = '$off_barangay' LIMIT 1");
        
        // Loop through clearance details
        if ($row = mysqli_fetch_array($squery)) {
            echo "<p style='font-family: \"Courier New\", Courier, monospace; text-align: justify; font-size: 16px;margin: 10px 50px 10px 3px;'>
                &nbsp;&nbsp;&nbsp;This is to certify that <strong>" . strtoupper($row['Name']) . "</strong>, of legal age, " . $row['gender'] . ", resident of 
                Purok " . $row['purok'] . ", Barangay " . $row['barangay'] . ", Madridejos, Cebu, is personally known to the undersigned.It is acknowledged that he/she belongs to an INDIGENT FAMILY.</strong>
            </p>";
        }
        ?>
        </p>
        <br>
        <p>
            <?php
            $off_barangay = $_SESSION["barangay"] ?? ""; // Get barangay from session
            $squery = mysqli_query($con, "SELECT * FROM tblindigency WHERE name = '$name' AND barangay = '$off_barangay' LIMIT 1");

            if ($row = mysqli_fetch_array($squery)) {
                echo "<p style='font-family: \"Courier New\", Courier, monospace; text-align: justify; font-size: 16px;margin: 10px 50px 10px 3px;'>
                &nbsp;&nbsp;&nbsp;This certification is issued upod the request of the above-name for <strong>" . $row['purpose'] . "</strong> purposes; it may serve him/her best.</p>";
            }
            ?> 
        </p>
        <br>
        <br>
        <p style="margin-left:-12px; font-size: 16px; font-family: 'Courier New', Courier; text-indent:40px; text-align: justify;margin: 10px 50px 10px 3px;">
            <?php
                $name = $_GET['resident'];
                $squery = mysqli_query($con, "SELECT * FROM tblindigency WHERE name = '$name' LIMIT 1");
            
                if ($row = mysqli_fetch_array($squery)) {
                    $dateRecorded = $row['dateRecorded'];
                    echo "<span style='font-family: \"Courier New\", Courier, monospace; text-align: justify; font-size: 15px;'>
                        Given this day <strong>". date('j', strtotime($dateRecorded)) ."<sup>". date('S', strtotime($dateRecorded)) ."</sup></strong> of 
                        <strong>" . date('F', strtotime($dateRecorded)) . "</strong>, <strong>" . date('Y', strtotime($dateRecorded)) . "</strong> 
                        at Barangay Hall <strong>" . $row['barangay'] . ", Madridejos</strong><strong> Cebu, Philippines.</strong>
                    </span>";
                }
            ?>
    <br>
    <br>
    <br>
    <br>
    <br>
    <i>Prepared by:</i>
    <br>
    <br>
        <p style="text-align: center;">
            <?php
                // Assuming a session has already been started somewhere in your code
                $off_barangay = $_SESSION["barangay"] ?? ""; // Get barangay from session
                // Adjust the query to filter by barangay
                $qry = mysqli_query($con, "SELECT * FROM tblbrgyofficial WHERE barangay = '$off_barangay'");
                while($row = mysqli_fetch_array($qry)){
                    if($row['sPosition'] == "Secretary"){
                        echo '
                        <strong style="font-size: 18px; margin-left: -440px;">HON.'.strtoupper($row['completeName']).'</strong><br>
                        <hr style="border: 1px solid black; width: 32%; margin-left: 40px;margin-top: -15px;" />
                        <span style="margin-left: 85px;margin-top: -15px;">Barangay Secretary</span>
                        ';
                    }
                }
            ?>
        </p>
        </p>
    </div>
    <div class="col-xs-offset-6 col-xs-5 col-md-offset-6 col-md-4" style="top: 170px;">
    <i>Approved by:</i>
    <br>
    <br>
        <p style="text-align: center;">
            <?php
                // Assuming a session has already been started somewhere in your code
                $off_barangay = $_SESSION["barangay"] ?? ""; // Get barangay from session
                // Adjust the query to filter by barangay
                $qry = mysqli_query($con, "SELECT * FROM tblbrgyofficial WHERE barangay = '$off_barangay'");
                while($row = mysqli_fetch_array($qry)){
                    if($row['sPosition'] == "Captain"){
                        echo '
                        <strong style="font-size: 18px; margin-right: 3px;">HON.'.strtoupper($row['completeName']).'</strong><br>
                        <hr style="border: 1px solid black; width: 90%; margin: 1px auto;margin-top: -15px;" />
                        <span style="margin-left: 85px;">Punong Barangay</span>
                        ';
                    }
                }
            ?>
        </p>
    </div>
</body>
</html>