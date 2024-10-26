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
<body class="skin-black">
    <?php 
    include "../connection.php"; 
    include('../header.php'); 
    ?>
    
    <div class="wrapper row-offcanvas row-offcanvas-left">
        <?php include('../sidebar-left.php'); ?>

        <aside class="right-side">
            <section class="content-header">
                <h1>Certificate Of Indigency</h1>
            </section>

            <section class="content">
                <div class="row">
                    <div class="box">
                        <div class="box-header">
                            <div style="padding:10px;">
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal">
                                    <i class="fa fa-user-plus" aria-hidden="true"></i> Add Certificate
                                </button>
                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i> Delete
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
                                            <th style="width: 160.667px;">Option</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        // Assuming you're storing the logged-in barangay in a session
                                        $off_barangay = $_SESSION['barangay']; // e.g., "Tabagak", "Bunakan", etc.
                                                    
                                        // Map barangays to their corresponding indigency form files
                                        $barangay_forms = [
                                            "Tabagak" => "tabagak_indigency_form.php",
                                            "Bunakan" => "bunakan_indigency_form.php",
                                            /* "Kodia" => "kodia_residency_form.php", */
                                            /* "Talangnan" => "talangnan_residency_form.php", */
                                            /* "Poblacion" => "poblacion_residency_form.php", */
                                            "Maalat" => "maalat_residency_form.php"
                                            /* "Pili" => "pili_residency_form.php", */
                                            /* "Kaongkod" => "kaongkod_residency_form.php", */
                                            /* "Mancilang" => "mancilang_residency_form.php", */
                                            /* "Kangwayan" => "kangwayan_residency_form.php", */
                                            /* "Tugas" => "tugas_residency_form.php", */
                                            /* "Malbago" => "malbago_residency_form.php", */
                                            /* "Tarong" => "tarong_residency_form.php", */
                                            /* "San Agustin" => "san_agustin_residency_form.php" */
                                        ];

                                        $stmt = $con->prepare("SELECT Name, purpose, barangay, purok, id AS pid FROM tblindigency WHERE barangay = '$off_barangay'");
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        while ($row = $result->fetch_assoc()) {
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
                                                    <a style="width: 80px;" href="' . $barangay_forms[$off_barangay] . '?resident=' . urlencode($row['Name']) .'&barangay=' . urlencode($row['barangay']) .'|' . $row['Name'] . '" class="btn btn-primary btn-sm">
                                                        <i class="fa fa-print" aria-hidden="true"></i> Print
                                                    </a>
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
/* function printRow(id) {
    // This will fetch the specific row using the ID
    var printContents = document.querySelector("tr input[value='"+id+"']").closest('tr').outerHTML;
    
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = '<table>' + printContents + '</table>';

    window.print();

    document.body.innerHTML = originalContents;
} */
    </script>
</body>
</html>
