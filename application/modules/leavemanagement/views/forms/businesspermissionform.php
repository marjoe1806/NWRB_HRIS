<div class="modal fade" id="print_preview_modal" tabindex="-1" role="dialog" aria-labelledby="print_preview_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 100%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
                <h4 class="modal-title">Print Preview</h4>
            </div>
            <div class = "table-responsive">
                <div id = "content">
                    <style type="text/css" media="print">
                        @media print {
                            @page { 
                                size: legal portrait;
                                width:816px;
                                height:1344px;
                                margin: 10mm 10mm 10mm 10mm;
                            }

                            #foot label{
                                color: black;
                                font-family: arial;
                                font-size: 6pt;
                            }

                            .break{
                                page-break-before: always;
                            }

                            span, b{
                                font-family: calibri;
                                font-size: 11pt;
                            }

                            table{
                                font-family: calibri;
                                font-size: 11pt;
                            }

                            h5{
                                font-family: calibri;
                                font-size: 11pt;
                            }
                        }
                        </style>
                    <center>
                        <h3 style="font-family: calibri; margin: 0px;">NATIONAL WATER RESOURCES BOARD</h3>
                        <small>8th Flr. NIA Building EDSA, Diliman, Quezon City</small>
                        <h5 style="font-family: calibri;">Static Date</h5>
                    </center>
                    <div class = "container-fluid">
                         <table style="border-collapse: collapse; width: 100%;">
                                <tr>
                                    <th style="text-align: center; border: 1px solid black; font-size:15pt;" colspan="4">APPLICATION FOR LEAVE</th>
                                </tr>   
                                <tr>
                                    <td style="text-align: center; border: 1px solid black; padding: 10px 10px 5px;" colspan="4" ></td>
                                    
                                </tr>

                                <tr>
                                    <td style="text-align: left; border-bottom: 1px solid black; border-left: 1px solid black;">
                                        1. Service/Division/Unit<br>
                                        <input type="text" name="servicedivisionunit" id = "servicedivisionunit" style="position: relative; left: 10px; top: -3px; border: 0px; text-align: center;" readonly>
                                    </td>
                                    <td style="text-align: left; border-bottom: 1px solid black;">
                                        2. Name (Last)<br>
                                        <input type="text" name="lname" id = "lname" style="position: relative; left: 10px; top: -3px; border: 0px; text-align: center;" readonly>
                                    </td>
                                    <td style="text-align: left; border-bottom: 1px solid black;">
                                        2. (First)<br>
                                        <input type="text" name="fname" id = "fname" style="position: relative; left: 10px; top: -3px; border: 0px; text-align: center;" readonly>
                                    </td>
                                    <td style="text-align: left; border-bottom: 1px solid black; border-right: 1px solid black;">
                                        2. (Middle)<br>
                                        <input type="text" name="mname" id = "mname" style="position: relative; left: 10px; top: -3px; border: 0px; text-align: center;" readonly>
                                    </td>
                                </tr>

                               <tr>
                                    <td style="text-align: left; border-bottom: 1px solid black; border-left: 1px solid black;">
                                        3. Date of Filing<br>
                                        <input type="text" name="datefiling" id = "datefiling" style="position: relative; left: 10px; top: -3px; border: 0px; text-align: center;" readonly>
                                    </td>
                                    <td style="text-align: left; border-bottom: 1px solid black;" colspan="2">
                                        4. Position<br>
                                        <input type="text" name="position" id = "position" style="position: relative; left: 10px; top: -3px; border: 0px; text-align: center;" readonly>
                                    </td>
                                    <td style="text-align: left; border-bottom: 1px solid black; border-right: 1px solid black;">
                                        5. Salary<br>
                                        <input type="text" name="salary" id = "salary" style="position: relative; left: 10px; top: -3px; border: 0px; text-align: center;" readonly>
                                    </td>
                                </tr>

                                <tr>
                                    <th style="text-align: center; border: 1px solid black; font-size:15pt;" colspan="4">DETAILS OF APPLICATION</th>
                                </tr> 

                                <tr>
                                    <td style="text-align: left; border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;" colspan="2">
                                        <b>6. C) Type of Leave</b><br>
                                            <div style="padding-left:25px; ">
                                                <div style="border: 1px solid black; height: 15px; width: 15px; float: left; display: block; padding-left:1px;">&nbsp&nbsp/</div>
                                                <span style = "float: left; display: block;">&nbsp Vacation/Mandatory/Forced Leave</span><br>
                                                <div style="border: 1px solid black; height: 15px; width: 15px; float: left; display: block; padding-left:1px;"></div>
                                                <span style = "float: left; display: block;">&nbsp Sick Leave</span><br>
                                                <div style="border: 1px solid black; height: 15px; width: 15px; float: left; display: block; padding-left:1px;"></div>
                                                <span style = "float: left; display: block;">&nbsp Maternity Leave</span><br>
                                                <div style="border: 1px solid black; height: 15px; width: 15px; float: left; display: block; padding-left:1px;"></div>
                                                <span style = "float: left; display: block;">&nbsp Paternity Leave</span><br>
                                                <div style="border: 1px solid black; height: 15px; width: 15px; float: left; display: block; padding-left:1px;"></div>
                                                <span style = "float: left; display: block;">&nbsp Special Leave</span><br>
                                                <div style="border: 1px solid black; height: 15px; width: 15px; float: left; display: block; padding-left:1px;"></div>
                                                <span style = "float: left; display: block;">&nbsp Study Leave</span><br>
                                                <div style="border: 1px solid black; height: 15px; width: 15px; float: left; display: block; padding-left:1px;"></div>
                                                <span style = "float: left; display: block;">&nbsp Violence Against Women and Children Leave</span><br><br>
                                                <div style="border: 1px solid black; height: 15px; width: 15px; float: left; display: block; padding-left:1px;"></div>
                                                <span style = "float: left; display: block;">&nbsp Rehabilitation Privilege</span><br>
                                                <div style="border: 1px solid black; height: 15px; width: 15px; float: left; display: block; padding-left:1px;"></div>
                                                <span style = "float: left; display: block;">&nbsp Special Leave Benefits for Women</span><br>
                                                <div style="border: 1px solid black; height: 15px; width: 15px; float: left; display: block; padding-left:1px;"></div>
                                                <span style = "float: left; display: block;">&nbsp Calamity Leave</span><br>
                                                Other Purpose <br><br>
                                                <div style="border: 1px solid black; height: 15px; width: 15px; float: left; display: block; padding-left:1px;"></div>
                                                <span style = "float: left; display: block;">&nbsp Monetization of Leave Credits</span><br>
                                                <div style="border: 1px solid black; height: 15px; width: 15px; float: left; display: block; padding-left:1px;"></div>
                                                <span style = "float: left; display: block;">&nbsp Terminal Leave</span><br>
                                            </div>
                                    </td>
                                    <td style="text-align: left; border-bottom: 1px solid black; border-right: 1px solid black;" colspan="2" valign="top">
                                            <b>6. B) Deatails of Leave:</b><br>
                                            <div style="padding-left:25px; ">
                                                &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspIn case of Vacation/Special Privelege Leave<br>
                                                <div style="border: 1px solid black; height: 15px; width: 15px; float: left; display: block; padding-left:1px;"></div>
                                                <span style = "float: left; display: block;">&nbsp Within the Philippines (Specify) _______________</span><br>
                                                <div style="border: 1px solid black; height: 15px; width: 15px; float: left; display: block; padding-left:1px;"></div>
                                                <span style = "float: left; display: block;">&nbsp Abroad (Specify) ____________________________</span><br>
                                                &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspIn case of Vacation/Special Privelege Leave<br>
                                                <div style="border: 1px solid black; height: 15px; width: 15px; float: left; display: block; padding-left:1px;"></div>
                                                <span style = "float: left; display: block;">&nbsp In Hospital (Specify Illness) _______________</span><br>
                                                <div style="border: 1px solid black; height: 15px; width: 15px; float: left; display: block; padding-left:1px;"></div>
                                                <span style = "float: left; display: block;">&nbsp Our Patient (Specify Illness) _______________</span><br>
                                                &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspIn case of Special Leave Benefits for Women:<br>
                                                &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp(Specify Illness)_______________________<br>
                                                &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspIn case of Leave<br>
                                                <div style="border: 1px solid black; height: 15px; width: 15px; float: left; display: block; padding-left:1px;"></div>
                                                <span style = "float: left; display: block;">&nbsp Completion of Master's Degree</span><br>
                                                <div style="border: 1px solid black; height: 15px; width: 15px; float: left; display: block; padding-left:1px;"></div>
                                                <span style = "float: left; display: block;">&nbsp BAR/Board Examination Review</span><br>
                                            </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left; border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;" colspan="2">
                                        <b>6. C) Type of Leave</b><br>
                                        &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp______________________<br><br>
                                        &nbsp&nbsp<b>Inclusive Dates:</b><br>
                                        &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp______________________<br><br>
                                    </td>
                                    <td style="text-align: left; border-bottom: 1px solid black; border-right: 1px solid black;" colspan="2" valign="top">
                                        <b>6. D) Communication</b><br>
                                            <div style="padding-left:25px; ">
                                                <div style="border: 1px solid black; height: 15px; width: 15px; float: left; display: block; padding-left:1px;"></div>
                                                <span style = "float: left; display: block;">&nbsp Requested&nbsp&nbsp&nbsp&nbsp </span>
                                                <div style="border: 1px solid black; height: 15px; width: 15px; float: left; display: block; padding-left:1px;"></div>
                                                <span style = "float: left; display: block;">&nbsp Not Requested</span><br><br>
                                                &nbsp&nbsp&nbsp&nbsp_________________________<br>
                                                &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspSignature of Applicant
                                            </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; border: 1px solid black; padding: 10px 10px 5px;" colspan="4" ></td>
                                </tr>
                                <tr>
                                    <th style="text-align: center; border: 1px solid black; font-size:15pt;" colspan="4">DETAILS OF ACTION ON APPLICATION</th>
                                </tr> 
                                <tr>
                                    <td style="text-align: left; border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;" colspan="2">
                                        <b>7. A) Certification</b><br><br>
                                            <div style="margin:auto; width:300px;">   
                                                Leave Credits of: ________________________________
                                                <table style="border-collapse: collapse; border: 1px solid black; width:100%;">
                                                    <tr style="border: 1px solid black;">
                                                        <td style="text-align: left; border: 1px solid black;" colspan="2">Vacation</td>
                                                        <td style="text-align: left; border: 1px solid black;" colspan="2" valign="top">Sick</td>
                                                        <td style="text-align: left; border: 1px solid black;" colspan="2" valign="top">Total</td>
                                                    </tr>
                                                     <tr style="border: 1px solid black;">
                                                        <td style="text-align: left; border: 1px solid black;" colspan="2"><input type="text" name="vacationdays" id = "vacationdays" style=" border: 0px; text-align: center; width:30px;" readonly></td>
                                                        <td style="text-align: left; border: 1px solid black;" colspan="2" valign="top"><input type="text" name="sickdays" id = "sickdays" style=" border: 0px; text-align: center; width:30px;" readonly></td>
                                                        <td style="text-align: left; border: 1px solid black;" colspan="2" valign="top"><input type="text" name="totaldays" id = "totaldays" style=" border: 0px; text-align: center; width:30px;" readonly></td>
                                                    </tr>
                                                    <tr style="border: 1px solid black;">
                                                        <td style="text-align: left; border: 1px solid black;" colspan="2">days</td>
                                                        <td style="text-align: left; border: 1px solid black;" colspan="2" valign="top">days</td>
                                                        <td style="text-align: left; border: 1px solid black;" colspan="2" valign="top">days</td>
                                                    </tr>
                                                </table>
                                                <br><br>
                                            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp_______________________________<br>
                                            &nbsp&nbsp&nbsp&nbsp&nbsp&nbspSignature over Printed Name<br>
                                            &nbsp&nbsp&nbsp&nbsp&nbsp&nbspof Authorized Official (FMAS)<br>
                                            </div>
                                         </td>
                                    <td style="text-align: left; border: 1px solid black;" colspan="2" valign="top">
                                        <b>7. A) Certification</b><br><br>
                                            <div style="margin:auto; width:300px;">   
                                              
                                               <div style="border: 1px solid black; height: 15px; width: 15px; float: left; display: block; padding-left:1px;"></div>
                                               <span style = "float: left; display: block;">&nbsp Approval</span><br>
                                               <div style="border: 1px solid black; height: 15px; width: 15px; float: left; display: block; padding-left:1px;"></div>
                                               <span style = "float: left; display: block;">&nbsp Disapproval due to</span><br>
                                               &nbsp&nbsp&nbsp&nbsp&nbsp_______________________________<br><br>
                                               &nbsp&nbsp&nbsp&nbsp&nbsp__________________________________<br>
                                               &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspSignature over Printed Name<br>
                                               &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspof Chief
                                            </div>
                                    </td>
                                </tr>
                                <tr>
                                     <td style=" border: 1px solid black; padding: 10px 10px 5px;" colspan="4" >
                                        <p style="text-align: center;"><b>7. C) Action of Approving Officer:</b></p><br>
                                            <div style="margin:auto; width:260px;"> 
                                               <div style="border: 1px solid black; height: 15px; width: 15px; float: left; display: block; padding-left:1px;"></div>
                                               <span style = "float: left; display: block;">&nbsp Approval</span><br>
                                               <div style="border: 1px solid black; height: 15px; width: 15px; float: left; display: block; padding-left:1px;"></div>
                                               <span style = "float: left; display: block;">&nbsp Disapproval due to_____________</span><br>
                                            </div>  <br><br>
                                            <div style="margin:auto; width:400px;"> 
                                               ____________________________________________________<br>
                                               &nbsp&nbsp&nbsp&nbsp&nbspSignature over Printed Name of Authorized Official<br>
                                               &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp(Director/Member of Collegial Body)
                                     </td>
                                </tr>
                            </table>    
                       
                    </div>  
                </div>
            </div>
            <div class = "modal-footer">
                <div class = "container-fluid">  
                    <button type="submit" class="btn btn-success" id = "print_preview_appleave">Print Preview</button>
                </div>
            </div>
        </div>
    </div>
</div>
