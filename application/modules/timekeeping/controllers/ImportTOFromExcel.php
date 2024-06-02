<?php

class ImportTOFromExcel extends MX_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('ImportTOFromExcelCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}

	public function index() {
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Import From Excel');
			Helper::setMenu('templates/menu_template');
			Helper::setView('importtofromexcel','',FALSE);
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
			$result['form'] = $this->load->view('forms/importtofromexcelform.php', '', TRUE);
		}
		echo json_encode($result);
	}

	public function importexcel(){
		$result = array();
		$data = $this->input->post();
		$EMPLOYEE_NUMBER = isset($data['scanning']) ? $data['scanning'] : null;
		
		if($EMPLOYEE_NUMBER  !== null) {
			
			$ret =  new ImportTOFromExcelCollection();
			
				if($ret->addRows($data)) {
					$result['Logs'] = date("Y-m-d h:i:s")." : ".$ret->getMessage()."";
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$result['Logs'] = date("Y-m-d h:i:s")." : Travel order is existing.";
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
			}else{
				$result['Logs'] = date("Y-m-d h:i:s")." : The file does not follow the provided format for import.";
			}
		
		echo json_encode($result);
	}

	
}

?>