<?php

class OvertimeTransactions extends MX_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('OvertimeTransactionsCollection');
		$this->load->module('session');
		$this->load->library('upload');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}

	public function index() {
		// Helper::rolehook(ModuleRels::HRIS_DASHBOARD);
		$listData = array();
		$viewData = array();
		$page = "viewOvertimeTransactions";
		$listData['key'] = $page;

		$listData['list'] = "";
		$viewData['table'] = $this->load->view("helpers/overtimetransactionslist",$listData,TRUE);
		$viewData['form'] = $this->load->view("forms/businesspermissionform",$listData,TRUE);

		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Overtime Transaction/s');
			Helper::setMenu('templates/menu_template');
			Helper::setView('overtimetransactions',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
			// var_dump($result);die();
		}
		Session::checksession();
	}

	function fetchRows(){
		$fetch_data = $this->OvertimeTransactionsCollection->make_datatables();
		$data = array();
		// var_dump($fetch_data); die();
        foreach($fetch_data as $k => $row) { 
			$status = "";
			$purpose = "";
			switch($row->purpose) {
				case "paid" :	$purpose = "With PAY";
									break;
				case "cto" :	$purpose = "For CTO";
									break;
				default : 		$purpose = "Overtime Request";
									break;
			}

			switch($row->status) {
				case "PENDING" :	$class = "text-warning";$status = "PENDING";
									break;
				case "APPROVED" :	$class = "text-success";$status = "APPROVED";
									break;
				case "REJECTED" :	$class = "text-danger";$status = "DISAPPROVED";
									break;
				case "COMPLETED" :	$class = "text-success";$status = "COMPLETED";
									break;
				default : 	$class = "text-default";
									break;
			} 
        	$buttons = "";
        	$buttons_data = "";
			$emp_id_num = "";
			$fullname = "";
			$emp_id = $row->salt;
        	$emp_id_num = $this->Helper->decrypt((string)$row->employee_id_number,$emp_id);
        	$row->first_name = $this->Helper->decrypt((string)$row->first_name,$emp_id);
        	$row->middle_name = $this->Helper->decrypt((string)$row->middle_name,$emp_id);
        	$row->last_name = $this->Helper->decrypt((string)$row->last_name,$emp_id);
			$row->extension = ($row->extension == null || $row->extension == "")?"":$this->Helper->decrypt((string)$row->extension,$emp_id);
			$time_in = date("h:i A", strtotime($row->time_in));
			$time_out = date("h:i A", strtotime($row->time_out));
			$sub_array = array();    
			// $sub_array[] = date('M d, Y', strtotime(@$value->transaction_date));
            // $sub_array[] = $row->first_name. ' '. $row->middle_name .' '.$row->last_name .' '.$row->extension;
			// $sub_array[] = $row->location;
			// $sub_array[] = $purpose;
			// $sub_array[] = $row->activity_name;
			// $sub_array[] = '<label class="'.$class.'">'.$status.'</label>';
			// $sub_array[] = $row->reject_remarks;
			
			// var_dump($row); die();
           	foreach($row as $k1=>$v1){
				if($k1=="time_in" || $k1=="time_out" || $k1=="transaction_time") $v1 =  date("h:i A", strtotime($v1));
				$buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
			}
			$buttons_data .= ' data-transaction_date_strto="'.date("F d, Y", strtotime($row->transaction_date)).'" ';
			$strto = explode(" ",$row->date_created);
			$buttons_data .= ' data-date_created_strto="'.date("F d, Y", strtotime($strto[0])) .' - '.date("H:i A", strtotime($strto[1])).'" ';
				
            // $buttons .= '<a id="view_report" class="btn btn-success btn-circle waves-effect waves-circle waves-float" '.$buttons_data.' data-toggle = "modal" data-target = "#print_preview_modal" title="Print" ><i class="material-icons">print</i></a>';
			if(Helper::role(ModuleRels::OVERTIME_APPLICATIONS_VIEW_DETAILS)):
			$buttons .= '<a id="viewOvertimeTransactionsForm" class="viewOvertimeTransactionsForm btn btn-info btn-circle waves-effect waves-circle waves-float" href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewOvertimeTransactionsForm'.'"data-toggle="tooltip" data-placement="right" title="View Details" '.$buttons_data.'><i class="material-icons">remove_red_eye</i></a>';
			endif;
			
			if($row->status == "APPROVED" && $row->secondapprove > 0):
				// $buttons .= '<a id="updateOvertimeTransactionsForm" class="updateOvertimeTransactionsForm btn btn-info btn-circle waves-effect waves-circle waves-float" href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateOvertimeTransactionsForm'.'"data-toggle="tooltip" data-placement="top" title="View Details" '.$buttons_data.'><i class="material-icons">mode_edit</i></a>';
				$buttons .=	'<a class="AssignToComplete btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Complete" href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/AssignToComplete" '.$buttons_data.'><i class="material-icons">done</i></a>';
			endif;
			if($row->status == "PENDING" && $row->approve > 0):
			$buttons .=	'<a class="approveOvertimeTransactions btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Approve" href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/approveOvertimeTransactions" '.$buttons_data.'><i class="material-icons">done</i></a>';
			$buttons .=	'<a class="rejectOvertimeTransactions btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Disapproved" href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/rejectOvertimeTransactions" '.$buttons_data.'><i class="material-icons">do_not_disturb</i></a>';
			endif;
	        $sub_array[] = $buttons;
            $sub_array[] = $row->first_name. ' '. $row->middle_name .' '.$row->last_name .' '.$row->extension;
			$sub_array[] = date('M, j Y', strtotime($row->filing_date));;//date('M d, Y', strtotime(@$value->transaction_date));
			$sub_array[] = $purpose;
			$sub_array[] = $row->activity_name;
			$sub_array[] = $time_in != date('00:00 A') ? $time_in .' - '.$time_out : $time_out;
			$sub_array[] = '<label class="'.$class.'">'.$status.'</label>';
			$sub_array[] = $row->reject_remarks;
            $data[] = $sub_array;  
        }  
		
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>     $this->OvertimeTransactionsCollection->get_all_data(),  
            "recordsFiltered"     	=>     $this->OvertimeTransactionsCollection->get_filtered_data(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
	}

	public function AssignToComplete(){
		$result = array();
		$page = 'AssignToComplete';
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
				$ret =  new OvertimeTransactionsCollection();
				if($ret->AssignToComplete($post_data)) {
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

	public function viewOvertimeTransactionsForm() {
		$formData = array();
		$result = array();
		$result['key'] = 'viewOvertimeTransactions';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/overtimetransactionsform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function addOvertimeTransactionsForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addOvertimeTransactions';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/overtimetransactionsform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updateOvertimeTransactionsForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateOvertimeTransactions';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/overtimetransactionsform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}


	public function addOvertimeTransactions(){

		$result = array();
		$page = 'addOvertimeTransactions';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{	
				// var_dump($this->input->post()); die();
			if($this->input->post() && $this->input->post() != null){
				$post_data = array();
				foreach ($_POST['table1'] as $key => $value) {
					
					$post_data[$key]  = $value;
					// var_dump($key);
				}
				var_dump($post_data);
				die();
				$ret =  new OvertimeTransactionsCollection();
				if($ret->addRows($post_data)) {
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

	public function updateOvertimeTransactions(){
		// var_dump($_POST); die();
		$result = array();
		$page = 'updateOvertimeTransactions';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}

				$ret =  new OvertimeTransactionsCollection();
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

	public function approveOvertimeTransactions(){
		$result = array();
		$page = 'approveOvertimeTransactions';
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
				$ret =  new OvertimeTransactionsCollection();
				if($ret->approveRows($post_data)) {
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


	public function rejectOvertimeTransactions(){
		$result = array();
		$page = 'rejectOvertimeTransactions';
		// var_dump($_POST); die();
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
				$ret =  new OvertimeTransactionsCollection();
				if($ret->rejectRows($post_data)) {
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
	public function getActiveOvertimeTransactions(){
		$result = array();
		$page = 'getActiveOvertimeTransactions';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new OvertimeTransactionsCollection();
			if($ret->hasRowsActive()) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}
			$result = json_decode($res,true);
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	public function getActualLogs() {
		$model =  new OvertimeTransactionsCollection();
		$number = $model->getScanningNumber($_POST['employee_id']);
		$result['number'] = @$number;
		$result['dtr'] = @$model->getDailyTimeRecord($number, $_POST['transaction_date']);
		echo json_encode($result);
	}

	function array_push_assoc($array, $key, $value){
		$array[$key] = $value;
		return $array;
	}

	// public function getOvertimeApprovals(){
	// 	$result = array();
	// 	if (!$this->input->is_ajax_request()) show_404();
	// 	else {
	// 		if($this->input->post() && $this->input->post() != null) {
	// 			$post_data = array();
	// 			foreach ($this->input->post() as $k => $v) {
	// 				$post_data[$k] = $this->input->post($k,true);
	// 			}
	// 			$ret =  new OvertimeTransactionsCollection();
	// 			if($ret->fetchOvertimeApprovals($post_data['id'])) {
	// 				$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
	// 			} else {
	// 				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
	// 			}
	// 			$result = json_decode($res,true);
	// 		} else {
	// 			$res = new ModelResponse();
	// 			$result = json_decode($res,true);
	// 		}
	// 	}
	// 	echo json_encode($result);
	// }
}

?>