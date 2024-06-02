<?php
	class EmployeesCollection extends Helper {
      	var $select_column = null; 
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
			$columns = $this->getColumns();
			foreach ($columns as $key => $value) {
				$this->select_column[] = $this->table.'.'.$value;
			}
			//var_dump($this->select_column);die();

		}
		//Fetch
		var $table = "tblemployees";   
      	var $order_column = array(
      				"DECRYPTER(tblemployees.employee_id_number,'sunev8clt1234567890',tblemployees.id)",
      				"DECRYPTER(tblemployees.employee_number,'sunev8clt1234567890',tblemployees.id)",
      				"DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id)",
      				"tblfieldpositions.name",
      				"tblfielddepartments.department_name",
      				"tblfieldlocations.name",
      				"tblemployees.employment_status",
      				"tblemployees.date_created",
      				""
      	);
      	public function getColumns(){
      		
      		/*$sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$this->table."' AND TABLE_SCHEMA='".$this->db->database."' ";
			$query = $this->db->query($sql);
			$rows = $query->result_array();*/
			$rows = array(
				'employee_id_number',
				'employee_number',
				'last_name',
				'middle_name',
				'first_name',
				'contact_number',
				'employment_status'
			);
			return $rows; 
      	}
		function make_query()  
		{  
			//var_dump($this->select_column);die();
			$this->select_column[] = 'tblfieldpositions.name';
			$this->select_column[] = 'tblfieldagencies.agency_name';
			$this->select_column[] = 'tblfieldoffices.name';
			$this->select_column[] = 'tblfielddepartments.department_name';
			$this->select_column[] = 'tblfieldlocations.name';
			$this->select_column[] = 'tblfieldfundsources.fund_source';
			$this->select_column[] = 'tblfieldbudgetclassifications.budget_classification_name';
		    $this->db->select(
		    	$this->table.'.*,
		    	tblfieldpositions.name AS position_name,
		    	tblfieldoffices.name AS office_name,
		    	tblfielddepartments.department_name,
		    	tblfieldlocations.name AS location_name,
		    	tblfieldfundsources.fund_source,
		    	tblfieldbudgetclassifications.budget_classification_name'
		    );  
		    $this->db->from($this->table);  
		    $this->db->join("tblfieldpositions",$this->table.".position_id = tblfieldpositions.id","left");
		    $this->db->join("tblfieldagencies",$this->table.".agency_id = tblfieldagencies.id","left");
		    $this->db->join("tblfieldoffices",$this->table.".office_id = tblfieldoffices.id","left");
		    $this->db->join("tblfielddepartments",$this->table.".division_id = tblfielddepartments.id","left");
		    $this->db->join("tblfieldlocations",$this->table.".location_id = tblfieldlocations.id","left");
		    $this->db->join("tblfieldfundsources",$this->table.".fund_source_id = tblfieldfundsources.id","left");
		    $this->db->join("tblfieldbudgetclassifications",$this->table.".budget_classification_id = tblfieldbudgetclassifications.id","left");
		   
		    if(isset($_POST["search"]["value"]))  
		    {  
		    	$this->db->group_start();

	     		//var_dump($this->select_column);die();
		     	foreach ($this->select_column as $key => $value) {
		     		//$this->db->like($value, $_POST["search"]["value"]); 
		     		//var_dump($value == "$this->table.first_name");
		     		if($value == "tblemployees.first_name" || $value == "tblemployees.last_name" || $value == "tblemployees.middle_name" || $value == "tblemployees.employee_number" || $value == "tblemployees.employee_id_number")  {
		     			$this->db->or_like("DECRYPTER($value,'sunev8clt1234567890',$this->table.id)", $_POST["search"]["value"]);  

		     		}
		     		else{
		     			$this->db->or_like($value, $_POST["search"]["value"]);  
		     		}
		     		
		     	}
		     	$this->db->or_like("CONCAT(DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',$this->table.id),', ',DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',$this->table.id),' ',DECRYPTER(tblemployees.middle_name,'sunev8clt1234567890',$this->table.id))"
		     		,$_POST["search"]["value"]);
		     	/*$this->db->or_like("CONCAT(DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',$this->table.id),' ',DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',$this->table.id))"
		     		,$_POST["search"]["value"]);*/
		        $this->db->group_end(); 
		    }
		    if(isset($_GET['PayBasis']) && $_GET['PayBasis'] != null)
		    	$this->db->where($this->table.'.pay_basis = "'.$_GET['PayBasis'].'"');
		    if(isset($_GET['LocationId']) && $_GET['LocationId'] != null)
		    	$this->db->where($this->table.'.location_id = "'.$_GET['LocationId'].'"');
		    if(isset($_SESSION['division_id']) && $_SESSION['division_id'] != null)
		    	$this->db->where($this->table.'.division_id = "'.$_SESSION['division_id'].'"');
		    if(isset($_GET['EmploymentStatus']) && $_GET['EmploymentStatus'] != null && $_GET['EmploymentStatus'] != "Active")
		    	$this->db->where($this->table.'.employment_status != "Active"');
		    else
		    	$this->db->where($this->table.'.employment_status = "Active"');
		    //var_dump($_GET['Id']);die();
		    if(isset($_GET['Id']) && $_GET['Id'] != null)
		    	$this->db->where($this->table.'.id="'.$_GET['Id'].'"');//die('hit');
		    if(isset($_POST['location_id']) && $_POST['location_id'] != null)
		    	$this->db->where($this->table.'.location_id="'.$_POST['location_id'].'"');
		    if(isset($_POST["order"]))  
		    {  	
		    	$this->db->order_by($this->order_column[$_POST['order']['0']['column']]." ". $_POST['order']['0']['dir']);
		    	// var_dump($this->order_column[$_POST['order']['0']['column']]." ". $_POST['order']['0']['dir']);die();
		        // $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
		    }  
		    else  
		    {  
		          $this->db->order_by('date_created', 'DESC');  
		    }  
		}  
		function make_datatables(){  
		    $this->make_query();  
		    if($_POST["length"] != -1)  
		    {  
		        $this->db->limit($_POST['length'], $_POST['start']);  
		    }  

		    $query = $this->db->get();  
		    //echo $this->db->last_query();die();

		    //var_dump($this->db->last_query());die();
		    return $query->result();  

		}  
		function hasRows(){
			$code = "1";
			$message = "No data available.";
			$sql = "SELECT a.id,a.location_id,a.employee_number, a.employee_id_number, a.first_name, a.middle_name, a.last_name, a.shift_id, c.code AS agency_code, d.name AS office_name, e.department_name AS department_name, f.name AS location_name FROM tblemployees a
			LEFT JOIN tblfieldagencies c ON a.agency_id = c.id
			LEFT JOIN tblfieldoffices d ON a.office_id = d.id
			LEFT JOIN tblfielddepartments e ON a.division_id = e.id
			LEFT JOIN tblfieldlocations f ON a.location_id = f.id ";
			$sql .= " WHERE a.employment_status = 'Active' ";
			if(isset($_POST['division_id']) && $_POST['division_id'] != "")
				$sql .= " AND a.division_id = '".$_POST['division_id']."' ";
			if(isset($_POST['location_id']) && $_POST['location_id'] != ""){
				$sql .= " AND a.location_id = '".$_POST['location_id']."' ";
			}
			else{
				$sql .= " AND 1=0 ";
			}
			if(isset($_POST['employee_id']))
				$sql .= " AND a.id = '".$_POST['employee_id']."' ";
			// var_dump($sql);die();
			$query = $this->db->query($sql);
			$rows = $query->result();
		    if(sizeof($rows) > 0){
				$code = "0";
				$message = "Successfully fetched employee.";
				$data['details'] = $rows;
				$this->ModelResponse($code, $message, $data);
				return true;		
			}	
			else {
				$this->ModelResponse($code, $message);
			}
			return false;
		}
		function hasRowsById(){
			$code = "1";
			$message = "No data available.";
			$this->make_query(); 
			$query = $this->db->get();
			$rows = $query->result();
		    if(sizeof($rows) > 0){
				$code = "0";
				$message = "Successfully fetched employee.";
				$data['details'] = $rows;
				$this->ModelResponse($code, $message, $data);
				return true;		
			}	
			else {
				$this->ModelResponse($code, $message);
			}
			return false;
		}
		function get_filtered_data(){  
		     $this->make_query(); 
		     //var_dump($this->make_query());die();

		     $query = $this->db->get();  
		     return $query->num_rows();  
		}       
		function get_all_data()  
		{  
		    $this->db->select($this->table."*");  
		    $this->db->from($this->table);
		    if(isset($_GET['EmploymentStatus']) && $_GET['EmploymentStatus'] != null){
		    	$this->db->where($this->table.'.employment_status',$_GET['EmploymentStatus']);
		    }
		    return $this->db->count_all_results();  
		}  
		//End Fetch
		public function hasRowsAllowances($employee_id){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT b.*,a.* FROM tblemployeesallowances a LEFT JOIN tblfieldallowances b ON b.id = a.allowance_id WHERE employee_id = ? ORDER BY a.is_active DESC";
			$query = $this->db->query($sql,array($employee_id));
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched sub allowances.";
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
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "1";
			$userlevel_sql = "	UPDATE ".$this->table." SET
									is_active = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully activated employee.";
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
			$message = "Employee failed to deactivate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "0";
			$userlevel_sql = "	UPDATE ".$this->table." SET
									is_active = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully deactivated employee.";
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
		public function activeAllowancesRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Allowance level failed to activate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "1";
			$userlevel_sql = "	UPDATE tblemployeesallowances SET
									is_active = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully activated allowance.";
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
		public function inactiveAllowancesRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Allowance failed to deactivate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "0";
			$userlevel_sql = "	UPDATE tblemployeesallowances SET
									is_active = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully deactivated allowance.";
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
			$message = "Employee failed to insert.";
			$id = uniqid();
			if(!$this->isEmployeeNumberExist($params['employee_number'])){
				$params['id'] = $id;
				$params['employee_id_number'] = Helper::encrypt($params['employee_id_number'],$id);
				$params['employee_number'] = Helper::encrypt($params['employee_number'],$id);
				$params['first_name'] = Helper::encrypt($params['first_name'],$id);
				$params['middle_name'] = Helper::encrypt($params['middle_name'],$id);
				$params['last_name'] = Helper::encrypt($params['last_name'],$id);
				$params['modified_by'] = Helper::get('userid');

				$this->db->insert($this->table, $params);
				if($this->db->affected_rows() > 0)
				{
					//Update photos deactivate
					$this->db->where('employee_id', $id);
				    $this->db->delete($this->table.'photos');

					$params2 = array("employee_id" => $id,
									 "employee_id_photo" => $_POST['employee_id_photo']
							);
					$this->db->insert($this->table.'photos', $params2);
					$code = "0";
					$message = "Successfully inserted employee.";
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
					return true;		
				}	
				else {
					$helperDao->auditTrails(Helper::get('userid'),$message);
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
			// var_dump($params);die();
			$params['employee_number'] = Helper::encrypt($params['employee_number'],$params['id']);
			$params['employee_id_number'] = Helper::encrypt($params['employee_id_number'],$params['id']);
			$params['first_name'] = Helper::encrypt($params['first_name'],$params['id']);
			$params['middle_name'] = Helper::encrypt($params['middle_name'],$params['id']);
			$params['last_name'] = Helper::encrypt($params['last_name'],$params['id']);
			$params['modified_by'] = Helper::get('userid');
			$params['uses_biometrics'] = isset($params['uses_biometrics'])?"1":"0";
			$params['with_late'] = isset($params['with_late'])?"1":"0";
			$params['regular_shift'] = isset($params['regular_shift'])?"1":"0";
			$params['with_tax'] = isset($params['with_tax'])?"1":"0";
			$params['with_gsis'] = isset($params['with_gsis'])?"1":"0";
			$params['with_e_cola'] = isset($params['with_e_cola'])?"1":"0";
			$params['with_pera'] = isset($params['with_pera'])?"1":"0";
			$params['with_pagibig_contribution'] = isset($params['with_pagibig_contribution'])?"1":"0";
			$params['with_philhealth_contribution'] = isset($params['with_philhealth_contribution'])?"1":"0";
			$params['with_acpcea'] = isset($params['with_acpcea'])?"1":"0";
			$this->db->trans_begin();
			$employee_by_id = $this->getEmployeeByFilter(array('id'=>$params['id']));
			// var_dump($employee_by_id);die();
			foreach ($params as $k2 => $v2) {
				if($params[$k2] != $employee_by_id[0][$k2]){
					$params3 = array(
						'employee_id'=>$params['id'],
						'column_name'=>$k2,
						'previous_value'=>$employee_by_id[0][$k2],
						'new_value'=>$params[$k2],
						'modified_by'=>Helper::get('userid')
					);
					// var_dump($this->db->insert($this->table.'updates',$params3));die();
					$this->db->insert($this->table.'updates',$params3);
				}
			}
			$this->db->where('id', $params['id']);
			$this->db->update($this->table,$params);
			if($this->db->trans_status() === TRUE){
				//Remove existing photo
				$id = $params["id"];
				$this->db->where('employee_id', $id);
			    $this->db->delete($this->table.'photos');
			    //Insert photo
				$params2 = array("employee_id" => $id,
								 "employee_id_photo" => $_POST['employee_id_photo']
						);
				$this->db->insert($this->table.'photos', $params2);

				$code = "0";
				$message = "Successfully updated employee.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				$this->db->trans_commit();
				return true;		
			}	
			else {
				$this->db->trans_rollback();
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
			}
			else{
				$this->db->insert('tblemployeesallowances', $params);
				if($this->db->affected_rows() > 0)
				{
					$code = "0";
					$message = "Successfully inserted Allowance.";
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
					return true;		
				}	
				else {
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
			}	
			else {
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
			$sql = " SELECT * FROM ".$this->table."photos WHERE employee_id = ? AND is_active='1' ORDER BY is_active DESC";
			$query = $this->db->query($sql,array($employee_id));
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched photo.";
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
		public function getEmployeeList($pay_basis,$location_id,$division_id = null, $specific = null) {
			// var_dump($specific);die();
			$this->select_column[] = 'c.agency_name';
			$this->select_column[] = 'd.name';
			$this->select_column[] = 'e.department_name';
			$this->select_column[] = 'f.name';
			$sql = "SELECT tblemployees.id, tblemployees.employee_number, tblemployees.employee_id_number, tblemployees.first_name, tblemployees.middle_name, tblemployees.last_name, tblemployees.shift_id, c.code AS agency_code, d.name AS office_name, e.department_name AS department_name, f.name AS location_name 
			FROM tblemployees
			LEFT JOIN tblfieldagencies c ON tblemployees.agency_id = c.id
			LEFT JOIN tblfieldoffices d ON tblemployees.office_id = d.id
			LEFT JOIN tblfielddepartments e ON tblemployees.division_id = e.id
			LEFT JOIN tblfieldlocations f ON tblemployees.location_id = f.id";
			$sql .= isset($pay_basis) ? " WHERE tblemployees.pay_basis='" . $pay_basis . "'" : "";
			$sql .= " AND tblemployees.employment_status = 'Active' ";
			$sql .= " AND tblemployees.location_id = ".$location_id." ";
			$sql .= isset($division_id) && $division_id != null && $division_id != "" ? " AND tblemployees.division_id='" . $division_id . "' " : "";
			if(isset($specific) && $specific != null && $specific != "")  
			{  

				$sql.= "AND (";
				$first = true;
			 	foreach ($this->select_column as $key => $value) {
			 		if($value == "tblemployees.first_name" || $value == "tblemployees.last_name" || $value == "tblemployees.middle_name" || $value == "tblemployees.employee_number" || $value == "tblemployees.employee_id_number")  {
			 			// $this->db->or_like("", $specific); 
			 			if($first){
			 				$sql .= " DECRYPTER($value,'sunev8clt1234567890',$this->table.id) = '$specific' ";
			 			}
			 			else{
			 				$sql .= " OR DECRYPTER($value,'sunev8clt1234567890',$this->table.id) = '$specific' ";
			 			}
			 			 

			 		}
			 		else{
			 			// $this->db->or_like($value, $specific);  
			 			if($first){
			 				$sql .= " $value = '$specific' ";
			 			}
			 			else{
			 				$sql .= " OR $value = '$specific' ";
			 			}
			 			
			 		}
			 		$first = false;
			 	}
			 	// $this->db->or_like("CONCAT(DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',$this->table.id),' ',DECRYPTER(tblemployees.middle_name,'sunev8clt1234567890',$this->table.id),' ',DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',$this->table.id))"
			 	// 	,$specific);
			 	$sql .= " OR CONCAT(DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',$this->table.id),' ',DECRYPTER(tblemployees.middle_name,'sunev8clt1234567890',$this->table.id),' ',DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',$this->table.id)) = '$specific' "; 
			    $sql.= ")";
			}
			$query = $this->db->query($sql);
			$rows = $query->result_array();
			return $rows;
		}
		public function getEmployeeByFilter($filter){
			$this->db->select('*');
			$this->db->from($this->table);
			$this->db->where($filter);
			return $this->db->get()->result_array();
		}
		public function isEmployeeNumberExist($num){
			$this->db->select("*");
			$this->db->from($this->table);
			$this->db->where("DECRYPTER(tblemployees.employee_number,'sunev8clt1234567890',tblemployees.id) = '".$num."'");
			if($this->db->get()->num_rows() > 0){
				return true;
			}
			return false;
		}

	}
?>