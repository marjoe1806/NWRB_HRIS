<style>
	.dataTables_info {
		width: auto;
		float: right;
		margin-bottom: 10px;
	}
</style>


<div class="row">
	<div class="col-md-12">
		<div class="btn-group btn-group-lg btn-group-justified" role="group" aria-label="Justified button group">
			<a id="filterOptionsButton" class="btn bg-blue waves-effect" data-toggle="collapse" data-parent="#accordion" href="#filterContainer"><i class="material-icons">find_in_page</i>
				<span>Filter Options</span></a>
			<a id="printPreviewButton" class="btn bg-green waves-effect"><i class="material-icons">print</i> <span>Print Preview</span></a>
		</div>
		<!-- <h4 class="text-info text-center">Employee Daily Time Record</h4> -->
		<div class="panel-group" id="accordion">
			<div class="panel">
				<div id="filterContainer" class="panel-collapse collapse">
					<div class="panel-body">
						<div class="row">
							<div class="col-md-6">
								<div class="input-group">
									<span class="input-group-addon">
										Search for
									</span>
									<div class="form-line">
										<input id="filterInput" type="text" class="form-control" disabled>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="btn-group btn-group-lg btn-group-justified filter-buttons" role="group" aria-label="Justified button group">
									<a class="btn filter-button bg-blue waves-effect allDept" id="filterByDepartment">Department</a>
									<a class="btn filter-button bg-blue waves-effect nonDept" id="filterByID">Card No.</a>
									<a class="btn filter-button bg-blue waves-effect nonDept" id="filterByName">Name</a>
									<a class="btn filter-button bg-blue waves-effect sAmount" id="filterByPSAmount">PS Amount</a>
									<a class="btn filter-button bg-blue waves-effect sAmount" id="filterByGSAmount">GS Amount</a>
									<a class="btn filter-button bg-blue waves-effect amount" id="filterByAmount">Amount</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="table-responsive" id="clearance-div">
			<style type="text/css">
			@media print{
                @page {
					size: portrait;
					margin: 3mm 3mm 3mm 3mm;
				}
                body{
                    font-family:'Times New Roman';
                    font-size:12px;
                } 
				
                table tr td{
                    font-family:'Times New Roman';
                    font-size:12px;

                } 
            }
			.hidden{
				display:none;
			}
			 #module {
                border-collapse: collapse;
            }
            #module th,#module td{
            	border: groove 1px #3b3b3b;
            	padding:5px;
            }
            td.allDept,td.nonDept{
            	text-align:left;
            }
            td.sAmount,td.amount{
            	text-align:right;
            }
			</style>
			<center>
			<h4><?php echo @$list[0]['loan'].' '. @$list[0]['subloan']; ?></h4>
			<h5><?php echo isset($list[0]['employee_number'])?@$list[0]['code']:"" ?></h5>
				<strong>NATIONAL WATER RESOURCES BOARD</strong><br>
				<strong>Summary of Remittances</strong><br>
				<strong>For Month 2019</strong>

			</center>
			<br><br><br>
			<table id="module" class="table table-hover table-striped table-responsive" style="width: 100%;">
				<thead>
					<tr class="employee-table-columns" style="background: #eee">
						<th class="employee-table-column allDept" id="dept">Department</th>
						<th class="employee-table-column nonDept" id="card-number-column">Card<br>Number</th>
						<th class="employee-table-column nonDept" id="name-column">Employee<br>Name</th>
						<th class="employee-table-column sAmount" id="PSAmount">PS Amount</th>
						<th class="employee-table-column sAmount" id="GSAmount">GS Amount</th>
						<th class="employee-table-column amount" id="Amount">Amount</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$totAmount = $totGS = $totPS = 0;
						$department = "";
						if(isset($list) && $list != null) { 
						foreach($list as $k => $v) {

							$totAmount += ((isset($v["amountPShare"]))?(float)$v["amountPShare"]:0);
							$totGS += ((isset($v["amountGShare"]))?(float)$v["amountGShare"]:0);
							$totPS += ((isset($v["amount"]))?(float)$v["amount"]:0);
							if($v["amount"] != 0):
				?>
								<tr>
									<td class="allDept">
										<?php echo @$v['code']; ?>
									</td>
									<td class="nonDept" style="text-align:right">
										<?php echo @$v['employee_number']; ?>
									</td>
									<td class="nonDept ">
										<?php echo @$v['last_name'] . ', ' . @$v['first_name'] . ' ' . (@$v['middle_name'] != null && @$v['middle_name'] == 'N/A' ? '' : @$v['middle_name']); ?>
									</td>
									<td class="sAmount">
										<?php echo (isset($v['amountPShare']))?number_format((double)@$v['amountPShare'],2):@0; ?>
									</td>
									<td class="sAmount">
										<?php echo (isset($v['amountGShare']))?number_format((double)@$v['amountGShare'],2):@0; ?>
									</td>
									<td class="amount">
										<?php echo (isset($v['amount']))?number_format((double)@$v['amount'],2):@0; ?>
									</td>
								</tr>
					<?php 
							endif;
						} 
						}  ?>
					<tr>
						<td class="allDept"></td>
						<td class="nonDept "></td>
						<td class="nonDept "></td>
						<td class="sAmount" style="font-weight: bold;font-size: 12px"><?php echo number_format((double)@$totAmount,2); ?></td>
						<td class="sAmount" style="font-weight: bold;font-size: 12px"><?php echo number_format((double)@$totGS,2); ?></td>
						<td class="amount" style="font-weight: bold;font-size: 12px"><?php echo number_format((double)@$totPS,2); ?></td>
					</tr>
				</tbody>
			</table>
		</div>


	</div>
</div>
</div>
