<?php
// ini_set('max_input_vars', 5000);
?>

<div class="row">
    <div class="col-md-12 text-right"> 
	<button type="button" id="btnXls2" data-date="<?php echo $date_year;?>" class="btn btn-success btn-circle-lg waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Export xls">
				<i class="material-icons">archive</i>
			</button>
    </div>
</div>
<hr>
<div class="row"  id="clearance-div">
    <div class="col-md-12">
        <div style="width:100%; overflow-x:auto;">
            <div>
				<style>
					th{
						font-size: 8pt;
						text-align: center;
					}
					#table1 table.container{
						page-break-after: always;
					}
					#table1 .table1_header{
						display: table-header-group;
						
					}
					#table1 .text-align{
						text-align:left;
					}
					.container td{
						border-bottom: 1px dashed black;
						font-size: 10pt;
					}
					#table1{
						width: 250%;
					}
					.thead{
						border-bottom:1px solid black;
						padding: 5px;
					}
					.form{
						display: flex;
						flex:1;
						min-height: 0px;
					}	
				</style>
				<!-- first table--->
				<div>
					<table id="table1">
						<thead class="table1_header">
							<?php 
                              $year = date ("Y");

                            ?>
							<tr>
							<td>H1604C</td>
							<td>000795636</td>
							<td>0000</td>
							<td><?php echo '12/31/'.$year; ?></td>
							<td>N</td>
							<td>0</td>
							<td>039</td>
							<tr>
						</thead>
						<tbody class="container">
							<?php 
								$benefits_12c = 0;
								$contribution_emps_only_12d = 0;
								$below_salaries_12e = 0;
								$non_taxable_compe_income_prev_empr_12f = 0;
								$pay_12h = 0;
								$compensation_12i = 0;
								$taxable_basic_salary_12g = 0;
								$total_taxable_compe_12j = 0;
								$gross_compe_income_7a = 0;
								$gross_compe_pre_empr_12a = 0;
								$benefits_paide_7b = 0;
								$benefits_paide_7c = 0;
								$contribution_emps_only_7d = 0;
								$non_taxable_compe_income_pres_empr_7f = 0;
								$taxable_basic_salary_7g = 0;
								$benefits_excess_7h = 0;
								$total_taxable_compe_income_pre_empr_7j = 0;
								$total_taxable_compe_income_13 = 0;
								$total_value = 0;
								$tax_due_14 = 0;
								$tax_withheld_15a = 0;
								$present_employer_15b = 0;
								$year_end_adjustment_16a = 0;
								$year_end_adjustment_16b = 0;
								$amount_tax_withheld_adjusted_17 = 0;


								for($x = 0; sizeof($payroll) > $x ; $x++){

								$benefits_12c += str_replace(',','', $payroll[$x]['benefits_12c']);
								$contribution_emps_only_12d += str_replace(',','', $payroll[$x]['contribution_emps_only_12d']);
								$below_salaries_12e += str_replace(',','', $payroll[$x]['below_salaries_12e']);
								$non_taxable_compe_income_prev_empr_12f += str_replace(',','', $payroll[$x]['non_taxable_compe_income_prev_empr_12f']);
								$pay_12h += str_replace(',','', $payroll[$x]['pay_12h']);
								$compensation_12i += str_replace(',','', $payroll[$x]['compensation_12i']);
								$taxable_basic_salary_12g += str_replace(',','', $payroll[$x]['taxable_basic_salary_12g']);
								$total_taxable_compe_12j += str_replace(',','', $payroll[$x]['total_taxable_compe_12j']);
								$gross_compe_income_7a += str_replace(',','', $payroll[$x]['gross_compe_income_7a']);
								$gross_compe_pre_empr_12a += str_replace(',','', $payroll[$x]['gross_compe_pre_empr_12a']);
								$benefits_paide_7b += str_replace(',','', $payroll[$x]['benefits_paide_7b']);
								$benefits_paide_7c += str_replace(',','', $payroll[$x]['benefits_paide_7c']);
								$contribution_emps_only_7d += str_replace(',','', $payroll[$x]['contribution_emps_only_7d']);
								$non_taxable_compe_income_pres_empr_7f += str_replace(',','', $payroll[$x]['non_taxable_compe_income_pres_empr_7f']);
								$taxable_basic_salary_7g += str_replace(',','', $payroll[$x]['taxable_basic_salary_7g']);
								$benefits_excess_7h += str_replace(',','', $payroll[$x]['benefits_excess_7h']);
								$total_taxable_compe_income_pre_empr_7j += str_replace(',','', $payroll[$x]['total_taxable_compe_income_pre_empr_7j']);
								$total_taxable_compe_income_13 += str_replace(',','', $payroll[$x]['total_taxable_compe_income_13']);
								$total_value = str_replace(',','', $payroll[$x]['taxable_basic_salary_7g']) + str_replace(',','', $payroll[$x]['benefits_excess_7h']);
								$tax_due_14 += str_replace(',','', $payroll[$x]['tax_due_14']);
								$tax_withheld_15a += str_replace(',','', $payroll[$x]['tax_withheld_15a']);
								$present_employer_15b += str_replace(',','', $payroll[$x]['present_employer_15b']);
								$year_end_adjustment_16a += str_replace(',','', $payroll[$x]['year_end_adjustment_16a']);
								$year_end_adjustment_16b += str_replace(',','', $payroll[$x]['year_end_adjustment_16b']);
								$amount_tax_withheld_adjusted_17 += str_replace(',','', $payroll[$x]['amount_tax_withheld_adjusted_17']);

								$value_1 = str_replace(',','', $payroll[$x]['taxable_basic_salary_7g']);
								//var_dump($value_1);
								$value_2 = str_replace(',','', $payroll[$x]['benefits_excess_7h']);
								$value_3 = $value_1 + $value_2;

								
							?>
							<tr style="text-align:center;">
								<td>D1</td>
								<td>1604C</td>
								<td>000795636</td>
								<td>0000</td>
								<td><?php echo '12/31'.$year; ?></td>


								<td><?php echo ($x + 1); ?></td>
								<td><?php echo $payroll[$x]['tax_id_number_8'] ;?></td>
								<td>0000</td>
								<td><?php echo $payroll[$x]['last_name']; ?></td>
								<td><?php echo $payroll[$x]['first_name']; ?></td>
								<td><?php echo $payroll[$x]['middle_name']; ?></td>
								<td>NCR</td>
								<td></td>
								<td></td>
								<td></td>
								<td><?php echo $payroll[$x]['benefits_12c'] ;?></td>
								<td><?php echo $payroll[$x]['contribution_emps_only_12d'] ;?></td>
								<td><?php echo $payroll[$x]['below_salaries_12e'] ;?></td>
								<td><?php echo $payroll[$x]['non_taxable_compe_income_prev_empr_12f'] ;?></td>
								<td><?php echo $payroll[$x]['pay_12h'] ;?></td>
								<td><?php echo $payroll[$x]['compensation_12i'] ;?></td>
								<td><?php echo $payroll[$x]['taxable_basic_salary_12g'] ;?></td>
								<td><?php echo $payroll[$x]['total_taxable_compe_12j'] ;?></td>
								
								
								<td><?php echo date('m/d/Y', strtotime($payroll[$x]['start_date'])); ?></td>
								<td><?php if($payroll[$x]['end_date'] != "0000-00-00"){ echo date('m/d/Y', strtotime($payroll[$x]['end_date']));} ?></td>
								<td><?php echo $payroll[$x]['gross_compe_income_7a'] ;?></td>
								<td><?php echo $payroll[$x]['gross_compe_pre_empr_12a'] ;?></td>
								<td><?php echo $payroll[$x]['benefits_paide_7b'] ;?></td>
								<td><?php echo $payroll[$x]['benefits_paide_7c'] ;?></td>
								<td><?php echo $payroll[$x]['contribution_emps_only_7d'] ;?></td>
								<td><?php echo $payroll[$x]['non_taxable_compe_income_pres_empr_7f'] ;?></td>
								<td><?php echo $payroll[$x]['taxable_basic_salary_7g'] ;?></td>
								<td><?php echo $payroll[$x]['benefits_excess_7h'] ;?></td>
								<td><?php echo $payroll[$x]['total_taxable_compe_income_pre_empr_7j'] ;?></td>
								<td><?php echo $payroll[$x]['total_taxable_compe_income_13'] ;?></td>

								<td><?php echo number_format($value_3, 2);?></td>
								<td><?php echo $payroll[$x]['tax_due_14'] ;?></td>
								<td><?php echo $payroll[$x]['tax_withheld_15a'] ;?></td>
								<td><?php echo $payroll[$x]['present_employer_15b'] ;?></td>
								<td><?php echo $payroll[$x]['year_end_adjustment_16a'] ;?></td>
								<td><?php echo $payroll[$x]['year_end_adjustment_16b'] ;?></td>
								<td><?php echo $payroll[$x]['amount_tax_withheld_adjusted_17'] ;?></td>
								<td><?php echo $payroll[$x]['nationality']; ?></td>
								<td><?php echo $payroll[$x]['status']; ?></td>
								<td><?php echo $payroll[$x]['reason_separation_applicable_11'];?></td>
							</tr>
							<?php
								}
							?>
							<tr>
								<td>C1</td>
								<td>1604C</td>
								<td>000795636</td>
								<td>0000</td>
								<td><?php echo '12/31'.$year; ?></td>
								<td><?php echo number_format($benefits_12c , 2);?></td>
								<td><?php echo number_format($contribution_emps_only_12d , 2);?></td>
								<td><?php echo number_format($below_salaries_12e , 2);?></td>
								<td><?php echo number_format($non_taxable_compe_income_prev_empr_12f , 2);?></td>
								<td><?php echo number_format($pay_12h , 2);?></td>
								<td><?php echo number_format($compensation_12i , 2);?></td>
								<td><?php echo number_format($taxable_basic_salary_12g , 2);?></td>
								<td><?php echo number_format($total_taxable_compe_12j , 2);?></td>
								<td><?php echo number_format($gross_compe_income_7a , 2);?></td>
								<td><?php echo number_format($gross_compe_pre_empr_12a , 2);?></td>
								<td><?php echo number_format($benefits_paide_7b , 2);?></td>
								<td><?php echo number_format($benefits_paide_7c , 2);?></td>
								<td><?php echo number_format($contribution_emps_only_7d , 2);?></td>
								<td><?php echo number_format($non_taxable_compe_income_pres_empr_7f , 2);?></td>
								<td><?php echo number_format($taxable_basic_salary_7g , 2);?></td>
								<td><?php echo number_format($benefits_excess_7h , 2);?></td>
								<td><?php echo number_format($total_taxable_compe_income_pre_empr_7j , 2);?></td>
								<td><?php echo number_format($total_taxable_compe_income_13 , 2);?></td>
								<td><?php echo number_format($total_value , 2);?></td>
								<td><?php echo number_format($tax_due_14 , 2);?></td>
								<td><?php echo number_format($tax_withheld_15a , 2);?></td>
								<td><?php echo number_format($present_employer_15b , 2);?></td>
								<td><?php echo number_format($year_end_adjustment_16a , 2);?></td>
								<td><?php echo number_format($year_end_adjustment_16b , 2);?></td>
								<td><?php echo number_format($amount_tax_withheld_adjusted_17 , 2);?></td>

							</tr>
						</tbody>
					</table>
				</div>
				<!--End of first table --->
			
			</div>
		</div>
	</div>
