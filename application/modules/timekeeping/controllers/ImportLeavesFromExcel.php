<?php

class ImportLeavesFromExcel extends MX_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('ImportLeavesFromExcelCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}

	public function index() {
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Import Leave Credits From Excel');
			Helper::setMenu('templates/menu_template');
			Helper::setView('importleavesfromexcel','',FALSE);
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
		$EMPLOYEE_NUMBER = isset($data['SCANNING_NUMBER_ID']) ? $data['SCANNING_NUMBER_ID'] : null;
		$YEAR = isset($data['YEAR']) ? $data['YEAR'] : null;
		$VL = isset($data['VL']) ? $data['VL'] : null;
		$SL = isset($data['SL']) ? $data['SL'] : null;
		$SOURCE_DEVICE = $data['SOURCE_DEVICE'];
		if($EMPLOYEE_NUMBER  != null && $YEAR != null && $VL != null && $SL != null) {
			$params = array (
				'scanning_no' => $EMPLOYEE_NUMBER,
				'year' => $YEAR,
				'vl' => $VL,
				'sl' => $SL,
				'total' => $VL + $SL,
				'source_location' => 'excel_import',
				'source_device' => $SOURCE_DEVICE 
			);			

			$ret =  new ImportLeavesFromExcelCollection();
				if($ret->addRows($params)) {
					$result['Logs'] = date("Y-m-d h:i:s")." : VL- ".$VL." and SL- ".$SL." balance for scanning no. ".$EMPLOYEE_NUMBER. " is inserted to the database.";
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$result['Logs'] = date("Y-m-d h:i:s")." : The file does not follow the provided format for import.";
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
			}else{
				$result['Logs'] = date("Y-m-d h:i:s")." : The file does not follow the provided format for import.";
			}
		echo json_encode($result);
	}

	
}

?>