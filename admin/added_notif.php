<!-- Include SweetAlert CSS and JS in your HTML file -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

<?php
if (isset($_SESSION['added'])) {
    echo '<script>$(document).ready(function (){swal("Successfully Added!", "", "success");});</script>';
    unset($_SESSION['added']);
}
?>