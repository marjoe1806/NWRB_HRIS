<?php

class AccomplishmentReports extends MX_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('AccomplishmentReportsCollection');
		$this->load->module('session');
		$this->load->library('upload');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}

	public function index() {
		// Helper::rolehook(ModuleRels::ACCOMPLISHMENT_REPORT_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewAccomplishmentReports";
		$listData['key'] = $page;
		$ret = new AccomplishmentReportsCollection();
		if($ret->hasRows()){
			$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
			$respo = json_decode($res);
		}
		else{
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			$respo = json_decode($res);
		}
		$listData['list'] = $respo;
		$viewData['table'] = $this->load->view("helpers/accomplishmentreportslist",$listData,TRUE);

		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('AccomplishmentReports');
			Helper::setMenu('templates/menu_template');
			Helper::setView('accomplishmentreports',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}

	public function addAccomplishmentReportsForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addAccomplishmentReports';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/accomplishmentreportsform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updateAccomplishmentReportsForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateAccomplishmentReports';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/accomplishmentreportsform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function addAccomplishmentReports(){
		$result = array();
		$page = 'addAccomplishmentReports';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			if($this->input->post() && $this->input->post() != null){
				$_FILES['uploadFile']['name'] = $_FILES['file']['name'];
				$_FILES['uploadFile']['type'] = $_FILES['file']['type'];
				$_FILES['uploadFile']['tmp_name'] = $_FILES['file']['tmp_name'];
				$_FILES['uploadFile']['error'] = $_FILES['file']['error'];
				$_FILES['uploadFile']['size'] = $_FILES['file']['size'];
				if (!file_exists('./assets/uploads/accomplishmentreports')) {
					mkdir('./assets/uploads/accomplishmentreports', 0777, true);
				}
				$config['upload_path'] = './assets/uploads/accomplishmentreports/';
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
				$post_data  = $this->array_push_assoc($post_data, 'file_name', $_FILES['file']['name']);
				$post_data  = $this->array_push_assoc($post_data, 'file_size', $_FILES['file']['size']);
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new AccomplishmentReportsCollection();
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
	public function updateAccomplishmentReports(){

		//var_dump($_POST);die();
		$result = array();
		$page = 'updateAccomplishmentReports';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$_FILES['uploadFile']['name'] = $_FILES['file']['name'];
				$_FILES['uploadFile']['type'] = $_FILES['file']['type'];
				$_FILES['uploadFile']['tmp_name'] = $_FILES['file']['tmp_name'];
				$_FILES['uploadFile']['error'] = $_FILES['file']['error'];
				$_FILES['uploadFile']['size'] = $_FILES['file']['size'];
				if (!file_exists('./assets/uploads/accomplishmentreports')) {
					mkdir('./assets/uploads/accomplishmentreports', 0777, true);
				}
				$config['upload_path'] = './assets/uploads/accomplishmentreports/';
				$config['allowed_types'] = '*';
				$config['overwrite'] = TRUE;
				$this->upload->initialize($config);
				if ($this->upload->do_upload('uploadFile')):
				$data = array('upload_data' => $this->upload->data());
				else:
						$error = array('error' => $this->upload->display_errors());
				endif;
				$post_data = array();
				$post_data  = $this->array_push_assoc($post_data, 'file_name', $_FILES['file']['name']);
				$post_data  = $this->array_push_assoc($post_data, 'file_size', $_FILES['file']['size']);
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new AccomplishmentReportsCollection();
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

	public function activateAccomplishmentReports(){
		$result = array();
		$page = 'activateAccomplishmentReports';
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
				$ret =  new AccomplishmentReportsCollection();
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
	public function deactivateAccomplishmentReports(){
		$result = array();
		$page = 'deactivateAccomplishmentReports';
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
				$ret =  new AccomplishmentReportsCollection();
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
	public function getActiveAccomplishmentReports(){
		$result = array();
		$page = 'getActiveAccomplishmentReports';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new AccomplishmentReportsCollection();
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

	function array_push_assoc($array, $key, $value){
		$array[$key] = $value;
		return $array;
	}
}

?>