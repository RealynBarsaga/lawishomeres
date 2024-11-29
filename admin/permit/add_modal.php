<!-- ========================= MODAL ======================= -->
<div id="addModal" class="modal fade">
            <form method="post">
              <div class="modal-dialog modal-sm" style="width:300px !important;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Manage Business Permit</h4>
                    </div>
                    <div class="modal-body">
                        
                        <div class="row">
                            <div class="col-md-12">
                            <div class="form-group">
                                    <label>Name:</label>
                                    <input name="txt_name" class="form-control input-sm" type="text" placeholder="Name" required/>
                                </div>
                                <div class="form-group">
                                    <label>Business Name:</label>
                                    <input name="txt_busname" class="form-control input-sm" type="text" placeholder="Business Name" required/>
                                </div>
                                <div class="form-group">
                                    <label>Business Address:</label>
                                    <input name="txt_busadd" class="form-control input-sm" type="text" placeholder="Business Address" required/>
                                </div>
                                <div class="form-group">
                                    <label>Type of Business:</label>
                                    <select name="ddl_tob" class="form-control input-sm">
                                        <option selected="" disabled="">-- Select Type of Business -- </option>
                                        <option value="Service">Option 1</option>
                                        <option value="Merchandising">Option 2</option>
                                        <option value="Manufacturing">Option 3</option>
                                        <option value="Hybrid Business">Option 4</option>
                                    </select>                                    
                                </div>
                                <div class="form-group">
                                    <label>OR Number:</label>
                                    <input name="txt_ornum" class="form-control input-sm" type="number" placeholder="OR Number" required/>
                                </div>
                                <div class="form-group">
                                    <label>Amount:</label>
                                    <input name="txt_amount" class="form-control input-sm" type="number" placeholder="Amount" required/>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default btn-sm" data-dismiss="modal" value="Cancel"/>
                        <input type="submit" class="btn btn-primary btn-sm" name="btn_add" value="Add"/>
                    </div>
                </div>
              </div>
              </form>
            </div>