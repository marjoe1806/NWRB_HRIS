<?php

class ImportOBFromExcel extends MX_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('ImportOBFromExcelCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}

	public function index() {
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Import From Excel');
			Helper::setMenu('templates/menu_template');
			Helper::setView('importobfromexcel','',FALSE);
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
		$TRANSACTION_DATE = isset($data['TRANSACTION_DATE']) ? date("Y-m-d", strtotime($data['TRANSACTION_DATE'])) : null;
		$TRANSACTION_DATE_END = isset($data['TRANSACTION_DATE_END']) ? date("Y-m-d", strtotime($data['TRANSACTION_DATE_END'])) : null;
		$RECEIVED_BY = isset($data['RECEIVED_BY']) ? $data['RECEIVED_BY'] : null;
		if($EMPLOYEE_NUMBER  != null || $TRANSACTION_DATE != null || $TRANSACTION_DATE_END != null || $TRANSACTION_DATE_END != null || $TRANSACTION_DATE_END != null || $RECEIVED_BY != null) {

			$uppercaseLetters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$lowercaseLetters = 'abcdefghijklmnopqrstuvwxyz';
			$allLetters = $uppercaseLetters . $lowercaseLetters;
			$Locator_id = '';

			for ($i = 0; $i < 8; $i++) {
				$randomIndex = rand(0, strlen($allLetters) - 1);
				$Locator_id .= $allLetters[$randomIndex];
			}
			
			$params = array (
				'locator_id' => $Locator_id,
				'location_id' => '0',
				'employee_number' => $EMPLOYEE_NUMBER ,
				'source_location' => 'excel_import',
				'source_device' => isset($data['SOURCE_DEVICE']) ? $data['SOURCE_DEVICE'] : null,
				'transaction_date' => $TRANSACTION_DATE,
				'transaction_date_end' => $TRANSACTION_DATE_END,
				'transaction_time' => isset($data['TRANSACTION_TIME']) ? date("H:i:s", strtotime($data['TRANSACTION_TIME'])) : null,
				'expected_time_return' => isset($data['TRANSACTION_TIME_END']) ? date("H:i:s", strtotime($data['TRANSACTION_TIME_END'])) : null,
				'control_no' => isset($data['CONTROL_NUMBER']) ? $data['CONTROL_NUMBER'] : null,
				'filing_date' => isset($data['FILING_DATE']) ? date("F d, Y", strtotime($data['FILING_DATE'])) : null,
				'activity_name' => isset($data['ACTIVITY_NAME']) ? $data['ACTIVITY_NAME'] : null,
				'purpose' => isset($data['PURPOSE']) ? $data['PURPOSE'] : null,
				'location' => isset($data['LOCATION']) ? $data['LOCATION'] : null,
				'checked_by' => isset($data['CHECKED_BY']) ? $data['CHECKED_BY'] : null,
				'filename' =>'NA',
				'filesize' => 'NA',
				'received_by' => $RECEIVED_BY,
				'remarks' => isset($data['REMARKS']) ? $data['REMARKS'] : null,
				'reject_remarks' => '',
				'modified_by' => '',
				'status' => isset($data['STATUS']) ? $data['STATUS'] : 'COMPLETED',
				'is_vehicle' => isset($data['VEHICLE']) && strtolower($data['VEHICLE']) == 'yes' ? 2 : 3,
				'is_return' => isset($data['RETURN']) && strtolower($data['RETURN']) == 'yes' ? 1 : 0,
				'is_active' => 0,
			);

			$ret =  new ImportOBFromExcelCollection();
				if($ret->addRows($params)) {
					$result['Logs'] = date("Y-m-d h:i:s")." : ".$ret->getMessage()."";
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