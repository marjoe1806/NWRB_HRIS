<?php

class ImportFromPCBase extends MX_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('ImportFromPCBaseCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}

	public function index() {
		$page = "addImportFromPCBasePayrollSetup";
		$listData['key'] = $page;
		$viewData['regular_form'] = $this->load->view("forms/importfrompcbaseform",$listData,TRUE);
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Import Raw DTR File');
			Helper::setMenu('templates/menu_template');
			Helper::setView('importfrompcbase',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		Session::checksession();
	}

	public function addImportFromPCBasePayrollSetup(){
		$result = array();
		$page = 'addImportFromPCBasePayrollSetup';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else {
			$data = array('post' => $_POST, 'file' => $_FILES['file']);
			$ret =  new ImportFromPCBaseCollection();
			$ret->uploadFiles($data);
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			$result = json_decode($res,true);
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	public function readFileContents(){
		$result = array();
		$model =  new ImportFromPCBaseCollection();
		$result = $model->importRawDTR();
		echo json_encode($result);
	}

}

?>