<?php
    // print_r($employee);
    $employee_name = @$employee['last_name'].', '.@$employee['first_name'].' '.@$employee['middle_name'];
    $employee_position = @$employee['position_name'];
    $employee_status = @$employee['employment_status'];
    $employee_civil_status = @$employee['civil_status'];
    $employee_entrance_to_duty = @$employee['employee_entrance_to_duty'];
    $employee_unit = @$employee['employee_unit'];
    $employee_gsis = @$employee['gsis'];
    $employee_tin = @$employee['tin'];
    $employee_nrcn = @$employee['employee_nrcn'];
    $employee_contract = @$employee['contract'];
    $employee_start_date = @$employee['start_date'];
    $employee_office_name = @$employee['office_name'];
    $employee_department = @$employee['department_name'];
    $vl_balance = isset($balance)?$balance['vl_balance_amt']:0.000;
    $sl_balance = isset($balance)?$balance['sl_balance_amt']:0.000;
    $tmp_vl_balance = isset($balance)?$balance['vl_balance_amt']:0.000;
    $tmp_sl_balance = isset($balance)?$balance['sl_balance_amt']:0.000;

    // var_dump(count($ledgers));
    // var_dump($remarks);
    // var_dump($remarks[0]);
?>
<?php if($key != "viewLeaveLedgerSummary"):
?>
<div class="employee-payslips">
    <div class="row">
        <div class="col-md-12">
            <div id="clearance-div">
                <style>
                    @media print{
                        @page {
                            size: A4 portrait;
                            margin: 0.2in;
                        }
                        body{
                            margin: 10mm 10mm 5mm 15mm;
                            font-family: "Times New Roman", Times, serif;
                            font-size:11px;
                        } 
                        table tr td,th{
                            font-family: "Times New Roman", Times, serif;
                            font-size:11px;
                        }
                        table {
                            border-spacing: 0;
                            border-collapse: collapse;
                            
                        } 
                        .tablebordered th, .tablebordered td{
                            border: 1px solid #3b3b3b;
                        }
                        /* @page {size: landscape; margin: 0mm;} */
                        .text-success{
                            color : #3c763d;
                        }
                        .text-danger{
                            color : #a94442;
                        }
                        .leaveledger-container {page-break-after: always;}
                    }
                    
                    #certificate-container{
                        width:100%;
                        text-align: justify;
                        text-justify: inter-word;

                    }
                    #certificate-container div{
                        display: inline-block;
                    } 
                    #certificate-container .fixdiv{
                       
                        border-bottom:1px solid gray;
                    }
                </style>
                <div id="certificate-container">
        <?php endif; ?>
                    <?php if($key == "viewLeaveLedgerSummary" || $key == "viewLeaveLedger"): ?>
                    <?php 
                    if(isset($ledgers) && sizeof($ledgers) > 0){ 
                        foreach ($ledgers as $k1 => $ledger) {
                    ?>
                    <div class="leaveledger-container" style="width: 100%">
                        <center>
                            <h2 style="border-bottom: solid 1px;width:100%;">EMPLOYEE LEAVE CARD</h2>
                        </center>
                        <table style="width:100%;font-size: 11px;display:none">
                            <tr>
                                <td style="width:7%">Name</td>
                                <td style="width:20%;border-bottom: dotted 1px;"><?php echo $employee_name; ?></td>
                                
                                <td style="width:5%;"></td>

                                <td style="width:12%">Civil Status</td>
                                <td style="width:18%;border-bottom: dotted 1px;"><?php echo $employee_civil_status; ?></td>

                                <td style="width:5%;"></td>

                                <td style="width:14%">GSIS Policy No.</td>
                                <td style="width:15%;border-bottom: dotted 1px;"><?php echo $employee_gsis; ?></td>
                            </tr>
                            <tr>
                                <td>Position</td>
                                <td style="border-bottom: dotted 1px;"><?php echo $employee_position; ?></td>
                                
                                <td style=""></td>

                                <td style="">Entrance to Duty</td>
                                <td style="border-bottom: dotted 1px;"><?php echo $employee_start_date; ?></td>

                                <td style=""></td>

                                <td style="">TIN No.</td>
                                <td style="border-bottom: dotted 1px;"><?php echo $employee_tin; ?></td>
                            </tr>
                            <tr>
                                <td style="">Status</td>
                                <td style="border-bottom: dotted 1px;"><?php echo $employee_contract; ?></td>
                                
                                <td style=""></td>

                                <td style="">Unit</td>
                                <td style="border-bottom: dotted 1px;"><?php echo $employee_office_name; ?></td>

                                <td style=""></td>

                                <td style="">National Reference Card No.</td>
                                <td style="border-bottom: dotted 1px;"><?php echo $employee_nrcn; ?></td>
                            </tr>
                        </table>
<!--                         <br>
                        <br> -->
                        <div class="body table-responsive">
                            <table class="table tablebordered table-bordered table-hover table-striped" width="100%" style="text-transform: uppercase;text-align:center;width:100%;font-size:10px;" id="leaveapplicationtable1">
                                <thead>
                                    <tr>
                                        <th colspan="11" style="vertical-align:middle; text-align: center;">
                                            Republic of the Philippines<br>
                                            NATIONAL WATER RESOURCES BOARD<br>
                                            Quezon City        
                                        </th>
                                    </tr>
                                    <tr>
                                        <th style="vertical-align:middle;">NAME:</th>
                                        <th colspan="4" style="vertical-align:middle"><?php echo $employee_name; ?></th>
                                        <th></th>
                                        <th style="vertical-align:middle">DIVISION:</th>
                                        <th colspan="4" style="vertical-align:middle"><?php echo $employee_department; ?></th>
                                    </tr>
                                    <tr>
                                        <th rowspan="2" style="text-align:center; vertical-align:middle">Period</th>
                                        <th rowspan="2" style="text-align:center; vertical-align:middle; width: 104px !important;">PARTICULARS</th>
                                        <th colspan="3" style="vertical-align:middle">
                                            <center>Vacation Leave</center>
                                        </th>
                                        <th></th>
                                        <th colspan="3" style="vertical-align:middle">
                                            <center>Sick Leave</center>
                                        </th>
                                        <th rowspan="2" style="vertical-align:middle">
                                            <center>Absences/<br>Undertime<br>w/out pay</center>
                                        </th>
                                        <th rowspan="2" style="text-align:center; vertical-align:middle">REMARKS</th>
                                    </tr>
                                    <tr>
                                        <th style="vertical-align:middle">
                                            <center>Earned<br>Leave</center>
                                        </th>
                                        <th width="8%" style="vertical-align:middle">
                                            <center>Absences/<br>Undertime<br>with pay</center>
                                        </th>
                                        <th style="vertical-align:middle">
                                            <center>BALANCE</center>
                                        </th>
                                        <th></th>
                                        <th style="vertical-align:middle">
                                            <center>Earned<br>Leave</center>
                                        </th>
                                        <th width="8%" style="vertical-align:middle">
                                            <center>Absences/<br>Undertime<br>with pay</center>
                                        </th>
                                        <th style="vertical-align:middle">
                                            <center>BALANCE<br>&nbsp;</center>
                                        </th>
                                    </tr>
                                    
                                    <?php if(date('Y') != 2021): ?>
                                    <tr>
                                        <th><center>BAL FROM LAST YEAR <?php //echo $year - 1; ?></center></th>
                                        <!-- <th>BAL. BROUGHT FORWARD</th> -->
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th style="text-align: right"><?php echo number_format((double) $vl_balance,3); ?>
                                        </th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th style="text-align: right"><?php echo number_format((double) $sl_balance,3); ?></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    <?php endif; ?>
                                </thead>
                                <tbody>
                                    <?php 
                                    if(isset($ledger) && sizeof($ledger) > 0){
                                        $first_period = "";
                                        $lst_k = 0;
                                        foreach ($ledger as $k => $value) {
                                            if($first_period == $value['period']){
                                                $value['period'] = "";
                                            }else {
                                                $first_period = $value['period'];
                                            }
                                            // if($value['period']="<span style='display:none;'></span>"){
                                            //     echo "<tr><td colspan='10'></td><td id='".$k."'></td></tr>";
                                            // }else{
                                    ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo (($k == 0 && date("Y") == 2021) ? "Leave Balance<br>as of " : "").$value['period']; ?></strong>
                                        </td>
                                        <td><?php echo (($k == 0 && date("Y") == 2021) ? "" : $value['particulars']); ?></td>
                                        <td class="text-success" style="text-align: right"> 
                                            <?php 
                                                if($value['vl_earned'] > 0)
                                                    echo (($k == 0 && date("Y") == 2021) ? "" : number_format((double) $value['vl_earned'],3));
                                            ?>
                                        </td>
                                        <td class="text-danger" style="text-align: right">  
                                            <?php 
                                                if($value['vl_a_utime_w_pay'] > 0)
                                                    echo (($k == 0 && date("Y") == 2021) ? "" : number_format((double) $value['vl_a_utime_w_pay'],3));
                                            ?></td>
                                        <td class="text-primary" style="text-align: right"> 
                                            <?php 
                                                if($value['vl_balance'] > 0 || $value['vl_a_utime_wo_pay'] > 0 || $value['vl_a_utime_w_pay'] > 0)
                                                    echo number_format((double) $value['vl_balance'],3);
                                            ?></td>
                                        <td></td>
                                        <td class="text-success" style="text-align: right"> 
                                            <?php 
                                                if($value['sl_earned'] > 0)
                                                    echo (($k == 0 && date("Y") == 2021) ? "" : number_format((double) $value['sl_earned'],3));
                                            ?></td>
                                        <td class="text-danger" style="text-align: right">  
                                        <!-- ==== -->
                                            <?php
                                                if($value['sl_a_utime_w_pay'] > 0) 
                                                    echo (($k == 0 && date("Y") == 2021) ? "" : number_format((double) $value['sl_a_utime_w_pay'],3));
                                                //    echo number_format((double) $value['sl_a_utime_w_pay'],3); 
                                            ?></td>
                                        <td class="text-primary" style="text-align: right"> 
                                            <?php 
                                                if(@$value['sl_balance'] > 0 || @$value['sl_a_utime_wo_pay'] > 0 || @$value['sl_a_utime_w_pay'] > 0)
                                                    echo number_format((double) $value['sl_balance'],3);
                                            ?></td>
                                        <td class="text-primary" style="text-align: right">
                                            <?php
                                                if($value['vl_a_utime_wo_pay'] > 0) 
                                                    echo (($k == 0 && date("Y") == 2021) ? "" : number_format((double) $value['vl_a_utime_wo_pay'],3)); 
                                            ?> 
                                        </td>
                                        <td style="font-size: 10px; text-align: left;" id="<?php echo $k; ?>">
                                            <?php echo @$remarks[$k];$lst_k = $k; ?>
                                        </td>
                                    </tr>
                                    <?php 
                                            // }sl_a_utime_w_pay
                                            $tmp_vl_balance += (number_format((double) @$value['vl_earned'],3) - number_format((double) @$value['vl_a_utime_w_pay'],3));
                                            $tmp_sl_balance += (number_format((double) @$value['sl_earned'],3) - number_format((double) @$value['sl_a_utime_w_pay'],3));
                                            $remarks = @$remarks == ""? [] : $remarks;

                                            if($tmp_vl_balance < 0)
                                                $tmp_vl_balance = 0.000;

                                            if($tmp_sl_balance < 0){
                                                $tmp_vl_balance -= abs($tmp_sl_balance);
                                                if(isset($total_vl_earned) && $total_vl_earned < 0)
                                                    $tmp_vl_balance = 0.000;
                                                
                                                $tmp_sl_balance = 0.000;
                                            }

                                            $vl_balance = $tmp_vl_balance;
                                            $sl_balance = $tmp_sl_balance;
                                        }

                                        if(sizeof($ledger) <= sizeof(@$remarks)){
                                            for($i=($lst_k+1);$i < sizeof(@$remarks);$i++){
                                            ?>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td class="text-success"> </td>
                                                <td class="text-danger"></td>
                                                <td class="text-primary"></td>
                                                <td class="text-danger"></td>
                                                <td class="text-success"> </td>
                                                <td class="text-danger"> </td>
                                                <td class="text-primary"></td>
                                                <td></td>
                                                <td style="font-size: 10px; text-align: left;" >
                                                    <?php echo @$remarks[$i]; ?>
                                                </td>
                                                
                                            </tr>
                                            <?php
                                            }
                                        }
                                    }
                                    for ($i = sizeof($ledger) + abs(sizeof($remarks) - sizeof($ledger)); $i < 25; $i++) {  
                                    ?>
                                        <tr>
                                            <td><br></td>
                                            <td></td>
                                            <td class="text-success"> </td>
                                            <td class="text-danger"></td>
                                            <td class="text-primary"></td>
                                            <td class="text-danger"></td>
                                            <td class="text-success"> </td>
                                            <td class="text-danger"> </td>
                                            <td class="text-primary"></td>
                                            <td></td>
                                            <td style="font-size: 10px; text-align: left;" ></td>
                                            
                                        </tr>
                                    <?php 
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php }
                    } ?>
                    <?php endif; ?>
                <?php if($key != "viewLeaveLedgerSummary"): ?>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12 text-right">
            <button id="printClearance" class="btn bg-green btn-fab btn-fab-mini">Print <i class = "material-icons">print</button>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
    $(document).ready(function(){
        // $("#leaveapplicationtable1").DataTable({
        //     "ordering": false,
        //     "bSort" : false,
        //     "dom": 't',
        //     iDisplayLength: -1,
        //     rowsGroup:[0]
        // });
    });
</script>
