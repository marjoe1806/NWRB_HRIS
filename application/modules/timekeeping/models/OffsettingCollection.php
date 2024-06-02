<?php
	class OffsettingCollection extends Helper {
		var $select_column = null;   
		//Fetch
		var $table = "tbltimekeepingoffsettings";   
		var $order_column = array('tbltimekeepingoffsettings.date_requested','tbltimekeepingoffsettings.number_of_hrs','tblemployees.first_name','tbltimekeepingoffsettings.purpose','tbltimekeepingoffsettings.checked_by','.tbloffsettingstatus.description');
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
		}
		  
		function make_query() {
			
			$this->select_column[] = 'tblemployees.first_name';
			$this->select_column[] = 'tblemployees.last_name';
			$this->select_column[] = 'tblemployees.middle_name';
			$this->select_column[] = 'tblemployees.extension';
			$this->select_column[] = 'tbltimekeepingoffsettings.date_requested';
			$this->select_column[] = 'tbltimekeepingoffsettings.number_of_hrs';
			$this->select_column[] = 'tbltimekeepingoffsettings.purpose';
			$this->select_column[] = 'tbltimekeepingoffsettings.checked_by';
			$this->select_column[] = 'tbloffsettingstatus.description';
		    $this->db->select($this->table.'.*, tbltimekeepingoffsettings.id as offsetting_id, tbloffsettingstatus.description as request_status,tblemployees.id as salt,tblemployees.*');
		    $this->db->from($this->table);
		    $this->db->join("tbloffsettingstatus","tbloffsettingstatus.id = ".$this->table.".status","left");	
		    $this->db->join("tblemployees","tblemployees.id = ".$this->table.".employee_id","left");	   
		    if(isset($_POST["search"]["value"])) {  
		    	$this->db->group_start();
		     	foreach ($this->select_column as $key => $value) {
					foreach ($this->select_column as $key => $value) {
						if($value == "tblemployees.first_name" || $value == "tblemployees.last_name" || $value == "tblemployees.middle_name" || $value == "tblemployees.extension" || $value == "tblemployees.employee_number" || $value == "tblemployees.employee_id_number")  {
							$this->db->or_like("DECRYPTER($value,'sunev8clt1234567890',tblemployees.id)", $_POST["search"]["value"]);  
						}
						else{
							$this->db->or_like($value, $_POST["search"]["value"]);  
						}
					}
				 }
				$this->db->or_like("CONCAT(DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.extension,'sunev8clt1234567890',tblemployees.id),', ',DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.middle_name,'sunev8clt1234567890',tblemployees.id))",$_POST["search"]["value"]);
		        $this->db->group_end(); 
			}
	
			if(isset($_GET['Status']) && $_GET['Status'] != NULL) $this->db->where($this->table.'.status="'.$_GET['Status'].'"');
			if(isset($_POST["order"]))
				$this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
			else
			$this->db->order_by($this->table.".date_created DESC");
		    $this->db->group_by($this->table.'.employee_id');
		}
		
		function make_datatables(){  
		    $this->make_query();  
			if($_POST["length"] != -1) $this->db->limit($_POST['length'], $_POST['start']);

			$query = $this->db->get();
		    return $query->result();  
		}

		function get_filtered_data(){  
		     $this->make_query();
		     $query = $this->db->get();  
		     return $query->num_rows();  
		}

		

		function get_all_data() {  
		    $this->db->select("*")->from($this->table);
		    return $this->db->count_all_results();  
		}
		
		public function hasRows(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = "SELECT a.*, b.description as request_status FROM tbltimekeepingoffsettings a LEFT JOIN tbloffsettingstatus b ON a.status = b.id";
			$query = $this->db->query($sql);
			$data['details']= $query->result_array();
			if(sizeof($data['details']) > 0){
				foreach ($data['details'] as $k => $v) {
					$employee = $this->getEmployeeById($v['employee_id']);
					$data['details'][$k]['employee_firstname'] = @$this->Helper->decrypt($employee[0]['first_name'], $employee[0]['id']);
					$data['details'][$k]['employee_middlename'] = @$this->Helper->decrypt($employee[0]['middle_name'], $employee[0]['id']);
					$data['details'][$k]['employee_lastname'] = @$this->Helper->decrypt($employee[0]['last_name'], $employee[0]['id']);
					$employee_number = @$this->Helper->decrypt($employee[0]['employee_number'], $employee[0]['id']);
				}
			}
			
			if(sizeof($data['details']) > 0){
				$code = "0";
				$message = "Successfully fetched Offsettings.";
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

		public function hasRowsActive(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tbltimekeepingoffsettings where is_active = '1'";
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

		public function hasRowsActiveHoliday(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT date FROM tblfieldholidays where is_active = '1'";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = array_column($userlevel_rows,"date");
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched holidays.";
				$this->ModelResponse($code, $message, $data);
				return true;
			}
			else {
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		public function activeRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Offsetting failed to activate.";
			$data['Id'] 	= isset($params['offsetting_id'])?$params['offsetting_id']:'';
			$data['Status'] 		= "1";
			$userlevel_sql = "	UPDATE tbltimekeepingoffsettings SET is_active = ? WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully activated Offsetting.";
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
			$dtr['details'] = $this->getDepartmentById($dtr['employee'][0]['office_id']);
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

		public function inactiveRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Offsetting failed to deactivate.";
			$data['Id'] 	= isset($params['offsetting_id'])?$params['offsetting_id']:'';
			$data['Status'] 		= "0";
			$userlevel_sql = "	UPDATE tbltimekeepingoffsettings SET is_active = ? WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			// $sql = "UPDATE tbltimekeepingdailytimerecordadjustments SET is_active = ? W"
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully deactivated Offsetting.";
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

		function getDTRAdjustments($employee_number, $date) {
			$params = array($employee_number, $date);
			$sql = "SELECT * FROM tbltimekeepingdailytimerecordadjustments WHERE employee_number = ? AND transaction_date = ? AND is_active = 1";
			$query = $this->db->query($sql, $params);
			$employee = $query->result_array();
			return $employee;
		}

		public function getAvailableHoursDetails($id){
			$code = "1";
			$message = "No data available.";
			$query = $this->db->select("a.num_hrs as available_hours")->from("tblovertimebalance a")->where("a.employee_id",$id)->limit(1)->get();
			$query2 = $this->db->select("SUM(a.number_of_hrs) as pending_offset_hours,COUNT(*) as tot_rows")->from("tbltimekeepingoffsettings a")->where("a.employee_id",$id)->where_in("a.status",array(1,2,3))->get();
		    if($query->num_rows() > 0 || $query2->num_rows() > 0){
				$code = "0";
				$message = "Successfully fetched overtime details.";
				$row1 = $query->row_array();
				$row2 = $query2->row_array();
				$data["available"] = ($query->num_rows()>0)?$row1["available_hours"]:0;
				$data["pending"] = (isset($row2["tot_rows"]) && (int)$row2["tot_rows"]>0)?$row2["pending_offset_hours"]:0;
				$this->ModelResponse($code, $message, $data);
				return true;		
			}else {
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		public function addRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Offsetting failed to insert.";
			$this->db->trans_begin();
			$locator_slip = array(
				'purpose'		=> $params['purpose'],
				'filename'		=> (isset($params['file']['name']))?$params['file']['name']:"",
				'filesize'		=> (isset($params['file']['size']))?$params['file']['size']:"",
				'filetype'		=> (isset($params['file']['type']))?$params['file']['type']:"",
				'employee_id' 	=> $params['employee_id'],
				'division_id' 	=> $params['division_id'],
				'received_by' 	=> Helper::get('userid'),
				'date_requested'=> $params['date_requested'],
				'number_of_hrs' => $params['number_of_hrs']
			);
			if($this->db->insert('tbltimekeepingoffsettings', $locator_slip)) {
				if($this->db->trans_status() === TRUE){
					$code = "0";
					$this->db->trans_commit();
					$message = "Offsetting successfully inserted.";
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message, $params['employee_id']);
					return true;
				}
				else {
					$this->db->trans_rollback();
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
				}
			}else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
			}
			$this->ModelResponse($code, $message);
			return false;
		}

		public function updateRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Offsetting failed to insert.";
			$this->db->trans_begin();
			$locator_slip = array(
				'purpose'		=> $params['purpose'],
				'filename'		=> (isset($params['file']['name']))?$params['file']['name']:"",
				'filesize'		=> (isset($params['file']['size']))?$params['file']['size']:"",
				'filetype'		=> (isset($params['file']['type']))?$params['file']['type']:"",
				'division_id' 	=> $params['division_id'],
				'modified_by' 	=> Helper::get('userid'),
				'date_requested'=> $params['date_requested'],
				'number_of_hrs' => $params['number_of_hrs']
			);
			if($this->db->where("employee_id",$params['employee_id'])->update('tbltimekeepingoffsettings', $locator_slip)) {
				if($this->db->trans_status() === TRUE){
					$code = "0";
					$this->db->trans_commit();
					$message = "Offsetting successfully inserted.";
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message, $params['employee_id']);
					return true;
				}
				else {
					$this->db->trans_rollback();
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
				}
			}else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
			}
			$this->ModelResponse($code, $message);
			return false;
		}
		
		public function certifyRow($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Offset request failed to certify.";
			$this->db->trans_begin();
			$approver = array("request_id"=>$params['id'],"approval_type"=>1,"approved_by"=>Helper::get("userid"),"name"=>Helper::get("first_name") ." ". Helper::get("middle_name") ." ". Helper::get("last_name"),"remarks"=>"");
			$this->db->insert("tbloffsettingapprovals",$approver);
			$sql_data = array(2,$params['offsetting_id']);
			$sql = "UPDATE tbltimekeepingoffsettings SET status = ? WHERE id = ? ";
			$this->db->query($sql,$sql_data);
			if($this->db->trans_status() === TRUE){
				$this->db->trans_commit();
				$code = "0";
				$message = "Successfully certification offset.";
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
		
		public function recommendRow($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Offset request failed to recommend.";
			$this->db->trans_begin();
			$approver = array("request_id"=>$params['id'],"approval_type"=>2,"approved_by"=>Helper::get("userid"),"name"=>Helper::get("first_name") ." ". Helper::get("middle_name") ." ". Helper::get("last_name"),"remarks"=>"");
			$this->db->insert("tbloffsettingapprovals",$approver);
			$sql_data = array(3,$params['offsetting_id']);
			$sql = "UPDATE tbltimekeepingoffsettings SET status = ? WHERE id = ? ";
			$this->db->query($sql,$sql_data);
			if($this->db->trans_status() === TRUE){
				$this->db->trans_commit();
				$code = "0";
				$message = "Successfully recommended offset.";
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
		
		public function approveRow($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Offset request failed to approve.";
			$this->db->trans_begin();
			$currentBal = $this->db->select("num_hrs")->from("tblovertimebalance")->where("employee_id",$params["employee_id"])->limit(1)->get()->row_array();
			if(((int)$currentBal["num_hrs"] >= (int)$params["nohrs"])){
				$approver = array("request_id"=>$params['id'],"approval_type"=>3,"approved_by"=>Helper::get("userid"),"name"=>Helper::get("first_name") ." ". Helper::get("middle_name") ." ". Helper::get("last_name"),"remarks"=>"");
				$this->db->insert("tbloffsettingapprovals",$approver);
				$this->db->where("employee_id",$params["employee_id"])->update("tblovertimebalance",array("modified_by"=>Helper::get("userid"),"num_hrs"=>((int)$currentBal["num_hrs"] - (int)$params["nohrs"])));//
				$sql_data = array(4,$params['offsetting_id']);
				$sql = "UPDATE tbltimekeepingoffsettings SET status = ? WHERE id = ? ";
				$this->db->query($sql,$sql_data);
				if($this->db->trans_status() === TRUE){
					$this->db->trans_commit();
					$code = "0";
					$message = "Successfully approved offset.";
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
					return true;
				} else{
					$this->db->trans_rollback();
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
				}
			}else{
				$message = "No available hours to offset.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}
		
		public function rejecteRow($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Offset request failed to reject.";
			$this->db->trans_begin();
			$approver = array("request_id"=>$params['id'],"approval_type"=>6,"approved_by"=>Helper::get("userid"),"name"=>Helper::get("first_name") ." ". Helper::get("middle_name") ." ". Helper::get("last_name"),"remarks"=>"");
			$this->db->insert("tbloffsettingapprovals",$approver);
			$sql_data = array(6,$params['offsetting_id']);
			$sql = "UPDATE tbltimekeepingoffsettings SET status = ? WHERE id = ? ";
			$this->db->query($sql,$sql_data);
			if($this->db->trans_status() === TRUE){
				$this->db->trans_commit();
				$code = "0";
				$message = "Successfully rejected offset.";
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

	}
?>