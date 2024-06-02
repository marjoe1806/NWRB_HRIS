<?php
$readonly = "";
$required = "";
$inputRequired = "inputRequired";
if($key == "addApplicantChecklist" || $key == "updateApplicantChecklist") {
	$required = "required";
} elseif ($key == "viewApplicantChecklist") {
	$readonly = "readonly";
}
?>
<style type="text/css">
	@media (min-width: 992px){
		.modal-lg {
			width: 960px;
		}
	}
	@media (min-width: 768px){
		.modal-dialog {
			width: 960px;
			margin: 30px auto;
		}
	}

	.items:after {
		content: '.';
		visibility: hidden;
	}
</style>
<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
    <input type="hidden" class="id" name="id" id="id" value="<?php echo isset($key) ? Helper::get('applicant_checklist_id') : ''; ?>">
    <input type="hidden" class="applicant_id" name="applicant_id" id="applicant_id" value="<?php echo isset($key) ? Helper::get('applicant_id') : ''; ?>">
    <input type="hidden" class="position_id" name="position_id" id="position_id" value="<?php echo isset($key) ? Helper::get('position_id') : ''; ?>">

	<div class="row">
		<div class="col-md-6">
			<label class="form-label">NAME <span class="text-danger">*</span></label>
			<div class="form-group">
				<div class="form-line">
					<input type="text" name="name" id="name" class="name form-control" <?php echo $required; ?> readonly>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<label class="form-label">DATE (mm/dd/yyyy) <span class="text-danger">*</span></label>
			<div class="form-group form-float">
				<div class="form-line">
					<input type="text" class="form-control date_mask start_date <?php echo $inputRequired; ?>" name="start_date" id="start_date" placeholder="Ex: 07/30/2016" <?php echo $required; ?> <?php echo $readonly ?>>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<label class="form-label">POSITION <span class="text-danger">*</span></label>
			<div class="form-group">
				<div class="form-line">
					<input type="text" name="position" id="position" class="position form-control" pattern="^[^\s]+(\s+[^\s]+)*$" <?php echo $required; ?> readonly>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<label class="form-label">SUPERVISOR <span class="text-danger">*</span></label>
			<div class="form-group">
				<div class="form-line employee_select">
					<select name="employee_id" id="employee_id" class="employee_id form-control <?php echo $inputRequired; ?>" data-live-search="true" <?php echo $required; ?>>
						<option value=""></option>
					</select>
				</div>
			</div>
		</div>
	</div>

	<hr>

	<h5 style="margin-top: 15px;">FIRST DAY</h5>
	<div class="row">
		<div class="col-md-6" id="first_day"></div>
		<div class="col-md-6" id="first_day_item"></div>
	</div>

	<h5 style="margin-top: 15px;">POLICIES</h5>
	<div class="row">
		<div class="col-md-6" id="policies"></div>
		<div class="col-md-6" id="policies_item"></div>
	</div>

	<h5 style="margin-top: 15px;">ADMINISTRATIVE PROCEDURE</h5>
	<div class="row">
		<div class="col-md-6" id="administrative_procedure"></div>
		<div class="col-md-6" id="administrative_procedure_item"></div>
	</div>

	<h5 style="margin-top: 15px;">GENERAL ORIENTATION</h5>
	<div class="row">
		<div class="col-md-6" id="general_orientation"></div>
		<div class="col-md-6" id="general_orientation_item"></div>
	</div>

	<h5 style="margin-top: 15px;">POSITION INFORMATION</h5>
	<div class="row">
		<div class="col-md-6" id="position_information"></div>
		<div class="col-md-6" id="position_information_item"></div>
	</div>


	<div class="text-right" style="width:100%;">
		<?php if($key == "addApplicantChecklist"): ?>
			<button id="addApplicantChecklist" class="btn btn-primary btn-sm waves-effect" type="submit">
				<i class="material-icons">add</i><span> Add</span>
			</button>
		<?php endif; ?>
		<?php if($key == "updateApplicantChecklist"): ?>
			<button id="updateApplicantChecklist" class="btn btn-primary btn-sm waves-effect" type="submit">
				<i class="material-icons">save</i><span> Update</span>
			</button>
		<?php endif; ?>
		<button id="cancelApplicantChecklist" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
			<i class="material-icons">close</i><span> Close</span>
		</button>
	</div>
</form>
