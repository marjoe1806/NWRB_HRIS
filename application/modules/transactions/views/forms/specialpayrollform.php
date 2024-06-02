<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
    <div class="form-elements-container">
    	<input type="hidden" class="id" name="id" value="">
		<!-- <div id="form-user" role="form" data-toggle="validator"> -->
		<div class="row clearfix">
            <div class="col-md-6">
                <label class="form-label">Division</label>
                <div class="form-group">
                    <div class="form-line division_select">
                        <select class="division_id form-control" name="division_id" id="division_id" data-live-search="true">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div> 
            <div class="col-md-6">
                <label class="form-label">Year <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                        <select class="year form-control" name="year" id="year" data-live-search="true">
                            <?php 
                            $years = array_combine(range(date("Y"), 1910), range(date("Y"), 1910));
                            foreach ($years as $k => $v) {
                                echo '<option value="'.$k.'">'.$v.'</option>';
                            } 
                            ?>
                        </select>
                    </div>
                </div>
            </div> 
			<div class="col-md-12">
                <label class="form-label">Employee <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line employee_select">
                        <select class="employee_id form-control " name="employee_id" id="employee_id" data-live-search="true">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
		</div>
        
        <div class="row clearfix">
            <div class="col-md-6">
                <label class="form-label">Type of Bonus <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line bonuses_select">
                        <select class="bonus_type form-control " name="bonus_type" id="bonus_type" data-live-search="true">
                            <option value="">Loading...</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Amount <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                       <input type="number" min="0" name="amount" id="amount" class="amount form-control" required >
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-6" id="cash_gift_container" style="display:none;">
                <label class="form-label">Cash Gift <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                       <input type="number" value="5000" min="0" name="cash_gift" id="cash_gift" class="cash_gift form-control" required >
                    </div>
                </div>
            </div>

            <div class="col-md-6" id="in_kind_container" style="display:none;">
                <label class="form-label">In-Kind <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                       <input type="number" value="0" min="0" name="in_kind" id="in_kind" class="in_kind form-control" required >
                    </div>
                </div>
            </div>

            <div class="col-md-6" id="union_fees_container" style="display:none;">
                <label class="form-label">Less Union Fees <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                       <input type="number" value="0" min="0" name="union_fees" id="union_fees" class="union_fees form-control" required >
                    </div>
                </div>
            </div>
            
            <div class="col-md-5 col-sm-10">
                <label class="form-label">Status</label>
                <div class="form-group">
                    <div class="form-line">
                        <div class="form-line location_select">
                            <select class="is_active form-control" name="is_active" id="is_active" data-live-search="true">
                                <option value="1">ACTIVE</option>
                                <option value="0">INACTIVE</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-1 col-sm-2">
                <label class="form-label"><br></label><br>
                <label class="status_icon text-success"><i class="material-icons">check_circle</i></label>
            </div>
        </div>
    <div class="text-right" style="width:100%;">
    	<?php if($key == "addSpecialPayroll"): ?>
    		<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">add</i><span> Add</span>
	        </button>
    	<?php endif; ?>
    	<?php if($key == "updateSpecialPayroll"): ?>
	        <button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">save</i><span> Update</span>
	        </button>            
        <?php endif; ?>
        <button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
            <i class="material-icons">close</i><span> Close</span>
        </button>
    </div>
</form>

