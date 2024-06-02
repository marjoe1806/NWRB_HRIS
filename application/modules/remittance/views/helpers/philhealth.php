<div class="row">
    <div class="col-md-12 text-right">
        <button id="downloadClearance" class="btn bg-green btn-fab btn-fab-mini" onclick="saveAsExcel();">Save as Excel <i class = "material-icons">cloud_download</i></button>
        <button id="printClearance" class="btn bg-blue btn-fab btn-fab-mini">Print Preview <i class = "material-icons">print</i></button>
    </div>
</div>
<hr>
<div class="row" style="overflow-x:auto;">
    <div class="col-md-12">
        <div style="width:100%;">
            <div id="clearance-div">
                <style type = 'text/css'>
                    @media print{
                        html { height: 0; }
                        @page {
                            size: A4 landscape;
                            margin: 0.2in;
                        }
                        body { font-family: "Times New Roman", Times, serif; font-size: 9; color: black; }
                        table{ border-collapse: collapse; }
                        .page-break{ display: table; vertical-align:top; width: 100% !important; page-break-after: always !important; table-layout: inherit; margin-top:2px; }
                        .no-print {
                            display: none !important;
						}					
                    }
                </style> 
                <style type="text/css" media="all">
                /* .break{  page-break-before: always; } */
                    table#tmpTable thead tr th, table#tmpTable tbody tr td{ padding: 2px; }
                    table#tmpTable thead tr th, table#excelTable thead tr th{ border: 1px solid black; }
                    table#tmpTable tr, #tmpTable td, #gtTable td 
                    table#excelTable tr, #excelTable td{
                        font-family: "Times New Roman", Times, serif;
                        font-size: 11px;
                        color: #000;
                        border: 1px solid black;
                    }
                    table#hdTable, table#ftTable, table#gtTable, table#excelTable{
                        font-family: "Times New Roman", Times, serif;
                        font-size: 11px;
                        color: #000;
                    }
                    table#ftTable{
                        font-family: "Times New Roman", Times, serif;
                        font-size: 11px;
                        color: #000;
                    }
                    #footer{
                        display: table-footer-group;							
					}
                </style>

                <!-- START OF FIRST PAGE -->
                <?php
                    $page = 0;
                    $totalNum = 0; 
                    $numExperience = sizeof($payroll) / 45;
                    $wholeNumExperience = floor($numExperience);
                    $decNumExperience = $wholeNumExperience - $numExperience;
                    $totalNum = $wholeNumExperience;
                    if($decNumExperience < 0) $totalNum += 1;
                    $count1 = 0;
                    $count2 = 24;
                    $page_count = 1;
                    $last_row = 0;
                    $count = 1;
                    $rows = 0;
                    $grand_total = array(
                        'philhealth_amt'=>0.00, 'philhealth_amt_employer'=>0.00
                    );
                ?>                
                <?php for($x = 0; $x < $totalNum; $x++){  ?>
                    <div class="header-container" style="width:100%;">
                        <table style="width:100%;border-bottom:0px;border-top:5px" id="hdTable">
                            <thead>
                                <tr>
                                    <td style="text-align:center;font-weight: bold;" colspan="13">REPUBLIC OF THE PHILIPPINES</td>
                                </tr>
                                <tr>
                                    <td style="text-align:center;font-weight: bold;" colspan="13">PHILIPPINE HEALTH INSURANCE CORPORATION </td>
                                </tr>
                                <tr>
                                    <td style="text-align:center;font-weight: normal;" colspan="13">11th - 17th Floors, CityState Centre, 709 Shaw Boulevard, Barangay Oranbo, Pasig City</td>
                                </tr>
                                <tr>
                                    <td style="text-align:center;font-weight: normal;" colspan="13">637-9999, 637-9852 to 81 / www.philhealth.gov.ph</td>
                                </tr>
                                <tr><td>&nbsp;</td></tr>
                                <tr>
                                    <td>PHIL. HEALTH  REMITTANCE FORM NO. 1</td>
                                    <td><b>REMITTANCE BANK:</b>&emsp;  LAND BANK OF THE PHILIPPINES, East Ave., Ext., EDSA, Q.C.</td>
                                </tr>
                                <tr>
                                    <td><b>RF - 01</b></td>
                                    <td><b>EMPLOYER I.D.:</b>&emsp;  14-037410004-7</td>
                                </tr>                                
                                <tr><td>&nbsp;</td></tr>
                                <tr>
                                    <td><b>REGISTERED EMPLOYER NAME:</b>&emsp;  NATIONAL WATER RESOURCES BOARD</td>
                                    <td><b>EMPLOYER TIN:</b>&emsp;    000-795-636-NV</td>
                                </tr>
                                <tr>
                                    <td><b>ADDRESS:</b>&emsp;    8th Floor NIA  Bldg., EDSA, Quezon City</td>
                                    <td><b>DATE PREPARED:</b>&emsp; </td>
                                </tr>
                            </thead>
                        </table>
                        <?php
                            $total_per_page = $total_next_page = 24;
                            $total_page = floor(sizeof($payroll)/$total_per_page) + 1;

                            $page_total = array(
                                'philhealth_amt'=>0.00, 'philhealth_amt_employer'=>0.00
                            );

                            if(( sizeof($payroll) < $total_next_page && sizeof($payroll) > floor($total_next_page*.5) ) || (sizeof($payroll) > $total_next_page && (sizeof($payroll)) - ($total_next_page * $total_page) > floor($total_next_page*.5) )){
                                $total_page = floor(sizeof($payroll)/$total_per_page) + 2;
                            }
                        ?>
                        <div class="table-container"><?php $page++; ?>
                            <table class="table table-hover table-striped" style="width:100%;" id="tmpTable">
                                <thead>
                                    <tr>
                                        <th style="text-align:center;"></th>
                                        <th style="text-align:center;" colspan="3">NAME OF EMPLOYEE</th>
                                        <th style="text-align:center;">GSIS POLICY NO./</th>
                                        <th style="text-align:center;"></th>
                                        <th style="text-align:center;">MONTHLY</th>
                                        <th style="text-align:center;">MONTH</th>
                                        <th style="text-align:center;">PERSONAL</th>
                                        <th style="text-align:center;">EMPLOYER'S</th>
                                        <th style="text-align:center;" colspan="3"></th>
                                    </tr>
                                    <tr>
                                        <th style="text-align:center;"></th>
                                        <th style="text-align:center;"> FAMILY NAME</th>
                                        <th style="text-align:center;">GIVEN NAME</th>
                                        <th style="text-align:center;">M.I.</th>
                                        <th style="text-align:center;">PHILHEALTH ID NO.</th>
                                        <th style="text-align:center;">POSITION TITLE</th>
                                        <th style="text-align:center;">COMPENSATION</th>
                                        <th style="text-align:center;">COVERAGE</th>
                                        <th style="text-align:center;">SHARE</th>
                                        <th style="text-align:center;">SHARE</th>
                                        <th style="text-align:center;"></th>
                                        <th style="text-align:center;">TOTAL</th>
                                        <th style="text-align:center;">REMARKS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        for($v = $count1 ; $v < sizeof($payroll); $v++ ) { 
                                            $rowtotaldeduction = 0;
                                            $lname = ((isset($payroll[$v]['last_name']) && $payroll[$v]['last_name'] != "")?$this->Helper->decrypt($payroll[$v]['last_name'],$payroll[$v]['employee_id']):"");
                                            $fname = ((isset($payroll[$v]['first_name']) && $payroll[$v]['first_name'] != "")?"&nbsp;".$this->Helper->decrypt($payroll[$v]['first_name'],$payroll[$v]['employee_id']):"");
                                            $mname = ((isset($payroll[$v]['middle_name']) && $payroll[$v]['middle_name'] != "")?"&nbsp;".substr($this->Helper->decrypt($payroll[$v]['middle_name'],$payroll[$v]['employee_id']),0,1):"");
                                            $extension = ((isset($payroll[$v]['extension']) && $payroll[$v]['extension'] != "")?"&nbsp;".$this->Helper->decrypt($payroll[$v]['extension'],$payroll[$v]['employee_id']):"");
                                        ?>
                                        <tr>
                                            <td valign="top" style="text-align:left;"><?php echo ($v+1); ?></td>
                                            <td valign="top" style="text-align:left;"><?php echo $lname;?></td>
                                            <td valign="top" style="text-align:left;"><?php echo $fname;?></td>
                                            <td valign="top" style="text-align:center;"><?php echo $mname; ?></td>
                                            <td valign="middle" align="center"><?php echo $payroll[$v]['philhealth']; ?></td> 
                                            <td valign="middle" align="left"><?php echo $payroll[$v]['position_name']; ?></td> 
                                            <td valign="middle" align="right"><?php echo $payroll[$v]['salary']; ?></td> 
                                            <td valign="middle" align="center"><?php echo strtoupper(date('F Y',strtotime(@$payroll_period[0]['start_date']))); ?></td> 
                                            <td valign="middle" align="right"><?php echo number_format($payroll[$v]['philhealth_amt'] , 2);$page_total["philhealth_amt"] += $payroll[$v]['philhealth_amt']; $grand_total["philhealth_amt"] += $payroll[$v]['philhealth_amt'];  ?></td> 
                                            <td valign="middle" align="right"><?php echo number_format($payroll[$v]['philhealth_amt_employer'] , 2);$page_total["philhealth_amt_employer"] += $payroll[$v]['philhealth_amt_employer']; $grand_total["philhealth_amt_employer"] += $payroll[$v]['philhealth_amt_employer'];  ?></td> 
                                            <td valign="middle" align="center">=</td> 
                                            <td valign="middle" align="right"><?php echo number_format($payroll[$v]['philhealth_amt'] + $payroll[$v]['philhealth_amt_employer'],2)?></td> 
                                            <td valign="middle" align="right"></td>
                                        </tr>  
                                        <?php
                                        if($v == $count2){ $count1 += 1; break; }
                                        $rows++; 
                                        if($rows === 44) { $last_row = 1; } else { $last_row = 0; } 
                                        } 
                                    ?>
                                    <!-- START OF SUB-TOTAL PER PAGE -->
                                    <tr>
                                        <td valign="middle" align="right" style="font-weight: bold;" colspan="8"><b>SUB-TOTAL for page <?php echo $page; ?></b></td>
                                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["philhealth_amt"],2); ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["philhealth_amt_employer"],2); ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold;">=</td>
                                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["philhealth_amt"] + $page_total["philhealth_amt_employer"],2); ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold;"><?php //remarks?></td>
                                    </tr><br>
                                    <!-- END OF SUB-TOTAL PER PAGE -->

                                    <!-- START OF GRAND TOTAL -->
                                    <?php if($page == $total_page) { ?>
                                        <tr style="border: none;">
                                            <td valign="middle" align="right" style="font-weight: bold; border: none;" colspan="8">GRAND TOTAL</td>
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["philhealth_amt"],2); ?></td>
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["philhealth_amt_employer"],2); ?></td>
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;">=</td>
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["philhealth_amt"] + $grand_total["philhealth_amt_employer"],2); ?></td>
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php //echo number_format($grand_total["ec"],2); ?></td>
                                        </tr>
                                    <?php } ?>
                                    <!-- END OF GRAND TOTAL -->
                                </tbody>
                            </table>
                            <!-- START OF SIGNATORIES -->
                            <table style="width:100%;" id="ftTable"><br>
                                <tr>
                                    <td colspan="2"><b>PREPARED BY:</td>
                                    <td colspan="2"><b>CERTIFIED BY:</td>
                                </tr>
                                <tr><td>&nbsp;<td></td>&nbsp;</td></tr>
                                <tr><td>&nbsp;<td></td>&nbsp;</td></tr>
                                <tr>
                                    <td colspan="2"><b>RIZZA D. FRANCISCO</td>
                                    <td colspan="2"><b>JESUSA T. DELOS SANTOS</td>
                                </tr>
                                <tr>
                                    <td colspan="2">Administrative Assistant II</td>
                                    <td colspan="2">Administrative Officer V</td>
                                </tr>
                            </table>
                            <!-- END OF SIGNATORIES -->
                            <p class="page-break"></p>
                        </div>
                    </div>
                <?php
                    $count1 += 24;
                    $count2 += 25;
                    }
                ?>
                <!-- END OF FIRST PAGE -->

                <!-- START OF EXCEL TABLE -->                
                <?php
                    $page = 0;
                    $totalNum = 0; 
                    $numExperience = sizeof($payroll) / 45;
                    $wholeNumExperience = floor($numExperience);
                    $decNumExperience = $wholeNumExperience - $numExperience;
                    $totalNum = $wholeNumExperience;
                    if($decNumExperience < 0) $totalNum += 1;
                    $count1 = 0;
                    $count2 = 24;
                    $page_count = 1;
                    $last_row = 0;
                    $count = 1;
                    $rows = 0;
                    $grand_total = array(
                        'philhealth_amt'=>0.00, 'philhealth_amt_employer'=>0.00
                    );
                ?>                
                <div class="header-container" style="width:100%; display:none;" id="excelTablediv">
                    <?php for($x = 0; $x < $totalNum; $x++){  ?>
                        <table class="excelTable no-print" style="width:100%;border-bottom:0px;border-top:5px" id="hdTable">
                            <thead>
                                <tr>
                                    <td style="text-align:center;font-weight: bold;" colspan="13">REPUBLIC OF THE PHILIPPINES</td>
                                </tr>
                                <tr>
                                    <td style="text-align:center;font-weight: bold;" colspan="13">PHILIPPINE HEALTH INSURANCE CORPORATION </td>
                                </tr>
                                <tr>
                                    <td style="text-align:center;font-weight: normal !important;" colspan="13">11th - 17th Floors, CityState Centre, 709 Shaw Boulevard, Barangay Oranbo, Pasig City</td>
                                </tr>
                                <tr>
                                    <td style="text-align:center;font-weight: normal !important;" colspan="13">637-9999, 637-9852 to 81 / www.philhealth.gov.ph</td>
                                </tr>
                            </thead>
                            <tr><td>&nbsp;</td></tr>
                            <tr>
                                <td></td>
                                <td style="text-align:left !important;font-weight: bold !important;" colspan="5">PHIL. HEALTH  REMITTANCE FORM NO. 1</td>
                                <td style="text-align:left !important;font-weight: normal !important;" colspan="7"><b>REMITTANCE BANK:</b>&emsp;  LAND BANK OF THE PHILIPPINES, East Ave., Ext., EDSA, Q.C.</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="text-align:left !important;font-weight: bold !important;" colspan="5"><b>RF - 01</b></td>
                                <td style="text-align:left !important;font-weight: normal !important;" colspan="7"><b>EMPLOYER I.D.:</b>&emsp;  14-037410004-7</td>
                            </tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr>
                                <td></td>
                                <td style="text-align:left !important;font-weight: bold !important;" colspan="5"><b>REGISTERED EMPLOYER NAME:</b>&emsp;  NATIONAL WATER RESOURCES BOARD</td>
                                <td style="text-align:left !important;font-weight: normal !important;" colspan="7"><b>EMPLOYER TIN:</b>&emsp;    000-795-636-NV</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="text-align:left !important;font-weight: bold !important;" colspan="5"><b>ADDRESS:</b>&emsp;    8th Floor NIA  Bldg., EDSA, Quezon City</td>
                                <td style="text-align:left !important;font-weight: normal !important;" colspan="7"><b>DATE PREPARED:</b>&emsp; </td>
                            </tr>
                        </table>
                        <?php
                            $total_per_page = $total_next_page = 24;
                            $total_page = floor(sizeof($payroll)/$total_per_page) + 1;

                            $page_total = array(
                                'philhealth_amt'=>0.00, 'philhealth_amt_employer'=>0.00
                            );

                            if(( sizeof($payroll) < $total_next_page && sizeof($payroll) > floor($total_next_page*.5) ) || (sizeof($payroll) > $total_next_page && (sizeof($payroll)) - ($total_next_page * $total_page) > floor($total_next_page*.5) )){
                                $total_page = floor(sizeof($payroll)/$total_per_page) + 2;
                            }
                        ?>
                        <div class="table-container"><?php $page++; ?>
                            <table class="table table-hover table-striped excelTable no-print" style="width:100%;" id="excelTable">
                                <thead>
                                    <tr>
                                        <th style="text-align:center;"></th>
                                        <th style="text-align:center;" colspan="3">NAME OF EMPLOYEE</th>
                                        <th style="text-align:center;">GSIS POLICY NO./</th>
                                        <th style="text-align:center;"></th>
                                        <th style="text-align:center;">MONTHLY</th>
                                        <th style="text-align:center;">MONTH</th>
                                        <th style="text-align:center;">PERSONAL</th>
                                        <th style="text-align:center;">EMPLOYER'S</th>
                                        <th style="text-align:center;" colspan="3"></th>
                                    </tr>
                                    <tr>
                                        <th style="text-align:center;"></th>
                                        <th style="text-align:center;"> FAMILY NAME</th>
                                        <th style="text-align:center;">GIVEN NAME</th>
                                        <th style="text-align:center;">M.I.</th>
                                        <th style="text-align:center;">PHILHEALTH ID NO.</th>
                                        <th style="text-align:center;">POSITION TITLE</th>
                                        <th style="text-align:center;">COMPENSATION</th>
                                        <th style="text-align:center;">COVERAGE</th>
                                        <th style="text-align:center;">SHARE</th>
                                        <th style="text-align:center;">SHARE</th>
                                        <th style="text-align:center;"></th>
                                        <th style="text-align:center;">TOTAL</th>
                                        <th style="text-align:center;">REMARKS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        for($v = $count1 ; $v < sizeof($payroll); $v++ ) { 
                                            $rowtotaldeduction = 0;
                                            $lname = ((isset($payroll[$v]['last_name']) && $payroll[$v]['last_name'] != "")?$this->Helper->decrypt($payroll[$v]['last_name'],$payroll[$v]['employee_id']):"");
                                            $fname = ((isset($payroll[$v]['first_name']) && $payroll[$v]['first_name'] != "")?"&nbsp;".$this->Helper->decrypt($payroll[$v]['first_name'],$payroll[$v]['employee_id']):"");
                                            $mname = ((isset($payroll[$v]['middle_name']) && $payroll[$v]['middle_name'] != "")?"&nbsp;".substr($this->Helper->decrypt($payroll[$v]['middle_name'],$payroll[$v]['employee_id']),0,1):"");
                                            $extension = ((isset($payroll[$v]['extension']) && $payroll[$v]['extension'] != "")?"&nbsp;".$this->Helper->decrypt($payroll[$v]['extension'],$payroll[$v]['employee_id']):"");
                                        ?>
                                        <tr>
                                            <td valign="top" style="text-align:left;"><?php echo ($v+1); ?></td>
                                            <td valign="top" style="text-align:left;"><?php echo $lname;?></td>
                                            <td valign="top" style="text-align:left;"><?php echo $fname;?></td>
                                            <td valign="top" style="text-align:center;"><?php echo $mname; ?></td>
                                            <td valign="middle" align="center"><?php echo $payroll[$v]['philhealth']; ?></td> 
                                            <td valign="middle" align="left"><?php echo $payroll[$v]['position_name']; ?></td> 
                                            <td valign="middle" align="right"><?php echo $payroll[$v]['salary']; ?></td> 
                                            <td valign="middle" align="center"><?php echo strtoupper(date('F Y',strtotime(@$payroll_period[0]['start_date']))); ?></td> 
                                            <td valign="middle" align="right"><?php echo number_format($payroll[$v]['philhealth_amt'] , 2);$page_total["philhealth_amt"] += $payroll[$v]['philhealth_amt']; $grand_total["philhealth_amt"] += $payroll[$v]['philhealth_amt'];  ?></td> 
                                            <td valign="middle" align="right"><?php echo number_format($payroll[$v]['philhealth_amt_employer'] , 2);$page_total["philhealth_amt_employer"] += $payroll[$v]['philhealth_amt_employer']; $grand_total["philhealth_amt_employer"] += $payroll[$v]['philhealth_amt_employer'];  ?></td> 
                                            <td valign="middle" align="center">=</td> 
                                            <td valign="middle" align="right"><?php echo number_format($payroll[$v]['philhealth_amt'] + $payroll[$v]['philhealth_amt_employer'],2)?></td> 
                                            <td valign="middle" align="right"></td>
                                        </tr>  
                                        <?php
                                        if($v == $count2){ $count1 += 1; break; }
                                        $rows++; 
                                        if($rows === 44) { $last_row = 1; } else { $last_row = 0; } 
                                        } 
                                    ?>
                                    <!-- START OF SUB-TOTAL PER PAGE -->
                                    <tr>
                                        <td valign="middle" align="center" style="font-weight: bold;" colspan="8"><b>SUB-TOTAL for page <?php echo $page; ?></b></td>
                                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["philhealth_amt"],2); ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["philhealth_amt_employer"],2); ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold;"></td>
                                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["philhealth_amt"] + $page_total["philhealth_amt_employer"],2); ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold;"><?php //remarks?></td>
                                    </tr>
                                    <tr><td>&nbsp;</td></tr>
                                    <!-- END OF SUB-TOTAL PER PAGE -->

                                    <!-- START OF GRAND TOTAL -->
                                    <?php if($page == $total_page) { ?>
                                        <tr style="border: none;">
                                            <td valign="middle" align="right" style="font-weight: bold; border: none;" colspan="8">GRAND TOTAL</td>
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["philhealth_amt"],2); ?></td>
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["philhealth_amt_employer"],2); ?></td>
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php //echo number_format($grand_total["union_dues_amt"],2); ?></td>
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php //echo number_format($grand_total["sss_gsis_amt_employer"],2); ?></td>
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php //echo number_format($grand_total["ec"],2); ?></td>
                                        </tr>
                                        <tr><td>&nbsp;</td></tr>
                                    <?php } ?>
                                    <!-- END OF GRAND TOTAL -->
                                </tbody>
                            </table>
                            <!-- START OF SIGNATORIES -->
                            <table class="excelTable no-print" style="width:100%;" id="ftTable"><br>
                                <tr>
                                    <td></td>
                                    <td style="text-align:left;font-weight: normal;" colspan="5"><b>PREPARED BY:</td>
                                    <td style="text-align:left;font-weight: normal;" colspan="5"><b>CERTIFIED BY:</td>
                                </tr>
                                <tr><td>&nbsp;<td></td>&nbsp;</td></tr>
                                <tr><td>&nbsp;<td></td>&nbsp;</td></tr>
                                <tr>
                                    <td></td>
                                    <td style="text-align:left;font-weight: normal;" colspan="5"><b>RIZZA D. FRANCISCO</td>
                                    <td style="text-align:left;font-weight: normal;" colspan="5"><b>JESUSA T. DELOS SANTOS</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td style="text-align:left;font-weight: normal;" colspan="5">Administrative Assistant II</td>
                                    <td style="text-align:left;font-weight: normal;" colspan="5">Administrative Officer V</td>
                                </tr>
                            </table>
                            <!-- END OF SIGNATORIES -->
                            <p class="page-break"></p>
                        </div>
                    <?php
                        $count1 += 24;
                        $count2 += 25;
                        }
                    ?>
                </div>
                <!-- END OF EXCEL TABLE -->
            </div>
        </div>
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
<script>
    function saveAsExcel() {
        document.getElementById("excelTablediv").style.display = "block";
        $('.excelTable').tableExport({
            type: "excel",
            fileName: "PhilHealth Remittance Report - Regular"
        });
        setTimeout(() => {
        const div = document.getElementById('excelTablediv');
            div.style.display = 'none';
        }, 1000); // time in milliseconds
    }
</script>