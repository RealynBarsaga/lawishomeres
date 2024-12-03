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
    <?php 
    include "../connection.php"; 
    include('../header.php'); 
    ?>

    <div class="row-offcanvas row-offcanvas-left">
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
                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal" id="deleteButton" style="display:none;margin-left: 5px;color: #fff;background-color: #dc3545;border-color: #dc3545;">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i> Delete (<span id="selectedCount">0</span>)
                                </button>
                            </div>
                        </div>
                        <div class="box-body table-responsive">
                            <form method="post" enctype="multipart/form-data">
                                <table id="table" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width: 80px; text-align: left;">
                                                <label>
                                                    <input type="checkbox" class="cbxMain" onchange="checkMain(this)" style="vertical-align: middle;" />
                                                    <span style="vertical-align: -webkit-baseline-middle; margin-left: 5px; font-size: 13px;">Select All</span>
                                                </label>
                                            </th>
                                            <th style="width: 15.6667px;">Image</th>
                                            <th>Household #</th>
                                            <th>Resident Name</th>
                                            <th>Age</th>
                                            <th>Gender</th>
                                            <th>Former Add</th>
                                            <th>Purok</th>
                                            <th>Role</th>
                                            <th style="width: 140px !important;">Option</th>
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
                                    role,
                                    image, 
                                    householdnum FROM tbltabagak WHERE barangay = '$off_barangay' ORDER BY lname, fname");
                                    while ($row = mysqli_fetch_array($squery)) {
                                        $deleteModalId = 'deleteModal' . $row['id'];

                                        echo '
                                        <tr>
                                            <td><input type="checkbox" name="chk_delete[]" class="chk_delete" value="'.htmlspecialchars($row['id']).'" /></td>
                                            <td><img src="image/' . basename($row['image']) . '" style="width:60px;height:60px;"/></td>
                                            <td>'. htmlspecialchars($row['householdnum']) .'</td>
                                            <td>'. htmlspecialchars($row['cname']) .'</td>
                                            <td>'. htmlspecialchars($row['age']) .'</td>
                                            <td>'. htmlspecialchars($row['gender']) .'</td>
                                            <td>'. htmlspecialchars($row['formerAddress']) .'</td>
                                            <td>'. htmlspecialchars($row['purok']) .'</td>
                                            <td>'. htmlspecialchars($row['role']) .'</td>
                                            <td>
                                                <button class="btn btn-primary btn-sm" data-target="#editModal'.htmlspecialchars($row['id']).'" data-toggle="modal">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit
                                                </button>
                                                <button class="btn btn-danger btn-sm" data-target="#' . $deleteModalId . '" data-toggle="modal" style="margin-left: 5px;color: #fff;background-color: #dc3545;border-color: #dc3545;">
                                                    <i class="fa fa-trash" aria-hidden="true"></i> Delete
                                                </button>
                                            </td>
                                        </tr>';
                                        include "edit_modal.php";
                                        include "delete_modal.php";
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