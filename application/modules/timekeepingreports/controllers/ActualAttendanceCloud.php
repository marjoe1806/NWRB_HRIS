<?php

class ActualAttendanceCloud extends MX_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('ActualAttendanceCloudCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::EMPLOYEE_ACTUAL_DTR_CLOUD_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewActualAttendanceCloud";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/actualattendancecloudlist",$listData,TRUE);
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('ActualAttendanceCloud');
			Helper::setMenu('templates/menu_template');
			Helper::setView('actualattendancecloud',$viewData,FALSE);
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
    $fetch_data = $this->ActualAttendanceCloudCollection->make_datatables();
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
			$buttons .= ' <a id="viewActualAttendanceCloudForm" '
								. ' class="viewActualAttendanceCloudForm" style="text-decoration: none;" '
								. ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewActualAttendanceCloudForm" '
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
				"recordsTotal"          =>     $this->ActualAttendanceCloudCollection->get_all_data(),
				"recordsFiltered"     	=>     $this->ActualAttendanceCloudCollection->get_filtered_data(),
				"data"                  =>     $data
		);
		echo json_encode($output);
	}

	public function viewActualAttendanceCloudForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewActualAttendanceCloud';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('helpers/actualattendancecloud.php', $formData, TRUE);
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
			$result['form'] = $this->load->view('helpers/actualattendancecloud.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function viewActualAttendanceCloudAll(){
		die('hit');
		$formData = array();
		$result = array();
		$result['key'] = 'viewActualAttendanceCloudAll';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			die('hit');
			$result['form'] = $this->load->view('helpers/actualattendancecloud.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	function fetchRowsSummary(){
		// var_dump($_GET); die();

		$total_undertime = 0;
		$total_absence = 0;
		$fetch_data = $this->ActualAttendanceCloudCollection->make_datatables_summary($_GET['Id'], $_GET['EmployeeNumber'], $_GET['PayrollPeriod'], $_GET['PayrollPeriodId'], $_GET['ShiftId']);
		// var_dump($fetch_data['employee']['']); die();
		foreach ($fetch_data['employee'] as $k => $value) {
			$fetch_data['employee'][$k]['employee_number'] = $this->Helper->decrypt($value['employee_number'], $value['id']);
			$fetch_data['employee'][$k]['employee_id_number'] = $this->Helper->decrypt($value['employee_id_number'], $value['id']);
			$fetch_data['employee'][$k]['last_name'] = $this->Helper->decrypt($value['last_name'], $value['id']);
			$fetch_data['employee'][$k]['first_name'] = $this->Helper->decrypt($value['first_name'], $value['id']);
			$fetch_data['employee'][$k]['middle_name'] = $this->Helper->decrypt($value['middle_name'], $value['id']);
			$fetch_data['employee'][$k]['location'] = $this->ActualAttendanceCloudCollection->getLocation($value['location_id']);
			$fetch_data['employee'][$k]['department'] = $this->ActualAttendanceCloudCollection->getDepartmentById($value['division_id']);
			$fetch_data['employee'][$k]['regular_shift'] = $value['regular_shift'];
			$fetch_data['employee'][$k]['total_undertime'] = $total_undertime;
			$fetch_data['employee'][$k]['total_absence'] = $total_absence;
		}
		$formData['list'] = @$fetch_data['records'];
		// var_dump($formData['list']);die();
		$formData['employee'] = @$fetch_data['employee'][0];
		$formData['details'] = @$fetch_data['details'][0];
		$formData['payroll_period'] = @$fetch_data['payroll_period'];
		$formData['key'] = "viewActualAttendanceCloud";
		$result['table'] = $this->load->view('helpers/actualattendancecloud.php', $formData, TRUE);
		$result['key'] = "viewActualAttendanceCloud";
		echo json_encode($result);

	}

	function getEmployeeList(){
		// var_dump($_POST); die();
		// $result['division'] = @$_POST['division'];
		$employee_sort = array();
		$employees = @$this->ActualAttendanceCloudCollection->getEmployeeList($_POST['pay_basis'], $_POST['location_id']);
		// var_dump($employees); die();
		foreach ($employees as $k => $value) {
			$employees[$k]['employee_number'] = $this->Helper->decrypt($value['employee_number'], $value['id']);
			$employees[$k]['employee_id_number'] = $this->Helper->decrypt($value['employee_id_number'], $value['id']);
			$employees[$k]['last_name'] = $this->Helper->decrypt($value['last_name'], $value['id']);
			$employees[$k]['first_name'] = $this->Helper->decrypt($value['first_name'], $value['id']);
			$employees[$k]['middle_name'] = $this->Helper->decrypt($value['middle_name'], $value['id']);
			$employee_sort[$k] = $employees[$k]['last_name'];
		}
		array_multisort($employee_sort, SORT_ASC, $employees);
		$formData['list'] = $employees;
		$formData['key'] = "viewEmployees";
		$result['table'] = $this->load->view('helpers/employeelist.php', $formData, TRUE);
		$result['key'] = "viewEmployees";
		echo json_encode($result);
	}

	function getTimeDifference($start, $end) {
		$start  = strtotime($start);
		$end = strtotime($end);
		$diff = ($end - $start);
		$minutes = ($diff / 60) / 60;
		return number_format((float)abs($minutes), 2, '.', '');
	}

	function checkPost() {
		$result = $_POST;
		echo json_encode($result);
	}

}

?>
