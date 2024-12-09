<?php
session_start();

// Check if 'userid' is not set (user not logged in)
if (!isset($_SESSION['userid'])) {
    header('Location: ../../login.php');
    exit();
}

// Check if the user's role is not 'Staff'
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Staff') {
    header('Location: /pages/access-denied');
    exit();
}

// Session timeout logic
if (isset($_SESSION['last_activity'])) {
    $timeout_duration = 10; // Default 15 minutes timeout

    // Check if barangay matches the session barangay
    $off_barangay = $_SESSION['barangay']; // Current user's barangay
    $target_barangay = 'TargetBarangay'; // Replace with the barangay you want to enforce logout for

    // If the barangay matches, enforce timeout logic (logout after inactivity)
    if ($off_barangay === $target_barangay) {
        if ((time() - $_SESSION['last_activity']) > $timeout_duration) {
            session_unset();
            session_destroy();
            header('Location: ../../login.php');
            exit();
        }
    } else {
        // If barangay does not match, keep session active (no logout logic)
        $_SESSION['last_activity'] = time(); // Update last activity timestamp
    }
} else {
    // Set initial activity time if it's the first visit
    $_SESSION['last_activity'] = time();
}

// If the user is logged in and their role is correct, include the necessary files
include('../head_css.php');
?>
<!DOCTYPE html>
<html lang="en">
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
html, body {
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
    height: 273px !important; /* Set fixed height for Line Chart */
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
$off_barangay = $_SESSION['barangay'];

// Purok options for each barangay
$puroks = [
    "Tabagak" => ["Lamon-Lamon", "Tangigue", "Lawihan", "Lower-Bangus", "Upper-Bangus"],
    "Bunakan" => ["Bilabid", "Helinggero", "Kamaisan", "Kalubian", "Samonite"],
    "Maalat" => ["Neem Tree", "Talisay", "Kabakhawan", "Mahogany", "Gmelina"],
    "Pili" => ["Malinawon", "Mahigugmaon", "Matinabangun", "Maabtikon", "Malipayon", "Mauswagon"],
    "Tarong" => ["Orchids", "Gumamela", "Santan", "Rose", "Vietnam Rose", "Kumintang", "Sunflower", "Daisy"],
    // Add other barangays and their corresponding puroks as needed
];

// Get puroks for the current barangay
$current_puroks = isset($puroks[$off_barangay]) ? $puroks[$off_barangay] : [];
$counts = [];

// Query database for each purok in the current barangay
foreach ($current_puroks as $purok) {
    $q = mysqli_query($con, "SELECT * FROM tbltabagak WHERE barangay = '$off_barangay' AND purok = '$purok'");
    $counts[] = mysqli_num_rows($q);
}
?>

<script>
const barCtx = document.getElementById('myBarChart').getContext('2d');
const myBarChart = new Chart(barCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($current_puroks) ?>,
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
                text: 'Purok Overview for Brgy. <?= $off_barangay ?>',
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
<?php
$off_barangay = $_SESSION['barangay']; // Get the logged-in user's barangay from the session

// Initialize variables
$maleCount = 0;
$femaleCount = 0;

// Query to count male residents in the specific barangay
$maleCountQuery = mysqli_query($con, "SELECT COUNT(*) AS male_count FROM tbltabagak WHERE barangay = '$off_barangay' AND gender = 'Male'");
if ($maleCountQuery && mysqli_num_rows($maleCountQuery) > 0) {
    $maleCountResult = mysqli_fetch_assoc($maleCountQuery);
    $maleCount = $maleCountResult['male_count'];
}

// Query to count female residents in the specific barangay
$femaleCountQuery = mysqli_query($con, "SELECT COUNT(*) AS female_count FROM tbltabagak WHERE barangay = '$off_barangay' AND gender = 'Female'");
if ($femaleCountQuery && mysqli_num_rows($femaleCountQuery) > 0) {
    $femaleCountResult = mysqli_fetch_assoc($femaleCountQuery);
    $femaleCount = $femaleCountResult['female_count'];
}

// Calculate total count and percentages
$totalCount = $maleCount + $femaleCount;
$malePercentage = $totalCount > 0 ? ($maleCount / $totalCount) * 100 : 0;
$femalePercentage = $totalCount > 0 ? ($femaleCount / $totalCount) * 100 : 0;
?>
<script>
    const pieCtx = document.getElementById('myPieChart').getContext('2d');
    const myPieChart = new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: ['Male', 'Female'],
            datasets: [{
                label: 'Gender Distribution',
                data: [<?= $maleCount ?>, <?= $femaleCount ?>],
                backgroundColor: ['#4CB5F5', '#FF6384'],
                borderColor: ['#fff', '#fff'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: '          Gender Distribution for Brgy. <?= $off_barangay ?>',
                    font: {
                        size: 16 // Adjusted font size for the title
                    },
                },
                legend: {
                    position: 'left',
                    labels: {
                        boxWidth: 9,  // Reduce the width of the box next to the labels (if you have colored boxes)
                        font: {
                            size: 10 // Smaller font size for legend labels
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            const total = tooltipItem.dataset.data.reduce((a, b) => a + b, 0);
                            const currentValue = tooltipItem.raw;
                            const percentage = ((currentValue / total) * 100).toFixed(1) + '%';
                            return currentValue + ' (' + percentage + ')'; // Show count and percentage in tooltip
                        }
                    },
                    bodyFont: {
                        size: 9 // Smaller font size for tooltip text
                    }
                },
                datalabels: {
                    formatter: (value, ctx) => {
                        const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((value / total) * 100).toFixed(1) + '%'; // Calculate percentage
                        return percentage; // Return the percentage
                    },
                    font: {
                        size: 9 // Smaller font size for data labels
                    },
                    color: '#fff', // Text color
                    anchor: 'center', // Center the labels on the segments
                    align: 'center' // Align the labels to the center
                }
            },
            layout: {
                padding: {
                    right: 45
                }
            }
        },
        plugins: [ChartDataLabels] // Register the plugin
    });
</script>
<?php
$off_barangay = $_SESSION['barangay']; // Get the logged-in user's barangay from the session

// Initialize variables for age distribution
$ageGroups = [
    '0-9' => 0,
    '10-19' => 0,
    '20-29' => 0,
    '30-39' => 0,
    '40-49' => 0,
    '50-59' => 0,
    '60+' => 0,
];

// Query to get age distribution filtered by barangay
$ageQuery = mysqli_query($con, "SELECT age FROM tbltabagak WHERE barangay = '$off_barangay'");
while ($row = mysqli_fetch_assoc($ageQuery)) {
    $age = $row['age'];
    if ($age >= 0 && $age <= 9) {
        $ageGroups['0-9']++;
    } elseif ($age >= 10 && $age <= 19) {
        $ageGroups['10-19']++;
    } elseif ($age >= 20 && $age <= 29) {
        $ageGroups['20-29']++;
    } elseif ($age >= 30 && $age <= 39) {
        $ageGroups['30-39']++;
    } elseif ($age >= 40 && $age <= 49) {
        $ageGroups['40-49']++;
    } elseif ($age >= 50 && $age <= 59) {
        $ageGroups['50-59']++;
    } else {
        $ageGroups['60+']++;
    }
}

$ageLabels = array_keys($ageGroups);
$ageCounts = array_values($ageGroups);
?>
<script>
const lineCtx = document.getElementById('myLineChart').getContext('2d');
const myLineChart = new Chart(lineCtx, {
    type: 'line',
    data: {
        labels: <?= json_encode($ageLabels) ?>,
        datasets: [{
            label: 'Age Distribution',
            data: <?= json_encode($ageCounts) ?>,
            backgroundColor: 'rgba(76, 181, 245, 0.2)', // Semi-transparent fill
            borderColor: '#4CB5F5', // Blue border
            borderWidth: 1,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Age Distribution for Brgy. <?= $off_barangay ?>',
                font: {
                    size: 14 // Adjusted title font size
                },
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1, // Step size for better readability
                    font: {
                        size: 9 // Adjusted font size for y-axis labels
                    }
                }
            },
            x: {
                ticks: {
                    font: {
                        size: 9 // Adjusted font size for x-axis labels
                    }
                }
            }
        }
    }
});
</script>
<?php include "../footer.php"; ?>
</body>
</html>
