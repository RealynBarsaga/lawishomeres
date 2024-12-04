<?php
echo '<div id="editModal'.$row['pid'].'" class="modal fade">
<form method="post">
  <div class="modal-dialog modal-sm" style="width:300px !important;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Edit Clearance</h4>
        </div>
        <div class="modal-body">';

        $edit_query = mysqli_query($con,"SELECT * from tblclearance where id = '".$row['pid']."' ");
        $row = mysqli_fetch_array($edit_query);

         // Calculate age based on birthdate
        $birthdate = new DateTime($row['bdate']);
        $today = new DateTime();
        $age = $today->diff($birthdate)->y;

        echo '
        <div class="row">
            <div class="col-md-12">
                <input type="hidden" value="'.$row['id'].'" name="hidden_id" id="hidden_id"/>

                <div class="form-group">
                    <label>Resident Name: </label>
                    <input name="txt_edit_residentname" class="form-control input-sm" type="text" pattern="^(?!\s*$)[A-Za-z\s.,]+$" value="'.$row['Name'].'"/>
                </div>

                <div class="form-group">
                    <label>Clearance #: </label>
                    <input name="txt_edit_cnum" class="form-control input-sm" type="text" value="'.$row['clearanceNo'].'" readonly/>
                </div>

                <div class="form-group">
                    <label>Purpose : </label>
                    <input name="txt_edit_purpose" class="form-control input-sm" type="text" pattern="^(?!\s*$)[A-Za-z\s.,]+$" value="'.$row['purpose'].'" />
                </div>

                <div class="form-group">
                    <label class="control-label">Age:</label>
                    <input name="txt_edit_age" id="txt_edit_age" class="form-control input-sm" type="text" value="'.$age.'" readonly/>
                    <label class="control-label" style="margin-top:10px;">Birthdate:</label>
                    <input name="txt_edit_bdate" id="txt_edit_bdate" class="form-control input-sm" type="date" value="'.$row['bdate'].'" onchange="calculateAge()" min="1924-01-01" max="'.date('Y-m-d').'"/>
                </div>

                <div class="form-group">
                    <label class="control-label">Purok:</label>
                    <input name="txt_edit_purok" class="form-control input-sm input-size" type="text" pattern="^(?!\s*$)[A-Za-z\s.,]+$" value="'.$row['purok'].'"/>
                </div>

                <div class="form-group">
                    <label class="control-label">Birth Place:</label>
                    <input name="txt_edit_bplace" class="form-control input-sm input-size" pattern="^(?!\s*$)[A-Za-z\s.,]+$" type="text" value="'.$row['bplace'].'"/>
                </div>

                <div class="form-group">
                    <label class="control-label">Civil Status:</label>
                    <select name="txt_edit_cstatus" class="form-control input-sm">
                        <option value="Single" '.($row['civilstatus'] == 'Single' ? 'selected' : '').'>Single</option>
                        <option value="Married" '.($row['civilstatus'] == 'Married' ? 'selected' : '').'>Married</option>
                        <option value="Widowed" '.($row['civilstatus'] == 'Widowed' ? 'selected' : '').'>Widowed</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>OR Number : </label>
                    <input name="txt_edit_ornum" class="form-control input-sm" type="text" pattern="^(?!\s*$)[A-Za-z\s.,]+$" value="'.$row['orNo'].'" />
                </div>
                
                <div class="form-group">
                    <label>Amount : </label>
                    <input name="txt_edit_amount" class="form-control input-sm" type="text" pattern="^(?!\s*$)[A-Za-z\s.,]+$" value="'.$row['samount'].'" />
                </div>

            </div>
        </div>
        </div>
        <div class="modal-footer">
            <input type="button" class="btn btn-default btn-sm" data-dismiss="modal" value="Cancel"/>
            <input type="submit" class="btn btn-primary btn-sm" name="btn_save" value="Save"/>
        </div>
    </div>
  </div>
</form>
</div>';
?>

<script>
function calculateAge() {
    var dob = new Date(document.getElementById('txt_edit_bdate').value);
    var today = new Date();
    var age = today.getFullYear() - dob.getFullYear();
    var m = today.getMonth() - dob.getMonth();

    // Check if the birthday has already occurred this year
    if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
        age--;
    }

    document.getElementById('txt_edit_age').value = age;
}

// Ensure the age is calculated when the page loads if the birthdate is already set
document.addEventListener('DOMContentLoaded', function() {
    calculateAge();
});

// Set minimum date to January 1, 1924 and disable future years
var birthdateInput = document.getElementById('txt_edit_bdate');
birthdateInput.setAttribute('min', '1924-01-01');
birthdateInput.setAttribute('max', new Date().toISOString().split('T')[0]);



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
</script>
