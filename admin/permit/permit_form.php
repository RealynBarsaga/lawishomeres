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

            /* Overlay Image Styles */
            .overlay-image {
                position: fixed; /* Fixed position relative to the page */
                top: 0; /* Position at the top */
                left: 0; /* Position at the left */
                width: 100%; /* Make the image span across the page */
                height: 91%; /* Make the image cover the full page */
                z-index: -1; /* Ensure the image is behind the text */
                opacity: 0.7; /* Make the image semi-transparent */
                pointer-events: none; /* Disable interactions with the image */
                object-fit: cover; /* Ensures the image scales nicely */
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
            position: relative; /* Allows positioning of absolute elements inside */
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
    </style>
    <script>
        window.print();
        onafterprint = () => {
            window.location.href = "permit.php";
        }
    </script>
</head>
<body class="skin-black">
    <!-- Overlay Image -->
    <img src="../../img/received_1185064586170879.jpeg" alt="Overlay Image" class="overlay-image">
    
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
    <div class="header-section" style="margin-bottom: 30px;">
        <!-- Left Logo -->
        <div class="col-xs-4 col-sm-3 col-md-2" style="padding: 10px; float: left;margin-top: 45px;">
            <img src="../../img/lg.png" style="width: 170px; height: 135px;" />
        </div>
        <!-- Centered Text Content -->
        <div class="col-xs-7 col-sm-6 col-md-8" style="text-align: center;margin-top: 30px;">
            <div style="font-size: 15px; font-family: 'Courier New', Courier;">
                <p style="margin-right: 180px;">Republic of the Philippines<br>
                Province of Cebu<br>
                <span style="font-size: 15px; font-weight: bolder;margin-left: -1px;">Municipality of Madridejos</span></p>
            </div>
            <p style="font-size: 23px; font-weight: bolder; margin-left: -190px;margin-top: -5px;">OFFICE OF THE MUNICIPAL MAYOR</p>
            <p style="font-size: 10px; font-weight: bolder;margin-left: -190px;margin-top: -15px;">BUSINESS PERMIT AND LICENSING OFFICE</p>
        </div>
        <!-- Right Logo -->
        <div class="col-xs-4 col-sm-3 col-md-2" style="padding: 10px; float: right;margin-top: -115px;">
            <img src="../../img/mayors.jfif" style="width: 170px; height: 135px;margin-left: 70px; border-radius: 50%;" />
        </div>

    </div>

    <!-- Main Content -->
    <div class="main-content col-xs-12 col-md-12">
    <p class="text-center" style="font-size: 60px; font-weight: 900; margin-right: 50px; color: #FFA500 !important; -webkit-text-stroke: 1px #000;">
        BUSINESS PERMIT
    </p>

    <!-- Date Issued aligned to the right -->
    <p style="font-family: 'Courier New', Courier; text-align: right; font-size: 15px; margin-right: 40px;margin-top: -20px;">
        <?php
            $name = $_GET['resident'];
            $squery = mysqli_query($con, "SELECT * FROM tblpermit WHERE name = '$name' LIMIT 1");
    
            if ($row = mysqli_fetch_array($squery)) {
                $dateRecorded = $row['dateRecorded'];
                echo "<span style='font-family: \"Courier New\", Courier, monospace;'>
                    <strong>Date Issued:</strong><br>
                    <strong>" . date('M j, Y', strtotime($dateRecorded)) . "</strong>
                </span>";
            }
        ?>
    </p>

    <!-- Permit No aligned to the left -->
    <p style="font-family: 'Courier New', Courier; text-align: left; font-size: 15px; margin-left: -20px;margin-top: -50px;">
        <?php
            $name = $_GET['resident'];
            $squery = mysqli_query($con, "SELECT * FROM tblpermit WHERE name = '$name' LIMIT 1");
    
            if ($row = mysqli_fetch_array($squery)) {
                $orNo = $row['orNo'];
                echo "<span style='font-family: \"Courier New\", Courier, monospace;'>
                    <strong>&nbsp;&nbsp;Permit No:</strong><br>
                    <strong style='margin-left: -20px;'>" . $row['orNo'] . "</strong>
                </span>";
            }
        ?>
    </p>
    </div>
    <div class="main-content col-xs-12 col-md-12">
    <p class="text-center" style="font-size: 45px;margin-left: -65px;">
        <?php
            $name = $_GET['resident'];
            $squery = mysqli_query($con, "SELECT * FROM tblpermit WHERE name = '$name' LIMIT 1");
    
            if ($row = mysqli_fetch_array($squery)) {
                $businessName = $row['businessName'];
                echo "<span>
                    <strong style='font-weight: 900; color: #FFA500 !important; -webkit-text-stroke: 1px #000;text-transform: uppercase;'>" . $row['businessName'] . "</strong><br>
                    <p style='font-size: 20px;margin-left: 275px;margin-top: -25px;'>Business Name</p>
                </span>";
            }
        ?>
    </p>
    <p class="text-center" style="font-size: 20px;margin-left: -65px;">
        <?php
            $name = $_GET['resident'];
            $squery = mysqli_query($con, "SELECT * FROM tblpermit WHERE name = '$name' LIMIT 1");
    
            if ($row = mysqli_fetch_array($squery)) {
                $businessAddress = $row['businessAddress'];
                echo "<span>
                    <strong style='font-weight: 900; color: #000;text-transform: uppercase;'>" . $row['businessAddress'] . "</strong><br>
                    <p style='font-size: 20px;margin-left: 265px;margin-top: -20px;'>Business Address</p>
                </span>";
            }
        ?>
    </p>
    <p class="text-center" style="font-size: 30px;margin-left: -65px;">
        <?php
            $name = $_GET['resident'];
            $squery = mysqli_query($con, "SELECT * FROM tblpermit WHERE name = '$name' LIMIT 1");
    
            if ($row = mysqli_fetch_array($squery)) {
                $Name = $row['Name'];
                echo "<span>
                    <strong style='font-weight: 900; color: #FFA500 !important; -webkit-text-stroke: 1px #000;text-transform: uppercase;'>" . $row['Name'] . "</strong><br>
                    <p style='font-size: 20px;margin-left: 275px;margin-top: -25px;'>Owner’s Name</p>
                </span>";
            }
        ?>
    <br>
    <p class="text-left" style="margin-left: -30px;margin-top: -20px;">
    <span style="font-size: 18px;font-weight: 900; color: #FFFFFF !important;"> 
        <!-- Business ID No with box -->
        <strong style="font-weight: 900; color: #FFFFFF !important; -webkit-text-stroke: 1px #000;">
            Business ID No:
        </strong><br>
        <!-- Official Receipt No with box -->
        <strong style="font-weight: 900; color: #FFFFFF !important; -webkit-text-stroke: 1px #000;">
            Official Receipt No: 
        </strong><br>
        <!-- OR Date with box -->
        <strong style="font-weight: 900; color: #FFFFFF !important; -webkit-text-stroke: 1px #000;">
            OR Date: 
        </strong><br>

        <!-- Type of Business with box -->
        <strong style="font-weight: 900; color: #FFFFFF !important; -webkit-text-stroke: 1px #000;">
            Type of Business: 
        </strong><br>

        <!-- Type of Application with box -->
        <strong style="font-weight: 900; color: #FFFFFF !important; -webkit-text-stroke: 1px #000;">
            Type of Application:    
        </strong><br>

        <!-- Line of Business with box -->
        <strong style="font-weight: 900; color: #FFFFFF !important; -webkit-text-stroke: 1px #000;">
            Line of Business: 
        </strong><br>

        <!-- Payment Mode with box -->
        <strong style="font-weight: 900; color: #FFFFFF !important; -webkit-text-stroke: 1px #000;">
            Payment Mode: 
        </strong><br><br>

        <!-- Amount Paid with box -->
        <strong style="font-weight: 900; color: #FFFFFF !important; -webkit-text-stroke: 1px #000;">
            Amount Paid: 
        </strong>
    </span>
    </p>
    <div class="col-md-5 col-xs-4" style="float:left;margin-top: -239px;margin-left: 165px;">
    <!-- Image -->
    <img src="../../img/image.png" alt="Image" style="margin-top: -65px;margin-left: -9px;">
    
    <!-- Text above the image -->
    <div style="text-align: center; margin-top: -86px;margin-left: -9px;"> <!-- Added margin-top to create space between image and text -->
        <span>
            <?php
                // Fetching data from the database and displaying the Business ID
                $name = $_GET['resident'];
                $squery = mysqli_query($con, "SELECT * FROM tblpermit WHERE name = '$name' LIMIT 1");
                if ($row = mysqli_fetch_array($squery)) {
                     echo $row['bussinessidno']; // Assuming 'bussinessidno' is the column name in your database
                }
            ?>
        </span>
    </div>
    </div>
    <div class="col-md-5 col-xs-4" style="float:left;margin-top: -213px;margin-left: 165px;">
    <!-- Image -->
    <img src="../../img/image.png" alt="Image" style="margin-top: -65px;margin-left: -9px;">

    <!-- Text above the image -->
    <div style="text-align: center; margin-top: -86px;margin-left: -9px;"> <!-- Added margin-top to create space between image and text -->
        <span>
            <?php
                // Fetching data from the database and displaying the Business ID
                $name = $_GET['resident'];
                $squery = mysqli_query($con, "SELECT * FROM tblpermit WHERE name = '$name' LIMIT 1");
                if ($row = mysqli_fetch_array($squery)) {
                     echo $row['offreceiptno']; // Assuming 'typeOfBusiness' is the column name in your database
                }
            ?>
        </span>
    </div>
    </div>
    <div class="col-md-5 col-xs-4" style="float:left;margin-top: -188px;margin-left: 165px;">
    <!-- Image -->
    <img src="../../img/image.png" alt="Image" style="margin-top: -65px;margin-left: -9px;">

    <!-- Text above the image -->
    <div style="text-align: center; margin-top: -86px;margin-left: -9px;"> <!-- Added margin-top to create space between image and text -->
        <span>
            <?php
                // Fetching data from the database and displaying the Business ID
                $name = $_GET['resident'];
                $squery = mysqli_query($con, "SELECT * FROM tblpermit WHERE name = '$name' LIMIT 1");
                if ($row = mysqli_fetch_array($squery)) {
                     echo $row['ordate']; // Assuming 'typeOfBusiness' is the column name in your database
                }
            ?>
        </span>
    </div>
    </div>
    <div class="col-md-5 col-xs-4" style="float:left;margin-top: -161px;margin-left: 165px;">
    <!-- Image -->
    <img src="../../img/image.png" alt="Image" style="margin-top: -65px;margin-left: -9px;">

    <!-- Text above the image -->
    <div style="text-align: center; margin-top: -86px;margin-left: -9px;"> <!-- Added margin-top to create space between image and text -->
        <span>
            <?php
                // Fetching data from the database and displaying the Business ID
                $name = $_GET['resident'];
                $squery = mysqli_query($con, "SELECT * FROM tblpermit WHERE name = '$name' LIMIT 1");
                if ($row = mysqli_fetch_array($squery)) {
                     echo $row['typeOfBusiness']; // Assuming 'typeOfBusiness' is the column name in your database
                }
            ?>
        </span>
    </div>
    </div>
    <div class="col-md-5 col-xs-4" style="float:left;margin-top: -136px;margin-left: 165px;">
    <!-- Image -->
    <img src="../../img/image.png" alt="Image" style="margin-top: -65px;margin-left: -9px;">

    <!-- Text above the image -->
    <div style="text-align: center; margin-top: -86px;margin-left: -9px;"> <!-- Added margin-top to create space between image and text -->
        <span>
            <?php
                // Fetching data from the database and displaying the Business ID
                $name = $_GET['resident'];
                $squery = mysqli_query($con, "SELECT * FROM tblpermit WHERE name = '$name' LIMIT 1");
                if ($row = mysqli_fetch_array($squery)) {
                     echo $row['typeofapplication']; // Assuming 'typeOfBusiness' is the column name in your database
                }
            ?>
        </span>
    </div>
    </div>
    <div class="col-md-5 col-xs-4" style="float:left;margin-top: -109px;margin-left: 165px;">
    <!-- Image -->
    <img src="../../img/image.png" alt="Image" style="margin-top: -65px;margin-left: -9px;">

    <!-- Text above the image -->
    <div style="text-align: center; margin-top: -86px;margin-left: -9px;"> <!-- Added margin-top to create space between image and text -->
        <span>
            <?php
                // Fetching data from the database and displaying the Business ID
                $name = $_GET['resident'];
                $squery = mysqli_query($con, "SELECT * FROM tblpermit WHERE name = '$name' LIMIT 1");
                if ($row = mysqli_fetch_array($squery)) {
                     echo "<span style='text-transform: uppercase;'>
                     " . $row['lineofbussiness'] . "</span>"; // Assuming 'typeOfBusiness' is the column name in your database
                }
            ?>
        </span>
    </div>
    </div>
    <div class="col-md-5 col-xs-4" style="float:left;margin-top: -85px;margin-left: 165px;">
    <!-- Image -->
    <img src="../../img/image.png" alt="Image" style="margin-top: -65px;margin-left: -9px;">

    <!-- Text above the image -->
    <div style="text-align: center; margin-top: -86px;margin-left: -9px;"> <!-- Added margin-top to create space between image and text -->
        <span>
            <?php
                // Fetching data from the database and displaying the Business ID
                $name = $_GET['resident'];
                $squery = mysqli_query($con, "SELECT * FROM tblpermit WHERE name = '$name' LIMIT 1");
                if ($row = mysqli_fetch_array($squery)) {
                     echo $row['paymentmode']; // Assuming 'typeOfBusiness' is the column name in your database
                }
            ?>
        </span>
    </div>
    </div>
    <div class="col-md-5 col-xs-4" style="float:left;margin-top: -40px;margin-left: 165px;">
    <!-- Image -->
    <img src="../../img/image1.png" alt="Image" style="margin-top: -65px;margin-left: -9px;">

    <!-- Text above the image -->
    <div style="text-align: center; margin-top: -80px;margin-left: -9px;"><!-- Added margin-top to create space between image and text -->
        <span>
        <?php
            // Fetching data from the database and displaying the Business ID
            $name = $_GET['resident'];
            $squery = mysqli_query($con, "SELECT * FROM tblpermit WHERE name = '$name' LIMIT 1");
            if ($row = mysqli_fetch_array($squery)) {
                 echo "PHP: " . number_format($row['samount'], 2); // Display 'PHP' instead of '₱'
            }
        ?>
        </span>
    </div>
    </div><br>
    <p class="text-left" style="margin-left: -30px;margin-top: -10px;">
    <span style="font-size: 17px;font-weight: 900; letter-spacing: -1px; line-height: 1.2;">
        <strong style="font-weight: 900; color: #FFFFFF !important; -webkit-text-stroke: 1px #000;">
            1.This permit must be displayed in a conspicuous place within the establishment.<br>
            2.This permit is only a privilage and not a right, subject to REVOCATION and CLOSURE <br>&nbsp;&nbsp;&nbsp;of business establishment for any violation of existing laws and ordinances and <br>&nbsp;&nbsp;&nbsp;conditions set forth in this permit.<br>
            3.This permit must be renewed on or before January 20 of the following year unless <br>&nbsp;&nbsp;&nbsp;sooner revoked for a cause. Failure to renew within the time required shall subject the <br>&nbsp;&nbsp;&nbsp;taxpayer to a surcharge of 25% to the amount of business taxes due plus an interest of 
            <br>&nbsp;&nbsp;&nbsp;2% per month of the unpaid business taxex.<br>
            4.In case of business closure, surrender this permit to the MUNICIPAL TREASURER for the <br>&nbsp;&nbsp;&nbsp;official retirement.
        </strong>  
    </span>
    </p>
    </p>
    <div class="col-xs-offset-6 col-xs-5 col-md-offset-6 col-md-4" style="top: 25px;">
        <p style="text-align: center;">
            <?php
                // Adjust the query to filter by barangay
                $qry = mysqli_query($con, "SELECT * FROM tblmadofficial");
                while($row = mysqli_fetch_array($qry)){
                    if($row['sPosition'] == "Mayor"){
                        echo '
                        <strong style="font-size: 17px; margin-left: 33px;">ENGR.'.strtoupper($row['completeName']).'</strong>
                        <hr style="border: 0.1px solid black; width: 88%; margin-left: 33px;margin-top: -15px;"/>
                        <p style="margin-left: 110px; margin-top: -20px;">Municipal Mayor</p>
                        ';
                    }
                }
            ?>
        </p>
    </div>
    <!-- Valid Up To Text -->
    <p style="text-align: center; font-weight: bold; margin-top: -1px;">
         <!-- Image -->
    <img src="../../img/images.png" alt="Image" style="margin-left: -1140px; width: 400px; height: 100px;">

        <span style="margin-left: -380px;font-size: 25px;letter-spacing: -2px;text-transform: uppercase;">VALID UP TO
        <?php
    $name = $_GET['resident'];
    $squery = mysqli_query($con, "SELECT * FROM tblpermit WHERE name = '$name' LIMIT 1");

    // Get the current date
    $currentDate = new DateTime();
    
    if ($row = mysqli_fetch_array($squery)) {
        $dateRecorded = $row['dateRecorded'];

        // Clone the current date to keep the original intact
        $expirationDate = new DateTime($dateRecorded);

        // Add 1 year to the expiration date
        $expirationDate->modify('+1 year');

        // Display the expiration date, formatted as "Month day, Year"
        // Use $expirationDate instead of the original $dateRecorded
        echo "<strong>" . $expirationDate->format('F j, Y') . "</strong>";
    }
?>
        </span>
    </p>
</body>
</html>