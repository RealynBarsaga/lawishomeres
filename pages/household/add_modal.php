<!-- Modal for Managing Household -->
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
                            <!-- Household Number -->
                            <div class="form-group">
                                <label>Household #:</label>
                                <input 
                                    onkeyup="show_head()" 
                                    id="txt_householdno" 
                                    name="txt_householdno" 
                                    class="form-control input-sm" 
                                    type="number" 
                                    placeholder="Household #" 
                                    required />
                            </div>
                            <!-- Head of Family -->
                            <div class="form-group">
                                <label>Head Of Family:</label>
                                <select 
                                    id="txt_hof" 
                                    name="txt_hof" 
                                    class="form-control input-sm select2" 
                                    style="width:100%" 
                                    onchange="show_total()" 
                                    required>
                                    <option disabled selected>-- Input Household # First --</option>
                                </select>
                            </div>
                            <!-- Family Members -->
                            <div class="form-group">
                                <label>Family Members:</label>
                                <input 
                                    id="txt_members" 
                                    name="txt_members" 
                                    class="form-control input-sm" 
                                    type="text" 
                                    placeholder="Family Members" 
                                    required readonly />
                            </div>
                            <!-- Total Household Members -->
                            <div class="form-group">
                                <label>Total Household Members:</label>
                                <input 
                                    id="txt_totalmembers" 
                                    name="txt_totalmembers" 
                                    class="form-control input-sm" 
                                    type="text" 
                                    placeholder="Total Household Members" 
                                    required readonly />
                            </div>
                            <!-- Barangay -->
                            <div class="form-group">
                                <label>Barangay:</label>
                                <input 
                                    id="txt_brgy" 
                                    disabled 
                                    name="txt_brgy" 
                                    class="form-control input-sm" 
                                    type="text" 
                                    placeholder="Barangay" 
                                    required />
                            </div>
                            <!-- Purok -->
                            <div class="form-group">
                                <label>Purok:</label>
                                <input 
                                    id="txt_purok" 
                                    disabled 
                                    name="txt_purok" 
                                    class="form-control input-sm" 
                                    type="text" 
                                    placeholder="Purok" 
                                    required />
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
    var loggedInBarangay = '<?= $_SESSION["barangay"] ?? ""; ?>'; // Pass PHP session variable to JS

    // Trigger when household number input changes
    function show_head() {
        var householdID = $('#txt_householdno').val();
        if (householdID) {
            $.ajax({
                type: 'POST',
                url: 'household_dropdown.php',
                data: { hhold_id: householdID, barangay: loggedInBarangay },
                success: function (html) {
                    $('#txt_hof').html(html);
                },
                error: function () {
                    $('#txt_hof').html('<option disabled selected>-- Error Loading Data --</option>');
                }
            });
        }
    }

    // Triggered when the Head of Family (HOF) is selected
    function show_total() {
        var totalID = $('#txt_hof').val();
        if (totalID) {
            // Fetch Barangay and Purok
            ['brgy_id', 'purok_id'].forEach(field => {
                $.ajax({
                    type: 'POST',
                    url: 'household_dropdown.php',
                    data: { [field]: totalID, barangay: loggedInBarangay },
                    success: function (html) {
                        if (field === 'brgy_id') $('#txt_brgy').val(html);
                        if (field === 'purok_id') $('#txt_purok').val(html);
                    },
                    error: function () {
                        if (field === 'brgy_id') $('#txt_brgy').val('Error');
                        if (field === 'purok_id') $('#txt_purok').val('Error');
                    }
                });
            });

            // Fetch Members
            fetchMembers(totalID);
        }
    }

    // Fetch and display members for a given Head of Family
    function fetchMembers(headoffamily) {
        $.ajax({
            type: 'POST',
            url: 'household_dropdown.php',
            data: { headoffamily: headoffamily, barangay: loggedInBarangay },
            success: function (response) {
                try {
                    var members = JSON.parse(response);
                    if (Array.isArray(members) && members.length > 0) {
                        var memberNames = members.map(member => member.fullName);
                        $('#txt_members').val(memberNames.join(", "));
                        $('#txt_totalmembers').val(members.length);
                    } else {
                        $('#txt_members').val("No Members Found");
                        $('#txt_totalmembers').val(0);
                    }
                } catch (error) {
                    $('#txt_members').val("Error loading members");
                    $('#txt_totalmembers').val(0);
                }
            },
            error: function () {
                $('#txt_members').val("Error loading members");
                $('#txt_totalmembers').val(0);
            }
        });
    }
</script>