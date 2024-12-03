<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    session_start();
    if (!isset($_SESSION['userid'])) {
        header('Location: ../../login.php');
        exit;
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
        <?php include('../sidebar-left.php'); ?>

        <aside class="right-side">
            <section class="content-header">
                <h1>Certificate Of Residency</h1>
            </section>

            <section class="content">
                <div class="row">
                    <div class="box">
                        <div class="box-header">
                            <div style="padding:10px;">
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal">
                                    <i class="fa fa-user-plus" aria-hidden="true"></i> Add Certificate
                                </button>
                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal" id="deleteButton" style="display:none;margin-left: 5px;color: #fff;background-color: #dc3545;border-color: #dc3545;">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i> Delete (<span id="selectedCount">0</span>)
                                </button>
                            </div>
                        </div>
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
                                            <th>Resident Name</th>
                                            <th>Purpose</th>
                                            <th>Barangay</th>
                                            <th>Purok</th>
                                            <th style="width: 215px !important;">Option</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Assuming you're storing the logged-in barangay in a session
                                        $off_barangay = $_SESSION['barangay']; // e.g., "Tabagak", "Bunakan", etc.
                                                    
                                        // Map barangays to their corresponding residency form files
                                        $barangay_forms = [
                                            "Tabagak" => "tabagak_residency_form",
                                            "Bunakan" => "bunakan_residency_form",
                                            /* "Kodia" => "kodia_residency_form", */
                                            /* "Talangnan" => "talangnan_residency_form", */
                                            /* "Poblacion" => "poblacion_residency_form", */
                                            "Maalat" => "maalat_residency_form",
                                            "Pili" => "pili_residency_form"
                                            /* "Kaongkod" => "kaongkod_residency_form", */
                                            /* "Mancilang" => "mancilang_residency_form", */
                                            /* "Kangwayan" => "kangwayan_residency_form", */
                                            /* "Tugas" => "tugas_residency_form", */
                                            /* "Malbago" => "malbago_residency_form", */
                                            /* "Tarong" => "tarong_residency_form", */
                                            /* "San Agustin" => "san_agustin_residency_form" */
                                        ];

                                        $stmt = $con->prepare("SELECT Name, purpose, barangay, purok, id AS pid FROM tblrecidency WHERE barangay = '$off_barangay'");
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        while ($row = $result->fetch_assoc()) {
                                            $deleteModalId = 'deleteModal' . $row['pid'];
                                            echo '
                                            <tr>
                                                <td><input type="checkbox" name="chk_delete[]" class="chk_delete" value="'.htmlspecialchars($row['pid']).'" /></td>
                                                <td>'.htmlspecialchars($row['Name']).'</td>
                                                 <td>'.htmlspecialchars($row['purpose']).'</td> 
                                                <td>'.htmlspecialchars($row['barangay']).'</td> 
                                                <td>'.htmlspecialchars($row['purok']).'</td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm" data-target="#editModal'.htmlspecialchars($row['pid']).'" data-toggle="modal">
                                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit
                                                    </button>
                                                    <a style="width: 80px;color: #fff;background-color: #198754;border-color: #198754;" href="' . $barangay_forms[$off_barangay] . '?resident=' . urlencode($row['Name']) .'&barangay=' . urlencode($row['barangay']) .'|' . $row['Name'] . '" class="btn btn-primary btn-sm">
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

                                <?php include "delete_modal.php"; ?>
                                <?php include "../deleteModal.php"; ?>
                            </form>
                        </div>
                    </div>

                    <?php 
                    include "../edit_notif.php"; 
                    include "../added_notif.php"; 
                    include "../delete_notif.php"; 
                    include "../duplicate_error.php"; 
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
                "aoColumnDefs": [{ "bSortable": false, "aTargets": [0, 4] }],
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
