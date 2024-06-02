<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
    <div class="form-elements-container">
    	<input type="hidden" class="id" name="id" value="">
		<!-- <div id="form-user" role="form" data-toggle="validator"> -->
		<div class="row clearfix">
            <!-- <div class="col-md-4">
                <label class="form-label">Location</label>
                <div class="form-group">
                    <div class="form-line location_select">
                        <select class="location_id form-control" name="location_id" id="location_id" data-live-search="true">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div> -->
            <div class="col-md-6">
                <label class="form-label">Pay Basis<span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line pay_basis_select">
                        <select class="pay_basis form-control payroll_filters" name="pay_basis" id="pay_basis" data-live-search="true">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Service / Unit / Division <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line division_select">
                        <select class="division_id form-control " name="division_id" id="division_id" data-live-search="true" >
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
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
<!--         <div class="row clearfix">
            <div class="col-md-6">
                <label class="form-label">Month <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line month_select">
                        <select class="month form-control " name="month" id="month" data-live-search="true">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Year <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" onKeyPress="if(this.value.length==4) return false;" min="0" name="year" id="year" maxlength="4" class="year form-control" required >
                    </div>
                </div>
            </div>
        </div> -->
        <div class="row clearfix">
            <div class="col-md-12">
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
            <div class="col-md-12">
                <label class="form-label">Type of Deduction <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line deduction_select">
                        <select class="deduction_id form-control " name="deduction_id" id="deduction_id" data-live-search="true">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-6">
                <label class="form-label">Amount <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" min="0" name="amount" id="amount" class="amount form-control" required >
                    </div>
                </div>
            </div>
            <div class="col-md-4">
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
    	<?php if($key == "addOtherDeductionEntries"): ?>
    		<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">add</i><span> Add</span>
	        </button>
    	<?php endif; ?>
    	<?php if($key == "updateOtherDeductionEntries"): ?>
	        <button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">save</i><span> Update</span>
	        </button>
            
        <?php endif; ?>
        <button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
            <i class="material-icons">close</i><span> Close</span>
        </button>
    </div>
</form>

