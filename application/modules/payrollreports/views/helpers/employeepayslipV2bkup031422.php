
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
                                <td colspan="2" style="text-align: center;font-weight: bold;font-size:15px">PAYROLL PAYMENT SLIP</td>
                            </tr>
                            <tr>
                                <td style="width: 20%;text-align: right;">Office/Division:</td>
                                <td><?php echo @$payroll[0]['department_name']; ?></td>
                            </tr>
                            <tr>
                                <td style="width: 20%;text-align: right;border-bottom: 1px solid">Pay Period:</td>
                                <td style="border-bottom: 1px solid">
                                    <?php 
                                        $from =  date("F d, Y", strtotime(@$payroll_period[0]['start_date']));
                                        $to = date("F d, Y", strtotime(@$payroll_period[0]['end_date']));;
                                    ?>
                                    <b><?php echo $from ?></b> to <b><?php echo $to; ?></b>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 20%;text-align: right;">Employee Name:</td>
                                <td><?php echo strtoupper(@$payroll[0]['last_name']) . ", " . strtoupper(@$payroll[0]['first_name']) . ' ' . strtoupper(@$payroll[0]['middle_name']); ?></td>
                            </tr>
                            <tr>
                                <td style="width: 20%;text-align: right;vertical-align: top;">Position:</td>
                                <td><?php echo strtoupper(@$payroll[0]['position_name']); ?></td>
                            </tr>
                            <tr>
                                <td style="width: 20%;text-align: right;">Basic Salary:</td>
                                <td><?php echo number_format(@$payroll[0]['salary'],2); ?></td>
                            </tr>
                            <tr>
                                <td style="width: 20%;text-align: right;">Employee No:</td>
                                <td><?php echo strtoupper(@$payroll[0]['employee_id_number']); ?></td>
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
                        <table  style="width: 100%" >
                            <tr>
                                <td style="text-align: center;font-weight: bold;font-style: italic;width: 80%">*** Earnings ***</td>
                                <td style="text-align: center;font-weight: bold;font-style: italic;">Monthly</td>
                            </tr>
                            <tr>
                                <td style="text-align: right;">7th of the month....</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format($cutoff_1,2); ?></td>
                            </tr>
                            <tr>
                                <td style="text-align: right;">15th of the month....</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format($cutoff_2,2); ?></td>
                            </tr>
                            <tr>
                                <td style="text-align: right;">22nd of the month....</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format($cutoff_3,2); ?></td>
                            </tr>
                            <tr>
                                <td style="text-align: right;">End of the month....</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format($cutoff_4,2); ?></td>
                            </tr>
                            <tr>
                                <td>Representation & Transportation Allowance (RATA)</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format((double)@$payroll[0]['rep_allowance'] + (double)@$payroll[0]['transpo_allowance'],2); ?></td>
                            </tr>
                            <tr>
                                <td>Additional Compensation</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format(0,2); ?></td>
                            </tr>
                            <tr>
                                <td>Personnel Economic Relief Allowance (PERA)</td>
                                <td style="border-bottom: 1px solid;text-align: right;font-weight: bold; padding-right: 10px;"><?php echo number_format((double)@$payroll[0]['pera_amt'],2); ?></td>
                            </tr>
                            <tr>
                                <td>Other Earnings Entry</td>
                                <td style="border-bottom: 1px solid;text-align: right;font-weight: bold; padding-right: 10px;"><?php echo number_format((double)@$payroll[0]['total_other_earning_amt'],2); ?></td>
                            </tr>
                            <tr>
                                <td>Other Benefits Entry</td>
                                <td style="border-bottom: 1px solid;text-align: right;font-weight: bold; padding-right: 10px;"><?php echo number_format((double)@$payroll[0]['total_other_benefit_amt'],2); ?></td>
                            </tr>
                            <tr>
                                <td style="text-align: center;font-weight: bold;font-style: italic">*** Net Pay ***</td>
                                <td style="text-align: right;font-weight: bold; padding-right: 10px;"><?php echo number_format((double)@$payroll[0]['net_pay'],2); ?></td>
                            </tr>
                            <tr>
                                <td style="text-align: center;font-weight: bold;font-style: italic">*** Deductions ***</td>
                                <td style="text-align: center;font-weight: bold;"></td>
                            </tr>
                            <tr>
                                <td>Other Deductions Entry</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format((double)@$payroll[0]['total_other_deduct_amt'],2); ?></td>
                            </tr>
                            <tr>
                                <td>GSIS Contribution</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format((double)@$payroll[0]['sss_gsis_amt'],2); ?></td>
                            </tr>
                            <tr>
                                <td>BIR Withholding TAX</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format((double)@$payroll[0]['wh_tax_amt'],2); ?></td>
                            </tr>
                            <tr>
                                <td>PHILHEALTH Contribution</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format((double)@$payroll[0]['philhealth_amt'],2); ?></td>
                            </tr>
                            <!-- <tr>
                                <td>Union Dues</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format((double)$payroll[0]['union_dues_amt'],2); ?></td>
                            </tr> -->
                            <tr>
                                <td>HDMF (Pag-ibig) Contribution</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format((double)@$payroll[0]['pagibig_amt'],2); ?></td>
                            </tr>
                            <tr>
                                <td>SSS Contribution</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format((double)@$payroll[0]['sss_amt'],2); ?></td>
                            </tr>
                            <!-- <tr>
                                <td>ACPCEA</td>
                                <td style="text-align: right; padding-right: 10px;"><?php //echo number_format((double)@$payroll[0]['acpcea_amt'],2); ?></td>
                            </tr> -->
                            <tr>
                                <td>PERA WOP</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format((double)@$payroll[0]['pera_wop_amt'],2); ?></td>
                            </tr>
                            <!-- <tr>
                                <td>UNION Dues</td>
                                <td style="text-align: right; padding-right: 10px;"><?php //echo number_format((double)@$payroll[0]['union_dues_amt'],2); ?></td>
                            </tr> -->
                            <!-- <tr>
                                <td>Damayan</td>
                                <td style="text-align: right; padding-right: 10px;"><?php //echo number_format((double)@$payroll[0]['damayan_amt'],2); ?></td>
                            </tr> -->
                            <!-- <tr>
                                <td>Sakamay</td>
                                <td style="text-align: right; padding-right: 10px;"><?php //echo number_format((double)@$payroll[0]['_amt'],2); ?></td>
                            </tr> -->
                            <tr>
                                <td>PSMBFund</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format((double)@$payroll[0]['psmbfund_amt'],2); ?></td>
                            </tr>
                            <!-- <tr>
                                <td>KOOP</td>
                                <td style="text-align: right; padding-right: 10px;"><?php //echo number_format((double)@$payroll[0]['koop_amt'],2); ?></td>
                            </tr> -->
                            <!-- <tr>
                                <td>Provident</td>
                                <td style="text-align: right; padding-right: 10px;"><?php //echo number_format((double)@$payroll[0]['provident_amt'],2); ?></td>
                            </tr> -->
                            <tr>
                                <td>Optional Unlimited (Additional Policy)</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format(0,2); ?></td>
                            </tr>
                            <tr>
                                <td>C E A P</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format(0,2); ?></td>
                            </tr>
                            <tr>
                                <td>Hospitalization</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format(0,2); ?></td>
                            </tr>
                            <tr>
                                <td>GSIS - Consilidated Salary Loan</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format(((get_key(2,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(2,$loanDeductions)]["amount"]),2); ?></td>
                            </tr>
                            <tr>
                                <td>     - Cash Advance Loan</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format(((get_key(15,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(15,$loanDeductions)]["amount"]),2); ?></td>
                            </tr>
                            <tr>
                                <td>     - Multi - Purpose</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format(((get_key(5,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(5,$loanDeductions)]["amount"]),2); ?></td>
                            </tr>
                            <tr>
                                <td>     - Educational</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format(((get_key(8,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(8,$loanDeductions)]["amount"]),2); ?></td>
                            </tr>
                            <tr>
                                <td>     - ELA Loan</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo "";//number_format(((get_key(2,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(22,$loanDeductions)]["amount"]),2); ?></td>
                            </tr>
                            <tr>
                                <td>     - Emergency</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format(((get_key(4,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(4,$loanDeductions)]["amount"]),2); ?></td>
                            </tr>
                            <tr>
                                <td>     - Enhanced</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format(((get_key(3,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(3,$loanDeductions)]["amount"]),2); ?></td>
                            </tr>
                            <tr>
                                <td>     - Housing</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format(((get_key(7,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(7,$loanDeductions)]["amount"]),2); ?></td>
                            </tr>
                            <!-- <tr>
                                <td>     - Lost TVR</td>
                                <td style="text-align: right; padding-right: 10px;"><?php //echo number_format(((get_key(14,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(14,$loanDeductions)]["amount"]),2); ?></td>
                            </tr> -->
                            <tr>
                                <td>     - Old GSIS</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format(((get_key(6,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(6,$loanDeductions)]["amount"]),2); ?></td>
                            </tr>
                            <tr>
                                <td>     - Optional Ins.</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format(((get_key(18,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(18,$loanDeductions)]["amount"]),2); ?></td>
                            </tr>
                            <!-- <tr>
                                <td>     - Other Loans</td>
                                <td style="text-align: right; padding-right: 10px;">
                                <?php //echo number_format(((get_key(2,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(17,$loanDeductions)]["amount"]),2); ?>
                                </td>
                            </tr> -->
                            <tr>
                                <td>     - Optional Policy Loan</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format(((get_key(16,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(16,$loanDeductions)]["amount"]),2); ?></td>
                            </tr>
                            <tr>
                                <td>     - Policy Loan</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format(((get_key(24,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(24,$loanDeductions)]["amount"]),2); ?></td>
                            </tr>
                            <tr>
                                <td>     - SOS Loan</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo "";//number_format(((get_key(2,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(22,$loanDeductions)]["amount"]),2); ?></td>
                            </tr>
                            <tr>
                                <td>HDMF (Pag-ibig) Loan</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>     - Calamity</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format(((get_key(22,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(22,$loanDeductions)]["amount"]),2); ?></td>
                            </tr>
                            <tr>
                                <td>     - Housing</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format(((get_key(20,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(20,$loanDeductions)]["amount"]),2); ?></td>
                            </tr>
                            <tr>
                                <td>     - Multi - Purpose</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format(((get_key(21,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(21,$loanDeductions)]["amount"]),2); ?></td>
                            </tr>
                            <tr>
                                <td>     - M.P. II</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format(((get_key(34,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(34,$loanDeductions)]["amount"]),2); ?></td>
                            </tr>
                            <tr>
                                <td>     - M.P. III</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format(((get_key(37,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(37,$loanDeductions)]["amount"]),2); ?></td>
                            </tr>
                            <tr>
                                <td>     - Computer</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format(((get_key(36,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(36,$loanDeductions)]["amount"]),2); ?></td>
                            </tr>
                            <tr>
                                <td>Other Loans</td>
                                <td style="text-align: right;"></td>
                            </tr>
                            <!-- <tr>
                                <td>     - Dagliang</td>
                                <td style="text-align: right; padding-right: 10px;"><?php //echo number_format(((get_key(33,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(33,$loanDeductions)]["amount"]),2); ?></td>
                            </tr> -->
                            <!-- <tr>
                                <td>     - Grocery Loan</td>
                                <td style="text-align: right; padding-right: 10px;"><?php //echo number_format(((get_key(30,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(30,$loanDeductions)]["amount"]),2); ?></td>
                            </tr> -->
                            <tr>
                                <td>     - Home Mortgage Finance Corp.</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format(((get_key(31,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(31,$loanDeductions)]["amount"]),2); ?></td>
                            </tr>
                            <!-- <tr>
                                <td>     - KOOP Loan</td>
                                <td style="text-align: right; padding-right: 10px;"><?php //echo number_format(((get_key(29,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(29,$loanDeductions)]["amount"]),2); ?></td>
                            </tr> -->
                            <!-- <tr>
                                <td>     - Provident</td>
                                <td style="text-align: right; padding-right: 10px;"><?php //echo number_format(((get_key(32,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(32,$loanDeductions)]["amount"]),2); ?></td>
                            </tr> -->
                            <tr>
                                <td>     - PSMBFund</td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format(((get_key(28,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(28,$loanDeductions)]["amount"]),2); ?></td>
                            </tr>
                            <!-- <tr>
                                <td>     - Sakamay</td>
                                <td style="text-align: right; padding-right: 10px;"><?php //echo number_format(((get_key(27,$loanDeductions)===false)?0:(double)$loanDeductions[get_key(27,$loanDeductions)]["amount"]),2); ?></td>
                            </tr> -->
                            <?php 
                                $payroll[0]['total_deduct_amt'] = @$payroll[0]['total_deduct_amt'] < 0 ? 0 : @$payroll[0]['total_deduct_amt'];
                                $payroll[0]['total_tardiness_amt'] = @$payroll[0]['total_tardiness_amt'] < 0 ? 0 : @$payroll[0]['total_tardiness_amt'];
                                $payroll[0]['pera_wop_amt'] = @$payroll[0]['pera_wop_amt'] < 0 ? 0 : @$payroll[0]['pera_wop_amt'];
                            ?>
                            <tr>
                                <td style="text-align: right;font-weight: bold;">Total Deductions</td>
                                <td style="text-align: right;font-weight: bold;border-top: 1px solid black;"><?php echo number_format((double)(@$payroll[0]['total_deduct_amt'] +@$payroll[0]['total_tardiness_amt'] + @$payroll[0]['pera_wop_amt']),2); ?></td>
                            </tr>
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