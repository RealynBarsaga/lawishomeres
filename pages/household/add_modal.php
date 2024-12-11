<!-- ========================= MODAL ======================= -->
<div id="addModal" class="modal fade">
    <form method="post" id="householdForm">
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
        if (householdID) {
            $.ajax({
                type: 'POST',
                url: 'household_dropdown.php',
                data: { 
                    hhold_id: householdID,
                    barangay: loggedInBarangay // Pass barangay as part of the POST data
                },
                success: function (html) {
                    $('#txt_hof').html(html);  // Populate the Head of Family dropdown
                },
                error: function (xhr, status, error) {
                    console.error('AJAX request failed:', status, error);
                }
            });
        }
    }

    // Show Family Members based on the selected Head of Family
    function show_family_members() {
        var hofID = $('#txt_hof').val();  
        if (hofID) {
            $.ajax({
                type: 'POST',
                url: 'household_dropdown.php',
                data: { 
                    hof_id: hofID,
                    barangay: loggedInBarangay // Pass barangay as part of the POST data
                },
                success: function (html) {
                    // Clear any existing family member inputs
                    $('#family_members_list').html('');

                    // Populate the family members list with input fields
                    var familyMembers = [];
                    $(html).each(function() {
                        var memberName = $(this).val(); // Get member name from each input
                        $('#family_members_list').append('<input type="text" class="form-control input-sm" value="' + memberName + '" readonly />');
                        familyMembers.push(memberName);
                    });

                    // Update the hidden input field with the family members
                    $('#txt_members').val(familyMembers.join(', ')); // Join names as a comma-separated string
                    updateTotalMembers(); // Update the total household members count
                },
                error: function (xhr, status, error) {
                    console.error('AJAX request failed:', status, error);
                }
            });

            // Fetch Barangay value
            $.ajax({
                type: 'POST',
                url: 'household_dropdown.php',
                data: { 
                    brgy_id: hofID,
                    barangay: loggedInBarangay  // Pass barangay as part of the POST data
                },
                success: function (html) {
                    $('#txt_brgy').val(html);  // Populate Barangay field
                },
                error: function (xhr, status, error) {
                    console.error('AJAX request failed:', status, error); 
                }
            });

            // Fetch Purok value
            $.ajax({
                type: 'POST',
                url: 'household_dropdown.php',
                data: { 
                    purok_id: hofID,
                    barangay: loggedInBarangay  // Pass barangay as part of the POST data
                },
                success: function (html) {
                    $('#txt_purok').val(html);  // Populate Purok field
                },
                error: function (xhr, status, error) {
                    console.error('AJAX request failed:', status, error); 
                }
            });
        }
    }

    // Update Total Household Members based on displayed family members
    function updateTotalMembers() {
        var familyMembersCount = $('#family_members_list').children('input').length;  // Count the number of input fields
        $('#txt_totalmembers').val(familyMembersCount);  // Update the total members field
    }

    // Handle Form Submission
    $('#householdForm').submit(function(event) {
        event.preventDefault();  // Prevent default form submission

        var formData = {
            householdNo: $('#txt_householdno').val(),
            hof: $('#txt_hof').val(),
            members: $('#txt_members').val(),
            totalMembers: $('#txt_totalmembers').val(),
            brgy: $('#txt_brgy').val(),
            purok: $('#txt_purok').val()
        };

        // Submit data using AJAX
        $.ajax({
            type: 'POST',
            url: 'household_dropdown.php',  // Change to your server-side submission URL
            data: formData,
            success: function(response) {
                alert('Household added successfully');
                $('#addModal').modal('hide');  // Hide the modal after successful submission
            },
            error: function(xhr, status, error) {
                console.error('Submission failed:', status, error);
            }
        });
    });

    // Reset the form before opening the modal
    $('#addModal').on('show.bs.modal', function () {
        $('#householdForm')[0].reset();  // Reset form fields
        $('#txt_hof').html('<option disabled selected>-- Input Household # First --</option>');  // Reset Head of Family dropdown
        $('#family_members_list').html('');  // Clear family members list
        $('#txt_members').val('');
        $('#txt_totalmembers').val('');
        $('#txt_brgy').val('');
        $('#txt_purok').val('');
    });
</script> 
