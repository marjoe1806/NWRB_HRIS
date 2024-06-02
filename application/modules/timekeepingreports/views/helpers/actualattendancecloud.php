<style>
	@media screen {
		.print-only {
			display: none;
		}
	}
</style>

<?php if($key == 'viewActualAttendanceCloud'): ?>
	<div class="table-responsive" style="width:100%; padding: 0; margin: 0;">
		<table class="table" style="font-size:10px !important; margin-bottom: 0">
			<thead style="background: #ddd">
				<?php if(isset($employee) && sizeof($employee) > 0): ?>
				<tr>
					<th class="text-left print-only" colspan="3" style="font-size: 10px !important;">NATIONAL WATER RESOURCES BOARD Actual Logs Form - Department/Unit -
						<?php if(isset($employee) && sizeof($employee) > 0) { echo isset($employee['department'][0]['code']) ? $employee['department'][0]['code'] : ''; } ?>
					</th>
				</tr>
				<tr>
					<th class="text-center print-only" rowspan="3" colspan="3" style="font-size: 14px">ACTUAL TIME RECORD</th>
				</tr>
				<tr>
					<th class="text-center print-only" colspan="3">&nbsp;</th>
				</tr>
				<tr>
					<th class="text-center print-only" colspan="3">&nbsp;</th>
				</tr>
				<tr class="no-print">
					<th class="text-center" colspan="3">
						<span style="text-transform: uppercase; font-size: 12px">
							<?php echo $employee['last_name'] . ', '.$employee['first_name'] . ' ' . ($employee['middle_name'] != null && $employee['middle_name'] == 'N/A' ? '' : $employee['middle_name']); ?></span>
					</th>
				</tr>
				<tr>
					<th class="text-center print-only" colspan="3">
						<span style="text-transform: uppercase; font-size: 12px">
							<?php echo $employee['last_name'] . ', '.$employee['first_name'] . ' ' . ($employee['middle_name'] != null && $employee['middle_name'] == 'N/A' ? '' : $employee['middle_name']); ?></span>
						<hr style="display: block; height: 1px; background: transparent; width: 100%; border: none; border-top: solid 1px #666; margin: 3px 0 3px 0">
						<span>Name</span>
					</th>
				</tr>
				<tr>
				</tr>
				<tr>
					<th class="text-left print-only" style="font-size: 11px !important; font-family: 'Times New Roman'; font-style: italic"
					 width="35%">For the month of</th>
					<th class="text-center print-only" style=" font-size: 12px !important; text-transform: uppercase; border-bottom: solid 1px #666;" colspan="2">
						<?php echo date('F ', strtotime($payroll_period[0])) . ' ' . date('j', strtotime($payroll_period[1])) . '-' . date('j', strtotime($payroll_period[2])) . ', ' . date(' Y', strtotime($payroll_period[0])) ?>
					</th>
				</tr>
				<?php endif; ?>
			</thead>
		</table>
	</div>
	<br class="print-only">
	<div class="table-responsive listTable border" style="width:100%;">
		<table id="datatables_details border" class="table table-hover table-striped border table-bordered" style="font-size:10px !important; margin-bottom: 0">
			<thead>
				<tr style="background: #ccc; vertical-align: middle; text-align: center;">
					<th class="text-center" style="vertical-align: middle; text-align: center; background: #fff" width="16%">Day</th>
					<th class="text-center" width="21%">TIME IN</th>
					<th class="text-center" width="21%">BREAK OUT</th>
					<th class="text-center" width="21%">BREAK IN</th>
					<th class="text-center" width="21%">TIME OUT</th>
				</tr>
			</thead>
			<tbody class="text-center">
				<?php
					// var_dump($list);die();
					if(isset($list) && sizeof($list) > 0):
						foreach($list as $k => $v) {
				?>
				<tr class="<?php echo isset($v['undertime_hours']) ? " text-danger": "0" ?>">
					<td class="fixed-height" width="16%">
						<?php echo  date('M d', strtotime($k)); ?>
					</td>
					<td width="21%" style="word-wrap:break-word"> <?php
									if(isset($v['time_in']) && sizeof($v['time_in']) > 0) {
										foreach ($v['time_in'] as $i => $time_in):
											echo $time_in['transaction_time'] . "<br>";
										endforeach;
									}
								?> </td>
					<td width="21%" style="word-wrap:break-word"> <?php
						if(isset($v['break_out']) && sizeof($v['break_out']) > 0) {
							foreach ($v['break_out'] as $i => $break_out):
								echo $break_out['transaction_time'] . "<br>";
							endforeach;
						}
					?> </td>
					<td width="21%" style="word-wrap:break-word"> <?php
						if(isset($v['break_in']) && sizeof($v['break_in']) > 0) {
							foreach ($v['break_in'] as $i => $break_in):
								echo $break_in['transaction_time'] . "<br>";
							endforeach;
						}
					?> </td>
					<td width="21%" style="word-wrap:break-word"> <?php
						if(isset($v['time_out']) && sizeof($v['time_out']) > 0) {
							foreach ($v['time_out'] as $i => $time_out):
								echo $time_out['transaction_time'] . "<br>";
							endforeach;
						}
					?> </td>
				</tr>
				<?php } endif; ?>
			</tbody>
		</table>
	</div>


<?php endif; ?>

<?php

	function checkTime($time, $type){
		$ante = $time != null && $time != '' ? date('a', strtotime($time)) : '';
		// echo $ante;
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
