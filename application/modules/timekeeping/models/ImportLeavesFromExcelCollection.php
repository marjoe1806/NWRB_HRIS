<?php
	class ImportLeavesFromExcelCollection extends Helper {
		public function __construct() {
			$this->load->model('HelperDao');
			$this->load->model("Security");
			ModelResponse::busy();
		}

		public function addRows($params){

			$helperDao = new HelperDao();
			$code = "1";
			$message = "Failed to import timelogs from excel.";

			$query = $this->fetchEmployee($params['scanning_no']);
			if ($query->num_rows() == 0 ) { 
				$message = "Employee with scanning number doesn't exist.";
				$this->ModelResponse($code, $message);
				return false;
			} else {
				$employee_id = $query->row()->id;
				$params['id'] = $employee_id;
				$isExist_query = $this->validateLeaves($params);
				try {
					if($isExist_query->num_rows() == 0 ) { 
						$this->db->insert('tblleavebalance',$params);
					}else{
						$sql = "UPDATE tblleavebalance 
								SET vl = CASE WHEN (source_device = '".$params['source_device']."') THEN vl + ".$params['vl']." ELSE ".$params['vl']." END,
									sl = CASE WHEN (source_device = '".$params['source_device']."') THEN sl + ".$params['sl']." ELSE ".$params['sl']." END,
									total = vl + sl,
									source_location =  'excel_import',
									source_device ='".$params['source_device']."'
								WHERE scanning_no = '".$params['scanning_no']."' AND year = '".$params['year']."'";
						$query = $this->db->query($sql);
					}
					$code = "0";
					$this->db->trans_commit();
					$message = "Timelogs successfully imported from excel.";
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
					return true;
				}catch(Exception $e) {
				  	$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
				}
				return false;
			}
		}

		function fetchEmployee($employee_number){
			$sql = 'SELECT division_id, position_id, id FROM tblemployees WHERE employee_number = ENCRYPTER("'.$employee_number.'","sunev8clt1234567890",tblemployees.id)';
			$query = $this->db->query($sql);
			return $query;
		}
		function validateLeaves($params){
			$sql = 'SELECT source_location, source_device FROM tblleavebalance WHERE scanning_no = "'.$params['scanning_no'].'" AND year = "'.$params['year'].'" ';
			$query = $this->db->query($sql);
			return $query;
		}

		
	}
?>