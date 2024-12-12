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
                                <select id="txt_hof" name="txt_hof" class="form-control input-sm select2" style="width:100%" onchange="show_family_members()" required>
                                    <option disabled selected>-- Input Household # First --</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Family Members:</label>
                                <div id="family_members_list" class="form-control input-sm" style="border: 1px solid #ccc; padding: 5px; height: 100px; overflow-y: auto;" readonly>
                                    <!-- Family member names will be dynamically added here -->
                                </div>
                                <input id="txt_members" name="txt_members" class="form-control input-sm" type="hidden"/>
                            </div>
                            <div class="form-group">
                                <label>Total Household Members:</label>
                                <input id="txt_totalmembers" name="txt_totalmembers" class="form-control input-sm" type="text" placeholder="Total Household Members" required readonly />
                            </div>
                            <div class="form-group">
                                <label>Barangay:</label>
                                <input id="txt_brgy" name="txt_brgy" class="form-control input-sm" type="text" placeholder="Barangay" required readonly />
                            </div>
                            <div class="form-group">
                                <label>Purok:</label>
                                <input id="txt_purok" name="txt_purok" class="form-control input-sm" type="text" placeholder="Purok" required readonly />
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
    // Logged-in Barangay (PHP passed to JS)
    var loggedInBarangay = '<?= $_SESSION["barangay"] ?? ""; ?>';

    // Fetch and display Head of Family options based on Household #
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
                success: function(html) {
                    $('#txt_hof').html(html);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching Head of Family:', error);
                }
            });
        }
    }

    // Fetch and display Family Members, Barangay, and Purok
    function show_family_members() {
        var hofID = $('#txt_hof').val();
        if (hofID) {
            $.ajax({
                type: 'POST',
                url: 'household_dropdown.php',
                data: {
                    hof_id: hofID,
                    barangay: loggedInBarangay
                },
                success: function(response) {
                    var familyMembers = JSON.parse(response);
                    $('#family_members_list').html('');
                    
                    if (familyMembers.length > 0 && familyMembers[0] !== "No family members found") {
                        familyMembers.forEach(function(memberName) {
                            $('#family_members_list').append('<div>' + memberName + '</div>');
                        });
                        $('#txt_members').val(familyMembers.join(', '));
                    } else {
                        $('#family_members_list').html('<div>No family members found</div>');
                        $('#txt_members').val('');
                    }

                    updateTotalMembers();
                    fetchBarangayPurok(hofID);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching Family Members:', error);
                }
            });
        }
    }

    // Update total household members
    function updateTotalMembers() {
        var memberCount = $('#family_members_list div').length;
        $('#txt_totalmembers').val(memberCount + 1);
    }

    // Fetch Barangay and Purok
    function fetchBarangayPurok(hofID) {
        $.ajax({
            type: 'POST',
            url: 'household_dropdown.php',
            data: { brgy_id: hofID, barangay: loggedInBarangay },
            success: function(response) {
                $('#txt_brgy').val(response || 'Unknown');
            }
        });

        $.ajax({
            type: 'POST',
            url: 'household_dropdown.php',
            data: { purok_id: hofID, barangay: loggedInBarangay },
            success: function(response) {
                $('#txt_purok').val(response || 'Unknown');
            }
        });
    }

    // Validate form before submission
    $('form').on('submit', function(e) {
        if (!$('#txt_householdno').val()) {
            alert('Please enter a Household #');
            e.preventDefault();
        } else if (!$('#txt_hof').val()) {
            alert('Please select a Head of Family.');
            e.preventDefault();
        } else if (!$('#txt_members').val()) {
            alert('No family members were selected.');
            e.preventDefault();
        }
    });
</script>