<?php
	class OtherEarningEntriesUpdatesCollection extends Helper {
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
		var $table = "tbltransactionsotherearnings_updates";   
      	var $order_column = array('(@cnt := @cnt)',
      							  'DECRYPTER(tblemployees.last_name,"sunev8clt1234567890",tblemployees.id)',
      							  "pay_basis",
      							  'period_id','earning_description','amount','balance',
      							  "is_active","username","date_created");
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
			$this->select_column[] = 'tblfieldotherearnings.earning_code';
			$this->select_column[] = 'tblfieldotherearnings.description';
			$this->select_column[] = 'tblfieldperiodsettings.payroll_period';
			$this->select_column[] = 'tblfieldperiodsettings.period_id';
			$this->select_column[] = 'tblmonths.code';
			$this->select_column[] = 'tblmonths.name';
			$this->select_column[] = 'tblwebusers.username';
		    $this->db->select(
		    	$this->table.'.*,
				(@cnt := @cnt + 1) AS rowNumber,
		    	tblemployees.first_name,
		    	tblemployees.middle_name,
		    	tblemployees.last_name,
		    	tblfieldotherearnings.earning_code,
		    	tblfieldotherearnings.description AS earning_description,
		    	tblfieldperiodsettings.payroll_period,
		    	tblfieldperiodsettings.period_id,
		    	tblmonths.code AS month_code,
		    	tblmonths.name AS month_name,
		    	tblwebusers.username'
		   
		    );  
		   	$this->db->from($this->table);  
		    $this->db->join("tblemployees",$this->table.".employee_id = tblemployees.id","left");
		    $this->db->join("tblfieldotherearnings",$this->table.".earning_id = tblfieldotherearnings.id","left");
		    $this->db->join("tblfieldperiodsettings",$this->table.".payroll_period_id = tblfieldperiodsettings.id","left");
		    $this->db->join("tblmonths",$this->table.".month = tblmonths.code","left");
		    $this->db->join("tblwebusers",$this->table.".modified_by = tblwebusers.userid","left");
		    $this->db->join('(SELECT @cnt := 0) as dummy','1=1');
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
		    
		    if(isset($_GET['ref_id']) && $_GET['ref_id'] != null)
		    	$this->db->where($this->table.'.ref_id="'.$_GET['ref_id'].'"');
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
		          $this->db->order_by('id', 'ASC');  
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
				$message = "Successfully fetched Other Earning.";
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
		

	}
?>