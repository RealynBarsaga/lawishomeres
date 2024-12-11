<!-- ========================= MODAL ======================= -->
<div id="addModal" class="modal fade">
    <form method="post" enctype="multipart/form-data">
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
                                <select id="txt_hof" name="txt_hof" class="form-control input-sm select2" style="width:100%" onchange="show_family_members()" required>
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
                                <input id="txt_totalmembers" name="txt_totalmembers" class="form-control input-sm" type="text" placeholder="Total Household Members" required readonly />
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
    var hofID = $('#txt_hof').val();  // Get the selected Head of Family ID
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
                console.log('Family Members HTML:', html); // Debugging

                // Clear any existing family member inputs
                $('#family_members_list').html('');

                // Array to hold member names
                var familyMembers = [];

                // Parse the response and update the family members list
                $(html).each(function() {
                    var memberName = $(this).val(); // Get member name from each input
                    $('#family_members_list').append('<input type="text" class="form-control input-sm" value="' + memberName + '" readonly />');

                    // Add each family member's name to the familyMembers array
                    familyMembers.push(memberName);
                });

                // Join the family members into a comma-separated string
                var membersString = familyMembers.join(', ');

                // Update the hidden txt_members field with the comma-separated list
                $('#txt_members').val(membersString);  // Set the value in the hidden input

                // Update the visible family members list (optional, if you want to show it somewhere else)
                $('#txt_members_list').val(membersString);  // Show family members in the visible field

                updateTotalMembers(); // Update the total household members count

                // Fetch Barangay and Purok information (if needed)
                fetchBarangayPurok(hofID);
            },
            error: function (xhr, status, error) {
                console.error('AJAX request failed:', status, error); // Debugging
            }
        });
    }
}

// Function to fetch Barangay and Purok values
function fetchBarangayPurok(hofID) {
    $.ajax({
        type: 'POST',
        url: 'household_dropdown.php',
        data: { 
            brgy_id: hofID,
            barangay: loggedInBarangay  // Pass barangay as part of the POST data
        },
        success: function (html) {
            console.log('Barangay HTML:', html);  // Debugging
            $('#txt_brgy').val(html);  // Assuming html contains the Barangay value
        },
        error: function (xhr, status, error) {
            console.error('AJAX request failed:', status, error);  // Debugging
        }
    });

    $.ajax({
        type: 'POST',
        url: 'household_dropdown.php',
        data: { 
            purok_id: hofID,
            barangay: loggedInBarangay  // Pass barangay as part of the POST data
        },
        success: function (html) {
            console.log('Purok HTML:', html);  // Debugging
            $('#txt_purok').val(html);  // Assuming html contains the Purok value
        },
        error: function (xhr, status, error) {
            console.error('AJAX request failed:', status, error);  // Debugging
        }
    });
}

// Update Total Household Members based on displayed family members
function updateTotalMembers() {
    var familyMembers = $('#family_members_list').children('input').length;  // Count the number of family member inputs
    $('#txt_totalmembers').val(familyMembers);  // Update the total members field
}
</script>