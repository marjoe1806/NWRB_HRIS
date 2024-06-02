<?php

class DailyTimeRecordSummary extends MX_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('DailyTimeRecordSummaryCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::EMPLOYEE_DTR_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewDailyTimeRecordSummary";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/dailytimerecordsummarylist",$listData,TRUE);
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Daily Time Record');
			Helper::setMenu('templates/menu_template');
			Helper::setView('dailytimerecordsummary',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}

	function fetchRows(){
    $fetch_data = $this->DailyTimeRecordSummaryCollection->make_datatables();
		$data = array();
		foreach($fetch_data as $k => $row)
		{
			$buttons = "";
			$buttons_data = "";
			$row->employee_number = $this->Helper->decrypt($row->employee_number,$row->id);
			$row->employee_id_number = $this->Helper->decrypt($row->employee_id_number,$row->id);
			$row->first_name = $this->Helper->decrypt($row->first_name,$row->id);
			$row->middle_name = $this->Helper->decrypt($row->middle_name,$row->id);
			$row->last_name = $this->Helper->decrypt($row->last_name,$row->id);
			$sub_array = array();
			$sub_array[] = $row->employee_id_number;
			$sub_array[] = $row->first_name.' '.$row->last_name;
			$sub_array[] = $row->department_name;
			$sub_array[] = $row->office_name;
			$sub_array[] = $row->position_name;
			$sub_array[] = $row->pay_basis;
			foreach($row as $k1=>$v1){
				$buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
			}
			$buttons .= ' <a id="viewDailyTimeRecordSummaryForm" '
								. ' class="viewDailyTimeRecordSummaryForm" style="text-decoration: none;" '
								. ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewDailyTimeRecordSummaryForm" '
								. $buttons_data
								. ' > '
								. ' <button class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Summary">'
								. ' <i class="material-icons">remove_red_eye</i> '
								. ' </button> '
								. ' </a> ';
			$sub_array[] = $buttons;
			$data[] = $sub_array;
		}
		$output = array(
				"draw"                  =>     intval($_POST["draw"]),
				"recordsTotal"          =>     $this->DailyTimeRecordSummaryCollection->get_all_data(),
				"recordsFiltered"     	=>     $this->DailyTimeRecordSummaryCollection->get_filtered_data(),
				"data"                  =>     $data
		);
		echo json_encode($output);
	}

	public function viewDailyTimeRecordSummaryForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewDailyTimeRecordSummary';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('helpers/dailytimerecordsummary.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function attendanceSummaryContainer(){
		$formData = array();
		$result = array();
		$result['key'] = 'attendanceSummaryContainer';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('helpers/dailytimerecordsummary.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function viewDailyTimeRecordSummaryAll(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewDailyTimeRecordSummaryAll';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$result['form'] = $this->load->view('helpers/dailytimerecordsummary.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	function fetchRowsSummary(){
		// var_dump($_GET);die();
		$total_undertime = 0;
		$total_absence = 0;
		$fetch_data = $this->DailyTimeRecordSummaryCollection->make_datatables_summary($_GET['Id'], $_GET['EmployeeNumber'], $_GET['PayrollPeriod'], $_GET['PayrollPeriodId'], $_GET['ShiftId']);
		// var_dump(json_encode($fetch_data)); die();
		$attendance_settings = $this->DailyTimeRecordSummaryCollection->checkGlobalSettings();
		$formData['flag_ceremony_day'] = $this->DailyTimeRecordSummaryCollection->getFlagCeremonyDay($_GET['PayrollPeriod']);
		$formData['holidays'] = $this->DailyTimeRecordSummaryCollection->getHolidays($_GET['PayrollPeriod']);

		$employeeShiftHistory = $this->DailyTimeRecordSummaryCollection->getEmployeeShiftHistory($_GET['Id'], $_GET['PayrollPeriod']);
		// var_dump(json_encode($employeeShiftHistory));
	
		if(sizeof($employeeShiftHistory) == 0) 
			$employeeShiftHistory =  $this->DailyTimeRecordSummaryCollection->getCurrentEmployeeShiftHistory($_GET['Id'], $_GET['PayrollPeriod']);
		// var_dump(json_encode($employeeShiftHistory)); die();
		$employeeSchedule = array();
		foreach ($employeeShiftHistory as $k => $v) {
			if($v['shift_type'] == 0)
				$employeeSchedule[$v['shift_date_effectivity']] = $this->DailyTimeRecordSummaryCollection->getEmployeeFlexibleSchedule($v['shift_id']);
			else 
				$employeeSchedule[$v['shift_date_effectivity']] = $this->DailyTimeRecordSummaryCollection->getEmployeeRegularSchedule($v['shift_id']);
		}
		$formData['employee_schedule'] = $employeeSchedule;
		// var_dump(json_encode($employeeSchedule)); die();
		foreach($fetch_data['records'] as $k => $v) {
			$key = array(
				"date" => $k,
				"employee_id" => $_GET['Id'],
			);
			$actual     = $this->getAttendanceV2($v['actual'],"actual", $key);
			$adjustment = $this->getAttendanceV2($v['adjustment'],"adjustment", $key);

			// Actual Logs
			$logs[$k]["actual_am_arrival"] 		= $actual['am_arrival'];
			$logs[$k]["actual_am_departure"] 	= $actual['am_departure'];
			$logs[$k]["actual_pm_arrival"] 		= $actual['pm_arrival'];
			$logs[$k]["actual_pm_departure"] 	= $actual['pm_departure'];
			$logs[$k]["actual_overtime_in"] 	= $actual['overtime_in'];
			$logs[$k]["actual_overtime_out"] 	= $actual['overtime_out'];

			// Adjustments
			$logs[$k]["adjustment_am_arrival"] 	= $adjustment['am_arrival'];
			$logs[$k]["adjustment_am_departure"] = $adjustment['am_departure'];
			$logs[$k]["adjustment_pm_arrival"] 	= $adjustment['pm_arrival'];
			$logs[$k]["adjustment_pm_departure"] = $adjustment['pm_departure'];
			$logs[$k]["adjustment_overtime_in"] = $adjustment['overtime_in'];
			$logs[$k]["adjustment_overtime_out"] = $adjustment['overtime_out'];
			$logs[$k]["remarks"] = @$adjustment['remarks'];



		}

		foreach ($fetch_data['employee'] as $k => $value) {
			$fetch_data['employee'][$k]['employee_number'] = $this->Helper->decrypt($value['employee_number'], $value['id']);
			$fetch_data['employee'][$k]['employee_id_number'] = $this->Helper->decrypt($value['employee_id_number'], $value['id']);
			$fetch_data['employee'][$k]['last_name'] = $this->Helper->decrypt($value['last_name'], $value['id']);
			$fetch_data['employee'][$k]['first_name'] = $this->Helper->decrypt($value['first_name'], $value['id']);
			$fetch_data['employee'][$k]['middle_name'] = $this->Helper->decrypt($value['middle_name'], $value['id']);
			$fetch_data['employee'][$k]['location'] = $this->DailyTimeRecordSummaryCollection->getLocation($value['location_id']);
			$fetch_data['employee'][$k]['department'] = $this->DailyTimeRecordSummaryCollection->getDepartmentById($value['division_id']);
			$fetch_data['employee'][$k]['regular_shift'] = $value['regular_shift'];
			$fetch_data['employee'][$k]['total_undertime'] = $total_undertime;
			$fetch_data['employee'][$k]['total_absence'] = $total_absence;
		}
		$fetch_data['employee'][0]['schedule'] = isset($employee_schedule['shift_code']) ? @$employee_schedule['shift_code'] : null;
		$formData['list'] = $logs;
		// var_dump(json_encode($formData['employee_schedule'])); die();
		// var_dump(json_encode($formData['list']));die();
		$formData['employee'] = @$fetch_data['employee'][0];
		$formData['details'] = @$fetch_data['details'][0];
		$formData['payroll_period'] = @$fetch_data['payroll_period'];
		$formData['key'] = "viewDailyTimeRecordSummary";
		$result['table'] = $this->load->view('helpers/dailytimerecordsummary.php', $formData, TRUE);
		$result['key'] = "viewDailyTimeRecordSummary";
		echo json_encode($result);

	}

	function getEmployeeList(){
		// var_dump($_POST); die();
		// $result['division'] = @$_POST['division'];
		$employee_sort = array();
		$employees = @$this->DailyTimeRecordSummaryCollection->getEmployeeList($_POST['pay_basis'], $_POST['location_id']);
		// var_dump($employees); die();
		foreach ($employees as $k => $value) {
			$employees[$k]['employee_number'] = $this->Helper->decrypt($value['employee_number'], $value['id']);
			$employees[$k]['employee_id_number'] = $this->Helper->decrypt($value['employee_id_number'], $value['id']);
			$employees[$k]['last_name'] = $this->Helper->decrypt($value['last_name'], $value['id']);
			$employees[$k]['first_name'] = $this->Helper->decrypt($value['first_name'], $value['id']);
			$employees[$k]['middle_name'] = $this->Helper->decrypt($value['middle_name'], $value['id']);
			$employee_sort[$k] = $employees[$k]['last_name'];
		}
		array_multisort($employee_sort, SORT_ASC, $employees);
		$formData['list'] = $employees;
		$formData['key'] = "viewEmployees";
		$result['table'] = $this->load->view('helpers/employeelist.php', $formData, TRUE);
		$result['key'] = "viewEmployees";
		echo json_encode($result);
	}

	function getTimeDifference($start, $end) {
		$start  = strtotime($start);
		$end = strtotime($end);
		$diff = ($end - $start);
		$minutes = ($diff / 60) / 60;
		return number_format((float)abs($minutes), 2, '.', '');
	}

	function checkPost() {
		$result = $_POST;
		echo json_encode($result);
	}

	function checkTime($time, $type) {
		return isset($time) && $time != null ? date('a', strtotime($time)) == $type ? $time : null : null;
	}

	function getEarliestTime($time = null) {
		// if($time != null) {
			$base_time = $time[0]['transaction_time'];
			$earliest = strtotime($base_time);
			foreach($time as $date){
				if($date['transaction_time'] != null) {
					$curDate = strtotime($date['transaction_time']);
					if ($curDate < $earliest) {
						$earliest = $curDate;
					}
				}

			}
		// }
		
		return $time != null && $base_time != null? date('H:i:s', $earliest) : null;
	}

	function getLastTime($time = null) {
		$last = 0;
		foreach($time as $date){
			if($date['transaction_time'] != null) {
				$curDate = strtotime($date['transaction_time']);
				if ($curDate > $last ) {
					$last  = $curDate;
				}
			}
		}
		return $time != null && $last != 0 ? date('H:i:s', $last) : null;
	}

	function getAttendanceV2($attendance,$ref, $key) {
		$time_in = null;
		$time_out = null;
		$break_in = null;
		$break_out = null;
		$overtime_in = null;
		$overtime_out = null;
		/*-------------------------------------TIMEIN---------------------------------*/
	
			if(isset($attendance['time_in']) && $attendance['time_in'] != null) {
				if(sizeof($attendance['time_in']) > 1) {
					// var_dump("here"); die();
					$time_in = $this->getEarliestTime($attendance['time_in']);
				} else {
					$time_in = $attendance['time_in'][0]['transaction_time'];
				}
			}

			/*-------------------------------------TIMEOUT---------------------------------*/
			if(isset($attendance['time_out']) && $attendance['time_out'] != null) {
				if(sizeof($attendance['time_out']) > 1) {
					$time_out 	= $this->getLastTime($attendance['time_out']);
				} else {
					$time_out = $attendance['time_out'][0]['transaction_time'];
				}
			}	

			/*-----------------------------------BREAKS-----------------------------------*/
			if($attendance['break_out'] != null || $attendance['break_in'] != null) {
				if(sizeof(@$attendance['break_out']) > 1) {
					$break_out 	= $this->getEarliestTime($attendance['break_out']);
				} else {
					$break_out 	= @$attendance['break_out'][0]['transaction_time'];
				}

				if(sizeof(@$attendance['break_in']) > 1) {
					$break_in 	= $this->getLastTime($attendance['break_in']);
				} else {
					$break_in 	= @$attendance['break_in'][0]['transaction_time'];
				}
			}

			/*--------------------------------OVERTIME-----------------------------------*/
			if(isset($attendance['overtime_in']) || isset($attendance['overtime_out']) || $attendance['overtime_in'] != null || $attendance['overtime_out'] != null) {
				if(sizeof(@$attendance['overtime_in']) > 1) {
					$overtime_in 	= $this->getEarliestTime($attendance['overtime_in']);
				} else {
					$overtime_in 	= @$attendance['overtime_in'][0]['transaction_time'];
				}

				if(sizeof(@$attendance['overtime_out']) > 1) {
					$overtime_out 	= $this->getLastTime($attendance['overtime_out']);
				} else {
					$overtime_out 	= @$attendance['overtime_out'][0]['transaction_time'];
				}
			}
			/*-------------------------------- ADDTIONAL REMARKS-----------------------------------*/
			// if($attendance['time_in'] == null || $attendance['time_out'] == null || $attendance['break_out'] == null || $attendance['break_in'] == null) {
				$ret =  new DailyTimeRecordSummaryCollection();
				if($ret->checkOB($key)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
					$result = json_decode($res,true);

					$attendance['remarks'] = $result['Data'][0]['remarks'] == '' ? "OB" : "OB -".$result['Data'][0]['remarks'];
				}
			// }

		$data = array(
			"am_arrival" => $time_in,
			"am_departure" 	=> $break_out,
			"pm_arrival" => $break_in,
			"pm_departure" 	=> $time_out,
			"overtime_in" 	=> $overtime_in,
			"overtime_out" 	=> $overtime_out,
			"remarks" => isset($attendance['remarks']) ? $attendance['remarks'] : null
		);
		return $data;
	}

	function getAttendance($attendance,$ref) {
		// Set default values
		$am_arrival 	= null;
		$am_departure = null;
		$pm_arrival 	= null;
		$pm_departure = null;
		// var_dump($attendance);die();
		/*-------------------------------------TIMEIN---------------------------------*/

			// Check if time in is set and not null
			if(isset($attendance['time_in']) && $attendance['time_in'] != null) {
				// If check in has more than 1 record, system will check for the earliest and last check in
				if(sizeof($attendance['time_in']) > 1) {
					$first_in 	= $this->getEarliestTime($attendance['time_in']);
					$last_in 	= $this->getLastTime($attendance['time_in']);
					// Check time ante if AM or PM
					if(date('a', strtotime($first_in)) == 'am' && date('a', strtotime($last_in)) == 'am')  {
						// if both AM set AM arrival to the earliest check in
						$am_arrival = $first_in;
					} elseif(date('a', strtotime($first_in)) == 'pm' && date('a', strtotime($last_in)) == 'pm') {
						// if both PM set PM arrival to the earliest check in
						$pm_arrival = $first_in;
					} else {
						// if check have different ante (AM and PM), set AM arrival to the earliest in then the PM arrival to the last check in
						$am_arrival = $first_in;
						if($am_arrival != $last_in)
							$pm_arrival = $last_in;
					}
					// If records has only one record, get that first value using 0 index
				} else {
					$current_log = $attendance['time_in'][0]['transaction_time'];
					// check ante if AM or PM
					if(date('a', strtotime($current_log)) == 'am')  {
						$am_arrival = $current_log;
					} elseif(date('a', strtotime($current_log)) == 'pm')  {
						if($am_arrival != $current_log)
						$pm_arrival = $current_log;
					}
				}
			}

			/*-------------------------------------TIMEOUT---------------------------------*/

			// Check if time in is set and not null
			if(isset($attendance['time_out']) && $attendance['time_out'] != null) {
				// If check in has more than 1 record, system will check for the earliest and last check in
				if(sizeof($attendance['time_out']) > 1) {
					//added first_in and last in for validation
					// $first_in 	= $this->getEarliestTime($attendance['time_in']);
					$first_out 	= $this->getEarliestTime($attendance['time_out']);
					$last_out 	= $this->getLastTime($attendance['time_out']);
					// Check time ante if AM or PM
					if(date('a', strtotime($first_out)) == 'am' && date('a', strtotime($last_out)) == 'pm')  {

						if($first_out != $am_arrival && $first_out != $pm_arrival) {
							// if($first_in_arr[0] != $first_out_arr[0])
								$am_departure = $first_out;
						}
						$pm_departure = $last_out;
					} elseif(date('a', strtotime($first_out)) == 'pm' && date('a', strtotime($last_out)) == 'pm') {
						// if both PM set PM departure to the earliest check in
						if($pm_arrival != $last_out)
							$pm_departure = $last_out;
					} elseif(date('a', strtotime($first_out)) == 'am' && date('a', strtotime($last_out)) == 'am') {
						// if both PM set PM departure to the earliest check in
						if($am_arrival != $last_out)
							$am_departure = $last_out;
					}

					else {
						// if check have different ante (AM and PM), set AM departure to the earliest in then the PM departure to the last check in
						if($am_arrival != $first_out)
							$am_departure = $first_out;
						if($pm_arrival != $last_out)
							$pm_departure = $last_out;
					}
					// If records has only one record, get that first value using 0 index

				} else {
					$current_log = $attendance['time_out'][0]['transaction_time'];
					// check ante if AM or PM
					if(date('a', strtotime($current_log)) == 'am')  {
						if($am_arrival != $current_log)
							$am_departure = $current_log;
					} elseif(date('a', strtotime($current_log)) == 'pm')  {
						$pm_departure = $current_log;
					}
				}
			}			

			/*-----------------------------------BREAKS-----------------------------------*/
			if( ($am_arrival != null
					&& date('a', strtotime( $am_arrival )) == 'am') 
					|| (is_array($pm_departure) && sizeof($pm_departure) > 0 && date('a', strtotime($pm_departure)) == 'pm')  
					&& $pm_arrival == null
					) {
				if($attendance['break_out'] != null || $attendance['break_in'] != null) {
					if(sizeof(@$attendance['break_out']) > 1) {
						// Get earliest break out if more than one
						$break_out 	= $this->getBreakOut(array("break_out"=>$attendance['break_out']));
					} else {
						$break_out 	= @$attendance['break_out'][0]['transaction_time'];
					}

					if($break_out == $am_arrival || $break_out == $pm_departure)
							$break_out 	= null;

					if(sizeof(@$attendance['break_in']) > 1) {
						// Get last break if more than one
						$break_in 	= $this->getLastTime($attendance['break_in']);
					} else {
						$break_in 	= @$attendance['break_in'][0]['transaction_time'];
					}

					if($break_in == $am_arrival || $break_in == $pm_departure)
							$break_in 	= null;

					if($break_out != null){
						$am_departure =  $break_out;
						$pm_arrival = $break_in;
					}
				}
				/*else{
					if($this->getLastTime($attendance['time_out']) != null && date('a', strtotime($this->getLastTime($attendance['time_out']))) == 'pm'){
						$am_departure = null;
						$pm_arrival = null;
					}
				}	*/
			}
			if($ref == "actual"){
				if(is_array($attendance['break_out']) && sizeof($attendance['break_out']) == 0):
					if(($am_arrival != null || $pm_departure != null) && $pm_arrival == null){
						if(((int)str_replace(":", "", $am_arrival) < 110000 && (int)str_replace(":", "", $am_arrival) >=40000) 
							|| ((int)str_replace(":", "", $pm_departure) > 140000 && (int)str_replace(":", "", $pm_departure) < 220000)){
							$break_out = $this->getBreakOut($attendance);
							if($break_out != null && $break_out != $am_arrival && $break_out != $pm_departure){
								
								/*
									*start*
									author: MARGEN
									description:
										additional validation to prevent setting breakout with the same hh of am arrival/pm departure
								*/
								if($am_arrival != null && $pm_departure != null) {
									$am_arrival_arr = explode(":",$am_arrival);
									$pm_departure_arr = explode(":",$pm_departure);
									$break_out_arr = explode(":",$break_out);
									if(($am_arrival_arr[0] != $break_out_arr[0]) && ($pm_departure_arr[0] != $break_out_arr[0])) {
										$am_departure = $break_out;
										$pm_arrival = $break_out;
									}
								}
								/*
									*end*
								*/
							}
						}
					}
				endif;
			}
			// var_dump((int)str_replace(":", "", $attendance['time_out'][0]) - (int)str_replace(":", "", $attendance['time_in'][0]) >= 20000);die();
			// Check if employee has multiple logs on either break out or in
			$data = array(
				"am_arrival" 		=> $am_arrival,
				"am_departure" 	=> $am_departure,
				"pm_arrival" 		=> $pm_arrival,
				"pm_departure" 	=> $pm_departure,
				"remarks"				=> isset($attendance['remarks']) ? $attendance['remarks'] : null
			);
			if($ref == "actual"){
				if(!(is_array($attendance['time_in']) && sizeof($attendance['time_in']) > 0 && sizeof($attendance['time_out']) > 0)):
					$merged = array();
					if(is_array($attendance['time_in'])){
						$merged = array_merge($attendance['time_in'],$merged);
					}
					if(is_array($attendance['time_out'])){
						$merged = array_merge($attendance['time_out'],$merged);
					}
					if(is_array($attendance['break_out'])){
						$merged = array_merge($attendance['break_out'],$merged);
					}
					if(is_array($attendance['break_in'])){
						$merged = array_merge($attendance['break_in'],$merged);
					}
					if(is_array($attendance['no_type'])){
						$merged = array_merge($attendance['no_type'],$merged);
					}
					$attendance = $this->array_sort_by_column($merged, 'transaction_time');
					if(sizeof($attendance) > 0){
						$base_time = $attendance[0]['transaction_time'];
						$end_time  = $attendance[sizeof($attendance) - 1]['transaction_time'];
						/////AM to PM missing
						//If AM arrival is missing
						if($data['am_arrival'] == null && $data['pm_departure'] != null){
							$am_arrival = $this->getEarliestTimeByTime($attendance,$base_time);
							if((int)str_replace(":", "", $data['pm_departure']) - (int)str_replace(":", "", $am_arrival) > 20000){
								if((int)str_replace(":", "", $am_arrival) < 103000 
									&& (int)str_replace(":", "", $data['pm_departure']) < 220000)
									if($am_arrival != $pm_departure && $am_arrival != $am_departure && $am_arrival != $pm_arrival)
										$data['am_arrival'] = $am_arrival;
							}
						}
						if($data['am_arrival'] != null && $data['pm_departure'] == null){
							$pm_departure = $this->getLastTimeByTime($attendance,$end_time);
							if((int)str_replace(":", "", $pm_departure) - (int)str_replace(":", "", $data['am_arrival']) > 20000){
								if((int)str_replace(":", "", $data['am_arrival']) < 103000 
									&& (int)str_replace(":", "", $pm_departure) > 133000 )
									if($pm_departure != $am_arrival && $pm_departure != $am_departure && $pm_departure != $pm_arrival)
										$data['pm_departure'] = $pm_departure;
							}
						}
						/////PM to AM missing
						if($data['pm_arrival'] == null && $data['am_departure'] != null){
							$pm_arrival = $this->getEarliestTimeByTime($attendance,$end_time);
							if((int)str_replace(":", "", $pm_arrival) - (int)str_replace(":", "", $data['am_departure']) > 20000){
								if((int)str_replace(":", "", $data['am_departure']) < 103000 
									&& (int)str_replace(":", "", $pm_arrival) < 150000)
									if($pm_arrival != $pm_departure && $pm_arrival != $am_departure && $pm_arrival != $am_arrival)
										$data['pm_arrival'] = $pm_arrival;
							}
						}
						if($data['pm_arrival'] != null && $data['am_departure'] == null){
							$am_departure = $this->getLastTimeByTime($attendance,$base_time);
							if((int)str_replace(":", "", $data['pm_arrival']) - (int)str_replace(":", "", $am_departure) > 20000){
								if((int)str_replace(":", "", $am_departure) > 150000 
									&& (int)str_replace(":", "", $data['pm_arrival']) < 103000 )
									if($am_departure != $pm_departure && $am_departure != $pm_arrival && $am_departure != $am_arrival)
										$data['am_departure'] = $am_departure;
							}
						}
						//2 - 11 pm
						if($data['pm_arrival'] != null && $data['am_departure'] == null && $data['pm_departure'] == null){
							if((int)str_replace(":", "", $data['pm_arrival']) < 150000){
								if((int)str_replace(":", "", $end_time) - (int)str_replace(":", "", $data['pm_arrival']) > 20000)
									if($end_time != $am_departure && $end_time != $pm_departure && $end_time != $pm_arrival && $end_time != $am_arrival)
										$data['pm_departure'] = $end_time;
							}
						}
						if($data['pm_arrival'] == null && $data['am_arrival'] == null && $data['pm_departure'] != null){
							if((int)str_replace(":", "", $data['pm_departure']) > 200000){
								// die('hit');
								if((int)str_replace(":", "", $data['pm_departure']) - (int)str_replace(":", "", $base_time) > 20000)
									if($base_time != $am_departure && $base_time != $pm_departure && $base_time != $pm_arrival && $base_time != $am_arrival)
										$data['pm_arrival'] = $base_time;
							}
						}
						
					}
				endif;
			}
			return $data;
			
	}
	function getBreakOut($attendance){
		$merged = array();
		if(is_array(@$attendance['time_in'])){
			$merged = array_merge($attendance['time_in'],$merged);
		}
		if(is_array(@$attendance['time_out'])){
			$merged = array_merge($attendance['time_out'],$merged);
		}
		if(is_array(@$attendance['break_out'])){
			$merged = array_merge($attendance['break_out'],$merged);
		}
		if(is_array(@$attendance['break_in'])){
			$merged = array_merge($attendance['break_in'],$merged);
		}
		if(is_array(@$attendance['no_type'])){
			$merged = array_merge($attendance['no_type'],$merged);
		}
		$attendance = $this->array_sort_by_column($merged, 'transaction_time');
		$lunch_out = $this->getBreakOutByTime($attendance,@$attendance[0]['transaction_time'],@$attendance[sizeof($attendance) - 1]['transaction_time']);
		return $lunch_out;
	}
	function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
	    $sort_col = array();
	    foreach ($arr as $key=> $row) {
	        $sort_col[$key][$col] = $row[$col];
	    }
	    array_multisort($sort_col, $dir, $arr);
	    return $sort_col;
	}

	function getEarliestTimeByTime($time,$base_time) {
		if(sizeof($time) > 0){
			$start_time = (int)str_replace(":", "", $base_time);
			$limit_time = 20000;//Get before 2 hours of time
			$end_time = $start_time - $limit_time;
			$chosen_time = $base_time;
			foreach ($time as $k => $v) {
				$transaction_time = (int)str_replace(":", "", $v['transaction_time']);
				if($transaction_time >= $end_time && $transaction_time < $start_time){
					$chosen_time = $v['transaction_time'];
				}
			}

			// var_dump($base_time);die();
			return $chosen_time;
		}
		return null;
	}

	function getLastTimeByTime($time,$base_time) {
		if(sizeof($time) > 0){
			$start_time = (int)str_replace(":", "", $base_time);
			$limit_time = 20000;//Get after 2 hours of time
			$end_time = $start_time + $limit_time;
			$chosen_time = $base_time;
			foreach ($time as $k => $v) {
				$transaction_time = (int)str_replace(":", "", $v['transaction_time']);
				if($transaction_time > $chosen_time &&  $transaction_time <= $end_time){
					$chosen_time = $v['transaction_time'];
				}
			}
			return $chosen_time;
		}
		return null;
	}
	function getBreakOutByTime($time,$base_time,$final_time){
		if(sizeof($time) > 0){
			// $start_time = (int)str_replace(":", "", $base_time);
			// $final_time = (int)str_replace(":", "", $final_time);
			// $limit_time = 20000;//Get after 2 hours of time
			// $lunch_time = $start_time + $limit_time;
			// $final_lunch_time = $final_time - $limit_time;
			$chosen_time = null;
			foreach ($time as $k => $v) {
				$transaction_time = (int)str_replace(":", "", $v['transaction_time']);
				if($transaction_time >= 100000 && $transaction_time <= 143000){
					$chosen_time = $v['transaction_time'];
					break;
				}
			}

			$departure_time = $this->getLastTime($time,$base_time);
			if($departure_time == $chosen_time)
				$chosen_time = null;
			// var_dump($base_time);die();
			return $chosen_time;
		}
		return null;
	}

}

?>
