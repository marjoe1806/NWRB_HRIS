<?php

class BIRformCollection extends Helper {

    var $select_column = null;
    var $selectEmployeesParams = array();
    var $sql = "";

    public function __construct() {
        $this->load->model('HelperDao');
        ModelResponse::busy();
        $columns = $this->getColumns();
        foreach ($columns as $key => $value) {
			$this->select_column[] = $this->table2.'.'.$value['COLUMN_NAME'];
		}
    }
    //Fetch
    var $table2 = "tblbiralphalist";   
      var $order_column = array('', 'last_name','department');
      
      public function getColumns(){
		$sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$this->table2."' AND TABLE_SCHEMA='".$this->db->database."' ";
	  $query = $this->db->query($sql);
	  $rows = $query->result_array();
	  return $rows;
  }
      
 function make_query($date_year){
	$this->db->select('*');  
	$this->db->from($this->table2);
	$this->db->where($this->table2.'.date_year', $date_year);
	
	
	if(isset($_POST["search"]["value"])) {  
		$this->db->group_start();
		foreach ($this->select_column as $key => $value) {
				$this->db->or_like($value, $_POST["search"]["value"]);  
		}
		$this->db->group_end(); 
	}
	if(isset($_POST["order"]))
		$this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
	else
		$this->db->order_by("tblbiralphalist.id ASC");
}

function make_datatables($date_year){  
	$this->make_query($date_year);  
	if($_POST["length"] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);

	$query = $this->db->get();
	// var_dump(json_encode($query->result_array()));die();
	return $query->result();  
}

function get_filtered_data($date_year){  
	$this->make_query($date_year);
	$query = $this->db->get();  
	return $query->num_rows();  
}

function get_all_data() {  
	$this->db->select("*");  
	$this->db->from($this->table2);
   return $this->db->count_all_results();  
} 

   public function getPayroll($employee_id){
    $sql ="SELECT * FROM tblbiralphalist WHERE id = ?";
    $loans = $this->db->query($sql,array($employee_id))->result_array();
    return $loans;
    
   }
   function getSignatoriesA($division_name){
    $sql ="SELECT
    a.*,
    CONCAT(
        DECRYPTER(b.first_name,'sunev8clt1234567890',b.id),' ',
        LEFT(DECRYPTER(b.middle_name,'sunev8clt1234567890',b.id),1),'. ',
        DECRYPTER(b.last_name,'sunev8clt1234567890',b.id),' ',DECRYPTER(b.extension,'sunev8clt1234567890',b.id)) as employee_name, b.position_designation, b.division_designation, c.name as position_title, d.department_name as department
    FROM tblfieldsignatories a LEFT JOIN tblemployees b ON a.employee_id = b.id
    LEFT JOIN tblfieldpositions c ON b.position_id = c.id LEFT JOIN tblfielddepartments d ON b.division_id = d.id WHERE a.signatory = ?";
    $loans = $this->db->query($sql,array($division_name))->result_array();
    return $loans;
}
}
?>