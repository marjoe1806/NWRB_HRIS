<?php
    $last_name = null;
    $first_name = null;
    $middle_name = null;
    $birthday = null;
    $birthplace = null;
    $issued = null;
    $admin_fname = null;
    $admin_mname = null;
    $admin_lname = null;
    $division = null;
    $position = null;
    //var_dump($signatory->Data->details);die();
    if(isset($signatory->Data->details) && sizeof($signatory->Data->details) > 0){
        $employee_name = explode(" ",$signatory->Data->details[0]->employee_name);
        $admin_fname = strToUpper($employee_name[0]); 
        $admin_mname = strToUpper($employee_name[1]);
        $admin_lname = strToUpper($employee_name[2]);
        $issued = $signatory->Data->details[0]->issued;
        $division = $signatory->Data->details[0]->division;;
        $position = $signatory->Data->details[0]->position;;
    }
    if(isset($list->Data->details) && sizeof($list->Data->details) > 0){
        $employee_name2 = explode(" ",$list->Data->details[0]->service_employee_name);
        $last_name = strToUpper($employee_name2[2]);
        $first_name = strToUpper($employee_name2[0]);
        $middle_name = strToUpper($employee_name2[1]);
        $birthday = "November 29, 1995";
        $birthplace = "QUEZON CITY";
    }
?>
<div id="servicerecords-container" class="table-responsive listTable">
    <div id="records-container" class="table-responsive listTable" style="width:100%;font-size:9px;font-family:Arial">
        <style>
            @media print{

                @page {
                    margin-top: 200px;
                }
                body{
                    color:#333;
                    margin: 30mm 30mm 50mm 25mm;
                } 
                table {
                    border-spacing: 0;
                    border-collapse: collapse;
                    font-size:9px;font-family:Arial;
                }
                #servicerecordtable td {
                    border: 1px solid #3b3b3b;
                }
                .text-center {
                    text-align: center;
                }
                .text-right {
                    text-align: right;
                }
                .text-left {
                    text-align: left;
                }
                .row {
                    margin-right: -15px;
                    margin-left: -15px;
                }  
            }

        </style>
        <center><h1>SERVICE RECORD</h1><center>
        <br>
        <br>
        <table style="width:100%;">
            <tr>
                <td>NAME:</td>
                <td class="text-center" style="border-bottom:1px solid;"><?php echo $last_name; ?></td>
                <td class="text-center" colspan="2" style="border-bottom:1px solid;"><?php echo $first_name; ?></td>
                <td class="text-center" style="border-bottom:1px solid;"><?php echo $middle_name; ?></td>
                <td>(If married woman, give also full name.)</td>
            </tr>
            <tr>
                <td></td>
                <td class="text-center">(Surname)</td>
                <td class="text-center" colspan="2">(Name)</td>
                <td class="text-center">(Middle Name)</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="6"><br></td>
            </tr>
            <tr>
                <td>BIRTH:</td>
                <td class="text-center" colspan="2" style="border-bottom:1px solid;"><?php echo $birthday; ?></td>
                <td class="text-center" colspan="2" style="border-bottom:1px solid;"><?php echo $birthplace; ?></td>
                <td>(Data herein should be checked from birtd or baptismal certificate or some </td>
            </tr>
            <tr>
                <td></td>
                <td class="text-center" colspan="2">(Date)</td>
                <td class="text-center" colspan="2">(Place)</td>
                <td>otder reliable documents.)</td>
            </tr>
            <tr>
                <td colspan="6"><br></td>
            </tr>
            <tr>
                <td colspan="6">This is to certify that the employee named hereinabove actually rendered services in this Office as shown by the service record below, each line of which is</td>
            </tr>
            <tr>
                <td colspan="6">supported by appointment and other papers actually issued by this Office and approved by the authorities concerned.</td>
            </tr>
            <tr>
                <td colspan="6"><br></td>
            </tr>
        </table>
        <table id="servicerecordtable" class="table-bordered" style="width:100%;border:1px solid;">
            <thead>
                <tr>
                    <td colspan="2" class="text-center" valign="bottom">Service <br> (Inclusive Dates)</td>
                    <td colspan="3" class="text-center" valign="bottom">Records of Appointment</td>
                    <td colspan="2" class="text-center" valign="bottom">Office/Entity/Division</td>
                    <td rowspan="2" class="text-center" valign="center">L/V ABS <br> W/O PAY</td>
                    <td colspan="2" class="text-center" valign="bottom">Separation/Remarks (4)</td>
                                                            
                </tr>
                <tr>
                    <td class="text-center" valign="bottom">From</td>
                    <td class="text-center" valign="bottom">To</td>
                    <td class="text-center" valign="bottom">Designation</td>
                    <td class="text-center" valign="bottom">Status<br>(1)</td>
                    <td class="text-center" valign="bottom">Salary<br>(2)</td>
                    <td class="text-center" valign="bottom">Station/Place of Assignment</td>
                    <td class="text-center" valign="bottom">Branch<br>(3)</td>
                    <td class="text-center" valign="bottom">Date</td>
                    <td class="text-center" valign="bottom">Cause</td>
                                                            
                </tr>
            </thead>
            <tbody>
                <?php 
                if(isset($list->Data->details) && sizeof($list->Data->details) > 0): 
                    foreach ($list->Data->details as $index => $value) { ?>
                    <tr>
                        <td><?php echo $value->service_from; ?></td>
                        <td><?php echo $value->service_to; ?></td>
                        <td><?php echo $value->designation; ?></td>
                        <td><?php echo $value->status; ?></td>
                        <td><?php echo $value->salary; ?></td>
                        <td><?php echo $value->station_place_of_assignment; ?></td>
                        <td><?php echo $value->branch; ?></td>
                        <td><?php echo $value->lv_abs_wo_pay; ?></td>
                        <td><?php echo $value->separation_date; ?></td>
                        <td><?php echo $value->separation_cause; ?></td>
                    </tr>
                <?php }
                endif; ?>
            </tbody>
        </table>
        <br>
        <center><?php echo $issued; ?></center>
        <br>
        <br>
        <div style="width:15%;float:left;">
            <br>
        </div>
        <table style="width:26%;float:left;">
            <tr>
                <td class="text-left" colspan="2"><br></td>
                <td></td>
            </tr>
            <tr>
                <td class="text-left" colspan=""></td>
                <td></td>
            </tr>
            
            <tr>
                <td class="text-left" colspan="2" style="border-bottom:1px solid;">
                    <br>
                    <br>
                    <br>
                </td>
            </tr>
            <tr>
                <td class="text-center" colspan="3">Date</td>
            </tr>
        </table> 
        <div style="width:18%;float:left;">
            <br>
        </div> 
        <table style="width:26%;float:left;">
            <tr>
                <td class="text-left" colspan="2"></td>
                <td></td>
            </tr>
            <tr>
                <td class="text-left" colspan="">Certified Correct</td>
                <td></td>
            </tr>
            <tr>
                <td class="text-left" colspan="2" style="border-bottom:1px solid;">
                    <br>
                    <br>
                    <br>
                </td>
            </tr>
            <tr>
                <td class="text-center" colspan="3"><b><?php echo $admin_fname.' '.substr($admin_mname, 0, 1).'. '.$admin_lname; ?></b></td>
            </tr>
            <tr>
                <td class="text-center" colspan="3"><?php echo $position ?></td>
            </tr>
            <tr>
                <td class="text-center" colspan="3"><?php echo $division ?></td>
            </tr>
        </table>
        <div style="width:15%;float:left;">
            <br>
        </div>  
    </div>
</div>
<hr>
<div class="text-right" style="width:100%;">
    <button id="printServiceRecords" class="btn btn-info btn-sm waves-effect" type="submit">
        <i class="material-icons">print</i><span> Print Preview</span>
    </button>
    <button id="cancelUpdateForm" class="btn btn-default btn-sm waves-effect" data-dismiss="modal" type="button">
        <i class="material-icons">close</i><span> Close</span>
    </button>
</div>