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
                                                    <th>Resident Name</th>
                                                    <th>Clearance #</th>
                                                    <th>Purpose</th>
                                                    <th>OR Number</th>
                                                    <th>Amount</th>
                                                    <th style="width: 15% !important;">Option</th>
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
                                                        "Maalat" => "maalat_clearance_form.php"
                                                        /* "Pili" => "pili_clearance_form.php", */
                                                        /* "Kaongkod" => "kaongkod_clearance_form.php", */
                                                       /*  "Mancilang" => "mancilang_clearance_form.php", */
                                                        /* "Kangwayan" => "kangwayan_clearance_form.php", */
                                                        /* "Tugas" => "tugas_clearance_form.php", */
                                                       /*  "Malbago" => "malbago_clearance_form.php", */
                                                       /*  "Tarong" => "tarong_clearance_form.php", */
                                                        /* "San Agustin" => "san_agustin_clearance_form.php" */
                                                    ];
                                                    
                                                    // Fetch approved clearances from database
                                                    $squery = mysqli_query($con, " 
                                                        SELECT
                                                            p.name, 
                                                            p.clearanceNo, 
                                                            p.purpose, 
                                                            p.orNo, 
                                                            p.samount,
                                                            p.id
                                                        FROM tblclearance AS p WHERE p.barangay = '$off_barangay'");
                                                    while ($row = mysqli_fetch_array($squery)) {
                                                        echo '
                                                            <tr>
                                                                <td><input type="checkbox" name="chk_delete[]" class="chk_delete" value="' . htmlspecialchars($row['id']) . '" /></td>
                                                                <td>' . htmlspecialchars($row['name']) . '</td>
                                                                <td>' . htmlspecialchars($row['clearanceNo']) . '</td>
                                                                <td>' . htmlspecialchars($row['purpose']) . '</td>
                                                                <td>' . htmlspecialchars($row['orNo']) . '</td>
                                                                <td>₱ ' . number_format($row['samount'], 2) . '</td>
                                                                <td style="width: 170px;">
                                                                    <button class="btn btn-primary btn-sm" data-target="#editModal' . htmlspecialchars($row['id']) . '" data-toggle="modal">
                                                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit
                                                                    </button>
                                                                    <!-- Link to generate clearance form -->
                                                                    <a style="width: 80px;" href="' . $barangay_forms[$off_barangay] . '?resident=' . urlencode($row['name']) .'&purpose=' . urlencode($row['purpose']) .'&clearance=' . urlencode($row['clearanceNo']) .'&val=' . urlencode(base64_encode($row['clearanceNo'] . '|' . $row['name'])) . '" class="btn btn-primary btn-sm">
                                                                        <i class="fa fa-print" aria-hidden="true"></i> Print
                                                                    </a>
                                                                </td>
                                                            </tr>';
                                                        include "edit_modal.php"; // Include edit modal for each row
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

    <?php include "../../footer.php"; ?>

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