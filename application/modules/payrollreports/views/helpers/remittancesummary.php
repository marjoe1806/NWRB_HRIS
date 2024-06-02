<style>
	@media screen {
		.print-only {
			display: none;
		}
	}
</style>

<?php if($key == 'viewRemittanceSummaryAll'): ?>
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
		<?php foreach ($list as $key => $value) { ?>
		<div style="page-break-after: always">
			<div class="table-responsive" style="width:100%; padding-bottom: 0">
				<table class="table" style="font-size:10px !important; margin-bottom: 0">
					<thead style="background: #ddd">
						<?php if(isset($list) && sizeof($list) > 0): ?>
						<tr>
							<th class="text-left print-only" colspan="3" style="font-size: 10px !important;">Civil Service Form No. 48
								&nbsp;&nbsp;&nbsp; Department/Unit -
								<?php if(isset($value['employee'][0]) && sizeof($value['employee'][0]) > 0) { echo isset($value['employee'][0]['department'][0]['department_name']) ? $value['employee'][0]['department'][0]['department_name'] : ''; } ?>
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
							<th class="text-center no-print" colspan="3" style="background: #fff">
								<span style="text-transform: uppercase; font-size: 12px">
									<?php echo $value['employee'][0]['last_name'] . ', '.$value['employee'][0]['first_name'] . ' ' . $value['employee'][0]['middle_name']; ?></span>
							</th>
						</tr>
						<tr>
							<th class="text-center print-only" colspan="3">
								<span style="text-transform: uppercase; font-size: 12px">
									<?php echo $value['employee'][0]['last_name'] . ', '.$value['employee'][0]['first_name'] . ' ' . $value['employee'][0]['middle_name']; ?></span>
								<hr style="display: block; height: 1px; background: transparent; width: 100%; border: none; border-top: solid 1px #666; margin: 3px 0 3px 0">
								<span>Name</span>
							</th>
						</tr>
						<tr>
						</tr>
						<tr>
							<th class="text-left print-only" style="font-size: 11px !important; font-family: 'Times New Roman'; font-style: italic"
							 width="35%">For the month of</th>
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
							<th class="text-right print-only" style="font-size: 11px !important; border-bottom: 1px dotted #333;" width="10%">&nbsp;</th>
						</tr>
						<?php endif; ?>
					</thead>
				</table>
			</div>
			<br class="print-only">
			<div class="table-responsive listTable border" style="width:100%;">
				<table id="datatables_details border" class="table table-hover table-striped border table-bordered" style="font-size:10px !important; margin-bottom: 0">
					<thead>
						<tr style="background: #eee; vertical-align: middle; text-align: center;">
							<th class="text-center print-only" style="vertical-align: middle; text-align: center;" colspan="9"></th>
						</tr>
						<tr style="background: #ccc; vertical-align: middle; text-align: center;">
							<th class="text-center" style="vertical-align: middle; text-align: center; background: #fff" rowspan="2" width="5%">Day</th>
							<th class="text-center" colspan="2">A. M.</th>
							<th width="1%" class="print-only"></th>
							<th class="text-center" colspan="2">P. M.</th>
							<th width="1%" class="print-only"></th>
							<th class="text-center" colspan="2">UNDERTIME</th>
						</tr>
						<tr style="background: #eee">
							<th class="text-center" width="15%">Arrival</th>
							<th class="text-center" width="15%">Departure</th>
							<th class="print-only"></th>
							<th class="text-center" width="15%">Arrival</th>
							<th class="text-center" width="15%">Departure</th>
							<th class="print-only"></th>
							<th class="text-center" width="15%">Hours</th>
							<th class="text-center" width="15%">Minutes</th>
						</tr>
					</thead>
					<tbody class="text-center">
						<?php if(isset($list[$key]['records']) && sizeof($list[$key]['records']) > 0): foreach($list[$key]['records'] as $k => $v) { ?>
						<tr class="<?php echo isset($v['undertime_hours']) ? " text-danger": "0" ?>">
							<td>
								<?php echo date('j', strtotime($v['transaction_date'])); ?>
							</td>
							<td>
								<?php echo isset($v['time_in']) && $v['time_in'] != null ? date('G:i:s', strtotime($v['time_in'])) : ''; ?>
							</td>
							<td>
								<?php echo isset($v['break_out']) && $v['break_out'] != null ? date('G:i:s', strtotime($v['break_out'])) < date('G:i:s', strtotime($v['time_in'])) ? date('G:i:s', strtotime($v['break_out'])) : date('G:i:s', strtotime($v['break_out'])) : ''; ?>
							</td>
							<td class="print-only"></td>
							<td>
								<?php echo isset($v['break_in']) && $v['break_in'] != null ? date('G:i:s', strtotime($v['break_in'])) < date('G:i:s', strtotime($v['time_in'])) ? date('G:i:s', strtotime($v['break_in'])) : date('G:i:s', strtotime($v['break_in'])) : ''; ?>
							</td>
							<td>
								<?php echo isset($v['time_out']) && $v['time_out'] != null ? date('G:i:s', strtotime($v['time_out'])) < date('G:i:s', strtotime($v['time_in'])) ? date('G:i:s', strtotime($v['time_out'])) : date('G:i:s', strtotime($v['time_out'])) : ''; ?>
							</td>
							<td class="print-only"></td>
							<td>
								<?php echo isset($v['undertime_hours']) ? convertTime($v['undertime_hours'], 'h') : "" ?>
							</td>
							<td>
								<?php echo isset($v['undertime_hours']) ? convertTime($v['undertime_hours'], 'i') : "" ?>
							</td>
						</tr>
						<?php }
						endif; ?>
						<tr>
							<td class="print-only" style="border-left: 0px solid; border-right: 0px solid;" colspan="13">&nbsp;</td>
						</tr>
						<tr class="<?php echo isset($v['undertime_hours']) ? " text-danger": "0" ?>" style="background: #ccc">
							<td style="font-weight: bold; border-left: 0px solid; border-right: 0px solid; border-bottom: 1px dotted #333 !important;">
								Total
							</td>
							<td class="no-print" colspan="4"></td>
							<td class="print-only" colspan="5" style="font-weight: bold; border-left: 0px solid; border-right: 0px solid; border-bottom: 1px dotted #333 !important;"></td>
							<td class="print-only" style="font-weight: bold; border-bottom: 1px dotted #333 !important;"></td>
							<td class="text-danger" style="font-weight: bold; border-bottom: 1px dotted #333 !important;">
								<?php echo isset($value['employee'][0]['total_undertime']) ? convertTime($value['employee'][0]['total_undertime'], 'h') : "" ?>
							</td>
							<td class="text-danger" style="font-weight: bold; border-left: 0px solid; border-right: 0px solid; border-bottom: 1px dotted #333 !important;">
								<?php echo isset($value['employee'][0]['total_undertime']) ? convertTime($value['employee'][0]['total_undertime'], 'i') : "" ?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<br class="print-only">
			<div class="table-responsive" style="width:100%; padding-bottom: 0">
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
							<th class="text-left print-only" style="font-size:10px !important">Station:
								<span style="font-weight: bold; font-size: 11px">
									<?php if(isset($value['employee'][0]) && sizeof($value['employee'][0]) > 0) { echo isset($value['employee'][0]['location']['name']) ? $value['employee'][0]['location']['name'] : ''; } ?>
								</span>
							</th>
							<th class="text-center print-only" colspan="2" width="50%" style="font-size:10px !important">In Charge</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
		<br class="no-print">
		<?php } ?>
	</div>
</div>
<?php endif; ?>

<?php if($key == 'viewRemittanceSummary'): ?>
<div id="printable-table-holder-summary" class="border">
	<div class="row">
		<div class="col-md-12">
		</div>
	</div>
	<div style="page-break-after: always">
		<div class="table-responsive" style="width:100%; padding-bottom: 0">
			<div class="table-responsive" style="width:100%; padding-bottom: 0">
				<table class="table" style="width:100%; font-size:12px !important; margin-bottom: 0">
					<thead style="background: #ddd">
						<tr>
							<th class="text-center header-2 print-only" colspan="2">NATIONAL WATER RESOURCES BOARD</th>
						</tr>
						<tr>
							<th class="text-center print-only" colspan="2" style="font-size:11px !important">Statement of
								Remittances/Deductions</th>
						</tr>
						<tr>
							<th class="text-center print-only" colspan="2" style="font-size:11px !important">
								<?php echo @$remittance_type; ?> of
								<?php echo @$pay_basis; ?>
							</th>
						</tr>
						<tr>
							<th class="text-center print-only" colspan="2">&nbsp;</th>
						</tr>
						<!-- <tr>
							<th class="text-center header-2 print-only" colspan="2">Division here</th>
						</tr> -->
						<tr>
							<th class="text-center print-only" colspan="2" style="font-size:11px !important">From
								<?php echo date('F 01, Y', strtotime(@$data['payroll_period'])) ?> to
								<?php echo date('F t, Y', strtotime(@$data['payroll_period'])) ?>
							</th>
						</tr>
						<tr>
							<th class="text-center print-only" colspan="2">&nbsp;</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
		<br class="print-only">
		<div class="table-responsive listTable" style="width:100%;">
			<table id="datatables_details" class="table table-hover table-striped table-bordered" style="width:100%; font-size:10px !important; margin-bottom: 0">
				<thead>
					<tr style="background: #ddd">
						<th class="text-center" width="5%">NO.</th>
						<th class="text-center" width="45%">NAME OF EMPLOYEE</th>
						<th class="text-center" width="30%">POSITION</th>
						<th class="text-right" width="20%">AMOUNT</th>
					</tr>
				</thead>
				<tbody class="text-center">
					<tr class="print-only">
						<td colspan="4">
							<hr style="border: 1px #666 solid">
						</td>
					</tr>
					<?php
					$total = 0;
					if(isset($payroll) && sizeof($payroll) > 0) :
							foreach ($payroll as $k => $v) { ?>
					<tr>
						<td><?php echo $k + 1 ?></td>
						<td>
							<?php
							$first_name = $this->Helper->decrypt($v['first_name'],$v['employee_id']);
							$last_name = $this->Helper->decrypt($v['last_name'],$v['employee_id']);
							$middle_name = $this->Helper->decrypt($v['middle_name'],$v['employee_id']);
							echo $first_name . ' ' . $middle_name . ' ' . $last_name;
							?>
						</td>
						<td>
								<?php echo @$v['position_name']; ?>
						</td>
						<td style="text-align:right;">
							<?php
								if($remittance_type == "Personal Economic Relief Allowance (PERA)"){
									echo number_format((double)@$v['pera_amt'],2);
									$total += $v['pera_amt'];
								}
								else if($remittance_type == "SAKAMAY"){
									$loan = "0.00";
									if(sizeof($loanDeductions[$v['employee_id']]) > 0){
											foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
													if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "SAKAMAY"){
															$loan = number_format($vl1['amount'],2);
															break;
													}
													else
															$loan = "0.00";

											}
									}
									echo $loan;
									$total += $loan;
								}
								else if($remittance_type == "Under Payment") {
									$total += $v['utime_amt'];
									echo number_format((double)@$v['utime_amt'],2);
								}
								else if($remittance_type == "Over Payment") {
									$total += $v['total_ot_amt'];
									echo number_format((double)@$v['total_ot_amt'],2);
								}
								else if($remittance_type == "Gross Pay") {
									$total += $v['gross_pay'];
									echo number_format((double)@$v['gross_pay'],2);
								}
								else if($remittance_type == "Net Pay") {
									$total += $v['net_pay'];
									echo number_format((double)@$v['net_pay'],2);
								}
								else if($remittance_type == "Philhealth") {
									$total += $v['philhealth_amt'];
									echo number_format((double)@$v['philhealth_amt'],2);
								}
								else if($remittance_type == "Provident Fund P Share") {
									$total +=0;
									echo number_format((double)0,2);
								}
								else if($remittance_type == "Total Deduction") {
									$total += $v['total_deduct_amt'];
									echo number_format((double)@$v['total_deduct_amt'],2);
								}
								else if($remittance_type == "Employees' Compensation Commission (E.C.C.)") {
									$total += $v['acpcea_amt'];
									echo number_format((double)@$v['acpcea_amt'],2);
								}
								else if($remittance_type == "GSIS Life and Retirement P Share") {
									$total += $v['sss_gsis_amt'];
									echo number_format((double)@$v['sss_gsis_amt'],2);
								}
								else if($remittance_type == "GSIS Life and Retirement G Share") {
									$total += $v['sss_gsis_amt_employer'];
									echo number_format((double)@$v['sss_gsis_amt_employer'],2);
								}
								else if($remittance_type == "Withholding Tax") {
									$total += $v['wh_tax_amt'];
									echo number_format((double)@$v['wh_tax_amt'],2);
								}
								else if($remittance_type == "Absence Without Pay") {
									$total += $v['abst_amt'];
									echo number_format((double)@$v['abst_amt'],2);
								}
								else if($remittance_type == "Other Loans") {
									$loan = "0.00";
									if(sizeof($loanDeductions[$v['employee_id']]) > 0){
											foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
													if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "Other Loans"){
															$loan = number_format($vl1['amount'],2);
															break;
													}
													else
															$loan = "0.00";

											}
									}
									echo $loan;
									$total += $loan;
								}
								else if($remittance_type == "Union Dues") {
									$loan = "0.00";
									if(sizeof($loanDeductions[$v['employee_id']]) > 0){
											foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
													if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "UNION DUES"){
															$loan = number_format($vl1['amount'],2);
															break;
													}
													else
															$loan = "0.00";

											}
									}
									echo $loan;
									$total += $loan;
								}
								else if($remittance_type == "GSIS Housing Loan") {
									$loan = "0.00";
									if(sizeof($loanDeductions[$v['employee_id']]) > 0){
											foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
													if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "Housing"){
															$loan = number_format($vl1['amount'],2);
															break;
													}
													else
															$loan = "0.00";

											}
									}
									echo $loan;
									$total += $loan;
								}
								else if($remittance_type == "GSIS Optional Loan") {
									$loan = "0.00";
									if(sizeof($loanDeductions[$v['employee_id']]) > 0){
											foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
													if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "Optional Loan"){
															$loan = number_format($vl1['amount'],2);
															break;
													}
													else
															$loan = "0.00";

											}
									}
									echo $loan;
									$total += $loan;
								}
								else if($remittance_type == "Public Safety Mutual Benefit Fund") {
									$loan = "0.00";
									if(sizeof($loanDeductions[$v['employee_id']]) > 0){
											foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
													if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "PSMBFund"){
															$loan = number_format($vl1['amount'],2);
															break;
													}
													else
															$loan = "0.00";

											}
									}
									echo $loan;
									$total += $loan;
								}
								else if($remittance_type == "GSIS Optional Ins.") {
									$loan = "0.00";
									if(sizeof($loanDeductions[$v['employee_id']]) > 0){
											foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
													if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "Optional Ins."){
															$loan = number_format($vl1['amount'],2);
															break;
													}
													else
															$loan = "0.00";

											}
									}
									echo $loan;
									$total += $loan;
								}
								else if($remittance_type == "GSIS Policy Loan") {
									$loan = "0.00";
									if(sizeof($loanDeductions[$v['employee_id']]) > 0){
											foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
													if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "Policy"){
															$loan = number_format($vl1['amount'],2);
															break;
													}
													else
															$loan = "0.00";

											}
									}
									echo $loan;
									$total += $loan;
								}
								else if($remittance_type == "KOOP") {
									$loan = "0.00";
									if(sizeof($loanDeductions[$v['employee_id']]) > 0){
											foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
													if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "KOOP LOAN"){
															$loan = number_format($vl1['amount'],2);
															break;
													}
													else
															$loan = "0.00";

											}
									}
									echo $loan;
									$total += $loan;
								}
								else if($remittance_type == "S.D.O. Cash Advance") {
									$loan = "0.00";
									if(sizeof($loanDeductions[$v['employee_id']]) > 0){
											foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
													if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "S.D.O. CA"){
															$loan = number_format($vl1['amount'],2);
															break;
													}
													else
															$loan = "0.00";

											}
									}
									echo $loan;
									$total += $loan;
								}
								else if($remittance_type == "GSIS Enhanced Salary") {
									$loan = "0.00";
									if(sizeof($loanDeductions[$v['employee_id']]) > 0){
											foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
													if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "Enhanced"){
															$loan = number_format($vl1['amount'],2);
															break;
													}
													else
															$loan = "0.00";

											}
									}
									echo $loan;
									$total += $loan;
								}
								else if($remittance_type == "Pagibig Fund P Share") {
									$total += $v['sss_gsis_amt'];
									echo number_format((double)@$v['pagibig_amt'],2);
								}
								else if($remittance_type == "Pagibig Fund G Share") {
									$total += $v['sss_gsis_amt_employer'];
									echo number_format((double)@$v['pagibig_amt_employer'],2);
								}
								else if($remittance_type == "Grocery") {
									$allowance = "0.00";
									if(sizeof($allowances[$v['employee_id']]) > 0){
											foreach ($allowances[$v['employee_id']] as $a => $av) {
													if($av['allowance_name'] == "Grocery"){
															$allowance = number_format($av['amount'],2);
															break;
													}
													else
															$allowance = "0.00";

											}
									}
									echo $allowance;
									$total += $allowance;
								}
								else if($remittance_type == "GSIS Consolidated Loan") {
									$loan = "0.00";
									if(sizeof($loanDeductions[$v['employee_id']]) > 0){
											foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
													if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "Consolidated"){
															$loan = number_format($vl1['amount'],2);
															break;
													}
													else
															$loan = "0.00";

											}
									}
									echo $loan;
									$total += $loan;
								}
								else if($remittance_type == "GSIS ECard") {
									$loan = "0.00";
									if(sizeof($loanDeductions[$v['employee_id']]) > 0){
											foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
													if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "ECard Plus"){
															$loan = number_format($vl1['amount'],2);
															break;
													}
													else
															$loan = "0.00";

											}
									}
									echo $loan;
									$total += $loan;
								}
								else if($remittance_type == "GSIS Emergency Loan") {
									$loan = "0.00";
									if(sizeof($loanDeductions[$v['employee_id']]) > 0){
											foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
													if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "Emergency"){
															$loan = number_format($vl1['amount'],2);
															break;
													}
													else
															$loan = "0.00";

											}
									}
									echo $loan;
									$total += $loan;
								}
								else if($remittance_type == "Old GSIS Emergency Loan") {
									$loan = "0.00";
									if(sizeof($loanDeductions[$v['employee_id']]) > 0){
											foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
													if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "Old GSIS"){
															$loan = number_format($vl1['amount'],2);
															break;
													}
													else
															$loan = "0.00";

											}
									}
									echo $loan;
									$total += $loan;
								}
								else if($remittance_type == "GSIS Educational Loan") {
									$loan = "0.00";
									if(sizeof($loanDeductions[$v['employee_id']]) > 0){
											foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
													if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "Educational"){
															$loan = number_format($vl1['amount'],2);
															break;
													}
													else
															$loan = "0.00";

											}
									}
									echo $loan;
									$total += $loan;
								}
								else if($remittance_type == "Pagibig Calamity Loan") {
									$loan = "0.00";
									if(sizeof($loanDeductions[$v['employee_id']]) > 0){
											foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
													if($vl1['code_loan'] == "PAG-IBIG" && $vl1['code_sub'] == "Calamity"){
															$loan = number_format($vl1['amount'],2);
															break;
													}
													else
															$loan = "0.00";

											}
									}
									echo $loan;
									$total += $loan;
								}
								else if($remittance_type == "Pagibig Housing Loan") {
									$loan = "0.00";
									if(sizeof($loanDeductions[$v['employee_id']]) > 0){
											foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
													if($vl1['code_loan'] == "PAG-IBIG" && $vl1['code_sub'] == "Housing"){
															$loan = number_format($vl1['amount'],2);
															break;
													}
													else
															$loan = "0.00";

											}
									}
									echo $loan;
									$total += $loan;
								}
								else if($remittance_type == "NHMFC") {
									$loan = "0.00";
									if(sizeof($loanDeductions[$v['employee_id']]) > 0){
											foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
													if($vl1['code_loan'] == "PAG-IBIG" && $vl1['code_sub'] == "NHMFC"){
															$loan = number_format($vl1['amount'],2);
															break;
													}
													else
															$loan = "0.00";

											}
									}
									echo $loan;
									$total += $loan;
								}
								else if($remittance_type == "Pagibig Multi-purpose Loan") {
									$loan = "0.00";
									if(sizeof($loanDeductions[$v['employee_id']]) > 0){
											foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
													if($vl1['code_loan'] == "PAG-IBIG" && $vl1['code_sub'] == "Multi-Purpose"){
															$loan = number_format($vl1['amount'],2);
															break;
													}
													else
															$loan = "0.00";

											}
									}
									echo $loan;
									$total += $loan;
								}
								else if($remittance_type == "Lost T.V.R.") {
									$loan = "0.00";
									if(sizeof($loanDeductions[$v['employee_id']]) > 0){
											foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
													if($vl1['code_loan'] == "PAG-IBIG" && $vl1['code_sub'] == "Lost TVR"){
															$loan = number_format($vl1['amount'],2);
															break;
													}
													else
															$loan = "0.00";

											}
									}
									echo $loan;
									$total += $loan;
								}
							?>


						</td>
					</tr>
					<?php } endif; ?>
					<tr class="print-only">
						<td colspan="4">
							<hr style="border: 1px #666 solid">
						</td>
					</tr>
					<tr class="print-only">
						<td colspan="4">&nbsp;</td>
					</tr>
					<tr style="background: #eee">
						<td></td>
						<td></td>
						<td class="text-left"><b>TOTAL OF PAYROLL REGISTER</b></td>
						<td class="text-right"><?php echo number_format((double)@$total, 2); ?></td>
					</tr>
					<tr class="print-only">
						<td colspan="4">&nbsp;</td>
					</tr>
					<tr style="background: #eee">
						<td></td>
						<td></td>
						<td class="text-left"><b>GRAND TOTAL</b></td>
						<td class="text-right"><?php echo number_format((double)@$total, 2); ?></td>
					</tr>
					<tr class="print-only">
						<td colspan="4">&nbsp;</td>
					</tr>
					<tr class="print-only">
						<td colspan="4">&nbsp;</td>
					</tr>
					<tr class="print-only" style="background: #eee">
						<td></td>
						<td></td>
						<td class="text-left"><b>CERTIFIED BY:</b></td>
						<td class="text-right"></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class='no-print text-right' style="margin: 10px 0 10px 0">
			<button type="button" class="btn bg-blue waves-effect" id="printSummaryButton">
				<i class="material-icons">print</i>
				<span>Print Report</span>
			</button>
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
