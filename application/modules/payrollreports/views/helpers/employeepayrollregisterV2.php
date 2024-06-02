<?php

?>
<div class="row">
    <div class="col-md-12 text-right">
        <button id="printClearance" class="btn bg-green btn-fab btn-fab-mini">Print Preview <i class = "material-icons">print</i></button>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-12">
        <style type="text/css">
        </style>
        <div style="width:100%; overflow-x:auto;">
            <div id="clearance-div">
                <style type = 'text/css'>
                    @media print{
                        /*280mm 378mm
                          11in 15in
                        */
                        html {
                            height: 0;
                        }
                        @page { 
                            size: US Std Fanfold; 

                        }
                        body {
                           font-family:Calibri;
                           font-size: 10;
                           color: black;
                        }
                        table{
                            border-collapse: collapse;
                        }
                        .page-break{
                            display: table;
                            vertical-align:top;
                            width: 100% !important;
                            page-break-after: always !important;
                            table-layout: inherit;
                            margin-top:2px;
                        }
                    }
                </style> 
                <style type="text/css" media="all">
                    table#tmpTable thead tr th, table#tmpTable tbody tr td{
                        padding: 2px;
                    }
                    table#tmpTable thead tr th{
                        border: 1px solid black;
                    }
                    table#main-table{
                        display:none;
                    }
                </style>
                <?php 
                    
                    $total_per_page = 10;
                    $total_next_page = 10;
                    $total_page = floor(sizeof($payroll)/$total_per_page) + 1;
                    if(( sizeof($payroll) < $total_next_page && sizeof($payroll) > floor($total_next_page*.5) ) || (sizeof($payroll) > $total_next_page && (sizeof($payroll)) - ($total_next_page * $total_page) > floor($total_next_page*.5) )){
                        $total_page = floor(sizeof($payroll)/$total_per_page) + 2;
                    }
                ?>
                <div class="header-container" style="width:100%;">
                    <table style="width:100%;border-bottom:0px;" id="tmpTable">
                        <thead>
                            <tr>
                                <td style="width:33%;text-align:left" nowrap>Date/Time Printed/User <?php echo date('m/d/Y  h:i:sa'); ?>  <?php echo Helper::get('first_name') ?></td>
                                <td style="width:33%;text-align:center" nowrap><label>GENERAL PAYROLL</label></td>
                                <td style="width:33%;text-align:right" nowrap><label>AS-PBD-005 &emsp;<?php echo strtoupper($payroll_type); ?> PAYROLL <?php echo strtoupper(@$pay_basis); ?>&emsp; Page No.: 1 of <?php echo $total_page; ?></label></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="100" nowrap>
                                    &emsp;WE HEREBY ACKNOWLEDGE to have received of <b>NATIONAL WATER RESOURCES BOARD <?php echo @$payroll_grouping[0]['code']; ?>&emsp; <?php echo @$pay_basis; ?></b> the sum therein specified opposite our names being in full compensation for
                                    <br> our services for the period &emsp;&emsp; <b><?php echo date('F d, Y',strtotime(@$payroll_period[0]['start_date'])); ?></b> &emsp; to &emsp; <b><?php echo date('F d, Y',strtotime(@$payroll_period[0]['end_date'])); ?></b>&emsp; except as noted otherwise in the Remarks Column.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php
                    
                    // var_dump(sizeof($payroll));die();

                    
                    $grand_total = array(
                        'salary'=>0.00,
                        'underpayment'=>0.00,
                        'overpayment'=>0.00,
                        'pera_amt'=>0.00,
                        'pera_wop_amt'=>0.00,
                        'sss_gsis_amt'=>0.00,
                        'sss_gsis_amt_employer'=>0.00,
                        'gsis_optional_ins'=>0.00,
                        'gsis_sdo_ca'=>0.00,
                        'gsis_enhanced'=>0.00,
                        'gsis_consolidated'=>0.00,
                        'gsis_ecard_plus'=>0.00,
                        'gsis_old_gsis'=>0.00,
                        'gsis_emergency'=>0.00,
                        'gsis_housing'=>0.00,
                        'gsis_educational'=>0.00,
                        'gsis_psmbfund'=>0.00,
                        'gsis_policy'=>0.00,
                        'gsis_sakamay'=>0.00,
                        'gsis_nhmfc'=>0.00,
                        'gsis_union_dues'=>0.00,
                        'gsis_koop_loan'=>0.00,
                        'gsis_lost_tvr'=>0.00,
                        'gsis_optional_loan'=>0.00,
                        'gsis_other_loans'=>0.00,
                        'dagliang'=>0.00,
                        'pagibig_housing'=>0.00,
                        'pagibig_multipurpose'=>0.00,
                        'pagibig_mp2'=>0,
                        'pagibig_calamity'=>0.00,
                        'gross_pay'=>0.00,
                        'net_pay'=>0.00,
                        'total_ot_amt'=>0.00,
                        'pagibig_amt'=>0.00,
                        'pagibig_amt_employer'=>0.00,
                        'philhealth_amt'=>0.00,
                        'wh_tax_amt'=>0.00,
                        'total_deduct_amt'=>0.00,
                        'cutoff_1'=>0.00,
                        'acpcea_amt'=>0.00,
                        'damayan_amt'=>0.00,
                        'loan_grocery'=>0.00,
                        'abst_amt'=>0.00,
                        'provident_amt'=>0.00

                    );
                    $page_count = 1;
                    $last_row = 0;
                    $count = 1;
                    $page = 0;
                    
                    ?>
                    <div class="table-container table-responsive">
                        <table style="width:100%;" id="main-table" style="display: none;">
                        <?php 
                        while(sizeof($payroll) > 0){
                            $page_total = array(
                                'salary'=>0.00,
                                'underpayment'=>0.00,
                                'overpayment'=>0.00,
                                'pera_amt'=>0.00,
                                'pera_wop_amt'=>0.00,
                                'sss_gsis_amt'=>0.00,
                                'sss_gsis_amt_employer'=>0.00,
                                'gsis_optional_ins'=>0.00,
                                'gsis_sdo_ca'=>0.00,
                                'gsis_enhanced'=>0.00,
                                'gsis_consolidated'=>0.00,
                                'gsis_ecard_plus'=>0.00,
                                'gsis_old_gsis'=>0.00,
                                'gsis_emergency'=>0.00,
                                'gsis_housing'=>0.00,
                                'gsis_educational'=>0.00,
                                'gsis_psmbfund'=>0.00,
                                'gsis_policy'=>0.00,
                                'gsis_sakamay'=>0.00,
                                'gsis_nhmfc'=>0.00,
                                'gsis_union_dues'=>0.00,
                                'gsis_koop_loan'=>0.00,
                                'gsis_lost_tvr'=>0.00,
                                'gsis_optional_loan'=>0.00,
                                'gsis_other_loans'=>0.00,
                                'dagliang'=>0.00,
                                'pagibig_housing'=>0.00,
                                'pagibig_multipurpose'=>0.00,
                                'pagibig_mp2'=>0,
                                'pagibig_calamity'=>0.00,
                                'gross_pay'=>0.00,
                                'net_pay'=>0.00,
                                'total_ot_amt'=>0.00,
                                'pagibig_amt'=>0.00,
                                'pagibig_amt_employer'=>0.00,
                                'philhealth_amt'=>0.00,
                                'wh_tax_amt'=>0.00,
                                'total_deduct_amt'=>0.00,
                                'cutoff_1'=>0.00,
                                'acpcea_amt'=>0.00,
                                'damayan_amt'=>0.00,
                                'loan_grocery'=>0.00,
                                'abst_amt'=>0.00,
                                'provident_amt'=>0.00

                            );
                            $page++; 
                            $class = "page-break";
                            ?>
                            
                            <tbody class="page-break">
                                <?php if($count > 1): ?>
                                <tr>
                                    <td colspan="100" style="text-align:right"><label>Page No.: <?php echo $page.' of '.$total_page; ?></label></td>
                                </tr>
                                <?php endif; ?>
                                <tr style="border-top: 1px solid black;height:10px;">
                                    <td colspan="100"></td>
                                </tr>
                                <tr style="text-align:center;border-top: 1px solid black;">
                                    <td style="text-align:left" nowrap valign="middle">No.</td>
                                    <td style="text-align:left" nowrap valign="middle">Name of Employee </td>
                                    <td nowrap valign="middle"> Monthly<br>Rate</td>
                                    <td nowrap valign="middle"></td>
                                    <td nowrap valign="middle">Pera</td>
                                    <td nowrap valign="middle" colspan="2">
                                        GSIS<br> <!-- remove colspan -->
                                        Life & Retirement
                                    </td>
                                    <td nowrap valign="middle">GSIS <br> Optional</td>
                                    <td nowrap valign="middle">S.D.O<br>Cash</td>
                                    <td nowrap valign="middle" style="width:5px;">GSIS Enhanced<br>Salary</td>
                                    <td nowrap valign="middle" style="width:5px;">GSIS<br>Consolidated</td>
                                    <td nowrap valign="middle" style="width:5px;">GSIS<br>ECard</td>
                                    <td nowrap valign="middle" style="width:5px;">GSIS<br>Emergency</td>
                                    <td nowrap valign="middle" style="width:5px;">OLD GSIS<br>Loan</td>
                                    <td nowrap valign="middle" style="width:5px;">GSIS<br>Educational</td>
                                    <td nowrap valign="middle">Dagliang<br>Damay</td>
                                    <td nowrap valign="middle">Net Pay:</td>
                                    <td nowrap valign="middle">Remarks</td>
                                </tr>
                                <tr style="text-align:center">
                                    <td></td>
                                    <td style="text-align:left" nowrap valign="middle">Position</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td nowrap valign="middle">P/Share</td>
                                    <td nowrap valign="middle">G/Share</td>
                                    <td nowrap valign="middle">Insurance</td>
                                    <td nowrap valign="middle">Advance</td>
                                    <td nowrap valign="middle">Loan</td>
                                    <td nowrap valign="middle">Loan</td>
                                    <td></td>
                                    <td nowrap valign="middle">Loan</td>
                                    <td></td>
                                    <td nowrap valign="middle">Loan</td>
                                    <td nowrap valign="middle">Loan</td>
                                    <td colspan="2"></td>
                                </tr>
                                <tr style="text-align:center">
                                    <td rowspan="2"></td>
                                    <td rowspan="2" nowrap valign="middle"></td>
                                    <td rowspan="2"><?php if($pay_basis == "Casual") echo 'No of<br>Days'; ?></td>
                                    <td rowspan="2" nowrap valign="middle"></td>
                                    <td rowspan="2" nowrap valign="top">(PERA)</td>
                                    <td rowspan="2" nowrap valign="middle">GSIS<br>Housing<br>Loan</td>
                                    <td rowspan="2" nowrap valign="middle">GSIS<br>Policy<br>Loan</td>
                                    <td rowspan="2" nowrap valign="middle">PAGIBIG<br>M.P. II</td>
                                    <td nowrap valign="middle" colspan="2">PAG-IBIG FUND <!-- remove colspan --></td>
                                    <td rowspan="2" nowrap valign="top">PAGIBIG<br>Housing Loan</td>
                                    <td rowspan="2" nowrap valign="top">PAGIBIG<br>Multi-Purpose</td>
                                    <td rowspan="2" nowrap valign="middle">Phil.Health</td>
                                    <td rowspan="2" nowrap valign="middle"></td>
                                    <td rowspan="2" nowrap valign="middle">Withholding<br>Tax</td>
                                    <td rowspan="2" nowrap valign="middle">Gross Pay</td>
                                    <td rowspan="2" colspan="2"></td>
                                </tr>
                                <tr style="text-align:center">
                                    <td nowrap valign="top">P/Share</td>
                                    <td nowrap valign="top">G/Share<br>PAGIBIG</td>
                                </tr>
                                <tr style="text-align:center;border-bottom: 1px solid black;">
                                    <td colspan="3" nowrap valign="top"></td>
                                    <!-- <td nowrap valign="middle">####Damayan</td> -->
                                    <td nowrap valign="middle">E.C.C</td>
                                    <!-- <td nowrap valign="middle">####SAKAMAY</td> -->
                                    <td nowrap valign="middle">PSMBFund</td>
                                   <!--  <td nowrap valign="middle">####KOOP</td>
                                    <td nowrap valign="middle">####Grocery</td> -->
                                    <td nowrap valign="middle">Calamity Loan</td>
                                    <td nowrap valign="middle">NHMFC</td>
                                    <!-- <td nowrap valign="middle">####Lost T.V.R</td> -->
                                    <td nowrap valign="middle">Provident<br>Fund PShare</td>
                                    <td nowrap valign="middle">Other Loans</td>
                                    <td nowrap valign="middle">Absence<br>Without Pay</td>
                                    <td nowrap valign="middle">Total <br> Deduction</td>
                                    <td colspan="2"></td>
                                </tr>
                                <?php 
                                foreach ($payroll as $k => $v) {?>
                                    <tr>
                                        <td colspan="100"></td>
                                    </tr>
                                    <tr style="">
                                        <td nowrap="" valign="top">
                                           <?php echo $count; ?> 
                                        </td>
                                        <td nowrap="" style="text-align:left;" valign="top">
                                            <b>
                                            <?php 
                                            echo (isset($v['last_name']) && $v['last_name'] != "")?$this->Helper->decrypt($v['last_name'],$v['employee_id']):"";
                                            echo (isset($v['first_name']) && $v['first_name'] != "")?"&emsp;&nbsp;".$this->Helper->decrypt($v['first_name'],$v['employee_id']):"";
                                            echo (isset($v['middle_name']) && $v['middle_name'] != "")?$this->Helper->decrypt($v['middle_name'],$v['employee_id']):"";

                                            ?>
                                            </b>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php $grand_total['salary'] += $v['salary']; ?>
                                            <?php $page_total['salary'] += $v['salary']; ?>
                                            <?php echo number_format((double)@$v['salary'],2); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php $grand_total['pera_amt'] += $v['pera_amt']; ?>
                                            <?php $page_total['pera_amt'] += $v['pera_amt']; ?>
                                            <?php echo number_format((double)@$v['pera_amt'],2); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php $grand_total['sss_gsis_amt'] += $v['sss_gsis_amt']; ?>
                                            <?php $page_total['sss_gsis_amt'] += $v['sss_gsis_amt']; ?>
                                            <?php echo number_format((double)@$v['sss_gsis_amt'],2); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php $grand_total['sss_gsis_amt_employer'] += $v['sss_gsis_amt_employer']; ?>
                                            <?php $page_total['sss_gsis_amt_employer'] += $v['sss_gsis_amt_employer']; ?>
                                            <?php echo number_format((double)@$v['sss_gsis_amt_employer'],2); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php 
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
                                            ?>
                                            <?php $grand_total['gsis_optional_ins'] += (double) str_replace( ',', '', $loan ); ?>
                                            <?php $page_total['gsis_optional_ins'] += (double) str_replace( ',', '', $loan ); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php 
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
                                            ?>
                                            <?php $grand_total['gsis_sdo_ca'] += (double) str_replace( ',', '', $loan ); ?>
                                            <?php $page_total['gsis_sdo_ca'] += (double) str_replace( ',', '', $loan ); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php 
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
                                            ?>
                                            <?php $grand_total['gsis_enhanced'] += (double) str_replace( ',', '', $loan ); ?>
                                            <?php $page_total['gsis_enhanced'] += (double) str_replace( ',', '', $loan ); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php 
                                            $loan =  get_key(2,$loanDeductions);
                                            echo $loan;
                                            ?>
                                            <?php $grand_total['gsis_consolidated'] += (double) str_replace( ',', '', $loan ); ?>
                                            <?php $page_total['gsis_consolidated'] += (double) str_replace( ',', '', $loan ); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php 
                                            $loan =  get_key(5,$loanDeductions);
                                            echo $loan;
                                            ?>
                                            <?php $grand_total['gsis_ecard_plus'] += (double) str_replace( ',', '', $loan ); ?>
                                            <?php $page_total['gsis_ecard_plus'] += (double) str_replace( ',', '', $loan ); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php 
                                            $loan =  get_key(4,$loanDeductions);
                                            echo $loan;
                                            ?>
                                            <?php $grand_total['gsis_emergency'] += (double) str_replace( ',', '', $loan ); ?>
                                            <?php $page_total['gsis_emergency'] += (double) str_replace( ',', '', $loan ); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php 
                                            $loan =  get_key(6,$loanDeductions);
                                            echo $loan;
                                            ?>
                                            <?php $grand_total['gsis_old_gsis'] += (double) str_replace( ',', '', $loan ); ?>
                                            <?php $page_total['gsis_old_gsis'] += (double) str_replace( ',', '', $loan ); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php 
                                            $loan =  get_key(8,$loanDeductions);
                                            echo $loan;
                                            ?>
                                            <?php $grand_total['gsis_educational'] += (double) str_replace( ',', '', $loan ); ?>
                                            <?php $page_total['gsis_educational'] += (double) str_replace( ',', '', $loan ); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php 
                                            $loan =  get_key(33,$loanDeductions);
                                            echo $loan;
                                            ?>
                                            <?php $grand_total['dagliang'] += (double) str_replace( ',', '', $loan ); ?>
                                            <?php $page_total['dagliang'] += (double) str_replace( ',', '', $loan ); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top" rowspan="3">
                                            <?php $grand_total['net_pay'] += $v['net_pay']; ?>
                                            <?php $page_total['net_pay'] += $v['net_pay']; ?>
                                            <?php echo number_format((double)@$v['net_pay'],2); ?>
                                        </td>
                                        <td rowspan="3" valign="bottom">
                                            <div style="width:100%;border-bottom:1px solid black;text-align:right">
                                                <?php echo $count; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td colspan="2" valign="top" style="text-align:right;">
                                            <?php 
                                            if($pay_basis == "Casual")
                                                echo strtoupper(@$v['present_day']) 
                                            ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php $grand_total['pera_wop_amt'] += $v['pera_wop_amt']; ?>
                                            <?php $page_total['pera_wop_amt'] += $v['pera_wop_amt']; ?>
                                            <?php echo number_format((double)@$v['pera_wop_amt'],2); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php 
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
                                            ?>
                                            <?php $grand_total['gsis_housing'] += (double) str_replace( ',', '', $loan ); ?>
                                            <?php $page_total['gsis_housing'] += (double) str_replace( ',', '', $loan ); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php 
                                            $loan = "0.00";
                                            if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                                foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                                    if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "Policy Loan"){
                                                        $loan = number_format($vl1['amount'],2);
                                                        break;
                                                    }
                                                    else
                                                        $loan = "0.00";
                                                }
                                            }
                                            echo $loan;
                                            ?>
                                            <?php $grand_total['gsis_policy'] += (double) str_replace( ',', '', $loan ); ?>
                                            <?php $page_total['gsis_policy'] += (double) str_replace( ',', '', $loan ); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php 
                                            $loan = "0.00";
                                            if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                                foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                                    if($vl1['code_loan'] == "PAG-IBIG" && $vl1['code_sub'] == "M.P. II"){
                                                        $loan = number_format($vl1['amount'],2);
                                                        break;
                                                    }
                                                    else
                                                        $loan = "0.00";
                                                }
                                            }
                                            echo $loan;
                                            ?>
                                            <?php $grand_total['pagibig_mp2'] += (double) str_replace( ',', '', $loan ); ?>
                                            <?php $page_total['pagibig_mp2'] += (double) str_replace( ',', '', $loan ); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php $grand_total['pagibig_amt'] += $v['pagibig_amt']; ?>
                                            <?php $page_total['pagibig_amt'] += $v['pagibig_amt']; ?>
                                            <?php echo number_format((double)@$v['pagibig_amt'],2); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php $grand_total['pagibig_amt_employer'] += $v['pagibig_amt_employer']; ?>
                                            <?php $page_total['pagibig_amt_employer'] += $v['pagibig_amt_employer']; ?>
                                            <?php echo number_format((double)@$v['pagibig_amt_employer'],2); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php 
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
                                            ?>
                                            <?php $grand_total['pagibig_housing'] += (double) str_replace( ',', '', $loan ); ?>
                                            <?php $page_total['pagibig_housing'] += (double) str_replace( ',', '', $loan ); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php 
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
                                            ?>
                                            <?php $grand_total['pagibig_multipurpose'] += (double) str_replace( ',', '', $loan ); ?>
                                            <?php $page_total['pagibig_multipurpose'] += (double) str_replace( ',', '', $loan ); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php $grand_total['philhealth_amt'] += $v['philhealth_amt']; ?>
                                            <?php $page_total['philhealth_amt'] += $v['philhealth_amt']; ?>
                                            <?php echo number_format((double)@$v['philhealth_amt'],2); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php $grand_total['wh_tax_amt'] += $v['wh_tax_amt']; ?>
                                            <?php $page_total['wh_tax_amt'] += $v['wh_tax_amt']; ?>
                                            <?php echo number_format((double)@$v['wh_tax_amt'],2); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php $grand_total['gross_pay'] += $v['gross_pay']; ?>
                                            <?php $page_total['gross_pay'] += $v['gross_pay']; ?>
                                            <?php echo number_format((double)@$v['gross_pay'],2); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td colspan="2" valign="bottom"><?php echo strtoupper(@$v['position_name']) ?></td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php $grand_total['acpcea_amt'] += $v['acpcea_amt']; ?>
                                            <?php $page_total['acpcea_amt'] += $v['acpcea_amt']; ?>
                                            <?php echo number_format((double)@$v['acpcea_amt'],2); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php 
                                            $loan =  get_key(28,$loanDeductions);
                                            echo $loan;
                                            ?>
                                            <?php $grand_total['gsis_psmbfund'] += (double) str_replace( ',', '', $loan ); ?>
                                            <?php $page_total['gsis_psmbfund'] += (double) str_replace( ',', '', $loan ); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php 
                                            $loan =  get_key(22,$loanDeductions);
                                            echo $loan;
                                            ?>
                                            <?php $grand_total['pagibig_calamity'] += (double) str_replace( ',', '', $loan ); ?>
                                            <?php $page_total['pagibig_calamity'] += (double) str_replace( ',', '', $loan ); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php 
                                            $loan =  get_key(31,$loanDeductions);
                                            echo $loan;
                                            ?>
                                            <?php $grand_total['gsis_nhmfc'] += (double) str_replace( ',', '', $loan ); ?>
                                            <?php $page_total['gsis_nhmfc'] += (double) str_replace( ',', '', $loan ); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php 
                                            $loan =  get_key(32,$loanDeductions);
                                            ?>
                                            <?php echo number_format((double)(@$v['provident_amt'] + $loan),2); ?>
                                            <?php $grand_total['provident_amt'] += (@$v['provident_amt'] + $loan); ?>
                                            <?php $page_total['provident_amt'] += (@$v['provident_amt'] + $loan); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php 
                                            $loan =  get_key(17,$loanDeductions);
                                            echo $loan;
                                            ?>
                                            <?php $grand_total['gsis_other_loans'] += (double) str_replace( ',', '', $loan ); ?>
                                            <?php $page_total['gsis_other_loans'] += (double) str_replace( ',', '', $loan ); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php $grand_total['abst_amt'] += $v['abst_amt'] + $v['late_amt'] + $v['utime_amt']; ?>
                                            <?php $page_total['abst_amt'] += $v['abst_amt'] + $v['late_amt'] + $v['utime_amt']; ?>
                                            <?php echo number_format((double)@$v['abst_amt'] + $v['late_amt'] + $v['utime_amt'],2); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php $grand_total['total_deduct_amt'] += $v['total_deduct_amt']; ?>
                                            <?php $page_total['total_deduct_amt'] += $v['total_deduct_amt']; ?>
                                            <?php echo number_format((double)@$v['total_deduct_amt'],2); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="100"><br></td>
                                    </tr>
                                    <?php
                                    unset($payroll[$k]); 
                                    $count++;
                                    $last_row ++;
                                    if($last_row == $total_per_page){
                                        $total_per_page = $total_next_page;
                                    } 
                                    if((($k+1)/$total_per_page) === (intval(($k+1)/$total_per_page)) || sizeof($payroll) == 0){ ?>
                                        <tr style="font-weight:bold;border-top:1px solid black;text-align:right;" class="page_total">
                                            <td></td>
                                            <td  style="text-align:left" valign="top">PAGE TOTAL:</td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['salary'],2); ?>
                                            </td>
                                            <td valign="top">
                                                
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['pera_amt'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['sss_gsis_amt'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['sss_gsis_amt_employer'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['gsis_optional_ins'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['gsis_sdo_ca'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['gsis_enhanced'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['gsis_consolidated'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['gsis_ecard_plus'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['gsis_emergency'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['gsis_old_gsis'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['gsis_educational'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['dagliang'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['net_pay'],2); ?>
                                            </td>
                                            <td rowspan="3"></td>
                                        </tr>
                                        <tr style="font-weight:bold;text-align:right;" class="page_total">
                                            <td></td>
                                            <td  style="text-align:left" valign="top"></td>
                                            <td valign="top"></td>
                                            <td valign="top">
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['pera_wop_amt'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['gsis_housing'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['gsis_policy'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['pagibig_mp2'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['pagibig_amt'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['pagibig_amt_employer'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['pagibig_housing'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['pagibig_multipurpose'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['philhealth_amt'],2); ?>
                                            </td>
                                            <td valign="top">
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['wh_tax_amt'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['gross_pay'],2); ?>
                                            </td>
                                        </tr>
                                        <tr style="font-weight:bold;text-align:right;" class="page_total">
                                            <td></td>
                                            <td  style="text-align:left" valign="top"></td>
                                            <td valign="top"></td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['acpcea_amt'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['gsis_psmbfund'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['pagibig_calamity'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['gsis_nhmfc'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['provident_amt'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['gsis_other_loans'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['abst_amt'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['total_deduct_amt'],2); ?>
                                            </td>
                                        </tr>
                                        <?php if(sizeof($payroll) == 0 &&  (( $count-1 <= $total_next_page && $count-1 <= floor($total_next_page*.5) ) || ($count-1 > $total_next_page && ($count-1) - ($total_next_page * $total_page) <= floor($total_next_page*.5) ) ) ): ?>
                                        <tr style="border-top:1px solid black;">
                                            <td colspan="100"><br></td>
                                        </tr>
                                        <tr style="font-weight:bold;text-align:right;" class="grand_total">
                                            <td></td>
                                            <td  style="text-align:left" valign="top">GRAND TOTAL:</td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['salary'],2); ?>
                                            </td>
                                            <td valign="top">
                                                
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['pera_amt'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['sss_gsis_amt'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['sss_gsis_amt_employer'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['gsis_optional_ins'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['gsis_sdo_ca'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['gsis_enhanced'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['gsis_consolidated'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['gsis_ecard_plus'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['gsis_emergency'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['gsis_old_gsis'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['gsis_educational'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['dagliang'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['net_pay'],2); ?>
                                            </td>
                                            <td rowspan="3"></td>
                                        </tr>
                                        <tr style="font-weight:bold;text-align:right;" class="grand_total">
                                            <td></td>
                                            <td  style="text-align:left" valign="top"></td>
                                            <td valign="top"></td>
                                            <td valign="top">
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['pera_wop_amt'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['gsis_housing'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['gsis_policy'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['pagibig_mp2'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['pagibig_amt'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['pagibig_amt_employer'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['pagibig_housing'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['pagibig_multipurpose'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['philhealth_amt'],2); ?>
                                            </td>
                                            <td valign="top">
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['wh_tax_amt'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['gross_pay'],2); ?>
                                            </td>
                                        </tr>
                                        <tr style="font-weight:bold;text-align:right;" class="grand_total">
                                            <td></td>
                                            <td  style="text-align:left" valign="top"></td>
                                            <td valign="top"></td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['acpcea_amt'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['gsis_psmbfund'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['pagibig_calamity'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['gsis_nhmfc'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['provident_amt'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['gsis_other_loans'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['abst_amt'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['total_deduct_amt'],2); ?>
                                            </td>
                                        </tr>
                                        <tr style="border-bottom: 1px solid black;height:5px;">
                                            <td colspan="100"><br></td>
                                        </tr>
                                        <tr style="border-bottom: 1px solid black;height:5px;">
                                            <td colspan="100"></td>
                                        </tr>
                                        <tr class="signatories" style="page-break-before:avoid;">
                                            <td colspan="100">
                                                <?php
                                                    $signatory3 = "";
                                                    $signatory3_position = "";
                                                    if(sizeof($signatories) > 0){
                                                        foreach ($signatories as $k1 => $v1) {
                                                            if($v1['signatory_no'] == "3" ){
                                                                $signatory3 = $v1['signatory'];
                                                                $signatory3_position = $v1['employee_id'];
                                                            }
                                                        }
                                                    }
                                                    $signatory2 = "";
                                                    $signatory2_position = "";
                                                    if(sizeof($signatories) > 0){
                                                        foreach ($signatories as $k1 => $v1) {
                                                            if($v1['signatory_no'] == "2" ){
                                                                $signatory2 = $v1['signatory'];
                                                                $signatory2_position = $v1['employee_id'];
                                                            }
                                                        }
                                                    }
                                                    
                                                ?>
                                                <table style="border-top:1px solid black;width:100%;">
                                                    <tr>
                                                        <td colspan="3" nowrap valign="top" style="height:100px;">
                                                            <b>
                                                                CERTIFICATION:
                                                                <br>&emsp; This is to certify that the names listed in this payroll are <?php echo $pay_basis; ?> personnel of this Authority. This is to certify further that the amount corresponding to the leave of absences, tardiness,
                                                                <br>halfdays and undertime incurred without pay  during the current and or pay period are deducted accordingly.
                                                            </b>
                                                        </td>
                                                    </tr>
                                                    <tr style="border-top: 1px;height:100px;">
                                                        <td style="padding-left:20px;width:35%;" nowrap="" valign="top">
                                                            (1) I CERTIFY on my official oath that the above payroll is correct and the
                                                            <br>services have been rendered as stated.
                                                        </td>
                                                        <td style="width:30%;"></td>
                                                        <td style="padding-left:20px;width:35%;" nowrap="" valign="top">
                                                            (3) I CERTIFY on my official, that I have paid to each employee
                                                            <br>whose name appears on the above payroll the amount set opposite
                                                            <br> his name, he having presented his Residence Certificate
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding-left:20px;" nowrap="" valign="bottom" nowrap="">
                                                            <div style="width:100%;border-bottom:1px solid black;"></div>
                                                        </td>
                                                        <td style="text-align:left;padding-left:5%;" valign="bottom" nowrap="">
                                                            APPROVED:
                                                            <br>BY AUTHORITY OF THE CHAIRMAN
                                                        </td>
                                                        <td style="padding-left:20px;" nowrap="" valign="bottom" nowrap="">
                                                            <div style="width:100%;border-bottom:1px solid black;"></div>
                                                        </td>
                                                        <td style="padding-left:20px;" nowrap="" valign="top">
                                                        </td>
                                                    </tr>
                                                    <tr style="border-top: 1px;">
                                                        <td style="padding-left:20px;" nowrap="" valign="top">
                                                            (2) APPROVED, payable appropriation for:
                                                        </td>
                                                        <td style="width:30%;"></td>
                                                        <td style="padding-left:20px;" nowrap="" valign="top">
                                                            (4) I CERTIFY on my official, that I have witnessed payment to each person,
                                                            <br>whose name appears hereon, of the amount set opposite his name and my initials
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding-left:7%;" nowrap="">
                                                            <b><?php echo strtoupper(@$signatory2); ?></b>
                                                            <br><?php echo ucfirst(@$signatory2_position); ?>
                                                        </td>
                                                        <td style="padding-left:7%;">

                                                            <b><?php echo strtoupper(@$signatory3); ?></b>
                                                            <br><?php echo ucfirst(@$signatory3_position); ?>
                                                        </td>
                                                        <td style="text-align:right;">
                                                            <br>
                                                            <br>
                                                            <?php echo @$payroll_grouping[0]['code']; ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                    <?php 
                                        break;
                                    }
                                     
                                } ?> 
                                <!-- Grand Total -->
                                <?php if(sizeof($payroll) == 0 &&  (( $count-1 <= $total_next_page && $count-1 > floor($total_next_page*.5) ) || ($count-1 > $total_next_page && ($count-1) - ($total_next_page * $total_page) > floor($total_next_page*.5) ) ) ):
                                    $page++;
                                ?>
                                    <tfoot class="page-break">
                                        <?php if($count > 1): ?>
                                        <tr>
                                            <td colspan="100" style="text-align:right"><label>Page No.: <?php echo $page.' of '.$total_page; ?></label></td>
                                        </tr>
                                        <?php endif; ?>
                                        <tr style="border-top: 1px solid black;height:10px;">
                                            <td colspan="100"></td>
                                        </tr>
                                        <tr style="font-weight:bold;text-align:right;" class="grand_total">
                                            <td></td>
                                            <td  style="text-align:left" valign="top">GRAND TOTAL:</td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['salary'],2); ?>
                                            </td>
                                            <td valign="top">
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['pera_amt'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['sss_gsis_amt'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['sss_gsis_amt_employer'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['gsis_optional_ins'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['gsis_sdo_ca'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['gsis_enhanced'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['gsis_consolidated'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['gsis_ecard_plus'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['gsis_emergency'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['gsis_old_gsis'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['gsis_educational'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['dagliang'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['net_pay'],2); ?>
                                            </td>
                                            <td rowspan="3"></td>
                                        </tr>
                                        <tr style="font-weight:bold;text-align:right;" class="grand_total">
                                            <td></td>
                                            <td  style="text-align:left" valign="top"></td>
                                            <td valign="top"></td>
                                            <td valign="top">
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['pera_wop_amt'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['gsis_housing'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['gsis_policy'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['pagibig_mp2'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['pagibig_amt'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['pagibig_amt_employer'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['pagibig_housing'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['pagibig_multipurpose'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['philhealth_amt'],2); ?>
                                            </td>
                                            <td valign="top">
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['wh_tax_amt'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['gross_pay'],2); ?>
                                            </td>
                                        </tr>
                                        <tr style="font-weight:bold;text-align:right;" class="grand_total">
                                            <td></td>
                                            <td  style="text-align:left" valign="top"></td>
                                            <td valign="top"></td>
                                            <!-- <td valign="top">
                                                <?php echo '####'.number_format((double)@$grand_total['damayan_amt'],2); ?>
                                            </td> -->
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['acpcea_amt'],2); ?>
                                            </td>
                                            <!-- <td valign="top">
                                                <?php echo '####'.number_format((double)@$grand_total['gsis_sakamay'],2); ?>
                                            </td> -->
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['gsis_psmbfund'],2); ?>
                                            </td>
                                           <!--  <td valign="top">
                                                <?php echo '####'.number_format((double)@$grand_total['gsis_koop_loan'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo '####'.number_format((double)@$grand_total['loan_grocery'],2); ?>
                                            </td> -->
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['pagibig_calamity'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['gsis_nhmfc'],2); ?>
                                            </td>
                                           <!--  <td valign="top">
                                                <?php echo '####'.number_format((double)@$grand_total['gsis_lost_tvr'],2); ?>
                                            </td> -->
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['provident_amt'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['gsis_other_loans'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['abst_amt'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['total_deduct_amt'],2); ?>
                                            </td>
                                        </tr>
                                        <tr style="border-bottom: 1px solid black;height:5px;">
                                            <td colspan="100"><br></td>
                                        </tr>
                                        <tr style="border-bottom: 1px solid black;height:5px;">
                                            <td colspan="100"></td>
                                        </tr>
                                        <tr class="signatories" style="page-break-before:avoid;">
                                            <td colspan="100">
                                                <?php
                                                    $signatory3 = "";
                                                    $signatory3_position = "";
                                                    if(sizeof($signatories) > 0){
                                                        foreach ($signatories as $k1 => $v1) {
                                                            if($v1['signatory_no'] == "3" ){
                                                                $signatory3 = $v1['signatory'];
                                                                $signatory3_position = $v1['employee_id'];
                                                            }
                                                        }
                                                    }
                                                    $signatory2 = "";
                                                    $signatory2_position = "";
                                                    if(sizeof($signatories) > 0){
                                                        foreach ($signatories as $k1 => $v1) {
                                                            if($v1['signatory_no'] == "2" ){
                                                                $signatory2 = $v1['signatory'];
                                                                $signatory2_position = $v1['employee_id'];
                                                            }
                                                        }
                                                    }
                                                    
                                                ?>
                                                <table style="border-top:1px solid black;width:100%;">
                                                    <tr>
                                                        <td colspan="3" nowrap valign="top" style="height:100px;">
                                                            <b>
                                                                CERTIFICATION:
                                                                <br>&emsp; This is to certify that the names listed in this payroll are <?php echo $pay_basis; ?> personnel of this Authority. This is to certify further that the amount corresponding to the leave of absences, tardiness,
                                                                <br>halfdays and undertime incurred without pay  during the current and or pay period are deducted accordingly.
                                                            </b>
                                                        </td>
                                                    </tr>
                                                    <tr style="border-top: 1px;height:100px;">
                                                        <td style="padding-left:20px;width:35%;" nowrap="" valign="top">
                                                            (1) I CERTIFY on my official oath that the above payroll is correct and the
                                                            <br>services have been rendered as stated.
                                                        </td>
                                                        <td style="width:30%;"></td>
                                                        <td style="padding-left:20px;width:35%;" nowrap="" valign="top">
                                                            (3) I CERTIFY on my official, that I have paid to each employee
                                                            <br>whose name appears on the above payroll the amount set opposite
                                                            <br> his name, he having presented his Residence Certificate
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding-left:20px;" nowrap="" valign="bottom" nowrap="">
                                                            <div style="width:100%;border-bottom:1px solid black;"></div>
                                                        </td>
                                                        <td style="text-align:left;padding-left:5%;" valign="bottom" nowrap="">
                                                            APPROVED:
                                                            <br>BY AUTHORITY OF THE CHAIRMAN
                                                        </td>
                                                        <td style="padding-left:20px;" nowrap="" valign="bottom" nowrap="">
                                                            <div style="width:100%;border-bottom:1px solid black;"></div>
                                                        </td>
                                                        <td style="padding-left:20px;" nowrap="" valign="top">
                                                        </td>
                                                    </tr>
                                                    <tr style="border-top: 1px;">
                                                        <td style="padding-left:20px;" nowrap="" valign="top">
                                                            (2) APPROVED, payable appropriation for:
                                                        </td>
                                                        <td style="width:30%;"></td>
                                                        <td style="padding-left:20px;" nowrap="" valign="top">
                                                            (4) I CERTIFY on my official, that I have witnessed payment to each person,
                                                            <br>whose name appears hereon, of the amount set opposite his name and my initials
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding-left:7%;" nowrap="">
                                                            <b><?php echo strtoupper(@$signatory2); ?></b>
                                                            <br><?php echo ucfirst(@$signatory2_position); ?>
                                                        </td>
                                                        <td style="padding-left:7%;">

                                                            <b><?php echo strtoupper(@$signatory3); ?></b>
                                                            <br><?php echo ucfirst(@$signatory3_position); ?>
                                                        </td>
                                                        <td style="text-align:right;">
                                                            <br>
                                                            <br>
                                                            <?php echo @$payroll_grouping[0]['code']; ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </tfoot>
                                <?php 
                                    endif; ?>
                            
                        <?php } ?>
                        </table>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-12 text-right">
        <button id="printClearance" class="btn bg-green btn-fab btn-fab-mini">Print Preview <i class = "material-icons">print</i></button>
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