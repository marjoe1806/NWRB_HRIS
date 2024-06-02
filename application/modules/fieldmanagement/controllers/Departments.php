<?php

class Departments extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('DepartmentsCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::DIVISIONS_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewDepartments";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/departmentslist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Departments');
			Helper::setMenu('templates/menu_template');
			Helper::setView('departments',$viewData,FALSE);
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
        $fetch_data = $this->DepartmentsCollection->make_datatables($status);  
        $data = array();  
        foreach($fetch_data as $k => $row)  
        {  
        	$buttons = "";
        	$buttons_data = "";
            $sub_array = array();    
			$dt = "";
			foreach ($row as $k => $v) $dt .= ' data-'.$k.'="'.$v.'" ';
			$btns = "";
			$btns = '<a  id="updateDepartmentsForm" 
				class="updateDepartmentsForm btn btn-info btn-circle waves-effect waves-circle waves-float"
				href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateDepartmentsForm"  data-toggle="tooltip" data-placement="top" title="Update" 
				data-id="'.$row->id.'" '.$dt.'><i class="material-icons">mode_edit</i></a>';
			if($row->is_active == "1"){
				$btns .= '<a class="deactivateDepartments btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Deactivate" 
						data-id="'.$row->id.'"href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/deactivateDepartments">
						<i class="material-icons">do_not_disturb</i></a>';
			}else{
				$btns .= '<a class="activateDepartments btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Activate" 
					data-id="'.$row->id.'" href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/activateDepartments">
					<i class="material-icons">done</i></a>';
			}
			$sub_array[] = $btns;
            $sub_array[] = $row->code;
            $sub_array[] = $row->department_name;
            $sub_array[] = ($row->is_active == "1")?'<label class="text-success">ACTIVE</label>':'<label class="text-danger">INACTIVE</label>';
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_GET["draw"]),  
            "recordsTotal"          =>      $this->DepartmentsCollection->get_all_data($status),  
            "recordsFiltered"     	=>     $this->DepartmentsCollection->get_filtered_data($status),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
	}
	public function addDepartmentsForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addDepartments';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/departmentsform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updateDepartmentsForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateDepartments';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/departmentsform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function addDepartments(){
		$result = array();
		$page = 'addDepartments';
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
				$ret =  new DepartmentsCollection();
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
	public function updateDepartments(){
		$result = array();
		$page = 'updateDepartments';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new DepartmentsCollection();
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
	
	public function activateDepartments(){
		$result = array();
		$page = 'activateDepartments';
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
				$ret =  new DepartmentsCollection();
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
	public function deactivateDepartments(){
		$result = array();
		$page = 'deactivateDepartments';
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
				$ret =  new DepartmentsCollection();
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
	public function getActiveDepartments(){
		$result = array();
		$page = 'getActiveDepartments';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new DepartmentsCollection();
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