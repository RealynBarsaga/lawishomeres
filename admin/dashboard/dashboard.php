<!DOCTYPE html>
<html lang="en">
<?php
    session_start();
    if(!isset($_SESSION['role']))
    {
        header("Location: ../../admin/login.php"); 
    }
    else
    {
    ob_start();
    include('../../admin/head_css.php');
?>
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
        display: flex;
        align-items: center;
        background: #fff;
        box-sizing: border-box;
        box-shadow: 2px 5px 9px #888888;
    }
    .chart-container {
        margin-left: 22px;
    }
    .chart-containers, .chart-contain {
        width: 28%;
        height: 300px;
        margin-left: 400px; /* Adjust if necessary */
    }
    .canvas {
        display: block;
        box-sizing: border-box;
        height: 307px;
        width: 380px;
    }
</style>
    
<body class="skin-black">
<!-- header logo: style can be found in header.less -->
<?php  
include "../../admin/connection.php";
?>
<?php include('../../admin/header.php'); ?>
<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Left side column. contains the logo and sidebar -->
    <?php include('../../admin/sidebar-left.php'); ?>
    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Dashboard
            </h1>                    
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <!-- left column -->
                    <div class="box">
                        
                        <div class="col-md-3 col-sm-6 col-xs-12"><br>
                          <div class="info-box">
                            <a href="../household/household.php"><span class="info-box-icon bg-aqua"><i class="fa fa-home"></i></span></a>
                            <div class="info-box-content">
                              <span class="info-box-text">Total Household</span>
                              <span class="info-box-number">
                                <?php
                                    $q = mysqli_query($con,"SELECT * from tblhousehold");
                                    $num_rows = mysqli_num_rows($q);
                                    echo $num_rows;
                                ?>
                              </span>
                            </div>
                            <!-- /.info-box-content -->
                          </div>
                          <!-- /.info-box -->
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12"><br>
                          <div class="info-box">
                            <a href="../resident/resident.php"><span class="info-box-icon bg-aqua"><i class="fa fa-users"></i></span></a>
                            <div class="info-box-content">
                              <span class="info-box-text">Total Resident</span>
                              <span class="info-box-number">
                                <?php
                                    $q = mysqli_query($con,"SELECT * from tblresident");
                                    $num_rows = mysqli_num_rows($q);
                                    echo $num_rows;
                                ?>
                              </span>
                            </div>
                            <!-- /.info-box-content -->
                          </div>
                          <!-- /.info-box -->
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12"><br>
                          <div class="info-box">
                            <a href="../clearance/clearance.php"><span class="info-box-icon bg-aqua"><i class="fa fa-file"></i></span></a>
                            <div class="info-box-content">
                              <span class="info-box-text">Total Clearance</span>
                              <span class="info-box-number">
                                <?php
                                    $q = mysqli_query($con,"SELECT * from tblclearance where status = 'Approved' ");
                                    $num_rows = mysqli_num_rows($q);
                                    echo $num_rows;
                                ?>
                              </span>
                            </div>
                            <!-- /.info-box-content -->
                          </div>
                          <!-- /.info-box -->
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12"><br>
                          <div class="info-box">
                            <a href="../permit/permit.php"><span class="info-box-icon bg-aqua"><i class="fa fa-file"></i></span></a>
                            <div class="info-box-content">
                              <span class="info-box-text">Total Permit</span>
                              <span class="info-box-number">
                                <?php
                                    $q = mysqli_query($con,"SELECT * from tblpermit where status = 'Approved' ");
                                    $num_rows = mysqli_num_rows($q);
                                    echo $num_rows;
                                ?>
                              </span>
                            </div>
                            <!-- /.info-box-content -->
                          </div>
                          <!-- /.info-box -->
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12"><br>
                          <div class="info-box">
                            <a href="../blotter/blotter.php"><span class="info-box-icon bg-aqua"><i class="fa fa-user"></i></span></a>
                            <div class="info-box-content">
                              <span class="info-box-text">Total Blotter</span>
                              <span class="info-box-number">
                                <?php
                                    $q = mysqli_query($con,"SELECT * from tblblotter");
                                    $num_rows = mysqli_num_rows($q);
                                    echo $num_rows;
                                ?>
                              </span>
                            </div>
                            <!-- /.info-box-content -->
                          </div>
                          <!-- /.info-box -->
                        </div>
                    </div><!-- /.box -->
            </div>   <!-- /.row -->
        </section><!-- /.content -->
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->
<!-- jQuery 2.0.2 -->
<?php }
include "../../admin/footer.php"; ?>
<script type="text/javascript">
    $(function() {
        $("#table").dataTable({
           "aoColumnDefs": [ { "bSortable": false, "aTargets": [ 0,5 ] } ],"aaSorting": []
        });
    });
</script>
    </body>
</html>