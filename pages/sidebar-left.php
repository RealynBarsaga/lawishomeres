<style>
.skin-black .left-side {
  background: rgb(51, 51, 51);
}
</style>
<?php
	echo '
	<aside class="left-side sidebar-offcanvas">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                        <ul class="sidebar-menu">
                            <li>
                                <a href="../dashboard/dashboard.php?page=dashboard">
                                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                                </a>
                            </li>
                            <li>
                                <a href="../officials/officials.php?page=officials">
                                    <i class="fa fa-user"></i> <span>Barangay Officials</span>
                                </a>
                            </li>
                            <li>
                                <a href="../household/household.php?page=household">
                                    <i class="fa fa-home"></i> <span>Household</span>
                                </a>
                            </li>
                            <li>
                                <a href="../resident/resident.php?page=resident">
                                    <i class="fa fa-users"></i> <span>Resident</span>
                                </a>
                            </li>
                            <li>
                                <a href="../brgyclearance/brgyclearance.php?page=brgyclearance">
                                    <i class="fa fa-file"></i> <span>Barangay Clearance</span>
                                </a>
                            </li>
                            <li>
                                <a href="../certofresidency/certofres.php?page=certofresidency">
                                    <i class="fa fa-file"></i> <span>Certificate Of Residency</span>
                                </a>
                            </li>
                            <li>
                                <a href="../certofindigency/certofindigency.php?page=certofindigency">
                                    <i class="fa fa-file"></i> <span>Certificate Of Indigency</span>
                                </a>
                            </li>
                            <li>
                                <a href="../brgycertificate/brgycertificate.php?page=brgycertificate">
                                    <i class="fa fa-file"></i> <span>Barangay Certificate</span>
                                </a>
                            </li>
                        </ul>
                </section>
                <!-- /.sidebar -->
            </aside>
	';
?>