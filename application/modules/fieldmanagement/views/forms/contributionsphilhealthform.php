<?php 
	$readonly = "";
	if($key == "viewContributionsPhilHealthDetails")
		$readonly = "disabled";
?>
<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
    <div class="form-elements-container">
    	<input type="hidden" class="id" name="id" value="">
		<!-- <div id="form-user" role="form" data-toggle="validator"> -->
		<div class="row clearfix">
			<div class="col-md-12">
				<h4 class="text-info">Compensation</h4>
			</div>
			<div class="col-md-6">
                <label class="form-label">Range 1 <span class="text-danger">*</span></label>
                <div class="form-group">
                	<div class="form-line">
                		<input type="number" name="compensation_range_1" id="compensation_range_1" class="compensation_range_1 form-control" required <?php echo $readonly ?>>
                	</div>
            	</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Range 2 <span class="text-danger">*</span></label>
                <div class="form-group">
                	<div class="form-line">
                		<input type="number" name="compensation_range_2" id="compensation_range_2" class="compensation_range_2 form-control" required <?php echo $readonly ?>>
                	</div>
            	</div>
            </div>
		</div>
		<hr>
		<div class="row clearfix">
			<div class="col-md-12">
                <label class="form-label">Monthly Salary Credit <span class="text-danger">*</span></label>
                <div class="form-group">
	                <div class="form-line">
	            		<input type="number" name="monthly_salary_credit" id="monthly_salary_credit" class="monthly_salary_credit form-control" required <?php echo $readonly ?>>
	            	</div>
            	</div>
            </div>
		</div>
		<hr>
		<div class="row clearfix">
			<div class="col-md-12">
				<h4 class="text-info">Monthly Contribution</h4>
			</div>
			<div class="col-md-6">
                <label class="form-label">Employer <span class="text-danger">*</span></label>
                <div class="form-group">
                	<div class="form-line">
                		<input type="number" name="monthly_contribution_employer" id="monthly_contribution_employer" class="monthly_contribution_employer form-control" required <?php echo $readonly ?>>
                	</div>
            	</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Employee <span class="text-danger">*</span></label>
                <div class="form-group">
                	<div class="form-line">
                		<input type="number" name="monthly_contribution_employee" id="monthly_contribution_employee" class="monthly_contribution_employee form-control" required <?php echo $readonly ?>>
                	</div>
            	</div>
            </div>
		</div>
		<hr>
		<div class="row clearfix">
			<div class="col-md-12">
                <label class="form-label">Total <span class="text-danger">*</span></label>
                <div class="form-group">
	                <div class="form-line">
	            		<input type="number" name="total" id="total" class="total form-control" required <?php echo $readonly ?> readonly>
	            	</div>
            	</div>
            </div>
		</div>
    </div>
    <div class="text-right" style="width:100%;">
    	<?php if($key == "addContributionsPhilHealth"): ?>
    		<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">add</i><span> Add</span>
	        </button>
    	<?php endif; ?>
    	<?php if($key == "updateContributionsPhilHealth"): ?>
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

