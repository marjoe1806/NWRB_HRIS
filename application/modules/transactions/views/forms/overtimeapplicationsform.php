<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
    <div class="form-elements-container">
    	<input type="hidden" class="id" name="id" value="">
		<!-- <div id="form-user" role="form" data-toggle="validator"> -->
		<div class="row clearfix">
            <!-- <div class="col-md-6">
                <label class="form-label">Location</label>
                <div class="form-group">
                    <div class="form-line location_select">
                        <select class="location_id form-control" name="location_id" id="location_id" data-live-search="true">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Serivce / Unit / Division</label>
                <div class="form-group">
                    <div class="form-line division_select">
                        <select class="location_id form-control" name="division_id" id="division_id" data-live-search="true">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div> -->
            <div class="col-md-4">
                <label class="form-label">Pay Basis <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line pay_basis_select">
                        <select class="pay_basis form-control payroll_filters" name="pay_basis" id="pay_basis" data-live-search="true">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label">Payroll Group <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line payroll_grouping_select">
                        <select class="payroll_grouping_id form-control payroll_filters" name="payroll_grouping_id" id="payroll_grouping_id" data-live-search="true" >
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label">Period <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line payroll_period_select">
                        <select class="payroll_period_id form-control " name="payroll_period_id" id="payroll_period_id" data-live-search="true">
                            <option value="">Loading...</option>
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
            <div class="col-md-3">
                <label class="form-label">Salary</label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" name="salary" id="salary" class="salary form-control" readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">Day Rate</label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" name="day_rate" id="day_rate" class="day_rate form-control" readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">Hr Rate</label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" name="hr_rate" id="hr_rate" class="hr_rate form-control" readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">Min Rate</label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" name="min_rate" id="min_rate" class="min_rate form-control" readonly>
                    </div>
                </div>
            </div>
		</div>
        <div class="row clearfix">
            <div class="col-md-12">
                <label class="form-label">Position</label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" name="position_name" id="position_name" class="position_name form-control" readonly>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix" id="permanent_ot">
            <div class="col-md-3">
                <label class="form-label">No. of hrs. (1.5)</label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" min="0" name="hrs_15" id="hrs_15" class="hrs_15 form-control" >
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">No.of mins (1.5)</label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" min="0" name="mins_15" id="mins_15" class="mins_15 form-control" >
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">No. of hrs. (1.25)</label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" min="0" name="hrs_125" id="hrs_125" class="hrs_125 form-control" >
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">No.of mins (1.25)</label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" min="0" name="mins_125" id="mins_125" class="mins_125 form-control" >
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix" id="other_ot" style="display:none;">
            <div class="col-md-3">
                <label class="form-label"># of hrs. (1)</label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" min="0" name="hrs_1" id="hrs_1" class="hrs_1 form-control" >
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label"># of mins (1)</label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" min="0" name="mins_1" id="mins_1" class="mins_1 form-control" >
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label"># of hrs. (1.625)</label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" min="0" name="hrs_1625" id="hrs_1625" class="hrs_1625 form-control" >
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label"># of mins (1.625)</label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" min="0" name="mins_1625" id="mins_1625" class="mins_1625 form-control" >
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-4">
                <label>Salary Exceed (%)</label>
                <br>
                <input name="ot_percent" type="radio" id="radio_30" class="type ot_percent30 radio-col-green" value="30">
                <label for="radio_30">30%</label>

                <input name="ot_percent" type="radio" id="radio_50" class="type ot_percent50 radio-col-green" value="50">
                <label for="radio_50">50%</label>

                <input name="ot_percent" type="radio" id="radio_100" class="type ot_percent100 radio-col-green" value="100">
                <label for="radio_100">100%</label>
            </div>
            <div class="col-md-8">
                <label class="form-label">Tax (%)</label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" name="tax" value="0" min="0" id="tax" class="tax form-control" >
                    </div>
                </div>
            </div>
            
        </div>
        <div class="row clearfix">
            <div class="col-md-4">
                <label class="form-label text-success">Earned for the Period</label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" id="period_earned" name="period_earned" class="period_earned form-control" readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label text-success">Tax Amount</label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" id="tax_amt" name="tax_amt" class="tax_amt form-control" readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label text-success">Net Pay</label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" id="net_pay" name="net_pay" class="net_pay form-control" readonly>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-7">
                <label class="form-label">Remarks</label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" id="remarks" name="remarks" class="remarks form-control">
                        <!-- <div class="help-block with-errors"></div> -->
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-10">
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
    </div>
    <div class="text-right" style="width:100%;">
    	<?php if($key == "addOvertimeApplications"): ?>
    		<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">add</i><span> Add</span>
	        </button>
    	<?php endif; ?>
    	<?php if($key == "updateOvertimeApplications"): ?>
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

