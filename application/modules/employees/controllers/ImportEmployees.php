<?php

class ImportEmployees extends MX_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('ImportEmployeesCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}

	public function index() {
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Import Leave Credits From Excel');
			Helper::setMenu('templates/menu_template');
			Helper::setView('importEmployees','',FALSE);
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
			$result['form'] = $this->load->view('forms/importemployeeform.php', '', TRUE);
		}
		echo json_encode($result);
	}

	public function importexcel(){
		$result = array();
		$data = $this->input->post();
		$scanning_no = isset($data['scanning_no']) ? $data['scanning_no'] : null;
		$employee_number = isset($data['employee_number']) ? $data['employee_number'] : null;
		$last_name = isset($data['last_name']) ? $data['last_name'] : null;
		$first_name = isset($data['first_name']) ? $data['first_name'] : null;
		$middle_name = isset($data['middle_name']) ? $data['middle_name'] : null;
		$suffix = isset($data['suffix']) ? $data['suffix'] : null;
		$division_id = isset($data['division_id']) ? $data['division_id'] : null;
		$position_id = isset($data['position_id']) ? $data['position_id'] : null;
		$email = isset($data['email']) ? $data['email'] : null;
		$gender = isset($data['gender']) ? $data['gender'] : null;
		$mobile_no = isset($data['mobile_no']) ? $data['mobile_no'] : null;
		$pay_basis = isset($data['pay_basis']) ? $data['pay_basis'] : null;
		$birthday = isset($data['birthday']) ? $data['birthday'] : null;
		$start_date = isset($data['start_date']) ? $data['start_date'] : null;
		$flex_shift_id = isset($data['flex_shift_id']) ? $data['flex_shift_id'] : null;
		if($scanning_no  != null && $last_name != null && $first_name != null && $email != null) {
			$params = array (
				'scanning_no' => $scanning_no,
				'employee_number' => $employee_number,
				'last_name' => $last_name,
				'first_name' => $first_name,
				'middle_name' => $middle_name,
				'extension' => $suffix,
				'division_id' => $division_id,
				'position_id' => $position_id,
				'email' => $email,
				'gender' => $gender,
				'mobile_no' => $mobile_no,
				'pay_basis' => $pay_basis,
				'birthday' => $birthday,
				'start_date' => $start_date,
				'flex_shift_id' => $flex_shift_id
			);
			// var_dump($params); die();		

			$ret =  new ImportEmployeesCollection();
				if($ret->addRows($params)) {
					$result['Logs'] = date("Y-m-d h:i:s").": ".$last_name.", ".$first_name." is inserted to the database.";
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$result['Logs'] = date("Y-m-d h:i:s").":  The file does not follow the provided format for import.";
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
			}else{
				$result['Logs'] = date("Y-m-d h:i:s").":  The file does not follow the provided format for import.";
			}
		// var_dump($params); die();
		echo json_encode($result);
	}

	
}

?>