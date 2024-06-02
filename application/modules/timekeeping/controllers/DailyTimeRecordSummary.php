<?php

class DailyTimeRecordSummary extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('DailyTimeRecordSummaryCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		$listData = array();
		$viewData = array();
		$page = "viewDailyTimeRecordSummary";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/dailytimerecordsummarylist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Daily Time Record Summary');
			Helper::setMenu('templates/menu_template');
			Helper::setView('dailytimerecordsummary',$viewData,FALSE);
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
				$fetch_data = $this->DailyTimeRecordSummaryCollection->make_datatables();
				$data = array();  
        foreach($fetch_data as $k => $row)  
        {  
        	$buttons = "";
        	$buttons_data = "";
						$sub_array = array();
						$sub_array[] = $this->Helper->decrypt($row->employee_id_number, $row->id);  
						$sub_array[] = $this->Helper->decrypt($row->first_name, $row->id) . ' ' . $this->Helper->decrypt($row->last_name, $row->id);
						$sub_array[] = $row->department_name;
						$sub_array[] = $row->office_name;
						$sub_array[] = $row->position_name;
						$sub_array[] = $row->pay_basis;
            foreach($row as $k1=>$v1){
            	$buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
            }
            $buttons .= ' <a id="viewDailyTimeRecordSummaryForm" ' 
            		  . ' class="viewDailyTimeRecordSummaryForm" style="text-decoration: none;" '
            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewDailyTimeRecordSummaryForm" '
            		  . $buttons_data
            		  . ' > '
            		  . ' <button class="btn btn-primary btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Details">'
            		  . ' <i class="material-icons">list</i> '
            		  . ' </button> '
            		  . ' </a> ';
									
	        $sub_array[] = $buttons;
          $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>     $this->DailyTimeRecordSummaryCollection->get_all_data(),  
            "recordsFiltered"     	=>     $this->DailyTimeRecordSummaryCollection->get_filtered_data(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
		}

		function fetchRowsSummary(){
			$fetch_data = $this->DailyTimeRecordSummaryCollection->make_datatables_summary($_GET['Id'], $_GET['EmployeeNumber'], $_GET['PayrollPeriod'], $_GET['PayrollPeriodId'], $_GET['ShiftId']);
			// var_dump(json_encode($fetch_data));die();
			$data = array();
			$total['no_of_days'] = 0;
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
				foreach($fetch_data as $k => $row) {
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
	
					$total['no_of_days']++;
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
			$result['table'] = $this->load->view('forms/dailytimerecordsummaryform.php', $formData, TRUE);
			echo json_encode($result); 

	}

	public function addDailyTimeRecordSummaryForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addDailyTimeRecordSummary';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/dailytimerecordsummaryform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updateDailyTimeRecordSummaryForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateDailyTimeRecordSummary';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/dailytimerecordsummaryform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function viewDailyTimeRecordSummaryForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewDailyTimeRecordSummary';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/dailytimerecordsummaryform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function addDailyTimeRecordSummary(){
		$result = array();
		$page = 'addDailyTimeRecordSummary';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			if($this->input->post() && $this->input->post() != null)
			{
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new DailyTimeRecordSummaryCollection();
				if($ret->addRows($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			}
			else
			{
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}
	public function updateDailyTimeRecordSummary(){
		$result = array();
		$page = 'updateDailyTimeRecordSummary';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new DailyTimeRecordSummaryCollection();
				if($ret->updateRows($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			} else {
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}
	
	public function activateDailyTimeRecordSummary(){
		$result = array();
		$page = 'activateDailyTimeRecordSummary';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			if($this->input->post() && $this->input->post() != null)
			{
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new DailyTimeRecordSummaryCollection();
				if($ret->activeRows($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			}
			else
			{
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}
	public function deactivateDailyTimeRecordSummary(){
		$result = array();
		$page = 'deactivateDailyTimeRecordSummary';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			if($this->input->post() && $this->input->post() != null)
			{
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new DailyTimeRecordSummaryCollection();
				if($ret->inactiveRows($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			}
			else
			{
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	public function getActiveDailyTimeRecordSummary(){
		$result = array();
		$page = 'getActiveDailyTimeRecordSummary';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new DailyTimeRecordSummaryCollection();
			if($ret->hasRows()) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}
			$result = json_decode($res,true);
			if(isset($result['Data'])){
				foreach ($result['Data']['details'] as $k1 => $v1) {
					$result['Data']['details'][$k1]['employee_number'] = $this->Helper->decrypt($v1['employee_number'],$v1['id']);
					$result['Data']['details'][$k1]['first_name'] = $this->Helper->decrypt($v1['first_name'],$v1['id']);
    			$result['Data']['details'][$k1]['middle_name'] = $this->Helper->decrypt($v1['middle_name'],$v1['id']);
    			$result['Data']['details'][$k1]['last_name'] = $this->Helper->decrypt($v1['last_name'],$v1['id']);
				}
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}
	public function getDailyTimeRecordSummaryById(){
		$result = array();
		$page = 'getDailyTimeRecordSummaryById';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			if(isset($_GET['Id']) && $_GET['Id'] != null)
			{
				$ret =  new DailyTimeRecordSummaryCollection();
				if($ret->hasRows()) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			}
			else
			{
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	public function getActiveEmployees(){
		$result = array();
		$page = 'getActiveEmployees';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new DailyTimeRecordSummaryCollection();
			if($ret->hasRowsEmployee()) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}
			$result = json_decode($res,true);
			if(isset($result['Data'])){
				foreach ($result['Data']['details'] as $k1 => $v1) {
					$result['Data']['details'][$k1]['employee_number'] = $this->Helper->decrypt($v1['employee_number'],$v1['id']);
					$result['Data']['details'][$k1]['first_name'] = $this->Helper->decrypt($v1['first_name'],$v1['id']);
    			$result['Data']['details'][$k1]['middle_name'] = $this->Helper->decrypt($v1['middle_name'],$v1['id']);
    			$result['Data']['details'][$k1]['last_name'] = $this->Helper->decrypt($v1['last_name'],$v1['id']);
				}
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

}

?>