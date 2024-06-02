<?php 
	$readonly = "";
	if($key == "viewPayrollConfigurationsMainDetails")
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
					<div class="form-line">
							<input type="text" class="form-control">
						</div>
					<!-- <select class="form-control">
						<option>Metropolitan Manila Development Authority</option>
					</select> -->
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
				<b>GSIS Number</b>
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
    </div>
    <!-- <div class="text-right" style="width:100%;">
    	<?php if($key == "addPayrollConfigurationsMain"): ?>
    		<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">add</i><span> Add</span>
	        </button>
    	<?php endif; ?>
    	<?php if($key == "updatePayrollConfigurationsMain"): ?>
	        <button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">save</i><span> Update</span>
	        </button>
	        <?php if($status == "INACTIVE"): ?>
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
            <?php endif; ?>
            
        <?php endif; ?>
        <button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
            <i class="material-icons">close</i><span> Close</span>
        </button>
    </div> -->
</form>

