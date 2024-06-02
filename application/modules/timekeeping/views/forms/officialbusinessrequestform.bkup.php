<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
	<div class="form-elements-container">
		<hr>
			<center><h4 class="text-info">Official Business Permission Form</h4></center>
		<hr>
		<input type="hidden" name="id" value="" class="id">
		<input type="hidden" id="employee_number" name="employee_number" value="<?php echo $_SESSION['employee_number']; ?>" class="employee_number">

		<div class="row clearfix">
		   	<div class="col-md-6">
		      	<label class="form-label">Division/Unit</label>
      			<div class="form-group">
			        <div class="form-line division_select">
			            <select class="division_id form-control " name="division_id" id="division_id" data-live-search="true" readonly>
			                <option value=""></option>
			            </select>
			        </div>
			    </div>
		   	</div>
		   	<div class="col-md-6">
	            <label class="form-label">Employee Name</label>
                <div class="form-group">
                	<div class="form-line employee_select">
                		<select class="employee_id  form-control" id="employee_id" name="employee_id" data-live-search="true"readonly>
							<option value=""></option>
						</select>
                	</div>
            	</div>
	        </div>
		</div>

		<div class="row clearfix">
			<?php if($key !== "addOfficialBusinessRequest"): ?>
			<div class="col-md-4">
                <label class="form-label">Control No. <span class="text-danger">*</span></label> 
				<div class="form-group form-float">
					<div class="form-line">
						<input type="text" class="control_no form-control" name="control_no" id="control_no" required>
					</div>
				</div>
            </div>
			<?php endif; ?>
            <div class="col-md-4">
                <label class="form-label">Date of Filing</label>
				<div class="form-group form-float">
					<div class="form-line">
						<input type="text" class="filing_date form-control" name="filing_date" id="filing_date" value="<?php echo date("F d, Y"); ?>" readonly="readonly">
					</div>
				</div>
            </div>
            <div class="col-md-4">
                <label class="form-label">Position</label>
				<div class="form-group">
                	<div class="form-line position_select">
                		<select class="position_id  form-control" id="position_id" name="position_id" data-live-search="true">
							<option value=""></option>
						</select>
                	</div>
            	</div>
            </div>
		</div>

		<hr>

		<div class="row clearfix">
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-12"> 
						<div id="activity_name_content">
							<label class="form-label" style="font-size: 1.25rem">
								<small>Purpose:</small></label>
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="activity_name form-control" name="activity_name" id="activity_name" required>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12"> 
						<div id="activity_date_content">
							<label class="form-label" style="font-size: 1.25rem">
								<small>Date of Activity:</small> <span class="text-danger">*</span></label>
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="transaction_date form-control" name="transaction_date" id="transaction_date" readonly required>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12"> 
						<div id="activity_venue_content">
							<label class="form-label" style="font-size: 1.25rem">
								<small>Location:</small> <span class="text-danger">*</span> </label>
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="location form-control" name="location" id="location" required>
								</div>
							</div>
						</div>
					</div>
					<!-- <div class="col-md-12"> 
						<div id="activity_time_content">
							<label class="form-label" style="font-size: 1.25rem">
								<small>Time of Activity: </small></label>
							<div class="form-group form-float">
								<div class="form-line">
									<input type="time" class="activity_time form-control" name="activity_time" id="activity_time">
								</div>
							</div>
						</div>
					</div> -->
					<div class="col-md-12"> 
						<label class="form-label" style="font-size: 1.25rem">
							<small>Time of Activity:</small>
						</label>
						<br>
						<div class="row">
							<div class="col-md-6">
		        				<label class="form-label" style="font-size: 1.25rem"><small>Check In</small><span class="text-danger">*</span>
		        				</label>
		        				<div class="form-group">
		        					<div class="form-line">
		        						<input type="time" name="locator_transaction_time[]" id="locator_time_in" class="locator_time_in locator_transaction_time timepicker form-control">
		        						<input type="hidden" name="locator_transaction_type[]" value="0">
		        					</div>
		        				</div>
		        			</div>
		        			<div class="col-md-6">
		        				<label class="form-label" style="font-size: 1.25rem"><small>Check Out</small><span class="text-danger">*</span>
		        				</label>
		        				<div class="form-group">
		        					<div class="form-line">
		        						<input type="time" name="locator_transaction_time[]" id="locator_time_out" class="locator_time_out locator_transaction_time timepicker form-control">
		        						<input type="hidden" name="locator_transaction_type[]" value="1">
		        					</div>
		        				</div>
		        			</div>
		        			<!-- <div class="col-md-6">
		        				<label class="form-label" style="font-size: 1.25rem"><small>Break Out</small>
		        				</label>
		        				<div class="form-group">
		        					<div class="form-line">
		        						<input type="time" name="locator_transaction_time[]" id="locator_break_out" class="locator_break_out locator_transaction_time timepicker form-control">
		        						<input type="hidden" name="locator_transaction_type[]" value="2">
		        					</div>
		        				</div>
		        			</div>
		        			<div class="col-md-6" style="font-size: 1.25rem">
		        				<label class="form-label"><small>Break In</small>
		        				</label>
		        				<div class="form-group">
		        					<div class="form-line">
		        						<input type="time" name="locator_transaction_time[]" id="locator_break_in" class="locator_break_in locator_transaction_time timepicker form-control">
		        						<input type="hidden" name="locator_transaction_type[]" value="3">
		        					</div>
		        				</div>
		        			</div>	 -->
		        		</div>					
					</div>
					<br>

					<?php if($key == "addOfficialBusinessRequest"): ?>
					<div class="col-md-12">
						<label class="form-label">Upload Attachment
							<!-- <span class="text-danger">*</span> -->
						</label>
						<div class="form-group">
							<div class="form-line">
								<input type="file" name="file" id="file" class="file form-control">
							</div>
						</div>
					</div>
					<?php endif;?>
					<?php if($key == "updateOfficialBusinessRequest" || $key == "viewOfficialBusinessRequest" || $key == "viewLocatorSlips"): ?>
					<div class="col-md-12">
						<label class="form-label">Attachment
							<!-- <span class="text-danger">*</span> -->
						</label>
						<div id="updateFileButtons" class="form-group text-center">
							<div class="btn-group btn-group-justified" role="group" aria-label="Justified button group">
								<a href="#" id="viewAttachment" class="btn waves-effect" role="button" target="_blank">
									<i class="material-icons">pageview</i>
									<span>VIEW</span></a>
								<a href="#" id="downloadAttachment" class="btn bg-blue waves-effect" role="button" download>
									<i class="material-icons">arrow_downward</i>
									<span>DOWNLOAD</span></a>
								<?php if($key != "viewOfficialBusinessRequest" && $key != "viewLocatorSlips"): ?>
									<a href="#" id="changeAttachment" class="btn bg-gray waves-effect" role="button">
									<i class="material-icons">refresh</i>
									<span>CHANGE</span></a>
								<?php endif; ?>
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
					<?php endif;?>


				</div>
			</div>

			<div class="col-md-6">
			 	<div class="row clearfix">
			 		<div class="col-md-12">
				 		<label class="form-label" style="font-size: 1.25rem">
							<small>Purpose of Official Business</small>
						</label>
						<br>
						<input name="purpose" type="radio" id="radio_meeting" class="purpose with-gap radio-col-green" value="meeting" />
						<label for="radio_meeting">Meeting</label>
						<br>
						<input name="purpose" type="radio" id="radio_training_program" class="purpose with-gap radio-col-green" value="training_program"/>
						<label for="radio_training_program">Training Program</label>
						<br>
						<input name="purpose" type="radio" id="radio_seminar_conference" class="purpose with-gap radio-col-green" value="seminar_conference" />
						<label for="radio_seminar_conference">Seminar/Conference</label>
						<br>
						<input name="purpose" type="radio" id="radio_gov_transaction" class="purpose with-gap radio-col-green" value="gov_transaction"/>
						<label for="radio_gov_transaction">Government Transaction/s</label><br>
						<input name="purpose" type="radio" id="radio_others" class="purpose with-gap radio-col-green" value="others"/>
						<label for="radio_others">Others</label>
						<br>
					</div>
				</div>
			</div>
			
		</div>
		
		
	</div>
	<div class="text-right" style="width:100%;">
    	<?php if($key == "addOfficialBusinessRequest"): ?>
    		<button id="saveOfficialBusinessRequest" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">add</i><span> Add Record</span>
	        </button>
    	<?php endif; ?>
    	<?php if($key == "updateOfficialBusinessRequest"): ?>
	        <button id="updateOfficialBusinessRequest" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">save</i><span> Update Record</span>
	        </button>
        <?php endif; ?>
        <button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
            <i class="material-icons">close</i><span> Close</span>
        </button>
    </div>
</form>