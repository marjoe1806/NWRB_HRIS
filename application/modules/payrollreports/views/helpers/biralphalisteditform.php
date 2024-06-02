<?php
// ini_set('max_input_vars', 5000);
?>

<div class="container-fluid" >
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
						width: 450%;
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
				<form id="<?= $key; ?>" action="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$key; ?>"  method="POST">
					<table id="table1">
						<thead>
							<tr>
								<th colspan="10" class="text-align">BIR FORM 1604C - SCHEDULE 1</th>
							
							<tr>
								<th colspan="10" class="text-align">ALPHABETICAL LIST EMPLOYEES (Declared and Certified using BIR Form No. 2316)</th>
								
							</tr>
							<tr>
								<th colspan="10" class="text-align">AS OF DECEMBER 31,2021</th>
								
							</tr>
							<tr>
								<th colspan="10" class="text-align">TIN : 000795636-0000</th>
								
							</tr>
							<tr>
								<th colspan="10" class="text-align">WITHHOLDING AGENT'S NAME: NATIONAL WATER RESOURCES BOARD</th>
								
							</tr>
							<tr>
								<th colspan="4"></th>
								<th colspan="5" style="letter-spacing:4px;text-align:left;">P   R   E   S   E   N   T     E   M   P   L   O   Y   E   R</th>
							</tr>

							<tr>
							<th class="thead">SEQ</th>
							<th class="thead" colspan="3">NAME OF EMPLOYEES</th>
							<th class="thead">NATIONALITY/ RESIDENT</th>
							<th class="thead">CURRENT EMPLOYMENT</th>
							<th class="thead" colspan="2">EMPLOYMENT</th>
							<th class="thead">REASON OF SEPARATION</th>
							<th class="thead">GROSS COMPENSATION INCOME</th>
							<th class="thead" colspan="2">13th MONTH PAIDE MINIMIS & OTHER BENEFITS</th>
							<th class="thead">SSS, GSIS, PHIC & PAG-IBIG CONTRIBUTIONS AND UNION DUES</th>
							<th class="thead">SALARIES (P250k & below) OTHER FORMS COMPENSATION</th>
							<!--2-->
							<th class="thead">TOTAL NON-TAXABLE/EXEMPT COMPENSATION INCOME</th>
							<th class="thead">TAXABLE BASIC SALARY </th>
							<th class="thead">13th MONTH PAY & OTHER BENEFITS</th>
							<!--3-->
							<th class="thead">TOTAL TAXABLE COMPENSATION INCOME</th>
							<th class="thead">TAXPAYER IDENTIFICATION NUMBER </th>
							<th class="thead">EMPLOYMENT STATUS</th>
							<th class="thead" colspan="3">PERIOD OF EMPLOYMENT</th>
							<!--4-->
							<th class="thead">REASON OF SEPARATION</th>
							<th class="thead">GROSS COMPENSATION PREVIOUS EMPLOYER</th>
							<th class="thead">13th MONTH PAY & OTHER BENIFITS</th>
							<th class="thead">DE MINIMIS BENEFIT</th>
							<th class="thead">SSS, GSIS, PHIC & PAG-IBIG CONTRIBUTIONS AND UNION DUES</th>
							<!--5-->
							<th class="thead">SALARIES (P250k & below) & OTHER FORM OF COMPENSATION</th>
							<th class="thead">TOTAL NON-TAXABLE/EXEMPT COMPENSATION</th>
							<th class="thead">TAXABLE BASIC SALARY</th>
							<th class="thead">TOTAL TAXABLE COMPENSATION (Net of SSS,GSIS,PHIC, HDMF Contri & Union Dues)</th>
							<th class="thead" >13th MONTH PAY & OTHER BENIFITS</th>
							<th class="thead" >SALARIES & OTHER FORMS OF COMPENSATION</th>
							<th class="thead" >TOTAL TAXABLE COMPENSATION INCOME</th>
							<!--6-->
							<th class="thead">TAX DUE (Jan. - Dec.)</th>
							<th class="thead">TAX WITHHELD (Jan. - Nov.) PREVIOUS EMPLOYER</th>
							<th class="thead">PRESENT EMPLOYER</th>
							<th class="thead" >Y E A R - E N D   A D J U S T M E N T (16a or 16b) AMT WITHHELD & PAID FOR IN DECEMBER or Last Salary</th>
							<th class="thead" >OVER WITHELD TAX REFUNDED TO EMPLOYEE</th>
							<!--7-->
							<th class="thead">AMOUNT OF TAX WITHHELD AS ADJUSTED </th>
							<th class="thead">SUBSTITUTED FILING YES/NO </th>

							<tr>
								<tr style="font-size:7pt !important;">
									<th>NO</th>
									<th colspan="3">(Last Name, First Name, Middle Name)</th>
									<th>(for foreigners only)</th>
									<th>STATUS(*)</th>
									<th >FROM</th>
									<th >TO</th>
									<th>(**)</th>
									<th>(present employer)</th>
									<th></th>
									<th></th>
									<th>(employees share only)</th>
									<th></th>
									<!--2-->
									<th>(present employer)</th>
									<th >(net of SSS, GSIS, PHIC & HDMF contri & Union Duest)</th>
									<th>(In excess of Threshold)</th>
									<!--3-->
									<th>(present employer)</th>
									<th></th>
									<th></th>
									<th>From</th>
									<th>
									<th>to</th>
									<!--4-->
									<th>if applicable</th>
									<th></th>
									<th></th>
									<th></th>
									<th>(employee share only)</th>
									<!--5-->
									<th></th>
									<th>(previous employer)</th>
									<th>(Net of SSS, GSIS, PHIC, HMDF contri & Union Dues)</th>
									<th>(previous employer)</th>
									<th></th>
									<th></th>
									<th></th>
								
									<!--6-->
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<!--7-->
									<th></th>
									<th>***</th>
								</tr>
								<tr style="border-bottom: 1px dashed black;">
									<th>(1)</th>
									<th colspan="3">(2a)(2b)(2c)</th>
									<th>(3)</th>
									<th>(4)</th>
									<th>(5a)</th>
									<th>(5b)</th>
									<th>(6)</th>
									<th>7a = (7f + 7j)</th>
									<th style="width:100px;">(7b)</th>
									<th style="width:100px;">(7c)</th>
									<th>(7d)</th>
									<th>(7e)</th>
									<!--2-->
									<th>7f=(7b+7c+7d+7e8)</th>
									<th>(7g)</th>
									<th>(7h)</th>
									<!--3-->
									<th>7j = (7g + 7h + 7i)</th>
									<th>(8)</th>
									<th>(9)</th>
									<th>(10a)</th>
									<th></th>
									<th>(10b)</th>
									<!--4-->
									<th>(11)</th>
									<th>12a = (12f + 12j)</th>
									<th>(12b)</th>
									<th>(12c)</th>
									<th>(12d)</th>
									<!--5-->
									<th>(12e)</th>
									<th>12f=(12b+12c+12d+12e)</th>
									<th>(12g)</th>
									<th>(12h)</th>
									<th>(12i)</th>
									<th>12j=(12g+12h+12i)</th>
									<th>13=(7j+12j)</th>
									<!--6-->
									<th>(14)</th>
									<th>(15a)</th>
									<th>(15b)</th>
									<th>16a=14-(15a+15b)</th>
									<th>16b=(15a+15b)-14</th>
									<!--7-->
									<th>17=(15b+16a) OR (15b-16b)</th>
									<th>(18)</th>
								</tr>
						</thead>
				
				
				
						
						<tbody class="container">
						
							<?php 
								for($x = 0; sizeof($payroll) > $x ; $x++){
							?>
							<tr>
								<input type="hidden" name="id" value="<?php echo $payroll[$x]['id']; ?>">
								<td><?php echo $x + 1; ?></td>
								<td colspan="3"><input type="hidden" name="employee_id[]" id="employee_id" value="<?php echo $payroll[$x]['employee_id']; ?>" >
									<?php 
										if( $payroll[$x]['extension'] == NULL && $payroll[$x]['extension'] == ""){
											echo $payroll[$x]['last_name'].", ". $payroll[$x]['first_name']. " ". $payroll[$x]['middle_name'];
										}else{
											echo $payroll[$x]['last_name']." ".$payroll[$x]['extension'].", ". $payroll[$x]['first_name']. " ". $payroll[$x]['middle_name'];
										}
									?></td>
								<td><?php echo $payroll[$x]['nationality']; ?></td>
								<td><?php echo $payroll[$x]['status']; ?></td>
								<td><input type="text" class="form-control fields " name="start_date" id="start_date" value="<?php echo date('m/d/Y', strtotime($payroll[$x]['start_date'])); ?>"></td>
								<td>
									<input type="text" class="form-control fields" name="end_date" id="end_date" 
									value="<?php 
										$end = explode('/', $payroll[$x]['end_date']);
										if($payroll[$x]['end_date'] != "0000-00-00")
									{
										 echo $end[0]."/".$end[1]."/".$end[2];
										 
									}else{
										echo "";
									}?>">
								</td>
								<td><input type="text" class="form-control fields" name="reason_separation_6" id="reason_separation_6" value="<?php echo $payroll[$x]['reason_separation_6'];?>"></td>
								<td><input type="text" class="form-control fields number" name="gross_compe_income_7a" id="gross_compe_income_7a" value="<?php echo $payroll[$x]['gross_compe_income_7a'];?>"></td>
								<td><input type="text" class="form-control fields number" name="benefits_paide_7b" id="benefits_paide_7b" value="<?php echo $payroll[$x]['benefits_paide_7b'];?>"></td>
								<td><input type="text" class="form-control fields number" name="benefits_paide_7c" id="benefits_paide_7c" value="<?php echo $payroll[$x]['benefits_paide_7c'];?>"></td>
								<td><input type="text" class="form-control fields number" name="contribution_emps_only_7d" id="contribution_emps_only_7d" value="<?php echo $payroll[$x]['contribution_emps_only_7d'];?>"></td>
								<td><input type="text" class="form-control fields number" name="salary_below_7e" id="salary_below_7e" value="<?php echo $payroll[$x]['salary_below_7e'];?>"></td>
								<td><input type="text" class="form-control fields number" name="non_taxable_compe_income_pres_empr_7f" id="non_taxable_compe_income_pres_empr_7f" value="<?php echo $payroll[$x]['non_taxable_compe_income_pres_empr_7f'];?>"></td>
								<td><input type="text" class="form-control fields number" name="taxable_basic_salary_7g" id="taxable_basic_salary_7g" value="<?php echo $payroll[$x]['taxable_basic_salary_7g'];?>"></td>
								<td><input type="text" class="form-control fields number" name="benefits_excess_7h" id="benefits_excess_7h" value="<?php echo $payroll[$x]['benefits_excess_7h'];?>"></td>
								<td><input type="text" class="form-control fields number" name="total_taxable_compe_income_pre_empr_7j" id="total_taxable_compe_income_pre_empr_7j" value="<?php echo $payroll[$x]['total_taxable_compe_income_pre_empr_7j'];?>"></td>
								<td><input type="text" class="form-control fields" name="tax_id_number_8" id="tax_id_number_8" value="<?php echo $payroll[$x]['tax_id_number_8'];?>"></td>
								<td><input type="text" class="form-control fields" name="employment_status_9" id="employment_status_9" value="<?php echo $payroll[$x]['employment_status_9'];?>"></td>
								<td><input type="text" class="form-control fields" name="period_employment_from_10a" id="period_employment_from_10a" value="<?php echo $payroll[$x]['period_employment_from_10a'];?>"></td>
								<td>&nbsp;&nbsp;</td>
								<td><input type="text" class="form-control fields" name="period_employment_to_10b" id="period_employment_to_10b" value="<?php echo $payroll[$x]['period_employment_to_10b'];?>"></td>
								<td><input type="text" class="form-control fields" name="reason_separation_applicable_11" id="reason_separation_applicable_11" value="<?php echo $payroll[$x]['reason_separation_applicable_11'];?>"></td>
								<td><input type="text" class="form-control fields number" name="gross_compe_pre_empr_12a" id="gross_compe_pre_empr_12a" value="<?php echo $payroll[$x]['gross_compe_pre_empr_12a'];?>"></td>
								<td><input type="text" class="form-control fields number" name="benefits_12b" id="benefits_12b" value="<?php echo $payroll[$x]['benefits_12b'];?>"></td>
								<td><input type="text" class="form-control fields number" name="benefits_12c" id="benefits_12c" value="<?php echo $payroll[$x]['benefits_12c'];?>"></td>
								<td><input type="text" class="form-control fields number" name="contribution_emps_only_12d" id="contribution_emps_only_12d" value="<?php echo $payroll[$x]['contribution_emps_only_12d'];?>"></td>
								<td><input type="text" class="form-control fields number" name="below_salaries_12e" id="below_salaries_12e" value="<?php echo $payroll[$x]['below_salaries_12e'];?>"></td>
								<td><input type="text" class="form-control fields number" name="non_taxable_compe_income_prev_empr_12f" id="non_taxable_compe_income_prev_empr_12f" value="<?php echo $payroll[$x]['non_taxable_compe_income_prev_empr_12f'];?>"></td>
								<td><input type="text" class="form-control fields number" name="pay_12h" id="pay_12h" value="<?php echo $payroll[$x]['pay_12h'];?>"></td>
								<td><input type="text" class="form-control fields number" name="compensation_12i" id="compensation_12i" value="<?php echo $payroll[$x]['compensation_12i'];?>"></td>
								<td><input type="text" class="form-control fields number" name="taxable_basic_salary_12g" id="taxable_basic_salary_12g" value="<?php echo $payroll[$x]['taxable_basic_salary_12g'];?>"></td>
								<td><input type="text" class="form-control fields number" name="total_taxable_compe_12j" id="total_taxable_compe_12j" value="<?php echo $payroll[$x]['total_taxable_compe_12j'];?>"></td>
								<td><input type="text" class="form-control field numbers" name="total_taxable_compe_income_13" id="total_taxable_compe_income_13" value="<?php echo $payroll[$x]['total_taxable_compe_income_13'];?>"></td>
								<td><input type="text" class="form-control fields number" name="tax_due_14" id="tax_due_14" value="<?php echo $payroll[$x]['tax_due_14'];?>"></td>
								<td><input type="text" class="form-control fields number" name="tax_withheld_15a" id="tax_withheld_15a" value="<?php echo $payroll[$x]['tax_withheld_15a'];?>"></td>
								<td><input type="text" class="form-control fields number" name="present_employer_15b" id="present_employer_15b" value="<?php echo $payroll[$x]['present_employer_15b'];?>"></td> 
								<td><input type="text" class="form-control fields number" name="year_end_adjustment_16a" id="year_end_adjustment_16a" value="<?php echo $payroll[$x]['year_end_adjustment_16a'];?>"></td>
								<td><input type="text" class="form-control fields number" name="year_end_adjustment_16b" id="year_end_adjustment_16b" value="<?php echo $payroll[$x]['year_end_adjustment_16b'];?>"></td>
								<td><input type="text" class="form-control fields number" name="amount_tax_withheld_adjusted_17" id="amount_tax_withheld_adjusted_17" value="<?php echo $payroll[$x]['amount_tax_withheld_adjusted_17'];?>" ></td>
								<td><input type="text" class="form-control fields" name="substituted_filing_18" id="substituted_filing_18" value="<?php echo $payroll[$x]['substituted_filing_18'];?>"></td>
								
								
							</tr>
									<?php
								}
									?>
						</tbody>
					</table>


	<div class="row">
            <div class="col-md-6"></div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                                <button type="submit" id="update" name="btnSubmit" style="float: right; margin: 10px 5px;" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Save">
                                    <i class="material-icons">save</i> Submit
                                </button> 
                           
                                <button type="button" style="float: right; margin: 10px 5px;" id="cancel" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Cancel">
                                    <i class="material-icons">cancel</i> Cancel
                                </button> 
                        </div>
                    </div>
                </div>
            </div>
		</div>
	</form>