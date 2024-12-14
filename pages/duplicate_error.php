<!-- Include SweetAlert CSS and JS in your HTML file -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

<?php
if (isset($_SESSION['duplicate'])) {
    echo '<script>$(document).ready(function (){swal("Duplicate record!", "", "error");});</script>';
    unset($_SESSION['duplicate']);
}

if (isset($_SESSION['duplicateuser'])) {
    echo '<script>$(document).ready(function (){swal("Username Already Exists!", "", "error");});</script>';
    unset($_SESSION['duplicateuser']);
}

if (isset($_SESSION['end'])) {
    echo '<script>$(document).ready(function (){swal("End Term Successfully!", "", "success");});</script>';
    unset($_SESSION['end']);
}

if (isset($_SESSION['start'])) {
    echo '<script>$(document).ready(function (){swal("Start Term Successfully!", "", "success");});</script>';
    unset($_SESSION['start']);
}

if (isset($_SESSION['delete'])) {
    echo '<script>$(document).ready(function (){swal("Deleted Successfully!", "", "success");});</script>';
    unset($_SESSION['delete']);
}

if (isset($_SESSION['filesize'])) {
    echo '<script>$(document).ready(function (){swal("File size is greater than 2mb or Invalid Format!", "", "error");});</script>';
    unset($_SESSION['filesize']);
}

if (isset($_SESSION['blotter'])) {
    echo '<script>$(document).ready(function (){swal("Blotter case was not been resolved!", "", "warning");});</script>';
    unset($_SESSION['blotter']);
}
?>