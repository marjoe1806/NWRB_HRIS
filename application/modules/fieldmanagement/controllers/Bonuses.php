<?php

class Bonuses extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('BonusesCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::BONUSES_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewBonuses";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/bonuseslist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Bonuses and Incentives');
			Helper::setMenu('templates/menu_template');
			Helper::setView('bonuses',$viewData,FALSE);
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
		$tax = "";
		if(isset($_GET["status"])) $status = $_GET["status"];
		if(isset($_GET["withTax"])) $tax = $_GET["withTax"];
        $fetch_data = $this->BonusesCollection->make_datatables($status,$tax);  
        $data = array();  
        foreach($fetch_data as $k => $row)  
        {  
        	$buttons = "";
        	$buttons_data = "";
            $sub_array = array();    
			$dt = "";
			foreach ($row as $k => $v) $dt .= ' data-'.$k.'="'.$v.'" ';
			$btns = "";
			$btns = '<a  id="updateBonusesForm" 
				class="updateBonusesForm btn btn-info btn-circle waves-effect waves-circle waves-float"
				href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateBonusesForm"  data-toggle="tooltip" data-placement="top" title="Update" 
				data-id="'.$row->id.'" '.$dt.'><i class="material-icons">mode_edit</i>
			</a>';
			if($row->is_active == "1"){
				$btns .= '<a class="deactivateBonuses btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Deactivate" 
						data-id="'.$row->id.'"href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/deactivateBonuses"><i class="material-icons">do_not_disturb</i></a>';
			}else{
				$btns .= '<a class="activateBonuses btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Activate" 
					data-id="'.$row->id.'"href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/activateBonuses"><i class="material-icons">done</i></a>';
			}
			$sub_array[] = $btns;
            $sub_array[] = $row->code;
            $sub_array[] = $row->group;  
            $sub_array[] = $row->max;
            $sub_array[] = ($row->with_tax == "1")?'Yes':'No';
            $sub_array[] = ($row->is_active == "1")?'<label class="text-success">ACTIVE</label>':'<label class="text-danger">INACTIVE</label>';
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_GET["draw"]),  
            "recordsTotal"          =>      $this->BonusesCollection->get_all_data($status,$tax),  
            "recordsFiltered"     	=>     $this->BonusesCollection->get_filtered_data($status,$tax),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
	}
	public function addBonusesForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addBonuses';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/bonusesform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updateBonusesForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateBonuses';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/bonusesform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function addBonuses(){
		$result = array();
		$page = 'addBonuses';
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
				$ret =  new BonusesCollection();
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
	public function updateBonuses(){
		$result = array();
		$page = 'updateBonuses';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new BonusesCollection();
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
	
	public function activateBonuses(){
		$result = array();
		$page = 'activateBonuses';
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
				$ret =  new BonusesCollection();
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
	public function deactivateBonuses(){
		$result = array();
		$page = 'deactivateBonuses';
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
				$ret =  new BonusesCollection();
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
	public function getActiveBonuses(){
		$result = array();
		$page = 'getActiveBonuses';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new BonusesCollection();
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
	public function getBonusesById(){
		$result = array();
		$page = 'getBonusesById';
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
				$ret =  new BonusesCollection();
				if($ret->hasRowsById($post_data)) {
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
}

?>