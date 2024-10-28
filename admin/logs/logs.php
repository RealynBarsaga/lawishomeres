<!DOCTYPE html>
<html lang="en">
<?php
    session_start();
    if (!isset($_SESSION['userid'])) {
        header('Location: ../../admin/login.php');
        exit; // Ensure no further execution after redirect
    }
    include('../../admin/head_css.php'); // Removed ob_start() since it's not needed here
?>
    <body class="skin-black">
        <!-- header logo: style can be found in header.less -->
        <?php 
        include "../connection.php";
        include('../header.php'); 
        ?>

        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <?php include('../sidebar-left.php'); ?>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Logs</h1>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <!-- left column -->
                        <div class="box">
                            <div class="box-header">
                                <div style="padding:10px;">
                                    <!-- Optionally, you can add some additional controls here -->
                                </div>
                            </div><!-- /.box-header -->
                            <div class="box-body table-responsive">
                                <form method="post">
                                    <table id="table" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>User</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                // Ensure connection to database is successful
                                                if (!$con) {
                                                    die("Connection failed: " . mysqli_connect_error());
                                                }
                                                
                                                // Fetch logs from the database
                                                $squery = mysqli_query($con, "SELECT * FROM tbllogs ORDER BY logdate DESC");
                                                while ($row = mysqli_fetch_array($squery)) {
                                                    echo '
                                                    <tr>
                                                        <td>' . htmlspecialchars($row['user']) . '</td>
                                                        <td>' . htmlspecialchars($row['logdate']) . '</td>
                                                        <td>' . htmlspecialchars($row['action']) . '</td>
                                                    </tr>
                                                    ';
                                                }
                                                // Close database connection
                                                mysqli_close($con);
                                            ?>
                                        </tbody>
                                    </table>
                                </form>
                            </div><!-- /.box-body -->
                        </div><!-- /.box -->
                    </div> <!-- /.row -->
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->

        <!-- jQuery 2.0.2 -->
        <?php include "../footer.php"; ?>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#table").DataTable({
                    "columnDefs": [ { "sortable": false, "targets": [ 0 ] } ],
                    "order": []
                });
            });
        </script>
    </body>
</html>
