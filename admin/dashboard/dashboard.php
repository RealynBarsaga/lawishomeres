<!DOCTYPE html>
<html lang="en">
<?php
    ob_start(); // Start output buffering at the very top to avoid header errors
    // Check if the user is logged in and is an admin
    session_start();
    if (!isset($_SESSION['userid'])) {
        header('Location: ../../admin/login.php');
        exit; // Ensure no further execution after redirect
    }
    include('../../admin/head_css.php'); 
?>
<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
</head>
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
<body class="skin-black">
    <?php
    include "../../admin/connection.php";
    include('../../admin/header.php');
    ?>
    <div class="row-offcanvas row-offcanvas-left">
        <?php include('../../admin/sidebar-left.php'); ?>

        <aside class="right-side">
            <section class="content-header">
                <h1>Dashboard</h1>
            </section>
        
            <section class="content">
                <div class="row">
                    <div class="box">
                        <!-- Info Boxes -->
                        <?php
                        $info_boxes = [
                            ['label' => 'Madridejos Officials', 'icon' => 'fa-user', 'color' => '#00c0ef', 'query' => "SELECT * FROM tblmadofficial", 'link' => '../officials/officials'],
                            ['label' => 'Total Barangay', 'icon' => 'fa-university', 'color' => '#007256', 'query' => "SELECT * FROM tblstaff", 'link' => '../staff/staff'],
                            ['label' => 'Total Household', 'icon' => 'fa-users', 'color' => '#bd1e24', 'query' => "SELECT * FROM tblhousehold", 'link' => '../householdlist/householdlist'],
                            ['label' => 'Total Resident', 'icon' => 'fa-users', 'color' => '#e5c707', 'query' => "SELECT * FROM tbltabagak", 'link' => '../residentlist/residentlist'],
                            // New Info Box for Total Permit
                            ['label' => 'Total Permit', 'icon' => 'fa-file', 'color' => '#f39c12', 'query' => "SELECT * FROM tblpermit", 'link' => '../permit/permit'],
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
// Initialize variables
$maleCount = 0;
$femaleCount = 0;

// Query to count male and female residents
$maleCountQuery = mysqli_query($con, "SELECT COUNT(*) AS male_count FROM tbltabagak WHERE gender = 'Male'");
if ($maleCountQuery) {
    $maleCountResult = mysqli_fetch_assoc($maleCountQuery);
    $maleCount = $maleCountResult['male_count'];
}

$femaleCountQuery = mysqli_query($con, "SELECT COUNT(*) AS female_count FROM tbltabagak WHERE gender = 'Female'");
if ($femaleCountQuery) {
    $femaleCountResult = mysqli_fetch_assoc($femaleCountQuery);
    $femaleCount = $femaleCountResult['female_count'];
}

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
                    text: '          Gender Distribution Overview',
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
<?php
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

// Query to get age distribution
$ageQuery = mysqli_query($con, "SELECT age FROM tbltabagak");
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
                backgroundColor: 'rgba(76, 181, 245, 0.2)',
                borderColor: '#4CB5F5',
                borderWidth: 1,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Age Distribution Overview',
                    font: {
                        size: 14 // Reduced title font size
                    },
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: {
                            size: 9 // Reduced font size for the y-axis labels
                        }
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 9 // Reduced font size for the x-axis labels
                        }
                    }
                }
            }
        }
    });
</script>
<?php include "../../admin/footer.php"; ?>
</body>
</html>