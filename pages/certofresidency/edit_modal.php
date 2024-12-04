<?php echo '<div id="editModal'.$row['pid'].'" class="modal fade">
<form method="post">
  <div class="modal-dialog modal-sm" style="width:300px !important;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Edit Certificate</h4>
        </div>
        <div class="modal-body">';
                $edit_query = mysqli_query($con,"SELECT * from tblrecidency where id = '".$row['pid']."' ");
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
                    <input name="txt_edit_resident" class="form-control input-sm" type="text" value="'.$row['Name'].'"/>
                </div>
                <div class="form-group">
                    <label>Purpose : </label>
                    <input name="txt_edit_purpose" class="form-control input-sm" type="text" value="'.$row['purpose'].'" />
                </div>
                <div class="form-group">
                    <label class="control-label">Age:</label>
                    <input name="txt_edit_age" id="txt_edit_age" class="form-control input-sm" type="text" value="'.$age.'" readonly/>
                    <label class="control-label" style="margin-top:10px;">Birthdate:</label>
                    <input name="txt_edit_bdate" id="txt_edit_bdate" class="form-control input-sm" type="date" value="'.$row['bdate'].'" onchange="calculateAge()" min="1924-01-01" max="'.date('Y-m-d').'"/>
                </div>
                <div class="form-group">
                    <label>Purok: </label>
                    <input name="txt_edit_purok" class="form-control input-sm" type="text" value="'.$row['purok'].'" />
                </div>
                <div class="form-group">
                    <label class="control-label">Civil Status:</label>
                    <select name="txt_edit_cstatus" class="form-control input-sm">
                        <option value="Single" '.($row['civilstatus'] == 'Single' ? 'selected' : '').'>Single</option>
                        <option value="Married" '.($row['civilstatus'] == 'Married' ? 'selected' : '').'>Married</option>
                        <option value="Widowed" '.($row['civilstatus'] == 'Widowed' ? 'selected' : '').'>Widowed</option>
                    </select>
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
</div>';?>

<script>
function calculateAge() {
    var dob = new Date(document.getElementById('txt_edit_bdate').value);
    var today = new Date();
    var age = today.getFullYear() - dob.getFullYear();
    var m = today.getMonth() - dob.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
        age--;
    }
    document.getElementById('txt_edit_age').value = age;
}

// Set minimum date to January 1, 2024 and disable future years
document.getElementById('txt_edit_bdate').setAttribute('min', '1924-01-01');
document.getElementById('txt_edit_bdate').setAttribute('max', new Date().toISOString().split('T')[0]);
</script>