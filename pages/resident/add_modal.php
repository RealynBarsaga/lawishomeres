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
                                    <label class="control-label">Name: <span style="color:gray; font-size: 10px;">(Lastname Firstname, Middlename)</span></label><br>
                                    <div class="col-sm-4">
                                        <input name="txt_lname" class="form-control input-sm" type="text" pattern="^(?!\s*$)[A-Za-z\s]+$" placeholder="Lastname" required/>
                                    </div>
                                    <div class="col-sm-4">
                                        <input name="txt_fname" class="form-control input-sm" type="text" pattern="^(?!\s*$)[A-Za-z\s]+$" placeholder="Firstname" required/>
                                    </div>
                                    <div class="col-sm-4">
                                        <input name="txt_mname" class="form-control input-sm" type="text" pattern="^(?!\s*$)[A-Za-z\s]+$" placeholder="Middlename" required/>
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
                                    "Maalat" => ["Neem Tree", "Talisay", "Kabakhawan", "Mahogany", "Gmelina"],
                                    "Pili" => ["Malinawon", "Mahigugmaon", "Matinabangun", "Maabtikon", "Malipayon", "Mauswagon"],
                                    "Tarong" => ["Orchids", "Gumamela", "Santan", "Rose", "Vietnam Rose", "Kumintang", "Sunflower", "Daisy"],
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
                                    <input name="txt_householdnum" id="txt_householdnum" class="form-control input-sm" type="number" min="1" placeholder="Household #" style="width: 419px;" required/>
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
                                    <input name="txt_bplace" class="form-control input-sm" type="text" pattern="^(?!\s*$)[A-Za-z\s]+$" placeholder="Birthplace" required/>
                                </div>

                                <!-- Nationality -->
                                <div class="form-group">
                                    <label class="control-label">Nationality:</label>
                                    <input name="txt_national" class="form-control input-sm" type="text" pattern="^(?!\s*$)[A-Za-z\s]+$" placeholder="Nationality" required/>
                                </div>

                                <!-- Religion -->
                                <div class="form-group">
                                    <label class="control-label">Religion:</label>
                                    <input name="txt_religion" class="form-control input-sm" type="text" pattern="^(?!\s*$)[A-Za-z\s]+$" placeholder="Religion" required/>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Role:</label>
                                    <select id="roleSelect" name="txt_role" class="form-control input-sm" required onchange="toggleHeadOfFamily()">
                                        <option value="Head of Family">Head of Family</option>
                                        <option value="Members">Members</option>
                                    </select>
                                </div>

                                <!-- Head of Family Dropdown -->
                                <div class="form-group" id="headOfFamilySelect" style="display: none;">
                                    <label class="control-label">Select Head of Family:</label>
                                    <select name="txt_head_of_family" class="form-control input-sm" onchange="getHouseholdNumber()">
                                        <option value="" disabled selected>Select a head of family</option>
                                        <?php
                                            $su = mysqli_query($con, "SELECT * FROM tbltabagak WHERE role = 'Head of Family'");
                                            while ($row = mysqli_fetch_assoc($su)) {
                                                $fullName = htmlspecialchars($row['lname'] . ', ' . $row['fname'] . ' ' . $row['mname']);
                                                echo '<option value="' . $row['id'] . '" data-household="' . $row['householdnum'] . '">' . $fullName . '</option>';
                                            }
                                        ?>
                                    </select>
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
                                    <input name="txt_faddress" class="form-control input-sm" type="text" pattern="^(?!\s*$)[A-Za-z\s]+$" placeholder="Former Address" required/>
                                </div>

                                <!-- Image -->
                                <div class="form-group">
                                    <label class="control-label">Image:</label>
                                    <input name="txt_image" class="form-control input-sm" type="file" accept=".jpg, .jpeg, .png, .bmp" required/>
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

// Toggle Head of Family dropdown based on role selection
function toggleHeadOfFamily() {
    var roleSelect = document.getElementById('roleSelect');
    var headOfFamilySelect = document.getElementById('headOfFamilySelect');
    
    if (roleSelect.value === 'Members') {
        headOfFamilySelect.style.display = 'block';
        householdNumField.readOnly = true;  // Make the Household # readonly if role is 'Members'
    } else {
        headOfFamilySelect.style.display = 'none';
        document.getElementById('txt_householdnum').value = ''; // Clear household number if Head of Family is not selected
    }
}

// Update Household Number based on selected Head of Family
function getHouseholdNumber() {
    var headOfFamilySelect = document.querySelector('[name="txt_head_of_family"]');
    var selectedOption = headOfFamilySelect.options[headOfFamilySelect.selectedIndex];
    var householdNumber = selectedOption.getAttribute('data-household');
    document.getElementById('txt_householdnum').value = householdNumber;
}
</script>
<script>
document.querySelector('form').addEventListener('submit', function(event) {
    // Check each required input field for empty or space-only values
    const requiredFields = document.querySelectorAll('input[required], select[required]');
    let isValid = true;

    requiredFields.forEach(function(field) {
        const value = field.value.trim(); // Remove leading/trailing spaces
        if (value === '') {
            // Show a custom alert or display the error message
            alert(`Please fill out the required field: ${field.placeholder || field.name}`);
            isValid = false;
            field.focus(); // Focus on the first empty required field
        }
    });

    // Check image file size
    const imageInput = document.querySelector('input[name="txt_image"]');
    if (imageInput.files.length > 0) {
        const file = imageInput.files[0];
        const maxFileSize = 2 * 1024 * 1024; // 2MB in bytes
        if (file.size > maxFileSize) {
            alert("The selected image file exceeds 2MB. Please upload a smaller file.");
            isValid = false;
            imageInput.focus(); // Focus on the file input
        }
    }

    if (!isValid) {
        event.preventDefault(); // Prevent form submission if validation fails
    }
});
</script>