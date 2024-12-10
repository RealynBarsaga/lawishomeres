<!-- ========================= MODAL ======================= -->
<div id="addModal" class="modal fade">
    <form method="post">
        <div class="modal-dialog modal-sm" style="width:300px !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Manage Household</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Household #:</label>
                                <input onkeyup="show_head()" id="txt_householdno" name="txt_householdno" class="form-control input-sm" type="number" placeholder="Household #" required/>
                            </div>
                            <div class="form-group">
                                <label>Head Of Family:</label>
                                <select id="txt_hof" name="txt_hof" class="form-control input-sm select2" style="width:100%" onchange="show_family_members()" required>
                                   <option disabled selected>-- Input Household # First --</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Family Members:</label>
                                <select id="txt_members" name="txt_members[]" class="form-control input-sm select2" style="width:100%" multiple="multiple" required>
                                   <option disabled selected>-- Select Family Members --</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Total Household Members:</label>
                                <input id="txt_totalmembers" name="txt_totalmembers" class="form-control input-sm" type="text" placeholder="Total Household Members" required readonly />
                            </div>
                            <div class="form-group">
                                <label>Barangay:</label>
                                <input id="txt_brgy" disabled name="txt_brgy" class="form-control input-sm" type="text" placeholder="Barangay" required/>
                            </div>
                            <div class="form-group">
                                <label>Purok:</label>
                                <input id="txt_purok" disabled name="txt_purok" class="form-control input-sm" type="text" placeholder="Purok" required/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default btn-sm" data-dismiss="modal" value="Cancel" />
                    <input type="submit" class="btn btn-primary btn-sm" name="btn_add" value="Add" />
                </div>
            </div>
        </div>
    </form>
</div>

<script> 
    // Assuming barangay information is passed in a hidden field or directly in JavaScript
    var loggedInBarangay = '<?= $_SESSION["barangay"] ?? ""; ?>'; // Pass PHP session variable to JS

    // Trigger when household number input changes
    function show_head() {
        var householdID = $('#txt_householdno').val();
        console.log('Household ID: ', householdID);  // Debugging
        if (householdID) {
            $.ajax({
                type: 'POST',
                url: 'household_dropdown.php',
                data: { 
                    hhold_id: householdID,
                    barangay: loggedInBarangay // Pass barangay as part of the POST data
                },
                success: function (html) {
                    console.log('Head of Family Dropdown HTML:', html); // Debugging
                    $('#txt_hof').html(html);
                },
                error: function (xhr, status, error) {
                    console.error('AJAX request failed:', status, error); // Debugging
                }
            });
        }
    }

    // Show Family Members based on the selected Head of Family
    function show_family_members() {
        var hofID = $('#txt_hof').val();
        console.log('Head of Family ID: ', hofID);  // Debugging
        if (hofID) {
            $.ajax({
                type: 'POST',
                url: 'household_dropdown.php',
                data: { 
                    hof_id: hofID,
                    barangay: loggedInBarangay // Pass barangay as part of the POST data
                },
                success: function (html) {
                    console.log('Family Members Dropdown HTML:', html); // Debugging
                    $('#txt_members').html(html); // Populate the family members dropdown
                    updateTotalMembers(); // Update total household members
                },
                error: function (xhr, status, error) {
                    console.error('AJAX request failed:', status, error); // Debugging
                }
            });
        }
    }

    // Update Total Household Members based on selected family members
    function updateTotalMembers() {
        var familyMembers = $('#txt_members').val(); // Get selected family members
        var totalMembers = familyMembers ? familyMembers.length : 0; // Count selected family members
        $('#txt_totalmembers').val(totalMembers); // Update the total members field
    }
</script>