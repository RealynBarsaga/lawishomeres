<!-- ========================= MODAL ======================= -->
            <div id="addOfficialModal" class="modal fade">
            <form method="post" enctype="multipart/form-data" onsubmit="validateAndSubmit(event)">
              <div class="modal-dialog modal-sm" style="width:300px !important;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Manage Officials</h4>
                    </div>
                    <div class="modal-body">
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Positions:</label>
                                    <select name="ddl_pos" class="form-control input-sm">
                                        <option selected="" disabled="">-- Select Positions -- </option>
                                        <option value="Captain">Barangay Captain</option>
                                        <option value="Kagawad">Barangay Kagawad</option>
                                        <option value="SK">SK</option>
                                        <option value="Secretary">Barangay Secretary</option>
                                        <option value="Treasurer">Barangay Treasurer</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Name: <span style="color:gray; font-size: 10px;">(Firstname Middlename, Lastname)</span></label>
                                    <input name="txt_cname" class="form-control input-sm" type="text" placeholder="Firstname Middlename, Lastname" pattern="^(?!\s*$)[A-Za-z\s.,]+$" required/>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Image:</label>
                                    <input type="file" name="image" id="txt_image" class="form-control input-sm" accept=".jpg, .jpeg, .png" required>
                                    <small id="fileError" style="color: red; display: none;">File size is greater than 2mb or Invalid Format !</small>
                                </div>
                                <div class="form-group">
                                    <label>Contact #:</label>
                                    <input name="txt_contact" id="txt_contact" class="form-control input-sm" type="text" placeholder="Contact #" maxlength="11" pattern="^09\d{9}$"
                                    title="Contact number should start with '09' and be exactly 11 digits." required oninput="this.value = this.value.replace(/[^0-9]/g, '');"/>
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
                                ?>
                                <div class="form-group">
                                    <label>Address:</label>
                                    <select name="txt_address" class="form-control input-sm" required>
                                        <option value="" disabled selected>Select Address</option>
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

                                <div class="form-group">
                                    <label>Start Term:</label>
                                    <input id="txt_sterm" name="txt_sterm" class="form-control input-sm" type="date" placeholder="Start Term" required/>
                                </div>
                                <div class="form-group">
                                    <label>End Term:</label>
                                    <input name="txt_eterm" class="form-control input-sm" type="date" placeholder="End Term" required/>
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


<script type="text/javascript">
    $(document).ready(function(){
        $('input[name="txt_sterm"]').change(function(){
            var startterm = document.getElementById("txt_sterm").value;
            console.log(startterm);
             document.getElementsByName("txt_eterm")[0].setAttribute('min', startterm);
        });
    });
    document.addEventListener('DOMContentLoaded', function () {
        const contactInput = document.getElementById('txt_contact');

        contactInput.addEventListener('input', function () {
            // Allow only numbers
            this.value = this.value.replace(/[^0-9]/g, '');

            // Check for exactly 11 digits
            if (this.value.length !== 11) {
                this.setCustomValidity('Please enter exactly 11 digits.');
            } else {
                this.setCustomValidity('');
            }
        });
    });

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
    
        if (!isValid) {
            event.preventDefault(); // Prevent form submission if there are invalid fields
        }
    });

    function validateAndSubmit(event) {
    // Get the file input and error message elements
    const fileInput = document.getElementById('txt_image');
    const errorMessage = document.getElementById('fileError');
    const file = fileInput.files[0];
    const allowedExtensions = /\.(jpg|jpeg|png)$/i;

    // Reset error message display
    errorMessage.style.display = 'none';

    if (file) {
        // Check file size
        if (file.size > 2 * 1024 * 1024) {
            errorMessage.style.display = 'block';
            event.preventDefault(); // Prevent form submission
            return;
        }

        // Check file extension
        if (!allowedExtensions.test(file.name)) {
            errorMessage.style.display = 'block';
            event.preventDefault(); // Prevent form submission
            return;
        }

        // Check for valid filename (preventing malicious file names like 'index.php.png')
        const invalidNamePattern = /[^a-zA-Z0-9_\-\.]/;
        if (invalidNamePattern.test(file.name.replace(/\.(jpg|jpeg|png)$/i, ''))) {
            errorMessage.style.display = 'block';
            event.preventDefault(); // Prevent form submission
            return;
        }
    }
}

</script>