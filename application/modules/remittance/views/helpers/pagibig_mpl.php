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
                    @page {
                        size: A4 landscape;
                    }
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
                    $payroll = array_values($payroll);
                    $numExperience = sizeof($payroll) / 40;
                    $wholeNumExperience = floor($numExperience);
                    $decNumExperience = $wholeNumExperience - $numExperience;
                    $totalNum = $wholeNumExperience;
                    if($decNumExperience < 0) $totalNum += 1;
                    $count1 = 0;
                    $count2 = 38;
                    $page_count = 1;
                    $last_row = 0;
                    $count = 1;
                    $rows = 0;
                    $grand_total = array(
                        'mpl_amt'=>0.00
                    );
                ?>
                <div class="header-container" style="width:100%;">
                    <table style="width:100%;border-bottom:0px;border-top:5px" id="hdTable">
                        <thead>
                            <tr>
                                <td><b>Employer ID:&emsp; 202240010005</b></td>
                            </tr>
                            <tr>
                                <td><b>Employer Name:&emsp; NATIONAL WATER RESOURCES BOARD</b></td>
                            </tr>
                            <tr>
                                <td><b>Address:&emsp; 8TH FLR NIA BLDG EDSA QUEZON CITY</b></td>
                                <td style="text-align:right;"><b>PERIOD COVERED:&emsp;<?php echo strtoupper(date('F Y',strtotime(@$payroll_period[0]['start_date']))); ?></b></td>
                            </tr>
                        </thead>
                    </table>
                    <?php for($x = 0; $x < $totalNum; $x++){  ?>
                    <?php
                        $total_per_page = $total_next_page = 40;
                        $total_page = floor(sizeof($payroll)/$total_per_page) + 1;

                        $page_total = array(
                            'mpl_amt'=>0.00
                        );

                        if(( sizeof($payroll) < $total_next_page && sizeof($payroll) > floor($total_next_page*.5) ) || (sizeof($payroll) > $total_next_page && (sizeof($payroll)) - ($total_next_page * $total_page) > floor($total_next_page*.5) )){
                            $total_page = floor(sizeof($payroll)/$total_per_page) + 2;
                        }
                    ?>
                    <div class="table-container"><?php $page++; ?>
                        <table class="table table-hover table-striped" style="width:100%;" id="tmpTable">
                            <thead>
                                <tr>
                                    <th style="text-align:center;">Pag-IBIG ID/RTN</th>
                                    <th style="text-align:center;">APPLICATION NO/<br>AGREEMENT NO</th>
                                    <th style="text-align:center;">LAST NAME</th>
                                    <th style="text-align:center;">FIRST NAME</th>
                                    <th style="text-align:center;">NAME <br>EXT.</th>
                                    <th style="text-align:center;">MIDDLE NAME</th>
                                    <th style="text-align:center;">LOAN TYPE</th>
                                    <th style="text-align:center;">AMOUNT</th>
                                    <th style="text-align:center;">REMARKS</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                                for($v = $count1 ; $v < sizeof($payroll); $v++ ) { 
                                    $rowtotaldeduction = 0;
                                    $lname = ((isset($payroll[$v]['last_name']) && $payroll[$v]['last_name'] != "")?$this->Helper->decrypt($payroll[$v]['last_name'],$payroll[$v]['employee_id']):"");
                                    $fname = ((isset($payroll[$v]['first_name']) && $payroll[$v]['first_name'] != "")?"&nbsp;".$this->Helper->decrypt($payroll[$v]['first_name'],$payroll[$v]['employee_id']):"");
                                    $mname = ((isset($payroll[$v]['middle_name']) && $payroll[$v]['middle_name'] != "")?"&nbsp;".$this->Helper->decrypt($payroll[$v]['middle_name'],$payroll[$v]['employee_id']):"");
                                    $extension = ((isset($payroll[$v]['extension']) && $payroll[$v]['extension'] != "")?"&nbsp;".$this->Helper->decrypt($payroll[$v]['extension'],$payroll[$v]['employee_id']):"");
                                ?>
                                <tr>
                                    <td valign="middle" align="center"><?php echo $payroll[$v]['pagibig']; ?></td> 
                                    <td valign="top" style="text-align:center;"></td>
                                    <td valign="top" style="text-align:left;"><?php echo $lname;?></td>
                                    <td valign="top" style="text-align:left;"><?php echo $fname;?></td>
                                    <td valign="top" style="text-align:left;"><?php echo $extension;?></td>
                                    <td valign="top" style="text-align:left;"><?php echo $mname; ?></td>
                                    <td valign="top" style="text-align:center;">MP3</td>
                                    <td valign="middle" align="right"><?php $val = 0; $val = get_key(21,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $page_total["mpl_amt"] += $val; $grand_total["mpl_amt"] += $val;?></td>
                                    <td valign="middle" align="right"></td>
                                </tr>  
                                <?php
                                if($v == $count2){ $count1 += 1; break; }
                                $rows++; 
                                if($rows === 38) { $last_row = 1; } else { $last_row = 0; } 
                                } 
                            ?>
                            <!-- START OF SUB-TOTAL PER PAGE -->
                            <tr>
                                <td valign="middle" align="right" style="font-weight: bold;" colspan="7"><b>SUB-TOTAL for page <?php echo $page; ?></b></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["mpl_amt"],2); ?></td>
                                <td valign="middle" align="right" style="font-weight: bold;"><?php //remarks?></td>
                            </tr><br>
                            <!-- END OF SUB-TOTAL PER PAGE -->

                            <!-- START OF GRAND TOTAL -->
                            <?php if($page == $total_page) { ?>
                                <tr style="border: none;">
                                    <td valign="middle" align="left" style="font-weight: bold; border: none;" colspan="7">TOTAL STL AMORTIZATION</td>
                                    <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["mpl_amt"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"></td>
                                </tr>
                            <?php } ?>
                            <!-- END OF GRAND TOTAL -->
                            </tbody>
                        </table>
                        <!-- <p class="page-break"></p> -->
                    </div>
                    <!-- END OF FIRST PAGE -->
                    <?php
                        $count1 += 38;
                        $count2 += 39;
                        }
                    ?>
                </div>

                <!-- START OF EXCEL TABLE -->                
                <?php
                    $page = 0;
                    $totalNum = 0; 
                    $payroll = array_values($payroll);
                    $numExperience = sizeof($payroll) / 35;
                    $wholeNumExperience = floor($numExperience);
                    $decNumExperience = $wholeNumExperience - $numExperience;
                    $totalNum = $wholeNumExperience;
                    if($decNumExperience < 0) $totalNum += 1;
                    $count1 = 0;
                    $count2 = 29;
                    $page_count = 1;
                    $last_row = 0;
                    $count = 1;
                    $rows = 0;
                    $grand_total = array(
                        'mpl_amt'=>0.00
                    );
                ?>                
                <div class="header-container" style="width:100%; display:none;" id="excelTablediv">
                    <table class="excelTable no-print" style="width:100%;border-bottom:0px;border-top:5px" id="hdTable">
                        <thead>
                            <tr>
                                <td><b>Employer ID:&emsp;</b></td>
                                <td colspan="5"><b>202240010005</b></td>
                            </tr>
                            <tr>
                                <td><b>Employer Name:&emsp;</b></td>
                                <td colspan="5"><b>NATIONAL WATER RESOURCES BOARD</b></td>
                            </tr>
                            <tr>
                                <td><b>Address:&emsp;</b></td>
                                <td colspan="5"><b>8TH FLR NIA BLDG EDSA QUEZON CITY</b></td>
                                <td colspan="3" style="text-align:right;"><b>PERIOD COVERED:&emsp;<?php echo strtoupper(date('F Y',strtotime(@$payroll_period[0]['start_date']))); ?></b></td>
                            </tr>
                        </thead>
                    </table>
                    <div class="table-container">
                        <table class="table table-hover table-striped excelTable no-print" style="width:100%;" id="excelTable">
                            <thead>
                                <tr>
                                    <th style="text-align:center;">Pag-IBIG ID/RTN</th>
                                    <th style="text-align:center;">APPLICATION NO/AGREEMENT NO</th>
                                    <th style="text-align:center;">LAST NAME</th>
                                    <th style="text-align:center;">FIRST NAME</th>
                                    <th style="text-align:center;">NAME EXT.</th>
                                    <th style="text-align:center;">MIDDLE NAME</th>
                                    <th style="text-align:center;">LOAN TYPE</th>
                                    <th style="text-align:center;">AMOUNT</th>
                                    <th style="text-align:center;">REMARKS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for($x = 0; $x < $totalNum; $x++){  ?>
                                <?php
                                    $total_per_page = $total_next_page = 35;
                                    $total_page = floor(sizeof($payroll)/$total_per_page) + 1;

                                    $page_total = array(
                                        'mpl_amt'=>0.00
                                    );

                                    if(( sizeof($payroll) < $total_next_page && sizeof($payroll) > floor($total_next_page*.5) ) || (sizeof($payroll) > $total_next_page && (sizeof($payroll)) - ($total_next_page * $total_page) > floor($total_next_page*.5) )){
                                        $total_page = floor(sizeof($payroll)/$total_per_page) + 2;
                                    }
                                ?>
                                <?php $page++; ?>
                                <?php 
                                    for($v = $count1 ; $v < sizeof($payroll); $v++ ) { 
                                        $rowtotaldeduction = 0;
                                        $lname = ((isset($payroll[$v]['last_name']) && $payroll[$v]['last_name'] != "")?$this->Helper->decrypt($payroll[$v]['last_name'],$payroll[$v]['employee_id']):"");
                                        $fname = ((isset($payroll[$v]['first_name']) && $payroll[$v]['first_name'] != "")?"&nbsp;".$this->Helper->decrypt($payroll[$v]['first_name'],$payroll[$v]['employee_id']):"");
                                        $mname = ((isset($payroll[$v]['middle_name']) && $payroll[$v]['middle_name'] != "")?"&nbsp;".$this->Helper->decrypt($payroll[$v]['middle_name'],$payroll[$v]['employee_id']):"");
                                        $extension = ((isset($payroll[$v]['extension']) && $payroll[$v]['extension'] != "")?"&nbsp;".$this->Helper->decrypt($payroll[$v]['extension'],$payroll[$v]['employee_id']):"");
                                    ?>
                                    <tr>
                                        <td valign="middle" align="center"><?php echo $payroll[$v]['pagibig']; ?></td> 
                                        <td valign="top" style="text-align:center;"></td>
                                        <td valign="top" style="text-align:left;"><?php echo $lname;?></td>
                                        <td valign="top" style="text-align:left;"><?php echo $fname;?></td>
                                        <td valign="top" style="text-align:left;"><?php echo $extension;?></td>
                                        <td valign="top" style="text-align:center;"><?php echo $mname; ?></td>
                                        <td valign="top" style="text-align:center;">MP3</td>
                                        <td valign="middle" align="right"><?php $val = 0; $val = get_key(21,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $page_total["mpl_amt"] += $val; $grand_total["mpl_amt"] += $val;?></td>
                                        <td valign="middle" align="right"></td>
                                    </tr>  
                                    <?php
                                    if($v == $count2){ $count1 += 1; break; }
                                    $rows++; 
                                    if($rows === 29) { $last_row = 1; } else { $last_row = 0; } 
                                    } 
                                ?>
                                <!-- START OF SUB-TOTAL PER PAGE -->
                                <tr>
                                    <td valign="middle" align="right" style="font-weight: bold;" colspan="7"><b>SUB-TOTAL for page <?php echo $page; ?></b></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($page_total["mpl_amt"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php //remarks?></td>
                                </tr>
                                <!-- END OF SUB-TOTAL PER PAGE -->

                                <!-- START OF GRAND TOTAL -->
                                <?php if($page == $total_page) { ?>
                                    <tr style="border: none;">
                                        <td valign="middle" align="left" style="font-weight: bold; border: none;" colspan="7">TOTAL STL AMORTIZATION</td>
                                        <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["mpl_amt"],2); ?></td>
                                        <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"></td>
                                    </tr>
                                    <tr><td>&nbsp;</td></tr>
                                <?php } ?>
                                <!-- END OF GRAND TOTAL -->
                                <?php
                                    $count1 += 29;
                                    $count2 += 30;
                                    }
                                ?>
                            </tbody>
                        </table>
                        <p class="page-break"></p>
                    </div>
                </div>
                <!-- END OF EXCEL TABLE -->

                <!-- START OF SIGNATORIES -->
                <table class="excelTable" style="width:100%;" id="ftTable"><br>
                    <thead>
                        <tr style="background-color:gray;" ><td colspan="12" style="font-weight:bold;text-align:center;">EMPLOYER CERTIFICATION</td></tr>
                    </thead>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td colspan="12">I hereby certify under pain of perjury that the information given and all statements made herein are true and correct to the best of my knowledge and belief.</td></tr>
                    <tr><td colspan="12">I further certify that my signature appearing herein is genuine and authentic.</td></tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td>&nbsp;</td></tr>
                    <!-- <tr>
                        <td colspan="2"><b>PREPARED BY:</td>
                        <td colspan="2"><b>CERTIFIED BY:</td>
                    </tr>
                    <tr><td>&nbsp;<td></td>&nbsp;</td></tr>
                    <tr><td>&nbsp;<td></td>&nbsp;</td></tr> -->
                    <tr>
                        <td colspan="4" style="font-weight:bold;text-align:center;"><b>JESUSA T. DELOS SANTOS</td>
                        <td colspan="3" style="font-weight:normal;text-align:center;"><b>Administrative Officer V</td>
                        <td>&nbsp;</td>
                        <td colspan="3" style="border-bottom:1px solid black;"></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="font-weight:normal;text-align:center;">HEAD OF OFFICE OR AUTHORIZED SIGNATORY   </td>
                        <td colspan="3" style="font-weight:normal;text-align:center;">DESIGNATION/POSITION </td>
                        <td>&nbsp;</td>
                        <td colspan="3" style="font-weight:normal;text-align:center;">DATE</td>
                    </tr>
                    </tr>
                    <tr>
                        <td colspan="4" style="font-weight:normal;text-align:center;">(Signature Over Printed Name)</td>
                    </tr>
                </table>
                <!-- END OF SIGNATORIES -->
                </div>
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
            fileName: "Pag-ibig MPL Remittance Report"
        });
        setTimeout(() => {
        const div = document.getElementById('excelTablediv');
            div.style.display = 'none';
        }, 1000); // time in milliseconds
    }
</script>