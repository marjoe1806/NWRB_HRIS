
<?php

class LeaveLedger extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('LeaveLedgerCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
	}
	public function index() {
		Helper::sessionEndedHook('session');
		Helper::rolehook(ModuleRels::LEAVE_LEDGER_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewLeaveLedger";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/leaveledgerlist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Leave Card');
			Helper::setMenu('templates/menu_template');
			Helper::setView('leaveledger',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}

	public function generateLeaveBalance(){
		// if($_GET["Crfltart"] == "vGbzj74ntBrjZKZA")
		// 	$_SESSION["sessionState"] = true;
		// Helper::sessionEndedHook('session');
		$result = array();
		$ret =  new LeaveLedgerCollection();
		$employees = $ret->getEmployeesList();
		foreach($employees as $k => $v){
			$employee_id = $v['id'];
			$scanning_no = $this->Helper->decrypt($v["employee_number"],$v["id"]);
			$year = date("Y", strtotime(date("Y") . " -1 year"));
			$date_of_permanent = @$v["date_of_permanent"];
			$present_day = @$v["present_day"];
			$end_date = @$v["end_date"];
			$start_date = @$v["start_date"];
			$ledger = $this->generateLedgerData("",$employee_id,"","","",$year,"",12,$date_of_permanent,$present_day,$end_date,$start_date);
			$lv_bal = $ledger[$year][count($ledger[$year])-2];
			$params = array(
				"id"=> $employee_id,
				"scanning_number"=> $scanning_no,
				"source_location"=> "server",
				"year"=> date("Y"),
				"vl"=> $lv_bal["vl_balance"],
				"sl"=> $lv_bal["sl_balance"],
				"total"=> number_format($lv_bal["vl_balance"] + $lv_bal["sl_balance"], 3)
			);
			if($ret->addbalance($params)) $res = new ModelResponse($ret->getCode(), $ret->getMessage());
			else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		}
		$result = json_decode($res,true);
		$_SESSION["sessionState"] = false;
		echo json_encode($result);
	}
	public function exhausted() {
		Helper::sessionEndedHook('session');
		Helper::rolehook(ModuleRels::EXHAUSTED_LEAVE_LEDGER_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewLeaveLedgerExhausted";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/leaveledgerlist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Exhausted Leave Ledger');
			Helper::setMenu('templates/menu_template');
			Helper::setView('leaveledgerexhausted',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}
	public function balance() {
		Helper::sessionEndedHook('session');
		Helper::rolehook(ModuleRels::LEAVE_BALANCE_SUB_MENU);
		// var_dump(Helper::role(ModuleRels::LEAVE_TRACKING_ALL_ACCESS));die();
		$listData = array();
		$viewData = array();
		$page = "viewLeaveLedgerExhausted";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/yearlybalancelist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Yearly Balance');
			Helper::setMenu('templates/menu_template');
			Helper::setView('yearlybalance',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}
	function fetchRowsExhausted(){
		Helper::sessionEndedHook('session');
		$employment_status = isset($_GET['EmploymentStatus'])?$_GET['EmploymentStatus']:"";
		$employee_id = isset($_GET['Id'])?$_GET['Id']:"";
		$pay_basis = "Permanent";
        $division_id = isset($_GET['Division'])?$_GET['Division']:"";
        $location_id = @$_GET['Location'];
        $month = @$_GET['Month'];
        $year = @$_GET['Year'];
        $post_year = @$_GET['Year'];
        $fetch_data = $this->LeaveLedgerCollection->make_datatables($employment_status,$employee_id,$pay_basis,$division_id,$location_id);
        $data = array();  
        
        foreach($fetch_data as $k => $row)  
        {  

        	$ledger = $this->generateLedgerData($row->employment_status,$row->id,$row->pay_basis,$row->division_id,$row->location_id,$year,$post_year,$month,$row->date_of_permanent,$row->present_day);
        	$total_ledger = 0;
        	// var_dump($ledger[$post_year]);die();
        	$vl_balance_amt = @$ledger[$post_year][sizeof(@$ledger[$post_year]) - 1]['vl_balance'];
		    $sl_balance_amt = @$ledger[$post_year][sizeof(@$ledger[$post_year]) - 1]['sl_balance'];
        	if(@$ledger[$post_year][sizeof(@$ledger[$post_year]) - 1]['period'] == ""){
				$vl_balance_amt = @$ledger[$post_year][sizeof(@$ledger[$post_year]) - 2]['vl_balance'];
			    $sl_balance_amt = @$ledger[$post_year][sizeof(@$ledger[$post_year]) - 2]['sl_balance']; 
			}
			$total_ledger = $vl_balance_amt + $sl_balance_amt;
			// var_dump($total_ledger);die();
			if($total_ledger < 10 && $row->date_of_permanent != "" && str_replace('-', '', $row->date_of_permanent) <= ($year.$month.'01')){
	        	$buttons = "";
	        	$buttons_data = "";
	        	$row->employee_number = $this->Helper->decrypt($row->employee_number,$row->employee_id);
	        	$row->employee_id_number = $this->Helper->decrypt($row->employee_id_number,$row->employee_id);
	            $sub_array = array();    
	            $sub_array[] = $row->emp_number;
				$row->first_name = $this->Helper->decrypt($row->first_name,$row->employee_id);
				$row->middle_name = $this->Helper->decrypt($row->middle_name,$row->employee_id);
				$row->last_name = $this->Helper->decrypt($row->last_name,$row->employee_id);
				$sub_array[] = $row->last_name.', '.$row->first_name.' '.$row->middle_name;
	            $sub_array[] = $row->position_name;
	            $sub_array[] = $row->department_name;
	            $sub_array[] = $row->contract;
	           	foreach($row as $k1=>$v1){
	            	$buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
	            }
	            $buttons_data .= ' data-division_id="'.$division_id.'" ';
	            $buttons .= ' <a id="viewLeaveLedgerForm" ' 
	            		  . ' class="viewLeaveLedgerForm" style="text-decoration: none;" '
	            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewLeaveLedgerForm" '
	            		  . $buttons_data
	            		  . ' > '
	            		  . ' <button class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Leave Card">'
	            		  . ' <i class="material-icons">remove_red_eye</i> '
	            		  . ' </button> '
	            		  . ' </a> ';
		        $sub_array[] = $buttons;
	            $data[] = $sub_array;  
	        }
        }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>      $this->LeaveLedgerCollection->get_all_data($employment_status,$employee_id,$pay_basis,$division_id),  
            "recordsFiltered"     	=>     $this->LeaveLedgerCollection->get_filtered_data($employment_status,$employee_id,$pay_basis,$division_id,$location_id),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }
    function fetchRowsYearlyBalance(){
		Helper::sessionEndedHook('session');
		$employment_status = isset($_GET['EmploymentStatus'])?$_GET['EmploymentStatus']:"";
		$employee_id = isset($_GET['Id'])?$_GET['Id']:"";
		$pay_basis = "Permanent";
        $division_id = isset($_GET['Division'])?$_GET['Division']:"";
        $location_id = @$_GET['Location'];
        $month = @$_GET['Month'];
        $year = @$_GET['Year'];
        $post_year = @$_GET['Year'];
        $fetch_data = $this->LeaveLedgerCollection->make_datatables($employment_status,$employee_id,$pay_basis,$division_id,$location_id);
        $data = array();  
        foreach($fetch_data as $k => $row)  
        {  
        	// var_dump($_GET);die();
        	$ledger = $this->generateLedgerData($row->employment_status,$row->id,$row->pay_basis,$row->division_id,$row->location_id,$year,$post_year,$month,$row->date_of_permanent,$row->present_day);
        	$total_ledger = 0;
        	// var_dump($ledger);die();
        	// var_dump($ledger[$post_year]);die();
        	$vl_balance_amt = @$ledger[$post_year][sizeof(@$ledger[$post_year]) - 1]['vl_balance'];
		    $sl_balance_amt = @$ledger[$post_year][sizeof(@$ledger[$post_year]) - 1]['sl_balance'];
        	if(@$ledger[$post_year][sizeof(@$ledger[$post_year]) - 1]['period'] == ""){
				$vl_balance_amt = @$ledger[$post_year][sizeof(@$ledger[$post_year]) - 2]['vl_balance'];
			    $sl_balance_amt = @$ledger[$post_year][sizeof(@$ledger[$post_year]) - 2]['sl_balance']; 
			}
			$total_ledger = $vl_balance_amt + $sl_balance_amt;
			// var_dump($total_ledger);die();
			if($row->date_of_permanent != "" && str_replace('-', '', $row->date_of_permanent) <= ($year.$month.'01')){
	        	$buttons = "";
	        	$buttons_data = "";
	        	$row->employee_number = $this->Helper->decrypt($row->employee_number,$row->employee_id);
	        	$row->employee_id_number = $this->Helper->decrypt($row->employee_id_number,$row->employee_id);
	        	$row->first_name = $this->Helper->decrypt($row->first_name,$row->employee_id);
	        	$row->middle_name = $this->Helper->decrypt($row->middle_name,$row->employee_id);
	        	$row->last_name = $this->Helper->decrypt($row->last_name,$row->employee_id);
	            $sub_array = array();    
	            $sub_array[] = $row->employee_id_number;
	            $sub_array[] = $row->first_name.' '.$row->last_name;  
	            $sub_array[] = $row->position_name;
	            $sub_array[] = number_format($vl_balance_amt, 3);
	            $sub_array[] = number_format($sl_balance_amt, 3);
	            $data[] = $sub_array;  
	        }
        }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>      $this->LeaveLedgerCollection->get_all_data($employment_status,$employee_id,$pay_basis,$division_id),  
            "recordsFiltered"     	=>     $this->LeaveLedgerCollection->get_filtered_data($employment_status,$employee_id,$pay_basis,$division_id,$location_id),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
	}
	
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
		if(in_array(17003,$_SESSION["sessionModules"])) $self = 1;
		$fetch_data = $this->LeaveLedgerCollection->make_datatables($employment_status,$employee_id,$pay_basis,$division_id,$location_id,$year,$month,$self);
        $data = array();
        foreach($fetch_data as $k => $row) {
        	// if($row->date_of_permanent != "" && strtotime($row->date_of_permanent) <= strtotime($year.'-'.$month.'-31')){
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
	            $buttons .= ' <a id="viewLeaveLedgerForm" ' 
	            		  . ' class="viewLeaveLedgerForm" style="text-decoration: none;" '
	            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewLeaveLedgerForm" '
	            		  . $buttons_data
	            		  . ' > '
	            		  . ' <button class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Leave Card">'
	            		  . ' <i class="material-icons">remove_red_eye</i> '
	            		  . ' </button> '
						  . ' </a> ';
				
				// $buttons .= ' <a href="javascript:void(0);" class="computeLeaveCredits" style="text-decoration: none;" '
	            // 		  . $buttons_data
	            // 		  . ' > '
	            // 		  . ' <button class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Compute Leave Credits">'
	            // 		  . ' <i class="material-icons">exposure</i> '
	            // 		  . ' </button> '
	            // 		  . ' </a> ';
		        $sub_array[] = $buttons;
	            $sub_array[] = $row->employee_id_number;
				$sub_array[] = $row->last_name.', '.$row->first_name.' '.$row->middle_name.' '.$row->extension;
	           
				if($row->position_id != "" && !is_numeric($row->position_id)){
					$sub_array[] = $row->position_id;
				}else{
					$sub_array[] = $row->position_name;
				}

	            $sub_array[] = $row->department_name;
	            $data[] = $sub_array; 
	        }
        // }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>      $this->LeaveLedgerCollection->get_all_data($employment_status,$employee_id,$pay_basis,$division_id,$year,$month,$self),  
            "recordsFiltered"     	=>     $this->LeaveLedgerCollection->get_filtered_data($employment_status,$employee_id,$pay_basis,$division_id,$location_id,$year,$month,$self),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }
	public function viewLeaveLedgerForm(){
		
		Helper::sessionEndedHook('session');
		$result = array();
		$result['key'] = 'viewLeaveLedger';
		$formData = array();
		if (!$this->input->is_ajax_request()) show_404();
		else {
			$ret =  new LeaveLedgerCollection();
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
			if(in_array(17003,$_SESSION["sessionModules"])) $self = 1;
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
			$ledger = $this->generateLedgerData($employment_status,$employee_id,$pay_basis,$division_id,$location_id,$year,$post_year,$month,$date_of_permanent,$present_day,$end_date,$start_date);
			$formData['balance']['vl_balance_amt'] = @$ledger[$year_from-1][sizeof(@$ledger[$year_from-1]) - 1]['vl_balance'];
			$formData['balance']['sl_balance_amt'] = @$ledger[$year_from-1][sizeof(@$ledger[$year_from-1]) - 1]['sl_balance'];
			if(@$ledger[$year_from-1][sizeof(@$ledger[$year_from-1]) - 1]['period'] == ""){
				$formData['balance']['vl_balance_amt'] = @$ledger[$year_from-1][sizeof(@$ledger[$year_from-1]) - 2]['vl_balance'];
			    $formData['balance']['sl_balance_amt'] = @$ledger[$year_from-1][sizeof(@$ledger[$year_from-1]) - 2]['sl_balance'];
			}

			// if($year >= 2020) {
	      		$total_balance = $ret->getLeaveBalance($employee_id, $year);
				if(sizeof($total_balance) > 0) {
					$formData['balance']['vl_balance_amt'] = $total_balance[0]['vl'];
				    $formData['balance']['sl_balance_amt'] = $total_balance[0]['sl'];
				}
			//   }
			
			$formData['year'] = $year;
			$formData['key'] = $result['key'];
			$selected_years = range($year_from,$post_year);
			$formData['remarks'] = $ledger['remarks'];
			foreach ($selected_years as $k1 => $v1) {
				if(isset($ledger[$v1])){
					$formData['ledgers'][] = $ledger[$v1];
				}
			}
			//var_dump($ledger).die();
			$result['form'] = $this->load->view('helpers/employeeleaveledger.php', $formData, TRUE);
			
			//Forms
		}
		echo json_encode($result);
	}
	
	public function generateLedgerData($employment_status,$employee_id,$pay_basis,$division_id,$location_id,$year,$post_year,$month,$date_of_permanent,$present_day, $end_date = "",$start_date){
		$ret =  new LeaveLedgerCollection();
		$month_codes = array("01","02","03","04","05","06","07","08","09","10","11","12");
		// if($year == 2021){
		// 	unset($month_codes[0]);
		// 	unset($month_codes[1]);
		// 	unset($month_codes[2]);
		// }
		$year_permanent = date("Y",strtotime($date_of_permanent));
		if($year_permanent > 2022) $year_range = range($year_permanent,$year);
		else $year_range = range(2022,$year);
		$year_range = range($year_permanent,$year);

		$total_vl_earned = 0;
		$total_sl_earned = 0;
		$total_balance = $ret->getLeaveBalance($employee_id, $year);
		if(sizeof($total_balance) > 0) {
			$total_vl_earned = @$total_balance[0]['vl'];
		    $total_sl_earned = @$total_balance[0]['sl'];
		}
		$ledger = array();
		// foreach($year_range as $yk => $year) {
			$current = strtotime($year.'-01-01');
	        $month_index = 0;
	        if(strtotime($date_of_permanent) > $current){
	        	// $month_of_permanent =(int) date('m',strtotime($date_of_permanent));
				// $month_index = $month_of_permanent - 1;
	        	// $total_vl_earned = 0;
				// $total_sl_earned = 0;
			}
			// $prev_curr_month = ((int)$month - 1);
			// $prev_curr_month = $month != 12 ? ((int)$month - 1):((int)$month);
			$selected_months = array();
			// for($i = $month_index; $i < $prev_curr_month; $i++){
			// // for($i = ($year == 2021) ? 3 : $month_index; $i < ((int) $month); $i++){
			// 	$selected_months[] = $month_codes[$i];
			// }
			for($i = $month_index; $i < ((int) $month); $i++){
				$selected_months[] = $month_codes[$i];
			}
			//Populate Leave Ledger
			
			$forced_count = 0;
			$first = true;
			$arrforce = $arrstudy = $arrmaternity = $arrsolo = $arrcalamity = $arrspecial = $arrrehab = $arrbenefits = $arrviolence = $arrmonet = $arrpaternity = $arrsick = $arrvacation = array();

			$terminal = $ret->getTerminal($year,$employee_id);
			$isTerminal = false;
			$dtfiled = "";
			if($terminal) {
				$isTerminal = true;
				$dtfiled = (int)date("m",strtotime($terminal['is_terminal']));
			}
			foreach ($selected_months as $key => $value) {
				if(($year.'-'.$value.'-01') < date("Y-m",strtotime($start_date)).'-01') continue;
				$total_leaves_per_month = $total_weekend_leaves_per_month = 0;
				$total_leaves_per_month_from_remarks = 0;
				$total_vl_from_monetized = $total_sl_from_monetized = $total_vl_force = 0;
				$haveVl = $haveSl = $total_vl = $total_sl = $time_suspension = 0;
				// if(isset($terminal) && (($dtfiled == (int)$value) && (int)date("Y",strtotime($terminal['date_filed'])) == $year)){
				// }else if(!$terminal || ($dtfiled > $value && date("Y",strtotime($terminal['date_filed'])) <= $year)){
					$tot_vl_leaves = $tot_sl_leaves = 0;
					//Monthly Earning Start
					$a_date = $year.'-'.$value.'-01';
					$z_date = date("t", strtotime($a_date));
					$monthly_data['period'] = date("M. t, Y", strtotime($a_date));
					$monthly_data['particulars'] = "";
					$monthly_data['vl_earned'] = 0;//1.250;
					$monthly_data['sl_earned'] = 0;//1.250;
					$isDTRRequired = $ret->isDTRRequired($employee_id);
					if($first){
						if(strtotime($date_of_permanent) > $current){
							$pemanent_day = date("d", strtotime($date_of_permanent));
							$monthly_data['period'] = date("M. t, Y", strtotime($a_date));
							$monthly_data['particulars'] = "";
							$permanent_date = str_replace('-', '', $date_of_permanent);
							$end_of_month = str_replace('-', '',date("Y-m-t", strtotime($date_of_permanent)));
							$days_of_present = $end_of_month - $permanent_date + 1;
	                        if($isDTRRequired){
	                        	if($isDTRRequired["is_dtr"] == 0){
	                        		$days_of_present = 22;
	                        	}
	                        }
							if($present_day == "half") $days_of_present -= .5;
							// $earned_conversion = $ret->getEarnedConversion($days_of_present);
							$earned_conversion = 0; // to have fixed 1.250 leave earned
							if(sizeof($earned_conversion) > 0){
								$monthly_data['vl_earned'] = $earned_conversion[0]['leave_credits_earned'];
								$monthly_data['sl_earned'] = $earned_conversion[0]['leave_credits_earned'];
							}
						}
					}
					
					// $ledger[$year][] = $monthly_data;
					
					$arrShiftSchedule = $arrShiftHistory = array();
					$shiftDetails = $ret->getShiftDetails($employee_id);
					$shiftType = $shiftId= 0;
					// shift date effectivity
					if(strtotime($year."-".$month."-31") >= strtotime(date("m",strtotime($shiftDetails["shift_date_effectivity"])))) {
						$shiftId = ($shiftDetails["regular_shift"] == 1) ? $shiftDetails["shift_id"] : $shiftDetails["flex_shift_id"];
						$shiftType = $shiftDetails["regular_shift"];
					}else{
						$arrShiftHistory = $ret->getShiftHistory($shiftDetails["id"]);
						foreach($arrShiftHistory as $k => $v){
							if($month >= date("m", strtotime($v["previous_date_effectivity"]))){
								$shiftId = $v["shift_id"];
								$shiftType = $v["shift_type"];
								break;
							}
						}
					}
					if($shiftType == 1) $arrShiftSchedule = $ret->getRegularShiftSchedule($shiftId);
					else $arrShiftSchedule = $ret->getFlexibleShiftSchedule($shiftId);

					$employee_working_days_schedule = array_column($arrShiftSchedule, 'week_day');
					//Monthly Earning End
					$get_working_days = $ret->countDays($year, $value, array(0, 6),$terminal,$employee_working_days_schedule);
					$holiday_attended = $ret->countHollidayAttended($year, $value,$employee_working_days_schedule,$employee_id);
					$tot_absent = $get_working_days;

					//UTime Posting Start
					$ut_time = $ret->getUndertTime($value,$employee_id,$year, $isTerminal, $dtfiled, @$terminal['is_terminal']);
					// $utime = $ret->getUTime($value,$employee_id,$year);
					$tot_ut_conversions = $tot_utime_mins = $tot_utime_hrs = $tot_utime_days = 0;
					$tot_utime = "";
					$process_absent = $process_tardiness_hrs = $process_tardiness_mins = $process_ut_hrs = $process_ut_mins = $process_offset_days = $process_offset_hrs = $process_offset_mins = 0;
					if($ut_time["scanning_no"] != null){
						$tot_absent -= (abs($ut_time["no_days"]) + @$ut_time["offset_days"]);
						$tot_absent += $holiday_attended;
						// $tot_absent += @$ut_time["no_days_holiday"];
						$tot_absent = $tot_absent < 0 ? 0 : $tot_absent;
						$tot_utime_mins = fmod($ut_time["tardiness_mins"] + $ut_time["ut_mins"], 60);
						$tot_utime_hrs	= fmod(floor(($ut_time["tardiness_mins"] + $ut_time["ut_mins"])/60) + ($ut_time["tardiness_hrs"] + $ut_time["ut_hrs"]), 8);
						$tot_utime_days = floor((floor(($ut_time["tardiness_mins"] + $ut_time["ut_mins"])/60) + ($ut_time["tardiness_hrs"] + $ut_time["ut_hrs"])) /8);
						$tot_utime_mins -= $ut_time["offset_mins"];
						if($tot_utime_mins < 0){
                        	$tot_utime_mins = 60 - $tot_utime_mins;
                            $tot_utime_hrs -= 1;
                        }
                        // if($tot_utime_hrs > 0) $tot_utime_hrs -= $ut_time["offset_hrs"];
                        if($tot_utime_hrs < 0){
                        	$tot_utime_hrs = 8 - abs($tot_utime_hrs);
                        	$tot_utime_days -= 1;
                        }
                        if($tot_utime_days < 0) $tot_utime_days = 0;
                        if($isDTRRequired){
                        	if($isDTRRequired["is_dtr"] == 0){
                        		$tot_absent = $tot_utime_days = $tot_utime_hrs = $tot_utime_mins = 0;
                        	}
                        }
						
						$tot_utime = $tot_utime_days."° ".$tot_utime_hrs."' ".$tot_utime_mins.'"';

						$conversions = $ret->getUTConvertsions($tot_utime_hrs,$tot_utime_mins);
						$tot_ut_conversions = number_format($tot_utime_days + $conversions, 3);
					}
					if($isDTRRequired){
						if($isDTRRequired["is_dtr"] == 0){
							$tot_absent = $tot_utime_days = $tot_utime_hrs = $tot_utime_mins = 0;
						}
					}
					
					$todays_year = date("Y");	
					$todays_month = date("m");
					// if($year == $todays_year && $value != $todays_month)$tot_ut_conversions = 0; $tot_absent = 0;
					$holidays = $ret->getHolidays($value,$employee_id,$year);
					$haveSuspension = $ret->getWorkSuspension($value,$year);
					$haveLeave = $ret->getLeaveIsMonth($value,$employee_id,$year);

					$haveUtime = false;
					$invalidEndDate = array("0000-00-00","1970-01-01");
					$monthly_data['vl_earned'] = ($employee_id == "5d9b767115646" || ($end_date && (!in_array($end_date, $invalidEndDate) && strtotime($end_date) <= strtotime(date("Y-m-d"))))) ? 0 : 1.250;
					$monthly_data['sl_earned'] = ($employee_id == "5d9b767115646" || ($end_date && (!in_array($end_date, $invalidEndDate) && strtotime($end_date) <= strtotime(date("Y-m-d"))))) ? 0 : 1.250;
					// $monthly_data['vl_earned'] = ($employment_status == "Active" || $employee_id == "5d9b767115646") ? 1.250 : 0;
					// $monthly_data['sl_earned'] = ($employment_status == "Active" || $employee_id == "5d9b767115646") ? 1.250 : 0;
					if(($tot_ut_conversions > 0) || sizeof($haveLeave) > 0){
						if($tot_ut_conversions > 0) $haveUtime = true;
					}
					//Leave Posting Start
					$leave_count = $leave_remarks_count = 0;
					$earned = 1.250;
					
	        		$month_of_permanent =(int) date('m',strtotime($date_of_permanent));
					if($month_of_permanent == $value && $year_permanent == $year){
						$last_day = $date = new DateTime('last day of '.$year.'-'.$value);
						$last_day = json_decode(json_encode($last_day), true);
						$last_day = $last_day['date'];
						$interval = $this->dateDiff($last_day,$date_of_permanent);						
						$ret_deduc_earned_conversion = $ret->getEarnedConversion($interval+1);
						$earned = $ret_deduc_earned_conversion[0]['leave_credits_earned'];
					}
					$tot_rehab_days = 0;
					$unique_day= [];
					$monet_unique_day = [];
					$rehab_unique_day = [];
					$spec_leave = 0;
					$vlsl = 0;
					$with_particulars_leaves = 0;

					if(end($selected_months) == $value){
						if($ut_time["no_days_upload_dtr"] == 0 && (int)date("d") < 4){
							$haveUtime = false;
							$monthly_data['vl_earned'] = $monthly_data['sl_earned'] = $tot_ut_conversions = $tot_absent = $earned = 0;
						}
					}

					$leave_types = array("maternity","paternity","solo","study","violence","benefits","special","covid","calamity","vacation","force","sick","monetization","rehab");
					foreach ($leave_types as $leave_key => $type) {
						$tot_leave_per_type = 0;
						$leaves = $ret->getLeave($value,$employee_id,$year,$type);
						$remarks = "";
						switch($type){
							case "vacation": $desc = "VL";break;
							case "sick": $desc = "SL"; break;
							case "force": $desc = "FL"; break;
							case "special": $desc = "SPL"; break;
							case "covid": $desc = "CV"; break;
							default: $desc = $type; break;
						}
						if($type == "monetization"){
							$monet = $ret->getMonetization($value,$year,$employee_id);
							if($monet && sizeof($monet)>0){
								$leave_count++;
								$vl_deduc = $sl_deduc = 0;
								foreach($monet as $k1 => $v1){

									$tot_vl_used = $tot_sl_used = 0;
									$tot_vl_rem = $tot_sl_rem = 0;
									$tot_vl_tmp = $tot_sl_tmp = 0;
									$tot_vl_putal = $tot_sl_putal = 0;
									$no_days = (int)$v1['number_of_days'];

									$tot_vl_putal = $total_vl_earned - floor($total_vl_earned);
									$tot_sl_putal = $total_sl_earned - floor($total_sl_earned);
									$tot_vl_tmp = $tot_vl_rem = floor($total_vl_earned);
									$tot_sl_tmp = $tot_sl_rem = floor($total_sl_earned);

									// monetized not for medical reason
									if($v1['isMedical'] == 0){
										$tot_vl_rem = $tot_vl_tmp - $no_days;
										$tot_vl_used = $tot_vl_rem <= 0 ? $tot_vl_tmp : $no_days;
										if($tot_vl_rem < 0 && $tot_sl_tmp > 0){
											$tot_sl_rem = $tot_sl_tmp - abs($tot_vl_rem);
											$tot_sl_used = $tot_sl_rem <= 0 ? $tot_sl_tmp : $tot_vl_rem;
										}
									}else{
										$tot_sl_rem = $tot_sl_tmp - $no_days;
										$tot_sl_used = $tot_sl_rem <= 0 ? $tot_sl_tmp : $no_days;
										if($tot_sl_rem < 0 && $tot_vl_tmp > 0){
											$tot_vl_rem = $tot_vl_tmp - abs($tot_sl_rem);
											$tot_vl_used = $tot_vl_rem <= 0 ? $tot_vl_tmp : $tot_sl_rem;
										}
									}
									$total_vl_from_monetized += $tot_vl_used;
									$total_sl_from_monetized += $tot_sl_used;
									$tot_vl_tmp = $tot_vl_rem <= 0 ? 0 : $tot_vl_rem;
									$tot_sl_tmp = $tot_sl_rem <= 0 ? 0 : $tot_sl_rem;

									$total_vl_earned = $tot_vl_tmp + $tot_vl_putal;
									$total_sl_earned = $tot_sl_tmp + $tot_sl_putal;
									$total_leaves_per_month_from_remarks += $tot_vl_used;
									// $total_leaves_per_month_from_remarks += $tot_sl_used;

									array_push($arrmonet,
										date('M. d, Y', strtotime($v1['date_filed']))."<br>".
										($tot_vl_used > 0 ? $tot_vl_used." days c/o VL<br>" : "").
										($tot_sl_used > 0 ? $tot_sl_used." days c/o SL<br>" : "").
										"<b>P ".$v1['amount_monetized']."</b>"
									);
								}
							}
						}else{
							if(sizeof($leaves) > 0){
								$leave_count++;
								$particulars = "<div style='width:100%;text-align:left;'>".$desc." ";
								
											
								foreach ($leaves as $k1 => $v1) {
									if($type == "force") $forced_count += $v1['number_of_days'];
									if($v1['status'] == "REJECTED") $remarks = $v1['remarks'];
									$days_of_leave = $ret->getLeaveDays($value,$year,$employee_id,$type);
									
									//Particulars for Different month
									if($type != "rehab" && $type != "maternity" && $type != "paternity" && $type != "study" && $type != "benefits" && $type != "violence" && $type != "monetization"){
										if(sizeof($days_of_leave) > 0){
											$first = true;
											foreach ($days_of_leave as $k2 => $v2) {
												switch($type){
													case "force": array_push($arrforce,date('M. d, Y', strtotime($v2['days_of_leave']))); break;
													case "solo": array_push($arrsolo,date('M. d, Y', strtotime($v2['days_of_leave']))); break;
													case "calamity": array_push($arrcalamity,date('M. d, Y', strtotime($v2['days_of_leave']))); break;
													case "special": array_push($arrspecial,date('M. d, Y', strtotime($v2['days_of_leave']))); break;
													case "sick": array_push($arrsick,date('M. d, Y', strtotime($v2['days_of_leave']))); break;
													case "vacation": array_push($arrvacation,date('M. d, Y', strtotime($v2['days_of_leave']))); break;
												}
												if($first){
													$particulars .= "(".sizeof($days_of_leave).") ".date('d', strtotime($v2['days_of_leave']));
													$first = false;
												}else $particulars.= ', '.date('d', strtotime($v2['days_of_leave']));
												$tot_vl_leaves += sizeof($days_of_leave);
												if($type != "rehab" && !in_array($value.date('d', strtotime($v2['days_of_leave'])), $unique_day)){
													$total_leaves_per_month++;
													if($type == "force") {
														// $total_vl_earned = (float)$total_vl_earned - 1; 
														$total_vl_force++;
														$tot_absent--;
													}
													if($type != "vacation" && $type != "sick" && $type != "force" && $type != "covid") $total_leaves_per_month_from_remarks++;
													// in_array($type, ['special','calamity','solo']);
													array_push($unique_day,$value.date('d', strtotime($v2['days_of_leave'])));
												}
											}
										}
									}else{
										$spf_dys = [];
										$unique_day= [];
										$days = 0;
										foreach ($days_of_leave as $k2 => $v2) {
											$days = 0;
											$spf_days = [];
											$rehabdates = explode(" - ",$days_of_leave[$k2]['days_of_leave']);
											if(sizeof($rehabdates) > 1){
												$start = new DateTime($rehabdates[0]);
												$end = new DateTime($rehabdates[1]);
												$end->modify('+1 day');
												$interval = $end->diff($start);
												// $days = $interval->days;
												$period = new DatePeriod($start, new DateInterval('P1D'), $end);
												// $holidays = array('2020-09-11');
												$fst = true;
												foreach($period as $dt) {
													$curr = $dt->format('l');
													if($dt->format('M') == date('M', strtotime(date('Y')."-".$value."-01"))){
														array_push($spf_days, $dt->format('d'));
														if($type != "rehab"){
															if(!in_array($value.$dt->format('d'),$unique_day)) {
																if(in_array($curr, $employee_working_days_schedule)){ 
																	$total_leaves_per_month++;
																	$total_leaves_per_month_from_remarks++;
																}
																array_push($unique_day,$value.$dt->format('d'));
															}
														}else if($type == "rehab") {
															if(in_array($curr, $employee_working_days_schedule) && !in_array($value.$dt->format('d'), $rehab_unique_day)) {
																$tot_rehab_days++;
																array_push($rehab_unique_day,$value.$dt->format('d'));
															}
														}
														$days++;
													}
													$fst = false;
												}
												$spfd = (sizeof($spf_days) == 1) ? reset($spf_days) : reset($spf_days) . " - " . end($spf_days);
												if(!in_array($spfd, $spf_dys)) array_push($spf_dys, $spfd);
												switch($type){
													case "study": 
														if(!in_array(date('M. d, Y', strtotime($rehabdates[0])) ." - ". date('M. d, Y', strtotime($rehabdates[1])),$arrstudy)){
															array_push($arrstudy,date('M. d, Y', strtotime($rehabdates[0])) ." - ". date('M. d, Y', strtotime($rehabdates[1])));
														}
													break;
													case "maternity": 
														if(!in_array(date('M. d, Y', strtotime($rehabdates[0])) ." - ". date('M. d, Y', strtotime($rehabdates[1])),$arrmaternity)){
															array_push($arrmaternity,date('M. d, Y', strtotime($rehabdates[0])) ." - ". date('M. d, Y', strtotime($rehabdates[1])));
														}
													break;
													case "paternity": 
														if(!in_array(date('M. d, Y', strtotime($rehabdates[0])) ." - ". date('M. d, Y', strtotime($rehabdates[1])),$arrpaternity)){
															array_push($arrpaternity,date('M. d, Y', strtotime($rehabdates[0])) ." - ". date('M. d, Y', strtotime($rehabdates[1])));
														}
													break;
													case "rehab": 
														if(!in_array(date('M. d, Y', strtotime($rehabdates[0])) ." - ". date('M. d, Y', strtotime($rehabdates[1])),$arrrehab)){
															array_push($arrrehab,date('M. d, Y', strtotime($rehabdates[0])) ." - ". date('M. d, Y', strtotime($rehabdates[1])));
														}
													break;
													case "benefits": 
														if(!in_array(date('M. d, Y', strtotime($rehabdates[0])) ." - ". date('M. d, Y', strtotime($rehabdates[1])),$arrbenefits)){
															array_push($arrbenefits,date('M. d, Y', strtotime($rehabdates[0])) ." - ". date('M. d, Y', strtotime($rehabdates[1])));
														}
													break;
													case "violence": 
														if(!in_array(date('M. d, Y', strtotime($rehabdates[0])) ." - ". date('M. d, Y', strtotime($rehabdates[1])),$arrviolence)){
															array_push($arrviolence,date('M. d, Y', strtotime($rehabdates[0])) ." - ". date('M. d, Y', strtotime($rehabdates[1])));
														}
													break;
												}
											}else{
												if($type != "rehab"){
													if(!in_array($value.date("d",strtotime($rehabdates[0])),$unique_day)) {
														$total_leaves_per_month++;
														$total_leaves_per_month_from_remarks++;
														array_push($unique_day,$value.date("d",strtotime($rehabdates[0])));
													}
												}else if($type == "rehab") {
													if(!in_array($value.date("d",strtotime($rehabdates[0])), $rehab_unique_day)) {
														$tot_rehab_days++;
														array_push($rehab_unique_day,$value.$dt->format('d'));
													}
												}
												array_push($spf_dys, date("d",strtotime($rehabdates[0])));
												$days++;
												switch($type){
													case "study": 
														if(!in_array(date('M. d, Y', strtotime($rehabdates[0])),$arrstudy)){
															array_push($arrstudy, date("M. d, Y",strtotime($rehabdates[0])));
														}
													break;
													case "maternity": 
														if(!in_array(date('M. d, Y', strtotime($rehabdates[0])),$arrmaternity)){
															array_push($arrmaternity, date("M. d, Y",strtotime($rehabdates[0])));
														}
													break;
													case "paternity": 
														if(!in_array(date('M. d, Y', strtotime($rehabdates[0])),$arrpaternity)){
															array_push($arrpaternity, date("M. d, Y",strtotime($rehabdates[0])));
														}
													break;
													case "rehab": 
														if(!in_array(date('M. d, Y', strtotime($rehabdates[0])),$arrrehab)){
															array_push($arrrehab, date("M. d, Y",strtotime($rehabdates[0])));
														}
													break;
													case "benefits": 
														if(!in_array(date('M. d, Y', strtotime($rehabdates[0])),$arrbenefits)){
															array_push($arrbenefits, date("M. d, Y",strtotime($rehabdates[0])));
														}
													break;
													case "violence": 
														if(!in_array(date('M. d, Y', strtotime($rehabdates[0])),$arrviolence)){
															array_push($arrviolence, date("M. d, Y",strtotime($rehabdates[0])));
														}
													break;
													case "monetization": 
														if(!in_array(date('M. d, Y', strtotime($rehabdates[0])),$arrmonet)){
															array_push($arrmonet, date("M. d, Y",strtotime($rehabdates[0])));
														}
													break;
												}
											}
										}
										$particulars .= "(".$days.") ".implode(", ",$spf_dys);
									}
									$particulars.="</div>";
								}
								$monthly_data['period'] = date("M. t, Y", strtotime($a_date));//$monthly_data['period'] = "";
								$monthly_data['particulars'] = $particulars;
								if($type == "benefits" || $type == "rehab"){
									$with_particulars_leaves++;
									$deduc_earned_conversion = 0;
									if($type== "rehab" && $tot_rehab_days > 0) {
										//$ret_deduc_earned_conversion = $ret->getEarnedConversion($tot_rehab_days);
										$ret_deduc_earned_conversion = 0; // to have fixed 1.250 leave earned
										if($ret_deduc_earned_conversion) $deduc_earned_conversion = $ret_deduc_earned_conversion[0]['leave_credits_earned'];
										$earned -= $deduc_earned_conversion;
									}
									//VL
									if(sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0){
										$monthly_data['vl_earned'] = ($earned - $deduc_earned_conversion);
										$monthly_data['sl_earned'] = ($earned - $deduc_earned_conversion);
									}else{
										$monthly_data['vl_earned'] = "";
										$monthly_data['sl_earned'] = "";
									}
									// $monthly_data['vl_earned'] = (sizeof($haveLeave) == $leave_count && $haveUtime == false)? (1.25 - $deduc_earned_conversion): "";
									$total_vl_earned += (sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $monthly_data['vl_earned'] : 0;
									// $monthly_data['vl_a_utime_w_pay'] = ((sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $total_vl_from_monetized + $total_vl_force : 0) + $deduc_earned_conversion;
									$monthly_data['vl_a_utime_w_pay'] = ((sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $total_vl_from_monetized : 0) + $deduc_earned_conversion;
									$monthly_data['vl_balance'] = (sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $total_vl_earned : "";
									$monthly_data['vl_a_utime_wo_pay'] = "";
									//SL
									$monthly_data['sl_earned'] = (sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $earned : 0;
									$total_sl_earned += (sizeof($haveLeave) == $leave_count && $haveUtime == false)? $monthly_data['sl_earned'] : 0;
									$monthly_data['sl_a_utime_w_pay'] = ((sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $total_sl_from_monetized : 0) + $deduc_earned_conversion;
									$monthly_data['sl_balance'] = (sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $total_sl_earned : "";
									$monthly_data['sl_a_utime_wo_pay'] = "";
									$monthly_data['remarks'] = $remarks;

									if(sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0){
										$balanceasof = $ret->getBalanceAsOf($value,$employee_id,$year);
										if($balanceasof){$monthly_data['particulars'] = $monthly_data['vl_earned'] = $monthly_data['vl_a_utime_w_pay'] = $monthly_data['sl_earned'] = $monthly_data['sl_a_utime_w_pay'] = "";
											$total_vl_earned = $balanceasof["vl"];
											$total_sl_earned = $balanceasof["sl"];
											$monthly_data['vl_balance'] = $total_vl_earned;
											$monthly_data['sl_balance'] = $total_sl_earned;
											$monthly_data['vl_earned'] = "";
											$monthly_data['sl_earned'] = "";
											$monthly_data['vl_a_utime_wo_pay'] = "";
											$monthly_data['sl_a_utime_wo_pay'] = "";
										}
									}
									// if(($year == 2021 && $value >= 4) || $year > 2021)
										$ledger[$year][] = $monthly_data;
								} else{
									if($type == "vacation"){
										$with_particulars_leaves++;
										$haveVl++;
										foreach ($haveSuspension as $suspension) {
											if(in_array(date('M. d, Y', strtotime($suspension['date'])), $arrvacation)){
												if(strpos($suspension['time_suspension'], "AM") !== false){
													$time_suspension = 1;
												}else{
													$time_suspension = 0.5;
												}											
											}
										}
										//VL
										$converted_hr = $ret->getConversionFractions(8,"hr");
									
										$vl_a_utime_w_pay = ($v1['number_of_days'] * $converted_hr[0]['equiv_day']) - $time_suspension;
										// $monthly_data['vl_a_utime_w_pay'] = ((sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $total_vl_from_monetized + $total_vl_force : 0) + $vl_a_utime_w_pay;
										$monthly_data['vl_a_utime_w_pay'] = ((sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $total_vl_from_monetized : 0) + $vl_a_utime_w_pay;
										$total_vl = $monthly_data['vl_a_utime_w_pay'];
										$tot_absent -= $monthly_data['vl_a_utime_w_pay'];
										$vlsl += $monthly_data['vl_a_utime_w_pay'];


										$monthly_data['vl_earned'] = (sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $earned : "";
										$total_vl_earned += (sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $monthly_data['vl_earned'] : 0;

										$total_vl_earned -= $monthly_data['vl_a_utime_w_pay'];
										$vl_a_utime_wo_pay = "";
										if($total_vl_earned < 0){
											$total_vl_earned = 0;
										}										

										$monthly_data['vl_balance'] = $total_vl_earned;
										$monthly_data['vl_a_utime_wo_pay'] = $vl_a_utime_wo_pay;
										//SL
										$monthly_data['sl_a_utime_w_pay'] = ((sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $total_sl_from_monetized : 0);
										$monthly_data['sl_earned'] = (sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $earned : "";
										$total_sl_earned += (sizeof($haveLeave) == $leave_count && $haveUtime == false && $monthly_data['sl_earned'])? $monthly_data['sl_earned'] : 0;
										$monthly_data['sl_balance'] = (sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $total_sl_earned : "";
										$monthly_data['sl_a_utime_wo_pay'] = "";
										$monthly_data['remarks'] = $remarks;

										if(sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0){
											$balanceasof = $ret->getBalanceAsOf($value,$employee_id,$year);
											if($balanceasof){$monthly_data['particulars'] = $monthly_data['vl_earned'] = $monthly_data['vl_a_utime_w_pay'] = $monthly_data['sl_earned'] = $monthly_data['sl_a_utime_w_pay'] = "";
												$total_vl_earned = $balanceasof["vl"];
												$total_sl_earned = $balanceasof["sl"];
												$monthly_data['vl_balance'] = $total_vl_earned;
												$monthly_data['sl_balance'] = $total_sl_earned;
												$monthly_data['vl_earned'] = "";
												$monthly_data['sl_earned'] = "";
												$monthly_data['vl_a_utime_wo_pay'] = "";
												$monthly_data['sl_a_utime_wo_pay'] = "";
											}
										}
										// if(($year == 2021 && $value >= 4) || $year > 2021)
											$ledger[$year][] = $monthly_data;
										$tot_vl_leaves += $v1['number_of_days'];
									}
									else if($type == "force"){
										// $with_particulars_leaves++;
										// $haveVl++;
										foreach ($haveSuspension as $suspension) {
											if(in_array(date('M. d, Y', strtotime($suspension['date'])), $arrforce)){
												if(strpos($suspension['time_suspension'], "AM") !== false){
													$time_suspension = 1;
												}else{
													$time_suspension = 0.5;
												}											
											}
										}
										//FL
										$converted_hr = $ret->getConversionFractions(8,"hr");
										$vl_a_utime_w_pay = ($v1['number_of_days'] * $converted_hr[0]['equiv_day']) - $time_suspension;
										// $monthly_data['vl_a_utime_w_pay'] = ((sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $total_vl_from_monetized + $total_vl_force : 0) + $vl_a_utime_w_pay;
										$monthly_data['vl_a_utime_w_pay'] = ((sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $total_vl_from_monetized : 0) + $vl_a_utime_w_pay;
										$total_vl = $monthly_data['vl_a_utime_w_pay'];
										$tot_absent -= $monthly_data['vl_a_utime_w_pay'];
										$vlsl += $monthly_data['vl_a_utime_w_pay'];

										$monthly_data['vl_earned'] = (sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $earned : "";
										$total_vl_earned += (sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $monthly_data['vl_earned'] : 0;
										
										$total_vl_earned -= $monthly_data['vl_a_utime_w_pay'];
										$vl_a_utime_wo_pay = "";
										if($total_vl_earned < 0){
											$total_vl_earned = 0;
										}										
										$monthly_data['vl_balance'] = $total_vl_earned;
										$monthly_data['vl_a_utime_wo_pay'] = $vl_a_utime_wo_pay;
										//SL
										$monthly_data['sl_a_utime_w_pay'] = ((sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $total_sl_from_monetized : 0);
										$monthly_data['sl_earned'] = (sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $earned : "";
										$total_sl_earned += (sizeof($haveLeave) == $leave_count && $haveUtime == false && $monthly_data['sl_earned'])? $monthly_data['sl_earned'] : 0;
										$monthly_data['sl_balance'] = (sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $total_sl_earned : "";
										$monthly_data['sl_a_utime_wo_pay'] = "";
										$monthly_data['remarks'] = $remarks;

										if(sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0){
											$balanceasof = $ret->getBalanceAsOf($value,$employee_id,$year);
											if($balanceasof){$monthly_data['particulars'] = $monthly_data['vl_earned'] = $monthly_data['vl_a_utime_w_pay'] = $monthly_data['sl_earned'] = $monthly_data['sl_a_utime_w_pay'] = "";
												$total_vl_earned = $balanceasof["vl"];
												$total_sl_earned = $balanceasof["sl"];
												$monthly_data['vl_balance'] = $total_vl_earned;
												$monthly_data['sl_balance'] = $total_sl_earned;
												$monthly_data['vl_earned'] = "";
												$monthly_data['sl_earned'] = "";
												$monthly_data['vl_a_utime_wo_pay'] = "";
												$monthly_data['sl_a_utime_wo_pay'] = "";
											}
										}
										// if(($year == 2021 && $value >= 4) || $year > 2021)
											$ledger[$year][] = $monthly_data;
										// $tot_vl_leaves += $v1['number_of_days'];
									}

									//Sick Leave
									else if($type == "sick"){
										$with_particulars_leaves++;
										$haveSl++;
										foreach ($haveSuspension as $suspension) {
											if(in_array(date('M. d, Y', strtotime($suspension['date'])), $arrsick)){
												if(strpos($suspension['time_suspension'], "AM") !== false){
													$time_suspension = 1;
												}else{
													$time_suspension = 0.5;
												}											
											}
										}
										//SL
										$converted_hr = $ret->getConversionFractions(8,"hr");
										$sl_a_utime_w_pay = ($v1['number_of_days'] * $converted_hr[0]['equiv_day']) - $time_suspension;

										$tot_absent -= $sl_a_utime_w_pay;
										$vlsl += $sl_a_utime_w_pay;

										$monthly_data['sl_earned'] = (sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $earned : 0;
										$total_sl_earned += (sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $monthly_data['sl_earned'] : 0;
										$putal = $total_sl_earned - floor($total_sl_earned);
										$total_sl_earned = $tmp_total_sl_earned = floor($total_sl_earned);
										$total_sl_earned -= $sl_a_utime_w_pay;
										$rem_sl_tobe_deduc_to_vl = 0;

										//VL
										if($total_sl_earned < 0){
											$total_vl_earned -= abs($total_sl_earned);
											$rem_sl_tobe_deduc_to_vl = abs($total_sl_earned);
											if($total_vl_earned < 0){
												$total_vl_earned = 0;
												$sl_a_utime_wo_pay = abs($total_sl_earned);
											}
											$total_sl_earned = 0;
										}

										$monthly_data['vl_earned'] = (sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $earned : 0;
										$total_vl_earned += (sizeof($haveLeave) == $leave_count && $haveUtime == false)? $monthly_data['vl_earned'] : 0;
										$monthly_data['vl_a_utime_w_pay'] = $total_vl_earned <= 0 ? $tmp_total_sl_earned - $sl_a_utime_w_pay : $rem_sl_tobe_deduc_to_vl;
										// $monthly_data['vl_a_utime_w_pay'] += ((sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $total_vl_from_monetized + $total_vl_force : 0);
										$monthly_data['vl_a_utime_w_pay'] += ((sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $total_vl_from_monetized : 0);
										$monthly_data['vl_balance'] = (sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $total_vl_earned : "";
										$monthly_data['vl_a_utime_wo_pay'] = "";
										
										$sl_a_utime_wo_pay = "";
										$sl_a_utime_w_pay += ((sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $total_sl_from_monetized : 0);
										$monthly_data['sl_a_utime_w_pay'] = $total_sl_earned <= 0 ? $tmp_total_sl_earned : $sl_a_utime_w_pay;
										$total_sl = $monthly_data['sl_a_utime_w_pay'];
										$total_sl_earned = $putal + $total_sl_earned;
										$monthly_data['sl_balance'] = $total_sl_earned;
										$monthly_data['sl_a_utime_wo_pay'] = $sl_a_utime_wo_pay;
										$monthly_data['remarks'] = $remarks;

										// var_dump($total_vl_force);
										if(sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0){
											$balanceasof = $ret->getBalanceAsOf($value,$employee_id,$year);
											if($balanceasof){$monthly_data['particulars'] = $monthly_data['vl_earned'] = $monthly_data['vl_a_utime_w_pay'] = $monthly_data['sl_earned'] = $monthly_data['sl_a_utime_w_pay'] = "";
												$total_vl_earned = $balanceasof["vl"];
												$total_sl_earned = $balanceasof["sl"];
												$monthly_data['vl_balance'] = $total_vl_earned;
												$monthly_data['sl_balance'] = $total_sl_earned;
												$monthly_data['vl_earned'] = "";
												$monthly_data['sl_earned'] = "";
												$monthly_data['vl_a_utime_wo_pay'] = "";
												$monthly_data['sl_a_utime_wo_pay'] = "";
											}
										}
										// if(($year == 2021 && $value >= 4) || $year > 2021)
											$ledger[$year][] = $monthly_data;
										$tot_sl_leaves += $v1['number_of_days'];
									}
								}
								
							}
						}
					}
					//Leave Posting End

					//UTime Posting Start
					$tmp_tot_ut_conversions = $tot_ut_conversions;
					if((float)$tot_ut_conversions > 0 || $tot_absent > 0){
						// $particulars = "<div style='width:100%;text-align:left'>". ($tot_absent > 0 ? "A ".($tot_absent - ($tot_rehab_days + $spec_leave))."<br>":"" ).($tot_utime != "" ?"UT ". $tot_utime:"") ."</div>";
						$particulars = "<div style='width:100%;text-align:left'>". ($tot_absent > 0 ? "A ".($tot_absent - ($tot_rehab_days + $spec_leave))."<br>":"" ).($tot_utime != "" && ($tot_utime_days != 0 || $tot_utime_hrs != 0 || $tot_utime_mins != 0) ?"UT ". $tot_utime:"") ."</div>";
						
						$monthly_data['period'] = date("M. t, Y", strtotime($a_date));//$monthly_data['period'] = "";
						$monthly_data['particulars'] = $particulars;
						$vl_a_utime_w_pay = 0;
						$vl_decimals = $sl_decimals = 0;
						$total_u_item_with_leaves = ($total_leaves_per_month - $vlsl);//$total_leaves_per_month  
						$total_u_item_with_leaves = ($total_u_item_with_leaves > 0) ? $total_u_item_with_leaves : 0;
						
						$monthly_data['vl_a_utime_w_pay'] = 0;
						$monthly_data['vl_a_utime_wo_pay'] =  0;
						// $eeerrr = $total_leaves_per_month + $ut_time["no_days"] + $get_working_days["ends"];
						// if($value == 2){ $eeerrr += 2;}
						// $add_earn_from_present = $ret->getEarnedConversion($eeerrr > 31 ? 31 : $eeerrr);
						// $earned = @$add_earn_from_present[0]['leave_credits_earned'];
						if($total_u_item_with_leaves > 0) {
							// $deduc_earn_from_absent = $ret->getEarnedConversion($total_u_item_with_leaves);
							$deduc_earn_from_absent = 0; // to have fixed 1.250 leave earned
							$earned -= @$deduc_earn_from_absent[0]['leave_credits_earned'];
						}
						$total_vl_earned += $earned;
						
						if($tot_ut_conversions > 0){
							if($total_vl_earned > 0){
									$tmp_vl = $total_vl_earned;
									$rem_tot_ut_conversion = $tot_ut_conversions -  $total_vl_earned;
									$total_vl_earned -= $tot_ut_conversions;
									$total_vl_earned = $total_vl_earned > 0 ? $total_vl_earned : 0;
									$monthly_data['vl_a_utime_w_pay'] = $total_vl_earned > 0 ? $tot_ut_conversions : $tot_ut_conversions + abs($rem_tot_ut_conversion);//$tmp_vl + $tot_ut_conversions;
									$tot_ut_conversions -= $total_vl_earned;
									if($tot_ut_conversions < 0) $tot_ut_conversions = 0;
									$monthly_data['vl_a_utime_wo_pay'] = $total_vl_earned > 0 ? 0 : abs($rem_tot_ut_conversion);//$tot_ut_conversions;
								$monthly_data['vl_balance'] = $total_vl_earned;
							}else $monthly_data['vl_a_utime_wo_pay'] = $tot_ut_conversions;
						}

						//VL
						// $total_vl_earned += $earned; // earn muna 1.25 bago ibawas ang mga absent
						$total_u_item_with_leaves_with_earned_with_leave_credits = $tmp_total_vl_earned = $tmp_total_u_item_with_leaves = 0;
						if($total_u_item_with_leaves > 0){
							$tmp_total_u_item_with_leaves = $total_u_item_with_leaves;
							$tmp_total_vl_earned = floor($total_vl_earned);
							if($tmp_total_vl_earned > 0){
								$vl_decimals = $total_vl_earned - floor($total_vl_earned);
								$total_vl_earned = $tmp_total_vl_earned - $total_u_item_with_leaves;
								$total_u_item_with_leaves = $total_u_item_with_leaves - $tmp_total_vl_earned;
								$total_u_item_with_leaves = $total_u_item_with_leaves > 0 ? $total_u_item_with_leaves : 0;
								$total_vl_earned = $total_vl_earned > 0 ? $total_vl_earned : 0;
							}else $monthly_data['vl_a_utime_wo_pay'] += $total_u_item_with_leaves;
						}
						$vl_a_utime_wo_pay = ($total_u_item_with_leaves > 0)?$total_u_item_with_leaves:0;
						$vl_a_utime_wo_pay += $tot_absent;
						$monthly_data['vl_a_utime_w_pay'] += $total_vl_from_monetized + ($total_u_item_with_leaves > 0 ? $tmp_total_vl_earned : $tmp_total_u_item_with_leaves);//$total_leaves_per_month_from_remarks + $vl_a_utime_w_pay;
						// if($haveVl>0) $monthly_data['vl_a_utime_w_pay'] -= $total_vl;
						if($total_vl_from_monetized != $total_leaves_per_month_from_remarks)
						$monthly_data['vl_a_utime_w_pay'] -= ($monthly_data['vl_a_utime_w_pay'] <= 0)? 0 : $total_leaves_per_month_from_remarks;
						$total_vl_earned += $vl_decimals;
						$monthly_data['vl_earned'] = $earned;
						$monthly_data['vl_balance'] = $total_vl_earned;
						
						//SL
						$sl_tot_deduc = $tmp_total_vl_earned = 0;
						// $total_sl_earned += 1.250; //earn muna bago ibawas ang mga absent
						$total_sl_earned += $earned;
						// if($vl_a_utime_wo_pay > 0 && $total_sl_earned > 0){
						// 	$sl_tot_deduc = $vl_a_utime_wo_pay;
						// 	$tmp_total_vl_earned = floor($total_sl_earned);
						// 	$sl_decimals = $total_sl_earned - floor($total_sl_earned);
						// 	$vl_a_utime_wo_pay -= floor($total_sl_earned);
						// 	$total_sl_earned = floor($total_sl_earned) - floor($sl_tot_deduc);
						// 	if($vl_a_utime_wo_pay < 0) $vl_a_utime_wo_pay = 0;
						// 	if($total_sl_earned < 0) $total_sl_earned = 0;
						// 	$total_sl_earned += $sl_decimals;
						// }
						
						$monthly_data['sl_earned'] = $earned;
						$monthly_data['sl_a_utime_w_pay'] = $total_sl_from_monetized + ($vl_a_utime_wo_pay > 0 ? $tmp_total_vl_earned : ($total_sl_earned > 0?$sl_tot_deduc:0));
						if($haveVl>0) $monthly_data['sl_a_utime_w_pay'] -= $total_sl;
						$monthly_data['sl_balance'] = $total_sl_earned;
						$monthly_data['sl_a_utime_wo_pay'] = "";
						$monthly_data['remarks'] = @$utime[0]['remarks'];
						
						if($total_vl_earned <= 10) {
							$total_vl_earned += $monthly_data['vl_a_utime_w_pay'];
							$total_sl_earned += $monthly_data['sl_a_utime_w_pay'];
							if($total_vl_earned >= 10){
								$monthly_data['vl_balance'] = 10;
								$vl_a_utime_w_pay = $total_vl_earned - 10;
								$vl_a_utime_wo_pay += ($monthly_data['vl_a_utime_w_pay'] - $vl_a_utime_w_pay);
								$monthly_data['vl_a_utime_w_pay'] = $vl_a_utime_w_pay;
							}else{
								$monthly_data['vl_balance'] = $total_vl_earned;
								$vl_a_utime_wo_pay += $monthly_data['vl_a_utime_w_pay'];
								$monthly_data['vl_a_utime_w_pay'] = 0;
							}					
							$total_vl_earned = $monthly_data['vl_balance'];
							$totalParticulars = $monthly_data['particulars'];					
						}
						$monthly_data['vl_a_utime_wo_pay'] = $vl_a_utime_wo_pay;

						$balanceasof = $ret->getBalanceAsOf($value,$employee_id,$year);
						if($balanceasof){
							$monthly_data['particulars'] = $monthly_data['vl_earned'] = $monthly_data['vl_a_utime_w_pay'] = $monthly_data['sl_earned'] = $monthly_data['sl_a_utime_w_pay'] = "";
							$total_vl_earned = $balanceasof["vl"];
							$total_sl_earned = $balanceasof["sl"];
							$monthly_data['vl_balance'] = $total_vl_earned;
							$monthly_data['sl_balance'] = $total_sl_earned;
							$monthly_data['vl_earned'] = "";
							$monthly_data['sl_earned'] = "";
							$monthly_data['vl_a_utime_wo_pay'] = "";
							$monthly_data['sl_a_utime_wo_pay'] = "";
						}
						
						// if(($year == 2021 && $value >= 4) || $year > 2021)
							$ledger[$year][] = $monthly_data;
					}

					//UTime Posting End
					if($with_particulars_leaves <= 0 && (float)$tmp_tot_ut_conversions <= 0 && $tot_absent <= 0){
						$monthly_data['particulars'] = "";
						//VL
						$monthly_data['vl_a_utime_w_pay'] = $total_vl_from_monetized;
						$total_vl_earned += $monthly_data['vl_earned'];
						$monthly_data['vl_balance'] = $total_vl_earned;
						$monthly_data['vl_a_utime_wo_pay'] = "";
						//SL
						
						$monthly_data['sl_a_utime_w_pay'] = $total_sl_from_monetized;
						$total_sl_earned += $monthly_data['sl_earned'];
						$monthly_data['sl_balance'] = $total_sl_earned;
						$monthly_data['sl_a_utime_wo_pay'] = "";
						
						$remarks = "";
						if($key == sizeof($selected_months) - 1) $remarks = $total_vl_earned + $total_sl_earned;
						$monthly_data['remarks'] = $remarks;

						$balanceasof = $ret->getBalanceAsOf($value,$employee_id,$year);
						if($balanceasof){
							$monthly_data['particulars'] = $monthly_data['vl_earned'] = $monthly_data['vl_a_utime_w_pay'] = $monthly_data['sl_earned'] = $monthly_data['sl_a_utime_w_pay'] = "";
							$total_vl_earned = $balanceasof["vl"];
							$total_sl_earned = $balanceasof["sl"];
							$monthly_data['vl_balance'] = $total_vl_earned;
							$monthly_data['sl_balance'] = $total_sl_earned;
							$monthly_data['vl_earned'] = "";
							$monthly_data['sl_earned'] = "";
							$monthly_data['vl_a_utime_wo_pay'] = "";
							$monthly_data['sl_a_utime_wo_pay'] = "";
						}
						// if(($year == 2021 && $value >= 4) || $year > 2021)
							$ledger[$year][] = $monthly_data;
					}
					
					//unused force leave <= 5
					if($value == 12) {
						$number_of_days = 0;
						if($forced_count < 5) {
							$particulars = "<div style='width:15%;text-align:left'>FL</div><div style='width:80%;text-align:right;'>";
							$number_of_days = 5 - $forced_count;
							$particulars.= $number_of_days.'.0.0';
							$particulars.= "</div>";
							$monthly_data['period'] = "";
							$monthly_data['particulars'] = $particulars;

							$monthly_data['vl_earned'] = "";
							// $converted_hr = $ret->getConversionFractions(8,"hr");
							// $vl_a_utime_w_pay = $number_of_days * $converted_hr[0]['equiv_day'];
							$monthly_data['vl_a_utime_w_pay'] = "";//$vl_a_utime_w_pay;
							$vl_a_utime_wo_pay = "";
							$monthly_data['vl_balance'] = "";//$total_vl_earned;
							$monthly_data['vl_a_utime_wo_pay'] = "";//$vl_a_utime_wo_pay;
							//SL
							$monthly_data['sl_earned'] = "";
							$monthly_data['sl_a_utime_w_pay'] = "";
							$monthly_data['sl_balance'] = "";
							$monthly_data['sl_a_utime_wo_pay'] = "";
							$monthly_data['remarks'] = "Unused";
							$ledger[$year][] = $monthly_data;
						} 	
						// $total_vl_earned -= $number_of_days;
						$ret->saveRemainingBal($employee_id,$year,$total_vl_earned,$total_sl_earned);
					}
					$first = false;
					if(isset($terminal) && (($dtfiled == (int)$value) && (int)date("Y",strtotime($terminal['is_terminal'])) == $year)){
						$monthly_data['period'] = "<span style='display:none;'></span>";
						$monthly_data['particulars'] = "Retired effective: ".date("M d, Y", strtotime($terminal['is_terminal']));

						$monthly_data['vl_earned'] = "";
						$monthly_data['vl_a_utime_w_pay'] = "";//$vl_a_utime_w_pay;
						$vl_a_utime_wo_pay = "";
						$monthly_data['vl_balance'] = "";//$total_vl_earned;
						$monthly_data['vl_a_utime_wo_pay'] = "";//$vl_a_utime_wo_pay;
						//SL
						$monthly_data['sl_earned'] = "";
						$monthly_data['sl_a_utime_w_pay'] = "";
						$monthly_data['sl_balance'] = "";
						$monthly_data['sl_a_utime_wo_pay'] = "";
						$monthly_data['remarks'] = "";
						// if(($year == 2021 && $value >= 4) || $year > 2021)
							$ledger[$year][] = $monthly_data;
						break;
					}
				// }
				// else{

				// }
				
			}
			
			$remarks_key = 1;
			$ledger['remarks'][0] = "<b>Special Privilege Leave</b>"; $remarks_key++;
			$ledger['remarks'][1] = sizeof($arrspecial) > 0 ? "1. ".$arrspecial[0] : "1. "; $remarks_key++;
			$ledger['remarks'][2] = sizeof($arrspecial) > 1 ? "2. ".$arrspecial[1] : "2. "; $remarks_key++;
			$ledger['remarks'][3] = sizeof($arrspecial) > 2 ? "3. ".$arrspecial[2] : "3. "; $remarks_key++;
			$arrrem_cnt = 4;
			if(sizeof($arrspecial) > 3){
				for ($i=3; $i < sizeof($arrspecial); $i++) { 
					$ledger['remarks'][$arrrem_cnt] = $arrrem_cnt.". ".$arrspecial[$i]; $remarks_key++;
					$arrrem_cnt++;
				}
			}
			$remarks_key--;
			if(sizeof($arrvacation) > 0){
				$ledger['remarks'][$remarks_key] = "<b>Vacation Leave</b>"; $remarks_key++;
				foreach($arrvacation as $remkey => $remval){
					$ledger['remarks'][$remarks_key] = ($remkey+1).'. '.$remval; $remarks_key++;
				}
			}
			if(sizeof($arrsick) > 0){
				$ledger['remarks'][$remarks_key] = "<b>Sick Leave</b>"; $remarks_key++;
				foreach($arrsick as $remkey => $remval){
					$ledger['remarks'][$remarks_key] = ($remkey+1).'. '.$remval; $remarks_key++;
				}
			}
			$ledger['remarks'][$remarks_key] = "<b>Forced Leave</b>"; $remarks_key++;
			$ledger['remarks'][$remarks_key] = sizeof($arrforce) > 0 ? "1. ".$arrforce[0] : "1. "; $remarks_key++;
			$ledger['remarks'][$remarks_key] = sizeof($arrforce) > 1 ? "2. ".$arrforce[1] : "2. "; $remarks_key++;
			$ledger['remarks'][$remarks_key] = sizeof($arrforce) > 2 ? "3. ".$arrforce[2] : "3. "; $remarks_key++;
			$ledger['remarks'][$remarks_key] = sizeof($arrforce) > 3 ? "4. ".$arrforce[3] : "4. "; $remarks_key++;
			$ledger['remarks'][$remarks_key] = sizeof($arrforce) > 4 ? "5. ".$arrforce[4] : "5. "; $remarks_key++;
			
			$ledger['remarks'][$remarks_key] = "<b>Calamity Leave</b>"; $remarks_key++;
			$ledger['remarks'][$remarks_key] = sizeof($arrcalamity) > 0 ? "1. ".$arrcalamity[0] : "1. "; $remarks_key++;
			$ledger['remarks'][$remarks_key] = sizeof($arrcalamity) > 1 ? "2. ".$arrcalamity[1] : "2. "; $remarks_key++;
			$ledger['remarks'][$remarks_key] = sizeof($arrcalamity) > 2 ? "3. ".$arrcalamity[2] : "3. "; $remarks_key++;
			$ledger['remarks'][$remarks_key] = sizeof($arrcalamity) > 3 ? "4. ".$arrcalamity[3] : "4. "; $remarks_key++;
			$ledger['remarks'][$remarks_key] = sizeof($arrcalamity) > 4 ? "5. ".$arrcalamity[4] : "5. "; $remarks_key++;

			if(sizeof($arrmaternity) > 0){
				$ledger['remarks'][$remarks_key] = "<b>Maternity Leave</b>"; $remarks_key++;
				foreach($arrmaternity as $remkey => $remval){
					$ledger['remarks'][$remarks_key] = $remval; $remarks_key++;
				}
			}

			if(sizeof($arrpaternity) > 0){
				$ledger['remarks'][$remarks_key] = "<b>Paternity Leave</b>"; $remarks_key++;
				foreach($arrpaternity as $remkey => $remval){
					$ledger['remarks'][$remarks_key] = $remval; $remarks_key++;
				}
			}

			if(sizeof($arrrehab) > 0){
				$ledger['remarks'][$remarks_key] = "<b>Rehab Leave</b>"; $remarks_key++;
				foreach($arrrehab as $remkey => $remval){
					$ledger['remarks'][$remarks_key] = $remval; $remarks_key++;
				}
			}

			if(sizeof($arrmonet) > 0){
				$ledger['remarks'][$remarks_key] = "<b>Monetization</b>"; $remarks_key++;
				foreach($arrmonet as $remkey => $remval){
					$ledger['remarks'][$remarks_key] = $remval; $remarks_key++;
				}
			}

			if(sizeof($arrbenefits) > 0){
				$ledger['remarks'][$remarks_key] = "<b>Special Leave Benefits for Women</b>"; $remarks_key++;
				foreach($arrbenefits as $remkey => $remval){
					$ledger['remarks'][$remarks_key] = $remval; $remarks_key++;
				}
			}

			if(sizeof($arrviolence) > 0){
				$ledger['remarks'][$remarks_key] = "<b>Violence</b>"; $remarks_key++;
				foreach($arrviolence as $remkey => $remval){
					$ledger['remarks'][$remarks_key] = $remval; $remarks_key++;
				}
			}

			if(sizeof($arrsolo) > 0){
				$ledger['remarks'][$remarks_key] = "<b>Solo Parent Leave</b>"; $remarks_key++;
				foreach($arrsolo as $remkey => $remval){
					$ledger['remarks'][$remarks_key] = $remval; $remarks_key++;
				}
			}

			if(sizeof($arrstudy) > 0){
				$ledger['remarks'][$remarks_key] = "<b>Study Leave</b>"; $remarks_key++;
				foreach($arrstudy as $remkey => $remval){
					$ledger['remarks'][$remarks_key] = $remval; $remarks_key++;
				}
			}
			
		// }
		return $ledger;
	}
	public function viewLeaveLedgerSummary(){
		Helper::sessionEndedHook('session');
		$formData = array();
		$result = array();
		$result['key'] = 'viewLeaveLedgerSummary';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new LeaveLedgerCollection();
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
			if(in_array(17003,$_SESSION["sessionModules"])) $self = 1;
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
				// $end_date = $formData['employee']['end_date'];
				// $start_date = $formData['employee']['start_date'];
	        endif;
	        // var_dump($employee[0]); die();
	        //return here

			// $ledger = $this->generateLedgerData($employment_status,$employee_id,$pay_basis,$division_id,$location_id,$year,$post_year,$month,$date_of_permanent,$present_day);
			$ledger = $this->generateLedgerData($employment_status,$employee_id,$pay_basis,$division_id,$location_id,$year,$post_year,$month,$date_of_permanent,$present_day,$end_date,$start_date);
			$formData['balance']['vl_balance_amt'] = @$ledger[$year_from-1][sizeof(@$ledger[$year_from-1]) - 1]['vl_balance'];
			$formData['balance']['sl_balance_amt'] = @$ledger[$year_from-1][sizeof(@$ledger[$year_from-1]) - 1]['sl_balance'];
			if(@$ledger[$year_from-1][sizeof(@$ledger[$year_from-1]) - 1]['period'] == ""){
				$formData['balance']['vl_balance_amt'] = @$ledger[$year_from-1][sizeof(@$ledger[$year_from-1]) - 2]['vl_balance'];
			    $formData['balance']['sl_balance_amt'] = @$ledger[$year_from-1][sizeof(@$ledger[$year_from-1]) - 2]['sl_balance'];
			}

			// if($year >= 2020) {
	      		$total_balance = $ret->getLeaveBalance($employee_id,$year);
				if(sizeof($total_balance) > 0) {
					$formData['balance']['vl_balance_amt'] = @$total_balance[0]['vl'];
				    $formData['balance']['sl_balance_amt'] = @$total_balance[0]['sl'];
				}
	      	// }
			
			$formData['year'] = $year;
			$formData['key'] = $result['key'];
			$selected_years = range($year_from,$post_year);
			// var_dump(json_encode($ledger)); die();
			foreach ($selected_years as $k1 => $v1) {
				if(isset($ledger[$v1])){
					$formData['ledgers'][] = @$ledger[$v1];
				}
			}

			//var_dump($ledger).die();
			$result['form'] = $this->load->view('helpers/employeeleaveledger.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	function getEmployeeListExhausted(){
		Helper::sessionEndedHook('session');
		// $this->load->model('employees/EmployeesCollection');
		$employee_sort = array();
		$year = @$_POST['year'];
		$post_year = @$_POST['year'];
		$month = @$_POST['month'];
		// var_dump($_POST);die();
		$employees = @$this->LeaveLedgerCollection->getEmployeeList($_POST['pay_basis'],$_POST['location_id'],@$_POST['division_id'],@$_POST['specific']);
		// var_dump($employees);die();
		$employee_final = array();
		foreach ($employees as $k => $value) {
        	$ledger = $this->generateLedgerData($value['employment_status'],$value['id'],$value['pay_basis'],$value['division_id'],$value['location_id'],$year,$post_year,$month,$value['date_of_permanent'],$value['present_day']);
        	$total_ledger = 0;
        	// var_dump($ledger[$post_year]);die();
        	$vl_balance_amt = @$ledger[$post_year][sizeof(@$ledger[$post_year]) - 1]['vl_balance'];
		    $sl_balance_amt = @$ledger[$post_year][sizeof(@$ledger[$post_year]) - 1]['sl_balance'];
        	if(@$ledger[$post_year][sizeof(@$ledger[$post_year]) - 1]['period'] == ""){
				$vl_balance_amt = @$ledger[$post_year][sizeof(@$ledger[$post_year]) - 2]['vl_balance'];
			    $sl_balance_amt = @$ledger[$post_year][sizeof(@$ledger[$post_year]) - 2]['sl_balance']; 
			}
			$total_ledger = $vl_balance_amt + $sl_balance_amt;
			// var_dump($total_ledger);die();
			if($total_ledger < 10 && $value['date_of_permanent'] != "" && strtotime($value['date_of_permanent']) <= strtotime($year.'-'.$month.'-01')){
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
	function getEmployeeList(){
		Helper::sessionEndedHook('session');
		// $this->load->model('employees/EmployeesCollection');
		$employee_sort = array();
		$year = @$_POST['year'];
		$post_year = @$_POST['year'];
		$month = @$_POST['month'];
		// var_dump($_POST);die();
		$employees = @$this->LeaveLedgerCollection->getEmployeeList($_POST['pay_basis'],$_POST['location_id'],@$_POST['division_id'],@$_POST['specific']);
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
	
	public function leaveledgerContainer(){
		Helper::sessionEndedHook('session');
		// die("hit");
		$formData = array();
		$result = array();
		$result['key'] = 'leaveledgerContainer';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			
			/*if($ret->computePayrollProcess($employee_id,$division_id)) {*/
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('helpers/employeeleaveledger.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	
	public function dateDiff($d1,$d2) {
		// Return the number of days between the two dates:    
		return round(abs(strtotime($d1) - strtotime($d2))/86400);	
	}
}

?>
