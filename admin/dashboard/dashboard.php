<!DOCTYPE html>
<html lang="en">
<?php
    session_start();
    if (!isset($_SESSION['userid'])) {
        header('Location: ../admin/login.php');
        exit; // Ensure no further execution after redirect
    }
    include('../../admin/head_css.php'); 
?>
<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<style>
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
.chart-container {
    margin-left: 400px;
    margin-top: 100px; /* Adjust as needed */
}
.chart-containers {
    margin-left: 403px;
    margin-top: -322px;
    display: flex;
    align-items: center;
    width: 28%;
    height: 300px;
    background: rgb(255, 255, 255);
    box-sizing: border-box;
}
.canvas#myPieChart {
    display: block;
    box-sizing: border-box;
    height: 307px;
    width: 380px;
}
.chart-contain {
    margin-left: 715px;
    margin-top: -322px;
    display: flex;
    align-items: center;
    width: 28%;
    height: 300px;
    background: rgb(255, 255, 255);
    box-sizing: border-box;
}
.canvas#PieChart {
    display: block;
    box-sizing: border-box;
    height: 307px;
    width: 380px;
}
</style>
<body class="skin-black">
    <?php
    include "../../admin/connection.php";
    include('../../admin/header.php');
    ?>
    <div class="wrapper row-offcanvas row-offcanvas-left">
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
                            ['label' => 'Madridejos Officials', 'icon' => 'fa-user', 'color' => '#00c0ef', 'query' => "SELECT * FROM tblMadofficial", 'link' => '../officials/officials.php'],
                            ['label' => 'Total Barangay', 'icon' => 'fa-university', 'color' => '#007256', 'query' => "SELECT * FROM tblstaff", 'link' => '../staff/staff.php'],
                            ['label' => 'Total Permit', 'icon' => 'fa-file', 'color' => '#bd1e24', 'query' => "SELECT * FROM tblpermit", 'link' => '../permit/permit.php'],
                            ['label' => 'Total Household', 'icon' => 'fa-users', 'color' => '#e5c707', 'query' => "SELECT * FROM tblhousehold", 'link' => '../householdlist/householdlist.php']
                        ];

                        foreach ($info_boxes as $box) {
                            $q = mysqli_query($con, $box['query']);
                            $num_rows = mysqli_num_rows($q);
                        ?>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <br>
                            <div class="info-box" style="margin-left: 9px; background-color: <?= $box['color'] ?> !important;">
                                <span style="background: transparent; position: absolute; top: 47%; left: 77%; transform: translate(-50%, -50%); font-size: 40px; color: #eeeeeeba; z-index: 1;">
                                    <i class="fa <?= $box['icon'] ?>"></i>
                                </span>
                                <span class="info-box-number" style="font-size: 30px; color: #fff; margin-left: 15px; font-family: 'Source Sans Pro', sans-serif; font-weight: bold;">
                                    <?= $num_rows ?>
                                    <span class="info-box-text"><?= $box['label'] ?></span>
                                </span>
                                <div class="info-box-footer" style="margin-top: 35px; text-align: center; background-color: rgba(0, 0, 0, 0.1); padding: 5px; cursor: pointer; z-index: 999; position: relative;">
                                    <a href="<?= $box['link'] ?>" style="color: #fff; text-decoration: none; font-weight: 100; font-family: 'Source Sans Pro', sans-serif;">
                                        More Info <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>

                            </div>
                        </div>
                        <?php } ?>
                    </div><!-- /.box -->
                    <!-- Bar Chart -->
                    <div class="chart-container" style="margin-left: 22px;">
                        <canvas id="myBarChart" width="100" height="30" style="max-width: 35%;background: #fff; box-shadow: 2px 5px 9px #888888;"></canvas>
                    </div>

                    <!-- Pie Chart -->
                    <div class="chart-containers" style="box-shadow: 2px 5px 9px #888888;">
                        <canvas id="myPieChart"></canvas>
                    </div>

                    <!-- Pie Chart -->
                    <div class="chart-contain" style="box-shadow: 2px 5px 9px #888888;">
                        <canvas id="PieChart"></canvas>
                    </div>
                </div><!-- /.row -->
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
                        text: 'Household Overview',
                        font: {
                            size: 18
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
</script>
<?php
// Count males and females per barangay
$barangays = ['Tabagak', 'Bunakan', 'Kodia', 'Talangnan', 'Poblacion', 'Maalat', 'Pili', 'Kaongkod', 'Mancilang', 'Kangwayan', 'Tugas', 'Malbago', 'Tarong', 'San Agustin'];
$maleCounts = [];
$femaleCounts = [];

// Assuming you also want to count females
foreach ($barangays as $barangay) {
    // Count males
    $q_male = mysqli_query($con, "SELECT * FROM tbltabagak WHERE barangay = '$barangay' AND gender = 'Male'");
    $maleCounts[] = mysqli_num_rows($q_male);

    // Count females
    $q_female = mysqli_query($con, "SELECT * FROM tbltabagak WHERE barangay = '$barangay' AND gender = 'Female'");
    $femaleCounts[] = mysqli_num_rows($q_female);
}
?>

<script>
// Pie chart for Male Distribution
const pieCtxMale = document.getElementById('myPieChart').getContext('2d');
const myPieChart = new Chart(pieCtxMale, {
    type: 'pie',
    data: {
        labels: <?= json_encode($barangays) ?>,
        datasets: [
            {
                label: 'Male',
                data: <?= json_encode($maleCounts) ?>, // Male counts
                backgroundColor: '#36A2EB',
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Male Distribution by Barangay',
                font: {
                    size: 17
                },
                padding: {
                   top: 2 // Adjust top padding as needed
                }
            },
            legend: {
                position: 'left',
                labels: {
                    boxWidth: 15,
                    usePointStyle: true,
                    padding: 6,
                    font: {
                        size: 10
                    }
                }
            }
        },
        onClick: (evt) => {
            const activePoints = myPieChart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, false);
            if (activePoints.length) {
                const chartIndex = activePoints[0].index;
                const barangay = <?= json_encode($barangays) ?>[chartIndex];
                const maleCount = <?= json_encode($maleCounts) ?>[chartIndex];
                // Optionally, handle click event here (e.g., display alert)
            }
        }
    }
});

// Pie chart for Female Distribution
const pieCtxFemale = document.getElementById('PieChart').getContext('2d');
const PieChart = new Chart(pieCtxFemale, {
    type: 'pie',
    data: {
        labels: <?= json_encode($barangays) ?>,
        datasets: [
            {
                label: 'Female',
                data: <?= json_encode($femaleCounts) ?>, // Female counts
                backgroundColor: '#FF6384',
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Female Distribution by Barangay',
                font: {
                    size: 17
                },
                padding: {
                   top: 2 // Adjust top padding as needed
                }
            },
            legend: {
                position: 'left',
                labels: {
                    boxWidth: 15,
                    usePointStyle: true,
                    padding: 6,
                    font: {
                        size: 10
                    }
                }
            }
        },
        onClick: (evt) => {
            const activePoints = PieChart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, false);
            if (activePoints.length) {
                const chartIndex = activePoints[0].index;
                const barangay = <?= json_encode($barangays) ?>[chartIndex];
                const femaleCount = <?= json_encode($femaleCounts) ?>[chartIndex];
                // Optionally, handle click event here (e.g., display alert)
            }
        }
    }
});
</script>
<?php include "../../admin/footer.php"; ?>
</body>
</html>