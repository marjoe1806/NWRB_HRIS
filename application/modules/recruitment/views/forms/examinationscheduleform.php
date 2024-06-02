<?php
$readonly = "";
$required = "";
$inputRequired = "inputRequired";
if($key == "addExaminationSchedule" || $key == "updateExaminationSchedule") {
	$required = "required";
} elseif ($key == "viewExaminationSchedule") {
	$readonly = "readonly";
}
?>
<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
    <input type="hidden" class="id" name="id" id="id" value="<?php echo isset($key) ? Helper::get('examination_schedule_id') : ''; ?>">
	<input type="hidden" class="applicant_id" name="applicant_id" id="applicant_id">

	<div class="row">
		<div class="col-md-12">
			<label class="form-label">NAME <span class="text-danger">*</span></label>
			<div class="form-group">
				<div class="form-line">
					<input type="text" name="name" id="name" class="name form-control" pattern="^[^\s]+(\s+[^\s]+)*$" <?php echo $required; ?> <?php echo $readonly ?>>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<label class="form-label">POSITION <span class="text-danger">*</span></label>
			<div class="form-group">
				<div class="form-line">
					<input type="text" name="position" id="position" class="position form-control" <?php echo $required; ?> readonly>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<label class="form-label">DATE (mm/dd/yyyy) <span class="text-danger">*</span></label>
			<div class="form-group form-float">
				<div class="form-line">
					<input type="text" class="form-control date_mask examination_schedule_date <?php echo $inputRequired; ?>" name="examination_schedule_date" id="examination_schedule_date" placeholder="Ex: 07/30/2016" <?php echo $required; ?> <?php echo $readonly ?>>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<label class="form-label">TIME <span class="text-danger">*</span></label>
			<div class="form-group">
				<div class="form-line">
					<input type="time" name="examination_schedule_time" id="examination_schedule_time" class="examination_schedule_time timepicker form-control <?php echo $inputRequired; ?>" pattern="^[^\s]+(\s+[^\s]+)*$" <?php echo $required; ?> <?php echo $readonly ?>>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<label class="form-label">REMARKS <span class="text-danger"></span></label>
			<div class="form-group">
				<div class="form-line">
					<textarea class="examination_remarks" name="examination_remarks"  id="examination_remarks" rows="4" style="width: 100%;" <?php echo $readonly ?>></textarea>
				</div>
			</div>
		</div>
	</div>
	<div class="text-right" style="width:100%;">
		<?php if($key == "addExaminationSchedule"): ?>
			<button id="addExaminationSchedule" class="btn btn-primary btn-sm waves-effect" type="submit">
				<i class="material-icons">add</i><span> Add</span>
			</button>
		<?php endif; ?>
		<?php if($key == "updateExaminationSchedule"): ?>
			<button id="updateExaminationSchedule" class="btn btn-primary btn-sm waves-effect" type="submit">
				<i class="material-icons">save</i><span> Update</span>
			</button>
		<?php endif; ?>
		<button id="cancelExaminationSchedule" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
			<i class="material-icons">close</i><span> Close</span>
		</button>
	</div>
</form>
