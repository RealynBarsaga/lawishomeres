<!DOCTYPE html>
<html>
<head>
    <title>Madridejos Home Residence Management System</title>
    <link rel="icon" type="x-icon" href="../../img/lg.png">
    <style>
        @media print {
            .noprint {
                visibility: hidden;
            }

            .overlay-image {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 91%;
                z-index: -1;
                opacity: 0.7;
                pointer-events: none;
                object-fit: cover;
            }
        }

        @page {
            size: auto;
            margin: 4mm;
        }

        body {
            margin: 20px;
            overflow: hidden;
            position: relative;
        }

        .header-section {
            margin-bottom: 30px;
        }

        .main-content {
            margin-left: 30px;
            margin-right: 30px;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        /* Additional styles for proper spacing */
        .inline-block {
            display: inline-block;
            vertical-align: top;
            margin-right: 20px;
        }

        .label-text {
            font-weight: 900;
            font-size: 18px;
            color: #FFFFFF !important;
            -webkit-text-stroke: 1px #000;
        }

        .data-text {
            font-weight: 900;
            color: #000;
            text-transform: uppercase;
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

    <img src="../../img/received_1185064586170879.jpeg" alt="Overlay Image" class="overlay-image">

    <?php
    session_start(); 
    if (!isset($_SESSION['role'])) {
        header("Location: ../../login.php");
        exit;
    }

    include('../head_css.php');
    include "../connection.php"; 

    $name = $_GET['resident'];
    $squery = mysqli_query($con, "SELECT * FROM tblpermit WHERE name = '$name' LIMIT 1");
    $permitData = mysqli_fetch_array($squery);
    ?>

    <div class="header-section">
        <div class="inline-block">
            <img src="../../img/lg.png" style="width: 170px; height: 135px;" />
        </div>
        <div class="inline-block" style="text-align: center;">
            <div style="font-size: 16px; font-family: 'Courier New', Courier;">
                <p>Republic of the Philippines<br>Province of Cebu<br><strong>Municipality of Madridejos</strong></p>
            </div>
            <p style="font-size: 24px; font-weight: bolder;">OFFICE OF THE MUNICIPAL MAYOR</p>
            <p style="font-size: 10px; font-weight: bolder;">BUSINESS PERMIT AND LICENSING OFFICE</p>
        </div>
        <div class="inline-block" style="padding: 10px;">
            <img src="../../img/mayors.jfif" style="width: 170px; height: 135px; border-radius: 50%;" />
        </div>
    </div>

    <div class="main-content">
        <p class="text-center" style="font-size: 60px; font-weight: 900; color: #FFA500;">
            BUSINESS PERMIT
        </p>

        <p class="text-right" style="font-family: 'Courier New', Courier; font-size: 15px;">
            <strong>Date Issued:</strong><br>
            <strong><?php echo date('M j, Y', strtotime($permitData['dateRecorded'])); ?></strong>
        </p>

        <p class="text-left" style="font-family: 'Courier New', Courier; font-size: 15px;">
            <strong>Permit No:</strong><br>
            <strong><?php echo $permitData['orNo']; ?></strong>
        </p>

        <p class="text-center" style="font-size: 45px; font-weight: 900; color: #FFA500;">
            <?php echo strtoupper($permitData['businessName']); ?><br>
            <span style="font-size: 20px;">Business Name</span>
        </p>

        <p class="text-center" style="font-size: 20px;">
            <?php echo strtoupper($permitData['businessAddress']); ?><br>
            <span style="font-size: 20px;">Business Address</span>
        </p>

        <p class="text-center" style="font-size: 30px;">
            <?php echo strtoupper($permitData['Name']); ?><br>
            <span style="font-size: 20px;">Owner’s Name</span>
        </p>

        <div class="text-left">
            <span class="label-text">Business ID No:</span><br>
            <span class="data-text"><?php echo $permitData['bussinessidno']; ?></span>
        </div>

        <div class="text-left">
            <span class="label-text">Official Receipt No:</span><br>
            <span class="data-text"><?php echo $permitData['offreceiptno']; ?></span>
        </div>

        <div class="text-left">
            <span class="label-text">OR Date:</span><br>
            <span class="data-text"><?php echo $permitData['ordate']; ?></span>
        </div>

        <div class="text-left">
            <span class="label-text">Type of Business:</span><br>
            <span class="data-text"><?php echo $permitData['typeOfBusiness']; ?></span>
        </div>

        <div class="text-left">
            <span class="label-text">Type of Application:</span><br>
            <span class="data-text"><?php echo $permitData['typeofapplication']; ?></span>
        </div>

        <div class="text-left">
            <span class="label-text">Line of Business:</span><br>
            <span class="data-text"><?php echo $permitData['lineofbussiness']; ?></span>
        </div>

        <div class="text-left">
            <span class="label-text">Payment Mode:</span><br>
            <span class="data-text"><?php echo $permitData['paymentmode']; ?></span>
        </div>

        <div class="text-left">
            <span class="label-text">Amount Paid:</span><br>
            <span class="data-text"><?php echo "PHP: " . number_format($permitData['samount'], 2); ?></span>
        </div>

        <p class="text-left" style="font-size: 17px;">
            <strong>1. This permit must be displayed in a conspicuous place within the establishment...</strong>
        </p>

        <div class="text-center">
            <p>
                <strong>ENGR. <?php echo strtoupper($permitData['completeName']); ?></strong>
                <hr style="border: 0.1px solid black; width: 88%;" />
                <p>Municipal Mayor</p>
            </p>
        </div>

        <p class="text-center">
            <img src="../../img/images.png" alt="Image" style="width: 400px; height: 100px;">
            <span style="font-size: 25px;">VALID UP TO <?php echo date('F j, Y', strtotime($permitData['dateRecorded'] . ' +1 year')); ?></span>
        </p>
    </div>
</body>
</html>