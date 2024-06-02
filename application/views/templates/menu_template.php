<div class="menu">
	<ul class="list">
		<li class="header">MAIN NAVIGATION</li>
		<!-- <li>
			<a href="<?php echo base_url(); ?>timekeeping/ImportPayrollInfoFromExcel/">
				<i class="material-icons">dashboard</i>
				<span>Import Employee Payroll Info</span>
			</a>
		</li> -->
		<?php if(Helper::role(ModuleRels::DASHBOARD_MAIN_MENU)): ?>
		<li>
			<a href="<?php echo base_url(); ?>home">
				<i class="material-icons">dashboard</i>
				<span>Dashboard</span>
			</a>
		</li>
		<?php endif; ?>
		<?php if(Helper::role(ModuleRels::TIMEKEEPING_REPORTS_MAIN_MENU)): ?>
			<li>
				<a href="javascript:void(0);" class="menu-toggle">
					<i class="material-icons">av_timer</i>
					<span>Daily Time Record</span>
				</a>
				<ul class="ml-menu">
					<?php if(Helper::role(ModuleRels::EMPLOYEE_DTR_SUB_MENU)): ?>
					<li>
					<a href="<?php echo base_url(); ?>timekeepingreports/DailyTimeRecordSummary/">Daily Time Record</a>
					</li>
					<?php endif; ?>
					<?php if(Helper::role(ModuleRels::SELF_EMPLOYEE_DTR)): ?>
					<li>
					<a href="<?php echo base_url(); ?>timekeepingreports/EmployeeDTRSummary/">My Daily Time Record</a>
					</li>
					<?php endif; ?>
					<?php if(Helper::role(ModuleRels::EMPLOYEE_ACTUAL_DTR_SUB_MENU)): ?>
					<li>
						<a href="<?php echo base_url(); ?>timekeepingreports/ActualAttendance/">Actual Attendance</a>
					</li>
					<?php endif; ?>
				</ul>
			</li>
		<?php endif; ?>
		<?php if(Helper::role(ModuleRels::EMPLOYEE_MANAGEMENT_MAIN_MENU)): ?>
		<li>
			<a href="javascript:void(0);" class="menu-toggle">
				<i class="material-icons">people</i>
				<span>Employee Management</span>
			</a>
			<ul class="ml-menu">
				<?php if(Helper::role(ModuleRels::EMPLOYEES_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>employees/Employees/">Active Employees</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::EX_EMPLOYEES_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>employees/Employees/?EmploymentStatus=1">Former Employees</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::EMPLOYEES_APPROVERS_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>employees/RequestApprovers/">Manage Employees Approver</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::EMPLOYEES_SERVICE_RECORDS_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>employees/ServiceRecords">Service Records</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::EMPLOYEES_PDS_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>employees/PDS">Personal Data Sheet</a>
				</li>
				<?php endif; ?>
				<!-- <li>
					<a href="<?php echo base_url(); ?>employees/ImportEmployees/">Import Employees from Excel</a>
				</li> -->
			</ul>
		</li>
		<?php endif; ?>
		<?php if(Helper::role(ModuleRels::FIELD_MANAGEMENT_MAIN_MENU)): ?>
		<li>
			<a href="javascript:void(0);" class="menu-toggle">
				<i class="material-icons">list</i>
				<span>Field Management</span>
			</a>
			<ul class="ml-menu">
				<?php if(Helper::role(ModuleRels::DIVISIONS_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>fieldmanagement/Departments/">Departments</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::HOLIDAYS_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>fieldmanagement/Holidays/">Holidays</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::LOANS_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>fieldmanagement/Loans/">Loans</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::POSITIONS_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>fieldmanagement/Positions/">Positions</a>
				</li>
				<?php endif; ?>

				<?php if(Helper::role(ModuleRels::SIGNATORIES_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>fieldmanagement/Signatories/">Signatories</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::OTHER_BENEFITS_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>fieldmanagement/OtherBenefits/">Other Benefits</a>
				</li>
				<?php endif; ?>

				<?php if(Helper::role(ModuleRels::OTHER_EARNINGS_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>fieldmanagement/OtherEarnings/">Other Earnings</a>
				</li>
				<?php endif; ?>

				<?php if(Helper::role(ModuleRels::OTHER_DEDUCTIONS_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>fieldmanagement/OtherDeductions/">Other Deductions</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::WITHHOLDING_TAX_TABLE_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>fieldmanagement/WithHoldingTaxes/">Withholding Tax Table</a>
				</li>
				<?php endif; ?>
				<?php  if(Helper::role(ModuleRels::SALARY_GRADES_SUB_MENU)): ?>
					<li>
						<a href="<?php echo base_url(); ?>fieldmanagement/SalaryGrades/">Salary Grades</a>
					</li>
				<?php  endif; ?>
				<?php if(Helper::role(ModuleRels::PAYROLL_PERIOD_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>fieldmanagement/PeriodSettings/">Payroll Period Settings</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::PAYROLL_CONFIGURATION_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>fieldmanagement/PayrollConfigurations/">Payroll Configuration</a>
				</li>
				<?php endif; ?>
				<?php  if(Helper::role(ModuleRels::BONUSES_SUB_MENU)): ?>
					<li>
						<a href="<?php echo base_url(); ?>fieldmanagement/Bonuses/">Bonuses and Incentives</a>
					</li>
				<?php  endif; ?>				
				<?php if(Helper::role(ModuleRels::SATELLITE_LOCATIONS_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>fieldmanagement/SatelliteLocations/">Satellite Locations</a>
				</li>
				<?php endif; ?>
			</ul>
		</li>
		<?php endif; ?>
		<?php if(Helper::role(ModuleRels::TRAINING_SEMINARS_MONITORING_MAIN_MENU)): ?>
		<li>
			<a href="javascript:void(0);" class="menu-toggle">
				<i class="material-icons">library_books</i>
				<span>Learning & Development</span>
			</a>
			<ul class="ml-menu">
				<li>
					<a href="<?php echo base_url(); ?>trainings/ManageTrainings/">Manage Trainings</a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>trainings/TrainingReport/">Employee Trainings</a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>trainings/TrainingList/">Training List</a>
				</li>
			</ul>
		</li>
		<?php endif; ?>
		
		<?php if(Helper::role(ModuleRels::COMPETENCY_MANAGEMENT)): ?>
		<!-- <li>
			<a href="javascript:void(0);" class="menu-toggle">
				<i class="material-icons">library_books</i>
				<span>Competency Test Management</span>
			</a>
			<ul class="ml-menu">
				<li>
					<a href="<?php echo base_url(); ?>competencymanagement/CompetencyTest/">Competency Test</a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>competencymanagement/CompetencyAccess/">Competency Access</a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>competencymanagement/CompetencyExam/">Take Compentency Exam</a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>competencymanagement/CompetencyTestExamResult/">Compentency Test Exam Result</a>
				</li>
			</ul>
		</li> -->
		<?php endif; ?>
		<?php if(Helper::role(ModuleRels::LEAVE_TRACKING_MAIN_MENU)): ?>
		<li>
			<a href="javascript:void(0);" class="menu-toggle">
				<i class="material-icons">art_track</i>
				<span>Leave Tracking</span>
			</a>
			<ul class="ml-menu">
				<?php if(Helper::role(ModuleRels::HRIS_DASHBOARD)): ?>
				<li>
					<a href="<?php echo base_url(); ?>leavemanagement/CTORequest/">Compensatory Time Off Request</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::LEAVE_CTO_APPROVER)): ?>
				<li>
					<a href="<?php echo base_url(); ?>leavemanagement/CTOApproval/">Compensatory Time Off Approval</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::CTO_LEDGER_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>leavemanagement/CTOLedger/">Compensatory Time Off Ledger</a>
				</li>
				<?php endif; ?>	
				<?php if(Helper::role(ModuleRels::HRIS_DASHBOARD)): ?>
				<li>
					<a href="<?php echo base_url(); ?>leavemanagement/LeaveRequest/">Leave Request</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::LEAVE_TRANSACTIONS_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>leavemanagement/PendingLeave/">Leave Transactions</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::LEAVE_STATUS_TRACKING_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>leavemanagement/LeaveTracking/">Leave Status Tracking</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::LEAVE_CREDITS_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>leavemanagement/LeaveCredits">Leave Credits</a>
				</li>
				<?php endif; ?>			
				<?php if(Helper::role(ModuleRels::LEAVE_LEDGER_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>leavemanagement/LeaveLedger/">Leave Card</a>
				</li>
				<?php endif; ?>				
				<?php if(Helper::role(ModuleRels::IMPORT_EXCEL_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>timekeeping/ImportMonthlyLeaveBalance/">Import Monthly Leave Credits</a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>timekeeping/ImportLeavesFromExcel/">Import Yearly Leave Credits</a>
				</li>
				<?php endif; ?>
			</ul>
		</li>
		<?php endif; ?>
		<?php if(Helper::role(ModuleRels::LOCATOR_SLIPS_SUB_MENU)): ?>
		<li>
			<a href="javascript:void(0);" class="menu-toggle">
				<i class="material-icons">art_track</i>
				<span>Locator Slips</span>
			</a>
			<ul class="ml-menu">
				<?php if(Helper::role(ModuleRels::HRIS_DASHBOARD)): ?>
				<li>
					<a href="<?php echo base_url(); ?>timekeeping/OfficialBusinessRequest/">Personnel Locator Slip Request</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::OB_VIEW_ALL_TRANSACTIONS)): ?>
				<li>
					<a href="<?php echo base_url(); ?>timekeeping/LocatorSlips/">Locator Slips Transactions</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::LOCATOR_STATUS_TRACKING_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>timekeeping/LocatorSlipsTracking/">Locator Slip Status Tracking</a>
				</li>
				<?php endif; ?>
			</ul>
		</li>
		<?php endif; ?>

		<?php if(Helper::role(ModuleRels::OVERTIME_APPLICATIONS_UPDATE_DETAILS)): ?>
		<li>
			<a href="javascript:void(0);" class="menu-toggle">
				<i class="material-icons">art_track</i>
				<span>Overtime Request</span>
			</a>
			<ul class="ml-menu">
				<?php if(Helper::role(ModuleRels::HRIS_DASHBOARD)): ?>
				<li>
					<a href="<?php echo base_url(); ?>overtimerequest/OvertimeRequest/">Overtime Request Forms</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::OVERTIME_SUMMARY_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>overtimerequest/OvertimeTransactions/">Overtime Transaction/s</a>
				</li>
				<?php endif; ?>
			</ul>
		</li>
		<?php endif; ?>
		<?php if(Helper::role(ModuleRels::TRAVEL_ORDER_MENU)): ?>
		<li>
			<a href="javascript:void(0);" class="menu-toggle">
				<i class="material-icons">art_track</i>
				<span>Travel Order</span>
			</a>
			<ul class="ml-menu">
				<?php if(Helper::role(ModuleRels::TRAVEL_ORDER_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>travelOrder/TravelOrderRequest/">Travel Order Request</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::TRAVEL_ORDER_APPROVER_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>travelOrder/TravelOrderApproval/">Travel Order Approval</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::TRAVEL_ORDER_STATUS_TRACKING_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>travelOrder/TravelOrderTracking/">Travel Order Status Tracking</a>
				</li>
				<?php endif; ?>
			</ul>
		</li>
		<?php endif; ?>
		<?php if(Helper::role(ModuleRels::PERFORMANCE_EVALUATION_MAIN_MENU)): ?>
		<!-- <li>
			<a href="<?php echo base_url(); ?>individual_goal_worksheet/Report_List/">
				<i class="material-icons">assessment</i>
				<span>Performance Evaluation</span>
			</a>
		</li> -->
		<li>
			<a href="javascript:void(0);" class="menu-toggle">
				<i class="material-icons">people</i>
				<span> Performance Evaluation</span>
			</a>
			<ul class="ml-menu">
				<!-- <li>
					<a href="<?php echo base_url(); ?>individual_goal_worksheet/Report_List/">
						<span>Performance Evaluation</span>
					</a>
				</li> -->
				<li>
					<a href="javascript:void(0);" class="menu-toggle">
						<span>OPCR</span>
					</a>
					<ul class="ml-menu">
						<li>
							<a href="<?php echo base_url(); ?>performancemanagement/Opcr/">OPCR Form</a>
						</li>
						
						<li>
							<a href="<?php echo base_url(); ?>performancemanagement/OpcrReviews/">OPCR Reviews</a>
						</li>
					</ul>
				</li>
				<li>
					<a href="javascript:void(0);" class="menu-toggle">
						<span>DPCR</span>
					</a>
					<ul class="ml-menu">
						<li>
							<a href="<?php echo base_url(); ?>performancemanagement/dpcr/">DPCR Form</a>
						</li>
						<li>                                                        
							<a href="<?php echo base_url(); ?>performancemanagement/DpcrReviews/">DPCR Reviews</a>
						</li>
					</ul>
				</li>
				<li>
					<a href="javascript:void(0);" class="menu-toggle">
						<!-- <i class="material-icons">people</i> -->
						<span>IPCR</span>
					</a>
					<ul class="ml-menu">
						<li>
							<a href="<?php echo base_url(); ?>performancemanagement/ipcr/">IPCR Form</a>
						</li>
						<li>                                                        
							<a href="<?php echo base_url(); ?>performancemanagement/IpcrReviews/">IPCR Reviews</a>
						</li>
					</ul>
				</li>
				
			</ul>
		</li>
		<?php endif; ?>
		<?php if(Helper::role(ModuleRels::PAYROLL_REPORTS_MAIN_MENU)): ?>
		<li>
			<a href="javascript:void(0);" class="menu-toggle">
				<i class="material-icons">assignment</i>
				<span>Payroll Reports</span>
			</a>
			<ul class="ml-menu">
				<?php if(Helper::role(ModuleRels::BIR_2316)): ?>
				<li>
					<a href="<?php echo base_url(); ?>payrollreports/BIRform/">2316 Form</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::BIR_ALPHALIST)): ?>
				<li>
					<a href="<?php echo base_url(); ?>payrollreports/BIRalphalist/">BIR Alphalist</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::PAYROLL_REPORTS_CERTIFICATES_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>payrollreports/Certificate/">Certificates</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::PAYROLL_REGISTER_SUMMARY_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>payrollreports/PayrollRegister/">Payroll Register</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::PAYSLIP_SUMMARY_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>payrollreports/Payslip/">Payslip Summary</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::RATASLIP_SUMMARY_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>payrollreports/RATASlip/">RATA Summary</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::OTHER_DEDUCTIONS_SUMMARY_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>payrollreports/OtherDeductions/">Other Deductions Summary</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::OTHER_EARNINGS_SUMMARY_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>payrollreports/OtherEarnings/">Other Earnings Summary</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::REMITTANCE_SUMMARY_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>payrollreports/RemittanceSummary/">Remittance Summary</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::SPECIAL_PAYROLL_REPORTS_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>annualreports/SpecialReports/">Special Payroll Reports</a>
				</li>
				<?php endif; ?> 
			</ul>
		</li>
		<?php endif; ?>		
		<?php if(Helper::role(ModuleRels::REMITTANCE_SUMMARY_SUB_MENU)): ?><li>
		<li>
			<a href="javascript:void(0);" class="menu-toggle">
				<i class="material-icons">assignment_ind</i>
				<span>Remittance Reports</span>
			</a>
			<ul class="ml-menu">
				<li>
					<a href="<?php echo base_url(); ?>remittance/GSISContribution/">GSIS Contribution</a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>remittance/NWRBEA/">NWRBEA</a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>remittance/PagibigContribution/">PAG-IBIG Contribution</a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>remittance/PagibigLCHL/">PAG-IBIG Housing Loan</a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>remittance/PagibigMP2/">PAG-IBIG MP2</a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>remittance/PagibigMPL/">PAG-IBIG MPL</a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>remittance/PhilHealth/">PHILHEALTH Contribution</a>
				</li>
			</ul>
		</li>
		<?php endif; ?>
		<?php if(Helper::role(ModuleRels::TIME_KEEPING_MAIN_MENU)): ?>
		<li>
			<a href="javascript:void(0);" class="menu-toggle">
				<i class="material-icons">timer</i>
				<span>Timekeeping Maintenance</span>
			</a>
			<ul class="ml-menu">
				<?php if(Helper::role(ModuleRels::EMPLOYEE_SCHEDULES_SUB_MENU)): ?>
				<?php if(Helper::role(ModuleRels::DAILY_TIME_RECORD_MAINTENANCE_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>timekeeping/DailyTimeRecordMaintenance/">Daily Time Record Maintenance</a>
				</li>
				<?php endif; ?>
				<li>
					<a href="<?php echo base_url(); ?>timekeeping/EmployeeSchedules/">Employee Fixed Schedules</a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>timekeeping/EmployeeFlexibleSchedules/">Employee Flexible Schedules</a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>timekeeping/FlagCeremonySchedules/">Flag Ceremony Schedules</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::IMPORT_EXCEL_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>timekeeping/ImportDTRFromDevice/">Import Attendance From Device</a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>timekeeping/ImportFromExcel/">Import Attendance From Excel</a>
				</li>
				<!-- <li>
					<a href="<?php echo base_url(); ?>timekeeping/ImportMonthlyLeaveBalance/">Import Monthly Leave Credits From Excel</a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>timekeeping/ImportLeavesFromExcel/">Import Yearly Leave Credits From Excel</a>
				</li> -->
				<li>
					<a href="<?php echo base_url(); ?>timekeeping/ImportCTO/">Import CTO Balance From Excel</a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>timekeeping/ImportUsedCTO/">Import Used CTO From Excel</a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>timekeeping/ImportLeaveManagementFromExcel/">Import Filed Leaves From Excel</a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>timekeeping/ImportOBFromExcel/">Import Locator Slips From Excel</a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>timekeeping/ImportTOFromExcel/">Import Travel Order From Excel</a>
				</li>
				<!-- <li>
					<a href="<?php echo base_url(); ?>timekeeping/ImportMonthlyRunningBalance/">Import Monthly Payroll Running Balance From Excel</a>
				</li> -->
				<!-- <li>
					<a href="<?php echo base_url(); ?>timekeeping/ImportTaxFromExcel/">Import Tax From Excel</a>
				</li> -->
				<?php endif; ?>
			</ul>
		</li>
		<?php endif; ?>
		<?php if(Helper::role(ModuleRels::TRANSACTIONS_MAIN_MENU)): ?>
		<li>
			<a href="javascript:void(0);" class="menu-toggle">
				<i class="material-icons">people</i>
				<span>Transactions</span>
			</a>
			<ul class="ml-menu">
				<?php if(Helper::role(ModuleRels::IMPORT_EXCEL_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>timekeeping/ImportTaxFromExcel/">Import Tax From Excel</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::OTHER_BENEFITS_ENTRY_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>transactions/OtherBenefitEntries/">Other Benefit Entries</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::OTHER_DEDUCTION_ENTRY_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>transactions/OtherDeductionEntries/">Other Deduction Entries</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::OTHER_EARNING_ENTRY_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>transactions/OtherEarningEntries/">Other Earning Entries</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::LOAN_ENTRY_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>transactions/LoanEntries/">Loan Entries</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::SPECIAL_PAYROLL_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>transactions/SpecialPayroll/">Special Payroll</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::PAYROLL_POSTING_SUB_MENU)): ?>					
				<li>
					<a href="<?php echo base_url(); ?>transactions/PayrollPosting/">Payroll Posting</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::PROCESS_PAYROLL_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>transactions/ProcessPayroll/">Process Payroll</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::TRANS_REMITTANCE_OR_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>transactions/RemittancesOR/">Remittances OR</a>
				</li>
				<?php endif; ?>
			</ul>
		</li>
		<?php endif; ?>
		
		<?php if(Helper::role(ModuleRels::RECRUITMENT_MAIN_MENU)): ?>
		<li>
			<a href="javascript:void(0);" class="menu-toggle">
				<i class="material-icons">people</i>
				<span>Recruitment</span>
			</a>
			<ul class="ml-menu">
				<?php if(Helper::role(ModuleRels::VACANT_POSITIONS_SUB_MENU)): ?>
					<li>
						<a href="<?php echo base_url(); ?>recruitment/VacantPositions/">Position List</a>
					</li>
				<?php endif; ?>

				<?php if(Helper::role(ModuleRels::JOB_OPENINGS_SUB_MENU)): ?>
					<li>
						<a href="<?php echo base_url(); ?>recruitment/JobOpenings/">Job Openings</a>
					</li>
				<?php endif; ?>

				<?php if(Helper::role(ModuleRels::APPLICANTS_SUB_MENU)): ?>
					<li>
						<a href="<?php echo base_url(); ?>recruitment/Applicants/">Applicants</a>
					</li>
				<?php endif; ?>

				<?php if(Helper::role(ModuleRels::EXAMINATION_SCHEDULES_SUB_MENU)): ?>
					<li>
						<a href="<?php echo base_url(); ?>recruitment/ExaminationSchedules/">Examination Schedules</a>
					</li>
				<?php endif; ?>

				<?php if(Helper::role(ModuleRels::INTERVIEW_SCHEDULES_SUB_MENU)): ?>
					<li>
						<a href="<?php echo base_url(); ?>recruitment/InterviewSchedules/">Interview Schedules</a>
					</li>
				<?php endif; ?>

				<?php if(Helper::role(ModuleRels::APPLICANT_CHECKLISTS_SUB_MENU)): ?>
					<li>
						<a href="<?php echo base_url(); ?>recruitment/ApplicantChecklists/">Applicant Checklists</a>
					</li>
				<?php endif; ?>

				<?php if(Helper::role(ModuleRels::APPLICANT_REPORTS_SUB_MENU)): ?>
					<li>
						<a href="<?php echo base_url(); ?>recruitment/ApplicantReports/">Applicant Reports</a>
					</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::POSITION_REPORTS_SUB_MENU)): ?>
					<li>
						<a href="<?php echo base_url(); ?>recruitment/PositionsReports/">Positions Reports</a>
					</li>
				<?php endif; ?>
			</ul>
		</li>
		<?php endif; ?>
		<?php if(Helper::role(ModuleRels::USER_MANAGEMENT_MAIN_MENU)): ?>
		<li>
			<a href="javascript:void(0);" class="menu-toggle">
				<i class="material-icons">person_pin</i>
				<span>User Management</span>
			</a>
			<ul class="ml-menu">
				<?php if(Helper::role(ModuleRels::USER_MOBILE_ACCOUNTS_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>usermanagement/MobileUserConfig/">Mobile User Accounts</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::USER_ACCOUNTS_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>usermanagement/UserConfig/">Web User Accounts</a>
				</li>
				<?php endif; ?>
				<?php if(Helper::role(ModuleRels::USER_ACCOUNT_LEVELS_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>usermanagement/UserLevelConfig/">User Account Levels</a>
				</li>
				<?php endif; ?>
			</ul>
		</li>
		<?php endif; ?>
		<?php if(Helper::role(ModuleRels::SETTINGS_MAIN_MENU)): ?>
		<li>
			<a href="javascript:void(0);" class="menu-toggle">
				<i class="material-icons">settings</i>
				<span>Settings</span>
			</a>
			<ul class="ml-menu">
				<?php if(Helper::role(ModuleRels::AUDIT_TRAILS_SUB_MENU)): ?>
				<li>
					<a href="<?php echo base_url(); ?>settings/AuditTrails/">Audit Trails</a>
				</li>
				<?php endif; ?>
			</ul>
		</li>
		<?php endif; ?>
	</ul>
</div>