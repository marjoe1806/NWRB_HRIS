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
			<div id="row_0" class="col-md-<?php echo $form_size; ?>">
				<div class="card" id="card-1">
		            <div class="body">

						<div class="row">
							<div class="col-md-6">
								<label class="form-label">Remarks
									<span class="text-danger">*</span>
								</label>
								<div class="form-group">
									<div class="form-line">
										<select class="remarks form-control" name="<?php echo $form_name; ?>remarks<?php echo $form_name_end; ?>" id="remarks" data-live-search="true" required>
											<option value="offsetting" selected>Offsetting</option>
				<!-- 							<option value="locator slip" selected>Locator Slip</option>
											<option value="Defective">Defective</option> -->
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-6 text-right">
		        				<button type="button" class="removeBtn btn btn-danger waves-effect" style="visibility: hidden;margin-top:-15px;margin-right:-15px;">
                                    <i class="material-icons">close</i>
                                </button>
		        			</div>
						</div>
						<div class="row clearfix">
							<div class="col-md-6">
								<!-- <label class="form-label">Select Location
									<span class="text-danger">*</span>
								</label>
								<div class="form-group">
									<div class="form-line location_select">
										<select class="location_id form-control" name="location_id" id="location_id" data-live-search="true" required>
											<option disabled selected></option>
										</select>
									</div>
								</div> -->
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
										<select class="employee_id form-control" name="<?php echo $form_name; ?>employee_id<?php echo $form_name_end; ?>" id="employee_id" data-live-search="true" required>
											<option disabled selected></option>
										</select>
									</div>
								</div>
							</div>
							
						</div>
						<div id="actual_logs_container">
							<h5 class="text-center text-primary">OFFICIAL RECORD</h5>
							<hr style="background: #ddd; margin: 0 0 10px 0; padding: 0">
							<div class="row">
								<div class="col-md-8">
									<label class="form-label">Offset Source Date
										<span class="text-danger">*</span>
									</label>
									<div class="form-group">
										<div class="form-line">
											<input type="text" name="<?php echo $form_name; ?>transaction_date<?php echo $form_name_end; ?>" id="transaction_date" class="transaction_date datepicker form-control"
											 required>
										</div>
									</div>
								</div>
							</div>
							<div class="row clearfix" style="pointer-events: none">
								<div class="col-md-4">
									<label class="form-label">Check In
									</label>
									<div class="form-group">
										<div class="form-line">
											<input type="text" id="actual_time_in" class="actual_time_in timepicker form-control">
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<label class="form-label">Lunch Out
									</label>
									<div class="form-group">
										<div class="form-line">
											<input type="text" id="actual_break_out" class="actual_break_out timepicker form-control">
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<label class="form-label">Check Out
									</label>
									<div class="form-group">
										<div class="form-line">
											<input type="text" id="actual_time_out" class="actual_time_out timepicker form-control">
										</div>
									</div>
								</div>
							</div>
							<h5 class="text-center text-success">ADJUSTMENTS</h5>
							<hr style="background: #ddd; margin: 0 0 10px 0; padding: 0">
						</div>

						<?php // endif; ?>
						<div class="row clearfix">
							<div class="col-md-8">
								<label class="form-label">Offset Target Date
									<span class="text-danger">*</span>
								</label>
								<div class="form-group">
									<div class="form-line">
										<input type="text" name="<?php echo $form_name; ?>transaction_date_to<?php echo $form_name_end; ?>" id="transaction_date_to" class="transaction_date_to datepicker form-control"
										 required>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<label class="form-label">Number of Hours</label>
								<div class="form-group">
									<div class="form-line">
										<input type="text" name="<?php echo $form_name; ?>number_of_hrs<?php echo $form_name_end; ?>" id="number_of_hrs" class="number_of_hrs form-control" readonly required>
									</div>
								</div>
							</div>
						</div>
						<div class="row clearfix">
							<div class="col-md-4">
								<label class="form-label">Check In
								</label>
								<div class="form-group">
									<div class="form-line">
										<input type="text" name="<?php echo $form_name2; ?>locator_transaction_time<?php echo $form_name_end2; ?>[]" id="locator_time_in" class="locator_time_in locator_transaction_time timepicker form-control">
										<input type="hidden" name="<?php echo $form_name2; ?>locator_transaction_type<?php echo $form_name_end2; ?>[]" value="0">
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<label class="form-label">Break Out
								</label>
								<div class="form-group">
									<div class="form-line">
										<input type="text" name="<?php echo $form_name2; ?>locator_transaction_time<?php echo $form_name_end2; ?>[]" id="locator_break_out" class="locator_break_out locator_transaction_time timepicker form-control">
										<input type="hidden" name="<?php echo $form_name2; ?>locator_transaction_type<?php echo $form_name_end2; ?>[]" value="2">
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<label class="form-label">Check Out
								</label>
								<div class="form-group">
									<div class="form-line">
										<input type="text" name="<?php echo $form_name2; ?>locator_transaction_time<?php echo $form_name_end2; ?>[]" id="locator_time_out" class="locator_time_out locator_transaction_time timepicker form-control">
										<input type="hidden" name="<?php echo $form_name2; ?>locator_transaction_type<?php echo $form_name_end2; ?>[]" value="1">
									</div>
								</div>
							</div>
						</div>
						<div class="row clearfix">
							<div class="col-md-12">
								<label class="form-label">Purpose</label>
								<div class="form-group">
									<div class="form-line">
										<textarea name="<?php echo $form_name; ?>purpose<?php echo $form_name_end; ?>" id="purpose" class="purpose form-control"></textarea>
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
										<input type="file" name="<?php echo $form_name; ?>file<?php echo $form_name_end; ?>" id="file" class="file required form-control">
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
										<input type="file" name="file" id="file" class="file required form-control">
									</div>
									<button id="cancelChange" type="button" class="btn btn-danger btn-block waves-effect waves-float">
										<i class="material-icons">close</i>
										CANCEL
									</button>
								</div>
							</div>
							<?php endif; ?>
						</div>
						<div class="row">
							<div class="col-md-4">
								<label class="form-label">Status
									<span class="text-danger">*</span>
								</label>
								<div class="form-group">
									<div class="form-line">
										<select class="is_active form-control" name="<?php echo $form_name; ?>is_active<?php echo $form_name_end; ?>" id="is_active" data-live-search="true" required>
											<option value="1" selected>ACTIVE</option>
											<option value="0">INACTIVE</option>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php if($key == "addOffsetting"): ?>
				<div class="col-md-6" id="addBtnCotainer">
					<button type="button" id="addBtn" class="btn btn-block btn-lg btn-primary waves-effect">Add New Offsetting</button>
				</div>
			<?php endif; ?>
		</div>
		<div class="text-right" style="width:100%;">
			<?php if($key == "addOffsetting"): ?>
			<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
				<i class="material-icons">add</i>
				<span> Add</span>
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
