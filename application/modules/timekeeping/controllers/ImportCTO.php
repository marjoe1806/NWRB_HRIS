<?php

class ImportCTO extends MX_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('ImportCTOCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}

	public function index() {
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Import CTO Balance');
			Helper::setMenu('templates/menu_template');
			Helper::setView('importctofromexcel','',FALSE);
			Helper::setTemplate('templates/master_template');
		}
		Session::checksession();
	}

	public function viewImportFromExcelForm() {
		$result = array();
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$result['form'] = $this->load->view('forms/importobfromexcelform.php', '', TRUE);
		}
		echo json_encode($result);
	}

	public function importexcel(){
		
		$result = array();
		$data = $this->input->post();
		$ret =  new ImportCTOCollection();

		if($ret->addRows($data)) $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		else $res = new ModelResponse($ret->getCode(), $ret->getMessage());

		$result = json_decode($res,true);

		$result["Logs"] = $ret->getMessage();
		echo json_encode($result);
	}

	
}

?>