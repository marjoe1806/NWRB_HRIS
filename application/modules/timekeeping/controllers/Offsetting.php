<?php

class Offsetting extends MX_Controller {
	public $allowedfiles = array('application/pdf','image/jpg','image/jpeg', 'image/png');

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('OffsettingCollection');
		$this->load->module('session');
		$this->load->library('upload');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}

	public function index() {
		$listData = array();
		$viewData = array();
		$page = "viewOffsetting";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/offsettinglist",$listData,TRUE);

		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Offsetting');
			Helper::setMenu('templates/menu_template');
			Helper::setView('offsetting',$viewData,FALSE);
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
		$fetch_data = $this->OffsettingCollection->make_datatables();  
		// var_dump(count($fetch_data)); die();
		$data = array();  
        foreach($fetch_data as $k => $row){  
        	$buttons = "";
        	$buttons_data = "";
			$emp_id = $row->salt;
        	$emp_id_num = $this->Helper->decrypt((string)$row->employee_id_number,$emp_id);
        	$row->first_name = $this->Helper->decrypt((string)$row->first_name,$emp_id);
        	$row->middle_name = $this->Helper->decrypt((string)$row->middle_name,$emp_id);
        	$row->last_name = $this->Helper->decrypt((string)$row->last_name,$emp_id);
        	$row->extension = ($row->extension == null || $row->extension == "")?"":$this->Helper->decrypt((string)$row->extension,$emp_id);
			
			$sub_array = array();  
			$sub_array[] = date('M d, Y', strtotime($row->date_requested));
			$sub_array[] = $row->number_of_hrs;
			$sub_array[] = $row->first_name. ' '. $row->middle_name .' '.$row->last_name .' '.$row->extension;
			$sub_array[] = $row->purpose != null ? $row->purpose : 'Not Specified';
			$sub_array[] =  $row->checked_by;
			$sub_array[] = '<label class="text-'. (($row->status === 5 || $row->status === 6)?"danger":"success") .'">'. $row->request_status .'</label></td>';

            foreach($row as $k1=>$v1) $buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
			if(Helper::role(ModuleRels::OFFSETTING_UPDATE_DETAILS)):
				$buttons .= '<a id="updateOffsettingForm" class="updateOffsettingForm btn btn-info btn-circle waves-effect waves-circle waves-float"'
						. 'href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateOffsettingForm'.'"'
						. 'data-toggle="tooltip" data-placement="top" title="Update Request"'
						. $buttons_data. '">'
						. '<i class="material-icons">mode_edit</i></a>';
			endif;
			if(Helper::role(ModuleRels::OFFSETTING_CERTIFY) && $row->status == 1): 
				$buttons .= '<a class="certifyPendingOffset btn btn-info btn-circle waves-effect waves-circle waves-float"'
						. 'href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/certifyPendingOffset'.'"'
						. 'title="Certify Request" data-empid="'. $row->employee_id.'" data-id="'. $row->id.'" data-nohrs="'.$row->number_of_hrs.'"'
						. $buttons_data. '">'
						. '<i class="material-icons">done</i></a>';
			endif;
			if(Helper::role(ModuleRels::OFFSETTING_RECOMMEND) && $row->status == 2): 
				$buttons .= '<a class="recommendPendingOffset btn btn-info btn-circle waves-effect waves-circle waves-float"'
						. 'href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/recommendPendingOffset'.'"'
						. 'title="Check Request" data-empid="'. $row->employee_id.'" data-id="'. $row->id.'" data-nohrs="'.$row->number_of_hrs.'"'
						. $buttons_data. '">'
						. '<i class="material-icons">done</i></a>';
			endif;
			if(Helper::role(ModuleRels::OFFSETTING_APPROVE) && $row->status == 3): 
				$buttons .= '<a class="approvedPendingOffset btn btn-info btn-circle waves-effect waves-circle waves-float"'
						. 'href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/approvedPendingOffset'.'"'
						. 'title="Approved Request" data-empid="'. $row->employee_id.'" data-id="'. $row->id.'" data-nohrs="'.$row->number_of_hrs.'"'
						. $buttons_data. '">'
						. '<i class="material-icons">done</i></a>';
			endif;
			if(Helper::role(ModuleRels::OFFSETTING_CERTIFY) || Helper::role(ModuleRels::OFFSETTING_CHECK) || Helper::role(ModuleRels::OFFSETTING_APPROVE) && ($row->status == 1 || $row->status == 2 || $row->status == 3)): 
				$buttons .= '<a class="rejectPendingOffset btn btn-danger btn-circle waves-effect waves-circle waves-float"'
						. 'href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/rejectPendingOffset'.'"'
						. 'title="Reject Request" data-empid="'. $row->employee_id.'" data-id="'. $row->id.'" data-nohrs="'.$row->number_of_hrs.'"'
						. $buttons_data. '">'
						. '<i class="material-icons">clear</i></a>';
			endif;
			$sub_array[] = $buttons;
            $data[] = $sub_array;
        }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>      $this->OffsettingCollection->get_all_data(),  
            "recordsFiltered"     	=>     $this->OffsettingCollection->get_filtered_data(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
	}

	public function addOffsettingForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addOffsetting';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/offsetform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updateOffsettingForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateOffsetting';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/offsetform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function viewOffsettingForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewOffsetting';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/offsetform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function getAvailableHours(){
		if (!$this->input->is_ajax_request())
		   show_404();
		
		$ret = new OffsettingCollection();
		if($ret->getAvailableHoursDetails($_POST["id"])) {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
		} else {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		}
		$result = json_decode($res,true);
		echo json_encode($result);
	}

	public function addOffsetting(){
		$result = array();
		$page = 'addOffsetting';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null){
				$post_data = array();
				$files = (isset($_FILES))?$_FILES:array();
				foreach ($this->input->post() as $k => $v) $post_data[$k] = $this->input->post($k,true);
				foreach ($files as $k => $v) $post_data[$k] = $files[$k];
				$ret =  new OffsettingCollection();
				if($ret->addRows($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
					if($files["file"]["size"]>0){
						$foldername = $ret->getData();
						$structure = './assets/uploads/offsetting/'.$foldername;
						if(!file_exists($structure)) mkdir($structure, 0777, true);
						chmod($structure, 0775);
						$destination = getcwd(). "/assets/uploads/offsetting/" . $foldername;
						if(!move_uploaded_file($files["file"]["tmp_name"], $destination.'/'.$files["file"]["name"])){
							$res = new ModelResponse($ret->getCode(), "Successfully submit offset but something<br>went wrong uploading files.");
						}
					}
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

	public function updateOffsetting(){
		$result = array();
		$page = 'updateOffsetting';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				$files = (isset($_FILES))?$_FILES:array();
				foreach ($this->input->post() as $k => $v) $post_data[$k] = $this->input->post($k,true);
				foreach ($files as $k => $v) $post_data[$k] = $files[$k];
				$ret =  new OffsettingCollection();
				if($ret->updateRows($post_data)) {
					if($files["file"]["size"]>0){
						$foldername = $ret->getData();
						$structure = './assets/uploads/offsetting/'.$foldername;
						if(!file_exists($structure)) mkdir($structure, 0777, true);
						chmod($structure, 0775);
						$destination = getcwd(). "/assets/uploads/offsetting/" . $foldername;
						if($post_data["filename"] !== "" && $post_data["file"]["name"] === $post_data["filename"]){
							if(unlink($structure."/".$post_data["current_file_title"])){
								if(!move_uploaded_file($files["file"]["tmp_name"], $destination.'/'.$files["file"]["name"])){
									$res = new ModelResponse($ret->getCode(), "Successfully update offset but something<br>went wrong uploading files.");
								}else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
							}else{
								$res = new ModelResponse($ret->getCode(), "Successfully update offset but something<br>went wrong uploading files.");
							}
						}else if($post_data["file"]["size"]>0){
							if(!move_uploaded_file($files["file"]["tmp_name"], $destination.'/'.$files["file"]["name"])){
								$res = new ModelResponse($ret->getCode(), "Successfully update offset but something<br>went wrong uploading files.");
							}else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
						}
					}else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
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
	
	public function certifyPendingOffset(){
		$result = array();
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) $post_data[$k] = $this->input->post($k,true);
				$ret =  new OffsettingCollection();
				if($ret->certifyRow($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			} else {
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
		}
		echo json_encode($result);
	}
	
	public function recommendPendingOffset(){
		$result = array();
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) $post_data[$k] = $this->input->post($k,true);
				$ret =  new OffsettingCollection();
				if($ret->recommendRow($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			} else {
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
		}
		echo json_encode($result);
	}
	
	public function approvedPendingOffset(){
		$result = array();
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) $post_data[$k] = $this->input->post($k,true);
				$ret =  new OffsettingCollection();
				if($ret->approveRow($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			} else {
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
		}
		echo json_encode($result);
	}
	
	public function rejectPendingOffset(){
		$result = array();
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) $post_data[$k] = $this->input->post($k,true);
				$ret =  new OffsettingCollection();
				if($ret->rejecteRow($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			} else {
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
		}
		echo json_encode($result);
	}

	public function activateOffsetting(){
		$result = array();
		$page = 'activateOffsetting';
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
				$ret =  new OffsettingCollection();
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
	public function deactivateOffsetting(){
		$result = array();
		$page = 'deactivateOffsetting';
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
				$ret =  new OffsettingCollection();
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
	public function getActiveOffsetting(){
		$result = array();
		$page = 'getActiveOffsetting';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new OffsettingCollection();
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

	public function getActualLogs() {
		$model =  new OffsettingCollection();
		$number = $model->getScanningNumber($_POST['employee_id']);
		$result['number'] = @$number;
		$result['dtr'] = @$model->getDailyTimeRecord($number, $_POST['transaction_date']);
		echo json_encode($result);
	}

	function array_push_assoc($array, $key, $row){
		$array[$key] = $row;
		return $array;
	}
	
	public function getActiveHolidays(){
		$result = array();
		$page = 'getActiveOffsetting';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			$ret =  new OffsettingCollection();
			if($ret->hasRowsActiveHoliday()) {
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