<?php
	class LeaveCollection extends Helper {
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
		}
		public function hasRows($status){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tblleavemanagement WHERE leave_status='".$status."'";
			if(isset($_GET['Kind']) && $_GET['Kind'] != ""){
				$sql .= " AND leave_kind='".$_GET['Kind']."'";
			}
			$sql.= " ORDER by leave_created DESC ";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched user levels.";
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
		public function activeRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "User account level failed to activate.";
			$data['UserLevelId'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "ACTIVE";
			$userlevel_sql = "	UPDATE tblwebuserslevel SET
									status = ? 
								WHERE userlevelid = ? ";
			$userlevel_params = array($data['Status'],$data['UserLevelId']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully activated user level.";
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
		public function approveRows($params){
			//die('asdfasfsdaf');
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Pending leave failed to approve.";
			$params['leave_status'] = "APPROVED";
			$sql_data = array("APPROVED",$params['id']); 
			$sql = "	UPDATE tblleavemanagement SET
							leave_status = ? 
						WHERE leave_id = ? ";
			$this->db->query($sql,$sql_data);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully approved leave.";
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
		public function rejectRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Pending leave failed to reject.";
			$params['leave_status'] = "REJECTED";
			$sql_data = array("REJECTED",$params['id']); 
			$sql = "	UPDATE tblleavemanagement SET
							leave_status = ? 
						WHERE leave_id = ? ";
			$this->db->query($sql,$sql_data);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully rejected leave.";
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
		public function inactiveRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "User account level failed to deactivate.";
			$data['UserLevelId'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "INACTIVE";
			$userlevel_sql = "	UPDATE tblwebuserslevel SET
									status = ? 
								WHERE userlevelid = ? ";
			$userlevel_params = array($data['Status'],$data['UserLevelId']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully deactivated user level.";
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
		public function addRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Regular leave failed to insert.";
			$params['table1']['leave_type_vacation_location'] = isset($params['table1']['leave_type_vacation_location'])?"Abroad":"Domestic";
			$params['table1']['leave_type_sick_location'] = isset($params['table1']['leave_type_sick_location'])?"Hospital":"Home";
			$params['table1']['leave_commutation'] = isset($params['table1']['leave_commutation'])?"Requested":"Not Requested";
			$params['table1']['leave_kind'] = "Regular";
			$params['table1']['leave_status'] = "PENDING";
			$this->db->trans_begin();
			$this->db->insert('tblleavemanagement', $params['table1']);
			$leave_id = $this->db->insert_id();
			foreach ($params['table2']['days_of_leave'] as $k => $v) {
				$leave_days = array(
					"leave_id"=> $leave_id,
					"days_of_leave"  => $params['table2']['days_of_leave'][$k]
				);
				//var_dump($participants);die();
				$this->db->insert('tblleavemanagementdaysleave', $leave_days);
			}
			if($this->db->trans_status() === TRUE){
				$code = "0";
				$this->db->trans_commit();
				$message = "Successfully inserted Regular Leave.";
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
			//var_dump($params);die();
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Regular leave failed to update.";
			$params['table1']['leave_type_vacation_location'] = isset($params['table1']['leave_type_vacation_location'])?"Abroad":"Domestic";
			$params['table1']['leave_type_sick_location'] = isset($params['table1']['leave_type_sick_location'])?"Hospital":"Home";
			$params['table1']['leave_commutation'] = isset($params['table1']['leave_commutation'])?"Requested":"Not Requested";
			$params['table1']['leave_kind'] = "Regular";
			$params['table1']['leave_status'] = "PENDING";
			$this->db->trans_begin();
			//Update Monitoring
			$this->db->where('leave_id', $params['table1']['leave_id']);
			$this->db->update('tblleavemanagement', $params['table1']);
			//Delete participants
			$this->db->where('leave_id', $params['table1']['leave_id']);
			$this->db->delete('tblleavemanagementdaysleave');
			//Add Participants
			$leave_id = $params['table1']['leave_id'];
			foreach ($params['table2']['days_of_leave'] as $k => $v) {
				$leave_days = array(
					"leave_id"=> $leave_id,
					"days_of_leave"  => $params['table2']['days_of_leave'][$k]
				);
				//var_dump($participants);die();
				$this->db->insert('tblleavemanagementdaysleave', $leave_days);
			}
			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);		
			}	
			else {
				$code = "0";
				$this->db->trans_commit();
				$message = "Successfully updated Regular Leave.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;
			}
			return false;
		}
		//Special Leave
		public function addRowsSpecial($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Special leave failed to insert.";
			$params['table1']['leave_kind'] = "Special";
			$params['table1']['leave_status'] = "PENDING";
			$this->db->trans_begin();
			$this->db->insert('tblleavemanagement', $params['table1']);
			$leave_id = $this->db->insert_id();
			foreach ($params['table2']['days_of_leave'] as $k => $v) {
				$leave_days = array(
					"leave_id"=> $leave_id,
					"days_of_leave"  => $params['table2']['days_of_leave'][$k]
				);
				//var_dump($participants);die();
				$this->db->insert('tblleavemanagementdaysleave', $leave_days);
			}
			if($this->db->trans_status() === TRUE){
				$code = "0";
				$this->db->trans_commit();
				$message = "Successfully inserted Special Leave.";
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
		public function updateRowsSpecial($params){
			//var_dump($params);die();
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Special leave failed to update.";
			$params['table1']['leave_kind'] = "Special";
			$params['table1']['leave_status'] = "PENDING";
			$this->db->trans_begin();
			//Update Monitoring
			$this->db->where('leave_id', $params['table1']['leave_id']);
			$this->db->update('tblleavemanagement', $params['table1']);
			//Delete participants
			$this->db->where('leave_id', $params['table1']['leave_id']);
			$this->db->delete('tblleavemanagementdaysleave');
			//Add Participants
			$leave_id = $params['table1']['leave_id'];
			foreach ($params['table2']['days_of_leave'] as $k => $v) {
				$leave_days = array(
					"leave_id"=> $leave_id,
					"days_of_leave"  => $params['table2']['days_of_leave'][$k]
				);
				//var_dump($participants);die();
				$this->db->insert('tblleavemanagementdaysleave', $leave_days);
			}
			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);		
			}	
			else {
				$code = "0";
				$this->db->trans_commit();
				$message = "Successfully updated Special Leave.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;
			}
			return false;
		}
		public function fetchLeaveDates($id){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tblleavemanagementdaysleave WHERE leave_id = ?";
			$params = array('id'=>$id);
			$query = $this->db->query($sql,$id);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched leave dates.";
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
		public function fetchMonths(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tblmonths";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched months.";
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
		public function addLeaveLedger($params){
			//var_dump($params);die();
			//var_dump($params);die();
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Leave Ledger failed to insert.";
			$this->db->trans_begin();
			foreach ($params['month_code'] as $k => $v) {
				$this->db->where(array(
					'employee_id' => $params['employee_id'],
					'leave_year' => $params['leave_year'][0],
					'month_code' => $params['month_code'][$k],
				));
    			$del=$this->db->delete('tblleaveledgermonthly'); 
				$params1 = array(
					'location_id' => $params['location_id'],
					'employee_id' => $params['employee_id'],
					'leave_year' => $params['leave_year'][0],
					'month_code' => $params['month_code'][$k],
					'leave_vacation_earned' => $params['leave_vacation_earned'][$k],
					'leave_vacation_undertime_w_pay' => $params['leave_vacation_undertime_w_pay'][$k],
					'leave_vacation_balance' => $params['leave_vacation_balance'][$k],
					'leave_sick_earned' => $params['leave_sick_earned'][$k],
					'leave_sick_undertime_w_pay' => $params['leave_sick_undertime_w_pay'][$k],
					'leave_sick_balance' => $params['leave_sick_balance'][$k],
					'remarks' => $params['remarks'][$k]
				);
				$this->db->insert('tblleaveledgermonthly', $params1);
				//Delete
				$this->db->where(array(
					'employee_id' => $params['employee_id'],
					'leave_year' => $params['leave_year'][0],
					'month_code' => $params['month_code'][$k],
					'deducted_from' => 'leave',
					'deduction_type' => 'vacation'
				));
    			$this->db->delete('tblleaveledgermonthlydeduction'); 
				if($params['leave_vacation_undertime_w_pay'][$k] != "0"){
					
	    			//Insert
					$params2 = array(
						'employee_id' => $params['employee_id'],
						'leave_year' => $params['leave_year'][0],
						'month_code' => $params['month_code'][$k],
						'amount' => $params['leave_vacation_undertime_w_pay'][$k],
						'deducted_from' => 'leave',
						'deduction_type' => 'vacation'
					);
					$this->db->insert('tblleaveledgermonthlydeduction', $params2);	
				}
				//Delete
				$this->db->where(array(
					'employee_id' => $params['employee_id'],
					'leave_year' => $params['leave_year'][0],
					'month_code' => $params['month_code'][$k],
					'deducted_from' => 'leave',
					'deduction_type' => 'sick'
				));
    			$this->db->delete('tblleaveledgermonthlydeduction');
				if($params['leave_sick_undertime_w_pay'][$k] != "0"){
					
	    			//Insert
					$params3 = array(
						'employee_id' => $params['employee_id'],
						'leave_year' => $params['leave_year'][0],
						'month_code' => $params['month_code'][$k],
						'amount' => $params['leave_sick_undertime_w_pay'][$k],
						'deducted_from' => 'leave',
						'deduction_type' => 'sick'
					);
					$this->db->insert('tblleaveledgermonthlydeduction', $params3);
				}
			}
			if($this->db->trans_status() === TRUE){
				$code = "0";
				$this->db->trans_commit();
				$message = "Successfully updated Leave Ledger.";
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
		public function updateLeaveLedger($params){
			//var_dump($params);die();
			//var_dump($params);die();
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Leave Ledger failed to update.";
			$this->db->trans_begin();
			// var_dump($params);die();
			$params2['leave_year'] = $params['leave_year'][0];
			$params2['month_code'] = $params['month_code'][0];
			$params2['leave_vacation_earned'] = $params['leave_vacation_earned'][0];
			$params2['leave_vacation_undertime_w_pay'] = $params['leave_vacation_undertime_w_pay'][0];
			$params2['leave_vacation_balance'] = $params['leave_vacation_balance'][0];
			$params2['leave_sick_earned'] = $params['leave_sick_earned'][0];
			$params2['leave_sick_undertime_w_pay'] = $params['leave_sick_undertime_w_pay'][0];
			$params2['leave_sick_balance'] = $params['leave_sick_balance'][0];
			$params2['remarks'] = $params['remarks'][0];
			$this->db->where('ledger_id',$params['ledger_id']);
			$this->db->update('tblleaveledgermonthly',$params2);
			$this->db->reset_query();
			//Delete
			$this->db->where(array(
				'employee_id' => $params['employee_id'][0],
				'leave_year' => $params['leave_year'][0],
				'month_code' => $params['month_code'][0],
				'deducted_from' => 'leave',
				'deduction_type' => 'vacation'
			));
			$this->db->delete('tblleaveledgermonthlydeduction');
			$this->db->reset_query();
			if($params['leave_vacation_undertime_w_pay'][0] != "0"){
    			//Insert
				$params3 = array(
					'employee_id' => $params['employee_id'],
					'leave_year' => $params['leave_year'][0],
					'month_code' => $params['month_code'][0],
					'amount' => $params['leave_vacation_undertime_w_pay'][0],
					'deducted_from' => 'leave',
					'deduction_type' => 'vacation'
				);
				$this->db->insert('tblleaveledgermonthlydeduction', $params3);	
			}
			//Delete
			$this->db->where(array(
				'employee_id' => $params['employee_id'],
				'leave_year' => $params['leave_year'][0],
				'month_code' => $params['month_code'][0],
				'deducted_from' => 'leave',
				'deduction_type' => 'sick'
			));
			$this->db->delete('tblleaveledgermonthlydeduction');
			$this->db->reset_query();
			if($params['leave_sick_undertime_w_pay'][0] != "0"){
				
    			//Insert
				$params4 = array(
					'employee_id' => $params['employee_id'],
					'leave_year' => $params['leave_year'][0],
					'month_code' => $params['month_code'][0],
					'amount' => $params['leave_sick_undertime_w_pay'][0],
					'deducted_from' => 'leave',
					'deduction_type' => 'sick'
				);
				$this->db->insert('tblleaveledgermonthlydeduction', $params4);
			}
			if($this->db->trans_status() === TRUE){
				$code = "0";
				$this->db->trans_commit();
				$message = "Successfully inserted Leave Ledger.";
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
		public function fetchLeaveLedger($emp_id,$year){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = "SELECT a.*,
						   b.first_name,b.middle_name,b.last_name,b.position_id,c.name AS position,b.gsis,b.tin,b.employment_status,b.civil_status,
						   d.name AS month_name, 
						IFNULL((SELECT SUM(k.amount) 
							FROM tblleaveledgermonthlydeduction k WHERE k.month_code = a.month_code AND k.deduction_type = 'vacation' GROUP BY k.month_code),0) AS leave_vacation_undertime_w_pay,
						IFNULL((SELECT SUM(k.amount) 
							FROM tblleaveledgermonthlydeduction k WHERE k.month_code = a.month_code AND k.deduction_type = 'sick' GROUP BY k.month_code),0) AS leave_sick_undertime_w_pay 
					FROM tblleaveledgermonthly a 
					LEFT JOIN tblemployees b ON b.id = a.employee_id 
					LEFT JOIN tblfieldpositions c ON c.id = b.position_id 
					LEFT JOIN tblmonths d on a.month_code = d.code
					WHERE a.employee_id = ? AND a.leave_year = ?
					ORDER BY month_code ASC";
			$params = array($emp_id,$year);
			// var_dump($sql);die();
			$query = $this->db->query($sql,$params);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			// var_dump($data);die();
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched leave ledger.";
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
		public function fetchLeaveLedgerMonthly($leave_id){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tblleaveledgermonthly a LEFT JOIN tblmonths b ON a.month_code = b.code WHERE a.leave_id = ?";
			$params = array($leave_id);
			$query = $this->db->query($sql,$params);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched leave ledger monthly.";
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
		public function fetchBalanceBrought($year,$employee_id){
			//var_dump($year);die();
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tblleaveledger WHERE leave_year = ? AND employee_id = ?";

			$params = array($year,$employee_id);
			$query = $this->db->query($sql,$params);
			$rows = $query->result_array();
			$data['details'] = $rows;
			if(sizeof($rows) > 0){
				$code = "0";
				$message = "Successfully fetched leave ledger.";
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

	}
?>