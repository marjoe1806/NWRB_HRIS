<?php

class ImportDTRFromDevice extends MX_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('ImportDTRFromDeviceCollection');
		$this->load->model('ImportFromExcelCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}

	public $paramsvalues = null;
	public $paramsid = null;
	public $paramsdate = null;

	public function index() {
		$page = "addImportDTRFromDevicePayrollSetup";
		$listData['key'] = $page;
		$viewData['regular_form'] = $this->load->view("forms/importdtrfromdeviceform",$listData,TRUE);
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Import From Portable Device');
			Helper::setMenu('templates/menu_template');
			Helper::setView('importdtrfromdevice',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		Session::checksession();
	}

	public function addImportDTRFromDevicePayrollSetup(){
		$result = array();
		$page = 'addImportDTRFromDevicePayrollSetup';
		$ret =  new ImportDTRFromDeviceCollection();

		if(!$this->input->is_ajax_request()) show_404();
		else {

			$params = $_FILES['file'];
			$timestamp = new DateTime();
			$securityKey = "TheBestSecretKey";
			$security = new Security();

			// create folder if it doesn't exist
			if (!file_exists('./assets/uploads/dailytimerecords'))
			mkdir('./assets/uploads/dailytimerecords', 0777, true);
			$mobile_flag 	= false;
			$file_details = array();
			$file_details = explode(".", $params['name'][0]);

			// decrypt if extracted from mobile P.O.S. device
			if(substr($params['name'][0], 0, 3) == "mob") {
				$encrypted_params = file_get_contents($params['tmp_name'][0]);
				$decrypted_params = $security->decrypt($encrypted_params, $securityKey);
				$mobile_flag = TRUE;
			}
			$source  = "standalone";
			if($mobile_flag == TRUE)$source = "mobile";
			$fileDetails = array("source_device" => $source,"file_name" => $params['name'][0],"file_size" => $params['size'][0],"file_type" => $params['type'][0]);
			$insertFileDetails = $ret->uploadFileDetails($fileDetails);

			$file = fopen("./assets/uploads/dailytimerecords/dtr". $timestamp->format('Ymd') . $timestamp->format('His') .".dat", "w");
			$file_contents = isset($decrypted_params) ? $decrypted_params : file_get_contents($params['tmp_name'][0]);
			$trimmed = preg_replace( '/\h+/', ' ', $file_contents);
			$trimmed = implode("\n", array_map('trim', explode("\n", $trimmed)));
			$trimmed = preg_replace('/^/m', ' ', $trimmed);
			$working_date = explode("-", array_slice(explode(" ", strtok($trimmed, "\n")), 2)[0]);
			fwrite($file, $trimmed);
			fclose($file);

			$dtrfile = file_get_contents("./assets/uploads/dailytimerecords/dtr". $timestamp->format('Ymd') . $timestamp->format('His') .".dat", "w");
			$csv_file = "./assets/uploads/dailytimerecords/dtr". $timestamp->format('Ymd') . $timestamp->format('His') .".dat";
			$trans = array();

			if (($handle = fopen($csv_file, "r")) !== FALSE) {
				$params = null;
				while (($data = fgetcsv($handle)) !== FALSE) {
					$data = preg_split('/\s+/', $data[0]);
					if(!array_key_exists($data[2], $trans)) $trans[$data[2]] = array();
					if(!array_key_exists($data[1], $trans[$data[2]])) $trans[$data[2]][$data[1]] = array();
					$trans[$data[2]][$data[1]][$data[5]] = $data[3];
				}
				fclose($handle);
			}
			$cnt_valid = 0;
			foreach($trans as $dk => $dv){
				if($this->paramsdate == null) $this->paramsdate = "DATE('" . $dk . "')";
				else $this->paramsdate = $this->paramsdate.", DATE('" . $dk . "')";
				foreach($dv as $ek => $ev){
					$DATE = isset($dk) ? $dk : null;
					$DATE = date_create($DATE);
					$DATE = date_format($DATE,"Y-m-d");
					$params = array(
						"SCANNING_NUMBER" => isset($ek) ? $ek : null,
						"TRANSACTION_DATE" => $DATE,
						"AM_ARRIVAL" => isset($ev[0]) ? $ev[0] : null,
						"AM_DEPARTURE" => isset($ev[2]) ? $ev[2] : null,
						"PM_ARRIVAL" => isset($ev[3]) ? $ev[3] : null,
						"PM_DEPARTURE" => isset($ev[1]) ? $ev[1] : null,
						"ACTUAL_AM_ARRIVAL" => isset($ev[0]) ? $ev[0] : null,
						"ACTUAL_AM_DEPARTURE" => isset($ev[2]) ? $ev[2] : null,
						"ACTUAL_PM_ARRIVAL" => isset($ev[3]) ? $ev[3] : null,
						"ACTUAL_PM_DEPARTURE" => isset($ev[1]) ? $ev[1] : null,
						"OT_ARRIVAL" => isset($ev[4]) ? $ev[4] : null,
						"OT_DEPARTURE" => isset($ev[5]) ? $ev[5] : null,
						"REMARKS" => "",
					);
					$insert_daily_dtr = $this->dailyDTR($params);
					if($insert_daily_dtr) {
						$cnt_valid++;
						if($this->paramsid == null) $this->paramsid = "'" . $insert_daily_dtr["SCANNING_NUMBER"] . "'";
						else $this->paramsid = $this->paramsid.", '" . $insert_daily_dtr["SCANNING_NUMBER"] . "'";
					}
				}
			}
			if($insert_daily_dtr > 0){
				if($ret->import_employees_dtr($this->paramsvalues,$this->paramsid,$this->paramsdate)) $res = new ModelResponse($ret->getCode(), $ret->getMessage());
				else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}else $res = new ModelResponse("1", "No available data to be import.");
			$result = json_decode($res,true);
			
			if($ret->importRawDTR($csv_file)) $res = new ModelResponse($ret->getCode(), $ret->getMessage());
			else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
			$result = json_decode($res,true);
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	function dailyDTR($DTRCRED){
		$ret = new ImportFromExcelCollection();
		$arrShiftSchedule = $arrShiftHistory = array();
		$shiftId = 0;
		$shiftType = 0;
		$shiftDetails = $ret->getShiftDetails($DTRCRED["SCANNING_NUMBER"]);
		if($shiftDetails == null) return false;
		$DTRCRED["SCANNING_NUMBER"] = $shiftDetails["scan_no"];
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
								if ($isHoliday != NULL && $isHoliday["is_active"] == 1 && $isHoliday["holiday_type"] == "Suspension") $DTRCRED["REMARKS"] .= " Work Suspension";
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
									if ($flagDetails["flagdateceremony"] != NULL && $flagDetails["is_active"] == 1) $DTRCRED["REMARKS"] = "FLAG CEREMONY";
									if ($isHoliday != NULL && $isHoliday["is_active"] == 1) $DTRCRED["REMARKS"] .= " Work Suspension";
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
									$DTRCRED["REMARKS"] = "See HR";
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
									$DTRCRED["REMARKS"] = "See HR";
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
							// 	$DTRCRED["REMARKS"] = "Incomplete";
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
								
								if($isOTCertification || $DTRCRED["REMARKS"] == "OT W/ CERTIFICATION") $grand_tot_monetized = $ot / 8 * 1.25;
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
								$DTRCRED["REMARKS"] = "Absent";
							}
						}
						else{
							$tot_ut_hrs = 8;
							$DTRCRED["REMARKS"] = "Absent";

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


							if($isHoliday != null) $DTRCRED["REMARKS"] = "Holiday";
							else  $DTRCRED["REMARKS"] = "Rest Day";

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
			// $DTRCRED["REMARKS"] = "OB - ".$isOB['remarks'];
			$DTRCRED["REMARKS"] = str_replace("NWRB-", "", $isOB['control_no']);
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
			$DTRCRED["REMARKS"] = "OFFSET";
		}

		$DTRCRED["OFFSET"] = round($tot_off_set, 3);
		$DTRCRED["TARDINESS_HRS"] = $grand_tot_tardiness_hrs;
		$DTRCRED["TARDINESS_MINS"] = $grand_tot_tardiness_mins;
		$DTRCRED["UT_HRS"] = $grand_tot_ut_hrs;
		$DTRCRED["UT_MINS"] = $grand_tot_ut_mins;
		$DTRCRED["OT_HRS"] = $grand_tot_ot_hrs;
		$DTRCRED["OT_MINS"] = $grand_tot_ot_mins;
		$DTRCRED["MONETIZED"] = $grand_tot_monetized;

		if($this->paramsvalues == null) $this->paramsvalues = "('" . $DTRCRED["SCANNING_NUMBER"] . "','" . $DTRCRED["TRANSACTION_DATE"] . "','" . $DTRCRED["ACTUAL_AM_ARRIVAL"] . "','" . $DTRCRED["ACTUAL_AM_DEPARTURE"] . "','" . $DTRCRED["ACTUAL_PM_ARRIVAL"] . "','" . $DTRCRED["ACTUAL_PM_DEPARTURE"] . "','" . $DTRCRED["OT_ARRIVAL"] . "','" . $DTRCRED["OT_DEPARTURE"] . "','" . $DTRCRED["OFFSET"] . "','" . $DTRCRED["APPROVE_OFFSET_HRS"] . "','" . $DTRCRED["APPROVE_OFFSET_MINS"] . "','" . $DTRCRED["OT_HRS"] . "','" . $DTRCRED["OT_MINS"] . "','" . $DTRCRED["MONETIZED"] . "','" . $DTRCRED["TARDINESS_HRS"] . "','" . $DTRCRED["TARDINESS_MINS"]. "','" . $DTRCRED["UT_HRS"] . "','" . $DTRCRED["UT_MINS"] . "','" . $DTRCRED["REMARKS"] . "', 'excel_dtr')";
		else $this->paramsvalues = $this->paramsvalues.", ('" . $DTRCRED["SCANNING_NUMBER"] . "','" . $DTRCRED["TRANSACTION_DATE"] . "','" . $DTRCRED["ACTUAL_AM_ARRIVAL"] . "','" . $DTRCRED["ACTUAL_AM_DEPARTURE"] . "','" . $DTRCRED["ACTUAL_PM_ARRIVAL"] . "','" . $DTRCRED["ACTUAL_PM_DEPARTURE"] . "','" . $DTRCRED["OT_ARRIVAL"] . "','" . $DTRCRED["OT_DEPARTURE"] . "','" . $DTRCRED["OFFSET"] . "','" . $DTRCRED["APPROVE_OFFSET_HRS"] . "','" . $DTRCRED["APPROVE_OFFSET_MINS"] . "','" . $DTRCRED["OT_HRS"] . "','" . $DTRCRED["OT_MINS"] . "','" . $DTRCRED["MONETIZED"] . "','" . $DTRCRED["TARDINESS_HRS"] . "','" . $DTRCRED["TARDINESS_MINS"]. "','" . $DTRCRED["UT_HRS"] . "','" . $DTRCRED["UT_MINS"] . "','" . $DTRCRED["REMARKS"] . "', 'excel_dtr')";

		// if($ret->insert_daily_dtr($DTRCRED)) {
		// 	$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		// 	return true;
		// }else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		// return false;
		return $DTRCRED;
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

	function array_push_assoc($array, $key, $value){
		$array[$key] = $value;
		return $array;
	}

}

?>