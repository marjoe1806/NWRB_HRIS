<?php
	class MobileUserConfigCollection extends Helper {
      	var $select_column = null; 
		public function __construct() {
			$this->load->model('HelperDao');
			$this->load->model('MessageRels');
			$this->load->model('SystemMessages');
			ModelResponse::busy();
			$columns = $this->getColumns();
			foreach ($columns as $key => $value) {
				$this->select_column[] = $this->table.'.'.$value;
			}
		}
		
		//set orderable columns in mobile user list
		var $table = "tblmobileusers";   
      	var $order_column = array('',
      				"CAST(emp_number as INT)",
      				"DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id)",
      				"tblfieldlocations.name",
      				"isfirstlogin",
      				"status",
      				
      	);

      	//set searchable parameters in mobile users table
      	public function getColumns(){
			$rows = array(
				'status',
				'isfirstlogin'
			);
			return $rows; 
      	}

		//set limit in datatable
		function make_datatables() {  
		    $this->make_query();  
		    if($_POST["length"] != -1) {  
		        $this->db->limit($_POST['length'], $_POST['start']);  
		    }  

		    $query = $this->db->get();  
		    return $query->result();  

		}  

		//fetch list of mobile users
		function make_query() {  
			$this->select_column[] = 'tblemployees.employee_id_number';
			$this->select_column[] = 'tblemployees.last_name';
			$this->select_column[] = 'tblemployees.first_name';
			$this->select_column[] = 'tblemployees.middle_name';
			$this->select_column[] = 'tblfieldlocations.name';
		    $this->db->select(
		    	$this->table.'.*,
		    	tblemployees.employee_number,
		    	DECRYPTER(tblemployees.employee_id_number,"sunev8clt1234567890",tblemployees.id) as emp_number,
		    	tblemployees.last_name,
		    	tblemployees.first_name,
		    	tblemployees.middle_name,
		    	tblemployees.extension,
		    	tblemployees.location_id,
		    	tblfieldlocations.name AS location_name'
		    );  
		    $this->db->from($this->table);  
		    $this->db->join("tblemployees",$this->table.".employee_id = tblemployees.id","left");
		    $this->db->join("tblfieldlocations","tblemployees.location_id = tblfieldlocations.id","left");
		   
		    if(isset($_POST["search"]["value"]))  
		    {  
		    	$this->db->group_start();
		     	foreach ($this->select_column as $key => $value) {
					if($value == "tblemployees.first_name" || $value == "tblemployees.last_name" || $value == "tblemployees.middle_name" || $value == "tblemployees.employee_number" || $value == "tblemployees.employee_id_number")
					{
		     			$this->db->or_like("DECRYPTER($value,'sunev8clt1234567890',tblemployees.id)", $_POST["search"]["value"]);  

		     		} else if($value == "tblmobileusers.isfirstlogin") {
		     			$search_val = $_POST["search"]["value"];

		     			if (stripos("DEFAULT", $_POST["search"]["value"]) !== FALSE) {
						   $search_val = "1";
						}

						if (stripos("UPDATED", $_POST["search"]["value"]) !== FALSE) {
						   $search_val = "0";
						}

		     			$this->db->or_like($value, $search_val);  
		     		} else {
		     			$this->db->or_like($value, $_POST["search"]["value"]);  
		     		}
		     		
		     	}
		        $this->db->group_end(); 
		    }
		    $this->db->where('tblemployees.employment_status="Active"');
		    if(isset($_GET['LocationId']) && $_GET['LocationId'] != null)
		    	$this->db->where('tblemployees.location_id = "'.$_GET['LocationId'].'"');
		    if(isset($_GET['DepartmentId']) && $_GET['DepartmentId'] != null)
		    	$this->db->where('tblemployees.division_id = "'.$_GET['DepartmentId'].'"');
		    if(isset($_GET['Status']) && $_GET['Status'] != null)
		    	$this->db->where($this->table.'.status = "'.$_GET['Status'].'"');
		    if(isset($_GET['Id']) && $_GET['Id'] != null)
		    	$this->db->where($this->table.'.id="'.$_GET['Id'].'"');
		    if(isset($_POST["order"])) {  	
		    	$this->db->order_by($this->order_column[$_POST['order']['0']['column']]." ". $_POST['order']['0']['dir']);
		    } else {  
		    	$this->db->order_by("CAST(DECRYPTER(tblemployees.employee_number,'sunev8clt1234567890',tblemployees.id) AS SIGNED) ASC");  
		    }  
		}

		//get count of all mobile users
		function get_all_data() {  
		    $this->db->select($this->table."*");  
		    $this->db->from($this->table);
		    if(isset($_GET['EmploymentStatus']) && $_GET['EmploymentStatus'] != null){
		    	$this->db->where($this->table.'.employment_status',$_GET['EmploymentStatus']);
		    }
		    return $this->db->count_all_results();  
		}  

		//get count of filtered mobile users
		function get_filtered_data(){  
		     $this->make_query(); 
		     $query = $this->db->get();  
		     return $query->num_rows();  
		}  

		//add mobile users
		public function addMobileUserConfig($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Failed to create mobile user.";
			$id = uniqid();
			$sysmessages = new SystemMessages();

			if(!$this->isEmployeeIdExist($params['employee_id'])){
				$employee_number = $this->getEmployeeNumber($params['employee_id']);
				$employee_email = $this->getEmployeeEmail($params['employee_id']);
				$params['userid'] = $id;
				$params['username'] = $employee_email[0]['email'];
				$params['username'] = strtoupper($params['username']);
				$params['password'] = Helper::encrypt($employee_number[0]['employee_number'],$params['username']);
				$tmp_password = $employee_number[0]['employee_number'];
				$params['isfirstlogin'] = 1;
				$params['status'] = "ACTIVE";
				$params['created_by'] = Helper::get('userid');
				$params['modified_by'] = Helper::get('userid');

				$this->db->insert($this->table, $params);
				if($this->db->affected_rows() > 0) {
					if(trim($tmp_password) != ""){
						$sysmessages->init(MessageRels::EMAIL_USER_MOBILE_REGISTER);
						$message = $sysmessages->getMessage();
						$message = str_replace("<fname>","Sir/Ma'am",$message);
						$message = str_replace("<system>","NWRB-CHRIS",$message);
						$message = str_replace("<username>",$params['username'],$message);
						$message = str_replace("<tempassw>",$tmp_password,$message); //input password
						$helperDao->sendMail(
							$sysmessages->getSubject(),
							$params['username'],
							$message
						);
					}

					$code = "0";
					$message = "Successfully created mobile user.";
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
					return true;		
				} else {
					$helperDao->auditTrails(Helper::get('userid'),$message);
				}
			} else {
				$message = "Account with this employee already exists.";
			}
			$this->ModelResponse($code, $message);
			return false;
		}

		//get all active satellite locations
		public function getActiveLocations(){
			$this->db->select('*');
			$this->db->from('tblfieldlocations');
			$this->db->where('is_active', 1);
			$this->db->order_by("name", "asc");
			return $this->db->get()->result_array();
		}

		//get all active departments
		public function getActiveDepartments(){
			$this->db->select('*');
			$this->db->from('tblfielddepartments');
			$this->db->where('is_active', 1);
			$this->db->order_by("department_name", "asc");
			return $this->db->get()->result_array();
		}

		//check if employee id already exists
		public function isEmployeeIdExist($num){
			$this->db->select("*");
			$this->db->from($this->table);
			$this->db->where("employee_id = '".$num."'");
			if($this->db->get()->num_rows() > 0){
				return true;
			}
			return false;
		}

		//get employee number
		public function getEmployeeNumber($num){
			$this->db->select("DECRYPTER(employee_number,'sunev8clt1234567890',id) AS employee_number");
			$this->db->from("tblemployees");
			$this->db->where("id = '".$num."'");
			return $this->db->get()->result_array();
		}
		//get employee number
		public function getEmployeeEmail($id){
			$this->db->select("email");
			$this->db->from("tblemployees");
			$this->db->where("id = '".$id."'");
			return $this->db->get()->result_array();
		}

		//activate mobile user
		public function activeRows($params){
			$helperDao = new HelperDao();
			$sysmessages = new SystemMessages();

			$code = "1";
			$message = "Failed to activate mobile user.";
			$data['Id'] = isset($params['id'])?$params['id']:'';
			$employee_email = $this->getEmployeeEmail($params['employee_id']);
			$email = $employee_email[0]['email'];
			$data['Status'] = "ACTIVE";

			$row_sql = "UPDATE tblmobileusers SET status = ? WHERE userid = ? ";
			$row_params = array($data['Status'],$data['Id']);
			$this->db->query($row_sql,$row_params);
			if($this->db->affected_rows() > 0){
				$sysmessages->init(MessageRels::EMAIL_USER_ACTIVATED);
				$message = $sysmessages->getMessage();
				$message = str_replace("<fname>","Sir/Ma'am",$message);
				$message = str_replace("<system>","NWRB-CHRIS",$message);
				$helperDao->sendMail(
					$sysmessages->getSubject(),
					$email,
					$message
				);

				$code = "0";
				$message = "Successfully activated mobile user.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;		
			} else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		//deactivate mobile user
		public function inactiveRows($params){
			$helperDao = new HelperDao();
			$sysmessages = new SystemMessages();

			$code = "1";
			$message = "Failed to deactivate mobile user.";
			$data['Id'] = isset($params['id'])?$params['id']:'';
			$employee_email = $this->getEmployeeEmail($params['employee_id']);
			$email = $employee_email[0]['email'];
			$data['Status'] = "INACTIVE";

			$row_sql = "UPDATE tblmobileusers SET status = ? WHERE userid = ? ";
			$row_params = array($data['Status'],$data['Id']);
			$this->db->query($row_sql,$row_params);
			if($this->db->affected_rows() > 0){
				$sysmessages->init(MessageRels::EMAIL_USER_DEACTIVATED);
				$message = $sysmessages->getMessage();
				$message = str_replace("<fname>","Sir/Ma'am",$message);
				$message = str_replace("<system>","NWRB-CHRIS",$message);
				$helperDao->sendMail(
					$sysmessages->getSubject(),
					$email,
					$message
				);

				$code = "0";
				$message = "Successfully deactivated mobile user.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;		
			} else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		//reset mobile user password
		public function resetPassword($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Failed to reset password.";
			$sysmessages = new SystemMessages();

			$data['Id'] = isset($params['id'])?$params['id']:'';
			$data['isfirstlogin'] = "1";
			$employee_number = $this->getEmployeeNumber($params['employee_id']);
			$employee_email = $this->getEmployeeEmail($params['employee_id']);
			$email = $employee_email[0]['email'];
			$email = strtoupper($email);
			$tmp_password = $employee_number[0]['employee_number'];
			$row_sql = "UPDATE tblmobileusers SET isfirstlogin = ?, password = (ENCRYPTER('".$tmp_password."','sunev8clt1234567890','".$email."')) WHERE userid = ? ";
			$row_params = array($data['isfirstlogin'],$data['Id']);
			$this->db->query($row_sql,$row_params);
			if($this->db->affected_rows() > 0){
				if(trim($tmp_password) != ""){
					$password = $this->Helper->encrypt($tmp_password,$email);
					$data1 = array(
						"password" => $password,
						// "isfirstlogin" => "1"
					);
					// update web users
					$this->db->where('employee_id', $params['employee_id']);
					if ($this->db->update('tblwebusers',$data1) === FALSE){
						$helperDao->auditTrails(Helper::get('userid'),$message);
						$this->ModelResponse($code, $message);
					}
					// send email
					$sysmessages->init(MessageRels::ACCOUNT_PASS_RESET);
					$message = $sysmessages->getMessage();
					$message = str_replace("<fname>","Sir/Ma'am",$message);
					$message = str_replace("<system>","NWRB-CHRIS",$message);
					$message = str_replace("<username>",$email,$message);
					// $message = str_replace("<tempassw>",$randomletter,$message); // random password
					$message = str_replace("<tempassw>",$tmp_password,$message); //input password
					$message = str_replace("<templink>",base_url(),$message);
					$helperDao->sendMail(
						$sysmessages->getSubject(),
						$email,
						$message
					);
				}
				$code = "0";
				$message = "Successfully reset password.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;		
			} else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}
	}
?>