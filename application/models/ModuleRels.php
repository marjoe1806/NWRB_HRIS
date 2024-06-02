<?php
/**
*
* WARNING: This is only intended for developers.
* Changing the values will result to many errors.
* Author: Telcom Live Content Inc.
*
*/
class ModuleRels {
	const DASHBOARD_MAIN_MENU						=	100;
	const PAYROLL_DASHBOARD							=	101;
	const HRIS_DASHBOARD							=	102;
	const EMPLOYEE_TRACKING_MAIN_MENU				=	1001;
	const USER_MANAGEMENT_MAIN_MENU					=	1100;
	const USER_ACCOUNTS_MENU						=	1101;
	const USER_ACCOUNT_LEVELS_MENU					=	1102;
	const EMPLOYEE_MANAGEMENT_MAIN_MENU				=	1200;
	const EMPLOYEES_SUB_MENU						=	1201;
	const EMPLOYEE_VIEW_DETAILS						=	12011;
	const EMPLOYEE_UPDATE_DETAILS					=	12012;
	const EMPLOYEE_ALLOWANCES						=	12013;
	const EMPLOYEE_ADD_RECORDS						= 	12014;
	const EMPLOYEES_SERVICE_RECORDS_SUB_MENU		=	1205;
	const EMPLOYEES_PDS_SUB_MENU					=	1206;
	const EMPLOYEES_APPROVERS_SUB_MENU				=	1207;
	const EX_EMPLOYEES_SUB_MENU						=	1202;
	const DOWNLOAD_MOBILE_EMPLOYEES_SUB_MENU		=	1203;
	const EMPLOYEES_DEPARTMENT_SUB_MENU				=	1204;
	const LEAVE_TRACKING_MAIN_MENU					=	1300;
	const LEAVE_TRACKING_ALL_ACCESS					=	13001;
	const LEAVE_TRANSACTIONS_SUB_MENU				=	1301;
	const LEAVE_VIEW_DETAILS						=	13011;
	const LEAVE_UPDATE_DETAILS						=	13012;
	const LEAVE_ACTIVATION							=	13013;
	const LEAVE_ADD_RECORDS							=	13014;
	const LEAVE_RECOMMEND							=	13015;
	const LEAVE_APPROVE								=	13016;
	const LEAVE_REJECT								=	13017;
	const LEAVE_CERTIFY								=	13018;
	const LEAVE_STATUS_TRACKING_SUB_MENU			=	13019;
	const LEAVE_LEDGER_SUB_MENU						=	1302;
	const CTO_LEDGER_SUB_MENU						=	13021;
	const UTIME_POSTING_SUB_MENU					=	1303;
	const UTIME_ADD_RECORDS							=	13031;
	const UTIME_UPDATE_DETAILS						=	13032;
	const UTIME_ACTIVATION							=	13033;
	const BALANCE_POSTING_SUB_MENU					=	1304;
	const BALANCE_ADD_RECORDS						=	13041;
	const BALANCE_UPDATE_DETAILS					=	13042;
	const BALANCE_ACTIVATION						=	13043;
	const LEAVE_BALANCE_SUB_MENU					= 	1307;
	const LEAVE_CREDITS_SUB_MENU					= 	1308;
	const LEAVE_VIEW_ALL_TRANSACTIONS				= 	1309;
	const OB_VIEW_ALL_TRANSACTIONS					= 	1310;
	const EXHAUSTED_POSTING_SUB_MENU				=	1305;
	const EXHAUSTED_ADD_RECORDS						=	13051;
	const EXHAUSTED_UPDATE_DETAILS					=	13052;
	const EXHAUSTED_ACTIVATION						=	13053;
	const EXHAUSTED_LEAVE_LEDGER_SUB_MENU			= 	1306;
	const TRANSACTIONS_MAIN_MENU					=	1400;
	const OTHER_DEDUCTION_ENTRY_SUB_MENU			=	1401;
	const OTHER_DEDUCTION_ADD_RECORDS				=	14011;
	const OTHER_DEDUCTION_UPDATE_DETAILS			=	14012;
	const OTHER_DEDUCTION_ACTIVATION				=	14013;
	const OTHER_DEDUCTION_HISTORY_DETAILS			=	14014;
	const OTHER_EARNING_ENTRY_SUB_MENU				=	1402;
	const OTHER_EARNING_ADD_RECORDS					=	14021;
	const OTHER_EARNING_UPDATE_DETAILS				=	14022;
	const OTHER_EARNING_ACTIVATION					=	14023;

	const OTHER_BENEFITS_ENTRY_SUB_MENU				=	1407;
	const OTHER_BENEFITS_ADD_RECORDS				=	14024;
	const OTHER_BENEFITS_UPDATE_DETAILS				=	14025;
	const OTHER_BENEFITS_ACTIVATION					=	14026;

	const LOAN_ENTRY_SUB_MENU						=	1403;
	const LOAN_ENTRY_ADD_RECORDS					=	14031;
	const LOAN_ENTRY_VIEW_DETAILS					=	14032;
	const LOAN_ENTRY_UPDATE_DETAILS					=	14033;
	const OVERTIME_APPLICATIONS_SUB_MENU			=	1404;
	const OVERTIME_APPLICATIONS_ADD_RECORDS			=	14041;
	const OVERTIME_APPLICATIONS_VIEW_DETAILS		=	14042;
	const OVERTIME_APPLICATIONS_UPDATE_DETAILS		=	14043;
	const OVERTIME_APPLICATIONS_HISTORY_DETAILS		=	14044;
	const PROCESS_PAYROLL_SUB_MENU					=	1405;
	const SPECIAL_PAYROLL_SUB_MENU					=	1406;
	const SPECIAL_PAYROLL_VIEW_DETAILS				=	14061;
	const SPECIAL_PAYROLL_UPDATE_DETAILS			=	14062;
	const SPECIAL_PAYROLL_HISTORY_DETAILS			=   14063;
	//TIME KEEPING
	const TIME_KEEPING_MAIN_MENU					=	1500;
	const MY_DTR_SUB_MENU							=   1507;
	const LOCATOR_SLIPS_SUB_MENU					=   1505;
	const LOCATOR_SLIPS_ADD_RECORDS					=   15051;
	const LOCATOR_SLIPS_UPDATE_DETAILS				=   15052;
	const LOCATOR_SLIPS_VIEW_DETAILS				=   15053;
	const LOCATOR_STATUS_TRACKING_SUB_MENU          = 	15054;
	const OFFSETTING_SUB_MENU						=   1508;
	const OFFSETTING_ADD_RECORDS					=   15081;
	const OFFSETTING_UPDATE_DETAILS					=   15082;
	const OFFSETTING_VIEW_DETAILS					=   15083;
	const OFFSETTING_CERTIFY						=	15084;
	const OFFSETTING_RECOMMEND						=	15085;
	const OFFSETTING_APPROVE						=	15086;
	const OFFSETTING_REJECT							=	15087;
	const EMPLOYEE_SCHEDULES_SUB_MENU				=	1501;
	const EMPLOYEE_SCHEDULES_ADD_RECORDS			=	15011;
	const EMPLOYEE_SCHEDULES_VIEW_DETAILS			=	15012;
	const EMPLOYEE_SCHEDULES_UPDATE_DETAILS			=	15013;
	const EMPLOYEE_SCHEDULES_ACTIVATION				=	15014;
	const IMPORT_EMPLOYEE_DTR_SUB_MENU				=	1502;
	const IMPORT_PC_FROM_BASE_SUB_MENU				= 	1506;
	const DAILY_TIME_RECORD_MAINTENANCE_SUB_MENU	=	1503;
	const DAILY_TIME_RECORD_MAINTENANCE_UPDATE_DETAILS	=	15031;
	const DAILY_TIME_RECORD_SUMMARY_SUB_MENU		=	1504;
	const PC_BASED_DTR_SYNCING_SUB_MENU				=   1509;
	const IMPORT_EXCEL_SUB_MENU						=   1510;
	//PAYROLL REPORTS
	const PAYROLL_REPORTS_MAIN_MENU					=	1600;
	const PAYSLIP_SUMMARY_SUB_MENU					=	1601;
	//const LOAN_APPLICATIONS_SUMMARY_SUB_MENU		=	1602;
	//const LOAN_DEDUCTIONS_SUMMARY_SUB_MENU			=	1603;
	const OTHER_DEDUCTIONS_SUMMARY_SUB_MENU			=	1604;
	const OTHER_EARNINGS_SUMMARY_SUB_MENU			=	1605;
	//const TAX_CONTRIBUTIONS_SUMMARY_SUB_MENU		=	1606;
	//const GSIS_CONTRIBUTIONS_SUMMARY_SUB_MENU		=	1607;
	//const PAGIBIG_CONTRIBUTIONS_SUMMARY_SUB_MENU	=	1608;
	const REMITTANCE_SUMMARY_SUB_MENU				=	1609;
	const PAYROLL_REGISTER_SUMMARY_SUB_MENU			=	1610;
	const RATASLIP_SUMMARY_SUB_MENU					=	1611;
	const OVERTIME_SUMMARY_SUB_MENU					=	1612;
	const FIELD_MANAGEMENT_MAIN_MENU				=	1700;
	const DIVISIONS_SUB_MENU						=	1701;
	const AGENCIES_SUB_MENU							=	1702;
	const ALLOWANCES_SUB_MENU						=	1703;
	//const BUDGET_CLASSIFICATIONS_SUB_MENU			=	1704;
	//const CONTRIBUTIONS_SUB_MENU					=	1705;
	//const FUND_SOURCES_SUB_MENU						=	1706;
	const HOLIDAYS_SUB_MENU							=	1707;
	//const LEAVES_SUB_MENU							=	1708;
	const LOANS_SUB_MENU							=	1709;
	const OFFICES_SUB_MENU							=	1710;
	const LOCATIONS_SUB_MENU						=	1711;
	const POSITIONS_SUB_MENU						=	1712;
	const SIGNATORIES_SUB_MENU						=	1713;
	const PAYROLL_PERIOD_SUB_MENU					=	1714;
	const OTHER_EARNINGS_SUB_MENU					=	1715;
	const OTHER_DEDUCTIONS_SUB_MENU					=	1716;
	const PAYROLL_SETTINGS_SUB_MENU					=	1717;
	const PAYROLL_CONFIGURATION_SUB_MENU			=	1718;
	const DOCUMENT_TYPES_SUB_MENU					=	1719;
	const SATELLITE_LOCATIONS_SUB_MENU				=	1720;
	const WITHHOLDING_TAX_TABLE_SUB_MENU			=	1721;
	const SALARY_GRADES_SUB_MENU					=	1722;
	const RATA_SUB_MENU								=	1723;
	const MOBILE_DEVICES_SUB_MENU					=	1724;
	const PAYROLL_GROUPING_SUB_MENU					=	1725;
	const LEAVE_GROUPING_SUB_MENU					=	1726;
	const OTHER_BENEFITS_SUB_MENU					=	1727;
	const SETTINGS_MAIN_MENU						=	1800;
	const AUDIT_TRAILS_SUB_MENU						=	1801;
	const TIMEKEEPING_REPORTS_MAIN_MENU				=	1900;
	const ATTENDANCE_SUMMARY_SUB_MENU				=	1901;
	const EMPLOYEE_DTR_SUB_MENU						=	1902;
	const EMPLOYEE_ACTUAL_DTR_SUB_MENU				= 	1903;
	const EMPLOYEE_ACTUAL_DTR_CLOUD_SUB_MENU		=	1904;
	const ANNUAL_REPORTS_MAIN_MENU					=   2000;
	const SALARY_INDEX_CARD_SUB_MENU				=   2001;
	const SPECIAL_PAYROLL_REPORTS_SUB_MENU			=   2002;
	const PAYROLL_POSTING_SUB_MENU					=	2100;
	const PAYROLL_POSTING_VIEW_DETAILS				=	21001;
	const PAYROLL_POSTING_ADD_RECORDS				=	21002;	
	const PAYROLL_POSTING_UPDATE_DETAILS			=	21003;
	const PAYROLL_POSTING_HISTORY_DETAILS			=	21004;

	const PAYROLL_NOTIFICATION_SUB_MENU				=	2200;
	const PAYROLL_NOTIFICATION_VIEW_DETAILS			=	22001;
	const PAYROLL_NOTIFICATION_ADD_RECORDS			=	22002;	
	const PAYROLL_NOTIFICATION_UPDATE_DETAILS		=	22003;
	const PAYROLL_NOTIFICATION_HISTORY_DETAILS		=	22004;

	const BONUSES_SUB_MENU							=	2101;

	const PERFORMANCE_EVALUATION_MAIN_MENU			=   16000;
	const TRAINING_SEMINARS_MONITORING_MAIN_MENU	=   16001;
	const ALL_RECORDS_MENU							=   16002;
	const LOCAL_RECORDS_MENU						=   16003;
	const FOREIGN_RECORDS_MENU						=   16004;

	const SELF_PAYSLIP_REPORT						=   17001;
	const SELF_EMPLOYEE_DTR							=   17002;
	const SELF_LEAVE_CARD							=   17003;
	const SELF_CTO_LEDGER							=   17004;
	
	const RECRUITMENT_MAIN_MENU						= 	2300;
	const VACANT_POSITIONS_SUB_MENU					= 	2301;
	const JOB_OPENINGS_SUB_MENU						= 	2302;
	const APPLICANTS_SUB_MENU						= 	2303;
	const INTERVIEW_SCHEDULES_SUB_MENU				= 	2304;
	const APPLICANT_REPORTS_SUB_MENU				=	2305;
	const APPLICANT_CHECKLISTS_SUB_MENU				=	2306;
	const EXAMINATION_SCHEDULES_SUB_MENU			= 	2307;

	const POSITION_REPORTS_SUB_MENU					= 	3020;
	
	const USER_MOBILE_ACCOUNTS_SUB_MENU				=	4000;

	const TRAVEL_ORDER_MENU							=	2527;
	const TRAVEL_ORDER_SUB_MENU						=	1125;
	const TRAVEL_ORDER_APPROVER_SUB_MENU			=	1126;
	const TRAVEL_ORDER_STATUS_TRACKING_SUB_MENU		=	1127;

	const TRANS_REMITTANCE_OR_SUB_MENU				=	5000;
	const PAYROLL_REPORTS_CERTIFICATES_SUB_MENU		=	5001;
	const LEAVE_CTO_APPROVER						=	1311;
	const HR_DASHBOARD								=	1312;
	const TRAVELORDER_CAN_SELECT_MULTIPLE			=	1313;
	const BIR_ALPHALIST								=	1314;
	const BIR_2316 									=	1315;

	const COMPETENCY_MANAGEMENT						=	14000;
}
?>