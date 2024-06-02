<?php

class ImportPayrollInfoFromExcel extends MX_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('ImportPayrollInfoFromExcelCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}

	public function index() {
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Import Employee Payroll Info');
			Helper::setMenu('templates/menu_template');
			Helper::setView('importpayrollinfofromexcel','',FALSE);
			Helper::setTemplate('templates/master_template');
		}
		Session::checksession();
	}

	public function importpayrollinfofromexcelform() {
		$result = array();
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$result['form'] = $this->load->view('forms/importpayrollinfofromexcelform.php', '', TRUE);
		}
		echo json_encode($result);
	}

	public function repSC($string){
		return (int)str_replace(":", "", $string);
	}

	public function getDeductions($start, $end){
		$elapsed = abs($end - $start);
		$years = abs(floor($elapsed / 31536000));
		$days = abs(floor(($elapsed-($years * 31536000))/86400));
		$hours = abs(floor(($elapsed-($years * 31536000)-($days * 86400))/3600));
		$minutes = abs(floor(($elapsed-($years * 31536000)-($days * 86400)-($hours * 3600))/60));
		return array($hours,$minutes);
	}

	public function importexcel(){
		$result = array();
		$data = $this->input->post();
		$ret =  new ImportPayrollInfoFromExcelCollection();

		if($ret->updatePayrollInfo($data)) $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		else $res = new ModelResponse($ret->getCode(), $ret->getMessage());

		$result = json_decode($res,true);
		echo json_encode($result);
	}

	public function addRows($SCANNING_NUMBER, $DATE, $TRANSACTION_TIME, $TRANSACTION_TYPE, $SOURCE_DEVICE, $OT_TOTAL, $REMARKS){
		$params = array (
			'employee_number' => $SCANNING_NUMBER,
			'transaction_date' => $DATE,
			'transaction_time' => $TRANSACTION_TIME,
			'col4' => 0,
			'transaction_type' => $TRANSACTION_TYPE,
			'col6' => 0,
			'col7' => 0,
			'source_location' => 'excel_import',
			'source_device' => $SOURCE_DEVICE,
			'latitude' => '',
			'longitude' => '',
			'remarks' => $REMARKS,
			'num_hrs' => $OT_TOTAL,
			'modified_by' => Helper::get('userid')
		);
		
		$ret =  new ImportPayrollInfoFromExcelCollection();
		if($ret->addRows($params)) {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		} else {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		}
		$result = json_decode($res,true);
	}
}

?>