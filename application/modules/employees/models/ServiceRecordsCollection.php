<?php
	class ServiceRecordsCollection extends Helper {
		var $select_column = null;  
		//Fetch
		var $table = "tbltransactionsprocesspayroll";  
		var $exp =  "tblemployeesservicerecord";
		// Set searchable column fields
		var $column_search =  array('employee_id_number','employee_number',"CONCAT(DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.extension,'sunev8clt1234567890',tblemployees.id),', ',DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.middle_name,'sunev8clt1234567890',tblemployees.id))",'employment_status');
		// Set default order
		var $order_column = array('',"CAST(emp_number as INT)",'first_name','tblfieldpositions.name','tblfielddepartments.department_name','tblemployees.salary','tblemployees.employment_status');
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
			$columns = $this->getColumns();
			foreach ($columns as $key => $value) {
				$this->select_column[] = $this->table.'.'.$value['COLUMN_NAME'];
			}
			//var_dump($this->select_column);die();

		}
		  
      	public function getColumns(){
      		$sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$this->table."' AND TABLE_SCHEMA='".$this->db->database."' ";
			$query = $this->db->query($sql);
			$rows = $query->result_array();
			return $rows;
		} 
		  
      	public function getEmployeeColumns(){
      		$sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='tblemployees' AND TABLE_SCHEMA='".$this->db->database."' ";
			$query = $this->db->query($sql);
			$rows = $query->result_array();
			return $rows;  
		  }

		  function make_query($division_id, $employment_status) {
			$this->select_column[] = "DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id)";
			$this->select_column[] = "DECRYPTER(tblemployees.employee_number,'sunev8clt1234567890',tblemployees.id) as emp_number";
			$this->select_column[] = 'tblfieldpositions.name';
			$this->select_column[] = 'tblfieldagencies.agency_name';
			$this->select_column[] = 'tblfieldoffices.name';  // Include the necessary column
			$this->select_column[] = 'tblfielddepartments.department_name';
			$this->select_column[] = 'tblfieldlocations.name';
			$this->select_column[] = 'tblfieldfundsources.fund_source';
			$this->select_column[] = 'tblfieldloans.description';
			$this->select_column[] = 'tblfieldbudgetclassifications.budget_classification_name';
		
			$this->db->select(
				'
				tblemployees.id as salt,
				DECRYPTER(tblemployees.employee_number,"sunev8clt1234567890",tblemployees.id) as emp_number,
				DECRYPTER(tblemployees.first_name,"sunev8clt1234567890",tblemployees.id) as first_name,
				tblemployees.*,
				tbltransactionsprocesspayroll.*,
				tblfieldpositions.name AS position_name,
				tblfieldoffices.name AS office_name,
				tblfielddepartments.department_name,
				tblfieldlocations.name AS location_name,
				tblfieldfundsources.fund_source,
				tblfieldloans.description AS loan_name,
				tblfieldbudgetclassifications.budget_classification_name'
			);
		
			
			$this->db->from("tblemployees");
			$this->db->join("tblemployeesfamilybackground", "tblemployees.id = tblemployeesfamilybackground.employee_id", "left");
			$this->db->join("tbltransactionsprocesspayroll", "tblemployees.id = tbltransactionsprocesspayroll.employee_id", "left");
			$this->db->join("tblfieldpositions", "tblemployees.position_id = tblfieldpositions.id", "left");
			$this->db->join("tblfieldagencies", "tblemployees.agency_id = tblfieldagencies.id", "left");
			$this->db->join("tblfieldoffices", "tblemployees.office_id = tblfieldoffices.id", "left");  // Adjust the join condition
			$this->db->join("tblfielddepartments", "tblemployees.division_id = tblfielddepartments.id", "left");
			$this->db->join("tblfieldlocations", "tblemployees.location_id = tblfieldlocations.id", "left");
			$this->db->join("tblfieldfundsources", "tblemployees.fund_source_id = tblfieldfundsources.id", "left");
			$this->db->join("tblfieldloans", "tblemployees.loans_id = tblfieldloans.id", "left");
			$this->db->join("tblfieldbudgetclassifications", "tblemployees.budget_classification_id = tblfieldbudgetclassifications.id", "left");
		
			
			if (isset($division_id) && $division_id != NULL) {
			if($division_id != "ALL"){
				if (isset($employment_status) && $employment_status != NULL) {
					if($employment_status == "ALL"){
						if (isset($division_id) && $division_id != NULL) {
							//$this->db->where('tblemployees.employment_status', $employment_status);
							$this->db->where('tblemployees.division_id', $division_id);
						} 
					}elseif($employment_status == "Inactive"){
						if (isset($division_id) && $division_id != NULL) {
							$this->db->where('tblemployees.employment_status !=', 'Active');
							$this->db->where('tblemployees.division_id', $division_id);
						} 
					}else{
						if (isset($division_id) && $division_id != NULL) {
							$this->db->where('tblemployees.employment_status', $employment_status);
							$this->db->where('tblemployees.division_id', $division_id);
						} 
					}
				}else {
					$this->db->where('1=0');
				}
			}
		}
				
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

			if (isset($_POST["order"])) {
				$this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
			} else {
				$this->db->order_by("tblfielddepartments.department_name, tblemployees.first_name ASC");
			}
		
			$this->db->group_by('tblemployees.id');
		}
		
	
		public function make_datatables($division_id, $employment_status) {
			$this->make_query($division_id, $employment_status);
		
			// Check if 'length' is set before using it
			if (isset($_POST["length"]) && $_POST["length"] != -1) {
				$this->db->limit($_POST['length'], $_POST['start']);
			}
		
			$query = $this->db->get();
			// Uncomment the line below for debugging
			// echo $this->db->last_query();
			return $query->result();
		}
		
	
		public function get_filtered_data($division_id, $employment_status) {
			$this->make_query($division_id, $employment_status);
			$query = $this->db->get();
			return $query->num_rows();
		}

		function get_all_data() {  
		    $this->db->select($this->table."*");  
		    $this->db->from($this->table);
		    $this->db->join("tblemployees",$this->table.".employee_id = tblemployees.id","left");
		    $this->db->join("tblfieldpositions","tblemployees.position_id = tblfieldpositions.id","left");
		    $this->db->join("tblfieldagencies","tblemployees.agency_id = tblfieldagencies.id","left");
		    $this->db->join("tblfielddepartments","tblemployees.division_id = tblfielddepartments.id","left");
		    $this->db->join("tblfieldlocations","tblemployees.location_id = tblfieldlocations.id","left");
		    $this->db->join("tblfieldfundsources","tblemployees.fund_source_id = tblfieldfundsources.id","left");
		    $this->db->join("tblfieldloans","tblemployees.loans_id = tblfieldloans.id","left");
		    $this->db->join("tblfieldbudgetclassifications","tblemployees.budget_classification_id = tblfieldbudgetclassifications.id","left");
		   
		    return $this->db->count_all_results();   
		}

		function get_service_record($id){
			$emp = $this->db->select("*")->from("tblemployees")->join("tblemployeesfamilybackground", "tblemployees.id = tblemployeesfamilybackground.employee_id", "left")->where("id",$id)->get()->row_array();
			$empexp = $this->db->select("*")->from("tblemployeesservicerecord")->where("employee_id",$id)->order_by("str_to_date(work_from,'%m-%d-%Y'), 'ASC'")->get()->result_array();
				
			return array("employee"=>$emp, "experience"=> $empexp);
		}
 
		// get_edit_service_record
		function get_edit_service_record($id){
			// get
			$emp = $this->db->select("*")->from("tblemployees")->join("tblemployeesfamilybackground", "tblemployees.id = tblemployeesfamilybackground.employee_id", "left")->where("id",$id)->get()->row_array();
			$empexp = $this->db->select("*")->from("tblemployeesservicerecord")->where("employee_id",$id)->order_by("str_to_date(work_from,'%m-%d-%Y'), 'ASC'")->get()->result_array();
			return array("employee"=>$emp, "experience"=> $empexp);
		}
//->order_by("str_to_date(work_from,'%m-%d-%Y'), 'ASC'")
		public function updateServiceRecord($id, $work_from, $work_to, $position, $status_appointment, $monthly_salary,$company, $branch, $lv_abs_wo_pay, $seperation_date, $seperation_cause ){
			// print_r($params); die();
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Failed to update service record.";
			
			for($i = 0; $i < count($id); $i++ ){
				//var_dump().die();
				 $this->db->where(['id'=>$id[$i]])->update($this->exp, [
					'work_from' 			=> $work_from[$i], 
					'work_to' 				=> $work_to[$i],
					'position' 				=> $position[$i],
					'status_appointment'	=> $status_appointment[$i],
					'monthly_salary' 		=> $monthly_salary[$i],
					"company" 				=> $company[$i],
					'branch' 				=> $branch[$i],
					'lv_abs_wo_pay' 		=> $lv_abs_wo_pay[$i], 
					'seperation_date'		=> $seperation_date[$i], 
					'seperation_cause'		=> $seperation_cause[$i] 
				]);


			}
			
				if($this->db->trans_status() === TRUE){
					$code = "0";
					$message = "Successfully updated service record.";
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
		public function addServiceRecord($params){
			$helperDao = new HelperDao();
			$code = "1"; 
			$message = "Failed to add service record.";
			$data = array();
			$this->db->where('employee_id', @$params["employee_id"][0])->delete($this->exp);

			foreach ($params["position"] as $key => $value) {
				($params['work_from'][$key] == "")? $work_from = "N/A" : $work_from = @$params["work_from"][$key];
				($params['work_to'][$key] == "")? $work_to = "N/A" : $work_to = @$params["work_to"][$key];
				($params['position'][$key] == "")? $position = "N/A" : $position = @$params["position"][$key];
				($params['status_appointment'][$key] == "")? $status_appointment = "N/A" : $status_appointment = @$params["status_appointment"][$key];
				
				($params['monthly_salary'][$key] == "")? $monthly_salary = "N/A" : $monthly_salary = @$params["monthly_salary"][$key];
				($params['company'][$key] == "")? $company = "N/A" : $company = @$params["company"][$key];
				($params['branch'][$key] == "")? $branch = "N/A" : $branch = @$params["branch"][$key];
				($params['lv_abs_wo_pay'][$key] == "")? $lv_abs_wo_pay = "N/A" : $lv_abs_wo_pay = @$params["lv_abs_wo_pay"][$key];
				($params['seperation_date'][$key] == "")? $seperation_date = "N/A" : $seperation_date = @$params["seperation_date"][$key];
				($params['seperation_cause'][$key] == "")? $seperation_cause = "N/A" : $seperation_cause = @$params["seperation_cause"][$key];

				$row[] = array("employee_id"=>@$params["employee_id"][0], 
				"work_from" => $work_from, 
				"work_to" => $work_to, 
				"position" => $position, 
				"status_appointment" => $status_appointment, 
				"monthly_salary" =>  $monthly_salary, 
				"company" => $company, 
				"branch" => $branch, 
				"lv_abs_wo_pay" => $lv_abs_wo_pay,
				"seperation_date" => $seperation_date,
				"seperation_cause" =>$seperation_cause);
			}
			//var_dump(@$params["employee_id"][$key]).die();
			
			
			$this->db->insert_batch($this->exp, $row);

			if($this->db->trans_status() === TRUE){
				$code = "0";
				$message = "Successfully added service record.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				$this->db->trans_commit();
				return true;		
			} else {
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
		}

		public function deleteServiceRecord($id){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Failed to delete service record.";

			$this->db->where('id', $id)->delete($this->exp);

			if($this->db->trans_status() === TRUE){
				$code = "0";
				$message = "Successfully deleted service record.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				$this->db->trans_commit();
				return true;		
			} else {
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
		}
		
		function get_signatories(){
			$sign = $this->db->query('SELECT
			a.*,
			CONCAT(
				DECRYPTER(b.first_name,"sunev8clt1234567890",b.id)," ",
				LEFT(DECRYPTER(b.middle_name,"sunev8clt1234567890",b.id),1),". ",
				DECRYPTER(b.last_name,"sunev8clt1234567890",b.id)) as employee_name, b.position_designation, b.division_designation, c.name as position_title, d.department_name as department
			FROM tblfieldsignatories a LEFT JOIN tblemployees b ON a.employee_id = b.id
			LEFT JOIN tblfieldpositions c ON b.position_id = c.id  LEFT JOIN tblfielddepartments d ON b.division_id = d.id WHERE a.id = 10')->result_array();
			return $sign;
		}

		
		
	}
?>
