<?php

class OtherBenefits extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('OtherBenefitsCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::OTHER_BENEFITS_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewOtherBenefits";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/otherbenefitslist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('OtherBenefits');
			Helper::setMenu('templates/menu_template');
			Helper::setView('otherbenefits',$viewData,FALSE);
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
        $fetch_data = $this->OtherBenefitsCollection->make_datatables($status);  
        $data = array();  
        foreach($fetch_data as $k => $row)  
        {  
        	$buttons = "";
        	$buttons_data = "";
            $sub_array = array();    
			$dt = "";
			foreach ($row as $k => $v) $dt .= ' data-'.$k.'="'.$v.'" ';
			$btns = "";
			$btns = '<a  id="updateOtherBenefitsForm" 
						class="updateOtherBenefitsForm btn btn-info btn-circle waves-effect waves-circle waves-float"
						href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateOtherBenefitsForm"  data-toggle="tooltip" data-placement="top" title="Update" 
						data-id="'.$row->id.'" '.$dt.'>
						<i class="material-icons">mode_edit</i>
					</a>';
			if($row->is_active == "1"){
				$btns .= '<a class="deactivateOtherBenefits btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Deactivate" 
					data-id="'.$row->id.'"
					href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/deactivateOtherBenefits">
					<i class="material-icons">do_not_disturb</i>
				</a>';
			}else{
				$btns .= '<a class="activateOtherBenefits btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Activate" 
					data-id="'.$row->id.'"
					href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/activateOtherBenefits">
					<i class="material-icons">done</i>
				</a>';
			}
			$sub_array[] = $btns;
            $sub_array[] = $row->name;
            $sub_array[] = $row->description;  
            $sub_array[] = ($row->is_active == "1")?'<label class="text-success">ACTIVE</label>':'<label class="text-danger">INACTIVE</label>';
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_GET["draw"]),  
            "recordsTotal"          =>      $this->OtherBenefitsCollection->get_all_data($status),  
            "recordsFiltered"     	=>     $this->OtherBenefitsCollection->get_filtered_data($status),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
	}
	public function addOtherBenefitsForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addOtherBenefits';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/otherbenefitsform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updateOtherBenefitsForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateOtherBenefits';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/otherbenefitsform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function addOtherBenefits(){
		$result = array();
		$page = 'addOtherBenefits';
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
				$ret =  new OtherBenefitsCollection();
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
	public function updateOtherBenefits(){
		$result = array();
		$page = 'updateOtherBenefits';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new OtherBenefitsCollection();
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
	
	public function activateOtherBenefits(){
		$result = array();
		$page = 'activateOtherBenefits';
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
				$ret =  new OtherBenefitsCollection();
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
	public function deactivateOtherBenefits(){
		$result = array();
		$page = 'deactivateOtherBenefits';
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
				$ret =  new OtherBenefitsCollection();
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
	public function getActiveOtherBenefits(){
		$result = array();
		$page = 'getActiveOtherBenefits';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new OtherBenefitsCollection();
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