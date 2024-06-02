<?php
	class PositionsReportsCollection extends Helper {
		var $select_column = null;
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
			$columns = $this->getColumns();
			foreach ($columns as $key => $value) {
				$this->select_column[] = $this->table.'.'.$value;
			}
		}

		// set orderable columns in vacant positions list
		var $table = "tblfieldpositions";
		var $order_column = array(
			'',
			'name',
			'grade',
			'vacant',
			'permanent',
			'temporary',
			'filled_pos',
		);

		// set searchable parameters in tblfieldpositions table
		public function getColumns() {
			$rows = array(
				'id',
				'name',
				'grade',
				'vacant',
				'permanent',
				'temporary',
				'filled_pos',
			);
			return $rows;
		}

		// set limit in datatable
		function make_datatables() {
			$this->make_query();
			if($_POST["length"] != -1) {
				$this->db->limit($_POST['length'], $_POST['start']);
			}

			$query = $this->db->get();
			// var_dump($this->db->last_query()); die();
			return $query->result();
		}

		// fetch list of vacant positions
		function make_query() {
			$order_columnvacantpositions = array(
					"",
      				"tblfieldpositions.name",
      				"tblfieldpositions.salary_grade_id",
					"tblfieldpositions.vacant",
					'COUNT(CASE WHEN tblemployees.pay_basis = "Permanent" AND tblemployees.employment_status = "Active" AND tblemployees.position_id = tblfieldpositions.id THEN 1 END)',
					'COUNT(CASE WHEN tblemployees.pay_basis = "Contractual" AND tblemployees.employment_status = "Active" AND tblemployees.position_id = tblfieldpositions.id THEN 1 END)',
					'COUNT(CASE WHEN tblemployees.pay_basis = "Contract of Service" AND tblemployees.employment_status = "Active" AND tblemployees.position_id = tblfieldpositions.id THEN 1 END)',
					'COUNT(CASE WHEN tblemployees.pay_basis = "Temporary" AND tblemployees.position_id = tblfieldpositions.id THEN 1 END)',
					'filled_pos'
      		);

			$this->db->select(
				$this->table.'.*,
				tblfieldpositions.salary_grade_id AS grade,
				tblfieldpositions.id as position_id,
				tblfielddepartments.department_name AS department_name,
				COUNT(CASE WHEN tblemployees.pay_basis = "Permanent" AND tblemployees.employment_status = "Active" AND tblemployees.position_id = tblfieldpositions.id THEN 1 END) AS permanent,
				COUNT(CASE WHEN tblemployees.pay_basis = "Contractual" AND tblemployees.employment_status = "Active" AND tblemployees.position_id = tblfieldpositions.id THEN 1 END) AS contractual,
				COUNT(CASE WHEN tblemployees.pay_basis = "Contract of Service" AND tblemployees.employment_status = "Active" AND tblemployees.position_id = tblfieldpositions.id THEN 1 END) AS cos,
				COUNT(CASE WHEN tblemployees.pay_basis = "Temporary" AND tblemployees.employment_status = "Active" AND tblemployees.position_id = tblfieldpositions.id THEN 1 END) AS temporary,
				tblfieldpositions.vacant - 
				(COUNT(CASE WHEN tblemployees.pay_basis = "Permanent" AND tblemployees.employment_status = "Active" AND tblemployees.position_id = tblfieldpositions.id THEN 1 END) +
				COUNT(CASE WHEN tblemployees.pay_basis = "Contractual" AND tblemployees.employment_status = "Active" AND tblemployees.position_id = tblfieldpositions.id THEN 1 END) +
				COUNT(CASE WHEN tblemployees.pay_basis = "Contract of Service" AND tblemployees.employment_status = "Active" AND tblemployees.position_id = tblfieldpositions.id THEN 1 END) +
				COUNT(CASE WHEN tblemployees.pay_basis = "Temporary" AND tblemployees.employment_status = "Active" AND tblemployees.position_id = tblfieldpositions.id THEN 1 END)) AS filled_pos'
				);

			$this->db->from($this->table);

			$this->db->join('tblemployees', $this->table.'.id = tblemployees.position_id', 'left');
			$this->db->join("tblfielddepartments","tblfieldpositions.division_id = tblfielddepartments.id","left");
			$this->db->join('tblfieldsalarygrades', $this->table.'.salary_grade_id = tblfieldsalarygrades.id', 'left');
			$this->db->where('tblfieldpositions.is_active', 1);

			$this->db->group_by($this->table.'.code');

			if(isset($_POST['filter1']) && $_POST['filter1'] != null && $_POST['filter1'] == "vacant"){
				$this->db->having('tblfieldpositions.vacant - 
				(COUNT(CASE WHEN tblemployees.pay_basis = "Permanent" AND tblemployees.employment_status = "Active" AND tblemployees.position_id = tblfieldpositions.id THEN 1 END) +
				COUNT(CASE WHEN tblemployees.pay_basis = "Contractual" AND tblemployees.employment_status = "Active" AND tblemployees.position_id = tblfieldpositions.id THEN 1 END) +
				COUNT(CASE WHEN tblemployees.pay_basis = "Contract of Service" AND tblemployees.employment_status = "Active" AND tblemployees.position_id = tblfieldpositions.id THEN 1 END) +
				COUNT(CASE WHEN tblemployees.pay_basis = "Temporary" AND tblemployees.employment_status = "Active" AND tblemployees.position_id = tblfieldpositions.id THEN 1 END)) >= 1');  
			}else if (isset($_POST['filter1']) && $_POST['filter1'] != null && $_POST['filter1'] == "filled"){
				$this->db->select('CONCAT(DECRYPTER(tblemployees.last_name,"sunev8clt1234567890",tblemployees.id),", ",
				DECRYPTER(tblemployees.first_name,"sunev8clt1234567890",tblemployees.id)," ",
				DECRYPTER(tblemployees.middle_name,"sunev8clt1234567890",tblemployees.id)) as full_name');
				$this->db->having('tblfieldpositions.vacant - 
				(COUNT(CASE WHEN tblemployees.pay_basis = "Permanent" AND tblemployees.employment_status = "Active" AND tblemployees.position_id = tblfieldpositions.id THEN 1 END) +
				COUNT(CASE WHEN tblemployees.pay_basis = "Contractual" AND tblemployees.employment_status = "Active" AND tblemployees.position_id = tblfieldpositions.id THEN 1 END) +
				COUNT(CASE WHEN tblemployees.pay_basis = "Contract of Service" AND tblemployees.employment_status = "Active" AND tblemployees.position_id = tblfieldpositions.id THEN 1 END) +
				COUNT(CASE WHEN tblemployees.pay_basis = "Temporary" AND tblemployees.employment_status = "Active" AND tblemployees.position_id = tblfieldpositions.id THEN 1 END)) = 0');  
			}else{
				$this->db->select('CONCAT(DECRYPTER(tblemployees.last_name,"sunev8clt1234567890",tblemployees.id),", ",
				DECRYPTER(tblemployees.first_name,"sunev8clt1234567890",tblemployees.id)," ",
				DECRYPTER(tblemployees.middle_name,"sunev8clt1234567890",tblemployees.id)) as full_name');
			}

	
			if(isset($_POST["order"])){
			$this->db->order_by($order_columnvacantpositions[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
			}else{
				$this->db->order_by($this->table.'.id');
			}
			
			$select_column = array('tblfieldpositions.code','name','department_name','grade','vacant');
			if(isset($_POST["search"]["value"]) && $_POST["search"]["value"] != "") {
				$this->db->group_start();
				foreach($select_column as $key => $value) {
					$this->db->or_like($value, $_POST["search"]["value"]);
				}
				$this->db->group_end();
			}

			if(isset($_POST['division']) && $_POST['division'] != null)
				$this->db->where('tblfieldpositions.division_id =', $_POST['division']);
		}

		// get count of all positions
		function get_all_data() {
			$this->db->select($this->table."*");
			$this->db->from($this->table);
			return $this->db->count_all_results();
		}

		// get count of filtered positions
		function get_filtered_data(){
			$this->make_query();
			$query = $this->db->get();
			return $query->num_rows();
		}
	}
?>
