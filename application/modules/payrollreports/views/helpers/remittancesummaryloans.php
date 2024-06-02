<style>
	@media screen {
		.print-only {
			display: none;
		}
	}
</style>
<div id="table-holder-summary">
	<!-- <div class="col-md-12">
		<div class='no-print text-right' style="margin: 10px 0 10px 0">
			<button type="button" class="btn bg-blue waves-effect" id="printSummaryButton">
				<i class="material-icons">print</i>
				<span>Print Report</span>
			</button>
		</div>
	</div> -->
	<div id="printable-table-holder-summary" class="border">
			<table class="table" style="font-size:10px !important; margin-bottom: 0;width: 100%">
				<thead>
					<tr>
					<?php if($data["payroll_grouping_id"]=="*"){ ?>
							<th>Payroll Group</th>
					<?php } ?>
					<?php if($data["remittance_type"] != "4,3" && $data["remittance_type"] != "4,4" && $data["remittance_type"] != "4,6"){ ?>
							<th>Card No.</th>
					<?php } ?>
					<?php if($data["remittance_type"] != "4,3" && $data["remittance_type"] != "4,4" && $data["remittance_type"] != "4,6"){ ?>
							<th>Name</th>
					<?php } ?>
					<?php if($data["remittance_type"] == "4,3" || $data["remittance_type"] == "4,4" || $data["remittance_type"] == "4,6"){ ?>
							<th>PS Amount</th>
					<?php } ?>
					<?php if($data["remittance_type"] == "4,3" || $data["remittance_type"] == "4,4" || $data["remittance_type"] == "4,6"){ ?>
							<th>GS Amount</th>
					<?php } ?>
					<?php if($data["remittance_type"] != "4,3" && $data["remittance_type"] != "4,4" && $data["remittance_type"] != "4,6"){ ?>
							<th>Amount</th>
					<?php } ?>
					</tr>
				</thead>
				<tbody>
				<?php 
				$totAmount = $totGS = $totPS = 0;

				foreach ($list as $key => $value) { 

					$totAmount += ((isset($value["amountPShare"]))?(float)$value["amountPShare"]:0);
					$totGS += ((isset($value["amountGShare"]))?(float)$value["amountGShare"]:0);
					$totPS += ((isset($value["amount"]))?(float)$value["amount"]:0);
					if($value['amount'] != 0):
			?>
					<tr>
					<?php if($data["payroll_grouping_id"]=="*"){ ?>
							<td><?php echo $value["code"]; ?></td>
					<?php } ?>
					<?php if($data["remittance_type"] != "4,3" && $data["remittance_type"] != "4,4" && $data["remittance_type"] != "4,6"){ ?>
							<td><?php echo $value["employee_number"]; ?></td>
					<?php } ?>
					<?php if($data["remittance_type"] != "4,3" && $data["remittance_type"] != "4,4" && $data["remittance_type"] != "4,6"){ ?>
							<td><?php echo $value["last_name"] . ", " . $value["first_name"] . " " . ($value['middle_name'] != null && $value['middle_name'] == 'N/A' ? '' : $value['middle_name']); ?></td>
					<?php } ?>
					<?php if($data["remittance_type"] == "4,3" || $data["remittance_type"] == "4,4" || $data["remittance_type"] == "4,6"){ ?>
							<td><?php echo (isset($value["amountPShare"]))?(float)$value["amountPShare"]:0; ?></td>
					<?php } ?>
					<?php if($data["remittance_type"] == "4,3" || $data["remittance_type"] == "4,4" || $data["remittance_type"] == "4,6"){ ?>
							<td><?php echo (isset($value["amountGShare"]))?(float)$value["amountGShare"]:0; ?></td>
					<?php } ?>
					<?php if($data["remittance_type"] != "4,3" && $data["remittance_type"] != "4,4" && $data["remittance_type"] != "4,6"){ ?>
							<td><?php echo (isset($value["amount"]))?(float)$value["amount"]:0; ?></td>
					<?php } ?>
					</tr>
			<?php 
					endif;
				} ?>
					<tr>
						<?php if($data["department"]=="*"){ ?>
							<td>&nbsp;</td>
						<?php } ?>
						<?php if($data["remittance_type"] != "4,3" && $data["remittance_type"] != "4,4" && $data["remittance_type"] != "4,6"){ ?>
							<td>&nbsp;</td>
						<?php } ?>
						<?php if($data["remittance_type"] != "4,3" && $data["remittance_type"] != "4,4" && $data["remittance_type"] != "4,6"){ ?>
							<td>&nbsp;</td>
						<?php } ?>
						<?php if($data["remittance_type"] == "4,3" || $data["remittance_type"] == "4,4" || $data["remittance_type"] == "4,6"){ ?>
							<td style="font-weight: bold;font-size: 12px"><?php echo $totAmount; ?></td>
						<?php } ?>
						<?php if($data["remittance_type"] == "4,3" || $data["remittance_type"] == "4,4" || $data["remittance_type"] == "4,6"){ ?>
							<td style="font-weight: bold;font-size: 12px"><?php echo $totGS; ?></td>
						<?php } ?>
						<?php if($data["remittance_type"] != "4,3" && $data["remittance_type"] != "4,4" && $data["remittance_type"] != "4,6"){ ?>
							<td style="font-weight: bold;font-size: 12px"><?php echo $totPS; ?></td>
						<?php } ?>
					</tr>
				</tbody>
			</table>
	</div>
</div>