<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    session_start();
    if (!isset($_SESSION['userid'])) {
        header('Location: ../../admin/login.php');
        exit; // Ensure no further execution after redirect
    }
    include('../../admin/head_css.php'); // Removed ob_start() since it's not needed here
    ?>
    <style>
        .nav-tabs li a {
            cursor: pointer;
        }
    </style>
</head>
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
                <h1>Business Permit</h1>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <!-- left column -->
                    <div class="box">
                        <div class="box-header">
                            <div style="padding:10px;">
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal">
                                    <i class="fa fa-user-plus" aria-hidden="true"></i> Add Permit
                                </button>
                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i> Delete
                                </button>
                            </div>
                        </div><!-- /.box-header -->
                        <div class="box-body table-responsive">
                            <form method="post">
                                <div class="tab-content">
                                    <div id="approved" class="tab-pane active in">
                                        <table id="table" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th style="width: 100px; text-align: left;">
                                                        <label>
                                                            <input type="checkbox" class="cbxMain" onchange="checkMain(this)" style="vertical-align: middle;" />
                                                            <span style="vertical-align: -webkit-baseline-middle; margin-left: 5px; font-size: 13px;">Select All</span>
                                                        </label>
                                                    </th>
                                                    <th>Name</th>
                                                    <th>Business Name</th>
                                                    <th>Business Address</th>
                                                    <th>Type of Business</th>
                                                    <th>OR Number</th>
                                                    <th>Amount</th>
                                                    <th style="width: 143.6667px;">Option</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Execute the query to select data from the tblpermit table
                                                $squery = mysqli_query($con, " 
                                                    SELECT
                                                        p.name, 
                                                        p.businessName, 
                                                        p.businessAddress, 
                                                        p.typeOfBusiness, 
                                                        p.orNo, 
                                                        p.samount,
                                                        p.id
                                                    FROM tblpermit AS p");

                                                // Loop through the results and generate table rows
                                                while ($row = mysqli_fetch_array($squery)) {
                                                    echo '
                                                    <tr>
                                                        <td><input type="checkbox" name="chk_delete[]" class="chk_delete" value="' . $row['id'] . '" /></td>
                                                        <td>' . $row['name'] . '</td>
                                                        <td>' . $row['businessName'] . '</td>
                                                        <td>' . $row['businessAddress'] . '</td>
                                                        <td>' . $row['typeOfBusiness'] . '</td>
                                                        <td>' . $row['orNo'] . '</td>
                                                        <td>₱ ' . number_format($row['samount'], 2) . '</td>
                                                        <td>
                                                            <button class="btn btn-primary btn-sm" data-target="#editModal' . $row['id'] . '" data-toggle="modal">
                                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit
                                                            </button>
                                                            <a style="width: 80px;" href="permit_form.php?" class="btn btn-primary btn-sm">
                                                                <i class="fa fa-print" aria-hidden="true"></i> Print
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    '; /* &resident=' . urlencode($row['name']) . '&purpose=' . urlencode($row['purpose']) . '&clearance=' . urlencode($row['clearanceNo']) . '&val=' . urlencode(base64_encode($row['clearanceNo'] . '|' . $row['name'])) . ' */
                                                    include "edit_modal.php";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php include "../deleteModal.php"; ?>
                            </form>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                    <?php include "../edit_notif.php"; ?>
                    <?php include "../added_notif.php"; ?>
                    <?php include "../delete_notif.php"; ?>
                    <?php include "../duplicate_error.php"; ?>
                    <?php include "add_modal.php"; ?>
                    <?php include "function.php"; ?>
                </div><!-- /.row -->
            </section><!-- /.content -->
        </aside><!-- /.right-side -->
    </div><!-- ./wrapper -->
    <?php include "../footer.php"; ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#table").DataTable({
                "columnDefs": [
                    { "sortable": false, "targets": [0, 6] }
                ],
                "order": []
            });
        });
    </script>
</body>
</html>
