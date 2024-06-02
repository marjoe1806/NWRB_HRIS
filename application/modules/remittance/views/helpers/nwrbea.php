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
                        body { font-family:Calibri; font-size: 9; color: black; }
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
                    $count2 = 44;
                    $page_count = 1;
                    $last_row = 0;
                    $count = 1;
                    $rows = 0;
                    $grand_total = array(
                        'union_dues_amt'=>0.00, 'nwrbea_project'=>0.00, 'nwrbea_cashLoan'=>0.00, 'nwrbea_emergency'=>0.00
                    );
                ?>                
                <?php for($x = 0; $x < $totalNum; $x++){  ?>
                    <div class="header-container" style="width:100%;">
                        <table style="width:100%;border-bottom:0px;border-top:5px" id="hdTable">
                            <thead>
                                <tr>
                                    <td style="text-align:center;font-weight: bold;" colspan="9">NATIONAL WATER RESOURCES BOARD EMPLOYEES ASSOCIATION</td>
                                </tr>
                                <tr>
                                    <td style="text-align:center;font-weight: bold;" colspan="9">REMITTANCES FOR THE MONTH OF <?php echo strtoupper(date('F Y',strtotime(@$payroll_period[0]['start_date']))); ?></td>
                                </tr>
                                <tr><td>&nbsp;</td></tr>
                                <tr>
                                    <td><b>Office Code:&emsp; B1331</b></td>
                                </tr>
                                <tr>
                                    <td><b>Office Name:&emsp; NATIONAL WATER RESOURCES BOARD</b></td>
                                </tr>
                                <tr>
                                    <td><b>Office Address:&emsp; 8th Floor, NIA Building, EDSA, Quezon City</b></td>
                                </tr>
                                <tr><td>&nbsp;</td></tr>
                            </thead>
                        </table>
                        <?php
                            $total_per_page = $total_next_page = 44;
                            $total_page = floor(sizeof($payroll)/$total_per_page) + 1;

                            $page_total = array(
                                'union_dues_amt'=>0.00, 'nwrbea_project'=>0.00, 'nwrbea_cashLoan'=>0.00, 'nwrbea_emergency'=>0.00
                            );

                            if(( sizeof($payroll) < $total_next_page && sizeof($payroll) > floor($total_next_page*.5) ) || (sizeof($payroll) > $total_next_page && (sizeof($payroll)) - ($total_next_page * $total_page) > floor($total_next_page*.5) )){
                                $total_page = floor(sizeof($payroll)/$total_per_page) + 2;
                            }
                        ?>
                        <div class="table-container"><?php $page++; ?>
                            <table style="width:100%;" id="tmpTable">
                                <thead>
                                    <tr>
                                        <th style="text-align:center;"></th>
                                        <th style="text-align:center;">EMPLOYEE NAME</th>
                                        <th style="text-align:center;">MONTHLY CONT.</th>
                                        <th style="text-align:center;">CASH LOAN</th>
                                        <th style="text-align:center;">REMARKS/<br> NO. OF<br> PAY'T</th>
                                        <th style="text-align:center;">EMERGENCY<br> LOAN</th>
                                        <th style="text-align:center;">REMARKS/<br> NO. OF<br> PAY'T</th>
                                        <th style="text-align:center;">NWRBEA PROJ</th>
                                        <th style="text-align:center;">REMARKS/<br> NO. OF<br> PAY'T</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        for($v = $count1 ; $v < sizeof($payroll); $v++ ) { 
                                            $rowtotaldeduction = 0;
                                            $lname = ((isset($payroll[$v]['last_name']) && $payroll[$v]['last_name'] != "")?$this->Helper->decrypt($payroll[$v]['last_name'],$payroll[$v]['employee_id']):"");
                                            $fname = ((isset($payroll[$v]['first_name']) && $payroll[$v]['first_name'] != "")?"&emsp;&nbsp;".$this->Helper->decrypt($payroll[$v]['first_name'],$payroll[$v]['employee_id']):"");
                                            $mname = ((isset($payroll[$v]['middle_name']) && $payroll[$v]['middle_name'] != "")?"&nbsp;".substr($this->Helper->decrypt($payroll[$v]['middle_name'],$payroll[$v]['employee_id']),0,1):"");
                                            $extension = ((isset($payroll[$v]['extension']) && $payroll[$v]['extension'] != "")?"&nbsp;".$this->Helper->decrypt($payroll[$v]['extension'],$payroll[$v]['employee_id']):"");
                                        ?>
                                        <tr>
                                            <td valign="top" style="text-align:left;font-weight: bold;"><?php echo ($v+1); ?></td>
                                            <td valign="top" style="text-align:left;font-weight: bold;"><?php echo $lname.','.$fname.' '.$mname.' '.$extension; ?></td>
                                            <td valign="middle" align="right"><?php echo number_format($payroll[$v]['union_dues_amt'] , 2);$page_total["union_dues_amt"] += $payroll[$v]['union_dues_amt']; $grand_total["union_dues_amt"] += $payroll[$v]['union_dues_amt'];  ?></td> 
                                            <td valign="middle" align="right"><?php //echo date('m/d/Y',strtotime($payroll[$v]['birthday'])); ?></td> 
                                            <td valign="middle" align="right"><?php //echo $payroll[$v]['crn'] ?></td> 
                                            <td valign="middle" align="right"><?php //echo number_format($payroll[$v]['basic_pay'],2); $page_total["salary"] += $payroll[$v]['basic_pay'];?></td>
                                            <td valign="middle" align="right"><?php //echo $payroll[$v]['effectivity_date'] ?></td> 
                                            <td valign="middle" align="right"><?php //echo number_format($payroll[$v]['ec'] , 2); ?></td>
                                            <td valign="middle" align="right"><?php //echo number_format($payroll[$v]['ecard'] , 2); ?></td>
                                        </tr>  
                                        <?php
                                        if($v == $count2){ $count1 += 1; break; }
                                        $rows++; 
                                        if($rows === 44) { $last_row = 1; } else { $last_row = 0; } 
                                        } 
                                    ?>
                                    <!-- START OF SUB-TOTAL PER PAGE -->
                                    <tr>
                                        <td valign="middle" align="center" style="font-weight: bold;" colspan="2"><b>SUB-TOTAL for page <?php echo $page; ?></b></td>
                                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["union_dues_amt"],2); ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold;"><?php //echo number_format($page_total["sss_gsis_amt"],2); $grand_total["sss_gsis_amt"] += $page_total['sss_gsis_amt']; ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold;"><?php //echo number_format($page_total["sss_gsis_amt_employer"],2); $grand_total["sss_gsis_amt_employer"] += $page_total['sss_gsis_amt_employer']; ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold;"><?php //echo number_format($page_total["ec"],2); ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold;"><?php //echo number_format($page_total["gsis_consolidated"],2); $grand_total["gsis_consolidated"] += $page_total["gsis_consolidated"];  ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold;"><?php //echo number_format($page_total["ecard"],2); ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold;"><?php //echo number_format($page_total["salary_loan"],2); ?></td>
                                    </tr><br>
                                    <!-- END OF SUB-TOTAL PER PAGE -->

                                    <!-- START OF GRAND TOTAL -->
                                    <?php if($page == $total_page) { ?>
                                        <tr style="border: none;">
                                            <td valign="middle" align="left" style="font-weight: bold; border: none;" colspan="2">TOTAL</td>
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["union_dues_amt"],2); ?></td>
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php //echo number_format($grand_total["union_dues_amt"],2); ?></td>
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php //echo number_format($grand_total["sss_gsis_amt_employer"],2); ?></td>
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php //echo number_format($grand_total["ec"],2); ?></td>
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php //echo number_format($grand_total["gsis_consolidated"],2); ?></td>
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php //echo number_format($grand_total["ecard"],2); ?></td>
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php //echo number_format($grand_total["salary_loan"],2); ?></td>
                                        </tr>
                                        <tr style="border: none;">
                                            <td valign="middle" align="left" style="font-weight: bold; border: none;" colspan="2"><b>GRAND TOTAL</b></td>                                           
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;">
                                            <?php echo number_format(
                                                $grand_total["union_dues_amt"]
                                                ,2); ?>
                                        </td>
                                        </tr>
                                    <?php } ?>
                                    <!-- END OF GRAND TOTAL -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php
                    $count1 += 44;
                    $count2 += 45;
                    }
                ?>
                <!-- END OF FIRST PAGE -->

                <!-- START OF EXCEL TABLE -->
                <?php
                    $count1 = 0;
                    $grand_total = array(                        
                        'union_dues_amt'=>0.00, 'nwrbea_project'=>0.00, 'nwrbea_cashLoan'=>0.00, 'nwrbea_emergency'=>0.00
                    );
                    $page_total = array(
                        'union_dues_amt'=>0.00, 'nwrbea_project'=>0.00, 'nwrbea_cashLoan'=>0.00, 'nwrbea_emergency'=>0.00
                    );
                ?>
                <div class="header-container" style="width:100%; display:none;" id="excelTablediv">
                    <table class="excelTable no-print" style="width:100%;border-bottom:0px;border-top:5px" id="hdTable">
                        <thead>
                            <tr>
                                <td style="text-align:center;font-weight: bold;" colspan="9">NATIONAL WATER RESOURCES BOARD EMPLOYEES ASSOCIATION</td>
                            </tr>
                            <tr>
                                <td style="text-align:center;font-weight: bold;" colspan="9">REMITTANCES FOR THE MONTH OF <?php echo strtoupper(date('F Y',strtotime(@$payroll_period[0]['start_date']))); ?></td>
                            </tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr>
                                <td></td>
                                <td style="text-align:left;font-weight:bold;">Office Code:</td>
                                <td style="text-align:left;font-weight:bold;">B1331</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="text-align:left;font-weight:bold;">Office Name:</td>
                                <td style="text-align:left;font-weight:bold;">NATIONAL WATER RESOURCES BOARD</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="text-align:left;font-weight:bold;">Office Address:</td>
                                <td style="text-align:left;font-weight:bold;">8th Floor, NIA Building, EDSA, Quezon City</td>
                            </tr>
                            <tr><td>&nbsp;</td></tr>
                        </thead>
                    </table>
                    <div class="table-container">
                        <table class="table table-hover table-striped excelTable no-print" style="width:100%;" id="excelTable">
                            <thead>
                                <tr>
                                    <th style="text-align:center;"></th>
                                    <th style="text-align:center;">EMPLOYEE NAME</th>
                                    <th style="text-align:center;">MONTHLY CONT.</th>
                                    <th style="text-align:center;">CASH LOAN</th>
                                    <th style="text-align:center;">REMARKS/<br> NO. OF<br> PAY'T</th>
                                    <th style="text-align:center;">EMERGENCY<br> LOAN</th>
                                    <th style="text-align:center;">REMARKS/<br> NO. OF<br> PAY'T</th>
                                    <th style="text-align:center;">NWRBEA PROJ</th>
                                    <th style="text-align:center;">REMARKS/<br> NO. OF<br> PAY'T</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <?php 
                                        for($v = $count1 ; $v < sizeof($payroll); $v++ ) { 
                                            $rowtotaldeduction = 0;
                                            $lname = ((isset($payroll[$v]['last_name']) && $payroll[$v]['last_name'] != "")?$this->Helper->decrypt($payroll[$v]['last_name'],$payroll[$v]['employee_id']):"");
                                            $fname = ((isset($payroll[$v]['first_name']) && $payroll[$v]['first_name'] != "")?"&emsp;&nbsp;".$this->Helper->decrypt($payroll[$v]['first_name'],$payroll[$v]['employee_id']):"");
                                            $mname = ((isset($payroll[$v]['middle_name']) && $payroll[$v]['middle_name'] != "")?"&nbsp;".substr($this->Helper->decrypt($payroll[$v]['middle_name'],$payroll[$v]['employee_id']),0,1):"");
                                            $extension = ((isset($payroll[$v]['extension']) && $payroll[$v]['extension'] != "")?"&nbsp;".$this->Helper->decrypt($payroll[$v]['extension'],$payroll[$v]['employee_id']):"");
                                        ?>
                                        <tr>
                                            <td valign="top" style="text-align:left;font-weight: bold;"><?php echo ($v+1); ?></td>
                                            <td valign="top" style="text-align:left;font-weight: bold;"><?php echo $lname.','.$fname.' '.$mname.' '.$extension; ?></td>
                                            <td valign="middle" align="right"><?php echo number_format($payroll[$v]['union_dues_amt'] , 2);$page_total["union_dues_amt"] += $payroll[$v]['union_dues_amt']; ?></td> 
                                            <td valign="middle" align="right"><?php //echo date('m/d/Y',strtotime($payroll[$v]['birthday'])); ?></td> 
                                            <td valign="middle" align="right"><?php //echo $payroll[$v]['crn'] ?></td> 
                                            <td valign="middle" align="right"><?php //echo number_format($payroll[$v]['basic_pay'],2); $page_total["salary"] += $payroll[$v]['basic_pay'];?></td>
                                            <td valign="middle" align="right"><?php //echo $payroll[$v]['effectivity_date'] ?></td> 
                                            <td valign="middle" align="right"><?php //echo number_format($payroll[$v]['ec'] , 2); ?></td>
                                            <td valign="middle" align="right"><?php //echo number_format($payroll[$v]['ecard'] , 2); ?></td>
                                        </tr>  
                                        <?php
                                        if($v == $count2){ $count1 += 1; break; }
                                        $rows++; 
                                        if($rows === 44) { $last_row = 1; } else { $last_row = 0; } 
                                        } 
                                    ?>
                                    <!-- START OF SUB-TOTAL PER PAGE -->
                                    <tr>
                                        <td valign="middle" align="center" style="font-weight: bold;" colspan="2"><b>SUB-TOTAL for page <?php echo $page; ?></b></td>
                                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["union_dues_amt"],2); $grand_total["union_dues_amt"] += $page_total['union_dues_amt']; ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold;"><?php //echo number_format($page_total["sss_gsis_amt"],2); $grand_total["sss_gsis_amt"] += $page_total['sss_gsis_amt']; ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold;"><?php //echo number_format($page_total["sss_gsis_amt_employer"],2); $grand_total["sss_gsis_amt_employer"] += $page_total['sss_gsis_amt_employer']; ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold;"><?php //echo number_format($page_total["ec"],2); ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold;"><?php //echo number_format($page_total["gsis_consolidated"],2); $grand_total["gsis_consolidated"] += $page_total["gsis_consolidated"];  ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold;"><?php //echo number_format($page_total["ecard"],2); ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold;"><?php //echo number_format($page_total["salary_loan"],2); ?></td>
                                    </tr><br>
                                    <!-- END OF SUB-TOTAL PER PAGE -->

                                    <!-- START OF GRAND TOTAL -->
                                    <?php if($page == $total_page) { ?>
                                        <tr style="border: none;"><td>&nbsp;</td></tr>
                                        <tr style="border: none;">
                                            <td valign="middle" align="left" style="font-weight: bold; border: none;" colspan="2">TOTAL</td>
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["union_dues_amt"],2); ?></td>
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php //echo number_format($grand_total["union_dues_amt"],2); ?></td>
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php //echo number_format($grand_total["sss_gsis_amt_employer"],2); ?></td>
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php //echo number_format($grand_total["ec"],2); ?></td>
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php //echo number_format($grand_total["gsis_consolidated"],2); ?></td>
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php //echo number_format($grand_total["ecard"],2); ?></td>
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php //echo number_format($grand_total["salary_loan"],2); ?></td>
                                        </tr>
                                        <tr style="border: none;">
                                            <td valign="middle" align="left" style="font-weight: bold; border: none;" colspan="2"><b>GRAND TOTAL</b></td>                                           
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;">
                                            <?php echo number_format(
                                                $grand_total["union_dues_amt"]
                                            ,2); ?>
                                            </td>
                                        </tr>
                                        <tr style="border: none;"><td>&nbsp;</td></tr>
                                    <?php } ?>
                                    <!-- END OF GRAND TOTAL -->
                                </tbody>
                        </table>
                    </div>
                </div>
                <!-- END OF EXCEL TABLE -->

                <!-- START OF SIGNATORIES -->
                <table class="excelTable" style="width:100%;" id="ftTable"><br>
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
            fileName: "NWRBEA Remittance Report"
        });
        setTimeout(() => {
        const div = document.getElementById('excelTablediv');
            div.style.display = 'none';
        }, 1000); // time in milliseconds
    }
</script>