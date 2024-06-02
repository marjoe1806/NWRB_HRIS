<?php
	class OfficialBusinessRequestCollection extends Helper {
		// var $select_column = null;
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
			// $columns = $this->getColumns();
			// foreach ($columns as $key => $value) {
			// 	if($key != "id")
			// 	$this->select_column[] = $this->table.'.'.$value['COLUMN_NAME']; 
			// }
		}

		var $table = "tbltimekeepinglocatorslips";
      	var $order_column = array('',"a.control_no",'a.transaction_date',"c.last_name",'a.purpose','d.department_name','a.location','a.activity_name','a.reject_remarks','a.status');
		var $select_column = array("a.control_no",'a.transaction_date',"c.last_name",'a.purpose','d.department_name','a.location','a.activity_name','a.reject_remarks','a.status');
      	public function getColumns(){
      		$sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$this->table."' AND TABLE_SCHEMA='".$this->db->database."' ";
			$query = $this->db->query($sql);
			$rows = $query->result_array();
			return $rows;
      	}
		function make_query(){
			$this->select_column[] = 'c.last_name';
			$this->select_column[] = 'a.filing_date';
			$this->select_column[] = 'a.purpose';
			$this->select_column[] = 'a.activity_name';
			$this->select_column[] = 'a.transaction_date';
			$this->select_column[] = 'a.status';
			$this->select_column[] = 'a.reject_remarks';
			$this->select_column[] = 'a.control_no';
		    $this->db->select(
				'a.*,
				DECRYPTER(c.employee_id_number, "sunev8clt1234567890", a.employee_id) as emp_id, 
				b.name as position_name,
				c.first_name,
				c.middle_name,
				c.last_name,
				c.extension,
				d.department_name as department_name'
		    );
		    $this->db->from($this->table." a");
			$this->db->join("tblemployees c", "a.employee_id = c.id","left");
			$this->db->join("tblfieldpositions b", "c.position_id = b.id","left");
			$this->db->join("tblfielddepartments d", "a.division_id = d.id","left");
			// $this->db->order_by('a.control_no', 'desc');
			if($_GET["status"] != "") $this->db->where("a.status",$_GET["status"]);
			// $this->db->where("a.employee_id",Helper::get("id"));
		    $this->db->where("(a.employee_id = '".Helper::get("id")."' OR a.received_by = '".Helper::get("id")."' )");
    	    // if(isset($_GET["search"]["value"])){
		    // 	$this->db->group_start();
		    //  	foreach ($this->select_column as $key => $value) {
		    //  		$this->db->or_like($value, $_GET["search"]["value"]);
		    //  	}
		    //     $this->db->group_end();
		    // }


			if(isset($_GET["search"]["value"])){
		    	$this->db->group_start();
		     	foreach ($this->select_column as $key => $value) {
					if($value == "tblemployees.first_name" || $value == "tblemployees.last_name" || $value == "tblemployees.middle_name" || $value == "tblemployees.extension")  {
						$this->db->or_like("CONCAT(DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.extension,'sunev8clt1234567890',tblemployees.id),', ',DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.middle_name,'sunev8clt1234567890',tblemployees.id))",$_GET["search"]["value"]); 
					}else{
						$this->db->or_like($value, $_GET["search"]["value"]);  
					}
		     	}
		        $this->db->group_end();
		    }

		    if(isset($_GET["order"])){
				$this->db->order_by($this->order_column[$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
		    }else{
				$this->db->order_by("a.id", 'DESC');
				$this->db->where("a.status !=", null);
		    }
		}
		
		function make_datatables(){
		    $this->make_query();
			if($_GET["length"] != -1) $this->db->limit($_GET['length'], $_GET['start']);
		    $query = $this->db->get();
		    return $query->result();
		}
		function get_filtered_data(){
			$this->make_query();
			$query = $this->db->get();
		    return $query->num_rows();
		}
		function get_all_data(){
		    $this->db->select($this->table."*");
			$this->db->from($this->table);
			if($_GET["status"] != "") $this->db->where($this->table.".status",$_GET["status"]);
			$this->db->where("employee_id",Helper::get("id"));
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
					FROM tbltimekeepinglocatorslips a
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
								$data['details'][$k]['locator_time_in'] 	= $v2['transaction_time'];
								break;
							case '3':
								$data['details'][$k]['locator_break_in'] = $v2['transaction_time'];
								break;
							case '2':
								$data['details'][$k]['locator_break_out'] = $v2['transaction_time'];
								break;
							case '1':
								$data['details'][$k]['locator_time_out'] = $v2['transaction_time'];
								break;
						}
					}
				}
			}
			
			if(sizeof($data['details']) > 0){
				$code = "0";
				$message = "Successfully fetched Personnel Locator Slip Forms.";
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

		public function addRows($params){
			// print_r($params); die();
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Personnel Locator Slip Request failed to insert.";
			$this->db->trans_begin();

			$emp_ids = explode(',', $params['employee_ids']); 

			$uppercaseLetters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$lowercaseLetters = 'abcdefghijklmnopqrstuvwxyz';
			$allLetters = $uppercaseLetters . $lowercaseLetters;
			$Locator_id = '';

			for ($i = 0; $i < 8; $i++) {
				$randomIndex = rand(0, strlen($allLetters) - 1);
				$Locator_id .= $allLetters[$randomIndex];
			}

			foreach ($emp_ids as $key => $value) {

				switch($params['purpose']) {
					case "official" :	$purpose = "Official";
										break;
					case "personal" :	$purpose = "Personal;";
										break;
					case "expected_time_return" :	$purpose = "Expected time of return";
										break;
					case "expected_not_back" :	$purpose = "Expected not to be back";
										break;
					default : 			$purpose = "Personnel Locator Slip";
										break;
				}


				if(@$params['location'] != "") $purpose = $purpose." : ".$params['location'];
				$transdate = explode(' - ',$params['transaction_date']);
				// var_dump($transdate);die();

				$nodays = ($transdate[0] == $transdate[1] || $transdate[1] == "0000-00-00" ? 1 : count($transdate));
				$arraydates = array();
				for ($i=0; $i < $nodays ; $i++) { 
					$insertdate = date("Y-m-d", strtotime($transdate[0].' + '.$i.' day'));
					array_push($arraydates,$insertdate);
				}

			
				//Check if Request cancelled
				$not_reject = $this->db->select("*")->from("tbltimekeepinglocatorslips")->where('employee_id', $value)->where('transaction_date', date("Y-m-d", strtotime($transdate[0])))->where('status !=', 'CANCELLED')->order_by('id','DESC')->limit(1)->get()->result_array();
				// var_dump($not_reject);die();
					if($not_reject != NULL){
						$this->ModelResponse($code, "Date selected is already filed.");
						return false;
					}
				//end
			
					//old code for date
					// $getfiledOb = $this->getOBSubmitted($params['employee_id']);
					// $filed_dates = array();
				
					// if(count($getfiledOb) > 0){
					// 	foreach ($getfiledOb as $key => $value) {
					// 		$dates_inclusive = explode(' - ', $value['transaction_date']);
					// 		foreach ($dates_inclusive  as $key => $value2) {
					// 			if(!in_array($value2,$filed_dates, true)){
					// 			       array_push($filed_dates,$value2);
					// 			}
					// 		}
					// 	}
						
					// 		if(array_intersect($arraydates, $filed_dates)){
					// 			$this->ModelResponse($code, "Date selected is already filed.");
					// 			return false;
					// 		}
						
					// }

					// // for control no.
					// $is_have = $this->db->select("control_no")->from("tbltimekeepinglocatorslips")->order_by('id','DESC')->limit(1)->get()->result_array();
					// // var_dump($is_have);die();
					// 	$con_no = $is_have[0]['control_no'];
					// 	$date = date('Y');
					// 	$num = explode("-", $con_no);
					
					// 	if(!empty($num[2])){
					// 		$no = intval($num[2]);
					// 	}else{
					// 		$no = "";
					// 	}
					// 	// var_dump($no);
					// 	$no1 = $no + 1;
					// 	$N = "NWRB";
					// 	$control_no = $N."-".$date."-".$no1;
					// 	// var_dump($control_no);die();
					// // end

			
		
			

			$locator_slip = array(
				'locator_id' => $Locator_id,
				'filing_date' => $params['filing_date'],
				// 'position_id' => $params['position_id'],
				'is_vehicle' =>  @$params['is_vehicle'],
				'activity_name' => @$params['activity_name'],
				'purpose' => @$params['purpose'],
				'filename' => @$params['file_name'],
				'filesize' => @$params['file_size'],
				'employee_id' => $value,
				'division_id' => $params['division_id'],
				'location' => @$params['location'],
				'checked_by' => $_SESSION['last_name'] . ', ' . $_SESSION['first_name'],
				'received_by' => $_SESSION['employee_id'],
				'transaction_date' => $transdate[0],
				'transaction_date_end' => $transdate[0] == $transdate[1] ? "" : $transdate[1],
				'transaction_time' => $params['locator_transaction_time'][0],
				'is_return' => $params['is_return'],
				// 'transaction_time_end' => $params['locator_transaction_time'][1],
				'expected_time_return' => $params['expected_return_time'][0],
				'remarks' => $purpose,
				'control_no' => "",
				'sig_filename' => $params['sig_file_name'],
				'sig_filesize' => $params['sig_file_size'],

				
			);
		
			// var_dump($locator_slip);die();

			// $this->db->where('transaction_date', $params['transaction_date']);
			// $this->db->where('employee_id', $params['employee_id']);
			// $this->db->delete('tbltimekeepinglocatorslips');\
			
			if($this->db->insert('tbltimekeepinglocatorslips', $locator_slip) > 0) {
				// $this->db->where('transaction_date', $transdate[0]);
				// if($transdate[0] == $transdate[1]) $this->db->where('transaction_date_end', $transdate[1]);
				// $this->db->where('employee_number', $params['employee_number']);
				// $this->db->delete('tbltimekeepingdailytimerecordadjustments');
			
				foreach ($params['locator_transaction_time'] as $k => $v) {
					$params2 = array();
					$params2['remarks'] = $purpose;
					$params2['source_device'] = 'manual input'; 
					$params2['source_location'] = 'manual input';
					$params2['modified_by'] = Helper::get('userid');
					$params2['is_active'] = 0;
					$params2['transaction_date'] = $params['transaction_date'];
					$params2['transaction_date_end'] = $transdate[0] == $transdate[1] ? "" : $transdate[1];
					$params2['transaction_type'] = $params['locator_transaction_type'][$k];
					$params2['transaction_time'] = $params['locator_transaction_time'][$k] != "" ? $params['locator_transaction_time'][$k] : null;
					$params2['employee_number'] = $this->getScanningNumber($value);
					$this->db->insert('tbltimekeepingdailytimerecordadjustments',$params2);
				}
			}
		}
			if($this->db->trans_status() === TRUE){
				$code = "0";
				$this->db->trans_commit(); 
				$message = "Personnel Locator Slip Request successfully inserted.";
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

		function getEmployeeById($employee_id) {
			$params = array($employee_id);
			$sql = "SELECT * FROM tblemployees WHERE id = ? AND is_active = 1";
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

		function getScanningNumber($employee_id) {
			$params = array($employee_id);
			$sql = "SELECT employee_number FROM tblemployees WHERE id = ? AND is_active = 1";
			$query = $this->db->query($sql, $params);
			$employee = $query->result_array();
			return @$this->Helper->decrypt($employee[0]['employee_number'], $employee_id);
		}

		public function cancelApplication($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Application failed to cancel.";
			$data['id'] 	= isset($params['id'])?$params['id']:'';
			$sql = "	UPDATE tbltimekeepinglocatorslips SET
									status = ? 
								WHERE id = ? ";
			$params = array("CANCELLED",$data['id']);
			$this->db->query($sql,$params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully cancelled application.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;		
			}else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		function getOBSubmitted($employee_id){
			$sql = 'SELECT transaction_date
					FROM tbltimekeepinglocatorslips
					WHERE employee_id = "'.$employee_id.'"';
					
			$query = $this->db->query($sql);

			return $query->result_array();
		}

		public function fetchUsersByLocatorID($locator_id) {
			$this->db->select('
				DECRYPTER(tblemployees.first_name, "sunev8clt1234567890", tblemployees.id) AS decrypted_first_name, 
				DECRYPTER(tblemployees.last_name, "sunev8clt1234567890", tblemployees.id) AS decrypted_last_name, 
				tblfieldpositions.name as position_name, tbltimekeepinglocatorslips.employee_id, tbltimekeepinglocatorslips.sig_filename'); // Fixed "postion_id" to "position_id"
			$this->db->from('tbltimekeepinglocatorslips');
			$this->db->join('tblemployees', 'tblemployees.id = tbltimekeepinglocatorslips.employee_id');
			$this->db->join("tblfieldpositions", "tblemployees.position_id = tblfieldpositions.id","left");
			$this->db->where('tbltimekeepinglocatorslips.locator_id', $locator_id);
			
			$query = $this->db->get();
		
			if ($query->num_rows() > 0) {
				return $query->result_array();
			} else {
				return array();
			}
		}
		

	}
?>