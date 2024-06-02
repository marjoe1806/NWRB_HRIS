<?php
	class OvertimeRegisterCollection extends Helper {
      	var $select_column = null; 
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
		}
		//Fetch
		var $table = "tbltransactionsovertimes";   
		function fetchOvertimeRegister($data){
			  
		    $this->db->select(
		    	'tblemployees.*,'.
		    	$this->table.'.*,
		    	tblfieldpayrollgrouping.payroll_grouping_name'
		    );  
		    $this->db->from($this->table);  
		    $this->db->join("tblemployees",$this->table.".employee_id = tblemployees.id","left");
		    $this->db->join("tblfieldpayrollgrouping",$this->table.".payroll_grouping_id = tblfieldpayrollgrouping.id","left");
		    $this->db->join("tblfieldpositions","tblemployees.position_id = tblfieldpositions.id","left");
		    $this->db->where($this->table.'.is_active',1);
		    if(isset($_POST['payroll_period_id']) && $_POST['payroll_period_id'] != "")
		    	$this->db->where($this->table.'.payroll_period_id',$_POST['payroll_period_id']);
		    if(isset($_POST['pay_basis']) && $_POST['pay_basis'] != "")
		    	$this->db->where($this->table.'.pay_basis',$_POST['pay_basis']);
		    if(isset($_POST['payroll_grouping_id']) && $_POST['payroll_grouping_id'] != "")
		    	$this->db->where($this->table.'.payroll_grouping_id',$_POST['payroll_grouping_id']);
		    // $this->db->where('tblemployees.employment_status','Active');
			$this->db->order_by("DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id) ASC");  
			$result = $this->db->get();
		    return $result->result_array();
		} 
		//End Fetch
		function getPayrollPeriodById($payroll_period_id){
			$sql = " SELECT * FROM tblfieldperiodsettings WHERE is_active = '1' AND id = ?";
			$query = $this->db->query($sql,array($payroll_period_id));
			$data = $query->result_array();
			return $data;
		}
		function getPayrollGroupById($payroll_grouping_id){
			$sql = " SELECT * FROM tblfieldpayrollgrouping WHERE is_active = '1' AND id = ?";
			$query = $this->db->query($sql,array($payroll_grouping_id));
			$data = $query->result_array();
			return $data;
		}
		function getPayrollPeriodCutoffByPayrollId($id){
			$sql = " SELECT * FROM tblfieldperiodsettingsweekly WHERE is_active = '1' AND payroll_period_id = ? ORDER BY start_date ASC";
			$query = $this->db->query($sql,array($id));
			$data = $query->result_array();
			return $data;
		}
		function getSignatories(){
			$sql = " SELECT * FROM tblfieldsignatories WHERE is_active = 1 ORDER BY signatory_no ASC";
			$query = $this->db->query($sql);
			$data = $query->result_array();
			return $data;
		}
		function getSignatoriesHead($payroll_grouping_id){
			$sql = " SELECT * FROM tblfieldheadsignatories WHERE is_active = 1 AND payroll_grouping_id = ?";
			$query = $this->db->query($sql,array($payroll_grouping_id));
			$data = $query->result_array();
			return $data;
		}
	}
?>