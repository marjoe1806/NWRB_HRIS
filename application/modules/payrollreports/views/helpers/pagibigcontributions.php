
<style>
	@media screen {
		.print-only {
			display: none;
		}
	}

</style>

<?php if($key == 'viewPAGIBIGContributionsAll'): ?>
<div id="table-holder-summary">
	<div id="printable-table-holder-summary" class="border">
		<div style="page-break-after: always">
			<div class="table-responsive" style="width:100%; padding-bottom: 0">
				<table class="table" style="width:100%; font-size:10px !important; margin-bottom: 0">
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
							<th class="text-center header-2 print-only" colspan="2">Summary of Monthly Pagibig Fund Contributions</th>
						</tr>
						<tr>
							<th class="text-center print-only" colspan="2">For the month of <?php if(isset($payroll_period)) { echo date('F, Y', strtotime($payroll_period)); } ?></th>
						</tr>
					</thead>
				</table>
			</div>
			<br class="print-only">
			<div class="table-responsive listTable border" style="width:100%;">
				<table id="datatables_details border" class="table table-hover table-striped border" style="width:100%; font-size:10px !important; margin-bottom: 0">
					<thead>
						<tr style="background: #ddd">
							<th class="text-center">Employee Number</th>
							<th class="text-center">Employee Name</th>
							<th class="text-center">Pagibig Number</th>
							<th class="text-center">Employee Share</th>
							<th class="text-center">Employer Share</th>
							<th class="text-center">Total</th>
						</tr>
					</thead>
					<tbody class="text-center">
						<?php if(isset($list) && sizeof($list) > 0) :
						$totalemployeeshare = 0;
						$totalemployershare = 0;
						$totalecc = 0;
						$grandtotal = 0;
						foreach ($list as $k => $row) {
							if($row['pagibig_amt'] != "0.00") :
						?>
						<tr>
							<td>
								<?php echo $row['employee_id_number']; ?>
							</td>
							<td>
								<?php echo $row['last_name'] . ', ' . $row['first_name'] . ' ' . $row['middle_name']?>
							</td>
							<td>
								<?php echo clean($row['pagibig']); ?>
							</td>
							<td>
								<?php echo number_format((float)abs($row['pagibig_amt']), 2, '.', ',') ; ?>
							</td>
							<td>
								<?php echo number_format((float)abs($row['pagibig_amt_employer']), 2, '.', ',') ; ?>
							</td>
							<td>
								<?php echo number_format((float)abs($row['pagibig_amt'] + $row['pagibig_amt_employer']), 2, '.', ',') ; ?>
							</td>
						</tr>
						<?php
							$totalemployeeshare += $row['pagibig_amt'];
							$totalemployershare += $row['pagibig_amt_employer'];
							$grandtotal += $row['pagibig_amt'] + $row['pagibig_amt_employer'];
							endif; } endif; ?>
						<tr style="background: #eee">
							<td>
								<b>Grand Total</b>
							</td>
							<td><b></b></td>
							<td><b></b></td>
							<td>
								<b><?php echo number_format((float)abs($totalemployeeshare), 2, '.', ',') ; ?></b>
							</td>
							<td>
								<b><?php echo number_format((float)abs($totalemployershare), 2, '.', ',') ; ?></b>
							</td>
							<td>
								<b><?php echo number_format((float)abs($grandtotal), 2, '.', ',') ; ?></b>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<br class="print-only">
			<div class="table-responsive" style="width:100%; padding-bottom: 0">
				<div class="col-md-12">
					<div class='no-print text-right' style="margin: 10px 0 10px 0">
						<button type="button" class="btn bg-blue waves-effect" id="printSummaryButton">
							<i class="material-icons">print</i>
							<span>Print Report</span>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<?php

	function clean($string) {
		$string = str_replace(' ', '-', $string);

		return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
 }

?>
