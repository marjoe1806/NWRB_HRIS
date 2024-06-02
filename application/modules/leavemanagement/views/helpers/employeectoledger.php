<?php
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
?>
<?php if($key != "viewCTOLedgerSummary"):
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
                        .text-success{
                            color : #3c763d;
                        }
                        .text-danger{
                            color : #a94442;
                        }
                        .ctoledger-container {page-break-after: always;}
                    }
                    
                    #certificate-container{
                        width:100%;
                        text-align: center;
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
                    <?php if($key == "viewCTOLedgerSummary" || $key == "viewCTOLedger"): ?>
                    <?php 
                    if(isset($ledgers) && sizeof($ledgers) > 0){ 
                        foreach ($ledgers as $k1 => $ledger) {
                    ?>
                    <div class="ctoledger-container" style="width: 100%">
                        <center>
                            <h2 style="border-bottom: solid 1px;width:100%;margin-top:0;">EMPLOYEE CTO LEDGER</h2>
                        </center>
                        <div class="body table-responsive">
                            <table class="table tablebordered table-bordered table-hover table-striped" width="100%" style="text-transform: uppercase;text-align:center;width:100%;font-size:10px;" id="leaveapplicationtable1">
                                <thead>
                                    <tr>
                                        <th colspan="10" style="vertical-align:middle; text-align: center;">
                                            Republic of the Philippines<br>
                                            NATIONAL WATER RESOURCES BOARD<br>
                                            Quezon City        
                                        </th>
                                    </tr>
                                    <tr>
                                        <th style="vertical-align:middle;">NAME:</th>
                                        <th colspan="3" style="vertical-align:middle"><?php echo $employee_name; ?></th>
                                        <th style="vertical-align:middle"></th>
                                        <th style="vertical-align:middle">DIVISION:</th>
                                        <th colspan="4" style="vertical-align:middle"><?php echo $employee_department; ?></th>
                                    </tr>
                                    <tr>
                                        <th rowspan="2" style="text-align:center; vertical-align:middle">Period</th>
                                        <th colspan="3" style="text-align:center; vertical-align:middle">Earned</th>
                                        <th></th>
                                        <th colspan="3" style="text-align:center; vertical-align:middle">Used</th>
                                        <th rowspan="2" style="text-align:center; vertical-align:middle">Remaining COCs</th>
                                        <th rowspan="2" style="text-align:center; vertical-align:middle">Remarks</th>
                                    </tr>
                                    <tr>
                                        
                                        <th style="text-align:center; vertical-align:middle; width: 104px !important;">Date</th>
                                        <th style="text-align:center; vertical-align:middle;">No. of<br> Hours</th>
                                        <th style="text-align:center; vertical-align:middle;">No. of<br> Minutes</th>
                                        <th></th>
                                        <th style="text-align:center; vertical-align:middle; width: 104px !important;">Date</th>
                                        <th style="text-align:center; vertical-align:middle;">Used COCs<br>(Hrs)</th>
                                        <th style="text-align:center; vertical-align:middle;">Used COCs<br>(Mins)</th>
                                    </tr>
                                    
                                    <?php if(date('Y') != 2021): ?>
                                    <tr>
                                        <th><center>BAL FROM LAST YEAR <?php //echo $year - 1; ?></center></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th style="text-align: right"><?php echo  $ledger[0]['bal_from_last_year']; ?></th>
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
                                    ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo (($k == 0 && date("Y") == 2021) ? "Leave Balance<br>as of " : "").$value['period']; ?></strong>
                                        </td>
                                        <td><?php echo (($k == 0 && date("Y") == 2021) ? "" : $value['transaction_date']); ?></td>
                                        <td class="text-success" style="text-align: right"> 
                                            <?php 
                                                if($value['cto_hrs_earned'] != "")
                                                    echo (($k == 0 && date("Y") == 2021) ? "" : $value['cto_hrs_earned']);
                                            ?>
                                        </td>
                                        <td class="text-success" style="text-align: right"> 
                                            <?php 
                                                if($value['cto_mins_earned'] != "")
                                                    echo (($k == 0 && date("Y") == 2021) ? "" : $value['cto_mins_earned']);
                                            ?>
                                        </td>
                                        <td></td>
                                        <td><?php echo (($k == 0 && date("Y") == 2021) ? "" : $value['offset_date_effectivity']); ?></td>
                                        <td class="text-danger" style="text-align: right"> 
                                            <?php 
                                                if($value['cto_hrs_used'] != "")
                                                    echo (($k == 0 && date("Y") == 2021) ? "" : $value['cto_hrs_used']);
                                            ?>
                                        </td>
                                        <td class="text-danger" style="text-align: right">
                                            <?php
                                                if($value['cto_mins_used'] != "")
                                                    echo (($k == 0 && date("Y") == 2021) ? "" : $value['cto_mins_used']);
                                            ?> 
                                        </td>
                                        <td class="text-primary" style="text-align: right">
                                            <?php 
                                                echo (($k == 0 && date("Y") == 2021) ? "" : $value['cto_remaining']);
                                            ?>
                                        </td>
                                        <td style="font-size: 10px; text-align: left;" id="<?php echo $k; ?>">
                                            <?php //echo @$remarks[$k];$lst_k = $k; ?>
                                        </td>
                                    </tr>
                                    <?php 
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php }
                    } ?>
                    <?php endif; ?>
                <?php if($key != "viewCTOLedgerSummary"): ?>
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
