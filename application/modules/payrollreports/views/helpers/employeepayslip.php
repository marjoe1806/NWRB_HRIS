
<?php if($key != "viewPayslipSummary"):
?>
<div id="employee-payslips">
    <div class="row">
        <div class="col-md-12">
            <style type="text/css">
            </style>
            <div style="width:100%; overflow-x:auto;">
                <div id="clearance-div">
                    <style>
                        @media print{
                            @page {
								size: portrait;
								margin: 3mm 3mm 3mm 3mm;
							}
                            body{
                                font-family:'Times New Roman';
                                font-size:11px;
                            } 
							
                            table tr td{
                                font-family:'Times New Roman';
                                font-size:11px;
                                padding-top: -1px;

                            } 
							.certificate-container{
								page-break-inside: avoid;
							}
                        }
                        @media screen and (min-width: 961px){
                            #certificate-container{
                                padding:2px 2px 2px 2px; 
                            }
                        }
                        @media screen and (max-width: 960px){
                            #certificate-container{
                                padding:2px 2px 2px 2px; 
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
                        #certificate-container .fixdiv{

                            border-bottom:1px groove gray;
                        }
                        #certificate-container #mainTable {
                            border-collapse: collapse;
                            border: groove 1px #3b3b3b;
                            

                        }
                        #certificate-container table{
                            width:100%;
                            border-collapse: collapse;
                        }
                        #certificate-container table tr td{
                            padding-left:2px;
                            padding-right: 2px;
                        }
                    </style>
                    <div id="certificate-container">
    <?php endif; ?>
                    <?php if($key == "viewPayslipSummary" || $key == "viewPayslip"): ?>
					<div class="certificate-container" style="margin-top:8px;">
                        <div style="width:100%;border-top:groove 1px #3b3b3b;"> </div>
                        <table style="width:100%;">
                            <tr>
                                <td nowrap style="width:25%;"><b>AS-PBD-013</b></td>
                                <td nowrap style="width:50%;text-align:center;">NATIONAL WATER RESOURCES BOARD</td>
                                <td nowrap></td>
                            </tr>
                            <tr>
                                <td nowrap style="width:25%;"></td>
                                <td nowrap style="width:50%;text-align:center;">Employee Payslip Permanent</td>
                                <td nowrap></td>
                            </tr>
                            <tr>
                                <td nowrap style="width:25%;"></td>
                                <td nowrap style="width:50%;text-align:center;">
                                    <b><?php echo @$payroll[0]['department_name'].' Department'; ?></b>
                                </td>
                                <td nowrap></td>
                            </tr>
                            <tr>
                                <td nowrap style="width:25%;border-bottom:1px groove #3b3b3b;"></td>
                                <td nowrap style="width:50%;border-bottom:1px groove #3b3b3b;text-align:center;">
                                    <?php 
                                        $from =  date("F d, Y", strtotime(@$payroll_period[0]['start_date']));
                                        $to = date("F d, Y", strtotime(@$payroll_period[0]['end_date']));;
                                    ?>
                                    Payroll Period <b><?php echo $from ?></b>&emsp;&emsp;&emsp;&emsp; to <b><?php echo $to; ?></b>
                                </td>
                                <td nowrap style="border-bottom:1px groove #3b3b3b;"></td>
                            </tr>
                        </table>
                        <table style="width:100%;">
                            <tr>
                                <td nowrap style="width:5%;">Employee Name:</td>
                                <td nowrap style="text-align:left;width:100px;"><b><?php echo strtoupper(@$payroll[0]['last_name']); ?></b></td>
                                <td nowrap><b><?php echo strtoupper(@$payroll[0]['first_name']); ?></b></td>
                                <td nowrap><b><?php echo strtoupper(@$payroll[0]['middle_name']); ?></b></td>
                                <td nowrap>Net Pay</td>
                                <td nowrap style="text-align:right;"><b><?php echo number_format(@$payroll[0]['net_pay'],2); ?></b></td>
                            </tr>
                            <tr>
                                <td nowrap>Position&emsp;&emsp;&emsp;:</td>
                                <td nowrap style="text-align:left;"><?php echo strtoupper(@$payroll[0]['position_name']); ?></td>
                                <td nowrap></td>
                                <td nowrap></td>
                                <td nowrap></td>
                                <td nowrap style="text-align:right;"></td>
                            </tr>
                            <tr>
                                <td style="border-bottom:1px groove #3b3b3b;" nowrap>Exemption Code:</td>
                                <td style="border-bottom:1px groove #3b3b3b;" nowrap style="text-align:left;">ME</td>
                                <td style="border-bottom:1px groove #3b3b3b;" nowrap></td>
                                <td style="border-bottom:1px groove #3b3b3b;" nowrap></td>
                                <td style="border-bottom:1px groove #3b3b3b;" nowrap></td>
                                <td style="border-bottom:1px groove #3b3b3b;" nowrap style="text-align:right;"></td>
                            </tr>
                        </table>
                        <table style="width:100%;">
                        <!--     <tr>
                                <td nowrap><b>PBD INCOME</b></td>
                                <td nowrap></td>
                                <td nowrap>GSIS LOANS:</td>
                                <td nowrap colspan="3">Remaining Months to Pay</td>
                                <td nowrap colspan="2" style="text-align:right;">(RMtP)</td>
                                <td nowrap colspan="5"></td>
                            </tr> -->
                            <tr>
                                <td nowrap><b>*** Earnings ***</b></td>
                                <td nowrap><b>Monthly</b></td>
                                <td nowrap>GSIS LOANS:</td>
                                <td nowrap colspan="3">Remaining Months to Pay</td>
                                <td nowrap colspan="2" style="text-align:right;">(RMtP)</td>
                                <td nowrap colspan="5"></td>
                            </tr>
                            <tr>
                                <td nowrap style="text-align:right;">7th of the month ...</td>
                                <td nowrap style="text-align:right;"><?php echo number_format((double)@$payroll[0]['cutoff_2'],2); ?></td>
                                <td nowrap>GSIS P/Share</td>
                                <td nowrap="" style="text-align:right;border-right:1px groove #3b3b3b;"><?php echo number_format(@$payroll[0]['sss_gsis_amt'],2); ?></td>
                                <td nowrap="" style="width:15px;text-align:right;border-right:1px groove #3b3b3b;"></td>
                                <td nowrap=""><b>PAG-IBIG LOANS</b>:</td>
                                <td nowrap="" style="text-align:right;border-right:1px groove #3b3b3b;"></td>
                                <td style="width:15px;text-align:right;border-right:1px groove #3b3b3b;"></td>
                                <td nowrap="">Optional Loan</td>
                                <td nowrap="" style="text-align:right;border-right:1px groove #3b3b3b;">
                                <?php 
                                $loan = "0.00";
                                $optional_rmtp = 0;
                                if(sizeof($loanDeductions) > 0){
                                    foreach ($loanDeductions as $k => $v) {
                                        if($v['code_loan'] == "GSIS" && $v['code_sub'] == "Optional Loan"){
                                            $loan = number_format($v['amount'],2);
                                            $maturity_date = date_create(date('Y-m-01',strtotime(@$v['maturity_date'])));
                                            $payroll_date = date_create(date('Y-m-01',strtotime(@$payroll_period[0]['start_date'])));
                                            $date_diff = date_diff($payroll_date,$maturity_date);
                                            $optional_rmtp = $date_diff->format("%m");
                                            break;
                                        }
                                        else
                                            $loan = "0.00";

                                    }
                                }
                                echo $loan;
                                ?>
                                </td>
                                <td nowrap="" style="width:15px;border-right:1px groove #3b3b3b;text-align:center"><?php echo $optional_rmtp; ?></td>
                               
                            </tr>
                            <tr>
                                <td nowrap style="text-align:right;">15th of the month ...</td>
                                <td nowrap style="text-align:right;"><?php echo number_format((double)@$payroll[0]['cutoff_2'],2); ?></td>
                                <td nowrap>Consolidated -</td>
                                <td nowrap="" style="text-align:right;border-right:1px groove #3b3b3b;">
                                <?php 
                                $loan = "0.00";
								$consolidated_rmtp = 0;
                                if(sizeof($loanDeductions) > 0){
                                    foreach ($loanDeductions as $k => $v) {
                                        if($v['code_loan'] == "GSIS" && $v['code_sub'] == "Consolidated"){
                                            $loan = number_format($v['amount'],2);
											$maturity_date = date_create(date('Y-m-01',strtotime(@$v['maturity_date'])));
											$payroll_date = date_create(date('Y-m-01',strtotime(@$payroll_period[0]['start_date'])));
											$date_diff = date_diff($payroll_date,$maturity_date);
											$consolidated_rmtp = $date_diff->format("%m");
                                            break;
                                        }
                                        else
                                            $loan = "0.00";

                                    }
                                }
                                echo $loan;
                                ?>
                                </td>
                                <td nowrap="" style="width:15px;text-align:center;border-right:1px groove #3b3b3b;"><?php echo $consolidated_rmtp; ?></td>
                                <td nowrap="">PAGIBIG/PShare</td>
                                <td nowrap="" style="text-align:right;border-right:1px groove #3b3b3b;"><?php echo number_format((double)@$payroll[0]['pagibig_amt'],2); ?></td>
                                <td style="width:15px;text-align:center;border-right:1px groove #3b3b3b;"></td>
                                <td nowrap="">Other Loans</td>
                                <td nowrap="" style="text-align:right;border-right:1px groove #3b3b3b;">
                                <?php 
                                $loan = "0.00";
                                $other_rmtp = 0;
                                if(sizeof($loanDeductions) > 0){
                                    foreach ($loanDeductions as $k => $v) {
                                        if($v['code_loan'] == "GSIS" && $v['code_sub'] == "Other Loans"){
                                            $loan = number_format($v['amount'],2);
                                            $maturity_date = date_create(date('Y-m-01',strtotime(@$v['maturity_date'])));
                                            $payroll_date = date_create(date('Y-m-01',strtotime(@$payroll_period[0]['start_date'])));
                                            $date_diff = date_diff($payroll_date,$maturity_date);
                                            $other_rmtp = $date_diff->format("%m");
                                            break;
                                        }
                                        else
                                            $loan = "0.00";

                                    }
                                }
                                echo $loan;
                                ?>
                                </td>
                                <td nowrap="" style="width:15px;border-right:1px groove #3b3b3b;text-align:center"><?php echo $other_rmtp; ?></td>
                                
                            </tr>
                            <tr>
                                <td nowrap style="text-align:right;">22nd of the month ...</td>
                               <td nowrap style="text-align:right;"><?php echo number_format((double)@$payroll[0]['cutoff_3'],2); ?></td>
                                <!-- <td nowrap style="text-align:right;border-bottom:1px groove #3b3b3b;">0.00</td> -->
                                <td nowrap>Enhanced -</td>
                                <td nowrap="" style="text-align:right;border-right:1px groove #3b3b3b;">
                                <?php 
                                $loan = "0.00";
								$enhanced_rmtp = "0";
                                if(sizeof($loanDeductions) > 0){
                                    foreach ($loanDeductions as $k => $v) {
                                        if($v['code_loan'] == "GSIS" && $v['code_sub'] == "Enhanced"){
                                            $loan = number_format($v['amount'],2);
											$maturity_date = date_create(date('Y-m-01',strtotime(@$v['maturity_date'])));
											$payroll_date = date_create(date('Y-m-01',strtotime(@$payroll_period[0]['start_date'])));
											$date_diff = date_diff($payroll_date,$maturity_date);
											$enhanced_rmtp = $date_diff->format("%m");
                                            break;
                                        }
                                        else
                                            $loan = "0.00";

                                    }
                                }
                                echo $loan;
                                ?>
                                </td>
                                <td nowrap="" style="width:15px;text-align:center;border-right:1px groove #3b3b3b;"><?php echo $enhanced_rmtp; ?></td>
                                <td nowrap="">Housing</td>
                                <td nowrap="" style="text-align:right;border-right:1px groove #3b3b3b;">
                                <?php 
                                $loan = "0.00";
								$pagibig_housing_rmtp = 0;
                                if(sizeof($loanDeductions) > 0){
                                    foreach ($loanDeductions as $k => $v) {
                                        if($v['code_loan'] == "PAG-IBIG" && $v['code_sub'] == "Housing"){
                                            $loan = number_format($v['amount'],2);
											$maturity_date = date_create(date('Y-m-01',strtotime(@$v['maturity_date'])));
											$payroll_date = date_create(date('Y-m-01',strtotime(@$payroll_period[0]['start_date'])));
											$date_diff = date_diff($payroll_date,$maturity_date);
											$pagibig_housing_rmtp = $date_diff->format("%m");
                                            break;
                                        }
                                        else
                                            $loan = "0.00";

                                    }
                                }
                                echo $loan;
                                ?>
                                </td>
                                <td style="width:15px;text-align:center;border-right:1px groove #3b3b3b;"><?php echo $pagibig_housing_rmtp; ?></td>
                                <td nowrap="">Optional Ins.</td>
                                <td nowrap="" style="text-align:right;border-right:1px groove #3b3b3b;">
                                <?php 
                                $loan = "0.00";
                                $optional_ins_rmtp = 0;
                                if(sizeof($loanDeductions) > 0){
                                    foreach ($loanDeductions as $k => $v) {
                                        if($v['code_loan'] == "GSIS" && $v['code_sub'] == "Optional Ins."){
                                            $loan = number_format($v['amount'],2);
                                            $maturity_date = date_create(date('Y-m-01',strtotime(@$v['maturity_date'])));
                                            $payroll_date = date_create(date('Y-m-01',strtotime(@$payroll_period[0]['start_date'])));
                                            $date_diff = date_diff($payroll_date,$maturity_date);
                                            $optional_ins_rmtp = $date_diff->format("%m");
                                            break;
                                        }
                                        else
                                            $loan = "0.00";

                                    }
                                }
                                echo $loan;
                                ?>
                                </td>
                                 <td nowrap="" style="width:15px;border-right:1px groove #3b3b3b;text-align:center"><?php echo $optional_ins_rmtp; ?></td>
                               
                            </tr>
                            <tr>
                                <td nowrap> End of the month ...</td>
                                <td nowrap style="text-align:right;"><?php echo number_format((double)@$payroll[0]['cutoff_4'],2); ?></td>
                                <td nowrap>Emergency -</td>
                                <td nowrap="" style="text-align:right;border-right:1px groove #3b3b3b;">
                                <?php 
                                $loan = "0.00";
								$emergency_rmtp = 0;
                                if(sizeof($loanDeductions) > 0){
                                    foreach ($loanDeductions as $k => $v) {
                                        if($v['code_loan'] == "GSIS" && $v['code_sub'] == "Emergency"){
                                            $loan = number_format($v['amount'],2);
											$maturity_date = date_create(date('Y-m-01',strtotime(@$v['maturity_date'])));
											$payroll_date = date_create(date('Y-m-01',strtotime(@$payroll_period[0]['start_date'])));
											$date_diff = date_diff($payroll_date,$maturity_date);
											$emergency_rmtp = $date_diff->format("%m");
                                            break;
                                        }
                                        else
                                            $loan = "0.00";

                                    }
                                }
                                echo $loan;
                                ?>
                                </td>
                                <td nowrap="" style="width:15px;text-align:center;border-right:1px groove #3b3b3b;"><?php echo $emergency_rmtp; ?></td>
                                <td nowrap="">Multi-purpose</td>
                                <td nowrap="" style="text-align:right;border-right:1px groove #3b3b3b;">
                                <?php 
                                $loan = "0.00";
								$multi_purpose_rmtp = 0;
                                if(sizeof($loanDeductions) > 0){
                                    foreach ($loanDeductions as $k => $v) {
                                        if($v['code_loan'] == "PAG-IBIG" && $v['code_sub'] == "Multi-Purpose"){
                                            $loan = number_format($v['amount'],2);
											$maturity_date = date_create(date('Y-m-01',strtotime(@$v['maturity_date'])));
											$payroll_date = date_create(date('Y-m-01',strtotime(@$payroll_period[0]['start_date'])));
											$date_diff = date_diff($payroll_date,$maturity_date);
											$multi_purpose_rmtp = $date_diff->format("%m");
                                            break;
                                        }
                                        else
                                            $loan = "0.00";

                                    }
                                }
                                echo $loan;
                                ?>
                                </td>
                                <td style="width:15px;text-align:center;border-right:1px groove #3b3b3b;"><?php echo $multi_purpose_rmtp; ?></td>
                                <td nowrap="">Additional Tax</td>
                                <td nowrap="" style="text-align:right;border-right:1px groove #3b3b3b;">0.00</td>
                                <td nowrap="" style="width:15px;border-right:1px groove #3b3b3b;text-align:center"></td>
                                
                            </tr>
                            <tr>
                                <td nowrap> (RATA)<br>
                                </td>
                                <td nowrap style="text-align:right;"></td>
                                <td nowrap>ECard Plus -</td>
                                <td nowrap="" style="text-align:right;border-right:1px groove #3b3b3b;">
                                <?php 
                                $loan = "0.00";
								$ecard_plus_rmtp = 0;
                                if(sizeof($loanDeductions) > 0){
                                    foreach ($loanDeductions as $k => $v) {
                                        if($v['code_loan'] == "GSIS" && $v['code_sub'] == "ECard Plus"){
                                            $loan = number_format($v['amount'],2);
											$maturity_date = date_create(date('Y-m-01',strtotime(@$v['maturity_date'])));
											$payroll_date = date_create(date('Y-m-01',strtotime(@$payroll_period[0]['start_date'])));
											$date_diff = date_diff($payroll_date,$maturity_date);
											$ecard_plus_rmtp = $date_diff->format("%m");
                                            break;
                                        }
                                        else
                                            $loan = "0.00";

                                    }
                                }
                                echo $loan;
                                ?>
                                </td>
                                <td nowrap="" style="width:15px;text-align:center;border-right:1px groove #3b3b3b;"><?php echo $ecard_plus_rmtp; ?></td>
                                <td nowrap="">Calamity</td>
                                <td nowrap="" style="text-align:right;border-right:1px groove #3b3b3b;">
                                <?php 
                                $loan = "0.00";
								$calamity_rmtp = 0;
                                if(sizeof($loanDeductions) > 0){
                                    foreach ($loanDeductions as $k => $v) {
                                        if($v['code_loan'] == "PAG-IBIG" && $v['code_sub'] == "Calamity"){
                                            $loan = number_format($v['amount'],2);
											$maturity_date = date_create(date('Y-m-01',strtotime(@$v['maturity_date'])));
											$payroll_date = date_create(date('Y-m-01',strtotime(@$payroll_period[0]['start_date'])));
											$date_diff = date_diff($payroll_date,$maturity_date);
											$calamity_rmtp = $date_diff->format("%m");
                                            break;
                                        }
                                        else
                                            $loan = "0.00";

                                    }
                                }
                                echo $loan;
                                ?>
                                </td>
                                <td style="width:15px;text-align:center;border-right:1px groove #3b3b3b;"><?php echo $calamity_rmtp; ?></td>
                                <td nowrap="">Overpayment</td>
                                <td nowrap="" style="text-align:right;border-right:1px groove #3b3b3b;">
                                    <?php echo number_format((double)@$payroll[0]['total_other_deduct_amt'],2); ?>
                                </td>
                                <td nowrap="" style="width:15px;border-right:1px groove #3b3b3b;text-align:center"></td>
                                
                               
                            </tr>
                            <tr>
                                <td nowrap> (PERA)</td>
                                 <td nowrap style="text-align:right;border-bottom:1px groove #3b3b3b;"><?php echo number_format((double)@$payroll[0]['pera_amt'],2); ?></td>              
                                <td nowrap>Old GSIS -</td>
                                <td nowrap="" style="text-align:right;border-right:1px groove #3b3b3b;">
                                <?php 
                                $loan = "0.00";
								$old_gsis_rmtp = 0;
                                if(sizeof($loanDeductions) > 0){
                                    foreach ($loanDeductions as $k => $v) {
                                        if($v['code_loan'] == "GSIS" && $v['code_sub'] == "Old GSIS"){
                                            $loan = number_format($v['amount'],2);
											$maturity_date = date_create(date('Y-m-01',strtotime(@$v['maturity_date'])));
											$payroll_date = date_create(date('Y-m-01',strtotime(@$payroll_period[0]['start_date'])));
											$date_diff = date_diff($payroll_date,$maturity_date);
											$old_gsis_rmtp = $date_diff->format("%m");
                                            break;
                                        }
                                        else
                                            $loan = "0.00";

                                    }
                                }
                                echo $loan;
                                ?>
                                </td>
                                <td nowrap="" style="width:15px;text-align:center;border-right:1px groove #3b3b3b;">
									<?php echo $old_gsis_rmtp; ?>
								</td>
                                <td nowrap=""><b>OTHER DEDUCTIONS:</b></td>
                                <td nowrap="" style="text-align:right;border-right:1px groove #3b3b3b;"></td>
                                <td style="width:15px;text-align:center;border-right:1px groove #3b3b3b;"></td>
                                <td nowrap=""></td>
                                <td nowrap="" style="text-align:right;border-right:1px groove #3b3b3b;">
                                </td>
                                <td nowrap="" style="width:15px;border-right:1px groove #3b3b3b;text-align:center"></td>
                                <td nowrap=""></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td nowrap></td>
                                <td nowrap style="text-align:right;"></td>
                                <td nowrap>Housing -</td>
                                <td nowrap="" style="text-align:right;border-right:1px groove #3b3b3b;">
                                <?php 
                                $loan = "0.00";
								$housing_rmtp = 0;
                                if(sizeof($loanDeductions) > 0){
                                    foreach ($loanDeductions as $k => $v) {
                                        if($v['code_loan'] == "GSIS" && $v['code_sub'] == "Housing"){
                                            $loan = number_format($v['amount'],2);
											$maturity_date = date_create(date('Y-m-01',strtotime(@$v['maturity_date'])));
											$payroll_date = date_create(date('Y-m-01',strtotime(@$payroll_period[0]['start_date'])));
											$date_diff = date_diff($payroll_date,$maturity_date);
											$housing_rmtp = $date_diff->format("%m");
                                            break;
                                        }
                                        else
                                            $loan = "0.00";

                                    }
                                }
                                echo $loan;
                                ?>
                                </td>
                                <td nowrap="" style="width:15px;text-align:center;border-right:1px groove #3b3b3b;">
									<?php echo $housing_rmtp; ?>
								</td>
                                <td nowrap="">PHIL.HEALTH</td>
                                <td nowrap="" style="text-align:right;border-right:1px groove #3b3b3b;">
                                    <?php echo number_format((double)@$payroll[0]['philhealth_amt'],2); ?>
                                </td>
                                <td style="width:15px;text-align:center;border-right:1px groove #3b3b3b;"></td>
                                <td nowrap=""></td>
                                <td nowrap="" style="text-align:right;border-right:1px groove #3b3b3b;"></td>
                                <td nowrap="" style="width:15px;border-right:1px groove #3b3b3b;text-align:center"></td>
                                <td nowrap=""></td>
                                <td></td>
                            </tr>
                            <tr>
                                 <td nowrap><b>*** Net Pay ***</b></td>
                                <td nowrap style="text-align:right;"><b><?php echo number_format((double)@$payroll[0]['net_pay'],2); ?></b></td>
                                <td nowrap>Educational -</td>
                                <td nowrap="" style="text-align:right;border-right:1px groove #3b3b3b;">
                                <?php 
                                $loan = "0.00";
								$educational_rmtp = 0;
                                if(sizeof($loanDeductions) > 0){
                                    foreach ($loanDeductions as $k => $v) {
                                        if($v['code_loan'] == "GSIS" && $v['code_sub'] == "Educational"){
                                            $loan = number_format($v['amount'],2);
											$maturity_date = date_create(date('Y-m-01',strtotime(@$v['maturity_date'])));
											$payroll_date = date_create(date('Y-m-01',strtotime(@$payroll_period[0]['start_date'])));
											$date_diff = date_diff($payroll_date,$maturity_date);
											$educational_rmtp = $date_diff->format("%m");
                                            break;
                                        }
                                        else
                                            $loan = "0.00";

                                    }
                                }
                                echo $loan;
                                ?>
                                </td>
                                <td nowrap="" style="width:15px;text-align:center;border-right:1px groove #3b3b3b;"><?php echo $educational_rmtp; ?></td>
                                <td nowrap="">WHTax</td>
                                <td nowrap="" style="text-align:right;border-right:1px groove #3b3b3b;">
                                    <?php echo number_format((double)@$payroll[0]['wh_tax_amt'],2); ?>
                                </td>
                                <td style="width:15px;text-align:center;border-right:1px groove #3b3b3b;"></td>
                                <td nowrap=""></td>
                                <td nowrap="" style="text-align:right;border-right:1px groove #3b3b3b;"></td>
                                <td nowrap="" style="width:15px;border-right:1px groove #3b3b3b;text-align:center"></td>
                                <td nowrap=""></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td nowrap></td>
                                <td nowrap style="text-align:right;"></td>
                                <td nowrap>Policy -</td>
                                <td nowrap="" style="text-align:right;border-right:1px groove #3b3b3b;">
                                <?php 
                                $loan = "0.00";
								$policy_rmtp = 0;
                                if(sizeof($loanDeductions) > 0){
                                    foreach ($loanDeductions as $k => $v) {
                                        if($v['code_loan'] == "GSIS" && $v['code_sub'] == "Policy Loan"){
                                            $loan = number_format($v['amount'],2);
											$maturity_date = date_create(date('Y-m-01',strtotime(@$v['maturity_date'])));
											$payroll_date = date_create(date('Y-m-01',strtotime(@$payroll_period[0]['start_date'])));
											$date_diff = date_diff($payroll_date,$maturity_date);
											$policy_rmtp = $date_diff->format("%m");
                                            break;
                                        }
                                        else
                                            $loan = "0.00";

                                    }
                                }
                                echo $loan;
                                ?>
                                </td>
                                <td nowrap="" style="width:15px;text-align:center;border-right:1px groove #3b3b3b;"><?php echo $policy_rmtp; ?></td>
                                <td nowrap="">Deductable PERA</td>
                                <td nowrap="" style="text-align:right;border-right:1px groove #3b3b3b;">0.00</td>
                                <td style="width:15px;text-align:center;border-right:1px groove #3b3b3b;"></td>
                                <td nowrap=""></td>
                                <td nowrap="" style="text-align:right;border-right:1px groove #3b3b3b;"></td>
                                <td nowrap="" style="width:15px;border-right:1px groove #3b3b3b;text-align:center"></td>
                                <td nowrap=""></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td nowrap style="font-size:6px;"></td>
                                <td nowrap style="text-align:right;"></td>
                                <td nowrap>PSMBFund -</td>
                                <td nowrap="" style="text-align:right;border-right:1px groove #3b3b3b;">
                                <?php 
                                $loan = "0.00";
								$psmbfund_rmtp = 0;
                                if(sizeof($loanDeductions) > 0){
                                    foreach ($loanDeductions as $k => $v) {
                                        if($v['code_loan'] == "Other Loans" && $v['code_sub'] == "PSMBFund"){
                                            $loan = number_format($v['amount'],2);
											$maturity_date = date_create(date('Y-m-01',strtotime(@$v['maturity_date'])));
											$payroll_date = date_create(date('Y-m-01',strtotime(@$payroll_period[0]['start_date'])));
											$date_diff = date_diff($payroll_date,$maturity_date);
											$psmbfund_rmtp = $date_diff->format("%m");
                                            break;
                                        }
                                        else
                                            $loan = "0.00";

                                    }
                                }
                                //echo $loan;
                                ?>
								
								<?php echo number_format((double)(@$payroll[0]['psmbfund_amt'] + $loan),2); ?>
                                </td>
                                <td nowrap="" style="width:15px;text-align:center;border-right:1px groove #3b3b3b;"><?php echo $psmbfund_rmtp; ?></td>
                                <td nowrap="">Absent/Late/U.T.</td>
                                <td nowrap="" style="text-align:right;border-right:1px groove #3b3b3b;">
                                    <?php echo number_format((double)@$payroll[0]['total_tardiness_amt'],2); ?>
                                </td>
                                <td style="width:15px;text-align:center;border-right:1px groove #3b3b3b;"></td>
                                <td nowrap=""></td>
                                <td nowrap="" style="text-align:right;border-right:1px groove #3b3b3b;"></td>
                                <td nowrap="" style="width:15px;border-right:1px groove #3b3b3b;text-align:center"></td>
                                <td nowrap=""></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="8" style="text-align:left;border-bottom:1px groove #3b3b3b;"></td>
                                <td colspan="5" style="border-bottom:1px groove #3b3b3b;text-align:center;">Total Deduction &emsp;&emsp;&emsp;&emsp;&emsp;&emsp; 
                                    <?php echo number_format((double)(@$payroll[0]['total_deduct_amt'] + @$payroll[0]['total_tardiness_amt']),2); ?>
                                </td>
                            </tr>
                        </table>
                        <div style="height:60px"></div>
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
