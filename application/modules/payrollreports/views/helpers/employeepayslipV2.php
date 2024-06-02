
<?php if($key != "viewPayslipSummary"):
?>
<div id="employee-payslips">
    <div class="row">
        <div class="col-md-12">
            <style type="text/css" media="all">
                .certificate-container{
                    width:415px  !important;
                    max-width:415px;
                    margin-left: 1em;
                    border: 1px solid black;
                }
            </style>
            <div style="width:100%; overflow-x:auto;">
                <div id="clearance-div">
                    <style>
                        @media print{
                            @page {
                                size: landscape;
                                margin: 3mm 3mm 3mm 3mm;
                            }
                            body{
                                font-family:'Times New Roman';
                                font-size:11px;
                            }
                            table tr td{
                                font-family:'Times New Roman';
                                font-size:11px;
                            } 
                            .certificate-container{
                                page-break-inside: avoid;
                                border: 1px solid black;
                            }
                        }
                        @media screen and (min-width: 961px){
                            #certificate-container{
                                padding:0px; 
                            }
                        }
                        @media screen and (max-width: 960px){
                            #certificate-container{
                                padding:0px; 
                            }
                        }
                        #certificate-container{
                            font-size:11px;
                            font-family:'Times New Roman';
                            width:100%;
                            text-align: justify;
                            text-justify: inter-word;
                        }
                        #certificate-container div{
                            display: inline-block;
                        }
                        #certificate-container table{
                            width:415px  !important;
                            border-collapse: collapse;
                        }
                        #certificate-container table tr td{
                            padding-left:2px;
                            padding-right: 2px;
                        }
                    </style>
                    <div id="certificate-container">
    <?php endif; ?>
                    <?php if(($key == "viewPayslipSummary" && sizeof($payroll) > 0) || ($key == "viewPayslip" && sizeof($payroll) > 0)): ?>
                    <div class="certificate-container" style="margin-top:8px;">
                        <table  style="width: 100%">
                            <tr>
                                <td colspan="2" style="text-align: center;font-weight: bold;font-size:15px">&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: center;font-weight: bold;font-size:15px"><img style="margin-top: -15px !important;" src="<?php echo base_url().'/assets/custom/images/nwrb.png'; ?>" width="50" alt="">NATIONAL WATER RESOURCES BOARD</td>
                            </tr>
                             <tr>
                                <td colspan="2" style="text-align: center;font-weight: bold;font-size:15px">PAYMENT SLIP</td>
                            </tr>
                        </table>
                        <?php
                        $cutoff_1 = $cutoff_2 = $cutoff_3 = $cutoff_4 = 0;
                        // if($payroll[0]['cutoff_3'] == 0 && $payroll[0]['cutoff_3'] == 0){
                        //     $cutoff_2 = (double)@$payroll[0]['cut1'];
                        //     $cutoff_4 = (double)@$payroll[0]['cut2'];
                        // }else{
                        //     $cutoff_1 = (double)@$payroll[0]['cut1'];
                        //     $cutoff_2 = (double)@$payroll[0]['cut2'];
                        //     $cutoff_3 = (double)@$payroll[0]['cut3'];
                        //     $cutoff_4 = (double)@$payroll[0]['cut4'];
                        // }

                        if($payroll[0]['cutoff_3'] == 0 && $payroll[0]['cutoff_3'] == 0){
                            $cutoff_2 = (double)@$payroll[0]['cutoff_1'];
                            $cutoff_4 = (double)@$payroll[0]['cutoff_2'];
                        }else{
                            $cutoff_1 = (double)@$payroll[0]['cutoff_1'];
                            $cutoff_2 = (double)@$payroll[0]['cutoff_2'];
                            $cutoff_3 = (double)@$payroll[0]['cutoff_3'];
                            $cutoff_4 = (double)@$payroll[0]['cutoff_4'];
                        }
                        ?>
                        <?php 
                        $total = 0;
                        $total2 = 0;
                        ?>
                        <table  style="width: 100%" >
                            <tr>
                                <td style="text-align: center;font-weight: bold;font-style: italic;width: 80%">&nbsp;</td>
                                <td style="text-align: center;font-weight: bold;font-style: italic;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>For the month of:&emsp;<b><?php echo date("F Y", strtotime(@$payroll_period[0]['start_date']))?></b></td>
                            </tr>
                            <tr>
                                <td>Employees Name:&emsp;<b><?php echo strtoupper(@$payroll[0]['last_name']) . ", " . strtoupper(@$payroll[0]['first_name']) . ' ' . strtoupper(@$payroll[0]['middle_name']); ?></b></td>
                            </tr>
                            <tr>
                                <td>ATM SA No.:</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo "00.0"; ?></td>
                            </tr>
                            <tr>
                                <td>Monthly Salary:</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format(@$payroll[0]['salary'],2); ?></td>
                                <?php $total = $payroll[0]['salary']; ?>
                            </tr>
                            <tr>
                                <td>ACA + PERA</td>
                                <td style="border-bottom: 1px solid;text-align: right; padding-right: 10px;"><?php echo number_format((double)@$payroll[0]['pera_amt'],2); ?></td>
                                <?php $total += $payroll[0]['pera_amt']; ?>
                            </tr>
                            <tr>
                                <td><b>Gross Pay</b></td>
                                <td style="text-align: right; padding-right: 10px;font-weight: bold"><?php echo number_format($total,2) ; ?></td>
                            </tr>
                            <tr>
                                <td style="text-align: center;font-weight: bold;font-style: italic;width: 80%">&nbsp;</td>
                                <td style="text-align: center;font-weight: bold;font-style: italic;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>Other Deductions:</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format((double)@$payroll[0]['total_other_deduct_amt'],2); ?></td>
                                <?php $total2 += $payroll[0]['total_other_deduct_amt']; ?>
                            </tr>
                            <tr>
                                <td>Life & Retirement Prem</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format((double)@$payroll[0]['sss_gsis_amt'],2); ?></td>
                                <?php $total2 += $payroll[0]['sss_gsis_amt']; ?>
                            </tr>
                            <tr>
                                <td>Salary Loan</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format(((get_key(15,$loanDeductions)===false)? "0.00" :(double)$loanDeductions[get_key(15,$loanDeductions)]["amount"]),2); ?></td>
                                <?php $total2 += ((get_key(15,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(15,$loanDeductions)]["amount"]); ?>
                            </tr>
                            <tr>
                                <td>Calamity Loan</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format(((get_key(22,$loanDeductions)===false)? "0.00" :(double)$loanDeductions[get_key(22,$loanDeductions)]["amount"]),2); ?></td>
                                <?php $total2 += ((get_key(22,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(22,$loanDeductions)]["amount"]); ?>

                            </tr>
                            <tr>
                                <td>Policy Loan</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format(((get_key(24,$loanDeductions)===false)? "0.00" :(double)$loanDeductions[get_key(24,$loanDeductions)]["amount"]),2); ?></td>
                                <?php $total2 += ((get_key(24,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(24,$loanDeductions)]["amount"]); ?>
                            </tr>
                            <tr>
                                <td>Optional Insurance</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format(((get_key(18,$loanDeductions)===false)? "0.00" :(double)$loanDeductions[get_key(18,$loanDeductions)]["amount"]),2); ?></td>
                                <?php $total2 += ((get_key(18,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(18,$loanDeductions)]["amount"]); ?>
                            </tr>
                            <tr>
                                <td>LCHL</td>
                                <td style="text-align: right; padding-right: 10px;"><?php 
                                 echo (get_key(7,$loanDeductions)===false && get_key(20,$loanDeductions)===false) ?  "0.00"  : (double)$loanDeductions[get_key(7,$loanDeductions)]["amount"] + (double)$loanDeductions[get_key(20,$loanDeductions)]["amount"];
                                 ?></td>
                                <?php $total2 += (get_key(7,$loanDeductions)===false && get_key(20,$loanDeductions)===false) ?  "0.00"  : (double)$loanDeductions[get_key(7,$loanDeductions)]["amount"] + (double)$loanDeductions[get_key(20,$loanDeductions)]["amount"]; ?>
                            </tr>
                            <tr>
                                <td>Optional Policy Insurance</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format(((get_key(16,$loanDeductions)===false)? "0.00" :(double)$loanDeductions[get_key(16,$loanDeductions)]["amount"]),2); ?></td>
                                <?php $total2 += ((get_key(16,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(16,$loanDeductions)]["amount"]); ?>
                            </tr>
                            <tr>
                                <td>MPL</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format(((get_key(5,$loanDeductions)===false)? "0.00" :(double)$loanDeductions[get_key(5,$loanDeductions)]["amount"]),2); ?></td>
                                <?php $total2 += ((get_key(5,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(5,$loanDeductions)]["amount"]); ?>
                            </tr>
                            <tr>
                                <td>SOS</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format(((get_key(2,$loanDeductions)===false)? "0.00" :(double)$loanDeductions[get_key(22,$loanDeductions)]["amount"]),2); ?></td>
                                <?php $total2 += ((get_key(2,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(22,$loanDeductions)]["amount"]); ?>
                            </tr>
                            <tr>
                                <td>LBP</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format(((get_key(39,$loanDeductions)===false)? "0.00" :(double)$loanDeductions[get_key(39,$loanDeductions)]["amount"]),2); ?></td>
                                <?php $total2 += ((get_key(39,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(39,$loanDeductions)]["amount"]); ?>
                            </tr>
                            <tr>
                                <td>MP2</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format((double)@$payroll[0]['mp2_contribution'],2); ?></td>
                                <?php $total2 += $payroll[0]['mp2_contribution']; ?>
                            </tr>
                            <tr>
                                <td>With Holding Tax</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format((double)@$payroll[0]['wh_tax_amt'],2); ?></td>
                                <?php $total2 += $payroll[0]['wh_tax_amt']; ?>
                            </tr>
                            <tr>
                                <td>Pag-ibig Contribution</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format((double)@$payroll[0]['pagibig_amt'],2); ?></td>
                                <?php $total2 += $payroll[0]['pagibig_amt']; ?>
                            </tr>
                            <tr>
                                <td>PHILHEALTH</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format((double)@$payroll[0]['philhealth_amt'],2); ?></td>
                                <?php $total2 += $payroll[0]['philhealth_amt']; ?>
                            </tr>
                            <tr>
                                <td>NWRB Contribution</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format((double)@$payroll[0]['union_dues_amt'],2); ?></td>
                                <?php $total2 += $payroll[0]['union_dues_amt']; ?>
                            </tr>
                            <tr>
                                <td>NWRB Loans</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo "00.0"; ?></td>
                            </tr>
                            <tr>
                                <td>Lates & Undertimes</td>
                                <td style="border-bottom: 1px solid;text-align: right; padding-right: 10px;"><?php echo number_format((double)@$payroll[0]['total_tardiness_amt'],2); ?></td>
                                <?php $total2 += $payroll[0]['total_tardiness_amt']; ?>
                            </tr>
                            <tr>
                                <td><b>TOTAL DEDUCTIONS</b></td>
                                <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($total2,2); ?></b></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                             <?php
                            $net_pay = $total - $total2;
                            $net_pay2 = $net_pay/2 ;
                             ?>
                            <tr>
                                <td>Net Pay 15th</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format($net_pay2,2);; ?></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>Net Pay 30th</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format($net_pay2,2);; ?></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td style="border-bottom: 1px solid;text-align: right; padding-right: 10px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td><b>Total Net Pay</b></td>
                                <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($net_pay,2); ?></b></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                           <tr>
                                <td colspan="2" style="text-align: center;font-weight: bold;font-size:15px;">AVECITA O. GARCIA</td>
                            </tr>
                             <tr>
                                <td colspan="2" style="text-align: center;font-size:15px;">Cashier III</td>
                            </tr>
                          
                            <?php 
                                // $payroll[0]['total_deduct_amt'] = @$payroll[0]['total_deduct_amt'] < 0 ? 0 : @$payroll[0]['total_deduct_amt'];
                                // $payroll[0]['total_tardiness_amt'] = @$payroll[0]['total_tardiness_amt'] < 0 ? 0 : @$payroll[0]['total_tardiness_amt'];
                                // $payroll[0]['pera_wop_amt'] = @$payroll[0]['pera_wop_amt'] < 0 ? 0 : @$payroll[0]['pera_wop_amt'];
                            ?>
                            <!-- <tr>
                                <td style="text-align: right;font-weight: bold;">Total Deductions</td>
                                <td style="text-align: right;font-weight: bold;border-top: 1px solid black;"><?php echo number_format((double)(@$payroll[0]['total_deduct_amt'] +@$payroll[0]['total_tardiness_amt'] + @$payroll[0]['pera_wop_amt']),2); ?></td>
                            </tr> -->
                        </table>
                    </div>
                    <?php endif; ?>
    <?php if($key != "viewPayslipSummary"): ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 text-right">
            <button id="printClearance" class="btn bg-green btn-fab btn-fab-mini">Print <i class = "material-icons">print</i></button>
        </div>
    </div>
</div>
<?php endif; ?>

<?php

function get_key($id,$arr){
    $key = array_search($id,array_column($arr,"sub_loans_id"));
    return ($key === false)?false:$key;
}


?>
