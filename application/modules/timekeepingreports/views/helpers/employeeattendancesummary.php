<style>
	@media screen {
		.print-only {
			display: none;
		}
	}

</style>

<?php if($key == 'viewAttendanceSummaryAll'): ?>
<div id="table-holder-summary">
	<div class="col-md-12">
		<div class='no-print text-right' style="margin: 10px 0 10px 0">
			<button type="button" class="btn bg-blue waves-effect" id="printSummaryButton">
				<i class="material-icons">print</i>
				<span>Print Report</span>
			</button>
		</div>
	</div>
	<div id="printable-table-holder-summary" class="border">
		<?php foreach ($employee as $key => $details) { ?>
		<div style="page-break-after: always">
			<div class="table-responsive" style="width:100%; padding-bottom: 0">
				<table class="table hide-this" style="width:100%; font-size:10px !important; margin-bottom: 0">
					<thead style="background: #ddd">
						<tr>
							<th class="text-center header-2 print-only" colspan="2">NATIONAL WATER RESOURCES BOARD</th>
						</tr>
						<tr>
							<th class="text-center print-only" colspan="2">8th Floor, NIA Building, EDSA, Diliman. Quezon City</th>
						</tr>
						<tr>
							<th class="text-center print-only" colspan="2">&nbsp;</th>
						</tr>
						<tr>
							<th class="text-center print-only" colspan="2">&nbsp;</th>
						</tr>
						<tr>
							<th class="text-center header-2 print-only" colspan="2">Attendance Summary</th>
						</tr>
						<tr>
							<th class="text-center header print-only" colspan="2">&nbsp;</th>
						</tr>
						<tr>
							<th class="text-left">Employee Name:</th>
							<th class="text-right">Monthly Attendance Summary</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="text-left header-2 summary_name_label" id="summary_name_label">
								<?php echo (isset($details['last_name']) ? $details['last_name'] : 'No Data Available') . ', ' . (isset($details['first_name']) ? $details['first_name'] : 'No Data Available') . ' ' . (isset($details['middle_name']) ? $details['middle_name'] : 'No Data Available'); ?>
							</td>
							<td class="text-right summary_period_label" id="summary_period_label">No Data Available</td>
						</tr>
					</tbody>
				</table>
			</div>
			<br class="print-only">
			<div class="table-responsive listTable border" style="width:100%;">
				<table id="datatables_details border" class="table table-hover table-striped border" style="font-size:10px !important">
					<thead>
						<tr>
							<th colspan="2" class="text-center" style="background: #ddd; margin: 0; padding: 10px 0 10px 0">Transaction Date</th>
							<th colspan="2" class="text-center" style="background: #eee; margin: 0; padding: 10px 0 10px 0">Work Schedule</th>
							<th colspan="2" class="text-center" style="background: #ddd; margin: 0; padding: 10px 0 10px 0">Actual Log</th>
							<th colspan="8" class="text-center" style="background: #eee; margin: 0; padding: 10px 0 10px 0"></th>
							<th colspan="13" class="text-center" style="background: #ddd; margin: 0; padding: 10px 0 10px 0">Overtime Hours</th>
						</tr>
						<tr>
							<th class="text-center">Tran. Date</th>
							<th class="text-center">Tran. Day</th>
							<th class="text-center">Time In</th>
							<th class="text-center">Time Out</th>
							<th class="text-center">Time In</th>
							<th class="text-center">Time Out</th>
							<th class="text-center">Leave Hours</th>
							<th class="text-center">Late Hours</th>
							<th class="text-center">Undertime Hours</th>
							<th class="text-center">Regular Hours</th>
							<th class="text-center">R.N.Diff. Hours</th>
							<th class="text-center">Absent Hours</th>
							<th class="text-center">Break Deduction</th>
							<th class="text-center">Present Day</th>
							<th class="text-center">Regular O.T.</th>
							<th class="text-center">N.Diff. Hours</th>
							<th class="text-center">Rest Day</th>
							<th class="text-center">Legal O.T.</th>
							<th class="text-center">Legal R.D.</th>
							<th class="text-center">Special O.T.</th>
							<th class="text-center">Special R.D.</th>
							<!-- <th class="text-center">Excess Regular</th> -->
							<th class="text-center">Excess R.D.</th>
							<th class="text-center">Excess Legal</th>
							<th class="text-center">Exc.Legal R.D.</th>
							<th class="text-center">Excess Special</th>
							<th class="text-center">Exc.Special R.D.</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if(isset($list[$details['id']]['list']) && sizeof($list[$details['id']]['list']) > 0):
						foreach ($list[$details['id']]['list'] as $index => $value) { ?>
							<tr class="text-center">
								<td>
									<?php echo $value['transaction_date']?>
								</td>
								<td>
									<?php echo $value['transaction_day']?>
								</td>
								<td>
									<?php echo $value['official_time_in']?>
								</td>
								<td>
									<?php echo $value['official_time_out']?>
								</td>
								<td>
									<?php echo $value['time_in']?>
								</td>
								<td>
									<?php echo $value['time_out']?>
								</td>
								<td>
									<?php echo $value['leave_hours']?>
								</td>
								<td>
									<?php echo $value['late_hours']?>
								</td>
								<td>
									<?php echo $value['undertime_hours']?>
								</td>
								<td>
									<?php echo $value['regular_hours']?>
								</td>
								<td>
									<?php echo $value['regular_nightdiff_hours']?>
								</td>
								<td>
									<?php echo $value['absent_hours']?>
								</td>
								<td>
									<?php echo $value['break_deduction']?>
								</td>
								<td>
									<?php echo $value['present_day']?>
								</td>
								<td>
									<?php echo $value['regular_overtime']?>
								</td>
								<td>
									<?php echo $value['nightdiff_overtime']?>
								</td>
								<td>
									<?php echo $value['restday_overtime']?>
								</td>
								<td>
									<?php echo $value['legal_holiday_overtime']?>
								</td>
								<td>
									<?php echo $value['legal_holiday_restday_overtime']?>
								</td>
								<td>
									<?php echo $value['special_holiday_overtime']?>
								</td>
								<td>
									<?php echo $value['special_holiday_restday_overtime']?>
								</td>
								<!-- <td>
								<?php // echo $value['regular_excess_overtime']?>
							</td> -->
								<td>
									<?php echo $value['restday_excess_overtime']?>
								</td>
								<td>
									<?php echo $value['legal_excess_overtime']?>
								</td>
								<td>
									<?php echo $value['legal_excess_restday_overtime']?>
								</td>
								<td>
									<?php echo $value['special_excess_overtime']?>
								</td>
								<td>
									<?php echo $value['special_excess_restday_overtime']?>
								</td>
							</tr>
							<?php } endif; ?>
					</tbody>
					<?php if(isset($total[$details['id']])): ?>
					<tfoot style="background: #eee">
						<tr class="text-center text-primary">
							<td colspan="3" style="background: #eee" class="text-left">Total Number of Days:
								<b>
									<?php echo $total[$details['id']]['no_of_days']; ?>
								</b>
							</td>
							<td colspan="3" style="background: #ddd">Total Number in Hours:</td>
							<td>
								<b>
									<?php echo $total[$details['id']]['leave_hours']; ?>
								</b>
							</td>
							<td>
								<b>
									<?php echo $total[$details['id']]['late_hours']; ?>
								</b>
							</td>
							<td>
								<b>
									<?php echo $total[$details['id']]['undertime_hours']; ?>
								</b>
							</td>
							<td>
								<b>
									<?php echo $total[$details['id']]['regular_hours']; ?>
								</b>
							</td>
							<td>
								<b>
									<?php echo $total[$details['id']]['regular_nightdiff_hours']; ?>
								</b>
							</td>
							<td>
								<b>
									<?php echo $total[$details['id']]['absent_hours']; ?>
								</b>
							</td>
							<td>
								<b>
									<?php echo $total[$details['id']]['break_deduction']; ?>
								</b>
							</td>
							<td>
								<b>
									<?php echo $total[$details['id']]['present_day']; ?>
								</b>
							</td>
							<td>
								<b>
									<?php echo $total[$details['id']]['regular_overtime']; ?>
								</b>
							</td>
							<td>
								<b>
									<?php echo $total[$details['id']]['nightdiff_overtime']; ?>
								</b>
							</td>
							<td>
								<b>
									<?php echo $total[$details['id']]['restday_overtime']; ?>
								</b>
							</td>
							<td>
								<b>
									<?php echo $total[$details['id']]['legal_holiday_overtime']; ?>
								</b>
							</td>
							<td>
								<b>

									<?php echo $total[$details['id']]['legal_holiday_restday_overtime']; ?>
								</b>
							</td>
							<td>
								<b>
									<?php echo $total[$details['id']]['special_holiday_overtime']; ?>
								</b>
							</td>
							<td>
								<b>
									<?php echo $total[$details['id']]['special_holiday_restday_overtime']; ?>
								</b>
							</td>
							<!-- <td>
								<b>
									<?php // echo $total[$details['id']]['regular_excess_overtime']; ?>
								</b>
							</td> -->
							<td>
								<b>
									<?php echo $total[$details['id']]['restday_excess_overtime']; ?>
								</b>
							</td>
							<td>
								<b>
									<?php echo $total[$details['id']]['legal_excess_overtime']; ?>
								</b>
							</td>
							<td>
								<b>
									<?php echo $total[$details['id']]['legal_excess_restday_overtime']; ?>
								</b>
							</td>
							<td>
								<b>
									<?php echo $total[$details['id']]['special_excess_overtime']; ?>
								</b>
							</td>
							<td>
								<b>
									<?php echo $total[$details['id']]['special_excess_restday_overtime']; ?>
								</b>
							</td>
						</tr>
					</tfoot>
				</table>
				<br class="print-only">
				<div class="row clearfix print-only">
					<div class="col-md-6">
						<div class="table-responsive border" style="width:100%; padding-bottom: 0">
							<table class="table table-hover table-striped border" style="font-size:10px !important; margin-bottom: 0">
								<thead style="background: #ddd">
									<tr>
										<th class="text-left">Summary</th>
										<th class="text-right">Hours</th>
										<th class="text-right">Minutes</th>
										<th class="text-right">Days</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="text-left">Total Overtime</td>
										<td class="text-right">
											<?php echo convertTime($total[$details['id']]['regular_overtime'], 'h'); ?>
										</td>
										<td class="text-right">
											<?php echo convertTime($total[$details['id']]['regular_overtime'], 'i'); ?>
										</td>
										<td class="text-right">
										</td>
									</tr>
									<tr>
										<td class="text-left">Total Late</td>
										<td class="text-right">
											<?php echo convertTime($total[$details['id']]['late_hours'], 'h'); ?>
										</td>
										<td class="text-right">
											<?php echo convertTime($total[$details['id']]['late_hours'], 'i'); ?>
										</td>
										<td class="text-right">
										</td>
									</tr>
									<tr>
										<td class="text-left">Total Undertime</td>
										<td class="text-right">
											<?php echo convertTime($total[$details['id']]['undertime_hours'], 'h'); ?>
										</td>
										<td class="text-right">
											<?php echo convertTime($total[$details['id']]['undertime_hours'], 'i'); ?>
										</td>
										<td class="text-right">
										</td>
									</tr>
									<tr>
										<td class="text-left">Total Absences</td>
										<td class="text-right">
										</td>
										<td class="text-right">
										</td>
										<td class="text-right">
											<?php echo lz(convertTime($total[$details['id']]['absent_hours'], 'h') / 8 ); ?>
										</td>
									</tr>
								</tbody>
								<?php endif; ?>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<br class="no-print">
		<?php } ?>
	</div>
</div>

<?php endif; ?>

<?php
if($key == 'viewAttendanceSummary'): ?>
<div id="table-holder-summary">
	<div id="printable-table-holder-summary" class="border">
		<div class="table-responsive" style="width:100%; padding-bottom: 0">
			<table class="table hide-this" style="width:100%; font-size:10px !important; margin-bottom: 0">
				<thead style="background: #ddd">
					<tr>
						<th class="text-center header-2 print-only" colspan="2">METRO MANILA DEVELOPMENT AUTHORITY</th>
					</tr>
					<tr>
						<th class="text-center print-only" colspan="2">Epifanio De Los Santos Corner EDSA, Guadalupe Nuevo, Makati, 1212 Metro Manila</th>
					</tr>
					<tr>
						<th class="text-center print-only" colspan="2">&nbsp;</th>
					</tr>
					<tr>
						<th class="text-center print-only" colspan="2">&nbsp;</th>
					</tr>
					<tr>
						<th class="text-center header-2 print-only" colspan="2">Attendance Summary</th>
					</tr>
					<tr>
						<th class="text-center header print-only" colspan="2">&nbsp;</th>
					</tr>
					<tr>
						<th class="text-left">Employee Name:</th>
						<th class="text-right">Monthly Attendance Summary</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="text-left header-2 summary_name_label" id="summary_name_label">
							<?php echo (isset($value['last_name']) ? $value['last_name'] : 'No Data Available') . ', ' . (isset($value['first_name']) ? $value['first_name'] : 'No Data Available') . ' ' . (isset($value['middle_name']) ? $value['middle_name'] : 'No Data Available'); ?>
						</td>
						<td class="text-right summary_period_label" id="summary_period_label">No Data Available</td>
					</tr>
				</tbody>
			</table>
		</div>
		<br class="print-only">
		<div class="table-responsive listTable border" style="width:100%;">
			<table id="datatables_details border" class="table table-hover table-striped border" style="font-size:10px !important">
				<thead>
					<tr>
						<th colspan="2" class="text-center" style="background: #ddd; margin: 0; padding: 10px 0 10px 0">Transaction Date</th>
						<th colspan="2" class="text-center" style="background: #eee; margin: 0; padding: 10px 0 10px 0">Work Schedule</th>
						<th colspan="2" class="text-center" style="background: #ddd; margin: 0; padding: 10px 0 10px 0">Actual Log</th>
						<th colspan="8" class="text-center" style="background: #eee; margin: 0; padding: 10px 0 10px 0"></th>
						<th colspan="13" class="text-center" style="background: #ddd; margin: 0; padding: 10px 0 10px 0">Overtime Hours</th>
					</tr>
					<tr>
						<th class="text-center">Tran. Date</th>
						<th class="text-center">Tran. Day</th>
						<th class="text-center">Time In</th>
						<th class="text-center">Time Out</th>
						<th class="text-center">Time In</th>
						<th class="text-center">Time Out</th>
						<th class="text-center">Leave Hours</th>
						<th class="text-center">Late Hours</th>
						<th class="text-center">Undertime Hours</th>
						<th class="text-center">Regular Hours</th>
						<th class="text-center">R.N.Diff. Hours</th>
						<th class="text-center">Absent Hours</th>
						<th class="text-center">Break Deduction</th>
						<th class="text-center">Present Day</th>
						<th class="text-center">Regular O.T.</th>
						<th class="text-center">N.Diff. Hours</th>
						<th class="text-center">Rest Day</th>
						<th class="text-center">Legal O.T.</th>
						<th class="text-center">Legal R.D.</th>
						<th class="text-center">Special O.T.</th>
						<th class="text-center">Special R.D.</th>
						<!-- <th class="text-center">Excess Regular</th> -->
						<th class="text-center">Excess R.D.</th>
						<th class="text-center">Excess Legal</th>
						<th class="text-center">Exc.Legal R.D.</th>
						<th class="text-center">Excess Special</th>
						<th class="text-center">Exc.Special R.D.</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if(isset($list) && sizeof($list) > 0):
					foreach ($list as $index => $value) { ?>
					<tr class="text-center">
						<td>
							<?php echo $value['transaction_date']?>
						</td>
						<td>
							<?php echo $value['transaction_day']?>
						</td>
						<td>
							<?php echo $value['official_time_in']?>
						</td>
						<td>
							<?php echo $value['official_time_out']?>
						</td>
						<td>
							<?php echo $value['time_in']?>
						</td>
						<td>
							<?php echo $value['time_out']?>
						</td>
						<td>
							<?php echo $value['leave_hours']?>
						</td>
						<td>
							<?php echo $value['late_hours']?>
						</td>
						<td>
							<?php echo $value['undertime_hours']?>
						</td>
						<td>
							<?php echo $value['regular_hours']?>
						</td>
						<td>
							<?php echo $value['regular_nightdiff_hours']?>
						</td>
						<td>
							<?php echo $value['absent_hours']?>
						</td>
						<td>
							<?php echo $value['break_deduction']?>
						</td>
						<td>
							<?php echo $value['present_day']?>
						</td>
						<td>
							<?php echo $value['regular_overtime']?>
						</td>
						<td>
							<?php echo $value['nightdiff_overtime']?>
						</td>
						<td>
							<?php echo $value['restday_overtime']?>
						</td>
						<td>
							<?php echo $value['legal_holiday_overtime']?>
						</td>
						<td>
							<?php echo $value['legal_holiday_restday_overtime']?>
						</td>
						<td>
							<?php echo $value['special_holiday_overtime']?>
						</td>
						<td>
							<?php echo $value['special_holiday_restday_overtime']?>
						</td>
						<!-- <td>
							<?php // echo $value['regular_excess_overtime']?>
						</td> -->
						<td>
							<?php echo $value['restday_excess_overtime']?>
						</td>
						<td>
							<?php echo $value['legal_excess_overtime']?>
						</td>
						<td>
							<?php echo $value['legal_excess_restday_overtime']?>
						</td>
						<td>
							<?php echo $value['special_excess_overtime']?>
						</td>
						<td>
							<?php echo $value['special_excess_restday_overtime']?>
						</td>
					</tr>
					<?php } endif; ?>
				</tbody>
				<?php if(isset($total)): ?>
				<tfoot style="background: #eee">
					<tr class="text-center text-primary">
						<td colspan="3" style="background: #eee" class="text-left">Total Number of Days:
							<b>
								<?php echo $total['no_of_days']; ?>
							</b>
						</td>
						<td colspan="3" style="background: #ddd">Total Number in Hours:</td>
						<td>
							<b>
								<?php echo $total['leave_hours']; ?>
							</b>
						</td>
						<td>
							<b>
								<?php echo $total['late_hours']; ?>
							</b>
						</td>
						<td>
							<b>
								<?php echo $total['undertime_hours']; ?>
							</b>
						</td>
						<td>
							<b>
								<?php echo $total['regular_hours']; ?>
							</b>
						</td>
						<td>
							<b>
								<?php echo $total['regular_nightdiff_hours']; ?>
							</b>
						</td>
						<td>
							<b>
								<?php echo $total['absent_hours']; ?>
							</b>
						</td>
						<td>
							<b>
								<?php echo $total['break_deduction']; ?>
							</b>
						</td>
						<td>
							<b>
								<?php echo $total['present_day']; ?>
							</b>
						</td>
						<td>
							<b>
								<?php echo $total['regular_overtime']; ?>
							</b>
						</td>
						<td>
							<b>
								<?php echo $total['nightdiff_overtime']; ?>
							</b>
						</td>
						<td>
							<b>
								<?php echo $total['restday_overtime']; ?>
							</b>
						</td>
						<td>
							<b>
								<?php echo $total['legal_holiday_overtime']; ?>
							</b>
						</td>
						<td>
							<b>

								<?php echo $total['legal_holiday_restday_overtime']; ?>
							</b>
						</td>
						<td>
							<b>
								<?php echo $total['special_holiday_overtime']; ?>
							</b>
						</td>
						<td>
							<b>
								<?php echo $total['special_holiday_restday_overtime']; ?>
							</b>
						</td>
						<!-- <td>
							<b>
								<?php // echo $total['regular_excess_overtime']; ?>
							</b>
						</td> -->
						<td>
							<b>
								<?php echo $total['restday_excess_overtime']; ?>
							</b>
						</td>
						<td>
							<b>
								<?php echo $total['legal_excess_overtime']; ?>
							</b>
						</td>
						<td>
							<b>
								<?php echo $total['legal_excess_restday_overtime']; ?>
							</b>
						</td>
						<td>
							<b>
								<?php echo $total['special_excess_overtime']; ?>
							</b>
						</td>
						<td>
							<b>
								<?php echo $total['special_excess_restday_overtime']; ?>
							</b>
						</td>
					</tr>
				</tfoot>
			</table>
			<div class="col-md-12">
				<div class='no-print text-right' style="margin: 10px 0 10px 0">
					<button type="button" class="btn bg-blue waves-effect" id="printSummaryButton">
						<i class="material-icons">print</i>
						<span>Print Report</span>
					</button>
				</div>
			</div>
			<br class="print-only">
			<div class="row clearfix print-only">
				<div class="col-md-6">
					<div class="table-responsive border" style="width:100%; padding-bottom: 0">
						<table class="table table-hover table-striped border" style="font-size:10px !important; margin-bottom: 0">
							<thead style="background: #ddd">
								<tr>
									<th class="text-left">Summary</th>
									<th class="text-right">Hours</th>
									<th class="text-right">Minutes</th>
									<th class="text-right">Days</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="text-left">Total Overtime</td>
									<td class="text-right">
										<?php echo convertTime($total['regular_overtime'], 'h'); ?>
									</td>
									<td class="text-right">
										<?php echo convertTime($total['regular_overtime'], 'i'); ?>
									</td>
									<td class="text-right">
									</td>
								</tr>
								<tr>
									<td class="text-left">Total Late</td>
									<td class="text-right">
										<?php echo convertTime($total['late_hours'], 'h'); ?>
									</td>
									<td class="text-right">
										<?php echo convertTime($total['late_hours'], 'i'); ?>
									</td>
									<td class="text-right">
									</td>
								</tr>
								<tr>
									<td class="text-left">Total Undertime</td>
									<td class="text-right">
										<?php echo convertTime($total['undertime_hours'], 'h'); ?>
									</td>
									<td class="text-right">
										<?php echo convertTime($total['undertime_hours'], 'i'); ?>
									</td>
									<td class="text-right">
									</td>
								</tr>
								<tr>
									<td class="text-left">Total Absences</td>
									<td class="text-right">
									</td>
									<td class="text-right">
									</td>
									<td class="text-right">
										<?php echo lz(convertTime($total['absent_hours'], 'h') / 8 ); ?>
									</td>
								</tr>
							</tbody>
							<?php endif; ?>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<?php

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

?>
