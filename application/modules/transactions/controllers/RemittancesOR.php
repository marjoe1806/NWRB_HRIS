<?php

class RemittancesOR extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('RemittancesORCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::TRANS_REMITTANCE_OR_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewRemittancesOR";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/remittancesorlist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Remittances OR');
			Helper::setMenu('templates/menu_template');
			Helper::setView('remittancesor',$viewData,FALSE);
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
		$status = "";
		$pay_basis = "";
		$payroll_period_id = "";
		if(isset($_GET["status"])) $status = $_GET["status"];
		if(isset($_GET["pay_basis"])) $pay_basis = $_GET["pay_basis"];
		if(isset($_GET["payroll_period_id"])) $payroll_period_id = $_GET["payroll_period_id"];
        $fetch_data = $this->RemittancesORCollection->make_datatables($status,$pay_basis,$payroll_period_id);  
        $data = array();
        foreach($fetch_data as $k => $row)  
        {  
        	$buttons = "";
        	$buttons_data = "";
            $sub_array = array();    
			$dt = "";
			foreach ($row as $k => $v) $dt .= ' data-'.$k.'="'.$v.'" ';
			$btns = "";
			$btns = '<a  id="updateRemittancesORForm" 
				class="updateRemittancesORForm btn btn-info btn-circle waves-effect waves-circle waves-float"
				href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateRemittancesORForm"  data-toggle="tooltip" data-placement="top" title="Update" 
				data-id="'.$row->id.'" '.$dt.'><i class="material-icons">mode_edit</i></a>';
			// if($row->is_active == "1"){
			// 	$btns .= '<a class="deactivateRemittancesOR btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Deactivate" 
			// 			data-id="'.$row->id.'"href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/deactivateRemittancesOR">
			// 			<i class="material-icons">do_not_disturb</i></a>';
			// }else{
			// 	$btns .= '<a class="activateRemittancesOR btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Activate" 
			// 		data-id="'.$row->id.'" href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/activateRemittancesOR">
			// 		<i class="material-icons">done</i></a>';
			// }
			if($row->loans_id == 3){ $row->category = "PhilHealth"; $row->type = "N/A";}
			if($row->loans_id == 2 && $row->sub_loans_id == 0) $row->type = "MP2 Contribution";
			$sub_array[] = $btns;
            $sub_array[] = $row->official_receipt_no;
            $sub_array[] = $row->pay_basis;
            $sub_array[] = (string)$row->category;
            $sub_array[] = $row->type;
            $sub_array[] =  date("F", strtotime($row->payroll_period));
            $sub_array[] =  date("Y", strtotime($row->payroll_period));
            $sub_array[] =  ($row->date_posted == "") ? "" : date("F d, Y", strtotime($row->date_posted));
            // $sub_array[] = ($row->is_active == "1")?'<label class="text-success">ACTIVE</label>':'<label class="text-danger">INACTIVE</label>';
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_GET["draw"]),  
            "recordsTotal"          =>      $this->RemittancesORCollection->get_all_data($status,$pay_basis,$payroll_period_id),  
            "recordsFiltered"     	=>     $this->RemittancesORCollection->get_filtered_data($status,$pay_basis,$payroll_period_id),  
            "data"                  =>     $data  
        );  
		// var_dump( $data );die();
        echo json_encode($output);  
	}
	public function addRemittancesORForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addRemittancesOR';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/remittancesorform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updateRemittancesORForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateRemittancesOR';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/remittancesorform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function addRemittancesOR(){
		//var_dump($_POST);die();
		$result = array();
		$page = 'addRemittancesOR';
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
				$ret =  new RemittancesORCollection();
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
	public function updateRemittancesOR(){

		//var_dump($_POST);die();
		$result = array();
		$page = 'updateRemittancesOR';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new RemittancesORCollection();
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
	
	public function activateRemittancesOR(){
		$result = array();
		$page = 'activateRemittancesOR';
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
				$ret =  new RemittancesORCollection();
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
	public function deactivateRemittancesOR(){
		$result = array();
		$page = 'deactivateRemittancesOR';
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
				$ret =  new RemittancesORCollection();
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
	public function getActiveRemittancesOR(){
		$result = array();
		$page = 'getActiveRemittancesOR';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new RemittancesORCollection();
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
}

?>