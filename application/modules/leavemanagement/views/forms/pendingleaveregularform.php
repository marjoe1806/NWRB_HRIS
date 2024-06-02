<?php 
	$readonly = "";
	if($key == "viewPendingLeaveRegularDetails")
		$readonly = "disabled";
?>
<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
    <div class="form-elements-container">
    	<input type="hidden" name="table1[id]" value="" class="id">
		<!-- <div id="form-user" role="form" data-toggle="validator"> -->
		<div class="row clearfix">
			<div class="col-md-6">
			    <label class="form-label">Department <span class="text-danger">*</span></label>
			    <div class="form-group">
			        <div class="form-line division_select">
			            <select class="division_id form-control " name="division_id" id="division_id" data-live-search="true" >
			                <option value=""></option>
			            </select>
			        </div>
			    </div>
			    <!-- <label class="form-label">Leave Group <span class="text-danger">*</span></label>
			    <div class="form-group">
			        <div class="form-line leave_grouping_select">
			            <select class="leave_grouping_id form-control " name="leave_grouping_id" id="leave_grouping_id" data-live-search="true" >
			                <option value=""></option>
			            </select>
			        </div>
			    </div> -->
			</div>
			<div class="col-md-6">
                <label class="form-label">Employee Name <span class="text-danger">*</span></label>
                <div class="form-group">
                	<div class="form-line employee_select">
                		<select class="employee_id  form-control" id="employee_id" name="table1[employee_id]" data-live-search="true" required <?php echo $readonly ?>>
							<option value=""></option>
						</select>
                	</div>
            	</div>
            </div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="row clearfix">
					<div class="col-md-12">
						<p>
							<label class="form-label" style="font-size: 1.25rem">Type of Leave
								<small>(Please choose one)</small>
							</label>
						</p>
					</div>
				</div>
				<div class="row clearfix">
					<div class="col-md-4">
						<input name="table1[type]" type="radio" id="radio_vacation" class="type with-gap radio-col-green" value="vacation" />
						<label for="radio_vacation">Vacation</label>
						<br>
						<input name="table1[type]" type="radio" id="radio_sick" class="type with-gap radio-col-green" value="sick" />
						<label for="radio_sick">Sick</label>
						<br>
						<input name="table1[type]" type="radio" id="radio_MC6" class="type with-gap radio-col-green" value="MC6" />
						<label for="radio_MC6">MC6</label>
						<br>
						<input name="table1[type]" type="radio" id="radio_MC8" class="type with-gap radio-col-green" value="MC8" />
						<label for="radio_MC8">MC8</label>
						<br>
						<input name="table1[type]" type="radio" id="radio_maternity" class="type with-gap radio-col-green" value="maternity" />
						<label for="radio_maternity">Maternity</label>
						<br>
						<input name="table1[type]" type="radio" id="radio_paternity" class="type with-gap radio-col-green" value="paternity" />
						<label for="radio_paternity">Paternity</label>
						<br>
						<input name="table1[type]" type="radio" id="radio_force" class="type with-gap radio-col-green" value="force" />
						<label for="radio_force">Force Leave</label>
						<br>
						<input name="table1[type]" type="radio" id="radio_monetization" class="type with-gap radio-col-green" value="monetization" />
						<label for="radio_monetization">Monetization</label>
						<br>
						<input name="table1[type]" type="radio" id="radio_others" class="type with-gap radio-col-green" value="others" />
						<label for="radio_others">Others</label>
					</div>
					<div class="col-md-8">
						<div id="vacation_type_content">
							<label class="form-label" style="font-size: 1.25rem">
								<small>If vacation, choose type:</small>
							</label>
							<br>
							<input name="table1[type_vacation]" type="radio" id="radio_vacation_seek" class="type_vacation with-gap radio-col-green" value="seek" />
							<label for="radio_vacation_seek">To seek employment</label>
							<br>
							<input name="table1[type_vacation]" type="radio" id="radio_vacation_others" class="type_vacation with-gap radio-col-green" value="others"
							/>
							<label for="radio_vacation_others">Others</label>
							<br>
							<div id="other_vacation_type_content">
								<label class="form-label" style="font-size: 1.25rem">
									<small>If others, please specify:<small></label>
								<div class="form-group form-float">
									<div class="form-line">
										<input type="text" class="type_vacation_others form-control" name="table1[type_vacation_others]" id="type_vacation_others" required>
									</div>
								</div>
							</div>
						</div>
						<div id="other_type_content">
							<label class="form-label" style="font-size: 1.25rem">
								<small>If others, please specify:</label>
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="type_others form-control" name="table1[type_others]" id="type_others" required>
								</div>
							</div>
						</div>
						<div id="force_type_content">
							<label class="form-label" style="font-size: 1.25rem">
								<small>Status:</small>
							</label>
							<br>
							<input name="table1[force_status]" type="radio" id="force_status1" class="force_status with-gap radio-col-green" value="1" />
							<label for="force_status1">Approved</label>
							<br>
							<input name="table1[force_status]" type="radio" id="force_status0" class="force_status with-gap radio-col-green" value="0"
							/>
							<label for="force_status0">Disapproved</label>
							<br>
							<div id="force_remarks">
								<label class="form-label" style="font-size: 1.25rem">
									<small>If disapproved, please specify reason:<small></label>
								<div class="form-group form-float">
									<div class="form-line">
										<input type="text" class="force_remarks form-control" name="table1[force_remarks]" id="force_remarks" required>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row clearfix">
					<div class="col-md-12">
						<div id="vacation_sick_header">
							<p>
								<label class="form-label" style="font-size: 1.25rem">
									Where Leave will be spent:</label>
							</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="vacation_spent_content">
						<div class="col-md-12">
							<p>
								<label class="form-label" style="font-size: 1.25rem">
									<small>In case of vacation leave</label>
							</p>
							<p>
								<div class="switch">
									<label>Domestic
										<input type="checkbox" id="type_vacation_location" name="table1[type_vacation_location]" class="type_vacation_location" value="Abroad">
										<span class="lever switch-col-grey"></span>Abroad</label>
								</div>
							</p>
							<div id="abroad_location_content">
								<hr style="padding: 0; margin: 9px 0 9px 0">
								<p>
									<label class="form-label" style="font-size: 1.25rem">
										<small>If abroad, please specify where:</label>
									<div class="form-group form-float">
										<div class="form-line">
											<input type="text" class="type_vacation_location_specific form-control" id="type_vacation_location_specific" name="table1[type_vacation_location_specific]" required>
										</div>
									</div>
								</p>
							</div>
						</div>
					</div>
					<div id="sick_spent_content">
						<div class="col-md-12">
							<p>
								<label class="form-label" style="font-size: 1.25rem">
									<small>In case of sick leave</label>
							</p>
							<p>
								<div class="switch">
									<label>Home
										<input type="checkbox" id="type_sick_location" class="type_sick_location" name="table1[type_sick_location]" value="Hospital">
										<span class="lever switch-col-grey"></span>Hospital</label>
								</div>
							</p>
							<div id="hospital_name_content">
								<hr style="padding: 0; margin: 9px 0 9px 0">
								<p>
									<label class="form-label" style="font-size: 1.25rem">
										<small>Please specify hospital name:</label>
									<div class="form-group form-float">
										<div class="form-line">
											<input type="text" class="type_sick_location_hospital form-control" id="type_sick_location_hospital" name="table1[type_sick_location_hospital]" required>
										</div>
									</div>
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<hr style="padding: 0; margin: 0 0 25px 0">
		<div class="row">
			<?php if($key == "addPendingLeaveRegular"): ?>
			<div class="col-md-6">
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
			<?php if($key == "updatePendingLeaveRegular" || $key == "viewPendingLeaveRegularDetails"): ?>
			<div class="col-md-6">
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
						<?php if($key != "viewPendingLeaveRegularDetails"): ?>
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
			<?php endif; ?>
			<div class="col-md-6">
				<div class="row clearfix">
					<div class="col-md-12">
						<p>
							<label class="form-label" style="font-size: 1.25rem">
								Commutation</label>
						</p>
					</div>
				</div>
				<div class="row clearfix">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-2">
								<!--  -->
							</div>
							<div class="col-md-12">
								<div class="switch">
									<label>Not Requested
										<input type="checkbox" class="commutation" id="commutation" name="table1[commutation]" value="Requested">
										<span class="lever switch-col-grey"></span>Requested</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="row clearfix">
					<div class="col-md-12">
						<p>
							<label class="form-label" style="font-size: 1.25rem">
								Days of Leave:</label>
						</p>
					</div>
				</div>
				<div class="days_of_container">
					<div class="row days_row clearfix">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-10">
									<label class="form-label" style="font-size: 1.25rem">
										<!-- <small>From</small> -->
									</label>
									<div class="form-group form-float">
										<div class="form-line">
											<input type="text" class="days_of_leave daysleave form-control" name="table2[days_of_leave][]" placeholder="yyyy-mm-dd" required>
										</div>
									</div>
								</div>
								<div class="col-md-2"></div>
							</div>
						</div>
					</div>
				</div>
				<?php if($key == "addPendingLeaveRegular" || $key == "updatePendingLeaveRegular"): ?>
				<div class="row clearfix">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-10">
							</div>
							<div class="col-md-2 text-right">
								<button type="button" class="addLeaveDays btn btn-info btn-circle waves-effect waves-circle waves-float">
									<i class="material-icons">add</i>
								</button>
							</div>
						</div>
					</div>
				</div>
				<?php endif; ?>
			</div>
			<div class="col-md-6">
				<div class="row clearfix">
					<div class="col-md-3">
						<p>
							<label class="form-label" style="font-size: 1.25rem">
								No. of Days</label>
						</p>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="number" min="0" value="1" class="number_of_days form-control" name="table1[number_of_days]" required readonly>
							</div>
						</div>
					</div>
					<div class="col-md-9">
						<p>
							<label class="form-label" style="font-size: 1.25rem">
								Date Filed <span class="text-danger">*</span></label>
						</p>
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" class="date_filed daysleave form-control" name="table1[date_filed]" placeholder="yyyy-mm-dd" required>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>
    <div class="text-right" style="width:100%;">
    	<?php if($key == "addPendingLeaveRegular"): ?>
    		<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">add</i><span> Add Record</span>
	        </button>
    	<?php endif; ?>
    	<?php if($key == "updatePendingLeaveRegular"): ?>
	        <button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">save</i><span> Update Record</span>
	        </button>
	        <!-- <?php if($status == "INACTIVE"): ?>
		        <a id="activateUserLevelConfig" class="activateUserLevelConfig" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.'activateUserLevelConfig'; ?>">
		            <button class="btn btn-success btn-sm waves-effect" type="button">
		                <i class="material-icons">visibility</i><span> Activate</span>
		            </button>
	            </a>
	        <?php endif; ?>
	        <?php if($status == "ACTIVE"): ?>
            <a id="deactivateUserLevelConfig" class="deactivateUserLevelConfig" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.'deactivateUserLevelConfig'; ?>">
	            <button  class="btn btn-danger btn-sm waves-effect" type="button">
	                <i class="material-icons">visibility_off</i><span> Deactivate</span>
	            </button>
            </a>
            <?php endif; ?> -->
            
        <?php endif; ?>
        <button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
            <i class="material-icons">close</i><span> Close</span>
        </button>
    </div>
</form>

