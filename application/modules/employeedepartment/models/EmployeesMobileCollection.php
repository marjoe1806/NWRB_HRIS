<?php
	class EmployeesMobileCollection extends Helper {
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
		var $table = "tblemployeesmobilelogs";   
      	var $order_column = array(
      				"tblfieldlocations.name",
      				"tblemployeesmobilelogs.log",
      				"tblwebusers.username",
      				"tblemployeesmobilelogs.date_created"
      	);
      	public function getColumns(){
      		
      		$sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$this->table."' AND TABLE_SCHEMA='".$this->db->database."' ";
			$query = $this->db->query($sql);
			$rows = $query->result_array();
			return $rows; 
      	}
		function make_query()  
		{  
			//var_dump($this->select_column);die();
		    $this->db->select(
		    	$this->table.'.*,
		    	tblwebusers.username AS username,
		    	tblfieldlocations.name AS location_name'
		    );  
		    $this->db->from($this->table);  
		    $this->db->join("tblwebusers",$this->table.".created_by = tblwebusers.userid","left");
		    $this->db->join("tblfieldlocations",$this->table.".location_id = tblfieldlocations.id","left");

		    if(isset($_POST["search"]["value"]))  
		    {  
		    	$this->db->group_start();

	     		//var_dump($this->select_column);die();
		     	foreach ($this->select_column as $key => $value) {
		     		
	     			$this->db->or_like($value, $_POST["search"]["value"]);  
		     		
		     	}
		
		        $this->db->group_end(); 
		    }
		 
		    if(isset($_POST["order"]))  
		    {  	
		    	$this->db->order_by($this->order_column[$_POST['order']['0']['column']]." ". $_POST['order']['0']['dir']);
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
		public function downloadMobileEmployees($location_id){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "Failed to download mobile employees.";
			//Get data from employee service
			$ret = $this->getMobileEmployeesCloud($location_id);
			var_dump($ret);die();

			if(isset($ret->Data->mobile_employee) && sizeof($ret->Data->mobile_employee) > 0){
				
				$code = "0";
				$message = "Successfully downloaded mobile employees.";
				$sqldata = array(
					'location_id'=>$location_id,
					'log'=>$message,
					'created_by'=>Helper::get('userid')
				);
				$this->db->insert('tblemployeesmobilelogs',$sqldata);
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
		public function getMobileEmployeesCloud($location_id){
			$ch = curl_init();
			$data = array('imei',$location_id);
			$url = $GLOBALS['service_url'] . "getEmployeeMobileByIMEI";
			// var_dump($url);die();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
			curl_setopt($ch, CURLOPT_POST, count($data));		
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 3000);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			$ret = json_decode(curl_exec($ch));
			if(!curl_error($ch)) return $ret;
			else return array();
			curl_close($ch);
		}

	}
?>