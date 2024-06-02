<?php

class ImportFromExcel extends MX_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('ImportFromExcelCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}

	public function index() {
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Import From Excel');
			Helper::setMenu('templates/menu_template');
			Helper::setView('importfromexcel','',FALSE);
			Helper::setTemplate('templates/master_template');
		}
		Session::checksession();
	}

	public function viewImportFromExcelForm() {
		$result = array();
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$result['form'] = $this->load->view('forms/importfromexcelform.php', '', TRUE);
		}
		echo json_encode($result);
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

	public function importexcel(){
		$result = array();
		$data = $this->input->post();

		$DAY = isset($data['DAY']) ? $data['DAY'] : null;
		$SCANNING_NUMBER = isset($data['SCANNING_NUMBER']) ? $data['SCANNING_NUMBER'] : null;
		$DATE = isset($data['DATE']) ? $data['DATE'] : null;
		$DATE = date_create($DATE);
		$DATE = date_format($DATE,"Y-m-d");
		$SOURCE_DEVICE = isset($data['source_device']) ? $data['source_device'] : null;;
		$DTRCRED = array(
			"SCANNING_NUMBER" => (int)$SCANNING_NUMBER,
			"TRANSACTION_DATE" => $DATE,
			"AM_ARRIVAL" => isset($data['AM_ARRIVAL']) ? ((preg_match('~[0-9]+~', $data['AM_ARRIVAL'])) ? $data['AM_ARRIVAL'] : null) : null,
			"AM_DEPARTURE" => isset($data['AM_DEPARTURE']) ? ((preg_match('~[0-9]+~', $data['AM_DEPARTURE'])) ? $data['AM_DEPARTURE'] : null) : null,
			"PM_ARRIVAL" => isset($data['PM_ARRIVAL']) ? ((preg_match('~[0-9]+~', $data['PM_ARRIVAL'])) ? $data['PM_ARRIVAL'] : null) : null,
			"PM_DEPARTURE" => isset($data['PM_DEPARTURE']) ? ((preg_match('~[0-9]+~', $data['PM_DEPARTURE'])) ? $data['PM_DEPARTURE'] : null) : null,
			"ACTUAL_AM_ARRIVAL" => isset($data['AM_ARRIVAL']) ? ((preg_match('~[0-9]+~', $data['AM_ARRIVAL'])) ? $data['AM_ARRIVAL'] : null) : null,
			"ACTUAL_AM_DEPARTURE" => isset($data['AM_DEPARTURE']) ? ((preg_match('~[0-9]+~', $data['AM_DEPARTURE'])) ? $data['AM_DEPARTURE'] : null) : null,
			"ACTUAL_PM_ARRIVAL" => isset($data['PM_ARRIVAL']) ? ((preg_match('~[0-9]+~', $data['PM_ARRIVAL'])) ? $data['PM_ARRIVAL'] : null) : null,
			"ACTUAL_PM_DEPARTURE" => isset($data['PM_DEPARTURE']) ? ((preg_match('~[0-9]+~', $data['PM_DEPARTURE'])) ? $data['PM_DEPARTURE'] : null) : null,
			"OT_ARRIVAL" => isset($data['OT_ARRIVAL']) ? ((preg_match('~[0-9]+~', $data['OT_ARRIVAL'])) ? $data['OT_ARRIVAL'] : null) : null,
			"OT_DEPARTURE" => isset($data['OT_DEPARTURE']) ? ((preg_match('~[0-9]+~', $data['OT_DEPARTURE'])) ? $data['OT_DEPARTURE'] : null) : null,
			"OT_TOTAL" => isset($data['OTtotal']) ? $data['OTtotal'] : 0,
			"REMARKS" => isset($data['REMARKS']) ? $data['REMARKS'] : "",
			"ADJUSTMENT_REMARKS" => ""
		);

		$REMARKS = "";

		if($DTRCRED['AM_ARRIVAL'] != null || $DTRCRED['AM_DEPARTURE'] != null || $DTRCRED['PM_ARRIVAL'] != null || $DTRCRED['PM_DEPARTURE'] != null || $DTRCRED['OT_ARRIVAL'] != null || $DTRCRED['OT_DEPARTURE'] != null) {
			if($DTRCRED['AM_ARRIVAL'] != NULL) {
				$TRANSACTION_TIME = $DTRCRED['AM_ARRIVAL'];
				$TRANSACTION_TYPE = 0;
				$this->addRows($SCANNING_NUMBER, $DATE, $TRANSACTION_TIME, $TRANSACTION_TYPE, $SOURCE_DEVICE, $DTRCRED['OT_TOTAL'], $REMARKS);
			}

			if($DTRCRED['AM_DEPARTURE'] != NULL) {
				$TRANSACTION_TIME = $DTRCRED['AM_DEPARTURE'];
				$TRANSACTION_TYPE = 2;

				$this->addRows($SCANNING_NUMBER, $DATE, $TRANSACTION_TIME, $TRANSACTION_TYPE, $SOURCE_DEVICE, $DTRCRED['OT_TOTAL'], $REMARKS);
			}

			if($DTRCRED['PM_ARRIVAL'] != NULL) {
				$TRANSACTION_TIME = $DTRCRED['PM_ARRIVAL'];
				$TRANSACTION_TYPE = 3;
				
				$this->addRows($SCANNING_NUMBER, $DATE, $TRANSACTION_TIME, $TRANSACTION_TYPE, $SOURCE_DEVICE, $DTRCRED['OT_TOTAL'], $REMARKS);
					
			}

			if($DTRCRED['PM_DEPARTURE'] != NULL) {
				$TRANSACTION_TIME = $DTRCRED['PM_DEPARTURE'];
				$TRANSACTION_TYPE = 1;
				$this->addRows($SCANNING_NUMBER, $DATE, $TRANSACTION_TIME, $TRANSACTION_TYPE, $SOURCE_DEVICE, $DTRCRED['OT_TOTAL'], $REMARKS);

			}

			if($DTRCRED['OT_ARRIVAL'] != NULL) {
				$TRANSACTION_TIME = $DTRCRED['OT_ARRIVAL'];
				$TRANSACTION_TYPE = 4;
				$this->addRows($SCANNING_NUMBER, $DATE, $TRANSACTION_TIME, $TRANSACTION_TYPE, $SOURCE_DEVICE, $DTRCRED['OT_TOTAL'], $REMARKS);

			}

			if($DTRCRED['OT_DEPARTURE'] != NULL) {
				$TRANSACTION_TIME = $DTRCRED['OT_DEPARTURE'];
				$TRANSACTION_TYPE = 5;
				$this->addRows($SCANNING_NUMBER, $DATE, $TRANSACTION_TIME, $TRANSACTION_TYPE, $SOURCE_DEVICE, $DTRCRED['OT_TOTAL'], $REMARKS);

			}

			$result['Logs'] = date("Y-m-d h:i:s").": ".date_format(date_create($DATE),"F d, Y")." (".$SCANNING_NUMBER.") is inserted to the database.";
		} else {
			$result['Logs'] = date("Y-m-d h:i:s").": ".date_format(date_create($DATE),"F d, Y")." (".$SCANNING_NUMBER.") is not inserted to the database.";
		}
		$ret = new ImportFromExcelCollection();
		$arrShiftSchedule = $arrShiftHistory = array();
		$shiftId = 0;
		$shiftType = 0;
		$shiftDetails = $ret->getShiftDetails($SCANNING_NUMBER);
		$flagDetails = $ret->getFlagCeremony($DATE);
		$isHoliday = $ret->getHoliday($DATE);
		$isOB = $ret->getOB($SCANNING_NUMBER,$DATE);
		$isOffset = $ret->getOffset($SCANNING_NUMBER,$DATE);
		$approve_offset_hrs = $approve_offset_mins = 0;
		if($isOffset){
			$approve_offset_hrs = $isOffset["offset_hrs"];
			$approve_offset_mins = $isOffset["offset_mins"];
		}
		$DTRCRED["APPROVE_OFFSET_HRS"] = $approve_offset_hrs;
		$DTRCRED["APPROVE_OFFSET_MINS"] = $approve_offset_mins;
		// $isBreak = $ret->getPositionBreak($SCANNING_NUMBER);
		// $isBreak = ($isBreak != null && $isBreak["is_break"] == 1) ? 1 : 0;
		$isDriver = $ret->getIfDriver($SCANNING_NUMBER);
		$isOTCertification = $ret->getCheckifOTCertification($SCANNING_NUMBER,$DATE);
		// shift date effectivity
		if(strtotime($DATE) >= strtotime($shiftDetails["shift_date_effectivity"])) {
			$shiftId = ($shiftDetails["regular_shift"] == 1) ? $shiftDetails["shift_id"] : $shiftDetails["flex_shift_id"];
			$shiftType = $shiftDetails["regular_shift"];
		}else{
			$arrShiftHistory = $ret->getShiftHistory($shiftDetails["id"]);
			foreach($arrShiftHistory as $k => $v){
				if(strtotime($DATE) >= strtotime($v["previous_date_effectivity"])){
					$shiftId = $v["shift_id"];
					$shiftType = $v["shift_type"];
					break;
				}
			}
		}
		// get weekly schedule
		if($shiftType == 1) $arrShiftSchedule = $ret->getRegularShiftSchedule($shiftId);
		else $arrShiftSchedule = $ret->getFlexibleShiftSchedule($shiftId);
		$weekday = date("l",strtotime($DATE)); // current day
		//tardiness
		$am_start_sched = $pm_start_sched = null;
		//ut
		$am_end_sched = $pm_end_sched = null;
		// total
		$tot_off_set = $tot_ot_hrs = $tot_ot_mins = 0;
		$tot_tardiness_hrs = $tot_tardiness_mins = $tot_ut_hrs = $tot_ut_mins = 0;
		$grand_tot_tardiness_hrs = $grand_tot_tardiness_mins = $grand_tot_ut_hrs = $grand_tot_ut_mins = $grand_tot_ot_hrs = $grand_tot_ot_mins = $grand_tot_monetized = 0;
		$late_addtl_hrs = 0;

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
						if($DTRCRED["AM_ARRIVAL"] != null || $DTRCRED["AM_DEPARTURE"] != null || $DTRCRED["PM_ARRIVAL"] != null || $DTRCRED["PM_DEPARTURE"] != null || ($isHoliday["holiday_type"] == "Suspension")){ // check if no dtr
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
									$isHoliday["holiday_type"] != "Suspension" &&
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
					$grand_tot_ut_mins = fmod($tot_ut_mins, 60);;
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

		$DTRCRED["OFFSET"] = round($tot_off_set, 3);
		$DTRCRED["TARDINESS_HRS"] = $grand_tot_tardiness_hrs;
		$DTRCRED["TARDINESS_MINS"] = $grand_tot_tardiness_mins;
		$DTRCRED["UT_HRS"] = $grand_tot_ut_hrs;
		$DTRCRED["UT_MINS"] = $grand_tot_ut_mins;
		$DTRCRED["OT_HRS"] = $grand_tot_ot_hrs;
		$DTRCRED["OT_MINS"] = $grand_tot_ot_mins;
		$DTRCRED["MONETIZED"] = $grand_tot_monetized;
		if($ret->insert_daily_dtr($DTRCRED)) $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		
		if($DATE == null)
			$result['Logs'] = date("Y-m-d h:i:s")." : The file does not follow the provided format for import.";

		echo json_encode($result);
	}

	public function addRows($SCANNING_NUMBER, $DATE, $TRANSACTION_TIME, $TRANSACTION_TYPE, $SOURCE_DEVICE, $OT_TOTAL, $REMARKS){
		$params = array (
			'employee_number' => (int)$SCANNING_NUMBER,
			'transaction_date' => $DATE,
			'transaction_time' => $TRANSACTION_TIME,
			'col4' => 0,
			'transaction_type' => $TRANSACTION_TYPE,
			'col6' => 0,
			'col7' => 0,
			'source_location' => 'excel_import',
			'source_device' => $SOURCE_DEVICE,
			'latitude' => '',
			'longitude' => '',
			'remarks' => $REMARKS,
			'num_hrs' => $OT_TOTAL,
			'modified_by' => Helper::get('userid')
		);
		
		$ret =  new ImportFromExcelCollection();
		if($ret->addRows($params)) {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		} else {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		}
		$result = json_decode($res,true);
	}
}

?>