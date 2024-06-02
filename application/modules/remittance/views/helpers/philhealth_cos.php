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
                                    <td style="text-align:center;font-weight: bold;" colspan="8">GROUP NAME : GROUP OF JOW-NATIONAL WATER RESOURCES BOARD</td>
                                </tr>
                                <tr>
                                    <td style="text-align:center;font-weight: bold;" colspan="8">APPLICABLE PERIOD - <?php echo strtoupper(date('F Y',strtotime(@$payroll_period[0]['start_date']))); ?></td>
                                </tr>
                                <tr>
                                    <td style="text-align:center;font-weight: bold;" colspan="8">AMOUNT  - <span id="grand_total_header"></span></td>
                                </tr>
                                <tr>
                                    <td style="text-align:center;font-weight: bold;" colspan="8">POR#</td>
                                </tr>
                                <tr>
                                    <td style="text-align:center;font-weight: normal;" colspan="3"></td>
                                    <td style="text-align:center;font-weight: normal;" colspan="2"></td>
                                </tr>
                                <tr><td>&nbsp;</td></tr>
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
                                        <th style="text-align:center;" rowspan="2">No.</th>
                                        <th style="text-align:center;" rowspan="2">PIN</th>
                                        <th style="text-align:center;" colspan="4">COMPLETE NAME</th>
                                        <th style="text-align:center;" rowspan="2">DEPARTMENT</th>
                                        <th style="text-align:center;" rowspan="2">BIRTHDAY</th>
                                        <th style="text-align:center;" rowspan="2"><?php echo strtoupper(date('F Y',strtotime(@$payroll_period[0]['start_date']))); ?></th>
                                    </tr>
                                    <tr>
                                        <th style="text-align:center;">LAST NAME</th>
                                        <th style="text-align:center;">GIVEN NAME</th>
                                        <th style="text-align:center;">MIDDLE NAME</th>
                                        <th style="text-align:center;">NAME SUFFIX</th>
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
                                            <td valign="top" style="text-align:right;"><?php echo ($v+1); ?></td>
                                            <td valign="top" style="text-align:center;"><?php echo $payroll[$v]['philhealth']; ?></td> 
                                            <td valign="top" style="text-align:left;"><?php echo $lname;?></td>
                                            <td valign="top" style="text-align:left;"><?php echo $fname;?></td>
                                            <td valign="top" style="text-align:left;"><?php echo $mname; ?></td>
                                            <td valign="top" style="text-align:left;"><?php echo $extension; ?></td>
                                            <td valign="top" style="text-align:center;"><?php echo strtoupper($payroll[$v]['department_name']); ?></td>
                                            <td valign="middle" align="center"><?php echo strtoupper(date('m/d/Y',strtotime(@$payroll[$v]['birthday']))); ?></td> 
                                            <td valign="middle" align="right"><?php echo number_format($payroll[$v]['philhealth_amt'] , 2);$page_total["philhealth_amt"] += $payroll[$v]['philhealth_amt']; $grand_total["philhealth_amt"] += $payroll[$v]['philhealth_amt'];  ?></td> 
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
                                    </tr><br>
                                    <!-- END OF SUB-TOTAL PER PAGE -->

                                    <!-- START OF GRAND TOTAL -->
                                    <?php if($page == $total_page) { ?>
                                        <tr style="border: none;">
                                            <td valign="middle" align="right" style="font-weight: bold; border: none;" colspan="8">GRAND TOTAL</td>
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["philhealth_amt"],2); ?></td>
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
                                    <td style="text-align:center;font-weight: bold;" colspan="8">GROUP NAME : GROUP OF JOW-NATIONAL WATER RESOURCES BOARD</td>
                                </tr>
                                <tr>
                                    <td style="text-align:center;font-weight: bold;" colspan="8">APPLICABLE PERIOD - <?php echo strtoupper(date('F Y',strtotime(@$payroll_period[0]['start_date']))); ?></td>
                                </tr>
                                <tr>
                                    <td style="text-align:center;font-weight: bold;" colspan="8">AMOUNT  - <span id="grand_total_header_excel"></span></td>
                                </tr>
                                <tr>
                                    <td style="text-align:center;font-weight: bold;" colspan="8">POR#</td>
                                </tr>
                                <tr>
                                    <td style="text-align:center;font-weight: normal;" colspan="3"></td>
                                    <td style="text-align:center;font-weight: normal;" colspan="2"></td>
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
                            <table class="table table-hover table-striped excelTable no-print" style="width:100%;" id="excelTable">
                                <thead>
                                    <tr>
                                        <th style="text-align:center;" rowspan="2">No.</th>
                                        <th style="text-align:center;" rowspan="2">PIN</th>
                                        <th style="text-align:center;" colspan="4">COMPLETE NAME</th>
                                        <th style="text-align:center;" rowspan="2">BIRTHDAY</th>
                                        <th style="text-align:center;" rowspan="2">DEPARTMENT</th>
                                        <th style="text-align:center;" rowspan="2"><?php echo strtoupper(date('F Y',strtotime(@$payroll_period[0]['start_date']))); ?></th>
                                    </tr>
                                    <tr>
                                        <th style="text-align:center;">LAST NAME</th>
                                        <th style="text-align:center;">GIVEN NAME</th>
                                        <th style="text-align:center;">MIDDLE NAME</th>
                                        <th style="text-align:center;">NAME SUFFIX</th>
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
                                            <td valign="top" style="text-align:right;"><?php echo ($v+1); ?></td>
                                            <td valign="top" style="text-align:center;"><?php echo $payroll[$v]['philhealth']; ?></td> 
                                            <td valign="top" style="text-align:left;"><?php echo $lname;?></td>
                                            <td valign="top" style="text-align:left;"><?php echo $fname;?></td>
                                            <td valign="top" style="text-align:left;"><?php echo $mname; ?></td>
                                            <td valign="top" style="text-align:left;"><?php echo $extension; ?></td>
                                            <td valign="top" style="text-align:center;"><?php echo strtoupper($payroll[$v]['department_name']); ?></td>
                                            <td valign="middle" align="center"><?php echo strtoupper(date('m/d/Y',strtotime(@$payroll[$v]['birthday']))); ?></td> 
                                            <td valign="middle" align="right"><?php echo number_format($payroll[$v]['philhealth_amt'] , 2);$page_total["philhealth_amt"] += $payroll[$v]['philhealth_amt']; $grand_total["philhealth_amt"] += $payroll[$v]['philhealth_amt'];  ?></td> 
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
                                    </tr><br>
                                    <!-- END OF SUB-TOTAL PER PAGE -->

                                    <!-- START OF GRAND TOTAL -->
                                    <?php if($page == $total_page) { ?>
                                        <tr style="border: none;">
                                            <td valign="middle" align="right" style="font-weight: bold; border: none;" colspan="8">GRAND TOTAL</td>
                                            <td valign="middle" align="right" style="font-weight: bold; border-top:0;border-left:0;border-right:0;border-bottom:3px solid;border-style:double;"><?php echo number_format($grand_total["philhealth_amt"],2); ?></td>
                                        </tr>
                                    <?php } ?>
                                    <!-- END OF GRAND TOTAL -->
                                </tbody>
                            </table>
                            <!-- START OF SIGNATORIES -->
                            <table class="excelTable no-print" style="width:100%;" id="ftTable">
                                <tr>
                                    <td></td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td style="text-align:left;font-weight: normal;" colspan="5"><b>Note:</td>
                                    <td style="text-align:left;font-weight: normal;" colspan="5"><b>CERTIFIED BY:</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td style="text-align:left;font-weight: normal;" colspan="5"><b>> Indicate in the POR "IPM of {Group Name}"</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td style="text-align:left;font-weight: normal;" colspan="5">> Attach photocopy of POR</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td style="text-align:left;font-weight: normal;" colspan="5">> Report should be in an "alphalist"</td>
                                    <td style="text-align:left;font-weight: normal;" colspan="5"><b>JESUSA T. DELOS SANTOS</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td style="text-align:left;font-weight: normal;" colspan="5">> POR amount should be equal to the amount in the report</td>
                                    <td style="text-align:left;font-weight: normal;" colspan="5">Administrative Officer V</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td style="text-align:left;font-weight: normal;" colspan="5">> Indicate the Grand Total in the last page of the report</td>
                                    <td style="text-align:left;font-weight: normal;" colspan="5"></td>
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
    $(document).ready(function () {
        grandTotal = $('#tmpTable tr:last-child td:last-child').html();
        $("#grand_total_header").text(grandTotal);
    })
    function saveAsExcel() {        
        grandTotal = $('#excelTable tr:last-child td:last-child').html();
        $("#grand_total_header_excel").text(grandTotal);
        document.getElementById("excelTablediv").style.display = "block";
        $('.excelTable').tableExport({
            type: "excel",
            fileName: "PhilHealth Remittance Report - COS"
        });
        setTimeout(() => {
        const div = document.getElementById('excelTablediv');
            div.style.display = 'none';
        }, 1000); // time in milliseconds
    }
</script>