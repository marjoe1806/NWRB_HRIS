<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>" method="POST">
	<div class="form-elements-container">
		<hr>
			<center><h4 class="text-info">APPLICATION FOR LEAVE</h4></center>
		<hr>
		<input type="hidden" name="table1[id]" value="" class="id">
		<input type="hidden" name="table3[currentdate]" value="<?php echo date('Y-m-d')?>">
		<div class="row clearfix">
			<?php if($key == "viewPendingLeaveDetails"):?>
			<div class="col-md-6" style="pointer-events:none ;">
			    <label class="form-label">Service/Division/Unit</label>
			    <div class="form-group">
			        <div class="form-line division_select">
			            <select class="division_id form-control " name="division_id" id="division_id" data-live-search="true">
			                <option value=""></option>
			            </select>
			        </div>
			    </div>
			</div>
			<?php else:?>
				<div class="col-md-6">
			    <label class="form-label">Service/Division/Unit</label>
			    <div class="form-group">
			        <div class="form-line division_select">
			            <select class="division_id form-control " name="division_id" id="division_id" data-live-search="true">
			                <option value=""></option>
			            </select>
			        </div>
			    </div>
			</div>
			<?php endif;?>
			<?php if($key == "viewPendingLeaveDetails"):?>
			<div class="col-md-6" style="pointer-events: none ;">
                <label class="form-label">Employee Name</label>
                <div class="form-group">
                	<div class="form-line employee_select">
                		<select class="employee_id  form-control" id="employee_id" name="table1[employee_id]" data-live-search="true">
							<option value=""></option>
						</select>
                	</div>
            	</div>
            </div>
			<?php else:?>
				<div class="col-md-6">
                <label class="form-label">Employee Name</label>
                <div class="form-group">
                	<div class="form-line employee_select">
                		<select class="employee_id  form-control" id="employee_id" name="table1[employee_id]" data-live-search="true">
							<option value=""></option>
						</select>
                	</div>
            	</div>
            </div>
			<?php endif;?>
		</div>
		<div class="row clearfix">
			<div class="col-md-4">
			    <label class="form-label">Date of Filing</label>
				<div class="form-group form-float">
					<div class="form-line">
						<input type="text" class="filing_date form-control" name="table1[filing_date]" id="filing_date" value="<?php echo date("F d, Y"); ?>" readonly="readonly">
					</div>
				</div>
			</div>
			<?php if($key == "viewPendingLeaveDetails"):?>
			<div class="col-md-4" style="pointer-events: none ;">
                <label class="form-label">Position</label>
                <div class="form-group">
                	<div class="form-line position_select">
                		<select class="position_id  form-control" id="position_id" name="table1[position_id]" data-live-search="true">
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
                		<select class="position_id  form-control" id="position_id" name="table1[position_id]" data-live-search="true">
							<option value=""></option>
						</select>
                	</div>
            	</div>
            </div>
			<?php endif;?>
            <div class="col-md-4">
                <label class="form-label">Salary</label>
				<div class="form-group form-float">
					<div class="form-line">
						<input type="text" class="salary form-control" name="table1[salary]" id="salary" readonly="readonly">
					</div>
				</div>
            </div>
		</div>

		<hr>
			<center><h4 class="text-info">DETAILS OF APPLICATION</h4></center>
		<hr>

		<div class="row clearfix">
		<?php if($key == "viewPendingLeaveDetails"):?>
			<div class="col-md-7" style="pointer-events: none;">
				<label class="form-label" style="font-size: 1.25rem">
					<small>Type of Leave</small>
				</label>
				<br>
				<input name="table1[type]" type="radio" id="radio_vacation" class="type with-gap radio-col-green" value="vacation" />
				<label for="radio_vacation">Vacation Leave <small>(Sec. 51, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</small></label>
				<br>
				<input name="table1[type]" type="radio" id="radio_force" class="type with-gap radio-col-green" value="force" />
				<label for="radio_force">Mandatory/Forced Leave <small>(Sec. 25, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</small></label>
				<br>
				<input name="table1[type]" type="radio" id="radio_sick" class="type with-gap radio-col-green" value="sick" />
				<label for="radio_sick">Sick Leave <small>(Sec. 43, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</small></label>
				<br>
				<input name="table1[type]" type="radio" id="radio_maternity" class="type with-gap radio-col-green" value="maternity" />
				<label for="radio_maternity">Maternity Leave <small>(R.A. No. 11210 / IRR issued by CSC, DOLE and SSS)</small></label>
				<br>
				<input name="table1[type]" type="radio" id="radio_paternity" class="type with-gap radio-col-green" value="paternity" />
				<label for="radio_paternity">Paternity Leave <small>(R.A. No. 8187 / CSC MC No. 71, s. 1998, as amended)</small></label>
				<br>
				<input name="table1[type]" type="radio" id="radio_special" class="type with-gap radio-col-green" value="special" />
				<label for="radio_special">Special Privilege Leave <small>(Sec. 21, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</small></label>
				<br>
				<input name="table1[type]" type="radio" id="radio_solo" class="type with-gap radio-col-green" value="solo" />
				<label for="radio_solo">Solo Parent Leave <small>(RA No. 8972 / CSC MC No. 8, s. 2004)</small></label>
				<br>
				<input name="table1[type]" type="radio" id="radio_study" class="type with-gap radio-col-green" value="study" />
				<label for="radio_study">Study Leave <small>(Sec. 68, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</small></label>
				<br>
				<input name="table1[type]" type="radio" id="radio_violence" class="type with-gap radio-col-green" value="violence" />
				<label for="radio_violence">10-Day VAWC Leave <small>(RA No. 9262 / CSC MC No. 15, s. 2005)</small></label>
				<br>
				<input name="table1[type]" type="radio" id="radio_rehab" class="type with-gap radio-col-green" value="rehab" />
				<label for="radio_rehab">Rehabilitation Privilege <small>(Sec. 55, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</small></label>
				<br>
				<input name="table1[type]" type="radio" id="radio_benefits" class="type with-gap radio-col-green" value="benefits" />
				<label for="radio_benefits">Special Leave Benefits for Women <small>(RA No. 9710 / CSC MC No. 25, s. 2010)</small></label>
				<br>
				<input name="table1[type]" type="radio" id="radio_calamity" class="type with-gap radio-col-green" value="calamity" />
				<label for="radio_calamity">Special Emergency (Calamity) Leave <small>(CSC MC No. 2, s. 2012, as amended)</small></label>
				<br>
				<input name="table1[type]" type="radio" id="radio_adoption" class="type with-gap radio-col-green" value="adoption" />
				<label for="radio_adoption">Adoption Leave <small> (R.A. No. 8552)</small></label>
				<br>
				<label class="form-label" style="font-size: 1.25rem">
					<small>Other Purpose</small>
				</label>
				<br>
				<input name="table1[type]" type="radio" id="radio_monetization" class="type with-gap radio-col-green" value="monetization" />
				<label for="radio_monetization">Monetization of Leave Credits</label>
				<div class="isMedicalReason" style="display: none;display: inline-block;margin-left: 25px;">
                    <div class="form-group">
                    	<input type="checkbox" class="chk" name="table1[isMedical]" id="isMedical">&nbsp;Medical / Calamities & Accidents / Education Needs / Payment of Mortgages & Loans / Extreme Financial Needs Reason
                	</div>
				</div>
				<br>
				<input name="table1[type]" type="radio" id="radio_terminal" class="type with-gap radio-col-green" value="terminal" />
				<label for="radio_terminal">Terminal Leave</label>
				<div class="form-group isTerminal" style="margin-bottom: -16px; display: none;">
					<label><small>Effective Date</small></label>
                    <div class="form-line">
                    	<input type="input" class="form-control datepicker" name="table1[isTerminal]" id="isTerminal" placeholder="yyyy-mm-dd">
                	</div>
				</div>
				<!-- <br>
				<input name="table1[type]" type="radio" id="radio_offset" class="type with-gap radio-col-green" value="offset" />
				<label for="radio_offset">Compensatory Time Off</label> -->
			</div>
			<div class="col-md-5">
                <div class="row">
                	<div class="col-md-12">
                		<div id="vacation_type_content">
							<label class="form-label" style="font-size: 1.25rem">
								<small>In case of Vacation / Special Privilege Leave</small>
							</label>
							<br>
							<input name="table1[vacation_loc]" type="radio" id="radio_vacation_philippines" class="vacation_loc with-gap radio-col-green" value="philippines" />
							<label for="radio_vacation_philippines">Within the Philippines</label>
							<br>
							<input name="table1[vacation_loc]" type="radio" id="radio_vacation_abroad" class="vacation_loc with-gap radio-col-green" value="abroad"
							/>
							<label for="radio_vacation_abroad">Abroad</label>
							<br>
							<div id="vacation_loc_content">
								<label class="form-label" style="font-size: 1.25rem">
									<small>Specify Location:<small></label>
								<div class="form-group form-float">
									<div class="form-line">
										<input type="text" class="vacation_loc_details form-control" name="table1[vacation_loc_details]" id="vacation_loc_details" disabled="disabled">
									</div>
								</div>
							</div>
						</div>
                	</div>
                	<div class="col-md-12">
                		<div id="vacation_type_content">
							<label class="form-label" style="font-size: 1.25rem">
								<small>In case of Sick Leave</small>
							</label>
							<br>
							<input name="table1[sick_loc]" type="radio" id="radio_sick_hospital" class="sick_loc with-gap radio-col-green" value="hospital" />
							<label for="radio_sick_hospital">In Hospital</label>
							<br>
							<input name="table1[sick_loc]" type="radio" id="radio_sick_out" class="sick_loc with-gap radio-col-green" value="out"
							 />
							<label for="radio_sick_out">Out Patient</label>
							<br>
							<div id="other_vacation_type_content">
								<label class="form-label" style="font-size: 1.25rem">
									<small>Specify Illness:<small></label>
								<div class="form-group form-float">
									<div class="form-line">
										<input type="text" class="sick_loc_details form-control" name="table1[sick_loc_details]" id="sick_loc_details" disabled="disabled">
									</div>
								</div>
							</div>
						</div>
                	</div>
                	<div class="col-md-12">
                		<div id="vacation_type_content">
							<label class="form-label" style="font-size: 1.25rem">
								<small>In case of Study Leave</small>
							</label>
							<br>
							<input name="table1[type_study]" type="radio" id="radio_study_master" class="type_study with-gap radio-col-green" value="master" disabled="disabled" />
							<label for="radio_study_master">Completion of Master's Degree</label>
							<br>
							<input name="table1[type_study]" type="radio" id="radio_study_bar" class="type_study with-gap radio-col-green" value="bar"
							disabled="disabled" />
							<label for="radio_study_bar">BAR/Board Examination Review</label>
						</div>
                	</div>
                </div>
            </div>
		</div>
		<?php else:?>
			<div class="col-md-7">
				<label class="form-label" style="font-size: 1.25rem">
					<small>Type of Leave</small>
				</label>
				<br>
				<input name="table1[type]" type="radio" id="radio_vacation" class="type with-gap radio-col-green" value="vacation" />
				<label for="radio_vacation">Vacation Leave <small>(Sec. 51, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</small></label>
				<br>
				<input name="table1[type]" type="radio" id="radio_force" class="type with-gap radio-col-green" value="force" />
				<label for="radio_force">Mandatory/Forced Leave <small>(Sec. 25, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</small></label>
				<br>
				<input name="table1[type]" type="radio" id="radio_sick" class="type with-gap radio-col-green" value="sick" />
				<label for="radio_sick">Sick Leave <small>(Sec. 43, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</small></label>
				<br>
				<input name="table1[type]" type="radio" id="radio_maternity" class="type with-gap radio-col-green" value="maternity" />
				<label for="radio_maternity">Maternity Leave <small>(R.A. No. 11210 / IRR issued by CSC, DOLE and SSS)</small></label>
				<br>
				<input name="table1[type]" type="radio" id="radio_paternity" class="type with-gap radio-col-green" value="paternity" />
				<label for="radio_paternity">Paternity Leave <small>(R.A. No. 8187 / CSC MC No. 71, s. 1998, as amended)</small></label>
				<br>
				<input name="table1[type]" type="radio" id="radio_special" class="type with-gap radio-col-green" value="special" />
				<label for="radio_special">Special Privilege Leave <small>(Sec. 21, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</small></label>
				<br>
				<input name="table1[type]" type="radio" id="radio_solo" class="type with-gap radio-col-green" value="solo" />
				<label for="radio_solo">Solo Parent Leave <small>(RA No. 8972 / CSC MC No. 8, s. 2004)</small></label>
				<br>
				<input name="table1[type]" type="radio" id="radio_study" class="type with-gap radio-col-green" value="study" />
				<label for="radio_study">Study Leave <small>(Sec. 68, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</small></label>
				<br>
				<input name="table1[type]" type="radio" id="radio_violence" class="type with-gap radio-col-green" value="violence" />
				<label for="radio_violence">10-Day VAWC Leave <small>(RA No. 9262 / CSC MC No. 15, s. 2005)</small></label>
				<br>
				<input name="table1[type]" type="radio" id="radio_rehab" class="type with-gap radio-col-green" value="rehab" />
				<label for="radio_rehab">Rehabilitation Privilege <small>(Sec. 55, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</small></label>
				<br>
				<input name="table1[type]" type="radio" id="radio_benefits" class="type with-gap radio-col-green" value="benefits" />
				<label for="radio_benefits">Special Leave Benefits for Women <small>(RA No. 9710 / CSC MC No. 25, s. 2010)</small></label>
				<br>
				<input name="table1[type]" type="radio" id="radio_calamity" class="type with-gap radio-col-green" value="calamity" />
				<label for="radio_calamity">Special Emergency (Calamity) Leave <small>(CSC MC No. 2, s. 2012, as amended)</small></label>
				<br>
				<input name="table1[type]" type="radio" id="radio_adoption" class="type with-gap radio-col-green" value="adoption" />
				<label for="radio_adoption">Adoption Leave <small> (R.A. No. 8552)</small></label>
				<br>
				<label class="form-label" style="font-size: 1.25rem">
					<small>Other Purpose</small>
				</label>
				<br>
				<input name="table1[type]" type="radio" id="radio_monetization" class="type with-gap radio-col-green" value="monetization" />
				<label for="radio_monetization">Monetization of Leave Credits</label>
				<div class="isMedicalReason" style="display: none;display: inline-block;margin-left: 25px;">
                    <div class="form-group">
                    	<input type="checkbox" class="chk" name="table1[isMedical]" id="isMedical">&nbsp;Medical / Calamities & Accidents / Education Needs / Payment of Mortgages & Loans / Extreme Financial Needs Reason
                	</div>
				</div>
				<br>
				<input name="table1[type]" type="radio" id="radio_terminal" class="type with-gap radio-col-green" value="terminal" />
				<label for="radio_terminal">Terminal Leave</label>
				<div class="form-group isTerminal" style="margin-bottom: -16px; display: none;">
					<label><small>Effective Date</small></label>
                    <div class="form-line">
                    	<input type="input" class="form-control datepicker" name="table1[isTerminal]" id="isTerminal" placeholder="yyyy-mm-dd">
                	</div>
				</div>
				<!-- <br>
				<input name="table1[type]" type="radio" id="radio_offset" class="type with-gap radio-col-green" value="offset" />
				<label for="radio_offset">Compensatory Time Off</label> -->
			</div>
			<div class="col-md-5">
                <div class="row">
                	<div class="col-md-12">
                		<div id="vacation_type_content">
							<label class="form-label" style="font-size: 1.25rem">
								<small>In case of Vacation / Special Privilege Leave</small>
							</label>
							<br>
							<input name="table1[vacation_loc]" type="radio" id="radio_vacation_philippines" class="vacation_loc with-gap radio-col-green" value="philippines" />
							<label for="radio_vacation_philippines">Within the Philippines</label>
							<br>
							<input name="table1[vacation_loc]" type="radio" id="radio_vacation_abroad" class="vacation_loc with-gap radio-col-green" value="abroad"
							/>
							<label for="radio_vacation_abroad">Abroad</label>
							<br>
							<div id="vacation_loc_content">
								<label class="form-label" style="font-size: 1.25rem">
									<small>Specify Location:<small></label>
								<div class="form-group form-float">
									<div class="form-line">
										<input type="text" class="vacation_loc_details form-control" name="table1[vacation_loc_details]" id="vacation_loc_details" disabled="disabled">
									</div>
								</div>
							</div>
							<div id="spl_other_content" style="display:none;">
								<label class="form-label" style="font-size: 1.25rem"> <small>Others: <small></label>
								<div class="form-group form-float">
									<div class="form-line">
										<input type="text" class="spl_other_details form-control" name="table1[spl_other_details]" id="spl_other_details" placeholder="MC2/MC6" disabled="disabled" require>
									</div>
								</div>
							</div>
						</div>
                	</div>
                	<div class="col-md-12">
                		<div id="vacation_type_content">
							<label class="form-label" style="font-size: 1.25rem">
								<small>In case of Sick Leave</small>
							</label>
							<br>
							<input name="table1[sick_loc]" type="radio" id="radio_sick_hospital" class="sick_loc with-gap radio-col-green" value="hospital" />
							<label for="radio_sick_hospital">In Hospital</label>
							<br>
							<input name="table1[sick_loc]" type="radio" id="radio_sick_out" class="sick_loc with-gap radio-col-green" value="out"
							 />
							<label for="radio_sick_out">Out Patient</label>
							<br>
							<div id="other_vacation_type_content">
								<label class="form-label" style="font-size: 1.25rem">
									<small>Specify Illness:<small></label>
								<div class="form-group form-float">
									<div class="form-line">
										<input type="text" class="sick_loc_details form-control" name="table1[sick_loc_details]" id="sick_loc_details" disabled="disabled">
									</div>
								</div>
							</div>
						</div>
                	</div>
                	<div class="col-md-12">
                		<div id="vacation_type_content">
							<label class="form-label" style="font-size: 1.25rem">
								<small>In case of Study Leave</small>
							</label>
							<br>
							<input name="table1[type_study]" type="radio" id="radio_study_master" class="type_study with-gap radio-col-green" value="master" disabled="disabled" />
							<label for="radio_study_master">Completion of Master's Degree</label>
							<br>
							<input name="table1[type_study]" type="radio" id="radio_study_bar" class="type_study with-gap radio-col-green" value="bar"
							disabled="disabled" />
							<label for="radio_study_bar">BAR/Board Examination Review</label>
						</div>
                	</div>
                </div>
            </div>
		</div>
		<?php endif;?>

		<hr>
		<?php if(isset($data['employee'])): ?>
		<div class="row">
			<div class="col-md-2">
		        <div class="form-group">
					<label class="form-label">Force Leave Available</label>
					<input type="number" class="form-control" id="fl_leave" value="<?php echo (isset($data['employee']['forceLeave']))?$data['employee']['forceLeave']:0; ?>" readonly>
				</div>
			</div>
			<div class="col-md-2">
		        <div class="form-group">
					<label class="form-label">Special Leave Available</label>
					<input type="number" class="form-control" id="spl_leave" value="<?php echo (isset($data['employee']['specialLeave']))?$data['employee']['specialLeave']:0; ?>" readonly>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-2">
		        <div class="form-group">
				<label class="form-label">Vacation Leave Available</label>
				<input type="number" class="form-control" id="vl_leave" name="table1[vl]" value="<?php echo (isset($data['employee']['vl'])?$data['employee']['vl']:0); ?>"readonly>
				</div>
			</div>
			<div class="col-md-2">
		        <div class="form-group">
					<label class="form-label">Sick Leave Available</label>
					<input type="number" class="form-control" id="sl_leave" name="table1[sl]" value="<?php echo (isset($data['employee']['sl']))?$data['employee']['sl']:0; ?>" readonly>
				</div>
			</div>
			<div class="col-md-2">
		        <div class="form-group">
					<label class="form-label">Total Leave Available</label>
					<input type="number" class="form-control" id="total_available_leave" value="<?php echo (isset($data['employee']['leaveCreditsTotal'])?$data['employee']['leaveCreditsTotal']:0); ?>" readonly>
				</div>
			</div>
		</div>
		<?php endif; ?>
		<div class="row clearfix">
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-6 inclusiveDates">
		                <label class="form-label">Number of Working Days applied for </label>
		                <div class="form-group">
		                	<div class="form-line">
		                		<input type="number" value="1" class="number_of_days form-control" name="table1[number_of_days]" id="number_of_days" required readonly>
		                	</div>
		            	</div>
		            </div>
					<div class="col-md-6 inclusiveDates">
		                <label class="form-label">Total Amount Monetized </label>
		                <div class="form-group">
		                	<div class="form-line">
		                		<input type="text" value="0.00" class="amount_monetized form-control" name="table1[amount_monetized]" readonly>
		                	</div>
		            	</div>
		            </div>
		            <div class="col-md-12 inclusiveDates hide_inclusive_dates">
						<label class="form-label">Inclusive Dates <span class="text-danger">*</span></label>
					</div>

				</div>
				<div class="row days_of_container clearfix inclusiveDates">
					<div class="col-md-10">
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" class="days_of_leave form-control" name="table2[days_of_leave][0]" placeholder="yyyy-mm-dd" required>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 rehabDates" style="display: none;">
		                <label class="form-label">Inclusive Date Range </label>
		                <div class="form-group">
		                	<div class="form-line">
		                		<input type="text" class="rehabilitation_dates form-control" name="table2[days_of_leave2[]" id="rehabilitation_dates" required readonly>
		                	</div>
		            	</div>
		            </div>
				</div>
			</div>
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
				<br>
				<div class="row clearfix">
					<?php if($key == "addLeaveRequest"): ?>
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
					<?php if($key == "updateLeaveRequest" || $key == "viewLeaveRequestDetails" || $key == "viewPendingLeaveDetails"): ?>
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
								<?php if($key != "viewLeaveRequestDetails" && $key != "viewPendingLeaveDetails"): ?>
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
		</div>
	</div>
	<div class="text-right" style="width:100%;">
    	<?php if($key == "addLeaveRequest"): ?>
    		<button id="saveLeaveRequest" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">add</i><span> Add Record</span>
	        </button>
    	<?php endif; ?>
    	<?php if($key == "updateLeaveRequest"): ?>
	        <button id="updateLeaveRequest" class="btn btn-primary btn-sm waves-effect" type="submit">
	            <i class="material-icons">save</i><span> Update Record</span>
	        </button>
        <?php endif; ?>
        <button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
            <i class="material-icons">close</i><span> Close</span>
        </button>
    </div>
</form>