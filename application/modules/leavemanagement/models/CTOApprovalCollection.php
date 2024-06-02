<?php
	class CTOApprovalCollection extends Helper {
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
		var $table = "tbloffsetting";   
		var $order_column = array('','first_name','date_filed','type','offset_date_effectivity','status_name','remarks');
		var $order_column2 = array('','first_name','filing_date','type','inclusive_dates','description','remarks');
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
			$this->sql = "CALL sp_get_employee_cto_requests(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
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
		    return $total;

		}  
		public function activeRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Leave failed to activate.";
			$data['id'] 	= isset($params['id'])?$params['id']:'';
			$data['is_active'] = "1";

			$this->db->trans_begin();
			$userlevel_sql = "	UPDATE tbloffsetting SET
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
			$userlevel_sql = "	UPDATE tbloffsetting SET
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
			$helperDao = new HelperDao();
			$code = "1";
			$message = "CTO failed to certify.";
			$approver = array(
				"request_id"=>$params['id'],
				"approval_type"=>1,
				"position"=>Helper::get("position_id"),
				"employee_id"=>Helper::get("employee_id"),
				"approved_by"=>Helper::get("userid"),
				"name"=>Helper::get("first_name") ." ". Helper::get("middle_name") ." ". Helper::get("last_name"),
				"remarks"=>"",
				"file_name"=>$params['file_name'],
				"file_size"=>$params['file_size']
			);
			$this->db->insert("tbloffsettingapprovals",$approver);
			$sql_data = array(2,$params['id']); 
			$sql = "UPDATE tbloffsetting SET status = ? WHERE id = ? ";
			$this->db->query($sql,$sql_data);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully certified CTO.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;		
			} else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		public function recommendRows($params){
			// var_dump(json_encode($params)); die();
			$helperDao = new HelperDao();
			$code = "1";
			$message = "CTO failed to recommend.";
			$this->db->trans_begin();

			$is_rec = " SELECT a.employee_id, b.position_id, c.is_leave_recom as recom FROM tbloffsetting a
					LEFT JOIN tblemployees b ON a.employee_id = b.id LEFT JOIN tblfieldpositions c ON b.position_id = c.id WHERE a.id = ?";
			$is_rec = $this->db->query($is_rec,array($params['id']))->row_array();
			$sql_data = array();
			if($is_rec["recom"] == 1) {
				$approver = array(
					"request_id"=>$params['id'],
					"approval_type"=>2,
					"position"=>Helper::get("position_id"),
					"employee_id"=>Helper::get("employee_id"),
					"approved_by"=>Helper::get("userid"),
					"name"=>Helper::get("first_name") ." ". Helper::get("middle_name") ." ". Helper::get("last_name"),
					"remarks"=>""
					// "file_name"=>$params['file_name'],
					// "file_size"=>$params['file_size']
				);
				$this->db->insert("tbloffsettingapprovals",$approver);
				$approver = array(
					"request_id"=>$params['id'],
					"approval_type"=>2,
					"position"=>"",
					"employee_id"=>"",
					"approved_by"=>"",
					"name"=>"",
					"remarks"=>""
					// "file_name"=>"",
					// "file_size"=>""
				);
				$this->db->insert("tbloffsettingapprovals",$approver);
				$sql_data = array(4,$params['id']);
			}else {
				$approver = array(
					"request_id"=>$params['id'],
					"approval_type"=>2,
					"position"=>Helper::get("position_id"),
					"employee_id"=>Helper::get("employee_id"),
					"approved_by"=>Helper::get("userid"),
					"name"=>Helper::get("first_name") ." ". Helper::get("middle_name") ." ". Helper::get("last_name"),
					"remarks"=>""
					// "file_name"=>$params['file_name'],
					// "file_size"=>$params['file_size']
				);
				$this->db->insert("tbloffsettingapprovals",$approver);
				$sql_data = array(3,$params['id']);
			}
			$sql = "UPDATE tbloffsetting SET status = ? WHERE id = ? ";
			$this->db->query($sql,$sql_data);
				if($this->db->trans_status() === TRUE){
					$this->db->trans_commit();
					$code = "0";
					$message = "Successfully recommended CTO.";
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

		public function recommendRows2($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "CTO failed to recommend.";
			$approver = array(
				"request_id"=>$params['id'],
				"approval_type"=>3,
				"position"=>Helper::get("position_id"),
				"employee_id"=>Helper::get("employee_id"),
				"approved_by"=>Helper::get("userid"),
				"name"=>Helper::get("first_name") ." ". Helper::get("middle_name") ." ". Helper::get("last_name"),
				"remarks"=>"",
				"file_name"=>$params['file_name'],
				"file_size"=>$params['file_size']
			);
			$this->db->insert("tbloffsettingapprovals",$approver);
			$sql_data = array(4,$params['id']); 
			$sql = "UPDATE tbloffsetting SET status = ? WHERE id = ? ";
			$this->db->query($sql,$sql_data);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully recommended CTO.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;		
			} else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		// public function recommendRowsHead($params){
		// 	$helperDao = new HelperDao();
		// 	$code = "1";
		// 	$message = "Pending leave failed to recommend.";
		// 	$approver = array("request_id"=>$params['id'],"approval_type"=>3,"position"=>Helper::get("position_id"),"employee_id"=>Helper::get("employee_id"),"approved_by"=>Helper::get("userid"),"name"=>Helper::get("first_name") ." ". Helper::get("middle_name") ." ". Helper::get("last_name"),"remarks"=>"");
		// 	$this->db->insert("tbloffsettingapprovals",$approver);
		// 	$sql_data = array(4,$params['id']); 
		// 	$sql = "UPDATE tbloffsetting SET status = ? WHERE id = ? ";
		// 	$this->db->query($sql,$sql_data);
		// 	if($this->db->affected_rows() > 0){
		// 		$code = "0";
		// 		$message = "Successfully recommended leave.";
		// 		$helperDao->auditTrails(Helper::get('userid'),$message);
		// 		$this->ModelResponse($code, $message);
		// 		return true;		
		// 	} else {
		// 		$helperDao->auditTrails(Helper::get('userid'),$message);
		// 		$this->ModelResponse($code, $message);
		// 	}
		// 	return false;
		// }

		public function approveRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "CTO failed to approved.";
			$params['status'] = "APPROVED";
			$arr_inclusive_dates = explode(' - ',$params['offset_date_effectivity']);
			$inclusive_dates = implode(", ",$this->getDatesFromRange($arr_inclusive_dates[0],$arr_inclusive_dates[1]));
			$inclusive_dates = explode(', ',$inclusive_dates);
			$params['offset_hrs'] = ($params['offset_hrs'] / $params['nodays']);

			foreach($inclusive_dates as $k => $v){
				$params['offset_date_effectivity'] = $v;
				$offsetTotal = $params['offset_hrs'] > 0 || $params['offset_mins'] > 0 ?  ($params['offset_hrs'] * 60) + $params['offset_mins'] : 0;
				if($params['type'] == 'offset' || $params['type'] == 'CTO'){
					$emp = $this->db->select("employee_number")->from("tblemployees")->where("id", $params['employee_id'])->get()->row_array();
	
					//update offset Balance
					$dtrlist = $this->db->select("*")->from("tbldtr")
					->where("transaction_date >=", date('Y-m-d', strtotime(date($params['date_filed']).'-1 year')))
					->where("transaction_date <=", date($params['date_filed']))
					->where("CAST(scanning_no AS INT)=", (int)$this->Helper->decrypt($emp['employee_number'],$params['employee_id']))->order_by('transaction_date', 'ASC')->get()->result_array();
					$convertedOffset = ( number_format($offsetTotal * .0020833333333333, 3, '.', ''));
	
					$validateAdjustementOffset = array_filter($dtrlist, function ($var){
						return ($var['adjustment_offset'] > 0);
					});
					// var_dump($dtrlist);die();
					foreach ($dtrlist as $k => $v) {
						$offset = count($validateAdjustementOffset) > 0 ? $v['adjustment_offset'] : $v['offset']; 
						$adjustOffset = $offset;
	
						if($convertedOffset > 0 && $offset > 0) $adjustOffset =  $offset - $convertedOffset <= 0 ? 0 : $offset - $convertedOffset;
							$this->db->set('adjustment_offset', $adjustOffset);
							$this->db->where('id', $v['id']);
							$this->db->update('tbldtr');
							$convertedOffset = $convertedOffset - $offset;
					}
					//update ut balance
					$dtrlistvalidate = $this->db->select("*")->from("tbldtr")
					->where("transaction_date", $params['offset_date_effectivity'])
					->where("CAST(scanning_no AS INT)=", (int)$this->Helper->decrypt($emp['employee_number'],$params['employee_id']))->get()->result_array();
					//->where("scanning_no", "0004321")->get()->result_array();
	
					if(count($dtrlistvalidate) > 0 ){
						// $this->db->set('approve_offset_hrs', $params['offset_hrs']);
						// $this->db->set('approve_offset_mins',$params['approval_typeve_offset_mins']);
						// $this->db->where('transaction_date', $params['offset_date_effectivity']);
						$data = array(
								'approve_offset_hrs' =>  $params['offset_hrs'],
								'approve_offset_mins' =>  $params['offset_mins'],
								'transaction_date' =>  $params['offset_date_effectivity'],
								'adjustment_remarks' =>  'OFFSET',
							);
						$this->db->where('scanning_no', (int)$this->Helper->decrypt($emp['employee_number'],$params['employee_id']));
						$this->db->where('transaction_date', $params['offset_date_effectivity']);
						$this->db->update('tbldtr', $data);
					}else{
						$datas = array(
							"scanning_no"=> (int)$this->Helper->decrypt($emp['employee_number'],$params['employee_id']),
							"transaction_date"=>$params['offset_date_effectivity'],
							// "ut_hrs" => "8",
							"approve_offset_hrs" => $params['offset_hrs'],
							"approve_offset_mins" => $params['offset_mins'],
							'adjustment_remarks' =>  'OFFSET',
						);
						$this->db->insert("tbldtr",$datas);
					}
				}
				if($arr_inclusive_dates[1] == $arr_inclusive_dates[0]) break;
			}

			$approver = array(
				"request_id"=>$params['id'],
				"approval_type"=>4,
				"position"=>Helper::get("position_id"),
				"employee_id"=>Helper::get("employee_id"),
				"approved_by"=>Helper::get("userid"),
				"name"=>Helper::get("first_name") ." ". Helper::get("middle_name") ." ". Helper::get("last_name"),
				"remarks"=>"",
				"file_name"=>$params['file_name'],
				"file_size"=>$params['file_size']
			);
			$this->db->insert("tbloffsettingapprovals",$approver);
			
			$sql = "UPDATE tbloffsetting SET status = ?, is_active = ? WHERE id = ? ";
			$sql_data = array(5,"1",$params['id']); 
			$this->db->query($sql,$sql_data);

			// $approve_dts_sql = "UPDATE tbltimekeepingdailytimerecordadjustments SET is_active = ? WHERE leave_id = ? ";
			// $approve_dts_data = array("1",$params['id']);
			// $this->db->query($approve_dts_sql,$approve_dts_data);

			// $types = ['vacation','force','sick','monetization'];



			// if(in_array($params['type'],$types)){
			// 	$select = $this->db->select("sl,vl,total")->from("tblleavebalance")->where("id",$params['employee_id'])->get()->row_array();
			// 	$updateleavebal = "UPDATE tblleavebalance SET " . (($params['type'] == "sick")?"sl = ?":"vl = ?") . " AND total = ? WHERE id = ? ";
			// 	$updateleavebal_data = array((($params['type'] == "sick")?$select["sl"]:$select["vl"])-$params['nodays'],$select["total"]-$params['nodays'],$params['employee_id']);
			// 	$this->db->query($updateleavebal,$updateleavebal_data);
			// }


			if($this->db->trans_status() === TRUE){
				$this->db->trans_commit();
				$code = "0";
				$message = "Successfully approved CTO.";
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

		public function rejectRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Pending CTO failed to reject.";
			$approver = array(
				"request_id"=>$params['id'],
				"approval_type"=>6,
				"position"=>Helper::get("position_id"),
				"employee_id"=>Helper::get("employee_id"),
				"approved_by"=>Helper::get("userid"),
				"name"=>Helper::get("first_name") ." ". Helper::get("middle_name") ." ". Helper::get("last_name"),
				"remarks"=>$params["remarks"],
				"file_name"=>$params['file_name'],
				"file_size"=>$params['file_size']
			);
			$this->db->insert("tbloffsettingapprovals",$approver);
			$sql_data = array(6,"0",$params['remarks'],$params['id']); 
			$sql = "UPDATE tbloffsetting SET status = ?, is_active = ?, remarks = ? WHERE id = ? ";
			$this->db->query($sql,$sql_data);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully rejected CTO.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;		
			}else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}
		
		public function fetchCTOApprovals($id){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$this->sql = "CALL sp_get_employee_cto_approvals(?)";
			$query = $this->db->query($this->sql,array($id));
			$result = $query->result();
			mysqli_next_result($this->db->conn_id);
			$data['details'] = $result;
			if(sizeof($result) > 0){
				$code = "0";
				$message = "Successfully fetched leave approvals.";
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

		function get_service_record($id){
			
			$emp = $this->db->select("a.*,b.name AS position_name, b.id,c.vl,c.sl,c.total as leaveCreditsTotal,d.department_name as dpt_name,d.code as dpt_code")->from("tblemployees a")
			->join("tblfieldpositions b","a.position_id = b.id","left")->join("tblleavebalance c","a.id = c.id","left")->join("tblfielddepartments d","a.division_id = d.id","left")
			->where("a.id", $id)->get()->row_array();
			$empexp = $this->db->select("*")->from("tblemployeesworkexperience")->where("employee_id",$id)->get()->result_array();
			

			$validateOffsetAdjustment = $this->db->select("offset_date_effectivity")->from("tbloffsetting")
			->where("offset_date_effectivity >=", date("Y-m-d", strtotime(date("Y-m-d").'-1 year')))
			->where("offset_date_effectivity <=", date("Y-m-d"))
			->where("status", "4")
			->where("employee_id", $id)->get()->result_array();

			// $empoffsetbal = $this->db->select("SUM(offset) as offsetBal, SUM(adjustment_offset) as adjOffsetBal")->from("tbldtr")
			// ->where("transaction_date >=", date("Y-m-d", strtotime(date("Y-m-d").'-1 year')))
			// ->where("transaction_date <=", date("Y-m-d"))
			// ->where("scanning_no", $this->Helper->decrypt($emp['employee_number'], $id))->get()
			// ->row();

			$empoffsetbal = 
				$this->db->select("SUM(CASE WHEN adjustment_offset > 0 THEN adjustment_offset ELSE offset END) as offset_balance")->from("tbldtr")
				->where("transaction_date >=", date("Y-m-d", strtotime(date("Y-m-d").'-1 year')))
				->where("transaction_date <=", date("Y-m-d"))
				->where("CAST(scanning_no AS INT)=", (int)$this->Helper->decrypt($emp['employee_number'], $id))->get()
				->row();
			// var_export($this->db->last_query());die();
			// var_dump($empoffsetbal); die();
			$totalOffset = json_decode(json_encode($empoffsetbal) , true);
			$totalOffset = array(
				"totaloffset" => $totalOffset['offset_balance']
			);

			array_push($emp, $totalOffset , true);
			return array("employee"=>$emp, "experience"=> $empexp);
		}

		function getDatesFromRange($start, $end){
			$dates = array($start);
			while(end($dates) < $end){
				$dates[] = date('Y-m-d', strtotime(end($dates).' +1 day'));
			}
			return $dates;
		}

	}
?>