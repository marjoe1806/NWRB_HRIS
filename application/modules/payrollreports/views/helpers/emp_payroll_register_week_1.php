<div class="row">
    <div class="col-md-12 text-right">
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
                        @page {  size: landscape;  }
                        body { font-family:Calibri; font-size: 10; color: black; }
                        table{ border-collapse: collapse; }
                        .page-break{ display: table; vertical-align:top; width: 100% !important; page-break-after: always !important; table-layout: inherit; margin-top:2px; }
                    }
                </style> 
                <style type="text/css" media="all">
                /* .break{  page-break-before: always; } */
                    table#tmpTable thead tr th, table#tmpTable tbody tr td{ padding: 2px; }
                    table#tmpTable thead tr th{ border: 1px solid black; }
                    table#tmpTable{
                        font-size: 9px;
                        color: #000;
                    }
                    table#hdTable, table#ftTable{
                        font-size: 12px;
                        color: #000;
                    }
                </style>
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
                                <td><br></td>
                            </tr>
                            <tr>
                                <td align="center" style="text-decoration: underline;"><b><?php echo "&emsp;".date('F 1, Y',strtotime(@$payroll_period[0]['start_date'])); ?></b> &emsp; to &emsp; <b><?php echo date('F 7, Y',strtotime(@$payroll_period[0]['end_date'])); ?></b>&emsp;</td>
                            </tr>
                            <tr>
                                <td style="text-align:center;font-size: 11px;font-style: italic;padding-top:0px;">Period</td>
                            </tr>
                            <tr>
                                <td><br></td>
                            </tr>
                            <tr>
                                <td align="left">&emsp;&emsp;&emsp;&emsp;We Acknowledge receipt of cash opposite our name as full compensation for services rendered for the period.</td>
                            </tr>
                        </thead>
                    </table>
                    <div class="table-container table-responsive"><?php $page++; ?>
                        <table style="width:100%;" id="tmpTable">
                            <thead>
                                <tr>
                                    <th valign="middle" style="text-align:center;" rowspan="2">No</th>
                                    <th valign="middle" style="text-align:center; min-width: 140px;" rowspan="2">NAME<br>(<span style="letter-spacing: 5px;">Position</span>)</th>
                                    <th valign="middle" style="text-align:center;" rowspan="2">Employee No.</th>
                                    <th valign="middle" style="text-align:center; min-width: 50px;" rowspan="2">BASIC SALARY</th>
                                    <th valign="middle" style="text-align:center;" colspan="2">OTHER COMPENSATIONS</th>
                                    <th valign="middle" style="text-align:center; min-width: 50px;" rowspan="2">GROSS AMOUNT</th>
                                    <th valign="middle" style="text-align:center; min-width: 50px;" rowspan="2">PLUS: PERA</th>
                                    <th valign="middle" style="text-align:center; min-width: 50px;" rowspan="2">OTHER EARNINGS</th>
                                    <th valign="middle" style="text-align:center; min-width: 50px;letter-spacing: 8px;" colspan="22">DEDUCTION</th>
                                    <th valign="middle" style="text-align:center; min-width: 50px;" rowspan="2">TOTAL<br>DEDUCTION</th>
                                    <th valign="middle" style="text-align:center;" rowspan="2">NET<br>AMOUNT<br>DUE</th>
                                    <th valign="middle" style="text-align:center;" rowspan="2">SIGNATURE<br>OF<br>RECIPIENT</th>
                                    <th valign="middle" style="text-align:center;" rowspan="2">REMARKS</th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th valign="top" style="text-align:center;">LESS:<br>W/O PAY<br>FOR<br>SALARY</th>
                                    <th valign="top" style="text-align:center;">LESS:<br>W/O PAY<br>FOR<br>PERA</th>
                                    <th valign="top" style="text-align:center;">OTHER<br>DEDUCTIONS</th>
                                    <th valign="top" style="text-align:center;">WITH<br>-HOLDING<br>TAX<br>(412)</th>
                                    <th valign="top" style="text-align:center;">LIFE & RET.<br>INS.<br>(413A)</th>
                                    <th valign="top" style="text-align:center;">HOSPITA<br>LIZATION (413-A2)</th>
                                    <th valign="top" style="text-align:center;">CONSOLI<br>DATED LOAN</th>
                                    <th valign="top" style="text-align:center;">OPTL.<br>UNLTD.<br>(413-A2)</th>
                                    <th valign="top" style="text-align:center;">SALARY<br>LOAN<br>(413-B)</th>
                                    <th valign="top" style="text-align:center;">POLICY<br>LOAN<br>(413-B1)</th>
                                    <th valign="top" style="text-align:center;">OPTL.<br>POLICY<br>LOAN<br>(413-B2)</th>
                                    <th valign="top" style="text-align:center;">SOS</th>
                                    <th valign="top" style="text-align:center;">ELA</th>
                                    <th valign="top" style="text-align:center;">EDUCATIONAL<br>ASSISTANCE<br>LOAN<br>(EAL)</th>
                                    <th valign="top" style="text-align:center;">CASH<br>ADVANCE<br>LOAN</th>
                                    <th valign="top" style="text-align:center;">GSIS<br>MULTI-PURPOSE</th>
                                    <th valign="top" style="text-align:center;">EMER-<br>GENCY<br>LOAN<br>(413-B3)</th>
                                    <th valign="top" style="text-align:center;">PAG-IBIG<br>CALAMITY<br>LOAN</th>
                                    <!-- <th valign="top" style="text-align:center;">UNION<br>DUES</th> -->
                                    <th valign="top" style="text-align:center;">PAG-IBIG<br>SHARE<br>(414)</th>
                                    <th valign="top" style="text-align:center;">PAG-IBIG<br>LOAN (414A)</th>
                                    <th valign="top" style="text-align:center;">PHIL. CARE<br>(415)</th>
                                    <th valign="top" style="text-align:center;">HOME<br>MORTGAGE FINANCE<br>CORP.<br>(414B)</th>
                                </tr>
                            </thead>
                            <?php
                                $page_total = array(
                                    'salary'=>0.00, 'gross_pay'=>0.00, 'pera_amt'=>0.00,'other_earnings_amt'=>0.00,'other_deductions_amt'=>0.00, 'pera_wop_amt'=>0.00, 'wh_tax_amt'=>0.00,  'sss_gsis_amt'=>0.00,'hospitalization_amt'=>0.00,   'gsis_consolidated'=>0.00,   'optional_unltd'=>0.00,    'salary_loan_amt'=>0.00,  'gsis_policy'=>0.00,   'gsis_optional_loan'=>0.00, 
                                    'sos_loan'=>0.00,   'ela_loan'=>0.00,  'gsis_educational'=>0.00,  'cash_advance_loan'=>0.00,  'gsis_ecard_plus'=>0.00, 'gsis_emergency'=>0.00, 
                                    'pagibig_calamity_amt'=>0.00, 'union_dues_amt'=>0.00, 'pagibig_amt'=>0.00,  'pagibig_loans'=>0.00, 'philhealth_amt'=>0.00,'gsis_nhmfc'=>0.00, 'tardiness_amt'=>0.00,    'total_deductions'=>0.00,  'net_amnt_due'=>0.00
                                );
                                $grand_total = array(
                                    'salary'=>0.00, 'gross_pay'=>0.00, 'pera_amt'=>0.00,'other_earnings_amt'=>0.00,'other_deductions_amt'=>0.00, 'pera_wop_amt'=>0.00, 'wh_tax_amt'=>0.00,  'sss_gsis_amt'=>0.00,'hospitalization_amt'=>0.00,   'gsis_consolidated'=>0.00,   'optional_unltd'=>0.00,    'salary_loan_amt'=>0.00,  'gsis_policy'=>0.00,   'gsis_optional_loan'=>0.00, 
                                    'sos_loan'=>0.00,   'ela_loan'=>0.00,  'gsis_educational'=>0.00,  'cash_advance_loan'=>0.00,  'gsis_ecard_plus'=>0.00, 'gsis_emergency'=>0.00, 
                                    'pagibig_calamity_amt'=>0.00, 'union_dues_amt'=>0.00, 'pagibig_amt'=>0.00,  'pagibig_loans'=>0.00, 'philhealth_amt'=>0.00,'gsis_nhmfc'=>0.00, 'tardiness_amt'=>0.00,    'total_deductions'=>0.00,  'net_amnt_due'=>0.00
                                );
                            ?>
                            <tbody>
                                <tr><td colspan="32" align="center"><span id="division_label" style="font-weight: bolder;font-style: italic;"></span></td></tr>
                                <tr><td colspan="32">&nbsp;</td></tr>
                            <?php foreach ($payroll as $k => $v) { 
                                    $rowtotaldeduction = 0; ?>
                                <tr>
                                    <td valign="top" style="text-align:center;"><?php echo ($k+1); ?></td>
                                    <td valign="top" style="text-align:left;font-weight: bold;"><?php echo ((isset($v['last_name']) && $v['last_name'] != "")?$this->Helper->decrypt($v['last_name'],$v['employee_id']):"") . ((isset($v['first_name']) && $v['first_name'] != "")?"&emsp;&nbsp;".$this->Helper->decrypt($v['first_name'],$v['employee_id']):"") . ((isset($v['middle_name']) && $v['middle_name'] != "")?"&nbsp;".$this->Helper->decrypt($v['middle_name'],$v['employee_id']):"") . "<br><span style='font-weight: normal;'>" . strtoupper(@$v['position_name']) . "</span>"; ?></td>
                                    <td valign="top" style="text-align:center;"><?php echo (isset($v['employee_id_number']) && $v['employee_id_number'] != "")?$this->Helper->decrypt($v['employee_id_number'],$v['employee_id']):""; ?> </td>
                                    <td valign="top" align="right"><?php echo number_format($v['basic_pay'],2); $page_total["salary"] += $v['basic_pay']; $grand_total["salary"] += $v['basic_pay']; ?></td>
                                    <td><!-- hospitalization --></td>
                                    <td><!-- hospitalization --></td>
                                    <td valign="top" align="right"><?php $gross_pay = $v['cut_off_1']; echo number_format($gross_pay,2); $page_total["gross_pay"] += $gross_pay; $grand_total["gross_pay"] += $gross_pay; ?></td>
                                    <td valign="top" align="right"><?php echo number_format($v['pera_amt'],2); $page_total["pera_amt"] += $v['pera_amt']; $grand_total["pera_amt"] += $v['pera_amt']; ?></td>
                                    <td valign="top" align="right"><?php echo number_format($v['total_other_earning_amt'],2); $page_total["other_earnings_amt"] += $v['total_other_earning_amt']; $grand_total["other_earnings_amt"] += $v['total_other_earning_amt']; ?></td>
                                    <td valign="top" align="right"><?php echo number_format($v['total_tardiness_amt'],2); $page_total["tardiness_amt"] += $v['total_tardiness_amt']; $grand_total["tardiness_amt"] += $v['total_tardiness_amt']; ?></td>
                                    <td valign="top" align="right"><?php echo number_format($v['pera_wop_amt'],2); $rowtotaldeduction += $v['pera_wop_amt']; $page_total["pera_wop_amt"] += $v['pera_wop_amt']; $grand_total["pera_wop_amt"] += $v['pera_wop_amt']; ?></td>
                                    <td valign="top" align="right"><?php echo number_format($v['total_other_deduct_amt'],2); $page_total["other_deductions_amt"] += $v['total_other_deduct_amt']; $grand_total["other_deductions_amt"] += $v['total_other_deduct_amt']; ?></td>
                                    <td valign="top" align="right"><?php echo number_format($v['wh_tax_amt'],2); $rowtotaldeduction += $v['wh_tax_amt']; $page_total["wh_tax_amt"] += $v['wh_tax_amt']; $grand_total["wh_tax_amt"] += $v['wh_tax_amt']; ?></td>
                                    <td valign="top" align="right"><?php echo number_format($v['sss_gsis_amt'],2); $rowtotaldeduction += $v['sss_gsis_amt']; $page_total["sss_gsis_amt"] += $v['sss_gsis_amt']; $grand_total["sss_gsis_amt"] += $v['sss_gsis_amt']; ?></td>
                                    <td><!-- hospitalization --></td>
                                    <td valign="top" align="right"><?php $val = 0; $val = get_key(2,$loanDeductions[$v['employee_id']]); echo number_format($val,2); $rowtotaldeduction += $val; $page_total["gsis_consolidated"] += $val; $grand_total["gsis_consolidated"] += $val; ?></td>
                                    <td valign="top" align="right"><?php $val = 0; $val = get_key(18,$loanDeductions[$v['employee_id']]); echo number_format($val,2); $rowtotaldeduction += $val; $page_total["optional_unltd"] += $val; $grand_total["optional_unltd"] += $val; ?></td>
                                    <td><!-- SALARY LOAN --></td>
                                    <td valign="top" align="right"><?php $val = 0; $val = get_key(24,$loanDeductions[$v['employee_id']]); echo number_format($val,2); $rowtotaldeduction += $val; $page_total["gsis_policy"] += $val; $grand_total["gsis_policy"] += $val; ?></td>
                                    <td valign="top" align="right"><?php $val = 0; $val = get_key(16,$loanDeductions[$v['employee_id']]); echo number_format($val,2); $rowtotaldeduction += $val; $page_total["gsis_optional_loan"] += $val; $grand_total["gsis_optional_loan"] += $val; ?></td>

                                    <td><!-- SOS --></td>
                                    <td><!-- ELA --></td>
                                    <td valign="top" align="right"><?php $val = 0; $val = get_key(8,$loanDeductions[$v['employee_id']]); echo number_format($val,2); $rowtotaldeduction += $val; $page_total["gsis_educational"] += $val; $grand_total["gsis_educational"] += $val; ?></td>
                                    <td><!-- CASH ADVANCE --></td>
                                    <td valign="top" align="right"><?php $val = 0; $val = get_key(5,$loanDeductions[$v['employee_id']]); echo number_format($val,2); $rowtotaldeduction += $val; $page_total["gsis_ecard_plus"] += $val; $grand_total["gsis_ecard_plus"] += $val; ?></td>
                                    <td valign="top" align="right"><?php $val = 0; $val = get_key(4,$loanDeductions[$v['employee_id']]); echo number_format($val,2); $rowtotaldeduction += $val; $page_total["gsis_emergency"] += $val; $grand_total["gsis_emergency"] += $val; ?></td>
                                    <td valign="top" align="right"><?php $val = 0; $val = get_key(22,$loanDeductions[$v['employee_id']]); echo number_format($val,2); $rowtotaldeduction += $val; $page_total["pagibig_calamity_amt"] += $val; $grand_total["pagibig_calamity_amt"] += $val; ?></td>
                                    <!-- <td valign="top" align="right"><?php echo number_format($v['union_dues_amt'],2); $rowtotaldeduction += $v['union_dues_amt']; $page_total["union_dues_amt"] += $v['union_dues_amt']; $grand_total["union_dues_amt"] += $v['union_dues_amt']; ?></td> -->
                                    <td valign="top" align="right"><?php echo number_format($v['pagibig_amt'],2); $rowtotaldeduction += $v['pagibig_amt']; $page_total["pagibig_amt"] += $v['pagibig_amt']; $grand_total["pagibig_amt"] += $v['pagibig_amt']; ?></td>
                                    <td valign="top" align="right">
                                    <?php
                                        $pagibig_loans = get_key(20,$loanDeductions[$v['employee_id']]) + get_key(21,$loanDeductions[$v['employee_id']]) + get_key(34,$loanDeductions[$v['employee_id']]) + get_key(37,$loanDeductions[$v['employee_id']]);
                                       echo number_format($pagibig_loans,2); $rowtotaldeduction += $pagibig_loans; $page_total["pagibig_loans"] += $pagibig_loans; $grand_total["pagibig_loans"] += $pagibig_loans; 
                                    ?>
                                    </td>
                                    <td valign="top" align="right"><?php echo number_format($v['philhealth_amt'],2); $rowtotaldeduction += $v['philhealth_amt']; $page_total["philhealth_amt"] += $v['philhealth_amt']; $grand_total["philhealth_amt"] += $v['philhealth_amt']; ?></td>
                                    <td valign="top" align="right"><?php $val = 0; $val = get_key(31,$loanDeductions[$v['employee_id']]); echo number_format($val,2); $rowtotaldeduction += get_key(31,$loanDeductions[$v['employee_id']]); $page_total["gsis_nhmfc"] += $val; $grand_total["gsis_nhmfc"] += $val; ?></td>
                                    <td valign="top" align="right"><?php echo number_format($v['total_deduct_amt']+$v['pera_wop_amt'],2); $page_total["total_deductions"] += $v['total_deduct_amt']+$v['pera_wop_amt']; $grand_total["total_deductions"] += $v['total_deduct_amt']+$v['pera_wop_amt']; ?></td>
                                    <td valign="top" align="right"><?php $net_amnt_due = $v['cutoff_1'] + $v['pera_amt']; echo number_format($net_amnt_due,2); $page_total["net_amnt_due"] += $net_amnt_due; $grand_total["net_amnt_due"] += $net_amnt_due; ?></td>
                                    <td style="border-bottom: 1px solid black;"></td>
                                    <td style="border: none;"></td>
                                </tr>  
                            <?php $rows++; if($rows === 10) { $last_row = 1; } else { $last_row = 0; }
                                } ?>
                                <tr class="<?php echo "";//($last_row === 1)?"page-break":""; ?>">
                                    <td valign="bottom" align="right" style="font-weight: bold;" colspan="2">TOTAL .........</td>
                                    <td valign="bottom" align="right"><?php echo ""; ?></td>
                                    <td valign="bottom" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["salary"],2); ?></td>
                                    <td></td>
                                    <td></td>
                                    <td valign="bottom" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["gross_pay"],2); ?></td>
                                    <td valign="bottom" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["pera_amt"],2); ?></td>
                                    <td valign="bottom" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["other_earnings_amt"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["tardiness_amt"],2); ?></td>
                                    <td valign="bottom" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["pera_wop_amt"],2); ?></td>
                                    <td valign="bottom" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["other_deductions_amt"],2); ?></td>
                                    <td valign="bottom" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["wh_tax_amt"],2); ?></td>
                                    <td valign="bottom" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["sss_gsis_amt"],2); ?></td>
                                    <td valign="bottom" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["hospitalization_amt"],2); ?></td>
                                    <td valign="bottom" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["gsis_consolidated"],2); ?></td>
                                    <td valign="bottom" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["optional_unltd"],2); ?></td>
                                    <td valign="bottom" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["salary_loan_amt"],2); ?></td>
                                    <td valign="bottom" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["gsis_policy"],2); ?></td>
                                    <td valign="bottom" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["gsis_optional_loan"],2); ?></td>
                                    <td valign="bottom" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["sos_loan"],2); ?></td>
                                    <td valign="bottom" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["ela_loan"],2); ?></td>
                                    <td valign="bottom" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["gsis_educational"],2); ?></td>
                                    <td valign="bottom" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["cash_advance_loan"],2); ?></td>
                                    <td valign="bottom" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["gsis_ecard_plus"],2); ?></td>
                                    <td valign="bottom" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["gsis_emergency"],2); ?></td>
                                    <td valign="bottom" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["pagibig_calamity_amt"],2); ?></td>
                                    <!-- <td valign="bottom" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["union_dues_amt"],2); ?></td> -->
                                    <td valign="bottom" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["pagibig_amt"],2); ?></td>
                                    <td valign="bottom" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["pagibig_loans"],2); ?></td>
                                    <td valign="bottom" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["philhealth_amt"],2); ?></td>
                                    <td valign="bottom" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["gsis_nhmfc"],2); ?></td>
                                    <td valign="bottom" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["total_deductions"],2); ?></td>
                                    <td valign="bottom" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["net_amnt_due"],2); ?></td>
                                    <td valign="bottom" align="right" colspan="2"><?php echo ""; ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                        <table style="border:1px solid black;width:100%;" id="ftTable">
                            <tr>
                                <td valign="top">A</td>
                                <td>CERTIFIED: Service duly rendered as stated.</td>
                                <td style="border-right:1px solid black;">&nbsp;</td>
                                <td valign="top">C</td>
                                <td colspan="2">APPROVED: FOR PAYMENT:&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<span style="text-decoration: underline;">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span></td>
                            </tr>
                            <tr style="height:45px;">
                                <td colspan="3" valign="bottom" style="text-decoration: underline;text-align:center;border-right:1px solid black"><?php echo $signatories[0]["employee_name"]; //name ?></td>
                                <td colspan="3" valign="bottom" style="text-decoration: underline;text-align:center;"><?php echo $signatories[2]["employee_name"]; //name ?></td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align:center;border-right:1px solid black">
                                    <?php echo $signatories[0]["position_designation"] != "" || $signatories[0]["position_designation"] != null ? $signatories[0]["position_designation"] : $signatories[0]["position_title"]; //position ?>
                                    <br>
                                    <?php echo $signatories[0]["division_designation"] != "" || $signatories[0]["division_designation"] != null ? $signatories[0]["division_designation"] : $signatories[0]["department"]; //position ?>
                                </td>
                                <td colspan="3" style="text-align:center;font-size:10px;">
                                    <?php echo $signatories[2]["position_designation"] != "" || $signatories[2]["position_designation"] != null ? $signatories[2]["position_designation"] : $signatories[2]["position_title"]; //position ?>
                                    <br>
                                    <?php echo $signatories[2]["division_designation"] != "" || $signatories[2]["division_designation"] != null ? $signatories[2]["division_designation"] : $signatories[2]["department"]; //position ?>
                                    <br>
                                    Head of Agency/Authorized Representative
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align:center;border-right:1px solid black">Authorized Official</td>
                                <td colspan="3" style="text-align:center;"></td>
                            </tr>
                            <tr style="border-top:1px solid black;">
                                <td width="2%" valign="top">B</td>
                                <td width="37%">CERTIFIED: Supporting documents complete and proper, and cash<br>available in the amount of P ___________</td>
                                <td width="10%" style="border-right:1px solid black">&nbsp;</td>
                                <td width="2%" valign="top">D</td>
                                <td width="37%">CERTIFIED: Each employee whose name appears above has<br>been paid the amount indicated opposite on his/her name.</span></td>
                                <td wdith="15%" rowspan="3" valign="middle" align="left">ALOBS No. ___________</td>
                            </tr>
                            <tr style="height:45px;">
                                <td colspan="3" valign="bottom" style="text-decoration: underline;text-align:center;border-right:1px solid black"><?php echo $signatories[1]["employee_name"]; //name ?></td>
                                <td colspan="2" valign="bottom" style="text-align:center;"><span style="text-decoration: underline;"></td>
                            </tr>
                            <tr style="height:35px;">
                                <td valign="top" colspan="3" style="text-align:center;border-right:1px solid black">
                                <?php echo $signatories[1]["position_designation"] != "" || $signatories[1]["position_designation"] != null ? $signatories[1]["position_designation"] : $signatories[1]["position_title"]; //position ?>
                                                <br>
                                                <?php echo $signatories[1]["division_designation"] != "" || $signatories[1]["division_designation"] != null ? $signatories[1]["division_designation"] : $signatories[1]["department"]; //position ?>
                                </td>
                                <td valign="top" colspan="2">
                                    <table width="100%" >
                                        <tr>
                                            <td width="45%" style="text-align:center;border-bottom:1px solid black"><?php echo $signatories[3]["employee_name"]; //name ?></td>
                                            <td width="10%"></td>
                                            <td width="45%" style="text-align:center;border-bottom:1px solid black"></td>
                                        </tr>
                                        <tr>
                                            <td width="45%" style="text-align:center;">
                                                <?php echo $signatories[3]["position_designation"] != "" || $signatories[3]["position_designation"] != null ? $signatories[3]["position_designation"] : $signatories[3]["position_title"]; //position ?>
                                                <br>
                                                <?php echo $signatories[3]["division_designation"] != "" || $signatories[3]["division_designation"] != null ? $signatories[3]["division_designation"] : $signatories[3]["department"]; //position ?>
                                            </td>
                                            <td width="10%"></td>
                                            <td width="45%" style="text-align:center;">Date</td>
                                        </tr>
                                    </table>
                                </td>
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