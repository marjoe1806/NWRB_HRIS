<?php 
	$readonly = "";
	if($key == "viewPendingLeaveSpecialDetails")
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
                	<div class="form-line">
                		<select class="employee employee_select form-control" id="employee_id" name="table1[employee]" data-live-search="true" required <?php echo $readonly ?>>
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
							<label class="form-label" style="font-size: 1.25rem">
								Type of Leave
								<small>(Please choose one)</small>
							</label>
						</p>
					</div>
				</div>
				<div class="row clearfix">
					<div class="col-md-6">
						<p>
							<input name="table1[type]" type="radio" id="radio_personal_milestone" class="type with-gap radio-col-green" value="personal_milestone"
							/>
							<label for="radio_personal_milestone">Personal Milestone</label>
						</p>
						<p>
							<input name="table1[type]" type="radio" id="radio_parental_obligation" class="type with-gap radio-col-green" value="parental_obligation"
							/>
							<label for="radio_parental_obligation">Parental Obligation</label>
						</p>
						<p>
							<input name="table1[type]" type="radio" id="radio_filial_obligations" class="type with-gap radio-col-green" value="filial_obligations"
							/>
							<label for="radio_filial_obligations">Filial Obligations</label>
						</p>
					</div>
					<div class="col-md-6">
						<p>
							<input name="table1[type]" type="radio" id="radio_domestic_emergencies" class="type with-gap radio-col-green" value="domestic_emergencies"
							/>
							<label for="radio_domestic_emergencies">Domestic Emergencies</label>
						</p>
						<p>
							<input name="table1[type]" type="radio" id="radio_personal_transactions" class="type with-gap radio-col-green" value="personal_transactions"
							/>
							<label for="radio_personal_transactions">Personal Transactions</label>
						</p>
						<p>
							<input name="table1[type]" type="radio" id="radio_accident" class="type with-gap radio-col-green" value="accident"
							/>
							<label for="radio_accident">Calamity, Accident, Hospitalization Leave</label>
						</p>
					</div>
				</div>
				<div class="row clearfix">
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
				</div>
				<?php if($key == "addPendingLeaveSpecial"): ?>
				<div class="col-md-12">
					<label class="form-label">Upload Attachment
						<span class="text-danger">*</span>
					</label>
					<div class="form-group">
						<div class="form-line">
							<input type="file" name="file" id="file" class="file required form-control">
						</div>
					</div>
				</div>
				<?php endif;?>
				<?php if($key == "updatePendingLeaveSpecial" || $key == "viewPendingLeaveSpecialDetails"): ?>
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
							<?php if($key != "viewPendingLeaveSpecialDetails"): ?>
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
			</div>
			<div class="col-md-6">

				<div class="row clearfix">
					<div class="col-md-12">
						<div id="additional_info_header">
							<p>
								<label class="form-label" style="font-size: 1.25rem">
									<i class="fa fa-exclamation-circle" aria-hidden="true"></i> Additional Information</label>
							</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div id="additional_info_content">
							<div class="row">
								<div class="col-md-12">
									<div id="milestone_content">
										<p>Birthdays / Wedding / Wedding Anniversary celebrations and other similar milestone, (including death anniversary).</p>
									</div>
									<div id="parental_content">
										<p>Attendance in school programs, PTA meetings, graduation, first communion, medical needs among others, where
											a child of a government employee is involved.</p>
									</div>
									<div id="filial_content">
										<p>Employee's moral obligation toward his parents and siblings for their medical and social needs.</p>
									</div>
									<div id="domestic_content">
										<p>Sudden urgent repairs needed at home, sudden absence of a yaya or maid, and the like.</p>
									</div>
									<div id="personal_content">
										<p>Entire range of transactions an individual does with goverment and private offices such as paying taxes,
											court appearances, arranging a housing loan, etc.</p>
									</div>
									<div id="calamity_content">
										<p>Pertain to force-majeure events that affect the life, limb and property of an employee or his immediate
											family.
										</p>
									</div>
								</div>
							</div>
							<div class="row clearfix">
								<div id="special_dates">
									<div class="col-md-12">
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
										<?php if($key == "addPendingLeaveRegular" || $key == "updatePendingLeaveRegular" || $key == "addPendingLeaveSpecial" || $key == "updatePendingLeaveSpecial"): ?>
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
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>
    <hr>
    <div class="text-right" style="width:100%;">
    	<?php if($key == "addPendingLeaveSpecial"): ?>
    		<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">add</i><span> Add Record</span>
	        </button>
    	<?php endif; ?>
    	<?php if($key == "updatePendingLeaveSpecial"): ?>
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

