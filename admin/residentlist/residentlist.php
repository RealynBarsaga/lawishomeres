<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    session_start();
    if (!isset($_SESSION['userid'])) {
        header('Location: ../../admin/login.php');
        exit; // Ensure no further execution after redirect
    }

    // Check if the user's role is not 'Administrator'
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Administrator') {
        // Redirect to the access denied page if not an admin
        header('Location: ../../admin/access-denied');
        exit(); // Stop further script execution
    } 
    
    include('../../admin/head_css.php');
    include("../connection.php");
    ?>
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
</style>
<body class="skin-black">
    <!-- header logo: style can be found in header.less -->
    <?php include('../header.php'); ?>

    <div class="row-offcanvas row-offcanvas-left">
        <!-- Left side column. contains the logo and sidebar -->
        <?php include('../sidebar-left.php'); ?>

        <!-- Right side column. Contains the navbar and content of the page -->
        <aside class="right-side">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>Total Resident</h1>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <!-- left column -->
                    <div class="box">
                        <div class="box-header">
                            <div style="padding:10px;">
                                <!-- Optional content for the box header -->
                            </div>
                        </div><!-- /.box-header -->
                        <div class="box-body table-responsive">
                            <form method="post">
                                <table id="table" class="table table-bordered table-striped">
                                <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Age</th>
                                            <th>Gender</th>
                                            <th>Barangay</th>
                                            <th>Purok</th>
                                            <th>Role</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Ensure you have the correct database connection
                                        $squery = mysqli_query($con, "SELECT id, 
                                        CONCAT(lname, ', ', fname, ' ', mname, ' ', exname) as 
                                        cname, 
                                        age, 
                                        gender, 
                                        barangay, 
                                        purok, 
                                        role 
                                        FROM tbltabagak ORDER BY lname, fname");
                                        
                                        // Check for query errors
                                        if (!$squery) {
                                            die('MySQL Error: ' . mysqli_error($con));
                                        }
                                        
                                        // Fetch and display rows from the result set
                                        while ($row = mysqli_fetch_array($squery, MYSQLI_ASSOC)) {
                                            echo '
                                            <tr>
                                                <td>'.htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8').'</td>
                                                <td>'.htmlspecialchars($row['cname'], ENT_QUOTES, 'UTF-8').'</td>
                                                <td>'.htmlspecialchars($row['age'], ENT_QUOTES, 'UTF-8').'</td>
                                                <td>'.htmlspecialchars($row['gender'], ENT_QUOTES, 'UTF-8').'</td>
                                                <td>'.htmlspecialchars($row['barangay'], ENT_QUOTES, 'UTF-8').'</td>
                                                <td>'.htmlspecialchars($row['purok'], ENT_QUOTES, 'UTF-8').'</td>
                                                <td>'.htmlspecialchars($row['role'], ENT_QUOTES, 'UTF-8').'</td>
                                            </tr>
                                            ';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </form>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div><!-- /.row -->
            </section><!-- /.content -->
        </aside><!-- /.right-side -->
    </div><!-- ./wrapper -->

    <!-- jQuery 2.0.2 -->
    <?php include("../footer.php"); ?>
    <script type="text/javascript">
        $(function() {
            $("#table").dataTable({
                "aoColumnDefs": [{ "bSortable": false, "aTargets": [0] }],
                "aaSorting": []
            });
        });
    </script>
</body>
</html>
