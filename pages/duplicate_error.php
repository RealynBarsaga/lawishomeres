<?php if(isset($_SESSION['duplicate'])){
    echo '<script>$(document).ready(function (){duplicate();});</script>';
    unset($_SESSION['duplicate']);
    } 
echo '<div class="alert alert-duplicate alert-autocloseable-duplicate" style="background: #d9534f; position: fixed; top: 1em; right: 1em; z-index: 9999; display: none;">
     Duplicate record !
</div>';
?>

<?php if(isset($_SESSION['duplicateuser'])){
    echo '<script>$(document).ready(function (){duplicateuser();});</script>';
    unset($_SESSION['duplicateuser']);
    } 
echo '<div class="alert alert-duplicateuser alert-autocloseable-duplicateuser" style="background: #d9534f; position: fixed; top: 1em; right: 1em; z-index: 9999; display: none;">
     Username Already Exists !
</div>';
?>

<?php if(isset($_SESSION['end'])){
    echo '<script>$(document).ready(function (){end();});</script>';
    unset($_SESSION['end']);
    } 
echo '<div class="alert alert-end alert-autocloseable-end" style="background: #dff0d8; position: fixed; top: 1em; right: 1em; z-index: 9999; display: none;">
     End Term Successfully !
</div>';
?>

<?php if(isset($_SESSION['start'])){
    echo '<script>$(document).ready(function (){start();});</script>';
    unset($_SESSION['start']);
    } 
echo '<div class="alert alert-start alert-autocloseable-start" style="background: #dff0d8; position: fixed; top: 1em; right: 1em; z-index: 9999; display: none;">
     Start Term Successfully !
</div>';
?>

<?php if(isset($_SESSION['delete'])){
    echo '<script>$(document).ready(function (){deleted();});</script>';
    unset($_SESSION['delete']);
    } ?>
<div class="alert alert-danger alert-autocloseable-danger" style="position: fixed; top: 1em; right: 1em; z-index: 9999; display: none;">
     Deleted Successfully !
</div>

<?php if(isset($_SESSION['filesize'])){
    echo '<script>$(document).ready(function (){filesize();});</script>';
    unset($_SESSION['filesize']);
    } 
echo '<div class="alert alert-filesize alert-autocloseable-filesize" style="background: #d9534f; position: fixed; top: 1em; right: 1em; z-index: 9999; display: none;">
     File size is greater than 2mb or Invalid Format !
</div>';
?>

<?php if(isset($_SESSION['file_upload_error'])){
    echo '<script>$(document).ready(function (){file_upload_error();});</script>';
    unset($_SESSION['file_upload_error']);
    } 
echo '<div class="alert alert-file_upload_error alert-autocloseable-file_upload_error" style="background: #d9534f; position: fixed; top: 1em; right: 1em; z-index: 9999; display: none;">
     Sorry, there was an error uploading your file !
</div>';
?>

<?php if(isset($_SESSION['invalid_image'])){
    echo '<script>$(document).ready(function (){invalid_image();});</script>';
    unset($_SESSION['invalid_image']);
    } 
echo '<div class="alert alert-invalid_image alert-autocloseable-invalid_image" style="background: #d9534f; position: fixed; top: 1em; right: 1em; z-index: 9999; display: none;">
     File is not a valid image !
</div>';
?>

<?php if(isset($_SESSION['invalid_file'])){
    echo '<script>$(document).ready(function (){invalid_file();});</script>';
    unset($_SESSION['invalid_file']);
    } 
echo '<div class="alert alert-invalid_file alert-autocloseable-invalid_file" style="background: #d9534f; position: fixed; top: 1em; right: 1em; z-index: 9999; display: none;">
     Invalid file type or size !
</div>';
?>

<?php if(isset($_SESSION['blotter'])){
    echo '<script>$(document).ready(function (){blotter();});</script>';
    unset($_SESSION['blotter']);
    } 
echo '<div class="alert alert-blotter alert-autocloseable-blotter" style="background: #f0ad4e; position: fixed; top: 1em; right: 1em; z-index: 9999; display: none;">
     Blotter case was not been resolved !
</div>';
?>