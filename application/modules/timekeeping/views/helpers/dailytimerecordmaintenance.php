<style>
	@media screen {
		.print-only {
			display: none;
		}
	}

</style>
<?php
// var_dump($list);
?>
<?php if($key == 'viewDailyTimeRecordMaintenance'): ?>
<div id="table-holder-summary">
	<div id="printable-table-holder-summary" class="border">
		<div class="table-responsive listTable border">
			<table id="datatables_details" class="datatables_details table table-hover"
				style="width:100%; font-size:11px !important; margin-bottom: 0">
				<?php if((isset($list) && sizeof($list) > 0)): ?>
				<thead>
					<tr>
						<th class="text-center">Date</th>
						<th class="text-center">Day</th>
						<th class="text-center">Time In</th>
						<th class="text-center">Lunch Out</th>
						<th class="text-center">Lunch In</th>
						<th class="text-center">Time Out</th>
						<th class="text-center">OT In</th>
						<th class="text-center">OT Out</th>
						<th class="text-center">Remarks</th>
						<th class="text-center" width="15%">Action</th>
					</tr>
				</thead>
				<tbody class="text-center">
					<?php 
						foreach ($list['attendance'] as $day => $log) {
						if(isset($list['attendance']) && $list['attendance'] != null):
							
							$am_arrival = $log['dtr_adjustment_am_arrival'] != null ? $log['dtr_adjustment_am_arrival'] : $log['dtr_am_arrival'];
							$am_departure = $log['dtr_adjustment_am_departure'] != null ? $log['dtr_adjustment_am_departure'] : $log['dtr_am_departure'];
							$pm_arrival = $log['dtr_adjustment_pm_arrival'] != null ? $log['dtr_adjustment_pm_arrival'] : $log['dtr_pm_arrival'];
							$pm_departure = $log['dtr_adjustment_pm_departure'] != null ? $log['dtr_adjustment_pm_departure'] : $log['dtr_pm_departure'];
							$ot_arrival = $log['dtr_adjustment_overtime_in'] != null ? $log['dtr_adjustment_overtime_in'] : $log['dtr_overtime_in'];
							$ot_departure = $log['dtr_adjustment_overtime_out'] != null ? $log['dtr_adjustment_overtime_out'] : $log['dtr_overtime_out'];
							// if($log['adjustment_am_arrival'] != null || $log['adjustment_am_departure'] != null || $log['adjustment_pm_arrival'] != null || $log['adjustment_pm_departure'] != null || $log['adjustment_overtime_in'] != null || $log['adjustment_overtime_out'] != null) {
								// $am_arrival = $log['adjustment_am_arrival'];
								// $am_departure = $log['adjustment_am_departure'];
								// $pm_arrival = $log['adjustment_pm_arrival'];
								// $pm_departure = $log['adjustment_pm_departure'];
								// $ot_arrival = $log['adjustment_overtime_in'];
								// $ot_departure = $log['adjustment_overtime_out'];
							// } else {
							// 	$am_arrival = $log['actual_am_arrival'];
							// 	$am_departure = $log['actual_am_departure'];
							// 	$pm_arrival = $log['actual_pm_arrival'];
							// 	$pm_departure = $log['actual_pm_departure'];
							// 	$ot_arrival = $log['actual_overtime_in'];
							// 	$ot_departure = $log['actual_overtime_out'];
							// }
						
						$remarks = $log['remarks'] == "OFFSET" ? "CTO" : $log['remarks'];
						// if($am_arrival != null || $am_departure != null || $pm_arrival != null || $pm_departure != null):
					?>
					<tr>
						<td><?php echo date('m-d-y', strtotime($day)); ?></td>
						<td><?php echo date('D', strtotime($day)); ?></td>
						<td class="am_arrival"><?php echo $am_arrival 		!= null ? date('h:i a', strtotime($am_arrival)) : noLog(); ?></td>
						<td class="am_departure"><?php echo $am_departure 	!= null ? date('h:i a', strtotime($am_departure)) : noLog(); ?></td>
						<td class="pm_arrival"><?php echo $pm_arrival 		!= null ? date('h:i a', strtotime($pm_arrival))  : noLog(); ?></td>
						<td class="pm_departure"><?php echo $pm_departure 	!= null ? date('h:i a', strtotime($pm_departure)) : noLog(); ?></td>
						<td class="overtime_in"><?php echo $ot_arrival 	!= null ? date('h:i a', strtotime($ot_arrival)) : noLog(); ?></td>
						<td class="overtime_out"><?php echo $ot_departure 	!= null ? date('h:i a', strtotime($ot_departure)) : noLog(); ?></td>
						<td class="remarks" style="text-transform: uppercase;"><?php echo $remarks 		!= null ? "<span class='text-success'>". strtoupper($remarks) . "</span>"	: "None"; ?></td>
						<td>
						<?php
							$button_data = "";
							foreach ($list as $k => $v) {
								$button_data .= !is_array($v) ? " data-" . $k . "='" . $v . "'" : "";
							}
							$button_data .= " data-transaction_date='" 			. $day ."'";
							$button_data .= " data-actual_am_arrival='" 		. $log['actual_am_arrival'] ."'";
							$button_data .= " data-actual_am_departure='" 		. $log['actual_am_departure'] ."'";
							$button_data .= " data-actual_pm_arrival='" 		. $log['actual_pm_arrival'] ."'";
							$button_data .= " data-actual_overtime_in='" 		. $log['actual_overtime_in'] ."'";
							$button_data .= " data-actual_overtime_out='" 		. $log['actual_overtime_out'] ."'";
							$button_data .= " data-actual_pm_departure='" 		. $log['actual_pm_departure'] ."'";
							$button_data .= " data-adjustment_am_arrival='" 	. $log['adjustment_am_arrival'] ."'";
							$button_data .= " data-adjustment_am_departure='"   . $log['adjustment_am_departure'] ."'";
							$button_data .= " data-adjustment_pm_arrival='" 	. $log['adjustment_pm_arrival'] ."'";
							$button_data .= " data-adjustment_pm_departure='"   . $log['adjustment_pm_departure'] ."'";
							$button_data .= " data-adjustment_overtime_in='" 	. $log['adjustment_overtime_in'] ."'";
							$button_data .= " data-adjustment_overtime_out='"   . $log['adjustment_overtime_out'] ."'";
							
							$button_data .= " data-dtr_am_arrival='" 	. $log['dtr_am_arrival'] ."'";
							$button_data .= " data-dtr_am_departure='" . $log['dtr_am_departure'] ."'";
							$button_data .= " data-dtr_pm_arrival='" 	. $log['dtr_pm_arrival'] ."'";
							$button_data .= " data-dtr_pm_departure='" . $log['dtr_pm_departure'] ."'";
							$button_data .= " data-dtr_overtime_in='" 	. $log['dtr_overtime_in'] ."'";
							$button_data .= " data-dtr_overtime_out='" . $log['dtr_overtime_out'] ."'";
							
							$button_data .= " data-dtr_adjustment_am_arrival='" 	. $log['dtr_adjustment_am_arrival'] ."'";
							$button_data .= " data-dtr_adjustment_am_departure='" . $log['dtr_adjustment_am_departure'] ."'";
							$button_data .= " data-dtr_adjustment_pm_arrival='" 	. $log['dtr_adjustment_pm_arrival'] ."'";
							$button_data .= " data-dtr_adjustment_pm_departure='" . $log['dtr_adjustment_pm_departure'] ."'";
							$button_data .= " data-dtr_adjustment_overtime_in='" 	. $log['dtr_adjustment_overtime_in'] ."'";
							$button_data .= " data-dtr_adjustment_overtime_out='" . $log['dtr_adjustment_overtime_out'] ."'";
							if (str_contains($log['remarks'], 'SPECIAL ORDER')) {
								$log_remarks = explode(" - ",$log['remarks']);
								$log['remarks'] = $log_remarks[0];
								$log['remarks_specific'] = $log_remarks[1];								
								$button_data .= " data-remarks='" . $log['remarks'] ."'";
								$button_data .= " data-remarks_specific='" . $log['remarks_specific'] ."'";
							}else{
								$button_data .= " data-remarks='" . $log['remarks'] ."'";
							}
							$disabled = "";
							if($log['remarks'] == "locator slip" || $log['remarks'] == "Defective")
								$disabled = "disabled";
						?>
							<?php //if(Helper::role(ModuleRels::DAILY_TIME_RECORD_MAINTENANCE_UPDATE_DETAILS)): ?>
							<a id="updateDailyTimeRecordMaintenanceForm" class="updateDailyTimeRecordMaintenanceForm"
								style="text-decoration: none;" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateDailyTimeRecordMaintenanceForm' ?>" <?php echo $button_data." ".$disabled; ?> >
								<button class="btn btn-info btn-circle waves-effect waves-circle waves-float" data- ="tooltip"
									data-placement="top" title="Correction" <?php echo $disabled; ?>>
									<i class="material-icons">playlist_add_check</i>
								</button>
							</a>
							<?php //endif; ?>
						</td>
					</tr>
						<?php 
						// endif; 
						endif; }
					else: ?>
					<div class="row clearfix" style="overflow: hidden !important">
						<div class="col-md-12 text-center">
							<br>
							<label class="text-primary" style="font-size:22px">
								<i class="material-icons" style="font-size:20px">error</i> No Record</label>
							<br>
						</div>
					</div>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php endif; ?>

<?php

	function displayAttendance($actual, $adjustment) {
		if($adjustment != null) {
			return $adjustment;
		} elseif($adjustment == null && $actual != null) {
			return $actual;
		} else return null;
	}

	function formatBytes($size, $precision = 2) {
		$base = log($size, 1024);
		$suffixes = array('Bytes', 'KB', 'MB', 'GB', 'TB');
		return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
	}

	function convertTime($dec, $type) {
		$seconds = ($dec * 3600);
		$hours = floor($dec);
		$seconds -= $hours * 3600;
		$minutes = floor($seconds / 60);
		$seconds -= $minutes * 60;
		switch ($type) {
			case 'h':
				return lz($hours);
			break;
			case 'i':
				return lz($minutes);
			break;
			case 's':
				return lz($seconds);
			break;
			default:
				return false;
			break;
		}
	}

	function lz($num) {
		return (strlen($num) < 2) ? "0{$num}" : $num;
	}

	function noLog() {
		return "<span class='text-danger'>No Log</span>";
	}
	function str_contains($haystack, $needle) {
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }

?>