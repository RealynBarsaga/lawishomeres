<?php 
echo '<div id="editModal' . $row['id'] . '" class="modal fade">
    <form method="post">
        <div class="modal-dialog modal-sm" style="width:300px !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Household Info</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="hidden" value="' . $row['id'] . '" name="hidden_id" id="hidden_id"/>
                            <input type="hidden" value="' . $row['householdno'] . '" name="hiddennum" id="hiddennum"/>
                            <div class="form-group">
                                <label>Household #: </label>
                                <input name="txt_edit_householdno" class="form-control input-sm" type="text" value="' . htmlspecialchars($row['householdno'], ENT_QUOTES, 'UTF-8') . '" readonly/>
                            </div>
                            <div class="form-group">
                                <label>Head of Family: <span style="color:gray; font-size: 10px;"></label>
                                 <input  id="txt_edit_name" name="txt_edit_name" class="form-control input-sm" value="' . htmlspecialchars($row['head_of_family'], ENT_QUOTES, 'UTF-8') . '" readonly/>
                            </div>
                            <div class="form-group">
                                <label>Family Members:</label>
                                <input  id="txt_members" name="txt_members" class="form-control input-sm" value="' . htmlspecialchars($row['membersname'], ENT_QUOTES, 'UTF-8') . '" readonly/>
                            </div>
                            <div class="form-group">
                                <label>Total Household Members: </label>
                                <input id="txt_edit_totalmembers" name="txt_edit_totalmembers" class="form-control input-sm" type="number" value="' . htmlspecialchars($row['totalhouseholdmembers'], ENT_QUOTES, 'UTF-8') . '" readonly/>
                            </div>
                            <div class="form-group">
                                <label>Barangay: </label>
                                <input id="txt_edit_brgy" name="txt_edit_brgy" class="form-control input-sm" type="text" value="' . htmlspecialchars($row['barangay'], ENT_QUOTES, 'UTF-8') . '" readonly/>
                            </div>
                            <div class="form-group">
                                <label>Purok: </label>
                                <input id="txt_edit_purok" name="txt_edit_purok" class="form-control input-sm" type="text" value="' . htmlspecialchars($row['purok'], ENT_QUOTES, 'UTF-8') . '" readonly/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </form>
</div>';
?>
