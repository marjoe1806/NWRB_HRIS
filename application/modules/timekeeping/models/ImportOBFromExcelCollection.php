<?php
	class ImportOBFromExcelCollection extends Helper {
		public function __construct() {
			$this->load->model('HelperDao');
			$this->load->model("Security");
			ModelResponse::busy();
		}

		public function addRows($params){

			$helperDao = new HelperDao();
			$code = "1";
			$message = "Failed to import timelogs from excel.";

			
			$query = $this->fetchEmployee($params['employee_number']);
			$receiver_query = $this->fetchEmployee($params['received_by']);
			$locator_id_exist = $this->fetchLocatorId($params['control_no']);


			if(sizeof($locator_id_exist) == 1){
				$params['locator_id'] = $locator_id_exist['locator_id'];
			}
			
			if (!$query && !$receiver_query) { 
				$message = "Employee with scanning number doesn't exist.";
				$this->ModelResponse($code, $message);
				return false;
			} else {
				$employee_id = $query['id'];
				$receiver_employee_id = $receiver_query['id'];
				
				
				// $params['leave_id'] = 0;
	 			// if($leave_id != null) 
				 $params['division_id'] = $query['division_id'];
				 $params['position_id'] = $query['position_id'];
				 $params['received_by'] = $receiver_employee_id;
				 $params['employee_id'] = $employee_id;
				 unset($params["employee_number"]);
				$this->db->trans_begin();
						
				// $d = date_parse_from_format("Y-m-d", $params['transaction_date']);
				// $this->db->where('MONTH(transaction_date)', $d["month"]);

				// $sql = "SELECT * FROM tbltimekeepinglocatorslips WHERE control_no = '".$params["control_no"]."' ";
				// $isExist = $this->db->query($sql)->row_array();

				// if(!$isExist){
					$this->db->where('transaction_date', $params['transaction_date']);
					$this->db->where('employee_id', $employee_id);
					$this->db->where('source_location', 'excel_import');
					$this->db->where('source_device !=', $params['source_device']);
					$this->db->delete('tbltimekeepinglocatorslips');
					$this->db->insert('tbltimekeepinglocatorslips',$params);
					if($this->db->trans_status() === TRUE){
						$code = "0";
						$this->db->trans_commit();
						$message = "Timelogs successfully imported from excel.";
						$helperDao->auditTrails(Helper::get('userid'),$message);
						$message = "Transaction date for ".$params['transaction_date']." is inserted to the database.";
						$this->ModelResponse($code, $message);
						return true;
					}
					else {
						$this->db->trans_rollback();
						$helperDao->auditTrails(Helper::get('userid'),$message);
						$this->ModelResponse($code, $message);
					}
				// }else{
				// 	$message = "Control number ".$params['control_no']." already exist.";
				// 	$helperDao->auditTrails(Helper::get('userid'),$message);
				// 	$this->ModelResponse($code, $message);
				// 	return true;
				// }
				return false;
			}
		}

		function fetchEmployee($employee_number){
			$sql = 'SELECT division_id, position_id, salary, id, DECRYPTER(employee_number,"sunev8clt1234567890",id) as empl_id
					FROM tblemployees
					WHERE 
					CAST(DECRYPTER(employee_number,"sunev8clt1234567890",tblemployees.id) AS INT) = CAST("'.$employee_number.'" AS INT)';
			$query = $this->db->query($sql)->row_array();

			return $query;

		}

		function fetchLocatorId($control_no){
			$sql = 'SELECT locator_id
					FROM tbltimekeepinglocatorslips
					WHERE 
					control_no = "'.$control_no.'" LIMIT 1';
			$query = $this->db->query($sql)->row_array();
			return $query;

		}

		
	}
?>