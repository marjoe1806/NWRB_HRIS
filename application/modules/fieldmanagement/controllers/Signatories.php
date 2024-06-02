<?php

class Signatories extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('SignatoriesCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		$this->load->library('upload');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::SIGNATORIES_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewSignatories";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/signatorieslist",$listData,TRUE);  
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Signatories');
			Helper::setMenu('templates/menu_template');
			Helper::setView('signatories',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			$result['tablehead'] = $viewData['tablehead'];
			echo json_encode($result);
		}
		Session::checksession();
	}
	function fetchRows(){ 

		$status = "";
		if(isset($_GET["status"])) $status = $_GET["status"];
        $fetch_data = $this->SignatoriesCollection->make_datatables($status);  
        $data = array();  
        foreach($fetch_data as $k => $row)  
        {  
        	$buttons = "";
        	$buttons_data = "";
            $sub_array = array();    
			$dt = "";
			foreach ($row as $row_k => $v) $dt .= ' data-'.$row_k.'="'.$v.'" ';
			$btns = "";
			$btns = '<a  id="updateSignatoriesForm" 
					class="updateSignatoriesForm btn btn-info btn-circle waves-effect waves-circle waves-float"
					href="'.base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateSignatoriesForm"  data-toggle="tooltip" data-placement="top" title="Update"'.$dt.'>
					<i class="material-icons">mode_edit</i></a>';
			$sub_array[] = $btns;
            $sub_array[] = $row->signatory;
            $sub_array[] = $row->employee_name;  
            $sub_array[] = ($row->is_active == "1")?'<label class="text-success">ACTIVE</label>':'<label class="text-danger">INACTIVE</label>';
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_GET["draw"]),  
            "recordsTotal"          =>      $this->SignatoriesCollection->get_all_data($status),  
            "recordsFiltered"     	=>     $this->SignatoriesCollection->get_filtered_data($status),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
	}
	public function addSignatoriesForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addSignatories';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$formData['module'] = 'Signatories';
			$result['form'] = $this->load->view('forms/signatoriesform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function addHeadSignatoriesForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addHeadSignatories';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$formData['module'] = 'Head Signatories';
			$result['form'] = $this->load->view('forms/signatoriesform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function updateSignatoriesForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateSignatories';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$formData['module'] = 'Signatories';
			$result['form'] = $this->load->view('forms/signatoriesform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updateHeadSignatoriesForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateHeadSignatories';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$formData['module'] = 'Head Signatories';
			$result['form'] = $this->load->view('forms/signatoriesform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function addSignatories(){
		$result = array();
		$page = 'addSignatories';
		if (!$this->input->is_ajax_request())  show_404();
		else{
			if($this->input->post() && $this->input->post() != null){
				// $_FILES['uploadFile']['name'] 		= $_FILES['file']['name'];
				// $_FILES['uploadFile']['type'] 		= $_FILES['file']['type'];
				// $_FILES['uploadFile']['size'] 		= $_FILES['file']['size'];
				// $_FILES['uploadFile']['error'] 		= $_FILES['file']['error'];
				// $_FILES['uploadFile']['tmp_name'] = $_FILES['file']['tmp_name'];
				// if (!file_exists('./assets/uploads/signatories'))
				// 	mkdir('./assets/uploads/signatories', 0777, true);
				// $config['upload_path'] = './assets/uploads/signatories/';
				// $config['allowed_types'] = '*';
				// $config['overwrite'] = TRUE;
				// $config['remove_spaces'] = FALSE;
				// $this->upload->initialize($config);
				// if ($this->upload->do_upload('uploadFile')):
				// 	$data = array('upload_data' => $this->upload->data());
				// else:
				// 	$error = array('error' => $this->upload->display_errors());
				// endif;

				// $post_data = array();
				// foreach ($this->input->post() as $k => $v) {
				// 	$post_data[$k] = $this->input->post($k,true);
				// }

				// $post_data['file_size'] = $_FILES['uploadFile']['size'];
				// $post_data['file_name'] = $_FILES['uploadFile']['name'];
				// $post_data['file_dir'] = './assets/uploads/signatories/';
				
				$ret =  new SignatoriesCollection();
				if($ret->addRows($_POST)) $res = new ModelResponse($ret->getCode(), $ret->getMessage());
				else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
				$result = json_decode($res,true);
			}else{
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	public function addHeadSignatories(){
		$result = array();
		$page = 'addHeadSignatories';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			if($this->input->post() && $this->input->post() != null)
			{
				$_FILES['uploadFile']['name'] 		= $_FILES['file']['name'];
				$_FILES['uploadFile']['type'] 		= $_FILES['file']['type'];
				$_FILES['uploadFile']['size'] 		= $_FILES['file']['size'];
				$_FILES['uploadFile']['error'] 		= $_FILES['file']['error'];
				$_FILES['uploadFile']['tmp_name'] = $_FILES['file']['tmp_name'];
				if (!file_exists('./assets/uploads/signatories'))
					mkdir('./assets/uploads/signatories', 0777, true);
				$config['upload_path'] = './assets/uploads/signatories/';
				$config['allowed_types'] = '*';
				$config['overwrite'] = TRUE;
				$config['remove_spaces'] = FALSE;
				$this->upload->initialize($config);
				if ($this->upload->do_upload('uploadFile')):
					$data = array('upload_data' => $this->upload->data());
				else:
					$error = array('error' => $this->upload->display_errors());
				endif;

				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}

				$post_data['file_size'] = $_FILES['uploadFile']['size'];
				$post_data['file_name'] = $_FILES['uploadFile']['name'];
				$post_data['file_dir'] = './assets/uploads/signatories/';
				
				$ret =  new SignatoriesCollection();
				if($ret->addHeadRows($post_data)) {
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

	public function updateSignatories(){
		$result = array();
		$page = 'updateSignatories';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {	
				// $post_data = array();
				// foreach ($this->input->post() as $k => $v) {
				// 	$post_data[$k] = $this->input->post($k,true);
				// }

				// if($_FILES['file']['name'] != '') {
				// 	$_FILES['uploadFile']['name'] 		= $_FILES['file']['name'];
				// 	$_FILES['uploadFile']['type'] 		= $_FILES['file']['type'];
				// 	$_FILES['uploadFile']['size'] 		= $_FILES['file']['size'];
				// 	$_FILES['uploadFile']['error'] 		= $_FILES['file']['error'];
				// 	$_FILES['uploadFile']['tmp_name'] = $_FILES['file']['tmp_name'];
				// 	if (!file_exists('./assets/uploads/signatories'))
				// 		mkdir('./assets/uploads/signatories', 0777, true);
				// 	$config['upload_path'] = './assets/uploads/signatories/';
				// 	$config['allowed_types'] = '*';
				// 	$config['overwrite'] = TRUE;
				// 	$config['remove_spaces'] = FALSE;
				// 	$this->upload->initialize($config);
				// 	if ($this->upload->do_upload('uploadFile')):
				// 		$data = array('upload_data' => $this->upload->data());
				// 	else:
				// 		$error = array('error' => $this->upload->display_errors());
				// 	endif;

				// 	$post_data['file_size'] = $_FILES['uploadFile']['size'];
				// 	$post_data['file_name'] = $_FILES['uploadFile']['name'];
				// 	$post_data['file_dir'] = './assets/uploads/signatories/';
				// }

				$ret =  new SignatoriesCollection();
				if($ret->updateRows($_POST)) {
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
	
	public function updateHeadSignatories(){
		$result = array();
		$page = 'updateHeadSignatories';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {	
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}

				if($_FILES['file']['name'] != '') {
					$_FILES['uploadFile']['name'] 		= $_FILES['file']['name'];
					$_FILES['uploadFile']['type'] 		= $_FILES['file']['type'];
					$_FILES['uploadFile']['size'] 		= $_FILES['file']['size'];
					$_FILES['uploadFile']['error'] 		= $_FILES['file']['error'];
					$_FILES['uploadFile']['tmp_name'] = $_FILES['file']['tmp_name'];
					if (!file_exists('./assets/uploads/signatories'))
						mkdir('./assets/uploads/signatories', 0777, true);
					$config['upload_path'] = './assets/uploads/signatories/';
					$config['allowed_types'] = '*';
					$config['overwrite'] = TRUE;
					$config['remove_spaces'] = FALSE;
					$this->upload->initialize($config);
					if ($this->upload->do_upload('uploadFile')):
						$data = array('upload_data' => $this->upload->data());
					else:
						$error = array('error' => $this->upload->display_errors());
					endif;

					$post_data['file_size'] = $_FILES['uploadFile']['size'];
					$post_data['file_name'] = $_FILES['uploadFile']['name'];
					$post_data['file_dir'] = './assets/uploads/signatories/';
				}

				$ret =  new SignatoriesCollection();
				if($ret->updateHeadRows($post_data)) {
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

	public function activateSignatories(){
		$result = array();
		$page = 'activateSignatories';
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
				$ret =  new SignatoriesCollection();
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

	public function activateHeadSignatories(){
		$result = array();
		$page = 'activateSignatories';
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
				$ret =  new SignatoriesCollection();
				if($ret->activeHeadRows($post_data)) {
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

	public function deactivateSignatories(){
		$result = array();
		$page = 'deactivateSignatories';
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
				$ret =  new SignatoriesCollection();
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

	public function deactivateHeadSignatories(){
		$result = array();
		$page = 'deactivateSignatories';
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
				$ret =  new SignatoriesCollection();
				if($ret->inactiveHeadRows($post_data)) {
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