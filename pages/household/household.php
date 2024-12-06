<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    session_start();
    if (!isset($_SESSION['userid'])) {
        header('Location: ../../login.php');
        exit; // Ensure no further execution after redirect
    }

    // Check if the user's role is not 'staff'
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Staff') {
        // Redirect to the access denied page if not an admin
        header('Location: /pages/redirectlink');
        exit(); // Stop further script execution
    }
    
    include('../head_css.php');
    ?>
</head>
<style>
body {
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
        <!-- Left side column contains the logo and sidebar -->
        <?php include('../sidebar-left.php'); ?>

        <!-- Right side column contains the navbar and content of the page -->
        <aside class="right-side">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>Household</h1>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <!-- Box container -->
                    <div class="box">
                        <div class="box-header">
                            <div style="padding:10px;">
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal">
                                    <i class="fa fa-user-plus" aria-hidden="true"></i> Add Household
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
                                            <th>Household #</th>
                                            <th>Total Members</th>
                                            <th>Head of Family</th>
                                            <th>Barangay</th>
                                            <th>Purok</th>
                                            <th>Family Members</th>
                                            <th style="width: 150px !important;">Option</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $stmt = $con->prepare("SELECT *, h.id as id, CONCAT(r.lname, ', ', r.fname, ' ', r.mname) as head_of_family 
                                    FROM tblhousehold h 
                                    LEFT JOIN tbltabagak r ON r.id = h.headoffamily WHERE r.barangay = ? "); // Use ? as a placeholder
                                    
                                    $stmt->bind_param("s", $off_barangay); // Bind the parameter
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    
                                    // Fetch and display the results
                                    while ($row = $result->fetch_assoc()) {
                                        $deleteModalId = 'deleteModal' . $row['id'];
                                    
                                        // Format the membersname field
                                        $membersname = htmlspecialchars($row['membersname'], ENT_QUOTES, 'UTF-8');
                                        
                                        // Split the names by commas and trim extra spaces
                                        $names = array_map('trim', explode(',', $membersname));
                                    
                                        // Create an array to hold formatted names
                                        $formatted_names = [];
                                    
                                        // Loop through the names and format each one
                                        for ($i = 0; $i < count($names); $i += 2) {
                                            $last_name = $names[$i];
                                            $first_and_middle = isset($names[$i + 1]) ? $names[$i + 1] : '';
                                    
                                            // Format the name as "Lastname, Firstname Middlename Initial"
                                            $formatted_names[] = $last_name . ', ' . $first_and_middle;
                                        }
                                    
                                        // Join the formatted names with line breaks
                                        $formatted_names_output = implode('</br>', $formatted_names);
                                    
                                        echo '
                                        <tr>
                                            <td><input type="checkbox" name="chk_delete[]" class="chk_delete" value="' . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . '" /></td>
                                            <td><a href="../resident/resident.php?resident=' . htmlspecialchars($row['householdno'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($row['householdno'], ENT_QUOTES, 'UTF-8') . '</a></td>
                                            <td>' . htmlspecialchars($row['totalhouseholdmembers'], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row['head_of_family'], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row['barangay'], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . htmlspecialchars($row['purok'], ENT_QUOTES, 'UTF-8') . '</td>
                                            <td>' . $formatted_names_output . '</td> <!-- Updated this line to display formatted membersnames -->
                                            <td>
                                                <button class="btn btn-primary btn-sm" data-target="#editModal' . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . '" data-toggle="modal">
                                                    <i class="fa fa-eye" aria-hidden="true"></i> View
                                                </button>
                                                <button class="btn btn-danger btn-sm" data-target="#' . $deleteModalId . '" data-toggle="modal" style="margin-left: 5px;color: #fff;background-color: #dc3545;border-color: #dc3545;">
                                                    <i class="fa fa-trash" aria-hidden="true"></i> Delete
                                                </button>
                                            </td>
                                        </tr>';
                                    
                                        // Include the edit modal
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

                    <!-- Include notifications and modals -->
                    <?php 
                    include "../edit_notif.php"; 
                    include "../added_notif.php"; 
                    include "../delete_notif.php"; 
                    include "../duplicate_error.php"; 
                    include "add_modal.php"; 
                    include "function.php"; 
                    ?>
                </div> <!-- /.row -->
            </section><!-- /.content -->
        </aside><!-- /.right-side -->
    </div><!-- ./wrapper -->

    <!-- jQuery 2.0.2 -->
    <?php include "../footer.php"; ?>

    <script type="text/javascript">
        $(function() {
            $("#table").dataTable({
                "aoColumnDefs": [{ "bSortable": false, "aTargets": [0, 5] }],
                "aaSorting": []
            });
            $(".select2").select2();
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