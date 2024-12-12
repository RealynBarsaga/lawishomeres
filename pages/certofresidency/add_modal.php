<!-- ========================= MODAL ======================= -->
<div id="addModal" class="modal fade">
<form method="post">
  <div class="modal-dialog modal-sm" style="width:300px !important;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Manage Certificate Of Residency</h4>
        </div>
        <div class="modal-body">
            
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Resident Name:</label>
                        <input name="txt_name" class="form-control input-sm" type="text" placeholder="Name" required
                        pattern="^(?!\s)(?!.*<script>)(?!.*<\/script>).*[\w\s]*$" 
                        title="Spaces and <script></script> tags are not allowed."/>
                    </div>
                    <div class="form-group">
                        <label>Purpose:</label>
                        <input name="txt_purpose" class="form-control input-sm" type="text" placeholder="Purpose" required
                        pattern="^(?!\s)(?!.*<script>)(?!.*<\/script>).*[\w\s]*$" 
                        title="Spaces and <script></script> tags are not allowed."/>
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
                            $off_barangay = $_SESSION['barangay'];
                    
                            // Purok options for each barangay
                            $puroks = [
                                "Tabagak" => ["Lamon-Lamon", "Tangigue", "Lawihan", "Lower-Bangus", "Upper-Bangus"],
                                "Bunakan" => ["Bilabid", "Helinggero", "Kamaisan", "Kalubian", "Samonite"],
                                "Kodia" => ["Mahugany", "Tugas", "Lumboy", "Dita", "Magtalisay", "Kawayan", "Bakhaw", "La Kodia Village"],
                                "Talangnan" => ["Gawot", "Lawihan", "Danggit", "Helinguero", "Tangigue", "Nukos", "Gusaw", "Tabagak"],
                                /*  "Poblacion" => ["",], */
                                "Maalat" => ["Neem Tree", "Talisay", "Kabakhawan", "Mahogany", "Gmelina"],
                                "Pili" => ["Malinawon", "Mahigugmaon", "Matinabangun", "Maabtikon", "Malipayon", "Mauswagon"],
                                "Kaongkod" => ["Kalubihan", "Kamanggahan", "Ipil-Ipil", "Pukotan 1", "Pukotan 2", "Bugsayan 1", "Bugsayan 2", "Kasagingan 1", "Kasagingan 2", "Maisan 1", "Maisan 2", "Maisan 3"],
                                /* "Mancilang" => ["Purok", "Puroks"], */
                                "Kangwayan" => ["Nangka", "Mangga", "Lutak", "Kulo"],
                                "Tugas" => ["Bombil A", "Bombil B", "Roses A", "Roses B", "Gomamela A", "Gomamela B", "Kumintang A", "Kumintang B", "Santan", "GK-Tugas"],
                                "Malbago" => ["Bolinao", "Tulingan", "Tangigue", "Nukos", "Danggit", "Bangus"],
                                "Tarong" => ["Orchids", "Gumamela 1", "Gumamela 2", "Santan 1", "Santan 2", "Rose 1", "Rose 2", "Vietnam Rose", "Kumintang 1", "Kumintang 2", "Sunflower", "Daisy"],
                                "San Agustin" => ["Proper", "Langob", "Tagaytay", "Mahayahay", "Baybay", "Desamperado"],
                                // Add purok options for other barangays
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
                        <div class="form-group">
                           <label class="control-label">Civil Status:</label>
                           <select name="txt_cstatus" class="form-control input-sm" required>
                                <option value="" disabled selected>Select Civil Status</option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Widowed">Widowed</option>
                           </select>
                        </div>
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