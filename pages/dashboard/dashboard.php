<!DOCTYPE html>
<html lang="en">
<?php
session_start();
// Check if 'userid' is not set (user not logged in)
if (!isset($_SESSION['userid'], $_SESSION['session_token'], $_SESSION['barangay'])) {
    // Redirect the user to the login page if not authenticated
    header('Location: ../../login.php');
    exit(); // Ensure no further execution after redirect
}

$userid = $_SESSION['userid'];
$session_token = $_SESSION['session_token'];
$off_barangay = $_SESSION['barangay'];

// Redirect user to the correct dashboard URL if URL is tampered
$current_url = $_SERVER['REQUEST_URI'];
$correct_url = '../pages/dashboard/dashboard';  // Update with the correct URL for your dashboard

if ($current_url !== $correct_url) {
    header("Location: $correct_url");
    exit();
}

// Validate the session token in the database
$stmt = $pdo->prepare("SELECT session_token, barangay FROM tblstaff WHERE id = ?");
$stmt->execute([$userid]);
$db_token = $stmt->fetchColumn();

if (!$db_token || $db_token['session_token'] !== $session_token || $db_token['barangay'] !== $off_barangay) {
    // Logout the user
    session_destroy();
    header("Location: login.php");
    exit;
}

// If the user is logged in, include the necessary files
include('../head_css.php');
?>
<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
</head>
<body class="skin-black">
    <?php 
      include "../connection.php"; 
      include('../header.php'); 
    ?>
<style>
body {
    overflow: hidden; /* Prevents body from scrolling */
}

.wrapper {
    overflow: hidden; /* Prevents the wrapper from scrolling */
}

.right-side {
    overflow: auto; /* Only this part is scrollable */
    max-height: calc(111vh - 120px); /* You already have this */
}
.info-box {
    display: block;
    min-height: 125px;
    background: #fff;
    width: 92%;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
    border-radius: 2px;
    margin-bottom: 15px;
}
.info-box-text {
    text-transform: none;
    font-weight: 100;
}
/* Container Styles */
.chart-wrapper {
    display: flex;
    justify-content: space-between; /* Aligns children (charts) in a row */
    flex-wrap: wrap; /* Allows wrapping of elements (so Line Chart can go under Bar Chart) */
    gap: 20px; /* Space between charts */
    margin: 20px auto;
}

/* Flex items */
.chart-container {
    max-width: 492px; /* Optional max-width */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    background-color: #fff;
    height: 320px; /* Set a fixed height for the Bar Chart container */
    width: 506px;
    margin-left: 9px;
}

/* Specific style for Pie Chart container */
.chart-containers {
    width: 48%; /* Makes pie chart container take 48% of the available width */
    max-width: 600px;
    padding: 6px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    background-color: #fff;
    height: 319px; /* Set a fixed height for the Pie Chart container */
    margin-right: 5px;
}

/* Specific style for Line Chart container */
.chart-contain {
    max-width: 492px; /* Optional max-width */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    background-color: #fff;
    height: 320px; /* Set a fixed height for the Bar Chart container */
    width: 506px;
    margin-left: 9px;
}

/* Canvas Styles (to ensure charts are responsive inside their containers) */
canvas {
    width: 100% !important; /* Makes canvas responsive to parent container */
    height: 100% !important; /* Ensure the canvas fills the container */
    display: block; /* Removes any extra space below the canvas */
}


/* Specific Styles for Bar Chart */
#myBarChart {
    width: 98% !important; /* Makes bar chart canvas responsive to parent container */
    height: 315px !important; /* Set fixed height for Bar Chart */
}

/* Specific Styles for Pie Chart */
#myPieChart {
    margin-left: 74px;
    width: 69% !important;
    height: 329px !important;
}

/* Specific Styles for Line Chart */
#myLineChart {
    width: 98% !important; /* Makes line chart canvas responsive to parent container */
    height: 315px !important; /* Set fixed height for Line Chart */
}

/* Optional: Style for titles above the charts */
h3 {
    font-size: 1.2rem;
    text-align: center;
    color: #333;
    margin-bottom: 15px;
}
</style>
<div class="row-offcanvas row-offcanvas-left">
<?php include('../sidebar-left.php'); ?>
    <aside class="right-side">
        <section class="content-header">
            <h1>Dashboard</h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="box">
                    <!-- Info Boxes -->
                    <?php
                    $off_barangay = $_SESSION['barangay'];
                    $info_boxes = [
                        ['label' => 'Barangay Officials', 'icon' => 'fa-user', 'color' => '#00c0ef', 'query' => "SELECT * FROM tblbrgyofficial WHERE barangay = '$off_barangay'", 'link' => '../officials/officials'],
                        ['label' => 'Total Household', 'icon' => 'fa-users', 'color' => '#007256', 'query' => "SELECT * FROM tblhousehold h LEFT JOIN tbltabagak r ON r.id = h.headoffamily WHERE r.barangay = '$off_barangay'", 'link' => '../household/household'],
                        ['label' => 'Total Resident', 'icon' => 'fa-users', 'color' => '#bd1e24', 'query' => "SELECT * FROM tbltabagak WHERE barangay = '$off_barangay'", 'link' => '../resident/resident'],
                        ['label' => 'Total Clearance', 'icon' => 'fa-file', 'color' => '#e5c707', 'query' => "SELECT * FROM tblclearance WHERE barangay = '$off_barangay'", 'link' => '../BrgyClearance/BrgyClearance'],
                        ['label' => 'Total Residency', 'icon' => 'fa-file', 'color' => '#f39c12', 'query' => "SELECT * FROM tblrecidency WHERE barangay = '$off_barangay'", 'link' => '../certofresidency/certofres'],
                        ['label' => 'Total Indigency', 'icon' => 'fa-file', 'color' => '#d9534f', 'query' => "SELECT * FROM tblindigency WHERE barangay = '$off_barangay'", 'link' => '../certofindigency/certofindigency'],
                        ['label' => 'Total Brgy Certificate', 'icon' => 'fa-file', 'color' => '#5bc0de', 'query' => "SELECT * FROM tblcertificate WHERE barangay = '$off_barangay'", 'link' => '../brgycertificate/brgycertificate'],
                    ];
                    
                    foreach ($info_boxes as $box) {
                        $q = mysqli_query($con, $box['query']);
                        $num_rows = mysqli_num_rows($q);
                    ?>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <br>
                            <div class="info-box" style="margin-left: 9px; background-color: <?= $box['color'] ?> !important;box-shadow: 2px 5px 9px #888888;">
                                <span style="background: transparent; position: absolute; top: 47%; left: 77%; transform: translate(-50%, -50%); font-size: 40px; color: #eeeeeeba; z-index: 1;">
                                    <i class="fa <?= $box['icon'] ?>"></i>
                                </span>
                                <span class="info-box-number" style="font-size: 30px; color: #fff; margin-left: 15px; font-family: 'Source Sans Pro', sans-serif; font-weight: bold;">
                                    <?= $num_rows ?>
                                    <span class="info-box-text"><?= $box['label'] ?></span>
                                </span>
                                <a href="<?= $box['link'] ?>" style="color: #fff; text-decoration: none; font-weight: 100; font-family: 'Source Sans Pro', sans-serif;">
                                    <div class="info-box-footer" style="margin-top: 35px; text-align: center; background-color: rgba(0, 0, 0, 0.1); padding: 5px; cursor: pointer; z-index: 999; position: relative;">
                                        More Info <i class="fa fa-arrow-circle-right"></i>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php } ?>
                </div><!-- /.box -->
            </div><!-- /.row -->
            <!-- Bar Chart -->
            <div class="chart-wrapper">
                <!-- Bar Chart (on the right) -->
                <div class="chart-container bar-chart">
                    <canvas id="myBarChart"></canvas>  <!-- Removed width/height attributes -->
                </div>
                 <!-- Pie Chart (on the left) -->
                 <div class="chart-containers pie-chart">
                    <canvas id="myPieChart"></canvas>  <!-- Removed width/height attributes -->
                </div>
            </div>
            
            <!-- Line Chart (below the Bar Chart) -->
            <div class="chart-contain line-chart">
                <canvas id="myLineChart"></canvas> <!-- Removed width/height attributes -->
            </div>
        </section><!-- /.content -->
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->
    <?php
    // Query to count data for each barangay
    $barangays = ['Tabagak', 'Bunakan', 'Kodia', 'Talangnan', 'Poblacion', 'Maalat', 'Pili', 'Kaongkod', 'Mancilang', 'Kangwayan', 'Tugas', 'Malbago', 'Tarong', 'San Agustin'];
    $counts = [];

    foreach ($barangays as $barangay) {
        $q = mysqli_query($con, "SELECT * FROM tbltabagak WHERE barangay = '$barangay'");
        $counts[] = mysqli_num_rows($q);
    }
    ?>

    <script>
    const barCtx = document.getElementById('myBarChart').getContext('2d');
    const myBarChart = new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($barangays) ?>,
            datasets: [{
                label: 'Count',
                data: <?= json_encode($counts) ?>,
                backgroundColor: [
                    '#4CB5F5',
                ],
                borderColor: [
                    '#4CB5F5',
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Population Overview',
                    font: {
                        size: 14 // Adjusted font size for the title
                    },
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: {
                            size: 9 // Adjusted font size for the y-axis labels
                        }
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 9 // Adjusted font size for the x-axis labels
                        }
                    }
                }
            },
        }
    });
</script>    
    <?php include "../footer.php"; ?>
</body>
</html>
