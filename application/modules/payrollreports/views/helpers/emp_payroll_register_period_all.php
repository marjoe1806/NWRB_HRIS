<div class="row">
    <div class="col-md-12 text-right">
        <button id="printClearance" class="btn bg-green btn-fab btn-fab-mini">Print Preview <i class = "material-icons">print</i></button>
    </div>
</div>
<hr>
<div class="row" style="overflow-x:auto;">
    <div class="col-md-12">
        <div style="width:100%;">
            <div id="clearance-div">
            <style type = 'text/css'>
                    @media print{
                        /*280mm 378mm 11in 15in */
                        html { height: 0; }
                        @page {  
                            size: US Std Fanfold landscape;
                            margin: 10mm 20mm;
                            
                        }
                        body { font-family:Calibri; font-size: 12; color: black; }
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
                    table#tmpTable thead tr th{ border: 1px solid black; }
                    table#tmpTable tr, #tmpTable td, #tblGSIS td {
                        font-size: 12px;
                        color: #000;
                        border: 1px solid black;
                    }
                    table#hdTable, table#ftTable, table#tblGSIS{
                        font-size: 12px;
                        color: #000;
                    }
                    #tblGSIS td{
                        text-align: center;
                    }
                    #footer{
							display: table-footer-group;
							
					}
                    /* .tmp_col2, .tmp_col1{
                        text-align:center;
                    } */
                    .total_col{
                        font-weight: bold;
                    }
                    .smallBottom{
                        position: relative;
                    }
                    .smallBottom::before{
                        content: '';
                        position: absolute;
                        bottom: 0;
                        right: 0;
                        border-bottom: 1px solid black;
                        width: 10%;
                    }
                    .smallBottomLeft{
                        position: relative;
                    }
                    .smallBottomLeft::before{
                        content: '';
                        position: absolute;
                        bottom: 0;
                        left: 0;
                        border-bottom: 1px solid black;
                        width: 40%;
                    }
                    .smallBottomTop{
                        position: relative;
                    }
                    .smallBottomTop::before{
                        content: '';
                        position: absolute;
                        top: 0;
                        right: 0;
                        border-bottom: 1px solid black;
                        width: 50%;
                    }
                    
                    .smallBottom2{
                        position: relative;
                    }
                    .smallBottom2::before{
                        content: '';
                        position: absolute;
                        bottom: 0;
                        right: 0;
                        border-bottom: 1px solid black;
                        width: 50%;
                    }
                    #bracket {
                    display: block;
                    font-weight: 20;
                    font-size: 100px;
                    text-align: center;
                    -webkit-transform: rotate(90deg);
                    -moz-transform: rotate(90deg);
                    -o-transform: rotate(90deg);
                    -ms-transform: rotate(90deg);
                    transform: rotate(90deg);
                }

                </style>
                 <?php
                $secondTableSALARY = array();
                $secondTablePERA = array();
                $secondTableGross = array();
                $secondTableRLIP = array();
                $secondTableGSIS = array();
                $secondTableLCHL = array();
                $secondTableTAX = array();
                $secondTablePHIL = array();
                $secondTableHDMF = array();
                $secondTableMP2 = array();
                $secondTableMPL = array();
                $secondTableLBP = array();
                $secondTableNWRBEA = array();
                $secondTableLATE = array();
                $secondTableDEDUCTION = array();
                $secondTableNET = array();
                $secondTableFirstCut = array();
                $secondTableSecondCut = array();

                $total_conso_loan = array();
                $total_GFAL = array();
                $total_MPL = array();
                $total_EMERGENCY_LOAN = array();
                $total_CPL = array();
                $total_POLICY_LOAN = array();
                $total_OPT_PL_LOAN = array();
                $total_OPT_INSURANCE = array();
                $total_EDU_ASSISTANCE = array();
                $total_Membership = array();
                $total_Kamanggagawa = array();
                $total_PROJECT = array();
                $total_CASH_LOAN = array();
                $total_EMERGENCY = array();
                $total_GSIS = array();
                $total_NHMFC = array();
                $total_PAG_IBIG = array();

				$totalNum = 0; 
				$numExperience = sizeof($payroll) / 92;
				$wholeNumExperience = floor($numExperience);
				$decNumExperience = $wholeNumExperience - $numExperience;
				$totalNum = $wholeNumExperience;
				//var_dump($decNumExperience);

                $basic_salary_total = 0;
                $life_ret_total = 0;
                $consoloan_total = 0;
                $gfal_total = 0;
                $calamity_total = 0;
                $cpl_total = 0;
                $policy_loan_total = 0;
                $opt_pl_loan_total = 0;
                $edu_assist_total = 0;
                $lch_rel_gsis_total = 0;
                $phil_health_total = 0;
                $col_1_total = 0;
                $col_2_total = 0;
                $contri_total = 0;
                $mp2_total = 0;
                $stlrf_total = 0;
                $housing_total = 0;
                $nwrb_contri_total = 0;
                $kamanggagawa_total = 0;
                $cash_loan_total = 0;
                $emergency_total = 0;
                $project_total = 0;
                $lbp_total = 0;
                $absent_total = 0;
                $tax_total = 0;

				if($decNumExperience < 0){
					$totalNum += 1;
				}
				$count1 = 0;
				$count2 = 91;
                $count3 = 0;
                
				for($x = 0; $x < $totalNum; $x++){

                ?>
                <table style="width:100%;" id="tmpTable">
                    <thead>
                    <tr>
                        <th style="text-align:center;" rowspan="2"><b>Ser. No.</b></th>
                        <th style="text-align:center;" rowspan="2"><b>Emp. No.</b></th>
                        <th style="text-align:center;width:100px;" rowspan="2">NAME</th>
                        <th style="text-align:center;" rowspan="2"><b>Basic Salary</b></th>
                        <?php
                                $colspan = 12;
                                if(isset($additional_header1[0]['name']) != ""){
                                    $colspan += 1;
                                }
                                if(isset($additional_header2[0]['name']) != ""){
                                    $colspan += 1;
                                }
                        ?>
                        <!-- <th valign="top" style="text-align:center;" rowspan="2">BASIC SALARY</th>
                        <th valign="top" style="text-align:center;" rowspan="2">GROSS AMOUNT EARNED</th>
                        <th valign="top" style="text-align:center;" rowspan="2">OTHER EARNINGS</th> -->

                        <th style="text-align:center;" id="deduction_header" colspan="11"><b>GSIS</b></th>
                        <th rowspan="2" style="text-align:center;">PHIL- HEALTH</th>
                        <?php
                            if(isset($additional_header1[0]['name']) != ""){
                        ?>
                        <th rowspan="2" style="text-align:center;"><?php echo isset($additional_header1[0]['name']) ? $additional_header1[0]['name'] : "Header_1";?></th>
                        <?php 
                            }
                        ?>
                            <?php
                            if(isset($additional_header2[0]['name']) != ""){
                        ?>
                        <th rowspan="2" style="text-align:center;"><?php echo isset($additional_header2[0]['name']) ? $additional_header2[0]['name'] : "Header_2";?></th>
                        <?php 
                            }
                        ?>
                        <th colspan="4" style="text-align:center;">HDMF</th>
                        <th colspan="5" style="text-align:center;">OTHER PAYABLES</th>
                        <th></th>
                        <th rowspan="2" style="text-align:center;">LATE & ABSENT</th>
                        <th rowspan="2" style="text-align:center;">TAX</th>
                    </tr>
                    <tr>
                       
                        <th style="text-align:center;">LIFE & RET</th>
                        <th style="text-align:center;">CONSOLOAN</th>
                        <th style="text-align:center;">GFAL</th>
                        <th style="text-align:center;">MPL</th>
                        <th style="text-align:center;">CALAMITY</th>
                        <th style="text-align:center;">CPL</th>
                        <th style="text-align:center;">POLICY LOAN</th>
                        <th style="text-align:center;">OPT.PL. LOAN</th>
                        <th style="text-align:center;">OPT/INS.</th>
                        <th style="text-align:center;">EDUC/ASSIST</th>
                        <th style="text-align:center;">LCH/REL-GSIS</th>
                        <th style="text-align:center;">CONTRI</th>
                        <th style="text-align:center;">MP2</th>
                        <th style="text-align:center;">STLRF</th>
                        <th style="text-align:center;">HOUSING</th>
                        <th style="text-align:center;">NWRB-CONTRI</th>
                        <th style="text-align:center;">KAMANGGAGAWA</th>
                        <th style="text-align:center;">CASH LOAN</th>
                        <th style="text-align:center;">EMERGENCY</th>
                        <th style="text-align:center;">PROJECT</th>
                        <th style="text-align:center;">LBP</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                            for($v = $count1 ; $v < sizeof($payroll); $v++ ){
                        ?>
                        <tr>
                            <td valign="top" style="text-align:center;"><?php echo ($v+1); ?></td>
                            <td valign="middle" style="text-align:center;"> <?php echo (isset($payroll[$v]['employee_id_number']) && $payroll[$v]['employee_id_number'] != "")?$this->Helper->decrypt($payroll[$v]['employee_id_number'],$payroll[$v]['employee_id']):""; ?> </td>
                            <td valign="top" style="text-align:left;font-weight: bold;"><?php echo ((isset($payroll[$v]['last_name']) && $payroll[$v]['last_name'] != "")?$this->Helper->decrypt($payroll[$v]['last_name'],$payroll[$v]['employee_id']):"") . ((isset($payroll[$v]['first_name']) && $payroll[$v]['first_name'] != "")?"&nbsp;".$this->Helper->decrypt($payroll[$v]['first_name'],$payroll[$v]['employee_id']):"") . ((isset($payroll[$v]['middle_name']) && $payroll[$v]['middle_name'] != "")?"&nbsp;".$this->Helper->decrypt($payroll[$v]['middle_name'],$payroll[$v]['employee_id']):"").((isset($payroll[$v]['extension']) && $payroll[$v]['extension'] != "")?"&nbsp;".$this->Helper->decrypt($payroll[$v]['extension'],$payroll[$v]['employee_id']):""); ?></td>
                                                                                            
                            <td valign="middle" align="right" id="<?php echo $payroll[$v]["id"]."_basic_pay"; ?>"><?php echo number_format($payroll[$v]['basic_pay'],2); $basic_salary_total += $payroll[$v]['basic_pay'];?></td>
                            <td valign="middle" align="right"><?php $sss_gsis_amt =$payroll[$v]['sss_gsis_amt'] ; echo number_format($sss_gsis_amt,2);?></td>
                            <td valign="middle" align="right"><?php $val = 0; $val = get_key(2,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $life_ret_total += $val;?></td>
                            <td valign="middle" align="right"><?php $val = 0; $val = get_key(45,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $consoloan_total += $val;?></td>
                            <td valign="middle" align="right"><?php $val = 0; $val = get_key(35,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $gfal_total += $val;?></td>
                            <td valign="middle" align="right"><?php $val = 0; $val = get_key(4,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $calamity_total += $val;?></td>
                            <td valign="middle" align="right"><?php $val = 0; $val = get_key(36,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $cpl_total += $val;?></td>
                            <td valign="middle" align="right"><?php $val = 0; $val = get_key(24,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $policy_loan_total += $val;?></td>
                            <td valign="middle" align="right"><?php $val = 0; $val = get_key(16,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $opt_pl_loan_total += $val;?></td>
                            <td valign="middle" align="right"><?php $val = 0; $val = get_key(18,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $edu_assist_total += $val;?></td>
                            <td valign="middle" align="right"><?php $val = 0; $val = get_key(8,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $lch_rel_gsis_total += $val;?></td>
                            <td valign="middle" align="right"><?php $val = 0; $val = get_key(7,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $phil_health_total += $val;?></td>
                            <td><?php echo $payroll[$v]['philhealth_amt'];?></td>
                            <td valign="middle" align="right" <?php if(isset($additional_header1[0]['name']) == ""){echo "style='display:none;'";} ?>class="tmp_col1 editable tmp_col1_value<?php echo $x;?> tmp_col1_fields" data-value="<?php echo $x;?>" data-id="<?php echo $payroll[$v]["id"]; ?>" id="<?php echo  "tmp_col1_".$payroll[$v]["id"]; ?>" <?php if(isset($additional_header1[0]['name']) == ""){echo "contenteditable";} ?>>
                            <?php $cut_col1_value = $payroll[$v]["col1_value"]; echo number_format($cut_col1_value, 2); $col_1_total += $cut_col1_value;?>
                            </td>
                            <td valign="middle" align="right" <?php if(isset($additional_header2[0]['name']) == ""){echo "style='display:none;'";}?> class="tmp_col2 editable tmp_col2_value<?php echo $x;?> tmp_col2_fields" data-value="<?php echo $x;?>"  data-id="<?php echo $payroll[$v]["id"] ?>" id="<?php echo "tmp_col2_".$payroll[$v]["id"]; ?>" <?php if(isset($additional_header2[0]['name']) == ""){echo "contenteditable";} ?>>
                            <?php $cut_col2_value = $payroll[$v]["col2_value"];  echo number_format($cut_col2_value,2); $col_2_total += $cut_col2_value;?>
                            </td>
                            <td valign="middle" align="right"><?php $cut_pagibig_amt = $payroll[$v]['pagibig_amt'] ; echo number_format($cut_pagibig_amt,2);$contri_total += $cut_pagibig_amt?></td>
                            <td valign="middle" align="right"><?php $val = 0; $val = $payroll[$v]['mp2_contribution_amt'] ; echo number_format($val,2); $mp2_total += $val;?></td>
                            <td valign="middle" align="right"><?php $val = 0; $val = get_key(21,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val ,2); $stlrf_total += $val;?></td>
                            <td valign="middle" align="right"><?php $val = 0; $val = get_key(20,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $housing_total += $val;?></td>
                            <td valign="middle" align="right"><?php $val = 0; $val = get_key(40,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $nwrb_contri_total += $val;?></td>
                            <td valign="middle" align="right"><?php $val = 0; $val = get_key(41,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $kamanggagawa_total += $val;?></td>
                            <td valign="middle" align="right"><?php $val = 0; $val = get_key(43,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $cash_loan_total += $val;?></td>
                            <td valign="middle" align="right"><?php $val = 0; $val = get_key(44,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $emergency_total += $val;?></td>
                            <td valign="middle" align="right"><?php $val = 0; $val = get_key(42,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val,2); $project_total += $val;?></td>
                            <td valign="middle" align="right"><?php  $val = 0; $val = get_key(39,$loanDeductions[$payroll[$v]['employee_id']]); echo number_format($val ,2); $lbp_total += $val;?></td>
                            <td valign="middle" align="right"><?php $totalAbs_late = $payroll[$v]['total_tardiness_amt']; echo number_format($totalAbs_late, 2); $absent_total += $totalAbs_late;?></td>
                            <td valign="middle" align="right"><?php $cut_wh_tax_amt = $payroll[$v]['wh_tax_amt'] ; echo number_format($cut_wh_tax_amt,2); $tax_total += $cut_wh_tax_amt;?></td>
                        </tr>
                        <?php
                                if($v == $count2){
                                    $count1 += 1;
                                    break;
                                }
                            ?>
                        <?php 
                            }
                        ?>
                    </tbody>           
                    <?php
                      $count1 += 91;
                      $count3 += 91;
                      $count2 += 92;     
                    }
                    ?>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td valign="middle" align="right"></td>
                        <td valign="middle" align="right"></td>
                        <td valign="middle" align="right" style="font-weight: bold;">GRAND TOTAL</td>
                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($basic_salary_total, 2);?></td>
                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($life_ret_total, 2);?></td>
                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($consoloan_total, 2);?></td>
                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($gfal_total, 2);?></td>
                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($calamity_total, 2);?></td>
                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($cpl_total, 2);?></td>
                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($policy_loan_total, 2);?></td>
                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($opt_pl_loan_total, 2);?></td>
                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($edu_assist_total, 2);?></td>
                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($lch_rel_gsis_total, 2);?></td>
                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($phil_health_total, 2);?></td>
                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($col_1_total, 2);?></td>
                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($col_2_total, 2);?></td>
                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($contri_total, 2);?></td>
                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($mp2_total, 2);?></td>
                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($stlrf_total, 2);?></td>
                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($housing_total, 2);?></td>
                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($nwrb_contri_total, 2);?></td>
                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($kamanggagawa_total, 2);?></td>
                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($cash_loan_total, 2);?></td>
                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($emergency_total, 2);?></td>
                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($project_total, 2);?></td>
                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($lbp_total, 2);?></td>
                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($absent_total, 2);?></td>
                        <td valign="middle" align="right" style="font-weight: bold;"><?php echo number_format($tax_total, 2);?></td>
                    </tr>
                </table>
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