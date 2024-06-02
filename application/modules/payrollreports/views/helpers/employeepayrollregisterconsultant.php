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
                    // $payroll = array_merge($payroll,$payroll);
                    // $payroll = array_merge($payroll,$payroll);
                    // $payroll = array_merge($payroll,$payroll);
                    // $payroll = array_merge($payroll,$payroll);
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
                                    &emsp;WE HEREBY ACKNOWLEDGE to have received of <b>NATIONAL WATER RESOURCES BOARD  <?php echo strtoupper(@$payroll_grouping[0]['code']); ?> <?php echo strtoupper(@$pay_basis); ?></b> the sum therein specified opposite our respective names, being in full
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
                        'earned_for_period'=>0.00,
                        'loyalty_card'=>0.00,
                        'net_earned'=>0.00,
                        'net_pay'=>0.00,
                        'wh_tax_amt'=>0.00
                    );
                    $last_row = 0;
                    $count = 1;
                    $page = 0;
                    
                    ?>
                    <div class="table-container table-responsive">
                        <table style="width:100%;" id="#main_table">
                        <?php 
                        while(sizeof($payroll) > 0){
                            $page++; 
                            $page_total = array(
                                'salary'=>0.00,
                                'earned_for_period'=>0.00,
                                'loyalty_card'=>0.00,
                                'net_earned'=>0.00,
                                'net_pay'=>0.00,
                                'wh_tax_amt'=>0.00
                            );
                        ?>
                            <tbody class="page-break">
                                <?php if($count > 1): ?>
                                <tr>
                                    <td colspan="100" style="text-align:right"><label>Page No.: <?php echo $page.' of '.$total_page; ?></label></td>
                                </tr>
                                <?php endif; ?>
                                <tr style="border-top: 1px solid black;">
                                    <td colspan="100" style="height:10px;"></td>
                                </tr>
                                <tr class="" style="border-bottom: 1px solid black;border-top:1px solid black;" style="font-weight:bold;">
                                    <td style="text-align:center;width:30px;" valign="top" nowrap>NO.</td>
                                    <td style="text-align:left;" colspan="3" valign="middle" nowrap>
                                        NAME OF EMPLOYEE<br>
                                        <br>
                                        &emsp;Position
                                    </td>
                                    <td style="text-align:center;" valign="middle" nowrap>Basic Rate</td>
                                    <td style="text-align:center;" valign="middle" nowrap>Earned for the<br>Period</td>
                                    <td style="text-align:center;" valign="middle" nowrap>W/TAX</td>
                                    <td style="text-align:center;" valign="middle" nowrap>Loyalty Card</td>
                                    <td style="text-align:center;" valign="middle" nowrap>Net Pay</td>
                                    <td style="text-align:center;" valign="middle" nowrap>Signature</td>
                                    <td style="text-align:center;" valign="middle" nowrap>Remarks</td>
                                </tr>
                                <?php 
                                foreach ($payroll as $k => $v) {
                                ?>
                                    <tr>
                                        <td style="width:10%;"></td>
                                        <td style="width:10%;"></td>
                                        <td style="width:10%;"></td>
                                        <td style="width:10%;"></td>
                                        <td style="width:10%;"></td>
                                        <td style="width:10%;"></td>
                                        <td style="width:10%;"></td>
                                        <td style="width:10%;"></td>
                                        <td style="width:10%;"></td>
                                        <td style="width:10%;"></td>
                                    </tr>
                                    <tr style="height:25px;" class="">
                                        <td nowrap="" valign="top">
                                           <?php echo $count; ?> 
                                        </td>
                                        <td nowrap="" style="text-align:left;" valign="top">
                                            <b><?php echo $this->Helper->decrypt($v['last_name'],$v['employee_id']); ?>
                                            </b>
                                            <br>
                                            <?php echo $v['position_name']; ?>
                                            
                                        </td>
                                        <td nowrap="" style="text-align:left;" valign="top">
                                            <b><?php echo $this->Helper->decrypt($v['first_name'],$v['employee_id']); ?></b>
                                        </td>
                                        <td nowrap="" style="text-align:left;" valign="top">
                                            <b><?php echo $this->Helper->decrypt($v['middle_name'],$v['employee_id']); ?></b>
                                        </td nowrap="" style="text-align:left;" valign="top">
                                        <td nowrap style="text-align:center;" valign="top">
                                            <?php $grand_total['salary'] += $v['salary']; ?>
                                            <?php $page_total['salary'] += $v['salary']; ?>
                                            <?php echo number_format((double)@$v['salary'],2); ?>
                                        </td>
                                        <td nowrap style="text-align:center;" valign="top">
                                            <?php $grand_total['earned_for_period'] += $v['earned_for_period']; ?>
                                            <?php $page_total['earned_for_period'] += $v['earned_for_period']; ?>
                                            <?php echo number_format((double)@$v['earned_for_period'],2); ?>
                                        </td>
                                        <td nowrap style="text-align:center;" valign="top">
                                            <?php $grand_total['wh_tax_amt'] += $v['wh_tax_amt']; ?>
                                            <?php $page_total['wh_tax_amt'] += $v['wh_tax_amt']; ?>
                                            <?php echo number_format((double)@$v['wh_tax_amt'],2); ?>
                                        </td>
                                        <td nowrap style="text-align:center;" valign="top">
                                            <?php $grand_total['loyalty_card'] += $v['loyalty_card'] + $v['loyalty_card']; ?>
                                            <?php $page_total['loyalty_card'] += $v['loyalty_card'] + $v['loyalty_card']; ?>
                                            <?php echo number_format((double)@$v['loyalty_card'] + $v['loyalty_card'],2); ?>
                                        </td>
                                        <td nowrap style="text-align:center;" valign="top">
                                            <?php $grand_total['net_pay'] += $v['net_pay']; ?>
                                            <?php $page_total['net_pay'] += $v['net_pay']; ?>
                                            <b><?php echo number_format((double)@$v['net_pay'],2); ?></b>
                                        </td>
                                        <td style="text-align:center;" valign="top">
                                            <div style="width:80%;border-bottom:1px solid black;height:30px;text-align:left"><?php echo $count; ?></div>
                                        </td>
                                        <td style="text-align:center;" valign="top">
                                            <div style="width:80%;border-bottom:1px solid black;height:30px;text-align:left"></div>
                                        </td>
                                    </tr>
                                    <?php
                                    unset($payroll[$k]); 
                                    $count++;
                                    $last_row ++;
                                    if($last_row == $total_per_page){
                                        $total_per_page = $total_next_page;
                                    } 
                                    if((($k+1)/$total_per_page) === (intval(($k+1)/$total_per_page)) || sizeof($payroll) == 0){ ?>
                                        <tr style="height:30px;text-align:center;font-weight:bold;border-top:1px solid black;border-bottom:1px solid black;" class="page_total">
                                            <td colspan="2" valign="top"></td>
                                            <td style="text-align:left" valign="top">PAGE TOTAL:</td>
                                            <td valign="top" colspan="2"></td>
                                            <td valign="top" >
                                                <?php echo number_format((double)@$page_total['earned_for_period'],2); ?>
                                            </td>
                                            <td valign="top" >
                                                <?php echo number_format((double)@$page_total['wh_tax_amt'],2); ?>
                                            </td>
                                            <td valign="top" >
                                                <?php echo number_format((double)@$page_total['loyalty_card'],2); ?>
                                            </td>
                                            <td valign="top">
                                                <?php echo number_format((double)@$page_total['net_pay'],2); ?>
                                            </td>
                                            <td colspan="2"></td>
                                        </tr>
                                        <?php if(sizeof($payroll) == 0 &&  (( $count-1 <= $total_next_page && $count-1 <= floor($total_next_page*.5) ) || ($count-1 > $total_next_page && ($count-1) - ($total_next_page * $total_page) <= floor($total_next_page*.5) ) ) ): ?>
                                        <?php if($count > 1): ?>
                                        <tr style="height:25px;text-align:center;font-weight:bold;" class="page_total">
                                            <td colspan="2" style="width:" valign="top"></td>
                                            <td style="text-align:left" valign="top">GRAND TOTAL:</td>
                                            <td valign="top" colspan="2"></td>
                                            <td valign="bottom" >
                                                <?php echo number_format((double)@$grand_total['earned_for_period'],2); ?>
                                            </td>
                                            <td valign="bottom" >
                                                <?php echo number_format((double)@$grand_total['wh_tax_amt'],2); ?>
                                            </td>
                                            <td valign="bottom" >
                                                <?php echo number_format((double)@$grand_total['loyalty_card'],2); ?>
                                            </td>
                                            <td valign="bottom">
                                                <?php echo number_format((double)@$grand_total['net_pay'],2); ?>
                                            </td>
                                            <td colspan="2"></td>
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
                                                            <b><?php echo strtoupper(@$signatory3); ?></b>
                                                        <br><?php echo ucfirst(@$signatory3_position); ?>
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
                                    <tr style="border-top: 1px solid black;">
                                        <td colspan="100" style="height:10px;"></td>
                                    </tr>
                                    <tr class="" style="border-bottom: 1px solid black;border-top:1px solid black;" style="font-weight:bold;">
                                        <td style="text-align:center;width:30px;" valign="top" nowrap>NO.</td>
                                        <td style="text-align:left;" colspan="3" valign="middle" nowrap>
                                            NAME OF EMPLOYEE<br>
                                            <br>
                                            &emsp;Position
                                        </td>
                                        <td style="text-align:center;" valign="middle" nowrap>Basic Rate</td>
                                        <td style="text-align:center;" valign="middle" nowrap>Earned for the<br>Period</td>
                                        <td style="text-align:center;" valign="middle" nowrap>W/TAX</td>
                                        <td style="text-align:center;" valign="middle" nowrap>Loyalty Card</td>
                                        <td style="text-align:center;" valign="middle" nowrap>Net Pay</td>
                                        <td style="text-align:center;" valign="middle" nowrap>Signature</td>
                                        <td style="text-align:center;" valign="middle" nowrap>Remarks</td>
                                    </tr>
                                    <tr style="border-top:1px solid black;">
                                        <td colspan="100"><br></td>
                                    </tr>
                                    <tr>
                                        <td style="width:10%;"></td>
                                        <td style="width:10%;"></td>
                                        <td style="width:10%;"></td>
                                        <td style="width:10%;"></td>
                                        <td style="width:10%;"></td>
                                        <td style="width:10%;"></td>
                                        <td style="width:10%;"></td>
                                        <td style="width:10%;"></td>
                                        <td style="width:10%;"></td>
                                        <td style="width:10%;"></td>
                                    </tr>
                                    <tr style="height:25px;text-align:center;font-weight:bold;" class="page_total">
                                        <td colspan="2" style="width:" valign="top"></td>
                                        <td style="text-align:left" valign="top">GRAND TOTAL:</td>
                                        <td valign="top" colspan="2"></td>
                                        <td valign="bottom" >
                                            <?php echo number_format((double)@$grand_total['earned_for_period'],2); ?>
                                        </td>
                                        <td valign="bottom" >
                                            <?php echo number_format((double)@$grand_total['wh_tax_amt'],2); ?>
                                        </td>
                                        <td valign="bottom" >
                                            <?php echo number_format((double)@$grand_total['loyalty_card'],2); ?>
                                        </td>
                                        <td valign="bottom">
                                            <?php echo number_format((double)@$grand_total['net_pay'],2); ?>
                                        </td>
                                        <td colspan="2"></td>
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
                                                        <b><?php echo strtoupper(@$signatory3); ?></b>
                                                        <br><?php echo ucfirst(@$signatory3_position); ?>
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
