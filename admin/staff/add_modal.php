<!-- ========================= MODAL ======================= -->
<div id="addModal" class="modal fade">
    <form method="post" enctype="multipart/form-data">
        <div class="modal-dialog modal-sm" style="width:300px !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Manage Brgy</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Barangay Logo:</label>
                                <input name="logo" id="txt_image" class="form-control input-sm" type="file" accept=".jpg, .jpeg, .png" required/>
                                <small id="fileError" style="color: red; display: none;">File size is greater than 2mb or Invalid Format!</small>
                            </div>

                            <div class="form-group">
                                <label>Name:</label>
                                <select name="txt_name" class="form-control input-sm" required>
                                    <option selected="" disabled="">Select Barangay</option>
                                    <option value="Tabagak">Brgy.Tabagak</option>
                                    <option value="Bunakan">Brgy.Bunakan</option>
                                    <option value="Kodia">Brgy.Kodia</option>
                                    <option value="Talangnan">Brgy.Talangnan</option>
                                    <option value="Poblacion">Brgy.Poblacion</option>
                                    <option value="Maalat">Brgy.Maalat</option>
                                    <option value="Pili">Brgy.Pili</option>
                                    <option value="Kaongkod">Brgy.Kaongkod</option>
                                    <option value="Mancilang">Brgy.Mancilang</option>
                                    <option value="Kangwayan">Brgy.Kangwayan</option>
                                    <option value="Tugas">Brgy.Tugas</option>
                                    <option value="Malbago">Brgy.Malbago</option>
                                    <option value="Tarong">Brgy.Tarong</option>
                                    <option value="San Agustin">Brgy.San Agustin</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Username:</label>
                                <input name="txt_uname" class="form-control input-sm" id="username" type="text" placeholder="Username" required
                                pattern="^(?!\s)(?!.*<script>)(?!.*<\/script>).*[\w\s]*$" 
                                title="Spaces and <script></script> tags are not allowed."/>
                                <label id="user_msg" class="text-danger"></label>
                            </div>

                            <div class="form-group">
                                <label>Email:</label>
                                <input name="txt_email" class="form-control input-sm" type="email" placeholder="Ex: juan@sample.com" required
                                pattern="^(?!\s)(?!.*<script>)(?!.*<\/script>).*[\w\s]*$" 
                                title="Spaces and <script></script> tags are not allowed."/>
                            </div>

                            <div class="form-group">
                                <label>Password:</label>
                                <div class="input-group">
                                    <input name="txt_pass" class="form-control input-sm" id="txt_pass" type="password" placeholder="•••••••••••" required 
                                        pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{10,}$"
                                        title="Password must be at least 10 characters long, contain at least one uppercase letter, one number, and one special character." 
                                        oninput="validatePassword(); showChecklist()" />
                                    <span class="input-group-addon eye-icon" id="togglePassword1" onclick="togglePassword('txt_pass', 'togglePassword1')">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="password-checklist" style="display: none;">
                                <h5>Password Requirements:</h5>
                                <div id="length" class="invalid" style="display: none;">❌ At least 10 characters</div>
                                <div id="uppercase" class="invalid" style="display: none;">❌ At least one uppercase letter</div>
                                <div id="number" class="invalid" style="display: none;">❌ At least one number</div>
                                <div id="special" class="invalid" style="display: none;">❌ At least one special character (!@#$%^&*)</div>
                            </div>
                            <div class="form-group">
                                <label>Confirm Password:</label>
                                <div class="input-group">
                                    <input name="txt_compass" class="form-control input-sm" type="password" id="txt_compass" placeholder ="•••••••••••" required 
                                        pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{10,}$" 
                                        title="Password must be at least 10 characters long, contain at least one uppercase letter, one number, and one special character." 
                                        oninput="validatePassword(); showChecklist()" />
                                    <span class="input-group-addon eye-icon" id="togglePassword2" onclick="togglePassword('txt_compass', 'togglePassword2')">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </span>
                                </div>
                            </div>
                            <div id="password_error" class="text-danger"></div> <!-- Error message -->
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <input type="button" class="btn btn-default btn-sm" data-dismiss="modal" value="Cancel"/>
                    <input type="submit" class="btn btn-primary btn-sm" name="btn_add" id="btn_add" value="Add" onclick="validateAndSubmit(event)"/>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function validatePassword() {
    const password = document.getElementById('txt_pass').value;
    const lengthCheck = document.getElementById('length');
    const uppercaseCheck = document.getElementById('uppercase');
    const numberCheck = document.getElementById('number');
    const specialCheck = document.getElementById('special');

    // Check length
    if (password.length >= 10) {
        lengthCheck.classList.remove('invalid');
        lengthCheck.classList.add('valid');
        lengthCheck.innerHTML = '✔️ At least 10 characters';
        lengthCheck.style.display = 'block';
    } else {
        lengthCheck.classList.remove('valid');
        lengthCheck.classList.add('invalid');
        lengthCheck.innerHTML = '❌ At least 10 characters';
        lengthCheck.style.display = 'block';
    }

    // Check for uppercase letter
    if (/[A-Z]/.test(password)) {
        uppercaseCheck.classList.remove('invalid');
        uppercaseCheck.classList.add('valid');
        uppercaseCheck.innerHTML = '✔️ At least one uppercase letter';
        uppercaseCheck.style.display = 'block';
    } else {
        uppercaseCheck.classList.remove('valid');
        uppercaseCheck.classList.add('invalid');
        uppercaseCheck.innerHTML = '❌ At least one uppercase letter';
        uppercaseCheck.style.display = 'block';
    }

    // Check for number
    if (/\d/.test(password)) {
        numberCheck.classList.remove('invalid');
        numberCheck.classList.add('valid');
        numberCheck.innerHTML = '✔️ At least one number';
        numberCheck.style.display = 'block';
    } else {
        numberCheck.classList.remove('valid');
        numberCheck.classList.add('invalid');
        numberCheck.innerHTML = '❌ At least one number';
        numberCheck.style.display = 'block';
    }

    // Check for special character
    if (/[!@#$%^&*]/.test(password)) {
        specialCheck.classList.remove('invalid');
        specialCheck.classList.add('valid');
        specialCheck.innerHTML = '✔️ At least one special character';
        specialCheck.style.display = 'block';
    } else {
        specialCheck.classList.remove('valid');
        specialCheck.classList.add('invalid');
        specialCheck.innerHTML = '❌ At least one special character';
        specialCheck.style.display = 'block';
    }
}

function showChecklist() {
    const checklist = document.querySelector('.password-checklist');
    checklist.style.display = 'block';
}
</script>