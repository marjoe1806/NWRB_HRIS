<?php
	class RequestApproversCollection extends Helper {
		function __construct() {
			// Set table name
			$this->table = "tblemployees";
			// Set orderable column fields
			$this->column_order = array(
				'',
				'CAST(emp_number as INT)',
				'CAST(scanning_no as INT)',
				
				'last_name',
				"tblfieldpositions.name",
				"tblfielddepartments.department_name",
				"tblfieldsalarygrades.grade",
				"tblemployees.start_date",
				"tblemployees.employment_status",
				"tblemployees.gender",
				"tblemployees.date_created",
				"tblemployees.date_modified"
			);
			// Set searchable column fields
			$this->column_search =  array('employee_id_number','employee_number',"CONCAT(DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.extension,'sunev8clt1234567890',tblemployees.id),', ',DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.middle_name,'sunev8clt1234567890',tblemployees.id))",'grade','contact_number','school','employment_status');
			// Set default order
			$this->order = array('tblemployees.last_name' => 'asc');
		}
		
		 /*
     * Fetch members data from the database
     * @param $_POST filter data based on the posted parameters
     */
    public function getRows($postData){
        $this->_get_datatables_query($postData);
        if($postData['length'] != -1){
            $this->db->limit($postData['length'], $postData['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }
    
     /*
     * Count all records
     */
    public function countAll(){
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
    
     /*
     * Count records based on the filter params
     * @param $_POST filter data based on the posted parameters
     */
    public function countFiltered($postData){
        $this->_get_datatables_query($postData);
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    
      /*
     * Perform the SQL queries needed for an server-side processing requested
     * @param $_POST filter data based on the posted parameters
     */
    private function _get_datatables_query($postData){
		$this->db->distinct();
		$this->db->select(
			'DECRYPTER(tblemployees.first_name,"sunev8clt1234567890",tblemployees.id) as first_name,
            DECRYPTER(tblemployees.middle_name,"sunev8clt1234567890",tblemployees.id) as middle_name,
            DECRYPTER(tblemployees.last_name,"sunev8clt1234567890",tblemployees.id) as last_name,
            DECRYPTER(tblemployees.employee_id_number,"sunev8clt1234567890",tblemployees.id) as emp_number,
			DECRYPTER(tblemployees.employee_number,"sunev8clt1234567890",tblemployees.id) as scanning_no,
		    tblemployees.*,
			tblfieldpositions.name AS position_name,
			tblfielddepartments.department_name,
			tblfieldlocations.name AS location_name,
			tblfieldsalarygrades.grade AS grade,'
		);  
		$this->db->from($this->table); 
		$this->db->join("tblfieldpositions","tblemployees.position_id = tblfieldpositions.id","left");
		$this->db->join("tblfielddepartments","tblemployees.division_id = tblfielddepartments.id","left");
		$this->db->join("tblfieldlocations","tblemployees.location_id = tblfieldlocations.id","left");
		$this->db->join("tblemployeeseducbgcolleges","tblemployees.id = tblemployeeseducbgcolleges.employee_id","left");
		$this->db->join("tblfieldsalarygrades","tblemployees.salary_grade_id = tblfieldsalarygrades.id","left");
        $this->db->where('tblemployees.employment_status', 'Active');
        
		if(isset($_POST["search"]["value"])) {  
			$this->db->group_start();
			foreach ($this->column_search as $key => $value) {
				if($value == "tblemployees.first_name" || $value == "tblemployees.last_name" || $value == "tblemployees.middle_name" || $value == "tblemployees.employee_number" || $value == "tblemployees.employee_id_number")  {
					$this->db->or_like("DECRYPTER($value,'sunev8clt1234567890',tblemployees.id)", $_POST["search"]["value"]);  

				}
				else{
					$this->db->or_like($value, $_POST["search"]["value"]);  
				}
				
			}
			
			$this->db->or_like("CONCAT(DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.middle_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id))"
				,$_POST["search"]["value"]);
			$this->db->or_like("CONCAT(DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id))"
				,$_POST["search"]["value"]);
			$this->db->group_end(); 
		}

		if(isset($_GET['EmploymentStatus']) && $_GET['EmploymentStatus'] != null)
			$this->db->where($this->table.'.employment_status="'.$_GET['EmploymentStatus'].'"');
		if(isset($_GET['DivisionId']) && $_GET['DivisionId'] != null)
			$this->db->where($this->table.'.division_id="'.$_GET['DivisionId'].'"');
		if(isset($_GET['PayBasis']) && $_GET['PayBasis'] != null)
			$this->db->where($this->table.'.pay_basis="'.$_GET['PayBasis'].'"');
		if(isset($_GET['SalaryGrade']) && $_GET['SalaryGrade'] != null)
			$this->db->where('tblfieldpositions.salary_grade_id="'.$_GET['SalaryGrade'].'"');
		if(isset($_GET['PositionId']) && $_GET['PositionId'] != null)
			$this->db->where($this->table.'.position_id="'.$_GET['PositionId'].'"');
		if(isset($_POST["order"])) {  
			$this->db->order_by($this->order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
		}  
		else {  
			$this->db->order_by('id', 'DESC');  
		}  
    }

		//CTO
		public function getCTOApprovers($id){
	        $ret = array();
			$result = $this->db->select("b.id as emp_id, b.employee_number, b.employee_id_number, b.first_name, b.middle_name, b.last_name, a.*, a.is_active as approver_status, c.type as type")
			->from('tblemployeesctoapprovers a')
			->join("tblemployees b","b.id = a.approver","left")
			->join("tbloffsettingapprovetype c","c.id = a.approve_type","left")
			->where("b.employment_status","Active")
			->where("a.employee_id",$id)->get()->result_array();
			if(sizeof($result)>0){
				$this->ModelResponse("0", "Successfully fetched CTO approvers.", $result);	
				return true;
			}
			$this->ModelResponse("1", "No data available.");	
	        return false;
		}

		public function addCTOApprover($postdata){
			$helperDao = new HelperDao();
			$this->db->trans_begin();
			$data = array(
				"employee_id" => $postdata["id_cto"],
				"approve_type" => $postdata["approve_type_cto"],
				"approver" => $postdata["employee_id"],
				"is_active" => isset($postdata["isActive_cto"]) && $postdata["isActive_cto"] == "on" ? 1 : 0
			);
			$isHave = $this->db->select("*")->from("tblemployeesctoapprovers")->where("employee_id", $postdata["id_cto"])->where('approver',$postdata['employee_id'])->where('approve_type',$postdata['approve_type_cto'])->limit(1)->get()->row_array();
			if($isHave){
				$message = "Approver already exist with the same approve type.";
				$this->ModelResponse("1", $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
				return false;
			}
			$this->db->insert("tblemployeesctoapprovers", $data);
			if($this->db->trans_status() === TRUE){
				$message = "Successfully inserted CTO approver.";
				$this->db->trans_commit();
				$this->ModelResponse("0", $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
				return true;
			}else{
				$message = "Insert CTO approver failed.";
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse("1", $message);
			}
			return false;
		}

		public function updateCTOApprover($postdata){
			$helperDao = new HelperDao();
			$this->db->trans_begin();
			$data = array(
				"employee_id" => $postdata["id_cto"],
				"approve_type" => $postdata["approve_type_cto"],
				"approver" => $postdata["employee_id"],
				"is_active" => isset($postdata["isActive_cto"]) && $postdata["isActive_cto"] == "on" ? 1 : 0
			);
			$this->db->where("id",$postdata['approver_id_cto'])->update("tblemployeesctoapprovers", $data);
			if($this->db->trans_status() === TRUE){
				$message = "Successfully updated CTO approver.";
				$this->db->trans_commit();
				$this->ModelResponse("0", $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
				return true;
			}else{
				$message = "Update travel CTO failed.";
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse("1", $message);
			}
			return false;
		}

		public function activateCTOApprover($id){
			$helperDao = new HelperDao();
			$this->db->trans_begin();
			$data = array("is_active" => 1);
			$this->db->where("id",$id)->update("tblemployeesctoapprovers", $data);
			if($this->db->trans_status() === TRUE){
				$message = "CTO approver successfully activated.";
				$this->db->trans_commit();
				$this->ModelResponse("0", $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
				return true;
			}else{
				$message = "Activate CTO approver failed.";
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse("1", $message);
			}
			return false;
		}

		public function deactivateCTOApprover($id){
			$helperDao = new HelperDao();
			$this->db->trans_begin();
			$data = array("is_active" => 0);
			$this->db->where("id",$id)->update("tblemployeesctoapprovers", $data);
			if($this->db->trans_status() === TRUE){
				$message = "CTO approver successfully deactivated.";
				$this->db->trans_commit();
				$this->ModelResponse("0", $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
				return true;
			}else{
				$message = "CTO travel approver failed.";
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse("1", $message);
			}
			return false;
		}

		public function deleteCTOApprover($id){
			$helperDao = new HelperDao();
			$this->db->trans_begin();
			$this->db->where("id",$id)->delete("tblemployeesctoapprovers");
			if($this->db->trans_status() === TRUE){
				$message = "CTO approver successfully deleted.";
				$this->db->trans_commit();
				$this->ModelResponse("0", $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
				return true;
			}else{
				$message = "Delete CTO approver failed.";
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse("1", $message);
			}
			return false;
		}

		//LEAVE
	    public function getLeaveApprovers($id){
	        $ret = array();
			$result = $this->db->select("b.id as emp_id, b.employee_number, b.employee_id_number, b.first_name, b.middle_name, b.last_name, a.*, a.is_active as approver_status, c.type as type")
			->from('tblemployeesleaveapprovers a')
			->join("tblemployees b","b.id = a.approver","left")
			->join("tblemployeesapprovetype c","c.id = a.approve_type","left")
			->where("b.employment_status","Active")
			->where("a.employee_id",$id)->get()->result_array();
			if(sizeof($result)>0){
				$this->ModelResponse("0", "Successfully fetched leave approvers.", $result);	
				return true;
			}
			$this->ModelResponse("1", "No data available.");	
	        return false;
		}

		public function addLeaveApprover($postdata){
			$helperDao = new HelperDao();
			$this->db->trans_begin();
			$data = array(
				"employee_id" => $postdata["id"],
				"approve_type" => $postdata["approve_type"],
				"approver" => $postdata["employee_id"],
				"is_active" => isset($postdata["isActive"]) && $postdata["isActive"] == "on" ? 1 : 0
			);
			$isHave = $this->db->select("*")->from("tblemployeesleaveapprovers")->where("employee_id", $postdata["id"])->where('approver',$postdata['employee_id'])->where('approve_type',$postdata['approve_type'])->limit(1)->get()->row_array();
			if($isHave){
				$message = "Approver already exist with the same approve type.";
				$this->ModelResponse("1", $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
				return false;
			}
			$this->db->insert("tblemployeesleaveapprovers", $data);
			if($this->db->trans_status() === TRUE){
				$message = "Successfully inserted leave approver.";
				$this->db->trans_commit();
				$this->ModelResponse("0", $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
				return true;
			}else{
				$message = "Insert leave approver failed.";
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse("1", $message);
			}
			return false;
		}

		public function updateLeaveApprover($postdata){
			$helperDao = new HelperDao();
			$this->db->trans_begin();
			$data = array(
				"employee_id" => $postdata["id"],
				"approve_type" => $postdata["approve_type"],
				"approver" => $postdata["employee_id"],
				"is_active" => isset($postdata["isActive"]) && $postdata["isActive"] == "on" ? 1 : 0
			);
			$this->db->where("id",$postdata['approver_id'])->update("tblemployeesleaveapprovers", $data);
			if($this->db->trans_status() === TRUE){
				$message = "Successfully updated leave approver.";
				$this->db->trans_commit();
				$this->ModelResponse("0", $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
				return true;
			}else{
				$message = "Update leave approver failed.";
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse("1", $message);
			}
			return false;
		}

		public function activateLeaveApprover($id){
			$helperDao = new HelperDao();
			$this->db->trans_begin();
			$data = array("is_active" => 1);
			$this->db->where("id",$id)->update("tblemployeesleaveapprovers", $data);
			if($this->db->trans_status() === TRUE){
				$message = "Leave approver successfully activated.";
				$this->db->trans_commit();
				$this->ModelResponse("0", $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
				return true;
			}else{
				$message = "Activate leave approver failed.";
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse("1", $message);
			}
			return false;
		}


		public function deactivateLeaveApprover($id){
			$helperDao = new HelperDao();
			$this->db->trans_begin();
			$data = array("is_active" => 0);
			$this->db->where("id",$id)->update("tblemployeesleaveapprovers", $data);
			if($this->db->trans_status() === TRUE){
				$message = "Leave approver successfully deactivated.";
				$this->db->trans_commit();
				$this->ModelResponse("0", $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
				return true;
			}else{
				$message = "Deactivate leave approver failed.";
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse("1", $message);
			}
			return false;
		}

		public function deleteLeaveApprover($id){
			$helperDao = new HelperDao();
			$this->db->trans_begin();
			$this->db->where("id",$id)->delete("tblemployeesleaveapprovers");
			if($this->db->trans_status() === TRUE){
				$message = "Leave approver successfully deleted.";
				$this->db->trans_commit();
				$this->ModelResponse("0", $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
				return true;
			}else{
				$message = "Delete leave approver failed.";
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse("1", $message);
			}
			return false;
		}

		// OB
	    public function getOBApprovers($id){
	        $result = $this->db->select("b.id as emp_id, b.employee_number, b.employee_id_number, b.first_name, b.middle_name, b.last_name, a.*, a.is_active as approver_status, c.type as type")
			->from('tblemployeesobapprovers a')
			->join("tblemployees b","b.id = a.approver","left")
			->join("tblemployeesapprovetype c","c.id = a.approve_type","left")
			->where("b.employment_status","Active")
			->where("a.employee_id",$id)->get()->result_array();
			if(sizeof($result)>0){
				$this->ModelResponse("0", "Successfully fetched ob approvers.", $result);	
				return true;
			}
			$this->ModelResponse("1", "No data available.");	
	        return false;
		}

		public function addOBApprover($postdata){
			$helperDao = new HelperDao();
			$this->db->trans_begin();
			$data = array(
				"employee_id" => $postdata["id_OB"],
				"approve_type" => $postdata["approve_type_OB"],
				"approver" => $postdata["employee_id"],
				"is_active" => isset($postdata["isActive_OB"]) && $postdata["isActive_OB"] == "on" ? 1 : 0
			);
			$isHave = $this->db->select("*")->from("tblemployeesobapprovers")->where("employee_id", $postdata["id_OB"])->where('approver',$postdata['employee_id'])->where('approve_type',$postdata['approve_type_OB'])->limit(1)->get()->row_array();
			if($isHave){
				$message = "Approver already exist with the same approve type.";
				$this->ModelResponse("1", $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
				return false;
			}
			$this->db->insert("tblemployeesobapprovers", $data);
			if($this->db->trans_status() === TRUE){
				$message = "Successfully inserted OB approver.";
				$this->db->trans_commit();
				$this->ModelResponse("0", $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
				return true;
			}else{
				$message = "Insert OB approver failed.";
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse("1", $message);
			}
			return false;
		}

		public function updateOBApprover($postdata){
			$helperDao = new HelperDao();
			$this->db->trans_begin();
			$data = array(
				"employee_id" => $postdata["id_OB"],
				"approve_type" => $postdata["approve_type_OB"],
				"approver" => $postdata["employee_id"],
				"is_active" => isset($postdata["isActive_OB"]) && $postdata["isActive_OB"] == "on" ? 1 : 0
			);
			$this->db->where("id",$postdata['approver_id_OB'])->update("tblemployeesobapprovers", $data);
			if($this->db->trans_status() === TRUE){
				$message = "Successfully updated OB approver.";
				$this->db->trans_commit();
				$this->ModelResponse("0", $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
				return true;
			}else{
				$message = "Update OB approver failed.";
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse("1", $message);
			}
			return false;
		}

		public function activateOBApprover($id){
			$helperDao = new HelperDao();
			$this->db->trans_begin();
			$data = array("is_active" => 1);
			$this->db->where("id",$id)->update("tblemployeesobapprovers", $data);
			if($this->db->trans_status() === TRUE){
				$message = "OB approver successfully activated.";
				$this->db->trans_commit();
				$this->ModelResponse("0", $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
				return true;
			}else{
				$message = "Activate OB approver failed.";
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse("1", $message);
			}
			return false;
		}

		public function deactivateOBApprover($id){
			$helperDao = new HelperDao();
			$this->db->trans_begin();
			$data = array("is_active" => 0);
			$this->db->where("id",$id)->update("tblemployeesobapprovers", $data);
			if($this->db->trans_status() === TRUE){
				$message = "OB approver successfully deactivated.";
				$this->db->trans_commit();
				$this->ModelResponse("0", $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
				return true;
			}else{
				$message = "Deactivate OB approver failed.";
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse("1", $message);
			}
			return false;
		}

		public function deleteOBApprover($id){
			$helperDao = new HelperDao();
			$this->db->trans_begin();
			$this->db->where("id",$id)->delete("tblemployeesobapprovers");
			if($this->db->trans_status() === TRUE){
				$message = "OB approver successfully deleted.";
				$this->db->trans_commit();
				$this->ModelResponse("0", $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
				return true;
			}else{
				$message = "Delete ob approver failed.";
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse("1", $message);
			}
			return false;
		}

		//TO
		public function getTravelApprovers($id){
	        $ret = array();
			$result = $this->db->select("b.id as emp_id, b.employee_number, b.employee_id_number, b.first_name, b.middle_name, b.last_name, a.*, a.is_active as approver_status, c.type as type")
			->from('tbltravelorderapprover a')
			->join("tblemployees b","b.id = a.approver","left")
			->join("tbltravelapprovetype c","c.id = a.approve_type","left")
			->where("b.employment_status","Active")
			->where("a.employee_id",$id)->get()->result_array();
			if(sizeof($result)>0){
				$this->ModelResponse("0", "Successfully fetched travel approvers.", $result);	
				return true;
			}
			$this->ModelResponse("1", "No data available.");	
	        return false;
		}

		public function addTravelApprover($postdata){
			$helperDao = new HelperDao();
			$this->db->trans_begin();
			$data = array(
				"employee_id" => $postdata["id_TO"],
				"approve_type" => $postdata["approve_type_TO"],
				"approver" => $postdata["employee_id"],
				"is_active" => isset($postdata["isActive_TO"]) && $postdata["isActive_TO"] == "on" ? 1 : 0
			);
			$isHave = $this->db->select("*")->from("tbltravelorderapprover")->where("employee_id", $postdata["id_TO"])->where('approver',$postdata['employee_id'])->where('approve_type',$postdata['approve_type_TO'])->limit(1)->get()->row_array();
			if($isHave){
				$message = "Approver already exist with the same approve type.";
				$this->ModelResponse("1", $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
				return false;
			}
			$this->db->insert("tbltravelorderapprover", $data);
			if($this->db->trans_status() === TRUE){
				$message = "Successfully inserted travel approver.";
				$this->db->trans_commit();
				$this->ModelResponse("0", $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
				return true;
			}else{
				$message = "Insert travel approver failed.";
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse("1", $message);
			}
			return false;
		}

		public function updateTravelApprover($postdata){
			// print_r($postdata); die();
			$helperDao = new HelperDao();
			$this->db->trans_begin();
			$data = array(
				"employee_id" => $postdata["id_TO"],
				"approve_type" => $postdata["approve_type_TO"],
				"approver" => $postdata["employee_id"],
				"is_active" => isset($postdata["isActive_TO"]) && $postdata["isActive_TO"] == "on" ? 1 : 0
			);
			
			$this->db->where("id",$postdata['approver_id_TO'])->update("tbltravelorderapprover", $data);
			if($this->db->trans_status() === TRUE){
				$message = "Successfully updated travel approver.";
				$this->db->trans_commit();
				$this->ModelResponse("0", $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
				return true;
			}else{
				$message = "Update travel approver failed.";
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse("1", $message);
			}
			return false;
		}

		public function activateTravelApprover($id){
			$helperDao = new HelperDao();
			$this->db->trans_begin();
			$data = array("is_active" => 1);
			$this->db->where("id",$id)->update("tbltravelorderapprover", $data);
			if($this->db->trans_status() === TRUE){
				$message = "Travel approver successfully activated.";
				$this->db->trans_commit();
				$this->ModelResponse("0", $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
				return true;
			}else{
				$message = "Activate Travel approver failed.";
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse("1", $message);
			}
			return false;
		}

		public function deactivateTravelApprover($id){
			$helperDao = new HelperDao();
			$this->db->trans_begin();
			$data = array("is_active" => 0);
			$this->db->where("id",$id)->update("tbltravelorderapprover", $data);
			if($this->db->trans_status() === TRUE){
				$message = "Travel approver successfully deactivated.";
				$this->db->trans_commit();
				$this->ModelResponse("0", $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
				return true;
			}else{
				$message = "Deactivate travel approver failed.";
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse("1", $message);
			}
			return false;
		}

		public function deleteTravelApprover($id){
			$helperDao = new HelperDao();
			$this->db->trans_begin();
			$this->db->where("id",$id)->delete("tbltravelorderapprover");
			if($this->db->trans_status() === TRUE){
				$message = "Travel approver successfully deleted.";
				$this->db->trans_commit();
				$this->ModelResponse("0", $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
				return true;
			}else{
				$message = "Delete travel approver failed.";
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse("1", $message);
			}
			return false;
		}

		// OT Request
	    public function getOvertimeApprovers($id){
	        $result = $this->db->select("b.id as emp_id, b.employee_number, b.employee_id_number, b.first_name, b.middle_name, b.last_name, a.*, a.is_active as approver_status, c.type as type")
			->from('tblemployeesovertimeapprovers a')
			->join("tblemployees b","b.id = a.approver","left")
			->join("tblemployeesapprovetype c","c.id = a.approve_type","left")
			->where("b.employment_status","Active")
			->where("a.employee_id",$id)->get()->result_array();
			if(sizeof($result)>0){
				$this->ModelResponse("0", "Successfully fetched overtime approvers.", $result);	
				return true;
			}
			$this->ModelResponse("1", "No data available.");	
	        return false;
		}

		public function addOvertimeApprover($postdata){
			$helperDao = new HelperDao();
			$this->db->trans_begin();
			$data = array(
				"employee_id" => $postdata["id_OT"],
				"approve_type" => $postdata["approve_type_OT"],
				"approver" => $postdata["employee_id"],
				"is_active" => isset($postdata["isActive_Overtime"]) && $postdata["isActive_Overtime"] == "on" ? 1 : 0
			);
			$isHave = $this->db->select("*")->from("tblemployeesovertimeapprovers")->where("employee_id", $postdata["id_OT"])->where('approver',$postdata['employee_id'])->where('approve_type',$postdata['approve_type_OT'])->limit(1)->get()->row_array();
			if($isHave){
				$message = "Approver already exist with the same approve type.";
				$this->ModelResponse("1", $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
				return false;
			}
			$this->db->insert("tblemployeesovertimeapprovers", $data);
			if($this->db->trans_status() === TRUE){
				$message = "Successfully inserted Overtime approver.";
				$this->db->trans_commit();
				$this->ModelResponse("0", $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
				return true;
			}else{
				$message = "Insert Overtime approver failed.";
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse("1", $message);
			}
			return false;
		}

		public function deleteOvertimeApprover($id){
			$helperDao = new HelperDao();
			$this->db->trans_begin();
			$this->db->where("id",$id)->delete("tblemployeesovertimeapprovers");
			if($this->db->trans_status() === TRUE){
				$message = "Overtime approver successfully deleted.";
				$this->db->trans_commit();
				$this->ModelResponse("0", $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
				return true;
			}else{
				$message = "Delete Overtime approver failed.";
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse("1", $message);
			}
			return false;
		}

		public function deactivateOvertimeApprover($id){
			$helperDao = new HelperDao();
			$this->db->trans_begin();
			$data = array("is_active" => 0);
			$this->db->where("id",$id)->update("tblemployeesovertimeapprovers", $data);
			if($this->db->trans_status() === TRUE){
				$message = "Overtime approver successfully deactivated.";
				$this->db->trans_commit();
				$this->ModelResponse("0", $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
				return true;
			}else{
				$message = "Deactivate Overtime approver failed.";
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse("1", $message);
			}
			return false;
		}

		public function activateOvertimeApprover($id){
			$helperDao = new HelperDao();
			$this->db->trans_begin();
			$data = array("is_active" => 1);
			$this->db->where("id",$id)->update("tblemployeesovertimeapprovers", $data);
			if($this->db->trans_status() === TRUE){
				$message = "Overtime approver successfully activated.";
				$this->db->trans_commit();
				$this->ModelResponse("0", $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
				return true;
			}else{
				$message = "Activate Overtime approver failed.";
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse("1", $message);
			}
			return false;
		}

		public function updateOvertimeApprover($postdata){
			$helperDao = new HelperDao();
			$this->db->trans_begin();
			$data = array(
				"employee_id" => $postdata["id_OT"],
				"approve_type" => $postdata["approve_type_OT"],
				"approver" => $postdata["employee_id"],
				"is_active" => isset($postdata["isActive_Overtime"]) && $postdata["isActive_Overtime"] == "on" ? 1 : 0
			);
			$this->db->where("id",$postdata['approver_id_OT'])->update("tblemployeesovertimeapprovers", $data);
			if($this->db->trans_status() === TRUE){
				$message = "Successfully updated Overtime approver.";
				$this->db->trans_commit();
				$this->ModelResponse("0", $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
				return true;
			}else{
				$message = "Update Overtime approver failed.";
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse("1", $message);
			}
			return false;
		}

	}
?>