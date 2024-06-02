<div class="row">
    <div class="col-md-12 text-right">
        <button id="downloadClearance" class="btn bg-green btn-fab btn-fab-mini" onclick="saveAsExcel();">Save as Excel <i class = "material-icons">cloud_download</i></button>
        <button id="printClearance" class="btn bg-blue btn-fab btn-fab-mini">Print Preview <i class = "material-icons">print</i></button>
    </div>
</div>
<hr>
<div class="row" style="overflow-x:auto;">
    <div class="col-md-12">
        <div style="width:100%;">
            <div id="clearance-div">
                <style type = 'text/css'>
                    @media print{
                        /*280mm 378mm 11in 15in */
                        html { height: 0; }
                        @page {  
                            size: 148mm 105mm portrait;  
                            margin: 10mm 20mm;
                        }
                        body { font-family:Calibri; font-size: 9; color: black; }
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
                    table#tmpTable thead tr th, table#excelTable thead tr th{ border: 1px solid black; }
                    table#tmpTable tr, #tmpTable td, #gtTable td 
                    table#excelTable tr, #excelTable td{
                        font-family: "Times New Roman", Times, serif;
                        font-size: 11px;
                        color: #000;
                        border: 1px solid black;
                    }
                    /* table#tmpTable tr:last-child td {
                        border: none;
                    }
                    table#tmpTable tr:last-child {
                        border: none;
                    } */
                    table#hdTable, table#ftTable, table#gtTable, table#excelTable{
                        font-family: "Times New Roman", Times, serif;
                        font-size: 11px;
                        color: #000;
                    }
                    table#ftTable{
                        font-family: "Times New Roman", Times, serif;
                        font-size: 12px;
                        color: #000;
                    }
                    #footer{
							display: table-footer-group;
							
					}
                </style>

                <!-- START OF FIRST PAGE -->
                <?php
                    $page = 0;
                    $totalNum = 0; 
                    $numExperience = sizeof($payroll) / 45;
                    $wholeNumExperience = floor($numExperience);
                    $decNumExperience = $wholeNumExperience - $numExperience;
                    $totalNum = $wholeNumExperience;
                    if($decNumExperience < 0) $totalNum += 1;
                    $count1 = 0;
                    $count2 = 44;
                    $page_count = 1;
                    $last_row = 0;
                    $count = 1;
                    $rows = 0;
                    $grand_total = array(
                        'salary'=>0.00, 'gsis_housing'=>0.00, 'pag_ibig_housing'=>0.00, 'gross_pay'=>0.00, 'wh_tax_amt'=>0.00, 'sss_gsis_amt'=>0.00, 'sss_gsis_amt_employer'=>0.00, 'sos_loan'=>0.00, 'gsis_educational'=>0.00, 'lbp1'=>0.00, 'mpl'=>0.00, 'gsis_ecard_plus'=>0.00, 'opt_pol_loan'=>0.00, 'opt_ins'=>0.00, 'cpl'=>0.00, 'gfal'=>0.00, 'gsis_emergency'=>0.00,
                        'gsis_policy'=>0.00, 'gsis_optional_loan'=>0.00, 'optional_unltd'=>0.00, 'gsis_consolidated'=>0.00, 'gsis_nhmfc'=>0.00,'union_dues_amt'=>0.00,  'pagibig_amt'=>0.00, 'pagibig_loans'=>0.00,
                        'philhealth_amt'=>0.00, 'total_deductions'=>0.00, 'net_amnt_due'=>0.00, 'other_earnings_amt'=>0.00, 'other_deductions_amt'=>0.00, 'tardiness_amt'=>0.00
                        ,'pera_amt'=>0.00,'gross_pera_amt'=>0.00,'nwrbea_project'=>0.00, 'lbp2'=>0.00, 'nwrbea_cashLoan'=>0.00, 'nwrbea_emergency'=>0.00, 'col1_value'=>0.00, 'col2_value'=>0.00 , 'late_amt'=>0.00 , 'gsis_total'=>0.00 , 'lchl_total'=>0.00 , 'cut_off_total'=>0.00
                    );
                ?>                
                <?php for($x = 0; $x < $totalNum; $x++){  ?>
                    <div class="header-container" style="width:100%;"><br>
                        <table style="width:100%;border-bottom:0px;border-top:5px" id="hdTable">
                            <thead>
                                <tr>
                                    <td><b>Remitting Agency:&emsp; NATIONAL WATER RESOURCES BOARD</b></td>
                                </tr>
                                <tr>
                                    <td><b>Office Code:&emsp; 1000030276</b></td>
                                </tr>
                                <tr>
                                    <td><b>Due Month:&emsp;<?php echo date('F Y',strtotime(@$payroll_period[0]['start_date'])); ?></b></td>
                                </tr>
                            </thead>
                        </table><br>
                        <?php
                            $total_per_page = $total_next_page = 44;
                            $total_page = floor(sizeof($payroll)/$total_per_page) + 1;

                            $page_total = array(
                                'salary'=>0.00, 'gross_pay'=>0.00, 'wh_tax_amt'=>0.00, 'sss_gsis_amt'=>0.00, 'sss_gsis_amt_employer'=>0.00, 'sos_loan'=>0.00, 'gsis_educational'=>0.00, 'mpl'=>0.00, 'lbp1'=>0.00, 'gsis_ecard_plus'=>0.00, 'opt_ins'=>0.00, 'cpl'=>0.00,  'opt_pol_loan'=>0.00, 'gfal'=>0.00, 'gsis_emergency'=>0.00,
                                'gsis_policy'=>0.00, 'gsis_optional_loan'=>0.00, 'optional_unltd'=>0.00, 'gsis_consolidated'=>0.00, 'gsis_nhmfc'=>0.00, 'union_dues_amt'=>0.00, 'pagibig_amt'=>0.00, 'pagibig_loans'=>0.00,
                                'philhealth_amt'=>0.00,'gsis_housing'=>0.00, 'pag_ibig_housing'=>0.00, 'total_deductions'=>0.00, 'net_amnt_due'=>0.00, 'other_earnings_amt'=>0.00, 'other_deductions_amt'=>0.00, 'tardiness_amt'=>0.00,
                                'pera_amt'=>0.00, 'gross_pera_amt'=>0.00 , 'mp2'=>0.00, 'lbp2'=>0.00, 'nwrbea_project'=>0.00, 'nwrbea_cashLoan'=>0.00, 'nwrbea_emergency'=>0.00, 'lchl_total'=>0.00 
                            );

                            if(( sizeof($payroll) < $total_next_page && sizeof($payroll) > floor($total_next_page*.5) ) || (sizeof($payroll) > $total_next_page && (sizeof($payroll)) - ($total_next_page * $total_page) > floor($total_next_page*.5) )){
                                $total_page = floor(sizeof($payroll)/$total_per_page) + 1;
                            }
                        ?>
                        <div class="table-container"><?php $page++; ?>
                            <table style="width:100%;" id="tmpTable">
                                <thead>
                                    <tr>
                                        <th style="text-align:center;">BPNO</th>
                                        <th style="text-align:center;">LASTNAME</th>
                                        <th style="text-align:center;">FIRSTNAME</th>
                                        <th style="text-align:center;">MI</th>
                                        <th style="text-align:center;">PREFIX</th>
                                        <th style="text-align:center;">APPELLATION</th>
                                        <th style="text-align:center;">BIRTHDATE</th>
                                        <th style="text-align:center;">CRN</th>
                                        <th style="text-align:center;">BASIC<br>MONTHLY<br>SALARY</th>
                                        <th style="text-align:center;">EFFECTIVITY<br>DATE</th>
                                        <th style="text-align:center;">PS</th>
                                        <th style="text-align:center;">GS</th>
                                        <th style="text-align:center;">EC</th>
                                        <th style="text-align:center;">CONSOLOAN</th>
                                        <th style="text-align:center;">ECARDPLUS</th>
                                        <th style="text-align:center;">SALARY<br>LOAN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        for($v = $count1 ; $v < sizeof($payroll); $v++ ) { 
                                            $rowtotaldeduction = 0; 
                                            $bpno = $payroll[$v]['gsis'];
                                            $lname = ((isset($payroll[$v]['last_name']) && $payroll[$v]['last_name'] != "")?$this->Helper->decrypt($payroll[$v]['last_name'],$payroll[$v]['employee_id']):"");
                                            $fname = ((isset($payroll[$v]['first_name']) && $payroll[$v]['first_name'] != "")?"&emsp;&nbsp;".$this->Helper->decrypt($payroll[$v]['first_name'],$payroll[$v]['employee_id']):"");
                                            $mname = ((isset($payroll[$v]['middle_name']) && $payroll[$v]['middle_name'] != "")?"&nbsp;".substr($this->Helper->decrypt($payroll[$v]['middle_name'],$payroll[$v]['employee_id']),0,1):"");
                                            $extension = ((isset($payroll[$v]['extension']) && $payroll[$v]['extension'] != "")?"&nbsp;".$this->Helper->decrypt($payroll[$v]['extension'],$payroll[$v]['employee_id']):"");
                                        ?>
                                        <tr>
                                            <td valign="top" style="text-align:center;font-weight: bold;"><?php echo $bpno; ?></td>
                                            <td valign="top" style="text-align:left;font-weight: bold;"><?php echo $lname; ?></td>
                                            <td valign="top" style="text-align:left;font-weight: bold;"><?php echo $fname; ?></td>
                                            <td valign="top" style="text-align:left;font-weight: bold;"><?php echo $mname; ?></td>
                                            <td valign="top" style="text-align:left;font-weight: bold;"><?php echo $extension; ?></td>
                                            
                                            <td valign="middle" align="right"><?php echo ""; ?></td> 
                                            <td valign="middle" align="right"><?php echo date('m/d/Y',strtotime($payroll[$v]['birthday'])); ?></td> 
                                            <td valign="middle" align="right"><?php //echo $payroll[$v]['crn'] ?></td> 
                                            <td valign="middle" align="right"><?php echo number_format($payroll[$v]['basic_pay'],2); $page_total["salary"] += $payroll[$v]['basic_pay'];?></td>
                                            <td valign="middle" align="right"><?php //echo $payroll[$v]['effectivity_date'] ?></td> 
                                            <td valign="middle" align="right"><?php echo number_format($payroll[$v]['sss_gsis_amt'],2); $page_total["sss_gsis_amt"] += $payroll[$v]['sss_gsis_amt'];?></td>
                                            <td valign="middle" align="right"><?php echo number_format($payroll[$v]['sss_gsis_amt_employer'],2); $page_total["sss_gsis_amt_employer"] += $payroll[$v]['sss_gsis_amt_employer'];?></td>
                                            <td valign="middle" align="right"><?php //echo number_format($payroll[$v]['ec'] , 2); ?></td>
                                            <td valign="middle" align="right"><?php $val = 0; $val = get_key(2,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $rowtotaldeduction += get_key(2,$loanDeductions[$payroll[$v]['employee_id']]); $page_total["gsis_consolidated"] += $val;?></td>
                                            <td valign="middle" align="right"><?php //echo number_format($payroll[$v]['ecard'] , 2); ?></td> 
                                            <td valign="middle" align="right"><?php //echo number_format($payroll[$v]['salary_loan'] , 2); ?></td>
                                        </tr>  
                                        <?php
                                        if($v == $count2){ $count1 += 1; break; }
                                        $rows++; 
                                        if($rows === 44) { $last_row = 1; } else { $last_row = 0; } 
                                        } 
                                    ?>
                                    <!-- START OF SUB-TOTAL PER PAGE -->
                                    <tr>
                                        <td valign="middle" align="center" style="font-weight: bold;" colspan="4"><b>SUB-TOTAL for page <?php echo $page; ?></b></td>
                                        <td valign="middle" align="center" style="font-weight: bold;" colspan="4"></td>
                                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["salary"],2); $grand_total["salary"] += $page_total['salary']; ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold;"></td>
                                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["sss_gsis_amt"],2); $grand_total["sss_gsis_amt"] += $page_total['sss_gsis_amt']; ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["sss_gsis_amt_employer"],2); $grand_total["sss_gsis_amt_employer"] += $page_total['sss_gsis_amt_employer']; ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold;"><?php //echo number_format($page_total["ec"],2); ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["gsis_consolidated"],2); $grand_total["gsis_consolidated"] += $page_total["gsis_consolidated"];  ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold;"><?php //echo number_format($page_total["ecard"],2); ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold;"><?php //echo number_format($page_total["salary_loan"],2); ?></td>
                                    </tr><br>
                                    <!-- END OF SUB-TOTAL PER PAGE -->

                                    <!-- START OF GRAND TOTAL -->
                                    <?php if($page == $total_page) { ?>
                                        <tr style="border: none;">
                                            <td valign="middle" align="center" style="font-weight: bold; border: none;" colspan="2"></td>
                                            <td valign="middle" align="left" style="font-weight: bold; border: none;" colspan="3">TOTAL</td>
                                            <td valign="middle" align="center" style="font-weight: bold; border: none;" colspan="11"></td>
                                        </tr>
                                        <tr style="border: none;">
                                            <td valign="middle" align="center" style="font-weight: bold; border: none;" colspan="2"></td>
                                            <td valign="middle" align="left" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;" colspan="3"><b>GRAND TOTAL</b></td>
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["salary"] + $grand_total["sss_gsis_amt"] + $grand_total["sss_gsis_amt_employer"] + $grand_total["gsis_consolidated"],2); ?></td>
                                            <td valign="middle" align="right" style="font-weight: bold; border: none;"></td>
                                            <td valign="middle" align="right" style="font-weight: bold; border: none;"></td>
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["salary"],2); ?></td>
                                            <td valign="middle" align="right" style="font-weight: bold; border: none;"></td>
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["sss_gsis_amt"],2); ?></td>
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["sss_gsis_amt_employer"],2); ?></td>
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php //echo number_format($grand_total["ec"],2); ?></td>
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["gsis_consolidated"],2); ?></td>
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php //echo number_format($grand_total["ecard"],2); ?></td>
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php //echo number_format($grand_total["salary_loan"],2); ?></td>
                                        </tr>
                                    <?php } ?>
                                    <!-- END OF GRAND TOTAL -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php
                    $count1 += 44;
                    $count2 += 45;
                    }
                ?>
                <!-- END OF FIRST PAGE -->
                <div class="page-break"></div>
                <!-- START OF SECOND PAGE -->
                <?php
                    $page = 0;
                    $totalNum = 0; 
                    $numExperience = sizeof($payroll) / 45;
                    $wholeNumExperience = floor($numExperience);
                    $decNumExperience = $wholeNumExperience - $numExperience;
                    $totalNum = $wholeNumExperience;
                    if($decNumExperience < 0) $totalNum += 1;
                    $count1 = 0;
                    $count2 = 44;
                    $page_count = 1;
                    $last_row = 0;
                    $count = 1;
                    $rows = 0;
                    $grand_total = array(
                        'salary'=>0.00, 'gsis_housing'=>0.00, 'pag_ibig_housing'=>0.00, 'gross_pay'=>0.00, 'wh_tax_amt'=>0.00, 'sss_gsis_amt'=>0.00, 'sss_gsis_amt_employer'=>0.00, 'sos_loan'=>0.00, 'gsis_educational'=>0.00, 'lbp1'=>0.00, 'mpl'=>0.00, 'gsis_ecard_plus'=>0.00, 'opt_pol_loan'=>0.00, 'opt_ins'=>0.00, 'cpl'=>0.00, 'gfal'=>0.00, 'gsis_emergency'=>0.00,
                        'gsis_policy'=>0.00, 'gsis_optional_loan'=>0.00, 'optional_unltd'=>0.00, 'gsis_consolidated'=>0.00, 'gsis_nhmfc'=>0.00,'union_dues_amt'=>0.00,  'pagibig_amt'=>0.00, 'pagibig_loans'=>0.00,
                        'philhealth_amt'=>0.00, 'total_deductions'=>0.00, 'net_amnt_due'=>0.00, 'other_earnings_amt'=>0.00, 'other_deductions_amt'=>0.00, 'tardiness_amt'=>0.00
                        ,'pera_amt'=>0.00,'gross_pera_amt'=>0.00,'nwrbea_project'=>0.00, 'lbp2'=>0.00, 'nwrbea_cashLoan'=>0.00, 'nwrbea_emergency'=>0.00, 'col1_value'=>0.00, 'col2_value'=>0.00 , 'late_amt'=>0.00 , 'gsis_total'=>0.00 , 'lchl_total'=>0.00 , 'cut_off_total'=>0.00
                    );
                ?>
                <?php for($x = 0; $x < $totalNum; $x++){  ?>
                <div class="header-container" style="width:100%;"><br>
                    <table style="width:100%;border-bottom:0px;border-top:5px" id="hdTable">
                        <thead>
                            <tr>
                                <td><b>Remitting Agency:&emsp; NATIONAL WATER RESOURCES BOARD</b></td>
                            </tr>
                            <tr>
                                <td><b>Office Code:&emsp; 1000030276</b></td>
                            </tr>
                            <tr>
                                <td><b>Due Month:&emsp;<?php echo date('F Y',strtotime(@$payroll_period[0]['start_date'])); ?></b></td>
                            </tr>
                        </thead>
                    </table><br>
                    <?php
                        $total_per_page = $total_next_page = 44;
                        $total_page = floor(sizeof($payroll)/$total_per_page) + 1;

                        $page_total = array(
                            'salary'=>0.00, 'gross_pay'=>0.00, 'wh_tax_amt'=>0.00, 'sss_gsis_amt'=>0.00, 'sss_gsis_amt_employer'=>0.00, 'sos_loan'=>0.00, 'gsis_educational'=>0.00, 'mpl'=>0.00, 'lbp1'=>0.00, 'gsis_ecard_plus'=>0.00, 'opt_ins'=>0.00, 'cpl'=>0.00,  'opt_pol_loan'=>0.00, 'gfal'=>0.00, 'gsis_emergency'=>0.00,
                            'gsis_policy'=>0.00, 'gsis_optional_loan'=>0.00, 'optional_unltd'=>0.00, 'gsis_consolidated'=>0.00, 'gsis_nhmfc'=>0.00, 'union_dues_amt'=>0.00, 'pagibig_amt'=>0.00, 'pagibig_loans'=>0.00,
                            'philhealth_amt'=>0.00,'gsis_housing'=>0.00, 'pag_ibig_housing'=>0.00, 'total_deductions'=>0.00, 'net_amnt_due'=>0.00, 'other_earnings_amt'=>0.00, 'other_deductions_amt'=>0.00, 'tardiness_amt'=>0.00,
                            'pera_amt'=>0.00, 'gross_pera_amt'=>0.00 , 'mp2'=>0.00, 'lbp2'=>0.00, 'nwrbea_project'=>0.00, 'nwrbea_cashLoan'=>0.00, 'nwrbea_emergency'=>0.00, 'lchl_total'=>0.00 
                        );

                        if(( sizeof($payroll) < $total_next_page && sizeof($payroll) > floor($total_next_page*.5) ) || (sizeof($payroll) > $total_next_page && (sizeof($payroll)) - ($total_next_page * $total_page) > floor($total_next_page*.5) )){
                            $total_page = floor(sizeof($payroll)/$total_per_page) + 1;
                        }
                    ?>
                    <div class="table-container"><?php $page++; ?>
                        <table style="width:100%;" id="tmpTable">
                            <thead>
                                <tr>
                                    <th style="text-align:center;">BPNO</th>
                                    <th style="text-align:center;">LASTNAME</th>
                                    <th style="text-align:center;">FIRSTNAME</th>
                                    <th style="text-align:center;">MI</th>
                                    <th style="text-align:center;">CASH<br>ADV</th>
                                    <th style="text-align:center;">EMRGYLN</th>
                                    <th style="text-align:center;">EDUC<br>ASST</th>
                                    <th style="text-align:center;">ELA</th>
                                    <th style="text-align:center;">SOS</th>
                                    <th style="text-align:center;">PLREG</th>
                                    <th style="text-align:center;">PLOPT</th>
                                    <th style="text-align:center;">REL</th>
                                    <th style="text-align:center;">LCH<br>DCS</th>
                                    <th style="text-align:center;">STOCK<br>PURCHASE</th>
                                    <th style="text-align:center;">OPT<br>LIFE</th>
                                    <th style="text-align:center;">MPL</th>
                                    <th style="text-align:center;">CPL</th>
                                    <th style="text-align:center;">CEAP</th>
                                    <th style="text-align:center;">GFAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    for($v = $count1 ; $v < sizeof($payroll); $v++ ) { 
                                        $rowtotaldeduction = 0; 
                                        $bpno = $payroll[$v]['gsis'];
                                        $lname = ((isset($payroll[$v]['last_name']) && $payroll[$v]['last_name'] != "")?$this->Helper->decrypt($payroll[$v]['last_name'],$payroll[$v]['employee_id']):"");
                                        $fname = ((isset($payroll[$v]['first_name']) && $payroll[$v]['first_name'] != "")?"&emsp;&nbsp;".$this->Helper->decrypt($payroll[$v]['first_name'],$payroll[$v]['employee_id']):"");
                                        $mname = ((isset($payroll[$v]['middle_name']) && $payroll[$v]['middle_name'] != "")?"&nbsp;".substr($this->Helper->decrypt($payroll[$v]['middle_name'],$payroll[$v]['employee_id']),0,1):"");
                                        $extension = ((isset($payroll[$v]['extension']) && $payroll[$v]['extension'] != "")?"&nbsp;".$this->Helper->decrypt($payroll[$v]['extension'],$payroll[$v]['employee_id']):"");
                                        ?>
                                    <tr>
                                        <td valign="top" style="text-align:center;font-weight: bold;"><?php echo $bpno; ?></td>
                                        <td valign="top" style="text-align:left;font-weight: bold;"><?php echo $lname; ?></td>
                                        <td valign="top" style="text-align:left;font-weight: bold;"><?php echo $fname; ?></td>
                                        <td valign="top" style="text-align:left;font-weight: bold;"><?php echo $mname; ?></td>

                                        <td valign="middle" align="right"><?php //echo number_format($payroll[$v]['cash_adv'],2); $page_total["cash_adv"] += $payroll[$v]['cash_adv'];?></td>
                                        <td valign="middle" align="right"><?php $val = 0; $val = get_key(4,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $rowtotaldeduction += get_key(4,$loanDeductions[$payroll[$v]['employee_id']]); $grand_total["gsis_emergency"] += $val; $page_total["gsis_emergency"] += $val;?></td>
                                        <td valign="middle" align="right"><?php $val = 0; $val = get_key(8,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $rowtotaldeduction += get_key(4,$loanDeductions[$payroll[$v]['employee_id']]); $grand_total["gsis_educational"] += $val; $page_total["gsis_educational"] += $val;?></td>
                                        <td valign="middle" align="right"><?php //echo number_format($payroll[$v]['ela'],2); $page_total["ela"] += $payroll[$v]['ela'];?></td>
                                        <td valign="middle" align="right"><?php $val = 0; $val = get_key(22,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $rowtotaldeduction += get_key(22,$loanDeductions[$payroll[$v]['employee_id']]); $grand_total["sos_loan"] += $val; $page_total["sos_loan"] += $val;?></td>
                                        <td valign="middle" align="right"><?php $val = 0; $val = get_key(24,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $rowtotaldeduction += get_key(24,$loanDeductions[$payroll[$v]['employee_id']]); $grand_total["gsis_policy"] += $val; $page_total["gsis_policy"] += $val;?></td>
                                        <td valign="middle" align="right"><?php $val = 0; $val = get_key(39,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $rowtotaldeduction += get_key(39,$loanDeductions[$payroll[$v]['employee_id']]); $grand_total["opt_pol_loan"] += $val; $page_total["opt_pol_loan"] += $val;?></td>
                                        <td valign="middle" align="right"><?php //echo number_format($payroll[$v]['rel'],2); $page_total["rel"] += $payroll[$v]['rel'];?></td>
                                        <td valign="middle" align="right"><?php $val = 0; $val = get_key(7,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $rowtotaldeduction += get_key(7,$loanDeductions[$payroll[$v]['employee_id']]); $grand_total["lchl_total"] += $val; $page_total["lchl_total"] += $val;?></td>
                                        <td valign="middle" align="right"><?php //echo number_format($payroll[$v]['stock'],2); $page_total["stock"] += $payroll[$v]['stock'];?></td>
                                        <td valign="middle" align="right"><?php $val = 0; $val = get_key(18,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $rowtotaldeduction += get_key(18,$loanDeductions[$payroll[$v]['employee_id']]); $grand_total["opt_ins"] += $val; $page_total["opt_ins"] += $val;?></td>
                                        <td valign="middle" align="right"><?php $val = 0; $val = get_key(5,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $rowtotaldeduction += get_key(5,$loanDeductions[$payroll[$v]['employee_id']]); $grand_total["mpl"] += $val; $page_total["mpl"] += $val;?></td>
                                        <td valign="middle" align="right"><?php $val = 0; $val = get_key(40,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $rowtotaldeduction += get_key(40,$loanDeductions[$payroll[$v]['employee_id']]); $grand_total["cpl"] += $val; $page_total["cpl"] += $val;?></td>
                                        <td valign="middle" align="right"><?php //echo number_format($payroll[$v]['ceap'],2); $page_total["ceap"] += $payroll[$v]['ceap'];?></td>
                                        <td valign="middle" align="right"><?php $val = 0; $val = get_key(41,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $rowtotaldeduction += get_key(41,$loanDeductions[$payroll[$v]['employee_id']]); $grand_total["gfal"] += $val; $page_total["gfal"] += $val;?></td>
                                    </tr>  
                                    <?php
                                    if($v == $count2){ $count1 += 1; break; }
                                    $rows++; 
                                    if($rows === 44) { $last_row = 1; } else { $last_row = 0; } 
                                    } 
                                ?>
                                <!-- START OF SUB-TOTAL PER PAGE -->
                                <tr>
                                    <td valign="middle" align="center" style="font-weight: bold;" colspan="4"><b>SUB-TOTAL for page <?php echo $page; ?></b></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php //echo number_format($page_total["cash_adv"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["gsis_emergency"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["gsis_educational"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php //echo number_format($page_total["ela"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["sos_loan"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["gsis_policy"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["opt_pol_loan"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php //echo number_format($page_total["rel"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["lchl_total"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php //echo number_format($page_total["stock"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["opt_ins"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["mpl"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["cpl"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php //echo number_format($page_total["ceap"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["gfal"],2); ?></td>
                                </tr><br>
                                <!-- END OF SUB-TOTAL PER PAGE -->

                                <!-- START OF GRAND TOTAL -->
                                <?php if($page == $total_page) { ?>
                                    <!-- <tr style="border: none;">
                                        <td valign="middle" align="center" style="font-weight: bold; border: none;"></td>
                                        <td valign="middle" align="left" style="font-weight: bold; border: none;" colspan="3">TOTAL</td>
                                        <td valign="middle" align="center" style="font-weight: bold; border: none;" colspan="15"></td>
                                    </tr> -->
                                    <tr style="border: none;">
                                        <td valign="middle" align="center" style="font-weight: bold; border: none;" colspan="2"></td>
                                        <td valign="middle" align="left" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;" colspan="2"><b>GRAND TOTAL</b></td>
                                        <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php //echo number_format($grand_total["cash_adv"],2); ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["gsis_emergency"],2); ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["gsis_educational"],2); ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php //echo number_format($grand_total["ela"],2); ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["sos_loan"],2); ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["gsis_policy"],2); ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["opt_pol_loan"],2); ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php //echo number_format($grand_total["rel"],2); ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["lchl_total"],2); ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php //echo number_format($grand_total["stock"],2); ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["opt_ins"],2); ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["mpl"],2); ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["cpl"],2); ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php //echo number_format($grand_total["ceap"],2); ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["gfal"],2); ?></td>
                                    </tr>
                                <?php } ?>
                                <!-- END OF GRAND TOTAL -->
                            </tbody>
                        </table>
                        <?php
                            $count1 += 44;
                            $count2 += 45;
                        }
                        ?>
                    </div>
                </div>
                <!-- END OF SECOND PAGE -->

                <!-- START OF EXCEL TABLE -->
                <?php
                    $count1 = 0;
                    $grand_total = array(
                        'salary'=>0.00, 'gsis_housing'=>0.00, 'pag_ibig_housing'=>0.00, 'gross_pay'=>0.00, 'wh_tax_amt'=>0.00, 'sss_gsis_amt'=>0.00, 'sss_gsis_amt_employer'=>0.00, 'sos_loan'=>0.00, 'gsis_educational'=>0.00, 'lbp1'=>0.00, 'mpl'=>0.00, 'gsis_ecard_plus'=>0.00, 'opt_pol_loan'=>0.00, 'opt_ins'=>0.00, 'cpl'=>0.00, 'gfal'=>0.00, 'gsis_emergency'=>0.00,
                        'gsis_policy'=>0.00, 'gsis_optional_loan'=>0.00, 'optional_unltd'=>0.00, 'gsis_consolidated'=>0.00, 'gsis_nhmfc'=>0.00,'union_dues_amt'=>0.00,  'pagibig_amt'=>0.00, 'pagibig_loans'=>0.00,
                        'philhealth_amt'=>0.00, 'total_deductions'=>0.00, 'net_amnt_due'=>0.00, 'other_earnings_amt'=>0.00, 'other_deductions_amt'=>0.00, 'tardiness_amt'=>0.00
                        ,'pera_amt'=>0.00,'gross_pera_amt'=>0.00,'nwrbea_project'=>0.00, 'lbp2'=>0.00, 'nwrbea_cashLoan'=>0.00, 'nwrbea_emergency'=>0.00, 'col1_value'=>0.00, 'col2_value'=>0.00 , 'late_amt'=>0.00 , 'gsis_total'=>0.00 , 'lchl_total'=>0.00 , 'cut_off_total'=>0.00
                    );
                    $page_total = array(
                        'salary'=>0.00, 'gross_pay'=>0.00, 'wh_tax_amt'=>0.00, 'sss_gsis_amt'=>0.00, 'sss_gsis_amt_employer'=>0.00, 'sos_loan'=>0.00, 'gsis_educational'=>0.00, 'mpl'=>0.00, 'lbp1'=>0.00, 'gsis_ecard_plus'=>0.00, 'opt_ins'=>0.00, 'cpl'=>0.00,  'opt_pol_loan'=>0.00, 'gfal'=>0.00, 'gsis_emergency'=>0.00,
                        'gsis_policy'=>0.00, 'gsis_optional_loan'=>0.00, 'optional_unltd'=>0.00, 'gsis_consolidated'=>0.00, 'gsis_nhmfc'=>0.00, 'union_dues_amt'=>0.00, 'pagibig_amt'=>0.00, 'pagibig_loans'=>0.00,
                        'philhealth_amt'=>0.00,'gsis_housing'=>0.00, 'pag_ibig_housing'=>0.00, 'total_deductions'=>0.00, 'net_amnt_due'=>0.00, 'other_earnings_amt'=>0.00, 'other_deductions_amt'=>0.00, 'tardiness_amt'=>0.00,
                        'pera_amt'=>0.00, 'gross_pera_amt'=>0.00 , 'mp2'=>0.00, 'lbp2'=>0.00, 'nwrbea_project'=>0.00, 'nwrbea_cashLoan'=>0.00, 'nwrbea_emergency'=>0.00, 'lchl_total'=>0.00 
                    );
                ?>
                <div class="header-container" style="width:100%; display:none;" id="excelTablediv"><br>
                    <table class="excelTable no-print" style="width:100%;border-bottom:0px;border-top:5px" id="hdTable">
                        <thead>
                            <tr>
                                <td style="text-align:left;font-weight: bold;">Remitting Agency:</td>
                                <td style="text-align:left;font-weight: bold;" colspan="5">NATIONAL WATER RESOURCES BOARD</td>
                            </tr>
                            <tr>
                                <td style="text-align:left;font-weight: bold;">Office Code:</td>
                                <td style="text-align:left;font-weight: bold;" colspan="5">1000030276</td>
                            </tr>
                            <tr>
                                <td style="text-align:left;font-weight: bold;">Due Month:</td>
                                <td style="text-align:left;font-weight: bold;" colspan="5"><?php echo date('F Y',strtotime(@$payroll_period[0]['start_date'])); ?></td>
                            </tr><tr><td>&nbsp;</td></tr>
                        </thead>
                    </table>
                    <div class="table-container">
                        <table class="table table-hover table-striped excelTable no-print" style="width:100%;" id="excelTable">
                            <thead>
                                <tr>
                                    <th style="text-align:center;">BPNO</th>
                                    <th style="text-align:center;">LASTNAME</th>
                                    <th style="text-align:center;">FIRSTNAME</th>
                                    <th style="text-align:center;">MI</th>
                                    <th style="text-align:center;">PREFIX</th>
                                    <th style="text-align:center;">APPELLATION</th>
                                    <th style="text-align:center;">BIRTHDATE</th>
                                    <th style="text-align:center;">CRN</th>
                                    <th style="text-align:center;">BASIC MONTHLY SALARY</th>
                                    <th style="text-align:center;">EFFECTIVITY DATE</th>
                                    <th style="text-align:center;">PS</th>
                                    <th style="text-align:center;">GS</th>
                                    <th style="text-align:center;">EC</th>
                                    <th style="text-align:center;">CONSOLOAN</th>
                                    <th style="text-align:center;">ECARDPLUS</th>
                                    <th style="text-align:center;">SALARY_LOAN</th>
                                    <th style="text-align:center;">CASH_ADV</th>
                                    <th style="text-align:center;">EMRGYLN</th>
                                    <th style="text-align:center;">EDUC_ASST</th>
                                    <th style="text-align:center;">ELA</th>
                                    <th style="text-align:center;">SOS</th>
                                    <th style="text-align:center;">PLREG</th>
                                    <th style="text-align:center;">PLOPT</th>
                                    <th style="text-align:center;">REL</th>
                                    <th style="text-align:center;">LCH_DCS</th>
                                    <th style="text-align:center;">STOCK_PURCHASE</th>
                                    <th style="text-align:center;">OPT_LIFE</th>
                                    <th style="text-align:center;">MPL</th>
                                    <th style="text-align:center;">CPL</th>
                                    <th style="text-align:center;">CEAP</th>
                                    <th style="text-align:center;">GFAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    for($v = $count1 ; $v < sizeof($payroll); $v++ ) { 
                                        $rowtotaldeduction = 0; 
                                        $bpno = $payroll[$v]['gsis'];
                                        $lname = ((isset($payroll[$v]['last_name']) && $payroll[$v]['last_name'] != "")?$this->Helper->decrypt($payroll[$v]['last_name'],$payroll[$v]['employee_id']):"");
                                        $fname = ((isset($payroll[$v]['first_name']) && $payroll[$v]['first_name'] != "")?"&emsp;&nbsp;".$this->Helper->decrypt($payroll[$v]['first_name'],$payroll[$v]['employee_id']):"");
                                        $mname = ((isset($payroll[$v]['middle_name']) && $payroll[$v]['middle_name'] != "")?"&nbsp;".substr($this->Helper->decrypt($payroll[$v]['middle_name'],$payroll[$v]['employee_id']),0,1):"");
                                        $extension = ((isset($payroll[$v]['extension']) && $payroll[$v]['extension'] != "")?"&nbsp;".$this->Helper->decrypt($payroll[$v]['extension'],$payroll[$v]['employee_id']):"");
                                ?>
                                <tr>
                                    <td valign="top" style="text-align:left;font-weight: bold;"><?php echo $bpno; ?></td>
                                    <td valign="top" style="text-align:left;font-weight: bold;"><?php echo $lname; ?></td>
                                    <td valign="top" style="text-align:left;font-weight: bold;"><?php echo $fname; ?></td>
                                    <td valign="top" style="text-align:left;font-weight: bold;"><?php echo $mname; ?></td>
                                    <td valign="top" style="text-align:left;font-weight: bold;"><?php echo $extension; ?></td>
                                    
                                    <td valign="middle" align="right"><?php echo ""; ?></td> 
                                    <td valign="middle" align="right"><?php echo date('m/d/Y',strtotime($payroll[$v]['birthday'])); ?></td> 
                                    <td valign="middle" align="right"><?php //echo $payroll[$v]['crn'] ?></td> 
                                    <td valign="middle" align="right"><?php echo number_format($payroll[$v]['basic_pay'],2); $page_total["salary"] += $payroll[$v]['basic_pay'];?></td>
                                    <td valign="middle" align="right"><?php //echo $payroll[$v]['effectivity_date'] ?></td> 
                                    <td valign="middle" align="right"><?php echo number_format($payroll[$v]['sss_gsis_amt'],2); $page_total["sss_gsis_amt"] += $payroll[$v]['sss_gsis_amt'];?></td>
                                    <td valign="middle" align="right"><?php echo number_format($payroll[$v]['sss_gsis_amt_employer'],2); $page_total["sss_gsis_amt_employer"] += $payroll[$v]['sss_gsis_amt_employer'];?></td>
                                    <td valign="middle" align="right"><?php //echo number_format($payroll[$v]['ec'] , 2); ?></td>
                                    <td valign="middle" align="right"><?php $val = 0; $val = get_key(2,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $rowtotaldeduction += get_key(2,$loanDeductions[$payroll[$v]['employee_id']]); $page_total["gsis_consolidated"] += $val;?></td>
                                    <td valign="middle" align="right"><?php //echo number_format($payroll[$v]['ecard'] , 2); ?></td> 
                                    <td valign="middle" align="right"><?php //echo number_format($payroll[$v]['salary_loan'] , 2); ?></td>
                                    <td valign="middle" align="right"><?php //echo number_format($payroll[$v]['cash_adv'],2); $page_total["cash_adv"] += $payroll[$v]['cash_adv'];?></td>
                                    <td valign="middle" align="right"><?php $val = 0; $val = get_key(4,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $rowtotaldeduction += get_key(4,$loanDeductions[$payroll[$v]['employee_id']]); $grand_total["gsis_emergency"] += $val; $page_total["gsis_emergency"] += $val;?></td>
                                    <td valign="middle" align="right"><?php $val = 0; $val = get_key(8,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $rowtotaldeduction += get_key(4,$loanDeductions[$payroll[$v]['employee_id']]); $grand_total["gsis_educational"] += $val; $page_total["gsis_educational"] += $val;?></td>
                                    <td valign="middle" align="right"><?php //echo number_format($payroll[$v]['ela'],2); $page_total["ela"] += $payroll[$v]['ela'];?></td>
                                    <td valign="middle" align="right"><?php $val = 0; $val = get_key(22,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $rowtotaldeduction += get_key(22,$loanDeductions[$payroll[$v]['employee_id']]); $grand_total["sos_loan"] += $val; $page_total["sos_loan"] += $val;?></td>
                                    <td valign="middle" align="right"><?php $val = 0; $val = get_key(24,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $rowtotaldeduction += get_key(24,$loanDeductions[$payroll[$v]['employee_id']]); $grand_total["gsis_policy"] += $val; $page_total["gsis_policy"] += $val;?></td>
                                    <td valign="middle" align="right"><?php $val = 0; $val = get_key(39,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $rowtotaldeduction += get_key(39,$loanDeductions[$payroll[$v]['employee_id']]); $grand_total["opt_pol_loan"] += $val; $page_total["opt_pol_loan"] += $val;?></td>
                                    <td valign="middle" align="right"><?php //echo number_format($payroll[$v]['rel'],2); $page_total["rel"] += $payroll[$v]['rel'];?></td>
                                    <td valign="middle" align="right"><?php $val = 0; $val = get_key(7,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $rowtotaldeduction += get_key(7,$loanDeductions[$payroll[$v]['employee_id']]); $grand_total["lchl_total"] += $val; $page_total["lchl_total"] += $val;?></td>
                                    <td valign="middle" align="right"><?php //echo number_format($payroll[$v]['stock'],2); $page_total["stock"] += $payroll[$v]['stock'];?></td>
                                    <td valign="middle" align="right"><?php $val = 0; $val = get_key(18,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $rowtotaldeduction += get_key(18,$loanDeductions[$payroll[$v]['employee_id']]); $grand_total["opt_ins"] += $val; $page_total["opt_ins"] += $val;?></td>
                                    <td valign="middle" align="right"><?php $val = 0; $val = get_key(5,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $rowtotaldeduction += get_key(5,$loanDeductions[$payroll[$v]['employee_id']]); $grand_total["mpl"] += $val; $page_total["mpl"] += $val;?></td>
                                    <td valign="middle" align="right"><?php $val = 0; $val = get_key(40,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $rowtotaldeduction += get_key(40,$loanDeductions[$payroll[$v]['employee_id']]); $grand_total["cpl"] += $val; $page_total["cpl"] += $val;?></td>
                                    <td valign="middle" align="right"><?php //echo number_format($payroll[$v]['ceap'],2); $page_total["ceap"] += $payroll[$v]['ceap'];?></td>
                                    <td valign="middle" align="right"><?php $val = 0; $val = get_key(41,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $rowtotaldeduction += get_key(41,$loanDeductions[$payroll[$v]['employee_id']]); $grand_total["gfal"] += $val; $page_total["gfal"] += $val;?></td>
                                </tr>  
                                <?php } ?>
                                <!-- START OF SUB-TOTAL PER PAGE -->
                                <tr>
                                    <td valign="middle" align="center" style="font-weight: bold;" colspan="2"></td>
                                    <td valign="middle" align="center" style="font-weight: bold;" colspan="6"><b>SUB-TOTAL for page <?php echo $page; ?></b></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["salary"],2); $grand_total["salary"] += $page_total['salary']; ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["sss_gsis_amt"],2); $grand_total["sss_gsis_amt"] += $page_total['sss_gsis_amt']; ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["sss_gsis_amt_employer"],2); $grand_total["sss_gsis_amt_employer"] += $page_total['sss_gsis_amt_employer']; ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php //echo number_format($page_total["ec"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["gsis_consolidated"],2); $grand_total["gsis_consolidated"] += $page_total["gsis_consolidated"];  ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php //echo number_format($page_total["ecard"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php //echo number_format($page_total["salary_loan"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php //echo number_format($page_total["cash_adv"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["gsis_emergency"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["gsis_educational"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php //echo number_format($page_total["ela"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["sos_loan"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["gsis_policy"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["opt_pol_loan"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php //echo number_format($page_total["rel"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["lchl_total"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php //echo number_format($page_total["stock"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["opt_ins"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["mpl"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["cpl"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php //echo number_format($page_total["ceap"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["gfal"],2); ?></td>
                                </tr><br>
                                <!-- END OF SUB-TOTAL PER PAGE -->

                                <!-- START OF GRAND TOTAL -->
                                <tr style="border: none;">
                                    <td valign="middle" align="center" style="font-weight: bold; border: none;" colspan="2"></td>
                                    <td valign="middle" align="left" style="font-weight: bold; border: none;">TOTAL</td>
                                    <td valign="middle" align="center" style="font-weight: bold; border: none;" colspan="29"></td>
                                </tr>
                                <tr style="border: none;">
                                    <td valign="middle" align="center" style="font-weight: bold; border: none;" colspan="2"></td>
                                    <td valign="middle" align="left" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;" colspan="3"><b>GRAND TOTAL</b></td>
                                    <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["salary"] + $grand_total["sss_gsis_amt"] + $grand_total["sss_gsis_amt_employer"] + $grand_total["gsis_consolidated"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold; border: none;"></td>
                                    <td valign="middle" align="right" style="font-weight: bold; border: none;"></td>
                                    <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["salary"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold; border: none;"></td>
                                    <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["sss_gsis_amt"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["sss_gsis_amt_employer"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php //echo number_format($grand_total["ec"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["gsis_consolidated"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php //echo number_format($grand_total["ecard"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php //echo number_format($grand_total["salary_loan"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php //echo number_format($grand_total["cash_adv"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["gsis_emergency"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["gsis_educational"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php //echo number_format($grand_total["ela"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["sos_loan"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["gsis_policy"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["opt_pol_loan"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php //echo number_format($grand_total["rel"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["lchl_total"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php //echo number_format($grand_total["stock"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["opt_ins"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["mpl"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["cpl"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php //echo number_format($grand_total["ceap"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["gfal"],2); ?></td>
                                </tr><tr><td>&nbsp;</td></tr>
                                <!-- END OF GRAND TOTAL -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- END OF EXCEL TABLE -->

                <!-- START OF SIGNATORIES -->
                <table class="excelTable" style="width:100%;" id="ftTable"><br>
                    <tr>
                        <td colspan="2"><b>PREPARED BY:</td>
                        <td colspan="2"><b>CERTIFIED CORRECT:</td>
                    </tr>
                    <tr><td>&nbsp;<td></td>&nbsp;</td></tr>
                    <tr><td>&nbsp;<td></td>&nbsp;</td></tr>
                    <tr>
                        <td colspan="2"><b>RIZZA D. FRANCISCO</td>
                        <td colspan="2"><b>JESUSA T. DELOS SANTOS</td>
                    </tr>
                    <tr>
                        <td colspan="2">Administrative Assistant II</td>
                        <td colspan="2">Administrative Officer V</td>
                    </tr>
                </table>
                <!-- END OF SIGNATORIES -->
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
<script>
    function saveAsExcel() {
        document.getElementById("excelTablediv").style.display = "block";
        $('.excelTable').tableExport({
            type: "excel",
            fileName: "GSIS Remittance Report"
        });
        setTimeout(() => {
        const div = document.getElementById('excelTablediv');
            div.style.display = 'none';
        }, 1000); // time in milliseconds
    }
</script>