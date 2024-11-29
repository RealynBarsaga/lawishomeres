<style>
    .eye-icon {
        cursor: pointer;
    }
</style>

<?php echo '<div id="editModal'.$row['id'].'" class="modal fade">
<form method="post" enctype="multipart/form-data">
  <div class="modal-dialog modal-sm" style="width:300px !important;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Edit Brgy Info</h4>
        </div>
        <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <input type="hidden" value="'.$row['id'].'" name="hidden_id" id="hidden_id"/>
                <div class="form-group">
                    <label>Barangay Logo:</label>
                    <input name="logo" class="form-control input-sm" type="file"/>
                </div>
                <div class="form-group">
                    <label>Barangay Info:</label>
                    <input name="txt_edit_name" class="form-control input-sm" type="text" value="'.$row['name'].'"/>
                </div>
                <div class="form-group">
                    <label>Username: </label>
                    <input name="txt_edit_uname" class="form-control input-sm" type="text" value="'.$row['username'].'" />
                </div>
                <div class="form-group">
                    <label>Email:</label>
                    <input name="txt_edit_email" class="form-control input-sm" type="email" placeholder="Ex: juan@sample.com"/>
                </div>
                <div class="form-group">
                    <label>Password: </label>
                    <div class="input-group">
                        <input id="password" name="txt_edit_pass" class="form-control input-sm" type="password" placeholder="Password"/>
                        <span class="input-group-addon eye-icon" onclick="togglePasswordVisibility(\'password\')">
                            <i class="fa fa-eye" id="eye-password"></i>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label>Confirm Password: </label>
                    <div class="input-group">
                        <input id="confirm-password" name="txt_confirm_pass" class="form-control input-sm" type="password" placeholder="Confirm Password"/>
                        <span class="input-group-addon eye-icon" onclick="togglePasswordVisibility(\'confirm-password\')">
                            <i class="fa fa-eye" id="eye-confirm-password"></i>
                        </span>
                    </div>
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
</div>';?>

<script>
    function togglePasswordVisibility(inputId) {
        const inputField = document.getElementById(inputId);
        const eyeIcon = inputId === 'password' ? document.getElementById('eye-password') : document.getElementById('eye-confirm-password');

        if (inputField.type === "password") {
            inputField.type = "text";
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            inputField.type = "password";
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
</script>