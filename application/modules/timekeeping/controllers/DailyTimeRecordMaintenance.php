<?php

class DailyTimeRecordMaintenance extends MX_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('DailyTimeRecordMaintenanceCollection');
		$this->load->model('ImportFromExcelCollection');
		$this->load->library('upload');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		$listData = array();
		$viewData = array();
		$page = "viewDailyTimeRecordMaintenance";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/dailytimerecordmaintenancelist",$listData,TRUE);
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Daily Time Record Maintenance');
			Helper::setMenu('templates/menu_template');
			Helper::setView('dailytimerecordmaintenance',$viewData,FALSE);
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
		$fetch_data = $this->DailyTimeRecordMaintenanceCollection->make_datatables();

		$data = array();
		foreach($fetch_data as $k => $row)
		{
			$buttons = "";
			$buttons_data = "";
			$row->employee_id_number = $this->Helper->decrypt($row->employee_id_number,$row->id);
			$row->first_name = $this->Helper->decrypt($row->first_name,$row->id);
			$row->middle_name = $this->Helper->decrypt($row->middle_name,$row->id);
			$row->last_name = $this->Helper->decrypt($row->last_name,$row->id);
			$sub_array = array();
			foreach($row as $k1=>$v1){
				$buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
			}
			$buttons .= ' <a id="viewDailyTimeRecordMaintenanceForm" '
			. ' class="viewDailyTimeRecordMaintenanceForm" style="text-decoration: none;" '
			. ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewDailyTimeRecordMaintenanceForm" '
			. $buttons_data
			. ' > '
			. ' <button class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Summary">'
			. ' <i class="material-icons">remove_red_eye</i> '
			. ' </button> '
			. ' </a> ';
			$sub_array[] = $buttons;
			$sub_array[] = $row->emp_number;
			$sub_array[] = $row->first_name.' '.$row->last_name;
			$sub_array[] = $row->department_name;
			$sub_array[] = $row->office_name;
			$sub_array[] = $row->position_name;
			$data[] = $sub_array;
		}
		$output = array(
				"draw"                  =>     intval($_POST["draw"]),
				"recordsTotal"          =>     $this->DailyTimeRecordMaintenanceCollection->get_all_data(),
				"recordsFiltered"     	=>     $this->DailyTimeRecordMaintenanceCollection->get_filtered_data(),
				"data"                  =>     $data
		);
		echo json_encode($output);
	}

	public function viewDailyTimeRecordMaintenanceForm() {
		$formData = array();
		$result = array();
		$result['key'] = 'viewDailyTimeRecordMaintenance';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('helpers/dailytimerecordmaintenance.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function attendanceSummaryContainer() {
		$formData = array();
		$result = array();
		$result['key'] = 'attendanceSummaryContainer';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('helpers/dailytimerecordmaintenance.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function viewDailyTimeRecordMaintenanceAll() {
		$formData = array();
		$result = array();
		$result['key'] = 'viewDailyTimeRecordMaintenanceAll';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$result['form'] = $this->load->view('helpers/dailytimerecordmaintenance.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	function fetchRowsSummary() {
		$fetch_data = $this->DailyTimeRecordMaintenanceCollection->make_datatables_summary($_GET['Id'], $_GET['EmployeeNumber'], $_GET['PayrollPeriod'], $_GET['PayrollPeriodId'], $_GET['ShiftId']);
		$employee 	= $fetch_data['employee'][0];
		// var_dump(json_encode($fetch_data['records']));die();
		foreach($fetch_data['records'] as $working_day => $attendance) {

			// Get actual attendance and adjustments
			$actual = $this->getAttendanceV2(@$attendance['actual'],"actual");
			$adjustment = $this->getAttendanceV2(@$attendance['adjustment'],"adjustment");
			$dtr = $this->getAttendanceV2(@$attendance['dtr'],"dtr");
			$dtr_adjustment = $this->getAttendanceV2(@$attendance['dtr_adjustments'],"dtr_adjustments");

			// Actual Logs
			$logs[$working_day]["actual_am_arrival"] 	= $actual['am_arrival'];
			$logs[$working_day]["actual_am_departure"] 	= $actual['am_departure'];
			$logs[$working_day]["actual_pm_arrival"] 	= $actual['pm_arrival'];
			$logs[$working_day]["actual_pm_departure"] 	= $actual['pm_departure'];
			$logs[$working_day]["actual_overtime_in"] 	= $actual['overtime_in'];
			$logs[$working_day]["actual_overtime_out"] 	= $actual['overtime_out'];

			// Adjustments
			$logs[$working_day]["adjustment_am_arrival"] 	= $adjustment['am_arrival'];
			$logs[$working_day]["adjustment_am_departure"] 	= $adjustment['am_departure'];
			$logs[$working_day]["adjustment_pm_arrival"] 	= $adjustment['pm_arrival'];
			$logs[$working_day]["adjustment_pm_departure"] 	= $adjustment['pm_departure'];
			$logs[$working_day]["adjustment_overtime_in"] = $adjustment['overtime_in'];
			$logs[$working_day]["adjustment_overtime_out"] = $adjustment['overtime_out'];

			// dtr
			$logs[$working_day]["dtr_am_arrival"] 	= $dtr['am_arrival'];
			$logs[$working_day]["dtr_am_departure"] 	= $dtr['am_departure'];
			$logs[$working_day]["dtr_pm_arrival"] 	= $dtr['pm_arrival'];
			$logs[$working_day]["dtr_pm_departure"] 	= $dtr['pm_departure'];
			$logs[$working_day]["dtr_overtime_in"] = $dtr['overtime_in'];
			$logs[$working_day]["dtr_overtime_out"] = $dtr['overtime_out'];

			// dtr Adjustments
			$logs[$working_day]["dtr_adjustment_am_arrival"] 	= $dtr_adjustment['am_arrival'];
			$logs[$working_day]["dtr_adjustment_am_departure"] 	= $dtr_adjustment['am_departure'];
			$logs[$working_day]["dtr_adjustment_pm_arrival"] 	= $dtr_adjustment['pm_arrival'];
			$logs[$working_day]["dtr_adjustment_pm_departure"] 	= $dtr_adjustment['pm_departure'];
			$logs[$working_day]["dtr_adjustment_overtime_in"] = $dtr_adjustment['overtime_in'];
			$logs[$working_day]["dtr_adjustment_overtime_out"] = $dtr_adjustment['overtime_out'];
			
			$logs[$working_day]["remarks"] 					= $dtr_adjustment['remarks'] != null || $dtr_adjustment['remarks'] != "" ? $dtr_adjustment['remarks'] : $dtr['remarks'];
		}

		// Prepare data before passing to view
		$data = array(
			"id" 					=> $employee['id'],
			"payroll_period"		=> $fetch_data['payroll_period'],
			"employee_number"	 	=> $this->Helper->decrypt($employee['employee_number'], $employee['id']),
			"last_name" 			=> $this->Helper->decrypt($employee['last_name'], 			$employee['id']),
			"middle_name" 			=> $this->Helper->decrypt($employee['middle_name'], 		$employee['id']),
			"first_name" 			=> $this->Helper->decrypt($employee['first_name'], 			$employee['id']),
			"attendance" 			=> $logs,
		);
		
		// Finalize form data and view
		$formData['list'] 						= $data;
		$formData['payroll_period'] 			= $fetch_data['payroll_period'];
		$formData['key'] 						= "viewDailyTimeRecordMaintenance";
		$result['table'] 						= $this->load->view('helpers/dailytimerecordmaintenance.php', $formData, TRUE);
		$result['key'] 							= "viewDailyTimeRecordMaintenance";
		echo json_encode($result);
	}

	public function addDailyTimeRecordMaintenanceForm() {
		$formData = array();
		$result = array();
		$result['key'] = 'addDailyTimeRecordMaintenance';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/dailytimerecordmaintenanceform.php', $formData, TRUE);
		}

		echo json_encode($result);
	}
	public function updateDailyTimeRecordMaintenanceForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateDailyTimeRecordMaintenance';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/dailytimerecordmaintenanceform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function addDailyTimeRecordMaintenance() {
		$result = array();
		$page = 'addDailyTimeRecordMaintenance';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			if($this->input->post() && $this->input->post() != null) {
				$_FILES['uploadFile']['name'] = $_FILES['file']['name'];
				$_FILES['uploadFile']['type'] = $_FILES['file']['type'];
				$_FILES['uploadFile']['tmp_name'] = $_FILES['file']['tmp_name'];
				$_FILES['uploadFile']['error'] = $_FILES['file']['error'];
				$_FILES['uploadFile']['size'] = $_FILES['file']['size'];
				if (!file_exists('./assets/uploads/accomplishmentreports')) {
					mkdir('./assets/uploads/accomplishmentreports', 0777, true);
				}
				$config['upload_path'] = './assets/uploads/accomplishmentreports/';
				$config['allowed_types'] = '*';
				$config['overwrite'] = TRUE;
				$config['remove_spaces'] = FALSE;
				$this->upload->initialize($config);
				if ($this->upload->do_upload('uploadFile')):
				$data = array('upload_data' => $this->upload->data());
				else:
						$error = array('error' => $this->upload->display_errors());
				endif;

				$post_data = array();
				$post_data  = $this->array_push_assoc($post_data, 'file_name', $_FILES['file']['name']);
				$post_data  = $this->array_push_assoc($post_data, 'file_size', $_FILES['file']['size']);
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new DailyTimeRecordMaintenanceCollection();
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

	public function updateDailyTimeRecordMaintenance() {
		$result = array();
		$page = 'updateDailyTimeRecordMaintenance';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new DailyTimeRecordMaintenanceCollection();
				$getNo = $ret->getEmployeeNumber($_POST["employee_id"]);
				$SCANNING_NUMBER = isset($getNo["employee_number"]) ? $getNo["employee_number"] : null;
				$DATE = isset($_POST["transaction_date"]) ? $_POST["transaction_date"] : null;
				$DATE = date_create($DATE);
				$DATE = date_format($DATE,"Y-m-d");
				$DTRCRED = array(
					"SCANNING_NUMBER" => $SCANNING_NUMBER,
					"TRANSACTION_DATE" => $DATE,
					"AM_ARRIVAL" => isset($_POST['transaction_time'][0]) ? $_POST['transaction_time'][0] : null,
					"AM_DEPARTURE" => isset($_POST['transaction_time'][1]) ? $_POST['transaction_time'][1] : null,
					"PM_ARRIVAL" => isset($_POST['transaction_time'][2]) ? $_POST['transaction_time'][2] : null,
					"PM_DEPARTURE" => isset($_POST['transaction_time'][3]) ? $_POST['transaction_time'][3] : null,
					"ACTUAL_AM_ARRIVAL" => isset($_POST['transaction_time'][0]) ? $_POST['transaction_time'][0] : null,
					"ACTUAL_AM_DEPARTURE" => isset($_POST['transaction_time'][1]) ? $_POST['transaction_time'][1] : null,
					"ACTUAL_PM_ARRIVAL" => isset($_POST['transaction_time'][2]) ? $_POST['transaction_time'][2] : null,
					"ACTUAL_PM_DEPARTURE" => isset($_POST['transaction_time'][3]) ? $_POST['transaction_time'][3] : null,
					"OT_ARRIVAL" => isset($_POST['transaction_time'][4]) ? $_POST['transaction_time'][4] : null,
					"OT_DEPARTURE" => isset($_POST['transaction_time'][5]) ? $_POST['transaction_time'][5] : null,
					"OT_TOTAL" => 0,
					"REMARKS" => "",
					"ADJUSTMENT_REMARKS" => $_POST['remarks']
				);

				$DTRCRED = $this->dailyDtr($DTRCRED);
				if($ret->updateRows($post_data,$DTRCRED)) $res = new ModelResponse($ret->getCode(), $ret->getMessage());
				else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
				$result = json_decode($res,true);
			} else {
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	public function activateDailyTimeRecordMaintenanceForm() {
		$result = array();
		$page = 'activateDailyTimeRecordMaintenance';
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
				$ret =  new DailyTimeRecordMaintenanceCollection();
				if($ret->activeRows($post_data)) {
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

	public function deactivateDailyTimeRecordMaintenanceForm() {
		$result = array();
		$page = 'deactivateDailyTimeRecordMaintenance';
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
				$ret =  new DailyTimeRecordMaintenanceCollection();
				if($ret->inactiveRows($post_data)) {
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

	function getAttendanceV2($attendance,$ref) {
		$time_in = null;
		$time_out = null;
		$break_in = null;
		$break_out = null;
		$overtime_in = null;
		$overtime_out = null;

		/*-------------------------------------TIMEIN---------------------------------*/
		if(isset($attendance['time_in']) && $attendance['time_in'] != null) {
			if(sizeof($attendance['time_in']) > 1) {
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
		return isset($time) && $time != null && $base_time != null && $earliest != null ? date('G:i:s', $earliest) : null;
	}
	function getEarliestBreakTime($time_in,$time) {
		$base_time = $time[0]['transaction_time'];
		/*if($time_in = '07:56:06'){
			$breakOutTimeInDiff = str_replace(':', '', $time[0]['transaction_time']) - str_replace(':', '', $time_in);
			var_dump($breakOutTimeInDiff);die();
		}*/
		// var_dump($time_in);
		// if($time_in = '07:56:06'){
		$earliest = strtotime($base_time);
		// var_dump($time);die();
		foreach($time as $date){
			if($date['transaction_time'] != null) {
				$curDate = strtotime($date['transaction_time']);
				
					// var_dump(str_replace(':', '', $date['transaction_time']) - str_replace(':', '', $time_in));die();
					
				if(str_replace(':', '', $date['transaction_time']) - str_replace(':', '', $time_in) >= 20000){
					$earliest = $curDate;
					// var_dump($earliest);die();
					break;
				}
			}
			
		}
		// }
		return isset($time) && $time != null && $base_time != null && $earliest != null ? date('G:i:s', $earliest) : null;
	}
	function getLastTime($time) {
		$last = 0;
		foreach($time as $date){
			if($date['transaction_time'] != null) {
				$curDate = strtotime($date['transaction_time']);
				if ($curDate > $last ) {
					$last  = $curDate;
				}
			}
		}
		return isset($time) && $time != null && $last != null ? date('G:i:s', $last) : null;
	}

	function array_push_assoc($array, $key, $value){
		$array[$key] = $value;
		return $array;
	}

	public function repSC($string){
		return (int)str_replace(":", "", $string);
	}

	public function getDeductions($start, $end){
		$elapsed = abs($end - $start);
		$years = abs(floor($elapsed / 31536000));
		$days = abs(floor(($elapsed-($years * 31536000))/86400));
		$hours = abs(floor(($elapsed-($years * 31536000)-($days * 86400))/3600));
		$minutes = abs(floor(($elapsed-($years * 31536000)-($days * 86400)-($hours * 3600))/60));
		return array($hours,$minutes);
	}

	function dailyDtr($DTRCRED){

		$ret = new ImportFromExcelCollection();
		$arrShiftSchedule = $arrShiftHistory = array();
		$shiftId = 0;
		$shiftType = 0;
		$shiftDetails = $ret->getShiftDetails($DTRCRED["SCANNING_NUMBER"]);
		$flagDetails = $ret->getFlagCeremony($DTRCRED["TRANSACTION_DATE"]);
		$isHoliday = $ret->getHoliday($DTRCRED["TRANSACTION_DATE"]);
		$isOB = $ret->getOB($DTRCRED["SCANNING_NUMBER"],$DTRCRED["TRANSACTION_DATE"]);
		$isOffset = $ret->getOffset($DTRCRED["SCANNING_NUMBER"],$DTRCRED["TRANSACTION_DATE"]);
		$approve_offset_hrs = $approve_offset_mins = 0;
		if($isOffset){
			$approve_offset_hrs = $isOffset["offset_hrs"];
			$approve_offset_mins = $isOffset["offset_mins"];
		}
		$DTRCRED["APPROVE_OFFSET_HRS"] = $approve_offset_hrs;
		$DTRCRED["APPROVE_OFFSET_MINS"] = $approve_offset_mins;
		// $isBreak = $ret->getPositionBreak($DTRCRED["SCANNING_NUMBER"]);
		// $isBreak = ($isBreak != null && $isBreak["is_break"] == 1) ? 1 : 0;
		$isDriver = $ret->getIfDriver($DTRCRED["SCANNING_NUMBER"]);
		$isOTCertification = $ret->getCheckifOTCertification($DTRCRED["SCANNING_NUMBER"],$DTRCRED["TRANSACTION_DATE"]);
		// shift date effectivity
		if(strtotime($DTRCRED["TRANSACTION_DATE"]) >= strtotime($shiftDetails["shift_date_effectivity"])) {
			$shiftId = ($shiftDetails["regular_shift"] == 1) ? $shiftDetails["shift_id"] : $shiftDetails["flex_shift_id"];
			$shiftType = $shiftDetails["regular_shift"];
		}else{
			$arrShiftHistory = $ret->getShiftHistory($shiftDetails["id"]);
			foreach($arrShiftHistory as $k => $v){
				if(strtotime($DTRCRED["TRANSACTION_DATE"]) >= strtotime($v["previous_date_effectivity"])){
					$shiftId = $v["shift_id"];
					$shiftType = $v["shift_type"];
					break;
				}
			}
		}
		// get weekly schedule
		if($shiftType == 1) $arrShiftSchedule = $ret->getRegularShiftSchedule($shiftId);
		else $arrShiftSchedule = $ret->getFlexibleShiftSchedule($shiftId);
		$weekday = date("l",strtotime($DTRCRED["TRANSACTION_DATE"])); // current day
		//tardiness
		$am_start_sched = $pm_start_sched = null;
		//ut
		$am_end_sched = $pm_end_sched = null;
		// total
		$tot_off_set = $tot_ot_hrs = $tot_ot_mins = 0;
		$tot_tardiness_hrs = $tot_tardiness_mins = $tot_ut_hrs = $tot_ut_mins = 0;
		$grand_tot_tardiness_hrs = $grand_tot_tardiness_mins = $grand_tot_ut_hrs = $grand_tot_ut_mins = $grand_tot_ot_hrs = $grand_tot_ot_mins = $grand_tot_monetized = 0;

		$time_suspension = "";
		$time_suspension_day = "";

		if ($DTRCRED["AM_ARRIVAL"] != null) $DTRCRED["AM_ARRIVAL"] = date("H:i", strtotime($DTRCRED["AM_ARRIVAL"])).':00';
		if ($DTRCRED["AM_DEPARTURE"] != null) $DTRCRED["AM_DEPARTURE"] = date("H:i", strtotime($DTRCRED["AM_DEPARTURE"])).':00';
		if ($DTRCRED["PM_ARRIVAL"] != null) $DTRCRED["PM_ARRIVAL"] = date("H:i", strtotime($DTRCRED["PM_ARRIVAL"])).':00';
		if ($DTRCRED["PM_DEPARTURE"] != null) $DTRCRED["PM_DEPARTURE"] = date("H:i", strtotime($DTRCRED["PM_DEPARTURE"])).':00';
		if ($DTRCRED["ACTUAL_AM_ARRIVAL"] != null) $DTRCRED["ACTUAL_AM_ARRIVAL"] = date("H:i", strtotime($DTRCRED["ACTUAL_AM_ARRIVAL"])).':00';
		if ($DTRCRED["ACTUAL_AM_DEPARTURE"] != null) $DTRCRED["ACTUAL_AM_DEPARTURE"] = date("H:i", strtotime($DTRCRED["ACTUAL_AM_DEPARTURE"])).':00';
		if ($DTRCRED["ACTUAL_PM_ARRIVAL"] != null) $DTRCRED["ACTUAL_PM_ARRIVAL"] = date("H:i", strtotime($DTRCRED["ACTUAL_PM_ARRIVAL"])).':00';
		if ($DTRCRED["ACTUAL_PM_DEPARTURE"] != null) $DTRCRED["ACTUAL_PM_DEPARTURE"] = date("H:i", strtotime($DTRCRED["ACTUAL_PM_DEPARTURE"])).':00';
		
		if($isOB == null){
			foreach($arrShiftSchedule as $k => $v){
				if($weekday == $v["week_day"]){
					if($v["is_restday"] == 0 && (!$isHoliday || ($isHoliday != null && $isHoliday["holiday_type"] == "Suspension")) ){ // check if rest day
						if($isHoliday){
							$time_suspension = date("H:i:s", strtotime($isHoliday["time_suspension"]) );
							$time_suspension_day = date("A", strtotime($isHoliday["time_suspension"]) );
						}
						if($DTRCRED["AM_ARRIVAL"] != null || $DTRCRED["AM_DEPARTURE"] != null || $DTRCRED["PM_ARRIVAL"] != null || $DTRCRED["PM_DEPARTURE"] != null){ // check if no dtr
							if($shiftType == 1){
								//tardiness
								if($DTRCRED["AM_ARRIVAL"] != null){
									// if( $this->repSC($v["start_time"]) > $this->repSC($DTRCRED["AM_ARRIVAL"]) ) $DTRCRED["AM_ARRIVAL"] = $v["start_time"];
									$am_start_sched = $v["start_time"];
									if($time_suspension_day == "AM") {
										if($this->repSC($am_max_start_date) > $this->repSC($time_suspension)) $am_start_sched = $DTRCRED["AM_ARRIVAL"];
									}
								}
								if($DTRCRED["PM_ARRIVAL"] != null){
									// if( $this->repSC($v["break_end_time"]) > $this->repSC($DTRCRED["PM_ARRIVAL"]) ) $DTRCRED["PM_ARRIVAL"] = $v["break_end_time"];
									$pm_start_sched = $v["break_end_time"];
									if($time_suspension_day == "PM") {
										if($this->repSC($pm_start_sched) > $this->repSC($time_suspension)) $pm_start_sched = $DTRCRED["PM_ARRIVAL"];
									}
								}
								//ut
								if($DTRCRED["AM_DEPARTURE"] != null){
									// if( $this->repSC($v["break_start_time"]) < $this->repSC($DTRCRED["AM_DEPARTURE"]) ) $DTRCRED["AM_DEPARTURE"] = $v["break_start_time"];
									$am_end_sched = $v["break_start_time"];
									if($time_suspension_day == "AM") $am_end_sched = $time_suspension;
								}
								if($DTRCRED["PM_DEPARTURE"] != null){
									// if( $this->repSC($v["end_time"]) < $this->repSC($DTRCRED["PM_DEPARTURE"]) ) $DTRCRED["PM_DEPARTURE"] = $v["end_time"];
									$pm_end_sched = $v["end_time"];
									if($time_suspension_day == "PM") $pm_end_sched = $time_suspension;
								}
								if ($isHoliday != NULL && $isHoliday["is_active"] == 1 && $isHoliday["holiday_type"] == "Suspension") $DTRCRED["ADJUSTMENT_REMARKS"] .= " Work Suspension";
							}else{
								//tardiness
								$late_addtl_hrs = $v["addtl_hrs"];
								if($DTRCRED["AM_ARRIVAL"] != null){
									$am_min_start_date = $v["start_time"];
									if($flagDetails["flagdateceremony"]){
										$am_max_start_date = date('H:i:s',strtotime("08:00:00"));
									}else{
										$am_max_start_date = date('H:i:s',strtotime('+'.$late_addtl_hrs.' hour',strtotime($am_min_start_date)));
									}
									$am_minstart = $this->repSC($am_min_start_date); // shift sched time in
									$am_maxstart = $this->repSC($am_max_start_date); // shift sched time in w/ addiotional 1hr
									if ($flagDetails["flagdateceremony"] != NULL && $flagDetails["is_active"] == 1) $DTRCRED["ADJUSTMENT_REMARKS"] = "FLAG CEREMONY";
									if ($isHoliday != NULL && $isHoliday["is_active"] == 1) $DTRCRED["ADJUSTMENT_REMARKS"] .= " Work Suspension";
									if($this->repSC($DTRCRED["AM_ARRIVAL"]) <= $am_minstart) $am_start_sched = $am_min_start_date;
									else if($this->repSC($DTRCRED["AM_ARRIVAL"]) > $am_minstart && $this->repSC($DTRCRED["AM_ARRIVAL"]) <= $am_maxstart) $am_start_sched = $DTRCRED["AM_ARRIVAL"];
									else if($this->repSC($DTRCRED["AM_ARRIVAL"]) > $am_maxstart) $am_start_sched = $am_max_start_date;
									if($time_suspension_day == "AM") {
										if($this->repSC($am_max_start_date) > $this->repSC($time_suspension)) $am_start_sched = $DTRCRED["AM_ARRIVAL"];
									}
								}
								if($DTRCRED["PM_ARRIVAL"] != null){
									if( $this->repSC($v["break_end_time"]) > $this->repSC($DTRCRED["PM_ARRIVAL"]) ) $DTRCRED["PM_ARRIVAL"] = $v["break_end_time"];
									$pm_start_sched = $v["break_end_time"];
									if($time_suspension_day == "PM") {
										if($this->repSC($pm_start_sched) > $this->repSC($time_suspension)) $pm_start_sched = $DTRCRED["PM_ARRIVAL"];
									}
								}
								//ut
								if($DTRCRED["AM_DEPARTURE"] != null){
									$am_end_sched = $v["break_start_time"];
									if( $this->repSC($v["break_start_time"]) < $this->repSC($DTRCRED["AM_DEPARTURE"]) ) $DTRCRED["AM_DEPARTURE"] = $v["break_start_time"];
									if($time_suspension_day == "AM") $am_end_sched = $time_suspension;
								}
								if($DTRCRED["PM_DEPARTURE"] != null){
									if($DTRCRED["AM_ARRIVAL"] != null){
									//    $pm_end_sched = date('H:i:s',strtotime('+'.($v["addtl_hrs"]+1).' hour', strtotime($this->repSC($DTRCRED["AM_ARRIVAL"]) >= $am_minstart && $this->repSC($DTRCRED["AM_ARRIVAL"]) < $am_maxstart ? $DTRCRED["AM_ARRIVAL"] : $am_start_sched)));
									   if($this->repSC($DTRCRED["AM_ARRIVAL"]) <= $am_minstart) $pm_end_sched = $v["end_time"];
									   else if($this->repSC($DTRCRED["AM_ARRIVAL"]) > $am_minstart && $this->repSC($DTRCRED["AM_ARRIVAL"]) <= $am_maxstart) $pm_end_sched = date('H:i:s',strtotime('+'.($v["working_hours"]+1).' hour', strtotime($DTRCRED["AM_ARRIVAL"]))); 
									   else if($this->repSC($DTRCRED["AM_ARRIVAL"]) > $am_maxstart) $pm_end_sched = date('H:i:s',strtotime('+'.($v["working_hours"]+$v["addtl_hrs"]+1).' hour', strtotime($v["start_time"]))); 
 									}else{
 										$pm_end_sched = $v["end_time"];
									}
									if($time_suspension_day == "PM") $pm_end_sched = $time_suspension;
								}

							}
							//check tardiness
							if($DTRCRED["AM_ARRIVAL"] != null){
								$start = strtotime($am_start_sched);
								$end = strtotime($DTRCRED["AM_ARRIVAL"]);
								if($end > $start) {
									$getDeds = $this->getDeductions($start, $end);
									$tot_tardiness_hrs += $getDeds[0];
									$tot_tardiness_mins += $getDeds[1];
								}
							}

							//check undertime
							if($DTRCRED["PM_DEPARTURE"] != null){
								$start = strtotime($DTRCRED["PM_DEPARTURE"]);
								$end = strtotime($pm_end_sched);
								if($end > $start) {
									$getDeds = $this->getDeductions($start, $end);
									$tot_ut_hrs += $getDeds[0];
									$tot_ut_mins += $getDeds[1];
								}
							}
							
							if($isHoliday["holiday_type"] != "Suspension"){
								//half day
								if($DTRCRED["AM_ARRIVAL"] == null && $DTRCRED["AM_DEPARTURE"] == null){
									$tot_tardiness_hrs += 4;
								}

								if($DTRCRED["PM_ARRIVAL"] == null && $DTRCRED["PM_DEPARTURE"] == null){
									$tot_ut_hrs += 4;
								}

								//late
								if(
									($DTRCRED["AM_ARRIVAL"] == null && $DTRCRED["AM_DEPARTURE"] != null && $DTRCRED["PM_ARRIVAL"] != null && $DTRCRED["PM_DEPARTURE"] != null) ||
									($DTRCRED["AM_ARRIVAL"] == null && $DTRCRED["AM_DEPARTURE"] != null && $DTRCRED["PM_ARRIVAL"] == null && $DTRCRED["PM_DEPARTURE"] != null) ||
									($DTRCRED["AM_ARRIVAL"] == null && $DTRCRED["AM_DEPARTURE"] != null && $DTRCRED["PM_ARRIVAL"] == null && $DTRCRED["PM_DEPARTURE"] == null) ||
									($DTRCRED["AM_ARRIVAL"] == null && $DTRCRED["AM_DEPARTURE"] == null && $DTRCRED["PM_ARRIVAL"] == null && $DTRCRED["PM_DEPARTURE"] != null)
								){
									$tot_tardiness_hrs = 4;
									$tot_ut_hrs = 0;
									$DTRCRED["ADJUSTMENT_REMARKS"] = "See HR";
								}
							
								//undertime
								if(
									($DTRCRED["AM_ARRIVAL"] != null && $DTRCRED["AM_DEPARTURE"] != null && $DTRCRED["PM_ARRIVAL"] != null && $DTRCRED["PM_DEPARTURE"] == null) || 
									($DTRCRED["AM_ARRIVAL"] != null && $DTRCRED["AM_DEPARTURE"] == null && $DTRCRED["PM_ARRIVAL"] != null && $DTRCRED["PM_DEPARTURE"] == null) ||
									($DTRCRED["AM_ARRIVAL"] != null && $DTRCRED["AM_DEPARTURE"] == null && $DTRCRED["PM_ARRIVAL"] == null && $DTRCRED["PM_DEPARTURE"] == null) ||
									($DTRCRED["AM_ARRIVAL"] == null && $DTRCRED["AM_DEPARTURE"] != null && $DTRCRED["PM_ARRIVAL"] != null && $DTRCRED["PM_DEPARTURE"] == null) ||
									($DTRCRED["AM_ARRIVAL"] == null && $DTRCRED["AM_DEPARTURE"] == null && $DTRCRED["PM_ARRIVAL"] != null && $DTRCRED["PM_DEPARTURE"] == null)
								){
									$tot_tardiness_hrs = 0;
									$tot_ut_hrs = 4;
									$DTRCRED["ADJUSTMENT_REMARKS"] = "See HR";
								}
							}
							
							if($isHoliday["holiday_type"] == "Suspension"){
								if($DTRCRED["OT_DEPARTURE"] == null){
									$tot_ut_hrs = 0;
								}
								
								if($DTRCRED["AM_ARRIVAL"] == null && $DTRCRED["PM_DEPARTURE"] == null){
									$tot_tardiness_hrs = 0;
									$time_out = date('H:i:s',strtotime($pm_end_sched));
									$time_suspension_obj = DateTime::createFromFormat('H:i:s', $time_suspension);
									$time_out_obj = DateTime::createFromFormat('H:i:s', $time_out);
									$interval = $time_suspension_obj->diff($time_out_obj);
									$tot_ut_hrs = 8 - $interval->h;
								}

								if($DTRCRED["PM_ARRIVAL"] != null && $DTRCRED["PM_DEPARTURE"] != null){
									if($time_suspension_day == "PM" 
											&& $time_suspension > "12:00:00" 
											&& (strtotime($time_suspension) > strtotime($DTRCRED["PM_DEPARTURE"]) )
									){
										$time_out = date('H:i:s',strtotime($DTRCRED["PM_DEPARTURE"]));
										$time_suspension_obj = DateTime::createFromFormat('H:i:s', $time_suspension);
										$time_out_obj = DateTime::createFromFormat('H:i:s', $time_out);
										$consumed_interval = $time_suspension_obj->diff($time_out_obj);
										$tot_ut_hrs = $consumed_interval->h;
										$tot_ut_mins = $consumed_interval->i;
									}
								}else if($DTRCRED["AM_ARRIVAL"] != null && $DTRCRED["AM_DEPARTURE"] != null){
									if($time_suspension_day == "AM" ||
										($time_suspension_day == "PM" 
											&& $time_suspension == "12:00:00" 
											&& (strtotime($am_end_sched) <= strtotime($time_suspension) )
										)
									){
										$time_out = date('H:i:s',strtotime($DTRCRED["AM_DEPARTURE"]));
										$time_suspension_obj = DateTime::createFromFormat('H:i:s', $time_suspension);
										$time_out_obj = DateTime::createFromFormat('H:i:s', $time_out);
										$consumed_interval = $time_suspension_obj->diff($time_out_obj);
										$tot_ut_hrs = $consumed_interval->h;
										$tot_ut_mins = $consumed_interval->i;
									}
								}
							}

							
							// if($DTRCRED["AM_ARRIVAL"] == null && ($isBreak == 1 && $DTRCRED["PM_ARRIVAL"] == null) && ($isBreak == 1 && $DTRCRED["AM_DEPARTURE"] == null) && $DTRCRED["PM_DEPARTURE"] == null){
							// 	$DTRCRED["ADJUSTMENT_REMARKS"] = "Incomplete";
							// }

							//ot
							if($DTRCRED["OT_ARRIVAL"] != null && $DTRCRED["OT_DEPARTURE"] != null){
								$start = strtotime($DTRCRED["OT_ARRIVAL"]);
								$end = strtotime($DTRCRED["OT_DEPARTURE"]);
								$getDiff = $this->getDeductions($start, $end);
								$tot_ot_hrs += $getDiff[0];
								$tot_ot_mins += $getDiff[1];
								//offset
								$ot = $tot_ot_hrs + ($tot_ot_mins/60);

								if($isOTCertification || $DTRCRED["ADJUSTMENT_REMARKS"] == "OT W/ CERTIFICATION") $grand_tot_monetized = $ot / 8 * 1.25;
								else {
									if($ot > 3){
										$tot_ot_hrs = 3;
										$tot_ot_mins = 0;
									}
									$grand_tot_monetized = (($ot > 3) ? 3 : $ot) / 8 * 1.25;
								}
								// $tot_off_set += (($ot > 3) ? 3 : $ot) / 8 * 1;
								if($tot_tardiness_hrs > 0 || $tot_tardiness_mins > 0){
									$ot = $ot < 2 ? 0 : $ot;
								}
							}
							
							if($tot_ut_hrs >= 8){
								$DTRCRED["ADJUSTMENT_REMARKS"] = "Absent";
							}
						}else if($DTRCRED["ADJUSTMENT_REMARKS"] == "LWOP" || $DTRCRED["ADJUSTMENT_REMARKS"] == "AWOL"){
							$tot_ut_hrs = 8;
							$DTRCRED["ADJUSTMENT_REMARKS"] = $DTRCRED["ADJUSTMENT_REMARKS"];
						}else{
							$tot_ut_hrs = 8;
							$DTRCRED["ADJUSTMENT_REMARKS"] = "Absent";

							$time_in = date('H:i:s',strtotime("08:00:00"));
							$time_out = date('H:i:s',strtotime("17:00:00"));
							if ($isHoliday["holiday_type"] == "Suspension") {
								$time_suspension_obj = DateTime::createFromFormat('H:i:s', $time_suspension);
								$time_out_obj = DateTime::createFromFormat('H:i:s', $time_out);
								$interval = $time_suspension_obj->diff($time_out_obj);
								$tot_ut_hrs -= $interval->h;
							}
						}

					}else if($v["is_restday"] == 1 || ($isHoliday != null && $isHoliday["holiday_type"] != "Suspension")){ // rest day and holiday
						// if($DTRCRED["AM_ARRIVAL"] != null && $DTRCRED["AM_DEPARTURE"] != null && $DTRCRED["PM_ARRIVAL"] != null && $DTRCRED["PM_DEPARTURE"] != null){
							if($isDriver){
								$fix_am_start = $v["start_time"];
								$fix_am_end = $v["break_start_time"];
								$fix_pm_start = $v["break_end_time"];
								$fix_pm_end =$v["end_time"];
							}else{
								$fix_am_start = "08:00:00";
								$fix_am_end = "12:00:00";
								$fix_pm_start = "13:00:00";
								$fix_pm_end = "17:00:00";
							}
							if($DTRCRED["AM_ARRIVAL"] != null && $DTRCRED["AM_DEPARTURE"] == null && $DTRCRED["PM_ARRIVAL"] == null && $DTRCRED["PM_DEPARTURE"] != null){
								$am_start_sched = $DTRCRED["AM_ARRIVAL"];
								$am_end_sched = $DTRCRED["AM_DEPARTURE"];
								$pm_start_sched = $DTRCRED["PM_ARRIVAL"];
								$pm_end_sched = $DTRCRED["PM_DEPARTURE"];
								if($this->repSC($DTRCRED["AM_ARRIVAL"]) < $this->repSC($fix_am_start)) $am_start_sched = $fix_am_start;
								if(!$isDriver){
									if($this->repSC($DTRCRED["PM_DEPARTURE"]) > $this->repSC($fix_pm_end)) $pm_end_sched = $fix_pm_end;
								}
								
								$start = strtotime($am_start_sched);
								$end = strtotime($fix_am_end);
								$getDiff = $this->getDeductions($start, $end);
								$tot_ot_hrs += $getDiff[0];
								$tot_ot_mins += $getDiff[1];
								
								$start = strtotime($fix_pm_start);
								$end = strtotime($pm_end_sched);
								$getDiff = $this->getDeductions($start, $end);
								$tot_ot_hrs += $getDiff[0];
								$tot_ot_mins += $getDiff[1];
							}else{
								//am
								if($DTRCRED["AM_ARRIVAL"] != null && $DTRCRED["AM_DEPARTURE"] != null){
									$am_start_sched = $DTRCRED["AM_ARRIVAL"];
									$am_end_sched = $DTRCRED["AM_DEPARTURE"];
									if($this->repSC($DTRCRED["AM_ARRIVAL"]) < $this->repSC($fix_am_start)) $am_start_sched = $fix_am_start;
									if($this->repSC($DTRCRED["AM_DEPARTURE"]) > $this->repSC($fix_am_end)) $am_end_sched = $fix_am_end;
									$start = strtotime($am_start_sched);
									$end = strtotime($am_end_sched);
									$getDiff = $this->getDeductions($start, $end);
									$tot_ot_hrs += $getDiff[0];
									$tot_ot_mins += $getDiff[1];
								}
								//pm
								if($DTRCRED["PM_ARRIVAL"] != null && $DTRCRED["PM_DEPARTURE"] != null){
									$pm_start_sched = $DTRCRED["PM_ARRIVAL"];
									$pm_end_sched = $DTRCRED["PM_DEPARTURE"];
									if($this->repSC($DTRCRED["PM_ARRIVAL"]) < $this->repSC($fix_pm_start)) $pm_start_sched = $fix_pm_start;
									if(!$isDriver){
										if($this->repSC($DTRCRED["PM_DEPARTURE"]) > $this->repSC($fix_pm_end)) $pm_end_sched = $fix_pm_end;
									}
									$start = strtotime($pm_start_sched);
									$end = strtotime($pm_end_sched);
									$getDiff = $this->getDeductions($start, $end);
									$tot_ot_hrs += $getDiff[0];
									$tot_ot_mins += $getDiff[1];
								}
							}


							if($isHoliday != null) $DTRCRED["ADJUSTMENT_REMARKS"] = "Holiday";
							else  $DTRCRED["ADJUSTMENT_REMARKS"] = "Rest Day";

							//offset
							$ot = $tot_ot_hrs + ($tot_ot_mins/60);
							if($isDriver){
								if($ot > 12){
									$tot_ot_hrs = 12;
									$tot_ot_mins = 0;
								}
								$grand_tot_monetized = (($ot > 12) ? 12 : $ot) / 8 * 1.25;
							}else{
								if($ot > 8){
									$tot_ot_hrs = 8;
									$tot_ot_mins = 0;
								}
								// $tot_off_set += (($ot > 8) ? 8 : $ot) / 8 * 1.5;
							}
						// }
					}
					//tardiness
					$grand_tot_tardiness_hrs = $tot_tardiness_hrs + floor($tot_tardiness_mins/60);
					$grand_tot_tardiness_mins = fmod($tot_tardiness_mins, 60);
					//ut
					$grand_tot_ut_hrs = $tot_ut_hrs + floor($tot_ut_mins/60);
					$grand_tot_ut_mins = fmod($tot_ut_mins, 60);
					//ot
					$grand_tot_ot_hrs = $tot_ot_hrs + floor($tot_ot_mins/60);
					$grand_tot_ot_mins = fmod($tot_ot_mins, 60);

				}
			}
		}else{
			// $DTRCRED["ADJUSTMENT_REMARKS"] = "OB - ".$isOB['remarks'];
			$DTRCRED["ADJUSTMENT_REMARKS"] = str_replace("NWRB-", "", $isOB['control_no']);
		}

		if($isOffset){
			$grand_tot_ut_mins -= $approve_offset_mins;
			if($grand_tot_ut_mins < 0){
				$grand_tot_ut_hrs -= 1;
				$grand_tot_ut_hrs = $grand_tot_ut_hrs < 0 ? 0 : $grand_tot_ut_hrs;
				$grand_tot_ut_mins = 60 - abs($grand_tot_ut_mins);
			}
			$grand_tot_ut_hrs -= $approve_offset_hrs;
			if($grand_tot_ut_hrs < 0){
				$grand_tot_ut_hrs = 0;
			}
			$DTRCRED["ADJUSTMENT_REMARKS"] = "OFFSET";
		}
		if($DTRCRED["ADJUSTMENT_REMARKS"] == "SPECIAL ORDER"){
			$DTRCRED["ADJUSTMENT_REMARKS"] = $DTRCRED["ADJUSTMENT_REMARKS"].' - '.$_POST['remarks_specific'];
			$grand_tot_tardiness_hrs = 0;
			$grand_tot_tardiness_mins = 0;
			$grand_tot_ut_hrs = 0;
			$grand_tot_ut_mins = 0;
		}

		$DTRCRED["OFFSET"] = round($tot_off_set, 3);
		$DTRCRED["TARDINESS_HRS"] = $grand_tot_tardiness_hrs;
		$DTRCRED["TARDINESS_MINS"] = $grand_tot_tardiness_mins;
		$DTRCRED["UT_HRS"] = $grand_tot_ut_hrs;
		$DTRCRED["UT_MINS"] = $grand_tot_ut_mins;
		$DTRCRED["OT_HRS"] = $grand_tot_ot_hrs;
		$DTRCRED["OT_MINS"] = $grand_tot_ot_mins;
		$DTRCRED["MONETIZED"] = $grand_tot_monetized;

		return $DTRCRED;
	}
}

?>