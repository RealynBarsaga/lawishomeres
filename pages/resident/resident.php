<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header('Location: ../../login.php');
    exit; // Ensure no further execution after redirect
}

include('../head_css.php'); 
?>

<style>
    .input-size {
        width: 418px;
    }
</style>

<body class="skin-black">
    <?php 
    include "../connection.php"; 
    include('../header.php'); 
    ?>

    <div class="wrapper row-offcanvas row-offcanvas-left">
        <?php include('../sidebar-left.php'); ?>

        <aside class="right-side">
            <section class="content-header">
                <h1>Resident</h1>
            </section>
            <section class="content">
                <div class="row">
                    <div class="box">
                        <div class="box-header">
                            <div style="padding: 10px;">
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addCourseModal">
                                    <i class="fa fa-user-plus" aria-hidden="true"></i> Add Residents
                                </button>
                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i> Delete
                                </button>
                            </div>
                        </div>
                        <div class="box-body table-responsive">
                            <form method="post" enctype="multipart/form-data">
                                <table id="table" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width: 100px; text-align: left;">
                                                <label>
                                                    <input type="checkbox" class="cbxMain" onchange="checkMain(this)" style="vertical-align: middle;" />
                                                    <span style="vertical-align: -webkit-baseline-middle; margin-left: 5px; font-size: 13px;">Select All</span>
                                                </label>
                                            </th>
                                            <th style="width: 15.6667px;">Image</th>
                                            <th>Resident Name</th>
                                            <th>Age</th>
                                            <th>Gender</th>
                                            <th>Former Address</th>
                                            <th>Purok</th>
                                            <th style="width: 40px !important;">Option</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    
                                    $squery = mysqli_query($con, 
                                    "SELECT id, 
                                    CONCAT(lname, ', ', fname, ' ', mname) as 
                                    cname, 
                                    age, 
                                    gender, 
                                    formerAddress, 
                                    purok, 
                                    image FROM tbltabagak WHERE barangay = '$off_barangay' ORDER BY lname, fname");
                                    while ($row = mysqli_fetch_array($squery)) {
                                        echo '
                                        <tr>
                                            <td><input type="checkbox" name="chk_delete[]" class="chk_delete" value="'.htmlspecialchars($row['id']).'" /></td>
                                            <td><img src="image/' . basename($row['image']) . '" style="width:60px;height:60px;"/></td>
                                            <td>'. htmlspecialchars($row['cname']) .'</td>
                                            <td>'. htmlspecialchars($row['age']) .'</td>
                                            <td>'. htmlspecialchars($row['gender']) .'</td>
                                            <td>'. htmlspecialchars($row['formerAddress']) .'</td>
                                            <td>'. htmlspecialchars($row['purok']) .'</td>
                                            <td>
                                                <button class="btn btn-primary btn-sm" data-target="#editModal'.htmlspecialchars($row['id']).'" data-toggle="modal">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit
                                                </button>
                                            </td>
                                        </tr>';
                                        include "edit_modal.php";
                                    } 
                                    ?>
                                    </tbody>
                                </table>
                                <?php include "../deleteModal.php"; ?>
                            </form>
                        </div>
                    </div>
                    <?php 
                    include "../edit_notif.php"; 
                    include "../duplicate_error.php"; 
                    include "../added_notif.php"; 
                    include "../delete_notif.php"; 
                    include "add_modal.php"; 
                    include "function.php"; 
                    ?>
                </div>
            </section>
        </aside>
    </div>

    <?php include "../footer.php"; ?>

    <script type="text/javascript">
    $(function() {
        $("#table").dataTable({
            "aoColumnDefs": [{
                "bSortable": false,
                "aTargets": [0, 6]
            }],
            "aaSorting": []
        });
    });
    </script>
</body>
</html>