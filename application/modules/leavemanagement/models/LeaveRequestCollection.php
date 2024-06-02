<?php
	class LeaveRequestCollection extends Helper {
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
		var $table = "tblleavemanagement";   
		var $order_column = array(null,'first_name','date_filed','type_name','inclusive_dates','status_name','remarks');
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
				(isset($_POST["status"])?$_POST["status"]:""),
				@$_POST["search"]["value"],
				$_SESSION['employee_id'],
				1,
				(isset($_POST['order']))?1:"",
				(isset($_POST['order']['0']['column']) && isset($this->order_column[$_POST['order']['0']['column']]))?$this->order_column[$_POST['order']['0']['column']]:"",
				(isset($_POST['order']['0']['dir']))?$_POST['order']['0']['dir']:"",
				@$_POST['length'],
				@$_POST['start'],
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
			$this->sql = "CALL sp_get_employee_leave_requests(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
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
				 (isset($_POST['status'])?$_POST['status']: ""),
				 @$_POST["search"]["value"],
				 $_SESSION['employee_id'],
				 1,
				 (isset($_POST['order']))?1:"",
				 (isset($_POST['order']['0']['column']) && isset($this->order_column[$_POST['order']['0']['column']]))?$this->order_column[$_POST['order']['0']['column']]:"",
				 (isset($_POST['order']['0']['dir']))?$_POST['order']['0']['dir']:"",
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

		function get_service_record($id){			
			$emp = $this->db->select("a.*,b.name AS position_name, b.id,c.vl,c.sl,c.total as leaveCreditsTotal,d.department_name as dpt_name,d.code as dpt_code")->from("tblemployees a")
			->join("tblfieldpositions b","a.position_id = b.id","left")->join("tblleavebalance c","a.id = c.id","left")->join("tblfielddepartments d","a.division_id = d.id","left")
			->where("a.id", $id)->get()->row_array();
			$empexp = $this->db->select("*")->from("tblemployeesworkexperience")->where("employee_id",$id)->get()->result_array();
			
			$validateOffsetAdjustment = $this->db->select("offset_date_effectivity")->from("tblleavemanagement")
			->where("offset_date_effectivity >=", date("Y-m-d", strtotime(date("Y-m-d").'-1 year')))
			->where("offset_date_effectivity <=", date("Y-m-d"))
			->where("status", "4")
			->where("employee_id", $id)->get()->result_array();

			$empoffsetbal = 
				$this->db->select("SUM(CASE WHEN adjustment_offset > 0 THEN adjustment_offset ELSE offset END) as offset_balance")->from("tbldtr")
				->where("transaction_date >=", date("Y-m-d", strtotime(date("Y-m-d").'-1 year')))
				->where("transaction_date <=", date("Y-m-d"))
				->where("scanning_no", $this->Helper->decrypt($emp['employee_number'], $id))->get()
				->row();
			$totalOffset = json_decode(json_encode($empoffsetbal) , true);
			$totalOffset = array(
				"totaloffset" => $totalOffset['offset_balance']
			);

			array_push($emp, $totalOffset , true);
			return array("employee"=>$emp, "experience"=> $empexp);
		}

		function isHoliday($date){
			$this->db->select('*');
			$this->db->where('date',$date);
			$query = $this->db->get('tblfieldholidays');
			if($query->num_rows() > 0) return true;
			return false;
		}

		function getEmployeeWeeklySched($employee_id,$week_day){
			$this->sql = "CALL sp_get_employee_weekly_sched(?)";
			$query = $this->db->query($this->sql,array($employee_id));
			$result = $query->result();
			mysqli_next_result($this->db->conn_id);
			return $result;
		}
		public function populateDateInterval($daterange)
		{
			$sepdate = explode(" - ",$daterange);
				$startDate = strtotime($sepdate[0]);
                $endDate = strtotime($sepdate[1]);

                $current_year_list = array();
                $other_year_list   = array();

			for ($loopStart = $startDate; $loopStart <= $endDate; $loopStart = strtotime('+1 day', $loopStart)) {
				if($endDate >= strtotime('+1 day', $loopStart)){
					$dateList = date('Y-m-d', $loopStart);
				}
				else{
					$dateList = date('Y-m-d', $loopStart);
				}

				if(date('l', strtotime($dateList)) != 'Saturday' && date('l', strtotime($dateList)) != 'Sunday' && date('Y', strtotime($dateList)) == date('Y')){
						array_push($current_year_list, $dateList);
				}else{
					if(date('l', strtotime($dateList)) != 'Saturday' && date('l', strtotime($dateList)) != 'Sunday'){
						array_push($other_year_list, $dateList);
					}	
				}
			}
			return array('current_year_list' => $current_year_list, 'other_year_list' => $other_year_list);
		}

		public function addRows($params){
			$employee_id = $params['employee_id'];


			$helperDao = new HelperDao();
			$code = "1";
			$message = "Leave request failed to insert.";
			$params['table1']['filesize'] = $params['file_size'];
			$params['table1']['filename'] = $params['file_name'];
			$params['table1']['sig_filename'] = $params['sig_file_name'];
			$params['table1']['sig_filesize'] = $params['sig_file_size'];

			$params['table1']['division_id'] = $params['division_id'];
			$params['table1']['employee_id'] = $params['employee_id'];
			$params['table1']['position_id'] = $params['position_id'];

			$params['table1']['filing_date'] = @$params['table1']['filing_date'];
		    if($params['table1']['type'] == "offset") $params['table1']['offset_hrs'] = @$params['table3']['offset_hrs'];
			if($params['table1']['type'] == "offset") $params['table1']['offset_mins'] = @$params['table3']['offset_mins'];
			if($params['table1']['type'] == "offset") $params['table1']['offset_date_effectivity'] = @$params['table3']['date_effectivity'];
 			$params['table1']['salary'] = @$params['table1']['salary'];
			$params['table1']['type'] = @$params['table1']['type'];
			$params['table1']['vacation_loc'] = @$params['table1']['vacation_loc'];
			$params['table1']['vacation_loc_details'] = @$params['table1']['vacation_loc_details'];
			$params['table1']['spl_other_details'] = @$params['table1']['spl_other_details'];
			$params['table1']['sick_loc'] = @$params['table1']['sick_loc'];
			$params['table1']['sick_loc_details'] = @$params['table1']['sick_loc_details'];
			$params['table1']['type_study'] = @$params['table1']['type_study'];
			$params['table1']['isMedical'] = (isset($params['table1']['isMedical']) && $params['table1']['isMedical'] == "on" && $params['table1']['type'] == "monetization")? 1 : 0;
			$params['table1']['is_terminal'] = ($params['table1']['type'] == "terminal")? $params['table1']['isTerminal'] : "";
			unset($params['table1']['isTerminal']);
			$nodays = 0;

			if($params['table1']['type'] == "rehab" || $params['table1']['type'] == "maternity" || $params['table1']['type'] == "study" || $params['table1']['type'] == "benefits" || $params['table1']['type'] == "violence"){

			// if( $params['table1']['type'] == "maternity" ){
			// 	$noOfLeaves = 60;
			// 	$dateOfLeavesInputed = $this->populateDateInterval(@$params['table2'][0]);
			// 	$approvedLeaves = $this->db->select("*")->from("tblleavemanagement")
			// 		                     ->where("status","4")
			// 		                     // ->where("type", $params['table1']['type'])
			// 		                     ->where("type", 'maternity')
			// 		                     // ->where("employee_id", $params['employee_id'])
			// 		                     ->like('inclusive_dates', date('Y'))
			// 		                     ->get()->result_array();

			// 	 $current_year_lists = array();
			// 	 $other_year_lists = array();
			// 	 $dateOfLeavesApproved=  array('current_year_list' => array(), 'other_year_list' => array());

			// 	 foreach ($approvedLeaves as $k => $v) {
	  //               $populatedInterval = $this->populateDateInterval($v['inclusive_dates']);
	  //               $dateOfLeavesApproved['current_year_list'] = array_merge($dateOfLeavesApproved['current_year_list'], $populatedInterval['current_year_list']);
	  //               $dateOfLeavesApproved['other_year_list']   = array_merge($dateOfLeavesApproved['other_year_list'], $populatedInterval['other_year_list']);
			// 	}			
			// 	var_dump($dateOfLeavesInputed);

			// 	if(count($dateOfLeavesInputed['current_year_list']) > $noOfLeaves - count($dateOfLeavesApproved['current_year_list'])){
			// 		 	$code = "1";
			// 			$message = "Insufficient ".$typeName." Balance.<br> <b>Leave balance/s: ". $totalBal ." </b>";
			// 		 	$this->ModelResponse($code, $message);
			// 		 	return false;
			// 	}

				$sepdate = explode(" - ",@$params['table2'][0]);
				$dr1 = $sepdate[0];
				$dr2 = $sepdate[1];
				$dr1 = strtotime($dr1);
				$dr2 = strtotime($dr2);
				$datediff = $dr2 - $dr1;
				$params['table1']['number_of_days'] = (abs(round($datediff / (60 * 60 * 24)))) + 1;
				$params['table1']['inclusive_dates'] = @$params['table2'][0];
			} else if($params['table1']['type'] == "monetization") {
				$params['table1']['number_of_days'] = $params['table1']['number_of_days'];
				$params['table1']['amount_monetized'] = round($params['table1']['salary'] * $params['table1']['number_of_days'] * .0481927,2);
			
			} else if($params['table1']['type'] == "special" || $params['table1']['type'] == "force" || $params['table1']['type'] == "calamity") {
				if($params['table1']['type'] == "special") $noOfLeaves = 3; $typeName = 'Special Leave';
				if($params['table1']['type'] == "force") $noOfLeaves = 5; $typeName = 'Force Leave';
				if($params['table1']['type'] == "calamity") $noOfLeaves = 5; $typeName = 'Calamity Leave';
				
				$datalist = array("type" => $params['table1']['type'],"noOfLeave" => $noOfLeaves,"employee_id" => $params['employee_id']);

				$listSpecialLeaveApproved = array();
				$specialLeaveApproved = $this->db->select("*")->from("tblleavemanagement")
				                     ->where("status","4")
				                     ->where("type", $params['table1']['type'])
				                     ->where("employee_id", $params['employee_id'])
				                     ->like('inclusive_dates', date('Y'))
				                     ->get()->result_array();

				 foreach ($specialLeaveApproved as $k => $v) {
				 	$inclusiveDatesArr = explode(',', $v['inclusive_dates']);
				 	 foreach ($inclusiveDatesArr as $k2 => $v2) {
				 	 	if ($v2 != " ") array_push($listSpecialLeaveApproved, $v2);
				 	 }
				 }
				 $totalBal = $noOfLeaves - count($listSpecialLeaveApproved);
				 if(count($params['table2']['days_of_leave']) > $noOfLeaves - count($listSpecialLeaveApproved)){
				 	$code = "1";
					$message = "Insufficient ".$typeName." Balance.<br> <b>Leave balance/s: ". $totalBal ." </b>";
				 	$this->ModelResponse($code, $message);

				 	return false;

				 }
				// $params['table1']['number_of_days'] = sizeof($params['table2']['days_of_leave']);
				$params['table1']['inclusive_dates'] = implode(", ",$params['table2']['days_of_leave']);

				// $listSpecialLeaveApproved = array();
				// $specialLeaveApproved = $this->db->select("*")->from("tblleavemanagement")
				//                      ->where("status","4")
				//                      ->where("type","special")
				//                      ->where("employee_id", $params['employee_id'])
				//                      ->like('inclusive_dates', date('Y'))
				//                      ->get()->result_array();

				//  foreach ($specialLeaveApproved as $k => $v) {
				//  	$inclusiveDatesArr = explode(',', $v['inclusive_dates']);
				//  	 foreach ($inclusiveDatesArr as $k2 => $v2) {
				//  	 	if ($v2 != " ") array_push($listSpecialLeaveApproved, $v2);
				//  	 }
				//  }
				//  $totalBal = 3 - count($listSpecialLeaveApproved);
				//  if(count($params['table2']['days_of_leave']) > 3 - count($listSpecialLeaveApproved)){
				//  	$code = "1";
				// 	$message = "Insufficient Special Leave Balance.<br> <b>Leave balance/s: ". $totalBal ." </b>";
				//  	$this->ModelResponse($code, $message);

				//  	return false;

				//  }
				// $params['table1']['number_of_days'] = sizeof($params['table2']['days_of_leave']);
				// $params['table1']['inclusive_dates'] = implode(", ",$params['table2']['days_of_leave']);
				                     // ->num_rows();
				// die();
	
			}else if($params['table1']['type'] == "vacation" || $params['table1']['type'] == "paternity"){
				$params['table1']['inclusive_dates'] = $params['table2']['days_of_leave'][0];
			// }else if($params['table1']['type'] == "vacation"){
			// 	$params['table2']['days_of_leave'] = explode(" - ",$params['table2']['days_of_leave'][0]);
			// 	$params['table1']['number_of_days'] = sizeof($params['table2']['days_of_leave']);
			// 	$params['table1']['inclusive_dates'] = implode(", ",$params['table2']['days_of_leave']);
			}else{
				$params['table1']['inclusive_dates'] = $params['table2']['days_of_leave'][0];
				// $params['table1']['number_of_days'] = sizeof($params['table2']['days_of_leave']);
				// $params['table1']['inclusive_dates'] = implode(", ",$params['table2']['days_of_leave']);
			}
			$params['table1']['commutation'] = @$params['table1']['commutation'];
			$params['table1']['date_filed'] = date('Y-m-d', strtotime($params['table1']['filing_date']));
			unset($params['table1']["id"]);

			$transac_date1 = "";
			$transac_date2 = "";
			$datenextmonth = date("Y-m-d", strtotime("+1 month"));
			$currmonth = date("m", strtotime($datenextmonth));
			if (strpos($params['table1']['inclusive_dates'], ', ') !== false ){
				$transac_date = explode(', ', $params['table1']['inclusive_dates']);
				sort($transac_date);
				$transac_date1 = $transac_date[0];
				$transac_date2 = $transac_date[1];
				$inclusivemonth1 = date("m", strtotime($transac_date1));
				$inclusivemonth2 = date("m", strtotime($transac_date2));
	
				// $dates = explode(', ', $params['table1']['inclusive_dates']);
				// $nodays = (int)$params['table1']['number_of_days'];
				// $arraydates = array();
				// for ($i=0; $i < $nodays ; $i++) { 
				// 	$insertdate = date("Y-m-d", strtotime($dates[0].' + '.$i.' day'));
				// 	array_push($arraydates,$insertdate);
				// }
				$dates = explode(', ', $params['table1']['inclusive_dates']);
				// $nodays = (int)$params['table1']['number_of_days'];
				// $arraydates = array();
				// for ($i=0; $i < $nodays ; $i++) { 
				// 	$insertdate = date("Y-m-d", strtotime($dates[0].' + '.$i.' day'));
				// 	array_push($arraydates,$insertdate);
				// }
				$arraydates = $this->getAvailabledays($dates);
				$arraydates = array_values($arraydates);

				$filed_dates = array();
				$getAppSubmit = $this->getLeaveSubmitted($params['table1']['employee_id']);
				$date_fileds = array();
				$Datenow = date("Y-m-d");
				if(count($getAppSubmit) > 0){
					foreach ($getAppSubmit as $key => $value) {
						if(!in_array($value['date_filed'],$date_fileds, true)){
					    	array_push($date_fileds,$value['date_filed']);
						}
						$dates_inclusive = explode(', ', $value['inclusive_dates']);
						foreach ($dates_inclusive  as $key => $value2) {
							if(!in_array($value2,$filed_dates, true)){
							       array_push($filed_dates,$value2);
							}
						}
					}

					if(array_intersect($arraydates, $filed_dates)){
						$this->ModelResponse($code, "Date selected is already filed.");
						return false;
					}
					// if(in_array($Datenow, $date_fileds)){ // issue here cannot file multiple dates
					// 	die('hit');
					// 	// $this->ModelResponse($code, "Date selected is already filed.");
					// 	// $this->ModelResponse($code, "You can file once a day.");
					// 	return false;
					// }
				}

				if($inclusivemonth1 != $inclusivemonth2){
					$this->ModelResponse($code, "Select date within the period.");
				 	return false;
				}

			}else if(strpos($params['table1']['inclusive_dates'], ' - ') !== false ){
				$transac_date = explode(' - ', $params['table1']['inclusive_dates']);
				$transac_date1 = $transac_date[0];
				$transac_date2 = $transac_date[1];
				$inclusivemonth1 = date("m", strtotime($transac_date1));
				$inclusivemonth2 = date("m", strtotime($transac_date2));

				$dates = explode(' - ', $params['table1']['inclusive_dates']);
				// $nodays = (int)$params['table1']['number_of_days'];
				// $arraydates = array();
				// for ($i=0; $i < $nodays ; $i++) { 
				// 	$insertdate = date("Y-m-d", strtotime($dates[0].' + '.$i.' day'));
				// 	array_push($arraydates,$insertdate);
				// }

				$arraydates = $this->getAvailabledays($dates);
				$arraydates = array_values($arraydates);

				$params['table2']['days_of_leave'] = $arraydates;
				$params['table1']['inclusive_dates'] = $params['table1']['inclusive_dates'] = implode(", ",$arraydates);
				$filed_dates = array();
				$getAppSubmit = $this->getLeaveSubmitted($params['table1']['employee_id']);
				$date_fileds = array();
				$Datenow = date("Y-m-d");
				if(count($getAppSubmit) > 0){
					foreach ($getAppSubmit as $key => $value) {
						if(!in_array($value['date_filed'],$date_fileds, true)){
					    	array_push($date_fileds,$value['date_filed']);
						}
						$dates_inclusive = explode(' - ', $value['inclusive_dates']);
						foreach ($dates_inclusive  as $key => $value2) {
							if(!in_array($value2,$filed_dates, true)){
							    array_push($filed_dates,$value2);
							}
						}
					}

					if(array_intersect($arraydates, $filed_dates)){
						$this->ModelResponse($code, "Date selected is already filed.");
						return false;
					}
					// if(in_array($Datenow, $date_fileds)){
					// 	$this->ModelResponse($code, "You can file once a day.");
					// 	return false;
					// }
				}

				if($inclusivemonth1 != $inclusivemonth2){
					$this->ModelResponse($code, "Select date within the period.");
				 	return false;
				}
			}else{
				$filed_dates = array();
				$getAppSubmit = $this->getLeaveSubmitted($params['table1']['employee_id']);
				$date_fileds = array();
				$Datenow = date("Y-m-d");
				if(count($getAppSubmit) > 0){
					foreach ($getAppSubmit as $key => $value) {
						if(!in_array($value['inclusive_dates'],$filed_dates, true)){
						    array_push($filed_dates,$value['inclusive_dates']);
						}
						if(!in_array($value['date_filed'],$date_fileds, true)){
						    array_push($date_fileds,$value['date_filed']);
						}
					}
					if(in_array($params['table1']['inclusive_dates'], $filed_dates)){
						$this->ModelResponse($code, "Date selected is already filed.");
						return false;
					}
					// if(in_array($Datenow, $date_fileds)){
					// 	$this->ModelResponse($code, "You can file once a day.");
					// 	return false;
					// }
				}

				// $inclusivemonth = date("m", strtotime($params['table1']['inclusive_dates']));
				// if($inclusivemonth == $currmonth){
				// 	$this->ModelResponse($code, "Select date within the period.");
				//  	return false;
				// }
			}
			$this->db->trans_begin();
			$this->db->insert('tblleavemanagement', $params['table1']);
			$id = $this->db->insert_id();
			$days = array();
			if($params['table1']['type'] == "rehab" || $params['table1']['type'] == "maternity" || $params['table1']['type'] == "study" || $params['table1']['type'] == "benefits" || $params['table1']['type'] == "violence"){
				$days[] = array(
					"id"=> $id,
					"days_of_leave"  => $params['table2'][0]
				);
			}else{
				if($params['table1']['type'] != "monetization"){
					foreach ($params['table2']['days_of_leave'] as $k => $v) {
						if($params['table2']['days_of_leave'][$k] != ""){
							$days[] = array(
								"id"=> $id,
								"days_of_leave"  => $params['table2']['days_of_leave'][$k]
							);
						}
					}
				}
			}
			if($params['table1']['type'] != "monetization" && $params['table1']['type'] != "terminal" && $params['table1']['type'] != "offset" ) 
				$this->db->insert_batch('tblleavemanagementdaysleave', $days); 
			if($this->db->trans_status() === TRUE){
				$code = "0";
				$this->db->trans_commit();
				$message = "Successfully inserted Leave Request.";
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
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Leave request failed to update.";
			if(isset($params['file_name'])) {
				$params['table1']['filesize'] = $params['file_size'];
				$params['table1']['filename'] = $params['file_name'];
			}
			
			$params['table1']['division_id'] = $params['division_id'];
			$params['table1']['employee_id'] = $params['employee_id'];
			$params['table1']['position_id'] = $params['position_id'];

			$params['table1']['filing_date'] = @$params['table1']['filing_date'];
			$params['table1']['salary'] = @$params['table1']['salary'];
			$params['table1']['type'] = @$params['table1']['type'];
			$params['table1']['vacation_loc'] = @$params['table1']['vacation_loc'];
			$params['table1']['vacation_loc_details'] = @$params['table1']['vacation_loc_details'];
			$params['table1']['spl_other_details'] = @$params['table1']['spl_other_details'];
			$params['table1']['sick_loc'] = @$params['table1']['sick_loc'];
			$params['table1']['sick_loc_details'] = @$params['table1']['sick_loc_details'];
			$params['table1']['type_study'] = @$params['table1']['type_study'];
			$params['table1']['number_of_days'] = (isset($params['table1']['number_of_days'])) ? sizeof($params['table1']['number_of_days']):0;
			$params['table1']['commutation'] = @$params['table1']['commutation'];
			$params['table1']['date_filed'] = date('Y-m-d', strtotime($params['table1']['filing_date']));

			$this->db->trans_begin();
			//Update Monitoring
			$this->db->where('id', $params['table1']['id']);
			$this->db->update('tblleavemanagement', $params['table1']);
			//Delete adjustments
			// $this->db->where('leave_id', $params['table1']['id']);
			// $this->db->delete('tbltimekeepingdailytimerecordadjustments');

			// $leave_days = @$params['table2']['days_of_leave'];
			//Add adjustments
			$id = $params['table1']['id'];
			// $adjustments = array();
			// foreach ($leave_days as $k1 => $v1) {
			// 	if(!$this->isHoliday($v1)){
			// 		$week_day =  date("l", strtotime($v1));
			// 		$employee_sched = $this->getEmployeeWeeklySched($params['employee_id'],$week_day);
			// 		$employee_sched[0]->start_time = "08:00:00";
			// 		$employee_sched[0]->break_start_time = "12:00:00";
			// 		$employee_sched[0]->break_end_time = "13:00:00";
			// 		$employee_sched[0]->end_time = "17:00:00";
			// 		// var_dump($employee_sched);die();
			// 		if(isset($employee_sched) && sizeof($employee_sched) > 0){
			// 			//Check In
			// 			$adjustments[] = array(
			// 				"employee_number"=> $employee_sched[0]->employee_number,
			// 				"transaction_date"=> $v1,
			// 				"transaction_time"=>$employee_sched[0]->start_time,
			// 				"transaction_type"=>0,
			// 				"source_location" =>"manual input",
			// 				"source_device" =>"manual input",
			// 				"remarks"=>str_replace("_", " ", @$params['table1']['type']),
			// 				"leave_id"=>$id,
			// 				"modified_by"=>Helper::get('userid')
			// 			);
			// 			//Lunch Out
			// 			$adjustments[] = array(
			// 				"employee_number"=> $employee_sched[0]->employee_number,
			// 				"transaction_date"=> $v1,
			// 				"transaction_time"=>$employee_sched[0]->break_start_time,
			// 				"transaction_type"=>2,
			// 				"source_location" =>"manual input",
			// 				"source_device" =>"manual input",
			// 				"remarks"=>str_replace("_", " ", @$params['table1']['type']),
			// 				"leave_id"=>$id,
			// 				"modified_by"=>Helper::get('userid')
			// 			);
			// 			//Lunch In
			// 			$adjustments[] = array(
			// 				"employee_number"=> $employee_sched[0]->employee_number,
			// 				"transaction_date"=> $v1,
			// 				"transaction_time"=>$employee_sched[0]->break_end_time,
			// 				"transaction_type"=>3,
			// 				"source_location" =>"manual input",
			// 				"source_device" =>"manual input",
			// 				"remarks"=>str_replace("_", " ", @$params['table1']['type']),
			// 				"leave_id"=>$id,
			// 				"modified_by"=>Helper::get('userid')
			// 			);
			// 			//Check Out
			// 			$adjustments[] = array(
			// 				"employee_number"=> $employee_sched[0]->employee_number,
			// 				"transaction_date"=> $v1,
			// 				"transaction_time"=>$employee_sched[0]->end_time,
			// 				"transaction_type"=>1,
			// 				"source_location" =>"manual input",
			// 				"source_device" =>"manual input",
			// 				"remarks"=>str_replace("_", " ", @$params['table1']['type']),
			// 				"leave_id"=>$id,
			// 				"modified_by"=>Helper::get('userid')
			// 			);
			// 		}
			// 	}
			// }
			// $this->db->insert_batch('tbltimekeepingdailytimerecordadjustments', $adjustments);
		
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
			}	
			else {
				$code = "0";
				$this->db->trans_commit();
				$message = "Successfully updated Leave Request.";
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
			$this->sql = "CALL sp_get_employee_leave_days(?)";
			$query = $this->db->query($this->sql,array($id));
			$result = $query->result();
			mysqli_next_result($this->db->conn_id);
			$data['details'] = $result;
			if(sizeof($result) > 0){
				$code = "0";
				$message = "Successfully fetched leave dates.";
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

		public function fetchLeaveApprovals($id, $employee_id){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			// $this->sql = "CALL sp_get_employee_leave_approvals(?)";
			$this->sql = "SELECT c.position_designation,
				b.name as position_title,
				c.position_id,
				a.approver,
				a.approve_type,
				DECRYPTER(c.first_name, 'sunev8clt1234567890', c.id) as first_name,
				DECRYPTER(c.middle_name, 'sunev8clt1234567890', c.id) AS middle_name,
				DECRYPTER(c.last_name, 'sunev8clt1234567890', c.id) as last_name,
				DECRYPTER(c.extension, 'sunev8clt1234567890', c.id) as extension
				FROM 
				tblemployeesleaveapprovers AS a
				#LEFT JOIN tblleavemanagementapprovals AS l ON l.approved_by = a.approver
				LEFT JOIN tblemployees c ON a.approver = c.id
				LEFT JOIN tblfieldpositions b ON c.position_id = b.id
				WHERE a.employee_id = ?
			";
			// $query = $this->db->query($this->sql,array($id));
			$query = $this->db->query($this->sql,array($employee_id));
			$result = $query->result();
			// mysqli_next_result($this->db->conn_id);
			$data['approvers'] = $result;
			if(sizeof($result) > 0){
				$this->sql = "SELECT
					c.position_designation,
					b.name as position_title,
					c.position_id,
					a.approval_type,
					DECRYPTER(c.first_name, 'sunev8clt1234567890', c.id) as first_name,
					DECRYPTER(c.middle_name, 'sunev8clt1234567890', c.id) AS middle_name,
					DECRYPTER(c.last_name, 'sunev8clt1234567890', c.id) as last_name,
					DECRYPTER(c.extension, 'sunev8clt1234567890', c.id) as extension,
					a.file_name,
					a.approved_by,
					a.employee_id
				FROM tblleavemanagementapprovals a 
				left join tblfieldpositions b on a.position = b.id
				left join tblemployees c on a.employee_id = c.id
				WHERE a.request_id = ?;
				";
				$query = $this->db->query($this->sql,array($id));
				$result = $query->result();
				$data['approved'] = $result;

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

		public function cancelApplication($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Application failed to cancel.";
			$data['id'] 	= isset($params['id'])?$params['id']:'';
			$data['status'] = 7;
			$sql = "	UPDATE tblleavemanagement SET status = ?  WHERE id = ? ";
			$params = array(7,$data['id']);
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

		function getLeaveSubmitted($employee_id){
			$sql = 'SELECT inclusive_dates, date_filed FROM tblleavemanagement WHERE employee_id = "'.$employee_id.'" AND status <= 5';					
			$query = $this->db->query($sql);
			return $query->result_array();
		}

		function getNextMonthLeave($employee_id, $month, $year, $type){
			$month += 1;
			$sql = "SELECT SUM(number_of_days) AS number_of_days FROM tblleavemanagement a
					WHERE	(
						((SELECT MONTH(b.days_of_leave) FROM tblleavemanagementdaysleave b WHERE a.id = b.id AND YEAR(b.days_of_leave) = '".$year."' LIMIT 0,1) = '".$month."')
								OR
								((SELECT MONTH(SUBSTRING_INDEX(b.days_of_leave,' - ',-1)) FROM tblleavemanagementdaysleave b WHERE a.id = b.id AND YEAR(SUBSTRING_INDEX(b.days_of_leave,' - ',-1)) = '".$year."' LIMIT 0,1) = '".$month."' )
							) AND (SELECT YEAR(b.days_of_leave) FROM tblleavemanagementdaysleave b WHERE a.id = b.id LIMIT 0,1) = '".$year."' AND employee_id = '".$employee_id."' AND type = '".$type."' AND a.is_active = 1 AND status = 5";
			$query = $this->db->query($sql);
			return $query->result_array();
		}

		function countHoliday($weekdays, $number_of_days) {
			$weekdays = implode("', '",$weekdays);
			$query = $this->db->query("SELECT COUNT(*) as no_days FROM tblfieldholidays WHERE date IN ('".$weekdays."') AND (holiday_type != 'Suspension' OR (holiday_type = 'Suspension' && time_suspension = '08:00 AM')) ")->row_array();

			if($this->db->trans_status() === TRUE){
				$count = $query["no_days"];
				$code = "0";
				$this->db->trans_commit();
				$message = "Successfully calculate weekdays.";
				$data['number_of_days'] = ($number_of_days - intval($count));
				$this->ModelResponse($code, $message, $data);
				return true;		
			}	
			else {
				$this->db->trans_rollback();
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		function getAvailabledays($dates) {
			$start = new DateTime($dates[0]);
			$end = new DateTime($dates[1]);
			$oneday = new DateInterval("P1D");
			$weekdays = array();

			foreach(new DatePeriod($start, $oneday, $end->add($oneday)) as $day) {
				$day_num = $day->format("N"); /* 'N' number days 1 (mon) to 7 (sun) */
				if($day_num < 6) { /* weekday */
					array_push($weekdays, $day->format("Y-m-d"));
				} 
			} 

			$days = implode("', '",$weekdays);
			$query = $this->db->query("SELECT date as holiday_dates FROM tblfieldholidays WHERE date IN ('".$days."') AND (holiday_type != 'Suspension' OR (holiday_type = 'Suspension' && time_suspension = '08:00 AM')) ")->result_array();
			
			$holidays = array();
			for ($i=0; $i < sizeof($query); $i++) { 
				array_push($holidays, $query[$i]['holiday_dates']);
			}

			$remaining_weekdays = array_diff($weekdays, $holidays);
			return $remaining_weekdays;
		}
	}
?>