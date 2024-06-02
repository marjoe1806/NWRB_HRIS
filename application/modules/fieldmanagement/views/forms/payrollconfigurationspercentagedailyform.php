<?php 
	$readonly = "";
	if($key == "viewPayrollConfigurationsPercentageDailyDetails")
		$readonly = "disabled";
?>
<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
    <div class="form-elements-container">
    	<input type="hidden" name="id" class="id" value="">
		<!-- <div id="form-user" role="form" data-toggle="validator"> -->

		<div class="row">
					<div class="col-md-4">
						<b>Payroll Year</b>
						<div class="input-group">
							<span class="input-group-addon">
								<i class="material-icons">access_time</i>
							</span>
							<div class="form-line">
								<input type="text" class="form-control" readonly value="<?php echo date(" Y "); ?>">
							</div>
						</div>
					</div>
					<div class="col-md-8">
						<b>Branch</b>
						<div class="input-group">
							<span class="input-group-addon">
								<i class="material-icons">account_balance</i>
							</span>
							<select class="form-control">
								<option>National Water Resources Board</option>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<b>Tax Identification Number</b>
						<div class="input-group">
							<span class="input-group-addon">
								<i class="material-icons">verified_user</i>
							</span>
							<div class="form-line">
								<input type="text" class="form-control key" placeholder="000-000-000-000">
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<b>SSS Number</b>
						<div class="input-group">
							<span class="input-group-addon">
								<i class="material-icons">verified_user</i>
							</span>
							<div class="form-line">
								<input type="text" class="form-control key" placeholder="00-0000000-0">
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<b>Account Number</b>
						<div class="input-group">
							<span class="input-group-addon">
								<i class="material-icons">verified_user</i>
							</span>
							<div class="form-line">
								<input type="text" class="form-control key" placeholder="0-00-0000000-0">
							</div>
						</div>
					</div>
				</div>
		<div class="row clearfix">
			<div class="col-md-12">
                <label class="form-label">Posted Flag <span class="text-danger">*</span></label>
                <div class="form-group">
					<div class="switch">
						<label>No <input type="checkbox" name="is_posted" id="is_posted" class="is_posted" value="1" <?php echo $readonly ?>><span class="lever switch-col-blue"></span> Yes</label>
					</div>
					<!-- <div class="help-block with-errors"></div> -->
            	</div>
            </div>
		</div>
		<div class="row clearfix">
			<div class="col-md-12">
                <label class="form-label">Payroll Period <span class="text-danger">*</span></label>
                <div class="form-group">
	                <div class="form-line">
	            		<!-- <input type="text" name="name" id="name" class="name form-control" required <?php echo $readonly ?>> -->
						<input type="text" name="payroll_period" id="payroll_period" class="payroll_period datepicker form-control" required <?php echo $readonly ?>>
						<!-- <div class="help-block with-errors"></div> -->
	            	</div>
            	</div>
            </div>
		</div>
		<div class="row clearfix">
			<div class="col-md-12">
                <label class="form-label">From <span class="text-danger">*</span></label>
                <div class="form-group">
	                <div class="form-line">
	            		<!-- <input type="text" name="name" id="name" class="name form-control" required <?php echo $readonly ?>> -->
						<input type="text" name="start_date" id="start_date" class="start_date datepicker form-control" required <?php echo $readonly ?>>
						<!-- <div class="help-block with-errors"></div> -->
	            	</div>
            	</div>
            </div>
		</div>
		<div class="row clearfix">
			<div class="col-md-12">
                <label class="form-label">To <span class="text-danger">*</span></label>
                <div class="form-group">
	                <div class="form-line">
	            		<!-- <input type="text" name="name" id="name" class="name form-control" required <?php echo $readonly ?>> -->
						<input type="text" name="end_date" id="end_date" class="end_date datepicker form-control" required <?php echo $readonly ?>>
						<!-- <div class="help-block with-errors"></div> -->
	            	</div>
            	</div>
            </div>
		</div>
		<div class="row clearfix">
			<div class="col-md-12">
                <label class="form-label">Period Identification <span class="text-danger">*</span></label>
                <div class="form-group">
	                <div class="form-line">
	            		<input type="text" name="period_id" id="period_id" class="period_id form-control" required <?php echo $readonly ?>>
						<!-- <div class="help-block with-errors"></div> -->
	            	</div>
            	</div>
            </div>
		</div>
		<div class="row clearfix">
			<div class="col-md-12">
                <label class="form-label">Sequence Number <span class="text-danger">*</span></label>
                <div class="form-group">
	                <div class="form-line">
	            		<input type="text" name="sequence_number" id="sequence_number" class="sequence_number form-control" required <?php echo $readonly ?>>
						<!-- <div class="help-block with-errors"></div> -->
	            	</div>
            	</div>
            </div>
		</div>
		<div class="row clearfix">
			<div class="col-md-12">
                <label class="form-label">Semi-Monthly<span class="text-danger">*</span></label>
                <div class="form-group">
					<div class="switch">
						<label>No <input type="checkbox" name="is_semi_monthly" id="is_semi_monthly" class="is_semi_monthly" value="1" <?php echo $readonly ?>><span class="lever switch-col-blue"></span> Yes</label>
					</div>
					<!-- <div class="help-block with-errors"></div> -->
            	</div>
            </div>
		</div>
    </div>
    <div class="text-right" style="width:100%;">
    	<?php if($key == "addPayrollConfigurationsPercentageDaily"): ?>
    		<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">add</i><span> Add</span>
	        </button>
    	<?php endif; ?>
    	<?php if($key == "updatePayrollConfigurationsPercentageDaily"): ?>
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

