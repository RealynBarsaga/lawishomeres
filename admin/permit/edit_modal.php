<?php echo '<div id="editModal'.$row['id'].'" class="modal fade">
<form method="post">
  <div class="modal-dialog modal-sm" style="width:597px !important;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Edit Permit</h4>
        </div>
        <div class="modal-body">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <input type="hidden" value="'.$row['id'].'" name="hidden_id" id="hidden_id"/>
                <div class="form-group">
                    <label>Name:</label>
                    <input name="txt_edit_name" class="form-control input-sm" type="text" value="'.$row['name'].'" 
                    pattern="^(?!\s)(?!.*<script>)(?!.*<\/script>).*[\w\s]*$" 
                    title="Spaces and <script></script> tags are not allowed."/>
                </div>
                <div class="form-group">
                    <label>Business Name:</label>
                    <input name="txt_edit_busname" class="form-control input-sm" type="text" value="'.$row['businessName'].'" 
                    pattern="^(?!\s)(?!.*<script>)(?!.*<\/script>).*[\w\s]*$" 
                    title="Spaces and <script></script> tags are not allowed."/>
                </div>
                <div class="form-group">
                    <label>Business Address:</label>
                    <input name="txt_edit_busadd" class="form-control input-sm" type="text" value="'.$row['businessAddress'].'" 
                    pattern="^(?!\s)(?!.*<script>)(?!.*<\/script>).*[\w\s]*$" 
                    title="Spaces and <script></script> tags are not allowed."/>
                </div>
                <div class="form-group">
                    <label>Type of Business:</label>
                    <input name="ddl_edit_tob" class="form-control input-sm" type="text" value="'.$row['typeOfBusiness'].'" 
                    pattern="^(?!\s)(?!.*<script>)(?!.*<\/script>).*[\w\s]*$" 
                    title="Spaces and <script></script> tags are not allowed."/>
                </div>
                <div class="form-group">
                    <label>Permit No:</label>
                    <input name="txt_edit_ornum" class="form-control input-sm" type="text" value="'.$row['orNo'].'" readonly/>
                </div>
                <div class="form-group">
                    <label>Amount:</label>
                    <input name="txt_edit_amount" class="form-control input-sm" type="text" value="'.$row['samount'].'" />
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>Type of Application:</label>
                    <input name="txt_edit_typeofapp" class="form-control input-sm" type="text" value="'.$row['typeofapplication'].'" 
                    pattern="^(?!\s)(?!.*<script>)(?!.*<\/script>).*[\w\s]*$" 
                    title="Spaces and <script></script> tags are not allowed."/>
                </div>
                <div class="form-group">
                    <label>Line of Business:</label>
                    <input name="txt_edit_lineofbus" class="form-control input-sm" type="text" value="'.$row['lineofbussiness'].'" 
                    pattern="^(?!\s)(?!.*<script>)(?!.*<\/script>).*[\w\s]*$" 
                    title="Spaces and <script></script> tags are not allowed."/>
                </div>
                <div class="form-group">
                    <label>Payment Mode:</label>
                    <input name="txt_edit_paymode" class="form-control input-sm" type="text" value="'.$row['paymentmode'].'" 
                    pattern="^(?!\s)(?!.*<script>)(?!.*<\/script>).*[\w\s]*$" 
                    title="Spaces and <script></script> tags are not allowed."/>
                </div>

                <div class="form-group">
                    <label>Official Receipt No:</label>
                    <input name="txt_edit_busidno" class="form-control input-sm" type="text" value="'.$row['offreceiptno'].'" readonly/>
                </div>
                <div class="form-group">
                    <label>Business ID No:</label>
                    <input name="txt_edit_offrecno" class="form-control input-sm" type="text" value="'.$row['bussinessidno'].'" readonly/>
                </div>
                <div class="form-group">
                    <label>OR Date:</label>
                    <input name="txt_edit_ordate" class="form-control input-sm" type="text" value="'.$row['ordate'].'" readonly/>
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