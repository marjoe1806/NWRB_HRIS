<div class="row">
    <div class="col-md-12 text-right">
    <button id="addColumn1" class="btn bg-blue btn-fab btn-fab-mini"><i class = "material-icons">add</i>Show/Add Column</button>
        <button id="addColumn2" class="btn bg-blue btn-fab btn-fab-mini" style="display:none"><i class = "material-icons">add</i>Show/Add Column</button>
        <button id="removeColumns" class="btn bg-red btn-fab btn-fab-mini" style="display:none"><i class = "material-icons">clear</i>Remove Columns</button>
        <button id="deleteColumn1" class="btn bg-red btn-fab btn-fab-mini" style="display:none"><i class = "material-icons">clear</i>Delete 1st Added Column</button>
        <button id="deleteColumn2" class="btn bg-red btn-fab btn-fab-mini" style="display:none"><i class = "material-icons">clear</i>Delete 2nd Added Column</button>
        <button id="btnSave" class="btn bg-green btn-fab btn-fab-mini" style="display:none"><i class = "material-icons">save</i>Save Changes</button>
        <button id="printClearance" class="btn bg-green btn-fab btn-fab-mini">Print Preview <i class = "material-icons">print</i></button>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-12">
        <div style="width:100%; overflow-x:auto;">
            <div id="clearance-div">
                <style type = 'text/css'>
                    @media print{
                        /*280mm 378mm 11in 15in */
                        html { height: 0; }
                        @page {  
                            size: US Std Fanfold landscape;
                            margin: 10mm 20mm;
                        }
                        body { font-family:Calibri; font-size: 12; color: black; }
                        table{ border-collapse: collapse; }
                        .page-break{ display: table; vertical-align:top; width: 100% !important; page-break-after: always !important; table-layout: inherit; margin-top:2px; }

                        .no-print {
                            display: none !important;
						}
						#ftTable{
							display: table-footer-group;
							
							
						}
                    }
                </style> 
                <style type="text/css" media="all">
                /* .break{  page-break-before: always; } */
                    table#tmpTable thead tr th, table#tmpTable tbody tr td{ padding: 2px; }
                    table#tmpTable thead tr th{ border: 1px solid black; }
                    table#tmpTable tr, #tmpTable td, #tblGSIS td {
                        font-size: 12px;
                        color: #000;
                        border: 1px solid black;
                    }
                    table#hdTable, table#ftTable, table#tblGSIS{
                        font-size: 12px;
                        color: #000;
                    }
                    #tblGSIS td{
                        text-align: center;
                    }
                </style>
				                <?php

						$totalNum = 0; 
						$numExperience = sizeof($payroll) / 15;
						$wholeNumExperience = floor($numExperience);
						$decNumExperience = $wholeNumExperience - $numExperience;
						$totalNum = $wholeNumExperience;
						//var_dump($decNumExperience);

						if($decNumExperience < 0){
							$totalNum += 1;
						}
						$count1 = 0;
						$count2 = 14;
						for($x = 0; $x < $totalNum; $x++){


						?>
						<br></br>
                <div class="header-container" style="width:100%;">
				
                    <?php
                        $total_per_page = $total_next_page = 10;
                        $total_page = floor(sizeof($payroll)/$total_per_page) + 1;
                        if(( sizeof($payroll) < $total_next_page && sizeof($payroll) > floor($total_next_page*.5) ) || (sizeof($payroll) > $total_next_page && (sizeof($payroll)) - ($total_next_page * $total_page) > floor($total_next_page*.5) )){
                            $total_page = floor(sizeof($payroll)/$total_per_page) + 2;
                        }
                        $page_count = 1;
                        $last_row = 0;
                        $count = 1;
                        $page = 0;
                        $rows = 0;
						//var_dump('test');
                    ?>
                    <table style="width:100%;border-bottom:0px;" id="hdTable">
                        <thead>
                            <tr>
                                <td align="center"><label>GENERAL PAYROLL</label></td>
                            </tr>
                            <tr>
                                <td align="center"><label>NATIONAL WATER RESOURCES BOARD</label></td>
                            </tr>
                            <tr>
                                <td align="center"><label>8th Floor NIA Building EDSA, Diliman Quezon City</label><br></td>
                            </tr>
                            <tr>
                                <td align="center" style="text-decoration: underline;"><b><?php echo date('F d, Y',strtotime(@$payroll_period[0]['start_date'])); ?></b> to <b><?php echo date('F d, Y',strtotime(@$payroll_period[0]['end_date'])); ?></b></td>
                            </tr>
                            <tr>
                                <td style="text-align:center;font-size: 11px;font-style: italic;padding-top:0px;">Period</td>
                            </tr>
                            <tr>
                                <td><br></td>
                            </tr>
                            <tr>
                                <td><b>ENTITY NAME: NATIONAL WATER RESOURCES BOARD</b></td>
                            </tr>
                            <tr>
                                <td><b>Fund Cluster: </b><u>01</u></td>
                            </tr>
                            <tr>
                                <td><b>Division: </b><b id="division_label"> <p id="division_label"></p></b></td>
                            </tr>
                            <tr>
                                <td align="left">We Acknowledge receipt of cash opposite our name as full compensation for services rendered for the period.</td>
                            </tr>
                        </thead>
                    </table>
                    <div class="table-container table-responsive"><?php $page++; ?>
                        <table style="width:100%;" id="tmpTable">
                            <thead>
                                <tr>
                                    <th style="text-align:center;" rowspan="2"><b>Ser. No.</b></th>
                                    <th style="text-align:center; min-width: 250px;" rowspan="2">NAME</th>
                                    <th style="text-align:center;" rowspan="2"><b>Emp. No.</b></th>
                                    <th style="text-align:center;" colspan="3"><b>COMPENSATIONS</b></th>
                                    <th style="text-align:center;" id="deduction_header" colspan="6"><b>DEDUCTIONS</b></th>
                                    <th style="text-align:center;" rowspan="2"><b>Total Deductions</b></th>
                                    <th style="text-align:center;" rowspan="2" style="border: 1px solid black;"><b>Net Amount Due</b></th>
                                    <th style="text-align:center;" rowspan="2"><b>Sig. of Recipient</b></th>
                                </tr>
                                <tr>
                                    <th style="text-align:center;">Salaries & Wages-Per Day</th>
                                    <th style="text-align:center;"></th>
                                    <th style="text-align:center;">Gross Amount Earned</th>
                                    <th style="text-align:center;">5% TAX</th>
                                    <th style="text-align:center;">3% TAX</th>
                                    <th style="text-align:center; min-width: 50px;">TOTAL TAX</th>
                                    <th <?php if(isset($additional_header1[0]['name']) == ""){echo "style='display:none;text-align:center; min-width: 50px;'";}?> id="header_1" class="tmp_col1  editableHeader" data-type="header_1" 
                                        data-period="<?php echo $payroll_period_id ?>" data-division="<?php echo $payroll[0]['division_id'] ?>"  
                                        data-pay_basis="<?php echo $pay_basis ?>" contenteditable>
                                        <?php echo isset($additional_header1[0]['name']) ? $additional_header1[0]['name'] : "Header_1";?>
                                    </th>
                                    <th <?php if(isset($additional_header2[0]['name']) == ""){echo "style='display:none;text-align:center; min-width: 50px;'";}?>  id="header_2" class="tmp_col2  editableHeader" data-type="header_2" 
                                        data-period="<?php echo $payroll_period_id ?>" data-division="<?php echo $payroll[0]['division_id'] ?>"  
                                        data-pay_basis="<?php echo $pay_basis ?>"  contenteditable>
                                        <?php echo isset($additional_header2[0]['name']) ? $additional_header2[0]['name'] : "Header_2";?>
                                    </th>
                                    <th style="text-align:center; min-width: 50px;">HDMF</th>
                                    <th style="text-align:center; min-width: 50px;">PHIC</th>
                                    <th style="text-align:center;">LATES & ABSENSES</th>
                                </tr>
                            </thead>
                            <?php
                                $page_total = array(
                                    'salary'=>0.00, 'gross_pay'=>0.00, 'wh_tax_amt'=>0.00, 'sss_gsis_amt'=>0.00, 'sos_loan'=>0.00, 'gsis_educational'=>0.00, 'mpl'=>0.00, 'lbp1'=>0.00, 'gsis_ecard_plus'=>0.00, 'opt_ins'=>0.00, 'cpl'=>0.00,  'opt_pol_loan'=>0.00, 'gfal'=>0.00, 'gsis_emergency'=>0.00,
                                    'gsis_policy'=>0.00, 'gsis_optional_loan'=>0.00, 'optional_unltd'=>0.00, 'gsis_consolidated'=>0.00, 'gsis_nhmfc'=>0.00, 'union_dues_amt'=>0.00, 'pagibig_amt'=>0.00, 'pagibig_loans'=>0.00,
                                    'philhealth_amt'=>0.00,'gsis_housing'=>0.00, 'pag_ibig_housing'=>0.00, 'total_deductions'=>0.00, 'net_amnt_due'=>0.00, 'other_earnings_amt'=>0.00, 'other_deductions_amt'=>0.00, 'tardiness_amt'=>0.00,
                                    'pera_amt'=>0.00, 'gross_pera_amt'=>0.00 , 'mp2'=>0.00, 'lbp2'=>0.00, 'nwrbea_project'=>0.00, 'nwrbea_cashLoan'=>0.00, 'nwrbea_emergency'=>0.00
                                );
                                $grand_total = array(
                                    'salary'=>0.00, 'gsis_housing'=>0.00, 'pag_ibig_housing'=>0.00, 'gross_pay'=>0.00, 'wh_tax_amt'=>0.00, 'sss_gsis_amt'=>0.00, 'sos_loan'=>0.00, 'gsis_educational'=>0.00, 'lbp1'=>0.00, 'mpl'=>0.00, 'gsis_ecard_plus'=>0.00, 'opt_pol_loan'=>0.00, 'opt_ins'=>0.00, 'cpl'=>0.00, 'gfal'=>0.00, 'gsis_emergency'=>0.00,
                                    'gsis_policy'=>0.00, 'gsis_optional_loan'=>0.00, 'optional_unltd'=>0.00, 'gsis_consolidated'=>0.00, 'gsis_nhmfc'=>0.00,'union_dues_amt'=>0.00,  'pagibig_amt'=>0.00, 'pagibig_loans'=>0.00,
                                    'philhealth_amt'=>0.00, 'total_deductions'=>0.00, 'net_amnt_due'=>0.00, 'other_earnings_amt'=>0.00, 'other_deductions_amt'=>0.00, 'tardiness_amt'=>0.00
                                    ,'pera_amt'=>0.00,'gross_pera_amt'=>0.00,'nwrbea_project'=>0.00, 'lbp2'=>0.00, 'nwrbea_cashLoan'=>0.00, 'nwrbea_emergency'=>0.00, 'col1_value'=>0.00, 'col2_value'=>0.00
                                );
                                $grand_total1 = array(
                                    'mpl'=>0.00, 'mp2'=>0.00
                                );

                                $grand_total2 = array(
                                    'lbp1'=>0.00, 'lbp2'=>0.00
                                );

                            ?>
                            <tbody>
                            <?php  for($v = $count1 ; $v < sizeof($payroll); $v++ ) { 
                                    $total_gsis = get_key(2,$loanDeductions[$payroll[$v]['employee_id']]) + get_key(41,$loanDeductions[$payroll[$v]['employee_id']]) + get_key(35,$loanDeductions[$payroll[$v]['employee_id']]) + get_key(4,$loanDeductions[$payroll[$v]['employee_id']]) + get_key(24,$loanDeductions[$payroll[$v]['employee_id']]) + get_key(39,$loanDeductions[$payroll[$v]['employee_id']]) + get_key(18,$loanDeductions[$payroll[$v]['employee_id']]) + get_key(8,$loanDeductions[$payroll[$v]['employee_id']]) + get_key(40,$loanDeductions[$payroll[$v]['employee_id']]);
                                    $total_lchl = get_key(7,$loanDeductions[$payroll[$v]['employee_id']]) + get_key(20,$loanDeductions[$payroll[$v]['employee_id']]);
                                    $rowtotaldeduction = 0; ?>
                                <tr>
                                    <td valign="top" style="text-align:center;"><?php echo ($v+1); ?></td>
                                    <td valign="top" style="text-align:left;font-weight: bold;"><?php echo ((isset($payroll[$v]['last_name']) && $payroll[$v]['last_name'] != "")?$this->Helper->decrypt($payroll[$v]['last_name'],$payroll[$v]['employee_id']):"") . ((isset($payroll[$v]['first_name']) && $payroll[$v]['first_name'] != "")?"&emsp;&nbsp;".$this->Helper->decrypt($payroll[$v]['first_name'],$payroll[$v]['employee_id']):"") . ((isset($v['middle_name']) && $payroll[$v]['middle_name'] != "")?"&nbsp;".$this->Helper->decrypt($payroll[$v]['middle_name'],$payroll[$v]['employee_id']):"") //. "<br><span style='font-weight: normal;'>" . strtoupper(@$v['position_name']) . "</span>"; ?></td>
                                    <td valign="middle" style="text-align:center;"> <?php echo (isset($payroll[$v]['employee_id_number']) && $payroll[$v]['employee_id_number'] != "")?$this->Helper->decrypt($payroll[$v]['employee_id_number'],$payroll[$v]['employee_id']):""; ?> </td>
                                    <td valign="middle" align="right"><?php echo number_format($payroll[$v]['day_rate'],2)."/day"; $page_total["salary"] += $payroll[$v]['basic_pay']; $grand_total["salary"] += $payroll[$v]['basic_pay']; ?></td>
                                    <td rowspan="2">
										<?php
											
											$compe1 = $payroll[$v]['cut_off_1'];
											$wholeCompe1 =floor($compe1);
											$decimalCompe1 = $compe1 - $wholeCompe1; 
											//echo number_format($wholeCompe1, 2);
											echo number_format($payroll[$v]['basic_pay'],2);
										?>
									</td>
                                    <td valign="middle" align="right"><?php $gross_pay = $wholeCompe1; echo number_format($gross_pay,2); $page_total["gross_pay"] += $gross_pay; $grand_total["gross_pay"] += $gross_pay; ?></td> <!-- gross earned -->
                                    <td valign="middle" align="right"><?php// echo number_format($payroll[$v]['wh_tax_amt'],2); $rowtotaldeduction += $payroll[$v]['wh_tax_amt']; $page_total["wh_tax_amt"] += $payroll[$v]['wh_tax_amt']; $grand_total["wh_tax_amt"] += $payroll[$v]['wh_tax_amt']; ?></td> <!-- 5% tax -->
                                    <td valign="middle" align="right"><?php //echo number_format($payroll[$v]['wh_tax_amt'],2); $rowtotaldeduction += $payroll[$v]['wh_tax_amt']; $page_total["wh_tax_amt"] += $payroll[$v]['wh_tax_amt']; $grand_total["wh_tax_amt"] += $payroll[$v]['wh_tax_amt']; ?></td> <!-- 3% tax -->
                                    <td valign="middle" align="right"><?php $total_tax  = $payroll[$v]['wh_tax_amt'];// echo number_format($total_tax); ?></td>
                                    <td valign="middle" align="right" <?php if(isset($additional_header1[0]['name']) == ""){echo "style='display:none;'";} ?> class="tmp_col1 editable tmp_col1_fields" data-id="<?php echo $payroll[$v]["id"] ?>" <?php if(isset($additional_header1[0]['name']) == ""){echo "contenteditable";} ?>><?php echo number_format($payroll[$v]["col1_value"],2);$grand_total["col1_value"] += $payroll[$v]['col1_value'] ?></td> <!-- col 1 -->
                                    <td valign="middle" align="right" <?php if(isset($additional_header2[0]['name']) == ""){echo "style='display:none;'";}?> class="tmp_col2 editable tmp_col2_fields" data-id="<?php echo $payroll[$v]["id"] ?>" <?php if(isset($additional_header2[0]['name']) == ""){echo "contenteditable";} ?>><?php echo number_format($payroll[$v]['col2_value'],2);$grand_total["col2_value"] += $payroll[$v]['col2_value'] ?></td> <!-- col 2 -->             
                                    <td valign="middle" align="right"><?php echo number_format($payroll[$v]['pagibig_amt'],2); $rowtotaldeduction += $payroll[$v]['pagibig_amt']; $page_total["pagibig_amt"] += $payroll[$v]['pagibig_amt']; $grand_total["pagibig_amt"] += $payroll[$v]['pagibig_amt']; ?></td> <!-- hdmf -->
                                    <td valign="middle" align="right"><?php echo number_format($payroll[$v]['philhealth_amt'],2); $rowtotaldeduction += $payroll[$v]['philhealth_amt']; $page_total["philhealth_amt"] += $payroll[$v]['philhealth_amt']; $grand_total["philhealth_amt"] += $payroll[$v]['philhealth_amt']; ?></td> <!-- PHIC -->
                                    <td valign="middle" align="right"><?php $totalAbs_late =  $payroll[$v]['total_tardiness_amt']; echo number_format($totalAbs_late, 2); ?></td> <!--late & absences-->
                                    <td valign="middle" align="right" >
                                        <?php
                                        $total_deduct = $payroll[$v]['pagibig_amt']
										+ $payroll[$v]['philhealth_amt']
										+ $total_tax
										+ $totalAbs_late;
                                    
                                        echo number_format($total_deduct,2); $page_total
                                        ["total_deductions"] += $payroll[$v]['total_deduct_amt']; $grand_total["total_deductions"] += $payroll[$v]['total_deduct_amt']; 
                                        ?>
                                    </td> <!--total deductions-->
                                    <td valign="middle" align="right"><?php $net_amnt_due = $payroll[$v]['cut_off_1'] - $total_deduct; echo number_format($net_amnt_due,2); $page_total["net_amnt_due"] += $net_amnt_due; $grand_total["net_amnt_due"] += $net_amnt_due; ?></td> <!-- net amount-->
                                    <td><?php echo ""; ?></td> <!--Sig. of Recipient-->
                                </tr>  
                                <tr>
                                    <td>&nbsp;</td>
                                    <td> Account # <?php if((isset($payroll[$v]['account_number']) && $payroll[$v]['account_number'] != "")){ echo $payroll[$v]['account_number'];} ?> </td>
                                    <td>&nbsp;</td><td>&nbsp;</td>
									<td  valign="middle" align="right" ><?php echo number_format($payroll[$v]['cut_off_2'] + $decimalCompe1, 2); ?></td>
                                    <td valign="middle" align="right"><?php echo number_format($payroll[$v]['wh_tax_amt'],2); $rowtotaldeduction += $payroll[$v]['wh_tax_amt']; $page_total["wh_tax_amt"] += $payroll[$v]['wh_tax_amt']; $grand_total["wh_tax_amt"] += $payroll[$v]['wh_tax_amt']; ?></td> <!-- 5% tax -->
                                    <td valign="middle" align="right"><?php echo number_format($payroll[$v]['wh_tax_amt'],2); $rowtotaldeduction += $payroll[$v]['wh_tax_amt']; $page_total["wh_tax_amt"] += $payroll[$v]['wh_tax_amt']; $grand_total["wh_tax_amt"] += $payroll[$v]['wh_tax_amt']; ?></td> <!-- 3% tax -->
                                    <td valign="middle" align="right"><?php $total_tax  = $payroll[$v]['wh_tax_amt']; echo number_format($total_tax, 2); ?></td> <!-- total tax -->
                                    <td style="display:none" class="tmp_col1 editable">&nbsp;</td> <!-- col 1 -->
                                    <td style="display:none" class="tmp_col2 editable">&nbsp;</td> <!-- col 2 -->   
                                    <td valign="middle" align="right" >0.00</td>
									<td valign="middle" align="right" >0.00</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td  valign="middle" align="right" ><?php echo number_format($payroll[$v]['cut_off_2'], 2); ?></td>
									<td>&nbsp;</td>
                                <tr>
                                    <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                                    <td style="display:none" class="tmp_col1 editable">&nbsp;</td> <!-- col 1 -->
                                    <td style="display:none" class="tmp_col2 editable">&nbsp;</td> <!-- col 2 -->   
                                    <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
									<?php
                                if($v == $count2){
                                    $count1 += 1;
                                    break;
                                }
                            ?>
                            <?php $rows++; if($rows === 10) { $last_row = 1; } else { $last_row = 0; }
                                } ?>
                                <tr class="<?php echo "";//($last_row === 1)?"page-break":""; ?>">
                                    <td>&nbsp;</td>
                                    <td valign="middle" align="center" style="font-weight: bold;"><b>SUB-TOTAL</b></td>
                                    <td>&nbsp;</td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["salary"],2); ?></td>
                                    <td valign="middle" align="right"><?php echo ""; ?>#####</td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["gross_pay"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["wh_tax_amt"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["wh_tax_amt"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["wh_tax_amt"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold; display:none" class="tmp_col1 editable"><?php echo number_format($grand_total["col1_value"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold; display:none" class="tmp_col2 editable"><?php echo number_format($grand_total["col2_value"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["pagibig_amt"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format(($grand_total1["mp2"]),2); ?></td>
                                    <td><?php echo ""; //<!--18th column total: lates and absenses--> ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["total_deductions"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["net_amnt_due"],2); ?></td> 
                                    <td><?php echo ""; //<!--21th column total: Sig. of Recipient--> ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <ul id="menu" class="container__menu container__menu--hidden"></ul>
						<center>
                        <table style="width:100%;" id="ftTable">
                            <tr class="borderTop" style="border-right:1px solid black;border-left:1px solid black;border-top:1px solid black; padding:20px">
                                <td valign="top"><b>A</b></td>
                                <td valign="top"><b>CERTIFIED: </b>Service duly rendered as stated.</td>
                                <td style="border-right:1px solid black;">&nbsp;</td>
                                <td valign="top"><b>C</b></td>
                                <td colspan="3" style="border-right:1px solid black;"><b>APPROVED FOR PAYMENT:</b><span style="text-decoration: underline;">
                                    &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
                                    &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
                                    </span> (₱ &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;)
                                
                                </td>
                            </tr>
                            <tr style="border-left:1px solid black;">
                                <td></td>
                                <td valign="bottom" colspan="2" style="padding:20px;">
                                    <table width="100%">
                                    <?php
                                            $box_a = "";
                                            if($signatories_a[0]["employee_name"] == "ARCHIE EDSEL C. ASUNCION " ||
                                            $signatories_a[0]["employee_name"] == "JUAN Y. CORPUZ JR." ){
                                                $box_a = 'ATTY. '.$signatories_a[0]["employee_name"];
                                            }
                                            else{
                                                $box_a = $signatories_a[0]["employee_name"];
                                            }
                                        ?>
                                        <tr>
                                            <td width="45%" style="font-size:12px;text-align:center;border-bottom:1px solid black !important;"><?php echo (sizeof($signatories_a) > 0) ? $box_a : ''; //name ?></td>
                                            <td width="10%"></td>
                                            <td width="45%" style="text-align:center;border-bottom:1px solid black;"></td>
                                        </tr>
                                        <tr>
                                            <td width="45%" style="font-size:12px;text-align:center;">
                                                <?php echo $signatories_a[0]["position_designation"] != "" || $signatories_a[0]["position_designation"] != null ? $signatories_a[0]["position_designation"] : $signatories_a[0]["position_title"]; //position ?>
                                                <!-- <br>
                                                <?php //echo $signatories[0]["division_designation"] != "" || $signatories[0]["division_designation"] != null ? $signatories[0]["division_designation"] : $signatories[0]["department"]; //position ?>
                                                Authorized Official -->
                                            </td>
                                            <td width="10%"></td>
                                            <td width="45%" style="font-size:12px;text-align:center;">Date</td>
                                        </tr>
                                    </table>
                                </td>
                                <td valign="bottom" colspan="5" style="border-left:1px solid black !important;border-right:1px solid black !important; padding:20px;">
                                    <table width="100%">
                                        <tr>
                                            <td width="50%" style="font-size:12px;text-align:center;border-bottom:1px solid black"><?php echo "DR. ".$signatories[2]["employee_name"].", CESO III"; //name ?></td>
                                            <td width="10%"></td>
                                            <td width="40%" style="font-size:12px;text-align:center;border-bottom:1px solid black;"></td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="font-size:12px;text-align:center;">
                                                <?php echo $signatories[2]["position_designation"] != "" || $signatories[2]["position_designation"] != null ? $signatories[2]["position_designation"] : $signatories[2]["position_title"]; //position ?>
                                                <!-- <br>
                                                <?php //echo $signatories[2]["division_designation"] != "" || $signatories[2]["division_designation"] != null ? $signatories[2]["division_designation"] : $signatories[2]["department"]; //position ?>
                                                Head of Agency/Authorized Representative -->
                                            </td>
                                            <td width="10%"></td>
                                            <td width="40%" style="font-size:12px;text-align:center;">Date</td>
                                        </tr>
                                    </table>
                                </td>
                                <!-- <td valign="top" colspan="1" style="border-right:1px solid black;">
                                </td> -->
                            </tr>

                            <tr class="borderTop" style="border-top:1px solid black !important;border-left:1px solid black;">
                                <td width="2%" valign="top"><b>B</b></td>
                                <td width="37%"><b>CERTIFIED: </b>Supporting documents complete and proper, and cash<br>available in the amount of P ___________</td>
                                <td width="10%" style="border-right:1px solid black">&nbsp;</td>
                                <td width="2%" valign="top"><b>D</b></td>
                                <td width="37%"><b>CERTIFIED:</b> Each employee whose name appears above has<br>been paid the amount indicated opposite on his/her name.</span></td>
                                <td width="2%" valign="top" style="border-left:1px solid black;"><b>E</b></td>
                                <td width="10%" style="border-right:1px solid black">&nbsp;</td>

                            </tr>
                            
                            <tr style="height:35px;border-bottom: 1px solid black;border-left:1px solid black;">
                                <td></td>
                                <td valign="bottom" colspan="2" style="padding:20px">
                                    <table width="100%">
                                        <tr>
                                            <td width="45%" style="font-size:12px;text-align:center;border-bottom:1px solid black !important;"><?php echo $signatories[1]["employee_name"]; //name ?></td>
                                            <td width="10%"></td>
                                            <td width="45%" style="text-align:center;border-bottom:1px solid black;"></td>
                                        </tr>
                                        <tr>
                                            <td width="45%" style="font-size:12px;text-align:center;">
                                                <?php echo $signatories[1]["position_designation"] != "" || $signatories[1]["position_designation"] != null ? $signatories[1]["position_designation"] : $signatories[1]["position_title"]; //position ?>
                                                <!-- <br>
                                                <?php //echo $signatories[1]["division_designation"] != "" || $signatories[1]["division_designation"] != null ? $signatories[1]["division_designation"] : $signatories[1]["department"]; //position ?> -->
                                            </td>
                                            <td width="10%"></td>
                                            <td width="45%" style="font-size:12px;text-align:center;">Date</td>
                                        </tr>
                                    </table>
                                </td>
                                <td valign="top" colspan="2"  style="border-left:1px solid black !important;padding:20px">
                                    <table width="100%" >
                                        <tr>
                                            <td width="45%" style="font-size:12px;text-align:center;border-bottom:1px solid black !important;"><?php echo $signatories[3]["employee_name"]; //name ?></td>
                                            <td width="10%"></td>
                                            <td width="45%" style="font-size:12px;text-align:center;border-bottom:1px solid black !important;"></td>
                                        </tr>
                                        <tr>
                                            <td width="45%" style="font-size:12px;text-align:center;">
                                                <?php echo $signatories[3]["position_designation"] != "" || $signatories[3]["position_designation"] != null ? $signatories[3]["position_designation"] : $signatories[3]["position_title"]; //position ?>
                                                <!-- <br>
                                                <?php //echo $signatories[3]["division_designation"] != "" || $signatories[3]["division_designation"] != null ? $signatories[3]["division_designation"] : $signatories[3]["department"]; //position ?> -->
                                            </td>
                                            <td width="10%"></td>
                                            <td width="45%" style="font-size:12px;text-align:center;">Date</td>
                                        </tr>
                                    </table>
                                </td>
                                <td valign="top" colspan="3"  style="font-size:12px;border-left:1px solid black !important;border-right:1px solid black;">
                                    <table width="100%" >
                                        <tr>
                                            <td style="font-size:12px;">ORS/BURS No. : _______________</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:12px;">Date : _______________________</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:12px;">JEV No. : _____________________</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:12px;">Date : _______________________</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <div style='page-break-before:always'></div>
						<?php
                            $count1 += 14;
                            $count2 += 15;
                           }
                        ?>
							</center>
                        <!-- GSIS DEDUCTIONS / NWRBEA / LOW COST HOUSING -->
                        <table style="width:100%; border-bottom:0px;" id="tblGSIS" class="no-print">
                            <tr>
                                <td>Ser. No.</td>
                                <td>NAME</td>
                                <td>EMP. NO.</td>
                                <td>SALARIES & WAGES PER DAY</td>
                                <td></td>
                                <td></td>
                                <td>5% TAX</td>
                                <td>3% TAX</td>
                                <td>HDMF</td>
                                <td>PHIC</td>
                                <td>LATES & ABSENCES</td>
                            </tr>
                            <?php
                             foreach ($payroll as $k => $v) {                            
                            ?>
                            <tr>
                                <td><?php echo ($k+1); ?></td>
                                <td><?php echo ((isset($v['last_name']) && $v['last_name'] != "")?$this->Helper->decrypt($v['last_name'],$v['employee_id']):"") . ((isset($v['first_name']) && $v['first_name'] != "")?"&emsp;&nbsp;".$this->Helper->decrypt($v['first_name'],$v['employee_id']):"") . ((isset($v['middle_name']) && $v['middle_name'] != "")?"&nbsp;".$this->Helper->decrypt($v['middle_name'],$v['employee_id']):"") //. "<br><span style='font-weight: normal;'>" . strtoupper(@$v['position_name']) . "</span>"; ?>
                                </td>
                                <td ><?php echo (isset($v['employee_id_number']) && $v['employee_id_number'] != "")?$this->Helper->decrypt($v['employee_id_number'],$v['employee_id']):""; ?></td>
                                <td valign="middle" align="right"><?php $val = 0; $val = get_key(2,$loanDeductions[$v['employee_id']]); echo number_format($val,2); $rowtotaldeduction += get_key(2,$loanDeductions[$v['employee_id']]); $page_total["gsis_consolidated"] += $val; $grand_total["gsis_consolidated"] += $val; ?></td>
                                <td valign="middle" align="right"><?php $val = 0; $val = get_key(41,$loanDeductions[$v['employee_id']]); echo number_format($val,2); $rowtotaldeduction += $val; $page_total["gfal"] += $val; $grand_total["gfal"] += $val; ?></td>
                                <td valign="middle" align="right"><?php $val = 0; $val = get_key(35,$loanDeductions[$v['employee_id']]); echo number_format($val,2); $rowtotaldeduction += $val; $page_total["mpl"] += $val; $grand_total["mpl"] += $val; ?></td>
                                <td valign="middle" align="right"><?php $val = 0; $val = get_key(4,$loanDeductions[$v['employee_id']]); echo number_format($val,2); $rowtotaldeduction += $val; $page_total["gsis_emergency"] += $val; $grand_total["gsis_emergency"] += $val; ?></td>                                
                                <td valign="middle" align="right"><?php $val = 0; $val = get_key(40,$loanDeductions[$v['employee_id']]); echo number_format($val,2); $rowtotaldeduction += $val; $page_total["cpl"] += $val; $grand_total["cpl"] += $val; ?></td>
                                <td valign="middle" align="right"><?php $val = 0; $val = get_key(24,$loanDeductions[$v['employee_id']]); echo number_format($val,2); $rowtotaldeduction += $val; $page_total["gsis_policy"] += $val; $grand_total["gsis_policy"] += $val; ?></td>
                                <td valign="middle" align="right"><?php $val = 0; $val = get_key(39,$loanDeductions[$v['employee_id']]); echo number_format($val,2); $rowtotaldeduction += $val; $page_total["opt_pol_loan"] += $val; $grand_total["opt_pol_loan"] += $val; ?></td>
                                <td valign="middle" align="right"><?php $val = 0; $val = get_key(18,$loanDeductions[$v['employee_id']]); echo number_format($val,2); $rowtotaldeduction += $val; $page_total["opt_ins"] += $val; $grand_total["opt_ins"] += $val; ?></td>
                            </tr>
                            <?php $rows++; if($rows === 10) { $last_row = 1; } else { $last_row = 0; } 
                            } ?>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>TOTAL</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>TOTAL PER PAYROLL (PAGE)</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>DIFFERENCE</td>
                                <td>&nbsp;</td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format(($grand_total["gsis_consolidated"]),2);  ?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format(($grand_total["gfal"]),2); ?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format(($grand_total["mpl"]),2); ?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["gsis_emergency"],2); ?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["cpl"],2); ?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["gsis_policy"],2); ?>                                
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["opt_pol_loan"],2); ?></td>                               
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["opt_ins"],2); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php 

function get_key($id,$arr){
    $key = array_search($id,array_column($arr,"sub_loans_id"));
    if($key === false){
        return 0;
    }else{
        return $arr[$key]["amount"];
    }
    return 0;
}

?>                        
