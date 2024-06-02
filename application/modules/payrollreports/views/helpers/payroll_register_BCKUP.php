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
<div class="row" style="overflow-x:auto;">
    <div class="col-md-12">
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
                    #footer{
							display: table-footer-group;
							
					}
                    /* .tmp_col2, .tmp_col1{
                        text-align:center;
                    } */
                    .total_col{
                        font-weight: bold;
                    }
                    .smallBottom{
                        position: relative;
                    }
                    .smallBottom::before{
                        content: '';
                        position: absolute;
                        bottom: 0;
                        right: 0;
                        border-bottom: 1px solid black;
                        width: 10%;
                    }
                    .smallBottomLeft{
                        position: relative;
                    }
                    .smallBottomLeft::before{
                        content: '';
                        position: absolute;
                        bottom: 0;
                        left: 0;
                        border-bottom: 1px solid black;
                        width: 40%;
                    }
                    .smallBottomTop{
                        position: relative;
                    }
                    .smallBottomTop::before{
                        content: '';
                        position: absolute;
                        top: 0;
                        right: 0;
                        border-bottom: 1px solid black;
                        width: 50%;
                    }
                    
                    .smallBottom2{
                        position: relative;
                    }
                    .smallBottom2::before{
                        content: '';
                        position: absolute;
                        bottom: 0;
                        right: 0;
                        border-bottom: 1px solid black;
                        width: 50%;
                    }
                    #bracket {
                    display: block;
                    font-weight: 20;
                    font-size: 100px;
                    text-align: center;
                    -webkit-transform: rotate(90deg);
                    -moz-transform: rotate(90deg);
                    -o-transform: rotate(90deg);
                    -ms-transform: rotate(90deg);
                    transform: rotate(90deg);
                }
                </style>
                  <?php

                        $payrollCategories = [
                            'SALARY', 'PERA', 'Gross', 'RLIP', 'GSIS', 'LCHL', 'TAX', 'PHIL', 'HDMF',
                            'MP2', 'MPL', 'LBP', 'NWRBEA', 'LATE', 'DEDUCTION', 'NET',
                            'FirstCut', 'SecondCut'
                        ];

                        $loanCategories = [
                            'conso_loan', 'GFAL', 'MPL', 'EMERGENCY_LOAN', 'CPL', 'POLICY_LOAN',
                            'OPT_PL_LOAN', 'OPT_INSURANCE', 'EDU_ASSISTANCE', 'Membership', 'Kamanggagawa',
                            'PROJECT', 'CASH_LOAN', 'EMERGENCY', 'GSIS', 'NHMFC', 'PAG_IBIG'
                        ];

                        $totals = [
                            'conso_loan', 'GFAL', 'MPL', 'EMERGENCY_LOAN', 'CPL', 'POLICY_LOAN',
                            'OPT_PL_LOAN', 'OPT_INSURANCE', 'EDU_ASSISTANCE', 'Membership', 'Kamanggagawa',
                            'PROJECT', 'CASH_LOAN', 'EMERGENCY', 'GSIS', 'NHMFC', 'PAG_IBIG'
                        ];

                        $secondTable = [];
                        $counter = 0;

                        for ($x = 0; $x < $totalNum; $x++) {
                            foreach ($payrollCategories as $category) {
                                $secondTable[$category][] = $payroll[$counter++];
                            }

                            foreach ($loanCategories as $loanCategory) {
                                $secondTable[$loanCategory][] = $payroll[$counter++];
                            }

                            $secondTable['totalNum'][] = $x + 1;
                        }
                    ?>
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
                                $rows2 = 0;
                                $last_row2 = 0;
                                //var_dump('test');
                            ?>

                        <table style="width:100%;border-bottom:0px;" id="hdTable">
                            <thead>
                                <tr>
                                    <td align="center" colspan="9"><label>GENERAL PAYROLL</label></td>
                                </tr>
                                <tr>
                                    <td align="center" colspan="9"><label>NATIONAL WATER RESOURCES BOARD</label></td>
                                </tr>
                                <tr>
                                    <td><br></td>
                                </tr>
                                <tr>
                                    <td align="center" style="text-decoration: underline;" colspan="9"><b><?php echo "&emsp;".date('F d, Y',strtotime(@$payroll_period[0]['start_date'])); ?></b> &emsp; to &emsp; <b><?php echo date('F d, Y',strtotime(@$payroll_period[0]['end_date'])); ?></b>&emsp;</td>
                                </tr>
                                <tr>
                                    <td style="text-align:center;font-size: 11px;font-style: italic;padding-top:0px;" colspan="9">Period</td>
                                </tr>
                                <tr>
                                    <td><br></td>
                                </tr>
                                <tr>
                                    <td><b>ENTITY NAME: NATIONAL WATER RESOURCES BOARD</b></td>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td>Payroll No.:___________________</td>
                                </tr>
                                <tr>
                                    <td><b>Fund Cluster: </b><u>01</u></td>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td>Sheet__________ of __________ Sheets</td>
                                </tr>
                                <tr>
                                    <td><b>Division: </b><b class="division_label"> <p class="division_label"></p></b></td>
                                </tr>
                                <tr>
                                    <td align="left">We Acknowledge receipt of cash opposite our name as full compensation for services rendered for the period.</td>
                                </tr>
                            </thead>
                        </table>
                        <div class="table-container"><?php $page++; ?>
                        <table style="width:100%;" id="tmpTable">
                            <thead>
                                <tr>
                                    <th style="text-align:center;" rowspan="2"><b>Ser. No.</b></th>
                                    <th style="text-align:center;width:100px;" rowspan="2">NAME</th>
                                    <th style="text-align:center;" rowspan="2"><b>Emp. No.</b></th>
                                    <th style="text-align:center;" colspan="3"><b>COMPENSATIONS</b></th>
                                    <?php
                                            $colspan = 12;
                                            if(isset($additional_header1[0]['name']) != ""){
                                                $colspan += 1;
                                            }
                                            if(isset($additional_header2[0]['name']) != ""){
                                                $colspan += 1;
                                            }
                                    ?>
                                    <!-- <th valign="top" style="text-align:center;" rowspan="2">BASIC SALARY</th>
                                    <th valign="top" style="text-align:center;" rowspan="2">GROSS AMOUNT EARNED</th>
                                    <th valign="top" style="text-align:center;" rowspan="2">OTHER EARNINGS</th> -->

                                    <th style="text-align:center;" id="deduction_header" colspan="<?php echo $colspan;?>"><b>DEDUCTIONS</b></th>
                                    <th colspan="3"></th>
                                </tr>
                                <tr>
                                    <th style="text-align:center;">Salaries &<br> Wages-<br>Regular</th>
                                    <th style="text-align:center;"></th>
                                    <th style="text-align:center;">ALLOW.<br> PERA</th>
                                    <th style="text-align:center;">Gross<br> Amount<br> Earned</th>
                                    <th style="text-align:center;">RLIP</th>
                                    <th style="text-align:center;">GSIS<br> LOANS</th>
                                    <th style="text-align:center;">LCHL</th>
                                    <th style="text-align:center;">TAX</th>
                                    <th style="text-align:center;">PHIL</th>
                                    
                                    <th <?php if(isset($additional_header1[0]['name']) == ""){echo "style='display:none;text-align:center'";} ?> id="header_1" class="tmp_col1  editableHeader" data-type="header_1" 
                                        data-period="<?php echo $payroll_period_id ?>" data-division="<?php echo $payroll[0]['division_id'] ?>"  
                                        data-pay_basis="<?php echo $pay_basis ?>" contenteditable>
                                        
                                        <?php echo isset($additional_header1[0]['name']) ? $additional_header1[0]['name'] : "Header_1";?>
                                    </th>
                                    <th <?php if(isset($additional_header2[0]['name']) == ""){echo "style='display:none;text-align:center'";}?> id="header_2" class="tmp_col2  editableHeader" data-type="header_2" 
                                        data-period="<?php echo $payroll_period_id ?>" data-division="<?php echo $payroll[0]['division_id'] ?>"  
                                        data-pay_basis="<?php echo $pay_basis ?>" contenteditable>
                                        <?php echo isset($additional_header2[0]['name']) ? $additional_header2[0]['name'] : "Header_2";?>
                                    </th>
                                    <th style="text-align:center;">HDMF</th>
                                    <th style="text-align:center;">MP2</th>
                                    <th style="text-align:center;">MPL</th>
                                    <th style="text-align:center;">LBP<br> LIVELIHOOD</th>
                                    <th style="text-align:center;"> NWRBEA<br>(Contri./Loans)</th>
                                    <th style="text-align:center;">LATES &<br> ABSENSES</th>
                                    <th style="text-align:center;" rowspan="2"><b>Total<br> Deductions</b></th>
                                    <th style="text-align:center;" rowspan="3" style="border: 1px solid black;"><b>Net<br> Amount<br> Due</b></th>
                                    <!-- <th style="text-align:center;" rowspan="3" style="border: 1px solid black;"><b>Remarks</b></th> -->
                                    <th style="text-align:center;" rowspan="3"><b>Sig. of<br> Recipient</b></th>
                                </tr>
                            </thead>
                            <?php
                                $page_total = array(
                                    'salary'=>0.00, 'gross_pay'=>0.00, 'wh_tax_amt'=>0.00, 'sss_gsis_amt'=>0.00, 'sos_loan'=>0.00, 'gsis_educational'=>0.00, 'mpl'=>0.00, 'lbp1'=>0.00, 'gsis_ecard_plus'=>0.00, 'opt_ins'=>0.00, 'cpl'=>0.00,  'opt_pol_loan'=>0.00, 'gfal'=>0.00, 'gsis_emergency'=>0.00,
                                    'gsis_policy'=>0.00, 'gsis_optional_loan'=>0.00, 'optional_unltd'=>0.00, 'gsis_consolidated'=>0.00, 'gsis_nhmfc'=>0.00, 'union_dues_amt'=>0.00, 'pagibig_amt'=>0.00, 'pagibig_loans'=>0.00,
                                    'philhealth_amt'=>0.00,'gsis_housing'=>0.00, 'pag_ibig_housing'=>0.00, 'total_deductions'=>0.00, 'net_amnt_due'=>0.00, 'other_earnings_amt'=>0.00, 'other_deductions_amt'=>0.00, 'tardiness_amt'=>0.00,
                                    'pera_amt'=>0.00, 'gross_pera_amt'=>0.00 , 'mp2'=>0.00, 'lbp2'=>0.00, 'nwrbea_project'=>0.00, 'nwrbea_cashLoan'=>0.00, 'nwrbea_emergency'=>0.00, 'membership'=>0.00, 'kamanggagawa'=>0.00
                                );
                                $grand_total = array(
                                    'salary'=>0.00, 'gsis_housing'=>0.00, 'pag_ibig_housing'=>0.00, 'gross_pay'=>0.00, 'wh_tax_amt'=>0.00, 'sss_gsis_amt'=>0.00, 'sos_loan'=>0.00, 'gsis_educational'=>0.00, 'lbp1'=>0.00, 'mpl'=>0.00, 'gsis_ecard_plus'=>0.00, 'opt_pol_loan'=>0.00, 'opt_ins'=>0.00, 'cpl'=>0.00, 'gfal'=>0.00, 'gsis_emergency'=>0.00,
                                    'gsis_policy'=>0.00, 'gsis_optional_loan'=>0.00, 'optional_unltd'=>0.00, 'gsis_consolidated'=>0.00, 'gsis_nhmfc'=>0.00,'union_dues_amt'=>0.00,  'pagibig_amt'=>0.00, 'pagibig_loans'=>0.00,
                                    'philhealth_amt'=>0.00, 'total_deductions'=>0.00, 'net_amnt_due'=>0.00, 'other_earnings_amt'=>0.00, 'other_deductions_amt'=>0.00, 'tardiness_amt'=>0.00
                                    ,'pera_amt'=>0.00,'gross_pera_amt'=>0.00,'nwrbea_project1'=>0.00, 'lbp2'=>0.00, 'nwrbea_cashLoan'=>0.00, 'nwrbea_emergency'=>0.00, 'col1_value'=>0.00, 'col2_value'=>0.00 , 'late_amt'=>0.00 , 'gsis_total'=>0.00 , 'lchl_total'=>0.00 , 'cut_off_total'=>0.00,
                                    'first_cut_off'=>0.00,'second_cut_off'=>0.00, 'membership'=>0.00, 'kamanggagawa'=>0.00
                                );

                                $grand_total1 = array(
                                    'mpl'=>0.00, 'mp2'=>0.00
                                );

                                $grand_total2 = array(
                                    'lbp1'=>0.00, 'lbp2'=>0.00
                                );

                                $grand_total3 = array(
                                    'conso_loan'=>0.00, 'gfal'=>0.00, 'mpl'=>0.00, 'emergency_loan'=>0.00, 'cpl'=>0.00, 'policy_loan'=>0.00, 'opt_loan'=>0.00, 'opt_insurance'=>0.00, 'edu_assistance'=>0.00, 'membership'=>0.00, 'kamanggagawa'=>0.00, 'project'=>0.00, 'cash_loan'=>0.00
                                    , 'emergency'=>0.00, 'gsis'=>0.00, 'nhmfc'=>0.00, 'pagibig'=>0.00
                                );
                                $grand_total4 = array(
                                    'conso_loan'=>0.00, 'gfal'=>0.00, 'mpl'=>0.00, 'emergency_loan'=>0.00, 'cpl'=>0.00, 'policy_loan'=>0.00, 'opt_loan'=>0.00, 'opt_insurance'=>0.00, 'edu_assistance'=>0.00, 'membership'=>0.00, 'kamanggagawa'=>0.00, 'project'=>0.00, 'cash_loan'=>0.00
                                    , 'emergency'=>0.00, 'gsis'=>0.00, 'nhmfc'=>0.00, 'pagibig'=>0.00
                                );
                                $grand_total5 = array(
                                    'nwrbea_project'=>0.00, 'nwrbea_project1'=>0.00,
                                );

                            ?>
                            <tbody>
                                <!-- <tr><td colspan="25" align="center"><span id="division_label" style="font-weight: bolder;font-style: italic;"></span></td></tr>
                                <tr><td colspan="25">&nbsp;</td></tr> -->
                            <?php 
                            $lastPersonSalary = 0; 
                            $lastPersonLate = 0; 
                                for($v = $count1 ; $v < sizeof($payroll); $v++ )
                            // foreach ($payroll as $k => $v) 
                          
                            { 
                                if($v + 1 == sizeof($payroll)){
                                    $lastPersonSalary = $payroll[$v]['basic_pay'];
                                    $$lastPersonLate = $payroll[$v]['total_tardiness_amt'];
                                }
                                //echo $payroll[$v]['employee_id'];
                                //var_dump(sizeof($payroll_2[$payroll[$v]['employee_id']]));
                                    $total_gsis = get_key(2,$loanDeductions[$payroll[$v]['employee_id']]) + get_key(41,$loanDeductions[$payroll[$v]['employee_id']]) + get_key(35,$loanDeductions[$payroll[$v]['employee_id']]) + get_key(4,$loanDeductions[$payroll[$v]['employee_id']]) + get_key(24,$loanDeductions[$payroll[$v]['employee_id']]) + get_key(39,$loanDeductions[$payroll[$v]['employee_id']]) + get_key(18,$loanDeductions[$payroll[$v]['employee_id']]) + get_key(8,$loanDeductions[$payroll[$v]['employee_id']]) + get_key(40,$loanDeductions[$payroll[$v]['employee_id']]);
                                    $total_lchl = get_key(7,$loanDeductions[$payroll[$v]['employee_id']]) + get_key(20,$loanDeductions[$payroll[$v]['employee_id']]);
                                    // $total_mp2 += $payrollinfo[$v['employee_id']]['data'][0]['mp2_contribution'];
                                    $rowtotaldeduction = 0; ?>
                                <tr>
                                    <td valign="top" style="text-align:center;"><?php echo ($v+1); ?></td>
                                    <td valign="top" style="text-align:left;font-weight: bold;"><?php echo ((isset($payroll[$v]['last_name']) && $payroll[$v]['last_name'] != "")?$this->Helper->decrypt($payroll[$v]['last_name'],$payroll[$v]['employee_id']):"") . ((isset($payroll[$v]['first_name']) && $payroll[$v]['first_name'] != "")?"&nbsp;".$this->Helper->decrypt($payroll[$v]['first_name'],$payroll[$v]['employee_id']):"") . ((isset($payroll[$v]['middle_name']) && $payroll[$v]['middle_name'] != "")?"&nbsp;".$this->Helper->decrypt($payroll[$v]['middle_name'],$payroll[$v]['employee_id']):"").((isset($payroll[$v]['extension']) && $payroll[$v]['extension'] != "")?"&nbsp;".$this->Helper->decrypt($payroll[$v]['extension'],$payroll[$v]['employee_id']):"") //. "<br><span style='font-weight: normal;'>" . strtoupper(@$v['position_name']) . "</span>"; ?></td>
                                    <td valign="middle" style="text-align:center;"> <?php echo (isset($payroll[$v]['employee_id_number']) && $payroll[$v]['employee_id_number'] != "")?$this->Helper->decrypt($payroll[$v]['employee_id_number'],$payroll[$v]['employee_id']):""; ?> </td>
                                    <td valign="middle" align="right" id="<?php echo $payroll[$v]["id"]."_basic_pay"; ?>"><?php echo number_format($payroll[$v]['basic_pay'],2); $page_total["salary"] += $payroll[$v]['basic_pay']; $grand_total["salary"] += $payroll[$v]['basic_pay']; ?></td>
                                    <!-- <td valign="middle" align="right"><?php //$gross_pay = $v['basic_pay']/2; echo number_format($gross_pay,2); $page_total["gross_pay"] += $gross_pay; $grand_total["gross_pay"] += $gross_pay; ?></td> -->
                                    <?php
                                         $totalAbs_late = 0;

                                       // if($count1 + 1 <= sizeof($payroll_2[$payroll[$v]['employee_id']])){
                                            //echo $payroll_2[$payroll[$v]['employee_id']][0]['abst_amt'] ;
                                            $totalAbs_late = $payroll[$v]['total_tardiness_amt']; 
                                        // }
									
                                        $total_deduct = $payroll[$v]['sss_gsis_amt'] //RLIP
                                        + $total_gsis //GSIS LOANS
                                        + $total_lchl //LCHL
                                        + $payroll[$v]["col1_value"]
                                        + $payroll[$v]["col2_value"]
                                        + $payroll[$v]['wh_tax_amt'] //TAX
                                        + $payroll[$v]['philhealth_amt'] //PHIL
                                        + $payroll[$v]['pagibig_amt'] //HDMF
                                        + $payroll[$v]['mp2_contribution_amt']
                                        + get_key(21,$loanDeductions[$payroll[$v]['employee_id']])
                                        + get_key(39,$loanDeductions[$payroll[$v]['employee_id']])
										+ $payroll[$v]['union_dues_amt']
										+ $totalAbs_late
                                        ; //MPL

										$compe1 = ($payroll[$v]['basic_pay'] - $total_deduct) / 2;
										$wholeCompe1 = $compe1;
										//$decimalCompe1 = $compe1 - $wholeCompe1; 
                                        var_dump($wholeCompe1);
									?>
                                    <input type="hidden" name="cut_off_1st" id=<?php echo $payroll[$v]["id"]."_cut_off_1st";?> value="<?php echo $wholeCompe1; ?>">
									<td valign="middle" align="right" data-value="<?php echo $wholeCompe1; ?>" id="<?php echo $payroll[$v]["id"]."_cut_off1"; ?>">
										<?php
											echo number_format($wholeCompe1, 2);
                                            $grand_total['cut_off_total'] += $wholeCompe1;
                                            $grand_total['first_cut_off'] += $wholeCompe1;
										?>
                                        
									</td>
                                    <td valign="middle" align="right" id="<?php echo $payroll[$v]["id"]."_pera_amt"; ?>"><?php  $pera_amt = $payroll[$v]['pera_amt'] / 2; echo number_format($pera_amt,2) ; $page_total["pera_amt"] += $payroll[$v]['pera_amt']; $grand_total["pera_amt"] += $payroll[$v]['pera_amt']; ?></td>
                                    <!-- <td valign="middle" align="right"><?php //echo number_format($v['total_other_earning_amt'],2); $page_total["other_earnings_amt"] += $v['total_other_earning_amt']; $grand_total["other_earnings_amt"] += $v['total_other_earning_amt']; ?></td> -->
                                    <td valign="middle" align="right" id="<?php echo $payroll[$v]["id"]."_cut_off1_gross"; ?>">
                                    <?php $gross_pay = $wholeCompe1 + $pera_amt ; echo number_format($gross_pay,2); $page_total["gross_pay"] += $gross_pay; $grand_total["gross_pay"] += $gross_pay; ?>
                                    
                                    </td>
                                    <input type="hidden" name="cut_off_1st" id=<?php echo $payroll[$v]["id"]."_cut_off_1st_gross";?> value="<?php echo $gross_pay; ?>">   
                                    <td valign="middle" align="right"><?php // $sss_gsis_amt =$payroll[$v]['sss_gsis_amt'] ; echo number_format($sss_gsis_amt,2); $rowtotaldeduction += $payroll[$v]['sss_gsis_amt']; $page_total["sss_gsis_amt"] += $payroll[$v]['sss_gsis_amt']; $grand_total["sss_gsis_amt"] += $payroll[$v]['sss_gsis_amt']; ?> </td>

                                    <td valign="middle" align="right"><?php //$cut_total_sis = $total_gsis ; echo number_format($cut_total_sis,2); ?></td> 
                                    <td valign="middle" align="right"><?php// $cut_total_lchl = $total_lchl ;  echo number_format($cut_total_lchl,2) ?></td>  
                                    <td valign="middle" align="right"><?php// $cut_wh_tax_amt = $payroll[$v]['wh_tax_amt'] ; echo number_format($cut_wh_tax_amt,2); $rowtotaldeduction += $payroll[$v]['wh_tax_amt']; $page_total["wh_tax_amt"] += $payroll[$v]['wh_tax_amt']; $grand_total["wh_tax_amt"] += $payroll[$v]['wh_tax_amt']; ?></td>
                                    <td valign="middle" align="right"><?php// $cut_philhealth_amt = $payroll[$v]['philhealth_amt'] ; echo number_format($cut_philhealth_amt,2); $rowtotaldeduction += $payroll[$v]['philhealth_amt']; $page_total["philhealth_amt"] += $payroll[$v]['philhealth_amt']; $grand_total["philhealth_amt"] += $payroll[$v]['philhealth_amt']; ?></td>
                                    <td valign="middle" align="right"  <?php if(isset($additional_header1[0]['name']) == ""){echo "style='display:none;'";} ?> class="tmp_col1"></td>
                                    <td valign="middle" align="right" <?php if(isset($additional_header2[0]['name']) == ""){echo "style='display:none;'";}?> class="tmp_col2"></td>
                                    <!-- <td valign="middle" align="right" style="display:none" class="tmp_col1 editable" data-id="<?php echo $payroll[$v]["id"] ?>" contenteditable><?php //$cut_col1_value = $payroll[$v]["col1_value"] / 2; echo number_format($cut_col1_value2);$grand_total["col1_value"] += $payroll[$v]['col1_value'] ?></td>
                                    <td valign="middle" align="right" style="display:none" class="tmp_col2 editable" data-id="<?php echo $payroll[$v]["id"] ?>" contenteditable><?php //$cut_col2_value = $payroll[$v]["col2_value"] / 2;  echo number_format($cut_col2_value,2);$grand_total["col2_value"] += $payroll[$v]['col2_value'] ?></td>
                                                 -->
                                    <td valign="middle" align="right"><?php// $cut_pagibig_amt = $payroll[$v]['pagibig_amt'] ; echo number_format($cut_pagibig_amt,2); $rowtotaldeduction += $payroll[$v]['pagibig_amt']; $page_total["pagibig_amt"] += $payroll[$v]['pagibig_amt']; $grand_total["pagibig_amt"] += $payroll[$v]['pagibig_amt']; ?></td>
                                    <td valign="middle" align="right"><?php// $val = 0; $val = $payrollinfo[$payroll[$v]['employee_id']]['data'][0]['mp2_contribution'] ; echo number_format($val,2); $rowtotaldeduction += $val; $page_total["mp2"] += $val; $grand_total1["mp2"] += $val;  ?></td> 
                                                                        <td valign="middle" align="right"><?php// $val = 0; $val = get_key(21,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val ,2); $rowtotaldeduction += $val; $page_total["mpl"] += $val; $grand_total1["mpl"] += $val; ?></td>  
                                    <td valign="middle" align="right"><?php //$val = 0; $val = get_key(39,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val ,2); $rowtotaldeduction += $val; $page_total["lbp2"] += $val; $grand_total2["lbp2"] += $val; ?></td>
                                    <td><?php //echo number_format($payroll[$v]['union_dues_amt'] , 2); ?></td>
                                    <?php
                                            //if($count1 + 1 <= sizeof($payroll_2[$payroll[$v]['employee_id']])){

                                                $totalAbs_late =$payroll[$v]['total_tardiness_amt']; 
                                           // }
                                    ?>
                                    <td><?php //$totalAbs_late = $payroll_2[$payroll[$v]['employee_id']][0]['abst_amt'] + $payroll_2[$payroll[$v]['employee_id']][0]['late_amt'] + $payroll_2[$payroll[$v]['employee_id']][0]['total_tardiness_amt']; //echo number_format($totalAbs_late, 2); ?></td>

                                    <td valign="middle" align="right">
                                        <?php
                                        $total_deduct = $payroll[$v]['sss_gsis_amt'] //RLIP
                                        + $total_gsis //GSIS LOANS
                                        + $total_lchl //LCHL
                                        + $payroll[$v]["col2_value"]
                                        + $payroll[$v]["col1_value"]
                                        + $payroll[$v]['wh_tax_amt'] //TAX
                                        + $payroll[$v]['philhealth_amt'] //PHIL
                                        + $payroll[$v]['pagibig_amt'] //HDMF
                                        + $payroll[$v]['mp2_contribution_amt'] //MP2
                                        + get_key(21,$loanDeductions[$payroll[$v]['employee_id']])
                                        + get_key(39,$loanDeductions[$payroll[$v]['employee_id']])
										+ $payroll[$v]['union_dues_amt']
										+ $totalAbs_late
                                        ; //MPL
                                      
                                      
                                         
                                        ?>
                                    </td>
                                    <td valign="middle" align="right" id="<?php echo $payroll[$v]["id"]."_cut_off1_net"; ?>"><?php $net_amt_due = $gross_pay; echo number_format($net_amt_due,2); $page_total["net_amnt_due"] += $net_amt_due; $grand_total["net_amnt_due"] += $net_amt_due; ?></td>
                                    <td><?php echo ""; //<!--21th column Sig. of Recipient--> ?></td> 
                                    <!-- <td><?php echo ""; ?></td>  -->
                                
                                </tr>  
                                <tr>
                                    <td>&nbsp;</td>
                                    <td ><?php echo @$payroll[$v]['position_name']; ?></td>
                                    <td></td>
									<td></td>
									<td align="right"  data-value="<?php echo ($payroll[$v]['basic_pay'] - $compe1); ?>" id="<?php echo $payroll[$v]["id"]."_cut_off2"; ?>">
                                    <?php 
                                        if($compe1 < 0 ){
                                            $compe1Value = abs($compe1);
                                        }else{
                                            $compe1Value = $compe1;
                                        }
                                        $second_value = $payroll[$v]['basic_pay'] - $compe1Value;
                                        echo number_format($second_value, 2); 
                                        $cut_off_2nd = ($payroll[$v]['basic_pay'] - $compe1Value);
                                        //var_dump($cut_off_2nd);
                                        $grand_total['cut_off_total'] += $cut_off_2nd;
                                        $grand_total['second_cut_off'] += $cut_off_2nd;
                                    ?>
                                    
                                    </td>
                                    <input type="hidden" name="cut_off_2nd" id=<?php echo $payroll[$v]["id"]."_cut_off_2nd";?> value="<?php echo $cut_off_2nd; ?>">
									<td align="right">
                                        <?php 
                                        $pera_amt = $payroll[$v]['pera_amt'] / 2; echo number_format($pera_amt,2); 
                                        ?>
                                    </td>
									<td valign="middle" align="right" id="<?php echo $payroll[$v]["id"]."_cut_off2_gross"; ?>">
                                    <?php
                                    if($compe1 < 0 ){
                                        $compe1Value = abs($compe1);
                                    }else{
                                        $compe1Value = $compe1;
                                    }
                                    $gross_pay2 =(($payroll[$v]['basic_pay'] - $compe1Value)) + $pera_amt; echo number_format($gross_pay2,2 );$page_total["gross_pay"] += $gross_pay2; $grand_total["gross_pay"] += $gross_pay2;
                                    
                                    ?> 
                                    
                                </td>
                                <input type="hidden" name="cut_off_2nd_gross" id=<?php echo $payroll[$v]["id"]."_cut_off_2nd_gross";?> value="<?php echo $gross_pay2; ?>">
								<td valign="middle" align="right"><?php  $sss_gsis_amt =$payroll[$v]['sss_gsis_amt'] ; echo number_format($sss_gsis_amt,2); $rowtotaldeduction += $payroll[$v]['sss_gsis_amt']; $page_total["sss_gsis_amt"] += $payroll[$v]['sss_gsis_amt']; $grand_total["sss_gsis_amt"] += $payroll[$v]['sss_gsis_amt']; ?> </td>

	<!--9th column GSIS LOANS--><td valign="middle" align="right"><?php $cut_total_sis = $total_gsis ; echo number_format($cut_total_sis,2);$payroll[$v]['late_amt']; $grand_total["gsis_total"] += $cut_total_sis; ?></td> 
		<!--10th column LCHL-->	<td valign="middle" align="right"><?php $cut_total_lchl = $total_lchl ;  echo number_format($cut_total_lchl,2);$payroll[$v]['late_amt']; $grand_total["lchl_total"] += $cut_total_lchl; ?></td>  
			<!--11th column TAX--><td valign="middle" align="right"><?php $cut_wh_tax_amt = $payroll[$v]['wh_tax_amt'] ; echo number_format($cut_wh_tax_amt,2); $rowtotaldeduction += $payroll[$v]['wh_tax_amt']; $page_total["wh_tax_amt"] += $payroll[$v]['wh_tax_amt']; $grand_total["wh_tax_amt"] += $payroll[$v]['wh_tax_amt']; ?></td>
			<!--12th column PHIL--><td valign="middle" align="right"><?php $cut_philhealth_amt = $payroll[$v]['philhealth_amt'] ; echo number_format($cut_philhealth_amt,2); $rowtotaldeduction += $payroll[$v]['philhealth_amt']; $page_total["philhealth_amt"] += $payroll[$v]['philhealth_amt']; $grand_total["philhealth_amt"] += $payroll[$v]['philhealth_amt']; ?></td>

			<!--additional col 1--><td valign="middle" align="right" <?php if(isset($additional_header1[0]['name']) == ""){echo "style='display:none;'";} ?>class="tmp_col1 editable tmp_col1_value<?php echo $x;?> tmp_col1_fields" data-value="<?php echo $x;?>" data-id="<?php echo $payroll[$v]["id"]; ?>" id="<?php echo  "tmp_col1_".$payroll[$v]["id"]; ?>" <?php if(isset($additional_header1[0]['name']) == ""){echo "contenteditable";} ?>>
									<?php $cut_col1_value = $payroll[$v]["col1_value"]; echo number_format($cut_col1_value, 2);$grand_total["col1_value"] += $payroll[$v]['col1_value'] ?>
									</td>
                                    <input type="hidden" id=<?php echo $payroll[$v]["id"]."col1_value_field"; ?> value="<?php echo $payroll[$v]["col1_value"]; ?>">
                                    <input type="hidden" id=<?php echo $payroll[$v]["id"]."col2_value_field"; ?> value="<?php echo $payroll[$v]["col2_value"]; ?>">
			<!--additional col 2--><td valign="middle" align="right" <?php if(isset($additional_header2[0]['name']) == ""){echo "style='display:none;'";}?> class="tmp_col2 editable tmp_col2_value<?php echo $x;?> tmp_col2_fields" data-value="<?php echo $x;?>"  data-id="<?php echo $payroll[$v]["id"] ?>" id="<?php echo "tmp_col2_".$payroll[$v]["id"]; ?>" <?php if(isset($additional_header2[0]['name']) == ""){echo "contenteditable";} ?>>
									<?php $cut_col2_value = $payroll[$v]["col2_value"];  echo number_format($cut_col2_value,2);$grand_total["col2_value"] += $payroll[$v]['col2_value'] ?>
									</td>
			  
			<!--13th column HDMF--><td valign="middle" align="right"><?php $cut_pagibig_amt = $payroll[$v]['pagibig_amt'] ; echo number_format($cut_pagibig_amt,2); $rowtotaldeduction += $payroll[$v]['pagibig_amt']; $page_total["pagibig_amt"] += $payroll[$v]['pagibig_amt']; $grand_total["pagibig_amt"] += $payroll[$v]['pagibig_amt']; ?></td>
			<!--14th column mp2--><td valign="middle" align="right"><?php $val = 0; $val = $payroll[$v]['mp2_contribution_amt'] ; echo number_format($val,2); $rowtotaldeduction += $val; $page_total["mp2"] += $val; $grand_total1["mp2"] += $val;  ?></td> 
								  <td valign="middle" align="right"><?php $val = 0; $val = get_key(21,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val ,2); $rowtotaldeduction += $val; $page_total["mpl"] += $val; $grand_total1["mpl"] += $val; ?></td>  
<!-- 16th column LBP livelihood--><td valign="middle" align="right"><?php $val = 0; $val = get_key(39,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val ,2); $rowtotaldeduction += $val; $page_total["lbp2"] += $val; $grand_total2["lbp2"] += $val; ?></td>
                                    <?php
                                        $membership = get_key(40,$loanDeductions[$payroll[$v]['employee_id']]);	
                                        $kamanggagawa = get_key(41,$loanDeductions[$payroll[$v]['employee_id']]);	
                                        $project = get_key(42,$loanDeductions[$payroll[$v]['employee_id']]);		
                                        $cash_loan = get_key(43,$loanDeductions[$payroll[$v]['employee_id']]);		
                                        $emergency = get_key(44,$loanDeductions[$payroll[$v]['employee_id']]);
                                        $union_dues =  $payroll[$v]['union_dues_amt'];

                                        $nwrbea = $membership + $kamanggagawa + $project + $cash_loan + $emergency + $union_dues;

                                    ?>
        <!--17th column NWRBEA--><td valign="middle" align="right"><?php $val = 0; $val = $nwrbea; echo number_format($val,2); $rowtotaldeduction += $val; $page_total["nwrbea_project"] += $nwrbea; $grand_total["nwrbea_project1"] += $val;  ?></td> 
                                    <?php
                                            // if($count1 + 1 <= sizeof($payroll_2[$payroll[$v]['employee_id']])){

                                                $totalAbs_late = $payroll[$v]['total_tardiness_amt']; 
                                           // }
                                    ?>
<!--18th column Lates & absenses--><td valign="middle" align="right"><?php echo number_format($totalAbs_late, 2);$grand_total["late_amt"] += $totalAbs_late; ?></td>
								<td valign="middle" align="right"  id="<?php echo $payroll[$v]["id"]."_total_deduct"; ?>">
                                        <?php
                                        $total_deduct = $payroll[$v]['sss_gsis_amt'] //RLIP
                                        + $total_gsis //GSIS LOANS
                                        + $total_lchl //LCHL
                                        + $payroll[$v]["col2_value"]
                                        + $payroll[$v]["col1_value"]
                                        + $payroll[$v]['wh_tax_amt'] //TAX
                                        + $payroll[$v]['philhealth_amt'] //PHIL
                                        + $payroll[$v]['pagibig_amt'] //HDMF
                                        + $payroll[$v]['mp2_contribution_amt'] //MP2
                                        + get_key(21,$loanDeductions[$payroll[$v]['employee_id']])
                                        + get_key(39,$loanDeductions[$payroll[$v]['employee_id']])
										+ $payroll[$v]['union_dues_amt']
										+ $totalAbs_late
                                        ;
                                        ; //MPL

                                        // + NWRBEA	
                                        // + LATES & ABSENSES
                                        
                                        echo number_format($total_deduct,2); $page_total
                                        ["total_deductions"] += $payroll[$v]['total_deduct_amt']; $grand_total["total_deductions"] += $total_deduct; 
                                        /*echo number_format($v['total_deduct_amt'],2); $page_total["total_deductions"] += $v['total_deduct_amt']; $grand_total["total_deductions"] += $v['total_deduct_amt'];*/ 
                                        ?>
                                       
                                    </td>

                                    <input type="hidden" name="total_deduct_field" id=<?php echo $payroll[$v]["id"]."_total_deduct_field";?> value="<?php echo $total_deduct; ?>">
									<td valign="middle" align="right" id="<?php echo $payroll[$v]["id"]."_cut_off2_net"; ?>"><?php $net_amt_due2 = $gross_pay2 - $total_deduct; echo number_format($net_amt_due2, 2); $page_total["net_amnt_due"] += $net_amt_due2; $grand_total["net_amnt_due"] += $net_amt_due2;?></td>
									<td valign="middle" align="right">&nbsp;</td>
									<!-- <td>&nbsp;</td> -->
                                <tr>
                                    <td>&nbsp;</td>
                                    <td> Account # <?php if((isset($payroll[$v]['account_number']) && $payroll[$v]['account_number'] != "")){ echo $payroll[$v]['account_number'];} ?> </td>
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
                                    <td <?php if(isset($additional_header1[0]['name']) == ""){echo "style='display:none;'";} ?> class="tmp_col1 editable">&nbsp;</td>
                                    <td <?php if(isset($additional_header1[0]['name']) == ""){echo "style='display:none;'";} ?> class="tmp_col2 editable">&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <!-- <td>&nbsp;</td> -->
                                    <!-- <td>&nbsp;</td> -->
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
                                    <td>&nbsp;</td>
                                    <td <?php if(isset($additional_header1[0]['name']) == ""){echo "style='display:none;'";} ?> class="tmp_col1 editable">&nbsp;</td>
                                    <td <?php if(isset($additional_header1[0]['name']) == ""){echo "style='display:none;'";} ?> class="tmp_col2 editable">&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>

                            <?php
                                if($v == $count2){
                                    $count1 += 1;
                                    break;
                                }
                            ?>
                                
                            <?php $rows++; if($rows === 10) { $last_row = 1; } else { $last_row = 0; }
                             
                          $val1 = get_key(2,$loanDeductions[$payroll[$v]['employee_id']]); $grand_total4['conso_loan'] += $val1;	
                          $val2 = get_key(45,$loanDeductions[$payroll[$v]['employee_id']]);$grand_total4['gfal'] += $val2;		
                          $val3 = get_key(35,$loanDeductions[$payroll[$v]['employee_id']]);$grand_total4['mpl'] += $val3;		
                          $val4 = get_key(4,$loanDeductions[$payroll[$v]['employee_id']]);$grand_total4['emergency_loan'] += $val4;		
                          $val5 = get_key(36,$loanDeductions[$payroll[$v]['employee_id']]);$grand_total4['cpl'] += $val5;		
                          $val6 = get_key(24,$loanDeductions[$payroll[$v]['employee_id']]);$grand_total4['policy_loan'] += $val6;		
                          $val7 = get_key(16,$loanDeductions[$payroll[$v]['employee_id']]);$grand_total4['opt_loan'] += $val7;		
                          $val8 = get_key(18,$loanDeductions[$payroll[$v]['employee_id']]);$grand_total4['opt_insurance'] += $val8;		
                          $val9 = get_key(8,$loanDeductions[$payroll[$v]['employee_id']]);$grand_total4['edu_assistance'] += $val9;		
                          $val10 = get_key(40,$loanDeductions[$payroll[$v]['employee_id']]);$grand_total4['membership'] += $val10;		
                          $val11 = get_key(41,$loanDeductions[$payroll[$v]['employee_id']]);$grand_total4['kamanggagawa'] += $val11;		
                          $val12 = get_key(42,$loanDeductions[$payroll[$v]['employee_id']]);$grand_total4['project'] += $val12;		
                          $val13 = get_key(43,$loanDeductions[$payroll[$v]['employee_id']]);$grand_total4['cash_loan'] += $val13;		
                          $val14 = get_key(44,$loanDeductions[$payroll[$v]['employee_id']]);$grand_total4['emergency'] += $val14;
                          $val15 = get_key(7,$loanDeductions[$payroll[$v]['employee_id']]);$grand_total4['gsis'] += $val15;		
                          $val16 = get_key(31,$loanDeductions[$payroll[$v]['employee_id']]);$grand_total4['nhmfc'] += $val16;		
                          $val17 = get_key(20,$loanDeductions[$payroll[$v]['employee_id']]);$grand_total4['pagibig'] += $val17;		

                                } 

                                array_push($secondTableSALARY, $grand_total["salary"]);
                                array_push($secondTablePERA, $grand_total["pera_amt"]);
                                array_push($secondTableGross, $grand_total["gross_pay"]);
                                array_push($secondTableRLIP, $grand_total["sss_gsis_amt"]);
                                array_push($secondTableGSIS, $grand_total["gsis_total"]);
                                array_push($secondTableLCHL, $grand_total["lchl_total"]);
                                array_push($secondTableTAX, $grand_total["wh_tax_amt"]);
                                array_push($secondTablePHIL, $grand_total["philhealth_amt"]);
                                array_push($secondTableHDMF, $grand_total["pagibig_amt"]);
                                array_push($secondTableMP2, $grand_total1["mp2"]);
                                array_push($secondTableMPL, $grand_total1["mpl"]);
                                array_push($secondTableLBP, $grand_total2["lbp2"]);
                                array_push($secondTableNWRBEA, $grand_total["nwrbea_project1"]);
                                array_push($secondTableLATE, $grand_total["late_amt"]);
                                array_push($secondTableDEDUCTION, $grand_total["total_deductions"]);
                                array_push($secondTableNET, $grand_total["net_amnt_due"]);	
                                array_push($secondTableFirstCut, $grand_total["first_cut_off"]);
                                array_push($secondTableSecondCut, $grand_total["second_cut_off"]);

                                array_push($total_conso_loan, $grand_total4["conso_loan"]);	
                                array_push($total_GFAL, $grand_total4["gfal"]);	
                                array_push($total_MPL, $grand_total4["mpl"]);	
                                array_push($total_EMERGENCY_LOAN, $grand_total4["emergency_loan"]);
                                array_push($total_CPL, $grand_total4["cpl"]);		
                                array_push($total_POLICY_LOAN, $grand_total4["policy_loan"]);	
                                array_push($total_OPT_PL_LOAN, $grand_total4["opt_loan"]);	
                                array_push($total_OPT_INSURANCE, $grand_total4["opt_insurance"]);	
                                array_push($total_EDU_ASSISTANCE, $grand_total4["edu_assistance"]);	
                                array_push($total_Membership, $grand_total4["membership"]);	
                                array_push($total_Kamanggagawa, $grand_total4["kamanggagawa"]);	
                                array_push($total_PROJECT, $grand_total4["project"]);	
                                array_push($total_CASH_LOAN, $grand_total4["cash_loan"]);	
                                array_push($total_EMERGENCY, $grand_total4["emergency"]);	
                                array_push($total_GSIS, $grand_total4["gsis"]);	
                                array_push($total_NHMFC, $grand_total4["nhmfc"]);	
                                array_push($total_PAG_IBIG , $grand_total4["pagibig"]);	

                                ?>
                                <tr class="<?php echo "";//($last_row === 1)?"page-break":""; ?>">
                                    <td>&nbsp;</td>
                                    <td valign="middle" align="center" style="font-weight: bold;"><b>SUB-TOTAL</b></td>
                                    <td>&nbsp;</td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["salary"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;" id="<?php echo "cut_off_total".$x;?>"><?php echo number_format($grand_total["cut_off_total"],2); ?></td>
                                    <input type="hidden" name="cut_off_total_value" id=<?php echo "cut_off_total_value".$x;?> value="<?php echo $grand_total["cut_off_total"]; ?>">
                                    <!-- <td valign="middle" align="right" style="font-weight: bold;"><?php //echo number_format($grand_total["salary"],2); ?></td> -->
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["pera_amt"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;" id="<?php echo "gross_total".$x;?>" ><?php echo number_format($grand_total["gross_pay"],2); ?></td>
                                    <input type="hidden" name="gross_total_value" id=<?php echo "gross_total_value".$x;?> value="<?php echo $grand_total["gross_pay"]; ?>">
                                    <!-- <td valign="middle" align="right" style="font-weight: bold;"><?php //echo number_format($grand_total["other_earnings_amt"],2); ?></td> -->
                                    <!-- <td valign="middle" align="right" style="font-weight: bold;"><?php //echo number_format($grand_total["other_deductions_amt"],2); ?></td> -->
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["sss_gsis_amt"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["gsis_total"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["lchl_total"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["wh_tax_amt"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["philhealth_amt"],2); ?></td>
                                    <td valign="middle" align="right"  <?php if(isset($additional_header1[0]['name']) == ""){echo "style='display:none;'";} ?> class="tmp_col1 total_col" id="tmp_col1_total<?php echo $x;?>"><?php echo number_format($grand_total["col1_value"],2); ?></td>
                                    <td valign="middle" align="right"  <?php if(isset($additional_header2[0]['name']) == ""){echo "style='display:none;'";}?> class="tmp_col2 total_col" id="tmp_col2_total<?php echo $x;?>"><?php echo number_format($grand_total["col2_value"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["pagibig_amt"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format(($grand_total1["mp2"]),2); ?></td>                                       
                                    
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format(($grand_total1["mpl"]),2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format(($grand_total2["lbp2"]),2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["nwrbea_project1"],2);; ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["late_amt"],2);; ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;" id="<?php echo "total_deduction_total".$x;?>"><?php echo number_format($grand_total["total_deductions"],2); ?></td>
                                    <input type="hidden" name="total_deduction_value" id=<?php echo "total_deduction_value".$x;?> value="<?php echo $grand_total["total_deductions"]; ?>">
                                   
                                    <td valign="middle" align="right" style="font-weight: bold;" id="<?php echo "net_amt_total".$x;?>"><?php echo number_format($grand_total["net_amnt_due"],2); ?></td>
                                    <input type="hidden" name="net_amt_total_value" id=<?php echo "net_amt_total_value".$x;?> value="<?php echo $grand_total["net_amnt_due"]; ?>"> 
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
                                            $box_a_department = "";

                                            if(sizeof($signatories_a) > 0){

                                            if($signatories_a[0]["employee_name"] == "ARCHIE EDSEL C. ASUNCION "){

                                                    $box_a = 'ATTY. '.$signatories_a[0]["employee_name"];
                                                    $box_a_department = "OIC, Deputy Executive Director";

                                            }else if($signatories_a[0]["employee_name"] == "JUAN Y. CORPUZ JR."){

                                                    $box_a = 'ATTY. '.$signatories_a[0]["employee_name"];
                                                    $box_a_department = "Chief, ".$signatories_a[0]["department"];

                                            }else if($signatories_a[0]["employee_name"] == "ELOISA L. LEGASPI "){
                                                    $box_a = $signatories_a[0]["employee_name"];
                                                    $box_a_department = "Chief, ".$signatories_a[0]["department"];

                                            }else if($signatories_a[0]["employee_name"] == "LUIS S. RONGAVILLA "){

                                                    $box_a = $signatories_a[0]["employee_name"];
                                                    $box_a_department = "Chief, ".$signatories_a[0]["department"];

                                            }else if($signatories_a[0]["employee_name"] == "SUSAN P. ABAÑO "){

                                                    $box_a = $signatories_a[0]["employee_name"];
                                                    $box_a_department = "Chief, ".$signatories_a[0]["department"];
                                                    
                                            }else if($signatories_a[0]["employee_name"] == "EVELYN V. AYSON "){

                                                $box_a = $signatories_a[0]["employee_name"];
                                                $box_a_department = "OIC, ".$signatories_a[0]["department"];
                                            }
                                            else{

                                                $box_a = $signatories_a[0]["employee_name"];
                                                $box_a_department = $signatories_a[0]["department"];
                                            }
                                        }
                                        ?>
                                        <tr>
                                            <td width="45%" style="font-size:12px;text-align:center;border-bottom:1px solid black !important;"><?php echo (sizeof($signatories_a) > 0) ? $box_a : ''; //name ?></td>
                                            <td width="10%"></td>
                                            <td width="45%" style="text-align:center;border-bottom:1px solid black;"></td>
                                        </tr>
                                        <tr>
                                            <td width="45%" style="font-size:12px;text-align:center;">
                                                <?php if(sizeof($signatories_a) > 0){ echo $box_a_department; } //position ?>
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
                       <!-- -------------------------------------------------------------------------------------------------------------------------------------------------- -->
                       <div style='page-break-before:always'></div>
                        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------- -->
                        
                        <table style="width:100%;border-bottom:0px;" class="headerHide">
                        <thead>
                        <tr>
                                <td align="center" colspan="9"><label>GENERAL PAYROLL</label></td>
                            </tr>
                            <tr>
                                <td align="center" colspan="9"><label>NATIONAL WATER RESOURCES BOARD</label></td>
                            </tr>
                            <tr>
                                <td><br></td>
                            </tr>
                            <tr>
                                <td align="center" style="text-decoration: underline;" colspan="9"><b><?php echo "&emsp;".date('F d, Y',strtotime(@$payroll_period[0]['start_date'])); ?></b> &emsp; to &emsp; <b><?php echo date('F d, Y',strtotime(@$payroll_period[0]['end_date'])); ?></b>&emsp;</td>
                            </tr>
                            <tr>
                                <td style="text-align:center;font-size: 11px;font-style: italic;padding-top:0px;" colspan="9">Period</td>
                            </tr>
                            <tr>
                                <td><br></td>
                            </tr>
                            <tr>
                                <td><b>ENTITY NAME: NATIONAL WATER RESOURCES BOARD</b></td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>Payroll No.:___________________</td>
                            </tr>
                            <tr>
                                <td><b>Fund Cluster: </b><u>01</u></td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>Sheet__________ of __________ Sheets</td>
                            </tr>
                            <tr>
                                <td><b>Division: </b><b class="division_label"> <p class="division_label"></p></b></td>
                            </tr>
                            <tr>
                                <td align="left">We Acknowledge receipt of cash opposite our name as full compensation for services rendered for the period.</td>
                            </tr>
                        </thead>
                        </table>
                        <table style="width:100%; border-bottom:0px;" id="tblGSIS">
                            <head>
                            <tr>
                                <td style="text-align:center;" rowspan="2"><b>Ser. No.</b></td>
                                <td style="text-align:center;width:100px;" rowspan="2">NAME</td>
                                <td style="text-align:center;" rowspan="2"><b>Emp. No.</b></td>
                                <td colspan="9"><b>GSIS</b></td>
                                <td colspan="5"><b>NWRBEA</b></td>
                                <td colspan="3"><b>LOW COST HOUSING</b></td>
                            </tr>
                            <tr>
                                
                                <td>CONSO-LOAN</td>
                                <td>GFAL</td>
                                <td>MPL</td>
                                <td>EMERGENCY LOAN</td>
                                <td>CPL</td>
                                <td>POLICY LOAN</td>
                                <td>OPT. PL LOAN</td>
                                <td>OPT. INSURANCE</td>
                                <td>EDU-ASSISTANCE</td>
                                <td>Membership</td>
                                <td>Kamanggagawa</td>
                                <td>PROJECT</td>
                                <td>CASH LOAN</td>
                                <td>EMERGENCY</td>
                                <td>GSIS</td>
                                <td>NHMFC</td>
                                <td>PAG-IBIG</td>
                            </tr>
                            </head>
                            <tbody>
                                <?php
                                
                                    for($v = $count3 ; $v < sizeof($payroll); $v++ )
                                    {    
                                        $val1 = get_key(2,$loanDeductions[$payroll[$v]['employee_id']]); $grand_total3['conso_loan'] += $val1;	
                                        $val2 = get_key(45,$loanDeductions[$payroll[$v]['employee_id']]);$grand_total3['gfal'] += $val2;		
                                        $val3 = get_key(35,$loanDeductions[$payroll[$v]['employee_id']]);$grand_total3['mpl'] += $val3;		
                                        $val4 = get_key(4,$loanDeductions[$payroll[$v]['employee_id']]);$grand_total3['emergency_loan'] += $val4;		
                                        $val5 = get_key(36,$loanDeductions[$payroll[$v]['employee_id']]);$grand_total3['cpl'] += $val5;		
                                        $val6 = get_key(24,$loanDeductions[$payroll[$v]['employee_id']]);$grand_total3['policy_loan'] += $val6;		
                                        $val7 = get_key(16,$loanDeductions[$payroll[$v]['employee_id']]);$grand_total3['opt_loan'] += $val7;		
                                        $val8 = get_key(18,$loanDeductions[$payroll[$v]['employee_id']]);$grand_total3['opt_insurance'] += $val8;		
                                        $val9 = get_key(8,$loanDeductions[$payroll[$v]['employee_id']]);$grand_total3['edu_assistance'] += $val9;		
                                        $val10 = get_key(40,$loanDeductions[$payroll[$v]['employee_id']]);$grand_total3['membership'] += $val10;		
                                        $val11 = get_key(41,$loanDeductions[$payroll[$v]['employee_id']]);$grand_total3['kamanggagawa'] += $val11;		
                                        $val12 = get_key(42,$loanDeductions[$payroll[$v]['employee_id']]);$grand_total3['project'] += $val12;		
                                        $val13 = get_key(43,$loanDeductions[$payroll[$v]['employee_id']]);$grand_total3['cash_loan'] += $val13;		
                                        $val14 = get_key(44,$loanDeductions[$payroll[$v]['employee_id']]);$grand_total3['emergency'] += $val14;
                                        $val15 = get_key(7,$loanDeductions[$payroll[$v]['employee_id']]);$grand_total3['gsis'] += $val15;		
                                        $val16 = get_key(31,$loanDeductions[$payroll[$v]['employee_id']]);$grand_total3['nhmfc'] += $val16;		
                                        $val17 = get_key(20,$loanDeductions[$payroll[$v]['employee_id']]);$grand_total3['pagibig'] += $val17;
                                ?>
                            <tr>
                                <td valign="top" style="text-align:center;"><?php echo ($v+1); ?></td>
                                <td valign="top" style="text-align:left;font-weight: bold;"><?php echo ((isset($payroll[$v]['last_name']) && $payroll[$v]['last_name'] != "")?$this->Helper->decrypt($payroll[$v]['last_name'],$payroll[$v]['employee_id']):"") . ((isset($payroll[$v]['first_name']) && $payroll[$v]['first_name'] != "")?"&nbsp;".$this->Helper->decrypt($payroll[$v]['first_name'],$payroll[$v]['employee_id']):"") . ((isset($payroll[$v]['middle_name']) && $payroll[$v]['middle_name'] != "")?"&nbsp;".$this->Helper->decrypt($payroll[$v]['middle_name'],$payroll[$v]['employee_id']):"").((isset($payroll[$v]['extension']) && $payroll[$v]['extension'] != "")?"&nbsp;".$this->Helper->decrypt($payroll[$v]['extension'],$payroll[$v]['employee_id']):"") //. "<br><span style='font-weight: normal;'>" . strtoupper(@$v['position_name']) . "</span>"; ?></td>
                                <td valign="middle" style="text-align:center;"> <?php echo (isset($payroll[$v]['employee_id_number']) && $payroll[$v]['employee_id_number'] != "")?$this->Helper->decrypt($payroll[$v]['employee_id_number'],$payroll[$v]['employee_id']):""; ?> </td>
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
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>

                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td ><?php echo @$payroll[$v]['position_name']; ?></td>
                                <td></td>
                                <td><?php echo number_format($val1, 2);?></td>
                                <td><?php echo number_format($val2, 2);?></td>
                                <td><?php echo number_format($val3, 2);?></td>
                                <td><?php echo number_format($val4, 2);?></td>
                                <td><?php echo number_format($val5, 2);?></td>
                                <td><?php echo number_format($val6, 2);?></td>
                                <td><?php echo number_format($val7, 2);?></td>
                                <td><?php echo number_format($val8, 2);?></td>
                                <td><?php echo number_format($val9, 2);?></td>
                                <td><?php echo number_format($val10, 2);?></td>
                                <td><?php echo number_format($val11, 2);?></td>
                                <td><?php echo number_format($val12, 2);?></td>
                                <td><?php echo number_format($val13, 2);?></td>
                                <td><?php echo number_format($val14, 2);?></td>
                                <td><?php echo number_format($val15, 2);?></td>
                                <td><?php echo number_format($val16, 2);?></td>
                                <td><?php echo number_format($val17, 2);?></td>

                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td> Account # <?php if((isset($payroll[$v]['account_number']) && $payroll[$v]['account_number'] != "")){ echo $payroll[$v]['account_number'];} ?> </td>
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
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                
                            </tr>
                     
                             <?php
                                 if($v == $count2){
                                    $count3 += 1;
                                    break;
                                }

                                }
                             ?>
                                <tr>
        
                                <td>&nbsp;</td>
                                <td valign="middle" align="right" style="font-weight: bold;">SUB-TOTAL</td>
                                <td>&nbsp;</td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total3['conso_loan'] , 2);?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total3['gfal'] , 2);?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total3['mpl'] , 2);?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total3['emergency_loan'] , 2);?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total3['cpl'] , 2);?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total3['policy_loan'] , 2);?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total3['opt_loan'] , 2);?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total3['opt_insurance'] , 2);?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total3['edu_assistance'] , 2);?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total['membership'] , 2);?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total3['kamanggagawa'] , 2);?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total3['project'] , 2);?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total3['cash_loan'] , 2);?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total3['emergency'] , 2);?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total3['gsis'] , 2);?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total3['nhmfc'] , 2);?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total3['pagibig'] , 2);?></td>

                            </tr>
                            </tbody>
                        </table>
                       <!-- -------------------------------------------------------------------------------------------------------------------------------------------------- -->
                       <div style='page-break-before:always'></div>
                        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------- -->
                        <?php
                                   
                            $count1 += 14;
                            $count3 += 14;
                            $count2 += 15;
                           }
                        ?>
                        </centter>

                        <table style="width:100%;border-bottom:0px;" class="headerHide">
                            <thead>
                            <tr>
                                <td align="center" colspan="9"><label>GENERAL PAYROLL</label></td>
                            </tr>
                            <tr>
                                <td align="center" colspan="9"><label>NATIONAL WATER RESOURCES BOARD</label></td>
                            </tr>
                            <tr>
                                <td><br></td>
                            </tr>
                            <tr>
                                <td align="center" style="text-decoration: underline;" colspan="9"><b><?php echo "&emsp;".date('F d, Y',strtotime(@$payroll_period[0]['start_date'])); ?></b> &emsp; to &emsp; <b><?php echo date('F d, Y',strtotime(@$payroll_period[0]['end_date'])); ?></b>&emsp;</td>
                            </tr>
                            <tr>
                                <td style="text-align:center;font-size: 11px;font-style: italic;padding-top:0px;" colspan="9">Period</td>
                            </tr>
                            <tr>
                                <td><br></td>
                            </tr>
                            <tr>
                                <td><b>ENTITY NAME: NATIONAL WATER RESOURCES BOARD</b></td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>Payroll No.:___________________</td>
                            </tr>
                            <tr>
                                <td><b>Fund Cluster: </b><u>01</u></td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>Sheet__________ of __________ Sheets</td>
                            </tr>
                            <tr>
                                <td><b>Division: </b><b class="division_label"> <p class="division_label"></p></b></td>
                            </tr>
                            <tr>
                                <td align="left">We Acknowledge receipt of cash opposite our name as full compensation for services rendered for the period.</td>
                            </tr>
                        </thead>
                    </table>
                    <table style="width:100%; border-bottom:0px;" id="tblGSIS">
                            <thead>
                                <tr>
                                    <td></td>
                                    <td colspan="2">COMPENSATION</td>
                                    <td colspan="12">DEDUCTIONS</td>
                                    <td colspan="2"></td>
                                </tr>
                            <tr>
                                <td></td>
                                <td>Salary per Month</td>
                                <td>ALLOWANCE PERA</td>
                                <td>Gross Amount Earned</td>
                                <td>RLIP</td>
                                <td>GSIS LOAN</td>
                                <td>LCHL</td>
                                <td>TAX</td>
                                <td>PHIL</td>
                                <td>HDMF</td>
                                <td>MP2</td>
                                <td>MPL</td>
                                <td>LBP LIVELIHOOD</td>
                                <td>NWRBEA</td>
                                <td>LATE & ABSENSES</td>
                                <td>Total Deduction</td>
                                <td>Net Amount</td>
                            </tr>
                            </thead>
                            <tbody>
                                <?php

                                $second_conso_loan1 = 0;
                                $second_GFAL1 = 0;
                                $second_MPL1 = 0;
                                $second_EMERGENCY_LOAN1 = 0;
                                $second_CPL1 = 0; 
                                $second_POLICY_LOAN1 = 0;
                                $second_OPT_PL_LOAN1 = 0;
                                $second_OPT_INSURANCE1 = 0;
                                $second_EDU_ASSISTANCE1 = 0;
                                $second_Membership1 = 0;
                                $second_Kamanggagawa1 = 0;
                                $second_PROJECT1 = 0;
                                $second_CASH_LOAN1 = 0;
                                $second_EMERGENCY1 = 0;
                                $second_GSIS1 = 0;
                                $second_NHMFC1 = 0;
                                $second_PAG_IBIG1 = 0;

                                for($x = 0 ; $x < sizeof($total_conso_loan); $x++){

                                $second_conso_loan1 += $total_conso_loan[$x];
                                $second_GFAL1 += $total_GFAL[$x];
                                $second_MPL1 += $total_MPL[$x];
                                $second_EMERGENCY_LOAN1 += $total_EMERGENCY_LOAN[$x];
                                $second_CPL1 += $total_CPL[$x];
                                $second_POLICY_LOAN1 += $total_POLICY_LOAN[$x];
                                $second_OPT_PL_LOAN1 += $total_OPT_PL_LOAN[$x];
                                $second_OPT_INSURANCE1 += $total_OPT_INSURANCE[$x];
                                $second_EDU_ASSISTANCE1 += $total_EDU_ASSISTANCE[$x];
                                $second_Membership1 += $total_Membership[$x];
                                $second_Kamanggagawa1 += $total_Kamanggagawa[$x];
                                $second_PROJECT1 += $total_PROJECT[$x];
                                $second_CASH_LOAN1 += $total_CASH_LOAN[$x];
                                $second_EMERGENCY1 += $total_EMERGENCY[$x];
                                $second_GSIS1 += $total_GSIS[$x];
                                $second_NHMFC1 += $total_NHMFC[$x];
                                $second_PAG_IBIG1 += $total_PAG_IBIG[$x];
                                }
                                //var_dump(sizeof($secondTableTotalSALARY));
                                  $secondTableTotalSALARY = 0;
                                  $secondTableTotalPERA = 0;
                                  $secondTableTotalGross = 0;
                                  $secondTableTotalRLIP = 0;
                                  $secondTableTotalGSIS = 0;
                                  $secondTableTotalLCHL = 0;
                                  $secondTableTotalTAX = 0;
                                  $secondTableTotalPHIL = 0;
                                  $secondTableTotalHDMF = 0;
                                  $secondTableTotalMP2 = 0;
                                  $secondTableTotalMPL = 0;
                                  $secondTableTotalLBP = 0;
                                  $secondTableTotalNWRBEA = 0;
                                  $secondTableTotalLATE = 0;
                                  $secondTableTotalDEDUCTION = 0;
                                  $secondTableTotalNET = 0;
                                  $secondTableTotalFirstCut = 0;
                                  $secondTableTotalNETSecondCut = 0;

                                    for($x = 0 ; $x < sizeof($secondTableSALARY); $x++){

                                        $secondTableTotalSALARY += $secondTableSALARY[$x];
                                        $secondTableTotalPERA += $secondTablePERA[$x];
                                        $secondTableTotalGross += $secondTableGross[$x];
                                        $secondTableTotalRLIP += $secondTableRLIP[$x];
                                        $secondTableTotalGSIS += $secondTableGSIS[$x];
                                        $secondTableTotalLCHL += $secondTableLCHL[$x];
                                        $secondTableTotalTAX += $secondTableTAX[$x];
                                        $secondTableTotalPHIL += $secondTablePHIL[$x];
                                        $secondTableTotalHDMF += $secondTableHDMF[$x];
                                        $secondTableTotalMP2 += $secondTableMP2[$x];
                                        $secondTableTotalMPL += $secondTableMPL[$x];
                                        $secondTableTotalLBP += $secondTableLBP[$x];
                                        $secondTableTotalNWRBEA += $secondTableNWRBEA[$x];
                                        $secondTableTotalLATE += $secondTableLATE[$x];
                                        $secondTableTotalDEDUCTION += $secondTableDEDUCTION[$x];
                                        $secondTableTotalNET += $secondTableNET[$x];
                                        $secondTableTotalFirstCut = $secondTableFirstCut[$x];
                                        $secondTableTotalNETSecondCut = $secondTableSecondCut[$x];
                                ?>
                                <tr>
                                    <td>PAGE <?php echo $x+1; ?></td>
                                    <td><?php echo number_format($secondTableSALARY[$x], 2); ?></td>
                                    <td><?php echo number_format($secondTablePERA[$x], 2); ?></td>
                                    <td><?php echo number_format($secondTableGross[$x], 2); ?></td>
                                    <td><?php echo number_format($secondTableRLIP[$x], 2); ?></td>
                                    <td><?php echo number_format($secondTableGSIS[$x], 2); ?></td>
                                    <td><?php echo number_format($secondTableLCHL[$x], 2); ?></td>
                                    <td><?php echo number_format($secondTableTAX[$x], 2); ?></td>
                                    <td><?php echo number_format($secondTablePHIL[$x], 2); ?></td>
                                    <td><?php echo number_format($secondTableHDMF[$x], 2); ?></td>
                                    <td><?php echo number_format($secondTableMP2[$x], 2); ?></td>
                                    <td><?php echo number_format($secondTableMPL[$x], 2); ?></td>
                                    <td><?php echo number_format($secondTableLBP[$x], 2); ?></td>
                                    <td><?php echo number_format($secondTableNWRBEA[$x], 2); ?></td>
                                    <td><?php echo number_format($secondTableLATE[$x], 2); ?></td>
                                    <td><?php echo number_format($secondTableDEDUCTION[$x], 2); ?></td>
                                    <td><?php echo number_format($secondTableNET[$x], 2); ?></td>

                                </tr>

                                  <?php
                                    }
                                ?>
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
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>

                                        </tr>
                              
                                   <tr>
                                    <td>TOTAL</td>
                                    <td style="text-align:center;font-weight: bold;"><?php echo number_format($secondTableTotalSALARY, 2); ?></td>
                                    <td style="text-align:center;font-weight: bold;"><?php echo number_format($secondTableTotalPERA, 2); ?></td>
                                    <td style="text-align:center;font-weight: bold;"><?php echo number_format($secondTableTotalGross, 2); ?></td>
                                    <td style="text-align:center;font-weight: bold;"><?php echo number_format($secondTableTotalRLIP, 2); ?></td>
                                    <td style="text-align:center;font-weight: bold;"><?php echo number_format($secondTableTotalGSIS, 2); ?></td>
                                    <td style="text-align:center;font-weight: bold;"><?php echo number_format($secondTableTotalLCHL, 2); ?></td>
                                    <td style="text-align:center;font-weight: bold;"><?php echo number_format($secondTableTotalTAX, 2); ?></td>
                                    <td style="text-align:center;font-weight: bold;"><?php echo number_format($secondTableTotalPHIL, 2); ?></td>
                                    <td style="text-align:center;font-weight: bold;"><?php echo number_format($secondTableTotalHDMF, 2); ?></td>
                                    <td style="text-align:center;font-weight: bold;"><?php echo number_format($secondTableTotalMP2, 2); ?></td>
                                    <td style="text-align:center;font-weight: bold;"><?php echo number_format($secondTableTotalMPL, 2); ?></td>
                                    <td style="text-align:center;font-weight: bold;"><?php echo number_format($secondTableTotalLBP, 2); ?></td>
                                    <td style="text-align:center;font-weight: bold;"><?php echo number_format($secondTableTotalNWRBEA, 2); ?></td>
                                    <td style="text-align:center;font-weight: bold;"><?php echo number_format($secondTableTotalLATE, 2); ?></td>
                                    <td style="text-align:center;font-weight: bold;"><?php echo number_format($secondTableTotalDEDUCTION, 2); ?></td>
                                    <td style="text-align:center;font-weight: bold;"><?php echo number_format($secondTableTotalNET, 2); ?></td>

                                </tr>
                            </tbody>
                        </table>
                        <div style="border:2px solid black;width:100%;">
                        <table> 
                                <thead>
                                    <tr>
                                        <td colspan="2"><?php echo date('F Y',strtotime(@$payroll_period[0]['start_date']));?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                         <td><?php echo date('m',strtotime(@$payroll_period[0]['start_date'])).'/1-'.date('d',strtotime(@$payroll_period[0]['end_date'])) / 2 ; ?></td>
                                         <td style="border-bottom:1px solid black;">&nbsp;</td>
                                         <td style="border-bottom:1px solid black;">&nbsp;</td>
                                         <td style="border-bottom:1px solid black;"><?php echo number_format($secondTableTotalFirstCut, 2); ?></td>
                                         <td>&nbsp;</td>
                                         <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                         <td><?php echo date('m',strtotime(@$payroll_period[0]['start_date'])) .'/'. (date('d',strtotime(@$payroll_period[0]['end_date'])) / 2 + 1) .'-'.date('d',strtotime(@$payroll_period[0]['end_date'])); ?></td>
                                         <td style="border-bottom:1px solid black;">&nbsp;</td>
                                         <td style="border-bottom:1px solid black;">&nbsp;</td>
                                         <td style="border-bottom:1px solid black;"><?php echo number_format($secondTableTotalNETSecondCut, 2); ?></td>
                                         <td>&nbsp;</td>
                                         <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>TOTAL</td>
                                        <td style="border-bottom:3px double black;">&nbsp;</td>
                                        <td style="border-bottom:3px double black;">&nbsp;</td>
                                        <td style="border-bottom:3px double black"><?php echo number_format($secondTableTotalFirstCut + $secondTableTotalNETSecondCut, 2);?></td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>5-01-01-010</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="text-align:right;"><?php echo number_format($secondTableTotalSALARY - $secondTableTotalLATE - $lastPersonSalary - $lastPersonLate , 2);?></td>
                                    </tr>
                                    <?php
                                        $totalValue = $secondTableTotalSALARY - $secondTableTotalLATE - $lastPersonSalary - $lastPersonLate;
                                        $sumValue2 = 0;
                                    ?>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td><?php echo number_format(($secondTableTotalFirstCut + $secondTableTotalNETSecondCut) - $secondTableTotalNET, 2)?></td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>5-01-02-010</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="text-align:right;"><?php echo number_format($secondTableTotalPERA, 2);?></td>
                                    </tr>
                                    <?php if($division_name2 == "AFD"){;?>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>5-01-01-020</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="text-align:right;"><?php echo number_format($secondTableTotalSALARY, 2);?></td>
                                    </tr>

                                        <?php
                                            $sumValue = $totalValue + $secondTableTotalPERA + $secondTableTotalSALARY;
                                    }else{
                                            $sumValue = $totalValue + $secondTableTotalPERA ;
                                    }
                                        ?>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td style="text-align:right;">20201020&nbsp;</td>
                                        <td style="text-align:left;">&nbsp;&nbsp;&nbsp;01-001</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="text-align:right;" class="smallBottom"><?php echo number_format($secondTableTotalRLIP ,2); $sumValue2 += $secondTableTotalRLIP; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="text-align:right;border-right:1px solid black;"><?php echo number_format( $second_conso_loan1 + $second_GFAL1 + $second_MPL1+ $second_EMERGENCY_LOAN1 + $second_CPL1 + $second_POLICY_LOAN1 + $second_OPT_PL_LOAN1 + $second_OPT_INSURANCE1+ $second_EDU_ASSISTANCE1 ,2);  $sumValue2 +=   $second_conso_loan1 + $second_GFAL1 + $second_MPL1+ $second_EMERGENCY_LOAN1 + $second_CPL1 + $second_POLICY_LOAN1 + $second_OPT_PL_LOAN1 + $second_OPT_INSURANCE1+ $second_EDU_ASSISTANCE1;?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td style="text-align:right;">20201020&nbsp;</td>
                                        <td style="text-align:left;">&nbsp;&nbsp;&nbsp;03-001</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="border-right:1px solid black;">&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="text-align:right;"><?php echo number_format($second_conso_loan1 + $second_MPL1, 2);?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td style="text-align:right;">20201020&nbsp;</td>
                                        <td style="text-align:left;">&nbsp;&nbsp;&nbsp;03-003</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="border-right:1px solid black;">&nbsp;</td>
                                        
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="text-align:right;"><?php echo number_format($second_EMERGENCY_LOAN1, 2); ?></td>
                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td>20201020-03-003</td>
                                        <td>&nbsp;</td>
                                        <td style="text-align:right;"><?php echo number_format($second_EMERGENCY_LOAN1, 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="border-right:1px solid black;">&nbsp;</td>
                                        <td  class="smallBottomLeft">&nbsp;&nbsp;&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="text-align:right;">-</td>
                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td>20201020-03-004</td>
                                        <td>&nbsp;</td>
                                        <td style="text-align:right;"><?php echo number_format(0, 2); ?></td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td> 
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td style="text-align:right;">20201020&nbsp;</td>
                                        <td style="text-align:left;">&nbsp;&nbsp;&nbsp;03-005</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="border-right:1px solid black;">&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <?php
                                            if($division_name2 == "AFD"){
                                        ?>
                                        <td style="text-align:right;"><?php echo number_format($second_EDU_ASSISTANCE1, 2); ?></td>
                                        <?php
                                            }else{
                                        ?>
                                         <td style="text-align:right;"><?php echo number_format($second_POLICY_LOAN1, 2); ?></td>
                                        <?php
                                            }
                                        ?>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td style="text-align:right;">20201020&nbsp;</td>
                                        <td style="text-align:left;">&nbsp;&nbsp;&nbsp;04-001</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="border-right:1px solid black;">&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <?php
                                            if($division_name2 == "AFD"){
                                        ?>
                                        <td style="text-align:right;"><?php echo number_format($second_POLICY_LOAN1, 2); ?></td>
                                        <?php
                                            }else{
                                        ?>
                                        <td style="text-align:right;"><?php echo number_format($second_OPT_INSURANCE1, 2); ?></td>
                                        <?php
                                            }
                                        ?>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td style="text-align:right;">20201020&nbsp;</td>
                                        <td style="text-align:left;">&nbsp;&nbsp;&nbsp;01-002</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="border-right:1px solid black;">&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <?php
                                            if($division_name2 == "AFD"){
                                        ?>
                                        <td style="text-align:right;"><?php echo number_format($second_OPT_INSURANCE1, 2); ?></td>
                                        <?php
                                            }else{
                                        ?>
                                        <td style="text-align:right;"><?php echo number_format($second_EDU_ASSISTANCE1, 2); ?></td>
                                        <?php
                                            }
                                        ?>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td style="text-align:right;">20201020&nbsp;</td>
                                        <td style="text-align:left;">&nbsp;&nbsp;&nbsp;04-002</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="border-right:1px solid black;">&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="text-align:right;"><?php echo number_format($second_OPT_PL_LOAN1, 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td style="text-align:right;">20201020&nbsp;</td>
                                        <td style="text-align:left;">&nbsp;&nbsp;&nbsp;03-006</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="border-right:1px solid black;" class="smallBottom">&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="text-align:right;"><?php echo number_format($second_GFAL1, 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="text-align:right;"><?php echo number_format($second_CPL1, 2); ?></td>
                                    </tr>

                                    <tr>
                                    
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td >&nbsp;&nbsp;&nbsp;</td>
                                        <td style="text-align:right;" ><?php echo number_format($second_GSIS1 + $second_NHMFC1 + $second_PAG_IBIG1 + $secondTableTotalMP2, 2); $sumValue2 += $second_GSIS1 + $second_NHMFC1 + $second_PAG_IBIG1 + $secondTableTotalMP2 + $secondTableTotalMP2;?></td>
                                        <td style="border-right:1px solid black;" class="smallBottomTop">&nbsp;&nbsp;&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>20201020-03-02</td>
                                        <td>&nbsp;</td>
                                        <td style="text-align:right;"><?php echo number_format($second_GSIS1, 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="border-right:1px solid black;" >&nbsp;</td>
                                        <td class="smallBottomLeft">&nbsp;&nbsp;&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>20201060-00-01</td>
                                        <td>&nbsp;</td>
                                        <td style="text-align:right;"><?php echo number_format($second_NHMFC1, 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="border-right:1px solid black;">&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>20201030-.03</td>
                                        <td>&nbsp;</td>
                                        <td style="text-align:right;"><?php echo number_format($second_PAG_IBIG1, 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <!----><td>&nbsp;</td>
                                        <td style="border-right:1px solid black;" class="smallBottom2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>20201030-03</td>
                                        <td>&nbsp;</td>
                                        <td style="text-align:right;"><?php echo number_format($secondTableTotalMP2, 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <!----><td>&nbsp;</td>
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
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td >&nbsp;</td>
                                        <td style="text-align:right;" ><?php echo number_format($secondTableTotalMPL + 0, 2); $sumValue2 += $secondTableTotalMPL + 0; ?></td>
                                        <td style="border-right:1px solid black;" class="smallBottomTop">&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>20201030-02-001</td>
                                        <td>&nbsp;</td>
                                        <td style="text-align:right;"><?php echo number_format($secondTableTotalMPL, 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <!----><td >&nbsp;</td>
                                        <td style="border-right:1px solid black;" class="smallBottom2">&nbsp;&nbsp;&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>20201030-02-002</td>
                                        <td>&nbsp;</td>
                                        <td style="text-align:right;"><?php echo number_format(0, 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td colspan="2">20201030-01</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="text-align:right;"><?php echo number_format($secondTableTotalHDMF, 2); $sumValue2 += $secondTableTotalHDMF;?></td>
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
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td colspan="2" >20201010-00-001</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="text-align:right;"><?php echo number_format($secondTableTotalTAX, 2); $sumValue2 += $secondTableTotalTAX;?></td>
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
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td colspan="2">20201040</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="text-align:right;"><?php echo number_format($secondTableTotalPHIL, 2); $sumValue2 += $secondTableTotalPHIL;?></td>
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
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td colspan="2" style="text-align:left;">29999990-00-002</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="text-align:right;"><?php echo number_format($secondTableTotalLBP, 2); $sumValue2 += $secondTableTotalLBP; ?></td>
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
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td colspan="2">&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <!-- testSum-->
                                        <td style="text-align:right;"><?php echo number_format($second_Membership1 + $second_Kamanggagawa1 + $second_CASH_LOAN1 + $second_EMERGENCY1 + $second_PROJECT1, 2); $sumValue2 += 0 + 0 + $second_CASH_LOAN1 + $second_EMERGENCY1 + $second_PROJECT1 ; ?></td>
                                        <td class="smallBottom2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>29999990-00-001</td>
                                        <td>&nbsp;</td>
                                        <!-- membership -->
                                        <td style="text-align:right;"><?php echo number_format($second_Membership1, 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td colspan="2">&nbsp;</td>
                                        
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="border-right:1px solid black;">&nbsp;</td>
                                        <td >&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>29999990-00-001-005</td>
                                        <td>&nbsp;</td>
                                        <!-- kamanggawa-->
                                        <td style="text-align:right;"><?php echo number_format($second_Kamanggagawa1, 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td colspan="2">&nbsp;</td>
                                        
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="border-right:1px solid black;" >&nbsp;</td>
                                        <td class="smallBottomLeft">&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>29999990-00-001-002</td>
                                        <td>&nbsp;</td>
                                        <td style="text-align:right;"><?php echo number_format($second_CASH_LOAN1, 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td colspan="2">&nbsp;</td>
                                        
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="border-right:1px solid black;">&nbsp;</td>
                                        <td >&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>29999990-00-001-003</td>
                                        <td>&nbsp;</td>
                                        <td style="text-align:right;"><?php echo number_format($second_EMERGENCY1, 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td colspan="2">&nbsp;</td>
                                        
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="border-right:1px solid black;" class="smallBottom2">&nbsp;</td>
                                        <td >&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>229999990-00-001-004</td> 
                                        <td>&nbsp;</td>
                                        <td style="text-align:right;"><?php echo number_format($second_PROJECT1,  2); ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td colspan="2">10104040</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="text-align:right;"><?php echo number_format($secondTableTotalNET, 2); $sumValue2 += $secondTableTotalNET; ?></td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="text-align:right;"></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td colspan="2"></td>
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
                                        <td style="text-align:right;"></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td colspan="2"></td>
                                        <td style="text-align:right;"><?php echo number_format($sumValue, 2); ?></td>
                                        <td>&nbsp;</td>
                                        <td style="text-align:right;"><?php echo number_format($sumValue2, 2); ?></td>
                                        <td ></td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="text-align:right;"></td>
                                    </tr>
                                </thead>
                                </table>
                                <br>
                        </div>
                       <!-- -------------------------------------------------------------------------------------------------------------------------------------------------- -->
                        <div style='page-break-before:always'></div>
                        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------- -->
                        <table style="width:100%;border-bottom:0px;" class="headerHide">
                        <thead>
                        <tr>
                                <td align="center" colspan="9"><label>GENERAL PAYROLL</label></td>
                            </tr>
                            <tr>
                                <td align="center" colspan="9"><label>NATIONAL WATER RESOURCES BOARD</label></td>
                            </tr>
                            <tr>
                                <td><br></td>
                            </tr>
                            <tr>
                                <td align="center" style="text-decoration: underline;" colspan="9"><b><?php echo "&emsp;".date('F d, Y',strtotime(@$payroll_period[0]['start_date'])); ?></b> &emsp; to &emsp; <b><?php echo date('F d, Y',strtotime(@$payroll_period[0]['end_date'])); ?></b>&emsp;</td>
                            </tr>
                            <tr>
                                <td style="text-align:center;font-size: 11px;font-style: italic;padding-top:0px;" colspan="9">Period</td>
                            </tr>
                            <tr>
                                <td><br></td>
                            </tr>
                            <tr>
                                <td><b>ENTITY NAME: NATIONAL WATER RESOURCES BOARD</b></td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>Payroll No.:___________________</td>
                            </tr>
                            <tr>
                                <td><b>Fund Cluster: </b><u>01</u></td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>Sheet__________ of __________ Sheets</td>
                            </tr>
                            <tr>
                                <td><b>Division: </b><b class="division_label"> <p class="division_label"></p></b></td>
                            </tr>
                            <tr>
                                <td align="left">We Acknowledge receipt of cash opposite our name as full compensation for services rendered for the period.</td>
                            </tr>
                        </thead>
                    </table>

                    <table style="width:100%; border-bottom:0px;" id="tblGSIS">
                            <thead>
                                <tr>
                                    <td></td>
                                    <td colspan="9">GSIS DEDUCTIONS</td>
                                    <td colspan="5">NWRBEA</td>
                                    <td colspan="3">LOW COST HOUSING</td>
                                </tr>
                            <tr>
                                <td></td>
                                <td>CONSO-LOAN</td>
                                <td>GFAL</td>
                                <td>MPL</td>
                                <td>EMERGENCY LOAN</td>
                                <td>CPL</td>
                                <td>POLICY LOAN</td>
                                <td>OPT. PL LOAN</td>
                                <td>OPT. INSURANCE</td>
                                <td>EDU-ASSISTANCE</td>
                                <td>Membership</td>
                                <td>Kamanggagawa</td>
                                <td>PROJECT</td>
                                <td>CASH LOAN</td>
                                <td>EMERGENCY</td>
                                <td>GSIS</td>
                                <td>NHMFC</td>
                                <td>PAG-IBIG</td>
                            </tr>
                            </thead>
                            <tbody>
                                <?php
                                //var_dump(sizeof($secondTableTotalSALARY));
                                  
                                    $second_conso_loan = 0;
                                    $second_GFAL = 0;
                                    $second_MPL = 0;
                                    $second_EMERGENCY_LOAN = 0;
                                    $second_CPL = 0; 
                                    $second_POLICY_LOAN = 0;
                                    $second_OPT_PL_LOAN = 0;
                                    $second_OPT_INSURANCE = 0;
                                    $second_EDU_ASSISTANCE = 0;
                                    $second_Membership = 0;
                                    $second_Kamanggagawa = 0;
                                    $second_PROJECT = 0;
                                    $second_CASH_LOAN = 0;
                                    $second_EMERGENCY = 0;
                                    $second_GSIS = 0;
                                    $second_NHMFC = 0;
                                    $second_PAG_IBIG = 0;

                                    for($x = 0 ; $x < sizeof($total_conso_loan); $x++){

                                        $second_conso_loan += $total_conso_loan[$x];
                                        $second_GFAL += $total_GFAL[$x];
                                        $second_MPL += $total_MPL[$x];
                                        $second_EMERGENCY_LOAN += $total_EMERGENCY_LOAN[$x];
                                        $second_CPL += $total_CPL[$x];
                                        $second_POLICY_LOAN += $total_POLICY_LOAN[$x];
                                        $second_OPT_PL_LOAN += $total_OPT_PL_LOAN[$x];
                                        $second_OPT_INSURANCE += $total_OPT_INSURANCE[$x];
                                        $second_EDU_ASSISTANCE += $total_EDU_ASSISTANCE[$x];
                                        $second_Membership += $total_Membership[$x];
                                        $second_Kamanggagawa += $total_Kamanggagawa[$x];
                                        $second_PROJECT += $total_PROJECT[$x];
                                        $second_CASH_LOAN += $total_CASH_LOAN[$x];
                                        $second_EMERGENCY += $total_EMERGENCY[$x];
                                        $second_GSIS += $total_GSIS[$x];
                                        $second_NHMFC += $total_NHMFC[$x];
                                        $second_PAG_IBIG += $total_PAG_IBIG[$x];
                                ?>
                                <tr>
                                    <td>PAGE <?php echo $x+1; ?></td>
                                    <td><?php echo number_format($total_conso_loan[$x], 2); ?></td>
                                    <td><?php echo number_format($total_GFAL[$x], 2); ?></td>
                                    <td><?php echo number_format($total_MPL[$x], 2); ?></td>
                                    <td><?php echo number_format($total_EMERGENCY_LOAN[$x], 2); ?></td>
                                    <td><?php echo number_format($total_CPL[$x], 2); ?></td>
                                    <td><?php echo number_format($total_POLICY_LOAN[$x], 2); ?></td>
                                    <td><?php echo number_format($total_OPT_PL_LOAN[$x], 2); ?></td>
                                    <td><?php echo number_format($total_OPT_INSURANCE[$x], 2); ?></td>
                                    <td><?php echo number_format($total_EDU_ASSISTANCE[$x], 2); ?></td>
                                    <td><?php echo number_format($total_Membership[$x], 2); ?></td>
                                    <td><?php echo number_format($total_Kamanggagawa[$x], 2); ?></td>
                                    <td><?php echo number_format($total_PROJECT[$x], 2); ?></td>
                                    <td><?php echo number_format($total_CASH_LOAN[$x], 2); ?></td>
                                    <td><?php echo number_format($total_EMERGENCY[$x], 2); ?></td>
                                    <td><?php echo number_format($total_GSIS[$x], 2); ?></td>
                                    <td><?php echo number_format($total_NHMFC[$x], 2); ?></td>
                                    <td><?php echo number_format($total_PAG_IBIG[$x], 2); ?></td>

                                </tr>

                                  <?php
                                    }
                                ?>
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
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>

                                        </tr>
                                      
                                   <tr>
                                    <td>TOTAL</td>
                                    <td style="text-align:center;font-weight: bold;"><?php echo number_format($second_conso_loan, 2); ?></td>
                                    <td style="text-align:center;font-weight: bold;"><?php echo number_format($second_GFAL, 2); ?></td>
                                    <td style="text-align:center;font-weight: bold;"><?php echo number_format($second_MPL, 2); ?></td>
                                    <td style="text-align:center;font-weight: bold;"><?php echo number_format($second_EMERGENCY_LOAN, 2); ?></td>
                                    <td style="text-align:center;font-weight: bold;"><?php echo number_format($second_CPL, 2); ?></td>
                                    <td style="text-align:center;font-weight: bold;"><?php echo number_format($second_POLICY_LOAN, 2); ?></td>
                                    <td style="text-align:center;font-weight: bold;"><?php echo number_format($second_OPT_PL_LOAN, 2); ?></td>
                                    <td style="text-align:center;font-weight: bold;"><?php echo number_format($second_OPT_INSURANCE, 2); ?></td>
                                    <td style="text-align:center;font-weight: bold;"><?php echo number_format($second_EDU_ASSISTANCE, 2); ?></td>
                                    <td style="text-align:center;font-weight: bold;"><?php echo number_format($second_Membership, 2); ?></td>
                                    <td style="text-align:center;font-weight: bold;"><?php echo number_format($second_Kamanggagawa, 2); ?></td>
                                    <td style="text-align:center;font-weight: bold;"><?php echo number_format($second_PROJECT, 2); ?></td>
                                    <td style="text-align:center;font-weight: bold;"><?php echo number_format($second_CASH_LOAN, 2); ?></td>
                                    <td style="text-align:center;font-weight: bold;"><?php echo number_format($second_EMERGENCY, 2); ?></td>
                                    <td style="text-align:center;font-weight: bold;"><?php echo number_format($second_GSIS, 2); ?></td>
                                    <td style="text-align:center;font-weight: bold;"><?php echo number_format($second_NHMFC, 2); ?></td>
                                    <td style="text-align:center;font-weight: bold;"><?php echo number_format($second_PAG_IBIG, 2); ?></td>

                                </tr>
                            </tbody>
                                </table> 

                            <!-- -------------------------------------------------------------------------------------------------------------------------------------------------- -->
                            <div style='page-break-before:always'></div>
                            <!-- -------------------------------------------------------------------------------------------------------------------------------------------------- -->

                            <table style="width:100%;border-bottom:0px;" class="headerHide">
                        <thead>
                        <tr>
                                <td align="center" colspan="9"><label>GENERAL PAYROLL</label></td>
                            </tr>
                            <tr>
                                <td align="center" colspan="9"><label>NATIONAL WATER RESOURCES BOARD</label></td>
                            </tr>
                            <tr>
                                <td><br></td>
                            </tr>
                            <tr>
                                <td align="center" style="text-decoration: underline;" colspan="9"><b><?php echo "&emsp;".date('F d, Y',strtotime(@$payroll_period[0]['start_date'])); ?></b> &emsp; to &emsp; <b><?php echo date('F d, Y',strtotime(@$payroll_period[0]['end_date'])); ?></b>&emsp;</td>
                            </tr>
                            <tr>
                                <td style="text-align:center;font-size: 11px;font-style: italic;padding-top:0px;" colspan="9">Period</td>
                            </tr>
                            <tr>
                                <td><br></td>
                            </tr>
                            <tr>
                                <td><b>ENTITY NAME: NATIONAL WATER RESOURCES BOARD</b></td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>Payroll No.:___________________</td>
                            </tr>
                            <tr>
                                <td><b>Fund Cluster: </b><u>01</u></td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>Sheet__________ of __________ Sheets</td>
                            </tr>
                            <tr>
                                <td><b>Division: </b><b class="division_label"> <p class="division_label"></p></b></td>
                            </tr>
                            <tr>
                                <td align="left">We Acknowledge receipt of cash opposite our name as full compensation for services rendered for the period.</td>
                            </tr>
                        </thead>
                    </table>

                    <table style="width:100%; border-bottom:0px;" id="tblGSIS">
                            <tr>
                                <td rowspan="2">Ser. No.</td>
                                <td rowspan="2">Name</td>
                                <td rowspan="2">Emp No.</td>
                                <td colspan="12"><b>DEDUCTIONS</b></td>
                            
                            </tr>
                            <tr>
                                <td>RLIP</td>
                                <td>GSIS LOAN</td>
                                <td>LCHL</td>
                                <td>TAX</td>
                                <td>PHIL</td>
                                <td>HDMF</td>
                                <td>MP2</td>
                                <td>STLRF</td>
                                <td>LBP LIVELIHOOD</td>
                                <td>NWRBEA</td>
                                <td>LATE & ABSENSES</td>
                            </tr>
                            <?php
                             $total_rlip = 0;
                             $total_gsis_loan = 0;
                             $total_lchl2 = 0;
                             $total_cut_wh_tax_amt = 0;
                             $total_cut_philhealth_amt = 0;
                             $total_cut_pagibig_amt = 0;
                             $total_val = 0;
                             $total_val2 = 0;
                             $total_val3 = 0;
                             $total_union_dues_amt = 0;
                             $total_totalAbs_late = 0;
                             $total_total_deduct = 0;
                                 for($v = 0 ; $v < sizeof($payroll); $v++ ){ 
                                    

                                    $sss_gsis_amt =$payroll[$v]['sss_gsis_amt']; 
                                    $total_rlip += $payroll[$v]['sss_gsis_amt'];
                                    
                                    $total_gsis = get_key(2,$loanDeductions[$payroll[$v]['employee_id']]) + get_key(41,$loanDeductions[$payroll[$v]['employee_id']]) + get_key(35,$loanDeductions[$payroll[$v]['employee_id']]) + get_key(4,$loanDeductions[$payroll[$v]['employee_id']]) + get_key(24,$loanDeductions[$payroll[$v]['employee_id']]) + get_key(39,$loanDeductions[$payroll[$v]['employee_id']]) + get_key(18,$loanDeductions[$payroll[$v]['employee_id']]) + get_key(8,$loanDeductions[$payroll[$v]['employee_id']]) + get_key(40,$loanDeductions[$payroll[$v]['employee_id']]) + get_key(45,$loanDeductions[$payroll[$v]['employee_id']]);
                                    $total_gsis_loan += $total_gsis;

                                    $total_lchl = get_key(7,$loanDeductions[$payroll[$v]['employee_id']]) + get_key(20,$loanDeductions[$payroll[$v]['employee_id']]);
                                    $total_lchl2 += $total_lchl;

                                    $cut_wh_tax_amt = $payroll[$v]['wh_tax_amt'] ; 
                                    $total_cut_wh_tax_amt += $cut_wh_tax_amt;

                                    $cut_philhealth_amt = $payroll[$v]['philhealth_amt'] ;
                                    $total_cut_philhealth_amt += $cut_philhealth_amt;

                                    $cut_pagibig_amt = $payroll[$v]['pagibig_amt'] ; 
                                    $total_cut_pagibig_amt += $cut_pagibig_amt;
                                    
                                    $val = 0; $val = $payroll[$v]['mp2_contribution_amt'] ; 
                                    $total_val += $val;

                                    $val2 = 0; $val2 = get_key(21,$loanDeductions[$payroll[$v]['employee_id']]);
                                    $total_val2 += $val2;

                                    $val3 = 0; $val3 = get_key(39,$loanDeductions[$payroll[$v]['employee_id']]); 
                                    $total_val3 += $val3;

                                    $union_dues_amt = $payroll[$v]['union_dues_amt'];
                                    $total_union_dues_amt += $union_dues_amt;

                                    $totalAbs_late = $payroll[$v]['total_tardiness_amt']; 
                                    $total_totalAbs_late += $totalAbs_late;

                                    $total_deduct = $payroll[$v]['sss_gsis_amt'] //RLIP
                                    + $total_gsis //GSIS LOANS
                                    + $total_lchl //LCHL
                                    + $payroll[$v]["col2_value"]
                                    + $payroll[$v]["col1_value"]
                                    + $payroll[$v]['wh_tax_amt'] //TAX
                                    + $payroll[$v]['philhealth_amt'] //PHIL
                                    + $payroll[$v]['pagibig_amt'] //HDMF
                                    + $payroll[$v]['mp2_contribution_amt'] //MP2
                                    + get_key(21,$loanDeductions[$payroll[$v]['employee_id']])
                                    + get_key(39,$loanDeductions[$payroll[$v]['employee_id']])
                                    + $payroll[$v]['union_dues_amt']
                                    + $totalAbs_late;

                                    $total_total_deduct += $total_deduct;

                            ?>
                            <tr>
                                <td><?php echo ($v+1); ?></td>
                                <td valign="top" style="text-align:left;font-weight: bold;"><?php echo ((isset($payroll[$v]['last_name']) && $payroll[$v]['last_name'] != "")?$this->Helper->decrypt($payroll[$v]['last_name'],$payroll[$v]['employee_id']):"") . ((isset($payroll[$v]['first_name']) && $payroll[$v]['first_name'] != "")?"&nbsp;".$this->Helper->decrypt($payroll[$v]['first_name'],$payroll[$v]['employee_id']):"") . ((isset($payroll[$v]['middle_name']) && $payroll[$v]['middle_name'] != "")?"&nbsp;".$this->Helper->decrypt($payroll[$v]['middle_name'],$payroll[$v]['employee_id']):"").((isset($payroll[$v]['extension']) && $payroll[$v]['extension'] != "")?"&nbsp;".$this->Helper->decrypt($payroll[$v]['extension'],$payroll[$v]['employee_id']):"")?></td>
                                </td>
                                <td><?php echo ((isset($payroll[$v]['employee_id_number']) && $payroll[$v]['employee_id_number'] != "")?$this->Helper->decrypt($payroll[$v]['employee_id_number'],$payroll[$v]['employee_id']):"") ?></td>
                                <td valign="middle" align="right"><?php echo number_format($sss_gsis_amt,2); ?></td>
                                <td valign="middle" align="right"><?php echo number_format($total_gsis,2); ?></td>
                                <td valign="middle" align="right"><?php echo number_format($total_lchl,2); ?></td>
                                <td valign="middle" align="right"><?php echo number_format($cut_wh_tax_amt,2); ?></td>
                                <td valign="middle" align="right"><?php echo number_format($cut_philhealth_amt,2); ?></td>
                                <td valign="middle" align="right"><?php echo number_format($cut_pagibig_amt,2); ?></td>
                                <td valign="middle" align="right"><?php echo number_format($val,2); ?></td>
                                <td valign="middle" align="right"><?php echo number_format($val2,2); ?></td>
                                <td valign="middle" align="right"><?php echo number_format($val3,2); ?></td>
                                <td valign="middle" align="right"><?php echo number_format($union_dues_amt,2); ?></td>
                                <td valign="middle" align="right"><?php echo number_format($totalAbs_late,2); ?></td>
                                
          
                            </tr> 
                           

                           
                            <?php $rows2++; if($rows2 === 10) { $last_row2 = 1; } else { $last_row2 = 0; } 
                              } 
                            ?>
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
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>

                            </tr>
                            
                            <tr>
                                <td></td>
                                <td style="text-align:left;font-weight: bold;">TOTAL</td>
                                <td></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($total_rlip,2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($total_gsis_loan,2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($total_lchl2,2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($total_cut_wh_tax_amt,2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($total_cut_philhealth_amt,2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($total_cut_pagibig_amt,2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($total_val,2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($total_val2,2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($total_val3,2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($total_union_dues_amt,2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($total_totalAbs_late,2); ?></td>

                            </tr>
                            <tr>
                                <td></td>
                                <td style="text-align:left;font-weight: bold;">TOTAL PER PAYROLL</td>
                                <td></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($secondTableTotalRLIP, 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($secondTableTotalGSIS, 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($secondTableTotalLCHL, 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($secondTableTotalTAX, 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($secondTableTotalPHIL, 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($secondTableTotalHDMF, 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($secondTableTotalMP2, 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($secondTableTotalMPL, 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($secondTableTotalLBP, 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($secondTableTotalNWRBEA, 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($secondTableTotalLATE, 2); ?></td>


                            </tr>
                            <tr>
                                <td></td>
                                <td style="text-align:left;font-weight: bold;">DIFFERENCE</td>
                                <td></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($total_rlip - $secondTableTotalRLIP,2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($total_gsis_loan - $secondTableTotalGSIS,2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($total_lchl2 - $secondTableTotalLCHL,2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($total_cut_wh_tax_amt - $secondTableTotalTAX,2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($total_cut_philhealth_amt - $secondTableTotalPHIL,2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($total_cut_pagibig_amt - $secondTableTotalHDMF,2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($total_val - $secondTableTotalMP2,2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($total_val2 - $secondTableTotalMPL,2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($total_val3 - $secondTableTotalLBP,2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($total_union_dues_amt - $secondTableTotalNWRBEA,2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($total_totalAbs_late - $secondTableTotalLATE,2); ?></td>

                            </tr>
                        </table> 

                        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------- -->
                        <div style='page-break-before:always'></div>
                            <!-- -------------------------------------------------------------------------------------------------------------------------------------------------- -->

                            <table style="width:100%;border-bottom:0px;" class="headerHide">
                        <thead>
                        <tr>
                                <td align="center" colspan="9"><label>GENERAL PAYROLL</label></td>
                            </tr>
                            <tr>
                                <td align="center" colspan="9"><label>NATIONAL WATER RESOURCES BOARD</label></td>
                            </tr>
                            <tr>
                                <td><br></td>
                            </tr>
                            <tr>
                                <td align="center" style="text-decoration: underline;" colspan="9"><b><?php echo "&emsp;".date('F d, Y',strtotime(@$payroll_period[0]['start_date'])); ?></b> &emsp; to &emsp; <b><?php echo date('F d, Y',strtotime(@$payroll_period[0]['end_date'])); ?></b>&emsp;</td>
                            </tr>
                            <tr>
                                <td style="text-align:center;font-size: 11px;font-style: italic;padding-top:0px;" colspan="9">Period</td>
                            </tr>
                            <tr>
                                <td><br></td>
                            </tr>
                            <tr>
                                <td><b>ENTITY NAME: NATIONAL WATER RESOURCES BOARD</b></td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>Payroll No.:___________________</td>
                            </tr>
                            <tr>
                                <td><b>Fund Cluster: </b><u>01</u></td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>Sheet__________ of __________ Sheets</td>
                            </tr>
                            <tr>
                                <td><b>Division: </b><b class="division_label"> <p class="division_label"></p></b></td>
                            </tr>
                            <tr>
                                <td align="left">We Acknowledge receipt of cash opposite our name as full compensation for services rendered for the period.</td>
                            </tr>
                        </thead>
                    </table>

                    <table style="width:100%; border-bottom:0px;" id="tblGSIS">
                            <tr>
                                <td rowspan="2">Ser NO.</td>
                                <td rowspan="2">NAME</td>
                                <td rowspan="2">EMP. NO.</td>
                                <td colspan="9"><b>GSIS</b></td>
                                <td colspan="5"><b>NWRBEA</b></td>
                                <td colspan="3"><b>LOW COST HOUSING</b></td>
                            </tr>
                            <tr>
                                
                                <td>CONSO-LOAN</td>
                                <td>GFAL</td>
                                <td>MPL</td>
                                <td>EMERGENCY LOAN</td>
                                <td>CPL</td>
                                <td>POLICY LOAN</td>
                                <td>OPT. PL LOAN</td>
                                <td>OPT. INSURANCE</td>
                                <td>EDU-ASSISTANCE</td>
                                <td>Membership</td>
                                <td>Kamanggagawa</td>
                                <td>PROJECT</td>
                                <td>CASH LOAN</td>
                                <td>EMERGENCY</td>
                                <td>GSIS</td>
                                <td>NHMFC</td>
                                <td>PAG-IBIG</td>
                            </tr>
                            
                            
                            <?php
                                 $conso_loan_total = 0;
                                 $gfal_total = 0;
                                 $mpl_total = 0;
                                 $emergency_loan_total = 0;
                                 $cpl_total = 0;
                                 $policy_loan_total  = 0;
                                 $opt_loan_total  = 0;
                                 $opt_insurance_total  = 0;
                                 $edu_assistance_total = 0;
                                 $membership_total = 0;
                                 $kamanggagawa_total = 0;
                                 $project_total = 0;
                                 $cash_loan_total = 0;
                                 $emergency_total = 0;
                                 $gsis_total = 0;
                                 $nhmfc_total = 0;
                                 $pagibig_total = 0;

                             foreach ($payroll as $k => $v) {
                            
                            ?>
                            
                            <tr>

                                <td><?php echo ($k+1); ?></td>
                                <td style="text-align:left;font-weight: bold;"><?php echo ((isset($v['last_name']) && $v['last_name'] != "")?$this->Helper->decrypt($v['last_name'],$v['employee_id']):"") . ((isset($v['first_name']) && $v['first_name'] != "")?"&nbsp;".$this->Helper->decrypt($v['first_name'],$v['employee_id']):"") . ((isset($v['middle_name']) && $v['middle_name'] != "")?"&nbsp;".$this->Helper->decrypt($v['middle_name'],$v['employee_id']):"") . ((isset($v['extension']) && $v['extension'] != "")?"&nbsp;".$this->Helper->decrypt($v['extension'],$v['employee_id']):"")//. "<br><span style='font-weight: normal;'>" . strtoupper(@$v['position_name']) . "</span>"; ?>
                                </td>
                                                                                                                                               
                                <td ><?php echo (isset($v['employee_id_number']) && $v['employee_id_number'] != "")?$this->Helper->decrypt($v['employee_id_number'],$v['employee_id']):""; ?></td>       
                                <td valign="middle" align="right"><?php $val = 0; $val = get_key(2,$loanDeductions[$v['employee_id']]); echo number_format($val,2); $conso_loan_total += $val; ?></td>
                                <td valign="middle" align="right"><?php $val = 0; $val = get_key(45,$loanDeductions[$v['employee_id']]); echo number_format($val,2); $gfal_total += $val; ?></td>
                                <td valign="middle" align="right"><?php $val = 0; $val = get_key(35,$loanDeductions[$v['employee_id']]); echo number_format($val,2); $mpl_total += $val; ?></td>
                                <td valign="middle" align="right"><?php $val = 0; $val = get_key(4,$loanDeductions[$v['employee_id']]); echo number_format($val,2); $emergency_loan_total += $val; ?></td>                                
                                <td valign="middle" align="right"><?php $val = 0; $val = get_key(36,$loanDeductions[$v['employee_id']]); echo number_format($val,2); $cpl_total += $val; ?></td>
                                <td valign="middle" align="right"><?php $val = 0; $val = get_key(24,$loanDeductions[$v['employee_id']]); echo number_format($val,2); $policy_loan_total += $val; ?></td>
                                <td valign="middle" align="right"><?php $val = 0; $val = get_key(16,$loanDeductions[$v['employee_id']]); echo number_format($val,2); $opt_loan_total += $val; ?></td>
                                <td valign="middle" align="right"><?php $val = 0; $val = get_key(18,$loanDeductions[$v['employee_id']]); echo number_format($val,2); $opt_insurance_total += $val; ?></td>
                                <td valign="middle" align="right"><?php $val = 0; $val = get_key(8,$loanDeductions[$v['employee_id']]); echo number_format($val,2); $edu_assistance_total += $val; ?></td>
                                <td valign="middle" align="right"><?php $val = 0; $val = get_key(40,$loanDeductions[$v['employee_id']]); echo number_format($val,2); $membership_total += $val; ?></td>
                                <td valign="middle" align="right"><?php $val = 0; $val = get_key(41,$loanDeductions[$v['employee_id']]); echo number_format($val,2); $kamanggagawa_total += $val; ?></td>
                                <td valign="middle" align="right"><?php $val = 0; $val = get_key(42,$loanDeductions[$v['employee_id']]); echo number_format($val,2); $project_total += $val; ?></td>
                                <td valign="middle" align="right"><?php $val = 0; $val = get_key(43,$loanDeductions[$v['employee_id']]); echo number_format($val,2); $cash_loan_total += $val; ?></td>
                                <td valign="middle" align="right"><?php $val = 0; $val = get_key(44,$loanDeductions[$v['employee_id']]); echo number_format($val,2); $emergency_total += $val; ?></td>
                                <td valign="middle" align="right"><?php $val = 0; $val = get_key(7,$loanDeductions[$v['employee_id']]); echo number_format($val,2); $gsis_total += $val; ?></td>
                                <td valign="middle" align="right"><?php $val = 0; $val = get_key(31,$loanDeductions[$v['employee_id']]); echo number_format($val,2); $nhmfc_total += $val; ?></td>                   
                                <td valign="middle" align="right"><?php $val = 0; $val = get_key(20,$loanDeductions[$v['employee_id']]); echo number_format($val,2); $pagibig_total += $val; ?></td>                            
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
                                <td style="text-align:center;font-weight: bold;">TOTAL</td>
                                <td>&nbsp;</td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($conso_loan_total , 2);?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total3['gfal'] , 2);?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total3['mpl'] , 2);?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total3['emergency_loan'] , 2);?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total3['cpl'] , 2);?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total3['policy_loan'] , 2);?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total3['opt_loan'] , 2);?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total3['opt_insurance'] , 2);?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total3['edu_assistance'] , 2);?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total3['membership'] , 2);?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total3['kamanggagawa'] , 2);?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total3['project'] , 2);?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total3['cash_loan'] , 2);?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total3['emergency'] , 2);?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total3['gsis'] , 2);?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total3['nhmfc'] , 2);?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total3['pagibig'] , 2);?></td>
                            </tr>
                            <tr>
                            <td>&nbsp;</td>
                                <td style="text-align:center;font-weight: bold;">TOTAL PER PAYROLL</td>
                                <td>&nbsp;</td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($grand_total4['conso_loan'], 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($grand_total4['gfal'], 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($grand_total4['mpl'], 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($grand_total4['emergency_loan'], 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($grand_total4['cpl'], 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($grand_total4['policy_loan'], 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($grand_total4['opt_loan'], 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($grand_total4['opt_insurance'], 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($grand_total4['edu_assistance'], 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($grand_total4['membership'], 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($grand_total4['kamanggagawa'], 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($grand_total4['project'], 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($grand_total4['cash_loan'], 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($grand_total4['emergency'], 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($grand_total4['gsis'], 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($grand_total4['nhmfc'], 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($grand_total4['pagibig'], 2); ?></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td style="text-align:center;font-weight: bold;">DIFFERENCE</td>
                                <td>&nbsp;</td>
                               
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($conso_loan_total - $grand_total4['conso_loan'], 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($grand_total3['gfal'] - $grand_total4['gfal'], 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($grand_total3['mpl']- $grand_total4['mpl'], 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($grand_total3['emergency_loan'] - $grand_total4['emergency_loan'], 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($grand_total3['cpl'] - $grand_total4['cpl'], 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($grand_total3['policy_loan'] - $grand_total4['policy_loan'], 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($grand_total3['opt_loan'] - $grand_total4['opt_loan'], 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($grand_total3['opt_insurance'] - $grand_total4['opt_insurance'], 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($grand_total3['edu_assistance'] - $grand_total4['edu_assistance'], 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($grand_total3['membership'] - $grand_total4['membership'], 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($grand_total3['kamanggagawa'] - $grand_total4['kamanggagawa'], 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($grand_total3['project'] - $grand_total4['project'], 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($grand_total3['cash_loan'] - $grand_total4['cash_loan'], 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($grand_total3['emergency'] - $grand_total4['emergency'], 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($grand_total3['gsis'] - $grand_total4['gsis'], 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($grand_total3['nhmfc'] - $grand_total4['nhmfc'], 2); ?></td>
                                <td style="text-align:center;font-weight: bold;"><?php echo number_format($grand_total3['pagibig'] - $grand_total4['pagibig'], 2); ?></td>
                            </tr>
                            <?php
                            
                                $gsis_deduction_total = $conso_loan_total 
                                + $grand_total3['gfal'] 
                                + $grand_total3['mpl'] 
                                + $grand_total3['emergency_loan'] 
                                + $grand_total3['cpl'] 
                                + $grand_total3['policy_loan'] 
                                + $grand_total3['opt_loan']
                                + $grand_total3['opt_insurance'] 
                                + $grand_total3['edu_assistance'];

                                $nwrbea_total = $grand_total3['membership'] 
                                + $grand_total3['kamanggagawa']
                                + $grand_total3['project']
                                + $grand_total3['cash_loan'] 
                                + $grand_total3['emergency'];

                                $low_cost_housing_total = $grand_total3['gsis'] 
                                + $grand_total3['nhmfc'] 
                                + $grand_total3['pagibig'];

                                $gsis_deduction_total2 = $grand_total4['conso_loan']  
                                + $grand_total4['gfal'] 
                                + $grand_total4['mpl'] 
                                + $grand_total4['emergency_loan'] 
                                + $grand_total4['cpl'] 
                                + $grand_total4['policy_loan'] 
                                + $grand_total4['opt_loan']
                                + $grand_total4['opt_insurance'] 
                                + $grand_total4['edu_assistance'];

                                $nwrbea_total2 = $grand_total4['membership'] 
                                + $grand_total4['kamanggagawa']
                                + $grand_total4['project']
                                + $grand_total4['cash_loan'] 
                                + $grand_total4['emergency'];

                                $low_cost_housing_total2 = $grand_total4['gsis'] 
                                + $grand_total4['nhmfc'] 
                                + $grand_total4['pagibig'];
                            ?>
                                 <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td colspan="9">&nbsp;</td>
                                <td colspan="5">&nbsp;</td>
                                <td colspan="3">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td colspan="9" style="text-align:center;font-weight: bold;"><?php echo number_format($gsis_deduction_total ,2); ?></td>
                                <td colspan="5" style="text-align:center;font-weight: bold;"><?php echo number_format($nwrbea_total, 2);?></td>
                                <td colspan="3" style="text-align:center;font-weight: bold;"><?php echo number_format($low_cost_housing_total, 2);?></td>
                            </tr>
                       
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td colspan="9" style="text-align:center;font-weight: bold;"><?php echo number_format($gsis_deduction_total -  $gsis_deduction_total2 ,2); ?></td>
                                <td colspan="5" style="text-align:center;font-weight: bold;"><?php echo number_format($nwrbea_total - $nwrbea_total2, 2);?></td>
                                <td colspan="3" style="text-align:center;font-weight: bold;"><?php echo number_format($low_cost_housing_total - $low_cost_housing_total2, 2);?></td>
                            </tr>
                            </table>
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
    