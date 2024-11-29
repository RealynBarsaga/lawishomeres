<?php
// Database credentials
$MySQL_username = "u510162695_db_barangay";
$Password = "1Db_barangay";    
$MySQL_database_name = "u510162695_db_barangay";

// Establishing connection with server
$conn = mysqli_connect('localhost', $MySQL_username, $Password, $MySQL_database_name);

// Checking connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Setting the default timezone
date_default_timezone_set("Asia/Manila");

// Get barangay from session (if set)
$off_barangay = $_SESSION["barangay"] ?? "";

// Query the latest clearance number from the database
$query = "SELECT clearanceNo FROM tblclearance WHERE barangay = '$off_barangay' ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    // Extract the last clearance number and increment it
    $last_clearance_number = $row['clearanceNo'];
    
    // Increment logic depending on your numbering format
    $next_clearance_number = intval($last_clearance_number) + 1;
} else {
    // If no records found, start with a default clearance number
    $next_clearance_number = 1; // or any starting number
}
// Format the clearance number to be 4 digits (e.g., 0001)
$formatted_clearance_number = str_pad($next_clearance_number, 4, '0', STR_PAD_LEFT);
?>
<!-- ========================= MODAL ======================= -->
<div id="addModal" class="modal fade">
    <form method="post">
        <div class="modal-dialog modal-sm" style="width:300px !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Manage Clearance</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Resident Name:</label>
                                <input name="txt_name" class="form-control input-sm" type="text" placeholder="Name" required/>
                            </div>
                            <div class="form-group">
                                <label>Clearance #:</label>
                                <input name="txt_cnum" class="form-control input-sm" type="text" value="<?php echo $formatted_clearance_number; ?>" readonly/>
                            </div>
                            <div class="form-group">
                                <label>Purpose:</label>
                                <input name="txt_purpose" class="form-control input-sm" type="text" placeholder="Purpose" required/>
                            </div>
                            <!-- Age -->
                            <div class="form-group">
                                <label>Age:</label>
                                <input name="txt_age" id="txt_age" class="form-control input-sm" type="text" placeholder="Age" readonly/>
                            </div>
                            <!-- Birthdate -->
                            <div class="form-group">
                                <label>Birthdate:</label>
                                <input name="txt_bdate" id="txt_bdate" class="form-control input-sm" type="date" placeholder="Birthdate" max="<?php echo date('Y-m-d'); ?>" required/>
                            </div>
                            <div class="form-group">
                                <?php
                                    // Purok options for each barangay
                                    $puroks = [
                                        "Tabagak" => ["Lamon-Lamon", "Tangigue", "Lawihan", "Lower-Bangus", "Upper-Bangus"],
                                        "Bunakan" => ["Bilabid", "Helinggero", "Kamaisan", "Kalubian", "Samonite"],
                                        "Maalat" => ["Neem Tree", "Talisay", "Kabakhawan", "Mahogany", "Gmelina"],
                                        "Pili" => ["Malinawon", "Mahigugmaon", "Matinabangun", "Maabtikon", "Malipayon", "Mauswagon"],
                                        "Tarong" => ["Orchids", "Gumamela", "Santan", "Rose", "Vietnam Rose", "Kumintang", "Sunflower", "Daisy"],
                                    ];
                                ?>
                                <label>Purok:</label>
                                <select name="txt_purok" class="form-control input-sm" required>
                                    <option value="">Select Purok</option>
                                    <?php
                                        if (array_key_exists($off_barangay, $puroks)) {
                                            foreach ($puroks[$off_barangay] as $purok) {
                                                echo "<option value=\"$purok\">$purok</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Birth Place:</label>
                                <input name="txt_bplace" class="form-control input-sm" type="text" placeholder="Birth Place" required/>
                            </div>
                            <div class="form-group">
                               <label class="control-label">Civil Status:</label>
                               <select name="txt_cstatus" class="form-control input-sm" required>
                                    <option value="" disabled selected>Select Civil Status</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Widowed">Widowed</option>
                               </select>
                            </div>
                            <div class="form-group">
                                <label>OR Number:</label>
                                <input name="txt_ornum" class="form-control input-sm" type="number" placeholder="OR Number" required/>
                            </div>
                            <div class="form-group">
                                <label>Amount:</label>
                                <input name="txt_amount" class="form-control input-sm" type="number" placeholder="Amount" required/>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default btn-sm" data-dismiss="modal" value="Cancel"/>
                    <input type="submit" class="btn btn-primary btn-sm" name="btn_add" value="Add"/>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    $(document).ready(function() {
        // Calculate age function
        $('#txt_bdate').change(function(){
            var dob = new Date($(this).val());
            var today = new Date();
            var age = today.getFullYear() - dob.getFullYear();
            var m = today.getMonth() - dob.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            $('#txt_age').val(age);
        });
    });
</script>