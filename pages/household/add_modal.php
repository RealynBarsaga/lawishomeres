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
                                <select id="txt_hof" name="txt_hof" class="form-control input-sm select2" style="width:100%" onchange="show_total()" required>
                                   <option disabled selected>-- Input Household # First --</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Family Members:</label>
                                <input id="txt_members" name="txt_members" class="form-control input-sm" type="text" placeholder="Family Members" required readonly/>
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
                success: function (html) {
                    console.log('Barangay HTML:', html); // Debugging
                    $('#txt_brgy').val(html); // Assuming html contains the Barangay value
                },
                error: function (xhr, status, error) {
                    console.error('AJAX request failed:', status, error); // Debugging
                }
            });

            // Fetch Purok value
            $.ajax({
                type: 'POST',
                url: 'household_dropdown.php',
                data: { 
                    purok_id: totalID,
                    barangay: loggedInBarangay // Pass barangay as part of the POST data
                },
                success: function (html) {
                    console.log('Purok HTML:', html); // Debugging
                    $('#txt_purok').val(html); // Assuming html contains the Purok value
                },
                error: function (xhr, status, error) {
                    console.error('AJAX request failed:', status, error); // Debugging
                }
            });

            // Fetch Members
            fetchMembers(totalID);
        }
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
                    
                    try {
                        var members = JSON.parse(response); // Parse the JSON response
                        
                        if (Array.isArray(members) && members.length > 0) {
                            // Set the member names if there are members
                            var memberNames = members.map(function(member) {
                                return member.fullName;
                            });
                            $('#txt_members').val(fullName.join(", ")); // Update the field
                            $('#txt_totalmembers').val(members.length);  // Update total members count
                        } else {
                            // If no members, set the appropriate value
                            $('#txt_members').val("No Members Found");
                            $('#txt_totalmembers').val(0);
                        }
                    } catch (error) {
                        console.error('Error parsing response:', error);  // Log any JSON parsing errors
                        $('#txt_members').val("Error loading members");
                        $('#txt_totalmembers').val(0);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX request failed:', status, error); // Debugging
                    $('#txt_members').val("Error loading members");
                    $('#txt_totalmembers').val(0);
                }
            });
        }
    }

    // Reset and display relevant fields when the modal is shown
    $('#addModal').on('show.bs.modal', function () {
        $('#txt_members').val('');  // Clear the members input field
        $('#txt_totalmembers').val('');  // Clear the total members count
        $('#txt_brgy').val('');  // Clear barangay field
        $('#txt_purok').val('');  // Clear purok field

        // Re-trigger the head of family dropdown on modal open if there's a household number
        var householdID = $('#txt_householdno').val();
        if (householdID) {
            show_head();
        }

        // Re-fetch total members if a head of family is selected
        var hofID = $('#txt_hof').val();
        if (hofID) {
            fetchMembers(hofID);  // Fetch members for the selected head of family
        }
    });

    // Optional: Trigger on the "Add" button or when changes are made to the Head of Family field
    $('#txt_hof').change(function() {
        var hofID = $(this).val();
        if (hofID) {
            fetchMembers(hofID);  // Fetch members whenever a new head of family is selected
        }
    });
</script>