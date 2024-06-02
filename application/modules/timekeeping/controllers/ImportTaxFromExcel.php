<?php

class ImportTaxFromExcel extends MX_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('ImportTaxFromExcelCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}

	public function index() {
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Import From Excel');
			Helper::setMenu('templates/menu_template');
			Helper::setView('importtaxfromexcel','',FALSE);
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
			$result['form'] = $this->load->view('forms/importtaxfromexcelform.php', '', TRUE);
		}
		echo json_encode($result);
	}

	public function importexcel(){
		$result = array();
		$data = $this->input->post();
		//var_dump($data);die();
		$EMPLOYEE_NUMBER = isset($data['EMPLOYEE_ID']) ? $data['EMPLOYEE_ID'] : null;
		
		if($EMPLOYEE_NUMBER  !== null) {
			
			$ret =  new ImportTaxFromExcelCollection();
			
				//var_dump($data);
				if($ret->addRows($data)) {
					$result['Logs'] = date("Y-m-d h:i:s")." : Tax is inserted to the database.";
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$result['Logs'] = date("Y-m-d h:i:s")." : Tax is existing.";
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
			}else{
				$result['Logs'] = date("Y-m-d h:i:s")." : The file does not follow the provided format for import.";
			}
		
		echo json_encode($result);
	}

	
}

?>