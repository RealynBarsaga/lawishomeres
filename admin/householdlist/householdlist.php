<!DOCTYPE html>
<html lang="en">
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
                    <h1>
                        Total Household
                    </h1>
                    
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <!-- left column -->
                            <div class="box">
                                <div class="box-header">
                                    <div style="padding:10px;">
                                        
                                    </div>                                
                                </div><!-- /.box-header -->
                                <div class="box-body table-responsive">
                                <form method="post">
                                    <table id="table" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Household #</th>
                                                <th>Head Of Family</th>
                                                <th>Total Members</th>
                                                <th>Family Members</th>
                                                <th>Barangay</th>
                                                <th>option</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                             $squery = mysqli_query($con, "
                                             SELECT 
                                                 h.*, 
                                                 h.id as id, 
                                                 CONCAT(r.lname, ', ', r.fname, ' ', r.mname) as name, 
                                                 b.barangay,
                                                 (
                                                     SELECT GROUP_CONCAT(CONCAT(lname, ', ', fname, ' ', mname) SEPARATOR '\n') AS names
                                                     FROM tbltabagak 
                                                     WHERE householdnum = h.householdno AND role = 'Members'
                                                 ) as membersname
                                             FROM tblhousehold h 
                                             LEFT JOIN tbltabagak r ON r.id = h.headoffamily 
                                             LEFT JOIN tbltabagak b ON r.id = b.id
                                         ");
                                         
                                              if (!$squery) {
                                                  die('MySQL Error: ' . mysqli_error($con));
                                              }
                                              while ($row = mysqli_fetch_array($squery)) {
                                                  // Format the membersname field
                                                  $membersName = !empty($row['membersname']) ? nl2br(htmlspecialchars($row['membersname'], ENT_QUOTES, 'UTF-8')) : "No family members available";
                                                  
                                                  echo '
                                                  <tr>
                                                      <td>'.$row['id'].'</td>
                                                      <td>'.$row['householdno'].'</td>
                                                      <td>'.$row['name'].'</td>
                                                      <td>'.$row['totalhouseholdmembers'].'</td>
                                                      <td>' . $membersName . '</td>
                                                      <td>'.$row['barangay'].'</td>
                                                      <td>
                                                            <button class="btn btn-danger btn-sm" data-target="#deleteModals' . htmlspecialchars($row['id']) . '" data-toggle="modal" style="margin-left: 1px;color: #fff;background-color: #dc3545;border-color: #dc3545;">
                                                                <i class="fa fa-trash" aria-hidden="true"></i> Delete
                                                            </button>
                                                      </td>
                                                  </tr>';
                                                  include "delete_modal.php";
                                              }
                                            ?>
                                        </tbody>
                                    </table>
                                    </form>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                            <?php include "function.php"; ?>
                    </div>   <!-- /.row -->
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->
        <!-- jQuery 2.0.2 -->
        <?php 
        include "../footer.php"; ?>
<script type="text/javascript">
        $(function() {
            $("#table").dataTable({
               "aoColumnDefs": [ { "bSortable": false, "aTargets": [ 0 ] } ],"aaSorting": []
            });
        });
    
</script>
    </body>
</html>