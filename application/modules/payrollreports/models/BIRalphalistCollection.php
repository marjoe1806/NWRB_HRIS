<?php

class BIRalphalistCollection extends Helper {
	var $select_column = null;  
    public function __construct() {
        $this->load->model('HelperDao'); 
        ModelResponse::busy();
		$columns = $this->getColumns();
		
		foreach ($columns as $key => $value) {
			$this->select_column[] = $this->table2.'.'.$value['COLUMN_NAME'];
		}
    }
	//Fetch
	var $table = "tbltransactionsprocesspayroll";   
	var $employee_table = "tblemployees";
	var $table2 = "tblbiralphalist";
	var $order_column = array('','tblbiralphalist.last_name','tblbiralphalist.department');

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
	
	function fetchPayrollRegister($year_select){
		$this->db->select( 'tblemployees.*,'. $this->table.'.*, tblfieldpositions.name AS position_name, tblfieldoffices.name AS office_name, tblfielddepartments.department_name, tblfieldlocations.name AS location_name' );  
		$this->db->from($this->table);  
		$this->db->join("tblemployees",$this->table.".employee_id = tblemployees.id","left");
		$this->db->join("tblfieldpositions","tblemployees.position_id = tblfieldpositions.id","left");
		$this->db->join("tblfieldoffices","tblemployees.office_id = tblfieldoffices.id","left");
		$this->db->join("tblfielddepartments","tblemployees.division_id = tblfielddepartments.id","left");
		$this->db->join("tblfieldlocations","tblemployees.location_id = tblfieldlocations.id","left");
		//$this->db->where($this->table.'.wh_tax_amt >','0.00');

		$this->db->where('YEAR(tbltransactionsprocesspayroll.date_created)', $year_select);
		$this->db->where('tblemployees.is_active', 1);
		$this->db->group_by("tbltransactionsprocesspayroll.employee_id");
		$this->db->order_by("tblemployees.last_name ASC");  

		$result = $this->db->get();
		return $result->result_array();
	} 
	function fetchEmployees(){

		$this->db->select("
		DECRYPTER(tblemployees.employee_number,'sunev8clt1234567890', tblemployees.id) as scanning,
		DECRYPTER(tblemployees.employee_id_number,'sunev8clt1234567890',tblemployees.id) as employee_id_number,
		DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id) as fname,
		DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id) as lname,
		DECRYPTER(tblemployees.extension,'sunev8clt1234567890',tblemployees.id) as extension");
		$this->db->from('tblemployees');
		$this->db->join("tblfieldpositions","tblfieldpositions.id = tblemployees.position_id","left");
		$this->db->join("tblfieldoffices","tblemployees.office_id = tblfieldoffices.id","left");
		$this->db->join("tblfielddepartments","tblemployees.division_id = tblfielddepartments.id","left");
		$this->db->join("tblfieldlocations","tblemployees.location_id = tblfieldlocations.id","left");
		
		$result = $this->db->get();
		return $result->result_array();
	}
	//End Fetch
	function getSpecialBonuses($employee_id, $year_select){
		$sql = " SELECT * FROM tbltransactionsbonus WHERE employee_id = ? AND YEAR(date_created) = ? AND bonus_type = '3'";
		$query = $this->db->query($sql,array($employee_id, $year_select));
		$data = $query->result_array();
		return $data;

	}
	function getAllSpecialBonuses($employee_id, $year_select){
		$sql = " SELECT * FROM tbltransactionsbonus WHERE employee_id = ? AND YEAR(date_created) = ?";
		$query = $this->db->query($sql,array($employee_id, $year_select));
		$data = $query->result_array();
		return $data;

	}
	function getWithHoldingTax(){
		$sql = " SELECT * FROM tblwithholdingtaxtable WHERE tax_percentage = ?";
		$query = $this->db->query($sql,array("0.00"));
		$data = $query->result_array();
		return $data;
	}

	function getAllcontribution($employee_id, $year_select){
		$sql = " SELECT * FROM tbltransactionsprocesspayroll WHERE employee_id = ? AND YEAR(date_created) = ?";
		$query = $this->db->query($sql,array($employee_id, $year_select));
		$data = $query->result_array();
		return $data;
	}

	function getPayrollPeriodById($payroll_period_id){
		$sql = " SELECT * FROM tblfieldperiodsettings WHERE is_active = '1' AND id = ?";
		$query = $this->db->query($sql,array($payroll_period_id));
		$data = $query->result_array();
		return $data;
	}
	
	function getSignatories(){
		$sign = $this->db->query('SELECT
		a.*,
		CONCAT(
			DECRYPTER(b.first_name,"sunev8clt1234567890",b.id)," ",
			LEFT(DECRYPTER(b.middle_name,"sunev8clt1234567890",b.id),1),". ",
			DECRYPTER(b.last_name,"sunev8clt1234567890",b.id)," ",			
			DECRYPTER(b.extension,"sunev8clt1234567890",b.id)) as employee_name, b.position_designation, b.division_designation, c.name as position_title, d.department_name as department
		FROM tblfieldsignatories a LEFT JOIN tblemployees b ON a.employee_id = b.id
		LEFT JOIN tblfieldpositions c ON b.position_id = c.id LEFT JOIN tblfielddepartments d ON b.division_id = d.id WHERE a.id IN (1,2,3,4) ORDER BY a.id')->result_array();
		return $sign;
	}

	//Fetch Computed Payrolls
	function getAmortizedAllowances($employee_id,$payroll_period_id){
		$sql = " SELECT *,c.allowance_name FROM tbltransactionspayrollprocessallowances a
				 LEFT JOIN tblemployeesallowances b ON a.allowance_id = b.id
				 LEFT JOIN tblfieldallowances c ON b.allowance_id = c.id
				 WHERE a.is_active = '1' AND b.employee_id = ? AND a.payroll_period_id = ? ";
		$loans = $this->db->query($sql,array($employee_id,$payroll_period_id))->result_array();
		return $loans;
	}
	function getAmortizedOtherEarnings($employee_id,$payroll_period_id){
		$sql = " SELECT * FROM tbltransactionsotherearningsamortization a
				 LEFT JOIN tbltransactionsotherearnings b ON a.other_earning_entry_id = b.id
				 LEFT JOIN tblfieldotherearnings c ON c.id = b.earning_id
				 WHERE a.is_active = '1' AND b.employee_id = ? AND a.payroll_period_id = ? ";
		$loans = $this->db->query($sql,array($employee_id,$payroll_period_id))->result_array();
		return $loans;
	}
	function getAmortizedOtherDeductions($employee_id,$payroll_period_id){
		$sql = " SELECT * FROM tbltransactionsotherdeductionsamortization a
				 LEFT JOIN tbltransactionsotherdeductions b ON a.other_deduction_entry_id = b.id
				 LEFT JOIN tblfieldotherdeductions c ON c.id = b.deduction_id
				 WHERE a.is_active = '1' AND b.employee_id = ? AND a.payroll_period_id = ? ";
		$loans = $this->db->query($sql,array($employee_id,$payroll_period_id))->result_array();
		return $loans;
	}
	function getAmortizedLoans($employee_id,$payroll_period_id){
		$sql = " SELECT a.*,b.*,c.code AS code_sub,c.description AS desc_sub, d.code AS code_loan,d.description AS desc_loan FROM tbltransactionsloansamortization a
				 LEFT JOIN tbltransactionsloans b ON a.loan_entry_id = b.id
				 LEFT JOIN tblfieldloanssub c ON c.id = b.sub_loans_id
				 LEFT JOIN tblfieldloans d ON d.id = c.loan_id
				 WHERE a.is_active = '1' AND b.employee_id = ? AND a.payroll_period_id = ? 
				 ORDER BY d.code ASC";
		$loans = $this->db->query($sql,array($employee_id,$payroll_period_id))->result_array();
		return $loans;
	}

	function payrollinfo($emp_id){
		$helperDao = new HelperDao();
		$data = array();
		$code = "1";
		
		$message = "No data available.";
		$sql = "SELECT mp2_contribution FROM tblemployees where id = ? ";
		$query = $this->db->query($sql,array($emp_id));
		$datas = $query->result_array();
		$data['data'] = $datas;
		// print_r($data['data']); die();
		if(sizeof($datas) > 0){

			return $data;
		}
		
		return false;
	}

	function addBIR($params){
		$helperDao = new HelperDao();
		$code = "1";
		$message = "Failed to add service record.";
		$data = array();

		// foreach ($params["employee_ids"] as $key => $value) {
		// 	//var_dump($params['employee_ids'][$key]);
		// 	($params['employee_id'][$key] == "")? $employee_id = "N/A" : $employee_id = @$params["employee_id"][$key];
		// 	($params['reason_separation_6'][$key] == "")? $reason_separation_6 = "N/A" : $reason_separation_6 = @$params["reason_separation_6"][$key];
		// 	($params['gross_compe_income_7a'][$key] == "")? $gross_compe_income_7a = "0.00" : $gross_compe_income_7a = @$params["gross_compe_income_7a"][$key];
		// 	($params['benefits_paide_7b'][$key] == "")? $benefits_paide_7b = "0.00" : $benefits_paide_7b = @$params["benefits_paide_7b"][$key];
			
		// 	($params['benefits_paide_7c'][$key] == "")? $benefits_paide_7c = "0.00" : $benefits_paide_7c = @$params["benefits_paide_7c"][$key];
		// 	($params['contribution_emps_only_7d'][$key] == "")? $contribution_emps_only_7d = "0.00" : $contribution_emps_only_7d = @$params["contribution_emps_only_7d"][$key];
		// 	($params['non_taxable_compe_income_pres_empr_7f'][$key] == "")? $non_taxable_compe_income_pres_empr_7f = "0.00" : $non_taxable_compe_income_pres_empr_7f = @$params["non_taxable_compe_income_pres_empr_7f"][$key];
		// 	($params['taxable_basic_salary_7g'][$key] == "")? $taxable_basic_salary_7g = "0.00" : $taxable_basic_salary_7g = @$params["taxable_basic_salary_7g"][$key];
		// 	($params['benefits_excess_7h'][$key] == "")? $benefits_excess_7h = "0.00" : $benefits_excess_7h = @$params["benefits_excess_7h"][$key];
		// 	($params['total_taxable_compe_income_pre_empr_7j'][$key] == "")? $total_taxable_compe_income_pre_empr_7j = "0.00" : $total_taxable_compe_income_pre_empr_7j = @$params["seperation_cause"][$key];
		// 	($params['tax_id_number_8'][$key] == "")? $tax_id_number_8 = "N/A" : $tax_id_number_8 = @$params["tax_id_number_8"][$key];
		// 	($params['employment_status_9'][$key] == "")? $employment_status_9 = "N/A" : $employment_status_9 = @$params["employment_status_9"][$key];
		// 	($params['period_employment_from_10a'][$key] == "")? $period_employment_from_10a = "N/A" : $period_employment_from_10a = @$params["seperation_cause"][$key];
		// 	($params['period_employment_to_10b'][$key] == "")? $period_employment_to_10b = "N/A" : $period_employment_to_10b = @$params["period_employment_to_10b"][$key];
		// 	($params['reason_separation_applicable_11'][$key] == "")? $reason_separation_applicable_11 = "N/A" : $reason_separation_applicable_11 = @$params["reason_separation_applicable_11"][$key];
		// 	($params['gross_compe_pre_empr_12a'][$key] == "")? $gross_compe_pre_empr_12a = "0.00" : $gross_compe_pre_empr_12a = @$params["gross_compe_pre_empr_12a"][$key];
		// 	($params['benefits_12b'][$key] == "")? $benefits_12b = "0.00" : $benefits_12b = @$params["benefits_12b"][$key];
		// 	($params['benefits_12c'][$key] == "")? $benefits_12c = "0.00" : $benefits_12c = @$params["benefits_12c"][$key];

		// 	($params['contribution_emps_only_12d'][$key] == "")? $contribution_emps_only_12d = "0.00" : $contribution_emps_only_12d = @$params["contribution_emps_only_12d"][$key];
		// 	($params['below_salaries_12e'][$key] == "")? $below_salaries_12e = "0.00" : $below_salaries_12e = @$params["below_salaries_12e"][$key];
		// 	($params['non_taxable_compe_income_prev_empr_12f'][$key] == "")? $non_taxable_compe_income_prev_empr_12f = "0.00" : $non_taxable_compe_income_prev_empr_12f = @$params["non_taxable_compe_income_prev_empr_12f"][$key];
		// 	($params['pay_12h'][$key] == "")? $pay_12h = "0.00" : $pay_12h = @$params["pay_12h"][$key];
		// 	($params['compensation_12i'][$key] == "")? $compensation_12i = "0.00" : $compensation_12i = @$params["compensation_12i"][$key];
			
		// 	($params['taxable_basic_salary_12g'][$key] == "")? $taxable_basic_salary_12g = "0.00" : $taxable_basic_salary_12g = @$params["taxable_basic_salary_12g"][$key];
		// 	($params['total_taxable_compe_12j'][$key] == "")? $total_taxable_compe_12j = "0.00" : $total_taxable_compe_12j = @$params["total_taxable_compe_12j"][$key];
		// 	($params['total_taxable_compe_income_13'][$key] == "")? $total_taxable_compe_income_13 = "0.00" : $total_taxable_compe_income_13 = @$params["total_taxable_compe_income_13"][$key];
		// 	($params['tax_due_14'][$key] == "")? $tax_due_14 = "0.00" : $tax_due_14 = @$params["tax_due_14"][$key];
		// 	($params['tax_withheld_15a'][$key] == "")? $tax_withheld_15a = "0.00" : $tax_withheld_15a = @$params["tax_withheld_15a"][$key];
		// 	($params['present_employer_15b'][$key] == "")? $present_employer_15b = "0.00" : $present_employer_15b = @$params["present_employer_15b"][$key];
		// 	($params['year_end_adjustment_16a'][$key] == "")? $year_end_adjustment_16a = "0.00" : $year_end_adjustment_16a = @$params["year_end_adjustment_16a"][$key];
		// 	($params['amount_tax_withheld_adjusted_17'][$key] == "")? $amount_tax_withheld_adjusted_17 = "0.00" : $amount_tax_withheld_adjusted_17 = @$params["amount_tax_withheld_adjusted_17"][$key];
		// 	($params['substituted_filing_18'][$key] == "")? $substituted_filing_18 = "N/A" : $substituted_filing_18 = @$params["substituted_filing_18"][$key];


		// 	$row[] = array("employee_id"=>$employee_id, 
		// 	"reason_separation_6" => $reason_separation_6, 
		// 	"gross_compe_income_7a" => $gross_compe_income_7a, 
		// 	"benefits_paide_7b" => $benefits_paide_7b, 
		// 	"benefits_paide_7c" => $benefits_paide_7c, 
		// 	"contribution_emps_only_7d" =>  $contribution_emps_only_7d, 
		// 	"non_taxable_compe_income_pres_empr_7f" => $non_taxable_compe_income_pres_empr_7f, 
		// 	"taxable_basic_salary_7g" => $taxable_basic_salary_7g, 
		// 	"benefits_excess_7h" => $benefits_excess_7h, 
		// 	"total_taxable_compe_income_pre_empr_7j" => $total_taxable_compe_income_pre_empr_7j,
		// 	"tax_id_number_8" => $tax_id_number_8,
		// 	"employment_status_9" =>$employment_status_9,
		// 	"period_employment_from_10a" =>$period_employment_from_10a,
		// 	"period_employment_to_10b" =>$period_employment_to_10b,
		// 	"reason_separation_applicable_11" =>$reason_separation_applicable_11,
		// 	"gross_compe_pre_empr_12a" =>$gross_compe_pre_empr_12a,
		// 	"benefits_12b" =>$benefits_12b,
		// 	"benefits_12c" =>$benefits_12c,
		// 	"contribution_emps_only_12d" =>$contribution_emps_only_12d,
		// 	"below_salaries_12e" =>$below_salaries_12e,
		// 	"non_taxable_compe_income_prev_empr_12f" =>$non_taxable_compe_income_prev_empr_12f,
		// 	"pay_12h" =>$pay_12h,
		// 	"compensation_12i" =>$compensation_12i,
		// 	"taxable_basic_salary_12g" =>$taxable_basic_salary_12g,
		// 	"total_taxable_compe_12j" =>$total_taxable_compe_12j,
		// 	"total_taxable_compe_income_13" =>$total_taxable_compe_income_13,
		// 	"tax_due_14" =>$tax_due_14,
		// 	"tax_withheld_15a" =>$tax_withheld_15a,
		// 	"present_employer_15b" =>$present_employer_15b,
		// 	"year_end_adjustment_16a" =>$year_end_adjustment_16a,
		// 	"amount_tax_withheld_adjusted_17" =>$amount_tax_withheld_adjusted_17,
		// 	"substituted_filing_18" =>$substituted_filing_18,
		// 	"date_year" => date('Y')
		// );

		//  }
		// var_dump($row);

		$this->db->insert_batch('tblbiralphalist', $params);

		// $data = array(
		// 	'date_year' => date('Y'),
		// 	'description' => 'BIR alphalist Year('.date('Y').')'
		// 	);

		// $this->db->insert('tblbiralphalistlist', $data);
		
		if($this->db->trans_status() === TRUE){
			$code = "0";
			$message = "Successfully added BIR alphalist.";
			$helperDao->auditTrails(Helper::get('userid'),$message);
			$this->ModelResponse($code, $message);
			$this->db->trans_commit();
			return true;		
		} else {
			$this->db->trans_rollback();
			$helperDao->auditTrails(Helper::get('userid'),$message);
			$this->ModelResponse($code, $message);
		}
	} 
	function fetchBirAlpalist ($id){
		$this->db->select('tblemployees.*,'. $this->table2.'.*');
		$this->db->from('tblbiralphalist');
		$this->db->join("tblemployees","tblbiralphalist.employee_id = tblemployees.id","left");
		$this->db->where('tblbiralphalist.id', $id);
		

		$result = $this->db->get();
		return $result->result_array();
	}
	function fetchBirAlpalistAll ($date_year){
		$this->db->select('*');
		$this->db->from('tblbiralphalist');
		//$this->db->join("tblemployees","tblbiralphalist.employee_id = tblemployees.id","left");
		$this->db->where('tblbiralphalist.date_year', $date_year);
		

		$result = $this->db->get();
		return $result->result_array();
	}

	function editBirAlpalist($params){
		$helperDao = new HelperDao();
		$code = "1";
		$message = "Failed to add service record.";
		$data = array();

		//var_dump($params);
			$id = $params["id"];
			$employee_id = $params["employee_id"];

			($params['reason_separation_6'] == "")? $reason_separation_6 = "" : $reason_separation_6 = @$params["reason_separation_6"];
			($params['gross_compe_income_7a'] == "")? $gross_compe_income_7a = "0.00" : $gross_compe_income_7a = @$params["gross_compe_income_7a"];
			($params['benefits_paide_7b'] == "")? $benefits_paide_7b = "0.00" : $benefits_paide_7b = @$params["benefits_paide_7b"];
			
			($params['benefits_paide_7c'] == "")? $benefits_paide_7c = "0.00" : $benefits_paide_7c = @$params["benefits_paide_7c"];
			($params['contribution_emps_only_7d'] == "")? $contribution_emps_only_7d = "0.00" : $contribution_emps_only_7d = @$params["contribution_emps_only_7d"];
			($params['salary_below_7e'] == "")? $salary_below_7e = "0.00" : $salary_below_7e = @$params["salary_below_7e"];
			($params['non_taxable_compe_income_pres_empr_7f'] == "")? $non_taxable_compe_income_pres_empr_7f = "0.00" : $non_taxable_compe_income_pres_empr_7f = @$params["non_taxable_compe_income_pres_empr_7f"];
			($params['taxable_basic_salary_7g'] == "")? $taxable_basic_salary_7g = "0.00" : $taxable_basic_salary_7g = @$params["taxable_basic_salary_7g"];
			($params['benefits_excess_7h'] == "")? $benefits_excess_7h = "0.00" : $benefits_excess_7h = @$params["benefits_excess_7h"];
			($params['total_taxable_compe_income_pre_empr_7j'] == NULL)? $total_taxable_compe_income_pre_empr_7j = "0.00" : $total_taxable_compe_income_pre_empr_7j = @$params["total_taxable_compe_income_pre_empr_7j"];
			($params['tax_id_number_8'] == "")? $tax_id_number_8 = "" : $tax_id_number_8 = @$params["tax_id_number_8"];
			($params['employment_status_9'] == "")? $employment_status_9 = "" : $employment_status_9 = @$params["employment_status_9"];
			($params['period_employment_from_10a'] == "")? $period_employment_from_10a = "" : $period_employment_from_10a = @$params["period_employment_from_10a"];
			($params['period_employment_to_10b'] == "")? $period_employment_to_10b = "" : $period_employment_to_10b = @$params["period_employment_to_10b"];
			($params['reason_separation_applicable_11'] == "")? $reason_separation_applicable_11 = "" : $reason_separation_applicable_11 = @$params["reason_separation_applicable_11"];
			($params['gross_compe_pre_empr_12a'] == "")? $gross_compe_pre_empr_12a = "0.00" : $gross_compe_pre_empr_12a = @$params["gross_compe_pre_empr_12a"];
			($params['benefits_12b'] == "")? $benefits_12b = "0.00" : $benefits_12b = @$params["benefits_12b"];
			($params['benefits_12c'] == "")? $benefits_12c = "0.00" : $benefits_12c = @$params["benefits_12c"];

			($params['contribution_emps_only_12d'] == "")? $contribution_emps_only_12d = "0.00" : $contribution_emps_only_12d = @$params["contribution_emps_only_12d"];
			($params['below_salaries_12e'] == "")? $below_salaries_12e = "0.00" : $below_salaries_12e = @$params["below_salaries_12e"];
			($params['non_taxable_compe_income_prev_empr_12f'] == "")? $non_taxable_compe_income_prev_empr_12f = "0.00" : $non_taxable_compe_income_prev_empr_12f = @$params["non_taxable_compe_income_prev_empr_12f"];
			($params['pay_12h'] == "")? $pay_12h = "0.00" : $pay_12h = @$params["pay_12h"];
			($params['compensation_12i'] == "")? $compensation_12i = "0.00" : $compensation_12i = @$params["compensation_12i"];
			
			($params['taxable_basic_salary_12g'] == "")? $taxable_basic_salary_12g = "0.00" : $taxable_basic_salary_12g = @$params["taxable_basic_salary_12g"];
			($params['total_taxable_compe_12j'] == "")? $total_taxable_compe_12j = "0.00" : $total_taxable_compe_12j = @$params["total_taxable_compe_12j"];
			($params['total_taxable_compe_income_13'] == "")? $total_taxable_compe_income_13 = "0.00" : $total_taxable_compe_income_13 = @$params["total_taxable_compe_income_13"];
			($params['tax_due_14'] == "")? $tax_due_14 = "0.00" : $tax_due_14 = @$params["tax_due_14"];
			($params['tax_withheld_15a'] == "")? $tax_withheld_15a = "0.00" : $tax_withheld_15a = @$params["tax_withheld_15a"];
			($params['present_employer_15b'] == "")? $present_employer_15b = "0.00" : $present_employer_15b = @$params["present_employer_15b"];
			($params['year_end_adjustment_16a'] == "" || $params['year_end_adjustment_16a'] == NULL)? $year_end_adjustment_16a = "0.00" : $year_end_adjustment_16a = @$params["year_end_adjustment_16a"];
			($params['year_end_adjustment_16b'] == NULL)? $year_end_adjustment_16b = "0.00" : $year_end_adjustment_16b = @$params["year_end_adjustment_16b"];
			($params['amount_tax_withheld_adjusted_17'] == "")? $amount_tax_withheld_adjusted_17 = "0.00" : $amount_tax_withheld_adjusted_17 = @$params["amount_tax_withheld_adjusted_17"];
			($params['substituted_filing_18'] == "")? $substituted_filing_18 = "" : $substituted_filing_18 = @$params["substituted_filing_18"];
			//var_dump(($params['year_end_adjustment_16b'] == NULL)? $year_end_adjustment_16b = "0.00" : $year_end_adjustment_16b = @$params["year_end_adjustment_16b"]);
			
			$row = array(
			"reason_separation_6" => $reason_separation_6, 
			"gross_compe_income_7a" => $gross_compe_income_7a, 
			"benefits_paide_7b" => $benefits_paide_7b, 
			"benefits_paide_7c" => $benefits_paide_7c, 
			"contribution_emps_only_7d" =>  $contribution_emps_only_7d, 
			"salary_below_7e" =>  $salary_below_7e, 
			"non_taxable_compe_income_pres_empr_7f" => $non_taxable_compe_income_pres_empr_7f, 
			"taxable_basic_salary_7g" => $taxable_basic_salary_7g, 
			"benefits_excess_7h" => $benefits_excess_7h, 
			"total_taxable_compe_income_pre_empr_7j" => $total_taxable_compe_income_pre_empr_7j,
			"tax_id_number_8" => $tax_id_number_8,
			"employment_status_9" =>$employment_status_9,
			"period_employment_from_10a" =>$period_employment_from_10a,
			"period_employment_to_10b" =>$period_employment_to_10b,
			"reason_separation_applicable_11" =>$reason_separation_applicable_11,
			"gross_compe_pre_empr_12a" =>$gross_compe_pre_empr_12a,
			"benefits_12b" =>$benefits_12b,
			"benefits_12c" =>$benefits_12c,
			"contribution_emps_only_12d" =>$contribution_emps_only_12d,
			"below_salaries_12e" =>$below_salaries_12e,
			"non_taxable_compe_income_prev_empr_12f" =>$non_taxable_compe_income_prev_empr_12f,
			"pay_12h" =>$pay_12h,
			"compensation_12i" =>$compensation_12i,
			"taxable_basic_salary_12g" =>$taxable_basic_salary_12g,
			"total_taxable_compe_12j" =>$total_taxable_compe_12j,
			"total_taxable_compe_income_13" =>$total_taxable_compe_income_13,
			"tax_due_14" =>$tax_due_14,
			"tax_withheld_15a" =>$tax_withheld_15a,
			"present_employer_15b" =>$present_employer_15b,
			"year_end_adjustment_16a" =>$year_end_adjustment_16a,
			"year_end_adjustment_16b" =>$year_end_adjustment_16b,
			"amount_tax_withheld_adjusted_17" =>$amount_tax_withheld_adjusted_17,
			"substituted_filing_18" =>$substituted_filing_18
		);

		//var_dump($row);
		$this->db->where('id', $id);
		$this->db->update('tblbiralphalist', $row);
		
		if($this->db->trans_status() === TRUE){
			$code = "0";
			$message = "Successfully updated BIR alphalist.";
			$helperDao->auditTrails(Helper::get('userid'),$message);
			$this->ModelResponse($code, $message);
			$this->db->trans_commit();
			return true;		
		} else {
			$this->db->trans_rollback();
			$helperDao->auditTrails(Helper::get('userid'),$message);
			$this->ModelResponse($code, $message);
		}
		
	}
	 function getPayroll($employee_id, $year_date){
		$sql = " SELECT * FROM tbltransactionsprocesspayroll WHERE employee_id = ? AND YEAR(date_created) = ?";
		$query = $this->db->query($sql,array($employee_id, $year_date));
		$data = $query->result_array();
		return $data;
	 }
	 function getbonuses($employee_id, $bonus_type, $year_date ){
		$sql = " SELECT * FROM tbltransactionsbonus WHERE employee_id = ? AND bonus_type = ? AND YEAR(date_created) = ? ";
		$query = $this->db->query($sql,array($employee_id, $bonus_type, $year_date));
		$data = $query->result_array();
		return $data;
	 }
	 function employeeInfo($employee_id){
		$sql = " SELECT * FROM tblemployees WHERE id = ?";
		$query = $this->db->query($sql,array($employee_id));
		$data = $query->result_array();
		return $data;
	 }
	 function position($position_id){
		$sql = " SELECT * FROM tblfieldpositions WHERE id = ?";
		$query = $this->db->query($sql,array($position_id));
		$data = $query->result_array();
		return $data;
	 }
}

?>
