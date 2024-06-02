<?php
class Collections extends Helper {
	var $select_columnpromotion = null;
	var $select_column_pending = null;
	var $select_column_approved = null;
	var $selectRequestParams = array();
	var $select_column = null;
	public function __construct() {
		ModelResponse::busy();
	}
			
	public function hasRows($linkid) {			
		$data = json_encode(array("LinkId"=>$linkid));
		$ret = parent::serviceCall("MLINK",$data);
		
		if($ret != null) {
			if($ret->Code == 0) {
				$this->ModelResponse($ret->Code, $ret->Message);
				return true;
			} else {
				$this->ModelResponse($ret->Code, $ret->Message);
			}			
		}
		return false;
	}

	//set orderable columns in employee list
	var $tableLocator = "tbltimekeepinglocatorslips"; 
	var $tableTO = "tbltravelorder"; 
	var $tablepromotion = "tblemployees";   
	var $order_columnpromotion = array(
				"CAST(DECRYPTER(tblemployees.employee_id_number,'sunev8clt1234567890',tblemployees.id) AS int)",
				"CONCAT(DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id),', ',DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.middle_name,'sunev8clt1234567890',tblemployees.id))",
				"tblemployees.pay_basis",
				// "tblfieldpositions.name",
				"tblfielddepartments.department_name",
				"tblemployees.start_date",
				"tblemployees.date_of_permanent",
				"",
	);

   //    //set searchable parameters in tblemployees table
   //    	public function getColumnspromotion(){
			// $rows = array(
			// 	'employee_number',
			// 	'employee_id_number',
			// 	'last_name',
			// 	'first_name',
			// 	'middle_name',
			// 	'pay_basis',
			// 	'position_name',
			// 	'department_name',
			// 	'start_date',

			// );
			// return $rows; 
   //    	}

	public function getTotalEmployees(){
		if(isset($_POST["asofdate"])){
			$dt = $_POST["asofdate"];
			$emp = $this->db->query('
								select
								(select COUNT(*) as tot from tblemployees where TIMESTAMP(start_date) <= TIMESTAMP("'.$dt.'")) as emp,
								(select COUNT(*) as tot from tblemployees where TIMESTAMP(start_date) <= TIMESTAMP("'.$dt.'") AND pay_basis = "Permanent" AND (end_date IS NULL OR TIMESTAMP(end_date) > TIMESTAMP("'.$dt.'"))) as permanent,
								(select COUNT(*) as tot from tblemployees where TIMESTAMP(start_date) <= TIMESTAMP("'.$dt.'") AND pay_basis != "Permanent" AND (end_date IS NULL OR TIMESTAMP(end_date) > TIMESTAMP("'.$dt.'"))) as coterminus,
								(select COUNT(*) as tot from tblemployees where TIMESTAMP(start_date) <= TIMESTAMP("'.$dt.'") AND gender= "Male" AND (end_date IS NULL OR TIMESTAMP(end_date) > TIMESTAMP("'.$dt.'"))) as male,
								(select COUNT(*) as tot from tblemployees where TIMESTAMP(start_date) <= TIMESTAMP("'.$dt.'") AND gender= "Female" AND (end_date IS NULL OR TIMESTAMP(end_date) > TIMESTAMP("'.$dt.'"))) as female
							FROM DUAL')->row_array();
		}else{
			$emp = $this->db->query('
								select
								(select COUNT(*) as tot from tblemployees) as emp,
								(select COUNT(*) as tot from tblemployees WHERE pay_basis = "Permanent") as permanent,
								(select COUNT(*) as tot from tblemployees WHERE pay_basis != "Permanent") as coterminus,
								(select COUNT(*) as tot from tblemployees WHERE gender= "Male") as male,
								(select COUNT(*) as tot from tblemployees WHERE gender= "Female") as female
							FROM DUAL')->row_array();
		}
		$data = array(
			"totemp"=>$emp['emp'],
			"totemppermanent"=>$emp['permanent'],
			"totempcoterminous"=>$emp['coterminus'],
			"totempmale"=>$emp['male'],
			"totempfemale"=>$emp['female']
		);
		return $data;
	}

	public function getTotalCTO(){
		$this->selectRequestParams = array(
			(isset($_POST["LeaveType"]))?@$_POST["LeaveType"]:"",
			(isset($_POST["Status"]))?@$_POST["Status"]:"",
			(isset($_POST["search"]["value"]))?@$_POST["search"]["value"]:"",
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
		$this->sql = "CALL sp_get_employee_cto_for_approval(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$query = $this->db->query($this->sql,$this->selectRequestParams);
		$result = $query->result();
		mysqli_next_result($this->db->conn_id);
		return sizeof($result);
	}

	public function getTotalLeave(){
		$this->selectRequestParams = array(
			(isset($_POST["LeaveType"]))?@$_POST["LeaveType"]:"",
			(isset($_POST["Status"]))?@$_POST["Status"]:"",
			(isset($_POST["search"]["value"]))?@$_POST["search"]["value"]:"",
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
		$this->sql = "CALL sp_get_employee_leave_for_approval(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$query = $this->db->query($this->sql,$this->selectRequestParams);
		$result = $query->result();
		mysqli_next_result($this->db->conn_id);
		return sizeof($result);
	}

	public function getTotalLocator(){  
		$this->select_column[] = 'tblemployees.first_name';
		$this->select_column[] = $this->tableLocator.'.filing_date';
		$this->select_column[] = $this->tableLocator.'.purpose';
		$this->select_column[] = $this->tableLocator.'.activity_name';
		$this->select_column[] = $this->tableLocator.'.transaction_date';
		$this->select_column[] = $this->tableLocator.'.status';
		$this->select_column[] = $this->tableLocator.'.remarks';
		$this->db->select('a.*, b.*, b.id as salt,
			DECRYPTER(b.employee_id_number, "sunev8clt1234567890", b.id) as emp_id, 
			c.name as position_name, d.department_name');
		$this->db->from($this->tableLocator." a");  
		$this->db->join("tblemployees b", "b.id = a.employee_id","left");
		$this->db->join("tblfieldpositions c", "a.position_id = c.id","left");
		$this->db->join("tblfielddepartments d", "a.division_id = d.id","left");
		$joinsql = "(e.employee_id = b.id AND a.status = 'PENDING' AND e.approve_type = 2 AND e.is_active = 1) OR
					(e.employee_id = b.id AND a.status = 'FOR APPROVAL' AND e.approve_type = 4 AND e.is_active = 1) OR
					(e.employee_id = b.id AND a.status = 'APPROVED' AND e.approve_type = 5 AND e.is_active = 1)";
		$this->db->join("tblemployeesobapprovers e", $joinsql,"left");
		if(isset($_POST["search"]["value"])) {  
			$this->db->group_start();
			foreach ($this->select_column as $key => $value) {
				if($value == "b.first_name" || $value == "b.last_name" || $value == "b.middle_name" || $value == "b.extension")  {
					$this->db->or_like("DECRYPTER($value,'sunev8clt1234567890',b.id)", $_POST["search"]["value"]);  
				}else{
					$this->db->or_like($value, $_POST["search"]["value"]);  
				}
			}
			$this->db->or_like("CONCAT(DECRYPTER(b.last_name,'sunev8clt1234567890',b.id),' ',DECRYPTER(b.extension,'sunev8clt1234567890',b.id),', ',DECRYPTER(b.first_name,'sunev8clt1234567890',b.id),' ',DECRYPTER(b.middle_name,'sunev8clt1234567890',b.id))",$_POST["search"]["value"]);
			$this->db->group_end(); 
		}
		$this->db->where("e.approver",$_SESSION['id']);
		if(isset($_POST['status']) && $_POST['status'] != '') $this->db->where("a.status",$_POST['status']);
		if(isset($_POST["order"])) $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		else $this->db->order_by("a.transaction_date DESC");
		$query = $this->db->get();
		$result = $query->result();
		return sizeof($result);
	}

	public function getTotalTO(){
		$this->select_column[] = "DECRYPTER(c.last_name, 'sunev8clt1234567890', c.id)";
		$this->select_column[] = 'a.vehicle_no';
		$this->select_column[] = 'a.driver';
		$this->select_column[] = 'a.destination';
		$this->select_column[] = 'a.duration';
		$this->db->select('a.*,c.first_name,c.middle_name,c.last_name,d.approver');
		$this->db->join("tblemployees c", "a.employee_id = c.id","left");		
		$joinsql = "d.employee_id = c.id AND a.status = d.approve_type AND d.is_active = 1";
		$this->db->join("tbltravelorderapprover d", $joinsql,"left");
		$joinsql1 = "e.id = a.status AND e.id = d.approve_type AND d.is_active = 1";
		$this->db->join("tbltravelapprovetype e", $joinsql1,"left");
		$this->db->from($this->tableTO." a");
		$this->db->where("d.approver",$_SESSION["id"]);
		$this->db->where("a.status <=",'4');

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
			$this->db->order_by("a.id", 'DESC');
		}
		$query = $this->db->get();
		$result = $query->result();
		return sizeof($result);
	}

	//set limit in datatable
	function make_datatablespromotion() {
	    $this->make_querypromotion();  
	    if($_POST["length"] != -1) {  
	        $this->db->limit($_POST['length'], $_POST['start']);  
	    }
	    $query = $this->db->get();  
	    return $query->result();  
	}  
	function get_filtered_data(){
		$this->make_querypromotion();
		$query = $this->db->get();
		return $query->num_rows();
	}
	function get_all_data(){
		$this->db->select($this->tablepromotion."*");
		$this->db->from($this->tablepromotion);
		return $this->db->count_all_results();
	}
	function make_querypromotion() {
		$year = date('Y');
		$year2 = $year-2;
		$getmonth = date('m');
		$month = ltrim($getmonth, "0"); 
		$year_month = $year."-".$month;
		$this->select_columnpromotion[] = "DECRYPTER(tblemployees.employee_number,'sunev8clt1234567890',tblemployees.id)";
		$this->select_columnpromotion[] = "DECRYPTER(tblemployees.employee_id_number,'sunev8clt1234567890',tblemployees.id)";
		$this->select_columnpromotion[] = "CONCAT(DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id),', ',DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.middle_name,'sunev8clt1234567890',tblemployees.id))";
		$this->select_columnpromotion[] = "tblemployees.pay_basis";
		$this->select_columnpromotion[] = "tblfieldpositions.name";
		$this->select_columnpromotion[] = "tblfielddepartments.department_name";
		$this->select_columnpromotion[] = "tblemployees.start_date";

		$this->db->distinct();
		$this->db->select('
			tblemployees.id,
			tblemployees.employee_number,
			tblemployees.employee_id_number,
			tblemployees.last_name,
			tblemployees.first_name,
			tblemployees.middle_name,
			tblemployees.extension,
			tblemployees.position_id,
			tblemployees.division_id,
			tblemployees.start_date,
			tblemployees.end_date,
			tblemployees.salary_grade_id,
			tblemployees.salary_grade_step_id,
			tblemployees.employment_status,
			tblemployees.pay_basis,
			tblfieldpositions.name AS position_name,
			tblfielddepartments.department_name AS department_name,
			tblemployees.date_of_permanent,
			TIMESTAMPDIFF(YEAR,tblemployees.start_date,CURDATE()) AS yrs_serve'
		);  

		$this->db->from($this->tablepromotion);
		$this->db->join("tblfieldpositions","tblemployees.position_id = tblfieldpositions.id","left");
		$this->db->join("tblfielddepartments","tblemployees.division_id = tblfielddepartments.id","left");
		$this->db->join("tblfieldsalarygrades","tblemployees.salary_grade_id = tblfieldsalarygrades.id","left");
		$this->db->join("tblfieldsalarygradesteps","tblfieldsalarygrades.grade = tblfieldsalarygradesteps.grade_id","left");
		$this->db->where('tblemployees.employment_status = "Active"');
		$this->db->where('tblemployees.start_date is NOT NULL', NULL, FALSE);
		$this->db->where("YEAR(tblemployees.start_date) != '0000-00-00'");
		
		
		if(isset($_POST['period']) && $_POST['period'] == "this_year"){
			// $this->db->where('IF(tblemployees.date_of_permanent IS NULL,CONCAT(YEAR(tblemployees.start_date) + "3","-",MONTH(tblemployees.start_date)) = "'.$year_month.'",CONCAT(YEAR(tblemployees.date_of_permanent) + "3","-",MONTH(tblemployees.date_of_permanent)) = "'.$year_month.'")');
			// $this->db->where('IF(tblemployees.date_of_permanent IS NULL OR tblemployees.date_of_permanent = "0000-00-00",(YEAR(`tblemployees`.`start_date`) % 3) = 0 , (YEAR(`tblemployees`.`date_of_permanent`) % 3) = 0)');
			// $this->db->where('IF(tblemployees.date_of_permanent IS NULL OR tblemployees.date_of_permanent = "0000-00-00",
			// IF (YEAR(tblemployees.start_date) % 3 = 0, YEAR(tblemployees.start_date) = "'.$year.'",""),
			// IF (YEAR(tblemployees.date_of_permanent) % 3 = 0, YEAR(tblemployees.date_of_permanent) = "'.$year.'",""))');
			$this->db->where('IF(tblemployees.date_of_permanent IS NULL OR tblemployees.date_of_permanent = "0000-00-00",
			IF (YEAR(tblemployees.start_date) % 3 = 2, YEAR(tblemployees.start_date) != "'.$year.'",""),
			IF (YEAR(tblemployees.date_of_permanent) % 3 = 2, YEAR(tblemployees.date_of_permanent) != "'.$year.'",""))');
			// $this->db->where('IF(tblemployees.date_of_permanent IS NULL OR tblemployees.date_of_permanent = "0000-00-00",
			// IF (YEAR(tblemployees.start_date), YEAR(tblemployees.start_date) = "'.$year.'",""),
			// IF (YEAR(tblemployees.date_of_permanent), YEAR(tblemployees.date_of_permanent) = "'.$year.'",""))');
		}else if (isset($_POST['period']) && $_POST['period'] == "this_month"){
			// $this->db->where('IF(tblemployees.date_of_permanent IS NULL,(YEAR(tblemployees.start_date) + "3") = "'.$year.'",(YEAR(tblemployees.date_of_permanent) + "3") = "'.$year.'")');
				// $this->db->where('IF(tblemployees.date_of_permanent IS NULL OR tblemployees.date_of_permanent = "0000-00-00",
				// 	IF (YEAR(tblemployees.start_date) % 3 = 0, MONTH(tblemployees.start_date) = "'.$month.'",""),
				// 	IF (YEAR(tblemployees.date_of_permanent) % 3 = 0, MONTH(tblemployees.date_of_permanent) = "'.$month.'",""))');
			$this->db->where('IF(tblemployees.date_of_permanent IS NULL OR tblemployees.date_of_permanent = "0000-00-00",
			IF (YEAR(tblemployees.start_date) % 3 = 2, (YEAR(tblemployees.start_date) != "'.$year.'" && MONTH(tblemployees.start_date) = "'.$month.'"),""),
			IF (YEAR(tblemployees.date_of_permanent) % 3 = 2, (YEAR(tblemployees.date_of_permanent) != "'.$year.'" && MONTH(tblemployees.date_of_permanent) = "'.$month.'"),""))');
			// $this->db->where('IF(tblemployees.date_of_permanent IS NULL,
			// CONCAT(YEAR(tblemployees.start_date),"-",MONTH(tblemployees.start_date)) = "'.$year_month.'",
			// CONCAT(YEAR(tblemployees.date_of_permanent),"-",MONTH(tblemployees.date_of_permanent)) = "'.$year_month.'")');
		}else{
			if(isset($_POST["pay_basis"]) && $_POST["pay_basis"] != "") $this->db->where("tblemployees.pay_basis",$_POST["pay_basis"]);
			if(isset($_POST["division"]) && $_POST["division"] != "") $this->db->where("tblemployees.division_id",$_POST["division"]);
		}

		if(isset($_POST["pay_basis"]) && $_POST["pay_basis"] != "") $this->db->where("tblemployees.pay_basis",$_POST["pay_basis"]);
		if(isset($_POST["division"]) && $_POST["division"] != "") $this->db->where("tblemployees.division_id",$_POST["division"]);
		if(isset($_POST["forloyal"]) && $_POST["forloyal"] != ""){
			$getcurryear = date('Y');
			$getpostloyalty =  (int)$getcurryear - (int)$_POST["forloyal"];
			// $this->db->where('TIMESTAMPDIFF(YEAR,tblemployees.start_date,CURDATE()) =' ,$_POST["forloyal"]);
			$this->db->where('YEAR(tblemployees.start_date)', $getpostloyalty);
			// $this->db->where('TIMESTAMPDIFF(YEAR,tblemployees.start_date,CURDATE())', (int)$_POST["forloyal"]);
		}

		if(isset($_POST["candidate"]) && $_POST["candidate"] == "loyalty"){
			$getcurryear = date('Y');
			// $this->db->where('TIMESTAMPDIFF(YEAR,tblemployees.start_date,CURDATE()) =' ,$_POST["forloyal"]);
			// $this->db->where('YEAR(tblemployees.start_date) < 2013');
			// $this->db->where('IF(YEAR(tblemployees.start_date) < 2013,TIMESTAMPDIFF(YEAR,tblemployees.start_date,CURDATE()) % 5 = 0,"")');
			$this->db->where('IF(YEAR(tblemployees.start_date) < 2013,('.$getcurryear.' - YEAR(tblemployees.start_date)) % 5 = 0,"")');
		}

		if(isset($_POST["search"]["value"])) {  
			$this->db->group_start();
			foreach ($this->select_columnpromotion as $key => $value) {
				if($value == "created_by")  {
					$this->db->or_like("DECRYPTER($value,'sunev8clt1234567890',$this->table.id)", $_POST["search"]["value"]);  
				} else{
					$this->db->or_like($value, $_POST["search"]["value"]);  
				}
				
			}
			$this->db->group_end(); 
		}

		// if(isset($_GET['Status']) && $_GET['Status'] != null)
		// 	$this->db->where($this->table.'.status = "'.$_GET['Status'].'"');

		if(isset($_POST["order"])) {  	
			$this->db->order_by($this->order_columnpromotion[$_POST['order']['0']['column']]." ". $_POST['order']['0']['dir']);
		} else { 
				// $this->db->limit(1,0);  
				$this->db->order_by("tblemployees".".start_date","DESC");

		}

	}

	public function getTotalEmployeesByContract($contract){
		$sql = " SELECT count(id) AS count FROM tblemployees WHERE employment_status = 'Active' AND pay_basis = ?";
		$query = $this->db->query($sql,array($contract));
		$data = $query->result_array();
		return $data;
	}
	public function getPayrollStatistics(){
		$payroll_period_id = isset($_GET['PayrollPeriod'])? $_GET['PayrollPeriod'] : "";
		//var_dump($payroll_period_id);die();
		$sql = " SELECT SUM(gross_pay) AS total_gross_pay,
				 		SUM(wh_tax_amt) AS total_withholding_tax,
				 		SUM(sss_gsis_amt) AS total_gsis,
				 		SUM(pagibig_amt) AS total_pagibig,
				 		SUM(philhealth_amt) AS total_philhealth 
				 FROM tbltransactionsprocesspayroll WHERE is_active = '1' AND payroll_period_id = ?";
		$query = $this->db->query($sql,array($payroll_period_id));
		$data = $query->result_array();
		return $data;
	}

	function fetchTotalPendingCTO(){
		$sql = " SELECT CONCAT(
			DECRYPTER(b.last_name,'sunev8clt1234567890',b.id),', ',
			DECRYPTER(b.first_name,'sunev8clt1234567890',b.id),' ',
			DECRYPTER(b.middle_name,'sunev8clt1234567890',b.id)) AS full_name,
			a.offset_date_effectivity as inclusive_dates
			FROM tbloffsetting a 
			LEFT JOIN tblemployees b ON a.employee_id = b.id 
			LEFT JOIN tbloffsettingapprovetype c ON a.status = c.id 
			WHERE b.employment_status = 'Active' AND a.status < ? ORDER BY full_name ASC";
		$query = $this->db->query($sql,5);
		$data = $query->result_array();
		return $data;
	}

	function fetchTotalPendingLeave(){
		$sql = " SELECT CONCAT(
			DECRYPTER(b.last_name,'sunev8clt1234567890',b.id),', ',
			DECRYPTER(b.first_name,'sunev8clt1234567890',b.id),' ',
			DECRYPTER(b.middle_name,'sunev8clt1234567890',b.id)) AS full_name 
			FROM tblleavemanagement a 
			LEFT JOIN tblemployees b ON a.employee_id = b.id 
			LEFT JOIN tblemployeesapprovetype c ON a.status = c.id 
			WHERE b.employment_status = 'Active' AND a.status < ? ORDER BY full_name ASC";
		$query = $this->db->query($sql,5);
		$data = $query->result_array();
		return $data;
	}
	function fetchTotalPendingOB(){
		$sql = " SELECT CONCAT(
			DECRYPTER(b.last_name,'sunev8clt1234567890',b.id),', ',
			DECRYPTER(b.first_name,'sunev8clt1234567890',b.id),' ',
			DECRYPTER(b.middle_name,'sunev8clt1234567890',b.id)) AS full_name 
			FROM tbltimekeepinglocatorslips a 
			LEFT JOIN tblemployees b ON a.employee_id = b.id 
			LEFT JOIN tblemployeesapprovetype c ON a.status = c.id 
			WHERE b.employment_status = 'Active' AND a.status = ? OR a.status = 'FOR APPROVAL' ORDER BY full_name ASC";
		$query = $this->db->query($sql,'PENDING');
		$data = $query->result_array();
		return $data;
	}
	function fetchTotalPendingTO(){
		$sql = " SELECT CONCAT(
			DECRYPTER(b.last_name,'sunev8clt1234567890',b.id),', ',
			DECRYPTER(b.first_name,'sunev8clt1234567890',b.id),' ',
			DECRYPTER(b.middle_name,'sunev8clt1234567890',b.id)) AS full_name 
			FROM tbltravelorder a 
			LEFT JOIN tblemployees b ON a.employee_id = b.id 
			LEFT JOIN tbltravelapprovetype c ON a.status = c.id 
			WHERE b.employment_status = 'Active' AND a.status < ? ORDER BY full_name ASC";
		$query = $this->db->query($sql,5);
		$data = $query->result_array();
		return $data;
	}
	function fetchTotalApprovedCTO(){
		$sql = " SELECT CONCAT(
			DECRYPTER(b.last_name,'sunev8clt1234567890',b.id),', ',
			DECRYPTER(b.first_name,'sunev8clt1234567890',b.id),' ',
			DECRYPTER(b.middle_name,'sunev8clt1234567890',b.id)) AS full_name,
			a.offset_date_effectivity as inclusive_dates
			FROM tbloffsetting a 
			LEFT JOIN tblemployees b ON a.employee_id = b.id 
			LEFT JOIN tbloffsettingapprovetype c ON a.status = c.id 
			WHERE a.status = ? ORDER BY full_name ASC";
		$query = $this->db->query($sql,5);
		$data = $query->result_array();
		return $data;
	}

	function fetchTotalApprovedLeave(){
		$sql = " SELECT CONCAT(
			DECRYPTER(b.last_name,'sunev8clt1234567890',b.id),', ',
			DECRYPTER(b.first_name,'sunev8clt1234567890',b.id),' ',
			DECRYPTER(b.middle_name,'sunev8clt1234567890',b.id)) AS full_name 
			FROM tblleavemanagement a 
			LEFT JOIN tblemployees b ON a.employee_id = b.id 
			WHERE a.status = ? ORDER BY full_name ASC";
		$query = $this->db->query($sql,5);
		$data = $query->result_array();
		return $data;
	}
	function fetchTotalApprovedOB(){
		$sql = " SELECT CONCAT(
			DECRYPTER(b.last_name,'sunev8clt1234567890',b.id),', ',
			DECRYPTER(b.first_name,'sunev8clt1234567890',b.id),' ',
			DECRYPTER(b.middle_name,'sunev8clt1234567890',b.id)) AS full_name 
			FROM tbltimekeepinglocatorslips a 
			LEFT JOIN tblemployees b ON a.employee_id = b.id 
			WHERE a.status = ? ORDER BY full_name ASC";
		$query = $this->db->query($sql,'APPROVED');
		$data = $query->result_array();
		return $data;
	}
	function fetchTotalApprovedTO(){
		$sql = " SELECT CONCAT(
			DECRYPTER(b.last_name,'sunev8clt1234567890',b.id),', ',
			DECRYPTER(b.first_name,'sunev8clt1234567890',b.id),' ',
			DECRYPTER(b.middle_name,'sunev8clt1234567890',b.id)) AS full_name 
			FROM tbltravelorder a 
			LEFT JOIN tblemployees b ON a.employee_id = b.id 
			LEFT JOIN tbltravelapprovetype c ON a.status = c.id 
			WHERE a.status = ? ORDER BY full_name ASC";
		$query = $this->db->query($sql,5);
		$data = $query->result_array();
		return $data;
	}

	//start of pending cto
	function make_datatables_pending_cto() {
	    $this->make_query_pending_cto();  
	    if($_POST["length"] != -1) {  
	        $this->db->limit($_POST['length'], $_POST['start']);  
	    }
	    $query = $this->db->get();
	    return $query->result();  
	}	
	function get_filtered_data_pending_cto(){
		$this->make_query_pending_cto();
		$query = $this->db->get();
		return $query->num_rows();
	}
	function get_all_data_pending_cto(){
		$this->db->select("tbloffsetting.*");
		$this->db->from('tbloffsetting');
		return $this->db->count_all_results();
	}
	function make_query_pending_cto() {
		$this->select_column_pending[] = "CONCAT(DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id),', ',DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.middle_name,'sunev8clt1234567890',tblemployees.id))";
		$this->select_column_pending[] = "tbloffsetting.date_filed";
		$this->select_column_pending[] = "tbloffsetting.offset_date_effectivity";
		$this->select_column_pending[] = "tbloffsetting.status";
		$this->db->select('
			tbloffsetting.*,
			tbloffsetting.offset_date_effectivity as inclusive_dates,
			tblemployees.id as emp_id,
			tblemployees.employee_number,
			tblemployees.employee_id_number,
			tblemployees.extension,
			tblemployees.first_name,
			tblemployees.middle_name,
			tblemployees.last_name,
			tbloffsettingapprovetype.type'
		);
		$this->db->from('tbloffsetting');
		$this->db->join("tblemployees","tbloffsetting.employee_id = tblemployees.id","left");
		$this->db->join("tbloffsettingapprovetype","tbloffsetting.status = tbloffsettingapprovetype.id","left");
		$this->db->where('tblemployees.employment_status = "Active"');
		$this->db->where('tbloffsetting.status < 5');
		if(isset($_POST["search"]["value"])) {  
			$this->db->group_start();
			foreach ($this->select_column_pending as $key => $value) {
				if($value == "created_by")  {
					$this->db->or_like("DECRYPTER($value,'sunev8clt1234567890',$this->table.id)", $_POST["search"]["value"]);  
				} else{
					$this->db->or_like($value, $_POST["search"]["value"]);  
				}				
			}
			$this->db->group_end(); 
		}
		if(isset($_POST["order"])) {  	
			$this->db->order_by($this->select_column_pending[$_POST['order']['0']['column']]." ". $_POST['order']['0']['dir']);
		} else { 
			$this->db->order_by("tbloffsetting".".inclusive_dates","DESC");
		}
	}
	//end of pending cto

	//start of approved cto
	function make_datatables_approved_cto() {
	    $this->make_query_approved_cto();  
	    if($_POST["length"] != -1) {  
	        $this->db->limit($_POST['length'], $_POST['start']);  
	    }
	    $query = $this->db->get();
	    return $query->result();  
	}	
	function get_filtered_data_approved_cto(){
		$this->make_query_approved_cto();
		$query = $this->db->get();
		return $query->num_rows();
	}
	function get_all_data_approved_cto(){
		$this->db->select("tbloffsetting.*");
		$this->db->from('tbloffsetting');
		return $this->db->count_all_results();
	}
	function make_query_approved_cto() {
		$this->select_column_approved[] = "CONCAT(DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id),', ',DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.middle_name,'sunev8clt1234567890',tblemployees.id))";
		$this->select_column_approved[] = "tbloffsetting.date_filed";
		$this->select_column_approved[] = "tbloffsetting.offset_date_effectivity";
		$this->select_column_approved[] = "tbloffsetting.status";
		$this->db->select('
			tbloffsetting.*,
			tbloffsetting.offset_date_effectivity as inclusive_dates,
			tblemployees.id as emp_id,
			tblemployees.employee_number,
			tblemployees.employee_id_number,
			tblemployees.extension,
			tblemployees.first_name,
			tblemployees.middle_name,
			tblemployees.last_name,
			tbloffsettingapprovetype.type'
		);
		$this->db->from('tbloffsetting');
		$this->db->join("tblemployees","tbloffsetting.employee_id = tblemployees.id","left");
		$this->db->join("tbloffsettingapprovetype","tbloffsetting.status = tbloffsettingapprovetype.id","left");
		$this->db->where('tblemployees.employment_status = "Active"');
		$this->db->where('tbloffsetting.status = 5');
		if(isset($_POST["search"]["value"])) {  
			$this->db->group_start();
			foreach ($this->select_column_approved as $key => $value) {
				if($value == "created_by")  {
					$this->db->or_like("DECRYPTER($value,'sunev8clt1234567890',$this->table.id)", $_POST["search"]["value"]);  
				} else{
					$this->db->or_like($value, $_POST["search"]["value"]);  
				}				
			}
			$this->db->group_end(); 
		}
		if(isset($_POST["order"])) {  	
			$this->db->order_by($this->select_column_approved[$_POST['order']['0']['column']]." ". $_POST['order']['0']['dir']);
		} else { 
			$this->db->order_by("tbloffsetting".".inclusive_dates","DESC");
		}
	}
	//end of approved cto

	//start of pending leave
	function make_datatables_pending_leave() {  
	    $this->make_query_pending_leave();  
	    if($_POST["length"] != -1) {  
	        $this->db->limit($_POST['length'], $_POST['start']);  
	    }  
	    $query = $this->db->get();  
	    return $query->result();  
	} 
	function get_filtered_data_pending_leave(){
		$this->make_query_pending_leave();
		$query = $this->db->get();
		return $query->num_rows();
	}
	function get_all_data_pending_leave(){
		$this->db->select("tblleavemanagement.*");
		$this->db->from('tblleavemanagement');
		return $this->db->count_all_results();
	}
	function make_query_pending_leave() {
		$this->select_column_pending[] = "CONCAT(DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id),', ',DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.middle_name,'sunev8clt1234567890',tblemployees.id))";
		$this->select_column_pending[] = "tblleavemanagement.date_filed";
		$this->select_column_pending[] = "tblleavemanagement.inclusive_dates";
		$this->select_column_pending[] = "tblleavemanagement.status";
		$this->db->select(
			'(SELECT COUNT(*) FROM tblemployeesleaveapprovers a LEFT JOIN tblemployees b ON a.employee_id = b.id where b.employment_status = "Active" AND a.is_active = 1 AND a.employee_id = tblleavemanagement.employee_id AND a.approve_type = 1) as certify,
			(SELECT COUNT(*) FROM tblemployeesleaveapprovers a LEFT JOIN tblemployees b ON a.employee_id = b.id where b.employment_status = "Active" AND a.is_active = 1 AND a.employee_id = tblleavemanagement.employee_id AND a.approve_type = 2) as supervisor,
			(SELECT COUNT(*) FROM tblemployeesleaveapprovers a LEFT JOIN tblemployees b ON a.employee_id = b.id where b.employment_status = "Active" AND a.is_active = 1 AND a.employee_id = tblleavemanagement.employee_id AND a.approve_type = 3) as division_head,
			(SELECT COUNT(*) FROM tblemployeesleaveapprovers a LEFT JOIN tblemployees b ON a.employee_id = b.id where b.employment_status = "Active" AND a.is_active = 1 AND a.employee_id = tblleavemanagement.employee_id AND a.approve_type = 8) as deputy,
			(SELECT COUNT(*) FROM tblemployeesleaveapprovers a LEFT JOIN tblemployees b ON a.employee_id = b.id where b.employment_status = "Active" AND a.is_active = 1 AND a.employee_id = tblleavemanagement.employee_id AND a.approve_type = 4) as approve,
			tblleavemanagement.*,
			tblemployees.id as emp_id,
			tblemployees.employee_number,
			tblemployees.employee_id_number,
			tblemployees.extension,
			tblemployees.first_name,
			tblemployees.middle_name,
			tblemployees.last_name,
			tblemployeesapprovetype.type'
		);
		$this->db->from('tblleavemanagement');
		$this->db->join("tblemployees","tblleavemanagement.employee_id = tblemployees.id","left");
		$this->db->join("tblemployeesapprovetype","tblleavemanagement.status = tblemployeesapprovetype.id","left");
		$this->db->where('tblleavemanagement.status < 5');
		if(isset($_POST["search"]["value"])) {  
			$this->db->group_start();
			foreach ($this->select_column_pending as $key => $value) {
				if($value == "created_by")  {
					$this->db->or_like("DECRYPTER($value,'sunev8clt1234567890',$this->table.id)", $_POST["search"]["value"]);  
				} else{
					$this->db->or_like($value, $_POST["search"]["value"]);  
				}				
			}
			$this->db->group_end(); 
		}
		if(isset($_POST["order"])) {  	
			$this->db->order_by($this->select_column_pending[$_POST['order']['0']['column']]." ". $_POST['order']['0']['dir']);
		} else { 
			$this->db->order_by("tblleavemanagement".".inclusive_dates","DESC");
		}
	}
	//end of pending leave

	//start of approved leave
	function make_datatables_approved_leave() {  
	    $this->make_query_approved_leave();  
	    if($_POST["length"] != -1) {  
	        $this->db->limit($_POST['length'], $_POST['start']);  
	    }  
	    $query = $this->db->get();  
	    return $query->result();  
	} 
	function get_filtered_data_approved_leave(){
		$this->make_query_approved_leave();
		$query = $this->db->get();
		return $query->num_rows();
	}
	function get_all_data_approved_leave(){
		$this->db->select("tblleavemanagement.*");
		$this->db->from('tblleavemanagement');
		return $this->db->count_all_results();
	}
	function make_query_approved_leave() {
		$this->select_column_approved[] = "CONCAT(DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id),', ',DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.middle_name,'sunev8clt1234567890',tblemployees.id))";
		$this->select_column_approved[] = "tblleavemanagement.date_filed";
		$this->select_column_approved[] = "tblleavemanagement.inclusive_dates";
		$this->select_column_approved[] = "tblleavemanagement.status";
		$this->db->select('
			tblleavemanagement.*,
			tblemployees.id as emp_id,
			tblemployees.employee_number,
			tblemployees.employee_id_number,
			tblemployees.extension,
			tblemployees.first_name,
			tblemployees.middle_name,
			tblemployees.last_name,
			tblemployeesapprovetype.type'
		);
		$this->db->from('tblleavemanagement');
		$this->db->join("tblemployees","tblleavemanagement.employee_id = tblemployees.id","left");
		$this->db->join("tblemployeesapprovetype","tblleavemanagement.status = tblemployeesapprovetype.id","left");
		$this->db->where('tblleavemanagement.status = 5');
		if(isset($_POST["search"]["value"])) {  
			$this->db->group_start();
			foreach ($this->select_column_approved as $key => $value) {
				if($value == "created_by")  {
					$this->db->or_like("DECRYPTER($value,'sunev8clt1234567890',$this->table.id)", $_POST["search"]["value"]);  
				} else{
					$this->db->or_like($value, $_POST["search"]["value"]);  
				}				
			}
			$this->db->group_end(); 
		}
		if(isset($_POST["order"])) {  	
			$this->db->order_by($this->select_column_approved[$_POST['order']['0']['column']]." ". $_POST['order']['0']['dir']);
		} else { 
			$this->db->order_by("tblleavemanagement".".inclusive_dates","DESC");
		}
	}
	//end of approved leave

	//start of pending ob
	function make_datatables_pending_ob() {  
	    $this->make_query_pending_ob();  
	    if($_POST["length"] != -1) {  
	        $this->db->limit($_POST['length'], $_POST['start']);  
	    }  
	    $query = $this->db->get();  
	    return $query->result();  
	} 
	function get_filtered_data_pending_ob(){
		$this->make_query_pending_ob();
		$query = $this->db->get();
		return $query->num_rows();
	}
	function get_all_data_pending_ob(){
		$this->db->select("tbltimekeepinglocatorslips.*");
		$this->db->from('tbltimekeepinglocatorslips');
		return $this->db->count_all_results();
	}
	function make_query_pending_ob() {
		$this->select_column_pending[] = "CONCAT(DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id),', ',DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.middle_name,'sunev8clt1234567890',tblemployees.id))";
		$this->select_column_pending[] = "tbltimekeepinglocatorslips.filing_date";
		$this->select_column_pending[] = "tbltimekeepinglocatorslips.transaction_date";
		$this->select_column_pending[] = "tbltimekeepinglocatorslips.status";
		$this->db->select('
			tbltimekeepinglocatorslips.*,
			tblemployees.id as emp_id,
			tblemployees.employee_number,
			tblemployees.employee_id_number,
			tblemployees.extension,
			tblemployees.first_name,
			tblemployees.middle_name,
			tblemployees.last_name'
		);
		$this->db->from('tbltimekeepinglocatorslips');
		$this->db->join("tblemployees","tbltimekeepinglocatorslips.employee_id = tblemployees.id","left");
		$this->db->where('tblemployees.employment_status = "Active"');
		$where = "(tbltimekeepinglocatorslips.status='PENDING' OR tbltimekeepinglocatorslips.status='FOR APPROVAL')";
		$this->db->where($where);
		if(isset($_POST["search"]["value"])) {  
			$this->db->group_start();
			foreach ($this->select_column_pending as $key => $value) {
				if($value == "created_by")  {
					$this->db->or_like("DECRYPTER($value,'sunev8clt1234567890',$this->table.id)", $_POST["search"]["value"]);  
				} else{
					$this->db->or_like($value, $_POST["search"]["value"]);  
				}				
			}
			$this->db->group_end(); 
		}
		if(isset($_POST["order"])) {  	
			$this->db->order_by($this->select_column_pending[$_POST['order']['0']['column']]." ". $_POST['order']['0']['dir']);
		} else { 
			$this->db->order_by("tbltimekeepinglocatorslips".".transaction_date","DESC");
		}
	}
	//end of pending ob

	//start of approved ob
	function make_datatables_approved_ob() {  
	    $this->make_query_approved_ob();  
	    if($_POST["length"] != -1) {  
	        $this->db->limit($_POST['length'], $_POST['start']);  
	    }  
	    $query = $this->db->get();  
	    return $query->result();  
	} 
	function get_filtered_data_approved_ob(){
		$this->make_query_approved_ob();
		$query = $this->db->get();
		return $query->num_rows();
	}
	function get_all_data_approved_ob(){
		$this->db->select("tbltimekeepinglocatorslips.*");
		$this->db->from('tbltimekeepinglocatorslips');
		return $this->db->count_all_results();
	}
	function make_query_approved_ob() {
		$this->select_column_approved[] = "CONCAT(DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id),', ',DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.middle_name,'sunev8clt1234567890',tblemployees.id))";
		$this->select_column_approved[] = "tbltimekeepinglocatorslips.filing_date";
		$this->select_column_approved[] = "tbltimekeepinglocatorslips.transaction_date";
		$this->select_column_approved[] = "tbltimekeepinglocatorslips.status";
		$this->db->select('
			tbltimekeepinglocatorslips.*,
			tblemployees.id as emp_id,
			tblemployees.employee_number,
			tblemployees.employee_id_number,
			tblemployees.extension,
			tblemployees.first_name,
			tblemployees.middle_name,
			tblemployees.last_name'
		);
		$this->db->from('tbltimekeepinglocatorslips');
		$this->db->join("tblemployees","tbltimekeepinglocatorslips.employee_id = tblemployees.id","left");
		$this->db->where('tbltimekeepinglocatorslips.status','APPROVED');
		if(isset($_POST["search"]["value"])) {  
			$this->db->group_start();
			foreach ($this->select_column_approved as $key => $value) {
				if($value == "created_by")  {
					$this->db->or_like("DECRYPTER($value,'sunev8clt1234567890',$this->table.id)", $_POST["search"]["value"]);  
				} else{
					$this->db->or_like($value, $_POST["search"]["value"]);  
				}				
			}
			$this->db->group_end(); 
		}
		if(isset($_POST["order"])) {  	
			$this->db->order_by($this->select_column_pending[$_POST['order']['0']['column']]." ". $_POST['order']['0']['dir']);
		} else { 
			$this->db->order_by("tbltimekeepinglocatorslips".".transaction_date","DESC");
		}
	}
	//end of approved ob

	//start of pending to
	function make_datatables_pending_to() {  
	    $this->make_query_pending_to();  
	    if($_POST["length"] != -1) {  
	        $this->db->limit($_POST['length'], $_POST['start']);  
	    }  
	    $query = $this->db->get();  
	    return $query->result();  
	} 
	function get_filtered_data_pending_to(){
		$this->make_query_pending_to();
		$query = $this->db->get();
		return $query->num_rows();
	}
	function get_all_data_pending_to(){
		$this->db->select("tbltravelorder.*");
		$this->db->from('tbltravelorder');
		return $this->db->count_all_results();
	}
	function make_query_pending_to() {
		$this->select_column_pending[] = "CONCAT(DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id),', ',DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.middle_name,'sunev8clt1234567890',tblemployees.id))";
		$this->select_column_pending[] = "tbltravelorder.date_created";
		$this->select_column_pending[] = "tbltravelorder.duration";
		$this->select_column_pending[] = "tbltravelorder.status";
		$this->db->select('
			tbltravelorder.*,
			tblemployees.id as emp_id,
			tblemployees.employee_number,
			tblemployees.employee_id_number,
			tblemployees.extension,
			tblemployees.first_name,
			tblemployees.middle_name,
			tblemployees.last_name,
			tbltravelapprovetype.type'
		);
		$this->db->from('tbltravelorder');
		$this->db->join("tblemployees","tbltravelorder.employee_id = tblemployees.id","left");
		$this->db->join("tbltravelapprovetype","tbltravelorder.status = tbltravelapprovetype.id","left");
		$this->db->where('tblemployees.employment_status = "Active"');
		$this->db->where('tbltravelorder.status <',"5");
		if(isset($_POST["search"]["value"])) {  
			$this->db->group_start();
			foreach ($this->select_column_pending as $key => $value) {
				if($value == "created_by")  {
					$this->db->or_like("DECRYPTER($value,'sunev8clt1234567890',$this->table.id)", $_POST["search"]["value"]);  
				} else{
					$this->db->or_like($value, $_POST["search"]["value"]);  
				}				
			}
			$this->db->group_end(); 
		}
		if(isset($_POST["order"])) {  	
			$this->db->order_by($this->select_column_pending[$_POST['order']['0']['column']]." ". $_POST['order']['0']['dir']);
		} else { 
			$this->db->order_by("tbltravelorder".".date_created","DESC");
		}
	}
	//end of pending to

	//start of approved to
	function make_datatables_approved_to() {  
	    $this->make_query_approved_to();  
	    if($_POST["length"] != -1) {  
	        $this->db->limit($_POST['length'], $_POST['start']);  
	    }  
	    $query = $this->db->get();  
	    return $query->result();  
	} 
	function get_filtered_data_approved_to(){
		$this->make_query_approved_to();
		$query = $this->db->get();
		return $query->num_rows();
	}
	function get_all_data_approved_to(){
		$this->db->select("tbltravelorder.*");
		$this->db->from('tbltravelorder');
		return $this->db->count_all_results();
	}
	function make_query_approved_to() {
		$this->select_column_approved[] = "CONCAT(DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id),', ',DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.middle_name,'sunev8clt1234567890',tblemployees.id))";
		$this->select_column_approved[] = "tbltravelorder.date_created";
		$this->select_column_approved[] = "tbltravelorder.duration";
		$this->select_column_approved[] = "tbltravelorder.status";
		$this->db->select('
			tbltravelorder.*,
			tblemployees.id as emp_id,
			tblemployees.employee_number,
			tblemployees.employee_id_number,
			tblemployees.extension,
			tblemployees.first_name,
			tblemployees.middle_name,
			tblemployees.last_name,
			tbltravelapprovetype.type'
		);
		$this->db->from('tbltravelorder');
		$this->db->join("tblemployees","tbltravelorder.employee_id = tblemployees.id","left");
		$this->db->join("tbltravelapprovetype","tbltravelorder.status = tbltravelapprovetype.id","left");
		$this->db->where('tbltravelorder.status =',"5");
		if(isset($_POST["search"]["value"])) {  
			$this->db->group_start();
			foreach ($this->select_column_approved as $key => $value) {
				if($value == "created_by")  {
					$this->db->or_like("DECRYPTER($value,'sunev8clt1234567890',$this->table.id)", $_POST["search"]["value"]);  
				} else{
					$this->db->or_like($value, $_POST["search"]["value"]);  
				}				
			}
			$this->db->group_end(); 
		}
		if(isset($_POST["order"])) {  	
			$this->db->order_by($this->select_column_approved[$_POST['order']['0']['column']]." ". $_POST['order']['0']['dir']);
		} else { 
			$this->db->order_by("tbltravelorder".".date_created","DESC");
		}
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
			c.id
			FROM 
			tblemployeesleaveapprovers AS a
			LEFT JOIN tblemployees c ON a.approver = c.id
			LEFT JOIN tblfieldpositions b ON c.position_id = b.id
			WHERE a.employee_id = ?
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
	//end of approved to
}
?>