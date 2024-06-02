<?php

class ImportUsedCTO extends MX_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('ImportUsedCTOCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}

	public function index() {
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Import Used CTO');
			Helper::setMenu('templates/menu_template');
			Helper::setView('importusedcto','',FALSE);
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

	public function importleavedays(){
		$ret =  new ImportUsedCTOCollection();
		if($ret->leaveDays()) {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		}
		echo json_encode($res);
	}

	public function importexcel(){
		$result = array();
		$data = $this->input->post();
		if(isset($data['SCANNING_NUMBER_ID']) && isset($data['LEAVE_TYPE'])  && isset($data['DATE_FILED']) ) { //&& isset($data['NO_OF_DAYS'])
			$params['table-1'] = array (
				'employee_number' => @$data['SCANNING_NUMBER_ID'],
				'offset_date_effectivity' => isset($data['INCLUSIVE_DATES']) ? $data['INCLUSIVE_DATES'] : '',
				'source_location' => 'excel_import',
				'source_device' => $data['SOURCE_DEVICE'],
				'inclusive_dates' => '',
				'kind' => @$data['KIND'],
				'type' => @$data['LEAVE_TYPE'],
				'type_others' => @$data['OTHER_TYPES'],
				'type_vacation' => @$data['VACATION_TYPE'],
				'type_vacation_others' => @$data['OTHER_VACATION_TYPE'],
				'type_vacation_location' => @$data['TYPE_VACATION_LOCATION'],
				'type_vacation_location_specific' => @$data['TYPE_VACATION_SPECIFIC_LOCATION'],
				'type_sick_location' => @$data['TYPE_SICK_LOCATION'],
				'type_sick_location_hospital' => @$data['TYPE_SICK_LOCATION_HOSPITAL'],
				'commutation' => @$data['COMMUTATION'],
				'number_of_days' => @$data['NO_OF_DAYS'],
				'isMedical' => @$data['IS_MEDICAL'],
				'is_terminal' => @$data['IS_TERMINAL'],
				'amount_monetized' => @$data['AMOUNT_MONETIZED'],
				'for_medical' => @$data['FOR_MEDICAL'],
				'date_filed' => @$data['DATE_FILED'],
				'offset_hrs' => @$data['CTO_HRS'],
				'offset_mins' => @$data['CTO_MINS'],
				'status' => 5,
				'approved_by' => '',
				'is_active' => 1,
				'date_created' => date("Y-m-d H:i:s"),
				'date_created' => date("Y-m-d H:i:s"),
				'filename' => '',
				'filesize' => '',
				'force_status' => '',
				'force_remarks' => '',
				'filing_date' => date("M d, Y", strtotime($data['DATE_FILED'])),
				'vacation_loc' => @$data['VACATION_LOCATION'],
				'vacation_loc_details' => @$data['VACATION_LOCATION_DETAILS'],
				'sick_loc' => @$data['SICK_LOCATION'],
				'sick_loc_details' => @$data['SICK_LOCATION_DETAILS'],
				'type_study' => @$data['STUDY_TYPE'],
				'remarks' => @$data['REMARKS'],                                                                                                                                         
			);
			$params['table-2'] = array (
				'approved_by' => $_SESSION['id'],
				'date_approved' => date("Y-m-d H:i:s"),
				'approval_type' => 3,
				'name' => @$data['NAME'],
				'remarks' => isset($data['REMARKS'])? $data['REMARKS'] : '',
			);
			$ret =  new ImportUsedCTOCollection();
			if($ret->addRows($params)) {
				$result['Logs'] = date("Y-m-d h:i:s")." : CTO filed for date ".$data['INCLUSIVE_DATES']." is inserted to the database.";
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