<?php
	class LocatorSlipsTrackingCollection extends Helper {
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
		}
		// Step 1: Configuration

		// Declare variables for selecting columns, table name, and ordering columns.
		var $select_column = null;
		var $table = "tbltimekeepinglocatorslips";
		var $order_column = array(
			'',
			'control_no',
			'tbltimekeepinglocatorslips.transaction_date',
			'tblemployees.first_name',
			'tblfielddepartments.department_name',
			'tbltimekeepinglocatorslips.purpose',
			'tbltimekeepinglocatorslips.location',
			'tbltimekeepinglocatorslips.activity_name',
			'tbltimekeepinglocatorslips.status',
			'tbltimekeepinglocatorslips.remarks'
		);

		// Step 2: make_query() Function

		// This function constructs a SQL query to retrieve data.
		function make_query() {
			// Step 2.1: Define which columns to select.
			$this->select_column[] = 'tblemployees.first_name';
			$this->select_column[] = $this->table.'.filing_date';
			$this->select_column[] = $this->table.'.purpose';
			$this->select_column[] = $this->table.'.activity_name';
			$this->select_column[] = $this->table.'.transaction_date';
			$this->select_column[] = $this->table.'.status';
			$this->select_column[] = $this->table.'.remarks';
			$this->select_column[] = $this->table.'.control_no';
			// Step 2.2: Construct a complex SQL query.
			$this->db->select('DISTINCT (SELECT COUNT(*) FROM tblemployeesobapprovers a LEFT JOIN tblemployees b ON a.employee_id = b.id where b.employment_status = "Active" AND a.is_active = 1 AND a.approver = "'.$_SESSION["id"].'" AND a.employee_id = tbltimekeepinglocatorslips.employee_id AND a.approve_type = 4) as approve,(SELECT COUNT(*) FROM tblemployeesobapprovers a LEFT JOIN tblemployees b ON a.employee_id = b.id where b.employment_status = "Active" AND a.is_active = 1 AND a.approver = "'.$_SESSION["id"].'" AND a.employee_id = tbltimekeepinglocatorslips.employee_id AND a.approve_type = 5) as secondapprove, (SELECT COUNT(*) FROM tblemployeesobapprovers a LEFT JOIN tblemployees b ON a.employee_id = b.id where b.employment_status = "Active" AND a.is_active = 1 AND a.approver = "'.$_SESSION["id"].'" AND a.employee_id = tbltimekeepinglocatorslips.employee_id AND a.approve_type = 2) as recom,tblemployees.*,'.$this->table.'.*,tblemployees.id as salt, DECRYPTER(tblemployees.employee_id_number, "sunev8clt1234567890", tblemployees.id) as emp_id, tblfieldpositions.name as position_name, tblfielddepartments.department_name as department_name');
			$this->db->from($this->table);
			$this->db->join("tblemployees","tblemployees.id = ".$this->table.".employee_id","left");
			$this->db->join("tblfieldpositions", "tblemployees.position_id = tblfieldpositions.id","left");
			$this->db->join("tblfielddepartments", $this->table.".division_id = tblfielddepartments.id","left");
		
			if(isset($_POST["search"]["value"])) {
				$this->db->group_start();
				foreach ($this->select_column as $key => $value) {
					if($value == "tblemployees.first_name" || $value == "tblemployees.last_name" || $value == "tblemployees.middle_name" || $value == "tblemployees.extension")  {
						$this->db->or_like("CONCAT(DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.extension,'sunev8clt1234567890',tblemployees.id),', ',DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.middle_name,'sunev8clt1234567890',tblemployees.id))",$_POST["search"]["value"]);
					} else {
						$this->db->or_like($value, $_POST["search"]["value"]);
					}
				}
				$this->db->or_like("CONCAT(DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.extension,'sunev8clt1234567890',tblemployees.id),', ',DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.middle_name,'sunev8clt1234567890',tblemployees.id))",$_POST["search"]["value"]);
				$this->db->group_end();
			}
			
			if(!Helper::role(ModuleRels::OB_VIEW_ALL_TRANSACTIONS)) {
				#$this->db->where('(tblemployees.division_id = '.$_SESSION["division_id"].' OR (SELECT COUNT(*) FROM tblemployeesobapprovers a LEFT JOIN tblemployees b ON a.employee_id = b.id where b.employment_status = "Active" AND a.is_active = 1 AND a.approver = "'.$_SESSION["id"].'" AND a.employee_id = tbltimekeepinglocatorslips.employee_id AND a.approve_type = 3) > 0)');
			}

				// tblemployees.division_id = '.$_SESSION["division_id"].' OR 
			// $this->db->where('
			// 	(SELECT COUNT(*) FROM tblemployeesobapprovers a LEFT JOIN tblemployees b ON a.employee_id = b.id where b.employment_status = "Active" AND a.is_active = 1 AND a.approver = "'.$_SESSION["id"].'" AND a.employee_id = tbltimekeepinglocatorslips.employee_id AND a.approve_type = 4) > 0 
			// 	OR (SELECT COUNT(*) FROM tblemployeesobapprovers a LEFT JOIN tblemployees b ON a.employee_id = b.id where b.employment_status = "Active" AND a.is_active = 1 AND a.approver = "'.$_SESSION["id"].'" AND a.employee_id = tbltimekeepinglocatorslips.employee_id AND a.approve_type = 5) > 0
			// 	OR (SELECT COUNT(*) FROM tblemployeesobapprovers a LEFT JOIN tblemployees b ON a.employee_id = b.id where b.employment_status = "Active" AND a.is_active = 1 AND a.approver = "'.$_SESSION["id"].'" AND a.employee_id = tbltimekeepinglocatorslips.employee_id AND a.approve_type = 2) > 0
			// ');
			
			if(isset($_POST['status']) && $_POST['status'] != '') {
				$this->db->where($this->table.".status",$_POST['status']);
			}
			
			if(isset($_POST["order"])) {
				$this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
			}
			
			$this->db->order_by($this->table.'.date_created',"desc");
			//$this->db->order_by($this->table.'.transaction_date',"DESC");
			$this->db->where($this->table.".status !=", null);
		}
		
		// Step 3: make_datatables() Function
		// This function executes the query and returns results for display.
		function make_datatables() {
			$this->make_query();
			if ($_POST["length"] != -1) $this->db->limit($_POST['length'], $_POST['start']);
			$query = $this->db->get();
			return $query->result();
		}

		// Step 4: get_filtered_data() Function

		// This function returns the count of filtered rows without limiting the result set.
		function get_filtered_data() {
			$this->make_query();
			$query = $this->db->get();
			return $query->num_rows();
		}

		// Step 5: get_all_data() Function

		// This function returns the total count of rows in the table without filtering.
		function get_all_data() {
			$this->db->select($this->table . "*");
			$this->db->from($this->table);
			return $this->db->count_all_results();
		}
	}
?>