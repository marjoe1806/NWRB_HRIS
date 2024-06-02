<?php
class EmployeeTrackingCollections extends Helper {
	
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
	var $table = "tbltimekeepingdailytimerecord";   
  	var $order_column = array('tblemployees.employee_id_number','tblemployees.employee_number','tblemployees.first_name','tblfieldpositions.name','tblfielddepartments.department_name','tblemployees.contact_number','tblemployees.employment_status','');
  	public function getColumns(){
  		
  		$sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$this->table."' AND TABLE_SCHEMA='".$this->db->database."' ";
		$query = $this->db->query($sql);
		$rows = $query->result_array();
		return $rows; 
  	}
	function make_query()  
	{  
		//var_dump($this->select_column);die();
		$this->select_column[] = 'tblfieldlocations.name';
	    $this->db->select(
	    	'DISTINCT '.
	    	$this->table.'.employee_number,'.
	    	$this->table.'.transaction_date,'.
	    	$this->table.".source_device,
	    	tblemployees.*,
	    	(SELECT CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) as date FROM tbltimekeepingdailytimerecord b 
							WHERE b.transaction_type = '0'
							AND b.employee_number = tbltimekeepingdailytimerecord.employee_number
							AND b.is_active = '1'
							AND b.transaction_date = tbltimekeepingdailytimerecord.transaction_date
							ORDER BY b.transaction_time ASC LIMIT 1) AS time_in,
			(SELECT IFNULL(CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime),'NO LOG') as date FROM tbltimekeepingdailytimerecord b 
							WHERE b.transaction_type = '1'
							AND b.employee_number = tbltimekeepingdailytimerecord.employee_number
							AND b.is_active = '1'
							AND b.transaction_date = tbltimekeepingdailytimerecord.transaction_date
							ORDER BY b.transaction_time ASC LIMIT 1) AS time_out,
	    	tblfieldlocations.name AS location_name"
	    ,false);  
	    $this->db->from($this->table);  
	    $this->db->join("tblemployees","(DECRYPTER(tblemployees.employee_number,'sunev8clt1234567890',tblemployees.id)) = tbltimekeepingdailytimerecord.employee_number","left");
	    $this->db->join("tblfieldlocations","tblemployees.location_id = tblfieldlocations.id","left");
	    
	   
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
	        $this->db->group_end(); 
	    }
	    $lat1 = isset($_GET['lat1']) ? $_GET['lat1'] : "";
	    $lat2 = isset($_GET['lat2']) ? $_GET['lat2'] : "";
	    $long1 = isset($_GET['long1']) ? $_GET['long1'] : "";
	    $long2 = isset($_GET['long2']) ? $_GET['long2'] : "";
    	$this->db->where($this->table.".latitude BETWEEN ".$this->db->escape($lat1)." AND ".$this->db->escape($lat2)." ",NULL,false);
    	$this->db->where($this->table.'.longitude BETWEEN '.$this->db->escape($long1).' AND '.$this->db->escape($long2).' ',NULL,false);
    	$this->db->where($this->table.".transaction_date = '2018-08-01'");
	    if(isset($_POST["order"]))  
	    {  
	          $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
	    }  
	    else  
	    {  
	          $this->db->order_by($this->table.'.id', 'DESC');  
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
		$this->make_query();
		$query = $this->db->get();
	    
	    if(sizeof($query->result()) > 0){
			$code = "0";
			$message = "Successfully fetched employee.";
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
	    if(isset($_GET['EmploymentStatus']) && $_GET['EmploymentStatus'] != null){
	    	$this->db->where($this->table.'.employment_status',$_GET['EmploymentStatus']);
	    }
	    return $this->db->count_all_results();  
	}  
	//End Fetch		
	public function hasRowsLocations(){
		$helperDao = new HelperDao();
		$data = array();
		$code = "1";
		$message = "No data available.";
		$sql = " SELECT * FROM tblfieldlocations WHERE is_active = '1'";
		$query = $this->db->query($sql);
		$userlevel_rows = $query->result_array();
		$data['details'] = $userlevel_rows;
		if(sizeof($userlevel_rows) > 0){
			$code = "0";
			$message = "Successfully fetched locations.";
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
	public function getTotalEmployeesByLocations($lat1,$lat2,$long1,$long2){

		$helperDao = new HelperDao();
		$data = array();
		$code = "1";
		$message = "No data available.";
		$sql = " SELECT COUNT(DISTINCT employee_number) AS count FROM tbltimekeepingdailytimerecord ";
		$sql .= " WHERE latitude BETWEEN ? AND ? AND longitude BETWEEN ? AND ? AND transaction_date = '2018-08-01' ";
		$query = $this->db->query($sql,array($lat1,$lat2,$long1,$long2));
		$userlevel_rows = $query->result_array();
		$data['details'] = $userlevel_rows;
		if(sizeof($userlevel_rows) > 0){
			$code = "0";
			$message = "Successfully fetched employee counts.";
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
}
?>