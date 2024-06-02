<?php

class CompetencyTestExamResult extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('CompetencyTestExamResultCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::LOANS_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewCompetencyTestExamResult";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/competencytestexamresultlist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Competency Test Exam Result');
			Helper::setMenu('templates/menu_template');
			Helper::setView('competencytestexamresult',$viewData,FALSE);
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
        $fetch_data = $this->CompetencyTestExamResultCollection->make_datatables($status);  
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
			<a id="viewBatch" class="viewBatch btn btn-warning btn-circle waves-effect waves-circle waves-float" href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewBatch"
			data-toggle="tooltip" data-placement="top" title="View Batch" data-id="'.$row->id.'" '.$dt.' ><i class="material-icons">remove_red_eye</i></a>

			<a id="viewQuestioner" class="viewQuestioner btn btn-warning btn-circle waves-effect waves-circle waves-float" href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewQuestioner"
			data-toggle="tooltip" data-placement="top" title="View Questioner" data-id="'.$row->id.'" '.$dt.' ><i class="material-icons">remove_red_eye</i></a>
			
			';

			

			// if($row->status == "1"){
			// 	$btns .= '<a class="deactivateLoans btn btn-danger btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top"
			// title="Deactivate" data-id="'.$row->id.'" href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/deactivateLoans">
			// 	<i class="material-icons">do_not_disturb</i></a>';
			// }else{
			// 	$btns .= '<a class="activateLoans btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top"
			// title="Activate" data-id="'.$row->id.'" href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/activateLoans">
			// 	<i class="material-icons">done</i></a>';
			// }
			
            $sub_array[] = $row->reference;
            $sub_array[] = $row->date;  
            $sub_array[] = $row->total_examinees;
            $sub_array[] = ($row->status == "1")?'<label class="text-success">ACTIVE</label>':'<label class="text-danger">INACTIVE</label>';
			$sub_array[] = $btns;
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_GET["draw"]),  
            "recordsTotal"          =>      $this->CompetencyTestExamResultCollection->get_all_data($status),  
            "recordsFiltered"     	=>     $this->CompetencyTestExamResultCollection->get_filtered_data($status),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
	}
	public function addLoansForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addLoans';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/loansform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function viewBatch(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewBatch';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$ret = new CompetencyTestExamResultCollection();
			if($ret->viewBatch($this->input->post('id'))){
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
				$respo = json_decode($res);
			}
			else{
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				$respo = json_decode($res);
			}
			$formData['list'] = $respo;
			$result['form'] = $this->load->view('forms/competencyViewBatch.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function viewQuestioner(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewQuestioner';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$ret = new CompetencyTestExamResultCollection();
			if($ret->hasQuestioner($this->input->post('id'))){
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
				$respo = json_decode($res);
			}
			else{
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				$respo = json_decode($res);
			}
			$formData['list'] = $respo;
			$result['form'] = $this->load->view('forms/competencyViewQuestioner.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updateScoreForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateScore';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$ret = new CompetencyTestExamResultCollection();
			if($ret->updateScoreForm($this->input->post('id'))){
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
				$respo = json_decode($res);
			}
			else{
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				$respo = json_decode($res);
			}
			$formData['list'] = $respo;
			$result['form'] = $this->load->view('forms/competencyUpdateScore.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updateScore(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateScore';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$ret = new CompetencyTestExamResultCollection();

			foreach ($this->input->post() as $k => $v) {
				$post_data[$k] = $this->input->post($k,true);
			}
			if($ret->updateScore($this->input->post($post_data))){
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				$respo = json_decode($res);
			}
			else{
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				$respo = json_decode($res);
			}
			$formData['list'] = $respo;
			$result['form'] = $this->load->view('forms/competencyViewBatch.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updateLoanForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateLoanForm';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/competencyUpdateScore.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function addSubLoans(){
		$result = array();
		$page = 'addSubLoans';
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
				$ret =  new CompetencyTestExamResultCollection();
				if($ret->addSubRows($post_data)) {
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
	public function addLoans(){
		$result = array();
		$page = 'addLoans';
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
				$ret =  new CompetencyTestExamResultCollection();
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
	public function updateLoans(){
		$result = array();
		$page = 'updateLoans';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new CompetencyTestExamResultCollection();
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
	
	public function activateLoans(){
		$result = array();
		$page = 'activateLoans';
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
				$ret =  new CompetencyTestExamResultCollection();
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
	public function deactivateLoans(){
		$result = array();
		$page = 'deactivateLoans';
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
				$ret =  new CompetencyTestExamResultCollection();
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
	public function activateSubLoans(){
		$result = array();
		$page = 'activateSubLoans';
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
				$ret =  new CompetencyTestExamResultCollection();
				if($ret->activeSubRows($post_data)) {
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
	public function deactivateSubLoans(){
		$result = array();
		$page = 'deactivateSubLoans';
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
				$ret =  new CompetencyTestExamResultCollection();
				if($ret->inactiveSubRows($post_data)) {
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
	public function getActiveLoans(){
		$result = array();
		$page = 'getActiveLoans';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new CompetencyTestExamResultCollection();
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
	public function getActiveSubLoans(){
		$result = array();
		$page = 'getActiveLoans';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new CompetencyTestExamResultCollection();
			if($ret->hasRowsSubActive()) {
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