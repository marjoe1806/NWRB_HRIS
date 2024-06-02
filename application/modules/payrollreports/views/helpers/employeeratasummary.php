
<div id="employee-payslips">
    <div class="row">
        <div class="col-md-12">
            <style type="text/css">
            </style>
            <div style="width:100%; overflow-x:auto;">
                <div id="clearance-div">
                    <style>
                     *{
                        color:black;
                     }
                     td{
                        color:black;
                        font-size: 12px;
                     
                     }
                     
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
                        /* #certificate-container table tr td{
                            padding-left:2px;
                            padding-right: 2px;
                        } */
                    </style>
                    <div id="certificate-container" style="height:100%; border: 2px solid black; padding: 3px">
                    <?php
                    	$from = date('F d',strtotime(@$payroll_period[0]['start_date']));
                        $to = date('d, Y',strtotime(@$payroll_period[0]['end_date']));
                        $period = $from.'-'.$to;

                        $department_name = @$payroll[0]['department_name'];

                        $total_rep_allowance = 0;
                        $total_transpo_allowance = 0;
                    ?>
                        <table style="width:100%;">
                            <tr>
                                <td nowrap style="text-align:left; font-size: 16px">
                                    <b>NATIONAL WATER RESOURCES BOARD</b>
                                </td>
                            </tr>
                            <tr>
                                <td nowrap style="text-align:left; font-size: 14px">
                                    8th Floor NIA Building, EDSA, Diliman, Quezon City
                                    <br>
                                    <br>
                                </td>
                                <!-- <td nowrap style="width:20%;text-align:center;" valign="top"><b>AS-PBD-007</b></td> -->
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align:center;">
                                    <p style="font-size: 18px; margin-bottom: 40px;">
                                        <b>PAYROLL FOR REPRESENTATION AND TRANSPORTATION ALLOWANCES</b>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:left; font-size: 12px;">
                                    <p>
                                        <b>AGENCY: </b> NATIONAL WATER RESOURCES BOARD
                                    </p>
                                </td>
                                <td style="text-align:center margin-bottom: 20px; font-size: 12px;">
                                    <p>
                                        <b>PERIOD: </b> <?php echo $period ?>
                                    </p>
                                </td>
                            </tr>
                            <br><br>
                            <!-- <tr>
                                <td colspan="2" nowrap>
                                    WE HEREBY ACKNOWLEDGE to have received from NATIONAL WATER RESOURCES BOARD - <b><?php echo $department_name; ?></b> the sums therein specified opposite of our respective names,
                                    <br> being in full compensation for our <b>REPRESENTATION AND TRANSPORTATION ALLOWANCE</b> for the period <b><?php echo $period ?></b> except as noted otherwise in the Remarks column.
                                </td>
                            </tr> -->
                        </table>
                        <table style="width:100%;">
                            <tr style="border-top:1px solid #3b3b3b; border-bottom:1px solid #3b3b3b; margin-bottom: 10px;">
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
                                <!-- <td valign="top" style="text-align:center;" nowrap="">
                                    Overpayment 
                                    <br> TA
                                </td>
                                <td valign="top" style="text-align:center;" nowrap="">
                                    UNCLAIMED TA
                                </td> -->
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
		                    <?php foreach ($payroll as $k => $v) : ?>
		                    <?php     
		                        $first_name = $this->Helper->decrypt($v['first_name'],$v['employee_id']);
		                        $last_name = $this->Helper->decrypt($v['last_name'],$v['employee_id']);
		                        $middle_name = $this->Helper->decrypt($v['middle_name'],$v['employee_id']);
		                        $employee_id_number = $this->Helper->decrypt($v['employee_id_number'],$v['employee_id']);
		                        $middle_initial = substr($middle_name, 0, 1);
		                        $total_rep_allowance += $v['rep_allowance'];
		                        $total_transpo_allowance += $v['transpo_allowance'];
		                    ?>
                            <tr style="border-top:1px solid #3b3b3b; border-bottom:1px solid #3b3b3b; padding-bottom: 1rem">
                                <td style="text-align:center" valign="bottom"><?php echo $k+1; ?></td>
                                <td style="text-align:left">
                                    <span style="font-size:10px;"><?php echo $v['department_name']; ?></span>
                                    <br><b><?php echo strtoupper($last_name.', '.$first_name.' '.$middle_initial.'.') ?></b>
                                </td>
                                <td valign="bottom"><?php echo $v['position_name']; ?></td>
                                <td valign="bottom" style="text-align:center;">
                                    <?php echo number_format((double)@$v['rep_allowance'],2); ?>
                                </td>
                                <td valign="bottom" style="text-align:center;">
                                    <?php echo number_format((double)@$v['transpo_allowance'],2); ?>
                                </td>
                                <!-- <td valign="bottom" style="text-align:right;"></td>
                                <td valign="bottom" style="text-align:right;"></td> -->
                                <td valign="bottom" style="text-align:right;"><?php echo number_format((double)($v['rep_allowance']+$v['transpo_allowance']),2); ?></td>
                                <td style="text-align:center" valign="bottom"><?php echo $k+1; ?></td>
                                <td valign="bottom" style="text-align:right;">
                                   <center>
                                        <div style=" width: 90%; border-bottom:1px solid #3b3b3b;"></div>
                                   </center>
                                </td>
                                <td></td>
                            </tr>
                           	<?php endforeach; ?>
                            <tr>
                                <td style="padding: 5px;"></td>
                            </tr>
                            <tr>    
                            
                                <td colspan="3" style="text-align:center;">
                                    <b>TOTAL</b>
                                </td>
                                <td style="text-align:center;border-bottom:1px solid #3b3b3b;border-top:1px solid #3b3b3b;">
                                    <?php echo number_format((double)@$total_rep_allowance,2); ?>
                                </td>
                                <td style="text-align:center; border-bottom:1px solid #3b3b3b;border-top:1px solid #3b3b3b;">
                                    <?php echo number_format((double)@$total_transpo_allowance,2); ?>
                                </td>
                              
                                <!-- <td style="text-align:right; border-bottom:1px solid #3b3b3b;border-top:1px solid #3b3b3b;">
                                    0.00
                                </td> -->
                                <td style="text-align:right; border-bottom:1px solid #3b3b3b;border-top:1px solid #3b3b3b;">
                                    <?php echo number_format((double)($total_rep_allowance+$total_transpo_allowance),2); ?>
                                </td>
                                <td colspan="3"></td>
                            </tr>
                        </table>
                        
                        <center>
                        <table style="margin-top:50px; width: 95%">
                            <tr>
                                <td style="margin-bottom: 10px;">
                                    <b>CERTIFIED: </b>
                                </td>
                            </tr>
                            <tr  style="border: 1px solid black">
                                <td style="padding:1rem; border-bottom: 1px solid #fff;">
                                    <p style="font-size: 15px">
                                        (1) Funds Available:________________ 
                                    </p>
                                </td>
                                <td style="padding:1rem; border-bottom: 1px solid #fff;  border-right: 1px solid black; border-left: 1px solid black">
                                    <p style="font-size: 15px">
                                        (2) Approval for Payment 
                                    </p>
                                </td>
                                <td style="padding:1rem; border-bottom: 1px solid #fff; width: 500px;">
                                    <p style="font-size: 15px">
                                        (3) Each person whose name appears on the above payroll has been paid the amount stated opposite his/her name.
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 2rem; width: 400px; border: 1px solid black">
                                   
                                    <center>
                                    <div  style="text-decoration: underline; font-size:12px;" align="center"><?php echo $signatories[1]["employee_name"]; //name ?></div><br>
                                    <div align="center"><?php echo $signatories[1]["position_designation"] != "" || $signatories[1]["position_designation"] != null ? $signatories[1]["position_designation"] : $signatories[1]["position_title"]; //position ?></div><br>
                                    <div align"center"><?php echo $signatories[1]["division_designation"] != "" || $signatories[1]["division_designation"] != null ? $signatories[1]["division_designation"] : $signatories[1]["department"]; //position ?></div>
                                    </center> 
                                </td>
                                <td style="padding: 2rem; width: 400px; border: 1px solid black">
                                   
                                    <center>
                                    <div  style="text-decoration: underline; font-size:12px;" align="center">
                                    <?php if($signatories[1]["employee_name"] = "SEVILLO D. DAVID")
                                    {
                                        echo "DR. SEVILLO D. DAVID JR., CESO III";
                                    }else{
                                        echo $signatories[1]["employee_name"];
                                    }
                                    ?></div><br>
                                    <div align="center"><?php echo $signatories[1]["position_designation"] != "" || $signatories[1]["position_designation"] != null ? $signatories[1]["position_designation"] : $signatories[1]["position_title"]; //position ?></div><br>
                                    <div align="center"><?php echo $signatories[1]["division_designation"] != "" || $signatories[1]["division_designation"] != null ? $signatories[1]["division_designation"] : $signatories[1]["department"]; //position ?></div>
                                    </center>
                                   
                                </td>
                                <td style="padding: 2rem; width: 400px; border: 1px solid black">
                                    <center>
                                    <div  style="text-decoration: underline; font-size:12px;" align="center"><?php echo $signatories[3]["employee_name"]; //name ?></div><br>
                                    <div align="center"><?php echo $signatories[3]["position_designation"] != "" || $signatories[3]["position_designation"] != null ? $signatories[3]["position_designation"] : $signatories[3]["position_title"]; //position ?></div><br>
                                    <div align"center"><?php echo $signatories[3]["division_designation"] != "" || $signatories[3]["division_designation"] != null ? $signatories[3]["division_designation"] : $signatories[3]["department"]; //position ?></div>
                                    </center> 
                                </td>
                            </tr>
                        </table>
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 5px;">
        <div class="col-md-12 text-right">
            <button id="printClearance" class="btn bg-green btn-fab btn-fab-mini mt-2">Print <i class = "material-icons">print</i></button>
        </div>
    </div>
</div>	