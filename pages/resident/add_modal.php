<!-- ========================= MODAL ======================= -->
<div id="addCourseModal" class="modal fade">
    <form class="form-horizontal" method="post" enctype="multipart/form-data">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Manage Residents</h4>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6 col-sm-12">

                                <!-- Name -->
                                <div class="form-group">
                                    <label class="control-label">Name:</label><br>
                                    <div class="col-sm-4">
                                        <input name="txt_lname" class="form-control input-sm" type="text" placeholder="Lastname" required/>
                                    </div>
                                    <div class="col-sm-4">
                                        <input name="txt_fname" class="form-control input-sm" type="text" placeholder="Firstname" required/>
                                    </div>
                                    <div class="col-sm-4">
                                        <input name="txt_mname" class="form-control input-sm" type="text" placeholder="Middlename" required/>
                                    </div>
                                </div>

                                <!-- Age -->
                                <div class="form-group">
                                    <label class="control-label">Age:</label>
                                    <input name="txt_age" id="txt_age" class="form-control input-sm" type="text" placeholder="Age" readonly style="width: 419px;" required/>
                                </div>

                                <!-- Birthdate -->
                                <div class="form-group">
                                    <label class="control-label">Birthdate:</label>
                                    <input name="txt_bdate" id="txt_bdate" class="form-control input-sm" type="date" placeholder="Birthdate" max="<?php echo date('Y-m-d'); ?>" style="width: 419px;" required/>
                                </div>

                                <!-- Barangay -->
                                <?php
                                // Assuming the barangay of the logged-in user is stored in the session
                                $off_barangay = $_SESSION['barangay']; // Change 'barangay' to whatever key you use
                                
                                // Available barangay options
                                $barangays = [
                                    "Tabagak", "Bunakan", "Kodia", "Talangnan", "Poblacion", "Maalat", 
                                    "Pili", "Kaongkod", "Mancilang", "Kangwayan", "Tugas", "Malbago", 
                                    "Tarong", "San Agustin"
                                ];

                                // Purok options for each barangay
                                $puroks = [
                                    "Tabagak" => ["Lamon-Lamon", "Tangigue", "Lawihan", "Lower-Bangus", "Upper-Bangus"],
                                    "Bunakan" => ["Bilabid", "Helinggero", "Kamaisan", "Kalubian", "Samonite"],
                                    /* "Kodia" => ["Purok X", "Purok Y", "Purok Z"], */
                                    /* "Talangnan" => ["",], */
                                    /*  "Poblacion" => ["",], */
                                    "Maalat" => ["Neem Tree", "Talisay", "Kabakhawan", "Mahogany", "Gmelina"],
                                    "Pili" => ["Malinawon", "Mahigugmaon", "Matinabangun", "Maabtikon", "Malipayon", "Mauswagon"],
                                    /* "Kaongkod" => ["Purok", "Puroks"], */
                                    /* "Mancilang" => ["Purok", "Puroks"], */
                                    /* "Kangwayan" => ["Purok", "Puroks"], */
                                    /* "Kangwayan" => ["Purok", "Puroks"], */
                                    /* "Tugas" => ["Purok", "Puroks"], */
                                    /* "Malbago" => ["Purok", "Puroks"], */
                                    "Tarong" => ["Orchids", "Gumamela", "Santan", "Rose", "Vietnam Rose", "Kumintang", "Sunflower", "Daisy"],
                                    /* "San Agustin" => ["Purok", "Puroks"], */
                                    // Add purok options for other barangays
                                ];
                                ?>
                                
                                <div class="form-group">
                                    <label class="control-label">Barangay:</label>
                                    <select name="txt_brgy" id="barangay_select" class="form-control input-sm" style="width: 419px;" required>
                                        <option value="" disabled selected>Select Barangay</option>
                                        <?php foreach($barangays as $barangay): ?>
                                            <option value="<?= $barangay ?>"  
                                                style="<?= ($barangay == $off_barangay) ? 'color: #000000;' : 'color: gray;' ?>" 
                                                <?= ($barangay == $off_barangay) ? '' : 'disabled' ?>
                                                >
                                                <?= $barangay ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Purok -->
                                <div class="form-group">
                                    <label class="control-label">Purok:</label>
                                    <select name="txt_purok" id="purok_select" class="form-control input-sm" style="width: 419px;" required>
                                        <option value="" disabled selected>Select Purok</option>
                                        <!-- Purok options will be dynamically added here based on selected barangay -->
                                    </select>
                                </div>

                                <!-- Household # -->
                                <div class="form-group">
                                    <label class="control-label">Household #:</label>
                                    <input name="txt_householdnum" class="form-control input-sm" type="number" min="1" placeholder="Household #" style="width: 419px;" required/>
                                </div>
                               <!-- Civil Status -->
                               <div class="form-group">
                                   <label class="control-label">Civil Status:</label>
                                   <select name="txt_cstatus" class="form-control input-sm" style="width: 419px;" required>
                                        <option value="" disabled selected>Select Civil Status</option>
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                        <option value="Widowed">Widowed</option>
                                   </select>
                                </div>

                                <!-- Religion -->
                                <div class="form-group">
                                    <label class="control-label">Religion:</label>
                                    <input name="txt_religion" class="form-control input-sm" type="text" placeholder="Religion" style="width: 419px;" required/>
                                </div>

                                <!-- Land Ownership Status -->
                                <div class="form-group">
                                    <label class="control-label">Land Ownership Status:</label>
                                    <select name="ddl_los" class="form-control input-sm" style="width: 419px;" required>
                                        <option>Owned</option>
                                        <option>Landless</option>
                                        <option>Tenant</option>
                                        <option>Care Taker</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">

                                <!-- Gender -->
                                <div class="form-group">
                                    <label class="control-label">Gender:</label>
                                    <select name="ddl_gender" class="form-control input-sm" required>
                                        <option selected="" disabled="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>

                                <!-- Birthplace -->
                                <div class="form-group">
                                    <label class="control-label">Birthplace:</label>
                                    <input name="txt_bplace" class="form-control input-sm" type="text" placeholder="Birthplace" required/>
                                </div>

                                <!-- Total Household Member -->
                                <div class="form-group">
                                    <label class="control-label">Total Household Members:</label>
                                    <input name="txt_householdmem" class="form-control input-sm" type="number" min="1" placeholder="Total Household Members" required/>
                                </div>
                                <!-- Household Members Section -->
                               <!--  <h4 style="margin-left: -13px;font-weight: bold;">Household Members</h4>
                                <div id="members">
                                    <div>
                                        <input type="text" name="member_name[]" placeholder="Member Name" required style="margin-left: -13px;">
                                        <input type="number" name="member_age[]" placeholder="Member Age" required>
                                        <input type="text" name="member_relationship[]" placeholder="Relationship" required style="margin-left: -13px;margin-top: 10px;">
                                    </div>
                                </div>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="addMember()" style="margin-top: 10px;margin-left: -13px;background-color: #3c8dbc;border-color: #367fa9;color:white;">Add Another Member</button> -->
                                <!-- Nationality -->
                                <div class="form-group">
                                    <label class="control-label">Nationality:</label>
                                    <input name="txt_national" class="form-control input-sm" type="text" placeholder="Nationality" required/>
                                </div>

                                <!-- House Ownership Status -->
                                <div class="form-group">
                                    <label class="control-label">House Ownership Status:</label>
                                    <select name="ddl_hos" class="form-control input-sm" required>
                                        <option value="Own Home">Own Home</option>
                                        <option value="Rent">Rent</option>
                                        <option value="Live with Parents/Relatives">Live with Parents/Relatives</option>
                                    </select>
                                </div>

                                <!-- Lightning Facilities -->
                                <div class="form-group">
                                    <label class="control-label">Lightning Facilities:</label>
                                    <select name="txt_lightning" class="form-control input-sm" required>
                                        <option>Electric</option>
                                        <option>Lamp</option>
                                    </select>
                                </div>

                                <!-- Former Address -->
                                <div class="form-group">
                                    <label class="control-label">Former Address:</label>
                                    <input name="txt_faddress" class="form-control input-sm" type="text" placeholder="Former Address" required/>
                                </div>

                                <!-- Image -->
                                <div class="form-group">
                                    <label class="control-label">Image:</label>
                                    <input name="txt_image" class="form-control input-sm" type="file" required/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default btn-sm" data-dismiss="modal" value="Cancel"/>
                    <input type="submit" class="btn btn-primary btn-sm" name="btn_add" id="btn_add" value="Add"/>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
   /*  // Function to add household member fields
    function addMember() {
        var membersDiv = document.getElementById('members');
        var newMember = document.createElement('div');
        newMember.innerHTML = `
            <br>
            <input type="text" name="member_name[]" placeholder="Member Name" required style="margin-left: -13px;">
            <input type="number" name="member_age[]" placeholder="Member Age" required>
            <input type="text" name="member_relationship[]" placeholder="Relationship" required style="margin-left: -13px;margin-top: 10px;">
        `;
        membersDiv.appendChild(newMember);
    } */

    $(document).ready(function() {
 
        var timeOut = null; // this used for hold few seconds to made ajax request
 
        var loading_html = '<img src="../../img/ajax-loader.gif" style="height: 20px; width: 20px;"/>'; // just an loading image or we can put any texts here
 
        //when button is clicked
        $('#username').keyup(function(e){
 
            // when press the following key we need not to make any ajax request, you can customize it with your own way
            switch(e.keyCode)
            {
                //case 8:   //backspace
                case 9:     //tab
                case 13:    //enter
                case 16:    //shift
                case 17:    //ctrl
                case 18:    //alt
                case 19:    //pause/break
                case 20:    //caps lock
                case 27:    //escape
                case 33:    //page up
                case 34:    //page down
                case 35:    //end
                case 36:    //home
                case 37:    //left arrow
                case 38:    //up arrow
                case 39:    //right arrow
                case 40:    //down arrow
                case 45:    //insert
                //case 46:  //delete
                    return;
            }
            if (timeOut != null)
                clearTimeout(timeOut);
            timeOut = setTimeout(is_available, 500);  // delay delay ajax request for 1000 milliseconds
            $('#user_msg').html(loading_html); // adding the loading text or image
        });
  });
function is_available(){
    //get the username
    var username = $('#username').val();
 
    //make the ajax request to check is username available or not
    $.post("check_username.php", { username: username },
    function(result)
    {
        console.log(result);
        if(result != 0)
        {
            $('#user_msg').html('Not Available');
            document.getElementById("btn_add").disabled = true;
        }
        else
        {
            $('#user_msg').html('<span style="color:#006600;">Available</span>');
            document.getElementById("btn_add").disabled = false;
        }
    });
}
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
    // JavaScript to dynamically update the purok dropdown based on selected barangay
    const puroks = <?php echo json_encode($puroks); ?>; // Pass the PHP puroks array to JavaScript
    
    document.getElementById('barangay_select').addEventListener('change', function() {
        const selectedBarangay = this.value;
        const purokSelect = document.getElementById('purok_select');
        
        // Clear existing purok options
        purokSelect.innerHTML = '<option value="" disabled selected>Select Purok</option>';
    
        // Check if there are puroks for the selected barangay
        if (puroks[selectedBarangay]) {
            puroks[selectedBarangay].forEach(function(purok) {
                const option = document.createElement('option');
                option.value = purok;
                option.textContent = purok;
                purokSelect.appendChild(option);
            });
        }
    });
</script>
