<?php
	class AuditTrailsCollection extends Helper {
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
		var $table = "tblaudittrail";
      	var $order_column = array();
      	public function getColumns(){

      		$sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$this->table."' AND TABLE_SCHEMA='".$this->db->database."' ";
			$query = $this->db->query($sql);
			$rows = $query->result_array();
			return $rows;
      	}
		function make_query($day)
		{
			$this->order_column = array(
				'tblemployees.employee_number',
				'tblemployees.last_name',
				"tblaudittrail.log",
				"tblaudittrail.ip",
				"tblaudittrail.source_device",
				"tblaudittrail.timestamp"
			);
			$this->select_column[] = 'tblemployees.employee_number';
			$this->select_column[] = 'tblemployees.first_name';
			$this->select_column[] = 'tblaudittrail.log';
			$this->select_column[] = 'tblaudittrail.ip';
			$this->select_column[] = 'tblaudittrail.source_device';
			$this->select_column[] = 'tblaudittrail.timestamp';
		    $this->db->select(
		    	$this->table.'.*,
		    	tblwebusers.*,
		    	tblemployees.*'
		    );
		    $this->db->from($this->table);
		    $this->db->join("tblwebusers",$this->table.".userid = tblwebusers.userid","left");
		    $this->db->join("tblemployees","tblwebusers.employee_id = tblemployees.id","left");

		    /*if(isset($_GET["search"]["value"]))
		    {
		    	$this->db->group_start();
		     	foreach ($this->select_column as $key => $value) {
		     		//$this->db->like($value, $_GET["search"]["value"]);
		     		$this->db->or_like($value, $_GET["search"]["value"]);
		     	}
		        $this->db->group_end();
		    }*/
			$this->db->where("DATE(tblaudittrail.timestamp) = DATE('".$day."')");
    	    if(isset($_GET["search"]["value"]))
		    {
		    	$this->db->group_start();

		    		//var_dump($this->select_column);die();
		     	foreach ($this->select_column as $key => $value) {
		     		//$this->db->like($value, $_GET["search"]["value"]);
		     		//var_dump($value == "$this->table.first_name");
		     		if($value == "tblemployees.first_name" || $value == "tblemployees.last_name" || $value == "tblemployees.middle_name" || $value == "tblemployees.employee_number" || $value == "tblemployees.employee_id_number")  {
		     			$this->db->or_like("DECRYPTER($value,'sunev8clt1234567890',tblemployees.id)", $_GET["search"]["value"]);

		     		}
		     		else{
		     			$this->db->or_like($value, $_GET["search"]["value"]);
		     		}

		     	}
		     	$this->db->or_like("CONCAT(DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.middle_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id))"
		     		,$_GET["search"]["value"]);
		     	$this->db->or_like("CONCAT(DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id))"
		     		,$_GET["search"]["value"]);
		        $this->db->group_end();
		    }
		    if(isset($_GET["order"]))
		    {
		          $this->db->order_by($this->order_column[$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
		    }
		    else
		    {
		          $this->db->order_by('timestamp', 'DESC');
		    }
		}
		function make_datatables($day){
		    $this->make_query($day);
		    if($_GET["length"] != -1)
		    {
		        $this->db->limit($_GET['length'], $_GET['start']);
		    }

		    $query = $this->db->get();

		    // var_dump($this->db->last_query());die();
		    return $query->result();
		}
		function get_filtered_data($day){
		     $this->make_query($day);
		     //var_dump($this->make_query());die();

		     $query = $this->db->get();
		     return $query->num_rows();
		}
		function get_all_data($day)
		{
		    $this->db->select($this->table."*");
			$this->db->from($this->table);
			$this->db->where("DATE(tblaudittrail.timestamp) = DATE('".$day."')");
		    return $this->db->count_all_results();
		}
	}
?>