<?php

class LeaveRequest extends MX_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('LeaveRequestCollection');
		$this->load->model('LeaveLedgerCollection');
		$this->load->model('PendingLeaveCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		$this->load->library('upload');
		Helper::sessionEndedHook('session');
	}

	public function index() {
		Helper::rolehook(ModuleRels::HRIS_DASHBOARD);
		$listData = array();
		$viewData = array();
		$page = "viewLeaveRequest";
		$listData['key'] = $page;
		$ret = new LeaveRequestCollection();
		$viewData['table'] = $this->load->view("helpers/leaverequestlist",$listData,TRUE); 
		$viewData['form'] = $this->load->view("forms/leaveapplicationform", $listData['key'], TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Leave Requests');
			Helper::setMenu('templates/menu_template');
			Helper::setView('leaverequest',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		} else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}

	function fetchRows(){ 
        $data = array();  
		$ret =  new LeaveRequestCollection();
		$ret = $ret->make_datatables();
		foreach($ret as $k => $row){
        	$buttons = $buttons_data = "";
        	$row->first_name = $this->Helper->decrypt($row->first_name,$row->employee_id);
        	$row->middle_name = $this->Helper->decrypt($row->middle_name,$row->employee_id);
        	$row->last_name = $this->Helper->decrypt($row->last_name,$row->employee_id);
        	$row->extension = $this->Helper->decrypt($row->extension,$row->employee_id);
            $sub_array = array();
			// $sub_array[] = $row->number_of_days;
			// $isRange= explode(" - ",$row->inclusive_dates);
			// if($row->inclusive_dates != ""){
			// 	if(sizeof($isRange) == 2) $row->inclusive_dates = date("F d, Y", strtotime($isRange[0])) . " - " . date("F d, Y", strtotime($isRange[1]));
			// 	else{
			// 		$dates = explode(", ",$row->inclusive_dates);
			// 		$stdates = array();
			// 		foreach($dates as $k2 => $v2) $stdates[] = date("F d, Y", strtotime($v2));
			// 		$row->inclusive_dates = implode(", ", $stdates);
			// 	}
			// }
            if($row->status == 5) $status_color = "text-success";
            else if($row->status == 1 || $row->status == 2 || $row->status == 3) $status_color = "text-warning";
			else if($row->status == 4) $status_color = "text-info";
			else $status_color = "text-danger";
            if($row->status_name == "REJECTED"){
				$status_name = "DISAPPROVED";
			}else{
				$status_name = $row->status_name;
			}
			
			foreach($row as $k1=>$v1){
				if($k1 == "inclusive_dates"){
					if(strpos($v1, ', ') !== false ){
						$isRange= explode(", ",$v1);
						sort($isRange);
						$v1 = date("M. d, Y", strtotime($isRange[0])) . " - " . date("M. d, Y", strtotime(end($isRange)));
						$row->inclusive_dates = $v1;
					}else if(strpos($v1, ' - ') !== false ){	
						$isRange= explode(" - ",$v1);					
						if(sizeof($isRange) == 2){
							if(strtotime($isRange[0]) == strtotime($isRange[1])){
								$v1 = date("M. d, Y", strtotime($isRange[0]));
								$row->inclusive_dates = $v1;
							} else{							
								$v1 = date("M. d, Y", strtotime($isRange[0])) . " - " . date("M. d, Y", strtotime($isRange[1]));
								$row->inclusive_dates = $v1;
							}
						}
					}else{
						$v1 = date("M. d, Y", strtotime($v1));;
						$row->inclusive_dates = $v1;
					}
				}
				$buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
			}

			// $supervisor = false;
			// $division_head = false;
			// $deputy = false;

			// $ret =  new PendingLeaveCollection();
			// if($ret->fetchLeaveApprovals($row->employee_id)) {
			// 	$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
			// 	$approvers = json_decode($res,true);

			// 	if($approvers['Code'] == "0"){
			// 		$app = $approvers['Data']['approvers'];

			// 		foreach ($app as $k => $v) {
			// 			$id = $v['id'];
			// 			$approve_type = $v['approve_type'];

			// 			if($approve_type == "2"){
			// 				$supervisor = true;
			// 			}
			// 			if($approve_type == "3"){
			// 				$division_head = true;
			// 			}

			// 			if($approve_type == "8"){
			// 				$deputy = true;
			// 			}
			// 		}
			// 	}
			// }

			// if($division_head && !$deputy){
			// 	if($row->division_head > 0): //LEAVE_RECOMMEND
			// 		if($row->status == 2){
			// 			$status_name = "FOR RECOMMENDATION (Division Hea1d)";
			// 		}
			// 	endif;
			// }else if(!$division_head && $deputy){
			// 	if($row->deputy > 0): //LEAVE_RECOMMEND
			// 		if($row->status == 2){
			// 			$status_name = "FOR RECOMMENDATION (Deputy)";
			// 		}
			// 	endif;
			// }

            $buttons_data.= ' data-totvlsl="'.(round($row->vl,3)+round($row->sl,3)).'" ';
            if(Helper::role(ModuleRels::LEAVE_VIEW_DETAILS)): 
            	$buttons .= ' <a id="viewLeaveRequestDetails" ' 
            		  . ' class="viewLeaveRequestDetails" style="text-decoration: none;" '
            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewLeaveRequestDetails" '
            		  . $buttons_data
            		  . ' > '
            		  . ' <button class="btn btn-primary btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Details">'
            		  . ' <i class="material-icons">remove_red_eye</i> '
            		  . ' </button> '
            		  . ' </a> '
            		  . ' <button id="view_report" class="btn btn-success btn-circle waves-effect waves-circle waves-float" '.$buttons_data.' data-toggle = "modal" data-target = "#print_preview_modal"  data-placement="top" title="Print Preview">'
            		  . '<i class="material-icons">print</i>'
            		  . ' </button>';
			endif;
			
    //         if(Helper::role(ModuleRels::LEAVE_UPDATE_DETAILS)): 
				// if($row->status == "PENDING"){
				// 	$buttons .= ' <a id="updateLeaveRequestForm" ' 
				// 			. ' class="updateLeaveRequestForm" style="text-decoration: none;" '
				// 			. ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateLeaveRequestForm" '
				// 			. $buttons_data
				// 			. ' > '
				// 			. ' <button class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Update">'
				// 			. ' <i class="material-icons">mode_edit</i> '
				// 			. ' </button> '
				// 			. ' </a> ';
				// }
    //         endif;
            	if($row->status < 5){
					$buttons .= ' <a href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/cancelApplication" data-id="'.$row->id.'" '
							. ' class="btn btn-danger btn-circle waves-effect waves-circle waves-float cancelLeaveRequestForm" data-toggle="tooltip" data-placement="top" title="Cancel">'
            		  		. '<i class="material-icons">do_not_disturb</i>'
							. ' </a> ';
				}
            
	        $sub_array[] = $buttons;
            $sub_array[] = $row->last_name.', '.$row->first_name." ".$row->middle_name." ".$row->extension;
            $sub_array[] = $row->filing_date;
            $sub_array[] = ucwords(str_replace("_"," ",$row->type_name));
			$sub_array[] = $row->inclusive_dates;
            $sub_array[] = '<b><span class="'.$status_color.'">'.$status_name.'</span><b>';
            $sub_array[] = $row->remarks;
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>     $this->LeaveRequestCollection->get_all_data(),  
            "recordsFiltered"     	=>     $this->LeaveRequestCollection->get_filtered_data(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }

	
	public function cancelApplication(){
		$result = array();
		if (!$this->input->is_ajax_request())
		   show_404();
		else{
			if($this->input->post() && $this->input->post() != null){
				$post_data = array();
				foreach ($this->input->post() as $k => $v) $post_data[$k] = $this->input->post($k,true);
				$ret =  new LeaveRequestCollection();
				if($ret->cancelApplication($post_data)) $res = new ModelResponse($ret->getCode(), $ret->getMessage());
				else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
				$result = json_decode($res,true);
			}else{
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
		}
		echo json_encode($result);
	}

	public function addLeaveRequestForm(){
		$formData = $empData = array();
		$result = array();
		$result['key'] = 'addLeaveRequest';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$employee_id = isset($_SESSION['id'])?$_SESSION['id']:"";
			$ret = $this->LeaveRequestCollection->get_service_record($employee_id);

			$formData['key'] = $result['key'];
			$retleaveAvailable = $this->LeaveLedgerCollection->leaveAvailable($employee_id);
			if(sizeof($ret['employee']) > 0){
				$empData['employee']['employee_number'] = $this->Helper->decrypt($ret['employee']['employee_number'],$employee_id);
				$empData['employee']['first_name'] = $this->Helper->decrypt($ret['employee']['first_name'],$employee_id);
				$empData['employee']['last_name'] = $this->Helper->decrypt($ret['employee']['last_name'],$employee_id);
				$empData['employee']['middle_name'] = $this->Helper->decrypt($ret['employee']['middle_name'],$employee_id);
				$empData['employee']['extension'] = ($ret['employee']['extension'] == null || $ret['employee']['extension'] == "")?"":$this->Helper->decrypt($ret['employee']['extension'],$employee_id);
				$empData['employee']['birthday'] = $ret['employee']['birthday'];
				$empData['employee']['birth_place'] = $ret['employee']['birth_place'];
				$empData['employee']['position_name'] = $ret['employee']['position_name'];
				$empData['employee']['branch'] = $ret['employee']['dpt_code'].' - '.$ret['employee']['dpt_name'];
				$empData['employee']['employee_id_number'] = $this->Helper->decrypt($ret['employee']['employee_id_number'],$employee_id);
				$employment_status = $ret['employee']['employment_status'];
				$pay_basis 	 = "Permanent";
				$division_id = isset($ret['employee']['division_id'])?$ret['employee']['division_id']:"";
				$location_id = isset($_POST['location_id'])?$_POST['location_id']:"";
				$year = date("Y");
				$year_from = $year;
				$post_year 	= isset($_POST['year'])?$_POST['year']:"";
				$month = 12;
				$current_year = date("Y");
				if($year == $current_year) $month = (int)date("m");
				$date_of_permanent = isset($ret['employee']['date_of_permanent']) && $ret['employee']['date_of_permanent'] != null?$ret['employee']['date_of_permanent']:$year;
				$present_day = isset($ret['employee']['present_day'])?$ret['employee']['present_day']:0;
				$end_date = isset($ret['employee']['end_date'])?$ret['employee']['end_date']:0;
				$start_date = isset($ret['employee']['start_date'])?$ret['employee']['start_date']:"";
				$ledger = $this->generateLedgerData($employment_status,$employee_id,$pay_basis,$division_id,$location_id,$year,$post_year,$month,$date_of_permanent,$present_day,$end_date,$start_date);
				
				// Fetch number of sick leave and vacation leave for the following month
				$ret1 =  new LeaveRequestCollection();
				$vl_next_month = $ret1->getNextMonthLeave($employee_id, $month, $year, 'vacation');
				$sl_next_month = $ret1->getNextMonthLeave($employee_id, $month, $year, 'sick');
				
				$vl_bal = @$ledger[$year][sizeof($ledger[$year])-1]['vl_balance'];
				$sl_bal = @$ledger[$year][sizeof($ledger[$year])-1]['sl_balance'];
				if($ledger[$year][sizeof($ledger[$year]) - 1]['period'] == ""){
					$vl_bal = @$ledger[$year][sizeof($ledger[$year])-2]['vl_balance'];
					$sl_bal = @$ledger[$year][sizeof($ledger[$year])-2]['sl_balance'];
				}

				$empData['employee']['vl'] = $vl_bal - (int)$vl_next_month[0]['number_of_days'];
				$empData['employee']['sl'] = $sl_bal - (int)$sl_next_month[0]['number_of_days'];

				$leavebal = (double)$empData['employee']['vl'] + (double)$empData['employee']['sl'];
				$offset =  @$ret['employee'][0]['totaloffset'] != ""? ($ret['employee'][0]['totaloffset'] / 0.00208) / 60 : 0;
				$offsetHrs = floor($offset);
				$offsetMins = floor((($offset - $offsetHrs)*60));

				$empData['employee']['specialLeave'] = $retleaveAvailable['availablespecial'] > 0 ? $retleaveAvailable['availablespecial'] : 0;
				$empData['employee']['forceLeave'] = $retleaveAvailable['availableforce'] > 0 ? $retleaveAvailable['availableforce'] : 0;
				$empData['employee']['totaloffsetHrs'] = $offsetHrs;
				$empData['employee']['totaloffsetMins'] = $offsetMins;
				$empData['employee']['leaveCreditsTotal'] = $leavebal;

				// $force = 5 - $empData['employee']['forceLeave'];
				// $empData['employee']['vl'] = $empData['employee']['vl'] - $force;
			}
			$empData["Experience"] = $ret["experience"];
			$formData["data"] = $empData;
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/leaverequestform.php', $formData, TRUE);
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

	public function getLeaveCredits(){
		$result = array();
		$formData = $empData = array();
		$employee_id = $_POST["employee_id"];
		$ret = $this->LeaveRequestCollection->get_service_record($_POST["employee_id"]);
			if(sizeof($ret['employee']) > 0){
				$empData['employee']['employee_number'] = $this->Helper->decrypt($ret['employee']['employee_number'],$employee_id);
				$empData['employee']['first_name'] = $this->Helper->decrypt($ret['employee']['first_name'],$employee_id);
				$empData['employee']['last_name'] = $this->Helper->decrypt($ret['employee']['last_name'],$employee_id);
				$empData['employee']['middle_name'] = $this->Helper->decrypt($ret['employee']['middle_name'],$employee_id);
				$empData['employee']['extension'] = ($ret['employee']['extension'] == null || $ret['employee']['extension'] == "")?"":$this->Helper->decrypt($ret['employee']['extension'],$employee_id);
				$empData['employee']['birthday'] = $ret['employee']['birthday'];
				$empData['employee']['birth_place'] = $ret['employee']['birth_place'];
				$empData['employee']['position_name'] = $ret['employee']['position_name'];
				$empData['employee']['branch'] = $ret['employee']['dpt_code'].' - '.$ret['employee']['dpt_name'];
				$empData['employee']['employee_id_number'] = $this->Helper->decrypt($ret['employee']['employee_id_number'],$employee_id);
				$employment_status 							= $ret['employee']['employment_status'];
				$pay_basis 	 = "Permanent";
				$division_id = isset($ret['employee']['division_id'])?$ret['employee']['division_id']:"";
				$year = date("Y");
				$year_from = $year;
				$post_year 	= isset($_POST['year'])?$_POST['year']:"";
				$month = 12;
				$current_year = date("Y");
				if($year == $current_year) $month = (int)date("m");
				$date_of_permanent = isset($ret['employee']['date_of_permanent']) && $ret['employee']['date_of_permanent'] != null?$ret['employee']['date_of_permanent']:$year;
				$present_day = isset($ret['employee']['present_day'])?$ret['employee']['present_day']:0;
				$end_date = isset($ret['employee']['end_date'])?$ret['employee']['end_date']:"";
				$start_date = isset($ret['employee']['start_date'])?$ret['employee']['start_date']:"";
				$ledger = $this->generateLedgerData($employment_status, $employee_id,$pay_basis,$division_id,$year,$month,$date_of_permanent,$present_day,$end_date,$start_date);
				$empData['employee']['vl'] = @$ledger[$year][sizeof($ledger[$year])-1]['vl_balance'];
				$empData['employee']['sl'] = @$ledger[$year][sizeof($ledger[$year])-1]['sl_balance'];
				if($ledger[$year][sizeof($ledger[$year]) - 1]['period'] == ""){
					$empData['employee']['vl'] = @$ledger[$year][sizeof($ledger[$year])-2]['vl_balance'];
					$empData['employee']['sl'] = @$ledger[$year][sizeof($ledger[$year])-2]['sl_balance'];
				}
				$leavebal = round((double)$empData['employee']['vl'],3) + round((double)$empData['employee']['sl'],3);
				$result['data']['vl'] = round($empData['employee']['vl'],3);
				$result['data']['sl'] = round($empData['employee']['sl'],3);
				$result['data']['leaveCreditsTotal'] = round($leavebal,3);
			}
		echo json_encode($result);
	}

	public function addLeaveRequest(){
		
		$result = array();
		$page = 'addLeaveRequest';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			if($this->input->post() && $this->input->post() != null){				
				$post_data = array();
				foreach($_FILES as $key1 => $value1){
					if($key1 == 'file'){
						$_FILES['uploadFile']['name'] 		= $_FILES['file']['name'];
						$_FILES['uploadFile']['type'] 		= $_FILES['file']['type'];
						$_FILES['uploadFile']['size'] 		= $_FILES['file']['size'];
						$_FILES['uploadFile']['error'] 		= $_FILES['file']['error'];
						$_FILES['uploadFile']['tmp_name'] = $_FILES['file']['tmp_name'];
						$uploadpath = './assets/uploads/leaveattachment/'.$_POST["employee_id"];
						if(!file_exists($uploadpath)) mkdir($uploadpath, 0777, true);
						chmod($uploadpath, 0775);
						$config['upload_path'] = $uploadpath;
						$config['allowed_types'] = '*';
						$config['overwrite'] = TRUE;
						$config['remove_spaces'] = FALSE;
						$this->upload->initialize($config);
						if ($this->upload->do_upload('uploadFile')):
							$data = array('upload_data' => $this->upload->data());
						else:
							$error = array('error' => $this->upload->display_errors());
						endif;
						$post_data  = $this->array_push_assoc($post_data, 'file_name', $_FILES['file']['name']);
						$post_data  = $this->array_push_assoc($post_data, 'file_size', $_FILES['file']['size']);
					}
					if($key1 == 'sig_file'){
						$_FILES['uploadFile']['name'] 		= $_FILES['sig_file']['name'];
						$_FILES['uploadFile']['type'] 		= $_FILES['sig_file']['type'];
						$_FILES['uploadFile']['size'] 		= $_FILES['sig_file']['size'];
						$_FILES['uploadFile']['error'] 		= $_FILES['sig_file']['error'];
						$_FILES['uploadFile']['tmp_name'] = $_FILES['sig_file']['tmp_name'];
						$uploadpath = './assets/uploads/leaveattachment/signature/'.$_POST["employee_id"];
						if(!file_exists($uploadpath)) mkdir($uploadpath, 0777, true);
						chmod($uploadpath, 0775);
						$config['upload_path'] = $uploadpath;
						$config['allowed_types'] = '*';
						$config['overwrite'] = TRUE;
						$config['remove_spaces'] = FALSE;
						$this->upload->initialize($config);
						if ($this->upload->do_upload('uploadFile')):
							$data = array('upload_data' => $this->upload->data());
						else:
							$error = array('error' => $this->upload->display_errors());
						endif;
						$post_data  = $this->array_push_assoc($post_data, 'sig_file_name', $_FILES['sig_file']['name']);
						$post_data  = $this->array_push_assoc($post_data, 'sig_file_size', $_FILES['sig_file']['size']);
					}
				}
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new LeaveRequestCollection();
				// var_dump($this->approverHierarchy($post_data['employee_id']));die();
				if($ret->addRows($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			}
			else
			{
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}
	
	public function approverHierarchy($employee_id, $current_status = NULL){
		$for_cert = false;
		$for_recom_sec = false;
		$for_recom_div = false;
		$for_recom_dep = false;
		$for_approval = false;
		$next_status = NULL;

		$ret =  new PendingLeaveCollection();
		if($ret->fetchLeaveApprovals($employee_id)) {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
			$approvers = json_decode($res,true);

			if($approvers['Code'] == "0"){
				$app = $approvers['Data']['approvers'];

				foreach ($app as $k => $v) {
					$id = $v['id'];
					$approve_type = $v['approve_type'];

					if($current_status == NULL){
						if($approve_type == "1"){  $for_cert = true; $next_status = 1;}
						else if($approve_type == "2"){ $for_recom_sec = true; if($next_status == NULL){$next_status = 2;}}	
						else if($approve_type == "3"){ $for_recom_div = true; if($next_status == NULL){$next_status = 3;}}
						else if($approve_type == "8"){ $for_recom_dep = true; if($next_status == NULL){$next_status = 8;}}
						else if($approve_type == "4"){ $for_approval = true; if($next_status == NULL){$next_status = 4;}}
					}else if($current_status == "1"){
						if($approve_type == "2"){ $for_recom_sec = true; $next_status = 2;}		
						else if($approve_type == "3"){ $for_recom_div = true; $next_status = 3;}
						else if($approve_type == "8"){ $for_recom_dep = true; $next_status = 8;}
						else if($approve_type == "4"){ $for_approval = true; $next_status = 4;}
					}else if($current_status == "2"){
						if($approve_type == "3"){ $for_recom_div = true; $next_status = 3;}
						else if($approve_type == "8"){ $for_recom_dep = true; $next_status = 8;}
						else if($approve_type == "4"){ $for_approval = true; $next_status = 4;}
					}else if($current_status == "3"){
						if($approve_type == "4"){ $for_approval = true; $next_status = 4;}
					}else if($current_status == "8"){
						if($approve_type == "4"){ $for_approval = true; $next_status = 4;}
					}
				}
			}
		}

		return [
			'for_cert' => $for_cert,
			'for_recom_sec' => $for_recom_sec,
			'for_recom_div' => $for_recom_div,
			'for_recom_dep' => $for_recom_dep,
			'for_approval' => $for_approval,
			'next_status' => $next_status,
		];
	}

	public function viewLeaveRequestDetails(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewLeaveRequestDetails';
		if (!$this->input->is_ajax_request()) show_404();
		else{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/leaverequestform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function getLeaveDates(){
		$result = array();
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			if($this->input->post() && $this->input->post() != null)
			{
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new LeaveRequestCollection();
				if($ret->fetchLeaveDates($post_data['id'])) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			}
			else
			{
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
		}
		echo json_encode($result);
	}

	public function getLeaveApprovals(){
		$result = array();
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new LeaveRequestCollection();
				if($ret->fetchLeaveApprovals($post_data['id'], $post_data['employee_id'])) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			} else {
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
		}
		echo json_encode($result);
	}

	public function updateLeaveRequestForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateLeaveRequest';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/leaverequestform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function updateLeaveRequest(){
		$result = array();
		$page = 'updateLeaveRequest';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				if(isset($_FILES) && $_FILES['file']['tmp_name'] != "") {
					$_FILES['uploadFile']['name'] 		= $_FILES['file']['name'];
					$_FILES['uploadFile']['type'] 		= $_FILES['file']['type'];
					$_FILES['uploadFile']['size'] 		= $_FILES['file']['size'];
					$_FILES['uploadFile']['error'] 		= $_FILES['file']['error'];
					$_FILES['uploadFile']['tmp_name'] = $_FILES['file']['tmp_name'];
					if (!file_exists('./assets/uploads/leaveattachment'))
						mkdir('./assets/uploads/leaveattachment', 0777, true);
					$config['upload_path'] = './assets/uploads/leaveattachment/';
					$config['allowed_types'] = '*';
					$config['overwrite'] = TRUE;
					$config['remove_spaces'] = FALSE;
					$this->upload->initialize($config);
					if ($this->upload->do_upload('uploadFile')):
						$data = array('upload_data' => $this->upload->data());
					else:
						$error = array('error' => $this->upload->display_errors());
					endif;
				}
				$post_data = array();
				if(isset($_FILES) && $_FILES['file']['tmp_name'] != "") {
					$post_data  = $this->array_push_assoc($post_data, 'file_name', $_FILES['file']['name']);
					$post_data  = $this->array_push_assoc($post_data, 'file_size', $_FILES['file']['size']);
				}
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new LeaveRequestCollection();
				if($ret->updateRows($post_data)) {
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

	function array_push_assoc($array, $key, $value){
		$array[$key] = $value;
		return $array;
	}
	
	public function dateDiff($d1,$d2) {
		// Return the number of days between the two dates:    
		return round(abs(strtotime($d1) - strtotime($d2))/86400);	
	}

	public function validateDaterange(){
		$result = array();
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			if($this->input->post() && $this->input->post() != null)
			{
				$inclusivedaterange = $this->input->post('inclusivedaterange');
				$dates = explode(' - ', $inclusivedaterange);

				$start = new DateTime($dates[0]);
				$end = new DateTime($dates[1]);
				$oneday = new DateInterval("P1D");
				$weekdays = array();

				$number_of_days = 0;

				foreach(new DatePeriod($start, $oneday, $end->add($oneday)) as $day) {
					$day_num = $day->format("N"); /* 'N' number days 1 (mon) to 7 (sun) */
					if($day_num < 6) { /* weekday */
						array_push($weekdays, $day->format("Y-m-d"));
						$number_of_days ++;
					} 
				} 

				$ret =  new LeaveRequestCollection();
				if($ret->countHoliday($weekdays, $number_of_days)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			}
			else
			{
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
		}
		echo json_encode($result);
	}
}

?>	