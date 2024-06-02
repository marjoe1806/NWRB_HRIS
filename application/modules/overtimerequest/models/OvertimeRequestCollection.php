<?php
	class OvertimeRequestCollection extends Helper {
		var $select_column = null;
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
			// $columns = $this->getColumns();
			// foreach ($columns as $key => $value) {
			// 	if($key != "id")
			// 	$this->select_column[] = $this->table.'.'.$value['COLUMN_NAME'];
			// }
		}

		var $table = "tblovertimerequest";
      	var $order_column = array('',"c.last_name",'a.filing_date','a.purpose','a.activity_name','a.time_in','a.status','a.reject_remarks');
      	public function getColumns(){
      		$sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$this->table."' AND TABLE_SCHEMA='".$this->db->database."' ";
			$query = $this->db->query($sql);
			$rows = $query->result_array();
			return $rows;
      	}
		function make_query(){
			$this->select_column[] = "DECRYPTER(c.last_name, 'sunev8clt1234567890', c.id)";
			$this->select_column[] = 'a.filing_date';
			$this->select_column[] = 'a.purpose';
			$this->select_column[] = 'a.activity_name';
			$this->select_column[] = 'a.transaction_date';
			$this->select_column[] = 'a.status';
			$this->select_column[] = 'a.reject_remarks';
		    $this->db->select(
				'a.*,
				DECRYPTER(c.employee_id_number, "sunev8clt1234567890", a.employee_id) as emp_id, 
				b.name as position_name,
				c.first_name,c.middle_name,c.last_name,c.extension,
				d.department_name as department_name'
		    );
		    $this->db->from($this->table." a");
			$this->db->join("tblfieldpositions b", "a.position_id = b.id","left");
			$this->db->join("tblemployees c", "a.employee_id = c.id","left");
			$this->db->join("tblfielddepartments d", "a.division_id = d.id","left");
			if($_GET["status"] != "") $this->db->where("a.status",$_GET["status"]);
			$this->db->where("a.employee_id",Helper::get("id"));
    	    if(isset($_GET["search"]["value"])){
		    	$this->db->group_start();
		     	foreach ($this->select_column as $key => $value) {
		     		$this->db->or_like($value, $_GET["search"]["value"]);
		     	}
		        $this->db->group_end();
		    }
		    if(isset($_GET["order"])){
				$this->db->order_by($this->order_column[$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
		    }else{
				$this->db->order_by("a.transaction_date", 'DESC');
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
								$data['details'][$k]['overtime_time_in'] 	= $v2['transaction_time'];
								break;
							case '3':
								$data['details'][$k]['overtime_break_in'] = $v2['transaction_time'];
								break;
							case '2':
								$data['details'][$k]['overtime_break_out'] = $v2['transaction_time'];
								break;
							case '1':
								$data['details'][$k]['overtime_time_out'] = $v2['transaction_time'];
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

		public function addRows($params){
			// print_r($params); die();
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Overtime Request failed to insert.";
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

			$getcode = $this->db->select("GETNUMBERSEQUENCE('NWRB') as tcode")->get();
			$rowcode = $getcode->row_array();
			$transdate = explode(' - ',$params['transaction_date']);
			if($params['purpose'] == "paid"){

				$isHave = $this->db->select("*")->from('tbltransactionsprocesspayroll')->where("employee_id", $params['employee_id'])->where("division_id", $params['division_id'])->limit(1)->get()->row_array();
				// var_dump($isHave);die();
				if($isHave){
					// getting numbers of workdays
					$workdays = array();
					$type = CAL_GREGORIAN;
					$month = date('n'); // Month ID, 1 through to 12.
					$year = date('Y'); // Year in 4 digit 2009 format.
					$day_count = cal_days_in_month($type, $month, $year); // Get the amount of days
					
					
							for ($i = 1; $i <= $day_count; $i++) {
					
								$date = $year.'/'.$month.'/'.$i; //format date
								$get_name = date('l', strtotime($date)); //get week day
								$day_name = substr($get_name, 0, 3); // Trim day name to 3 chars
					
								//if not a weekend add day to array
								if($day_name != 'Sun' && $day_name != 'Sat'){
									$workdays[] = $i;
								}
					
							}
					// var_dump(count($workdays) - 1);die();
					$day = $isHave['rate'] / count($workdays) - 1;
					$hours = $day / 8;
					$rate = $hours;
					// var_dump(number_format($rate, 2));die();

					$time_in = date('h:i:s', strtotime($params['time_in']));
					$time_out = date('h:i:s', strtotime($params['time_out']));

					$datetime1 = new DateTime($time_in);
					$datetime2 = new DateTime($time_out);
					$interval = $datetime1->diff($datetime2);
					$hourly  = $interval->format('%h');//for rate
					$totalOT = $interval->format('%h:%i:%s');//for total overtime hours
					// var_dump($totalOT);die();
					$hourlyRate = $hourly * number_format($rate, 2);
					// var_dump(number_format($hourly, 2));die();
				
				}
			} else{
					$time_in = date('h:i:s', strtotime($params['time_in']));
					$time_out = date('h:i:s', strtotime($params['time_out']));

					$datetime1 = new DateTime($time_in);
					$datetime2 = new DateTime($time_out);
					$interval = $datetime1->diff($datetime2);
					// $hourly  = $interval->format('%h');//for rate
					$totalOT = $interval->format('%h:%i:%s');//for total overtime hours

					$hourlyRate = 0;
			}
			
			$overtime_slip = array(
				'control_no' =>	$rowcode["tcode"],
				'filing_date' => $params['filing_date'],
				'position_id' => $params['position_id'],
				'activity_name' => @$params['activity_name'],
				'purpose' => @$params['purpose'],
				'filename' => @$params['file_name'],
				'filesize' => @$params['file_size'],
				'employee_id' => $params['employee_id'],
				'division_id' => $params['division_id'],
				'location' => @$params['location'],
				'checked_by' => $_SESSION['last_name'] . ', ' . $_SESSION['first_name'],
				'received_by' => $_SESSION['employee_id'],
				'transaction_date' => $transdate[0],
				'transaction_time' => $params['overtime_transaction_time'],
				'time_in' => $params['time_in'],
				'time_out' => $params['time_out'],
				'totalOT' => $totalOT,
				'hourlyRate' => $hourlyRate,
				'remarks' => $purpose,

				
			);

			// var_dump($overtime_slip);die();
			// $this->db->where('transaction_date', $params['transaction_date']);
			// $this->db->where('employee_id', $params['employee_id']);
			// $this->db->delete('tbltimekeepinglocatorslips');\
			
			if($this->db->insert('tblovertimerequest', $overtime_slip) > 0) {
				// $this->db->where('transaction_date', $transdate[0]);
				// if($transdate[0] == $transdate[1]) $this->db->where('transaction_date_end', $transdate[1]);
				// $this->db->where('employee_number', $params['employee_number']);
				// $this->db->delete('tbltimekeepingdailytimerecordadjustments');

					$params2 = array();
					$params2['remarks'] = strtoupper($purpose);
					$params2['source_device'] = 'manual input';
					$params2['source_location'] = 'manual input';
					$params2['modified_by'] = Helper::get('userid');
					$params2['is_active'] = 0;
					$params2['transaction_date'] = $params['transaction_date'];
					$params2['transaction_time'] = $params['overtime_transaction_time'] != "" ? $params['overtime_transaction_time'] : null;
					$params2['employee_number'] = $this->getScanningNumber($params['employee_id']);
					$this->db->insert('tbltimekeepingdailytimerecordadjustments',$params2);
	
			}
			if($this->db->trans_status() === TRUE){
				$code = "0";
				$this->db->trans_commit();
				$message = "Overtime Request successfully inserted.";
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
			$sql = "	UPDATE tblovertimerequest SET
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

	}
?>