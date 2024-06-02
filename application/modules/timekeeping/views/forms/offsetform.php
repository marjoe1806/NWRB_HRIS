<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>"
 method="POST" autocomplete=off>
	<?php if($key == "updateOffsetting"): ?>
	<input type="hidden" name="id" class="id" id="id">
	<?php endif; ?>
	<?php 
		$form_name = "";
		$form_name2 = "";
		$form_name_end = "";
		$form_name_end2 = "";
		$form_size = "12";
		if ($key == "addOffsetting"){
			$form_name = "table1[0][";
			$form_name_end = "]";
			$form_name2 = "table2[0][";
			$form_name_end2 = "]";
			$form_size = "6";
		}
	?>
	<div class="form-elements-container">
		<div class="row form-elements">
			<div  class="col-md-12">
				<div class="card" >
		            <div class="body">
						<div class="row clearfix">
							<div class="col-md-6">
								<label class="form-label">Service/Division/Unit <span class="text-danger">*</span></label>
	                            <div class="form-group">
	                                <div class="form-line division_select">
	                                    <select class="division_id form-control " name="division_id" id="division_id" data-live-search="true" >
	                                        <option value=""></option>
	                                    </select>
	                                </div>
	                            </div>
							</div>
							<div class="col-md-6">
								<label class="form-label">Select Employee
									<span class="text-danger">*</span>
								</label>
								<div class="form-group">
									<div class="form-line employee_select">
										<select class="employee_id form-control" name="employee_id" id="employee_id" data-live-search="true" required>
											<option disabled selected></option>
										</select>
									</div>
								</div>
							</div>
							
						</div>
						<div id="actual_logs_container">
							<hr style="background: #ddd; margin: 0 0 10px 0; padding: 0">
                            <div class="row">
								<div class="col-md-6">
									<label class="form-label">No. of OT Available Hours</label>
									<div class="form-group">
										<div class="form-line">
											<input type="number" name="hours_available" id="hours_available" value="0" class="hours_available form-control"
											 readonly>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<label class="form-label">No. of Pending Request Hours</label>
									<div class="form-group">
										<div class="form-line">
											<input type="number" name="hours_pending" id="hours_pending" value="0" class="hours_pending form-control"
											readonly>
										</div>
									</div>
								</div>
                            </div>
							<div class="row">
								<div class="col-md-6">
									<label class="form-label">Date Request<span class="text-danger">*</span></label>
									<div class="form-group">
										<div class="form-line">
											<input type="text" name="date_requested" id="date_requested" class="date_requested datepicker form-control"
											 required>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<label class="form-label">No. of Offset Hours</label>
									<div class="form-group">
										<div class="form-line">
											<input type="text" name="number_of_hrs" id="number_of_hrs" class="number_of_hrs input-integer form-control"
											 required>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row clearfix">
							<div class="col-md-12">
								<label class="form-label">Purpose</label>
								<div class="form-group">
									<div class="form-line">
										<textarea name="purpose" id="purpose" class="purpose form-control" rows="2" style="resize: nonel"></textarea>
									</div>
								</div>
							</div>
						</div>
						<div class="row clearfix">
							<?php if($key == "addOffsetting"): ?>
							<div class="col-md-12">
								<label class="form-label">Upload Attachment
									<span class="text-danger">*</span>
								</label>
								<div class="form-group">
									<div class="form-line">
										<input type="file" name="file" id="file" class="file form-control">
									</div>
								</div>
							</div>
							<?php endif;?>
							<?php if($key == "updateOffsetting" || $key == "viewOffsetting"): ?>
							<div class="col-md-12">
								<label class="form-label">Attachment
									<span class="text-danger">*</span>
								</label>
								<div id="updateFileButtons" class="form-group text-center">
									<div class="btn-group btn-group-justified" role="group" aria-label="Justified button group">
										<a href="#" id="viewAttachment" class="btn waves-effect" role="button" target="_blank">
											<i class="material-icons">pageview</i>
											<span>VIEW</span></a>
										<a href="#" id="downloadAttachment" class="btn bg-blue waves-effect" role="button" download>
											<i class="material-icons">arrow_downward</i>
											<span>DOWNLOAD</span></a>
										<a href="#" id="changeAttachment" class="btn bg-gray waves-effect" role="button">
											<i class="material-icons">refresh</i>
											<span>CHANGE</span></a>
									</div>
								</div>
								<div id="hiddenFileInput" class="form-group" style="display: none">
									<div class="form-line">
										<input type="file" name="file" id="file" class="file inputFile form-control">
										<input type="hidden" name="filename" class="filename">
									</div>
									<button id="cancelChange" type="button" class="btn btn-danger btn-block waves-effect waves-float">
										<i class="material-icons">close</i>
										CANCEL
									</button>
								</div>
							</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="text-right" style="width:100%;">
			<?php if($key == "addOffsetting"): ?>
			<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
				<i class="material-icons">add</i>
				<span> Submit</span>
			</button>
			<?php endif; ?>
			<?php if($key == "updateOffsetting"): ?>
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
	</div>
</form>
