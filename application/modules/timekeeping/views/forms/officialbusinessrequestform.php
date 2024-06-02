<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
	<div class="form-elements-container">
		<hr>
			<center><h4 class="text-info">Personnel Locator Slip Form</h4></center>
		<hr>
		<input type="hidden" name="id" value="" class="id">
		<!-- <input type="hidden" name="employee_id" value="<?php //echo $employee_id; ?>" class="user_employee_id"> -->
		<!-- <input type="hidden" name="division_id" value="<?php //echo $division_id; ?>"  class="user_division_id">
		<input type="hidden" name="userlevel_id" value="<?php //echo $userlevel_id; ?>" class="userlevel_id"> -->
		<!-- <input type="hidden" name="canselectmultiple" value="<?php //echo $canselectmultiple; ?>" class="canselectmultiple"> -->
		<input type="hidden" id="employee_number" name="employee_number" value="<?php echo $_SESSION['employee_number']; ?>" class="employee_number">

		<div class="row clearfix">		   	 
			<?php if($key == "viewOfficialBusinessRequest"):?>
		   	<div class="col-md-6" style="pointer-events:none;">
		      	<label class="form-label">Division/Unit</label>
      			<div class="form-group">
			        <div class="form-line division_select">
			            <select class="division_id form-control " name="division_id" id="division_id" data-live-search="true" required>
			                <option value=""></option>
			            </select>
			        </div>
			    </div>
		   	</div>
			<?php else:?>
				<div class="col-md-6">
		      	<label class="form-label">Division/Unit</label>
      			<div class="form-group">
			        <div class="form-line division_select">
			            <select class="division_id form-control" name="division_id" id="division_id" data-live-search="true" required>
			                <option value=""></option>
			            </select>
			        </div>
			    </div>
		   	</div>
			<?php endif;?>		   
			<?php if($key == "viewOfficialBusinessRequest"):?>
		   	<div class="col-md-6" style="pointer-events:none ;">
	            <label class="form-label">Employee Name</label>
                <div class="form-group">
                	<div class="form-line employee_select">
                		<select class="employee_id  form-control" id="employee_id" name="employee_id" data-live-search="true">
							<option value=""></option>
						</select>
                	</div>
            	</div>
	        </div>
			
			<?php else:?>
			<div class="col-md-6">
				<label class="form-label emplabel">Employee Name</label>
                <div class="form-group">
                	<div class="form-line employee_select">
                		<select class="employee_id  form-control" id="employee_id" name="employee_id" data-live-search="true" >
							<option value=""></option>
						</select>
                	</div>
            	</div>
	        </div>
			<?php endif;?>
		</div>
		<div class="row clearfix">
			<?php if($key !== "addOfficialBusinessRequest"): ?>
			<div class="col-md-4" style="display:none;">
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
                <label class="form-label">Driver</label> 
				<div class="form-group form-float">
					<div class="form-line">
						<input type="text" class="driver form-control" name="driver" id="driver" readonly>
					</div>
				</div>
            </div>
			
            <div class="col-md-4">
                <label class="form-label">Vehicle</label>
				<div class="form-group form-float">
					<div class="form-line">
						<input type="text" class="vehicle form-control" name="vehicle" id="vehicle" readonly>
					</div>
				</div>
            </div>
			<!-- CODE FOR POSITION -->
			<!-- <?php if($key == "viewOfficialBusinessRequest"):?>
            <div class="col-md-4" style="pointer-events:none;">
                <label class="form-label">Position</label>
				<div class="form-group">
                	<div class="form-line position_select">
                		<select class="position_id  form-control" id="position_id" name="position_id" data-live-search="true">
							<option value=""></option>
						</select>
                	</div>
            	</div>
            </div>
			<?php else:?>
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
			<?php endif;?> -->
			<!-- END -->
		</div>
		<hr>

		<div class="row clearfix">
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-12"> 
						<div id="activity_name_content">
							<label class="form-label" style="font-size: 1.25rem">
								<small>Reason</small> <span class="text-danger">*</span> </label>
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="activity_name form-control" name="activity_name" id="activity_name" required>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12"> 
						<div id="activity_venue_content">
							<label class="form-label" style="font-size: 1.25rem">
								<small>Location</small> <span class="text-danger">*</span> </label>
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="location form-control" name="location" id="location" required>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12"> 
						<div class="row">
							<div class="col-md-6">
		        				<label class="form-label" style="font-size: 1.25rem"><small>Time out</small> <span class="text-danger">*</span>
		        				</label>
		        				<div class="form-group">
		        					<div class="form-line">
		        						<input type="time" name="locator_transaction_time[]" id="locator_time_in" class="locator_time_in locator_transaction_time timepicker form-control">
		        						<input type="hidden" name="locator_transaction_type[]" value="0">
		        					</div>
		        				</div>
		        			</div>
							<div class="col-md-6 expected_time_return_input" style="display: none;">
		        				<label class="form-label" style="font-size: 1.25rem"><small>Expected time of return</small> <span class="text-danger">*</span>
		        				</label>
		        				<div class="form-group">
		        					<div class="form-line">
										<input type="time" name="expected_return_time[]" id="expected_return" class="expected_return timepicker form-control">
		        					</div>
		        				</div>
		        			</div>
		        		</div>					
					</div>
					<br>

					<?php if($key == "addOfficialBusinessRequest"): ?>
					<div class="col-md-12" style="display:none;">
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
					<div class="col-md-12"  style="display:none;">
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
				<div class="col-md-12"> 
					<div id="activity_date_content">
						<label class="form-label" style="font-size: 1.25rem">
							<small>Date of Activity</small> <span class="text-danger">*</span></label>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" class="transaction_date form-control" name="transaction_date" id="transaction_date" readonly required>
							</div>
						</div>
					</div>
				</div>
			<!-- For Vehicle -->
			<div class="col-md-12"> 
					<div id="vehicle_content">
						<label class="form-label" style="font-size: 1.25rem;text-align:left;">
						<small>No / With Vehicle</small> <span class="text-danger">*</span> 
						</label>
						<div class="row">
							<div class="col-md-6">
								<input name="is_vehicle" type="radio" id="radio_2" class="is_vehicle with-gap radio-col-green" value="2" />
								<label for="radio_2">With Vehicle</label><br>
							</div>
							<div class="col-md-6">
							<input name="is_vehicle" type="radio" id="radio_3" class="is_vehicle with-gap radio-col-green" value="3"/>
								<label for="radio_3">No Vehicle</label>
								<br>
							</div>
						</div>
					</div>
				</div>
				<!-- End -->
				<div class="col-md-12"> 
					<div id="purpose_content">
						<label class="form-label" style="font-size: 1.25rem;text-align:left;">
							<small>Purpose</small> <span class="text-danger">*</span> 
						</label>
						<div class="row">
							<div class="col-md-6">
								<input name="purpose" type="radio" id="radio_official" class="purpose with-gap radio-col-green" value="official" />
								<label for="radio_official">Official</label><br>
								<input name="purpose" type="radio" id="radio_personal" class="purpose with-gap radio-col-green" value="personal"/>
								<label for="radio_personal">Personal</label>
							</div>
							<div class="col-md-6">
								<input name="is_return" type="radio" id="radio_1" class="is_return with-gap radio-col-green" value="1" />
								<label for="radio_1">Expected time of return</label>
								<input name="is_return" type="radio" id="radio_0" class="is_return with-gap radio-col-green" value="0"/>
								<label for="radio_0">Expected not to be back</label><br>
								<!-- <input name="purpose" type="radio" id="radio_others" class="purpose with-gap radio-col-green" value="others"/>
								<label for="radio_others">Others</label> -->
								<br>
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
	<div class="text-right" style="width:100%;">
    	<?php if($key == "addOfficialBusinessRequest"): ?>
    		<button id="saveOfficialBusinessRequest" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">add</i><span> Add Records</span>
	        </button>
    	<?php endif; ?>
    	<?php if($key == "updateOfficialBusinessRequest"): ?>
	        <button id="updateOfficialBusinessRequest" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">save</i><span> Update Record</span>
	        </button>
        <?php endif; ?>
        <button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
            <i class="material-icons" id="cancel">close</i><span> Close</span>
        </button>
    </div>
</form>