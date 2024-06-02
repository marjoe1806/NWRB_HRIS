<?php
class PrintedDTRCollection extends Helper {
	var $select_column = null;
	public function __construct() {
		$this->load->model('Helper');
		$this->load->model('HelperDao');
		ModelResponse::busy();
		$columns = $this->getColumns();
		foreach ($columns as $key => $value) {
			$this->select_column[] = $this->table.'.'.$value;
		}
	}

	var $table = "tblemployees";
	var $order_column = array(
		"DECRYPTER(tblemployees.employee_number,'sunev8clt1234567890',tblemployees.id)",
		"DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id)",
		"tblfielddepartments.department_name",
		"tblfieldlocations.name"
  	);

	public function getColumns(){
		$rows = array(
			'employee_number',
			'last_name',
			'middle_name',
			'first_name',
		);
		return $rows;
	}



	function make_query() {
		$this->select_column[] = 'tblfielddepartments.department_name';
		$this->select_column[] = 'tblfieldlocations.name';
		$this->db->distinct();
		$this->db->select(
			$this->table.'.*,'.
			'tbltimekeepingdailytimerecord.employee_number AS scanning_number,
			tblfielddepartments.department_name,
			tblfieldlocations.name AS location_name'
		);
		$this->db->from('tblemployees');
		$this->db->join("tbltimekeepingdailytimerecord","CAST(DECRYPTER(tblemployees.employee_number,'sunev8clt1234567890',tblemployees.id) AS int) = CAST(tbltimekeepingdailytimerecord.employee_number AS int)","left");
		
		$this->db->join("tblfielddepartments","tblemployees.division_id = tblfielddepartments.id","left");
		$this->db->join("tblfieldlocations","tblemployees.location_id = tblfieldlocations.id","left");

		if(isset($_POST["search"]["value"])) {
			$this->db->group_start();
			foreach ($this->select_column as $key => $value) {
				$this->db->or_like($value, $_POST["search"]["value"]);
			}
			$this->db->group_end();
		}
		if(isset($_POST['date_from']) && $_POST['date_from'] != "" 
			&& isset($_POST['date_to']) && $_POST['date_to'] != ""){
			$this->db->where('tbltimekeepingdailytimerecord.transaction_date BETWEEN "'.$_POST['date_from'].'" AND "'.$_POST['date_to'].'"');
		}
		if((isset($_POST['location_id']) && $_POST['location_id'] != null))
			$this->db->where('tblemployees.location_id='.$_POST['location_id'].'"');
		else {
			$this->db->where('1=0');
		}
		$this->db->group_by('tbltimekeepingdailytimerecord.employee_number');
		if(isset($_POST["order"])) {
			$this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		}
		else {
			$this->db->order_by('tblemployees.date_created', 'DESC');
		}
	}

	function make_datatables() {
		$this->make_query();
		if($_POST["length"] != -1)
		{
			$this->db->limit($_POST['length'], $_POST['start']);
		}
		$query = $this->db->get();
		return $query->result();
	}

	function get_filtered_data(){
		$this->make_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	function get_all_data() {
		$this->db->select($this->table."*");
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

}
?>