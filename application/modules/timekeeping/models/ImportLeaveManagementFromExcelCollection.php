<?php
	class ImportLeaveManagementFromExcelCollection extends Helper {
		public function __construct() {
			$this->load->model('HelperDao');
			$this->load->model("Security");
			ModelResponse::busy();
		}

		public function leaveDays(){
			
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Failed to import leave days.";
			$sqldtr = 'SELECT id,inclusive_dates,type,status
					FROM tblleavemanagement where status = 5 AND id >= 198 AND id <= 301';
			$leaves = $this->db->query($sqldtr)->result_array();

			$this->db->trans_begin();
			foreach($leaves as $k => $v){
				$days = explode(', ', $v['inclusive_dates']);
				for($i = 0; $i < sizeof($days);$i++){
					$this->db->insert('tblleavemanagementdaysleave', array("id" => $v['id'],  "days_of_leave" => $days[$i]));
				}
			}
			if($this->db->trans_status() === TRUE){
				$code = "0";
				$this->db->trans_commit();
				$message = "Timelogs successfully import leave days.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;
			}
			else {
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		public function addRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Failed to import timelogs from excel.";

			
			$query = $this->fetchEmployee($params['table-1']['employee_number']);
			if ($query->num_rows() == 0 ) { 
				$message = "Employee with scanning number doesn't exist.";
				$this->ModelResponse($code, $message);
				return false;
			} else {
				unset($params['table-1']["employee_number"]);
				
				$params['table-1']['employee_id'] = $params['table-2']['employee_id'] = $query->row()->id;
				$params['table-1']['position_id'] = $params['table-2']['position'] = $query->row()->position_id;
				$params['table-1']['division_id'] = $query->row()->division_id;
				$params['table-1']['salary'] = $query->row()->salary;
				if($params['table-1']['type'] == "monetization")
					$params['table-1']['amount_monetized'] = round($params['table-1']['salary'] * $params['table-1']['number_of_days'] * .0481927,2);

				$isExist_query = $this->validateLeaves($params['table-1']);
				
				$this->db->trans_begin();
				if($isExist_query->num_rows() > 0){
					$request_id = $isExist_query->row()->id;
					$this->db->where('id', $request_id)->delete('tblleavemanagement');
					$this->db->where('request_id', $request_id)->delete('tblleavemanagementapprovals');
				}

				$adjustment_remarks = "LEAVE";
				if($params['table-1']['type'] == 'offset') $adjustment_remarks = "LEAVE";
				$days = explode(', ', $params['table-1']['inclusive_dates']);

				if(strpos($params['table-1']['inclusive_dates'], ' - ') !== false) {

					  for ($i=0; $i <count($days) ; $i++) { 
						  
					  	$dtrdate = date("Y-m-d", strtotime($days[0].' + '.$i.' day'));
						$sqldtr = 'SELECT *
						FROM tbldtr 
						WHERE scanning_no = "'.(int)$query->row()->empl_id.'"
								  AND transaction_date = "'.$dtrdate.'"';
						$querydtr = $this->db->query($sqldtr);
						if($querydtr->num_rows() > 0){
						$leavetype = ucfirst($params['table-1']['type'])." Leave";	
						$dtr_sql = "UPDATE tbldtr SET adjustment_remarks = ? , tardiness_mins = 0 , tardiness_hrs = 0 , ut_mins = 0 , ut_hrs = 0 WHERE scanning_no = ? AND transaction_date = ?";
						$dtrparams = array($leavetype, (int)$query->row()->empl_id, $querydtr->row()->transaction_date);
						$this->db->query($dtr_sql,$dtrparams);	
							
						}else{
							$leavetype = ucfirst($params['table-1']['type'])." Leave";	
							$this->db->insert('tbldtr', array("adjustment_remarks" => $leavetype,  "transaction_date" => $dtrdate, "scanning_no" => (int)$query->row()->empl_id));
						
						}
					  }
				} else {
				 
				  $dtrdate = date("Y-m-d", strtotime($params['table-1']['inclusive_dates']));

				  $sqldtr = 'SELECT *
						FROM tbldtr 
						WHERE scanning_no = "'.(int)$query->row()->empl_id.'"
							  AND transaction_date = "'.$dtrdate.'"';
					$querydtr = $this->db->query($sqldtr);
					if($querydtr->num_rows() > 0){
						$leavetype = ucfirst($params['table-1']['type'])." Leave";	
						$dtr_sql = "UPDATE tbldtr SET adjustment_remarks = ? , tardiness_mins = 0 , tardiness_hrs = 0 , ut_mins = 0 , ut_hrs = 0 WHERE scanning_no = ? AND transaction_date = ?";
						$dtrparams = array($leavetype , (int)$query->row()->empl_id, $querydtr->row()->transaction_date);
						$this->db->query($dtr_sql,$dtrparams);	
						
						
					}else{
						$leavetype = ucfirst($params['table-1']['type'])." Leave";	
						$this->db->insert('tbldtr', array("adjustment_remarks" => $leavetype ,  "transaction_date" => $dtrdate, "scanning_no" => (int)$query->row()->empl_id));
						
					}
				}

				// $sqldtr = 'SELECT *
				// 	FROM tbldtr 
				// 	WHERE scanning_no = "'.(int)$query->row()->empl_id.'"
				// 		  AND transaction_date = "'.$params['table-1']['inclusive_dates'].'"';
				// $querydtr = $this->db->query($sqldtr);
				// if($querydtr->num_rows() > 0){
					
				// 	$dtr_sql = "UPDATE tbldtr SET adjustment_remarks = ? AND tardiness_mins = 0 AND tardiness_hrs = 0 AND ut_mins = 0 AND ut_hrs = 0 WHERE scanning_no = ?  AND transaction_date = ?";
				// 	$dtrparams = array('LEAVE', (int)$query->row()->empl_id, $querydtr->row()->transaction_date);
				// 	$this->db->query($dtr_sql,$dtrparams);	
				// 	var_dump("meron existing"); 
				// }else{
				// 	$this->db->insert('tbldtr', array("adjustment_remarks" => "LEAVE",  "transaction_date" => $params['table-1']['inclusive_dates'], "scanning_no" => (int)$query->row()->empl_id));
				// 	var_dump("pumasok yung bago");
				// }


				// var_dump($this->db->insert('tblleavemanagement',$params['table-1']));
				// die();
				$this->db->insert('tblleavemanagement',$params['table-1']);
				$params['table-2']['request_id'] = $this->db->insert_id();
				$days2 = explode(', ', $params['table-1']['inclusive_dates']);
				for($i = 0; $i < sizeof($days2);$i++){
					$this->db->insert('tblleavemanagementdaysleave', array("id" => $params['table-2']['request_id'],  "days_of_leave" => $days2[$i]));
				}
				// $this->db->insert('tblleavemanagementdaysleave',$params['table-3']);
				// var_dump($params['table-2']); die();
				$this->db->insert('tblleavemanagementapprovals',$params['table-2']);
				// var_dump($this->db->insert_id()); die();


				if($this->db->trans_status() === TRUE){
					$code = "0";
					$this->db->trans_commit();
					$message = "Timelogs successfully imported from excel.";
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
					return true;
				}
				else {
					$this->db->trans_rollback();
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
				}
				return false;
			}
		}

		function fetchEmployee($employee_number){
			$sql = 'SELECT division_id, position_id, salary, id, DECRYPTER(employee_number,"sunev8clt1234567890",id) as empl_id
					FROM tblemployees
					WHERE 
					CAST(DECRYPTER(employee_number,"sunev8clt1234567890",tblemployees.id) AS INT) = CAST("'.$employee_number.'" AS INT)';
			$query = $this->db->query($sql);

			return $query;

		}
		function validateLeaves($params){
			$sql = 'SELECT id 
					FROM tblleavemanagement 
					WHERE employee_id = "'.$params['employee_id'].'"
						  AND source_location = "excel_import" 
						  AND source_device != "'.$params['source_device'].'"
						  AND date_filed = "'.$params['date_filed'].'"
						  AND isMedical = "'.$params['isMedical'].'"
						  AND inclusive_dates = "'.$params['inclusive_dates'].'"';
			$query = $this->db->query($sql);
			return $query;

		}

		
	}
?>