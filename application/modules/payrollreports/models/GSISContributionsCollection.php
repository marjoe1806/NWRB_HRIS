<?php
	class GSISContributionsCollection extends Helper {
      	var $select_column = null;
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();

		}

	function make_datatables_summary_all($pay_basis, $payroll_period, $payroll_period_id){
		$all_employees = array();
		$all_employees = $this->getAllEmployeeDetails($pay_basis);
		foreach ($all_employees as $k => $employee) {
			if(isset($employee) && sizeof($employee) > 0) {
				$all_employees[$k]['first_name'] = $this->Helper->decrypt($all_employees[$k]['first_name'], $employee['employee_id']);
				$all_employees[$k]['middle_name'] = $this->Helper->decrypt($employee['middle_name'], $employee['employee_id']);
				$all_employees[$k]['last_name'] = $this->Helper->decrypt($employee['last_name'], $employee['employee_id']);
				$all_employees[$k]['employee_number'] = $this->Helper->decrypt($employee['employee_number'], $employee['employee_id']);
				$all_employees[$k]['employee_id_number'] = $this->Helper->decrypt($employee['employee_id_number'], $employee['employee_id']);
			}
		}
		return $all_employees;
	}

	function getAllEmployeeDetails($params) {
		$pay_basis = $params != "All" ? "tblemployees.pay_basis = '" . $params . "' AND " : "";
		$sql = "SELECT * FROM tblemployees LEFT JOIN tbltransactionsprocesspayroll ON tblemployees.id = tbltransactionsprocesspayroll.employee_id WHERE " . $pay_basis . "tblemployees.employment_status = 'Active' AND tblemployees.is_active = 1 AND tbltransactionsprocesspayroll.is_active = 1";
		$query = $this->db->query($sql);
		$employee = $query->result_array();
		return $employee;
	}

}
?>