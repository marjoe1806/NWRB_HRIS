<?php
	class EmployeesCollection extends Helper {
      	var $select_column = array();
		var $selectEmployeesParams = array();
		var $sql = "";

		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
			$columns = $this->getColumns();
			foreach ($columns as $key => $value) {
				$this->select_column[] = $this->table.'.'.$value;
			}
		}
		//Fetch
		var $table = "tblemployees";   
		  
      	public function getColumns(){
			$rows = array("tblemployees.employee_number", "tblemployees.employee_id_number",'tblemployees.last_name','tblfieldpositions.name','tblfielddepartments.department_name','tblfieldsalarygrades.grade','tblemployees.employment_status','tblemployees.gender','tblemployees.contact_number','tblemployees.email','tblemployees.date_created','tblemployees.date_modified');
			return $rows; 
		}
		  
		function make_query(){
		  
			$this->order_column = array(
				'',
				'',
				'tblemployees.employee_id_number',
				'tblemployees.employee_number',
				'tblemployees.last_name',
				'tblfieldpositions.name',
				'tblfielddepartments.department_name',
				'tblemployees.salary_grade_id',
				isset($_GET['EmploymentStatus']) && $_GET['EmploymentStatus'] != "Active" ? "tblemployees.end_date" : "tblemployees.start_date",
				'tblemployees.employment_status',
				'tblemployees.gender',
				'tblemployees.contact_number',
				'tblemployees.email',
				'tblemployees.date_created',
				'tblemployees.date_modified'
			);
			
			$this->selectEmployeesParams = array(
				(isset($_POST["search"]["value"]))?$_POST["search"]["value"]:"",
				(isset($_GET['PayBasis']))?$_GET['PayBasis']:"",
				(isset($_GET['DivisionId']))?$_GET['DivisionId']:"",
				(isset($_GET['SalaryGrade']))?$_GET['SalaryGrade']:"",
				(isset($_GET['PositionId']))?$_GET['PositionId']:"",
				(isset($_GET['EmploymentStatus']))?$_GET['EmploymentStatus']:"",
				(isset($_GET['Id']))?$_GET['Id']:"",
				(isset($_POST['order']))?1:"",
				(isset($_POST['order']['0']['column']) && isset($this->order_column[$_POST['order']['0']['column']]))?$this->order_column[$_POST['order']['0']['column']]:"",
				(isset($_POST['order']['0']['dir']))?$_POST['order']['0']['dir']:"",
				(isset($_POST['length']))?$_POST['length']:"",
				(isset($_POST['start']))?$_POST['start']:"",
			);
			$this->sql = "CALL sp_get_employees(?,?,?,?,?,?,?,?,?,?,?,?)";
			// var_dump($this->sql);
		}

	    public function getEmpRows($id){
	        $ret = array();
	        $familybackgroundchildrens = $this->db->select("*")->from($this->table.'familybackgroundchildrens')->where("employee_id",$id)->get()->result_array();
			$civilserviceeligibility = $this->db->select("*,date_format(str_to_date(date_conferment, '%m/%d/%Y'), '%Y-%m-%d') as date")->from($this->table.'civilserviceeligibility')->where("employee_id",$id)->order_by('date DESC')->get()->result_array();
			$workexperience = $this->db->select("*,date_format(str_to_date(work_from, '%m/%d/%Y'), '%Y-%m-%d') as date")->from($this->table.'workexperience')->where("employee_id",$id)->order_by('date DESC')->get()->result_array();
			$voluntarywork = $this->db->select("*,date_format(str_to_date(organization_work_from, '%m/%d/%Y'), '%Y-%m-%d') as date")->from($this->table.'voluntarywork')->where("employee_id",$id)->order_by('date DESC')->get()->result_array();
			$learningdevelopment = $this->db->select("*,date_format(str_to_date(traning_from, '%m/%d/%Y'), '%Y-%m-%d') as date")->from($this->table.'learningdevelopment')->where("employee_id",$id)->order_by('date DESC')->get()->result_array();
			$trainings = $this->db->select("
					a.id,title as training,
					start_date as traning_from,
					end_date as training_to,
					no_of_hours as training_number_hours,
					type as training_type,
					sponsor as training_sponsored_by,
					date_format(str_to_date(start_date, '%m/%d/%Y'), '%Y-%m-%d') as date")
				->from('tbltrainings a')->join('tbltrainingsattendees b',"b.seminar_id = a.id","left")->where("b.employee_id",$id)
				->get()->result_array();
			$specialskills = $this->db->select("*")->from($this->table.'specialskills')->where("employee_id",$id)->get()->result_array();
			$recognitions = $this->db->select("*")->from($this->table.'recognitions')->where("employee_id",$id)->get()->result_array();
			$organizations = $this->db->select("*")->from($this->table.'organizations')->where("employee_id",$id)->get()->result_array();
			$references = $this->db->select("*")->from($this->table.'references')->where("employee_id",$id)->get()->result_array();
			$educbgelementary = $this->db->select("*")->from($this->table.'educbgelementary')->where("employee_id",$id)->get()->result_array();
			$educbgsecondary = $this->db->select("*")->from($this->table.'educbgsecondary')->where("employee_id",$id)->get()->result_array();
			$educbgcolleges = $this->db->select("*")->from($this->table.'educbgcolleges')->where("employee_id",$id)->get()->result_array();
			$educbggradstuds = $this->db->select("*")->from($this->table.'educbggradstuds')->where("employee_id",$id)->get()->result_array();
			$educbgvocationals = $this->db->select("*")->from($this->table.'educbgvocationals')->where("employee_id",$id)->get()->result_array();
			// Push each training record into the $learningdevelopment array
			// foreach ($trainings as $training) {
			// 	array_push($learningdevelopment, $training);
			// }
	        $ret = array("Code"=>0,"Message"=>"Success!",
	            "Data"=>array(
	                "familybackgroundchildrens" => $familybackgroundchildrens,
					"civilserviceeligibility" => $civilserviceeligibility,
					"workexperience" => $workexperience,
					"voluntarywork" => $voluntarywork,
					"learningdevelopment" => $learningdevelopment,
					"specialskills" => $specialskills,
					"recognitions" => $recognitions,
					"organizations" => $organizations,
					"references" => $references,
					"educbgelementary" => $educbgelementary,
					"educbgsecondary" => $educbgsecondary,
					"educbgcolleges" => $educbgcolleges,
					"educbggradstuds" => $educbggradstuds,
					"educbgvocationals" => $educbgvocationals
	            )
	        );
	        return $ret;
		}

	    public function getItemPosition($id){
	        $data = $this->db->select("*")->from('tblfieldpositions')->where("id",$id)->get()->row_array();
	        $ret = array("Code"=>0,"Message"=>"Success!","Data"=>$data);
	        return $ret;
		}

	    public function getEmpAtthcmentRows($id){
			$code = "1";
			$message = "No data available.";
			$this->db->select("*")->from($this->table.'attatchments')->where("employee_id",$id);
			$query = $this->db->get();
		    if($query->num_rows() > 0){
				$code = "0";
				$message = "Successfully fetched Employee PDS Attachments.";
				$data = $query->result_array();
				$this->ModelResponse($code, $message, $data);
				return true;		
			}else $this->ModelResponse($code, $message);
			return false;
		}

	    public function getisNameExist($params){
			$code = "0";
			$message = "No data available.";
			$query = $query2 = $query3 = false;
			if($params["fname"] != "" && $params["mname"] != "" && $params["lname"] != "")
				$query = $this->db->select("*")->from($this->table)->where('DECRYPTER(first_name,"sunev8clt1234567890",id)',$params['fname'])->where('DECRYPTER(middle_name,"sunev8clt1234567890",id)',$params['mname'])->where('DECRYPTER(last_name,"sunev8clt1234567890",id)',$params['lname'])->limit(1)->get()->row_array();
			if($params["employee_id_number"] != "")
				$query2 = $this->db->select("DECRYPTER(employee_number,'sunev8clt1234567890',id) as emp_no")->from($this->table)->where('DECRYPTER(employee_id_number,"sunev8clt1234567890",id)',$params['employee_id_number'])->limit(1)->get()->row_array();
			if($params["employee_number"] != "")
				$query3 = $this->db->select("*")->from($this->table)->where('DECRYPTER(employee_number,"sunev8clt1234567890",id)',$params['employee_number'])->limit(1)->get()->row_array();

		    if($query){
				$code = "1";
				$message = "Name is already used.";
				$data = $query;
				$this->ModelResponse($code, $message, $data);
				return true;		
			}
			if($query2){
				$code = "1";
				$message = "Employee Number is already used.";
				$data = $query2;
				$this->ModelResponse($code, $message, $data);
				return true;
			}
			if($query3){
				$code = "1";
				$message = "Scanning Number is already used.";
				$data = $query3;
				$this->ModelResponse($code, $message, $data);
				return true;
			}
			$this->ModelResponse($code, $message);
			return false;
		}
		
		function getPayrollConfigRow($id){
			$code = "1";
			$message = "No data available.";
			$query = $this->db->select("*")->from("tblfieldpayrollconfigurations")->where("is_active","1")->order_by("id","DESC")->limit(1)->get();
			// $query2 = $this->db->select("*")->from("tblfieldbonuses")->where("is_active","1")->order_by("id","DESC")->get();
			$query2 = $this->db->select('a.*, IFNULL((SELECT b.amount FROM tbltransactionsbonus b WHERE b.bonus_type = a.id AND b.employee_id = "'.$id.'"),0) amount')
			->from("tblfieldbonuses a")
			->where("a.is_active","1")
			->order_by("a.id","DESC")->get();
			$employee_bonuses = $this->db->query("SELECT SUM(a.amount) as amount, (SELECT SUM(max) FROM tblfieldbonuses c WHERE c.group = b.group) as max, b.with_tax as tax, b.name as bonus,b.group FROM tbltransactionsbonus AS a INNER JOIN tblfieldbonuses AS b ON a.bonus_type = b.id WHERE a.employee_id = '".$id."' AND a.year = '".date("Y")."' GROUP BY b.group")->result_array();
			$taxable_bonus = $total_bonus = $labis = 0;
			if(sizeof($employee_bonuses) > 1){
				foreach($employee_bonuses as $bk => $bv){
					if($bv["tax"] == 1) $total_bonus += $bv["amount"];
					else{
						if($bv["max"] == 0) $total_bonus += $bv["amount"];
						else{
							$labis += $bv["amount"] > $bv["max"] ? ($bv["max"] == 0 ? 0 : $bv["amount"] - $bv["max"]) : 0;
							// $total_bonus += $bv["amount"] > $bv["max"] ? $bv["max"] : $bv["amount"];
						}
					}
				}
			}
			$payroll_config = $this->db->query("SELECT * FROM tblfieldpayrollconfigurations WHERE is_active = '1' ORDER BY id DESC LIMIT 1")->row_array();
			$total_bonus += $labis;
			$taxable_bonus = $total_bonus - (float)$payroll_config["allowable_compensation"];
			$taxable_bonus = $taxable_bonus < 0 ? 0 : $taxable_bonus;
			// $taxable_bonus += $labis;
		    if($query->num_rows() > 0){
				$code = "0";
				$message = "Successfully fetched payroll configuration details.";
				$data["payroll_info"] = $query->row_array();
				$data["bonuses"] = $query2->result_array();
				$data["taxable_bonuses"] = array("total_bonus" => $total_bonus, "taxable_bonus" => $taxable_bonus,"allowable"=>(float)$payroll_config["allowable_compensation"]);
				$this->ModelResponse($code, $message, $data);
				return true;		
			}else {
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		function getWithHoldingTaxRow($an_tax_sal){
			$this->sql = " SELECT * FROM tblwithholdingtaxtable  WHERE compensation_level_from <= ? AND compensation_level_to >= ? ";
			$query = $this->db->query($this->sql,array($an_tax_sal,$an_tax_sal));
			$tax_table = $query->row_array();
			$comp_from = 0.00;
			$tax_percentage = 0.00;
			$tax_additional = 0.00;
			$annualWithHoldingTaxAmount = 0.00;
			if(sizeof($tax_table) > 0){
				$comp_from = $tax_table['compensation_level_from'];
				$tax_percentage = $tax_table['tax_percentage'];
				$tax_additional = $tax_table['tax_additional'];
			}
			if($an_tax_sal > $comp_from){
				$annualWithHoldingTaxAmount = ($an_tax_sal - $comp_from) * ($tax_percentage / 100) + $tax_additional;
			}
			return $annualWithHoldingTaxAmount.",".$tax_percentage;
		}

		function make_datatables(){  
			$this->make_query();  
			// if($_POST["length"] != -1) {
			// 	$this->db->limit($_POST['length'], $_POST['start']);
			// }
			// $query = $this->db->get();
			// return $query->result();
			// $result = $this->db->select("*")->from('tblemployees')->join("tblemployeeseducbgcolleges", "tblemployeeseducbgcolleges.employee_id = tblemployees.id","left")->get()->result_array();
			$query = $this->db->query($this->sql,$this->selectEmployeesParams);
			$result = $query->result();
			mysqli_next_result($this->db->conn_id);
		    return $result;
		}

		function hasRows(){
			$code = "1";
			$message = "No data available.";
			$this->sql = "CALL sp_get_active_employees()";
			$query = $this->db->query($this->sql);
			$rows = $query->result();
			mysqli_next_result($this->db->conn_id);
		    if(sizeof($rows) > 0){
				$code = "0";
				$message = "Successfully fetched employee.";
				$data['details'] = $rows;
				$this->ModelResponse($code, $message, $data);
				return true;		
			}else $this->ModelResponse($code, $message);
			return false;
		}

		function hasRowsById(){
			$code = "1";
			$message = "No data available.";
			$_GET['EmploymentStatus'] = "Active";
			$this->make_query();
			$query = $this->db->query($this->sql,$this->selectEmployeesParams);
			$rows = $query->result();
			mysqli_next_result($this->db->conn_id);
		    if(sizeof($rows) > 0){
				$code = "0";
				$message = "Successfully fetched employee.";
				$data['details'] = $rows;
				$this->ModelResponse($code, $message, $data);
				return true;		
			}else {
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		public function hasRowsByDivision($post_data){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$this->sql = "SELECT
						a.id,
						a.location_id,

						a.employee_number,
						a.employee_id_number,
		
						a.first_name,
						a.middle_name,
						a.last_name,
						a.shift_id,
						e.department_name AS department_name
				FROM
					tblemployees a
					LEFT JOIN tblfielddepartments e ON a.division_id = e.id WHERE a.employment_status = 'Active'";
			if(isset($post_data['division_id']) && $post_data['division_id'] != "") $this->sql .= " AND a.division_id = '".$post_data['division_id']."'";
			// if(isset($post_data['pay_basis']) && $post_data['division_id'] != "") $this->sql .= " AND a.pay_basis = '".$post_data['pay_basis']."'";
			




			$query = $this->db->query($this->sql);
			$userlevel_rows = $query->result_array();
			// var_dump($userlevel_rows); die();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched sub allowances.";
				$this->ModelResponse($code, $message, $data);	
				// $helperDao->auditTrails(Helper::get('userid'),$message);	
				return true;		
			}else {
				$this->ModelResponse($code, $message);
				// $helperDao->auditTrails(Helper::get('userid'),$message);
			}
			return false;
		}



		function get_filtered_data(){  
			 $this->make_query();
			 $this->order_column = array(
				'',
				'',
				'employee_id_number',
				'employee_number',
				'tblemployees.last_name',
				"tblfieldpositions.name",
				"tblfielddepartments.department_name",
				"tblfieldsalarygrades.grade",
				isset($_GET['EmploymentStatus']) && $_GET['EmploymentStatus'] != "Active" ? "tblemployees.end_date" : "tblemployees.start_date",
				"tblemployees.employment_status",
				"tblemployees.gender",
				"tblemployees.contact_number",
				"tblemployees.email",
				"tblemployees.date_created",
				"tblemployees.date_modified"
			 );
			 $this->selectEmployeesParams = array(
				(isset($_POST["search"]["value"]))?$_POST["search"]["value"]:"",
				(isset($_GET['PayBasis']))?$_GET['PayBasis']:"",
				(isset($_GET['DivisionId']))?$_GET['DivisionId']:"",
				(isset($_GET['SalaryGrade']))?$_GET['SalaryGrade']:"",
				(isset($_GET['PositionId']))?$_GET['PositionId']:"",
				(isset($_GET['EmploymentStatus']))?$_GET['EmploymentStatus']:"",
				(isset($_GET['Id']))?$_GET['Id']:"",
				(isset($_POST['order']))?1:"",
				(isset($_POST['order']['0']['column']) && isset($this->order_column[$_POST['order']['0']['column']]))?$this->order_column[$_POST['order']['0']['column']]:"",
				(isset($_POST['order']['0']['dir']))?$_POST['order']['0']['dir']:"",
				"",
				""
			);
			 $query = $this->db->query($this->sql,$this->selectEmployeesParams);
			 $result = $query->result();
			 mysqli_next_result($this->db->conn_id); 
		     return sizeof($result);  
		} 

		function get_all_data() {  
		    $this->db->select('*');  
		    $this->db->from($this->table);
		    if(isset($_GET['EmploymentStatus']) && $_GET['EmploymentStatus'] != null) $this->db->where($this->table.'.employment_status',$_GET['EmploymentStatus']);
		    return $this->db->count_all_results();  
		}  
		//End Fetch

		public function hasRowsAllowances($employee_id){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$this->sql = " SELECT b.*,a.* FROM tblemployeesallowances a LEFT JOIN tblfieldallowances b ON b.id = a.allowance_id WHERE employee_id = ? ORDER BY a.is_active DESC";
			$query = $this->db->query($this->sql,array($employee_id));
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched sub allowances.";
				$this->ModelResponse($code, $message, $data);	
				// $helperDao->auditTrails(Helper::get('userid'),$message);	
				return true;		
			}else {
				$this->ModelResponse($code, $message);
				// $helperDao->auditTrails(Helper::get('userid'),$message);
			}
			return false;
		}

		public function isEmployeeAllowanceExists($allowance_id,$employee_id){
			$this->db->select('*');
			$this->db->from('tblemployeesallowances');
			$this->db->where(array('allowance_id'=>$allowance_id,'employee_id'=>$employee_id));
			$query = $this->db->get();
			return $query->result_array();
		}

		public function activeRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Employee failed to activate.";
			$data['Id'] = isset($params['id'])?$params['id']:'';
			$data['Status'] = "1";
			$userlevel_sql = "	UPDATE ".$this->table." SET is_active = ? WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully activated employee.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;		
			}else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}
		public function inactiveRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Employee failed to deactivate.";
			$data['Id'] = isset($params['id'])?$params['id']:'';
			$data['Status'] = "0";
			$userlevel_sql = "	UPDATE ".$this->table." SET is_active = ? WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully deactivated employee.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;		
			}else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		public function activeAllowancesRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Allowance level failed to activate.";
			$data['Id'] = isset($params['id'])?$params['id']:'';
			$data['Status'] = "1";
			$userlevel_sql = "	UPDATE tblemployeesallowances SET is_active = ? WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully activated allowance.";
				// $helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;		
			}else {
				// $helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		public function inactiveAllowancesRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Allowance failed to deactivate.";
			$data['Id'] = isset($params['id'])?$params['id']:'';
			$data['Status'] = "0";
			$userlevel_sql = "	UPDATE tblemployeesallowances SET is_active = ? WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully deactivated allowance.";
				// $helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;		
			}else {
				// $helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		public function addRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Employee failed to insert.";
			$id = uniqid();
			$rows1 = $rows2 = $rows3 = $rows4 = $rows5 = $rows6 = $rows7 = $rows8 = $rows9 = $rows10 = $rows11 = $rows12 = $rows13 = $rows14 = $rows15 = $govids = $qarr = $emparr = $educarr = $famarr = array();
			if(!$this->isEmployeeNumberExist($params['employee_number'])){

				
				$isEmpSql = $this->db->select("*")->from("tblemployees")
				->where('DECRYPTER(first_name,"sunev8clt1234567890",id)',$params['first_name'])
				->where('DECRYPTER(middle_name,"sunev8clt1234567890",id)',$params['middle_name'])
				->where('DECRYPTER(last_name,"sunev8clt1234567890",id)',$params['last_name'])
				->where('DECRYPTER(extension,"sunev8clt1234567890",id)',$params['extension'])->limit(1)->get();
				$isEmployee = $isEmpSql->row_array();
				if($isEmployee){
					$message = "Name is already exists.";
				}else{
					// additional table family background
					$famarr['employee_id'] = $id;
					$famarr["spouse_last_name"] = strtoupper($params["spouse_last_name"]);
					$famarr["spouse_first_name"] = strtoupper($params["spouse_first_name"]);
					$famarr["spouse_extension"] = strtoupper($params["spouse_extension"]);
					$famarr["spouse_middle_name"] = strtoupper($params["spouse_middle_name"]);
					$famarr["spouse_occupation"] = strtoupper($params["spouse_occupation"]);
					$famarr["spouse_employer_name"] = strtoupper($params["spouse_employer_name"]);
					$famarr["spouse_business_address"] = strtoupper($params["spouse_business_address"]);
					$famarr["spouse_tel_no"] = strtoupper($params["spouse_tel_no"]);
					$famarr["father_last_name"] = strtoupper($params["father_last_name"]);
					$famarr["father_first_name"] = strtoupper($params["father_first_name"]);
					$famarr["father_extension"] = strtoupper($params["father_extension"]);
					$famarr["father_middle_name"] = strtoupper($params["father_middle_name"]);
					$famarr["mother_last_name"] = strtoupper($params["mother_last_name"]);
					$famarr["mother_first_name"] = strtoupper($params["mother_first_name"]);
					$famarr["mother_extension"] = strtoupper($params["mother_extension"]);
					$famarr["mother_middle_name"] = strtoupper($params["mother_middle_name"]);
					// end of additional table family background
					// additional table govid
					$govids['employee_id'] = $id;
					$govids['gov_issued_id'] = strtoupper(@$params["gov_issued_id"]);
					$govids['valid_id'] = strtoupper(@$params["valid_id"]);
					$govids['place_issuance'] = strtoupper(@$params["place_issuance"]);
					//end of additional table govid
					// additional table for questions
					$qarr['employee_id'] = $id;
					$qarr["radio_input_01"] = @$params["radio_input_01"];
					$qarr["if_yes_01"] = ((@$params["radio_input_01"] == "Yes")?strtoupper(@$params["if_yes_01"]):"");
					$qarr["radio_input_02"] = @$params["radio_input_02"];
					$qarr["if_yes_02"] = ((@$params["radio_input_02"] == "Yes")?strtoupper(@$params["if_yes_02"]):"");
					$qarr["radio_input_03"] = @$params["radio_input_03"];
					$qarr["if_yes_03"] = ((@$params["radio_input_03"] == "Yes")?strtoupper(@$params["if_yes_03"]):"");
					$qarr["radio_input_04"] = @$params["radio_input_04"];
					$qarr["if_yes_04"] = ((@$params["radio_input_04"] == "Yes")?strtoupper(@$params["if_yes_04"]):"");
					$qarr["if_yes_case_status_04"] = ((@$params["radio_input_04"] == "Yes")?strtoupper(@$params["if_yes_case_status_04"]):"");
					$qarr["radio_input_05"] = @$params["radio_input_05"];
					$qarr["if_yes_05"] = ((@$params["radio_input_05"] == "Yes")?strtoupper(@$params["if_yes_05"]):"");
					$qarr["radio_input_06"] = @$params["radio_input_06"];
					$qarr["if_yes_06"] = ((@$params["radio_input_06"] == "Yes")?strtoupper(@$params["if_yes_06"]):"");
					$qarr["radio_input_07"] = @$params["radio_input_07"];
					$qarr["if_yes_07"] = ((@$params["radio_input_07"] == "Yes")?strtoupper(@$params["if_yes_07"]):"");
					$qarr["radio_input_08"] = @$params["radio_input_08"];
					$qarr["if_yes_08"] = ((@$params["radio_input_08"] == "Yes")?strtoupper(@$params["if_yes_08"]):"");
					$qarr["radio_input_09"] = @$params["radio_input_09"];
					$qarr["if_yes_09"] = ((@$params["radio_input_09"] == "Yes")?strtoupper(@$params["if_yes_09"]):"");
					$qarr["radio_input_10"] = @$params["radio_input_10"];
					$qarr["if_yes_10"] = ((@$params["radio_input_10"] == "Yes")?strtoupper(@$params["if_yes_10"]):"");
					$qarr["radio_input_11"] = @$params["radio_input_11"];
					$qarr["if_yes_11"] = ((@$params["radio_input_11"] == "Yes")?strtoupper(@$params["if_yes_11"]):"");
					$qarr["radio_input_12"] = @$params["radio_input_12"];
					$qarr["if_yes_12"] = ((@$params["radio_input_12"] == "Yes")?strtoupper(@$params["if_yes_12"]):"");
					// end of additional table for questions
					// employee table fields
					// additional employee fields
					$emparr["bloodtype"] = strtoupper($params["bloodtype"]);
					$emparr["sss"] = str_replace("_","0",strtoupper($params["sss"]));
					// $emparr["ageny_employee_no"] = strtoupper($params["ageny_employee_no"]);
					$emparr["tel_no"] = strtoupper($params["tel_no"]);
					// end of additional employee fields
					
					$emparr['id'] = $id;
					$emparr['employee_id_number'] = Helper::encrypt(strtoupper($params['employee_id_number']),$id);
					$emparr['employee_number'] = Helper::encrypt(strtoupper($params['employee_number']),$id);
					$emparr['first_name'] = Helper::encrypt(strtoupper($params['first_name']),$id);
					$emparr['middle_name'] = Helper::encrypt(strtoupper($params['middle_name']),$id);
					$emparr['last_name'] = Helper::encrypt(strtoupper($params['last_name']),$id);
					$emparr["extension"] = Helper::encrypt(strtoupper($params['extension']),$id);;
					$emparr['modified_by'] = Helper::get('userid');
					$emparr["division_id"] = $params["division_id"];
					$emparr["birthday"] = $params["birthday"];
					$emparr["birth_place"] = strtoupper($params["birth_place"]);
					$emparr["gender"] = $params["gender"];
					$emparr["civil_status"] = $params["civil_status"];
					$emparr["civil_status_others"] = (($params["civil_status"] == "Others")?$params["civil_status_others"]:"");
					$emparr["height"] = $params["height"];
					$emparr["weight"] = $params["weight"];
					$emparr["gsis"] = str_replace("_","0",$params["gsis"]);
					$emparr["pagibig"] = str_replace("_","0",$params["pagibig"]);
					$emparr["philhealth"] = str_replace("_","0",$params["philhealth"]);
					$emparr["tin"] = str_replace("_","0",$params["tin"]);
					$emparr["nationality"] = $params["nationality"];
					$emparr["nationality_details"] = $params["nationality_details"];
					$emparr["nationality_country"] = (($params["nationality"] != "Filipino")?strtoupper($params["nationality_country"]):"");
					$emparr["house_number"] = ((isset($params["house_number"]))?strtoupper($params["house_number"]):"");
					$emparr["street"] = ((isset($params["street"]))?strtoupper($params["street"]):"");
					$emparr["village"] = ((isset($params["village"]))?strtoupper($params["village"]):"");
					$emparr["barangay"] = ((isset($params["barangay"]))?strtoupper($params["barangay"]):"");
					$emparr["municipality"] = ((isset($params["municipality"]))?strtoupper($params["municipality"]):"");
					$emparr["province"] = ((isset($params["province"]))?strtoupper($params["province"]):"");
					$emparr["permanent_house_number"] = ((isset($params["permanent_house_number"]))?strtoupper($params["permanent_house_number"]):"");
					$emparr["permanent_street"] = ((isset($params["permanent_street"]))?strtoupper($params["permanent_street"]):"");
					$emparr["permanent_village"] = ((isset($params["permanent_village"]))?strtoupper($params["permanent_village"]):"");
					$emparr["permanent_barangay"] = ((isset($params["permanent_barangay"]))?strtoupper($params["permanent_barangay"]):"");
					$emparr["permanent_municipality"] = ((isset($params["permanent_municipality"]))?strtoupper($params["permanent_municipality"]):"");
					$emparr["permanent_province"] = ((isset($params["permanent_province"]))?strtoupper($params["permanent_province"]):"");
					$emparr["zip_code"] = strtoupper($params["zip_code"]);
					$emparr["permanent_zip_code"] = strtoupper($params["permanent_zip_code"]);
					$emparr["contact_number"] = strtoupper($params["contact_number"]);
					$emparr["email"] = $params["email"];
					// end of employee table fields
					$this->db->trans_begin();
					$this->db->insert("tblemployees", $emparr);
					if($this->db->affected_rows() > 0) {
						$this->db->insert('tblleavebalance', array("id"=>$id,"scanning_no"=>$params['employee_number'],"source_location"=>"manual_input","year"=>(int)date("Y")-1,"vl"=>0,"sl"=>0,"total"=>0));
						$this->db->insert($this->table.'familybackground', $famarr);
						$this->db->insert($this->table.'questions', $qarr);
						$this->db->insert($this->table.'governmentid', $govids);
						if(isset($params["children_name"]) && sizeof($params["children_name"]) > 0){
							foreach ($params["children_name"] as $key => $value) {
								$rows1[] = array("employee_id"=>$id, "children_name"=> strtoupper($value), "children_birthday"=>strtoupper($params["children_birthday"][$key]));
							}
							$this->db->insert_batch($this->table.'familybackgroundchildrens',$rows1);
						}
						if(isset($params["civil_service_eligibility"]) && sizeof($params["civil_service_eligibility"]) > 0){
							foreach ($params["civil_service_eligibility"] as $key => $value) {
								$rows2[] = array("employee_id"=>$id, "civil_service_eligibility" => strtoupper($value), "rating" => strtoupper(@$params["rating"][$key]), "date_conferment" => @$params["date_conferment"][$key], "place_examination" => strtoupper(@$params["place_examination"][$key]), "license_number" => strtoupper(@$params["license_number"][$key]), "license_validity" => strtoupper(@$params["license_validity"][$key]));
							}
							$this->db->insert_batch($this->table.'civilserviceeligibility',$rows2);
						}
						if(isset($params["position"]) && sizeof($params["position"]) > 0){
							foreach ($params["position"] as $key => $value) {
							
								if(@$params["day_check"][$key]){
									$per_day = 'checked';
								}else{
									$per_day = '';
								}
								if(@$params["work_to"][$key] == 'on' || @$params["work_to"][$key] == 'PRESENT'){
									@$params["work_to"][$key] = 'PRESENT';
								}

								$rows3[] = array("employee_id"=>$id, 
								"work_from" => @$params["work_from"][$key], 
								"work_to" => @$params["work_to"][$key], 
								"position" => strtoupper(@$params["position"][$key]), 
								"company" => strtoupper(@$params["company"][$key]), 
								"monthly_salary" => @$params["monthly_salary"][$key], 
								"per_day" => $per_day,
								"grade" => @strtoupper($params["grade"][$key]), 
								"status_appointment" => @strtoupper($params["status_appointment"][$key]), 
								"gov_service" => strtoupper($params["gov_service"][$key]));
							}
							$this->db->insert_batch($this->table.'workexperience',$rows3);
						}
						if(isset($params["organization"]) && sizeof($params["organization"]) > 0){
							foreach ($params["organization"] as $key => $value) {
								$rows4[] = array("employee_id"=>$id, "organization" => strtoupper($value), "organization_work_from" => $params["organization_work_from"][$key], "organization_work_to" => $params["organization_work_to"][$key], "organization_number_hours" => $params["organization_number_hours"][$key], "organization_work_nature" => strtoupper(@$params["organization_work_nature"][$key]));
							}
							$this->db->insert_batch($this->table.'voluntarywork',$rows4);
						}
						if(isset($params["training"]) && sizeof($params["training"]) > 0){
							foreach ($params["training"] as $key => $value) {
								$rows5[] = array("employee_id"=>$id, "training" => strtoupper($value), "traning_from" => $params["traning_from"][$key], "training_to" => $params["training_to"][$key], "training_number_hours" => $params["training_number_hours"][$key], "training_type" => strtoupper(@$params["training_type"][$key]), "training_sponsored_by" => strtoupper(@$params["training_sponsored_by"][$key]));
							}
							$this->db->insert_batch($this->table.'learningdevelopment',$rows5);
						}
						if(isset($params["special_skills"]) && sizeof($params["special_skills"]) > 0){
							foreach ($params["special_skills"] as $key => $value) {
								$rows6[] = array("employee_id"=>$id, "special_skills" => strtoupper(@$value));
							}
							$this->db->insert_batch($this->table.'specialskills',$rows6);
						}
						if(isset($params["recognitions"]) && sizeof($params["recognitions"]) > 0){
							foreach ($params["recognitions"] as $key => $value) {
								$rows7[] = array("employee_id"=>$id, "recognitions" => strtoupper(@$value));
							}
							$this->db->insert_batch($this->table.'recognitions',$rows7);
						}
						if(isset($params["organization"]) && sizeof($params["organization"]) > 0){
							foreach ($params["organization"] as $key => $value) {
								$rows8[] = array("employee_id"=>$id, "organization" => strtoupper(@$value));
							}
							$this->db->insert_batch($this->table.'organizations',$rows8);
						}
						if(isset($params["reference_name"]) && sizeof($params["reference_name"]) > 0){
							foreach ($params["reference_name"] as $key => $value) {
								$rows9[] = array("employee_id"=>$id, "reference_name" => strtoupper(@$value), "reference_address" => strtoupper(@$params["reference_address"][$key]), "reference_tel_no" => strtoupper(@$params["reference_tel_no"][$key]));
							}
							$this->db->insert_batch($this->table.'references',$rows9);
						}
						if(isset($params["elementary_school"]) && sizeof($params["elementary_school"]) > 0){
							foreach ($params["elementary_school"] as $key => $value) {
								$rows10[] = array("employee_id"=>$id,"school" => strtoupper(@$value),"degree" => strtoupper(@$params["elementary_degree"][$key]),"period_from" => @$params["elementary_period_from"][$key],"period_to" => @$params["elementary_period_to"][$key],"highest_level" => strtoupper(@$params["elementary_highest_level"][$key]),"year_graduated" => @$params["elementary_year_graduated"][$key],"received" => strtoupper(@$params["elementary_received"][$key]));
							}
							$this->db->insert_batch($this->table.'educbgelementary',$rows10);
						}
						if(isset($params["secondary_school"]) && sizeof($params["secondary_school"]) > 0){
							foreach ($params["secondary_school"] as $key => $value) {
								$rows11[] = array("employee_id"=>$id, "school" => strtoupper(@$value),"degree" => strtoupper(@$params["secondary_degree"][$key]),"period_from" => @$params["secondary_period_from"][$key],"period_to" => @$params["secondary_period_to"][$key],"highest_level" => strtoupper(@$params["secondary_highest_level"][$key]),"year_graduated" => @$params["secondary_year_graduated"][$key],"received" => strtoupper(@$params["secondary_received"][$key]));
							}
							$this->db->insert_batch($this->table.'educbgsecondary',$rows11);
						}
						if(isset($params["vocational_school"]) && sizeof($params["vocational_school"]) > 0){
							foreach ($params["vocational_school"] as $key => $value) {
								$rows12[] = array("employee_id"=>$id,"school" => strtoupper(@$value),"degree" => strtoupper(@$params["vocational_degree"][$key]),"period_from" => @$params["vocational_period_from"][$key],"period_to" => @$params["vocational_period_to"][$key],"highest_level" => strtoupper(@$params["vocational_highest_level"][$key]),"year_graduated" => @$params["vocational_year_graduated"][$key],"received" => strtoupper(@$params["vocational_received"][$key]));
							}
							$this->db->insert_batch($this->table.'educbgvocationals',$rows12);
						}
						if(isset($params["college_school"]) && sizeof($params["college_school"]) > 0){
							foreach ($params["college_school"] as $key => $value) {
								$rows13[] = array("employee_id"=>$id,"school" => strtoupper(@$value),"degree" => strtoupper(@$params["college_degree"][$key]),"period_from" => @$params["college_period_from"][$key],"period_to" => @$params["college_period_to"][$key],"highest_level" => strtoupper(@$params["college_highest_level"][$key]),"year_graduated" => @$params["college_year_graduated"][$key],"received" => strtoupper(@$params["college_received"][$key]));
							}
							$this->db->insert_batch($this->table.'educbgcolleges',$rows13);
						}
						if(isset($params["grad_stud_school"]) && sizeof($params["grad_stud_school"]) > 0){
							foreach ($params["grad_stud_school"] as $key => $value) {
								$rows14[] = array("employee_id"=>$id, "school" => strtoupper(@$value),"degree" => strtoupper(@$params["grad_stud_degree"][$key]),"period_from" => @$params["grad_stud_period_from"][$key],"period_to" => @$params["grad_stud_period_to"][$key],"highest_level" => strtoupper(@$params["grad_stud_highest_level"][$key]),"year_graduated" => @$params["grad_stud_year_graduated"][$key],"received" => strtoupper(@$params["grad_stud_received"][$key]));
							}
							$this->db->insert_batch($this->table.'educbggradstuds',$rows14);
						}
						if(isset($params["uploaded_file"]["name"]) && sizeof($params["uploaded_file"]["name"]) > 0){
							$attachments = 0;
							foreach ($params["file_title"] as $key => $value) {
								if($params["uploaded_file"]["size"][$key] > 0){
									$attachments++;
									$rows15[] = array(
										"employee_id"=>$id,
										"file_title" => strtoupper(@$value),
										"uploaded_file" => @$params["uploaded_file"]["name"][$key],
										"file_size" => @$params["uploaded_file"]["size"][$key],
										"file_type" => @$params["uploaded_file"]["type"][$key],
										"file_name" => strtoupper($value).substr($params["uploaded_file"]["name"][$key],($params["uploaded_file"]["type"][$key]==="image/jpeg")?-5:-4)
										);
								}
							}
							if($attachments> 0) $this->db->insert_batch($this->table.'attatchments',$rows15);
						}
						$this->db->where('employee_id', $id);//Update photos deactivate
						$this->db->delete($this->table.'photos');
						$params2 = array("employee_id" => $id, "employee_id_photo" => $_POST['employee_id_photo']);
						$this->db->insert($this->table.'photos', $params2);

						if($this->db->trans_status() === TRUE){
							$this->db->trans_commit();
							$code = "0";
							$message = "Successfully inserted employee.";
							$helperDao->auditTrails(Helper::get('userid'),$message);
							$this->ModelResponse($code, $message, $id);
							return true;
						}else{

							$this->db->trans_rollback();
							$helperDao->auditTrails(Helper::get('userid'),$message);
							$this->ModelResponse($code, $message);
						}		
					}	
					else {
						$helperDao->auditTrails(Helper::get('userid'),$message);
					}
				
				}
			}
			else{
				$message = "Scanning No. already exists.";
			}
			$this->ModelResponse($code, $message);
			return false;
		}

		public function updateRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Employee failed to update.";
			$id = $params["id"];
			$rows1 = $rows2 = $rows3 = $rows4 = $rows5 = $rows6 = $rows7 = $rows8 = $rows9 = $rows10 = $rows11 = $rows12 = $rows13 = $rows14 = $rows15 = $govids = $qarr = $emparr = $educarr = $famarr = array();

			// additional table family background
				$famarr["spouse_last_name"] = strtoupper($params["spouse_last_name"]);
				$famarr["spouse_first_name"] = strtoupper($params["spouse_first_name"]);
				$famarr["spouse_extension"] = strtoupper($params["spouse_extension"]);
				$famarr["spouse_middle_name"] = strtoupper($params["spouse_middle_name"]);
				$famarr["spouse_occupation"] = strtoupper($params["spouse_occupation"]);
				$famarr["spouse_employer_name"] = strtoupper($params["spouse_employer_name"]);
				$famarr["spouse_business_address"] = strtoupper($params["spouse_business_address"]);
				$famarr["spouse_tel_no"] = strtoupper($params["spouse_tel_no"]);
				$famarr["father_last_name"] = strtoupper($params["father_last_name"]);
				$famarr["father_first_name"] = strtoupper($params["father_first_name"]);
				$famarr["father_extension"] = strtoupper($params["father_extension"]);
				$famarr["father_middle_name"] = strtoupper($params["father_middle_name"]);
				$famarr["mother_last_name"] = strtoupper($params["mother_last_name"]);
				$famarr["mother_first_name"] = strtoupper($params["mother_first_name"]);
				$famarr["mother_extension"] = strtoupper($params["mother_extension"]);
				$famarr["mother_middle_name"] = strtoupper($params["mother_middle_name"]);
				// end of additional table family background
				// additional table govid
				$govids['gov_issued_id'] = strtoupper(@$params["gov_issued_id"]);
				$govids['valid_id'] = strtoupper(@$params["valid_id"]);
				$govids['place_issuance'] = strtoupper(@$params["place_issuance"]);
				//end of additional table govid
				// additional table for questions
				$qarr["radio_input_01"] = @$params["radio_input_01"];
				$qarr["if_yes_01"] = ((@$params["radio_input_01"] == "Yes")?strtoupper(@$params["if_yes_01"]):"");
				$qarr["radio_input_02"] = @$params["radio_input_02"];
				$qarr["if_yes_02"] = ((@$params["radio_input_02"] == "Yes")?strtoupper(@$params["if_yes_02"]):"");
				$qarr["radio_input_03"] = @$params["radio_input_03"];
				$qarr["if_yes_03"] = ((@$params["radio_input_03"] == "Yes")?strtoupper(@$params["if_yes_03"]):"");
				$qarr["radio_input_04"] = @$params["radio_input_04"];
				$qarr["if_yes_04"] = ((@$params["radio_input_04"] == "Yes")?strtoupper(@$params["if_yes_04"]):"");
				$qarr["if_yes_case_status_04"] = ((@$params["radio_input_04"] == "Yes")?strtoupper(@$params["if_yes_case_status_04"]):"");
				$qarr["radio_input_05"] = @$params["radio_input_05"];
				$qarr["if_yes_05"] = ((@$params["radio_input_05"] == "Yes")?strtoupper(@$params["if_yes_05"]):"");
				$qarr["radio_input_06"] = @$params["radio_input_06"];
				$qarr["if_yes_06"] = ((@$params["radio_input_06"] == "Yes")?strtoupper(@$params["if_yes_06"]):"");
				$qarr["radio_input_07"] = @$params["radio_input_07"];
				$qarr["if_yes_07"] = ((@$params["radio_input_07"] == "Yes")?strtoupper(@$params["if_yes_07"]):"");
				$qarr["radio_input_08"] = @$params["radio_input_08"];
				$qarr["if_yes_08"] = ((@$params["radio_input_08"] == "Yes")?strtoupper(@$params["if_yes_08"]):"");
				$qarr["radio_input_09"] = @$params["radio_input_09"];
				$qarr["if_yes_09"] = ((@$params["radio_input_09"] == "Yes")?strtoupper(@$params["if_yes_09"]):"");
				$qarr["radio_input_10"] = @$params["radio_input_10"];
				$qarr["if_yes_10"] = ((@$params["radio_input_10"] == "Yes")?strtoupper(@$params["if_yes_10"]):"");
				$qarr["radio_input_11"] = @$params["radio_input_11"];
				$qarr["if_yes_11"] = ((@$params["radio_input_11"] == "Yes")?strtoupper(@$params["if_yes_11"]):"");
				$qarr["radio_input_12"] = @$params["radio_input_12"];
				$qarr["if_yes_12"] = ((@$params["radio_input_12"] == "Yes")?strtoupper(@$params["if_yes_12"]):"");
				// end of additional table for questions
				// employee table fields
				// additional employee fields
				$emparr["bloodtype"] = strtoupper($params["bloodtype"]);
				$emparr["sss"] = str_replace("_","0",strtoupper($params["sss"]));
				// $emparr["ageny_employee_no"] = strtoupper($params["ageny_employee_no"]);
				$emparr["tel_no"] = strtoupper($params["tel_no"]);
				// end of additional employee fields
				$emparr['employee_id_number'] = Helper::encrypt(strtoupper($params['employee_id_number']),$id);
				$emparr['employee_number'] = Helper::encrypt(strtoupper($params['employee_number']),$id);
				$emparr['first_name'] = Helper::encrypt(strtoupper($params['first_name']),$id);
				$emparr['middle_name'] = Helper::encrypt(strtoupper($params['middle_name']),$id);
				$emparr['last_name'] = Helper::encrypt(strtoupper($params['last_name']),$id);
				$emparr["extension"] = Helper::encrypt(strtoupper($params['extension']),$id);;
				$emparr['modified_by'] = Helper::get('userid');
				$emparr["division_id"] = $params["division_id"];
				$emparr["birthday"] = $params["birthday"];
				$emparr["birth_place"] = strtoupper($params["birth_place"]);
				$emparr["gender"] = $params["gender"];
				$emparr["civil_status"] = $params["civil_status"];
				$emparr["civil_status_others"] = (($params["civil_status"] == "Others")?strtoupper($params["civil_status_others"]):"");
				$emparr["height"] = $params["height"];
				$emparr["weight"] = $params["weight"];
				$emparr["gsis"] = str_replace("_","0",$params["gsis"]);
				$emparr["pagibig"] = str_replace("_","0",$params["pagibig"]);
				$emparr["philhealth"] = str_replace("_","0",$params["philhealth"]);
				$emparr["tin"] = str_replace("_","0",$params["tin"]);
				$emparr["nationality"] = $params["nationality"];
				$emparr["nationality_details"] = $params["nationality_details"];
				$emparr["nationality_country"] = (($params["nationality"] != "Filipino")?strtoupper($params["nationality_country"]):"");
				$emparr["house_number"] = ((isset($params["house_number"]))?strtoupper($params["house_number"]):"");
				$emparr["street"] = ((isset($params["street"]))?strtoupper($params["street"]):"");
				$emparr["village"] = ((isset($params["village"]))?strtoupper($params["village"]):"");
				$emparr["barangay"] = ((isset($params["barangay"]))?strtoupper($params["barangay"]):"");
				$emparr["municipality"] = ((isset($params["municipality"]))?strtoupper($params["municipality"]):"");
				$emparr["province"] = ((isset($params["province"]))?strtoupper($params["province"]):"");
				$emparr["permanent_house_number"] = ((isset($params["permanent_house_number"]))?strtoupper($params["permanent_house_number"]):"");
				$emparr["permanent_street"] = ((isset($params["permanent_street"]))?strtoupper($params["permanent_street"]):"");
				$emparr["permanent_village"] = ((isset($params["permanent_village"]))?strtoupper($params["permanent_village"]):"");
				$emparr["permanent_barangay"] = ((isset($params["permanent_barangay"]))?strtoupper($params["permanent_barangay"]):"");
				$emparr["permanent_municipality"] = ((isset($params["permanent_municipality"]))?strtoupper($params["permanent_municipality"]):"");
				$emparr["permanent_province"] = ((isset($params["permanent_province"]))?strtoupper($params["permanent_province"]):"");
				$emparr["zip_code"] = strtoupper($params["zip_code"]);
				$emparr["permanent_zip_code"] = strtoupper($params["permanent_zip_code"]);
				$emparr["contact_number"] = strtoupper($params["contact_number"]);
				$emparr["email"] = $params["email"];
				// end of employee table fields
			$this->db->trans_begin();
			// $this->db->where('id', $id)->update($this->table,$emparr);
			// if($this->db->affected_rows() > 0) {
			// if($this->db->where('id', $id)->update($this->table,$emparr)){
				$this->db->where('id', $id)->update($this->table,$emparr);
				$isFam = $this->db->select("*")->from($this->table."familybackground")->where("employee_id",$id)->limit(1)->get()->row_array();
				$isQ = $this->db->select("*")->from($this->table."questions")->where("employee_id",$id)->limit(1)->get()->row_array();
				$isGIds = $this->db->select("*")->from($this->table."governmentid")->where("employee_id",$id)->limit(1)->get()->row_array();
				if(isset($isFam)) $this->db->where('employee_id', $id)->update($this->table.'familybackground', $famarr);
				else $famarr['employee_id'] = $id; $this->db->insert($this->table.'familybackground', $famarr);
				if(isset($isQ)) $this->db->where('employee_id', $id)->update($this->table.'questions', $qarr);
				else $qarr['employee_id'] = $id; $this->db->insert($this->table.'questions', $qarr);
				if(isset($isGIds)) $this->db->where('employee_id', $id)->update($this->table.'governmentid', $govids);
				else $govids['employee_id'] = $id; $this->db->insert($this->table.'governmentid', $govids);

				$this->db->where('employee_id', $id)->delete($this->table.'familybackgroundchildrens');
				if(isset($params["children_name"]) && sizeof($params["children_name"]) > 0){
					foreach ($params["children_name"] as $key => $value) $rows1[] = array("employee_id"=>$id, "children_name"=> strtoupper($value), "children_birthday"=>$params["children_birthday"][$key]);
					$this->db->insert_batch($this->table.'familybackgroundchildrens',$rows1);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'civilserviceeligibility');
				if(isset($params["civil_service_eligibility"]) && sizeof($params["civil_service_eligibility"]) > 0){
					foreach ($params["civil_service_eligibility"] as $key => $value) $rows2[] = array("employee_id"=>$id, "civil_service_eligibility" => strtoupper($value), "rating" => strtoupper($params["rating"][$key]), "date_conferment" => $params["date_conferment"][$key], "place_examination" => strtoupper($params["place_examination"][$key]), "license_number" => strtoupper($params["license_number"][$key]), "license_validity" => $params["license_validity"][$key]);
					$this->db->insert_batch($this->table.'civilserviceeligibility',$rows2);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'workexperience');
				if(isset($params["position"]) && sizeof($params["position"]) > 0){
					foreach ($params["position"] as $key => $value) {
						// $key = sizeof($params["position"]) - $key;
						if(@$params["day_check"][$key]){
							$per_day = 'checked';
						}else{
							$per_day = '';
						}
			
						if(@$params["work_to"][$key] == 'on' || @$params["work_to"][$key] == 'PRESENT'){
							@$params["work_to"][$key] = 'PRESENT';
						}
				
						$rows3[] = array("employee_id"=>$id, "work_from" => @$params["work_from"][$key], "work_to" => @$params["work_to"][$key], "position" => strtoupper(@$params["position"][$key]), "company" => strtoupper(@$params["company"][$key]), "monthly_salary" => @$params["monthly_salary"][$key], "per_day" => $per_day, "grade" => strtoupper(@$params["grade"][$key]), "status_appointment" => strtoupper(@$params["status_appointment"][$key]), "gov_service" => strtoupper(@$params["gov_service"][$key]));
					}
					$this->db->insert_batch($this->table.'workexperience',$rows3);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'voluntarywork');
				if(isset($params["organization"]) && sizeof($params["organization"]) > 0){
					foreach ($params["organization"] as $key => $value) $rows4[] = array("employee_id"=>$id, "organization" => strtoupper(@$value), "organization_work_from" => @$params["organization_work_from"][$key], "organization_work_to" => @$params["organization_work_to"][$key], "organization_number_hours" => @$params["organization_number_hours"][$key], "organization_work_nature" => strtoupper(@$params["organization_work_nature"][$key]));
					$this->db->insert_batch($this->table.'voluntarywork',$rows4);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'learningdevelopment');
				if(isset($params["training"]) && sizeof($params["training"]) > 0){
					foreach ($params["training"] as $key => $value) {
						// $key = sizeof($params["work_from"]) - $key;
						$rows5[] = array("employee_id"=>$id, "training" => strtoupper(@$params["training"][$key]), "traning_from" => @$params["traning_from"][$key], "training_to" => @$params["training_to"][$key], "training_number_hours" => @$params["training_number_hours"][$key], "training_type" => strtoupper(@$params["training_type"][$key]), "training_sponsored_by" => strtoupper(@$params["training_sponsored_by"][$key]));
					}
					$this->db->insert_batch($this->table.'learningdevelopment',$rows5);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'specialskills');
				if(isset($params["special_skills"]) && sizeof($params["special_skills"]) > 0){
					foreach ($params["special_skills"] as $key => $value) $rows6[] = array("employee_id"=>$id, "special_skills" => strtoupper(@$value));
					$this->db->insert_batch($this->table.'specialskills',$rows6);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'recognitions');
				if(isset($params["recognitions"]) && sizeof($params["recognitions"]) > 0){
					foreach ($params["recognitions"] as $key => $value) $rows7[] = array("employee_id"=>$id, "recognitions" =>strtoupper(@$value));
					$this->db->insert_batch($this->table.'recognitions',$rows7);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'organizations');
				if(isset($params["membership"]) && sizeof($params["membership"]) > 0){
					foreach ($params["membership"] as $key => $value) $rows8[] = array("employee_id"=>$id, "organization" => strtoupper(@$value));
					$this->db->insert_batch($this->table.'organizations',$rows8);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'references');
				if(isset($params["reference_name"]) && sizeof($params["reference_name"]) > 0){
					foreach ($params["reference_name"] as $key => $value) $rows9[] = array("employee_id"=>$id, "reference_name" => strtoupper(@$value), "reference_address" => strtoupper(@$params["reference_address"][$key]), "reference_tel_no" => strtoupper(@$params["reference_tel_no"][$key]));
					$this->db->insert_batch($this->table.'references',$rows9);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'educbgelementary');
				if(isset($params["elementary_school"]) && sizeof($params["elementary_school"]) > 0){
					foreach ($params["elementary_school"] as $key => $value) $rows10[] = array("employee_id"=>$id,"school" => strtoupper(@$value),"degree" => strtoupper(@$params["elementary_degree"][$key]),"period_from" => @$params["elementary_period_from"][$key],"period_to" => @$params["elementary_period_to"][$key],"highest_level" => strtoupper(@$params["elementary_highest_level"][$key]),"year_graduated" => $params["elementary_year_graduated"][$key],"received" => strtoupper(@$params["elementary_received"][$key]));
					$this->db->insert_batch($this->table.'educbgelementary',$rows10);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'educbgsecondary');
				if(isset($params["secondary_school"]) && sizeof($params["secondary_school"]) > 0){
					foreach ($params["secondary_school"] as $key => $value) $rows11[] = array("employee_id"=>$id, "school" => strtoupper(@$value),"degree" => strtoupper(@$params["secondary_degree"][$key]),"period_from" => @$params["secondary_period_from"][$key],"period_to" => @$params["secondary_period_to"][$key],"highest_level" => strtoupper(@$params["secondary_highest_level"][$key]),"year_graduated" => $params["secondary_year_graduated"][$key],"received" => strtoupper(@$params["secondary_received"][$key]));
					$this->db->insert_batch($this->table.'educbgsecondary',$rows11);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'educbgvocationals');
				if(isset($params["vocational_school"]) && sizeof($params["vocational_school"]) > 0){
					foreach ($params["vocational_school"] as $key => $value) $rows12[] = array("employee_id"=>$id,"school" => strtoupper(@$value),"degree" => strtoupper(@$params["vocational_degree"][$key]),"period_from" => @$params["vocational_period_from"][$key],"period_to" => @$params["vocational_period_to"][$key],"highest_level" => strtoupper(@$params["vocational_highest_level"][$key]),"year_graduated" => $params["vocational_year_graduated"][$key],"received" => strtoupper(@$params["vocational_received"][$key]));
					$this->db->insert_batch($this->table.'educbgvocationals',$rows12);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'educbgcolleges');
				if(isset($params["college_school"]) && sizeof($params["college_school"]) > 0){
					foreach ($params["college_school"] as $key => $value) $rows13[] = array("employee_id"=>$id,"school" => strtoupper(@$value),"degree" => strtoupper(@$params["college_degree"][$key]),"period_from" => @$params["college_period_from"][$key],"period_to" => @$params["college_period_to"][$key],"highest_level" => strtoupper(@$params["college_highest_level"][$key]),"year_graduated" => $params["college_year_graduated"][$key],"received" => strtoupper(@$params["college_received"][$key]));
					$this->db->insert_batch($this->table.'educbgcolleges',$rows13);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'educbggradstuds');
				if(isset($params["grad_stud_school"]) && sizeof($params["grad_stud_school"]) > 0){
					foreach ($params["grad_stud_school"] as $key => $value) $rows14[] = array("employee_id"=>$id, "school" => strtoupper(@$value),"degree" => strtoupper(@$params["grad_stud_degree"][$key]),"period_from" => @$params["grad_stud_period_from"][$key],"period_to" => @$params["grad_stud_period_to"][$key],"highest_level" => strtoupper(@$params["grad_stud_highest_level"][$key]),"year_graduated" => $params["grad_stud_year_graduated"][$key],"received" => strtoupper(@$params["grad_stud_received"][$key]));
					$this->db->insert_batch($this->table.'educbggradstuds',$rows14);
				}
				
				// $employee_by_id = $this->getEmployeeByFilter($params['id']);
				// foreach ($emparr as $k2 => $v2) {
				// 	if($emparr[$k2] != $employee_by_id[0][$k2]) $this->db->insert($this->table.'updates',array('employee_id'=>$params['id'], 'column_name'=>$k2, 'previous_value'=>$employee_by_id[0][$k2], 'new_value'=>$emparr[$k2], 'modified_by'=>Helper::get('userid')));
				// }
				
				//Remove existing photo
				$this->db->where('employee_id', $id);
				$this->db->delete($this->table.'photos');
				//Insert photo
				$params2 = array("employee_id" => $id, "employee_id_photo" => $_POST['employee_id_photo'] );
				$this->db->insert($this->table.'photos', $params2);

				if($this->db->trans_status() === TRUE){
					$code = "0";
					$message = "Successfully updated employee.";
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
					$this->db->trans_commit();
					return true;		
				} else {
					$this->db->trans_rollback();
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
				}
			// }
			return false;
		}

		public function updatePayrollInfoRows($params){
			// print_r($params); die();
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Employee failed to update payroll information.";
				$id = $params["id"];
				//payroll information
				$emparr["employment_status"] = $params["employment_status"];
				$emparr["account_number"] = $params["account_number"];
				$emparr["start_date"] = DATE("Y-m-d",strtotime($params["start_date"]));
				$emparr["end_date"] = $params["end_date"] ? DATE("Y-m-d",strtotime($params["end_date"])) : "";
				$emparr["pay_basis"] = $params["pay_basis"];
				$emparr["mp2_contribution"] = $params["mp2_contribution"];
				// print_r($emparr["pay_basis"]); die();
				$emparr["philhealth_cos"] = $params["philhealth_contribution"];
				/*if ($emparr["pay_basis"] == "Contract of Service"){
					$emparr["philhealth_cos"] = $params["philhealth_contribution"];
				}*/
				$emparr["item_no"] = isset($params['item_no'])?$params['item_no']:"";
				$emparr["location_id"] = trim($params["location_id"]);

				$emparr["position_id"] = isset($params['item_no']) && $emparr['item_no'] != "" ? $params["item_no"] : $params["position_title"];
				$emparr["daily_rate"] = $params["daily_rate"];
				
				// $emparr["position_designation"] = trim($params["position_designation"]);
				// $emparr["division_designation"] = trim($params["division_designation"]);
				$emparr["date_of_permanent"] = $params["date_of_permanent"] ? DATE("Y-m-d",strtotime($params["date_of_permanent"])) : "";
				// $emparr["salary_grade_id"] = $params["salary_grade_id"];
				$emparr["salary_grade_id"] = ($params["salary_grade"] != null || $params["salary_grade"] != "") ? $params["salary_grade"] : null;
				$salary_grade_step_id = isset($params["salary_grade_step_id"])?$params["salary_grade_step_id"]:0;
				$emparr["salary_grade_step_id"] = $salary_grade_step_id;
				
				$emparr["tax_percentage"] = $params["tax_percentage"];
				$emparr["tax_additional"] = $params["tax_additional"];

				// $emparr['uses_biometrics'] = (isset($params['uses_biometrics']))?"1":"0";
				// $emparr['uses_atm'] = (isset($params['uses_atm']))?"1":"0";
				// $emparr['is_initial_salary'] = (isset($params['is_initial_salary']))?"1":"0";

				$emparr["salary"] = $params["salary"];

				$emparr["cut_off_1"] = ((isset($params["cut_off_1"]) || $params["cut_off_1"] === "")?$params["cut_off_1"]:0);
				$emparr["cut_off_2"] = ((isset($params["cut_off_2"]) || $params["cut_off_2"] === "")?$params["cut_off_2"]:0);
				$emparr["cut_off_3"] = ((isset($params["cut_off_3"]) || $params["cut_off_3"] === "")?$params["cut_off_3"]:0);
				$emparr["cut_off_4"] = ((isset($params["cut_off_4"]) || $params["cut_off_4"] === "")?$params["cut_off_4"]:0);
				
				$emparr['with_late'] = (isset($params['with_late']))?"1":"0";
				$emparr['regular_shift'] = (isset($params['regular_shift']))?$params['regular_shift']:"0";
				$emparr["shift_id"] = (isset($params['regular_shift']) && $params['regular_shift'] == 1)?$params["shift_id"]:0;
				$emparr["flex_shift_id"] = (isset($params['regular_shift']) && $params['regular_shift'] == 0)?$params["flex_shift_id"]:0;
				$shift_date_effectivity = (isset($params["shift_date_effectivity"]))?$params["shift_date_effectivity"]:"";
				$is_change = 0;
				if($params["regular_shift"] === "1"){
					if($params["tmp_regular_shift"] !== $params["regular_shift"] || $params["tmp_shift_id"] !== $params["shift_id"]){
						$emparr["shift_date_effectivity"] = $shift_date_effectivity;
						$is_change++;
					}
				}else {
					if($params["tmp_regular_shift"] !== $params["regular_shift"] || $params["tmp_shift_id"] !== $params["flex_shift_id"]){
						$emparr["shift_date_effectivity"] = $shift_date_effectivity;
						$is_change++;
					}
				}
				$emparr['with_tax'] = (isset($params['with_tax']))?"1":"0";
				$emparr['with_gsis'] = (isset($params['with_gsis']))?"1":"0";
				$emparr['with_sss'] = (isset($params['with_sss']))?"1":"0";
				$emparr['with_e_cola'] = (isset($params['with_e_cola']))?"1":"0";
				$emparr['with_pera'] = (isset($params['with_pera']))?"1":"0";
				$emparr['with_pagibig_contribution'] = (isset($params['with_pagibig_contribution']))?"1":"0";
				$emparr["pagibig_contribution"] = $params["pagibig_contribution"];
				$emparr['with_philhealth_contribution'] = (isset($params['with_philhealth_contribution']))?"1":"0";
				$emparr['with_acpcea'] = (isset($params['with_acpcea']))?"1":"0";
				$emparr['with_damayan'] = (isset($params['with_damayan']))?"1":"0";
				$emparr['with_union_dues'] = (isset($params['with_union_dues']))?"1":"0";
				$emparr['with_mp2_contributions'] = (isset($params['with_mp2_contributions']))?"1":"0";

				$emparr["tax"] = $params["tax"];
				$emparr["gmp"] = $params["gmp"];
				$emparr["0th"] = $params["0th"];
				$emparr["rep_allowance"] = $params["rep_allowance"];
				$emparr["trans_allowance"] = $params["trans_allowance"];

				// end of employee table fields
			$this->db->trans_begin();
			// $employee_by_id = $this->getEmployeeByFilter($params['id']);
			// foreach ($emparr as $k2 => $v2) {
			// 	if($emparr[$k2] != $employee_by_id[0][$k2]){
			// 		$params3 = array(
			// 			'employee_id'=>$params['id'],
			// 			'column_name'=>$k2,
			// 			'previous_value'=>$employee_by_id[0][$k2],
			// 			'new_value'=>(($k2 == "regular_shift" && $emparr[$k2] == "") || ($k2 == "shift_id" && $emparr[$k2] == "") || ($k2 == "flex_shift_id" && $emparr[$k2] == ""))?0:$emparr[$k2],
			// 			'modified_by'=>Helper::get('userid')
			// 		);
			// 		$this->db->insert($this->table.'updates',$params3);
			// 	}
			// }

			// print_r($emparr); die();
			$this->db->where('id', $id)->update($this->table,$emparr);
			$shift_arr = array(
				"employee_id"=>isset($params['id'])?$params['id']:"",
				"shift_type"=>isset($params['tmp_regular_shift'])?$params['tmp_regular_shift']:null, 
				"shift_id"=>isset($params['tmp_shift_id'])?$params['tmp_shift_id']:null,
				"previous_date_effectivity"=>isset($params["tmp_shift_date_effectivity"])?$params["tmp_shift_date_effectivity"]:null,
				"created_by"=>Helper::get('userid')
			);
			if($is_change>0) $this->db->insert($this->table."shifthistory",$shift_arr);
				if($this->db->trans_status() === TRUE){
					$code = "0";
					$message = "Successfully updated employee payroll information.";
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
					$this->db->trans_commit();
					return true;		
				} else {
					$this->db->trans_rollback();
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
				}
			return false;
		}

		public function addEmployeeAttachment($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Insert employee attachment failed.";

			$update_params = array();
			$add_params = array();
			if(isset($params["file_title"]) && sizeof($params["file_title"]) > 0){
				foreach ($params["file_title"] as $key => $value){
					$arr = array();
					if(isset($params["index_id"][$key]) && $params["index_id"][$key] != ""){
						$arr["id"] = $params["index_id"][$key];
						$arr["file_title"] = $value;
						if($params["new_file"]["name"][$key] !== "" ) $arr["uploaded_file"] = $params["new_file"]["name"][$key];
						if($params["new_file"]["type"][$key] !== "" ) $arr["file_type"] = $params["new_file"]["type"][$key];
						if($params["new_file"]["size"][$key] !== 0 ) $arr["file_size"] = $params["new_file"]["size"][$key];
						if($params["new_file"]["size"][$key] > 0 ) $arr["file_name"] = $params["new_file"]["name"][$key];
						$update_params[] = $arr;
					}else{
						$arr["employee_id"] = $params["id"];
						$arr["file_title"] = $value;
						if($params["new_file"]["name"][$key] !== "" ) $arr["uploaded_file"] = $params["new_file"]["name"][$key];
						if($params["new_file"]["type"][$key] !== "" ) $arr["file_type"] = $params["new_file"]["type"][$key];
						if($params["new_file"]["size"][$key] !== 0 ) $arr["file_size"] = $params["new_file"]["size"][$key];
						$arr["file_name"] = $params["new_file"]["name"][$key];
						$add_params[] = $arr;
					}
				}
			}
			$this->db->trans_begin();
			$this->db->where_not_in('id', $params["index_id"])->where("employee_id",$params["id"])->delete($this->table.'attatchments');
			if(sizeof($update_params)>0)$this->db->update_batch($this->table.'attatchments',$update_params,"id");
			if(sizeof($add_params)>0) $this->db->insert_batch($this->table.'attatchments',$add_params);
			if($this->db->trans_status() === TRUE){
				$code = "0";
				$message = "Successfully employee attachment files.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				$this->db->trans_commit();
				return true;		
			} else {
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		public function updateSpecificEmployeesAttachmentRow($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Update employee attachment failed.";
			$addparam = array(
				"employee_id"=>$params["employee_id"],
				"file_title" => $params["file_title"],
				"uploaded_file" => $params["uploaded_file"]["name"],
				"file_size" => $params["uploaded_file"]["size"],
				"file_type" => $params["uploaded_file"]["type"],
				"file_name" => $params["file_title"].substr($params["uploaded_file"]["name"],($params["uploaded_file"]["type"]==="image/jpeg")?-5:-4)
			);
			if($this->db->where("id",$params["id"])->update($this->table.'attatchments',$addparam)){
				$code = "0";
				$message = "Successfully update employee attachment file.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;		
			} else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		public function deleteSpecificEmployeesAttachmentRow($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Delete employee attachment failed.";
			if($this->db->where("id",$params["id"])->delete($this->table.'attatchments')){
				$code = "0";
				$message = "Successfully delete employee attachment file.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;		
			} else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		public function givePDSAccess($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Allow employee to access PDS Failed.";
			$data['Id'] = isset($params['id'])?$params['id']:'';
			$data['Status'] = "0";
			$this->sql = "CALL sp_grant_employee_pds_access(?)";
			$updateparams = array($data['Id']);
			$this->db->query($this->sql,$updateparams);
			mysqli_next_result($this->db->conn_id);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Allow employee to access PDS successful.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;		
			} else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		public function addAllowancesRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$params['modified_by'] = Helper::get('userid');
			$message = "Allowance failed to insert.";
			$allowance_id = @$params['allowance_id'];
			$employee_id = @$params['employee_id'];
			if(sizeof($this->isEmployeeAllowanceExists($allowance_id,$employee_id)) > 0){
				$message = "Employee allowance already exists.";
				$this->ModelResponse($code, $message);
			}else{
				$this->db->insert('tblemployeesallowances', $params);
				if($this->db->affected_rows() > 0){
					$code = "0";
					$message = "Successfully inserted Allowance.";
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
					return true;		
				}else {
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
				}
			}
			return false;
		}
		public function updateAllowancesRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$params['modified_by'] = Helper::get('userid');
			$message = "Allowance failed to update.";
			$allowance_id = @$params['allowance_id'];
			$employee_id = @$params['employee_id'];
			$this->db->where('id', $params['id']);
			if ($this->db->update('tblemployeesallowances',$params) === FALSE){
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}else {
				$code = "0";
				$message = "Successfully updated employee allowance.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;	
			}
			return false;
		}

		public function hasRowsPhotos($employee_id){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$this->sql = " CALL sp_employee_photo_details(?)";
			$query = $this->db->query($this->sql,array($employee_id));
			$result = $query->result();
			mysqli_next_result($this->db->conn_id);
			$data['details'] = $result;
			if(sizeof($result) > 0){
				$code = "0";
				$message = "Successfully fetched photo.";
				$this->ModelResponse($code, $message, $data);
				// $helperDao->auditTrails(Helper::get('userid'),$message);
				return true;		
			}else {
				$this->ModelResponse($code, $message);
				// $helperDao->auditTrails(Helper::get('userid'),$message);
			}
			return false;
		}

		public function getEmployeeList($pay_basis,$location_id,$division_id = null, $specific = null, $leave_grouping_id = null, $payroll_grouping_id = null) {
			$this->select_column[] = 'c.agency_name';
			$this->select_column[] = 'd.name';
			$this->select_column[] = 'e.department_name';
			$this->select_column[] = 'f.name';
			$this->sql = "SELECT tblemployees.id, tblemployees.employee_number, tblemployees.employee_id_number, tblemployees.first_name, tblemployees.middle_name, tblemployees.last_name, tblemployees.shift_id, c.code AS agency_code, d.name AS office_name, e.department_name AS department_name, f.name AS location_name,
				tblemployees.employment_status,tblemployees.date_of_permanent,tblemployees.present_day,tblemployees.pay_basis,tblemployees.location_id,tblemployees.division_id,tblemployees.leave_grouping_id,tblemployees.payroll_grouping_id
			FROM tblemployees
			LEFT JOIN tblfieldagencies c ON tblemployees.agency_id = c.id
			LEFT JOIN tblfieldoffices d ON tblemployees.office_id = d.id
			LEFT JOIN tblfielddepartments e ON tblemployees.division_id = e.id
			LEFT JOIN tblfieldlocations f ON tblemployees.location_id = f.id";
			$this->sql .= " WHERE tblemployees.employment_status = 'Active' ";
			$this->sql .= (isset($pay_basis) && $pay_basis != "") ? " AND tblemployees.pay_basis='" . $pay_basis . "'" : "";
			if(isset($location_id) && $location_id != null && $location_id != "")
				$this->sql .= " AND tblemployees.location_id = ".$location_id." ";
			$this->sql .= isset($division_id) && $division_id != null && $division_id != "" ? " AND tblemployees.division_id='" . $division_id . "' " : "";
			if(isset($specific) && $specific != null && $specific != "") {
				$this->sql.= "AND (";
				$first = true;
			 	foreach ($this->select_column as $key => $value) {
			 		if($value == "tblemployees.first_name" || $value == "tblemployees.last_name" || $value == "tblemployees.middle_name" || $value == "tblemployees.employee_number" || $value == "tblemployees.employee_id_number")  {
			 			if($first) $this->sql .= " DECRYPTER($value,'sunev8clt1234567890',$this->table.id) = '$specific' ";
						else $this->sql .= " OR DECRYPTER($value,'sunev8clt1234567890',$this->table.id) = '$specific' ";
					}else{
			 			if($first) $this->sql .= " $value = '$specific' ";
			 			else $this->sql .= " OR $value = '$specific' ";			 			
			 		}
			 		$first = false;
			 	}
			 	$this->sql .= " OR CONCAT(DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',$this->table.id),' ',DECRYPTER(tblemployees.middle_name,'sunev8clt1234567890',$this->table.id),' ',DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',$this->table.id)) = '$specific' "; 
			    $this->sql.= ")";
			}
			$query = $this->db->query($this->sql);
			$rows = $query->result_array();
			return $rows;
		}

		// WILL EDIT TO REMOVE EMPLOYEES WITH < 10 LEAVE CREDITS
		public function getRegularEmployeeList($pay_basis,$location_id,$division_id = null, $specific = null, $leave_grouping_id = null, $payroll_grouping_id = null) {
			$this->select_column[] = 'c.agency_name';
			$this->select_column[] = 'd.name';
			$this->select_column[] = 'e.department_name';
			$this->select_column[] = 'f.name';
			$this->sql = "SELECT tblemployees.id, tblemployees.employee_number, tblemployees.employee_id_number, tblemployees.first_name, tblemployees.middle_name, tblemployees.last_name, tblemployees.shift_id, tblemployees.regular_shift, 
				c.code AS agency_code, d.name AS office_name, e.department_name AS department_name, f.name AS location_name,
				tblemployees.employment_status,tblemployees.date_of_permanent,tblemployees.present_day,tblemployees.pay_basis,tblemployees.location_id,tblemployees.division_id,tblemployees.leave_grouping_id,tblemployees.payroll_grouping_id
			FROM tblemployees
			LEFT JOIN tblfieldagencies c ON tblemployees.agency_id = c.id
			LEFT JOIN tblfieldoffices d ON tblemployees.office_id = d.id
			LEFT JOIN tblfielddepartments e ON tblemployees.division_id = e.id
			LEFT JOIN tblfieldlocations f ON tblemployees.location_id = f.id";
			$this->sql .= isset($pay_basis) ? " WHERE tblemployees.pay_basis='" . $pay_basis . "'" : "";
			$this->sql .= " AND tblemployees.employment_status = 'Active' ";
			if(isset($location_id) && $location_id != null && $location_id != "")
				$this->sql .= " AND tblemployees.location_id = ".$location_id." ";
			$this->sql .= isset($division_id) && $division_id != null && $division_id != "" ? " AND tblemployees.division_id='" . $division_id . "' " : "";
			if(isset($specific) && $specific != null && $specific != ""){
				$this->sql.= "AND (";
				$first = true;
			 	foreach ($this->select_column as $key => $value) {
			 		if($value == "tblemployees.first_name" || $value == "tblemployees.last_name" || $value == "tblemployees.middle_name" || $value == "tblemployees.employee_number" || $value == "tblemployees.employee_id_number")  {
			 			if($first) $this->sql .= " DECRYPTER($value,'sunev8clt1234567890',$this->table.id) = '$specific' ";
			 			else $this->sql .= " OR DECRYPTER($value,'sunev8clt1234567890',$this->table.id) = '$specific' ";
					}else{
			 			if($first) $this->sql .= " $value = '$specific' ";
						else $this->sql .= " OR $value = '$specific' ";
			 		}
			 		$first = false;
			 	}
			 	$this->sql .= " OR CONCAT(DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',$this->table.id),' ',DECRYPTER(tblemployees.middle_name,'sunev8clt1234567890',$this->table.id),' ',DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',$this->table.id)) = '$specific' "; 
			    $this->sql.= ")";
			}
			$query = $this->db->query($this->sql);
			$rows = $query->result_array();
			return $rows;
		}

		public function getEmployeeByFilter($id){
			$this->sql = "CALL sp_get_employee_by_filter(?)";
			$query = $this->db->query($this->sql,array($id));
			$rows = $query->result_array();
			mysqli_next_result($this->db->conn_id);
			return $rows;
			// $this->db->select('*');
			// $this->db->from($this->table);
			// $this->db->where($filter);
			// return $this->db->get()->result_array();
		}
		public function isEmployeeNumberExist($num){
			$this->sql = "CALL sp_is_Employee_num_exist(?)";
			$query = $this->db->query($this->sql,array($num));
			$rows = $query->result();
			mysqli_next_result($this->db->conn_id);
			// $this->db->select("*");
			// $this->db->from($this->table);
			// $this->db->where("DECRYPTER(tblemployees.employee_number,'sunev8clt1234567890',tblemployees.id) = '".$num."'");
			if(sizeof($rows) > 0){
				return true;
			}
			return false;
		}

	}
?>