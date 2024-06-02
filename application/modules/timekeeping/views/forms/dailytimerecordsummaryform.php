<style>
	.view-hidden {
		visibility: hidden;
	}

</style>

<div id="table-holder-summary">
	<div id="printable-table-holder-summary" class="border">
		<div class="table-responsive" style="width:100%; padding-bottom: 0">
			<table class="table" style="width:100%; font-size:10px !important; margin-bottom: 0">
				<thead style="background: #ddd">
					<tr>
						<th class="text-center header view-hidden" colspan="2">Payroll System</th>
					</tr>
					<tr>
						<th class="text-left header-2">Employee Name:</th>
						<th class="text-right header-2">Monthly Attendance Summary</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="text-left header summary_name_label" id="summary_name_label">No Data Available</td>
						<td class="text-right header summary_period_label" id="summary_period_label">No Data Available</td>
					</tr>
				</tbody>
			</table>
		</div>
		<!-- <div class='no-print text-left' style="margin: 10px 0 10px 0">
			<button type="button" class="btn bg-blue waves-effect" onclick="printSummary()">
				<i class="material-icons">print</i>
				<span>Print Report</span>
			</button>
		</div> -->
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
						<td colspan="3" style="background: #ddd; color: #777" class="text-left">Total Number of Days:
							<b>
								<?php echo $total['no_of_days']; ?>
							</b>
						</td>
						<td class="text-right" colspan="3" style="background: #ddd">Total Number in Hours:</td>
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
					<tr style="background: #ddd">
						<td colspan="6"></td>
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
				</tfoot>
			</table>
			<?php endif; ?>
		</div>
	</div>
</div>

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

	<script>
		function printSummary() {
			var style =
				"* { font-family: arial; text-align: center } .header { font-size: 22px; visibility: visible !important } .header-2 { font-size: 16px } .text-left { text-align: left } .text-right { text-align: right } .border table { border-collapse: collapse } .border td, .border th { border: 1px solid #ccc} @media print { .no-print, .no-print * { display: none !important; } }";
			var divToPrint = document.getElementById('printable-table-holder-summary');
			var newWin = window.open('', 'Print-Window');
			newWin.document.open();
			newWin.document.write(
				'<html><style>' + style + '</style><body onload="window.print()">' +
				divToPrint.innerHTML + '</body></html>');
			newWin.document.close();
			setTimeout(function () {
				newWin.close();
			}, 10);
		}

	</script>
