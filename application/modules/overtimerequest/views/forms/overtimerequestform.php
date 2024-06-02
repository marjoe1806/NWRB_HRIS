
<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
	<div class="form-elements-container">
		<hr>
			<center><h4 class="text-info">Overtime Request Form</h4></center>
		<hr>
		<input type="hidden" name="id" value="" class="id">
		<input type="hidden" id="employee_number" name="employee_number" value="<?php echo $_SESSION['employee_number']; ?>" class="employee_number">

		<div class="row clearfix">
		<?php if($key == "addOvertimeRequest"):?>
		   	<div class="col-md-6">
		      	<label class="form-label">Division/Unit</label>
      			<div class="form-group">
			        <div class="form-line division_select">
			            <select class="division_id form-control " name="division_id" id="division_id" data-live-search="true" >
			                <option value=""></option>
			            </select>
			        </div>
			    </div>
		   	</div>
			<?php endif;?>
			<?php if($key == "viewOvertimeRequest"):?>
		   	<div class="col-md-6" style="pointer-events:none;">
		      	<label class="form-label">Division/Unit</label>
      			<div class="form-group">
			        <div class="form-line division_select">
			            <select class="division_id form-control " name="division_id" id="division_id" data-live-search="true" readonly>
			                <option value=""></option>
			            </select>
			        </div>
			    </div>
		   	</div>
			<?php endif;?>
			<?php if($key == "addOvertimeRequest"):?>
		   	<div class="col-md-6">
	            <label class="form-label">Employee Name</label>
                <div class="form-group">
                	<div class="form-line employee_select">
                		<select class="employee_id  form-control" id="employee_id" name="employee_id" data-live-search="true">
							<option value=""></option>
						</select>
                	</div>
            	</div>
	        </div>
			<?php endif;?>
			<?php if($key == "viewOvertimeRequest"):?>
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
			<?php endif;?>
		</div>

		<div class="row clearfix">
			<?php if($key !== "addOvertimeRequest"): ?>
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
			<?php if($key == "addOvertimeRequest"):?>
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
			<?php endif;?>
			<?php if($key == "viewOvertimeRequest"):?>
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
			<?php endif;?>
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
									<input type="text" class="activity_name form-control" name="activity_name" id="activity_name" value="Overtime Request" required>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12"> 
						<div id="activity_date_content">
							<label class="form-label" style="font-size: 1.25rem">
								<small>Date of Overtime:</small> <span class="text-danger">*</span></label>
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
						<div class="row">
							<div class="col-md-6">
		        				<label class="form-label" style="font-size: 1.25rem"><small>Time in</small><span class="text-danger">*</span>
		        				</label>
		        				<div class="form-group">
		        					<div class="form-line">
		        						<input type="time" name="time_in" id="time_in" class="time_in timepicker form-control">
		        					</div>
		        				</div>
		        			</div>
							<input type="hidden" name="overtime_transaction_time" id="overtime_transaction_time" value="<?php echo date('H:i:s')?>" class="overtime_transaction_time timepicker form-control">
		        			<div class="col-md-6">
		        				<label class="form-label" style="font-size: 1.25rem"><small>Time out</small><span class="text-danger">*</span>
		        				</label>
		        				<div class="form-group">
		        					<div class="form-line">
		        						<input type="time" name="time_out" id="time_out" class="time_out over timepicker form-control">
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

					<?php if($key == "addOvertimeRequest"): ?>
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
					<?php if($key == "updateOvertimeRequest" || $key == "viewOvertimeRequest" /*|| $key == "viewLocatorSlips"*/): ?>
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
								<?php if($key != "viewOvertimeRequest" /*&& $key != "viewLocatorSlips"*/): ?>
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
							<small>Type:<span class="text-danger">*</span></small>
						</label>
						<br>
						<input name="purpose" type="radio" id="radio_paid" class="purpose with-gap radio-col-green" value="paid" />
						<label for="radio_paid">WITH PAY</label>
						<br>
						<input name="purpose" type="radio" id="radio_cto" class="purpose with-gap radio-col-green" value="cto"/>
						<label for="radio_cto">FOR CTO</label>
						<br>
						<!--<input name="purpose" type="radio" id="radio_expected_time_return" class="purpose with-gap radio-col-green" value="expected_time_return" />
						<label for="radio_expected_time_return">Expected time of return</label>
						<div class="form-group expected_time_return_input" style="width:35%;display: none;">
        					<div class="form-line">
        						<input type="time" name="expected_return_time[]" id="expected_return" class="expected_return timepicker form-control">
        					</div>
        				</div>
        				<br>
						<input name="purpose" type="radio" id="radio_expected_not_back" class="purpose with-gap radio-col-green" value="expected_not_back"/>
						<label for="radio_expected_not_back">Expected not to be back</label><br>-->
						<!-- <input name="purpose" type="radio" id="radio_others" class="purpose with-gap radio-col-green" value="others"/>
						<label for="radio_others">Others</label> -->
						<br>
					</div>
				</div>
			</div>
			
		</div>
		
		
	</div>
	<div class="text-right" style="width:100%;">
    	<?php if($key == "addOvertimeRequest"): ?>
    		<button id="saveOvertimeRequest" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">add</i><span> Add Record</span>
	        </button>
    	<?php endif; ?>
    	<?php if($key == "updateOvertimeRequest"): ?>
	        <button id="updateOvertimeRequest" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">save</i><span> Update Record</span>
	        </button>
        <?php endif; ?>
        <button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
            <i class="material-icons">close</i><span> Close</span>
        </button>
    </div>
</form>