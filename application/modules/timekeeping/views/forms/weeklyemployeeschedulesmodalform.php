<?php 
	$readonly = "";
	if($key == "viewWeeklyEmployeeSchedulesDetails")
		$readonly = "disabled";
?>
<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>"
method="POST">
	<div class="form-elements-container">
		<input type="hidden" name="id" class="id" value="">
		<input type="hidden" id="shift_code_id" name="shift_code_id" class="shift_code_id">
		<!-- <div id="form-user" role="form" data-toggle="validator"> -->
		<div class="row clearfix">
			<div class="col-md-6">
				<label class="form-label">Day
					<span class="text-danger">*</span>
				</label>
				<div class="form-group">
					<div class="form-line">
						<!-- <div class="help-block with-errors"></div> -->
						<select name="week_day" id="week_day" class="week_day form-control" required <?php echo $readonly ?>>
							<option disabled selected></option>
							<option value="Monday">Monday</option>
							<option value="Tuesday">Tuesday</option>
							<option value="Wednesday">Wednesday</option>
							<option value="Thursday">Thursday</option>
							<option value="Friday">Friday</option>
							<option value="Saturday">Saturday</option>
							<option value="Sunday">Sunday</option>
						</select>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<label class="form-label">Working Hours
					<span class="text-danger">*</span>
				</label>
				<div class="form-group">
					<div class="form-line">
						<input type="number" name="working_hours" id="working_hours" class="working_hours form-control" required <?php echo $readonly
						?>>
						<!-- <div class="help-block with-errors"></div> -->
					</div>
				</div>
			</div>
		</div>
		<div class="row clearfix">
			<div class="col-md-6">
				<label class="form-label">Time In
					<span class="text-danger">*</span>
				</label>
				<div class="form-group">
					<div class="form-line">
						<input type="text" name="start_time" id="start_time" class="start_time timepicker form-control" required <?php echo $readonly
						?>>
						<!-- <div class="help-block with-errors"></div> -->
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<label class="form-label">Time Out
					<span class="text-danger">*</span>
				</label>
				<div class="form-group">
					<div class="form-line">
						<input type="text" name="end_time" id="end_time" class="end_time timepicker form-control" required <?php echo $readonly ?>>
						<!-- <div class="help-block with-errors"></div> -->
					</div>
				</div>
			</div>
		</div>
		<div class="row clearfix">
			<div class="col-md-6">
				<label class="form-label">Break Time Out
					<span class="text-danger">*</span>
				</label>
				<div class="form-group">
					<div class="form-line">
						<input type="text" name="break_start_time" id="break_start_time" class="break_start_time timepicker form-control" required
						<?php echo $readonly ?>>
						<!-- <div class="help-block with-errors"></div> -->
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<label class="form-label">Break Time In
					<span class="text-danger">*</span>
				</label>
				<div class="form-group">
					<div class="form-line">
						<input type="text" name="break_end_time" id="break_end_time" class="break_end_time timepicker form-control" required <?php
						echo $readonly ?>>
						<!-- <div class="help-block with-errors"></div> -->
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row clearfix">
		<div class="col-md-12">
			<label class="form-label">Restday?
				<span class="text-danger">*</span>
			</label>
			<div class="form-group">
				<div class="switch">
					<label>No
						<input type="checkbox" name="is_restday" id="is_restday" class="is_restday" value="1" <?php echo $readonly ?>>
						<span class="lever switch-col-blue"></span> Yes</label>
				</div>
			</div>
		</div>
	</div>
	<div class="text-right" style="width:100%;">
		<?php if($key == "addWeeklyEmployeeSchedules"): ?>
		<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
			<i class="material-icons">add</i>
			<span> Add</span>
		</button>
		<?php endif; ?>
		<?php if($key == "updateWeeklyEmployeeSchedules"): ?>
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