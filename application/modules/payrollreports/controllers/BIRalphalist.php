<?php
class BIRalphalist extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('BIRalphalistCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::PAYROLL_REGISTER_SUMMARY_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewBirAlphalist";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/birAlphalistlist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('BIR Alphalist');
			Helper::setMenu('templates/menu_template');
			Helper::setView('birAlphalist',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}

	public function addBIRform(){

		$formData = array();
		$result = array();
		$result['key'] = 'addBir';
		if (!$this->input->is_ajax_request()){
		   show_404();
		}else{
				$ret =  new BIRalphalistCollection();
				$formData['payroll']  = $ret->fetchPayrollRegister(date('Y'));
				//var_dump($formData['payroll']);
				$formData['key'] = $result['key'];
				$result['form'] = $this->load->view('helpers/biralphalistaddform.php', $formData, TRUE);
			}
		   echo json_encode($result);
	}

		public function addBir(){
			$result = array();
			$page = 'addBir';
			if (!$this->input->is_ajax_request()) {
			   show_404();
			} else {
				$employee_id = "";
				$reason_separation_6 = "";
				$gross_compe_income_7a = "";
				$benefits_paide_7b = "";
				$benefits_paide_7c = "";
				$contribution_emps_only_7d = "";
				$salary_below_7e = "";
				$non_taxable_compe_income_pres_empr_7f = "";
				$taxable_basic_salary_7g = "";
				$benefits_excess_7h = "";
				$total_taxable_compe_income_pre_empr_7j = "";
				$tax_id_number_8 = "";
				$employment_status_9 = "";
				$period_employment_from_10a = "";
				$period_employment_to_10b = "";
				$reason_separation_applicable_11 = "";
				$gross_compe_pre_empr_12a = "";
				$benefits_12b = "";
				$benefits_12c = "";
				$contribution_emps_only_12d = "";
				$below_salaries_12e = "";
				$non_taxable_compe_income_prev_empr_12f = "";
				$pay_12h = "";
				$compensation_12i = "";
				$taxable_basic_salary_12g = "";
				$total_taxable_compe_12j = "";
				$total_taxable_compe_income_13 = "";
				$tax_due_14 = "";
				$tax_withheld_15a = "";
				$present_employer_15b = "";
				$year_end_adjustment_16a = "";
				$amount_tax_withheld_adjusted_17 = "";
				$substituted_filing_18 = "";

				$sss_amt = 0;
				$gsis_amt = 0; 
				$pagibig_amt = 0;
				$philhealth_amt = 0;
				$union_dues_amt = 0;
				$CNA_bonus_amt = 0;
				$clothing_bonus_amt = 0;
				$cash_bonus_atm = 0;
				$mid_year_bonus_atm = 0;
				$end_year_bonus_atm = 0;
				$contact_number = "";
				$address = "";
				$birthday = "";
				$status = "";
				$start_date = "";
				$end_date = "";
				$abst_amt = 0;
				$basic_salary = 0;
				$tax_amt = 0;
					foreach($_POST['employee_ids'] as $k => $v){
						$ret =  new BIRalphalistCollection();
						
						$getPayroll = $ret->getPayroll($_POST['employee_ids'][$k], date('Y'));

							for($x = 0 ; sizeof($getPayroll) > $x ; $x++){
								$sss_amt += $getPayroll[$x]['sss_amt'];
								$gsis_amt += $getPayroll[$x]['sss_gsis_amt'];
								$pagibig_amt += $getPayroll[$x]['pagibig_amt'];
								$philhealth_amt += $getPayroll[$x]['philhealth_amt'];
								$union_dues_amt += $getPayroll[$x]['union_dues_amt'];
								$basic_salary += $getPayroll[$x]['basic_pay'];
								$abst_amt += $getPayroll[$x]['total_tardiness_amt'];
								$tax_amt += $getPayroll[$x]['wh_tax_amt'];
							}

						$value_7d = $sss_amt + $gsis_amt + $pagibig_amt + $philhealth_amt + $union_dues_amt;
						$value_7g = $basic_salary - $abst_amt;
						$getBonusesCNA = $ret->getbonuses($_POST['employee_ids'][$k], 9, date('Y'));

							if(sizeof($getBonusesCNA) > 0){
								$CNA_bonus_amt = $getBonusesCNA[0]['amount'];
							}

						$getBonusesClothing = $ret->getbonuses($_POST['employee_ids'][$k], 10, date('Y'));
							if(sizeof($getBonusesClothing) > 0){
								$clothing_bonus_amt = $getBonusesCNA[0]['amount'];
							}
						$getBonusesClothing = $ret->getbonuses($_POST['employee_ids'][$k], 11, date('Y'));
							if(sizeof($getBonusesClothing) > 0){
								$cash_bonus_atm = $getBonusesCNA[0]['amount'];
							}
							
						

						$getBonusesClothing = $ret->getbonuses($_POST['employee_ids'][$k], 3, date('Y'));
							if(sizeof($getBonusesClothing) > 0){
								$mid_year_bonus_atm = $getBonusesCNA[0]['amount'];
							}
						$getBonusesClothing = $ret->getbonuses($_POST['employee_ids'][$k], 4, date('Y'));
							if(sizeof($getBonusesClothing) > 0){
								$end_year_bonus_atm = $getBonusesCNA[0]['amount'];
							}

						
						$deminimis_7c = $CNA_bonus_amt + $clothing_bonus_amt + $cash_bonus_atm;

						$value_7c = 0;
						$deminimis_excess = 0;
							if($deminimis_7c > 16000){
								$value_7c = 16000;
								$deminimis_excess = $deminimis_7c - 16000; 
							}else{
								$value_7c = $deminimis_7c;
							}

							if($deminimis_excess <= 0 ){

								$benifits_7b = $mid_year_bonus_atm + $end_year_bonus_atm;
							}else{
								$benifits_7b = $mid_year_bonus_atm + $end_year_bonus_atm + $deminimis_excess;
							}

						$value_7b = 0;
						$benifit_excess = 0;

							if($benifits_7b > 90000){
								$value_7b = 90000;
								$benifit_excess = $benifits_7b - 90000;
							}else{
								$value_7b = $benifits_7b;
							}

						$value_7h = 0;	
							if($benifit_excess > 0 ){
								$value_7h = $benifit_excess;
							}
						
						$employeeInfo = $ret->employeeInfo($_POST['employee_ids'][$k]);
							//var_dump($getPayroll);
								$contact_number = $employeeInfo[0]['contact_number'];
								$birthday = $employeeInfo[0]['union_dues_amt'];

							if($employeeInfo[0]['permanent_street'] != "N/A" && $employeeInfo[0]['permanent_street'] != NULL ){
								$address .= " ".$employeeInfo[0]['permanent_street'];
							}
							if($employeeInfo[0]['permanent_barangay'] != "N/A" && $employeeInfo[0]['permanent_barangay'] != NULL ){
								$address .= " ".$employeeInfo[0]['permanent_barangay'];
							}
							if($employeeInfo[0]['permanent_municipality'] != "N/A" && $employeeInfo[0]['permanent_municipality'] != NULL ){
								$address .= " ".$employeeInfo[0]['permanent_municipality'];
							}
						$value_7e = 0;
						$value_7i = 0;
						$value_7f = $value_7b + $value_7c + $value_7d + $value_7e;
						$value_7j = $value_7g + $value_7h + $value_7i;
						$value_7a = $value_7f + $value_7j;
						$status = $employeeInfo[0]['employment_status'];
						$start_date = $employeeInfo[0]['start_date'];
						$end_date = $employeeInfo[0]['end_date'];
						$first_name = $this->Helper->decrypt((string)$employeeInfo[0]['first_name'],$employeeInfo[0]['id']);
						$middle_name = $this->Helper->decrypt((string)$employeeInfo[0]['middle_name'],$employeeInfo[0]['id']);
						$last_name = $this->Helper->decrypt((string)$employeeInfo[0]['last_name'],$employeeInfo[0]['id']);
						$extension = $this->Helper->decrypt((string)$employeeInfo[0]['extension'],$employeeInfo[0]['id']);
						
						$position = $ret->position($employeeInfo[0]['position_id']);
						$position_name = $position[0]['name'];
						//var_dump($department_name);
						
						$row[] = array("employee_id"=> $_POST['employee_ids'][$k],
						"first_name" => $first_name, 
						"last_name" => $last_name, 
						"middle_name" => $middle_name, 
						"extension" => $extension, 
						"position" => $position_name,
						"status" => $status, 
						"start_date" => $start_date, 
						"end_date" => $end_date, 
						"gross_compe_income_7a" => $value_7a, 
						"benefits_paide_7b" =>$value_7b, 
						"benefits_paide_7c" => $value_7c, 
						"contribution_emps_only_7d" =>  $value_7d ,
						"salary_below_7e"  =>  $value_7e ,
						"non_taxable_compe_income_pres_empr_7f" => $value_7f, 
						"taxable_basic_salary_7g" => $value_7g, 
						"benefits_excess_7h" => $value_7h, 
						"total_taxable_compe_income_pre_empr_7j" => $value_7j,
						"tax_id_number_8" => 'N/A',
						"employment_status_9" => 'R',
						"period_employment_from_10a" => 'N/A',
						"period_employment_to_10b" => 'N/A',
						"reason_separation_applicable_11" => 'N/A',
						"gross_compe_pre_empr_12a" => '0.00',
						"benefits_12b" => '0.00',
						"benefits_12c" => '0.00',
						"contribution_emps_only_12d" => '0.00',
						"below_salaries_12e" => '0.00',
						"non_taxable_compe_income_prev_empr_12f" => '0.00',
						"pay_12h" => '0.00',
						"compensation_12i" => '0.00',
						"taxable_basic_salary_12g" => '0.00',
						"total_taxable_compe_12j" => '0.00',
						"total_taxable_compe_income_13" => '0.00',
						"tax_due_14" => $tax_amt,
						"tax_withheld_15a" => '0.00',
						"present_employer_15b" => '0.00',
						"year_end_adjustment_16a" => '0.00',
						"year_end_adjustment_16b" => '0.00',
						"amount_tax_withheld_adjusted_17" => '0.00',
						"substituted_filing_18" => '0.00', 
						"contact_number" => $contact_number,
						"address" => $address,
						"birthday" => $birthday,
						"date_year" => date('Y')
					);

					}
	
					$ret =  new BIRalphalistCollection();
					if($ret->addBIR($row)) {
						$res = new ModelResponse($ret->getCode(), $ret->getMessage());
					} else {
						$res = new ModelResponse($ret->getCode(), $ret->getMessage());
					}
					$result = json_decode($res,true);
				

				$result['key'] = $page;
			}
			echo json_encode($result);
		} 


	function fetchRows(){ 
        $date_year = (isset($_GET['dateYear']))?@$_GET['dateYear']:"";
		$fetch_data = $this->BIRalphalistCollection->make_datatables($date_year);
        $data = array(); 
		//var_dump($fetch_data); 
        foreach($fetch_data as $k => $row) {  
        	$buttons = "";
        	$buttons_data = "";

        	
        	$first_name = $row->first_name;
			$middle_name = $row->middle_name;
			$last_name = $row->last_name;
			$extension = $row->extension;
			$position_name = $row->department;
			if($extension != ""){ 

				$full_name2 = $last_name.' '.$extension.', '.$first_name.' '.$middle_name;
			}else{

				$full_name2 =  $last_name.', '.$first_name.' '.$middle_name;
			};

            $sub_array = array();    
			foreach($row as $k1=>$v1)
			$buttons_data .= ' data-'.$k1.'="'.$v1.'" '; 
					
			$buttons .= ' <a class="viewBirAlphalist" data-id="'.$row->id.'" style="text-decoration: none;" '
						. ' href="'. base_url().$this->uri->segment(1).'/BIRalphalist/viewBirAlphalist" > '
						. ' <button class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Alphalist">'
						. ' <i class="material-icons">remove_red_eye</i> '
						. ' </button> '
						. ' </a> ';
		    // $buttons .= ' <a class="viewBirAlphalistdat" data-id="'.$row->id.'" style="text-decoration: none;" '
			// 			. ' href="'. base_url().$this->uri->segment(1).'/BIRalphalist/viewBirAlphalistdat" > '
			// 			. ' <button class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Alphalist DAT file">'
			// 			. ' <i class="material-icons">attach_file</i> '
			// 			. ' </button> '
			// 			. ' </a> ';
			$buttons .= ' <a class="editBirAlpalistform" data-id="'.$row->id.'" style="text-decoration: none;" '
						. ' href="'. base_url().$this->uri->segment(1).'/BIRalphalist/editBirAlpalistform" > '
						. ' <button class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Update Alphalist">'
						. ' <i class="material-icons">edit</i> '
						. ' </button>'
						. ' </a> ';
			$sub_array[] = $buttons;
			$sub_array[] = $full_name2;

            
            $sub_array[] = $position_name; 
    
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>     $this->BIRalphalistCollection->get_all_data(),  
            "recordsFiltered"     	=>     $this->BIRalphalistCollection->get_filtered_data($date_year),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
	}

	public function viewBirAlphalistALL(){

		$formData = array();
		$result = array();
		$result['key'] = 'viewBirAlphalistALL';
		if (!$this->input->is_ajax_request()){
		   show_404();
		}else{
				$ret =  new BIRalphalistCollection();
				$date_year = isset($_POST['date_year'])?$_POST['date_year']:"";
				$formData['date_year'] = $date_year;
				$formData['payroll']  = $ret->fetchBirAlpalistAll($date_year);
				//var_dump($id);
				$formData['key'] = $result['key'];
				$result['form'] = $this->load->view('helpers/biralphalist.php', $formData, TRUE);
			}
		   echo json_encode($result);
	}
	public function viewBirAlphalist(){

		$formData = array();
		$result = array(); 
		$result['key'] = 'viewBirAlphalist';
		if (!$this->input->is_ajax_request()){
		   show_404();
		}else{
				$ret =  new BIRalphalistCollection();
				$id = isset($_POST['id'])?$_POST['id']:"";
				//var_dump($id);
				$formData['date_year'] = $id;
				$formData['payroll']  = $ret->fetchBirAlpalist($id);
				//var_dump($id);
				$formData['key'] = $result['key'];
				$result['form'] = $this->load->view('helpers/biralphalist.php', $formData, TRUE);
			}
		   echo json_encode($result);
	}
	public function viewBirAlphalistdat(){

		$formData = array();
		$result = array();
		$result['key'] = 'viewBirAlphalistdat';
		if (!$this->input->is_ajax_request()){
		   show_404();
		}else{
				$ret =  new BIRalphalistCollection();
				$date_year = isset($_POST['date_year'])?$_POST['date_year']:"";
				$formData['date_year'] = $date_year;
				$formData['payroll']  = $ret->fetchBirAlpalistAll($date_year);
				//var_dump($formData['payroll']);
				$formData['key'] = $result['key'];
				$result['form'] = $this->load->view('helpers/biralphalistdatfile.php', $formData, TRUE);
			}
		   echo json_encode($result);
	}

	public function editBirAlpalistform(){
		$formData = array();
		$result = array();
		$result['key'] = 'editBirAlpalist';
		if (!$this->input->is_ajax_request()){
		   show_404();
		}else{
				$id = isset($_POST['id'])?$_POST['id']:"";
				$ret =  new BIRalphalistCollection();
				$formData['payroll']  = $ret->fetchBirAlpalist($id);
				//var_dump($formData['payroll']);
				$formData['key'] = $result['key'];
				$result['form'] = $this->load->view('helpers/biralphalisteditform.php', $formData, TRUE);
			}
		   echo json_encode($result);
	}

	public function editBirAlpalist(){
		$result = array();
		$page = 'editBirAlpalist';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {

			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				
				foreach ($this->input->post() as $k => $v) {
						$post_data[$k] = $this->input->post($k,true);
				}
				//var_dump($post_data).die();

				$ret =  new BIRalphalistCollection();
				if($ret->editBirAlpalist($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			
			} else {
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}


}

?>
 