<?php

class AttendanceSummary extends MX_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('AttendanceSummaryCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::ATTENDANCE_SUMMARY_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewAttendanceSummary";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/attendancesummarylist",$listData,TRUE);
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('AttendanceSummary');
			Helper::setMenu('templates/menu_template');
			Helper::setView('attendancesummary',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}

	function fetchRows(){
    $fetch_data = $this->AttendanceSummaryCollection->make_datatables();
		$data = array();
		foreach($fetch_data as $k => $row)
		{
			$buttons = "";
			$buttons_data = "";
			$row->employee_number = $this->Helper->decrypt($row->employee_number,$row->id);
			$row->employee_id_number = $this->Helper->decrypt($row->employee_id_number,$row->id);
			$row->first_name = $this->Helper->decrypt($row->first_name,$row->id);
			$row->middle_name = $this->Helper->decrypt($row->middle_name,$row->id);
			$row->last_name = $this->Helper->decrypt($row->last_name,$row->id);
			$sub_array = array();
			$sub_array[] = $row->employee_id_number;
			$sub_array[] = $row->first_name.' '.$row->last_name;
			$sub_array[] = $row->department_name;
			$sub_array[] = $row->office_name;
			$sub_array[] = $row->position_name;
			$sub_array[] = $row->pay_basis;
			foreach($row as $k1=>$v1){
				$buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
			}
			$buttons .= ' <a id="viewAttendanceSummaryForm" '
								. ' class="viewAttendanceSummaryForm" style="text-decoration: none;" '
								. ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewAttendanceSummaryForm" '
								. $buttons_data
								. ' > '
								. ' <button class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Summary">'
								. ' <i class="material-icons">remove_red_eye</i> '
								. ' </button> '
								. ' </a> ';
			$sub_array[] = $buttons;
			$data[] = $sub_array;
		}
		$output = array(
				"draw"                  =>     intval($_POST["draw"]),
				"recordsTotal"          =>     $this->AttendanceSummaryCollection->get_all_data(),
				"recordsFiltered"     	=>     $this->AttendanceSummaryCollection->get_filtered_data(),
				"data"                  =>     $data
		);
		echo json_encode($output);
	}

	public function viewAttendanceSummaryForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewAttendanceSummary';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('helpers/employeeattendancesummary.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function attendanceSummaryContainer(){
		$formData = array();
		$result = array();
		$result['key'] = 'attendanceSummaryContainer';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('helpers/employeeattendancesummary.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function viewAttendanceSummaryAll(){
		die('hit');
		$formData = array();
		$result = array();
		$result['key'] = 'viewAttendanceSummaryAll';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			die('hit');
			$result['form'] = $this->load->view('helpers/employeeattendancesummary.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	function fetchRowsSummaryAll(){
		$fetch_data = $this->AttendanceSummaryCollection->make_datatables_summary_all($_GET['PayBasis'], $_GET['PayrollPeriod'], $_GET['PayrollPeriodId']);
		$data = array();

		foreach ($fetch_data['employee'] as $k => $value) {
			$fetch_data['employee'][$k]['employee_number'] = $this->Helper->decrypt($value['employee_number'], $value['id']);
			$fetch_data['employee'][$k]['employee_id_number'] = $this->Helper->decrypt($value['employee_id_number'], $value['id']);
			$fetch_data['employee'][$k]['last_name'] = $this->Helper->decrypt($value['last_name'], $value['id']);
			$fetch_data['employee'][$k]['first_name'] = $this->Helper->decrypt($value['first_name'], $value['id']);
			$fetch_data['employee'][$k]['middle_name'] = $this->Helper->decrypt($value['middle_name'], $value['id']);
		}

		foreach ($fetch_data['records'] as $key => $value) {
			$total[$key]['no_of_days'] = isset($value['total_working_days']) ? $value['total_working_days'] : 0;
			$total[$key]['no_of_hours'] = 0;
			$total[$key]['leave_hours'] = 0;
			$total[$key]['late_hours'] = 0;
			$total[$key]['undertime_hours'] = 0;
			$total[$key]['regular_hours'] = 0;
			$total[$key]['regular_nightdiff_hours'] = 0;
			$total[$key]['absent_hours'] = 0;
			$total[$key]['break_deduction'] = 0;
			$total[$key]['present_day'] = 0;
			$total[$key]['regular_overtime'] = 0;
			$total[$key]['nightdiff_overtime'] = 0;
			$total[$key]['restday_overtime'] = 0;
			$total[$key]['legal_holiday_overtime'] = 0;
			$total[$key]['legal_holiday_restday_overtime'] = 0;
			$total[$key]['special_holiday_overtime'] = 0;
			$total[$key]['special_holiday_restday_overtime'] = 0;
			$total[$key]['regular_excess_overtime'] = 0;
			$total[$key]['restday_excess_overtime'] = 0;
			$total[$key]['legal_excess_overtime'] = 0;
			$total[$key]['legal_excess_restday_overtime'] = 0;
			$total[$key]['special_excess_overtime'] = 0;
			$total[$key]['special_excess_restday_overtime'] = 0;

			foreach($fetch_data['records'][$key]['data'] as $k => $row) {
				$sub_array = array();
				$sub_array['transaction_date'] = date("m/d/y", strtotime($row["transaction_date"]));
				$sub_array['transaction_day'] = date("D", strtotime($row["transaction_date"]));
				$sub_array['official_time_in'] = $row["official_time_in"] != null ? date('g:i A', strtotime($row["official_time_in"])) : '<label>None</label>';
				$sub_array['official_time_out'] = $row["official_time_out"] != null ? date('g:i A', strtotime($row["official_time_out"])) : '<label>None</label>';
				$sub_array['time_in'] = isset($row["time_in"]) ? date('g:i A', strtotime($row["time_in"])) : '<label class="text-danger">No Log</label>';
				$sub_array['time_out'] = isset($row["time_out"]) ? date('g:i A', strtotime($row["time_out"])) : '<label class="text-danger">No Log</label>';
				$sub_array['leave_hours'] = number_format((float)abs($row["leave_hours"]), 2, '.', '');
				$sub_array['late_hours'] = number_format((float)abs($row["late_hours"]), 2, '.', '');
				$sub_array['undertime_hours'] = number_format((float)abs($row["undertime_hours"]), 2, '.', '');
				$sub_array['regular_hours'] = number_format((float)abs($row["regular_hours"]), 2, '.', '');
				$sub_array['regular_nightdiff_hours'] = number_format((float)abs($row["regular_nightdiff_hours"]), 2, '.', '');
				$sub_array['absent_hours'] = number_format((float)abs($row["absent_hours"]), 2, '.', '');
				$sub_array['break_deduction'] = number_format((float)abs($row["break_deduction"]), 2, '.', '');
				$sub_array['present_day'] = number_format((float)abs($row["present_day"]), 2, '.', '');
				$sub_array['regular_overtime'] = number_format((float)abs($row["regular_overtime"]), 2, '.', '');
				$sub_array['nightdiff_overtime'] = number_format((float)abs($row["nightdiff_overtime"]), 2, '.', '');
				$sub_array['restday_overtime'] = number_format((float)abs($row["restday_overtime"]), 2, '.', '');
				$sub_array['legal_holiday_overtime'] = number_format((float)abs($row["legal_holiday_overtime"]), 2, '.', '');
				$sub_array['legal_holiday_restday_overtime'] = number_format((float)abs($row["legal_holiday_restday_overtime"]), 2, '.', '');
				$sub_array['special_holiday_overtime'] = number_format((float)abs($row["special_holiday_overtime"]), 2, '.', '');
				$sub_array['special_holiday_restday_overtime'] = number_format((float)abs($row["special_holiday_restday_overtime"]), 2, '.', '');
				$sub_array['regular_excess_overtime'] = number_format((float)abs($row["regular_excess_overtime"]), 2, '.', '');
				$sub_array['restday_excess_overtime'] = number_format((float)abs($row["restday_excess_overtime"]), 2, '.', '');
				$sub_array['legal_excess_overtime'] = number_format((float)abs($row["legal_excess_overtime"]), 2, '.', '');
				$sub_array['legal_excess_restday_overtime'] = number_format((float)abs($row["legal_excess_restday_overtime"]), 2, '.', '');
				$sub_array['special_excess_overtime'] = number_format((float)abs($row["special_excess_overtime"]), 2, '.', '');
				$sub_array['special_excess_restday_overtime'] = number_format((float)abs($row["special_excess_restday_overtime"]), 2, '.', '');
				$data[$k] = $sub_array;
				$total[$key]['leave_hours'] += $row["leave_hours"];
				$total[$key]['late_hours'] += $row["late_hours"];
				$total[$key]['undertime_hours'] += $row["undertime_hours"];
				$total[$key]['regular_hours'] += $row["regular_hours"];
				$total[$key]['regular_nightdiff_hours'] += $row["regular_nightdiff_hours"];
				$total[$key]['absent_hours'] += $row["absent_hours"];
				$total[$key]['break_deduction'] += $row["break_deduction"];
				$total[$key]['present_day'] += $row["present_day"];
				$total[$key]['regular_overtime'] += $row["regular_overtime"];
				$total[$key]['nightdiff_overtime'] += $row["nightdiff_overtime"];
				$total[$key]['restday_overtime'] += $row["restday_overtime"];
				$total[$key]['legal_holiday_overtime'] += $row["legal_holiday_overtime"];
				$total[$key]['legal_holiday_restday_overtime'] += $row["legal_holiday_restday_overtime"];
				$total[$key]['special_holiday_overtime'] += $row["special_holiday_overtime"];
				$total[$key]['special_holiday_restday_overtime'] += $row["special_holiday_restday_overtime"];
				$total[$key]['regular_excess_overtime'] += $row["regular_excess_overtime"];
				$total[$key]['restday_excess_overtime'] += $row["restday_excess_overtime"];
				$total[$key]['legal_excess_overtime'] += $row["legal_excess_overtime"];
				$total[$key]['legal_excess_restday_overtime'] += $row["legal_excess_restday_overtime"];
				$total[$key]['special_excess_overtime'] += $row["special_excess_overtime"];
				$total[$key]['special_excess_restday_overtime'] += $row["special_excess_restday_overtime"];
			}

			$total[$key]['no_of_hours'] = $total[$key]['no_of_days'] * 8;
			$attendance[$key]['list'] = $data;
			$attendance[$key]['total'] = $total;

		}

		$formData['total'] = $total;
		$formData['list'] = $attendance;
		$formData['employee'] = $fetch_data['employee'];
		$formData['key'] = "viewAttendanceSummaryAll";
		$result['table'] = $this->load->view('helpers/employeeattendancesummary.php', $formData, TRUE);
		$result['key'] = "viewAttendanceSummaryAll";
		echo json_encode($result);
}

function fetchRowsSummary(){
	$fetch_data = $this->AttendanceSummaryCollection->make_datatables_summary($_GET['Id'], $_GET['EmployeeNumber'], $_GET['PayrollPeriod'], $_GET['PayrollPeriodId'], $_GET['ShiftId']);
	// var_dump($fetch_data); die();
	$data = array();
	$total['no_of_days'] = isset($fetch_data['total_working_days']) ? $fetch_data['total_working_days'] : 0;
	$total['no_of_hours'] = 0;
	$total['leave_hours'] = 0;
	$total['late_hours'] = 0;
	$total['undertime_hours'] = 0;
	$total['regular_hours'] = 0;
	$total['regular_nightdiff_hours'] = 0;
	$total['absent_hours'] = 0;
	$total['break_deduction'] = 0;
	$total['present_day'] = 0;
	$total['regular_overtime'] = 0;
	$total['nightdiff_overtime'] = 0;
	$total['restday_overtime'] = 0;
	$total['legal_holiday_overtime'] = 0;
	$total['legal_holiday_restday_overtime'] = 0;
	$total['special_holiday_overtime'] = 0;
	$total['special_holiday_restday_overtime'] = 0;
	// $total['regular_excess_overtime'] = 0;
	$total['restday_excess_overtime'] = 0;
	$total['legal_excess_overtime'] = 0;
	$total['legal_excess_restday_overtime'] = 0;
	$total['special_excess_overtime'] = 0;
	$total['special_excess_restday_overtime'] = 0;
	if(isset($fetch_data)) {
		// var_dump($fetch_data);die();
		foreach($fetch_data['data'] as $k => $row) {
			$sub_array = array();
			$sub_array['transaction_date'] = date("m/d/y", strtotime($row["transaction_date"]));
			$sub_array['transaction_day'] = date("D", strtotime($row["transaction_date"]));
			$sub_array['official_time_in'] = $row["official_time_in"] != null ? date('g:i A', strtotime($row["official_time_in"])) : '<label>None</label>';
			$sub_array['official_time_out'] = $row["official_time_out"] != null ? date('g:i A', strtotime($row["official_time_out"])) : '<label>None</label>';
			$sub_array['time_in'] = isset($row["time_in"]) ? date('g:i A', strtotime($row["time_in"])) : '<label class="text-danger">No Log</label>';
			$sub_array['time_out'] = isset($row["time_out"]) ? date('g:i A', strtotime($row["time_out"])) : '<label class="text-danger">No Log</label>';
			$sub_array['leave_hours'] = number_format((float)abs($row["leave_hours"]), 2, '.', '');
			$sub_array['late_hours'] = number_format((float)abs($row["late_hours"]), 2, '.', '');
			$sub_array['undertime_hours'] = number_format((float)abs($row["undertime_hours"]), 2, '.', '');
			$sub_array['regular_hours'] = number_format((float)abs($row["regular_hours"]), 2, '.', '');
			$sub_array['regular_nightdiff_hours'] = number_format((float)abs($row["regular_nightdiff_hours"]), 2, '.', '');
			$sub_array['absent_hours'] = number_format((float)abs($row["absent_hours"]), 2, '.', '');
			$sub_array['break_deduction'] = number_format((float)abs($row["break_deduction"]), 2, '.', '');
			$sub_array['present_day'] = number_format((float)abs($row["present_day"]), 2, '.', '');
			$sub_array['regular_overtime'] = number_format((float)abs($row["regular_overtime"]), 2, '.', '');
			$sub_array['nightdiff_overtime'] = number_format((float)abs($row["nightdiff_overtime"]), 2, '.', '');
			$sub_array['restday_overtime'] =  number_format((float)abs($row["restday_overtime"]), 2, '.', '');
			$sub_array['legal_holiday_overtime'] = number_format((float)abs($row["legal_holiday_overtime"]), 2, '.', '');
			$sub_array['legal_holiday_restday_overtime'] = number_format((float)abs($row["legal_holiday_restday_overtime"]), 2, '.', '');
			$sub_array['special_holiday_overtime'] = number_format((float)abs($row["special_holiday_overtime"]), 2, '.', '');
			$sub_array['special_holiday_restday_overtime'] = number_format((float)abs($row["special_holiday_restday_overtime"]), 2, '.', '');
			// $sub_array['regular_excess_overtime'] = number_format((float)abs($row["regular_excess_overtime"]), 2, '.', '');
			$sub_array['restday_excess_overtime'] = number_format((float)abs($row["restday_excess_overtime"]), 2, '.', '');
			$sub_array['legal_excess_overtime'] = number_format((float)abs($row["legal_excess_overtime"]), 2, '.', '');
			$sub_array['legal_excess_restday_overtime'] = number_format((float)abs($row["legal_excess_restday_overtime"]), 2, '.', '');
			$sub_array['special_excess_overtime'] = number_format((float)abs($row["special_excess_overtime"]), 2, '.', '');
			$sub_array['special_excess_restday_overtime'] = number_format((float)abs($row["special_excess_restday_overtime"]), 2, '.', '');
			$data[] = $sub_array;
			$total['leave_hours'] += $row["leave_hours"];
			$total['late_hours'] += $row["late_hours"];
			$total['undertime_hours'] += $row["undertime_hours"];
			$total['regular_hours'] += $row["regular_hours"];
			$total['regular_nightdiff_hours'] += $row["regular_nightdiff_hours"];
			$total['absent_hours'] += $row["absent_hours"];
			$total['break_deduction'] += $row["break_deduction"];
			$total['present_day'] += $row["present_day"];
			$total['regular_overtime'] += $row["regular_overtime"];
			$total['nightdiff_overtime'] += $row["nightdiff_overtime"];
			$total['restday_overtime'] += $row["restday_overtime"];
			$total['legal_holiday_overtime'] += $row["legal_holiday_overtime"];
			$total['legal_holiday_restday_overtime'] += $row["legal_holiday_restday_overtime"];
			$total['special_holiday_overtime'] += $row["special_holiday_overtime"];
			$total['special_holiday_restday_overtime'] += $row["special_holiday_restday_overtime"];
			// $total['regular_excess_overtime'] += $row["regular_excess_overtime"];
			$total['restday_excess_overtime'] += $row["restday_excess_overtime"];
			$total['legal_excess_overtime'] += $row["legal_excess_overtime"];
			$total['legal_excess_restday_overtime'] += $row["legal_excess_restday_overtime"];
			$total['special_excess_overtime'] += $row["special_excess_overtime"];
			$total['special_excess_restday_overtime'] += $row["special_excess_restday_overtime"];
		}
	}
		$total['no_of_hours'] = $total['no_of_days'] * 8;
		$formData['total'] = $total;
		$formData['list'] = $data;
		$formData['key'] = "viewAttendanceSummary";
		$result['table'] = $this->load->view('helpers/employeeattendancesummary.php', $formData, TRUE);
		$result['key'] = "viewAttendanceSummary";
		echo json_encode($result);
	}

}

?>