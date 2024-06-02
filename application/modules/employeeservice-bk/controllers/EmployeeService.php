<?php
//http://localhost/PAYROLL/employeeservice/EmployeeService/addMobileEmployeeDTR
//192.168.1.106
class EmployeeService extends MX_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('EmployeeServiceCollections');
	}
	public function addEmployeeDetails() {
		//var_dump(date('h:i:s', strtotime("2018-07-08 05:20:31")));die();
		$securityKey = "TheBestSecretKey";
		$this->load->model("Security");
		$security = new Security();
		$ret = new EmployeeServiceCollections();
		//$params = (array) json_decode(file_get_contents('php://input'), TRUE);
		$params = file_get_contents('php://input');
		$decrypted_params = $security->decrypt($params,$securityKey);
		$object_params = json_decode($decrypted_params,true);
		//var_dump($object_params['EmpModelList']);die();
		if(isset($object_params) && $object_params != null){
			if($ret->insertEmployeeMobile($object_params)) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}
			$result = json_decode($res,true);
		}
		else
		{

			$res = new ModelResponse("1","The system is currently experiencing some errors. Please try again later.");
			$result = json_decode($res,true);
		}
		echo json_encode($result);
	}
	public function addMobileEmployeeDTR() {
		//var_dump(date('h:i:s', strtotime("2018-07-08 05:20:31")));die();
		$securityKey = "TheBestSecretKey";
		$this->load->model("Security");
		$security = new Security();
		$ret = new EmployeeServiceCollections();
		//$params = (array) json_decode(file_get_contents('php://input'), TRUE);
		$params = file_get_contents('php://input');
		$decrypted_params = $security->decrypt($params,$securityKey);
		$object_params = json_decode($decrypted_params,true);
		//var_dump($object_params['EmpModelList']);die();
		if(isset($object_params) && $object_params != null){
			if($ret->insertEmployeeDTR($object_params)) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}
			$result = json_decode($res,true);
		}
		else
		{

			$res = new ModelResponse("1","The system is currently experiencing some errors. Please try again later.");
			$result = json_decode($res,true);
		}
		echo json_encode($result);
	}
	public function insertEmployee(){
		$ret = new EmployeeServiceCollections();
		$ret->insertEmployees();
	}
	public function init(){
		$params = (array) json_decode(file_get_contents('php://input'), TRUE);
		if(isset($params) && sizeof($params) > 0){
			$res = new ModelResponse("0","Successfully connected!");
			$result = json_decode($res,true);
		}
		else
		{

			$res = new ModelResponse("1","No data given.");
			$result = json_decode($res,true);
		}
		echo json_encode($result);
	}

}
?>