<?php
	class ImportTOFromExcelCollection extends Helper {
		public function __construct() {
			$this->load->model('HelperDao');
			$this->load->model("Security");
			ModelResponse::busy();
		}

		public function addRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Failed to import travel order from excel.";

			// $sql = "SELECT * FROM tbltravelorder WHERE travel_order_no = '".$params["travel_order_no"]."' ";
			// $isExist = $this->db->query($sql)->row_array();

			// if(!$isExist){
				$query = $this->fetchEmployee($params['scanning']);	
				if (!$query) {
					$message = "Employee with scanning number doesn't exist.";
					$this->ModelResponse($code, $message);
					return false;
				} else {
					$dates = $params['duration'];
					$string_days = $params['no_days'];
					$length = (int)$string_days;
					$no_of_days = $length;

					if(strpos($dates, '-') !== false) {
						$transac_date = explode('-', $dates);
						$remove_space = str_replace(' ', '', $transac_date[0]);
						$newdateFormat = str_replace('/', '-', $remove_space);
						$date_for_dtr = explode('-', $newdateFormat);		  

						for ($i=0; $i <$no_of_days ; $i++) { 
							$dtr_transacdate=  $date_for_dtr[2]."-".$date_for_dtr[0]."-".$date_for_dtr[1];
							$dtrdate = date("Y-m-d", strtotime($dtr_transacdate.' + '.$i.' day'));
							$sqldtr = 'SELECT *
							FROM tbldtr 
							WHERE scanning_no = "'.(int)$query->row()->empl_id.'"
										AND transaction_date = "'.$dtrdate.'"';
							$querydtr = $this->db->query($sqldtr);
							if($querydtr->num_rows() > 0){
								$dtr_sql = "UPDATE tbldtr SET adjustment_remarks = ? , tardiness_mins = 0 , tardiness_hrs = 0 , ut_mins = 0 , ut_hrs = 0 WHERE scanning_no = ? AND transaction_date = ?";
								$dtrparams = array(($params['travel_order_no'] != "" && $params['travel_order_no'] != null ? $params['travel_order_no'] : "0000").'', (int)$query->row()->empl_id, $querydtr->row()->transaction_date);
								$this->db->query($dtr_sql,$dtrparams);				
							}else{
								$this->db->insert('tbldtr', array("adjustment_remarks" => ($params['travel_order_no'] != "" && $params['travel_order_no'] != null ? $params['travel_order_no'] : "0000").'',  "transaction_date" => $dtrdate, "scanning_no" => (int)$query->row()->empl_id));
							}
						}
					} else {
						$newdateFormat = str_replace('/', '-', $dates);
						$date_for_dtr = explode('-', $newdateFormat);	
						$dtr_transacdate=  $date_for_dtr[2]."-".$date_for_dtr[0]."-".$date_for_dtr[1];
						$dtrdate = date("Y-m-d", strtotime($dtr_transacdate));

						$sqldtr = 'SELECT *
							FROM tbldtr 
							WHERE scanning_no = "'.(int)$query->row()->empl_id.'"
								AND transaction_date = "'.$dtrdate.'"';
						$querydtr = $this->db->query($sqldtr);
						if($querydtr->num_rows() > 0){
							
							$dtr_sql = "UPDATE tbldtr SET adjustment_remarks = ? , tardiness_mins = 0 , tardiness_hrs = 0 , ut_mins = 0 , ut_hrs = 0 WHERE scanning_no = ? AND transaction_date = ?";
							$dtrparams = array(($params['travel_order_no'] != "" && $params['travel_order_no'] != null ? $params['travel_order_no'] : "0000").'', (int)$query->row()->empl_id, $querydtr->row()->transaction_date);
							$this->db->query($dtr_sql,$dtrparams);	
							
						}else{
							$this->db->insert('tbldtr', array("adjustment_remarks" => ($params['travel_order_no'] != "" && $params['travel_order_no'] != null ? $params['travel_order_no'] : "0000").'',  "transaction_date" => $dtrdate, "scanning_no" => (int)$query->row()->empl_id));
							
						}
					}
					$employee_id = $query->row()->id;
					$division_id = $query->row()->division_id;

					$sqlTO = 'SELECT * FROM tbltravelorder WHERE employee_id = ? AND travel_order_no = ? AND status = 5 AND duration = ?';
					$toparams = array($employee_id,$params['travel_order_no'],$params['duration']);
					$queryTO = $this->db->query($sqlTO,$toparams);

					if($queryTO->num_rows() === 0){

						$uppercaseLetters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
						$lowercaseLetters = 'abcdefghijklmnopqrstuvwxyz';
						$allLetters = $uppercaseLetters . $lowercaseLetters;
						$travel_id = '';

						for ($i = 0; $i < 8; $i++) {
							$randomIndex = rand(0, strlen($allLetters) - 1);
							$travel_id .= $allLetters[$randomIndex];
						}
						
						$insert_parameters = array(
							'travel_id' => $travel_id,
							'travel_order_no' => $params['travel_order_no'],
							'division_id' => $division_id,
							'employee_id' => $employee_id,
							'location' => $params['location'],
							'officialpurpose' => $params['official_purpose'],
							'duration' => $params['duration'],
							'no_days' => $params['no_days'],
							'source_location' => 'excel_import',
							'is_vehicle' => isset($params['vehicle']) && strtolower($params['vehicle']) == 'yes' ? 2 : 3,
							'purpose' => isset($params['return']) && strtolower($params['return']) == 'yes' ? 'return' : 'not_return',
							'reason' => $params['reason'],
							'status' => 5
						);

						$this->db->insert('tbltravelorder',$insert_parameters);
						if($this->db->trans_status() === TRUE){
							$code = "0";
							$this->db->trans_commit();
							$message = "Travel order successfully imported from excel.";
							$helperDao->auditTrails(Helper::get('userid'),$message);
							$message = "Travel order is inserted to the database.";
							$this->ModelResponse($code, $message);
							return true;
						}
						else {
							$this->db->trans_rollback();
							$helperDao->auditTrails(Helper::get('userid'),$message);
							$this->ModelResponse($code, $message);
						}
					}else{
						$update_param = array(
							$params['duration'],
							$params['location'],
							$params['official_purpose'],
							$params['travel_order_no'],
							$employee_id,
						);

						$updateTO = "UPDATE tbltravelorder SET duration = ?, location = ? , officialpurpose = ? WHERE travel_order_no = ? AND employee_id = ?";
						// $paramsTO = array($params['duration'], $params['location'],$params['officialpurpose'],$params['travel_order_no'],$employee_id);
						$queryTO = $this->db->query($updateTO,$update_param);
					}
					return false;
				}
			// }else{
			// 	$message = "Travel order nunber ".$params['tbltravelorder']." already exist.";
			// 	$helperDao->auditTrails(Helper::get('userid'),$message);
			// 	$this->ModelResponse($code, $message);
			// 	return true;
			// }
		}

		function fetchEmployee($employee_number){
			$sql = 'SELECT division_id, position_id, salary, id, DECRYPTER(employee_number,"sunev8clt1234567890",id) as empl_id
					FROM tblemployees
					WHERE 
					CAST(DECRYPTER(employee_number,"sunev8clt1234567890",tblemployees.id) AS INT) = CAST("'.$employee_number.'" AS INT)';
			$query = $this->db->query($sql);
			return $query;
		}		
	}
?>