<?php 
	$readonly = "";
	if($key == "viewPayrollSettingsPayrollSetupDetails")
		$readonly = "disabled";
?>
<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
    <div class="form-elements-container">
    	<!-- <input type="hidden" class="id" name="id" value=""> -->
		<!-- <div id="form-user" role="form" data-toggle="validator"> -->
		<div class="row clearfix">
			<div class="col-md-6">
				<h4 class="text-info">Pagibig</h4>
                <label class="form-label">Employee <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" name="pagibig_employee" id="pagibig_employee" class="pagibig_employee form-control" required <?php echo $readonly ?>>
                    </div>
                </div>
                <br>
                <label class="form-label">Employer <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" name="pagibig_employer" id="pagibig_employer" class="pagibig_employer form-control" required <?php echo $readonly ?>>
                    </div>
                </div>
			</div>
            <div class="col-md-6">
                <h4 class="text-info">Payroll</h4>
                <label class="form-label">Minimum Take Home Pay <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" name="minimum_take_home_pay" id="minimum_take_home_pay" class="minimum_take_home_pay form-control" required <?php echo $readonly ?>>
                    </div>
                </div>
            </div>
		</div>
		<hr>
        <div class="row clearfix">
            <div class="col-md-6">
                <h4 class="text-info">Overtime</h4>
                <div class="demo-radio-button">
                    <input name="overtime" value="standard_ot_rate" type="radio" id="radio_6" class="standard_ot_rate radio-col-light-blue" checked="">
                    <label for="radio_6">Standard OT Rate</label>
                    <br>
                    <input name="overtime" value="multiple_hour_ot_rate" type="radio" id="radio_8" class="multiple_hour_ot_rate radio-col-light-blue">
                    <label for="radio_8">Multiple Hour OT Rate</label>
                </div>
                <br>
                <label class="form-label">Standard Working Hours <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" name="standard_working_hours" id="standard_working_hours" class="standard_working_hours form-control" required <?php echo $readonly ?>>
                    </div>
                </div>
                <br>
                <label class="form-label">Allowed Overtime Hours Beyond <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" name="allowed_overtime_hours_beyond" id="allowed_overtime_hours_beyond" class="allowed_overtime_hours_beyond form-control" required <?php echo $readonly ?>>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h4 class="text-info">Attendance</h4>
                <label class="form-label">Grace Period (Mins) <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                        <input type="number" name="grace_period" id="grace_period" class="grace_period form-control" required <?php echo $readonly ?>>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-12">
                <label class="form-label">Night Differential Hours Between <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" name="night_differential_hours_between_from" id="night_differential_hours_between_from" class="night_differential_hours_between_from timepicker form-control" required <?php echo $readonly ?>>
                    </div>
                </div>
            </div>
            <div class="col-md-1"> and </div>
            <div class="col-md-2">
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" name="night_differential_hours_between_to" id="night_differential_hours_between_to" class="night_differential_hours_between_to timepicker form-control" required <?php echo $readonly ?>>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="text-right" style="width:100%;">
    	<?php if($key == "addPayrollSettingsPayrollSetup"): ?>
    		<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">save</i><span> Save</span>
	        </button>
    	<?php endif; ?>
    	<?php if($key == "updatePayrollSettingsPayrollSetup"): ?>
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

