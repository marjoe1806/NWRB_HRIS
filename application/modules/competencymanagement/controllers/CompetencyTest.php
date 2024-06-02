<?php

class CompetencyTest extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('CompetencyTestCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::DIVISIONS_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewCompetencyTest";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/competencytestlist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Competency Test');
			Helper::setMenu('templates/menu_template');
			Helper::setView('competencytest',$viewData,FALSE);
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
        $fetch_data = $this->CompetencyTestCollection->make_datatables($status);  
        $data = array();  
        foreach($fetch_data as $k => $row)  
        {  
        	$buttons = "";
        	$buttons_data = "";
            $sub_array = array();    
			$dt = "";
			if($row->type == "general"){
				$type_name = "General Ability";
			}else if($row->type == "promotion"){
				$type_name = "2nd Level Promotion";
			}else if($row->type == "ethics"){
				$type_name = "Ethics";
			}
			foreach ($row as $k => $v) $dt .= ' data-'.$k.'="'.$v.'" ';
			$btns = "";
			$btns = '<a  id="updateCompetencyTestForm" 
				class="updateCompetencyTestForm btn btn-primary btn-circle waves-effect waves-circle waves-float"
				href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateCompetencyTestForm"  data-toggle="tooltip" data-placement="top" title="View" 
				data-id="'.$row->id.'" '.$dt.'><i class="material-icons">remove_red_eye</i></a>';
			$btns .= '<a  id="addCompetencyTestForm" 
				class="addCompetencyTestForm btn btn-info btn-circle waves-effect waves-circle waves-float"
				href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addCompetencyTestForm"  data-toggle="tooltip" data-placement="top" title="Duplicate" 
				data-id="'.$row->id.'" '.$dt.'><i class="material-icons">content_copy</i></a>';
			if($row->is_active == "1"){
				$btns .= '<a class="deactivateCompetencyTest btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Deactivate" 
						data-id="'.$row->id.'"href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/deactivateCompetencyTest">
						<i class="material-icons">do_not_disturb</i></a>';
			}else{
				$btns .= '<a class="activateCompetencyTest btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Activate" 
					data-id="'.$row->id.'" href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/activateCompetencyTest">
					<i class="material-icons">done</i></a>';
			}
			$sub_array[] = $btns;
            $sub_array[] = $row->date_created;
            $sub_array[] = $type_name;
            $sub_array[] = $row->employee_name;
            $sub_array[] = ($row->is_active == "1")?'<label class="text-success">ACTIVE</label>':'<label class="text-danger">INACTIVE</label>';
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_GET["draw"]),  
            "recordsTotal"          =>      $this->CompetencyTestCollection->get_all_data($status),  
            "recordsFiltered"     	=>     $this->CompetencyTestCollection->get_filtered_data($status),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
	}
	public function addCompetencyTestForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addCompetencyTest';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/competencytestform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updateCompetencyTestForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateCompetencyTest';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/competencytestform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function addCompetencyTest(){
		$result = array();
		$page = 'addCompetencyTest';
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
				$ret =  new CompetencyTestCollection();
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
	public function updateCompetencyTest(){
		$result = array();
		$page = 'updateCompetencyTest';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new CompetencyTestCollection();
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
	
	public function activateCompetencyTest(){
		$result = array();
		$page = 'activateCompetencyTest';
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
				$ret =  new CompetencyTestCollection();
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
	public function deactivateCompetencyTest(){
		$result = array();
		$page = 'deactivateCompetencyTest';
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
				$ret =  new CompetencyTestCollection();
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
	public function getActiveCompetencyTest(){
		$result = array();
		$page = 'getActiveCompetencyTest';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new CompetencyTestCollection();
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
	
	public function getQuestions(){
		if (!$this->input->is_ajax_request())
		   show_404();
		
		$ret = $this->CompetencyTestCollection->getQuestionRows($_POST["id"]);
		echo json_encode($ret);
	}
	
}

?>