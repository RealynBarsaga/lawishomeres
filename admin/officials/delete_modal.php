<!-- ===== DELETE MODAL ==== -->
<div id="deleteModal<?php echo $row['id'];?>" class="modal fade">
    <form method="post">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Delete Confirmation</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this record?</p>
                    <input type="hidden" name="hidden_id" value="<?php echo $row['id']; ?>" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">No</button>
                    <input type="submit" class="btn btn-primary btn-sm" name="btn_del" id="btn_del" value="Yes"/>
                </div>
            </div>
        </div>
    </form>
</div>