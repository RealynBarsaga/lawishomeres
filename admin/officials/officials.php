<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    session_start();
    if (!isset($_SESSION['userid'])) {
        header('Location: ../');
        exit; // Ensure no further execution after redirect
    }
    include('../../admin/head_css.php'); // Removed ob_start() since it's not needed here
    include "../connection.php";
    ?>
</head>
<body class="skin-black">
    <!-- header logo: style can be found in header.less -->
    <?php include('../header.php'); ?>

    <div class="wrapper row-offcanvas row-offcanvas-left">
        <!-- Left side column. contains the logo and sidebar -->
        <?php include('../sidebar-left.php'); ?>

        <!-- Right side column. Contains the navbar and content of the page -->
        <aside class="right-side">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>Madridejos Officials</h1>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <!-- left column -->
                    <div class="box">
                        <div class="box-header">
                            <div style="padding:10px;">
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addOfficialModal">
                                    <i class="fa fa-user-plus" aria-hidden="true"></i> Add Officials
                                </button>  
                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i> Delete
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
                                            <th>Image</th>
                                            <th>Position</th>
                                            <th style="width: 150px !important;">Name</th>
                                            <th>Contact</th>
                                            <th>Address</th>
                                            <th>Start of Term</th>
                                            <th>End of Term</th>
                                            <th style="width: 130px !important;">Option</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $squery = mysqli_query($con, "SELECT * FROM tblmadofficial");
                                            while ($row = mysqli_fetch_array($squery)) {
                                                $editModalId = 'editModal' . $row['id'];
                                                $endModalId = 'endModal' . $row['id'];
                                                $startModalId = 'startModal' . $row['id'];

                                                echo '
                                                <tr>
                                                    <td><input type="checkbox" name="chk_delete[]" class="chk_delete" value="' . htmlspecialchars($row['id']) . '" /></td>
                                                    <td><img src="./image/' . basename($row['image']) . '" style="width:60px;height:60px;"/></td>
                                                    <td>' . htmlspecialchars($row['sPosition']) . '</td>
                                                    <td>' . htmlspecialchars($row['completeName']) . '</td>
                                                    <td>' . htmlspecialchars($row['pcontact']) . '</td>
                                                    <td>' . htmlspecialchars($row['paddress']) . '</td>
                                                    <td>' . htmlspecialchars($row['termStart']) . '</td>
                                                    <td>' . htmlspecialchars($row['termEnd']) . '</td>
                                                    <td>
                                                        <button class="btn btn-primary btn-sm" data-target="#' . $editModalId . '" data-toggle="modal">
                                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit
                                                        </button>';
                                                        if ($row['status'] == 'Ongoing Term') {
                                                            echo '<button class="btn btn-danger btn-sm" data-target="#' . $endModalId . '" data-toggle="modal">
                                                                <i class="fa fa-minus-circle" aria-hidden="true"></i> End
                                                            </button>';
                                                        } else {
                                                            echo '<button class="btn btn-success btn-sm" data-target="#' . $startModalId . '" data-toggle="modal">
                                                                <i class="fa fa-plus-circle" aria-hidden="true"></i> Start
                                                            </button>';
                                                        }
                                                    echo '</td>
                                                </tr>';

                                                include "edit_modal.php";
                                                include "endterm_modal.php";
                                                include "startterm_modal.php";
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
                </div> <!-- /.row -->
            </section><!-- /.content -->
        </aside><!-- /.right-side -->
    </div><!-- ./wrapper -->

    <!-- jQuery 2.0.2 -->
    <?php include "../footer.php"; ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#table").DataTable({
                "columnDefs": [
                    { "sortable": false, "targets": [0, 7] }
                ],
                "order": []
            });
        });
    </script>
</body>
</html>