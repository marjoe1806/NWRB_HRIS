<?php
	class MonthsCollection extends Helper {
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
		var $table = "tblmonths";   
      	var $order_column = array('first_name','last_name','address');
      	public function getColumns(){
      		
      		$sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$this->table."' ";
			$query = $this->db->query($sql);
			$rows = $query->result_array();
			return $rows; 
      	}
		function make_query()  
		{  
			//var_dump($this->select_column);die();
			/*$this->select_column[] = 'tblfieldpositions.name';
			$this->select_column[] = 'tblfieldagencies.agency_name';
			$this->select_column[] = 'tblfieldoffices.name';
			$this->select_column[] = 'tblfielddepartments.department_name';
			$this->select_column[] = 'tblfieldlocations.name';
			$this->select_column[] = 'tblfieldfundsources.fund_source';
			$this->select_column[] = 'tblfieldloans.description';
			$this->select_column[] = 'tblfieldbudgetclassifications.budget_classification_name';*/
		    $this->db->select(
		    	$this->table.'.*'
		    );  
		    $this->db->from($this->table);  
		    /*$this->db->join("tblfieldpositions",$this->table.".position_id = tblfieldpositions.id","left");
		    $this->db->join("tblfieldagencies",$this->table.".agency_id = tblfieldagencies.id","left");
		    $this->db->join("tblfieldoffices",$this->table.".office_id = tblfieldoffices.id","left");
		    $this->db->join("tblfielddepartments",$this->table.".division_id = tblfielddepartments.id","left");
		    $this->db->join("tblfieldlocations",$this->table.".location_id = tblfieldlocations.id","left");
		    $this->db->join("tblfieldfundsources",$this->table.".fund_source_id = tblfieldfundsources.id","left");
		    $this->db->join("tblfieldloans",$this->table.".loans_id = tblfieldloans.id","left");
		    $this->db->join("tblfieldbudgetclassifications",$this->table.".budget_classification_id = tblfieldbudgetclassifications.id","left");*/
		   
		    if(isset($_POST["search"]["value"]))  
		    {  
		    	$this->db->group_start();
		     	foreach ($this->select_column as $key => $value) {
		     		//$this->db->like($value, $_POST["search"]["value"]);  
		     		$this->db->or_like($value, $_POST["search"]["value"]);  
		     	}
		        $this->db->group_end(); 
		    }
		    if(isset($_GET['Id']) && $_GET['Id'] != null)
		    	$this->db->where($this->table.'.id="'.$_GET['Id'].'"');//die('hit');
		    if(isset($_POST["order"]))  
		    {  
		          $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
		    }  
		    else  
		    {  
		          $this->db->order_by('id', 'ASC');  
		    }  
		}  
		function hasRows(){
			$code = "1";
			$message = "No data available.";
			$this->make_query();
			$query = $this->db->get();
		    
		    if(sizeof($query->result()) > 0){
				$code = "0";
				$message = "Successfully fetched Other Deduction.";
				$data['details'] = $query->result();
				$this->ModelResponse($code, $message, $data);
				return true;		
			}	
			else {
				$this->ModelResponse($code, $message);
			}
			return false;
		}    
		

	}
?>