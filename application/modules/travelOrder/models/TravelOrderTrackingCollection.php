<?php
	class TravelOrderTrackingCollection extends Helper {
		var $select_column = null; 
		var $sql = "";
		var $selectRequestParams = array();
		public function __construct() {
			$this->load->model('Helper');
			$this->load->model('HelperDao');
			ModelResponse::busy();
		}

		var $table = "tbltravelorder";
      	var $order_column = array('','a.travel_order_no','duration','last_name','location','officialpurpose','a.division','a.status');
      	public function getColumns(){
      		$sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$this->table."' AND TABLE_SCHEMA='".$this->db->database."' ";
			$query = $this->db->query($sql);
			$rows = $query->result_array();
			return $rows;
      	}
		function make_query(){
			$this->select_column[] = 'a.travel_order_no';
			$this->select_column[] = 'c.last_name';
			$this->select_column[] = 'c.first_name';
			$this->select_column[] = 'c.middle_name';
			$this->select_column[] = 'a.duration';
			$this->select_column[] = 'a.driver';
			$this->select_column[] = 'a.location';
			$this->select_column[] = 'a.officialpurpose';
			$this->select_column[] = 'b.department_name';
			$this->select_column[] = 'a.status';
			
			$this->db->select(
				'DISTINCT 
				(SELECT COUNT(*) FROM tbltravelorderapprover e LEFT JOIN tblemployees b ON e.employee_id = b.id where b.employment_status = "Active" AND e.is_active = 1 AND e.approver = "'.$_SESSION["id"].'" AND e.employee_id = a.employee_id AND e.approve_type = 4) as for_driver, 
				(SELECT COUNT(*) FROM tbltravelorderapprover e LEFT JOIN tblemployees b ON e.employee_id = b.id where b.employment_status = "Active" AND e.is_active = 1 AND e.approver = "'.$_SESSION["id"].'" AND e.employee_id = a.employee_id AND e.approve_type = 3) as approval,
				(SELECT COUNT(*) FROM tbltravelorderapprover e LEFT JOIN tblemployees b ON e.employee_id = b.id where b.employment_status = "Active" AND e.is_active = 1 AND e.approver = "'.$_SESSION["id"].'" AND e.employee_id = a.employee_id AND e.approve_type = 2) as certify,
				(SELECT COUNT(*) FROM tbltravelorderapprover e LEFT JOIN tblemployees b ON e.employee_id = b.id where b.employment_status = "Active" AND e.is_active = 1 AND e.approver = "'.$_SESSION["id"].'" AND e.employee_id = a.employee_id AND e.approve_type = 1) as division_head,
				(SELECT COUNT(*) FROM tbltravelorderapprover e LEFT JOIN tblemployees b ON e.employee_id = b.id where b.employment_status = "Active" AND e.is_active = 1 AND e.approver = "'.$_SESSION["id"].'" AND e.employee_id = a.employee_id AND e.approve_type = 0) as section_head,
				a.*,a.status,b.department_name,
				DECRYPTER(c.first_name,"sunev8clt1234567890",c.id) as first_name,
				DECRYPTER(c.middle_name,"sunev8clt1234567890",c.id) as middle_name,
				DECRYPTER(c.last_name,"sunev8clt1234567890",c.id) as last_name,
				DECRYPTER(c.extension,"sunev8clt1234567890",c.id) as extension');
		    $this->db->from($this->table." a");
			$this->db->join("tblfielddepartments b", "a.division_id = b.id","left");
			$this->db->join("tblemployees c", "a.employee_id = c.id","left");
			
			$joinsql = "d.employee_id = c.id  AND d.is_active = 1";
			$this->db->join("tbltravelorderapprover d", $joinsql,"left");

			if(isset($_GET["search"]["value"])){
				$this->db->group_start();
				foreach ($this->select_column as $key => $value) {
					if($value == "c.first_name" || $value == "c.last_name" || $value == "c.middle_name" || $value == "c.extension")  {
						$this->db->or_like("CONCAT(DECRYPTER(c.last_name,'sunev8clt1234567890',c.id),' ',DECRYPTER(c.extension,'sunev8clt1234567890',c.id),', ',DECRYPTER(c.first_name,'sunev8clt1234567890',c.id),' ',DECRYPTER(c.middle_name,'sunev8clt1234567890',c.id))",$_GET["search"]["value"]); 
					}else{
						$this->db->or_like($value, $_GET["search"]["value"]);  
					}
				}
				$this->db->group_end();
				
			}
			// $this->db->where("d.approver",$_SESSION["id"]);
			// end
			//for order column
			if($_GET["status"] != "") $this->db->where("a.status",$_GET["status"]);
		    if(isset($_GET["order"])){
				$this->db->order_by($this->order_column[$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
		    }else{
				$this->db->order_by("a.date_created", 'DESC');				
				$this->db->where("a.status !=", null);
		    }
			// end
		}
		
		function make_datatables(){
		    $this->make_query();
			if($_GET["length"] != -1) $this->db->limit($_GET['length'], $_GET['start']);
		    $query = $this->db->get();
		    // var_dump($this->db->last_query($query)); die();
		    return $query->result();
		}

		function get_filtered_data(){
			$this->make_query();
			$query = $this->db->get();
		    return $query->num_rows();
		}
		function get_all_data(){
		    $this->db->select($this->table."*");
			$this->db->from($this->table);
			if($_GET["status"] != "") $this->db->where($this->table.".status",$_GET["status"]);
		    return $this->db->count_all_results();
		}
	}
?>