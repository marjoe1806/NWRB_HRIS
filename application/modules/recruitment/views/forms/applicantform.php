<?php
$readonly = "";
$required = "";
$inputRequired = "inputRequired";
if($key == "addApplicant" || $key == "updateApplicant") {
	$required = "required";
} elseif ($key == "viewApplicant") {
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
</style>
<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
    <input type="hidden" class="id" name="id" id="id" value="<?php echo isset($key) ? Helper::get('vacancy_id') : ''; ?>">

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
	<div class="row">
		<div class="col-md-3">
			<label class="form-label">FIRST NAME <span class="text-danger">*</span></label>
			<div class="form-group">
				<div class="form-line">
					<input type="text" name="first_name" id="first_name" class="first_name form-control "<?php echo $inputRequired; ?> pattern="^[^\s]+(\s+[^\s]+)*$" <?php echo $required; ?> <?php echo $readonly ?>>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<label class="form-label">MIDDLE NAME <span class="text-danger"></span></label>
			<div class="form-group">
				<div class="form-line">
					<input type="text" name="middle_name" id="middle_name" class="middle_name form-control" <?php echo $readonly ?>>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<label class="form-label">LAST NAME <span class="text-danger">*</span></label>
			<div class="form-group">
				<div class="form-line">
					<input type="text" name="last_name" id="last_name" class="last_name form-control <?php echo $inputRequired; ?>" pattern="^[^\s]+(\s+[^\s]+)*$" <?php echo $required; ?> <?php echo $readonly ?>>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<label class="form-label">NAME EXTENSION (JR.,SR) <span class="text-danger"></span></label>
			<div class="form-group">
				<div class="form-line">
					<input type="text" name="extension" id="extension" class="extension form-control" <?php echo $readonly ?>>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<label class="form-label">EMAIL <span class="text-danger">*</span></label>
			<div class="form-group">
				<div class="form-line">
					<input type="email" name="email" id="email" class="email form-control <?php echo $inputRequired; ?>" pattern="^[^\s]+(\s+[^\s]+)*$" <?php echo $required; ?> <?php echo $readonly ?>>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<label class="form-label">CONTACT NUMBER <span class="text-danger">*</span></label>
			<div class="form-group">
				<div class="form-line">
					<input type="text" name="contact_number" id="contact_number" class="contact_number form-control <?php echo $inputRequired; ?>" <?php echo $required; ?> <?php echo $readonly ?> onkeypress="return isNumber(event)">
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<?php if($key == "updateApplicant") : ?>
			<div class="col-md-12">
				<label class="form-label">Attachment/s
					<span class="text-danger">*</span>
				</label>
				<button id="addrow" class="btn btn-primary btn-sm waves-effect" style="float: right;">
					<i class="material-icons">add</i><span> Add</span>
			    </button>
			    <div>&nbsp;</div>
			    <div>&nbsp;</div>
				<?php foreach ($filenames as $key => $value): ?>

				<div id="updateFileButtons" class="form-group text-center">
					<div class="btn-group btn-group-justified buttons" role="group" aria-label="Justified button group">
						<?php $name = $last_name . "_" . $first_name; ?>
						<a href="<?php echo base_url().'assets/uploads/applicants/'.$name.'/'.$value['filename']; ?>" id="viewAttachment" class="btn waves-effect view_<?php echo $value['id']; ?>" role="button" target="_blank">
						<i class="material-icons">pageview</i>
						<span><?php echo $value['filename']; ?></span></a>
						<a href="<?php echo base_url().'assets/uploads/applicants/'.$name.'/'.$value['filename']; ?>" id="downloadAttachment" class="btn bg-blue waves-effect download_<?php echo $value['id']; ?>" role="button" download>
						<i class="material-icons">arrow_downward</i>
						<span>DOWNLOAD</span></a>
						<a href="#" id="changeAttachment" data-id="<?php echo $value['id']; ?>" data-lastname="<?php echo $last_name; ?>" data-firstname="<?php echo $first_name; ?>" class="btn bg-gray waves-effect change_<?php echo $value['id']; ?>" role="button">
						<i class="material-icons">refresh</i>
						<span>CHANGE</span></a>
						<a href="#" id="deleteAttachment" data-id="<?php echo $value['id']; ?>" data-lastname="<?php echo $last_name; ?>" data-firstname="<?php echo $first_name; ?>" class="btn btn-danger waves-effect delete_<?php echo $value['id']; ?>" role="button">
						<i class="material-icons">close</i>
						<span>DELETE</span></a>
					</div>
				</div>
					<div id="hiddenFileInput<?php echo $value['id']; ?>" class="form-group" style="display: none">
						<div class="form-line">
							<input type="file" class="file_upload_<?php echo $value['id']; ?> form-control" name="file_upload_<?php echo $value['id']; ?>" id="file_upload">
						</div>
						<!-- <button id="changeFile<?php echo $value['id']; ?>" type="button" data-id="<?php echo $value['id']; ?>" class="btn btn-info btn-block waves-effect waves-float">
							<i class="material-icons">refresh</i>
							Change
						</button> -->
						<button id="cancelChange" type="button" data-id="<?php echo $value['id']; ?>" class="btn btn-danger btn-block waves-effect waves-float">
							<i class="material-icons">close</i>
							CANCEL
						</button>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
			
			<div class="form-group newfile">
				<!-- <div>html append here</div> -->
			</div>

			<div>&nbsp;</div>
		<?php elseif($key == "viewApplicant") : ?>	
			<div class="col-md-12">
				<label class="form-label">Attachment/s
					<span class="text-danger">*</span>
				</label>
				<?php foreach ($filenames as $key => $value): ?>
				<div id="updateFileButtons" class="form-group text-center">
					<div class="btn-group btn-group-justified" role="group" aria-label="Justified button group">
						<?php $name = $last_name . "_" . $first_name; ?>
						<a href="<?php echo base_url().'assets/uploads/applicants/'.$name.'/'.$value['filename']; ?>" id="viewAttachment" class="btn waves-effect" role="button" target="_blank">
						<i class="material-icons">pageview</i>
						<span><?php echo $value['filename']; ?></span></a>
						<a href="<?php echo base_url().'assets/uploads/applicants/'.$name.'/'.$value['filename']; ?>" id="downloadAttachment" class="btn bg-blue waves-effect" role="button" download>
						<i class="material-icons">arrow_downward</i>
						<span>DOWNLOAD</span></a>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
			<!-- </div> -->
		<?php else: ?>
			<div class="col-md-12">
				<label class="form-label">UPLOAD FILE: <span class="text-danger">*</span></label>
				<div class="form-group">
					<div class="form-line">
						<input type="file" class="file_upload form-control" name="file_upload[]" id="file_upload" required  multiple>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
	<div class="text-right" style="width:100%;">
		<?php if($key == "addApplicant"): ?>
			<button id="addApplicant" class="btn btn-primary btn-sm waves-effect add" type="submit">
				<i class="material-icons">add</i><span> Add</span>
			</button>
		<?php endif; ?>
		<?php //if($key == "updateApplicant"): ?>
			<button id="updateApplicant" class="btn btn-primary btn-sm waves-effect update" type="submit" style="display:none;">
				<i class="material-icons">save</i><span> Update</span>
			</button>
		<?php //endif; ?>
		<button id="cancelApplicant" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
			<i class="material-icons">close</i><span> Close</span>
		</button>
	</div>
</form>

<script type="text/javascript">
	function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	}
</script>
