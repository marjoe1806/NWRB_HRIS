<?php
$with_time = false;
$month_selected = "";
$year_selected = "";
foreach ($list as $kz => $vz) {
	$month_selected = date('F', strtotime($kz));
	$year_selected = date('Y', strtotime($kz));
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
if($with_time): ?>
<style>
	@media screen {
		.print-only {
			display: none;
		}
	}
</style>
<style type="text/css" media="all">
	table#tblcontent tbody > tr:not(:last-child) > td, table#tblcontent thead > tr > th{
		border: .5px solid black;
	}
</style>
<?php if($key == 'viewDailyTimeRecordSummary' && is_array($list) && sizeof($list)): ?>
		<div style="page-break-after: always">
		<table style="font-size:11px !important; margin-bottom: 0;font-family: 'Times New Roman', Times, serif;" width="100%">
				<thead>
					<?php if(isset($employee) && sizeof($employee) > 0): ?>
					<tr><th colspan="2" style="text-align: center;font-weight: bold;">Republic of the Philipines</th></tr>
					<tr><th colspan="2" style="text-align: center;font-weight: bold;font-size: 13px !important;">NATIONAL WATER RESOURCES BOARD</th></tr>
					<tr><th colspan="2">&nbsp;</th></tr>
					<tr><th colspan="2" style="text-align: center;font-weight: bold;">STATEMENT OF OVERTIME SERVICE RENDERED</th></tr>
					<tr><th colspan="2" style="text-align: center;font-weight: bold;"><center>DURING THE MONTH(S) OF <span style="width: 150px;text-decoration: underline;">&nbsp;&nbsp;&nbsp;<?php echo $month_selected . " " . $year_selected; ?>&nbsp;&nbsp;&nbsp;</span><center></th></tr>
					<tr><th colspan="2">&nbsp;</th></tr>
					<tr><th width="7%">NAME:</th><th style="border-bottom: .5px solid black;text-transform: uppercasse;"><?php echo $employee['last_name'] . ', '.$employee['first_name'] . ' ' . ($employee['middle_name'] != null && $employee['middle_name'] == 'N/A' ? '' : $employee['middle_name']); ?></th></tr>
					<tr><th>DIVISION:</th><th style="border-bottom: .5px solid black;text-transform: uppercasse;"><?php echo $employee['department'][0]['department_name']; ?></th></tr>
					<?php endif; ?>
				</thead>
			</table>
		</div>
		<br>
	<table id="tblcontent" cellpadding="5" cellpadding="5" style="font-size:11px !important; margin-bottom: 0;font-family: 'Times New Roman', Times, serif;" width="100%">
			<thead>
				<tr style="vertical-align: middle; text-align: center;">
					<th class="text-center" rowspan="2" style="vertical-align: middle; text-align: center; background: #fff" width="12.25%">Date</th>
					<th class="text-center" rowspan="2" style="vertical-align: middle; text-align: center; background: #fff" width="12.25%">Day</th>
					<th class="text-center" rowspan="2" width="12.25%">MORNING</th>
					<th class="text-center" rowspan="2" width="12.25%">NOON</th>
					<th class="text-center" rowspan="2" width="12.25%">NOON</th>
					<th class="text-center" rowspan="2" width="8.5%">OUT</th>
					<th class="text-center" colspan="2">EXTRA</th>
					<th class="text-center" rowspan="2" width="13%">TOTAL</th>
				</tr>
				<tr style="vertical-align: middle; text-align: center;">
					<th class="text-center" rowspan="2" width="8.5%">IN</th>
					<th class="text-center" rowspan="2" width="8.5%">OUT</th>
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
							
							$a = $list[$k]['adjustment_overtime_in'];
							$b = $list[$k]['adjustment_overtime_out'];
							$w = $list[$k]['actual_overtime_in'];
							$x = $list[$k]['actual_overtime_out'];

							$hours = 0;
							$mins = 0;
							// check if attendance has adjustments
							if($a != null && $b != null) {
								$a = strtotime($a);
								$b = strtotime($b);
								$elapsed = $b - $a;
								$years = abs(floor($elapsed / 31536000));
								$days = abs(floor(($elapsed-($years * 31536000))/86400));
								$hours = abs(floor(($elapsed-($years * 31536000)-($days * 86400))/3600));
								$mins = abs(floor(($elapsed-($years * 31536000)-($days * 86400)-($hours * 3600))/60));
								$total_ot_hours = $hours.":".$mins;
							} else {
								if($w != null && $x != null) {
									$w = strtotime($w);
									$x = strtotime($x);
									$elapsed = $x - $w;
									$years = abs(floor($elapsed / 31536000));
									$days = abs(floor(($elapsed-($years * 31536000))/86400));
									$hours = abs(floor(($elapsed-($years * 31536000)-($days * 86400))/3600));
									$mins = abs(floor(($elapsed-($years * 31536000)-($days * 86400)-($hours * 3600))/60));
									$total_ot_hours = $hours.":".$mins;
								} else {
									$total_ot_hours = "";
								}
							}
							$total_ot_hrs += (float) $hours;	
							$total_ot_min += (float) $mins;
							// if(date('l', strtotime($k)) !== "Saturday" || date('l', strtotime($k)) !== "Sunday"){
							// if($total_ot_hours!= ""){
				?>
				<tr>
					<td class="fixed-height"> <?php echo date('F d, Y', strtotime($k)); ?> </td>
					<td class="fixed-height"> <?php echo date('l', strtotime($k)); ?> </td>
					<td class="fixed-height"> <?php echo $arrival_am; ?> </td>
					<td class="fixed-height"> <?php echo $departure_am; ?> </td>
					<td class="fixed-height"> <?php echo $arrival_pm; ?> </td>
					<td class="fixed-height"> <?php echo $departure_pm; ?> </td>
					<td class="fixed-height"> <?php echo $overtime_in; ?> </td>
					<td class="fixed-height"> <?php echo $overtime_out; ?> </td>
					<td class="fixed-height"> <?php echo $total_ot_hours; ?> </td>
				</tr>
				<?php

				$day++; }
				// } 
				endif; ?>
				<tr>
					<td style="border: none;" colspan="7">&nbsp;</td>
					<td style="border: none; text-align: middle;">Total:</td>
					<td style="border-bottom: 1px solid black;border-left:none;border-right:none;"><?php echo $total_ot_hrs + floor($total_ot_min/60).":".fmod($total_ot_min,60); ?></td>
				</tr>
			</tbody>
		</table>
		<table style="font-size:12px !important; margin-bottom: 0;font-family: 'Times New Roman', Times, serif;" width="100%">
			<tr>
				<td width="25%">Check as to Time Card:</td>
				<td width="25%"></td>
				<td width="20%">Submitted by:</td>
				<td width="30%"></td>
			</tr>
			<tr>
				<td colspan="4">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="4">&nbsp;</td>
			</tr>
			<tr>
				<td style="border-bottom: .5px solid black;"></td>
				<td></td>
				<td style="border-bottom: .5px solid black;"></td>
				<td></td>
			</tr>
			<tr>
				<td>Personal Officer</td>
				<td></td>
				<td style="text-align: center;">Signature</td>
				<td></td>
			</tr>
			<tr>
				<td colspan="4">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="4">&nbsp;</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td>CERTIFIED CORRECT:</td>
				<td></td>
			</tr>
			<tr>
				<td colspan="4">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="4">&nbsp;</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td style="border-bottom: .5px solid black;"></td>
				<td></td>
			</tr>
		</table>

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
<?php endif; ?>
