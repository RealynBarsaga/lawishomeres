<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header('Location: ../../admin/login.php');
    exit; // Ensure no further execution after redirect
}
include('../../admin/head_css.php');
?>
<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        .chart-container, .chart-containers, .chart-contain {
            margin: 100px 0 0 22px;
            display: flex;
            align-items: center;
            width: 28%;
            height: 300px;
            background: rgb(255, 255, 255);
            box-sizing: border-box;
            box-shadow: 2px 5px 9px #888888;
        }
        .canvas {
            display: block;
            box-sizing: border-box;
            height: 307px;
            width: 380px;
        }
    </style>
</head>
<body class="skin-black">
        <!-- header logo: style can be found in header.less -->
        <?php  
        include "../../admin/connection.php";
        ?>
        <?php include('../../admin/header.php'); ?>
<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Left side column. contains the logo and sidebar -->
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
                        <div class="info-box" style="background-color: <?= $box['color'] ?>;">
                            <span style="background: transparent; position: absolute; top: 47%; left: 77%; transform: translate(-50%, -50%); font-size: 40px; color: #eeeeeeba; z-index: 1;">
                                <i class="fa <?= $box['icon'] ?>"></i>
                            </span>
                            <span class="info-box-number" style="font-size: 30px; color: #fff; margin-left: 15px; font-family: 'Source Sans Pro', sans-serif; font-weight: bold;">
                                <?= $num_rows ?>
                                <span class="info-box-text"><?= $box['label'] ?></span>
                            </span>
                            <div class="info-box-footer" style="margin-top: 35px; text-align: center; background-color: rgba(0, 0, 0, 0.1); padding: 5px; cursor: pointer;">
                                <a href="<?= $box['link'] ?>" style="color: #fff; text-decoration: none; font-weight: 100; font-family: 'Source Sans Pro', sans-serif;">
                                    More Info <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div><!-- /.box -->

                <!-- Bar Chart -->
                <div class="chart-container">
                    <canvas id="myBarChart"></canvas>
                </div>

                <!-- Pie Charts -->
                <div class="chart-containers">
                    <canvas id="myPieChart"></canvas>
                </div>
                <div class="chart-contain">
                    <canvas id="PieChart"></canvas>
                </div>

                <?php
                // Query to count data for each barangay
                $barangays = ['Tabagak', 'Bunakan', 'Kodia', 'Talangnan', 'Poblacion', 'Maalat', 'Pili', 'Kaongkod', 'Mancilang', 'Kangwayan', 'Tugas', 'Malbago', 'Tarong', 'San Agustin'];
                $counts = [];
                $maleCounts = [];
                $femaleCounts = [];                    

                foreach ($barangays as $barangay) {
                    $q = mysqli_query($con, "SELECT * FROM tbltabagak WHERE barangay = '$barangay'");
                    $counts[] = mysqli_num_rows($q);

                    // Count males and females
                    $q_male = mysqli_query($con, "SELECT * FROM tbltabagak WHERE barangay = '$barangay' AND gender = 'Male'");
                    $maleCounts[] = mysqli_num_rows($q_male);                    

                    $q_female = mysqli_query($con, "SELECT * FROM tbltabagak WHERE barangay = '$barangay' AND gender = 'Female'");
                    $femaleCounts[] = mysqli_num_rows($q_female);
                }
                ?>

                <script>
                    const barCtx = document.getElementById('myBarChart').getContext('2d');
                    new Chart(barCtx, {
                        type: 'bar',
                        data: {
                            labels: <?= json_encode($barangays) ?>,
                            datasets: [{
                                label: 'Count',
                                data: <?= json_encode($counts) ?>,
                                backgroundColor: '#4CB5F5',
                                borderColor: '#4CB5F5',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Household Overview',
                                    font: { size: 18 }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: { stepSize: 1 }
                                }
                            }
                        }
                    });

                    // Male Distribution Pie Chart
                    const pieCtxMale = document.getElementById('myPieChart').getContext('2d');
                    new Chart(pieCtxMale, {
                        type: 'pie',
                        data: {
                            labels: <?= json_encode($barangays) ?>,
                            datasets: [{
                                label: 'Male',
                                data: <?= json_encode($maleCounts) ?>,
                                backgroundColor: '#36A2EB',
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Male Distribution by Barangay',
                                    font: { size: 17 },
                                    padding: { top: 2 }
                                },
                                legend: {
                                    position: 'left',
                                    labels: {
                                        boxWidth: 15,
                                        usePointStyle: true,
                                        padding: 6,
                                        font: { size: 10 }
                                    }
                                }
                            }
                        }
                    });

                    // Female Distribution Pie Chart
                    const pieCtxFemale = document.getElementById('PieChart').getContext('2d');
                    new Chart(pieCtxFemale, {
                        type: 'pie',
                        data: {
                            labels: <?= json_encode($barangays) ?>,
                            datasets: [{
                                label: 'Female',
                                data: <?= json_encode($femaleCounts) ?>,
                                backgroundColor: '#FF6384',
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Female Distribution by Barangay',
                                    font: { size: 17 },
                                    padding: { top: 2 }
                                },
                                legend: {
                                    position: 'left',
                                    labels: {
                                        boxWidth: 15,
                                        usePointStyle: true,
                                        padding: 6,
                                        font: { size: 10 }
                                    }
                                }
                            }
                        }
                    });
                </script>
            </div><!-- /.row -->
        </section><!-- /.content -->
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->
<?php include "../../admin/footer.php"; ?>
<script type="text/javascript">
    $(function() {
        $("#table").dataTable({
           "aoColumnDefs": [ { "bSortable": false, "aTargets": [ 0,5 ] } ],"aaSorting": []
        });
    });
</script>
</body>
</html>