<?php

class AllRecord extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('AllRecordCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		//Helper::rolehook(ModuleRels::VIEW_APPROVED_ARCHIVES);
		$listData = array();
		$viewData = array();
		$page = "viewAllRecord";
		$listData['key'] = $page;
		$ret = new AllRecordCollection();
		if($ret->hasRows()){
			$tmpData = $ret->getData();
			foreach($tmpData["details"] as $k => $value){
				$tmpData["details"][$k]["participants"] = $this->getParticipantsByTraining($value["tm_id"]);
			}
			$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$tmpData);
			$respo = json_decode($res);
		}
		else{
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			$respo = json_decode($res);
		}
		$listData['list'] = $respo;
		$viewData['table'] = $this->load->view("helpers/allrecordlist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('All Records');
			Helper::setMenu('templates/menu_template');
			Helper::setView('allrecord',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}
	public function getParticipantsByTraining($id){
		$result = array();
		$ret =  new AllRecordCollection();
		$ret->fetchParticipants($id);
		$participants = "";
		foreach($ret->getData()["details"] as $k => $v){
			if($k>0) $participants .= "<br>";
			$participants .= $v["tmp_participant_name"];
		}
		return $participants;
	}
	

	function fetchRows(){
		$fetch_data = $this->AllRecordCollection->make_datatables();
        $data = array();  
        foreach($fetch_data as $k => $row) {  
        	$buttons = "";
			$buttons_data = "";
            $sub_array = array();    
            $sub_array[] = $row->tm_start_date;
            $sub_array[] = $row->tm_end_date;
            $sub_array[] = $row->tm_seminar_training;
            $sub_array[] = @$row->sponsored_by;
            $sub_array[] = $row->tm_place;
            $sub_array[] = $row->tm_country;
            $sub_array[] = $this->getParticipantsByTraining($row->tm_id);
           	foreach($row as $k1=>$v1) $buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
            $buttons .= ' <a  id="updateAllRecordForm"'
                      . '       class="updateAllRecordForm btn btn-info btn-circle waves-effect waves-circle waves-float"'
					  . '       href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateAllRecordForm' .'"  data-toggle="tooltip" data-placement="top" title="Update" '
					  .  $buttons_data
					  . '	>'
                      . '       <i class="material-icons">mode_edit</i>'
					  . '   </a>'
					  . '<a  id="previewAllRecord" '
                      . '      class="previewAllRecord btn btn-info btn-circle waves-effect waves-circle waves-float"'
					  . '      href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/previewAllRecord' .'"  data-toggle="tooltip" data-placement="top" title="Preview" '
					  .  $buttons_data
					  . '	>'
                      . '      <i class="material-icons">remove_red_eye</i>'
                      . '  </a>';
	        $sub_array[] = $buttons;
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>     $this->AllRecordCollection->get_all_data(),  
            "recordsFiltered"     	=>     $this->AllRecordCollection->get_filtered_data(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
	}


	public function addAllRecordForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addAllRecord';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/allrecordform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function printRecordForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'printRecord';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/printrecordform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updateAllRecordForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateAllRecord';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/allrecordform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function previewAllRecord(){
		$formData = array();
		$result = array();
		$result['key'] = 'previewAllRecord';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$helperData['key'] = $result['key'];
			$result['table'] = $this->load->view('helpers/allrecordpreview.php', $helperData, TRUE);
		}
		echo json_encode($result);
	}
	public function addAllRecord(){
		//var_dump($_POST);die();
		$result = array();
		$page = 'addAllRecord';
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
				$ret =  new AllRecordCollection();
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
	public function updateAllRecord(){

		//var_dump($_POST);die();
		$result = array();
		$page = 'updateAllRecord';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new AllRecordCollection();
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
	public function getParticipants(){
		$result = array();
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
				$ret =  new AllRecordCollection();
				if($ret->fetchParticipants($post_data['id'])) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
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
		}
		echo json_encode($result);
	}
	public function getAllRecords(){
		//var_dump($_POST);die();
		$result = array();
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new AllRecordCollection();
			if($ret->hasRows()) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}
			$result = json_decode($res,true);
		}
		//var_dump($result);
		echo json_encode($result);
	}
	public function getAllRecordsByCountry(){
		//var_dump($_POST);die();
		$result = array();
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new AllRecordCollection();
			if($ret->hasRowsByCountry($_POST['country'])) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}
			$result = json_decode($res,true);
		}
		//var_dump($result);
		echo json_encode($result);
	}

	public function getPlaces(){
		$result = array();
		$page = 'getActiveDepartments';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new AllRecordCollection();
			if($ret->hasRowsPlace()) {
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