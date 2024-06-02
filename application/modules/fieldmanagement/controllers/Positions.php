<?php

class Positions extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('PositionsCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::POSITIONS_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewPositions";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/positionslist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Positions');
			Helper::setMenu('templates/menu_template');
			Helper::setView('positions',$viewData,FALSE);
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

		$grade = "";
		if(isset($_GET["grade"])) $grade = $_GET["grade"];

        $fetch_data = $this->PositionsCollection->make_datatables($grade);  
        $data = array();  
        foreach($fetch_data as $k => $row)  
        {  
        	$buttons = "";
        	$buttons_data = "";
            $sub_array = array();    
			$dt = "";
			foreach ($row as $k => $v) $dt .= ' data-'.$k.'="'.$v.'" ';
			$btns = "";
			$btns = '<a  id="updatePositionsForm" 
			class="updatePositionsForm btn btn-info btn-circle waves-effect waves-circle waves-float"
			href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updatePositionsForm"  data-toggle="tooltip" data-placement="top" title="Update" 
			data-id="'.$row->id.'" '.$dt.'> <i class="material-icons">mode_edit</i></a>';
			if($row->is_active == "1"){
				$btns .= '<a class="deactivatePositions btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Deactivate" 
					data-id="'.$row->id.'" href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/deactivatePositions">
					<i class="material-icons">do_not_disturb</i></a>';
			}else{
				$btns .= '<a class="activatePositions btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Activate" 
				data-id="'.$row->id.'" href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/activatePositions">
				<i class="material-icons">done</i></a>';
			}
			$sub_array[] = $btns;
            $sub_array[] = $row->code;
            $sub_array[] = $row->name;  
            $sub_array[] = $row->is_break;
            $sub_array[] = ($row->pay_basis === "Permanent")? "Monthly": "Bi-Monthly";
            $sub_array[] = $row->grade.' Step-'.$row->step.' (Php. '.$row->salary.')';
			$sub_array[] = ($row->is_active == "1")?'<label class="text-success">ACTIVE</label>':'<label class="text-danger">INACTIVE</label>';
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_GET["draw"]),  
            "recordsTotal"          =>      $this->PositionsCollection->get_all_data($grade),  
            "recordsFiltered"     	=>     $this->PositionsCollection->get_filtered_data($grade),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
	}
	
	public function addPositionsForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addPositions';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/positionsform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updatePositionsForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updatePositions';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/positionsform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function addPositions(){
		$result = array();
		$page = 'addPositions';
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
				$ret =  new PositionsCollection();
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
	public function updatePositions(){
		$result = array();
		$page = 'updatePositions';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new PositionsCollection();
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
	
	public function activatePositions(){
		$result = array();
		$page = 'activatePositions';
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
				$ret =  new PositionsCollection();
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
	public function deactivatePositions(){
		$result = array();
		$page = 'deactivatePositions';
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
				$ret =  new PositionsCollection();
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
	public function getActivePositions(){
		$result = array();
		$page = 'getActivePositions';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new PositionsCollection();
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
	public function getActivePositionsByName(){
		$result = array();
		$page = 'getActivePositions';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new PositionsCollection();
			if($ret->hasRowsActiveByName()) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}
			$result = json_decode($res,true);
			
			$result['key'] = $page;
		}
		echo json_encode($result);
	} 
	public function getPositionsById(){
		$result = array();
		$page = 'getPositionsById';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$id = isset($_POST['id'])?$_POST['id']:"";
			$ret =  new PositionsCollection();
			if($ret->hasRowsById($id)) {
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