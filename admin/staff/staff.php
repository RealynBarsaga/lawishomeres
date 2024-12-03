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
</head>
<body class="skin-black">
    <!-- header logo: style can be found in header.less -->
    <?php 
    include "../../admin/connection.php";
    include('../../admin/header.php'); 
    ?>

    <div class="row-offcanvas row-offcanvas-left">
        <!-- Left side column. contains the logo and sidebar -->
        <?php include('../../admin/sidebar-left.php'); ?>

        <!-- Right side column. Contains the navbar and content of the page -->
        <aside class="right-side">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>List Of Barangay</h1>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <!-- left column -->
                    <div class="box">
                        <div class="box-header">
                            <div style="padding:10px;">
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal">
                                    <i class="fa fa-user-plus" aria-hidden="true"></i> Add Brgy
                                </button>  
                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal" id="deleteButton" style="display:none;margin-left: 5px;color: #fff;background-color: #dc3545;border-color: #dc3545;">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i> Delete (<span id="selectedCount">0</span>)
                                </button>
                            </div>                                
                        </div><!-- /.box-header -->
                        <div class="box-body table-responsive">
                            <form method="post">
                                <table id="table" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width: 100px; text-align: left;">
                                                <label>
                                                    <input type="checkbox" class="cbxMain" onchange="checkMain(this)" style="vertical-align: middle;" />
                                                    <span style="vertical-align: -webkit-baseline-middle; margin-left: 5px; font-size: 13px;">Select All</span>
                                                </label>
                                            </th>
                                            <th>List Of Barangay</th>
                                            <th>Username</th>
                                            <th style="width: 150px !important;">Option</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $squery = mysqli_query($con, "SELECT * FROM tblstaff") or die('Error: ' . mysqli_error($con));
                                        while ($row = mysqli_fetch_array($squery)) {
                                            $deleteModalId = 'deleteModal' . $row['id'];

                                            echo '
                                            <tr>
                                                <td><input type="checkbox" name="chk_delete[]" class="chk_delete" value="' . $row['id'] . '" /></td>
                                                <td>' . htmlspecialchars($row['name']) . '</td>
                                                <td>' . htmlspecialchars($row['username']) . '</td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm" data-target="#editModal' . $row['id'] . '" data-toggle="modal">
                                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit
                                                    </button>
                                                    <button class="btn btn-danger btn-sm" data-target="#' . $deleteModalId . '" data-toggle="modal" style="margin-left: 5px;color: #fff;background-color: #dc3545;border-color: #dc3545;">
                                                            <i class="fa fa-trash" aria-hidden="true"></i> Delete
                                                    </button>
                                                </td>
                                            </tr>
                                            ';
                                            include "edit_modal.php";
                                            include "delete_modal.php";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <?php include "../deleteModal.php"; ?>
                            </form>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                    <?php
                        include "../duplicate_error.php";
                        include "../edit_notif.php";
                        include "../added_notif.php";
                        include "../delete_notif.php";
                        include "add_modal.php";
                        include "function.php";
                    ?>
                </div><!-- /.row -->
            </section><!-- /.content -->
        </aside><!-- /.right-side -->
    </div><!-- ./wrapper -->

    <?php include "../../admin/footer.php"; ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#table").DataTable({
                "columnDefs": [
                    { "sortable": false, "targets": [0, 3] }
                ],
                "order": []
            });
        });
        $(document).ready(function() {
        // Check if 'Select All' checkbox is checked or not
        $(".cbxMain").change(function() {
            // If checked, show the delete button, otherwise hide it
            if ($(this).prop("checked")) {
                $("#deleteButton").show(); // Show delete button
            } else {
                $("#deleteButton").hide(); // Hide delete button
            }
        });

        // Trigger change event on page load to set initial state
        $(".cbxMain").trigger("change");
    });
    $(document).ready(function() {
        // When any individual checkbox is changed
        $("input[name='chk_delete[]']").change(function() {
            // Check if any checkbox is checked
            if ($("input[name='chk_delete[]']:checked").length > 0) {
                $("#deleteButton").show(); // Show delete button
            } else {
                $("#deleteButton").hide(); // Hide delete button if no checkboxes are checked
            }
        });

        // Trigger change event on page load to set initial state
        $("input[name='chk_delete[]']").trigger("change");
    });
    $(document).ready(function() {
        // Update 'Select All' functionality to show/hide delete button
        $(".cbxMain").change(function() {
            updateDeleteButton();
        });

        // Update individual checkbox change event
        $("input[name='chk_delete[]']").change(function() {
            updateDeleteButton();
        });

        // Function to update the count and visibility of the delete button
        function updateDeleteButton() {
            var selectedCount = $("input[name='chk_delete[]']:checked").length;

            // Update the count in the delete button
            $("#selectedCount").text(selectedCount);

            // If there's at least one selected checkbox, show the delete button
            if (selectedCount > 0) {
                $("#deleteButton").show();
            } else {
                $("#deleteButton").hide();
            }
        }

        // Trigger the update function on page load to set the initial state
        updateDeleteButton();
    });
    </script>
</body>
</html>
