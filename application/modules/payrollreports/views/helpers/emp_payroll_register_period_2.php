<div class="row">
    <div class="col-md-12 text-right">
        <button id="printClearance" class="btn bg-green btn-fab btn-fab-mini">Print Preview <i class = "material-icons">print</i></button>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-12">
        <div style="width:100%; overflow-x:auto;">
            <div id="clearance-div">
                <style type = 'text/css'>
                    @media print{
                        /*280mm 378mm 11in 15in */
                        html { height: 0; }
                        @page {  size: landscape;  }
                        body { font-family:Calibri; font-size: 10; color: black; }
                        table{ border-collapse: collapse; }
                        .page-break{ display: table; vertical-align:top; width: 100% !important; page-break-after: always !important; table-layout: inherit; margin-top:2px; }
                    }
                </style> 
                <style type="text/css" media="all">
                    table#tmpTable thead tr th, table#tmpTable tbody tr td{ padding: 2px; }
                    table#tmpTable thead tr th{ border: 1px solid black; }
                    table#tmpTable{
                        font-size: 10px;
                        color: #000;
                    }
                    table#hdTable, table#ftTable{
                        font-size: 12px;
                        color: #000;
                    }
                </style>
                <div class="header-container" style="width:100%;">
                    <?php
                        $total_per_page = $total_next_page = 10;
                        $total_page = floor(sizeof($payroll)/$total_per_page) + 1;
                        if(( sizeof($payroll) < $total_next_page && sizeof($payroll) > floor($total_next_page*.5) ) || (sizeof($payroll) > $total_next_page && (sizeof($payroll)) - ($total_next_page * $total_page) > floor($total_next_page*.5) )){
                            $total_page = floor(sizeof($payroll)/$total_per_page) + 2;
                        }
                        $page_count = 1;
                        $last_row = 0;
                        $count = 1;
                        $page = 0;
                    ?>
                    <table style="width:100%;border-bottom:0px;" id="hdTable">
                        <thead>
                            <tr>
                                <td align="center"><label>GENERAL PAYROLL</label></td>
                            </tr>
                            <tr>
                                <td align="center"><label>NATIONAL WATER RESOURCES BOARD</label></td>
                            </tr>
                            <tr>
                                <td><br></td>
                            </tr>
                            <tr>
                                <td align="center" style="text-decoration: underline;"><b><?php echo "&emsp;".date('F d, Y',strtotime(@$payroll_period[0]['start_date'])); ?></b> &emsp; to &emsp; <b><?php echo date('F d, Y',strtotime(@$payroll_period[0]['end_date'])); ?></b>&emsp;</td>
                            </tr>
                            <tr>
                                <td style="text-align:center;font-size: 11px;font-style: italic;padding-top:0px;">Period</td>
                            </tr>
                            <tr>
                                <td><br></td>
                            </tr>
                            <tr>
                                <td align="left">&emsp;&emsp;&emsp;&emsp;We Acknowledge receipt of cash opposite our name as full compensation for services rendered for the period.</td>
                            </tr>
                        </thead>
                    </table>
                    <div class="table-container table-responsive"><?php $page++; ?>
                        <table style="width:100%;" id="tmpTable">
                            <thead>
                                <tr><?php $fstcutoff = date('F ',strtotime(@$payroll_period[0]['start_date'])) . '1-' .(date('d',strtotime(@$payroll_period[0]['start_date']))-1).''.date(', Y',strtotime(@$payroll_period[0]['start_date'])); ?>
                                    <th valign="top" style="text-align:center;" rowspan="2">No</th>
                                    <th valign="top" style="text-align:center; min-width: 200px;" rowspan="2">NAME<br>(<span style="letter-spacing: 5px;">Position</span>)</th>
                                    <th valign="top" style="text-align:center;" rowspan="2">Employee No.</th>
                                    <th valign="top" style="text-align:center;" rowspan="2">BASIC SALARY</th>
                                    <th valign="top" style="text-align:center;" rowspan="2">GROSS AMOUNT</th>
                                    <!-- <th valign="top" style="text-align:center;" rowspan="2">AMOUNT<br>RECEIVED<br><?php $val = ""; $cl = strlen($fstcutoff); $val = substr($fstcutoff,$cl - 10);  echo substr($fstcutoff,0,$cl - 10).'<br>'.$val; ?></th> -->
                                    <th valign="top" style="text-align:center;letter-spacing: 8px;" colspan="11">DEDUCTION</th>
                                    <th valign="top" style="text-align:center;" rowspan="2">TOTAL DEDUCTION</th>
                                    <th valign="top" style="text-align:center;" rowspan="2">NET AMOUNT DUE</th>
                                    <th valign="top" style="text-align:center;" rowspan="2">SIGNATURE OF RECIPIENT</th>
                                    <th valign="top" style="text-align:center;" rowspan="2">REMARKS</th>
                                </tr>
                                <tr>
                                    <th valign="top" style="text-align:center; min-width: 100px;">SALARY PER<br>MON. LESS<br>RECEIVED<br><?php echo $fstcutoff; ?></th>
                                    <th valign="top" style="text-align:center;">PLUS: PERA</th>
                                    <th valign="top" style="text-align:center;">RATA</th>
                                    <th valign="top" style="text-align:center;">TOTAL</th>
                                    <th valign="top" style="text-align:center;">LESS: W/O PAY FOR SALARY</th>
                                    <th valign="top" style="text-align:center;">LESS: W/O PAY FOR PERA</th>
                                    <th valign="top" style="text-align:center;">&nbsp;</th>
                                    <th valign="top" style="text-align:center;">LIFE & RET.<br>INSURANCE<br>PREMIUM ADJUSTMENT</th>
                                    <th valign="top" style="text-align:center;">CONSOLIDATED LOAN</th>
                                    <th valign="top" style="text-align:center;">PAG-IBIG SHARE</th>
                                    <th valign="top" style="text-align:center;">PHIL. CARE</th>
                                </tr>
                            </thead>
                            <?php
                            $page_total = array(
                                'salary'=>0.00,
                                'gross_pay'=>0.00,
                                'salary_less_received'=>0.00,
                                'pera_amt'=>0.00,
                                'gross_pera_amt'=>0.00,
                                'tardiness_amt'=>0.00,
                                'pera_wop_amt'=>0.00,
                                'net_amnt_due'=>0.00
                            );
                            $grand_total = array(
                                'salary'=>0.00,
                                'gross_pay'=>0.00,
                                'salary_less_received'=>0.00,
                                'pera_amt'=>0.00,
                                'gross_pera_amt'=>0.00,
                                'tardiness_amt'=>0.00,
                                'pera_wop_amt'=>0.00,
                                'net_amnt_due'=>0.00
                            );
                            ?>
                            <tbody>
                                <tr><td colspan="24" align="center"><span id="division_label" style="font-weight: bolder;font-style: italic;"></span></td></tr>
                                <tr><td colspan="24">&nbsp;</td></tr>
                            <?php foreach ($payroll as $k => $v) { 
                                    $rowtotaldeduction = 0; ?>
                                <tr>
                                    <td valign="top" style="text-align:center;"><?php echo ($k+1); ?></td>
                                    <td valign="top" style="text-align:left;font-weight: bold;"><?php echo ((isset($v['last_name']) && $v['last_name'] != "")?$this->Helper->decrypt($v['last_name'],$v['employee_id']):"") . ((isset($v['first_name']) && $v['first_name'] != "")?"&emsp;&nbsp;".$this->Helper->decrypt($v['first_name'],$v['employee_id']):"") . ((isset($v['middle_name']) && $v['middle_name'] != "")?"&nbsp;".$this->Helper->decrypt($v['middle_name'],$v['employee_id']):"") . "<br><span style='font-weight: normal;'>" . strtoupper(@$v['position_name']) . "</span>"; ?></td>
                                    <td valign="middle" style="text-align:center;"> <?php echo (isset($v['employee_id_number']) && $v['employee_id_number'] != "")?$this->Helper->decrypt($v['employee_id_number'],$v['employee_id']):""; ?> </td>
                                    <td valign="middle" align="right"><?php echo number_format($v['basic_pay'],2); $page_total["salary"] += $v['basic_pay']; $grand_total["salary"] += $v['basic_pay']; ?></td>
                                    <td valign="middle" align="right"><?php echo number_format(($v['basic_pay']/2),2); $page_total["gross_pay"] += ($v['basic_pay']/2); $grand_total["gross_pay"] += ($v['basic_pay']/2); ?></td>
                                    <td valign="middle" align="right"><?php echo number_format($v['cutoff_1'],2); $page_total["salary_less_received"] += $v['cutoff_1']; $grand_total["salary_less_received"] += $v['cutoff_1']; ?></td>
                                    <td valign="middle" align="right"><?php echo number_format($v['pera_amt'],2); $page_total["pera_amt"] += $v['pera_amt']; $grand_total["pera_amt"] += $v['pera_amt']; ?></td>
                                    <td><!-- RATA --></td>
                                    <td valign="middle" align="right"><?php echo number_format(($v['gross_pay']/2)+$v['pera_amt'],2); $page_total["gross_pera_amt"] += ($v['gross_pay']/2)+$v['pera_amt']; $grand_total["gross_pera_amt"] += ($v['gross_pay']/2)+$v['pera_amt']; ?></td>
                                    <td valign="middle" align="right"><?php echo number_format($v['total_tardiness_amt'],2); $page_total["tardiness_amt"] += $v['total_tardiness_amt']; $grand_total["tardiness_amt"] += $v['total_tardiness_amt']; ?></td>
                                    <td valign="middle" align="right"><?php echo number_format($v['pera_wop_amt'],2); $page_total["pera_wop_amt"] += $v['pera_wop_amt']; $grand_total["pera_wop_amt"] += $v['pera_wop_amt']; ?></td>
                                    <td valign="middle" align="right">&nbsp;</td>
                                    <td valign="middle" align="right"><?php echo number_format(0,2); ?></td>
                                    <td valign="middle" align="right"><?php echo number_format(0,2); ?></td>
                                    <td valign="middle" align="right"><?php echo number_format(0,2); ?></td>
                                    <td valign="middle" align="right"><?php echo number_format(0,2); ?></td>
                                    <td valign="middle" align="right"><?php echo number_format(0,2); ?></td>
                                    <td valign="middle" align="right"><?php $net_amnt_due = $v['cutoff_2'] + $v['pera_amt']; echo number_format($net_amnt_due,2); $page_total["net_amnt_due"] += $net_amnt_due; $grand_total["net_amnt_due"] += $net_amnt_due; ?></td>
                                    <td style="border-bottom: 1px solid black;"></td>
                                    <td style="border: none;"></td>
                                </tr>  
                            <?php } ?>
                                <tr class="<?php echo "";//($last_row === 1)?"page-break":""; ?>">
                                    <td valign="middle" align="right" style="font-weight: bold;" colspan="3">TOTAL .........</td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["salary"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["gross_pay"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["salary_less_received"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["pera_amt"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["gross_pera_amt"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["tardiness_amt"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["pera_wop_amt"],2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format(0,2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format(0,2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format(0,2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format(0,2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format(0,2); ?></td>
                                    <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($grand_total["net_amnt_due"],2); ?></td>
                                    <td valign="middle" align="right" colspan="2"><?php echo ""; ?></td>
                                    <td valign="middle" align="right" colspan="2"><?php echo ""; ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                        <table style="border:1px solid black;width:100%;" id="ftTable">
                            <tr>
                                <td valign="top">A</td>
                                <td>CERTIFIED: Service duly rendered as stated.</td>
                                <td style="border-right:1px solid black;">&nbsp;</td>
                                <td valign="top">C</td>
                                <td colspan="2">APPROVED: FOR PAYMENT:&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<span style="text-decoration: underline;">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span></td>
                            </tr>
                            <tr style="height:45px;">
                                <td colspan="3" valign="bottom" style="text-decoration: underline;text-align:center;border-right:1px solid black"><?php echo $signatories[0]["employee_name"]; //name ?></td>
                                <td colspan="3" valign="bottom" style="text-decoration: underline;text-align:center;"><?php echo $signatories[2]["employee_name"]; //name ?></td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align:center;border-right:1px solid black">
                                    <?php echo $signatories[0]["position_designation"] != "" || $signatories[0]["position_designation"] != null ? $signatories[0]["position_designation"] : $signatories[0]["position_title"]; //position ?>
                                    <br>
                                    <?php echo $signatories[0]["division_designation"] != "" || $signatories[0]["division_designation"] != null ? $signatories[0]["division_designation"] : $signatories[0]["department"]; //position ?>
                                </td>
                                <td colspan="3" style="text-align:center;font-size:10px;">
                                    <?php echo $signatories[2]["position_designation"] != "" || $signatories[2]["position_designation"] != null ? $signatories[2]["position_designation"] : $signatories[2]["position_title"]; //position ?>
                                    <br>
                                    <?php echo $signatories[2]["division_designation"] != "" || $signatories[2]["division_designation"] != null ? $signatories[2]["division_designation"] : $signatories[2]["department"]; //position ?>
                                    <br>
                                    Head of Agency/Authorized Representative
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align:center;border-right:1px solid black">Authorized Official</td>
                                <td colspan="3" style="text-align:center;"></td>
                            </tr>
                            <tr style="border-top:1px solid black;">
                                <td width="2%" valign="top">B</td>
                                <td width="37%">CERTIFIED: Supporting documents complete and proper, and cash<br>available in the amount of P ___________</td>
                                <td width="10%" style="border-right:1px solid black">&nbsp;</td>
                                <td width="2%" valign="top">D</td>
                                <td width="37%">CERTIFIED: Each employee whose name appears above has<br>been paid the amount indicated opposite on his/her name.</span></td>
                                <td wdith="15%" rowspan="3" valign="middle" align="left">ALOBS No. ___________</td>
                            </tr>
                            <tr style="height:45px;">
                                <td colspan="3" valign="bottom" style="text-decoration: underline;text-align:center;border-right:1px solid black"><?php echo $signatories[1]["employee_name"]; //name ?></td>
                                <td colspan="2" valign="bottom" style="text-align:center;"><span style="text-decoration: underline;"></td>
                            </tr>
                            <tr style="height:35px;">
                                <td valign="top" colspan="3" style="text-align:center;border-right:1px solid black">
                                <?php echo $signatories[1]["position_designation"] != "" || $signatories[1]["position_designation"] != null ? $signatories[1]["position_designation"] : $signatories[1]["position_title"]; //position ?>
                                                <br>
                                                <?php echo $signatories[1]["division_designation"] != "" || $signatories[1]["division_designation"] != null ? $signatories[1]["division_designation"] : $signatories[1]["department"]; //position ?>
                                </td>
                                <td valign="top" colspan="2">
                                    <table width="100%" >
                                        <tr>
                                            <td width="45%" style="text-align:center;border-bottom:1px solid black"><?php echo $signatories[3]["employee_name"]; //name ?></td>
                                            <td width="10%"></td>
                                            <td width="45%" style="text-align:center;border-bottom:1px solid black"></td>
                                        </tr>
                                        <tr>
                                            <td width="45%" style="text-align:center;">
                                                <?php echo $signatories[3]["position_designation"] != "" || $signatories[3]["position_designation"] != null ? $signatories[3]["position_designation"] : $signatories[3]["position_title"]; //position ?>
                                                <br>
                                                <?php echo $signatories[3]["division_designation"] != "" || $signatories[3]["division_designation"] != null ? $signatories[3]["division_designation"] : $signatories[3]["department"]; //position ?>
                                            </td>
                                            <td width="10%"></td>
                                            <td width="45%" style="text-align:center;">Date</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
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