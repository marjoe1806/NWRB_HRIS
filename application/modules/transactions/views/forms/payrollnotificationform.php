<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
    <div class="form-elements-container">
    	<input type="hidden" class="id" name="id" value="">
		<!-- <div id="form-user" role="form" data-toggle="validator"> -->
		<div class="row clearfix">
            <div class="col-md-6">
                <label class="form-label">Pay Basis <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line pay_basis_select">
                        <select class="pay_basis form-control payroll_filters" name="pay_basis" id="pay_basis" data-live-search="true">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Period <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line payroll_period_select">
                        <select class="payroll_period_id form-control " name="payroll_period_id" id="payroll_period_id" data-live-search="true">
                            <option value="">Loading...</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-6">
                <label class="form-label">Leave Group <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line leave_grouping_select">
                        <select class="leave_grouping_id form-control payroll_filters" name="leave_grouping_id" id="leave_grouping_id" data-live-search="true" >
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
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
            <div class="col-md-1">
                <label class="form-label"><br></label><br>
                <label class="status_icon text-success"><i class="material-icons">check_circle</i></label>
            </div>
        </div>
        <hr>
        <div class="row clearfix">
			<div class="col-md-12">
                <label class="text-primary">List of Employees</label>
                <div id="employee-container">
                    <div class="alert alert-danger">
                        No employees available. Please select leave grouping.
                    </div>
                    
                </div>
            </div>
		</div>
    </div>
    <div class="text-right" style="width:100%;">
    	<?php if($key == "addPayrollNotification"): ?>
    		<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">add</i><span> Add</span>
	        </button>
    	<?php endif; ?>
    	<?php if($key == "updatePayrollNotification"): ?>
	        <button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">save</i><span> Update</span>
	        </button>
	        <!-- <?php if($status == "INACTIVE"): ?>
		        <a id="activateUserLevelConfig" class="activateUserLevelConfig" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.'activateUserLevelConfig'; ?>">
		            <button class="btn btn-success btn-sm waves-effect" type="button">
		                <i class="material-icons">visibility</i><span> Activate</span>
		            </button>
	            </a>
	        <?php endif; ?>
	        <?php if($status == "ACTIVE"): ?>
            <a id="deactivateUserLevelConfig" class="deactivateUserLevelConfig" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.'deactivateUserLevelConfig'; ?>">
	            <button  class="btn btn-danger btn-sm waves-effect" type="button">
	                <i class="material-icons">visibility_off</i><span> Deactivate</span>
	            </button>
            </a>
            <?php endif; ?> -->
            
        <?php endif; ?>
        <button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
            <i class="material-icons">close</i><span> Close</span>
        </button>
    </div>
</form>

