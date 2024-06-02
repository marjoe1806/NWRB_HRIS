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
                            page-break-inside:always;
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
                <?php
                    
                    $total_per_page = 18;
                    $total_next_page = 18;
                    $total_page = floor(sizeof($payroll)/$total_per_page) + 1;
                    if(( sizeof($payroll) < $total_next_page && sizeof($payroll) > floor($total_next_page*.5) ) || (sizeof($payroll) > $total_next_page && (sizeof($payroll)) - ($total_next_page * $total_page) > floor($total_next_page*.5) )){
                        $total_page = floor(sizeof($payroll)/$total_per_page) + 2;
                    }
                ?> 
                <div class="header-container" style="width:100%;">
                    <table style="width:100%;border-bottom:0px;">
                        <thead>
                            <tr>
                                <td style="width:33%;text-align:left" nowrap>Date/Time Printed/User <?php echo date('m/d/Y  h:i:sa'); ?>  <?php echo Helper::get('first_name') ?></td>
                                <td style="width:33%;text-align:center" nowrap><label>GENERAL PAYROLL</label></td>
                                <td style="width:33%;text-align:right" nowrap><label>AS-PBD-005 <?php echo strtoupper(@$pay_basis); ?> PAYROLL <br> Page No.: 1 of <?php echo $total_page; ?></label></td>
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
                    
                    $page_count = 1;
                    $grand_total = array(
                        'salary'=>0.00,
                        'present_day'=>0.00,
                        'earned_for_period'=>0.00,
                        'late_amt'=>0.00,
                        'net_earned'=>0.00,
                        'pagibig_amt'=>0.00,
                        'philhealth_amt'=>0.00,
                        'philhealth_amt_employer'=>0.00,
                        'gsis_koop_loan'=>0.00,
                        'gsis_psmbfund'=>0.00,
                        'pagibig_multipurpose'=>0.00,
                        'dagliang'=>0.00,
                        'pagibig_housing'=>0.00,
                        'sss_gsis_amt'=>0.00,
                        'loan_grocery'=>0.00,
                        'deduct_lost_tvr'=>0.00,
                        'net_pay'=>0.00,
                        'wh_tax_amt'=>0.00,
                        'pagibig_amt_employer'=>0.00,
                        'damayan_amt'=>0.00,
                        'pagibig_calamity'=>0.00,
                        'dagliang'=>0.00,
                        'provident_amt'=>0.00,
                        'refund'=>0.00
                    );
                    $last_row = 0;
                    $count = 1;
                    $page = 0;
                    ?>
                    <div class="table-container table-responsive">
                        <?php 
                            
                            
                        ?>
                        <table style="width:100%">
                        <?php 
                        while(sizeof($payroll) > 0){
                            $page++; 
                            $page_total = array(
                                'salary'=>0.00,
                                'present_day'=>0.00,
                                'earned_for_period'=>0.00,
                                'late_amt'=>0.00,
                                'net_earned'=>0.00,
                                'pagibig_amt'=>0.00,
                                'philhealth_amt'=>0.00,
                                'philhealth_amt_employer'=>0.00,
                                'gsis_koop_loan'=>0.00,
                                'gsis_psmbfund'=>0.00,
                                'pagibig_multipurpose'=>0.00,
                                'dagliang'=>0.00,
                                'pagibig_housing'=>0.00,
                                'sss_gsis_amt'=>0.00,
                                'loan_grocery'=>0.00,
                                'deduct_lost_tvr'=>0.00,
                                'net_pay'=>0.00,
                                'wh_tax_amt'=>0.00,
                                'pagibig_amt_employer'=>0.00,
                                'damayan_amt'=>0.00,
                                'pagibig_calamity'=>0.00,
                                'dagliang'=>0.00,
                                'provident_amt'=>0.00,
                                'refund'=>0.00
                            );
                        ?>
                            <?php 
                                $class = "page-break";
                                if($count == 1)
                                    $class = "";

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
                                <tr class="" style="border-top: 1px solid black;border-bottom: 1px solid black;">
                                    <td style="text-align:center;width:30px;" valign="middle" nowrap>No.</td>
                                    <td style="text-align:left;" valign="middle" colspan="3" nowrap>Name of Employee<br>Position</td>
                                    <td style="text-align:center;" valign="middle" nowrap>Rate Per <br>Day</td>
                                    <td style="text-align:center;" valign="middle" nowrap>No. of <br>Days</td>
                                    <td style="text-align:center;" valign="middle" nowrap>Earned for <br>the Period</td>
                                    <td style="text-align:center;" valign="middle" nowrap>Lates <br>(WOP)</td>
                                    <td style="text-align:center;" valign="middle" nowrap>Net Earned for <br>the Period</td>
                                    <td style="text-align:center;" valign="bottom" nowrap>W/TAX</td>
                                    <td style="text-align:center;" valign="middle" nowrap>PAGIBIG <br>Share</td>
                                    <td style="text-align:center;" valign="middle" nowrap>PHILHEALTH <br>P/GSHARE</td>
                                    <td style="text-align:center;" valign="middle" nowrap>KOOP <br>Damayan</td>
                                    <td style="text-align:center;" valign="middle" nowrap>PAGIBIG Multi <br>PCalamity</td>
                                    <td style="text-align:center;" valign="middle" nowrap>PSMBFI <br>DAGLIANG</td>
                                    <td style="text-align:center;" valign="middle" nowrap>LOST <br>TVR</td>
                                    <td style="text-align:center;" valign="top" nowrap>PHousing</td>
                                    <td style="text-align:center;" valign="middle" nowrap>SSS<br>Provident</td>
                                    <td style="text-align:center;" valign="middle" nowrap>GROCERY<br>REFUND</td>
                                    <td style="text-align:center;" valign="middle" nowrap>Net Pay</td>
                                    <td style="text-align:center;" valign="middle" nowrap>Signature<br>Remarks</td>
                                </tr>
                                <tr>
                                    <td colspan="100"><br></td>
                                </tr>
                                <?php 
                                foreach ($payroll as $k => $v) {?>
                                    
                                    <tr style="height:40px;">
                                        <td nowrap="" valign="top">
                                           <?php echo $count; ?> 
                                        </td>
                                        <td nowrap="" style="text-align:left;" colspan="3" valign="top">
                                            <b><?php echo $this->Helper->decrypt($v['last_name'],$v['employee_id']); ?>
                                            &emsp;
                                            <?php echo $this->Helper->decrypt($v['first_name'],$v['employee_id']); ?>
                                            &emsp;
                                            <?php echo $this->Helper->decrypt($v['middle_name'],$v['employee_id']); ?>
                                            </b>
                                            <br>
                                            <?php echo $v['position_name']; ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php $grand_total['salary'] += $v['day_rate']; ?>
                                            <?php $page_total['salary'] += $v['day_rate']; ?>
                                            <?php echo number_format((double)@$v['day_rate'],2); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php $grand_total['present_day'] += $v['present_day']; ?>
                                            <?php $page_total['present_day'] += $v['present_day']; ?>
                                            <?php echo @$v['present_day']; ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php $grand_total['earned_for_period'] += $v['earned_for_period']; ?>
                                            <?php $page_total['earned_for_period'] += $v['earned_for_period']; ?>
                                            <?php echo number_format((double)@$v['earned_for_period'],2); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php $grand_total['late_amt'] += $v['late_amt'] + $v['utime_amt']; ?>
                                            <?php $page_total['late_amt'] += $v['late_amt'] + $v['utime_amt']; ?>
                                            <?php echo number_format((double)@$v['late_amt'] + $v['utime_amt'],2); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php $grand_total['net_earned'] += $v['net_earned']; ?>
                                            <?php $page_total['net_earned'] += $v['net_earned']; ?>
                                            <?php echo number_format((double)@$v['net_earned'],2); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php $grand_total['wh_tax_amt'] += $v['wh_tax_amt']; ?>
                                            <?php $page_total['wh_tax_amt'] += $v['wh_tax_amt']; ?>
                                            <?php echo number_format((double)@$v['wh_tax_amt'],2); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php $grand_total['pagibig_amt'] += $v['pagibig_amt']; ?>
                                            <?php $page_total['pagibig_amt'] += $v['pagibig_amt']; ?>
                                            <?php echo number_format((double)@$v['pagibig_amt'],2); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php $grand_total['philhealth_amt'] += $v['philhealth_amt']; ?>
                                            <?php $page_total['philhealth_amt'] += $v['philhealth_amt']; ?>
                                            <?php $display = number_format((double)@$v['philhealth_amt'],2); ?>
                                            <?php $grand_total['philhealth_amt_employer'] += $v['philhealth_amt_employer']; ?>
                                            <?php $page_total['philhealth_amt_employer'] += $v['philhealth_amt_employer']; ?>
                                            <?php $display .= "<br>".number_format((double)@$v['philhealth_amt_employer'],2); ?>
                                            <?php echo @$display; ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php 
                                            $loan = "0.00";
                                            if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                                foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                                    if($vl1['code_loan'] == "Other Loans" && $vl1['code_sub'] == "KOOP"){
                                                        $loan = number_format($vl1['amount'],2);
                                                        break;
                                                    }
                                                    else
                                                        $loan = "0.00";

                                                }
                                            }
                                            $display = $loan;
                                            ?>
                                            <?php $grand_total['gsis_koop_loan'] += (double) str_replace( ',', '', $loan ); ?>
                                            <?php $page_total['gsis_koop_loan'] += (double) str_replace( ',', '', $loan ); ?>
                                            <?php $grand_total['damayan_amt'] += $v['damayan_amt']; ?>
                                            <?php $page_total['damayan_amt'] += $v['damayan_amt']; ?>
                                            <?php $display .= "<br>". number_format((double)@$v['damayan_amt'],2); ?>
                                            <?php echo $display; ?>
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
                                            $display = $loan;
                                            ?>
                                            <?php $grand_total['pagibig_multipurpose'] += (double) str_replace( ',', '', $loan ); ?>
                                            <?php $page_total['pagibig_multipurpose'] += (double) str_replace( ',', '', $loan ); ?>
                                            
                                            <?php 
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
                                            $display.="<br>". $loan;
                                            echo $display;
                                            ?>
                                            <?php $grand_total['pagibig_calamity'] += (double) str_replace( ',', '', $loan ); ?>
                                            <?php $page_total['pagibig_calamity'] += (double) str_replace( ',', '', $loan ); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php 
                                            $loan = "0.00";
                                            if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                                foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                                    if($vl1['code_loan'] == "Other Loans" && $vl1['code_sub'] == "PSMBFund"){
                                                        $loan = number_format($vl1['amount'],2);
                                                        break;
                                                    }
                                                    else
                                                        $loan = "0.00";

                                                }
                                            }
                                            $display = $loan;
                                            ?>
                                            <?php $grand_total['gsis_psmbfund'] += (double) str_replace( ',', '', $loan ); ?>
                                            <?php $page_total['gsis_psmbfund'] += (double) str_replace( ',', '', $loan ); ?>

                                            <?php
                                            ////Not existing Dagliang 
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
                                            $display.="<br>". $loan;
                                            echo $display;
                                            ?>
                                            <?php $grand_total['dagliang'] += (double) str_replace( ',', '', $loan ); ?>
                                            <?php $page_total['dagliang'] += (double) str_replace( ',', '', $loan ); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php 
                                            $loan = "0.00";
                                            if(sizeof($otherDeductions[$v['employee_id']]) > 0){
                                                foreach ($otherDeductions[$v['employee_id']] as $l1 => $vl1) {
                                                    if($vl1['deduct_code'] == "Lost TVR"){
                                                        $loan = number_format($vl1['amount'],2);
                                                        break;
                                                    }
                                                    else
                                                        $loan = "0.00";

                                                }
                                            }
                                            echo $loan;
                                            ?>
                                            <?php $grand_total['deduct_lost_tvr'] += (double) str_replace( ',', '', $loan ); ?>
                                            <?php $page_total['deduct_lost_tvr'] += (double) str_replace( ',', '', $loan ); ?>
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
                                            <?php $grand_total['sss_gsis_amt'] += $v['sss_amt']; ?>
                                            <?php $page_total['sss_gsis_amt'] += $v['sss_amt']; ?>
                                            <?php $display = number_format((double)@$v['sss_amt'],2); ?>
                                            <?php 
                                                $loan = "0.00";
                                                if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                                    foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                                        if($vl1['code_loan'] == "Other Loans" && $vl1['code_sub'] == "Provident"){
                                                            $loan = number_format($vl1['amount'],2);
                                                            break;
                                                        }
                                                        else
                                                            $loan = "0.00";

                                                    }
                                                }
                                                //echo $loan;
                                            ?>
                                            <?php $display.="<br>". number_format((double)(@$v['provident_amt'] + $loan),2); ?>
                                            <?php $grand_total['provident_amt'] += (@$v['provident_amt'] + $loan); ?>
                                            <?php echo $display; ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php 
                                            $loan = "0.00";
                                            if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                                foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                                    if($vl1['code_loan'] == "Other Loans" && $vl1['code_sub'] == "Grocery"){
                                                        $loan = number_format($vl1['amount'],2);
                                                        break;
                                                    }
                                                    else
                                                        $loan = "0.00";

                                                }
                                            }
                                            $display = $loan;
                                            ?>
                                            <?php $grand_total['loan_grocery'] += (double) str_replace( ',', '', $loan ); ?>
                                            <?php $page_total['loan_grocery'] += (double) str_replace( ',', '', $loan ); ?>
                                            <?php 
                                            /*$loan = "0.00";
                                            if(sizeof($loanDeductions[$v['employee_id']]) > 0){
                                                foreach ($loanDeductions[$v['employee_id']] as $l1 => $vl1) {
                                                    if($vl1['code_loan'] == "GSIS" && $vl1['code_sub'] == "UNION DUES"){
                                                        $loan = number_format($vl1['amount'],2);
                                                        break;
                                                    }
                                                    else
                                                        $loan = "0.00";

                                                }
                                            }*/
                                            ////Refund no comoutations yet
                                            $loan = "0.00";
                                            $display.="<br>". $loan;
                                            echo $display;
                                            ?>
                                            <?php $grand_total['refund'] += (double) str_replace( ',', '', $loan ); ?>
                                            <?php $page_total['refund'] += (double) str_replace( ',', '', $loan ); ?>
                                        </td>
                                        <td nowrap style="text-align:right;" valign="top">
                                            <?php $grand_total['net_pay'] += $v['net_pay']; ?>
                                            <?php $page_total['net_pay'] += $v['net_pay']; ?>
                                            <b><?php echo number_format((double)@$v['net_pay'],2); ?></b>
                                        </td>
                                        <td>
                                            ----
                                            <div style="width:100%;border-bottom:1px solid black;"></div>
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
                                        <tr style="height:40px;text-align:right;font-weight:bold;border-top:1px solid black;" class="page_total">

                                            <td  colspan = '2' style="text-align:center" valign="top">PAGE TOTAL:</td>
                                            <td  colspan="2" valign="top"></td>

                                            <td valign="top">
                                                
                                            </td>
                                            <td valign="top">
                                                
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['earned_for_period'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['late_amt'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['net_earned'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['wh_tax_amt'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['pagibig_amt'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php $display = number_format((double)@$page_total['philhealth_amt'],2); ?>
                                                <?php $display.="<br>". number_format((double)@$page_total['philhealth_amt_employer'],2); ?>
                                                <?php echo $display; ?>
                                            </td>
                                            <td valign="top">
                                                <?php $display = number_format((double)@$page_total['gsis_koop_loan'],2); ?>
                                                <?php $display .="<br>". number_format((double)@$page_total['damayan_amt'],2); ?>
                                                <?php echo $display; ?>
                                            </td>
                                            <td valign="top">
                                                <?php $display = number_format((double)@$page_total['pagibig_multipurpose'],2); ?>
                                                <?php $display .= "<br>". number_format((double)@$page_total['pagibig_calamity'],2); ?>
                                                <?php echo $display; ?>
                                            </td>
                                            <td valign="top">
                                                <?php $display = number_format((double)@$page_total['gsis_psmbfund'],2); ?>
                                                <?php $display .="<br>". number_format((double)@$page_total['dagliang'],2); ?>
                                                <?php echo $display; ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['deduct_lost_tvr'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['pagibig_housing'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php $display = number_format((double)@$page_total['sss_gsis_amt'],2); ?> 
                                                <?php $display .= "<br>". number_format((double)@$page_total['provident_amt'],2); ?> 
                                                <?php echo $display; ?>
                                            </td>
                                            <td valign="top">
                                                <?php $display = number_format((double)@$page_total['loan_grocery'],2); ?>
                                                <?php $display .= "<br>". number_format((double)@$page_total['refund'],2); ?>
                                                <?php echo $display; ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['net_pay'],2); ?>
                                            </td>
                                            <td ></td>
                                        </tr>
                                        <?php if(sizeof($payroll) == 0 &&  (( $count-1 <= $total_next_page && $count-1 <= floor($total_next_page*.5) ) || ($count-1 > $total_next_page && ($count-1) - ($total_next_page * $total_page) <= floor($total_next_page*.5) ) ) ): ?>
                                        <tr style="height:40px;text-align:right;font-weight:bold;border-top:1px solid black;border-bottom:1px solid black;" class="page_total">
                                            <td  colspan = '2' style="text-align:center" valign="top">GRAND TOTAL:</td>
                                            <td  colspan="2" valign="top"></td>
                                            <td valign="top">
                                                
                                            </td>
                                            <td valign="top">
                                                
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['earned_for_period'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['late_amt'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['net_earned'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['wh_tax_amt'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['pagibig_amt'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php $display = number_format((double)@$grand_total['philhealth_amt'],2); ?>
                                                <?php $display.="<br>". number_format((double)@$grand_total['philhealth_amt_employer'],2); ?>
                                                <?php echo $display; ?>
                                            </td>
                                            <td valign="top">
                                                <?php $display = number_format((double)@$grand_total['gsis_koop_loan'],2); ?>
                                                <?php $display .="<br>". number_format((double)@$grand_total['damayan_amt'],2); ?>
                                                <?php echo $display; ?>
                                            </td>
                                            <td valign="top">
                                                <?php $display = number_format((double)@$grand_total['pagibig_multipurpose'],2); ?>
                                                <?php $display .= "<br>". number_format((double)@$grand_total['pagibig_calamity'],2); ?>
                                                <?php echo $display; ?>
                                            </td>
                                            <td valign="top">
                                                <?php $display = number_format((double)@$grand_total['gsis_psmbfund'],2); ?>
                                                <?php $display .="<br>". number_format((double)@$grand_total['dagliang'],2); ?>
                                                <?php echo $display; ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['deduct_lost_tvr'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['pagibig_housing'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php $display = number_format((double)@$grand_total['sss_gsis_amt'],2); ?> 
                                                <?php $display .= "<br>". number_format((double)@$grand_total['provident_amt'],2); ?> 
                                                <?php echo $display; ?>
                                            </td>
                                            <td valign="top">
                                                <?php $display = number_format((double)@$grand_total['loan_grocery'],2); ?>
                                                <?php $display .= "<br>". number_format((double)@$grand_total['refund'],2); ?>
                                                <?php echo $display; ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$grand_total['net_pay'],2); ?>
                                            </td>
                                            <td valign="top"></td>
                                        </tr>
                                        <tr style="border-bottom: 1px solid black;height:5px;">
                                            <td colspan="100"></td>
                                        </tr>
                                        <tr class="signatories">
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
                                                    <tr style="border-top 1px;height:100px;">
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
                                                    <tr style="border-top 1px;">
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
                                                            <?php echo $payroll_grouping[0]['code']; ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <?php endif;?>
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
                                    <tr class="" style="border-top: 1px solid black;border-bottom: 1px solid black;">
                                        <td style="text-align:center;width:30px;" valign="middle" nowrap>No.</td>
                                        <td style="text-align:left;" valign="middle" colspan="3" nowrap>Name of Employee<br>Position</td>
                                        <td style="text-align:center;" valign="middle" nowrap>Rate Per <br>Day</td>
                                        <td style="text-align:center;" valign="middle" nowrap>No. of <br>Days</td>
                                        <td style="text-align:center;" valign="middle" nowrap>Earned for <br>the Period</td>
                                        <td style="text-align:center;" valign="middle" nowrap>Lates <br>(WOP)</td>
                                        <td style="text-align:center;" valign="middle" nowrap>Net Earned for <br>the Period</td>
                                        <td style="text-align:center;" valign="bottom" nowrap>W/TAX</td>
                                        <td style="text-align:center;" valign="middle" nowrap>PAGIBIG <br>Share</td>
                                        <td style="text-align:center;" valign="middle" nowrap>PHILHEALTH <br>P/GSHARE</td>
                                        <td style="text-align:center;" valign="middle" nowrap>KOOP <br>Damayan</td>
                                        <td style="text-align:center;" valign="middle" nowrap>PAGIBIG Multi <br>PCalamity</td>
                                        <td style="text-align:center;" valign="middle" nowrap>PSMBFI <br>DAGLIANG</td>
                                        <td style="text-align:center;" valign="middle" nowrap>LOST <br>TVR</td>
                                        <td style="text-align:center;" valign="top" nowrap>PHousing</td>
                                        <td style="text-align:center;" valign="middle" nowrap>SSS<br>Provident</td>
                                        <td style="text-align:center;" valign="middle" nowrap>GROCERY<br>REFUND</td>
                                        <td style="text-align:center;" valign="middle" nowrap>Net Pay</td>
                                        <td style="text-align:center;" valign="middle" nowrap>Signature<br>Remarks</td>
                                    </tr>
                                    <tr style="height:40px;text-align:right;font-weight:bold;border-top:1px solid black;border-bottom:1px solid black;" class="page_total">
                                        <td  colspan = '2' style="text-align:center" valign="top">GRAND TOTAL:</td>
                                        <td  colspan="2" valign="top"></td>
                                        <td valign="top">
                                            
                                        </td>
                                        <td valign="top">
                                            
                                        </td>
                                        <td valign="top">
                                            <?php echo number_format((double)@$grand_total['earned_for_period'],2); ?>
                                        </td>
                                        <td valign="top">
                                            <?php echo number_format((double)@$grand_total['late_amt'],2); ?>
                                        </td>
                                        <td valign="top">
                                            <?php echo number_format((double)@$grand_total['net_earned'],2); ?>
                                        </td>
                                        <td valign="top">
                                            <?php echo number_format((double)@$grand_total['wh_tax_amt'],2); ?>
                                        </td>
                                        <td valign="top">
                                            <?php echo number_format((double)@$grand_total['pagibig_amt'],2); ?>
                                        </td>
                                        <td valign="top">
                                            <?php $display = number_format((double)@$grand_total['philhealth_amt'],2); ?>
                                            <?php $display.="<br>". number_format((double)@$grand_total['philhealth_amt_employer'],2); ?>
                                            <?php echo $display; ?>
                                        </td>
                                        <td valign="top">
                                            <?php $display = number_format((double)@$grand_total['gsis_koop_loan'],2); ?>
                                            <?php $display .="<br>". number_format((double)@$grand_total['damayan_amt'],2); ?>
                                            <?php echo $display; ?>
                                        </td>
                                        <td valign="top">
                                            <?php $display = number_format((double)@$grand_total['pagibig_multipurpose'],2); ?>
                                            <?php $display .= "<br>". number_format((double)@$grand_total['pagibig_calamity'],2); ?>
                                            <?php echo $display; ?>
                                        </td>
                                        <td valign="top">
                                            <?php $display = number_format((double)@$grand_total['gsis_psmbfund'],2); ?>
                                            <?php $display .="<br>". number_format((double)@$grand_total['dagliang'],2); ?>
                                            <?php echo $display; ?>
                                        </td>
                                        <td valign="top">
                                            <?php echo number_format((double)@$grand_total['deduct_lost_tvr'],2); ?>
                                        </td>
                                        <td valign="top">
                                            <?php echo number_format((double)@$grand_total['pagibig_housing'],2); ?>
                                        </td>
                                        <td valign="top">
                                            <?php $display = number_format((double)@$grand_total['sss_gsis_amt'],2); ?> 
                                            <?php $display .= "<br>". number_format((double)@$grand_total['provident_amt'],2); ?> 
                                            <?php echo $display; ?>
                                        </td>
                                        <td valign="top">
                                            <?php $display = number_format((double)@$grand_total['loan_grocery'],2); ?>
                                            <?php $display .= "<br>". number_format((double)@$grand_total['refund'],2); ?>
                                            <?php echo $display; ?>
                                        </td>
                                        <td valign="top">
                                            <?php echo number_format((double)@$grand_total['net_pay'],2); ?>
                                        </td>
                                        <td valign="top"></td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid black;height:5px;">
                                        <td colspan="100"></td>
                                    </tr>
                                    <tr class="signatories">
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
                                                <tr style="border-top 1px;height:100px;">
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
                                                <tr style="border-top 1px;">
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
                                                        <?php echo $payroll_grouping[0]['code']; ?>
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
