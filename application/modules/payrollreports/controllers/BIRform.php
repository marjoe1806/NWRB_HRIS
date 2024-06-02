<?php

use setasign\Fpdi\Fpdi;

require('assets/plugins/setasign/fpdf.php');
require('assets/plugins/setasign/Fpdi/src/autoload.php');

class BIRform extends MX_Controller {

	

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('BIRformCollection');
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
		$viewData['table'] = $this->load->view("helpers/birformlist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('2316 Form');
			Helper::setMenu('templates/menu_template');
			Helper::setView('birform',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}

	function fetchRows(){  
		$date_year = (isset($_GET['dateYear']))?@$_GET['dateYear']:"";
		$fetch_data = $this->BIRformCollection->make_datatables($date_year);
		$data = array(); 
		//var_dump($fetch_data); 
		foreach($fetch_data as $k => $row) {  
			$buttons = "";
			$buttons_data = "";
			$first_name = $row->first_name;
			$middle_name =$row->middle_name;
			$last_name = $row->last_name;
			$extension = $row->extension;
			//$fullname = $row->fullname;
			$position_name = $row->department;
			if($extension != ""){ 

				$full_name2 = $last_name.' '.$extension.', '.$first_name.' '.$middle_name;
			}else{

				$full_name2 =  $row->last_name.', '.$row->first_name.' '.$row->middle_name;
			};

			$sub_array = array();    
			foreach($row as $k1=>$v1)
			$buttons_data .= ' data-'.$k1.'="'.$v1.'" '; 
					
			$buttons .= ' <a id="" ' 
							. ' class="viewbirForm" target="_blank" style="text-decoration: none;" '
							. ' href="'. base_url().'payrollreports/BIRform/birFormPdfView/'.$row->id.'" '
							. $buttons_data
							. ' > '
							. ' <button class="btn btn-warning btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="2316 form">'
							. ' <i class="material-icons">attach_file</i> '
							. ' </button> '
							. ' </a> ';
			$sub_array[] = $buttons;
			$sub_array[] = $full_name2;

			$sub_array[] = $position_name; 
	
			$data[] = $sub_array;  
		}  
		$output = array(  
			"draw"                  =>     intval($_POST["draw"]),  
			"recordsTotal"          =>     $this->BIRformCollection->get_all_data(),  
			"recordsFiltered"     	=>     $this->BIRformCollection->get_filtered_data($date_year),  
			"data"                  =>     $data  
		);  
        echo json_encode($output);  
    }
 
	public function viewbirForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewbirForm';
		if (!$this->input->is_ajax_request()){
		   show_404();
		}else{
			$employee_id = isset($_POST['employee_id'])?$_POST['employee_id']:"";
			// $date_period = isset($_POST['date_period'])?$_POST['date_period']:"";
			$formData['employee_id'] = $employee_id;
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('helpers/birformform.php', $formData, TRUE);
		}
		echo json_encode($result);

	}
	public function birFormPdfView($employee_id){
		
		$ret =  new BIRformCollection(); 
		
		$payroll = $ret->getPayroll($employee_id);
		//var_dump($payroll[0]['code']);
		$year = $payroll[0]['date_year'];

		if(sizeof($payroll) <= 0){

			echo '<script>
			confirm("No available data")
			</script>';
		
			
		}else{
		

			$pdf = new Fpdi('P','mm','A4');
			$pdf->AddPage();
			// set the source file
			$pdf->setSourceFile('assets/plugins/setasign/bir-form.pdf');
			// import page 1
			$tplIdx = $pdf->importPage(1);
			// use the imported page and place it at position 10,10 with a width of 100 mm
			$pdf->useTemplate($tplIdx, 4, 0, 199);
			
			// now write some text above the imported page
			
			$pdf->SetTextColor(0, 0, 0);
			if($payroll[0]['extension'] != ""){
				$fullname = $payroll[0]['last_name'] .' '. $payroll[0]['extension'] .', '.$payroll[0]['first_name'] .' '.$payroll[0]['middle_name'];
			}else{
				$fullname = $payroll[0]['last_name'].', '.$payroll[0]['first_name'] .' '.$payroll[0]['middle_name'];
			}
			
		
			//var_dump($last_name);
			$employee_address = "";
			//$payroll[0]['house_number'].' '. $payroll[0]['street'].' '.$payroll[0]['village'].' '.$payroll[0]['barangay'].' '.$payroll[0]['municipality'].' '.$payroll[0]['province'];
			// $payroll[0]['permanent_zip_code']
			$zip_code = "";
			//$payroll[0]['permanent_zip_code'];
			if($payroll[0]['birthday'] != ""){
				$birthday = explode('/', $payroll[0]['birthday']);
				$birthday_month = $birthday[0];
				$birthday_day = $birthday[1];
				$birthday_year = $birthday[2];
			}


			$contact_number = $payroll[0]['contact_number'];
			$tin_number = "000-795-636-0000";
			//$payroll[0]['tin'];
			$pre_tin_number = $payroll[0]['tax_id_number_8']; 
			$period_employment_from_10a = date("m",strtotime($payroll[0]['period_employment_from_10a']));
			$period_employment_to_10b = date("m",strtotime($payroll[0]['period_employment_to_10b']));
			//var_dump($period_employment_from_10a);
			$benefits_34 = "";
			$benefits_excess_7h = "";
			$benefits_35 = "";
			$contribution_36 = "";
			$non_taxable_38  = "";
			$basic_salary_39 = "";
			$gross_compe_income_19 = "";
			$non_taxable_compe_20 = "";
			$total_taxable_compe_income_pre_empr_7j = "";
			$total_taxable_compe_12j = "";
			$tax_due_14 = "";
			$tax_withheld_15a = "";
			$present_employer_15b = "";
			$year_end_adjustment_16a = "";
			
			$signatories_a = $ret->getSignatoriesA('2316 form signatories');
			//var_dump($signatories2);
			//$signatories = "ATTY. ARCHIE EDSEL C. ASUNCION";
			
			
			if($payroll[0]['benefits_excess_7h'] != "N/A" && $payroll[0]['benefits_excess_7h'] != NULL ){
				$benefits_excess_7h = $payroll[0]['benefits_excess_7h'];
			}
			if($payroll[0]['benefits_paide_7b'] != "N/A" && $payroll[0]['benefits_paide_7b'] != NULL ){
				$benefits_34 = $payroll[0]['benefits_paide_7b'];
			}
			if($payroll[0]['benefits_paide_7b'] != "N/A" && $payroll[0]['benefits_paide_7b'] != NULL ){
				$benefits_35 = $payroll[0]['benefits_paide_7c'];
			}
			if($payroll[0]['contribution_emps_only_7d'] != "N/A" && $payroll[0]['contribution_emps_only_7d'] != NULL ){
				$contribution_36 = $payroll[0]['contribution_emps_only_7d'];
			}
			if($payroll[0]['non_taxable_compe_income_pres_empr_7f'] != "N/A" && $payroll[0]['non_taxable_compe_income_pres_empr_7f'] != NULL ){
				$non_taxable_38 = $payroll[0]['non_taxable_compe_income_pres_empr_7f'];
			}
			if($payroll[0]['taxable_basic_salary_7g'] != "N/A" && $payroll[0]['taxable_basic_salary_7g'] != NULL ){
				$basic_salary_39 = $payroll[0]['taxable_basic_salary_7g'];
			}
			if($payroll[0]['gross_compe_income_7a'] != "N/A" && $payroll[0]['gross_compe_income_7a'] != NULL ){
				$gross_compe_income_19 = $payroll[0]['gross_compe_income_7a'];
			}
			if($payroll[0]['non_taxable_compe_income_pres_empr_7f'] != "N/A" && $payroll[0]['non_taxable_compe_income_pres_empr_7f'] != NULL ){
				$non_taxable_compe_20 = $payroll[0]['non_taxable_compe_income_pres_empr_7f'];
			}
			if($payroll[0]['total_taxable_compe_income_pre_empr_7j'] != "N/A" && $payroll[0]['total_taxable_compe_income_pre_empr_7j'] != NULL ){
				$total_taxable_compe_income_pre_empr_7j = $payroll[0]['total_taxable_compe_income_pre_empr_7j'];
			}
			if($payroll[0]['total_taxable_compe_12j'] != "N/A" && $payroll[0]['total_taxable_compe_12j'] != NULL ){
				$total_taxable_compe_12j = $payroll[0]['total_taxable_compe_12j'];
			}
			if($payroll[0]['tax_due_14'] != "N/A" && $payroll[0]['tax_due_14'] != NULL ){
				$tax_due_14 = $payroll[0]['tax_due_14'];
			}
			if($payroll[0]['tax_withheld_15a'] != "N/A" && $payroll[0]['tax_withheld_15a'] != NULL ){
				$tax_withheld_15a = $payroll[0]['tax_withheld_15a'];
			}
			if($payroll[0]['present_employer_15b'] != "N/A" && $payroll[0]['present_employer_15b'] != NULL ){
				$present_employer_15b = $payroll[0]['present_employer_15b'];
			}
			if($payroll[0]['year_end_adjustment_16a'] != "N/A" && $payroll[0]['year_end_adjustment_16a'] != NULL ){
				$year_end_adjustment_16a = $payroll[0]['year_end_adjustment_16a'];
			}
			$employer_tin_number = '000-795-636-0000';
			$employer_name = 'NATIONAL WATER RESOURCES BOARD';
			$employer_address = '8TH FLOOR, NIA BUILDING, EDSA, DILIMAN, QUEZON CITY';
	
			$permanent_address = $payroll[0]['address'];
				// if($payroll[0]['permanent_street'] != "N/A" && $payroll[0]['permanent_street'] != NULL ){
				// 	$permanent_address .= " ".$payroll[0]['permanent_street'];
				// }
				// if($payroll[0]['permanent_barangay'] != "N/A" && $payroll[0]['permanent_barangay'] != NULL ){
				// 	$permanent_address .= " ".$payroll[0]['permanent_barangay'];
				// }
				// if($payroll[0]['permanent_municipality'] != "N/A" && $payroll[0]['permanent_municipality'] != NULL ){
				// 	$permanent_address .= " ".$payroll[0]['permanent_municipality'];
				// }
			//var_dump($zip_code);
			$employment_from = $payroll[0]['start_date'];
			$employment_to = $payroll[0]['end_date'];
			$pdf->SetFont('Helvetica','' , 9);

			$pdf->SetXY(132, 33);
			$pdf->Write(0, $employment_from[0].'  '.$employment_from[1].'       '.$employment_from[6].'  '.$employment_from[7]);
			$pdf->SetXY(173, 33);
			$pdf->Write(0, $employment_to[0].'  '.$employment_to[1].'       '.$employment_to[6].'  '.$employment_to[7]);
			//34
			if(str_replace(',', '', $benefits_34) <= 90000){
				$value_34 = str_replace(',', '', $benefits_34);
			}else{
				$value_34 = 90000;
			}
			$pdf->SetXY(162, 81);
			$pdf->Write(0, number_format($value_34, 2));
			$pdf->SetXY(162, 87);
			$pdf->Write(0, $benefits_35);
			$pdf->SetXY(162, 93);
			$pdf->Write(1, $contribution_36);
			$pdf->SetXY(162, 105);
			$pdf->Write(1, $non_taxable_38);
			$pdf->SetXY(162, 118);
			$pdf->Write(0, $basic_salary_39);
			//48
			if(str_replace(',', '', $benefits_34) <= 90000){
				$value_48 = 0;
			}else{
				$value_48 = 90000 - str_replace(',', '', $value_34);
			}
			

			$pdf->SetXY(162, 186);
			$pdf->Write(0, number_format($value_48, 2));

			$gross_compe_19 = str_replace(',', '', $non_taxable_38) + str_replace(',', '', $total_taxable_compe_income_pre_empr_7j);
			//19
			$pdf->SetXY(72, 170);
			$pdf->Write(0, number_format($gross_compe_19, 2));
			//20
			$pdf->SetXY(72, 176);
			$pdf->Write(0, $non_taxable_38);
			//21
			$value_21 = str_replace(',', '', $gross_compe_19) - str_replace(',', '', $non_taxable_38);
			$pdf->SetXY(72, 182);
			$pdf->Write(0, number_format($value_21, 2));
			//22
			$pdf->SetXY(72, 188);
			$pdf->Write(0, $total_taxable_compe_12j);
			
			//23
			$gross_compe_19 = str_replace(',', '', $value_21) + str_replace(',', '', $total_taxable_compe_12j);
			$pdf->SetXY(162, 220);
			$pdf->Write(0, $total_taxable_compe_income_pre_empr_7j);
			//24
			$pdf->SetXY(72, 201);
			$pdf->Write(0, $tax_due_14);
			//25a
			$pdf->SetXY(72, 207);
			$pdf->Write(0, $tax_withheld_15a);
			//25b
			$pdf->SetXY(72, 214);
			$pdf->Write(0, $present_employer_15b);
			//26
			$pdf->SetXY(72, 221);
			$pdf->Write(0, $year_end_adjustment_16a);

			$pdf->SetXY(72, 195);
			$pdf->Write(0, $basic_salary_39);
	
			$pdf->SetFont('Helvetica','' , 9);
			$pdf->SetXY(45, 34);
			$pdf->Write(0, $year[0].'    '.$year[1].'      '.$year[2].'     '.$year[3]);
			$pdf->SetXY(32, 43);
			$pdf->Write(0, $tin_number[0].'  '.$tin_number[1].'   '.$tin_number[2].'       '.$tin_number[4].'   '.$tin_number[5].'   '.$tin_number[6].'       '.$tin_number[8].'   '.$tin_number[9].'   '.$tin_number[10].'       0   0   0   0');
			
			$pdf->SetXY(32, 143);
			$pdf->Write(0, $pre_tin_number[0].'  '.$pre_tin_number[1].'   '.$pre_tin_number[2].'       '.$pre_tin_number[4].'   '.$pre_tin_number[5].'   '.
			$pre_tin_number[6].'       '.$pre_tin_number[8].'   '.$pre_tin_number[9].'   '.$pre_tin_number[10]
			.'       0   0   0   0');
			
			if(strlen($fullname) > 30){
				$pdf->SetFont('Helvetica','' , 8.5);
				$pdf->SetXY(17.7, 51);
				$pdf->Write(0, utf8_decode($fullname));
			}else{
				$pdf->SetFont('Helvetica','' , 9);
				$pdf->SetXY(17.7, 51);
				$pdf->Write(0, utf8_decode($fullname));
			}


			$pdf->SetXY(90, 51);
			$pdf->Write(0, '0   3   9');
			$p = 0;
			$appendedFirstAddress = "";
			$appendedSecondAddress = "";

			if($payroll[0]['address'] != "" && $payroll[0]['address'] != NULL){
				if(str_word_count($permanent_address) > 4){
					$explodedAddress = explode(" ", $permanent_address);
					for($x = 0; $x <= round(sizeof($explodedAddress)) / 2 ; $x++){
						$appendedFirstAddress .= " ".$explodedAddress[$x];
						$p++;
					}

					for($t = $p; $t <= sizeof($explodedAddress) - 1; $t++){
						$appendedSecondAddress .= " ".$explodedAddress[$t];
					}
						//var_dump(sizeof($explodedAddress));
				$pdf->SetFont('Helvetica','' , 8);
				$pdf->SetXY(19, 59);
				$pdf->Write(0, $appendedFirstAddress);
				$pdf->SetXY(17, 61.5);
				$pdf->Write(0, $appendedSecondAddress);
				}else{
				$pdf->SetXY(17, 60);
				$pdf->Write(0, $permanent_address);
				}

			}

		
	
			$pdf->SetFont('Helvetica','' , 9);
			if($zip_code != "N/A" && $zip_code != NULL){
				$pdf->SetXY(89, 60);	
				$pdf->Write(2, $zip_code[0].'  '.$zip_code[1].'   '.$zip_code[2].'   '.$zip_code[3]);
			}else{
				
	
			}
			//var_dump($birthday);
			if($payroll[0]['birthday'] != 'N/A' && $payroll[0]['birthday'] != NULL){
				$pdf->SetXY(19, 86);
				$pdf->Write(0, $birthday_month[0].'   '.$birthday_month[1].'   '.$birthday_day[0].'   '.$birthday_day[0].'  '.$birthday_year[0].'   '.$birthday_year[1].'   '.$birthday_year[2].'  '.$birthday_year[3]);
			}
	
			if($payroll[0]['contact_number'] != 'N/A' && $payroll[0]['contact_number'] != NULL){
				$pdf->SetXY(58, 86);
				$pdf->Write(0, $contact_number[0].'   '.$contact_number[1].'   '.$contact_number[2].'   '.$contact_number[3].'   '.$contact_number[4].'  '.$contact_number[5].'   '.$contact_number[6].'   '.$contact_number[7].'   '.$contact_number[8].'   '.
				$contact_number[9].'   '.$contact_number[10]);
			}
			$pdf->SetXY(32, 112);
			$pdf->Write(0, $employer_tin_number[0].'  '.$employer_tin_number[1].'   '.$employer_tin_number[2].'       '.$employer_tin_number[4].'   '.$employer_tin_number[5].'   '.$employer_tin_number[6].'       '.$employer_tin_number[8].'   '.$employer_tin_number[9].'   '.$employer_tin_number[10].'       0   0   0   0');
			$pdf->SetXY(17.7, 120);
			$pdf->Write(0, $employer_name);
			$pdf->SetFont('Helvetica','' , 6.8);
			$pdf->SetXY(17.7, 129);
			$pdf->Write(0, $employer_address);
	
			$pdf->SetFont('Helvetica','' , 9);
			if(sizeof($signatories_a) > 0){
	
				if($signatories_a[0]["employee_name"] == "ARCHIE EDSEL C. ASUNCION "){
	
						$box_a = 'ATTY. '.$signatories_a[0]["employee_name"];
						$box_a_department = "OIC, Deputy Executive Director";
	
				}else if($signatories_a[0]["employee_name"] == "JUAN Y. CORPUZ JR."){
	
						$box_a = 'ATTY. '.$signatories_a[0]["employee_name"];
						$box_a_department = "Chief, ".$signatories_a[0]["department"];
	
				}else if($signatories_a[0]["employee_name"] == "ELOISA L. LEGASPI "){
						$box_a = $signatories_a[0]["employee_name"];
						$box_a_department = "Chief, ".$signatories_a[0]["department"];
	
				}else if($signatories_a[0]["employee_name"] == "LUIS S. RONGAVILLA "){
	
						$box_a = $signatories_a[0]["employee_name"];
						$box_a_department = "Chief, ".$signatories_a[0]["department"];
	
				}else if($signatories_a[0]["employee_name"] == "SUSAN P. ABAÑO "){
	
						$box_a = $signatories_a[0]["employee_name"];
						$box_a_department = "Chief, ".$signatories_a[0]["department"];
						
				}else if($signatories_a[0]["employee_name"] == "EVELYN V. AYSON "){
	
					$box_a = $signatories_a[0]["employee_name"];
					$box_a_department = "OIC, ".$signatories_a[0]["department"];
	
				}else if($signatories_a[0]["employee_name"] == "SEVILLO D. DAVID JR."){
	
						$box_a = 'DR. '.$signatories_a[0]["employee_name"].', CESO III';
						$box_a_department = "OIC, Deputy Executive Director";
				}
				else{
	
					$box_a = $signatories_a[0]["employee_name"];
					$box_a_department = $signatories_a[0]["department"];
				}
			}
			
			$pdf->text(32, 249 ,$box_a);

			$pdf->SetXY(32, 259);
			$pdf->Write(0, utf8_decode($fullname));

			$pdf->SetFont('Helvetica','' , 6);
			$pdf->text(128, 291 ,utf8_decode($fullname));

			$pdf->SetFont('Helvetica','' , 9);
			$pdf->text(32, 286 ,utf8_decode($box_a));
			$pdf->Output('I', 'generated.pdf');
		}
		
	
	}
}

?>