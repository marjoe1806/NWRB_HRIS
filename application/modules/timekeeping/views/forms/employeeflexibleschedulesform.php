<?php 
	$readonly = "";
	if($key == "viewEmployeeSchedulesDetails")
		$readonly = "disabled";
?>
<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>"
method="POST">
	<div class="form-elements-container">
		<input type="hidden" name="id" class="id" value="">
		<div class="row clearfix">
			<div class="col-md-6">
				<label class="form-label">Shift Code
					<span class="text-danger">*</span>
				</label>
				<div class="form-group">
					<div class="form-line">
						<input type="text" name="shift_code" id="shift_code" class="shift_code form-control" required <?php echo $readonly ?>>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<label class="form-label">Additional Hours
					<span class="text-danger">*</span>
				</label>
				<div class="form-group">
					<div class="form-line">
						<input type="text" name="addtl_hrs" id="addtl_hrs" class="addtl_hrs form-control" required <?php echo $readonly ?>>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<label class="form-label">Shift Description
					<span class="text-danger">*</span>
				</label>
				<div class="form-group">
					<div class="form-line">
						<input type="text" name="description" id="description" class="description form-control" required <?php echo $readonly ?>>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="text-right" style="width:100%;">
		<?php if($key == "addEmployeeSchedules"): ?>
		<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
			<i class="material-icons">add</i>
			<span> Add</span>
		</button>
		<?php endif; ?>
		<?php if($key == "updateEmployeeSchedules"): ?>
		<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
			<i class="material-icons">save</i>
			<span> Update</span>
		</button>
		<?php endif; ?>
		<button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
			<i class="material-icons">close</i>
			<span> Close</span>
		</button>
	</div>
</form>
