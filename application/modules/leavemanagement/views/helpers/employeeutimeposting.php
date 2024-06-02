<?php
    $employee_name = null;
    $employee_position = null;
    $employee_status = null;
    $employee_civil_status = null;
    $employee_entrance_to_duty = null;
    $employee_unit = null;
    $employee_gsis = null;
    $employee_tin = null;
    $employee_nrcn = null;
    $leave_vacation_balance_brought =null;
    $leave_sick_balance_brought = null;
    if($ledger['Code'] == "0"){
        $firstname = $this->Helper->decrypt($ledger['Data']['details'][0]['first_name'],$ledger['Data']['details'][0]['employee_id']);
        $lastname = $this->Helper->decrypt($ledger['Data']['details'][0]['last_name'],$ledger['Data']['details'][0]['employee_id']);
        $employee_name =  $firstname.' '.$lastname;
        $employee_position = $ledger['Data']['details'][0]['position'];
        $employee_status = $ledger['Data']['details'][0]['employment_status'];
        $employee_civil_status = $ledger['Data']['details'][0]['civil_status'];
        //$employee_entrance_to_duty = $ledger['Data']['details'][0]['employee_entrance_to_duty'];
        //$employee_unit = $ledger['Data']['details'][0]['employee_unit'];
        $employee_gsis = $ledger['Data']['details'][0]['gsis'];
        $employee_tin = $ledger['Data']['details'][0]['tin'];
        //$employee_nrcn = $ledger['Data']['details'][0]['employee_nrcn'];
    }
?>
<div class="row">
    <div class="col-md-12">
        <div id="clearance-div">
            <style>
                @media print{
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
                    @page {size: landscape}
                    .text-success{
                        color : #3c763d;
                    }
                    .text-danger{
                        color : #a94442;
                    }
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
                <center>
                    <h2 style="border-bottom: solid 1px;width:100%;">EMPLOYEE LEAVE CARD</h2>
                </center>
                <table style="width:100%;font-size: 11px;">
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
                        <td style="border-bottom: dotted 1px;"><?php echo $employee_entrance_to_duty; ?></td>

                        <td style=""></td>

                        <td style="">TIN No.</td>
                        <td style="border-bottom: dotted 1px;"><?php echo $employee_tin; ?></td>
                    </tr>
                    <tr>
                        <td style="">Status</td>
                        <td style="border-bottom: dotted 1px;"><?php echo $employee_status; ?></td>
                        
                        <td style=""></td>

                        <td style="">Unit</td>
                        <td style="border-bottom: dotted 1px;"><?php echo $employee_unit; ?></td>

                        <td style=""></td>

                        <td style="">National Reference Card No.</td>
                        <td style="border-bottom: dotted 1px;"><?php echo $employee_nrcn; ?></td>
                    </tr>
                </table>
                <br>
                <br>
                <div class="body table-responsive">
                    <table class="table tablebordered table-bordered table-hover table-striped" style="text-transform: uppercase;text-align:center;width:100%;font-size:10px;" id="leaveapplicationtable1">
                        <thead>
                            <tr>
                                <th rowspan="2" style="text-align:center" valign="bottom">Period</th>
                                <th rowspan="2" style="text-align:center" valign="middle">PARTICULARS</th>
                                <th colspan="4" style="">
                                    <center>Vacation Leave</center>
                                </th>
                                <th colspan="4" style="">
                                    <center>Sick Leave</center>
                                </th>
                                <th style="text-align:center" valign="middle">REMARKS</th>
                                <th style="text-align:center"></th>
                            </tr>
                            <tr>
                                <th>Earned</th>
                                <th width="8%" style="">
                                    <center>Undertime w/ Pay</center>
                                </th>
                                <th>Balance</th>
                                <th width="8%" style="">
                                    <center>Undertime w/o Pay</center>
                                </th>
                                <th>Earned</th>
                                <th width="8%" style="">
                                    <center>Undertime w/ Pay</center>
                                </th>
                                <th>Balance</th>
                                <th width="8%" style="">
                                    <center>Undertime w/o Pay</center>
                                </th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if($ledger['Code'] == "0"){
                                foreach ($ledger['Data']['details'] as $k => $value) {
                            ?>
                            <tr>
                                <td><?php echo $value['month_name']; ?></td>
                                <td></td>
                                <td class="text-success"> <?php echo '+'.$value['leave_vacation_earned']; ?></td>
                                <td class="text-danger"> <?php echo '-'.$value['leave_vacation_undertime_w_pay']; ?></td>
                                <td class="text-primary"><?php echo $value['leave_vacation_balance']; ?></td>
                                <td class="text-danger"></td>
                                <td class="text-success"> <?php echo '+'.$value['leave_sick_earned']; ?></td>
                                <td class="text-danger"> <?php echo '-'.$value['leave_sick_undertime_w_pay']; ?></td>
                                <td class="text-primary"><?php echo $value['leave_sick_balance']; ?></td>
                                <td></td>
                                <td><?php echo $value['remarks']; ?></td>
                                <td class="text-right">
                                    <?php 
                                    $buttons_data = "";
                                    foreach ($value as $k1 => $v1) {
                                        $buttons_data.=' data-'.$k1.'="'.$v1.'" ';
                                    } ?>
                                    <a href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateEmployeeLedgerForm'; ?>" class="updateEmployeeLedgerForm btn btn-info btn-circle waves-effect waves-circle waves-float" <?php echo $buttons_data; ?>><i class="material-icons">edit</i></a>
                                </td>
                            </tr>
                            <?php 
                                }
                            }
                            for ($i=0; $i < 12; $i++) {  
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
                                    <td></td>
                                    <td></td>
                                </tr>
                            <?php 
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
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
