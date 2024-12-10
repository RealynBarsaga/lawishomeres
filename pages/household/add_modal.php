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
                                <input onkeyup="show_head()" id="txt_householdno" name="txt_householdno" class="form-control input-sm" type="number" placeholder="Household #" required />
                            </div>
                            <div class="form-group">
                                <label>Head Of Family:</label>
                                <select id="txt_hof" name="txt_hof" class="form-control input-sm select2" style="width:100%" onchange="show_total()" required>
                                    <option disabled selected>-- Input Household # First --</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Family Members:</label>
                                <input id="txt_members" name="txt_members" class="form-control input-sm" type="text" placeholder="Family Members" required />
                            </div>
                            <div class="form-group">
                                <label>Total Household Members:</label>
                                <input id="txt_totalmembers" name="txt_totalmembers" class="form-control input-sm" type="text" placeholder="Total Household Members" required />
                            </div>
                            <div class="form-group">
                                <label>Barangay:</label>
                                <input id="txt_brgy" disabled name="txt_brgy" class="form-control input-sm" type="text" placeholder="Barangay" required />
                            </div>
                            <div class="form-group">
                                <label>Purok:</label>
                                <input id="txt_purok" disabled name="txt_purok" class="form-control input-sm" type="text" placeholder="Purok" required />
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

    function show_head() {
        var householdID = $('#txt_householdno').val();
        if (householdID) {
            $.ajax({
                type: 'POST',
                url: 'household_dropdown.php',
                data: {
                    hhold_id: householdID,
                    barangay: loggedInBarangay
                },
                success: function (html) {
                    $('#txt_hof').html(html);
                },
                error: function (xhr, status, error) {
                    console.error('Error in show_head AJAX:', status, error);
                }
            });
        }
    }

    function show_total() {
        var totalID = $('#txt_hof').val();
        if (totalID) {
            // Fetch Barangay
            $.ajax({
                type: 'POST',
                url: 'household_dropdown.php',
                data: {
                    brgy_id: totalID,
                    barangay: loggedInBarangay
                },
                success: function (html) {
                    $('#txt_brgy').val(html);
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching barangay:', status, error);
                }
            });

            // Fetch Purok
            $.ajax({
                type: 'POST',
                url: 'household_dropdown.php',
                data: {
                    purok_id: totalID,
                    barangay: loggedInBarangay
                },
                success: function (html) {
                    $('#txt_purok').val(html);
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching purok:', status, error);
                }
            });

            // After updating Barangay and Purok, fetch and update family members and total members
            update_family_info(householdID);
        }
    }

    // Function to update the family members and total member count
    function update_family_info(householdID) {
        $.ajax({
            type: 'POST',
            url: 'household_dropdown.php',  // This should be the PHP file that returns the members and total count
            data: { household_id: householdID, barangay: loggedInBarangay },
            success: function(response) {
                var data = JSON.parse(response);
                $('#txt_members').val(data.members);  // Assuming data.members contains the family member names/IDs
                $('#txt_totalmembers').val(data.total_members);  // Assuming data.total_members contains the total count
            },
            error: function (xhr, status, error) {
                console.error('Error fetching family info:', status, error);
            }
        });
    }
</script>