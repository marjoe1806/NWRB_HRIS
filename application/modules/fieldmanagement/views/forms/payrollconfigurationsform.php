<?php
	$readonly = "";
	if($key == "viewPayrollSettingsPayrollSetupDetails")
		$readonly = "disabled";
?>
<form id="<?php echo $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>"
 method="POST">
	<div class="form-elements-container">
		<div class="row clearfix">
			<div class="col-md-4">
				<h4 class="text-info">Payroll Year</h4>
				<div class="input-group">
					<span class="input-group-addon">
						<i class="material-icons">access_time</i>
					</span>
					<div class="form-line">
						<input type="text" class="form-control" readonly value="<?php echo date(" Y "); ?>" <?php echo $readonly; ?>>
					</div>
				</div>
			</div>
			<div class="col-md-8">
				<h4 class="text-info">Branch</h4>
				<div class="input-group">
					<span class="input-group-addon">
						<i class="material-icons">account_balance</i>
					</span>
					<div class="form-line">
					<?php foreach($data as $row):?>
						<input type="text" id="branch" name="branch"   class="branch form-control" value="<?=$row['branch'];?>" <?php echo $readonly; ?>>
					<?php endforeach;?>
					</div>
				</div>
			</div>
		</div>
		<div class="row clearfix">
			<div class="col-md-4">
				<h4 class="text-info">Tax Identification Number</h4>
				<div class="input-group">
					<span class="input-group-addon">
						<i class="material-icons">verified_user</i>
					</span>
					<div class="form-line">
						<input type="text" id="tax_identification_number" name="tax_identification_number" class="tax_identification_number form-control"
						 placeholder="000-000-000-000" <?php echo $readonly; ?>>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<h4 class="text-info">GSIS Number</h4>
				<div class="input-group">
					<span class="input-group-addon">
						<i class="material-icons">verified_user</i>
					</span>
					<div class="form-line">
						<input type="text" id="sss_number" name="sss_number" class="sss_number form-control" placeholder="00-0000000-0"
						 <?php echo $readonly; ?>>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<h4 class="text-info">Account Number</h4>
				<div class="input-group">
					<span class="input-group-addon">
						<i class="material-icons">verified_user</i>
					</span>
					<div class="form-line">
						<input type="text" id="account_number" name="account_number" class="account_number form-control" placeholder="0-00-0000000-0"
						 <?php echo $readonly; ?>>
					</div>
				</div>
			</div>
		</div>
		<div class="row clearfix">
			<!-- <div class="col-md-6">
				<div class="card">
					<div class="header">
						<b>Monthly</b>
					</div>
					<div class="body">
						<div class="input-group">
							<span class="input-group-addon" style="font-size: 1.15rem">
								<p id="number_of_days_year_label">No. of days in a full-time work year:</p>
							</span>
							<div class="form-line">
								<input type="number" id="number_of_days_year" name="number_of_days_year" class="number_of_days_year form-control" <?php echo
								$readonly; ?>>
							</div>
						</div>
						<div class="input-group">
							<span class="input-group-addon" style="font-size: 1.15rem">
								<p id="number_of_days_month_label">No. of days in a full-time work month:</p>
							</span>
							<div class="form-line">
								<input type="number" id="number_of_days_month" name="number_of_days_month" class="number_of_days_month form-control" <?php
								echo $readonly; ?>>
							</div>
						</div>
						<div class="text-center">
							<label class="form-label" style="font-size: 1rem">
								<u>Salary rate computation will be based on:</u>
							</label>
							<div class="form-group">
								<div class="switch">
									<label>Work Year
										<input type="checkbox" name="computation_based_on" id="computation_based_on" class="computation_based_on" value="1" <?php
										echo $readonly; ?>>
										<span class="lever switch-col-grey"></span> Work Month</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div> -->
			<div class="col-md-12">
				<!-- <div class="card">
					<div class="header">
						<b>Weekly</b>
					</div>
					<div class="body" style="margin-bottom: 0; padding-bottom: 2px">
						<div class="input-group">
							<span class="input-group-addon" style="font-size: 1.15rem">
								No. of days for the current pay period:
							</span>
							<div class="form-line">
								<input type="number" id="number_of_days_current_period" name="number_of_days_current_period" class="number_of_days_current_period form-control"
								<?php echo $readonly; ?>>
							</div>
						</div>
					</div>
				</div> -->
				<div class="row">
					<div class="col-md-8">
						<div class="card">
							<div class="header">
								<b>GSIS Contribution</b>
							</div>
							<div class="body" style="margin-bottom: 0; padding-bottom: 0">
								<div class="row" style="margin: 0; padding: 0">
									<div class="col-md-6">
										<b>Employee Share (%): </b>
										<div class="input-group">
											<div class="form-line">
												<input type="number" id="employee_gsis_rate" name="employee_gsis_rate" class="employee_gsis_rate form-control"
												 <?php echo $readonly; ?>>
											</div>
											<!-- <div class="text-center">(P10,000.00 and below)</div> -->
										</div>
									</div>
									<div class="col-md-6">
										<b>Employer Share (%) :</b>
										<div class="input-group">
											<div class="form-line">
												<input type="number" id="employer_gsis_rate" name="employer_gsis_rate" class="employer_gsis_rate form-control"
												 <?php echo $readonly; ?>>
											</div>
											<!-- <div class="text-center">(P10,000.01 to P39,999.99)</div> -->
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="card">
							<div class="header">
								<b>Pagibig Contribution</b>
							</div>
							<div class="body" style="margin-bottom: 0; padding-bottom: 0">
								<div class="row" style="margin: 0; padding: 0">
									<div class="col-md-12">
										<b>Employer Share Amount: </b>
										<div class="input-group">
											<div class="form-line">
												<input type="number" id="employer_pagibig_amount" name="employer_pagibig_amount" class="employer_pagibig_amount form-control"
												 <?php echo $readonly; ?>>
											</div>
											<!-- <div class="text-center">(P10,000.00 and below)</div> -->
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="card">
					<div class="header">
						<b>PhilHealth Contribution</b>
					</div>
					<div class="body" style="margin-bottom: 0; padding-bottom: 0">
						<div class="row" style="margin: 0; padding: 0">
							<div class="col-md-4">
								<b>Fixed Amount: </b>
								
								<div class="input-group">
									<div class="form-line">
										<input type="number" style="text-align: right;" id="philhealth_rate_1" name="philhealth_rate_1" class="philhealth_rate_1 form-control" <?php echo $readonly; ?>>
									</div>
									<div class="row">
										<div class="col-md-3"></div>
										<div class="col-md-6" ><input type="text"  style="text-align: center;" id="phil_range_1" class="phil_range_1 form-control" name="phil_range_1"  placeholder="( P 10,000.00 and below )" <?php echo $readonly; ?>></div>
										<div class="col-md-3"></div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<b>Percentage (%) :</b>
								<div class="input-group">
									<div class="form-line">
										<input type="number" style="text-align: right;" id="philhealth_rate_2" name="philhealth_rate_2" class="philhealth_rate_2 form-control" <?php echo $readonly; ?>>
									</div>
									<div class="row">
										<div class="col-md-3"></div>
										<div class="col-md-6"><input type="text"  style="text-align: center;" id="phil_range_2" class="phil_range_2 form-control" name="phil_range_2"  placeholder="( P 11,000.01 and 79,999.99 )" <?php echo $readonly; ?>></div>
										<div class="col-md-3"></div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<b>Fixed Amount:</b>
								<div class="input-group">
									<div class="form-line">
										<input type="number" style="text-align: right;" id="philhealth_rate_3" name="philhealth_rate_3" class="philhealth_rate_3 form-control" <?php echo $readonly; ?>>
									</div>
									<div class="row">
										<!--  -->
										<div class="col-md-3"></div>
										<div class="col-md-6"><input type="text" style="text-align: center;" id="phil_range_3" class="phil_range_3 form-control" name="phil_range_3"  placeholder="( P 80,000.00 and above )" <?php echo $readonly; ?>></div>
										<div class="col-md-3"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="body" style="margin-bottom: 0; padding-bottom: 0">
						<div class="row" style="margin: 0; padding: 0">
							<div class="col-md-4">
								<b>NWRBEA Membership Amount:</b>
								<div class="input-group">
									<div class="form-line">
										<input type="number" id="union_dues_amount" name="union_dues_amount" class="union_dues_amount form-control" <?php echo
										 $readonly; ?>>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<b>PERA Amount:</b>
								<div class="input-group">
									<div class="form-line">
										<input type="number" id="pera_amount" name="pera_amount" class="pera_amount form-control" <?php echo
										 $readonly; ?>>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<b>Allowable Compensation:</b>
								<div class="input-group">
									<div class="form-line">
										<input type="number" id="allowable_compensation" name="allowable_compensation" class="allowable_compensation form-control" <?php echo
										 $readonly; ?>>
									</div>
								</div>
							</div>
							<!-- <div class="col-md-4">
								<b>PhilHealth (%):</b>
								<div class="input-group">
									<div class="form-line">
										<input type="number" id="philhealth_rate" name="philhealth_rate" class="philhealth_rate form-control" <?php echo $readonly; ?>>
									</div>
								</div>
							</div> -->
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row clearfix" style="display: none;">
			<div class="col-md-12">
				<!-- <ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active">
						<a href="#payroll_configurations_monthly" data-toggle="tab" aria-expanded="true">
							<i class="material-icons">insert_chart_outlined</i> Monthly Overtime Rates
						</a>
					</li>
					<li role="presentation" class="">
						<a href="#payroll_configurations_daily" data-toggle="tab" aria-expanded="false">
							<i class="material-icons">insert_chart</i> Daily Overtime Rates
						</a>
					</li>
				</ul> -->
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane fade active in" id="payroll_configurations_monthly">
						<div class="row clearfix">
							<div class="col-md-6">
								<div class="card">
									<div class="header">
										<b>Rest Day</b>
									</div>
									<div class="body">
										<div class="input-group">
											<span class="input-group-addon">
												Rest Day Overtime Rate (%):
											</span>
											<div class="form-line">
												<input type="number" id="monthly_restday_overtime_rate" name="monthly_restday_overtime_rate" class="monthly_restday_overtime_rate form-control">
											</div>
										</div>
										<div class="input-group">
											<span class="input-group-addon">
												Excess of 8 Hours Rate (%):
											</span>
											<div class="form-line">
												<input type="number" id="monthly_restday_excess_rate" name="monthly_restday_excess_rate" class="monthly_restday_excess_rate form-control">
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="card">
									<div class="header">
										<b>Regular Day</b>
									</div>
									<div class="body">
										<div class="input-group">
											<span class="input-group-addon">
												Regular Overtime Rate (%):
											</span>
											<div class="form-line">
												<input type="number" id="monthly_regular_overtime_rate" name="monthly_regular_overtime_rate" class="monthly_regular_overtime_rate form-control">
											</div>
										</div>
										<div class="input-group">
											<span class="input-group-addon">
												Regular Night Shift Diff. Rate (%):
											</span>
											<div class="form-line">
												<input type="number" id="monthly_regular_nightdiff_rate" name="monthly_regular_nightdiff_rate" class="monthly_regular_nightdiff_rate form-control">
											</div>
										</div>
										<div class="input-group">
											<span class="input-group-addon">
												Overtime Night Shift Diff. Rate (%):
											</span>
											<div class="form-line">
												<input type="number" id="monthly_regular_overtime_nightdiff_rate" name="monthly_regular_overtime_nightdiff_rate"
												 class="monthly_regular_overtime_nightdiff_rate form-control">
											</div>
										</div>
										<div class="input-group">
											<span class="input-group-addon">
												Regular Overtime Excess Rate (%):
											</span>
											<div class="form-line">
												<input type="number" id="monthly_regular_overtime_excess_rate" name="monthly_regular_overtime_excess_rate"
												 class="monthly_regular_overtime_excess_rate form-control">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row clearfix">
							<div class="col-md-6">
								<div class="card">
									<div class="header">
										<b>Special Holiday</b>
									</div>
									<div class="body">
										<div class="input-group">
											<span class="input-group-addon">
												Special Holiday Overtime Rate (%):
											</span>
											<div class="form-line">
												<input type="number" id="monthly_special_holiday_overtime_rate" name="monthly_special_holiday_overtime_rate"
												 class="monthly_special_holiday_overtime_rate form-control">
											</div>
										</div>
										<div class="input-group">
											<span class="input-group-addon">
												Excess of 8 Hours Rate (%):
											</span>
											<div class="form-line">
												<input type="number" id="monthly_special_holiday_excess_rate" name="monthly_special_holiday_excess_rate"
												 class="monthly_special_holiday_excess_rate form-control">
											</div>
										</div>
										<div class="input-group">
											<span class="input-group-addon">
												Rest Day Overtime Rate (%):
											</span>
											<div class="form-line">
												<input type="number" id="monthly_special_holiday_restday_overtime_rate" name="monthly_special_holiday_restday_overtime_rate"
												 class="monthly_special_holiday_restday_overtime_rate form-control">
											</div>
										</div>
										<div class="input-group">
											<span class="input-group-addon">
												Rest Day Excess of 8 Hours Rate (%):
											</span>
											<div class="form-line">
												<input type="number" id="monthly_special_holiday_restday_excess_rate" name="monthly_special_holiday_restday_excess_rate"
												 class="monthly_special_holiday_restday_excess_rate form-control">
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="card">
									<div class="header">
										<b>Legal Holiday</b>
									</div>
									<div class="body">
										<div class="input-group">
											<span class="input-group-addon">
												Legal Holiday Overtime Rate (%):
											</span>
											<div class="form-line">
												<input type="number" id="monthly_legal_holiday_overtime_rate" name="monthly_legal_holiday_overtime_rate"
												 class="monthly_legal_holiday_overtime_rate form-control">
											</div>
										</div>
										<div class="input-group">
											<span class="input-group-addon">
												Excess of 8 Hours Rate (%):
											</span>
											<div class="form-line">
												<input type="number" id="monthly_legal_holiday_excess_rate" name="monthly_legal_holiday_excess_rate" class="monthly_legal_holiday_excess_rate form-control">
											</div>
										</div>
										<div class="input-group">
											<span class="input-group-addon">
												Rest Day Overtime Rate (%):
											</span>
											<div class="form-line">
												<input type="number" id="monthly_legal_holiday_restday_overtime_rate" name="monthly_legal_holiday_restday_overtime_rate"
												 class="monthly_legal_holiday_restday_overtime_rate form-control">
											</div>
										</div>
										<div class="input-group">
											<span class="input-group-addon">
												Rest Day Excess of 8 Hours Rate (%):
											</span>
											<div class="form-line">
												<input type="number" id="monthly_legal_holiday_restday_excess_rate" name="monthly_legal_holiday_restday_excess_rate"
												 class="monthly_legal_holiday_restday_excess_rate form-control">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div role="tabpanel" class="tab-pane fade" id="payroll_configurations_daily">
						<div class="row clearfix">
							<div class="col-md-6">
								<div class="card">
									<div class="body" style="margin: 0; padding-bottom: 1rem">
										<div class="input-group">
											<span class="input-group-addon">
												E-COLA Amount:
											</span>
											<div class="form-line">
												<input type="number" id="daily_e_cola_amount" name="daily_e_cola_amount" class="daily_e_cola_amount form-control">
											</div>
										</div>
									</div>
								</div>
								<div class="card">
									<div class="header">
										<b>Rest Day</b>
									</div>
									<div class="body">
										<div class="input-group">
											<span class="input-group-addon">
												Rest Day Overtime Rate (%):
											</span>
											<div class="form-line">
												<input type="number" id="daily_restday_overtime_rate" name="daily_restday_overtime_rate" class="daily_restday_overtime_rate form-control">
											</div>
										</div>
										<div class="input-group">
											<span class="input-group-addon">
												Excess of 8 Hours Rate (%):
											</span>
											<div class="form-line">
												<input type="number" id="daily_restday_excess_rate" name="daily_restday_excess_rate" class="daily_restday_excess_rate form-control">
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="card">
									<div class="header">
										<b>Regular Day</b>
									</div>
									<div class="body">
										<div class="input-group">
											<span class="input-group-addon">
												Regular Overtime Rate (%):
											</span>
											<div class="form-line">
												<input type="number" id="daily_regular_overtime_rate" name="daily_regular_overtime_rate" class="daily_regular_overtime_rate form-control">
											</div>
										</div>
										<div class="input-group">
											<span class="input-group-addon">
												Regular Night Shift Diff. Rate (%):
											</span>
											<div class="form-line">
												<input type="number" id="daily_regular_nightdiff_rate" name="daily_regular_nightdiff_rate" class="daily_regular_nightdiff_rate form-control">
											</div>
										</div>
										<div class="input-group">
											<span class="input-group-addon">
												Overtime Night Shift Diff. Rate (%):
											</span>
											<div class="form-line">
												<input type="number" id="daily_regular_overtime_nightdiff_rate" name="daily_regular_overtime_nightdiff_rate"
												 class="daily_regular_overtime_nightdiff_rate form-control">
											</div>
										</div>
										<div class="input-group">
											<span class="input-group-addon">
												Regular Overtime Excess Rate (%):
											</span>
											<div class="form-line">
												<input type="number" id="daily_regular_overtime_excess_rate" name="daily_regular_overtime_excess_rate"
												 class="daily_regular_overtime_excess_rate form-control">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row clearfix">
							<div class="col-md-6">
								<div class="card">
									<div class="header">
										<b>Special Holiday</b>
									</div>
									<div class="body">
										<div class="input-group">
											<span class="input-group-addon">
												Special Holiday Overtime Rate (%):
											</span>
											<div class="form-line">
												<input type="number" id="daily_special_holiday_overtime_rate" name="daily_special_holiday_overtime_rate"
												 class="daily_special_holiday_overtime_rate form-control">
											</div>
										</div>
										<div class="input-group">
											<span class="input-group-addon">
												Excess of 8 Hours Rate (%):
											</span>
											<div class="form-line">
												<input type="number" id="daily_special_holiday_excess_rate" name="daily_special_holiday_excess_rate" class="daily_special_holiday_excess_rate form-control">
											</div>
										</div>
										<div class="input-group">
											<span class="input-group-addon">
												Rest Day Overtime Rate (%):
											</span>
											<div class="form-line">
												<input type="number" id="daily_special_holiday_restday_overtime_rate" name="daily_special_holiday_restday_overtime_rate"
												 class="daily_special_holiday_restday_overtime_rate form-control">
											</div>
										</div>
										<div class="input-group">
											<span class="input-group-addon">
												Rest Day Excess of 8 Hours Rate (%):
											</span>
											<div class="form-line">
												<input type="number" id="daily_special_holiday_restday_excess_rate" name="daily_special_holiday_restday_excess_rate"
												 class="daily_special_holiday_restday_excess_rate form-control">
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="card">
									<div class="header">
										<b>Legal Holiday</b>
									</div>
									<div class="body">
										<div class="input-group">
											<span class="input-group-addon">
												Legal Holiday Overtime Rate (%):
											</span>
											<div class="form-line">
												<input type="number" id="daily_legal_holiday_overtime_rate" name="daily_legal_holiday_overtime_rate" class="daily_legal_holiday_overtime_rate form-control">
											</div>
										</div>
										<div class="input-group">
											<span class="input-group-addon">
												Excess of 8 Hours Rate (%):
											</span>
											<div class="form-line">
												<input type="number" id="daily_legal_holiday_excess_rate" name="daily_legal_holiday_excess_rate" class="daily_legal_holiday_excess_rate form-control">
											</div>
										</div>
										<div class="input-group">
											<span class="input-group-addon">
												Rest Day Overtime Rate (%):
											</span>
											<div class="form-line">
												<input type="number" id="daily_legal_holiday_restday_overtime_rate" name="daily_legal_holiday_restday_overtime_rate"
												 class="daily_legal_holiday_restday_overtime_rate form-control">
											</div>
										</div>
										<div class="input-group">
											<span class="input-group-addon">
												Rest Day Excess of 8 Hours Rate (%):
											</span>
											<div class="form-line">
												<input type="number" id="daily_legal_holiday_restday_excess_rate" name="daily_legal_holiday_restday_excess_rate"
												 class="daily_legal_holiday_restday_excess_rate form-control">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="text-right" style="width:100%;">
		<?php if($key == "addPayrollConfigurationsPayrollSetup"): ?>
		<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
			<i class="material-icons">save</i>
			<span> Save</span>
		</button>
		<?php endif; ?>
		<?php if($key == "updatePayrollConfigurationsPayrollSetup"): ?>
		<button id="saveUserLevelConfig" class="btn btn-primary btn-sm waves-effect" type="submit">
			<i class="material-icons">save</i>
			<span> Update</span>
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
		<!-- <button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
            <i class="material-icons">close</i><span> Close</span>
        </button> -->
	</div>
</form>
