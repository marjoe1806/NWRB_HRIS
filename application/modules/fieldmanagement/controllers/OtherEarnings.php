<?php

class OtherEarnings extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('OtherEarningsCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::OTHER_EARNINGS_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewOtherEarnings";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/otherearningslist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('OtherEarnings');
			Helper::setMenu('templates/menu_template');
			Helper::setView('otherearnings',$viewData,FALSE);
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
		if(isset($_GET["status"])) $status = $_GET["status"];
        $fetch_data = $this->OtherEarningsCollection->make_datatables($status);  
        $data = array();  
        foreach($fetch_data as $k => $row)  
        {  
        	$buttons = "";
        	$buttons_data = "";
            $sub_array = array();    
			$dt = "";
			foreach ($row as $k => $v) $dt .= ' data-'.$k.'="'.$v.'" ';
			$btns = "";
			$btns = '<a  id="updateOtherEarningsForm" 
						class="updateOtherEarningsForm btn btn-info btn-circle waves-effect waves-circle waves-float"
						href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateOtherEarningsForm"  data-toggle="tooltip" data-placement="top" title="Update" 
						data-id="'.$row->id.'" '.$dt.'>
						<i class="material-icons">mode_edit</i>
					</a>';
			if($row->is_active == "1"){
				$btns .= '<a class="deactivateOtherEarnings btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Deactivate" 
					data-id="'.$row->id.'"
					href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/deactivateOtherEarnings">
					<i class="material-icons">do_not_disturb</i>
				</a>';
			}else{
				$btns .= '<a class="activateOtherEarnings btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Activate" 
					data-id="'.$row->id.'"
					href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/activateOtherEarnings">
					<i class="material-icons">done</i>
				</a>';
			}
			$sub_array[] = $btns;
            $sub_array[] = $row->earning_code;
            $sub_array[] = $row->description;  
            $sub_array[] = ($row->is_active == "1")?'<label class="text-success">ACTIVE</label>':'<label class="text-danger">INACTIVE</label>';
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_GET["draw"]),  
            "recordsTotal"          =>      $this->OtherEarningsCollection->get_all_data($status),  
            "recordsFiltered"     	=>     $this->OtherEarningsCollection->get_filtered_data($status),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
	}
	public function addOtherEarningsForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addOtherEarnings';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/otherearningsform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updateOtherEarningsForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateOtherEarnings';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/otherearningsform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function addOtherEarnings(){
		$result = array();
		$page = 'addOtherEarnings';
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
				$ret =  new OtherEarningsCollection();
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
	public function updateOtherEarnings(){
		$result = array();
		$page = 'updateOtherEarnings';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new OtherEarningsCollection();
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
	
	public function activateOtherEarnings(){
		$result = array();
		$page = 'activateOtherEarnings';
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
				$ret =  new OtherEarningsCollection();
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
	public function deactivateOtherEarnings(){
		$result = array();
		$page = 'deactivateOtherEarnings';
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
				$ret =  new OtherEarningsCollection();
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
	public function getActiveOtherEarnings(){
		$result = array();
		$page = 'getActiveOtherEarnings';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new OtherEarningsCollection();
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