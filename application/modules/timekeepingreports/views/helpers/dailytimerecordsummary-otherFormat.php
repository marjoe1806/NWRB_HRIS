
<?php
// print_r($flag_ceremony_day);
// print_r($holidays);

// print_r($employee_schedule);
// print_r($employee_flex_minima_schedule);
// print_r($employee_flex_maxima_schedule);
$with_time = false;
foreach ($list as $kz => $vz) {
	// var_dump($vz);
	foreach ($vz as $kx => $vx) {
		if(@$vx['am_arrival'] != NULL || @$vx['pm_arrival'] != NULL || @$vx['am_departure'] != NULL || @$vx['pm_departure'] != NULL){
			$with_time = true;
			break;
		}
	}

	if($with_time == true)
		break;
}
// die();
 ?>
<style>
	@media screen {
		.print-only {
			display: none;
		}
	}
</style>
<?php if($key == 'viewDailyTimeRecordSummary' && is_array($list) && sizeof($list) > 0): ?>
<div style="page-break-after: always">
	<!-- HEADER FOR PRINTOUT -->
	<div class="table-responsive" style="width:100%; padding: 0; margin: 0;">
		<table class="table" style="font-size:10px !important; margin-bottom: 0">
			<thead style="background: #red">
				<?php if(isset($employee) && sizeof($employee) > 0): ?>
				<tr>
					<th class="text-left print-only" colspan="3" style="font-size: 10px !important;">Civil Service Form No. 48
						&nbsp;&nbsp;&nbsp; Department/Unit -
						<?php if(isset($employee) && sizeof($employee) > 0) { echo isset($employee['department'][0]['code']) ? $employee['department'][0]['code'] : ''; } ?>
					</th>
				</tr>
				<tr>
					<th class="text-center print-only" rowspan="3" colspan="3" style="font-size: 14px">DAILY TIME RECORD</th>
				</tr>
				<tr>
					<th class="text-center print-only" colspan="3">&nbsp;</th>
				</tr>
				<tr>
					<th class="text-center print-only" colspan="3">&nbsp;</th>
				</tr>
				<tr>
					<th class="text-center print-only" colspan="3">
						<span style="text-transform: uppercase; font-size: 12px">
							<?php echo $employee['last_name'] . ', '.$employee['first_name'] . ' ' . ($employee['middle_name'] != null && $employee['middle_name'] == 'N/A' ? '' : $employee['middle_name']); ?></span>
						<hr style="display: block; height: 1px; background: transparent;  border: none; border-top: solid 1px #666; margin: 3px 0 3px 0">
						<span>Name</span>
					</th>
				</tr>
				<tr>
				</tr>
				<tr>
					<th class="text-left print-only" style="font-size: 11px !important; font-family: 'Times New Roman'; font-style: italic"
					 >For the month of</th>
					<th class="text-center print-only" style="border-bottom: 1px dotted #333; font-size: 12px !important; text-transform: uppercase"
					 colspan="2">
						<?php echo date('F ', strtotime($payroll_period[0])) . ' ' . date('j', strtotime($payroll_period[1])) . '-' . date('j', strtotime($payroll_period[2])) . ', ' . date(' Y', strtotime($payroll_period[0])) ?>
					</th>
				</tr>
				<tr>
					<th class="text-left print-only" style="font-size: 11px !important; font-family: 'Times New Roman'; font-style: italic">Official
						hours for arrival</th>
					<th class="text-right print-only" style="font-size: 11px !important; font-family: 'Times New Roman'; font-style: italic">Regular
						days: </th>
					<th class="text-right print-only" style="font-size: 11px !important; border-bottom: 1px dotted #333;">&nbsp;</th>
				</tr>
				<tr>
					<th class="text-left print-only" style="font-size: 11px !important; font-family: 'Times New Roman'; font-style: italic">and
						departure</i></th>
					<th class="text-right print-only" style="font-size: 11px !important; font-family: 'Times New Roman'; font-style: italic">Saturdays:
					</th>
					<th class="text-right print-only" style="font-size: 11px !important; border-bottom: 1px dotted #333;">&nbsp;</th>
				</tr>
				<?php endif; ?>
			</thead>
		</table>
	</div>

	<!-- HEADER FOR GUI -->
	<div class="table-responsive" style="width:100%; padding: 0; margin: 0;">
		<table class="table" style="font-size:10px !important; margin-bottom: 0">
			<thead style="background: #ddd">
				<?php if(isset($employee) && sizeof($employee) > 0): ?>
				<tr class="no-print">
					<th class="text-center" colspan="3">
						<span style="text-transform: uppercase; font-size: 12px">
							<?php echo $employee['last_name'] . ', '.$employee['first_name'] . ' ' . ($employee['middle_name'] != null && $employee['middle_name'] == 'N/A' ? '' : $employee['middle_name']); ?></span>
					</th>
				</tr>
				<?php endif; ?>
			</thead>
		</table>
	</div>
	<!-- <br class="print-only"> -->
	<div class="table-responsive listTable border" style="width:100%;">
		<table id="datatables_details border" class="table table-hover table-striped border table-bordered" style="font-size:10px !important; margin-bottom: 0">
			<thead>
				<!-- <tr style="background: #eee; vertical-align: middle; text-align: center;">
					<th class="text-center print-only" style="vertical-align: middle; text-align: center;" colspan="9"></th>
				</tr> -->
				<tr style="background: #ccc; vertical-align: middle; text-align: center;">
					<th class="text-center" style="vertical-align: middle; text-align: center; background: #fff" rowspan="2" width="5%">Day</th>
					<th class="text-center" colspan="2">A. M.</th>
					<th width="1%" class="print-only"></th>
					<th class="text-center" colspan="2">P. M.</th>
					<th width="1%" class="print-only"></th>
					<th class="text-center" colspan="2">UNDERTIME</th>
					<th width="1%" class="print-only"></th>
					<th class="text-center" style="vertical-align: middle; text-align: center;" rowspan="2">Remarks</th>
				</tr>
				<tr style="background: #eee">
					<th class="text-center" width="15%">ARRIVAL</th>
					<th class="text-center" width="15%">DEPARTURE</th>
					<th class="print-only"></th>
					<th class="text-center" width="15%">ARRIVAL</th>
					<th class="text-center" width="15%">DEPARTURE</th>
					<th width="1%" class="print-only"></th>
					<th class="text-center" width="15%">HOURS</th>
					<th class="text-center" width="15%">MINUTES</th>
					<th class="print-only"></th>
				</tr>
			</thead>
			<tbody class="text-center">
				<?php
					if(isset($list) && sizeof($list) > 0):
						$day  = 1;
						$total_tardiness_hrs = 0;
						$total_tardiness_min = 0;
						$total_ut_hrs = 0;
						$total_ut_min = 0;	
						$total_ot_hrs = 0;
						$total_ot_min = 0;
						
						foreach($list as $k => $v) {
							

							if($v['adjustment_am_arrival'] != null || $v['adjustment_am_departure'] != null || $v['adjustment_pm_arrival'] != null || $v['adjustment_pm_departure'] != null) {
								$arrival_am = @$v['adjustment_am_arrival'] != "" ? date('h:i a', strtotime($v['adjustment_am_arrival'])) : "";
								$departure_am = $v['adjustment_am_departure'] != "" ? date('h:i a', strtotime($v['adjustment_am_departure'])) : "";
								$arrival_pm = $v['adjustment_pm_arrival'] != "" ? date('h:i a', strtotime($v['adjustment_pm_arrival'])) : "";
								$departure_pm = $v['adjustment_pm_departure'] != "" ? date('h:i a', strtotime($v['adjustment_pm_departure'])) : "";
								$overtime_in = $v['adjustment_overtime_in'] != "" ? date('h:i a', strtotime($v['adjustment_overtime_in'])) : "";
								$overtime_out = $v['adjustment_overtime_out'] != "" ? date('h:i a', strtotime($v['adjustment_overtime_out'])) : "";
							} else {
								$arrival_am = @$v['actual_am_arrival'] != "" ? date('h:i a', strtotime($v['actual_am_arrival'])) : "";
								$departure_am = @$v['actual_am_departure'] != "" ? date('h:i a', strtotime($v['actual_am_departure'])) : "";
								$arrival_pm = @$v['actual_pm_arrival'] != "" ? date('h:i a', strtotime($v['actual_pm_arrival'])) : "";
								$departure_pm = @$v['actual_pm_departure'] != "" ? date('h:i a', strtotime($v['actual_pm_departure'])) : "";
								$overtime_in = @$v['actual_overtime_in'] != "" ? date('h:i a', strtotime($v['actual_overtime_in'])) : "";
								$overtime_out = @$v['actual_overtime_out'] != "" ? date('h:i a', strtotime($v['actual_overtime_out'])) : "";
							}
							$remarks = $v['remarks'];
							// var_dump($remarks); die();
				?>
				<tr class="<?php echo isset($v['undertime_hours']) ? " text-danger": "0" ?>">
					<td class="fixed-height">
						<?php echo $day; ?>
					</td>
					<td class="fixed-height">
					<?php
						//Arrival AM
						echo $arrival_am;
							// echo checkTime($time_in, 'am') != false ? checkTime($time_in, 'am'): checkTime($break_in, 'am');

					?>
					</td>
					<td class="fixed-height">
					<?php
						//Departure AM
						echo $departure_am;
						// if($nextday != false) {
						// 	echo $break_out != '' ? $break_out : checkTime($time_out, 'am');
						// }
					?>
					</td>
					<td class="print-only"></td>
					<td class="fixed-height">
					<?php
						//Arrival PM
						echo $arrival_pm;
						// echo $break_in != '' ? $break_in : checkTime($time_in, 'pm');
					?>
					</td>
					<td class="fixed-height">
						<?php
						//Departure PM
							echo $departure_pm;
							// echo checkTime($time_out, 'pm') != false ? checkTime($time_out, 'pm'): checkTime($break_out, 'pm');
						?>
					</td>
					
					
			
					<td class="print-only"></td>
					
					<td class="fixed-height">
					<?php
						$transaction_date = date('Y-', strtotime($payroll_period[0])).date('m-', strtotime($payroll_period[0])).$day;
						$transaction_day = date('l', strtotime($transaction_date));
						$hours = null;
						$minutes = null;
						$break_hours = null;
						$break_minutes = null;

						$a = $list[$k]['adjustment_am_arrival'];
						$b = $list[$k]['adjustment_am_departure'];
						$c = $list[$k]['adjustment_pm_arrival'];
						$d = $list[$k]['adjustment_pm_departure'];
						$w = $list[$k]['actual_am_arrival'];
						$x = $list[$k]['actual_am_departure'];
						$y = $list[$k]['actual_pm_arrival'];
						$z = $list[$k]['actual_pm_departure'];

						if(isset($employee_schedule) && (sizeof($employee_schedule) > 0)) {
							foreach ($employee_schedule as $employee_schedule_key => $employee_schedule_value) {
								if((int)$employee_schedule_key <= $day) {

									for($t=0; $t<sizeof($employee_schedule_value); $t++) {
										if($employee_schedule_value[$t]['shift_type'] == 1) {
											if($employee_schedule_value[$t]['week_day'] == $transaction_day){
												if($b != null || $d != null) {
													if((int)str_replace(":", "", $employee_schedule_value[$t]['end_time']) < (int)str_replace(":", "", $d)) {
														$d = $employee_schedule_value[$t]['end_time'];
													}

													if((int)str_replace(":", "", $employee_schedule_value[$t]['break_start_time']) < (int)str_replace(":", "", $b)) {
														$b = $employee_schedule_value[$t]['break_start_time'];
													}
												} else if($x != null || $z != null) {
													if((int)str_replace(":", "", $employee_schedule_value[$t]['end_time']) < (int)str_replace(":", "", $z)) {
														$z = $employee_schedule_value[$t]['end_time'];
													}

													if((int)str_replace(":", "", $employee_schedule_value[$t]['break_start_time']) < (int)str_replace(":", "", $x)) {
														$x = $employee_schedule_value[$t]['break_start_time'];
													}
												}

												$end_sched = $employee_schedule_value[$t]['end_time'];
												$break_start_sched = $employee_schedule_value[$t]['break_start_time'];
											}
										} else {
											//flexi schedule
											$min_start_date = $employee_schedule_value[$t]['start_time'];
											$max_start_date = date('H:i:s',strtotime('+1 hour',strtotime($min_start_date)));
											$minstart = (int)str_replace(":", "", $min_start_date);
											$maxstart = (int)str_replace(":", "", $max_start_date);

											if($d != null) 
												$end_sched = $d;
											else if($z != null) 
												$end_sched = $z;



											if($a != null || $c != null) {
												//for start time
												$start_sched = $a;
												if($maxstart < (int)str_replace(":", "", $a)) {
													$start_sched = $max_start_date;
												}

												if($minstart > (int)str_replace(":", "", $a)) {
													$start_sched = $min_start_date;
												}
											} else if($w != null || $y != null) {
												$start_sched = $w;
												if($maxstart < (int)str_replace(":", "", $w)) {
													$start_sched = $max_start_date;
												}

												if($minstart > (int)str_replace(":", "", $w)) {
													$start_sched = $min_start_date;
												}												
											}	
											$end_sched = date('H:i:s',strtotime('+9 hour',strtotime($start_sched)));
											$break_start_sched = "12:00:00";
											
										}
									}	
								}
							}		
						}
							

						if($b != null || $d != null) {
							// $start = strtotime($a);  
							// $end = strtotime($d);  

							$start = strtotime($d);
							$end = strtotime($end_sched);

							if($end > $start) {
								$elapsed = abs($end - $start);
								$years = abs(floor($elapsed / 31536000));
								$days = abs(floor(($elapsed-($years * 31536000))/86400));
								$hours = abs(floor(($elapsed-($years * 31536000)-($days * 86400))/3600));
								$minutes = abs(floor(($elapsed-($years * 31536000)-($days * 86400)-($hours * 3600))/60));
							}
							
							if($b != null) {
								$start = strtotime($b);
								$end = strtotime($break_start_sched);

								// print_r($hours);
								// print_r($break_end_sched);
								// print_r("!!!!!".$y);

								if($end > $start) {
									$elapsed = abs($end - $start);
									$years = abs(floor($elapsed / 31536000));
									$days = abs(floor(($elapsed-($years * 31536000))/86400));
									$break_hours = abs(floor(($elapsed-($years * 31536000)-($days * 86400))/3600));
									$break_minutes = abs(floor(($elapsed-($years * 31536000)-($days * 86400)-($break_hours * 3600))/60));
								}
							}	
							
							// print_r($end."!!!".$start."!!!");
							$minutes = $minutes + $break_minutes;
							$hours = $hours + $break_hours;
						
						} else {
							if($x != null || $z != null) {
								$start = strtotime($z);
								$end = strtotime($end_sched);

								if($end > $start) {
									$elapsed = abs($end - $start);
									$years = abs(floor($elapsed / 31536000));
									$days = abs(floor(($elapsed-($years * 31536000))/86400));
									$hours = abs(floor(($elapsed-($years * 31536000)-($days * 86400))/3600));
									$minutes = abs(floor(($elapsed-($years * 31536000)-($days * 86400)-($hours * 3600))/60));
								}
								
								if($x != null) {
									$start = strtotime($x);
									$end = strtotime($break_start_sched);

									// // print_r($break_end_sched);
									// // print_r("!!!!!".$y);

									if($end > $start) {
										$elapsed = abs($end - $start);
										$years = abs(floor($elapsed / 31536000));
										$days = abs(floor(($elapsed-($years * 31536000))/86400));
										$break_hours = abs(floor(($elapsed-($years * 31536000)-($days * 86400))/3600));
										$break_minutes = abs(floor(($elapsed-($years * 31536000)-($days * 86400)-($break_hours * 3600))/60));
									}
								}
									
								$minutes = $minutes + $break_minutes;
								$hours = $hours + $break_hours;
								// print_r("!!!!!".$minutes);

							}	
						}


						if($minutes == 60) {
							$hours += 1;
							$minutes = 0;
						}

						if($minutes > 60) {
							$minutes = fmod($minutes,60);
							$hours += intdiv($minutes,60);
						}

						$total_ut_min += (float) $minutes;
						$total_ut_hrs += (float) $hours;
						// $hours = $w;
						// $minutes = $z;
						// $total_hours = $total_hours_am != null && $total_hours_pm != null ? number_format((float)$total_hours_am + $total_hours_pm, 2, '.', '') : 0;
						// $undertime = $total_hours != null && $total_hours != 0 ? $total_hours - 9 : 0;
						// $undertime = $undertime < 0 ? abs($undertime) : 0;

						// $seconds = $undertime * 3600;
						// $hours = floor($undertime);
						// $seconds -= $hours * 3600;
						// $minutes = floor($seconds / 60);
						// $seconds -= $minutes * 60;

					?>
						<?php // echo $total_hours_am; ?>
						<?php 
							echo $hours != 0 ? $hours : ""; 
							// echo $start_sched." !! ".$end_sched." = ".$d;						
						?>
						<?php //echo isset($undertime) && $undertime != null ? "<label class='text-danger'>" . $hours . "</label>" : ""; ?>
						<?php if(isset($remarks) && $remarks != ""){
						}
						?>
					</td>
					<td class="fixed-height">
						<?php 
								echo $minutes != 0 ? $minutes : "";

						?>
					</td>

					<td class="print-only"></td>

					<td>
						<?php if(isset($remarks) && $remarks != ""){
							echo '<span style="font-size:8px">';
							// if($remarks == "locator slip"){
							// 	echo "with Locator Slip";
							// }
							// elseif($remarks == "Defective"){
							// 	echo "Defective";
							// }
							// elseif($remarks == "offsetting"){
							// 	echo "Offsetting";
							// }
							// elseif($remarks == "timeplacement"){
							// 	// echo "Time Placement";
							// 	echo "<span class='tplacement'>Time Placement</span>";
							// }
							// else {
							// 	echo "On Leave";
							// }

							if (strpos($remarks, ':') !== false) 
							    echo $remarks;
							else {
								switch($remarks) {
									case 'timeplacement' :
										echo "Time Placement";
										break;
									case 'flagceremony' :
										echo "Flag Ceremony";
										break;
									case 'holiday' :
										echo $holidays[0]['holiday_name'];
										break;
									default :
										echo "On Leave";
										break;
								}
							}
							echo '</span>';
						}
						?>

					</td>
				
				</tr>
				<?php // else: ?>

					<!-- <tr>
						<td>
						</td>
						<td>
						</td>
						<td>
						</td>
						<td class="print-only">
						</td>
						<td>
						</td>
						<td>
						</td>
						<td class="print-only">
						</td>
						<td>
						</td>
						<td>
						</td>
					</tr> -->
				<?php
				// endif;

				$day++; } endif; ?>
				<!-- <tr>
					<td class="print-only" style="border-left: 0px solid; border-right: 0px solid;" colspan="13">&nbsp;</td>
				</tr> -->
				<tr class="<?php echo isset($v['undertime_hours']) ? " text-danger": "0" ?>" style="background: #ccc">
					<td style="font-weight: bold; border-left: 0px solid; border-right: 0px solid; border-bottom: 1px dotted #333 !important;">
						Total
					</td>
					<td class="no-print" colspan="4"></td>
					<td class="print-only" colspan="6" style="font-weight: bold; border-left: 0px solid; border-right: 0px solid; border-bottom: 1px dotted #333 !important;"></td>
					
				
					<td class="text-danger" style="font-weight: bold; border-bottom: 1px dotted #333 !important;">
						<?php echo $total_ut_hrs + floor($total_ut_min/60); ?>
					</td>
					<td class="text-danger" style="font-weight: bold; border-bottom: 1px dotted #333 !important;">
						<?php echo fmod($total_ut_min,60); ?>
					</td>
					<td class="print-only" style="font-weight: bold; border-bottom: 1px dotted #333 !important;"></td>
					<td class="text-danger" style="font-weight: bold; border-bottom: 1px dotted #333 !important;">
						
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<br class="print-only">
	<!-- <img class="print-only" style="float: right; z-index: 100; margin-right: 100px" src="<?php echo base_url() . 'assets/custom/images/signature_mam_mila-mod1.jpg?'.rand(); ?>"> -->
	<div class="table-responsive" style="width:100%; padding: 0; margin: 0;">
		<table class="table" style="font-size:11px !important; margin: 0; padding: 0; border: 0px">
			<thead style="background: #ddd">
				<tr>
					<th class="text-left print-only" colspan="3" style="font-size:10px !important">
						<hr class="print-only" style="display: block; height: 1px; background: transparent; width: 100%; border: none; border-top: solid 1px #000; margin: 0 0 2px 0">
						<hr class="print-only" style="display: block; height: 1px; background: transparent; width: 100%; border: none; border-top: solid 1px #000; margin: 0 0 5px 0">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; I certify on my honor that the above is a true and
						correct report of the hours of work performed, record of
						which was made daily at the time of arrival and departure from office.
					</th>
				</tr>
				<tr>
					<th class="text-left print-only" colspan="2">&nbsp;</th>
					<th class="text-right print-only" style="font-size:10px !important; border-bottom: 1px dotted #999; width: 50%">
					</th>
				</tr>
				<tr>
					<th class="text-left print-only" colspan="3" style="font-size:10px !important">
						<hr class="print-only" style="display: block; height: 1px; background: transparent; width: 100%; border: none; border-top: solid 1px #000; margin: 5px 0 0 0">
						<hr class="print-only" style="display: block; height: 1px; background: transparent; width: 100%; border: none; border-top: solid 1px #000; margin: 2px 0 5px 0">
						Verified as to the prescribed office hours.
					</th>
				</tr>
				<tr>
					<th class="text-left print-only" colspan="2">&nbsp;</th>
					<th class="text-right print-only" style="font-size:10px !important; border-bottom: 1px dotted #999; width: 50%">
					</th>
				</tr>
				<tr>
					<th class="text-left print-only" style="font-size:10px !important"><!-- Station: -->
						<!-- <span style="font-weight: bold; font-size: 11px">
							<?php if(isset($employee) && sizeof($employee) > 0) { echo isset($employee['location']['name']) ? $employee['location']['name'] : ''; } ?>
						</span> -->
					</th>
					<th class="text-center print-only" colspan="2" width="50%" style="font-size:10px !important">In Charge</th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<?php endif; ?>

<?php

	function checkTime($time, $type){
		$ante = $time != null && $time != '' ? date('a', strtotime($time)) : '';
		if($type != null && $type != '') {
			switch ($type) {
				case 'am':
					return $ante == 'am' ? date('G:i:s', strtotime($time)) : '';
					break;
				case 'pm':
					return $ante == 'pm' ? date('G:i:s', strtotime($time)) : '';
					break;
				default:
					return false;
					break;
			}
		} else {
			return false;
		}
	}

	function convertTime($dec, $type) {
		$seconds = ($dec * 3600);
		$hours = floor($dec);
		$seconds -= $hours * 3600;
		$minutes = floor($seconds / 60);
		$seconds -= $minutes * 60;
		switch ($type) {
			case 'h':
				return $hours;
			break;
			case 'i':
				return $minutes;
			break;
			case 's':
				return $seconds;
			break;
			default:
				return false;
			break;
		}
	}

	function lz($num) {
		return (strlen($num) < 2) ? "0{$num}" : $num;
	}
?>

