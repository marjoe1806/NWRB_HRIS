<?php
	class PayrollPostingCollection extends Helper {
      	var $select_column = null; 
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
			$columns = $this->getColumns();
			foreach ($columns as $key => $value) {
				$this->select_column[] = $this->table.'.'.$value['COLUMN_NAME'];
			}
			//var_dump($this->select_column);die();

		}
		//Fetch
		var $table = "tbltransactionspayrollposting";   
      	var $order_column = array("DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id)",
      							  "days_count","lwp","pg_share",
      							  "is_active","username","");
      	public function getColumns(){
      		
      		$sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$this->table."' AND TABLE_SCHEMA='".$this->db->database."' ";
			$query = $this->db->query($sql);
			$rows = $query->result_array();
			return $rows; 
      	}
		function make_query()  
		{  
			//var_dump($this->select_column);die();
			$this->select_column[] = 'tblemployees.first_name';
			$this->select_column[] = 'tblemployees.middle_name';
			$this->select_column[] = 'tblemployees.last_name';
			$this->select_column[] = 'tblfieldperiodsettings.payroll_period';
			$this->select_column[] = 'tblfieldperiodsettings.period_id';
			$this->select_column[] = 'tblwebusers.username';
		    $this->db->select(
		    	$this->table.'.*,
		    	tblemployees.first_name,
		    	tblemployees.middle_name,
		    	tblemployees.last_name,
		    	tblfieldperiodsettings.payroll_period,
		    	tblfieldperiodsettings.period_id,
		    	tblwebusers.username'
		    	
		    );  
		    $this->db->from($this->table);  
		    $this->db->join("tblemployees",$this->table.".employee_id = tblemployees.id","left");
		    $this->db->join("tblwebusers",$this->table.".created_by = tblwebusers.userid","left");
		    $this->db->join("tblfieldperiodsettings",$this->table.".payroll_period_id = tblfieldperiodsettings.id","left");

		    if(isset($_POST["search"]["value"]))  
		    {  
		    	$this->db->group_start();

		    		//var_dump($this->select_column);die();
		     	foreach ($this->select_column as $key => $value) {
		     		//$this->db->like($value, $_POST["search"]["value"]); 
		     		//var_dump($value == "$this->table.first_name");
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
		     	if(stripos("active",$_POST["search"]["value"]) !== FALSE){
		     		$this->db->or_like($this->table.".is_active", 1); 
		     	}
		     	if(stripos("inactive",$_POST["search"]["value"]) !== FALSE && strtolower($_POST["search"]["value"]) != "active"){
		     		$this->db->or_like($this->table.".is_active", 0); 
		     	}
		        $this->db->group_end(); 
		    }
		    
		    if(isset($_GET['Id']) && $_GET['Id'] != null)
		    	$this->db->where($this->table.'.id="'.$_GET['Id'].'"');//die('hit');
		    if(isset($_GET['pay_basis']) && $_GET['pay_basis'] != null)
		    	$this->db->where($this->table.'.pay_basis="'.$_GET['pay_basis'].'"');
		    if(isset($_GET['division_id']) && $_GET['division_id'] != null)
		    	$this->db->where($this->table.'.division_id="'.$_GET['division_id'].'"');
		    // var_dump($_GET['payroll_period_id']);die();
		    if(isset($_GET['payroll_period_id']) && $_GET['payroll_period_id'] != null)
		    	$this->db->where($this->table.'.payroll_period_id="'.$_GET['payroll_period_id'].'"');
		    // if(isset($_GET['EmployeeId'])){
		    // 	$this->db->where($this->table.'.employee_id="'.$_GET['EmployeeId'].'"');
		    // }
		    else
		    {
		    	$this->db->where('1=0');
		    }	
		    if(isset($_POST["order"]))  
		    {  
		          $this->db->order_by($this->order_column[$_POST['order']['0']['column']]." ". $_POST['order']['0']['dir']);  
		    }  
		    else  
		    {  
		          $this->db->order_by('id', 'DESC');  
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
		    return $query->result();  
		}  
		function hasRows(){
			$code = "1";
			$message = "No data available.";
			$this->make_query();
			$query = $this->db->get();
		    
		    if(sizeof($query->result()) > 0){
				$code = "0";
				$message = "Successfully fetched Payroll Posting.";
				$data['details'] = $query->result();
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
		    return $this->db->count_all_results();  
		}  
		//End Fetch
		public function activeRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Payroll Posting failed to activate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "1";
			$userlevel_sql = "	UPDATE ".$this->table." SET
									is_active = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully activated Payroll Posting.";
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
			$message = "Payroll Posting failed to deactivate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "0";
			$userlevel_sql = "	UPDATE ".$this->table." SET
									is_active = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully deactivated Payroll Posting.";
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
			$params['created_by'] = Helper::get('userid');
			$message = "Payroll Posting failed to insert.";
			$this->db->trans_begin();
			$this->db->insert($this->table, $params);
			//Create History
			$params['ref_id'] = $this->db->insert_id();
			$this->db->insert($this->table.'_updates', $params);
			if($this->db->trans_status() === TRUE){
				$code = "0";
				$message = "Successfully inserted Payroll Posting.";
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
		public function updateRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Payroll Posting failed to update.";
			// $params['is_approved'] = isset($params['is_approved'])?"1":"0";
			$params['created_by'] = Helper::get('userid');
			$this->db->trans_begin();
			$this->db->where('id', $params['id']);
			$this->db->update($this->table,$params);
			//Create History
			$params['ref_id'] = $params['id'];
			$params['id'] = null;
			$this->db->insert($this->table.'_updates', $params);
			if($this->db->trans_status() === TRUE){
				$code = "0";
				$message = "Successfully updated Payroll Posting.";
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
		

	}
?>