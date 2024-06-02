<?php

class PrintedDTR extends MX_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('PrintedDTRCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::EMPLOYEE_DTR_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewPrintedDTR";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/printeddtrlist",$listData,TRUE);
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('PrintedDTR');
			Helper::setMenu('templates/menu_template');
			Helper::setView('printeddtr',$viewData,FALSE);
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
		// var_dump($_POST);die();
    	$fetch_data = $this->PrintedDTRCollection->make_datatables();
		$data = array();
		foreach($fetch_data as $k => $row)
		{
			$buttons = "";
			$buttons_data = "";
			$row->employee_number = $this->Helper->decrypt($row->employee_number,$row->id);
			$row->first_name = $this->Helper->decrypt($row->first_name,$row->id);
			$row->middle_name = $this->Helper->decrypt($row->middle_name,$row->id);
			$row->last_name = $this->Helper->decrypt($row->last_name,$row->id);
			$sub_array = array();
			$sub_array[] = $row->employee_number;
			$sub_array[] = $row->last_name.', '.$row->first_name;
			$sub_array[] = $row->department_name;
			$sub_array[] = $row->location_name;
			$sub_array[] = $row->position_name;
			$sub_array[] = $row->pay_basis;
			$data[] = $sub_array;
		}
		$output = array(
				"draw"                  =>     intval($_POST["draw"]),
				"recordsTotal"          =>     $this->PrintedDTRCollection->get_all_data(),
				"recordsFiltered"     	=>     $this->PrintedDTRCollection->get_filtered_data(),
				"data"                  =>     $data
		);
		echo json_encode($output);
	}

	public function viewPrintedDTR(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewPrintedDTR';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('helpers/printeddtrlist.php', $formData, TRUE);
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
			$result['form'] = $this->load->view('helpers/PrintedDTR.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function viewPrintedDTRAll(){
		die('hit');
		$formData = array();
		$result = array();
		$result['key'] = 'viewPrintedDTRAll';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			die('hit');
			$result['form'] = $this->load->view('helpers/PrintedDTR.php', $formData, TRUE);
		}
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
