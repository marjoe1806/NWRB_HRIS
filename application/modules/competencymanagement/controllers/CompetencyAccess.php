<?php

class CompetencyAccess extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('CompetencyAccessCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::DIVISIONS_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewCompetencyAccess";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/competencyaccesslist",$listData,TRUE); 

		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Competency Access');
			Helper::setMenu('templates/menu_template');
			Helper::setView('competencyaccess',$viewData,FALSE);
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

        $fetch_data = $this->CompetencyAccessCollection->make_datatables($status);  
        $data = array();  
        foreach($fetch_data as $k => $row)  
        {  
        	$buttons = "";
        	$buttons_data = "";
            $sub_array = array();    
			$dt = "";
            
            $status_name = "";
            $status_label = "";
			if($row->status == 1){
				$status_name = "Go";
				$status_label = '<label class="text-success">'.$status_name.'</label>';
			}else if($row->status == 2){
				$status_name = "Suspended";
				$status_label = '<label class="text-warning">'.$status_name.'</label>';
			}else if($row->status == 3){
				$status_name = "Not Go";
				$status_label = '<label class="text-danger">'.$status_name.'</label>';
			}
			
			if($row->type == "general"){
				$type_name = "General Ability";
			}else if($row->type == "promotion"){
				$type_name = "2nd Level Promotion";
			}else if($row->type == "ethics"){
				$type_name = "Ethics";
			}

			$hours = floor($row->exam_duration / 60).':'.($row->exam_duration -   floor($row->exam_duration / 60) * 60);

			foreach ($row as $k => $v) $dt .= ' data-'.$k.'="'.$v.'" ';
			$btns = "";
			$btns = '<a class="viewCompetencyAccessDetails btn btn-primary btn-circle waves-effect waves-circle waves-float"
				href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewCompetencyAccessDetails"  data-toggle="tooltip" data-placement="top" title="View" 
				data-id="'.$row->id.'" '.$dt.'><i class="material-icons">remove_red_eye</i></a>';
			$btns .= '<a class="updateCompetencyAccessForm btn btn-success btn-circle waves-effect waves-circle waves-float"
				href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateCompetencyAccessForm"  data-toggle="tooltip" data-placement="top" title="Edit" 
				data-id="'.$row->id.'" '.$dt.'><i class="material-icons">edit</i></a>';
			$sub_array[] = $btns;
            $sub_array[] = $row->reference;
            $sub_array[] = $type_name;
            $sub_array[] = date('F d, Y', strtotime($row->date));
            $sub_array[] = date('H:i', strtotime($row->time_start)).' - '.date('H:i', strtotime($row->time_end));
            // $sub_array[] = $hours;
            $sub_array[] = $row->exam_duration.' Minutes';
            $sub_array[] = $status_label;
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_GET["draw"]),  
            "recordsTotal"          =>      $this->CompetencyAccessCollection->get_all_data($status),  
            "recordsFiltered"     	=>     $this->CompetencyAccessCollection->get_filtered_data($status),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
	}
	public function addCompetencyAccessForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addCompetencyAccess';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$dafault_list = array(
				(object) array(
					"id" => 0,
					"status" => '',
					"emailaddress" => '',
				),
			);

			$ret =  new CompetencyAccessCollection();
			$formData['type_info'] = $ret->getType('All',true);
			$formData['employee_list'] = $ret->getEmployee('All',true);
			$formData['email_list'] = $dafault_list;
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/competencyaccessform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updateCompetencyAccessForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateCompetencyAccess';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			if($this->input->post() && $this->input->post() != null)
			{
				$ret =  new CompetencyAccessCollection();
				$formData['type_info'] = $ret->getType('All',true);
				$formData['employee_list'] = $ret->getEmployee('All',true);
				$formData['email_list'] = $ret->getEmailList($this->input->post('id',true),true);
				$formData['key'] = $result['key'];
				$result['form'] = $this->load->view('forms/competencyaccessform.php', $formData, TRUE);
			}
			else
			{
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
		}
		echo json_encode($result);
	}
	public function viewCompetencyAccessDetails(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewCompetencyAccessDetails';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			if($this->input->post() && $this->input->post() != null)
			{
				$ret =  new CompetencyAccessCollection();
				$formData['type_info'] = $ret->getType('All',true);
				$formData['employee_list'] = $ret->getEmployee('All',true);
				$formData['email_list'] = $ret->getEmailList($this->input->post('id',true),true);
				$formData['key'] = $result['key'];
				$result['form'] = $this->load->view('forms/competencyaccessform.php', $formData, TRUE);
			}
			else
			{
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
		}
		echo json_encode($result);
	}
	public function addCompetencyAccess(){
		$result = array();
		$page = 'addCompetencyAccess';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			if($this->input->post() && $this->input->post() != null)
			{
				$post_data = array();
				$ret =  new CompetencyAccessCollection();

				$year_month = date("Y-m");
				$count = $ret->getLastRow($year_month);
				$type_info = $ret->getType($this->input->post('type_id',true));

				$start = $this->input->post('time_start',true);
				$end = $this->input->post('time_end',true);

				$old_start = $this->input->post('old_start_date',true);
				$old_end = $this->input->post('old_end_date',true);

				if(strtotime($end) < strtotime($start)){
					$code = "1";
					$message = "Time start must earlier than time end.";
					$res = new ModelResponse($code, $message);
					$result = json_decode($res,true);
				}else{
					if($type_info) {
						$type = $type_info[0]->type;
						$ref = 'NA';
						if($type == 'general'){
							$ref = "GA";
						}else if($type == 'promotion'){
							$ref = "2L";
						}else if($type == 'ethics'){
							$ref = "ET";
						}
	
						$reference = $ref.'-'.$year_month.'-'.str_pad(($count + 1), 2, 0, STR_PAD_LEFT);
						
						$post_data['reference'] = $reference;
						$post_data['type_id'] = $this->input->post('type_id',true);
						$post_data['date'] = $this->input->post('date',true);
						$post_data['time_start'] = $this->input->post('time_start',true);
						$post_data['time_end'] = $this->input->post('time_end',true);
						$post_data['exam_duration'] = $this->input->post('exam_duration',true);
						$post_data['status'] = $this->input->post('status',true);
						$emails = $this->input->post('emailaddress',true);
	
						if($ret->addRows($post_data)) {
							$access_id = $ret->getData()['access_id'];
							$email_post = array();
	
							foreach ($emails as $el) {
								$e = array(
									'emailaddress' => $el, 
									'access_id' => $access_id, 
									'created_by' => Helper::get('userid')
								);
	
								if (!in_array($e, $email_post) && $el !== '') {
									array_push($email_post, $e);
								}
							}
	
							if($ret->addEmails($email_post, 'add', 1)) {
								$res = new ModelResponse($ret->getCode(), $ret->getMessage());
							} else {
								$res = new ModelResponse($ret->getCode(), $ret->getMessage());
							}
						} else {
							$res = new ModelResponse($ret->getCode(), $ret->getMessage());
						}
						$result = json_decode($res,true);
					}else{
						$res = new ModelResponse($ret->getCode(), $ret->getMessage());
						$result = json_decode($res,true);
					}
				}
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
	public function updateCompetencyAccess(){
		$result = array();
		$page = 'updateCompetencyAccess';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				$ret =  new CompetencyAccessCollection();

				$type_info = $ret->getType($this->input->post('type_id',true));
				
				$date = $this->input->post('date',true);
				$start = $this->input->post('time_start',true);
				$end = $this->input->post('time_end',true);

				$old_date = $this->input->post('old_date',true);
				$old_start = $this->input->post('old_start_date',true);
				$old_end = $this->input->post('old_end_date',true);

				if(strtotime($end) < strtotime($start)){
					$code = "1";
					$message = "Time start must earlier than time end.";
					$res = new ModelResponse($code, $message);
					$result = json_decode($res,true);
				}else{
					$resend = 0;
					if($date !== $old_date || strtotime($start) !== strtotime($old_start) || strtotime($end) !== strtotime($old_end)){
						$resend = 1;
					}

					if($type_info) {
						$type = $type_info[0]->type;
						$ref = 'NA';
						if($type == 'general'){
							$ref = "GA";
						}else if($type == 'promotion'){
							$ref = "2L";
						}else if($type == 'ethics'){
							$ref = "ET";
						}

						$old_start_date = $this->input->post('old_start_date',true);
						$post_data['id'] = $this->input->post('id',true);
						$post_data['type_id'] = $this->input->post('type_id',true);
						$post_data['date'] = $this->input->post('date',true);
						$post_data['time_start'] = $this->input->post('time_start',true);
						$post_data['time_end'] = $this->input->post('time_end',true);
						$post_data['exam_duration'] = $this->input->post('exam_duration',true);
						$post_data['status'] = $this->input->post('status',true);
						$emails = $this->input->post('emailaddress',true);

						$ref_slice = explode("-", $this->input->post('reference',true));
						$reference = $ref.'-'.$ref_slice[1].'-'.$ref_slice[2].'-'.$ref_slice[3];
						$post_data['reference'] = $reference;

						if($ret->updateRows($post_data)) {
							$email_post = array();

							foreach ($emails as $el) {
								$e = array(
									'emailaddress' => $el, 
									'access_id' => $post_data['id'], 
									'modified_by' => Helper::get('userid'), 
								);

								if (!in_array($e, $email_post) && $el !== '') {
									$validate_exam = $ret->validateEmailExam($el, $post_data['id']);
									if(!$validate_exam){
										array_push($email_post, $e);
									}
								}
							}
							if($email_post){
								if($ret->addEmails($email_post, 'update', $resend)) {
									$res = new ModelResponse($ret->getCode(), $ret->getMessage());
								} else {
									$res = new ModelResponse($ret->getCode(), $ret->getMessage());
								}
							}
							$res = new ModelResponse($ret->getCode(), $ret->getMessage());
						} else {
							$res = new ModelResponse($ret->getCode(), $ret->getMessage());
						}
						$result = json_decode($res,true);
					}else{
						$res = new ModelResponse($ret->getCode(), $ret->getMessage());
						$result = json_decode($res,true);
					}
				}
			}else{
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}
	public function activateCompetencyAccess(){
		$result = array();
		$page = 'activateCompetencyAccess';
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
				$ret =  new CompetencyAccessCollection();
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
	public function deactivateCompetencyAccess(){
		$result = array();
		$page = 'deactivateCompetencyAccess';
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
				$ret =  new CompetencyAccessCollection();
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
	public function getActiveCompetencyAccess(){
		$result = array();
		$page = 'getActiveCompetencyAccess';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new CompetencyAccessCollection();
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