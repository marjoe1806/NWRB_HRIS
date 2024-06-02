<?php
	class DailyTimeRecordSummaryCollection extends Helper {
    var $select_column = null;
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
			$columns = $this->getColumns();
			foreach ($columns as $key => $value) {
				$this->select_column[] = $this->table.'.'.$value['COLUMN_NAME'];
			}
		}
		//Fetch
		var $table = "tblemployees";
		var $order_column = array('first_name','last_name','address');

    public function getColumns(){
			$sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$this->table."' AND TABLE_SCHEMA='".$this->db->database."' ";
			$query = $this->db->query($sql);
			$rows = $query->result_array();
			return $rows;
		}

		function make_query_summary($employee_id, $employee_number, $payroll_period, $payroll_period_id, $shift_id){

			$dtr = array();
			$payroll_period = $this->getPayrollPeriodById($payroll_period_id);
			
			// Get global settings
			$configurations = @$this->checkGlobalSettings();

			// Get all working days for this month
			$working_days = $this->getWorkingDays(@$payroll_period[0]['start_date'],@$payroll_period[0]['end_date'],array());
			// var_dump($working_days);die();
			foreach($working_days as $index => $working_day){
				// $working_day = '2019-06-17';
				// Set defaults
				$attendance = array(
					"transaction_date" 									=> null,
					"time_in" 													=> null,
					"break_out" 												=> null,
					"break_in" 													=> null,
					"time_out" 													=> null,
					"official_time_in" 									=> null,
					"official_time_out" 								=> null,
					"late_hours" 												=> null,
					"undertime_hours" 									=> null,
					"regular_hours" 										=> null,
					"leave_hours" 											=> null,
					"regular_nightdiff_hours" 					=> null,
					"absent_hours" 											=> null,
					"break_deduction" 									=> null,
					"present_day" 											=> null,
					"regular_overtime" 									=> null,
					"nightdiff_overtime" 								=> null,
					"restday_overtime" 									=> null,
					"legal_holiday_overtime" 						=> null,
					"legal_holiday_restday_overtime" 		=> null,
					"special_holiday_overtime" 					=> null,
					"special_holiday_restday_overtime"	=> null,
					"regular_excess_overtime" 					=> null,
					"restday_excess_overtime" 					=> null,
					"legal_excess_overtime" 						=> null,
					"legal_excess_restday_overtime" 		=> null,
					"special_excess_overtime" 					=> null,
					"special_excess_restday_overtime" 	=> null
				);

				// Get employee shift schedule then set schedule
				$schedule = $this->getEmployeeSchedule($shift_id, $working_day);

				// Check if today is holiday or if employee has an approved leave as well as overtime for this day
				$holiday_details 		= @$this->checkIfHoliday($working_day);
				$leave_details 			= @$this->checkForApprovedLeave($working_day, $employee_id);
				$overtime_approval 	= @$this->getApprovedOvertimes($working_day);

				// Get grace period and night diff hours
				if($configurations != null && sizeof($configurations) > 0) {
					$night_diff_start 	= date("Y-m-d H:i:s", strtotime($configurations['night_differential_hours_between_from'] . ' ' . $working_day));
					$night_diff_end 		= date("Y-m-d H:i:s", strtotime($configurations['night_differential_hours_between_to'] . (($configurations['night_differential_hours_between_to'] < $configurations['night_differential_hours_between_from'] ) ? ' ' . date('Y-m-d', strtotime($working_day . '+1 day')) : ' ' . $working_day)));
				}

				// Fetch data from imported daily time records
				$current_day = $this->getAdjustmentsByWorkingDays($working_day, $employee_number);
				if(sizeof($current_day) == 0){
					$current_day = $this->getAttendanceByWorkingDays($working_day, $employee_number);
				}
				
				$attendance['time_in'] 					= isset($current_day['time_in']) ? date("Y-m-d H:i:00", strtotime($current_day['time_in'])) : null;
				$attendance['time_out'] 				= isset($current_day['time_out']) ? date("Y-m-d H:i:00", strtotime($current_day['time_out'])) : null;
				$attendance['break_out'] 				= isset($current_day['break_out']) ? date("Y-m-d H:i:00", strtotime($current_day['break_out'])) : null;
				$attendance['break_in'] 				= isset($current_day['break_in']) ? date("Y-m-d H:i:00", strtotime($current_day['break_in'])) : null;
				$attendance['transaction_date']	= @$working_day;

				// Attendance summary for employes with regular schedule
				// Important: seconds are not counted as per client request
				if(sizeof($schedule) > 0) {

					// Set official employee schedule
					$attendance['official_time_in'] = date("Y-m-d H:i:00", strtotime($working_day . ' ' . $schedule['start_time']));
					$attendance['official_time_out'] = date("Y-m-d H:i:00", strtotime(($schedule['end_time'] < $schedule['start_time'] ? date('Y-m-d', strtotime($working_day . '+1 day')) : $working_day)  . ' ' . $schedule['end_time']));

					// Set grace period and allowable time in
					$allowable_time_in = date("Y-m-d H:i:00", strtotime($attendance['official_time_in'] . "+" . round($configurations['grace_period'], 0) . " minutes"));
					$grace_period = $this->getTimeDifference($attendance['official_time_in'], $allowable_time_in);

					// Get grace period and night diff hours
					if(sizeof($configurations) > 0) {
						$night_shift_start = date("Y-m-d H:i:s", strtotime($configurations['night_differential_hours_between_from'] . ' ' . $working_day));
						$night_shift_end = date("Y-m-d H:i:s", strtotime(($configurations['night_differential_hours_between_to'] < $configurations['night_differential_hours_between_from'] ) ? $configurations['night_differential_hours_between_to']  . ' ' . date('Y-m-d', strtotime($working_day . '+1 day')) : $configurations['night_differential_hours_between_to'] . ' ' . $working_day));
					}

					// Check if employee has time logs for this day
					if($attendance['time_in'] != null && $attendance['time_out'] != null) {

						// Get adjusted start time and required end time when late
						if($attendance['time_in'] >= $attendance['official_time_in']) {

							// Get late in decimal hours then set required end time
							// Important: Employee must compensate for the time he was late in order to get 8 hours
							$attendance['late_hours'] = $this->getTimeDifference($attendance['official_time_in'], $attendance['time_in']);
							$required_end_time = date(
								"Y-m-d H:i:00", strtotime(
									$attendance['official_time_out'] . "+" . $this->ct($attendance['late_hours'], 'h') ." hours " . "+" . $this->ct($attendance['late_hours'], 'i') . " minutes"
								)
							);
						}

						// Set start and end time (adjusted if employee is late)
						$start_time = date("Y-m-d H:i:00", strtotime($attendance['time_in'] < $attendance['official_time_in'] ? $attendance['official_time_in'] : $attendance['time_in']));
						$end_time 	= date("Y-m-d H:i:00", strtotime($attendance['time_out']));
						$late_compensation = isset($attendance['late_hours']) ? $this->getTimeDifference($end_time, $required_end_time) : 0;

						// Set total hours
						$total_hours = $this->getTimeDifference($start_time, $end_time);
						// var_dump($total_hours);die();
						// Set late hours (total amount of late minus grace period)
						// $attendance['late_hours'] = $attendance['late_hours'] > $grace_period ? $attendance['late_hours'] - $grace_period : 0;
						$attendance['late_hours'] = $attendance['time_in'] > $attendance['official_time_in'] ? $this->getTimeDifference($attendance['official_time_in'], $attendance['time_in']) : 0;

						// Set undertime hours (total amount of late minus grace period)
						// $attendance['undertime_hours'] = ($end_time < $attendance['official_time_out'] ? $this->getTimeDifference($end_time, $attendance['official_time_out']) : 0) - (isset($required_end_time) && $end_time < $required_end_time ? $this->getTimeDifference($required_end_time, $end_time) : 0);
						$attendance['undertime_hours'] = $attendance['late_hours'] + ($end_time < $attendance['official_time_out'] ? $this->getTimeDifference($end_time, $attendance['official_time_out']) : 0);
						// $attendance['undertime_hours'] = $attendance['undertime_hours'] <= 4 ? $attendance['undertime_hours'] : 0;

						// Set tardiness
						// if($end_time < $attendance['official_time_out']) {
						// 	$tardiness = $attendance['late_hours'];
						// } elseif($end_time >= $attendance['official_time_out']) {
						// 	$tardiness = $attendance['late_hours'] + $attendance['undertime_hours'];
						// }

						// Fetch break hours (Breaks must be at least 5 minutes or 1 hour will be deducted)
						// 0.08 decimal hours is equivalent to 5 minutes, no break if halfday
						if($attendance['break_out'] != null && $attendance['break_in'] != null) {
							$break_hours = $this->getTimeDifference($attendance['break_out'], $attendance['break_in']);
							$break_hours = $total_hours > 5 ? ($break_hours > 0.08 ? $break_hours : 1) : 0;
							$attendance['break_deduction'] = $break_hours > 1 ? ($break_hours - 1) : 0;
						} else {
							// Set break hours to 1 hour if employee did not break out/in
							// Set to zero if employee attendance is half day
							$break_hours =  $total_hours >= 5 ? 1 : 0;
						}

						// Set regular hours
						$attendance['regular_hours'] = number_format((float)abs($total_hours), 2, '.', '');
						// $attendance['regular_hours'] = ($tardiness != 0 ? ($attendance['regular_hours'] >= 9 ? 9 : $attendance['regular_hours']) : $attendance['regular_hours'] > 9 ? 9 : $attendance['regular_hours']) - ($tardiness + $break_hours);
						$attendance['regular_hours'] = $attendance['regular_hours'] > 8 ? 8 - $attendance['undertime_hours'] : $attendance['regular_hours'];

						// Set whole or halfday attendance flag
						if($total_hours >= 4 && $total_hours < 6) {
							$break_hours = 0;
							$attendance['regular_hours'] = $attendance['regular_hours'] <= 4 ? $attendance['regular_hours'] : 4;
							$attendance['present_day'] = .5;
							$attendance['absent_hours'] = 4;
							$attendance['late_hours'] = 0;
							$attendance['undertime_hours'] = 0;
						} elseif($total_hours >= 6) {
							$attendance['present_day'] = 1;
							$attendance['absent_hours'] = 0;
						} else {
							$attendance['present_day'] = 0;
							$attendance['absent_hours'] = 8;
						}

						// Pass attendance values to dtr array
						foreach ($attendance as $key => $value) {
							$dtr[$index][$key] = isset($attendance[$key]) && $attendance[$key] != null ? $attendance[$key] : null;
						}
						// var_dump($attendance['present_day']);die();
					} else {

						// Set official time
						$attendance['official_time_in'] = date("Y-m-d H:i:00", strtotime($working_day . ' ' . $schedule['start_time']));
						$attendance['official_time_out'] = date("Y-m-d H:i:00", strtotime(($schedule['end_time'] < $schedule['start_time'] ? date('Y-m-d', strtotime($working_day . '+1 day')) : $working_day)  . ' ' . $schedule['end_time']));

						// Check if today is restday
						if($schedule['is_restday'] == true) {
							$attendance['present_day'] = 0;
							$attendance['absent_hours'] = 0;
							$attendance['official_time_in'] = null; $attendance['official_time_out'] = null;
						}
						// Check if employee has leave today
						elseif(sizeof($leave_details) > 0) {
							$attendance['leave_hours'] = 8;
							$attendance['present_day'] = 0;
							$attendance['absent_hours'] = 0;
							$attendance['official_time_in'] = null; $attendance['official_time_out'] = null;
						}
						// Check if today is a holiday
						elseif(sizeof($holiday_details) > 0) {
							$holiday_type = $holiday_details[0]['holiday_type'];
							$attendance['legal_holiday_hours'] = $holiday_type == "Legal" ? 8 : 0;
							$attendance['special_holiday_hours'] = $holiday_type == "Special" ? 8 : 0;
							$attendance['present_day'] = 0;
							$attendance['absent_hours'] = 0;
							$attendance['official_time_in'] = null; $attendance['official_time_out'] = null;
						// Statement will fall here if employee was absent
						} else {
							$attendance['present_day'] = 0;
							$attendance['absent_hours'] = 8;
							$attendance['official_time_in'] = null; $attendance['official_time_out'] = null;
						}

						// Pass attendance values to dtr array
						// foreach ($attendance as $key => $value) {
						// 	$dtr[$index][$key] = isset($attendance[$key]) && $attendance[$key] != null ? $attendance[$key] : null;
						// }

						// night diff hours
						$attendance['regular_nightdiff_hours'] = ($attendance['time_out'] >= $night_shift_start) ? $attendance['time_out'] < $night_shift_end ? $this->getTimeDifference($night_shift_start, $attendance['time_out']) : $this->getTimeDifference($night_shift_start, $night_shift_end) : null;

					}

					// Check overtimes
					if(sizeof($overtime_approval) > 0) {
						switch ($overtime_approval['ot_type']) {
							case 'REGOT (OT1)':
								$regular_overtime = sizeof($holiday_details) <= 0  && $overtime_approval['ot_hours'] > 0 ? $overtime_approval['ot_hours'] : 0;
								$legal_holiday_overtime = sizeof($holiday_details) > 0 && $holiday_details[0]['holiday_type'] ==  "Legal" ? $overtime_approval['ot_hours'] : 0;
								$special_holiday_overtime = sizeof($holiday_details) > 0 && $holiday_details[0]['holiday_type'] ==  "Special" ? $overtime_approval['ot_hours'] : 0;
								break;
							case 'WKNDOT(OT2)':
								$restday_overtime = sizeof($holiday_details) <= 0  && $overtime_approval['ot_hours'] > 0 ? $overtime_approval['ot_hours'] : 0;
								$restday_excess_overtime = sizeof($holiday_details) <= 0 && $overtime_approval['ot_hrs_excess'] > 0 ? $overtime_approval['ot_hrs_excess'] : 0;
								$legal_holiday_restday_overtime = sizeof($holiday_details) > 0 && $holiday_details[0]['holiday_type'] ==  "Legal" ? $overtime_approval['ot_hours'] : 0;
								$legal_excess_restday_overtime = sizeof($holiday_details) > 0 && $holiday_details[0]['holiday_type'] ==  "Legal" ? $overtime_approval['ot_hrs_excess'] : 0;
								$special_holiday_restday_overtime = sizeof($holiday_details) > 0 && $holiday_details[0]['holiday_type'] ==  "Special" ? $overtime_approval['ot_hours'] : 0;
								$special_excess_restday_overtime = sizeof($holiday_details) > 0 && $holiday_details[0]['holiday_type'] ==  "Special" ? $overtime_approval['ot_hrs_excess'] : 0;
								break;
							case 'NDIFF (OT3)':
								$nightdiff_overtime = $overtime_approval['ndiff_hrs'] > 0 ? $overtime_approval['ndiff_hrs'] : 0;
								break;
						}
					}
					// var_dump($attendance['present_day']);die();
					// Finalize Data
					$dtr[$index] = array(
						"transaction_date" 									=> @$working_day,
						"time_in" 													=> @$attendance['time_in'],
						"break_out" 												=> @$attendance['break_out'],
						"break_in" 													=> @$attendance['break_in'],
						"time_out" 													=> @$attendance['time_out'],
						"official_time_in" 									=> @$attendance['official_time_in'],
						"official_time_out" 								=> @$attendance['official_time_out'],
						"late_hours" 												=> @$attendance['late_hours'],
						"undertime_hours" 									=> @$attendance['undertime_hours'],
						"regular_hours" 										=> @$attendance['regular_hours'],
						"leave_hours" 											=> @$attendance['leave_hours'],
						"regular_nightdiff_hours" 					=> @$attendance['regular_nightdiff_hours'],
						"absent_hours" 											=> @$attendance['absent_hours'],
						"break_deduction" 									=> @$attendance['break_deduction'],
						"present_day" 											=> @$attendance['present_day'],
						"regular_overtime" 									=> sizeof($overtime_approval) > 0 && isset($regular_overtime) && $regular_overtime != null ? $regular_overtime: "0.00",
						"nightdiff_overtime" 								=> sizeof($overtime_approval) > 0 && isset($nightdiff_overtime) && $nightdiff_overtime != null ? $nightdiff_overtime: "0.00",
						"restday_overtime" 									=> sizeof($overtime_approval) > 0 && isset($restday_overtime) && $restday_overtime != null ? $restday_overtime: "0.00",
						"legal_holiday_overtime" 						=> sizeof($overtime_approval) > 0 && isset($legal_holiday_overtime) && $legal_holiday_overtime != null ? $legal_holiday_overtime: "0.00",
						"legal_holiday_restday_overtime" 		=> sizeof($overtime_approval) > 0 && isset($legal_holiday_restday_overtime) && $legal_holiday_restday_overtime != null ? $legal_holiday_restday_overtime: "0.00",
						"special_holiday_overtime" 					=> sizeof($overtime_approval) > 0 && isset($special_holiday_overtime) && $special_holiday_overtime != null ? $special_holiday_overtime: "0.00",
						"special_holiday_restday_overtime"	=> sizeof($overtime_approval) > 0 && isset($special_holiday_restday_overtime) && $special_holiday_restday_overtime != null ? $special_holiday_restday_overtime: "0.00",
						"regular_excess_overtime" 					=> sizeof($overtime_approval) > 0 && isset($regular_excess_overtime) && $regular_excess_overtime != null ? $regular_excess_overtime: "0.00",
						"restday_excess_overtime" 					=> sizeof($overtime_approval) > 0 && isset($restday_excess_overtime) && $restday_excess_overtime != null ? $restday_excess_overtime: "0.00",
						"legal_excess_overtime" 						=> sizeof($overtime_approval) > 0 && isset($legal_excess_overtime) && $legal_excess_overtime != null ? $legal_excess_overtime: "0.00",
						"legal_excess_restday_overtime" 		=> sizeof($overtime_approval) > 0 && isset($legal_excess_restday_overtime) && $legal_excess_restday_overtime != null ? $legal_excess_restday_overtime: "0.00",
						"special_excess_overtime" 					=> sizeof($overtime_approval) > 0 && isset($special_excess_overtime) && $special_excess_overtime != null ? $special_excess_overtime: "0.00",
						"special_excess_restday_overtime" 	=> sizeof($overtime_approval) > 0 && isset($special_excess_restday_overtime) && $special_excess_restday_overtime != null ? $special_excess_restday_overtime: "0.00"
					);
				}
			}

			// var_dump($dtr); die();
			$current_period[0] = date('Y-m',strtotime($payroll_period[0]['payroll_period'])).'-01';
			$current_period[1] = date("Y-m-t", strtotime($payroll_period[0]['payroll_period']));
			// var_dump($current_period);die();
			$response['data'] = $dtr;
			$response['format'] = $attendance;
			$response['total_working_days'] = $this->calculateWorkingDaysInMonth($current_period[0], $current_period[1]);
			return $response;
		}

			function getEmployeeSchedule($shift_id, $working_day) {
				$week_day = date('l', strtotime($working_day));
				$schedule = array();
				$params = array($shift_id, $week_day);
				$sql = "SELECT * FROM tbltimekeepingemployeeschedules a
								LEFT JOIN tbltimekeepingemployeeschedulesweekly b
								ON a.id = b.shift_code_id WHERE b.shift_code_id = ? AND b.week_day = ? AND b.is_active = 1";
				$query = $this->db->query($sql, $params);
				$schedule = $query->result_array();
				return (sizeof($schedule) > 0) ? $schedule[0] : $schedule;
			}

			function checkGlobalSettings() {
				$settings = array();
				$sql = "SELECT * FROM tblfieldpayrollsetup WHERE is_active = 1  ORDER BY id DESC LIMIT 1";
				$query = $this->db->query($sql);
				$settings = $query->result_array();
				return (sizeof($settings) > 0) ? $settings[0] : $settings;
			}

			function getApprovedOvertimes($params) {
				$overtime = array();
				$sql = "SELECT * FROM tbltransactionsovertimes WHERE is_active = 1  AND ot_date_from = ? ORDER BY id DESC LIMIT 1";
				$query = $this->db->query($sql, $params);
				$overtime = $query->result_array();
				return (sizeof($overtime) > 0) ? $overtime[0] : $overtime;
			}
			function getAdjustmentsByWorkingDays($working_day, $employee_number){
				$attendance = array();
				$params = array($employee_number, $employee_number, $employee_number, date('Y-m-d', strtotime($working_day . '+1 day')), $employee_number, $employee_number, date('Y-m-d', strtotime($working_day . '+1 day')), $employee_number, $employee_number, date('Y-m-d', strtotime($working_day . '+1 day')), $employee_number, $working_day );
				$sql = "SELECT DISTINCT a.transaction_date, a.employee_number,
				(SELECT CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) as date FROM tbltimekeepingdailytimerecordadjustments b
							WHERE b.transaction_type = '0'
							AND b.employee_number = ?
							AND b.is_active = '1'
							AND b.transaction_date = a.transaction_date
							ORDER BY b.transaction_time ASC LIMIT 1) AS time_in,
				IFNULL((SELECT CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) FROM tbltimekeepingdailytimerecordadjustments b
							WHERE b.transaction_type = '1'
							AND b.employee_number = ?
							AND b.is_active = '1'
							AND b.transaction_date = a.transaction_date
							AND CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) > time_in
							ORDER BY b.transaction_time ASC LIMIT 1),
							(SELECT CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) FROM tbltimekeepingdailytimerecordadjustments b
							WHERE b.transaction_type = '1'
							AND b.employee_number = ?
							AND b.is_active = '1'
							AND b.transaction_date = ?
							AND CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) > time_in
							ORDER BY b.transaction_time ASC LIMIT 1)) AS time_out,
				IFNULL((SELECT CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) FROM tbltimekeepingdailytimerecordadjustments b
							WHERE b.transaction_type = '2'
							AND b.employee_number = ?
							AND b.is_active = '1'
							AND b.transaction_date = a.transaction_date
							AND CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) >= time_in
							ORDER BY b.transaction_time ASC LIMIT 1),
							(SELECT CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) FROM tbltimekeepingdailytimerecordadjustments b
							WHERE b.transaction_type = '2'
							AND b.employee_number = ?
							AND b.is_active = '1'
							AND b.transaction_date = ?
							AND CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) >= time_in
							ORDER BY b.transaction_time ASC LIMIT 1)) AS break_out,
				IFNULL((SELECT CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) FROM tbltimekeepingdailytimerecordadjustments b
							WHERE b.transaction_type = '3'
							AND b.employee_number = ?
							AND b.is_active = '1'
							AND b.transaction_date = a.transaction_date
							AND CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) >= time_in
							ORDER BY b.transaction_time ASC LIMIT 1),
							(SELECT CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) FROM tbltimekeepingdailytimerecordadjustments b
							WHERE b.transaction_type = '3'
							AND b.employee_number = ?
							AND b.is_active = '1'
							AND b.transaction_date = ?
							AND CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) >= time_in
							ORDER BY b.transaction_time ASC LIMIT 1)) AS break_in
				FROM tbltimekeepingdailytimerecordadjustments a
				WHERE a.employee_number = ? AND transaction_date = ? ";
				$query = $this->db->query($sql,$params);
				$attendance = $query->result_array();
				return (sizeof($attendance) > 0) ? $attendance[0] : $attendance;
			}
			function getAttendanceByWorkingDays($working_day, $employee_number){
				$attendance = array();
				$params = array($employee_number, $employee_number, $employee_number, date('Y-m-d', strtotime($working_day . '+1 day')), $employee_number, $employee_number, date('Y-m-d', strtotime($working_day . '+1 day')), $employee_number, $employee_number, date('Y-m-d', strtotime($working_day . '+1 day')), $employee_number, $working_day );
				$sql = "SELECT DISTINCT a.transaction_date, a.employee_number,
				(SELECT CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) as date FROM tbltimekeepingdailytimerecord b
							WHERE b.transaction_type = '0'
							AND b.employee_number = ?
							AND b.is_active = '1'
							AND b.transaction_date = a.transaction_date
							ORDER BY b.transaction_time ASC LIMIT 1) AS time_in,
				IFNULL((SELECT CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) FROM tbltimekeepingdailytimerecord b
							WHERE b.transaction_type = '1'
							AND b.employee_number = ?
							AND b.is_active = '1'
							AND b.transaction_date = a.transaction_date
							AND CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) > time_in
							ORDER BY b.transaction_time ASC LIMIT 1),
							(SELECT CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) FROM tbltimekeepingdailytimerecord b
							WHERE b.transaction_type = '1'
							AND b.employee_number = ?
							AND b.is_active = '1'
							AND b.transaction_date = ?
							AND CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) > time_in
							ORDER BY b.transaction_time ASC LIMIT 1)) AS time_out,
				IFNULL((SELECT CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) FROM tbltimekeepingdailytimerecord b
							WHERE b.transaction_type = '2'
							AND b.employee_number = ?
							AND b.is_active = '1'
							AND b.transaction_date = a.transaction_date
							AND CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) >= time_in
							ORDER BY b.transaction_time ASC LIMIT 1),
							(SELECT CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) FROM tbltimekeepingdailytimerecord b
							WHERE b.transaction_type = '2'
							AND b.employee_number = ?
							AND b.is_active = '1'
							AND b.transaction_date = ?
							AND CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) >= time_in
							ORDER BY b.transaction_time ASC LIMIT 1)) AS break_out,
				IFNULL((SELECT CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) FROM tbltimekeepingdailytimerecord b
							WHERE b.transaction_type = '3'
							AND b.employee_number = ?
							AND b.is_active = '1'
							AND b.transaction_date = a.transaction_date
							AND CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) >= time_in
							ORDER BY b.transaction_time ASC LIMIT 1),
							(SELECT CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) FROM tbltimekeepingdailytimerecord b
							WHERE b.transaction_type = '3'
							AND b.employee_number = ?
							AND b.is_active = '1'
							AND b.transaction_date = ?
							AND CAST(CONCAT(b.transaction_date, ' ', b.transaction_time) as datetime) >= time_in
							ORDER BY b.transaction_time ASC LIMIT 1)) AS break_in
				FROM tbltimekeepingdailytimerecord a
				WHERE a.employee_number = ? AND transaction_date = ? ";
				$query = $this->db->query($sql,$params);
				$attendance = $query->result_array();
				return (sizeof($attendance) > 0) ? $attendance[0] : $attendance;
			}
			function checkIfHoliday($working_day){
				$params = array($working_day);
				$sql = "SELECT * FROM tblfieldholidays WHERE date = ? AND is_active = 1";
				$query = $this->db->query($sql, $params);
				$holiday = $query->result_array();
				return $holiday;
			}
			function checkForApprovedLeave($working_day, $employee_id){
				$params = array($working_day, $employee_id);
				$sql = "SELECT *
				FROM tblleavemanagement a
				LEFT JOIN tblleavemanagementdaysleave b ON a.id = b.id WHERE b.days_of_leave = ? AND a.employee_id = ? AND a.is_active = 1";
				$query = $this->db->query($sql, $params);
				$leave = $query->result_array();
				return $leave;
			}
		function make_query_employee()
		{
			$this->select_column[] = 'tblfieldpositions.name';
			$this->select_column[] = 'tblfieldagencies.agency_name';
			$this->select_column[] = 'tblfieldoffices.name';
			$this->select_column[] = 'tblfielddepartments.department_name';
			$this->select_column[] = 'tblfieldlocations.name';
			$this->select_column[] = 'tblfieldfundsources.fund_source';
			$this->select_column[] = 'tblfieldloans.description';
			$this->select_column[] = 'tblfieldbudgetclassifications.budget_classification_name';
		    $this->db->select(
		    	$this->table.'.*,
		    	tblfieldpositions.name AS position_name,
		    	tblfieldoffices.name AS office_name,
		    	tblfielddepartments.department_name,
		    	tblfieldlocations.name AS location_name,
		    	tblfieldfundsources.fund_source,
		    	tblfieldloans.description AS loan_name,
		    	tblfieldbudgetclassifications.budget_classification_name'
		    );
		    $this->db->from($this->table);
		    $this->db->join("tblfieldpositions",$this->table.".position_id = tblfieldpositions.id","left");
		    $this->db->join("tblfieldagencies",$this->table.".agency_id = tblfieldagencies.id","left");
		    $this->db->join("tblfieldoffices",$this->table.".office_id = tblfieldoffices.id","left");
		    $this->db->join("tblfielddepartments",$this->table.".division_id = tblfielddepartments.id","left");
		    $this->db->join("tblfieldlocations",$this->table.".location_id = tblfieldlocations.id","left");
		    $this->db->join("tblfieldfundsources",$this->table.".fund_source_id = tblfieldfundsources.id","left");
		    $this->db->join("tblfieldloans",$this->table.".loans_id = tblfieldloans.id","left");
		    $this->db->join("tblfieldbudgetclassifications",$this->table.".budget_classification_id = tblfieldbudgetclassifications.id","left");

				if(isset($_POST["search"]["value"])) {
		    	$this->db->group_start();
		     	foreach ($this->select_column as $key => $value) {
		     		if($value == "tblemployees.first_name" || $value == "tblemployees.last_name" || $value == "tblemployees.middle_name" || $value == "tblemployees.employee_number" || $value == "tblemployees.employee_id_number")
		     			$this->db->or_like("DECRYPTER($value,'sunev8clt1234567890',tblemployees.id)", $_POST["search"]["value"]);
		     		else
		     			$this->db->or_like($value, $_POST["search"]["value"]);
		     	}
		     	$this->db->or_like("CONCAT(DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.middle_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id))"
		     		,$_POST["search"]["value"]);
		     	$this->db->or_like("CONCAT(DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id))"
		     		,$_POST["search"]["value"]);
		        $this->db->group_end();
		    }

		    if(isset($_GET['EmploymentStatus']) && $_GET['EmploymentStatus'] != null)
		    	$this->db->where($this->table.'.employment_status != "Active"');
		    else
					$this->db->where($this->table.'.employment_status = "Active"');

				if(isset($_GET['PayBasis']) && $_GET['PayBasis'] != null) {
					// if($_GET['PayBasis'] == 'Monthly') {
						$this->db->where($this->table.'.pay_basis = "' . $_GET['PayBasis'] . '"');
					// } else {
						// $this->db->where($this->table.'.pay_basis = "' . $_GET['PayBasis'] . '"');
					// }
				} else {
					$this->db->where('1=0');
				}
		    if(isset($_POST["order"]))
		    {
		          $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		    }
		    else
		    {
		          $this->db->order_by('id', 'DESC');
		    }
		}

		function make_datatables(){
		    $this->make_query_employee();
		    if($_POST["length"] != -1)
		    {
		        $this->db->limit($_POST['length'], $_POST['start']);
		    }
		    $query = $this->db->get();
		    return $query->result();
		}

		function make_datatables_summary($employee_id, $employee_number, $payroll_period, $payroll_period_id, $shift_id){
			$result = $this->make_query_summary($employee_id, $employee_number, $payroll_period, $payroll_period_id, $shift_id);
			return $result;
		}

		function hasRows(){
			$code = "1";
			$message = "No data available.";
			$this->make_query_employee();
			$query = $this->db->get();
		    if(sizeof($query->result()) > 0){
				$code = "0";
				$message = "Successfully fetched Daily Time Record.";
				$data['details'] = $query->result();
				$this->ModelResponse($code, $message, $data);
				return true;
			}
			else {
				$this->ModelResponse($code, $message);
			}
			return false;
		}
		function get_filtered_data(){
		     $this->make_query_employee();
		     $query = $this->db->get();
		     return $query->num_rows();
		}
		function get_all_data()
		{
		    $this->db->select($this->table."*");
				$this->db->from($this->table);
		    return $this->db->count_all_results();
		}

		function get_filtered_data_summary(){
		     $this->make_query_summary();
		     $query = $this->db->get();
		     return $query->num_rows();
		}
		function get_all_data_summary()
		{
			$this->db->select('*');
			$this->db->from('tbltimekeepingdailytimerecord');
		    return $this->db->count_all_results();
		}
		//End Fetch
		function getPayrollPeriodById($payroll_period_id){
			$sql = " SELECT * FROM tblfieldperiodsettings WHERE is_active = '1' AND id = ?";
			$query = $this->db->query($sql,array($payroll_period_id));
			$data = $query->result_array();
			return $data;
		}
		public function activeRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Daily Time Record failed to activate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "1";
			$userlevel_sql = "	UPDATE ".$this->table." SET
									is_active = ?
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully activated Daily Time Record.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;
			}
			else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		public function inactiveRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Daily Time Record failed to deactivate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "0";
			$userlevel_sql = "	UPDATE ".$this->table." SET
									is_active = ?
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully deactivated Daily Time Record.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;
			}
			else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}
		public function addRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$params['modified_by'] = Helper::get('userid');
			$params['is_restday'] = isset($params['is_restday']) ? "1" : "0" ;
			$message = "Daily Time Record failed to insert.";
			$this->db->insert($this->table, $params);
			if($this->db->affected_rows() > 0)
			{
				$code = "0";
				$message = "Successfully inserted Daily Time Record.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;
			}
			else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}
		public function updateRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Daily Time Record failed to update.";
			$params['modified_by'] = Helper::get('userid');
			$params['is_restday'] = isset($params['is_restday']) ? "1" : "0" ;
			$this->db->where('id', $params['id']);
			if ($this->db->update($this->table,$params) === FALSE){
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			else {
				$code = "0";
				$message = "Successfully updated Daily Time Record.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;
			}
			return false;
		}

		function hasRowsEmployee(){
			$code = "1";
			$message = "No data available.";
			$this->make_query_employee();
			$query = $this->db->get();

		    if(sizeof($query->result()) > 0){
				$code = "0";
				$message = "Successfully fetched employee.";
				$data['details'] = $query->result();
				$this->ModelResponse($code, $message, $data);
				return true;
			}
			else {
				$this->ModelResponse($code, $message);
			}
			return false;
		}
		/*function getWorkingDays ($payroll_period) {
			$workdays = array();
			$payroll_date = explode("-", $payroll_period);
			$no_of_days = date('t',strtotime($payroll_period));
			for ($day=1; $day <= $no_of_days; $day++) {
				$workdays[] = $payroll_date[0] . '-' . $payroll_date[1] . '-' . (($day > 9) ? $day : '0'. $day);
			}
			return $workdays;
		}*/
		function getDatesFromRange($start, $end, $format = 'Y-m-d') {
		    $array = array();
		    $interval = new DateInterval('P1D');

		    $realEnd = new DateTime($end);
		    $realEnd->add($interval);

		    $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

		    foreach($period as $date) { 
		        $array[] = $date->format($format); 
		    }

		    return $array;
		}
		function getWorkingDays($startDate,$endDate,$holidays){
		    $days = $this->getDatesFromRange( $startDate, $endDate );
		    $workdays = array();
		    foreach ($days as $key => $date) {
		    	$weekDay = date('w', strtotime($date));
    			if($weekDay != 0 && $weekDay != 6){
    				$workdays[] = $date;
    			}
		    }
		    return $workdays;
		}
		function isWeekend($date) {
		    return (date('N', strtotime($date)) >= 6);
		}

		function getTimeDifference($start, $end) {
			$start  	= strtotime($start);
			$end 			= strtotime($end);
			$diff 		= floor($end - $start);
			$minutes 	= floor($diff / 60) / 60;
			$time 		= explode(".", number_format((float)abs($minutes), 3, '.', ''));
			$time[1] 	= round($time[1]);
			$result 	= $time[0] . "." . $time[1];
			return $result;
		}

		function calculateWorkingDaysInMonth($year, $month) {
			$startdate = strtotime($year . '-' . $month . '-01');
			$enddate = strtotime('+' . (date('t',$startdate) - 1). ' days',$startdate);
			$currentdate = $startdate;
			$return = intval((date('t',$startdate)), 10);
			while ($currentdate <= $enddate) {
				if ((date('D',$currentdate) == 'Sat') || (date('D',$currentdate) == 'Sun')) {
					$return = $return - 1;
				}
				$currentdate = strtotime('+1 day', $currentdate);
			}
			return $return;
		}

		function ct($dec, $type) {
			$seconds = ($dec * 3600);
			$hours = floor($dec);
			$seconds -= $hours * 3600;
			$minutes = floor($seconds / 60);
			$seconds -= $minutes * 60;
			switch ($type) {
				case 'h':
					return $this->lz($hours);
				break;
				case 'i':
					return $this->lz($minutes);
				break;
				case 's':
					return $this->lz($seconds);
				break;
				default:
					return false;
				break;
			}
	}

		function lz($num) {
			return (strlen($num) < 2) ? "0{$num}" : $num;
		}



	}

?>
