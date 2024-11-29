<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    session_start();
    if (!isset($_SESSION['userid'])) {
        header('Location: ../../login.php');
        exit; // Ensure no further execution after redirect
    }
    include('../head_css.php');
    ?>
    <style>
    .nav-tabs li a {
        cursor: pointer;
    }
    body {
        overflow: hidden;
    }

    .wrapper {
        overflow: hidden;
    }

    .right-side {
        overflow: auto;
        max-height: calc(111vh - 120px);
    }
    </style>
</head>
<body class="skin-black">
    <?php
    include "../connection.php";
    include('../header.php');
    ?>

    <div class="row-offcanvas row-offcanvas-left">
        <?php include('../sidebar-left.php'); ?>

        <aside class="right-side">
            <section class="content-header">
                <h1>Barangay Clearance</h1>
            </section>

            <section class="content">
                <div class="row">
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
                        </div>
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
                                                    $off_barangay = $_SESSION['barangay']; // e.g., "Tabagak", "Bunakan", etc.
                                                    
                                                    $barangay_forms = [
                                                        "Tabagak" => "tabagak_clearance_form.php",
                                                        "Bunakan" => "bunakan_clearance_form.php",
                                                        "Maalat" => "maalat_clearance_form.php",
                                                        "Pili" => "pili_clearance_form.php"
                                                    ];

                                                    // Using prepared statement to prevent SQL injection
                                                    $stmt = $con->prepare("SELECT name, clearanceNo, purpose, orNo, samount, id AS pid FROM tblclearance WHERE barangay = ?");
                                                    $stmt->bind_param("s", $off_barangay);
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
                        </div>
                    </div>

                    <?php include "../edit_notif.php"; ?>
                    <?php include "../added_notif.php"; ?>
                    <?php include "../delete_notif.php"; ?>
                    <?php include "../duplicate_error.php"; ?>
                    <?php include "add_modal.php"; ?>
                    <?php include "function.php"; ?>
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

            function updateDeleteButton() {
                var selectedCount = $("input[name='chk_delete[]']:checked").length;
                $("#selectedCount").text(selectedCount);
                if (selectedCount > 0) {
                    $("#deleteButton").show();
                } else {
                    $("#deleteButton").hide();
                }
            }

            $(".cbxMain, input[name='chk_delete[]']").change(function() {
                updateDeleteButton();
            });

            updateDeleteButton(); // Initialize delete button state
        });
    </script>
</body>
</html>
