<?php
    // Include the backup/restore class
    require_once('backup_restore_class.php'); 

    // Database connection details
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $db = 'db_barangay';

    // Create a new instance of the BackupRestore class
    $newImport = new BackupRestore($host, $db, $user, $pass);

    $message = '';

    // If a process (backup/restore) is requested
    if (isset($_GET['process'])) {
        $process = $_GET['process'];

        // If backup is requested
        if ($process == 'backup') {
            $message = $newImport->backup();   
        } 
        // If restore is requested
        else if ($process == 'restore') {
            $message = $newImport->restore(); 
        
            // After restoring, delete the backup file if it exists
            $backupFilePath = 'backup_db/database_' . $db . '.sql';
            if (file_exists($backupFilePath)) {
                unlink($backupFilePath);
            } else {
                $message = "Backup file not found or already deleted.";
            }
        }
    }

    // If a file is uploaded for restore
    if (isset($_POST['submit'])) {
        // Validate the uploaded file (e.g., check file extension)
        if ($_FILES["file"]["error"] == UPLOAD_ERR_OK) {
            $db = 'database_'.$db.'.sql';
            $target_path = 'backup_db';

            // Validate file extension (SQL files)
            $file_type = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
            if ($file_type !== 'sql') {
                $message = "Only .sql files are allowed.";
            } else {
                // Move the uploaded file to the backup directory
                move_uploaded_file($_FILES["file"]["tmp_name"], $target_path . '/' . $db);    
                $message = 'Successfully uploaded. You can now <a href="backup.php?process=restore">restore</a> the database!';
            }
        } else {
            $message = "Failed to upload file. Please try again.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<?php
    session_start();
    if (!isset($_SESSION['userid'])) {
        header('Location: ../../login.php');
        exit;
    }
    include('../head_css.php');
?>

<style>
body {
    overflow: hidden; /* Prevents body from scrolling */
}

.wrapper {
    overflow: hidden; /* Prevents the wrapper from scrolling */
}

.right-side {
    overflow: auto; /* Only this part is scrollable */
    max-height: calc(111vh - 120px); /* Adjust based on your header/footer size */
}
</style>

<body class="skin-black">
    <?php 
    ob_start();
    include "../connection.php";
    ?>
    <?php include('../header.php'); ?>

    <div class="wrapper row-offcanvas row-offcanvas-left">
        <!-- Left side column. contains the logo and sidebar -->
        <?php include('../sidebar-left.php'); ?>

        <!-- Right side column. Contains the navbar and content of the page -->
        <aside class="right-side">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Backup Database
                </h1>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="box">
                        <div class="col-lg-offset-2 col-lg-7" style="margin-top: 20px;">
                        
                            <?php if($message): ?>
                                <div class="alert alert-info text-center">
                                    <strong><?php echo $message; ?></strong>
                                </div>
                            <?php endif; ?>
                        
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-offset-1 col-md-6">
                                        <a href="backup.php?process=backup">
                                            <button type="button" class="btn btn-success btn-lg span7">
                                                <i class="fa fa-database"></i> BACKUP DATABASE
                                            </button>
                                        </a>
                                    </div>
                                    <div class="col-md-5">
                                        <a href="backup.php?process=restore">
                                            <button type="button" class="btn btn-info btn-lg span7">
                                                <i class="fa fa-database"></i> RESTORE DATABASE
                                            </button>
                                        </a>
                                    </div>                        
                                </div>
                            </div>

                            <br />

                            <div class="upload alert alert-warning">
                                <hr />
                                <form method="POST" enctype="multipart/form-data">
                                    <label>Upload SQL File:</label>
                                    <input type="file" name="file" class="form-control"><br>
                                    <input type="submit" name="submit" class="btn btn-success" value="Upload Database" />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section><!-- /.content -->
        </aside><!-- /.right-side -->
    </div><!-- ./wrapper -->

    <!-- Include Footer -->
    <?php include "../footer.php"; ?>
</body>
</html>