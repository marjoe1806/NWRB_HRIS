<?php
	class ImportTaxFromExcelCollection extends Helper {
		public function __construct() {
			$this->load->model('HelperDao');
			$this->load->model("Security");
			ModelResponse::busy();
		}
		//**********new*********
		public function addRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Failed to import tax from excel.";

			//$query = $this->fetchEmployee($params['EMPLOYEE_ID']);
			$this->db->select('division_id, position_id, salary, id,pay_basis, DECRYPTER(employee_number,"sunev8clt1234567890",id) as empl_id');
			$this->db->from('tblemployees');
			$this->db->where('DECRYPTER(employee_id_number,"sunev8clt1234567890",id)', $params['EMPLOYEE_ID']);
			$query = $this->db->get()->result_array();
			//var_dump($query);die();
			$getperiodsettings = $this->fetchperiodID($params['START_PERIOD'], $params['END_PERIOD']);
			$formattax = "";
			if (isset($params['TAX']) && $params['TAX'] != ""){
				$formattax = str_replace(",", "", $params['TAX']);
			}

			$data = array(
				'employee_id' => $query[0]['id'],
				'division_id' => $query[0]['division_id'],
				'pay_basis' => $query[0]['pay_basis'],
				'salary' => $query[0]['salary'],
				'wh_tax_amt' => $formattax,
				'payroll_period_id' => (int)$getperiodsettings->row()->period_id
			);


			//var_dump($data);die();
			if (!$query) { 
				$message = "Employee doesn't exist.";
				$this->ModelResponse($code, $message);
				return false;
			} else {
				$sqlimporttax = 'SELECT *
		 		FROM tblimportprocesspayroll 
		 		WHERE employee_id = "'.$query[0]['id'].'"
		 				  AND pay_basis = "'. $query[0]['pay_basis'].'" AND division_id = '.(int)$query[0]['division_id'].' AND payroll_period_id = '.(int)$getperiodsettings->row()->period_id.'';
				$queryimporttax = $this->db->query($sqlimporttax);
				if($queryimporttax->num_rows() > 0){

				$tax = "UPDATE tblimportprocesspayroll SET wh_tax_amt = ?,  payroll_period_id = ?, salary = ?
		 			 WHERE id = ? ";
		 			 $params = array(
						$formattax,
						(int)$getperiodsettings->row()->period_id,
						$query[0]['salary'],
						$queryimporttax->row()->id
					);
					$this->db->query($tax,$params);
					if($this->db->trans_status() === TRUE){
						$code = "0";
						$this->db->trans_commit();
						$message = "Tax successfully imported from excel.";
						$helperDao->auditTrails(Helper::get('userid'),$message);
						$this->ModelResponse($code, $message);
						return true;
					}
					else {
						$this->db->trans_rollback();
						$helperDao->auditTrails(Helper::get('userid'),$message);
						$this->ModelResponse($code, $message);
					}
				}else{
					$this->db->insert('tblimportprocesspayroll', $data);
					if($this->db->trans_status() === TRUE){
						$code = "0";
						$this->db->trans_commit();
						$message = "Tax successfully imported from excel.";
						$helperDao->auditTrails(Helper::get('userid'),$message);
						$this->ModelResponse($code, $message);
						return true;
					}
					else {
						$this->db->trans_rollback();
						$helperDao->auditTrails(Helper::get('userid'),$message);
						$this->ModelResponse($code, $message);
					}
				}	
			} 
			return false;
		}

		function fetchEmployee($employee_number){
			$sql = 'SELECT division_id, position_id, salary, id,pay_basis, DECRYPTER(employee_number,"sunev8clt1234567890",id) as empl_id
					FROM tblemployees
					WHERE 
					CAST(DECRYPTER(employee_number,"sunev8clt1234567890",tblemployees.id) AS INT) = CAST("'.$employee_number.'" AS INT)';
			$query = $this->db->query($sql);
			return $query;
		}

		function fetchperiodID($start_period, $end_period){
			$sql = 'SELECT id as period_id
					FROM tblfieldperiodsettings
					WHERE 
					start_date = "'.$start_period.'" AND end_date = "'.$end_period.'" AND is_active = 1';
			$query = $this->db->query($sql);
			return $query;
		}		
	}
?>