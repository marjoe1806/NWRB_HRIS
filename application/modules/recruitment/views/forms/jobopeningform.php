<?php
$readonly = "";
$required = "";
$inputRequired = "inputRequired";
if($key == "addJobOpening") {
	$required = "required";
}
?>
<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
	<input type="hidden" class="id" name="id" id="id" value="<?php echo isset($key) ? Helper::get('position_id') : ''; ?>">

	<div class="row">
		<div class="col-md-12">
			<label class="form-label">Position <span class="text-danger">*</span></label>
			<div class="form-group">
				<div class="form-line position_select">
					<select name="position_id" id="position_id" class="position_id form-control" required readonly>
						<option value=""></option>
					</select>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<label class="form-label">Place of Assignment <span class="text-danger">*</span></label>
			<div class="form-group">
				<div class="form-line">
					<input type="text" name="place" id="place" class="place form-control" pattern="^[^\s]+(\s+[^\s]+)*$" required <?php echo $readonly ?>>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<label class="form-label">Date of Posting<span class="text-danger">*</span></label>
			<div class="form-group form-float">
				<div class="form-line">
					<input type="text" class="form-control date_mask expiration_date <?php echo $inputRequired; ?>" name="expiration_date" id="expiration_date" placeholder="YYYY-MM-DD - YYYY-MM-DD" autocomplete="off" <?php echo $required; ?> <?php echo $readonly ?>>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<label class="form-label">Date of Publication<span class="text-danger">*</span></label>
			<div class="form-group form-float">
				<div class="form-line">
					<input type="text" class="form-control date_mask publication_date <?php echo $inputRequired; ?>" name="publication_date" id="publication_date" placeholder="YYYY-MM-DD" autocomplete="off" <?php echo $required; ?> <?php echo $readonly ?>>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="table-responsive">
				<table cellspacing="5" class="table table-bordered" style="width: 100%;">
					<thead>
						<tr>
							<th>Qualification Standard <span class="text-danger">*</span></th>
							<th>Category <span class="text-danger">*</span></th>
							<th>ACTION <button type="button" id="btnAddQualification" class="btn btn-primary btn-sm" style="float: right;"><i class="material-icons">add</i></button></th>
						</tr>
					</thead>
					<tbody id="tbvacancyqualifications">
						<tr>
							<td>
								<div class="form-group">
									<div class="form-line">
										<textarea class="form-control <?php echo $inputRequired; ?>" name="qualification_name[0]" id="qualification_name[0]" pattern="^[^\s]+(\s+[^\s]+)*$" cols="40" rows="4" <?php echo $required; ?>></textarea>
										<!-- <input type="text" class="form-control <?php echo $inputRequired; ?>" name="qualification_name[0]" id="qualification_name[0]" pattern="^[^\s]+(\s+[^\s]+)*$" <?php echo $required; ?>> -->
									</div>
								</div>
							</td>
							<td>
								<select name="qualification_category[0]" id="qualification_category[0]" class="form-control" <?php echo $inputRequired; ?> data-live-search="true" <?php echo $required; ?>>
									<option value="Education">Education</option>
									<option value="Experience">Experience</option>
									<option value="Training">Training</option>
									<option value="Eligibility">Eligibility</option>
									<option value="Competency">Competency</option>
									<option value="Duties">Duties and Responsibilities</option>
								</select>
							</td>
							<td><button type="button" class="btn btn-danger btn-sm deleteRow" style="float: right;"><i class="material-icons">remove</i></button></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="text-right" style="width:100%;">
		<?php if($key == "addJobOpening"): ?>
			<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
				<i class="material-icons">add</i><span> Add</span>
			</button>
		<?php endif; ?>
		<?php if($key == "updateJobOpening" || $key == "updateExpirationDate"): ?>
			<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
				<i class="material-icons">save</i><span> Update</span>
			</button>
		<?php endif; ?>
		<button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
			<i class="material-icons">close</i><span> Close</span>
		</button>
	</div>
</form>

