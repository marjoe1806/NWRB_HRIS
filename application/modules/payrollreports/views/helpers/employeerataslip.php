
<?php if($key != "viewRATASlipSummary"):
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
                            @page {size: landscape}
                            body{
                                margin: 5mm 5mm 5mm 5mm;
                                font-family:'Times New Roman';
                                font-size:11px;
                            } 
                            table tr td{
                                font-family:'Times New Roman';
                                font-size:8px;

                            } 
                        }
                        @media screen and (min-width: 961px){
                            #certificate-container{
                                padding:15px 15px 15px 15px; 
                            }
                        }
                        @media screen and (max-width: 960px){
                            #certificate-container{
                                padding:10px 10px 10px 10px; 
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
                    <div id="certificate-container" style="height:800px;">
    <?php endif; ?>
                        <?php if($key == "viewRATASlipSummary" || $key == "viewRATASlip"): ?>
                        <?php 
                            $from = date('F d',strtotime(@$payroll_period[0]['start_date']));
                            $to = date('d, Y',strtotime(@$payroll_period[0]['end_date']));
                            $period = $from.'-'.$to;
                            
                            $employee_number = $this->Helper->decrypt($payroll[0]['employee_number'],$payroll[0]['employee_id']);
                            $first_name = $payroll[0]['first_name'];
                            $last_name = $payroll[0]['last_name'];
                            $middle_name = $payroll[0]['middle_name'];
                            $employee_id_number = $payroll[0]['employee_id_number'];;
                            $middle_initial = substr($middle_name, 0, 1);
                        ?>
                        <table style="width:100%;">
                            <tr>
                                <td nowrap style="width:80%;text-align:center;"><b>GENERAL PAYROLL</b><br><br></td>
                                <td nowrap style="width:20  %;text-align:center;" valign="top"><b>AS-PBD-007</b></td>
                            </tr>
                            <tr>
                                <td colspan="2" nowrap>
                                    WE HEREBY ACKNOWLEDGE to have received from NATIONAL WATER RESOURCES BOARD - <b>OFFICE OF THE CHAIRMAN</b> the sums therein specified opposite of our respective names,
                                    <br> being in full compensation for our <b>REPRESENTATION AND TRANSPORTATION ALLOWANCE</b> for the period <b><?php echo $period ?></b> except as noted otherwise in the Remarks column.
                                </td>
                            </tr>
                        </table>
                        <table style="width:100%;">
                            <tr style="border-top:1px solid #3b3b3b;">
                                <td>No.</td>
                                <td style="text-align:center;" nowrap="">
                                    Name of Officer 
                                    <br> <b>(Appointed to the position-NOSCA)</b>
                                </td>
                                <td nowrap valign="top">Designation</td>
                                <td valign="top" style="text-align:center;" nowrap="">
                                    Representation 
                                    <br> Allowance
                                </td>
                                <td valign="top" style="text-align:center;" nowrap="">
                                    Transportation 
                                    <br> Allowance
                                </td>
                                <td valign="top" style="text-align:center;" nowrap="">
                                    Overpayment 
                                    <br> TA
                                </td>
                                <td valign="top" style="text-align:center;" nowrap="">
                                    UNCLAIMED TA
                                </td>
                                <td valign="top" style="text-align:center;" nowrap="">
                                    Net 
                                    <br> Pay
                                </td>
                                <td valign="top" style="text-align:center;" nowrap="">
                                    No.
                                </td>
                                <td valign="top" style="text-align:center;width:150px;" nowrap="">
                                    Signature 
                                    <br> of Payee
                                </td>
                                <td valign="middle" style="text-align:center;width:180px;" nowrap="">
                                    REMARKS
                                </td>
                            </tr>
                            <tr style="border-top:1px solid #3b3b3b;">
                                <td style="text-align:center" valign="bottom">1</td>
                                <td style="text-align:left">
                                    <span style="font-size:8px;"><?php echo $payroll[0]['department_name']; ?></span>
                                    <br><b><?php echo strtoupper($last_name.', '.$first_name.' '.$middle_initial.'.') ?></b>
                                </td>
                                <td valign="bottom"><?php echo $payroll[0]['position_name']; ?></td>
                                <td valign="bottom" style="text-align:right;">
                                    <?php echo number_format((double)@$payroll[0]['rep_allowance'],2); ?>
                                </td>
                                <td valign="bottom" style="text-align:right;">
                                    <?php echo number_format((double)@$payroll[0]['transpo_allowance'],2); ?>
                                </td>
                                <td valign="bottom" style="text-align:right;"></td>
                                <td valign="bottom" style="text-align:right;"></td>
                                <td valign="bottom" style="text-align:right;"><?php echo number_format((double)($payroll[0]['rep_allowance']+$payroll[0]['transpo_allowance']),2); ?></td>
                                <td style="text-align:center">1</td>
                                <td valign="bottom" style="text-align:right;">
                                     <div style="width:90%;border-bottom:1px solid #3b3b3b;"></div>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align:center;">
                                    <b>TOTAL</b>
                                </td>
                                <td style="text-align:right;border-bottom:1px solid #3b3b3b;border-top:1px solid #3b3b3b;">
                                    <?php echo number_format((double)@$payroll[0]['rep_allowance'],2); ?>
                                </td>
                                <td style="text-align:right; border-bottom:1px solid #3b3b3b;border-top:1px solid #3b3b3b;">
                                    <?php echo number_format((double)@$payroll[0]['transpo_allowance'],2); ?>
                                </td>
                                <td style="text-align:right; border-bottom:1px solid #3b3b3b;border-top:1px solid #3b3b3b;">
                                </td>
                                <td style="text-align:right; border-bottom:1px solid #3b3b3b;border-top:1px solid #3b3b3b;">
                                    0.00
                                </td>
                                <td style="text-align:right; border-bottom:1px solid #3b3b3b;border-top:1px solid #3b3b3b;">
                                    <?php echo number_format((double)($payroll[0]['rep_allowance']+$payroll[0]['transpo_allowance']),2); ?>
                                </td>
                                <td colspan="3"></td>
                            </tr>
                        </table>
                        <table style="width:100%;">
                            <tr>
                                <td nowrap valign="top">
                                    (1) I CERTIFY on my official oath that the above payroll is correct and the 
                                    <br>services have been rendered as stated.
                                </td>
                                <td nowrap valign="top">
                                    
                                </td>
                                <td nowrap valign="top">
                                    (3) I CERTIFY on my official, that I have paid to each personnel whose name appears on the 
                                    <br> above payroll the amount set opposite his name, he having presented his Residence Certificate
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3"><br></td>
                            </tr>
                            <tr>
                                <td nowrap valign="top">
                                    (2) APPROVED, payable appropriation for:
                                </td>
                                <td nowrap valign="top">
                                    
                                </td>
                                <td nowrap valign="top">
                                    (4) I CERTIFY on my official oath that, I have witnessed payment to each person,
                                    <br>whose name appears hereon, of the amount set opposite his name and my initials.
                                </td>
                            </tr>
                        </table>
                    <?php endif; ?>
    <?php if($key != "viewRATASlipSummary"): ?>
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
