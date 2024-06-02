<?php
function convertToHoursMins($time, $format = '%02d:%02d') {
    if ($time < 1) {
        return;
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    return sprintf($format, $hours, $minutes);
}
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
                           font-size: 15px;
                           color: black;
                        }
                        table tr td{
                            font-size: 20px;
                        }
                        table{
                            border-collapse: collapse;
                            page-break-inside:always;
                            width:100%;
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
                    .no_dtr{
                        background-image: -moz-linear-gradient(45deg, #d4d4d4 25%, transparent 25%), 
                            -moz-linear-gradient(-45deg, #d4d4d4 25%, transparent 25%), 
                            -moz-linear-gradient(45deg, transparent 75%, #d4d4d4 75%), 
                            -moz-linear-gradient(-45deg, transparent 75%, #d4d4d4 75%);
                        background-image: -webkit-gradient(linear, 0 100%, 100% 0, color-stop(.25, #d4d4d4), color-stop(.25, transparent)), 
                            -webkit-gradient(linear, 0 0, 100% 100%, color-stop(.25, #d4d4d4), color-stop(.25, transparent)), 
                            -webkit-gradient(linear, 0 100%, 100% 0, color-stop(.75, transparent), color-stop(.75, #d4d4d4)), 
                            -webkit-gradient(linear, 0 0, 100% 100%, color-stop(.75, transparent), color-stop(.75, #d4d4d4));
                        background-image: -webkit-linear-gradient(45deg, #d4d4d4 25%, transparent 25%), 
                            -webkit-linear-gradient(-45deg, #d4d4d4 25%, transparent 25%), 
                            -webkit-linear-gradient(45deg, transparent 75%, #d4d4d4 75%), 
                            -webkit-linear-gradient(-45deg, transparent 75%, #d4d4d4 75%);
                        background-image: -o-linear-gradient(45deg, #d4d4d4 25%, transparent 25%), 
                            -o-linear-gradient(-45deg, #d4d4d4 25%, transparent 25%), 
                            -o-linear-gradient(45deg, transparent 75%, #d4d4d4 75%), 
                            -o-linear-gradient(-45deg, transparent 75%, #d4d4d4 75%);
                        background-image: linear-gradient(45deg, #d4d4d4 25%, transparent 25%), 
                            linear-gradient(-45deg, #d4d4d4 25%, transparent 25%), 
                            linear-gradient(45deg, transparent 75%, #d4d4d4 75%), 
                            linear-gradient(-45deg, transparent 75%, #d4d4d4 75%);
                        -moz-background-size: 2px 2px;
                        background-size: 2px 2px;
                        -webkit-background-size: 2px 2.1px; /* override value for webkit */
                        background-position: 0 0, 1px 0, 1px -1px, 0px 1px;
                    }
                </style> 
                <?php
                   
                    $total_per_page = 20;
                    $total_next_page = 20;
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
                                    &emsp;WE HEREBY ACKNOWLEDGE to have received of <b>NATIONAL WATER RESOURCES BOARD <?php echo strtoupper(@$payroll_grouping[0]['code']); ?> <?php echo strtoupper(@$pay_basis); ?></b> the sum therein specified opposite our respective names, being in full
                                    <br>compensation for our services for the period &emsp;&emsp; <b><?php echo date('F d, Y',strtotime(@$payroll_period[0]['start_date'])); ?></b> &emsp; to &emsp; <b><?php echo date('F d, Y',strtotime(@$payroll_period[0]['end_date'])); ?></b>&emsp; except as noted otherwise in the Remarks Column.
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
                        <table style="width:100%;" id="mainTable">
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
                                <?php 
                                    // var_dump($uses_atm);die();
                                    $visible_atm = "hidden";
                                    $visible_initial = "hidden";
                                    if($uses_atm == 0){
                                        $visible_atm = "visible";
                                    } 
                                    if($is_initial_salary == 1){
                                        $visible_initial = "visible";
                                    }
                                ?>
                                <tr class="" style="border: 1px solid black;" style="font-weight:bold;">
                                    <td style="text-align:center;width:30px;" valign="top" nowrap>NO.</td>
                                    <td style="text-align:left;" valign="top" nowrap>NAME OF EMPLOYEE<br><div style="width:100%;text-align:left;font-weight:bold;"><span style="visibility:<?php echo $visible_atm; ?>">W/OUT ATM </span><span style="visibility:<?php echo $visible_initial; ?>">INITIAL SALARY</span></div></td>
                                    <td valign="top" style="text-align:center;" nowrap>DESIGNATION</td>
                                    <td style="text-align:center;" valign="top" nowrap>RATE/<br>DAY</td>
                                    <td style="text-align:center;" valign="top" nowrap>NO. OF<br>DAYS</td>
                                    <td style="text-align:center;" valign="top" nowrap>EARNED FOR<br>THE PERIOD</td>
                                    <td style="text-align:center;" valign="top" nowrap>LATES <br>(WOP)</td>
                                    <td style="text-align:center;" valign="top" nowrap>EARNED FOR <br>THE PERIOD</td>
                                    <td style="text-align:center;" valign="top" nowrap>W/HOLDING<br>TAX</td>
                                    <td style="text-align:center;" valign="top" nowrap>KOOP</td>
                                    <td style="text-align:center;" valign="top" nowrap>Net Pay</td>
                                    <td style="text-align:center;width:30px;" valign="top" nowrap>NO.</td>
                                    <td style="text-align:center;" valign="top" nowrap>SIGNATURE</td>
                                    <td style="text-align:center;border-right:1px solid black;" valign="top" nowrap>REMARKS</td>
                                </tr>
                                <tr>
                                    <td colspan="100"><br></td>
                                </tr>
                                <?php 
                                foreach ($payroll as $k => $v) {
                                    $tr_class = "";
                                    if($v['net_pay'] == 0)
                                        $tr_class = "no_dtr";
                                ?>
                                    <tr style="height:25px;" class="<?php echo $tr_class; ?>">
                                        <td nowrap="" valign="top">
                                           <?php echo $count; ?> 
                                        </td>
                                        <td nowrap="" style="text-align:left;" valign="top">
                                            <b><?php echo $this->Helper->decrypt($v['last_name'],$v['employee_id']); ?>
                                            ,
                                            <?php echo $this->Helper->decrypt($v['first_name'],$v['employee_id']); ?>
                                            <?php echo $this->Helper->decrypt($v['middle_name'],$v['employee_id']); ?>
                                            </b>
                                            
                                        </td>
                                        <td nowrap="" style="text-align:center;" valign="top"><?php echo $v['position_name']; ?></td>
                                        <td nowrap style="text-align:center;" valign="top">
                                            <?php $grand_total['salary'] += $v['day_rate']; ?>
                                            <?php $page_total['salary'] += $v['day_rate']; ?>
                                            <?php echo number_format((double)@$v['day_rate'],2); ?>
                                        </td>
                                        <td nowrap style="text-align:center;" valign="top">
                                            <?php $grand_total['present_day'] += $v['present_day']; ?>
                                            <?php $page_total['present_day'] += $v['present_day']; ?>
                                            <?php echo @$v['present_day']; ?>
                                        </td>
                                        <td nowrap style="text-align:center;" valign="top">
                                            <?php $grand_total['earned_for_period'] += $v['earned_for_period']; ?>
                                            <?php $page_total['earned_for_period'] += $v['earned_for_period']; ?>
                                            <?php echo number_format((double)@$v['earned_for_period'],2); ?>
                                        </td>
                                        <td nowrap style="text-align:center;" valign="top">
                                            <?php $grand_total['late_amt'] += $v['late_amt'] + $v['utime_amt']; ?>
                                            <?php $page_total['late_amt'] += $v['late_amt'] + $v['utime_amt']; ?>
                                            <?php echo number_format((double)@$v['late_amt'] + $v['utime_amt'],2); ?>
                                        </td>
                                        <td nowrap style="text-align:center;" valign="top">
                                            <?php $grand_total['net_earned'] += $v['net_earned']; ?>
                                            <?php $page_total['net_earned'] += $v['net_earned']; ?>
                                            <?php echo number_format((double)@$v['net_earned'],2); ?>
                                        </td>
                                        <td nowrap style="text-align:center;" valign="top">
                                            <?php $grand_total['wh_tax_amt'] += $v['wh_tax_amt']; ?>
                                            <?php $page_total['wh_tax_amt'] += $v['wh_tax_amt']; ?>
                                            <?php echo number_format((double)@$v['wh_tax_amt'],2); ?>
                                        </td>
                                        <td nowrap style="text-align:center;" valign="top">
                                            
                                        </td>
                                        <td nowrap style="text-align:center;" valign="top">
                                            <?php $grand_total['net_pay'] += $v['net_pay']; ?>
                                            <?php $page_total['net_pay'] += $v['net_pay']; ?>
                                            <b><?php echo number_format((double)@$v['net_pay'],2); ?></b>
                                        </td>
                                        <td nowrap="" valign="top">
                                           <?php echo $count; ?> 
                                        </td>
                                        <td>
                                            <div style="width:100%;border-bottom:1px solid black;height:12px;"></div>
                                        </td>
                                        <td>
                                            <?php
                                                if($pay_basis == "Congress"){
                                                    if($v['net_pay'] == 0){
                                                        echo "NO DTR";
                                                    }
                                                    else{
                                                        echo date('F d',strtotime(@$payroll_period[0]['start_date'])).date('-d, Y',strtotime(@$payroll_period[0]['end_date']));
                                                    } 
                                                }
                                                if($pay_basis == "Oyster"){
                                                    if($v['late'] + $v['utime'] > 0){
                                                        $lates = explode('.', $v['late']);
                                                        $utime = explode('.', $v['utime']);
                                                        $late_utime_hr = @$lates[0] + @$utime[0];
                                                        $late_utime_min = @$lates[1] + @$utime[1];
                                                        $converted_time = convertToHoursMins($late_utime_min);
                                                        $exp_conv = explode(':', $converted_time);
                                                        $late_utime_hr += @$exp_conv[0];
                                                        $late_utime_min = @$exp_conv[1];
                                                        if($late_utime_hr > 0){
                                                            echo $late_utime_hr."H";
                                                        }
                                                        if($late_utime_min > 0){
                                                            echo $late_utime_min."M";
                                                        }
                                                    }   
                                                }
                                                
                                            ?>
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
                                        <tr style="height:25px;text-align:center;font-weight:bold;" class="page_total">
                                            <td colspan="2" valign="top"></td>
                                            <td style="text-align:center" valign="top">PAGE TOTAL:</td>
                                            <td valign="top" colspan="2"></td>
                                            <td valign="bottom" style="border-top:1px solid black;border-bottom:1px solid black;">
                                                <?php echo number_format((double)@$page_total['earned_for_period'],2); ?>
                                            </td>
                                            <td valign="bottom" style="border-top:1px solid black;border-bottom:1px solid black;">
                                                <?php echo number_format((double)@$page_total['late_amt'],2); ?>
                                            </td>
                                            <td valign="bottom" style="border-top:1px solid black;border-bottom:1px solid black;">
                                                <?php echo number_format((double)@$page_total['net_earned'],2); ?>
                                            </td>
                                            <td valign="bottom" style="border-top:1px solid black;border-bottom:1px solid black;">
                                                <?php echo number_format((double)@$page_total['wh_tax_amt'],2); ?>
                                            </td>
                                            <td valign="bottom" style="border-top:1px solid black;border-bottom:1px solid black;">
                                                <?php echo "0.00"; ?>
                                            </td>
                                            <td valign="bottom" style="border-top:1px solid black;border-bottom:1px solid black;">
                                                <?php echo number_format((double)@$page_total['net_pay'],2); ?>
                                            </td>
                                            <td colspan="3"></td>
                                        </tr>
                                        <?php if(sizeof($payroll) == 0 &&  (( $count-1 <= $total_next_page && $count-1 <= floor($total_next_page*.5) ) || ($count-1 > $total_next_page && ($count-1) - ($total_next_page * $total_page) <= floor($total_next_page*.5) ) ) ): ?>
                                        <tr style="height:25px;text-align:center;font-weight:bold;page-break-after: avoid;" class="page_total">
                                            <td colspan="2" valign="top"></td>
                                            <td style="text-align:center" valign="top">GRAND TOTAL:</td>
                                            <td valign="top" colspan="2"></td>
                                            <td valign="bottom" style="border-top:1px solid black;border-bottom:1px solid black;">
                                                <?php echo number_format((double)@$grand_total['earned_for_period'],2); ?>
                                            </td>
                                            <td valign="bottom" style="border-top:1px solid black;border-bottom:1px solid black;">
                                                <?php echo number_format((double)@$grand_total['late_amt'],2); ?>
                                            </td>
                                            <td valign="bottom" style="border-top:1px solid black;border-bottom:1px solid black;">
                                                <?php echo number_format((double)@$grand_total['net_earned'],2); ?>
                                            </td>
                                            <td valign="bottom" style="border-top:1px solid black;border-bottom:1px solid black;">
                                                <?php echo number_format((double)@$grand_total['wh_tax_amt'],2); ?>
                                            </td>
                                            <td valign="bottom" style="border-top:1px solid black;border-bottom:1px solid black;">
                                                <?php echo "0,00"; ?>
                                            </td>
                                            <td valign="bottom" style="border-top:1px solid black;border-bottom:1px solid black;">
                                                <?php echo number_format((double)@$grand_total['net_pay'],2); ?>
                                            </td>
                                            <td valign="bottom"></td>
                                            <td></td>
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
                                    <?php 
                                        $visible_atm = "hidden";
                                        $visible_initial = "hidden";
                                        if($uses_atm == 1){
                                            $visible_atm = "visible";
                                        } 
                                        if($is_initial_salary == 1){
                                            $visible_initial = "visible";
                                        }
                                    ?>
                                    <tr class="" style="border: 1px solid black;" style="font-weight:bold;">
                                        <td style="text-align:center;width:30px;" valign="top" nowrap>NO.</td>
                                        <td style="text-align:left;" valign="top" nowrap>NAME OF EMPLOYEE<br><div style="width:100%;text-align:left;font-weight:bold;"><span style="visibility:<?php echo $visible_atm; ?>">W/OUT ATM </span><span style="visibility:<?php echo $visible_initial; ?>">INITIAL SALARY</span></div></td>
                                        <td valign="top" style="text-align:center;" nowrap>DESIGNATION</td>
                                        <td style="text-align:center;" valign="top" nowrap>RATE/<br>DAY</td>
                                        <td style="text-align:center;" valign="top" nowrap>NO. OF<br>DAYS</td>
                                        <td style="text-align:center;" valign="top" nowrap>EARNED FOR<br>THE PERIOD</td>
                                        <td style="text-align:center;" valign="top" nowrap>LATES <br>(WOP)</td>
                                        <td style="text-align:center;" valign="top" nowrap>EARNED FOR <br>THE PERIOD</td>
                                        <td style="text-align:center;" valign="top" nowrap>W/HOLDING<br>TAX</td>
                                        <td style="text-align:center;" valign="top" nowrap>KOOP</td>
                                        <td style="text-align:center;" valign="top" nowrap>Net Pay</td>
                                        <td style="text-align:center;width:30px;" valign="top" nowrap>NO.</td>
                                        <td style="text-align:center;" valign="top" nowrap>SIGNATURE</td>
                                        <td style="text-align:center;border-right:1px solid black;" valign="top" nowrap>REMARKS</td>
                                    </tr>
                                    <tr style="height:25px;text-align:center;font-weight:bold;page-break-after: avoid;" class="page_total">
                                        <td colspan="2" valign="top"></td>
                                        <td style="text-align:center" valign="top">GRAND TOTAL:</td>
                                        <td valign="top" colspan="2"></td>
                                        <td valign="bottom" style="border-top:1px solid black;border-bottom:1px solid black;">
                                            <?php echo number_format((double)@$grand_total['earned_for_period'],2); ?>
                                        </td>
                                        <td valign="bottom" style="border-top:1px solid black;border-bottom:1px solid black;">
                                            <?php echo number_format((double)@$grand_total['late_amt'],2); ?>
                                        </td>
                                        <td valign="bottom" style="border-top:1px solid black;border-bottom:1px solid black;">
                                            <?php echo number_format((double)@$grand_total['net_earned'],2); ?>
                                        </td>
                                        <td valign="bottom" style="border-top:1px solid black;border-bottom:1px solid black;">
                                            <?php echo number_format((double)@$grand_total['wh_tax_amt'],2); ?>
                                        </td>
                                        <td valign="bottom" style="border-top:1px solid black;border-bottom:1px solid black;">
                                            <?php echo "0,00"; ?>
                                        </td>
                                        <td valign="bottom" style="border-top:1px solid black;border-bottom:1px solid black;">
                                            <?php echo number_format((double)@$grand_total['net_pay'],2); ?>
                                        </td>
                                        <td valign="bottom"></td>
                                        <td></td>
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
