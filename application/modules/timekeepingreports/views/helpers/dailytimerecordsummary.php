<?php
$with_time = false;
foreach ($list as $kz => $vz) {
	foreach ($vz as $kx => $vx) {
		if(@$vx['am_arrival'] != NULL || @$vx['pm_arrival'] != NULL || @$vx['am_departure'] != NULL || @$vx['pm_departure'] != NULL){
			$with_time = true;
			break;
		}
	}

	if($with_time == true)
		break;
}

function isWeekend($date) {
    $weekDay = date('w', strtotime($date));
    return ($weekDay == 0 || $weekDay == 6);
}
?>
<style>
	@media screen {
		.print-only {
			display: none;
			border-spacing: 0;
		}
	}

</style>
<?php if($key == 'viewDailyTimeRecordSummary' && is_array($list) && sizeof($list) > 0): ?>
<div style="page-break-after: always">
	<div class="table-responsive" style="width:100%; padding: 0; margin: 0; margin-left: 4mm !important; margin-right: 5mm;">
		<table class="table" style="font-size:10px !important; margin-bottom: 0">
			<thead style="background: #ddd">
				<?php if(isset($employee) && sizeof($employee) > 0): ?>
				<tr class="no-print">
					<th class="text-center" colspan="3">
						<span style="text-transform: uppercase; font-size: 12px; font-family: Arial, Helvetica, sans-serif !important;">
							<?php echo $employee['last_name'] . ', '.$employee['first_name'] . ' ' . ($employee['middle_name'] != null && $employee['middle_name'] == 'N/A' ? '' : $employee['middle_name']); ?></span>
					</th>
				</tr>
				<?php endif; ?>
			</thead>
		</table>
	</div>
	<div class="table-responsive" style="width:100%; padding: 0; margin: 0; margin-left: 4mm; margin-right: 5mm;">
		<table id="datatables_details border" cellspacing="0" class="table table-hover table-striped" style="font-size:10px !important;">
			<thead>
				<!-- No print -->
				<tr style="background: #eee;"  class="no-print">
					<th class="text-center" style="font-size:10px; ver tical-align: middle; text-align: center; background: #fff; font-family: Arial, Helvetica, sans-serif !important; border: 1px solid;" rowspan="2" width="10%">Day</th>
					<th class="text-center" style="font-size:10px !important; font-family: Arial, Helvetica, sans-serif !important; border: 1px solid;" colspan="2">AM</th>
					<th class="text-center" style="font-size:10px !important; font-family: Arial, Helvetica, sans-serif !important; border: 1px solid;" colspan="2">PM</th>
					<th class="text-center" style="font-size:10px !important; font-family: Arial, Helvetica, sans-serif !important; border: 1px solid;" colspan="2">Total</th>
					<th class="text-center" style=" font-size:10px; vertical-align: middle; text-align: center; font-family: Arial, Helvetica, sans-serif !important; border: 1px solid;" rowspan="2" width="25%">Remarks</th>
				</tr>
				<tr style="background: #eee;" class="no-print">
					<th class="text-center" style="font-size:10px !important; font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif !important; border: 1px solid;" width="10%">ARRIVAL</th>
					<th class="text-center" style="font-size:10px !important; font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif !important; border: 1px solid;"  width="15%">DEPARTURE</th>
					<th class="text-center" style="font-size:10px !important; font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif !important; border: 1px solid;"  width="10%">ARRIVAL</th>
					<th class="text-center" style="font-size:10px !important; font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif !important; border: 1px solid;"  width="15%">DEPARTURE</th>
					<th class="text-center" style="font-size:10px !important; font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif !important; border: 1px solid;"  width="10%">LATE</th>
					<th class="text-center" style="font-size:10px !important; font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif !important; border: 1px solid;"  width="15%">UNDERTIME</th>
				</tr>
				<!-- End of no Print -->
			</thead>
			<!-- No Print for TBODY -->
			<tbody class="text-center no-print" style="font-size: 11px !important; margin: 0;" >
				<?php
					if(isset($list) && sizeof($list) > 0):
						$day  = 1;
						$total_tardiness_hrs = 0;
						$total_tardiness_min = 0;
						$total_ut_hrs = 0;
						$total_ut_min = 0;	
						$total_ot_hrs = 0;
						$total_ot_min = 0;
						$total_ot_dec = 0.00;
						
						foreach($list as $k => $v) {
				?>
				<tr class="<?php echo isset($v['undertime_hours']) ? " text-danger": "0" ?>">
					<td class="fixed-height" style=" width: 50px; font-size: 10px; border: 1px solid;">
						<?php 
							$appr_offset_hrs = $v['appr_offset_hrs'];
							$appr_offset_mins = $v['appr_offset_mins'];

							$year = date('Y', strtotime($payroll_period[0]));
							$month = date('m', strtotime($payroll_period[0]));
							$date = $year . "-" . str_pad($month, 2, "0", STR_PAD_LEFT) . "-" . str_pad((string)$day, 2, "0", STR_PAD_LEFT);
							$my_date = date('d D', strtotime($date));
							echo $my_date."<br>";
							$adjustment_remarks = ($v['adjustment_remarks'] != "") ? $v['adjustment_remarks'] : "";
						?>
					</td>
					<td class="fixed-height" style="font-size: 10px; border: 1px solid;" align="center">
						<?php
							//Arrival AM
							$am_time_arrival =   $v['adjustment_am_arrival'] != "" ? $v['adjustment_am_arrival'] : $v['actual_am_arrival'];
							if ($am_time_arrival != null)echo  date("H:i", strtotime($am_time_arrival));
						?>
					</td>
					<td class="fixed-height" style="font-size: 11px; border: 1px solid;" align="center">
						<?php
							//Departure AM
							$am_time_departure =  $v['adjustment_am_departure'] != "" ? $v['adjustment_am_departure'] : $v['actual_am_departure'];
							if ($am_time_departure != null)echo  date("H:i", strtotime($am_time_departure));
						?>
					</td>
					<td class="fixed-height" style="font-size: 10px; border: 1px solid;" align="center">
						<?php
							//Arrival PM
							$pm_time_arrival =  $v['adjustment_pm_arrival'] != "" ? $v['adjustment_pm_arrival'] : $v['actual_pm_arrival'];
							if ($pm_time_arrival != null)echo  date("H:i", strtotime($pm_time_arrival));
						?>
					</td>
					<td class="fixed-height" style="font-size: 10px; border: 1px solid;" align="center">
						<?php
							//Departure PM
							$pm_time_departure =  $v['adjustment_pm_departure'] != "" ? $v['adjustment_pm_departure'] : $v['actual_pm_departure'];
							if ($pm_time_departure != null)echo  date("H:i", strtotime($pm_time_departure));
						?>
					</td>
					<td class="fixed-height" style="font-size: 10px; border: 1px solid;" align="center">
						<?php
							//Tardiness hours
							echo sprintf('%02d:%02d', $v['tardiness_hrs'], $v['tardiness_mins']);
							$total_tardiness_hrs += @$v['tardiness_hrs'];
							$total_tardiness_min += @$v['tardiness_mins'];
						?>
					</td>					
					<td class="fixed-height" style="font-size: 10px; border: 1px solid;" align="center">
						<?php
							//Undertime hours
							$totalOffset = 0;
							if ( $v['actual_remarks'] == "OB" || $v['adjustment_remarks'] == "OB") $v['ut_hrs'] = $v['ut_mins'] = 0;
							if($v['actual_remarks'] == "OFFSET" || $v['adjustment_remarks'] == "OFFSET"){
								$totalOffset = ($v['appr_offset_hrs'] * 60) + $v['appr_offset_mins'];
									if((($v['ut_hrs'] * 60) - $totalOffset) > 0 ) $v['ut_mins'] =(($v['ut_hrs'] * 60) -  $totalOffset)%60;
									$v['ut_hrs'] = (($v['ut_hrs'] * 60) -  $totalOffset > 0)?   (int)((($v['ut_hrs'] * 60) -  $totalOffset)/60) : 0;
						
							}
							echo sprintf('%02d:%02d', $v['ut_hrs'], $v['ut_mins']);
							$total_ut_hrs += @$v['ut_hrs'];
							$total_ut_min += @$v['ut_mins'];
						?>
					</td>
					<td align="center" style="font-size: 10px; border: 1px solid;">
					<?php
						//Remarks
						$remarks = $v['actual_remarks'] != "" ? $v['actual_remarks'] : $v['adjustment_remarks'];
						
						if($v["actual_am_arrival"] == "" &&
							$v["actual_am_departure"] == "" &&
							$v["actual_pm_arrival"] == "" &&
							$v["actual_pm_departure"] == "" &&
							$v["actual_overtime_in"] == "" &&
							$v["actual_overtime_out"] == "" &&
							$v["actual_remarks"] == "" &&
							$v["adjustment_am_arrival"] == "" &&
							$v["adjustment_am_departure"] == "" &&
							$v["adjustment_pm_arrival"] == "" &&
							$v["adjustment_pm_departure"] == "" &&
							$v["adjustment_overtime_in"] == "" &&
							$v["adjustment_overtime_out"] == "" &&
							$v["adjustment_remarks"] == "" &&
							$v["offset"] == "" &&
							$v["ot_hrs"] == "" &&
							$v["ot_mins"] == "" &&
							$v["tardiness_hrs"] == "" &&
							$v["tardiness_mins"] == "" &&
							$v["ut_hrs"] == "" &&
							$v["ut_mins"] == "" &&
							$v["appr_offset_hrs"] == "" &&
							$v["appr_offset_mins"] == "" &&
							(strtotime($k) <= strtotime(date("Y/m/d"))) &&
							isWeekend($k) != 1
							){
							$remarks = "Absent";
						}
						
						if(isset($holidays) && !preg_match('/\b(OB|TO|Leave)\b/', $remarks)) {
							foreach($holidays as $k => $v) {
								$holiday = isset($v['date'])? date('d', strtotime($v['date'])) : 0;
								if($day == (int) $holiday) {
									if($v['holiday_type'] == 'Suspension'){
										if($am_time_arrival == null && ($am_time_departure != null || $pm_time_arrival != null || $pm_time_departure != null)){
											$remarks = "Work Suspension (See HR)";	
										}else if ($appr_offset_hrs > 0 || $appr_offset_mins > 0) {
											$remarks = "Work Suspension (CTO)";	
										}else if ($am_time_arrival == null &&  $pm_time_departure == null) {
											$remarks = "Work Suspension (Absent)";		
											if($v['time_suspension'] == '08:00 AM'){
												$remarks = "Work Suspension";		
											}							
										}else{
											$remarks = 'Work Suspension';
										}
									}else{
										$remarks = "Holiday";
									}
								}
							}
						}
						if($remarks == 'Rest Day') $remarks = "";
					
						if($adjustment_remarks == "Special Order" ||
							$adjustment_remarks == "Time-Off" ||
							$adjustment_remarks == "Timeplacement" ||
							$adjustment_remarks == "OT w/ Certification" ||
							$adjustment_remarks == "WFH" ||
							$adjustment_remarks == "LWOP" ||
							$adjustment_remarks == "AWOL"
						)
						{
							echo $adjustment_remarks;
						}else{
							echo  $remarks == "OFFSET" ? "CTO" : $remarks;
						}
					    ?>
					</td>					
				</tr>
				<?php $day++; } endif; ?>
				<tr class="<?php echo isset($v['undertime_hours']) ? " text-danger": "0" ?>" style="background: #ccc">
					<td style="font-weight: bold; border-left: 0px solid; border-right: 0px solid; border-bottom: 1px dotted #333 !important; border: 1px solid; font-size: 11px;">Total</td>
					<td class="no-print" style="border: 1px solid;" colspan="4"></td>
					<!-- <td align="center" class="print-only" colspan="5" style="font-weight: bold; border-left: 0px solid; border-right: 0px solid; border-bottom: 1px dotted #333 !important; border: 1px solid; font-size: 11px;"></td> -->
					<td align="center" class="text-danger" style="font-weight: bold; border-bottom: 1px dotted #333 !important; border: 1px solid black; font-size: 11px;">
						<?php echo sprintf('%02d:%02d',$total_tardiness_hrs + floor($total_tardiness_min/60), fmod($total_tardiness_min,60)); ?>
					</td>
					<td align="center" class="text-danger" style="font-weight: bold; border-bottom: 1px dotted #333 !important; border: 1px solid black; font-size: 11x;">
						<?php echo sprintf('%02d:%02d',$total_ut_hrs + floor($total_ut_min/60),fmod($total_ut_min,60)); ?>
					</td>
					<td align="center" class="text-danger" style="font-weight: bold; border-bottom: 1px dotted #333 !important; border: 1px solid black; font-size: 11x;">
						<?php echo sprintf('%02d:%02d',$total_tardiness_hrs + $total_ut_hrs + floor(($total_tardiness_min+$total_ut_min)/60), fmod($total_tardiness_min + $total_ut_min,60)); ?>
					</td>
				</tr>
			</tbody>
			<!-- END OF NO PRINT FOR TBODY -->
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
