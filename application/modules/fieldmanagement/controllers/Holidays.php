<?php

class Holidays extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('HolidaysCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::HOLIDAYS_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewHolidays";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/holidayslist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Holidays');
			Helper::setMenu('templates/menu_template');
			Helper::setView('holidays',$viewData,FALSE);
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
		$month = "";
		if(isset($_GET["status"])) $status = $_GET["status"];
		if(isset($_GET["month"])) $month = $_GET["month"];
        $fetch_data = $this->HolidaysCollection->make_datatables($status,$month);  
        $data = array();  
        foreach($fetch_data as $k => $row)  
        {  
        	$buttons = "";
        	$buttons_data = "";
            $sub_array = array();    
			$dt = "";
			foreach ($row as $k => $v) $dt .= ' data-'.$k.'="'.$v.'" ';
			$btns = "";
			$btns = '
			<a  id="updateHolidaysForm" 
				class="updateHolidaysForm btn btn-info btn-circle waves-effect waves-circle waves-float"
				href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateHolidaysForm"  data-toggle="tooltip" data-placement="top" title="Update" 
				data-id="'.$row->id.'" '.$dt.'> <i class="material-icons">mode_edit</i> </a>';
			if($row->date > date('Y-m-d')){
				if($row->is_active == "1"){
					$btns .= '<a class="deactivateHolidays btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Deactivate" 
							data-id="'.$row->id.'"
							href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/deactivateHolidays">
							<i class="material-icons">do_not_disturb</i>
						</a>';
				}else{
					$btns .= '<a class="activateHolidays btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Activate" 
						data-id="'.$row->id.'"
						href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/activateHolidays">
						<i class="material-icons">done</i>
					</a>';
				}
			}
			if($row->holiday_type == 'Regular'){
				$row->holiday_type = $row->holiday_type.' Holiday';
			} else if($row->holiday_type == 'Special'){				
				$row->holiday_type = $row->holiday_type.' Non-Working Holiday';
			}
			$sub_array[] = $btns;
            $sub_array[] = $row->date;
            $sub_array[] = $row->holiday_name;  
            $sub_array[] = $row->holiday_type;
            $sub_array[] = ($row->is_active == "1")?'<label class="text-success">ACTIVE</label>':'<label class="text-danger">INACTIVE</label>';
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_GET["draw"]),  
            "recordsTotal"          =>      $this->HolidaysCollection->get_all_data($status,$month),  
            "recordsFiltered"     	=>     $this->HolidaysCollection->get_filtered_data($status,$month),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
	}
	public function addHolidaysForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addHolidays';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/holidaysform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updateHolidaysForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateHolidays';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/holidaysform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function addHolidays(){
		$result = array();
		$page = 'addHolidays';
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
				$ret =  new HolidaysCollection();
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
	public function updateHolidays(){
		$result = array();
		$page = 'updateHolidays';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new HolidaysCollection();
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
	
	public function activateHolidays(){
		$result = array();
		$page = 'activateHolidays';
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
				$ret =  new HolidaysCollection();
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
	public function deactivateHolidays(){
		$result = array();
		$page = 'deactivateHolidays';
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
				$ret =  new HolidaysCollection();
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
}

?>