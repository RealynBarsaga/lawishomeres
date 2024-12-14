<!-- Include SweetAlert CSS and JS in your HTML file -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

<?php
// Check for session variable and display SweetAlert
if (isset($_SESSION['delete'])) {
    echo '<script>$(document).ready(function (){swal("Deleted Successfully!", "", "success");});</script>';
    unset($_SESSION['delete']);
}
?>