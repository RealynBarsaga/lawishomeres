<?php
    ob_start(); // Start output buffering at the very top to avoid header errors
    session_start();
    if (!isset($_SESSION['userid'])) {
        header('Location: ../../admin/login.php');
        exit;
    }

    // Check if the user's role is not 'Administrator'
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Administrator') {
        header('Location: ../../admin/access-denied');
        exit();
    }

    include('../../admin/head_css.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <style>
    /* Wrapper for Info Boxes */
    .info-box-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: space-between; /* Adjust spacing between boxes */
        padding: 20px;
    }

    /* Styling for Individual Info Boxes */
    .info-box {
        flex: 1 1 calc(19% - 10px); /* Five boxes in a row */
        min-width: 200px; /* Minimum width to ensure proper layout */
        max-width: 240px; /* Prevent boxes from stretching too wide */
        background-color: #fff;
        box-shadow: 2px 5px 9px #888888;
        border-radius: 8px; /* Rounded corners */
        position: relative;
        overflow: hidden;
    }
    .info-box-number {
        font-size: 30px;
        color: #fff;
        margin-left: 15px;
        font-family: 'Source Sans Pro', sans-serif;
        font-weight: bold;
    }
    .info-box-text {
        font-size: 16px;
        color: #fff;
    }
    .info-box-footer {
        margin-top: 35px;
        text-align: center;
        background-color: rgba(0, 0, 0, 0.1);
        padding: 5px;
        cursor: pointer;
        z-index: 1;
        position: relative;
    }
    .info-box i {
        position: absolute;
        top: 47%;
        left: 77%;
        transform: translate(-50%, -50%);
        font-size: 40px;
        color: #eeeeeeba;
        z-index: 0;
    }

    /* Custom Styles for Charts */
    .chart-wrapper {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
        margin: 20px auto;
    }
    .chart-container, .chart-containers, .chart-contain {
        max-width: 492px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: #fff;
        height: 320px;
    }
    canvas {
        width: 100% !important;
        height: 100% !important;
        display: block;
    }

    /* Optional: Style for titles above the charts */
    h3 {
        font-size: 1.2rem;
        text-align: center;
        color: #333;
        margin-bottom: 15px;
    }
    </style>
</head>
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
                <!-- Info Boxes -->
                <div class="info-box-wrapper">
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
                    <div class="info-box" style="background-color: <?= $box['color'] ?> !important;">
                        <i class="fa <?= $box['icon'] ?>"></i>
                        <div>
                            <span class="info-box-number">
                                <?= $num_rows ?>
                                <span class="info-box-text"><?= $box['label'] ?></span>
                            </span>
                        </div>
                        <a href="<?= $box['link'] ?>" style="color: #fff; text-decoration: none;">
                            <div class="info-box-footer">
                                More Info <i class="fa fa-arrow-circle-right"></i>
                            </div>
                        </a>
                    </div>
                    <?php } ?>
                </div>

                <!-- Charts -->
                <div class="chart-wrapper">
                    <div class="chart-container">
                        <canvas id="myBarChart"></canvas>
                    </div>

                    <div class="chart-containers">
                        <canvas id="myPieChart"></canvas>
                    </div>
                </div>

                <div class="chart-contain">
                    <canvas id="myLineChart"></canvas>
                </div>
            </section>
        </aside>
    </div>

    <?php
    // Data initialization for male/female count
    $maleCount = 0;
    $femaleCount = 0;

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
                    text: 'Gender Distribution Overview',
                    font: {
                        size: 16
                    },
                },
                legend: {
                    position: 'left',
                    labels: {
                        boxWidth: 9,
                        font: {
                            size: 10
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            const total = tooltipItem.dataset.data.reduce((a, b) => a + b, 0);
                            const currentValue = tooltipItem.raw;
                            const percentage = ((currentValue / total) * 100).toFixed(1) + '%';
                            return currentValue + ' (' + percentage + ')';
                        }
                    },
                    bodyFont: {
                        size: 9
                    }
                },
                datalabels: {
                    formatter: (value, ctx) => {
                        const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((value / total) * 100).toFixed(1) + '%';
                        return percentage;
                    },
                    font: {
                        size: 9
                    },
                    color: '#fff',
                    anchor: 'center',
                    align: 'center'
                }
            },
            layout: {
                padding: {
                    right: 45
                }
            }
        },
        plugins: [ChartDataLabels]
    });

    const barCtx = document.getElementById('myBarChart').getContext('2d');
    const myBarChart = new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($barangays) ?>,
            datasets: [{
                label: 'Count',
                data: <?= json_encode($counts) ?>,
                backgroundColor: ['#4CB5F5'],
                borderColor: ['#4CB5F5'],
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
                        size: 14
                    },
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: {
                            size: 9
                        }
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 9
                        }
                    }
                }
            },
        }
    });

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
                        size: 14
                    },
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: {
                            size: 9
                        }
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 9
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