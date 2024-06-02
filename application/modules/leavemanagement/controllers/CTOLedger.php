
<?php

class CTOLedger extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('CTOLedgerCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
	}
	public function index() {
		Helper::sessionEndedHook('session');
		Helper::rolehook(ModuleRels::CTO_LEDGER_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewCTOLedger";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/ctoledgerlist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Compensatory Time Off Ledger');
			Helper::setMenu('templates/menu_template');
			Helper::setView('ctoledger',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}

	// public function generateLeaveBalance(){
	// 	// if($_GET["Crfltart"] == "vGbzj74ntBrjZKZA")
	// 	// 	$_SESSION["sessionState"] = true;
	// 	// Helper::sessionEndedHook('session');
	// 	$result = array();
	// 	$ret =  new CTOLedgerCollection();
	// 	$employees = $ret->getEmployeesList();
	// 	foreach($employees as $k => $v){
	// 		$employee_id = $v['id'];
	// 		$scanning_no = $this->Helper->decrypt($v["employee_number"],$v["id"]);
	// 		$year = date("Y", strtotime(date("Y") . " -1 year"));
	// 		$date_of_permanent = @$v["date_of_permanent"];
	// 		$present_day = @$v["present_day"];
	// 		$end_date = @$v["end_date"];
	// 		$start_date = @$v["start_date"];
	// 		$ledger = $this->generateLedgerData("",$employee_id,"","","",$year,"",12,$date_of_permanent,$present_day,$end_date,$start_date);
	// 		$lv_bal = $ledger[$year][count($ledger[$year])-2];
	// 		$params = array(
	// 			"id"=> $employee_id,
	// 			"scanning_number"=> $scanning_no,
	// 			"source_location"=> "server",
	// 			"year"=> date("Y"),
	// 			"vl"=> $lv_bal["vl_balance"],
	// 			"sl"=> $lv_bal["sl_balance"],
	// 			"total"=> number_format($lv_bal["vl_balance"] + $lv_bal["sl_balance"], 3)
	// 		);
	// 		if($ret->addbalance($params)) $res = new ModelResponse($ret->getCode(), $ret->getMessage());
	// 		else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
	// 	}
	// 	$result = json_decode($res,true);
	// 	$_SESSION["sessionState"] = false;
	// 	echo json_encode($result);
	// }
	// public function exhausted() {
	// 	Helper::sessionEndedHook('session');
	// 	Helper::rolehook(ModuleRels::EXHAUSTED_LEAVE_LEDGER_SUB_MENU);
	// 	$listData = array();
	// 	$viewData = array();
	// 	$page = "viewCTOLedgerExhausted";
	// 	$listData['key'] = $page;
	// 	$viewData['table'] = $this->load->view("helpers/ctoledgerlist",$listData,TRUE); 
	// 	if (!$this->input->is_ajax_request()) {
	// 		Helper::setTitle('Exhausted Leave Ledger');
	// 		Helper::setMenu('templates/menu_template');
	// 		Helper::setView('ctoledgerexhausted',$viewData,FALSE);
	// 		Helper::setTemplate('templates/master_template');
	// 	}
	// 	else{
	// 		$result['key'] = $listData['key'];
	// 		$result['table'] = $viewData['table'];
	// 		echo json_encode($result);
	// 	}
	// 	Session::checksession();
	// }
	// public function balance() {
	// 	Helper::sessionEndedHook('session');
	// 	Helper::rolehook(ModuleRels::LEAVE_BALANCE_SUB_MENU);
	// 	// var_dump(Helper::role(ModuleRels::LEAVE_TRACKING_ALL_ACCESS));die();
	// 	$listData = array();
	// 	$viewData = array();
	// 	$page = "viewCTOLedgerExhausted";
	// 	$listData['key'] = $page;
	// 	$viewData['table'] = $this->load->view("helpers/yearlybalancelist",$listData,TRUE); 
	// 	if (!$this->input->is_ajax_request()) {
	// 		Helper::setTitle('Yearly Balance');
	// 		Helper::setMenu('templates/menu_template');
	// 		Helper::setView('yearlybalance',$viewData,FALSE);
	// 		Helper::setTemplate('templates/master_template');
	// 	}
	// 	else{
	// 		$result['key'] = $listData['key'];
	// 		$result['table'] = $viewData['table'];
	// 		echo json_encode($result);
	// 	}
	// 	Session::checksession();
	// }
	// function fetchRowsExhausted(){
	// 	Helper::sessionEndedHook('session');
	// 	$employment_status = isset($_GET['EmploymentStatus'])?$_GET['EmploymentStatus']:"";
	// 	$employee_id = isset($_GET['Id'])?$_GET['Id']:"";
	// 	$pay_basis = "Permanent";
    //     $division_id = isset($_GET['Division'])?$_GET['Division']:"";
    //     $location_id = @$_GET['Location'];
    //     $month = @$_GET['Month'];
    //     $year = @$_GET['Year'];
    //     $post_year = @$_GET['Year'];
    //     $fetch_data = $this->CTOLedgerCollection->make_datatables($employment_status,$employee_id,$pay_basis,$division_id,$location_id);
    //     $data = array();  
        
    //     foreach($fetch_data as $k => $row)  
    //     {  

    //     	$ledger = $this->generateLedgerData($row->employment_status,$row->id,$row->pay_basis,$row->division_id,$row->location_id,$year,$post_year,$month,$row->date_of_permanent,$row->present_day);
    //     	$total_ledger = 0;
    //     	// var_dump($ledger[$post_year]);die();
    //     	$vl_balance_amt = @$ledger[$post_year][sizeof(@$ledger[$post_year]) - 1]['vl_balance'];
	// 	    $sl_balance_amt = @$ledger[$post_year][sizeof(@$ledger[$post_year]) - 1]['sl_balance'];
    //     	if(@$ledger[$post_year][sizeof(@$ledger[$post_year]) - 1]['period'] == ""){
	// 			$vl_balance_amt = @$ledger[$post_year][sizeof(@$ledger[$post_year]) - 2]['vl_balance'];
	// 		    $sl_balance_amt = @$ledger[$post_year][sizeof(@$ledger[$post_year]) - 2]['sl_balance']; 
	// 		}
	// 		$total_ledger = $vl_balance_amt + $sl_balance_amt;
	// 		// var_dump($total_ledger);die();
	// 		if($total_ledger < 10 && $row->date_of_permanent != "" && str_replace('-', '', $row->date_of_permanent) <= ($year.$month.'01')){
	//         	$buttons = "";
	//         	$buttons_data = "";
	//         	$row->employee_number = $this->Helper->decrypt($row->employee_number,$row->employee_id);
	//         	$row->employee_id_number = $this->Helper->decrypt($row->employee_id_number,$row->employee_id);
	//             $sub_array = array();    
	//             $sub_array[] = $row->employee_id_number;
	// 			$row->first_name = $this->Helper->decrypt($row->first_name,$row->employee_id);
	// 			$row->middle_name = $this->Helper->decrypt($row->middle_name,$row->employee_id);
	// 			$row->last_name = $this->Helper->decrypt($row->last_name,$row->employee_id);
	// 			$sub_array[] = $row->last_name.', '.$row->first_name.' '.$row->middle_name;
	//             $sub_array[] = $row->position_name;
	//             $sub_array[] = $row->department_name;
	//             $sub_array[] = $row->contract;
	//            	foreach($row as $k1=>$v1){
	//             	$buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
	//             }
	//             $buttons_data .= ' data-division_id="'.$division_id.'" ';
	//             $buttons .= ' <a id="viewCTOLedgerForm" ' 
	//             		  . ' class="viewCTOLedgerForm" style="text-decoration: none;" '
	//             		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewCTOLedgerForm" '
	//             		  . $buttons_data
	//             		  . ' > '
	//             		  . ' <button class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Leave Card">'
	//             		  . ' <i class="material-icons">remove_red_eye</i> '
	//             		  . ' </button> '
	//             		  . ' </a> ';
	// 	        $sub_array[] = $buttons;
	//             $data[] = $sub_array;  
	//         }
    //     }  
    //     $output = array(  
    //         "draw"                  =>     intval($_POST["draw"]),  
    //         "recordsTotal"          =>      $this->CTOLedgerCollection->get_all_data($employment_status,$employee_id,$pay_basis,$division_id),  
    //         "recordsFiltered"     	=>     $this->CTOLedgerCollection->get_filtered_data($employment_status,$employee_id,$pay_basis,$division_id,$location_id),  
    //         "data"                  =>     $data  
    //     );  
    //     echo json_encode($output);  
    // }
    // function fetchRowsYearlyBalance(){
	// 	Helper::sessionEndedHook('session');
	// 	$employment_status = isset($_GET['EmploymentStatus'])?$_GET['EmploymentStatus']:"";
	// 	$employee_id = isset($_GET['Id'])?$_GET['Id']:"";
	// 	$pay_basis = "Permanent";
    //     $division_id = isset($_GET['Division'])?$_GET['Division']:"";
    //     $location_id = @$_GET['Location'];
    //     $month = @$_GET['Month'];
    //     $year = @$_GET['Year'];
    //     $post_year = @$_GET['Year'];
    //     $fetch_data = $this->CTOLedgerCollection->make_datatables($employment_status,$employee_id,$pay_basis,$division_id,$location_id);
    //     $data = array();  
    //     foreach($fetch_data as $k => $row)  
    //     {  
    //     	// var_dump($_GET);die();
    //     	$ledger = $this->generateLedgerData($row->employment_status,$row->id,$row->pay_basis,$row->division_id,$row->location_id,$year,$post_year,$month,$row->date_of_permanent,$row->present_day);
    //     	$total_ledger = 0;
    //     	// var_dump($ledger);die();
    //     	// var_dump($ledger[$post_year]);die();
    //     	$vl_balance_amt = @$ledger[$post_year][sizeof(@$ledger[$post_year]) - 1]['vl_balance'];
	// 	    $sl_balance_amt = @$ledger[$post_year][sizeof(@$ledger[$post_year]) - 1]['sl_balance'];
    //     	if(@$ledger[$post_year][sizeof(@$ledger[$post_year]) - 1]['period'] == ""){
	// 			$vl_balance_amt = @$ledger[$post_year][sizeof(@$ledger[$post_year]) - 2]['vl_balance'];
	// 		    $sl_balance_amt = @$ledger[$post_year][sizeof(@$ledger[$post_year]) - 2]['sl_balance']; 
	// 		}
	// 		$total_ledger = $vl_balance_amt + $sl_balance_amt;
	// 		// var_dump($total_ledger);die();
	// 		if($row->date_of_permanent != "" && str_replace('-', '', $row->date_of_permanent) <= ($year.$month.'01')){
	//         	$buttons = "";
	//         	$buttons_data = "";
	//         	$row->employee_number = $this->Helper->decrypt($row->employee_number,$row->employee_id);
	//         	$row->employee_id_number = $this->Helper->decrypt($row->employee_id_number,$row->employee_id);
	//         	$row->first_name = $this->Helper->decrypt($row->first_name,$row->employee_id);
	//         	$row->middle_name = $this->Helper->decrypt($row->middle_name,$row->employee_id);
	//         	$row->last_name = $this->Helper->decrypt($row->last_name,$row->employee_id);
	//             $sub_array = array();    
	//             $sub_array[] = $row->employee_id_number;
	//             $sub_array[] = $row->first_name.' '.$row->last_name;  
	//             $sub_array[] = $row->position_name;
	//             $sub_array[] = number_format($vl_balance_amt, 3);
	//             $sub_array[] = number_format($sl_balance_amt, 3);
	//             $data[] = $sub_array;  
	//         }
    //     }  
    //     $output = array(  
    //         "draw"                  =>     intval($_POST["draw"]),  
    //         "recordsTotal"          =>      $this->CTOLedgerCollection->get_all_data($employment_status,$employee_id,$pay_basis,$division_id),  
    //         "recordsFiltered"     	=>     $this->CTOLedgerCollection->get_filtered_data($employment_status,$employee_id,$pay_basis,$division_id,$location_id),  
    //         "data"                  =>     $data  
    //     );  
    //     echo json_encode($output);  
	// }
	
	function fetchRows(){
		Helper::sessionEndedHook('session');
		$employment_status = isset($_GET['EmploymentStatus'])?$_GET['EmploymentStatus']:"";
		$employee_id = isset($_GET['Id'])?$_GET['Id']:"";
		$pay_basis = "Permanent";
        $division_id = isset($_GET['Division'])?$_GET['Division']:"";
        $location_id = @$_GET['Location'];
        $month = 12;
        $year = isset($_GET['Year']) ? $_GET['Year'] : date("Y");

      	$current_year = date("Y");
      	if($year == $current_year) {
      		$month = (int) date("m");
		  }
		$self = 0;
		if(in_array(17004,$_SESSION["sessionModules"])) $self = 1;
		$fetch_data = $this->CTOLedgerCollection->make_datatables($employment_status,$employee_id,$pay_basis,$division_id,$location_id,$year,$month,$self);
        $data = array();
        foreach($fetch_data as $k => $row) {
	        	$buttons = "";
				$buttons_data = "";
	        	$row->employee_number = $this->Helper->decrypt($row->employee_number,$row->employee_id);
	        	$row->employee_id_number = $this->Helper->decrypt($row->employee_id_number,$row->employee_id);
				$row->first_name = $this->Helper->decrypt($row->first_name,$row->employee_id);
				$row->middle_name = $this->Helper->decrypt($row->middle_name,$row->employee_id);
				$row->last_name = $this->Helper->decrypt($row->last_name,$row->employee_id);
				$row->extension = $this->Helper->decrypt($row->extension,$row->employee_id);
	            $sub_array = array();
	           	foreach($row as $k1=>$v1){
	            	$buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
	            }
	            $buttons_data .= ' data-division_id="'.$division_id.'" ';
	            $buttons .= ' <a id="viewCTOLedgerForm" ' 
	            		  . ' class="viewCTOLedgerForm" style="text-decoration: none;" '
	            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewCTOLedgerForm" '
	            		  . $buttons_data
	            		  . ' > '
	            		  . ' <button class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Leave Card">'
	            		  . ' <i class="material-icons">remove_red_eye</i> '
	            		  . ' </button> '
						  . ' </a> ';
		        $sub_array[] = $buttons;
	            $sub_array[] = $row->emp_number;
				$sub_array[] = $row->last_name.' '.$row->extension.', '.$row->first_name.' '.$row->middle_name;
	            $sub_array[] = $row->position_name;
	            $sub_array[] = $row->department_name;
	            $data[] = $sub_array; 
	        }
        // }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>      $this->CTOLedgerCollection->get_all_data($employment_status,$employee_id,$pay_basis,$division_id,$year,$month,$self),  
            "recordsFiltered"     	=>     $this->CTOLedgerCollection->get_filtered_data($employment_status,$employee_id,$pay_basis,$division_id,$location_id,$year,$month,$self),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }
	public function viewCTOLedgerForm(){
		
		Helper::sessionEndedHook('session');
		$result = array();
		$result['key'] = 'viewCTOLedger';
		$formData = array();
		if (!$this->input->is_ajax_request()) show_404();
		else {
			$ret =  new CTOLedgerCollection();
			$employment_status = '';
			$employee_id = isset($_POST['employee_id'])?$_POST['employee_id']:"";
			$pay_basis 	 = "Permanent";
			$division_id = isset($_POST['division_id'])?$_POST['division_id']:"";
			$location_id = isset($_POST['location_id'])?$_POST['location_id']:"";
			$end_date = isset($_POST['end_date'])?$_POST['end_date']:"";
			$start_date = isset($_POST['start_date'])?$_POST['start_date']:"";
			$year = isset($_POST['year'])?$_POST['year']:date("Y");
			$year_from = $year;
			$post_year 	= isset($_POST['year'])?$_POST['year']:"";
			$month = isset($_POST['month'])?$_POST['month']:"";
			$current_year = date("Y");
			if($year == $current_year) $month = (int) date("m");
			$self = 0;
			if(in_array(17004,$_SESSION["sessionModules"])) $self = 1;
			$employee = $ret->getEmployee($employment_status,$employee_id,$pay_basis,$division_id,$location_id,$year,$month, $self);
			if(sizeof($employee) > 0):
				$formData['employee']['employee_number'] 	= $this->Helper->decrypt($employee[0]['employee_number'],$employee[0]['employee_id']);
	        	$formData['employee']['employee_id_number'] = $this->Helper->decrypt($employee[0]['employee_id_number'],$employee[0]['employee_id']);
	        	$formData['employee']['first_name'] 		= $this->Helper->decrypt($employee[0]['first_name'],$employee[0]['employee_id']);
	        	$formData['employee']['middle_name']		= $this->Helper->decrypt($employee[0]['middle_name'],$employee[0]['employee_id']);
	        	$formData['employee']['last_name'] 			= $this->Helper->decrypt($employee[0]['last_name'],$employee[0]['employee_id']);
	        	$formData['employee']['position_name'] 		= $employee[0]['position_name'];
				$employment_status 							= $employee[0]['employment_status'];
				$end_date									= $employee[0]['end_date'];
	        	$formData['employee']['employment_status'] 	= $employee[0]['employment_status'];
	        	$formData['employee']['civil_status'] 		= $employee[0]['civil_status'];
	        	$formData['employee']['employee_entrance_to_duty'] = @$employee[0]['employee_entrance_to_duty'];
	        	$formData['employee']['employee_unit'] 		= @$employee[0]['employee_unit'];
	        	$formData['employee']['gsis'] 				= $employee[0]['gsis'];
	        	$formData['employee']['tin'] 				= $employee[0]['tin'];
	        	$formData['employee']['employee_nrcn'] 		= @$employee[0]['employee_nrcn'];
	        	$formData['employee']['contract'] 			= @$employee[0]['contract'];
	        	$formData['employee']['start_date'] 		= @$employee[0]['start_date'];
	        	$formData['employee']['office_name'] 		= @$employee[0]['office_name'];
	        	$formData['employee']['department_name'] 	= @$employee[0]['department_name'];
	        	$date_of_permanent = isset($employee[0]['date_of_permanent']) && $employee[0]['date_of_permanent'] != null?$employee[0]['date_of_permanent']:$year;
	        	$present_day = isset($employee[0]['present_day'])?$employee[0]['present_day']:0;
				$start_date = $formData['employee']['start_date'];
			endif;
			$ledger = $this->generateLedgerData($employee_id,$year,$month);
			$formData['year'] = $year;
			$formData['key'] = $result['key'];
			$selected_years = range($year_from,$post_year);
			foreach ($selected_years as $k1 => $v1) {
				if(isset($ledger[$v1])){
					$formData['ledgers'][] = $ledger[$v1];
				}
			}
			$result['form'] = $this->load->view('helpers/employeectoledger.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	
	public function generateLedgerData($employee_id,$year,$month){
		$ret =  new CTOLedgerCollection();			
		$month_codes = array("01","02","03","04","05","06","07","08","09","10","11","12");	
		$selected_months = array();
		$month_index = 0;
		$bal_from_last_year = 0.00;
		$cto_remaining = 0.00;	
		for($i = $month_index; $i < ((int) $month); $i++){
			$selected_months[] = $month_codes[$i];
		}
		foreach ($selected_months as $key => $value) {	
			$remaining = "";
			$transaction_date = "";	
			$offset_date_effectivity = "";	
			$cto_hrs_earned = "";	
			$cto_mins_earned = "";	
			$cto_hrs_used = "";	
			$cto_mins_used = "";	
			$a_date = $year.'-'.$value.'-01';
			$z_date = date("t", strtotime($a_date));
			$monthly_data['period'] = date("M. t, Y", strtotime($a_date));

			$earned = $ret->getEarned($value,$year,$employee_id);
			$used = $ret->getUsed($value,$year,$employee_id);
			foreach($earned as $k => $v){			
				$transaction_date .= "<p style='width:100%;text-align:left;'>".$v['transaction_date']."</p>";
				$offset =  $v['offset'] > 0 ? ($v['offset'] / 0.0020833333333333) / 60 : 0;
				$offsetHrs = floor($offset);
				$offsetMins = round(($offset - $offsetHrs)*60);
				$cto_hrs_earned .= "<p style='width:100%;text-align:right;'>".sprintf('%02d', $offsetHrs)."</p>";
				$cto_mins_earned .= "<p style='width:100%;text-align:right;'>".sprintf('%02d', $offsetMins)."</p>";
				
				$cto_remaining += (($bal_from_last_year + $v['offset'])/ 0.0020833333333333) / 60;
				$r_offsetHrs = floor($cto_remaining);
				$r_offsetMins = round(($cto_remaining - $r_offsetHrs)*60);
				if(sizeof($used) == 0) $remaining .= "<p style='width:100%;text-align:right;'>".sprintf('%02d:%02d',$r_offsetHrs,$r_offsetMins)."</p>";
			}
			foreach($used as $k => $v){
				$offset_date_effectivity .= "<p style='width:100%;text-align:left;'>".$v['offset_date_effectivity']."</p>";
				$cto_hrs_used .= "<p style='width:100%;text-align:right;'>".sprintf('%02d', $v['offset_hrs'])."</p>";
				$cto_mins_used .= "<p style='width:100%;text-align:right;'>".sprintf('%02d', $v['offset_mins'])."</p>";

				$offset_hrs = ($v['offset_hrs'] * 60) * 0.002084;
				$offset_mins = $v['offset_mins'];
				switch($offset_mins){
					case 1 : $offset_mins = 0.002; break;
					case 2 : $offset_mins = 0.004; break;
					case 3 : $offset_mins = 0.006; break;
					case 4 : $offset_mins = 0.008; break;
					case 5 : $offset_mins = 0.01; break;
					case 6 : $offset_mins = 0.012; break;
					case 7 : $offset_mins = 0.015; break;
					case 8 : $offset_mins = 0.017; break;
					case 9 : $offset_mins = 0.019; break;
					case 10 : $offset_mins = 0.021; break;
					case 11 : $offset_mins = 0.023; break;
					case 12 : $offset_mins = 0.025; break;
					case 13 : $offset_mins = 0.027; break;
					case 14 : $offset_mins = 0.029; break;
					case 15 : $offset_mins = 0.031; break;
					case 16 : $offset_mins = 0.033; break;
					case 17 : $offset_mins = 0.035; break;
					case 18 : $offset_mins = 0.037; break;
					case 19 : $offset_mins = 0.04; break;
					case 20 : $offset_mins = 0.042; break;
					case 21 : $offset_mins = 0.044; break;
					case 22 : $offset_mins = 0.046; break;
					case 23 : $offset_mins = 0.048; break;
					case 24 : $offset_mins = 0.05; break;
					case 25 : $offset_mins = 0.052; break;
					case 26 : $offset_mins = 0.054; break;
					case 27 : $offset_mins = 0.056; break;
					case 28 : $offset_mins = 0.058; break;
					case 29 : $offset_mins = 0.06; break;
					case 30 : $offset_mins = 0.062; break;
					case 31 : $offset_mins = 0.065; break;
					case 32 : $offset_mins = 0.067; break;
					case 33 : $offset_mins = 0.069; break;
					case 34 : $offset_mins = 0.071; break;
					case 35 : $offset_mins = 0.073; break;
					case 36 : $offset_mins = 0.075; break;
					case 37 : $offset_mins = 0.077; break;
					case 38 : $offset_mins = 0.079; break;
					case 39 : $offset_mins = 0.081; break;
					case 40 : $offset_mins = 0.083; break;
					case 41 : $offset_mins = 0.085; break;
					case 42 : $offset_mins = 0.087; break;
					case 43 : $offset_mins = 0.09; break;
					case 44 : $offset_mins = 0.092; break;
					case 45 : $offset_mins = 0.094; break;
					case 46 : $offset_mins = 0.096; break;
					case 47 : $offset_mins = 0.098; break;
					case 48 : $offset_mins = 0.1; break;
					case 49 : $offset_mins = 0.102; break;
					case 50 : $offset_mins = 0.104; break;
					case 51 : $offset_mins = 0.106; break;
					case 52 : $offset_mins = 0.108; break;
					case 53 : $offset_mins = 0.11; break;
					case 54 : $offset_mins = 0.112; break;
					case 55 : $offset_mins = 0.115; break;
					case 56 : $offset_mins = 0.117; break;
					case 57 : $offset_mins = 0.119; break;
					case 58 : $offset_mins = 0.121; break;
					case 59 : $offset_mins = 0.123; break;
					case 60 : $offset_mins = 0.125; break;
					default : 0;
				}
				$offset = $offset_hrs + $offset_mins;
				$cto_remaining = ($cto_remaining - ($offset/ 0.002084) / 60);
				$r_offsetHrs = floor($cto_remaining);
				$r_offsetMins = round(($cto_remaining - $r_offsetHrs)*60);
				$remaining .= "<p style='width:100%;text-align:right;'>".sprintf('%02d:%02d',$r_offsetHrs,$r_offsetMins)."</p>";
			}
			if(sizeof($earned) == 0 && sizeof($used) == 0) {
				$r_offsetHrs = floor($cto_remaining);
				$r_offsetMins = round(($cto_remaining - $r_offsetHrs)*60);
				$remaining .= "<p style='width:100%;text-align:right;'>".sprintf('%02d:%02d',$r_offsetHrs,$r_offsetMins)."</p>";
			}

			$monthly_data['bal_from_last_year'] = $bal_from_last_year;
			$monthly_data['cto_remaining'] = $remaining;
			$monthly_data['transaction_date'] = $transaction_date;
			$monthly_data['offset_date_effectivity'] = $offset_date_effectivity;
			$monthly_data['cto_hrs_earned'] = $cto_hrs_earned;
			$monthly_data['cto_mins_earned'] = $cto_mins_earned;
			$monthly_data['cto_hrs_used'] = $cto_hrs_used;
			$monthly_data['cto_mins_used'] = $cto_mins_used;
			$ledger[$year][] = $monthly_data;
		}
			
		return $ledger;
	}
	public function viewCTOLedgerSummary(){
		Helper::sessionEndedHook('session');
		$formData = array();
		$result = array();
		$result['key'] = 'viewCTOLedgerSummary';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new CTOLedgerCollection();
			$employment_status = '';
			$employee_id = isset($_POST['employee_id'])?$_POST['employee_id']:"";
			$pay_basis 	 = "Permanent";
			$division_id = isset($_POST['division_id'])?$_POST['division_id']:"";
			$location_id = isset($_POST['location_id'])?$_POST['location_id']:"";
			$year 		 = isset($_POST['year'])?$_POST['year']:"";
			$year_from 		 = isset($_POST['year_from'])?$_POST['year_from']:"";
			$post_year 		 = isset($_POST['year'])?$_POST['year']:"";
			$month 		 = isset($_POST['month'])?$_POST['month']:"";
			$end_date = isset($_POST['end_date'])?$_POST['end_date']:"";
			$start_date = isset($_POST['start_date'])?$_POST['start_date']:"";

			$current_year = date("Y");
	      	if($year == $current_year) {
	      		$month = (int) date("m");
	      	}
			$self = 0;
			if(in_array(17004,$_SESSION["sessionModules"])) $self = 1;
			$employee = $ret->getEmployee($employment_status,$employee_id,$pay_basis,$division_id,$location_id,$year,$month,$self);
			if(sizeof($employee) > 0):
				$formData['employee']['employee_number'] 	= $this->Helper->decrypt($employee[0]['employee_number'],$employee[0]['employee_id']);
	        	$formData['employee']['employee_id_number'] = $this->Helper->decrypt($employee[0]['employee_id_number'],$employee[0]['employee_id']);
	        	$formData['employee']['first_name'] 		= $this->Helper->decrypt($employee[0]['first_name'],$employee[0]['employee_id']);
	        	$formData['employee']['middle_name']		= $this->Helper->decrypt($employee[0]['middle_name'],$employee[0]['employee_id']);
	        	$formData['employee']['last_name'] 			= $this->Helper->decrypt($employee[0]['last_name'],$employee[0]['employee_id']);
	        	$formData['employee']['position_name'] 		= $employee[0]['position_name'];
	        	$formData['employee']['employment_status'] 	= $employee[0]['employment_status'];
	        	$formData['employee']['civil_status'] 		= $employee[0]['civil_status'];
	        	$formData['employee']['employee_entrance_to_duty'] = @$employee[0]['employee_entrance_to_duty'];
	        	$formData['employee']['employee_unit'] 		= @$employee[0]['employee_unit'];
	        	$formData['employee']['gsis'] 				= $employee[0]['gsis'];
	        	$formData['employee']['tin'] 				= $employee[0]['tin'];
	        	$formData['employee']['employee_nrcn'] 		= @$employee[0]['employee_nrcn'];
	        	$formData['employee']['contract'] 		= @$employee[0]['contract'];
	        	$formData['employee']['start_date'] 		= @$employee[0]['start_date'];
	        	$formData['employee']['office_name'] 		= @$employee[0]['office_name'];
	        	$formData['employee']['department_name'] 		= @$employee[0]['department_name'];
	        	$date_of_permanent = isset($employee[0]['date_of_permanent']) && $employee[0]['date_of_permanent'] != null?$employee[0]['date_of_permanent']:$year;
	        	$present_day = @$employee[0]['present_day'];
	        endif;
			$ledger = $this->generateLedgerData($employee_id,$year,$month);
			
			$formData['year'] = $year;
			$formData['key'] = $result['key'];
			$selected_years = range($year_from,$post_year);
			foreach ($selected_years as $k1 => $v1) {
				if(isset($ledger[$v1])){
					$formData['ledgers'][] = @$ledger[$v1];
				}
			}
			$result['form'] = $this->load->view('helpers/employeectoledger.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	
	function getEmployeeList(){
		Helper::sessionEndedHook('session');
		// $this->load->model('employees/EmployeesCollection');
		$employee_sort = array();
		$year = @$_POST['year'];
		$post_year = @$_POST['year'];
		$month = @$_POST['month'];
		// var_dump($_POST);die();
		$employees = @$this->CTOLedgerCollection->getEmployeeList($_POST['pay_basis'],$_POST['location_id'],@$_POST['division_id'],@$_POST['specific']);
		// var_dump($employees);die();
		$employee_final = array();
		foreach ($employees as $k => $value) {
			// var_dump($total_ledger);die();
			if(strtotime($value['date_of_permanent']) <= strtotime($year.'-'.$month.'-01') && $value['date_of_permanent'] != ""){
				$employees[$k]['employee_number'] = $this->Helper->decrypt($value['employee_number'], $value['id']);
				$employees[$k]['employee_id_number'] = $this->Helper->decrypt($value['employee_id_number'], $value['id']);
				$employees[$k]['last_name'] = $this->Helper->decrypt($value['last_name'], $value['id']);
				$employees[$k]['first_name'] = $this->Helper->decrypt($value['first_name'], $value['id']);
				$employees[$k]['middle_name'] = $this->Helper->decrypt($value['middle_name'], $value['id']);
				$employee_sort[$k] = $employees[$k]['last_name'];
				$employee_final[$k] = $employees[$k];
			}
		}
		array_multisort($employee_sort, SORT_ASC, $employee_final);
		$formData['list'] = $employee_final;
		$formData['key'] = "viewEmployees";
		$result['table'] = $this->load->view('employees/helpers/employeechecklist.php', $formData, TRUE);
		$result['key'] = "viewEmployees";
		echo json_encode($result);
	}
	
	public function ctoledgerContainer(){
		Helper::sessionEndedHook('session');
		// die("hit");
		$formData = array();
		$result = array();
		$result['key'] = 'ctoledgerContainer';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			
			/*if($ret->computePayrollProcess($employee_id,$division_id)) {*/
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('helpers/employeectoledger.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	
	public function dateDiff($d1,$d2) {
		// Return the number of days between the two dates:    
		return round(abs(strtotime($d1) - strtotime($d2))/86400);	
	}
}

?>
