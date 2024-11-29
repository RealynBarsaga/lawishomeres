<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    session_start();
    if (!isset($_SESSION['userid'])) {
        header('Location: ../../login.php');
        exit; // Ensure no further execution after redirect
    }
    include('../head_css.php'); // Removed ob_start() since it's not needed here
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
    <?php 
    include "../connection.php"; 
    include('../header.php'); 
    ?>

    <div class="row-offcanvas row-offcanvas-left">
        <!-- Left side column. contains the logo and sidebar -->
        <?php include('../sidebar-left.php'); ?>

        <!-- Right side column. Contains the navbar and content of the page -->
        <aside class="right-side">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>Barangay Clearance</h1>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <!-- left column -->
                    <div class="box">
                        <div class="box-header">
                            <div style="padding:10px;">
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal">
                                    <i class="fa fa-user-plus" aria-hidden="true"></i> Add Clearance
                                </button>
                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal" id="deleteButton" style="display:none;margin-left: 5px;color: #fff;background-color: #dc3545;border-color: #dc3545;">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i> Delete (<span id="selectedCount">0</span>)
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
                                                    <th>Resident Name</th>
                                                    <th>Clearance #</th>
                                                    <th>Purpose</th>
                                                    <th>OR Number</th>
                                                    <th>Amount</th>
                                                    <th style="width: 215px !important;">Option</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    // Assuming you're storing the logged-in barangay in a session
                                                    $off_barangay = $_SESSION['barangay']; // e.g., "Tabagak", "Bunakan", etc.
                                                    
                                                    // Map barangays to their corresponding clearance form files
                                                    $barangay_forms = [
                                                        "Tabagak" => "tabagak_clearance_form.php",
                                                        "Bunakan" => "bunakan_clearance_form.php",
                                                        /* "Kodia" => "kodia_clearance_form.php", */
                                                        /* "Talangnan" => "talangnan_clearance_form.php", */
                                                        /* "Poblacion" => "poblacion_clearance_form.php", */
                                                        "Maalat" => "maalat_clearance_form.php",
                                                        "Pili" => "pili_clearance_form.php"
                                                        /* "Kaongkod" => "kaongkod_clearance_form.php", */
                                                       /*  "Mancilang" => "mancilang_clearance_form.php", */
                                                        /* "Kangwayan" => "kangwayan_clearance_form.php", */
                                                        /* "Tugas" => "tugas_clearance_form.php", */
                                                       /*  "Malbago" => "malbago_clearance_form.php", */
                                                       /*  "Tarong" => "tarong_clearance_form.php", */
                                                        /* "San Agustin" => "san_agustin_clearance_form.php" */
                                                    ];

                                                    $stmt = $con->prepare("SELECT name, clearanceNo, purpose, orNo, samount, id AS pid FROM tblclearance WHERE barangay = '$off_barangay'");
                                                    $stmt->execute();
                                                    $result = $stmt->get_result();
                                                    
                                                        while ($row = $result->fetch_assoc()) {
                                                            $deleteModalId = 'deleteModal' . $row['pid'];
                                                        echo '
                                                            <tr>
                                                                <td><input type="checkbox" name="chk_delete[]" class="chk_delete" value="' . htmlspecialchars($row['pid']) . '" /></td>
                                                                <td>' . htmlspecialchars($row['name']) . '</td>
                                                                <td>' . htmlspecialchars($row['clearanceNo']) . '</td>
                                                                <td>' . htmlspecialchars($row['purpose']) . '</td>
                                                                <td>' . htmlspecialchars($row['orNo']) . '</td>
                                                                <td>₱ ' . number_format($row['samount'], 2) . '</td>
                                                                <td>
                                                                    <button class="btn btn-primary btn-sm" data-target="#editModal' . htmlspecialchars($row['pid']) . '" data-toggle="modal">
                                                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit
                                                                    </button>
                                                                    <a style="width: 80px;color: #fff;background-color: #198754;border-color: #198754;" href="' . $barangay_forms[$off_barangay] . '?resident=' . urlencode($row['name']) .'&purpose=' . urlencode($row['purpose']) .'&clearance=' . urlencode($row['clearanceNo']) .'&val=' . urlencode(base64_encode($row['clearanceNo'] . '|' . $row['name'])) . '" class="btn btn-primary btn-sm">
                                                                        <i class="fa fa-print" aria-hidden="true"></i> Print
                                                                    </a>
                                                                    <button class="btn btn-danger btn-sm" data-target="#deleteModals' . htmlspecialchars($row['pid']) . '" data-toggle="modal" style="margin-left: 1px;color: #fff;background-color: #dc3545;border-color: #dc3545;">
                                                                        <i class="fa fa-trash" aria-hidden="true"></i> Delete
                                                                    </button>
                                                                </td>
                                                            </tr>';
                                                        include "edit_modal.php";
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php include "delete_modal.php"; ?>
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
