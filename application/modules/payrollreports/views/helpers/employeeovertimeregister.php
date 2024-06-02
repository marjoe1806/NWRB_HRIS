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
                            page-break-before: always;
                            margin-top:2px;
                        }
                    }
                </style> 
                <div class="header-container" style="width:100%;">
                    <table style="width:100%;border-bottom:0px;">
                        <thead>
                            <tr>
                                <td style="width:33%;text-align:left" nowrap><label>OVERTIME <?php echo strtoupper(@$pay_basis); ?> - VPSG<?php echo date('m/d/Y h:i:sa') ?></label></td>
                                <td style="width:33%;text-align:center" nowrap><label>GENERAL PAYROLL</label></td>
                                <td style="width:33%;text-align:right" nowrap><label>AS-PBD-005 OVERTIME PAYROLL <?php echo strtoupper(@$pay_basis); ?><br> Page No.: 1 of 1</label></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="100" nowrap>
                                    &emsp;WE HEREBY ACKNOWLEDGE to have received from <b>METROPOLITAN MANILA DEVELOPMENT AUTHORITY <?php echo @$payroll_grouping[0]['code']; ?></b> the sums therein specified opposite our
                                    <br>respective names, being in full compensation for our overtime services for the period <?php echo date('F Y',strtotime(@$payroll_period[0]['payroll_period'])); ?> except as noted otherwise in the Remarks Column.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php
                    // $payroll = array_merge($payroll,$payroll);
                    // $payroll = array_merge($payroll,$payroll);
                    // $payroll = array_merge($payroll,$payroll);
                    // $payroll = array_merge($payroll,$payroll);
                    // $payroll = array_merge($payroll,$payroll);
                    // var_dump(sizeof($payroll));die();
                    $total_per_page = 20;
                    $total_next_page = 20;
                    $page_count = 1;
                    $grand_total = array(
                        "period_earned" => 0,
                        "tax_amt" => 0,
                        "net_pay" => 0
                    );
                    $multiplier1 = 1.5;
                    $multiplier2 = 1.25;
                    if($pay_basis != "Permanent" && $pay_basis != "Casual"){
                        $multiplier1 = 1;
                        $multiplier2 = 1.625;
                    }
                    $last_row = 0;
                    $count = 1;
                    $page = 0;
                    $total_page = floor(sizeof($payroll)/$total_per_page) + 1;
                    ?>
                    <div class="table-container">
                        <?php 
                            
                            $page_total = array(
                                "period_earned" => 0,
                                "tax_amt" => 0,
                                "net_pay" => 0
                            );
                        ?>
                        
                        <?php 
                        while(sizeof($payroll) > 0){
                            $page++; 
                        ?>
                            <?php 
                                $class = "page-break";
                                if($count == 1)
                                    $class = "";

                            ?>
                            <table style="width:100%" class="<?php echo $class; ?>">
                                <?php if($count > 1): ?>
                                <tr>
                                    <td colspan="100" style="text-align:right"><label>Page No.: <?php echo $page.' of '.$total_page; ?></label></td>
                                </tr>
                                <?php endif; ?>
                                <tr class="" style="border: 1px solid black;">
                                    <td style="text-align:center;" valign="middle" nowrap><b>No.</b></td>
                                    <td style="text-align:center;" valign="middle" nowrap><b>NAME OF EMPLOYEE</b></td>
                                    <td style="text-align:center;" valign="middle" nowrap><b>DESIGNATION</b></td>
                                    <td style="text-align:center;" valign="middle" nowrap><b>Monthly<br> Rate</b></td>
                                    <td style="text-align:center;" valign="middle" nowrap><b>No. of HRS<br> (<?php echo $multiplier1; ?>)</b></td>
                                    <td style="text-align:center;" valign="middle" nowrap><b>No. of Mins<br> (<?php echo $multiplier1; ?>)</b></td>
                                    <td style="text-align:center;" valign="middle" nowrap><b>No. of HRS<br> (<?php echo $multiplier2; ?>)</b></td>
                                    <td style="text-align:center;" valign="middle" nowrap><b>No. of Mins<br> (<?php echo $multiplier2; ?>)</b></td>
                                    <td style="text-align:center;" valign="middle" nowrap><b>EARNED FOR<br> THE PERIOD</b></td>
                                    <td style="text-align:center;" valign="middle" nowrap><b>W/HOLDING<br> TAX</b></td>
                                    <td style="text-align:center;" valign="middle" nowrap><b>NET PAY</b></td>
                                    <td style="text-align:center;" valign="middle" nowrap><b>SIGNATURE</b></td>
                                    <td style="text-align:center;" valign="middle" nowrap><b>REMARKS</b></td>
                                </tr>
                                <?php 
                                foreach ($payroll as $k => $v) {?>
                                    <tr style="height:40px;">
                                        <td nowrap="">
                                           <?php echo $count; ?> 
                                        </td>
                                        <td nowrap="" style="text-align:left;">
                                            <?php 
                                                echo $this->Helper->decrypt($v['last_name'],$v['employee_id'])
                                                .', '. $this->Helper->decrypt($v['first_name'],$v['employee_id'])   
                                                .' '. $this->Helper->decrypt($v['middle_name'],$v['employee_id']);
                                            ?>
                                        </td>
                                        <td nowrap="" style="text-align:center;">
                                            <?php echo $v['position_name']; ?>
                                        </td>
                                        <td nowrap style="text-align:right;">
                                            <?php echo number_format($v['salary'],2); ?>
                                        </td nowrap style="text-align:center;">
                                        <?php if($pay_basis != "Permanent" && $pay_basis != "Casual"){ ?>
                                        <td nowrap style="text-align:center;">
                                            <?php echo $v['hrs_15']; ?>
                                        </td>
                                        <td nowrap style="text-align:center;">
                                            <?php echo $v['mins_15']; ?>
                                        </td>
                                        <td nowrap style="text-align:center;">
                                            <?php echo $v['hrs_125']; ?>
                                        </td>
                                        <td nowrap style="text-align:center;">
                                            <?php echo $v['mins_125']; ?>
                                        </td>
                                        <?php } else { ?>
                                        <td nowrap style="text-align:center;">
                                            <?php echo $v['hrs_1']; ?>
                                        </td>
                                        <td nowrap style="text-align:center;">
                                            <?php echo $v['mins_1']; ?>
                                        </td>
                                        <td nowrap style="text-align:center;">
                                            <?php echo $v['hrs_1625']; ?>
                                        </td>
                                        <td nowrap style="text-align:center;">
                                            <?php echo $v['mins_1625']; ?>
                                        </td>
                                        <?php } ?>
                                        <td nowrap style="text-align:right;">
                                            <?php 
                                                echo number_format($v['period_earned'],2); 
                                                $page_total['period_earned'] += $v['period_earned'];
                                                $grand_total['period_earned'] += $v['period_earned'];
                                            ?>
                                        </td>
                                        <td nowrap style="text-align:right;">
                                            <?php 
                                                echo number_format($v['tax_amt'],2); 
                                                $page_total['tax_amt'] += $v['tax_amt'];
                                                $grand_total['tax_amt'] += $v['tax_amt'];
                                            ?>
                                        </td>
                                        <td nowrap style="text-align:right;">
                                            <?php 
                                                echo number_format($v['net_pay'],2); 
                                                $page_total['net_pay'] += $v['net_pay'];
                                                $grand_total['net_pay'] += $v['net_pay'];
                                            ?>
                                        </td>
                                        <td nowrap style="text-align:center;">
                                            <div style="width:100%;border-bottom:1px solid;">
                                            <?php 
                                            if($v['ot_percent'] != 100)
                                                echo $v['ot_percent'].'%';
                                            ?>
                                            </div>
                                        </td>
                                        <td nowrap style="text-align:center;">
                                            <?php echo $v['tax'].'%<br>-'; ?>
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
                                        <tr style="height:40px;" class="page_total">
                                            <td colspan="5" style="border-top: 1px solid;"></td>
                                            <td colspan="2" style="border-top: 1px solid;text-align:left;"><b>PAGE TOTAL:</b></td>
                                            <td style="border-top: 1px solid;border-bottom:1px solid;"></td>
                                            <td style="border-top: 1px solid;text-align:right;border-bottom:1px solid;"><?php echo number_format($page_total['period_earned'],2); ?></td>
                                            <td style="border-top: 1px solid;text-align:right;border-bottom:1px solid;"><?php echo number_format($page_total['tax_amt'],2); ?></td>
                                            <td style="border-top: 1px solid;text-align:right;border-bottom:1px solid;"><?php echo number_format($page_total['net_pay'],2); ?></td>
                                        </tr>
                                    <?php 
                                        $page_total = array(
                                            "period_earned" => 0,
                                            "tax_amt" => 0,
                                            "net_pay" => 0
                                        );
                                        break;
                                    }
                                     
                                } ?> 
                                <!-- Grand Total -->
                                <?php if(sizeof($payroll) == 0): ?>
                                    <tr style="height:40px;" class="grand_total">
                                        <td colspan="5" ></td>
                                        <td colspan="2" style="text-align:left;"><b>GRAND TOTAL:</b></td>
                                        <td></td>
                                        <td style="text-align:right;"><?php echo number_format($grand_total['period_earned'],2); ?></td>
                                        <td style="text-align:right;"><?php echo number_format($grand_total['tax_amt'],2); ?></td>
                                        <td style="text-align:right;"><?php echo number_format($grand_total['net_pay'],2); ?></td>
                                    </tr>
                                    <tr class="signatories">
                                        <td colspan="100">
                                            <?php
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
                                                            <br>&emsp; This is to certify that the names listed in this payroll are <?php echo $pay_basis; ?> personnel of this Authority. This is to certify further that the amount corresponding to the overtimes
                                                            <br>incurred without pay  during the current and or pay period are deducted accordingly.
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
                                                        <b><?php echo strtoupper(@$signatories_head[0]['signatory']); ?></b>
                                                        <br><?php echo ucfirst(@$signatories_head[0]['employee_id']); ?>
                                                    </td>
                                                    <td style="padding-left:7%;">

                                                        <b><?php echo strtoupper(@$signatory2); ?></b>
                                                        <br><?php echo ucfirst(@$signatory2_position); ?>
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
                                <?php 
                                    endif; ?>
                            </table>
                        <?php } ?>

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
