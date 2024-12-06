<style>
.skin-black .left-side {
    background: rgb(51, 51, 51);
}
</style>

<?php
session_start(); // Start the session to access session variables

// Check if the user is authorized
if (!isset($_SESSION['role']) || $_SESSION['role'] != "Staff") {
    // Redirect to 403 Forbidden page if the user is not Staff
    header('HTTP/1.0 403 Forbidden');
    include('403-forbidden.php'); // Display the 403 Forbidden page
    exit();
}

echo '
<aside class="left-side sidebar-offcanvas">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- sidebar menu: style can be found in sidebar.less -->
        <ul class="sidebar-menu">';

// Sidebar items for Staff
if ($_SESSION['role'] == "Staff") {
    echo '
        <li>
            <a href="../dashboard/dashboard">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="../officials/officials">
                <i class="fa fa-user"></i> <span>Barangay Officials</span>
            </a>
        </li>
        <li>
            <a href="../household/household">
                <i class="fa fa-home"></i> <span>Household</span>
            </a>
        </li>
        <li>
            <a href="../resident/resident">
                <i class="fa fa-users"></i> <span>Resident</span>
            </a>
        </li>
        <li>
            <a href="../clearance/clearance">
                <i class="fa fa-file"></i> <span>Barangay Clearance</span>
            </a>
        </li>
        <li>
            <a href="../certofresidency/certofres">
                <i class="fa fa-file"></i> <span>Certificate Of Residency</span>
            </a>
        </li>
        <li>
            <a href="../certofindigency/certofindigency">
                <i class="fa fa-file"></i> <span>Certificate Of Indigency</span>
            </a>
        </li>
        <li>
            <a href="../brgycertificate/brgycertificate">
                <i class="fa fa-file"></i> <span>Barangay Certificate</span>
            </a>
        </li>';
}

echo '
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>';
?>