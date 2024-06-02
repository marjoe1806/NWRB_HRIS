<?php
	class OvertimeTransactionsCollection extends Helper {
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
		}
		var $select_column = null;
		var $table = "tblovertimerequest";   
		// var $order_column = array('tblemployees.employee_number','tblemployees.first_name','tblfieldpositions.name','tblfielddepartments.department_name','tblemployees.salary','tblemployees.contract','');
		var $order_column = array('','tblemployees.first_name', 'tblovertimerequest.filing_date', 'tblovertimerequest.purpose', 'tblovertimerequest.activity_name', 'tblovertimerequest.time_in', 'tblovertimerequest.status', 'tblovertimerequest.remarks');
		  
		function make_query() {
			$this->select_column[] = 'tblemployees.first_name';
			$this->select_column[] = $this->table.'.filing_date';
			$this->select_column[] = $this->table.'.purpose';
			$this->select_column[] = $this->table.'.activity_name';
			$this->select_column[] = $this->table.'.transaction_date';
			$this->select_column[] = $this->table.'.status';
			$this->select_column[] = $this->table.'.remarks';
		    $this->db->select('DISTINCT (SELECT COUNT(*) FROM tblemployeesovertimeapprovers a LEFT JOIN tblemployees b ON a.employee_id = b.id where b.employment_status = "Active" AND a.is_active = 1 AND a.approver = "'.$_SESSION["id"].'" AND a.employee_id = tblovertimerequest.employee_id AND a.approve_type = 6) as approve,(SELECT COUNT(*) FROM tblemployeesovertimeapprovers a LEFT JOIN tblemployees b ON a.employee_id = b.id where b.employment_status = "Active" AND a.is_active = 1 AND a.approver = "'.$_SESSION["id"].'" AND a.employee_id = tblovertimerequest.employee_id AND a.approve_type = 7) as secondapprove,tblemployees.*,'.$this->table.'.*,tblemployees.id as salt,
		    	DECRYPTER(tblemployees.employee_id_number, "sunev8clt1234567890", tblemployees.id) as emp_id, 
				tblfieldpositions.name as position_name,
				tblfielddepartments.department_name as department_name');
		    $this->db->from($this->table);  
		    $this->db->join("tblemployees","tblemployees.id = ".$this->table.".employee_id","left");
			$this->db->join("tblfieldpositions", $this->table.".position_id = tblfieldpositions.id","left");
			$this->db->join("tblfielddepartments", $this->table.".division_id = tblfielddepartments.id","left");
			$joinsql = "tblemployeesovertimeapprovers.employee_id = tblemployees.id AND (tblemployeesovertimeapprovers.approve_type = 6 OR tblemployeesovertimeapprovers.approve_type = 7) AND tblemployeesovertimeapprovers.is_active = 1";
		//	if(isset($_POST['status']) && $_POST['status'] != '') $joinsql .= " AND tblovertimerequest.status = 'PENDING'";

			$this->db->join("tblemployeesovertimeapprovers", $joinsql,"left");
		    if(isset($_POST["search"]["value"])) {  
		    	$this->db->group_start();
		     	foreach ($this->select_column as $key => $value) {
		     		if($value == "tblemployees.first_name" || $value == "tblemployees.last_name" || $value == "tblemployees.middle_name" || $value == "tblemployees.extension")  {
		     			$this->db->or_like("DECRYPTER($value,'sunev8clt1234567890',tblemployees.id)", $_POST["search"]["value"]);  
		     		}else{
		     			$this->db->or_like($value, $_POST["search"]["value"]);  
		     		}
		     	}
		     	$this->db->or_like("CONCAT(DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.extension,'sunev8clt1234567890',tblemployees.id),', ',DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.middle_name,'sunev8clt1234567890',tblemployees.id))",$_POST["search"]["value"]);
		        $this->db->group_end(); 
			}
			// if(!Helper::role(ModuleRels::OB_VIEW_ALL_TRANSACTIONS)) {
			// 	$this->db->where('(tblemployees.division_id = '.$_SESSION["division_id"].' OR (SELECT COUNT(*) FROM tblemployeesovertimeapprovers a LEFT JOIN tblemployees b ON a.employee_id = b.id where b.employment_status = "Active" AND a.is_active = 1 AND a.approver = "'.$_SESSION["id"].'" AND a.employee_id = tblovertimerequest.employee_id AND a.approve_type = 3) > 0)');
			// }
			$this->db->where("tblemployeesovertimeapprovers.approver",$_SESSION['id']);
			if(isset($_POST['status']) && $_POST['status'] != '') $this->db->where($this->table.".status",$_POST['status']);
			if(isset($_POST["order"])) $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
			else $this->db->order_by($this->table.".transaction_date DESC");
		}
		
		function make_datatables(){ 
		    $this->make_query();  
			if(isset($_POST["length"]) && $_POST["length"] != -1) $this->db->limit($_POST['length'], $_POST['start']);
			$query = $this->db->get();
			// var_dump($this->db->last_query()); die();
		    return $query->result();  
		}

		function get_filtered_data(){  
		     $this->make_query();
		     $query = $this->db->get();  
		     return $query->num_rows();  
		}

		function get_all_data() {  
		    $this->db->select($this->table."*");  
		    $this->db->from($this->table);
		    return $this->db->count_all_results();  
		}

		public function hasRows(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = "SELECT a.*, 
						   DECRYPTER(c.employee_id_number, 'sunev8clt1234567890', a.employee_id) as emp_id, 
						   b.name as position_name,
						   d.department_name as department_name, CURRENT_TIMESTAMP as cur_timestamp
					FROM tblovertimerequest a
					LEFT JOIN tblfieldpositions b ON a.position_id = b.id
					LEFT JOIN tblemployees c ON a.employee_id = c.id
					LEFT JOIN tblfielddepartments d ON a.division_id = d.id WHERE c.id = '".Helper::get("id")."' ORDER BY a.transaction_date DESC";
			$query = $this->db->query($sql);

			$data['details']= $query->result_array();
			if(sizeof($data['details']) > 0){
				foreach ($data['details'] as $k => $v) {
					$employee = $this->getEmployeeById($v['employee_id']);
					$data['details'][$k]['employee_firstname'] = @$this->Helper->decrypt($employee[0]['first_name'], $employee[0]['id']);
					$data['details'][$k]['employee_middlename'] = @$this->Helper->decrypt($employee[0]['middle_name'], $employee[0]['id']);
					$data['details'][$k]['employee_lastname'] = @$this->Helper->decrypt($employee[0]['last_name'], $employee[0]['id']);
					$employee_number = @$this->Helper->decrypt($employee[0]['employee_number'], $employee[0]['id']);
					$dtr = $this->getDTRAdjustmentsForRecommendation($employee_number, $data['details'][$k]['transaction_date']);
					foreach ($dtr as $k2 => $v2) {
						switch ($v2['transaction_type']) {
							case '0':
								$data['details'][$k]['time_in'] 	= $v2['transaction_time'];
								break;
							case '1':
								$data['details'][$k]['time_out'] = $v2['transaction_time'];
								break;
						}
					}
				}
			}
			
			if(sizeof($data['details']) > 0){
				$code = "0";
				$message = "Successfully fetched Overtime Forms.";
				$this->ModelResponse($code, $message, $data);
				// $helperDao->auditTrails(Helper::get('userid'),$message);
				return true;
			}
			else {
				$this->ModelResponse($code, $message);
				// $helperDao->auditTrails(Helper::get('userid'),$message);
			}
			return false;
		}

		public function hasRowsActive(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tblovertimerequest where is_active = '1'";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched positions.";
				$this->ModelResponse($code, $message, $data);
				return true;
			}
			else {
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		public function approveRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Overtime request failed to approve.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Transaction_Date'] 	= isset($params['transaction_date'])?$params['transaction_date']:'';
			$data['Employee_Number'] 	= isset($params['employee_id'])?$this->getScanningNumber($params['employee_id']):'';
			$data['Is_Active'] 		= 1;
			$data['Status'] 		= "APPROVED";
			$this->db->trans_begin();
			$approver = array("request_id"=>$params['id'],"approval_type"=>3,"position"=>Helper::get("position_id"),"employee_id"=>Helper::get("employee_id"),"approved_by"=>Helper::get("userid"),"name"=>Helper::get("first_name") ." ". Helper::get("middle_name") ." ". Helper::get("last_name"),"remarks"=>"");
			$this->db->insert("tblotapprovals",$approver);

			$overtimeslip = "UPDATE tblovertimerequest SET is_active = ?, status = ? WHERE id = ? ";
			$overtimeslipsdata = array(1,"APPROVED",(int)$data['Id']);
			$this->db->query($overtimeslip,$overtimeslipsdata);
				if($this->db->trans_status() === TRUE){
					$this->db->trans_commit();
			
					// $approve_sql = "	UPDATE tbltimekeepingdailytimerecordadjustments SET is_active = ? WHERE employee_number = ? AND transaction_date = ?";
					// $approve_params = array($data['Is_Active'],$data['Employee_Number'],$data['Transaction_Date']);
					// $this->db->query($approve_sql,$approve_params);
					$code = "0";
					$message = "Successfully approved Overtime Request.";
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
					return true;
				} else{
					$this->db->trans_rollback();
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
				}
			return false;
		}

		function make_query_summary($employee_id, $employee_number, $payroll_period, $payroll_period_id, $shift_id) {
			$dtr = array();
			$get_record = array();
			$attendance = array();
			$no_of_days = date('t',strtotime($payroll_period));
			$payroll_date = explode("-", $payroll_period);

			for ($day=1; $day <= $no_of_days; $day++) {
				$current_day = $payroll_date[0] . '-' . $payroll_date[1] . '-' . (($day > 9) ? $day : '0'. $day);
				$time_record = $this->getDailyTimeRecordData($current_day, $employee_number);
				$get_record = $time_record['actual_data'];
				if(sizeof($get_record) > 0) {
						foreach ($get_record as $key => $value) {
							$dtr['records'][$current_day][$value['transaction_type']] = $value;
						}
				}
				if(sizeof($time_record['adjustments']) > 0){
					$get_record = $time_record['adjustments'];
					if(sizeof($get_record) > 0) {
						foreach ($get_record as $key => $value) {
							$dtr['adjustments'][$current_day][$value['transaction_type']] = $value;
						}
					}
				}

			}
			$dtr['employee'] = $this->getEmployeeById($employee_id);
			// $dtr['details'] = $this->getDepartmentById($dtr['employee'][0]['office_id']);
			$dtr['payroll_period'] = $payroll_period;
			return $dtr;

		}

		function getDailyTimeRecordDataAdjustments($date, $employee_number) {
			$attendance = array();
			$params = array($date, $employee_number);
			$sql = "SELECT * FROM tbltimekeepingdailytimerecordadjustments WHERE transaction_date = ? AND employee_number = ?";
			$query = $this->db->query($sql, $params);
			$attendance = $query->result_array();
			return $attendance;
		}

		function getDailyTimeRecordData($date, $employee_number) {
			$attendance = array();
			$adjustments = $this->getDailyTimeRecordDataAdjustments($date, $employee_number);
			$attendance['adjustments'] = $adjustments;
			$params = array($date, $employee_number);
			$sql = "SELECT * FROM tbltimekeepingdailytimerecord  WHERE transaction_date = ? AND employee_number = ?";
			$query = $this->db->query($sql, $params);
			$attendance['actual_data'] = $query->result_array();
			return $attendance;
		}

		public function rejectRows($params){
			// var_dump($params['remarks']); die();	
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Overtime request failed to reject.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Is_Active'] 		= "0";
			$data['Remarks'] 		= $params['remarks'];
			$data['Status'] 		= "REJECTED";
			$userlevel_sql = "	UPDATE tblovertimerequest SET is_active = ?, status = ?, reject_remarks = ? WHERE id = ? ";
			$userlevel_params = array($data['Is_Active'],$data['Status'], $data['Remarks'], $data['Id']);
			// $sql = "UPDATE tbltimekeepingdailytimerecordadjustments SET is_active = ? W"
			$this->db->query($userlevel_sql,$userlevel_params);
			// var_dump($data['Id']); die();
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully disapproved Overtime Request.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;
			}
			else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		public function AssignToComplete($params){
			// print_r($params); die();	
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Overtime request failed to reject.";

			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Is_Active'] 		= "1";
			$data['Status'] 		= "COMPLETED";
			$userlevel_sql = "	UPDATE tblovertimerequest SET is_active = ?, status = ? WHERE id = ? ";
			$userlevel_params = array($data['Is_Active'],$data['Status'], $data['Id']);
			// $sql = "UPDATE tbltimekeepingdailytimerecordadjustments SET is_active = ? W"
			$this->db->query($userlevel_sql,$userlevel_params);
			// var_dump($data['Id']); die();
			if($this->db->trans_status() === TRUE){
				$this->db->trans_commit();
				$code = "0";
				$message = "Successfully complete Overtime Request.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);

			}
			else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		function getLocationById($location_id) {
			$params = array($location_id);
			$sql = "SELECT * FROM tblfieldlocations WHERE id = ? AND is_active = 1";
			$query = $this->db->query($sql, $params);
			$location_id = $query->result_array();
			return @$location_id[0];
		}

		function getEmployeeById($employee_id) {
			$params = array($employee_id);
			$sql = "SELECT * FROM tblemployees WHERE id = ? AND is_active = 1";
			$query = $this->db->query($sql, $params);
			$employee = $query->result_array();
			return $employee;
		}

		function getScanningNumber($employee_id) {
			$params = array($employee_id);
			$sql = "SELECT employee_number FROM tblemployees WHERE id = ? AND is_active = 1";
			$query = $this->db->query($sql, $params);
			$employee = $query->result_array();
			return @$this->Helper->decrypt($employee[0]['employee_number'], $employee_id);
		}

		function getDailyTimeRecord($employee_number, $date) {
			$params = array($employee_number, $date);
			$sql = "SELECT * FROM tbltimekeepingdailytimerecord WHERE employee_number = ? AND transaction_date = ? AND is_active = 1";
			$query = $this->db->query($sql, $params);
			$employee = $query->result_array();
			return $employee;
		}

		function getDTRAdjustmentsForRecommendation($employee_number, $date) {
			$params = array($employee_number, $date);
			$sql = "SELECT * FROM tbltimekeepingdailytimerecordadjustments WHERE employee_number = ? AND transaction_date = ? AND is_active = 0";
			$query = $this->db->query($sql, $params);
			$employee = $query->result_array();
			return $employee;
		}

		public function addRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Official Business request failed to insert.";
			$this->db->trans_begin();
			$overtime_slip = array(
				'purpose'						=> $params['purpose'],
				'filename'					=> $params['file_name'],
				'filesize'					=> $params['file_size'],
				'employee_id' 			=> $params['employee_id'],
				'division_id' 			=> $params['division_id'],
				'location' 					=> @$params['location'],
				'checked_by' 				=> $_SESSION['last_name'] . ', ' . $_SESSION['first_name'],
				'received_by' 			=> $_SESSION['employee_id'],
				'transaction_date' 	=> $params['transaction_date'],
				'remarks' 	=> $params['remarks']
			);

			$this->db->where('transaction_date', $params['transaction_date']);
			$this->db->where('employee_id', $params['employee_id']);
			$this->db->delete('tblovertimerequest');
			
			if($this->db->insert('tblovertimerequest', $overtime_slip) > 0) {
				$this->db->where('transaction_date', $params['transaction_date']);
				$this->db->where('employee_number', $params['employee_number']);
				$this->db->delete('tbltimekeepingdailytimerecordadjustments');

				foreach ($params['overtime_transaction_time'] as $k => $v) {
					$params2 = array();
					$params2['remarks'] = $params['remarks'];
					$params2['source_device'] = 'manual input';
					$params2['source_location'] = 'manual input';
					$params2['modified_by'] = Helper::get('userid');
					$params2['transaction_date'] = $params['transaction_date'];
					$params2['transaction_type'] = $params['overtime_transaction_type'][$k];
					$params2['transaction_time'] = $params['overtime_transaction_time'][$k] != "" ? $params['overtime_transaction_time'][$k] : null;
					$params2['employee_number'] = $this->getScanningNumber($params['employee_id']);
					$this->db->insert('tbltimekeepingdailytimerecordadjustments',$params2);
				}
			}
			if($this->db->trans_status() === TRUE){
				$code = "0";
				$this->db->trans_commit();
				$message = "Official Business Form successfully inserted.";
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

		public function updateRows($params){
			// var_dump($params); die();
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Overtime request failed to update.";
			$this->db->trans_begin();
			switch($params['purpose']) {
				case "paid" :	$purpose = "With PAY";
									break;
				case "cto" :	$purpose = "For CTO";
									break;
				default : 		$purpose = "Overtime Request";
									break;
			}

			if(@$params['location'] != "") $purpose = $purpose." : ".$params['location'];
			$overtime_slip = array(
				'purpose'						=>$params['purpose'],
				'location' 					=> $params['location'],
				'checked_by' 				=> $_SESSION['last_name'] . ', ' . $_SESSION['first_name'],
				'received_by' 			=> $_SESSION['employee_id'],
				'employee_id' 			=> $params['employee_id'],
				'location_id' 			=> $params['location_id'],
				'division_id' 			=> $params['division_id'],
				'transaction_date' 	=> $params['transaction_date'],
				'is_active'				=> $params['is_active']
			);
			$this->db->where('id', $params['id']);
			if ($this->db->update('tblovertimerequest',$overtime_slip) === FALSE){
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			else {
					$this->db->where('transaction_date', $params['transaction_date']);
					$this->db->delete('tbltimekeepingdailytimerecordadjustments');
					
						$params2 = array();
						$params2['remarks'] = 'overtime slip';
						$params2['source_device'] = 'manual input';
						$params2['source_location'] = 'manual input';
						$params2['modified_by'] = Helper::get('userid');
						$params2['transaction_date'] = $params['transaction_date'];
						$params2['transaction_time'] = $params['overtime_transaction_time'] != "" ? $params['overtime_transaction_time'] : null;
						$params2['employee_number'] = $this->getScanningNumber($params['employee_id']);
						$this->db->insert('tbltimekeepingdailytimerecordadjustments',$params2);
				if($this->db->trans_status() === TRUE){
					$code = "0";
					$this->db->trans_commit();
					$message = "Overtime request successfully updated.";
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
			return false;
		}

		public function fetchApprovals($id){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$this->sql = "CALL sp_get_employee_overtime_approvals(?)";
			$query = $this->db->query($this->sql,array($id));
			$result = $query->result();
			mysqli_next_result($this->db->conn_id);
			$data['details'] = $result;
			if(sizeof($result) > 0){
				$code = "0";
				$message = "Successfully fetched overtime approvals.";
				$this->ModelResponse($code, $message, $data);	
				$helperDao->auditTrails(Helper::get('userid'),$message);	
				return true;		
			}	
			else {
				$this->ModelResponse($code, $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
			}
			return false;
		}

		function getEmpNum($employee_id){
			$sql = 'SELECT DECRYPTER(employee_number,"sunev8clt1234567890",id) as empl_id
					FROM tblemployees
					WHERE id = "'.$employee_id.'"';
					
			$query = $this->db->query($sql);

			return $query;

		}

	}
?>