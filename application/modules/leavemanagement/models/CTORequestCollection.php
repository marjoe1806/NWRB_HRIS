<?php
	class CTORequestCollection extends Helper {
		var $select_column = null; 
		var $sql = "";
		var $selectRequestParams = array();
		public function __construct() {
			$this->load->model('Helper');
			$this->load->model('HelperDao');
			ModelResponse::busy();
			$params['table1'] = array();
			$columns = $this->getColumns();
			foreach ($columns as $key => $value)
				$this->select_column[] = $this->table.'.'.$value->COLUMN_NAME;
		}

		//Fetch
		var $table = "tbloffsetting";   
		var $order_column = array(null,'first_name','date_filed','type','offset_date_effectivity','status_name','remarks');
      	public function getColumns(){
      		// $sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$this->table."' AND TABLE_SCHEMA='".$this->db->database."' ";
			// $query = $this->db->query($sql);
			// $rows = $query->result_array();
			
			$this->sql = "CALL sp_get_leave_mngt_columns()";
			$query = $this->db->query($this->sql);
			$result = $query->result();
			mysqli_next_result($this->db->conn_id);
			return $result; 
      	}

      	function make_query(){ 
			$this->selectRequestParams = array(
				'',
				(isset($_GET["status"])?$_GET["status"]:""),
				(isset($_GET["search"]["value"])?$_GET["search"]["value"]:""),
				$_SESSION['employee_id'],
				1,
				(isset($_GET['order']))?1:"",
				(isset($_GET['order']['0']['column']) && isset($this->order_column[$_GET['order']['0']['column']]))?$this->order_column[$_GET['order']['0']['column']]:"",
				(isset($_GET['order']['0']['dir']))?$_GET['order']['0']['dir']:"",
				@$_GET['length'],
				@$_GET['start'],
				"",
				0,
				"",
				0,
				0,
				$_SESSION["division_id"],
				$_SESSION["id"],
				0,
				0,
				0
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

		function get_filtered_data(){  
		     $this->make_query(); 
			 $this->selectRequestParams = array(
				 '',
				 (isset($_GET['status'])?$_GET['status']: ""),
				 (isset($_GET["search"]["value"])?$_GET["search"]["value"]:""),
				 $_SESSION['employee_id'],
				 1,
				 (isset($_GET['order']))?1:"",
				 (isset($_GET['order']['0']['column']) && isset($this->order_column[$_GET['order']['0']['column']]))?$this->order_column[$_GET['order']['0']['column']]:"",
				 (isset($_GET['order']['0']['dir']))?$_GET['order']['0']['dir']:"",
				 '',
				 '',
				 "",
				 0,
				 "",
				 0,
				 0,
				 $_SESSION["division_id"],
				 $_SESSION["id"],
				0,
				0,
				0
			 );
			 $query = $this->db->query($this->sql,$this->selectRequestParams);
			 $result = $query->result();
			 mysqli_next_result($this->db->conn_id); 
		     return sizeof($result);   

		} 

		function get_all_data(){  
		    $this->db->select($this->table."*");  
			$this->db->from($this->table);
			$this->db->where("employee_id",$_SESSION['employee_id']);
		    return $this->db->count_all_results();  
		}  
		
		public function addRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Compensatory Time Off Request failed to insert.";
			$this->db->trans_begin();
			// $rowcode = $getcode->row_array();
			$arr_inclusive_dates = explode(' - ',$params['inclusive_dates']);
			$inclusive_dates = implode(", ",$this->getDatesFromRange($arr_inclusive_dates[0],$arr_inclusive_dates[1]));

			for ($i=0; $i < $params['number_of_days']; $i++) { 
				$day = date("l", strtotime($arr_inclusive_dates[0].' + '.$i.' day'));
				
				if($day == "Saturday" || $day == "Sunday"){
					$code = "1";
					$message = "Please do not select weekend days.";
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
					return true;
				}
			}

			$cto = array(
				'source_location' => 'manual_input',
				'total_offset' => $params['total_offset'],
				'type' => 'offset',
				'employee_id' => $params['employee_id'],
				'division_id' => $params['division_id'],
				'position_id' => $params['position_id'],
				'offset_date_effectivity' => $params['inclusive_dates'],
				'date_filed' => date('Y-m-d', strtotime($params['filing_date'])),
				'filing_date' => $params['filing_date'],
				'offset_hrs' => (int)$params['no_of_hrs'],
				'offset_mins' => $params['no_of_mins'],
				'sig_filename' => $params['sig_file_name'],
				'sig_filesize' => $params['sig_file_size'],
				'number_of_days' => $params['number_of_days']
			);
			$this->db->insert($this->table, $cto);
			if($this->db->trans_status() === TRUE){
				$code = "0";
				$this->db->trans_commit();
				$message = "Compensatory Time Off Request successfully inserted.";
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
			$sql = "UPDATE tbloffsetting SET
									status = ? 
								WHERE id = ? ";
			$params = array(8,$data['id']);
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
				// ->where("transaction_date >=", date("Y-m-d", strtotime(date("Y-m-d").'-1 year')))
				->where("transaction_date >=", date("Y-m-d", strtotime('first day of January '.date('Y') )))
				->where("transaction_date <=", date("Y-m-d"))
				->where("CAST(scanning_no AS INT)=", (int)$this->Helper->decrypt($emp['employee_number'], $id))->get()
				->row();
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