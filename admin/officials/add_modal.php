<!-- ========================= MODAL ======================= -->
            <div id="addOfficialModal" class="modal fade">
            <form method="post" enctype="multipart/form-data">
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
                                        <option value="Mayor">Mayor</option>
                                        <option value="Vice Mayor">Vice Mayor</option>
                                        <option value="Hon">Hon</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Name: <span style="color:gray; font-size: 10px;">(Firstname Middlename, Lastname)</span></label>
                                    <input name="txt_cname" class="form-control input-sm" type="text" placeholder="Firstname Middlename, Lastname" required/>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Image:</label>
                                    <input type="file" name="image" class="form-control input-sm" required>
                                </div>
                                <div class="form-group">
                                    <label>Contact #:</label>
                                    <input name="txt_contact" id="txt_contact" class="form-control input-sm" type="text" placeholder="Contact #" maxlength="11" pattern="^\d{11}$" required />
                                </div>
                                <div class="form-group">
                                    <label>Address:</label>
                                    <input name="txt_address" class="form-control input-sm" type="text" placeholder="Ex.Talangnan, Madridejos, Cebu" required/>
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
                        <input type="submit" class="btn btn-primary btn-sm" name="btn_add" value="Add Officials"/>
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
</script>