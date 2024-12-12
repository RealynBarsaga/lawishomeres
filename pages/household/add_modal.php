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
                                <select id="txt_hof" name="txt_hof" class="form-control input-sm select2" style="width:100%" onchange="show_total()">
                                   <option disabled selected>-- Input Household # First --</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Family Members:</label>
                                <div id="family_members_list" class="form-control input-sm" style="height: 100px; overflow-y: auto;" readonly>
                                    <!-- Family member names will be dynamically inserted here as input fields -->
                                </div>
                                <!-- Hidden input to store family member names -->
                                <input id="txt_members" name="txt_members" type="hidden" readonly />
                            </div>
                            <div class="form-group">
                                <label>Total Household Members:</label>
                                <input id="txt_totalmembers" name="txt_totalmembers" class="form-control input-sm" type="text" placeholder="Total Household Members" required readonly/>
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
                    // Clear previous member data when a new household is selected
                    $('#txt_members').val('');
                    $('#txt_totalmembers').val('');
                },
                error: function (xhr, status, error) {
                    console.error('AJAX request failed:', status, error); // Debugging
                }
            });
        }
    }

    // Triggered when the Head of Family (HOF) is selected
    function show_total() {
        var totalID = $('#txt_hof').val();
        console.log('Head of Family ID: ', totalID);  // Debugging
        if (totalID) {
            // Fetch Barangay value
            $.ajax({
                type: 'POST',
                url: 'household_dropdown.php',
                data: { 
                    brgy_id: totalID,
                    barangay: loggedInBarangay // Pass barangay as part of the POST data
                },
                success: function (html) ```javascript
                console.log('Barangay HTML:', html); // Debugging
                $('#txt_brgy').val(html); // Assuming the response contains the barangay name
                fetchMembers(totalID); // Fetch family members for the selected Head of Family
            },
            error: function (xhr, status, error) {
                console.error('AJAX request failed:', status, error); // Debugging
            }
        });
    }

    // Fetch and display members for a given Head of Family
    function fetchMembers(headoffamily) {
        console.log('Fetching members for HOF ID:', headoffamily);  // Debugging
        if (headoffamily) {
            $.ajax({
                type: 'POST',
                url: 'household_dropdown.php',
                data: { 
                    headoffamily: headoffamily,
                    barangay: loggedInBarangay // Pass barangay as part of the POST data
                },
                success: function (response) {
                    console.log('Family Members Response:', response);  // Debugging
                    if (response !== '') {
                        try {
                            var members = JSON.parse(response);
                            if (Array.isArray(members) && members.length > 0) {
                                // Set the member names if there are members
                                var memberNames = members.map(function(member) {
                                    return member.fullName;
                                });
                                $('#txt_members').val(memberNames.join(", "));
                                $('#txt_totalmembers').val(members.length);
                            } else {
                                // If no members, set the appropriate value
                                $('#txt_members').val("No Members Found");
                                $('#txt_totalmembers').val(0);
                            }
                        } catch (e) {
                            console.error('Error parsing JSON response:', e);
                        }
                    } else {
                        console.error('Empty response from server');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX request failed:', status, error); // Debugging
                }
            });
        }
    }
</script>