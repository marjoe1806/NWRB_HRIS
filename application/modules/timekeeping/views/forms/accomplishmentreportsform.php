<?php 
	$readonly = "";
	if($key == "viewAccomplishmentReportsDetails")
		$readonly = "disabled";
?>
<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>"
method="POST" enctype="multipart/form-data">
	<div class="form-elements-container">
		<input type="hidden" class="id" name="id" value="">
		<!-- <div id="form-user" role="form" data-toggle="validator"> -->
		<div class="row clearfix">
			<div class="col-md-6">
				<label class="form-label">Select Employee
					<span class="text-danger">*</span>
				</label>
				<div class="form-group">
					<div class="form-line employee_select">
						<select class="employee_id form-control" name="employee_id" id="employee_id" data-live-search="true">
							<option disabled selected></option>
						</select>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<label class="form-label">Date
					<span class="text-danger">*</span>
				</label>
				<div class="form-group">
					<div class="form-line">
						<input type="text" name="accomplishment_date" id="accomplishment_date" class="accomplishment_date datepicker form-control" required>
					</div>
				</div>
			</div>
		</div>
		<div class="row clearfix">
			<div class="col-md-6">
				<label class="form-label">Upload a file
					<span class="text-danger">*</span>
				</label>
				<div class="form-group">
					<div class="form-line">
						<input type="file" name="file" id="file" class="file required form-control">
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<label class="form-label">Document Type
					<span class="text-danger">*</span>
				</label>
				<div class="form-group">
					<div class="form-line type_id_select">
						<select name="type_id" id="type_id" class="type_id form-control" required <?php echo $readonly ?>>
							<option disabled selected></option>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="text-right" style="width:100%;">
		<?php if($key == "addAccomplishmentReports"): ?>
		<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
			<i class="material-icons">add</i>
			<span> Add</span>
		</button>
		<?php endif; ?>
		<?php if($key == "updateAccomplishmentReports"): ?>
		<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
			<i class="material-icons">save</i>
			<span> Update</span>
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
			<i class="material-icons">close</i>
			<span> Close</span>
		</button>
	</div>
</form>
