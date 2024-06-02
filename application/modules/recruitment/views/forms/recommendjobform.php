<?php
$readonly = "";
$required = "";
$inputRequired = "inputRequired";
if($key == "recommendJob") {
	$required = "required";
}
?>
<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
	<input type="hidden" class="id" name="id" id="id">

	<div class="row">
		<div class="col-md-12">
			<label class="form-label">VACANCY <span class="text-danger">*</span></label>
			<div class="form-group">
				<div class="form-line vacancy_select">
					<select name="vacancy_id" id="vacancy_id" class="vacancy_id form-control" <?php echo $required; ?> readonly>
						<option value=""></option>
					</select>
				</div>
			</div>
		</div>
	</div>
	<div class="text-right" style="width:100%;">
		<button id="recommendJob" class="btn btn-primary btn-sm waves-effect" type="submit">
			<i class="material-icons">add</i><span> Add</span>
		</button>
		<button id="cancelForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
			<i class="material-icons">close</i><span> Close</span>
		</button>
	</div>
</form>

