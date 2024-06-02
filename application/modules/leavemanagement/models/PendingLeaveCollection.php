<?php
	class PendingLeaveCollection extends Helper {
		var $select_column = null; 
		var $sql = "";
		var $selectRequestParams = array();
		public function __construct() {
			$this->load->model('Helper');
			$this->load->model('HelperDao');
			ModelResponse::busy();
			$columns = $this->getColumns();
			foreach ($columns as $key => $value)
				$this->select_column[] = $this->table.'.'.$value->COLUMN_NAME;
		}

		//Fetch
		var $table = "tblleavemanagement";   
		var $order_column = array('','last_name','date_filed','type','offset_date_effectivity','status_name','remarks');
		var $order_column2 = array('','last_name','filing_date','type','inclusive_dates','description','remarks','');
		public function getColumns(){		
			$this->sql = "CALL sp_get_leave_mngt_columns()";
			$query = $this->db->query($this->sql);
			$result = $query->result();
			mysqli_next_result($this->db->conn_id);
			return $result; 
		}

		function make_query(){
			$this->selectRequestParams = array(
				(isset($_POST["LeaveType"]))?@$_POST["LeaveType"]:"",
				(isset($_POST["Status"]))?@$_POST["Status"]:"",
				@$_POST["search"]["value"],
				"",
				0,
				(isset($_POST['order']))?1:"",
				(isset($_POST['order']['0']['column']) && isset($this->order_column[$_POST['order']['0']['column']]))?$this->order_column[$_POST['order']['0']['column']]:"",
				(isset($_POST['order']['0']['dir']))?$_POST['order']['0']['dir']:"",
				@$_POST['length'],
				@$_POST['start'],
				(!Helper::role(ModuleRels::LEAVE_TRACKING_ALL_ACCESS))?Helper::get('leave_grouping_id'):"",
				(!Helper::role(ModuleRels::LEAVE_TRACKING_ALL_ACCESS))?1:0,
				(isset($_POST['Id']) && $_POST['Id'] != null)?$_POST['Id']:"",
				(isset($_POST['Id']) && $_POST['Id'] != null)?1:0,
				(Helper::role(ModuleRels::LEAVE_VIEW_ALL_TRANSACTIONS)) ? 1:0,
				$_SESSION["division_id"],
				$_SESSION["id"],
				Helper::get('userlevelid') == 43 ? 1 : 0,
				in_array(Helper::get('userlevelid'), array(38,45)) ? 1 : 0,
				Helper::get('userlevelid') == 46 ? 1 : 0

			);
			if($_SESSION['position_id'] != 363){
				$this->sql = "CALL sp_get_employee_leave_requests(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
			} else {
				$this->sql = "CALL sp_get_employee_disapproved_leave_requests(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
			}
		}

		function make_datatables(){
			$this->make_query();
			$query = $this->db->query($this->sql,$this->selectRequestParams);
			$result = $query->result();
			mysqli_next_result($this->db->conn_id);
		    return $result;
		}

		function hasRows(){
			$code = "1";
			$message = "No data available.";
			$this->make_query();
			$query = $this->db->query($this->sql,$this->selectRequestParams);
			$result = $query->result();
			mysqli_next_result($this->db->conn_id);
		    if(sizeof($result) > 0){
				$code = "0";
				$message = "Successfully fetched Other Deduction.";
				$data['details'] = $result;
				$this->ModelResponse($code, $message, $data);
				return true;		
			}	
			else $this->ModelResponse($code, $message);
			return false;
		}

		function get_filtered_data(){  
		     $this->make_query();
			 $this->selectRequestParams = array(
				(isset($_POST["LeaveType"]))?@$_POST["LeaveType"]:"",
				(isset($_POST["Status"]))?@$_POST["Status"]:"",
				 @$_POST["search"]["value"],
				 "",
				 0,
				 (isset($_POST['order']))?1:"",
				 (isset($_POST['order']['0']['column']) && isset($this->order_column[$_POST['order']['0']['column']]))?$this->order_column[$_POST['order']['0']['column']]:"",
				 (isset($_POST['order']['0']['dir']))?$_POST['order']['0']['dir']:"",
				 "",
				 "",
				 (!Helper::role(ModuleRels::LEAVE_TRACKING_ALL_ACCESS))?Helper::get('leave_grouping_id'):"",
				 (!Helper::role(ModuleRels::LEAVE_TRACKING_ALL_ACCESS))?1:0,
				 (isset($_POST['Id']) && $_POST['Id'] != null)?$_POST['Id']:"",
				 (isset($_POST['Id']) && $_POST['Id'] != null)?1:0,
				 (Helper::role(ModuleRels::LEAVE_VIEW_ALL_TRANSACTIONS)) ? 1:0,
				 $_SESSION["division_id"],
				 $_SESSION["id"],
				 Helper::get('userlevelid') == 43 ? 1 : 0,
				 in_array(Helper::get('userlevelid'), array(38,45)) ? 1 : 0,
				 Helper::get('userlevelid') == 46 ? 1 : 0
			 );
			 $query = $this->db->query($this->sql,$this->selectRequestParams);
			 $result = $query->result();
			 mysqli_next_result($this->db->conn_id);
		     return sizeof($result);  
		}   

		function get_all_data(){

			$this->db->select('*');
			$query = $this->db->get($this->table);
			$total = $this->db->count_all($this->table);
			// $this->db->select($this->table."*");  
			// $this->db->from($this->table);
			// var_dump();
		    return $total;

		}  
		//End Fetch

		public function activeRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Leave failed to activate.";
			$data['id'] 	= isset($params['id'])?$params['id']:'';
			$data['is_active'] = "1";

			$this->db->trans_begin();
			$userlevel_sql = "	UPDATE tblleavemanagement SET
									is_active = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['is_active'],$data['id']);
			$this->db->query($userlevel_sql,$userlevel_params);

			$userlevel_sql = "	UPDATE tbltimekeepingdailytimerecordadjustments SET
									is_active = ? 
								WHERE leave_id = ? ";
			$userlevel_params = array($data['is_active'],$data['id']);
			$this->db->query($userlevel_sql,$userlevel_params);

			if($this->db->trans_status() === TRUE){
				$code = "0";
				$this->db->trans_commit();
				$message = "Successfully Activated Leave.";
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

		public function inactiveRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Leave failed to deactivate.";
			$data['id'] 	= isset($params['id'])?$params['id']:'';
			$data['is_active'] = "0";

			$this->db->trans_begin();
			$userlevel_sql = "	UPDATE tblleavemanagement SET
									is_active = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['is_active'],$data['id']);
			$this->db->query($userlevel_sql,$userlevel_params);

			$userlevel_sql = "	UPDATE tbltimekeepingdailytimerecordadjustments SET
									is_active = ? 
								WHERE leave_id = ? ";
			$userlevel_params = array($data['is_active'],$data['id']);
			$this->db->query($userlevel_sql,$userlevel_params);

			if($this->db->trans_status() === TRUE){
				$code = "0";
				$this->db->trans_commit();
				$message = "Successfully Deactivated Leave.";
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

		public function certifyRows($params){
			// var_dump(json_encode($params)); die();
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Pending leave failed to certify.";
			$this->db->trans_begin();

			$is_rec = " SELECT a.employee_id, b.position_id, c.is_leave_recom as recom FROM tblleavemanagement a
					LEFT JOIN tblemployees b ON a.employee_id = b.id LEFT JOIN tblfieldpositions c ON b.position_id = c.id WHERE a.id = ?";
			$is_rec = $this->db->query($is_rec,array($params['id']))->row_array();
			$sql_data = array();
			if($is_rec["recom"] == 1) {
				$approver = array(
					"request_id"=>$params['id'],
					"approval_type"=>1,
					"position"=>Helper::get("position_id"),
					"employee_id"=>Helper::get("employee_id"),
					"approved_by"=>Helper::get("userid"),
					"name"=>Helper::get("first_name") ." ". substr(Helper::get("middle_name"), 0, 1) .". ". Helper::get("last_name"),
					"remarks"=>"",
					"file_name"=>$params['file_name'],
					"file_size"=>$params['file_size']
				);
				$this->db->insert("tblleavemanagementapprovals",$approver);
				$approver = array(
					"request_id"=>$params['id'],
					"approval_type"=>2,
					"position"=>"",
					"employee_id"=>"",
					"approved_by"=>"",
					"name"=>"",
					"remarks"=>"",
					"file_name"=>"",
					"file_size"=>""
				);
				$this->db->insert("tblleavemanagementapprovals",$approver);
				$sql_data = array(3,$params['id']);
			}else {
				$approver = array(
					"request_id"=>$params['id'],
					"approval_type"=>1,
					"position"=>Helper::get("position_id"),
					"employee_id"=>Helper::get("employee_id"),
					"approved_by"=>Helper::get("userid"),
					"name"=>Helper::get("first_name") ." ". substr(Helper::get("middle_name"), 0, 1) .". ". Helper::get("last_name"),
					"remarks"=>"",
					"file_name"=>$params['file_name'],
					"file_size"=>$params['file_size']
				);
				$this->db->insert("tblleavemanagementapprovals",$approver);
				$sql_data = array(2,$params['id']);
			}
			$sql = "UPDATE tblleavemanagement SET status = ? WHERE id = ? ";
			$this->db->query($sql,$sql_data);
				if($this->db->trans_status() === TRUE){
					$this->db->trans_commit();
					$code = "0";
					$message = "Successfully certified leave.";
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

		public function recommendRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Pending leave failed to recommend.";
			$approver = array(
				"request_id"=>$params['id'],
				"approval_type"=>2,
				"position"=>Helper::get("position_id"),
				"employee_id"=>Helper::get("employee_id"),
				"approved_by"=>Helper::get("userid"),
				"name"=>Helper::get("first_name") ." ". substr(Helper::get("middle_name"), 0, 1) .". ". Helper::get("last_name"),"remarks"=>"",
				// "file_name"=>$params['file_name'],
				// "file_size"=>$params['file_size']
			);
			$this->db->insert("tblleavemanagementapprovals",$approver);
			$sql_data = array(3,$params['id']); 
			$sql = "UPDATE tblleavemanagement SET status = ? WHERE id = ? ";
			$this->db->query($sql,$sql_data);
			if($this->db->trans_status() === TRUE){
				$this->db->trans_commit();
				$code = "0";
				$message = "Successfully recommended leave.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;		
			} else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		public function recommendRowsHead($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Pending leave failed to recommend.";
			$approver = array(
				"request_id"=>$params['id'],
				"approval_type"=>3,
				"position"=>Helper::get("position_id"),
				"employee_id"=>Helper::get("employee_id"),
				"approved_by"=>Helper::get("userid"),
				"name"=>Helper::get("first_name") ." ". substr(Helper::get("middle_name"), 0, 1) .". ". Helper::get("last_name"),
				"remarks"=>"",
				"file_name"=>$params['file_name'],
				"file_size"=>$params['file_size']
			);
			$this->db->insert("tblleavemanagementapprovals",$approver);
			$sql_data = array(4,$params['id']); 
			$sql = "UPDATE tblleavemanagement SET status = ? WHERE id = ? ";
			$this->db->query($sql,$sql_data);
			if($this->db->trans_status() === TRUE){
				$this->db->trans_commit();
				$code = "0";
				$message = "Successfully recommended leave.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;		
			} else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		public function approveRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Pending leave failed to approve.";
			$params['status'] = "APPROVED";
			$approver = array(
				"request_id"=>$params['id'],
				"approval_type"=>4,
				"position"=>Helper::get("position_id"),
				"employee_id"=>Helper::get("employee_id"),
				"approved_by"=>Helper::get("userid"),
				"name"=>Helper::get("first_name") ." ". substr(Helper::get("middle_name"), 0, 1) .". ". Helper::get("last_name"),
				"remarks"=>"",
				"file_name"=>$params['file_name'],
				"file_size"=>$params['file_size']
			);
			$this->db->insert("tblleavemanagementapprovals",$approver);
			
			$sql = "UPDATE tblleavemanagement SET status = ?, is_active = ? WHERE id = ? ";
			$sql_data = array(5,"1",$params['id']); 
			$this->db->query($sql,$sql_data);

			$approve_dts_sql = "UPDATE tbltimekeepingdailytimerecordadjustments SET is_active = ? WHERE leave_id = ? ";
			$approve_dts_data = array("1",$params['id']);
			$this->db->query($approve_dts_sql,$approve_dts_data);

			$types = ['vacation','force','sick','monetization'];

			// if(in_array($params['type'],$types)){
			// 	$select = $this->db->select("sl,vl,total")->from("tblleavebalance")->where("id",$params['employee_id'])->get()->row_array();
			// 	$updateleavebal = "UPDATE tblleavebalance SET " . (($params['type'] == "sick")?"sl = ?":"vl = ?") . " AND total = ? WHERE id = ? ";
			// 	$updateleavebal_data = array((($params['type'] == "sick")?$select["sl"]:$select["vl"])-$params['nodays'],$select["total"]-$params['nodays'],$params['employee_id']);
			// 	$this->db->query($updateleavebal,$updateleavebal_data);
			// }

			if($this->db->trans_status() === TRUE){
				$this->db->trans_commit();
				$code = "0";
				$message = "Successfully approve leave.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);

				$transac_date1 = "";
				$transac_date2 = "";
				if (strpos($params['inclusive_dates'], ', ') !== false ){
					$transac_date = explode(', ', $params['inclusive_dates']);
					sort($transac_date);
					$transac_date1 = $transac_date[0];
					$transac_date2 = $transac_date[1];
				}else if(strpos($params['inclusive_dates'], ' - ') !== false ){
					$transac_date = explode(' - ', $params['inclusive_dates']);
					$transac_date1 = $transac_date[0];
					$transac_date2 = $transac_date[1];
				}
				
				if ($transac_date1 == $transac_date2){
					$emp_num = $this->getEmpNum($params['employee_id']);
					$dtrdate = date("Y-m-d", strtotime($transac_date1));

					$sqldtr = 'SELECT *
						FROM tbldtr 
						WHERE scanning_no = "'.(int)$emp_num->row()->empl_id.'"
								  AND transaction_date = "'.$dtrdate.'"';
						$querydtr = $this->db->query($sqldtr);
						if($querydtr->num_rows() > 0){
							
							$dtr_sql = "UPDATE tbldtr SET source = ? , adjustment_remarks = ? , tardiness_mins = 0 , tardiness_hrs = 0 , ut_mins = 0 , ut_hrs = 0 WHERE scanning_no = ? AND transaction_date = ?";
							$dtrparams = array('manual',''.ucfirst($params['type']).' Leave', (int)$emp_num->row()->empl_id, $querydtr->row()->transaction_date);
							$this->db->query($dtr_sql,$dtrparams);	
							
						}else{
							$this->db->insert('tbldtr', array("source" => 'manual',"adjustment_remarks" => ''.ucfirst($params['type']).' Leave',  "transaction_date" => $dtrdate, "scanning_no" => (int)$emp_num->row()->empl_id));
						}	
				}else{
					$dates = explode(', ', $params['inclusive_dates_original']);
					for ($i=0; $i < sizeof($dates) ; $i++) { 
						$emp_num = $this->getEmpNum($params['employee_id']);
						$dtrdate = date("Y-m-d", strtotime($dates[$i]));
						
						$sqldtr = 'SELECT *
						FROM tbldtr 
						WHERE scanning_no = "'.(int)$emp_num->row()->empl_id.'"
								  AND transaction_date = "'.$dtrdate.'"';
						$querydtr = $this->db->query($sqldtr);
						if($querydtr->num_rows() > 0){
							
							$dtr_sql = "UPDATE tbldtr SET source = ? , adjustment_remarks = ? , tardiness_mins = 0 , tardiness_hrs = 0 , ut_mins = 0 , ut_hrs = 0 WHERE scanning_no = ? AND transaction_date = ?";
							$dtrparams = array('manual',''.ucfirst($params['type']).' Leave', (int)$emp_num->row()->empl_id, $querydtr->row()->transaction_date);
							$this->db->query($dtr_sql,$dtrparams);	
							
						}else{
							$this->db->insert('tbldtr', array("source" => 'manual',"adjustment_remarks" => ''.ucfirst($params['type']).' Leave',  "transaction_date" => $dtrdate, "scanning_no" => (int)$emp_num->row()->empl_id));
						}
					}
					// $no_of_days = (int)$params['nodays'];
					// for ($i=0; $i < $no_of_days ; $i++) { 
					// 	$emp_num = $this->getEmpNum($params['employee_id']);
					// 	$dtrdate = date("Y-m-d", strtotime($transac_date1.' + '.$i.' day'));
						
					// 	$sqldtr = 'SELECT *
					// 	FROM tbldtr 
					// 	WHERE scanning_no = "'.(int)$emp_num->row()->empl_id.'"
					// 			  AND transaction_date = "'.$dtrdate.'"';
					// 	$querydtr = $this->db->query($sqldtr);
					// 	if($querydtr->num_rows() > 0){
							
					// 		$dtr_sql = "UPDATE tbldtr SET source = ? , adjustment_remarks = ? , tardiness_mins = 0 , tardiness_hrs = 0 , ut_mins = 0 , ut_hrs = 0 WHERE scanning_no = ? AND transaction_date = ?";
					// 		$dtrparams = array('manual',''.ucfirst($params['type']).' Leave', (int)$emp_num->row()->empl_id, $querydtr->row()->transaction_date);
					// 		$this->db->query($dtr_sql,$dtrparams);	
							
					// 	}else{
					// 		$this->db->insert('tbldtr', array("source" => 'manual',"adjustment_remarks" => ''.ucfirst($params['type']).' Leave',  "transaction_date" => $dtrdate, "scanning_no" => (int)$emp_num->row()->empl_id));
					// 	}
					// }
				}

				return true;
			} else{
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		public function rejectRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Pending leave failed to reject.";
			$approver = array(
				"request_id"=>$params['id'],
				"approval_type"=>6,
				"position"=>Helper::get("position_id"),
				"employee_id"=>Helper::get("employee_id"),
				"approved_by"=>Helper::get("userid"),
				"name"=>Helper::get("first_name") ." ". substr(Helper::get("middle_name"), 0, 1) .". ". Helper::get("last_name"),
				"remarks"=>$params["remarks"],
				"file_name"=>$params['file_name'],
				"file_size"=>$params['file_size']
			);
			$this->db->insert("tblleavemanagementapprovals",$approver);
			$sql_data = array(6,"0",$params['remarks'],$params['id']); 
			$sql = "UPDATE tblleavemanagement SET status = ?, is_active = ?, remarks = ? WHERE id = ? ";
			$this->db->query($sql,$sql_data);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully rejected leave.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;		
			}else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		public function approveDisapprovedLeave($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Failed to approve request.";
			$approver = array(
				"request_id"=>$params['id'],
				"approval_type"=>6,
				"position"=>Helper::get("position_id"),
				"employee_id"=>Helper::get("employee_id"),
				"approved_by"=>Helper::get("userid"),
				"name"=>Helper::get("first_name") ." ". substr(Helper::get("middle_name"), 0, 1) .". ". Helper::get("last_name"),
				"remarks"=>@$params["remarks"],
				"file_name"=>$params['dir_file_name'],
				"file_size"=>$params['dir_file_size']
			);
			$this->db->insert("tblleavemanagementapprovals",$approver);

			$sql_data = array($params['dir_file_name'],$params['dir_file_size'],$params['sess_employee_id'],$params['id']);
			$sql = "UPDATE tblleavemanagement SET dir_filename = ?, dir_filesize = ?, dir_employee_id = ? WHERE id = ? ";
			$this->db->query($sql,$sql_data);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully approved request.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;		
			}else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		public function rejectDisapprovedLeave($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Failed to disapprove request.";
			$approver = array(
				"request_id"=>$params['id'],
				"approval_type"=>4,
				"position"=>Helper::get("position_id"),
				"employee_id"=>Helper::get("employee_id"),
				"approved_by"=>Helper::get("userid"),
				"name"=>Helper::get("first_name") ." ". substr(Helper::get("middle_name"), 0, 1) .". ". Helper::get("last_name"),
				"remarks"=>"",
				"file_name"=>$params['dir_file_name'],
				"file_size"=>$params['dir_file_size']
			);
			$this->db->insert("tblleavemanagementapprovals",$approver);

			$sql_data = array(5, 1, $params['dir_file_name'], $params['dir_file_size'], $params['sess_employee_id'], $params['id']); 
			$sql = "UPDATE tblleavemanagement SET status = ?, is_active =?, dir_filename = ?, dir_filesize = ?, dir_employee_id = ? WHERE id = ? ";
			$this->db->query($sql,$sql_data);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully disapproved request.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;		
			}else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}
		
		public function addRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Regular leave failed to insert.";
			$params['table1']['filesize'] = $params['file_size'];
			$params['table1']['filename'] = $params['file_name'];
			$params['table1']['division_id'] = $params['division_id'];
			$params['table1']['employee_id'] = $params['employee_id'];
			$params['table1']['type_vacation_location'] = isset($params['table1']['type_vacation_location'])?"Abroad":"Domestic";
			$params['table1']['type_sick_location'] = isset($params['table1']['type_sick_location'])?"Hospital":"Home";
			$params['table1']['commutation'] = isset($params['table1']['commutation'])?"Requested":"Not Requested";
			$params['table1']['kind'] = "Regular";
			$params['table1']['number_of_days'] = @$params['table1']['number_of_days'];
			$params['table1']['date_filed'] = @$params['table1']['date_filed'];
			$params['table1']['status'] = "APPROVED";
			$params['table1']['force_status'] = @$params['table1']['force_status'];
			$params['table1']['force_remarks'] = @$params['table1']['force_remarks'];
			$this->db->trans_begin();
			$this->db->insert('tblleavemanagement', $params['table1']);
			$id = $this->db->insert_id();
			$leave_days = @$params['table2']['days_of_leave'];
			$adjustments = array();
			foreach ($leave_days as $k1 => $v1) {
				if(!$this->isHoliday($v1)){
					$week_day =  date("l", strtotime($v1));

					$employee_sched = $this->getEmployeeWeeklySched($params['employee_id'],$week_day);
					$employee_sched[0]['start_time'] = "08:00:00";
					$employee_sched[0]['break_start_time'] = "12:00:00";
					$employee_sched[0]['end_time'] = "17:00:00";
					if(isset($employee_sched) && sizeof($employee_sched) > 0){
						//Check In
						$adjustments[] = array(
							"employee_number"=> $employee_sched[0]['employee_number'],
							"transaction_date"=> $v1,
							"transaction_time"=>$employee_sched[0]['start_time'],
							"transaction_type"=>0,
							"source_location" =>"manual input",
							"source_device" =>"manual input",
							"remarks"=>str_replace("_", " ", @$params['table1']['type']),
							"leave_id"=>$id,
							"modified_by"=>Helper::get('userid')
						);
						//Lunch Out
						$adjustments[] = array(
							"employee_number"=> $employee_sched[0]['employee_number'],
							"transaction_date"=> $v1,
							"transaction_time"=>$employee_sched[0]['break_start_time'],
							"transaction_type"=>2,
							"source_location" =>"manual input",
							"source_device" =>"manual input",
							"remarks"=>str_replace("_", " ", @$params['table1']['type']),
							"leave_id"=>$id,
							"modified_by"=>Helper::get('userid')
						);
						//Check Out
						$adjustments[] = array(
							"employee_number"=> $employee_sched[0]['employee_number'],
							"transaction_date"=> $v1,
							"transaction_time"=>$employee_sched[0]['end_time'],
							"transaction_type"=>1,
							"source_location" =>"manual input",
							"source_device" =>"manual input",
							"remarks"=>str_replace("_", " ", @$params['table1']['type']),
							"leave_id"=>$id,
							"modified_by"=>Helper::get('userid')
						);
					}
				}
			}
			$this->db->insert_batch('tbltimekeepingdailytimerecordadjustments', $adjustments);
			$days = array();
			foreach ($params['table2']['days_of_leave'] as $k => $v) {
				$days[] = array(
					"id"=> $id,
					"days_of_leave"  => $params['table2']['days_of_leave'][$k]
				);
			}
			$this->db->insert_batch('tblleavemanagementdaysleave', $days); 
			if($this->db->trans_status() === TRUE){
				$code = "0";
				$this->db->trans_commit();
				$message = "Successfully inserted Regular Leave.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;		
			}else {
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		public function updateRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Regular leave failed to update.";
			$params['table1']['division_id'] = $params['division_id'];
			$params['table1']['employee_id'] = $params['employee_id'];
			$params['table1']['filesize'] = $params['file_size'];
			$params['table1']['filename'] = $params['file_name'];
			$params['table1']['type_vacation_location'] = isset($params['table1']['type_vacation_location'])?"Abroad":"Domestic";
			$params['table1']['type_sick_location'] = isset($params['table1']['type_sick_location'])?"Hospital":"Home";
			$params['table1']['commutation'] = isset($params['table1']['commutation'])?"Requested":"Not Requested";
			$params['table1']['kind'] = "Regular";
			$params['table1']['number_of_days'] = @$params['table1']['number_of_days'];
			$params['table1']['date_filed'] = @$params['table1']['date_filed'];
			$params['table1']['status'] = "APPROVED";
			$params['table1']['force_status'] = @$params['table1']['force_status'];
			$params['table1']['force_remarks'] = @$params['table1']['force_remarks'];
			$this->db->trans_begin();
			$this->db->where('id', $params['table1']['id']);
			$this->db->update('tblleavemanagement', $params['table1']);
			$this->db->where('leave_id', $params['table1']['id']);
			$this->db->delete('tbltimekeepingdailytimerecordadjustments');
			$leave_days = @$params['table2']['days_of_leave'];
			$id = $params['table1']['id'];
			$adjustments = array();
			foreach ($leave_days as $k1 => $v1) {
				if(!$this->isHoliday($v1)){
					$week_day =  date("l", strtotime($v1));
					$employee_sched = $this->getEmployeeWeeklySched($params['employee_id'],$week_day);
					$employee_sched[0]['start_time'] = "08:00:00";
					$employee_sched[0]['break_start_time'] = "12:00:00";
					$employee_sched[0]['end_time'] = "17:00:00";
					if(isset($employee_sched) && sizeof($employee_sched) > 0){
						//Check In
						$adjustments[] = array(
							"employee_number"=> $employee_sched[0]['employee_number'],
							"transaction_date"=> $v1,
							"transaction_time"=>$employee_sched[0]['start_time'],
							"transaction_type"=>0,
							"source_location" =>"manual input",
							"source_device" =>"manual input",
							"remarks"=>str_replace("_", " ", @$params['table1']['type']),
							"leave_id"=>$id,
							"modified_by"=>Helper::get('userid')
						);
						//Lunch Out
						$adjustments[] = array(
							"employee_number"=> $employee_sched[0]['employee_number'],
							"transaction_date"=> $v1,
							"transaction_time"=>$employee_sched[0]['break_start_time'],
							"transaction_type"=>2,
							"source_location" =>"manual input",
							"source_device" =>"manual input",
							"remarks"=>str_replace("_", " ", @$params['table1']['type']),
							"leave_id"=>$id,
							"modified_by"=>Helper::get('userid')
						);
						//Check Out
						$adjustments[] = array(
							"employee_number"=> $employee_sched[0]['employee_number'],
							"transaction_date"=> $v1,
							"transaction_time"=>$employee_sched[0]['end_time'],
							"transaction_type"=>1,
							"source_location" =>"manual input",
							"source_device" =>"manual input",
							"remarks"=>str_replace("_", " ", @$params['table1']['type']),
							"leave_id"=>$id,
							"modified_by"=>Helper::get('userid')
						);
					}
				}
			}
			$this->db->insert_batch('tbltimekeepingdailytimerecordadjustments', $adjustments);
			$days = array();
			foreach ($params['table2']['days_of_leave'] as $k => $v) {
				$days[] = array(
					"id"=> $id,
					"days_of_leave"  => $params['table2']['days_of_leave'][$k]
				);
			}
			$this->db->query("DELETE FROM tblleavemanagementdaysleave WHERE id = $id");
			$this->db->insert_batch('tblleavemanagementdaysleave', $days);
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
			$params['table1']['filesize'] = $params['file_size'];
			$params['table1']['filename'] = $params['file_name'];
			$params['table1']['division_id'] = $params['division_id'];
			$params['table1']['employee_id'] = $params['employee_id'];
			$params['table1']['kind'] = "Special";
			$params['table1']['number_of_days'] = @$params['table1']['number_of_days'];
			$params['table1']['date_filed'] = @$params['table1']['date_filed'];
			$params['table1']['status'] = "APPROVED";
			$this->db->trans_begin();
			$this->db->insert('tblleavemanagement', $params['table1']);
			$id = $this->db->insert_id();
			$days = array();
			foreach ($params['table2']['days_of_leave'] as $k => $v) {
				$days[] = array(
					"id"=> $id,
					"days_of_leave"  => $params['table2']['days_of_leave'][$k]
				);
			}
			$this->db->insert_batch('tblleavemanagementdaysleave', $days);
			if($this->db->trans_status() === TRUE){
				$code = "0";
				$this->db->trans_commit();
				$message = "Successfully inserted Special Leave.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;		
			}else {
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}
		
		public function updateRowsSpecial($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Special leave failed to update.";
			$params['table1']['filesize'] = $params['file_size'];
			$params['table1']['filename'] = $params['file_name'];
			$params['table1']['division_id'] = $params['division_id'];
			$params['table1']['employee_id'] = $params['employee_id'];
			$params['table1']['kind'] = "Special";
			$params['table1']['number_of_days'] = @$params['table1']['number_of_days'];
			$params['table1']['date_filed'] = @$params['table1']['date_filed'];
			$params['table1']['status'] = "APPROVED";
			$this->db->trans_begin();
			//Update Leave
			$this->db->where('id', $params['table1']['id']);
			$this->db->update('tblleavemanagement', $params['table1']);
			//Add adjustments
			$id = $params['table1']['id'];
			$days = array();
			foreach ($params['table2']['days_of_leave'] as $k => $v) {
				$days[] = array(
					"id"=> $id,
					"days_of_leave"  => $params['table2']['days_of_leave'][$k]
				);
			}
			$this->db->query("DELETE FROM tblleavemanagementdaysleave WHERE id = $id");
			$this->db->insert_batch('tblleavemanagementdaysleave', $days);
			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);		
			}else {
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
			$sql = " SELECT * FROM tblleavemanagementdaysleave WHERE id = ?";
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
			}else {
				$this->ModelResponse($code, $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
			}
			return false;
		}

		// public function fetchLeaveApprovals($id){
		// 	$helperDao = new HelperDao();
		// 	$data = array();
		// 	$code = "1";
		// 	$message = "No data available.";
		// 	$sql = " SELECT * FROM tblleavemanagementapprovals WHERE request_id = ?";
		// 	$params = array('id'=>$id);
		// 	$query = $this->db->query($sql,$id);
		// 	$userlevel_rows = $query->result_array();
		// 	$data['details'] = $userlevel_rows;
		// 	if(sizeof($userlevel_rows) > 0){
		// 		$code = "0";
		// 		$message = "Successfully fetched leave approvals.";
		// 		$this->ModelResponse($code, $message, $data);	
		// 		$helperDao->auditTrails(Helper::get('userid'),$message);	
		// 		return true;		
		// 	}	
		// 	else {
		// 		$this->ModelResponse($code, $message);
		// 		$helperDao->auditTrails(Helper::get('userid'),$message);
		// 	}
		// 	return false;
		// }

		function getEmployeeWeeklySched($employee_id,$week_day){
			$sql = " SELECT a.id,a.regular_shift,a.shift_id,DECRYPTER(a.employee_number,'sunev8clt1234567890',a.id) AS employee_number,
							b.week_day,b.start_time,b.break_start_time,b.break_end_time,b.end_time,b.is_restday 
					FROM tblemployees a
					LEFT JOIN tbltimekeepingemployeeschedulesweekly b ON a.shift_id = b.shift_code_id
					WHERE a.id = ?";
			$query = $this->db->query($sql,array($employee_id/*,$week_day*/));
			return $query->result_array(); 
		}

		function isHoliday($date){
			$this->sql = "CALL sp_is_holiday(?)";
			$query = $this->db->query($this->sql,array($date));
			$result = $query->result();
			mysqli_next_result($this->db->conn_id);
			if(sizeof($result) > 0){
				return true;
			}
			return false;
		}

		function returnBetweenDates( $startDate, $endDate ){
		    $startStamp = strtotime(  $startDate );
		    $endStamp   = strtotime(  $endDate );
		    if( $endStamp > $startStamp ){
		        while( $endStamp >= $startStamp ){
		            $dateArr[] = date( 'Y-m-d', $startStamp );
		            $startStamp = strtotime( ' +1 day ', $startStamp );
		        }
		        return $dateArr;    
		    }else{
		        return $startDate;
		    }

		}
		
		public function getDays($params){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$this->sql = "CALL sp_get_days(?,?)";
			$query = $this->db->query($this->sql,array($params["employee_id"],$params["date"]));
			$result = $query->result();
			mysqli_next_result($this->db->conn_id);
			$data['details'] = $result;
			if(sizeof($data['details']) > 0){
				$code = "0";
				$message = "Successfully fetched leave dates.";
				$this->ModelResponse($code, $message, $data);	
				return true;		
			} else {
				$this->ModelResponse($code, $message);
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

		public function fetchLeaveApprovals($employee_id){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$this->sql = "SELECT c.position_designation,
				b.name as position_title,
				c.position_id,
				a.approver,
				a.approve_type,
				DECRYPTER(c.first_name, 'sunev8clt1234567890', c.id) as first_name,
				DECRYPTER(c.middle_name, 'sunev8clt1234567890', c.id) AS middle_name,
				DECRYPTER(c.last_name, 'sunev8clt1234567890', c.id) as last_name,
				DECRYPTER(c.extension, 'sunev8clt1234567890', c.id) as extension,
				c.id
				FROM 
				tblemployeesleaveapprovers AS a
				LEFT JOIN tblemployees c ON a.approver = c.id
				LEFT JOIN tblfieldpositions b ON c.position_id = b.id
				WHERE a.employee_id = ?
				AND a.is_active = 1
			";
			$query = $this->db->query($this->sql,array($employee_id));
			$result = $query->result();
			$data['approvers'] = $result;
			if(sizeof($result) > 0){
				$code = "0";
				$message = "Successfully fetched leave approvals.";
				$this->ModelResponse($code, $message, $data);	
				return true;		
			}	
			else {
				$this->ModelResponse($code, $message);
			}
			return false;
		}

	}
?>