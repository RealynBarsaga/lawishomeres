<!DOCTYPE html>
<html>
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
            window.location.href = "certofres";
        }
    </script>
</head>
<body class="skin-black">
    <?php
    session_start(); // Start session management

    // Set timezone to Manila
    date_default_timezone_set('Asia/Manila');

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
            <p style="font-weight: bold;margin-left:-5px;">OFFICE OF THE PUNONG BARANGAY</p>
            <hr style="border: 1px solid black; width: 252%; margin: 1px auto; position: relative; right: 210px;" />
        </div>
    </div>
    <div class="col-xs-4 col-sm-3 col-md-2" style="background: white; margin-left: -82px; position: relative; left: 85px; padding: 10px;">
        <img src="../../img/lg.png" style="width:70%; height:120px;" />
    </div>
    <!-- Overlay Image -->
    <img src="../../admin/staff/logo/<?= $_SESSION['logo'] ?>" class="overlay-image" />
    </div>
    <div class="col-xs-4 col-sm-6 col-md-3" style="margin-top: -14px;background: white; margin-left:50px; border: 1px solid black;width: 200px;height:790px;">
        <div style="margin-top:40px; text-align: center; word-wrap: break-word;font-size:15px;">
            <p style="font-size:12px;font-weight: 600;">SANGGUNIANG BARANGAY</p>
            <?php
            $off_barangay = $_SESSION["barangay"] ?? "";

                $qry = mysqli_query($con,"SELECT * from tblbrgyofficial WHERE barangay = '$off_barangay'");
                while($row=mysqli_fetch_array($qry)){
                    if($row['sPosition'] == "Captain"){
                        echo '
                            <p style="text-align: center;">
                            <b style="font-size:10.5px;text-decoration: underline;">HON.'.strtoupper($row['completeName']).'</b>
                            <span style="font-size:12px;">Barangay Captain</span><br>
                            </p><br>
                        ';
                    echo '
                    <p style="font-size:12px;font-weight: 600;">
                    SANGGUNIANG BARANGAY MEMBERS
                    </p>';
                    }elseif($row['sPosition'] == "Kagawad"){
                        echo '
                        <p style="text-align: center;">
                        <b style="font-size:10.5px;  text-decoration: underline;">HON.'.strtoupper($row['completeName']).'</b><br>
                        <span style="font-size:12px;">&nbsp;&nbsp;&nbsp;Barangay Kagawad</span>
                        </p>
                        ';
                    }elseif($row['sPosition'] == "SK"){
                        echo '
                        <div style="text-align: center;"><br>
                            <b style="font-size:10.5px; text-decoration: underline;">'.strtoupper($row['completeName']).'</b><br>
                            <span style="font-size:12px;">SK Chairman:</span><br>
                        </div>';
                    }elseif($row['sPosition'] == "Secretary") {
                        echo '
                        <div style="text-align: center;"><br>
                            <b style="font-size:10.5px; text-decoration: underline;">'.strtoupper($row['completeName']).'</b><br>
                            <span style="font-size:12px;">Barangay Secretary:</span><br>
                        </div>';
                    } elseif($row['sPosition'] == "Treasurer") {
                        echo '
                        <div style="text-align: center;">
                            <b style="font-size:10.5px; text-decoration: underline;">'.strtoupper($row['completeName']).'</b><br>
                            <span style="font-size:12px;">Barangay Treasurer:</span><br>
                        </div>';
                    }
                }
            ?>
        </div>
    </div>
    <!-- Main Content -->
    <div class="main-content col-xs-12 col-md-12">
        <br><br>
        <p class="text-center" style="font-size: 20px; font-weight: bold; margin-left: 100px;margin-top:-730px;">
            <b style="font-size: 23px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>CERTIFICATE OF RESIDENCY</u></b>
        </p><br>
        <p style="margin-left: 220px;font-size: 12px; font-family: 'Courier New', Courier;">TO WHOM IT MAY CONCERN:</p>
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
            $squery = mysqli_query($con, "SELECT * FROM tblrecidency WHERE name = '$name' AND barangay = '$off_barangay' LIMIT 1");
            
            // Loop through clearance details
            if ($row = mysqli_fetch_array($squery)) {
                echo "<p style='font-family: \"Courier New\", Courier, monospace; text-align: justify; font-size: 15px;margin-left: 220px;margin-right: 60px;'>
                &nbsp;&nbsp;&nbsp;This is to certify that <strong>" . strtoupper($row['Name']) . "</strong> a Filipino citizen 
                of " . $row['age'] . " years old a bonafide resident of Purok " . $row['purok'] . ", Barangay " . $row['barangay'] . ", 
                Madridejos, Cebu.</p>";
            }
        ?>
        </p>
        <br>
        <p>
            <?php
            $name = $_GET['resident'];
            $squery = mysqli_query($con, "SELECT * FROM tblrecidency WHERE name = '$name' LIMIT 1");

            if ($row = mysqli_fetch_array($squery)) {
                echo "<p style='font-family: \"Courier New\", Courier, monospace; text-align: justify; font-size: 15px;margin-left: 220px;margin-right: 60px;'>
                    &nbsp;&nbsp;&nbsp;This certification is being issued upon the request of the interested person for " .  strtoupper($row['purpose']) . " purposes it may serve him/her best.</p>";
            }
            ?> 
        </p>
        <br>
        <p style="margin-left: 220px; margin-right: 60px; font-family: 'Courier New', Courier; text-indent:15px; text-align: justify;">
            <?php
                $name = $_GET['resident'];
                $squery = mysqli_query($con, "SELECT * FROM tblrecidency WHERE name = '$name' LIMIT 1");
            
                if ($row = mysqli_fetch_array($squery)) {
                    $dateRecorded = $row['dateRecorded'];
                    echo "<span style='font-family: \"Courier New\", Courier, monospace; text-align: justify; font-size: 15px;'>
                        &nbsp;&nbsp;Done this " . date('j', strtotime($dateRecorded)) . "<sup>" . date('S', strtotime($dateRecorded)) . "</sup> day of 
                        " . date('F', strtotime($dateRecorded)) . " " . date('Y', strtotime($dateRecorded)) . ", 
                        at Barangay " . $row['barangay'] . ", Madridejos, Cebu.
                    </span>";
                }
            ?>
        </p>
    </p>
    <br>
    <br>
    <p>
        <strong style="margin-left: 225px;">CERTIFIED BY:</strong>
    </p>
    </div>
    <div class="col-xs-offset-6 col-xs-5 col-md-offset-6 col-md-4" style="top: 70px;">
        <p style="text-align: center;">
            <?php
                // Assuming a session has already been started somewhere in your code
                $off_barangay = $_SESSION["barangay"] ?? ""; // Get barangay from session
                // Adjust the query to filter by barangay
                $qry = mysqli_query($con, "SELECT * FROM tblbrgyofficial WHERE barangay = '$off_barangay'");
                while($row = mysqli_fetch_array($qry)){
                    if($row['sPosition'] == "Captain"){
                        echo '
                        <strong style="font-size: 17px; margin-left: 40px;">HON.'.strtoupper($row['completeName']).'</strong>
                        <hr style="border: 0.1px solid black; width: 69%; margin-left: 65px;margin-top: -15px;"/>
                        <p style="margin-left: 110px; margin-top: -20px;">Punong Barangay</p>
                        ';
                    }
                }
            ?>
        </p>
    </div>
</body>
</html>