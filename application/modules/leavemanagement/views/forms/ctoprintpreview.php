<div class="modal fade " id="print_preview_modal" tabindex="-1" role="dialog" aria-labelledby="print_preview_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-blue">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
                <h4 class="modal-title">Print Preview</h4>
            </div>
            <div class = "table-responsive">
                <div id = "content">
                    <style type="text/css" media="all">
                        @media screen {
                            .print-only {
                                display: none;
                            }
                        }

                        @media print {
                            @page { 
                                size: letter portrait;
                                /* width:816px;
                                height:1344px; */
                                margin: -5mm 10mm 10mm 10mm;
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
                            .firstApprover,.secondApprover, .thirdApprover{
                                height: 30px !important;
                            }
                        }
                        </style>
                    <!-- <center>
                        <p style="padding-top: 4px">
                            <span style="text-align: center; font-size:14pt; font-family: 'Times New Roman', Times, serif;">Republic of the Philippines</span><br>
                            <span style="text-align: center; font-size:12pt; font-family: 'Times New Roman', Times, serif;">NATIONAL WATER RESOURCES BOARD</span><br>
                            <span style="text-align: center; font-size:11pt; font-family: 'Times New Roman', Times, serif;">8th Floor NIA Bldg., EDSA, Quezon City</span><br>
                        </p>
                    </center> -->
                    <br>
                    <div class = "container-fluid">
                        <table style="border-collapse: collapse; width: 100%; border: 1px solid black">
                            <tr>
                                <td style="text-align: right;" colspan="4">
                                    <div class="col-md-12"> 
                                        <label class="form-label"><small>Date of Filing:</small></label>
                                        <span style="position: relative; left: 20px; border-bottom: 1px solid black; text-align: left; margin-right: 30px;" class="datefiling boxes"></span>
                                    </div>
                                </td>
                            </tr> 
                            <tr>
                                <th style="text-align: center; font-size:11pt;" colspan="4">APPLICATION FOR AVAILMENT OF <br> COMPENSATORY TIME OFF (CTO)</th>
                            </tr>   
                            <tr>
                                <th style="text-align: center; padding: 2px 2px 5px;" colspan="4" ></th>
                            </tr>
                                <td style="text-align: left; width:10%;">
                                    <div class="col-md-12">
                                        <label class="form-label"><strong>Name:</strong></label>
                                    </div>
                                </td>
                                <td style="text-align: center; width:30%;">
                                    <div style="width:90%; position: relative;" class="lname boxes"></div>
                                    <div style="width:90%; border-top: 1px solid black;">(Last)</div>
                                </td>
                                <td style="text-align: center; width:30%;">
                                    <div style="width:90%; position: relative;" class="fname boxes"></div>
                                    <div style="width:90%; border-top: 1px solid black;">(First)</div>
                                </td>
                                <td style="text-align: center; width:30%;">
                                    <div style="width:90%; position: relative;" class="mname boxes"></div>
                                    <div style="width:90%; border-top: 1px solid black;">(Middle)</div>
                                </td>
                            </tr>                                
                            <tr>
                                <th style="text-align: center; padding: 2px 2px 15px;" colspan="4"></th>                                    
                            </tr>
                               <tr>
                                    <td style="text-align: left;" colspan="2">
                                        <div class="col-md-12"> 
                                            <label class="form-label"><strong>Position:</strong></label>
                                            <span style="position: relative; left: 20px; text-align: left; text-decoration: underline;" class="position boxes"></span>
                                        </div>
                                    </td>
                                    <td style="text-align: left;" colspan="2">
                                        <div class="col-md-12"> 
                                            <label class="form-label"><strong>Office/Division:</strong></label>
                                            <span style="position: relative; left: 10px; text-align: left; text-decoration: underline;" class="servicedivisionunit boxes"></span>
                                        </div>
                                    </td>
                                </tr> 
                                <tr>
                                    <th style="text-align: center; padding: 2px 2px 15px;" colspan="4"></th>                                    
                                </tr>
                                <tr>
                                    <th style="text-align: center; font-size:11pt;" colspan="4">DETAILS OF APPLICATION</th>
                                </tr> 
                                <tr>
                                    <td style="text-align: left;" colspan="2">
                                        <div class="col-md-12"> 
                                            <div id="no_of_hrs_content">
                                                <label class="form-label"><small>No. of Hours Applied for:</small></label>
                                                <!-- <span class="form-group form-float"> -->
                                                    <span style="position: relative; left: 20px; text-align: left; text-decoration: underline;" class="no_of_hrs boxes"></span>
                                                <!-- </span> -->
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label"><small></small></label><br>
                                                <center>
                                                    <div style="width: 70%; height: 30px; margin-top: 20px; margin-bottom: -10px;" class="applicantSign"></div>
                                                    <div style="width: 70%; border-top: 1px solid black;">(Signature of Applicant)</div><br>
                                                </center>
                                        </div>
                                    </td>
                                    <td style="text-align: left;" colspan="2" valign="top">
                                        <div class="col-md-12"> 
                                            <div id="activity_date_content">
                                                <label class="form-label"><small>Inclusive Dates:</small></label>
                                                <!-- <span class="form-group form-float"> -->
                                                    <span style="position: relative; left: 20px; text-align: left; text-decoration: underline;;" class="transaction_date boxes"></span>
                                                <!-- </span> -->
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label"><small>Recommending Approval:</small></label><br>
                                            <center>
                                                <div style="width: 70%; border-bottom: 1px solid black;height: 40px" class="secondApprover boxes"></div><br>
                                            </center>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; padding: 2px 2px 5px;" colspan="4" ></td>
                                </tr>
                                <tr>
                                    <th style="text-align: center; font-size:11pt;" colspan="4">DETAILS OF ACTION ON APPLICATION</th>
                                </tr> 
                                <tr>
                                    <td style="text-align: left;" colspan="2">
                                        <table width="100%" style="margin-left:10px;">
                                            <tbody>
                                                <tr>
                                                    <td><label class="form-label"><small>A. Certification of Compensatory Overtime</small></label></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div style="margin:auto;"> 
                                                            <div style="display: flex;">
                                                                <span style="display: inline-block; width: 160px; padding-right: 10px;"><small>Credits (COC) as of:</small></span>
                                                                <div style="text-align: center; width: 150px; border-bottom: 1px solid black; height: 20px;" class="as_of boxes"><?php echo date("F d, Y"); ?></div>
                                                            </div>
                                                            <div style="display: flex;">
                                                                <span style="display: inline-block; width: 160px; padding-right: 10px;"><small>No. of Hours Earned:</small></span>
                                                                <div style="text-align: center; width: 150px; border-bottom: 1px solid black; height: 20px;" class="hours_earned boxes"></div>
                                                            </div>
                                                            <div style="display: flex;">
                                                                <span style="display: inline-block; width: 160px; padding-right: 10px;"><small>Less this application:</small></span>
                                                                <div style="text-align: center; width: 150px; border-bottom: 1px solid black; height: 20px;" class="less boxes"></div>
                                                            </div>
                                                            <div style="display: flex;">
                                                                <span style="display: inline-block; width: 160px; padding-right: 10px;"><small>Remaining COCs:</small></span>
                                                                <div style="text-align: center; width: 150px; border-bottom: 1px solid black; height: 20px;" class="remaining boxes"></div>
                                                            </div><br>
                                                            <div style="margin:auto; text-align: center;">
                                                                <div style="width: 75%; border-bottom: 1px solid black; height: 40px" class="firstApprover boxes"></div><br>
                                                                <!-- <div style="width: 70%;line-height: 1;">Administrative V</div> -->
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td style="text-align: left;" colspan="2" valign="top">
                                        <table width="100%" style="margin-left:10px;">
                                            <tbody>
                                                <tr>
                                                    <td><label class="form-label"><small>B. Approval</small></label></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div style="margin:auto;">
                                                            <div class="sqr_boxes" style="border: 1px solid black; height: 14px; width: 20px;text-align: center;padding-top: -2px; float: left; display: block; " class="boxes">&nbsp<span class="thirdApproval" style="position:relative; top:-3px;"></span></div>
                                                            <span style = "float: left; display: block;">&nbsp; For approval</span><br>
                                                            <div class="sqr_boxes" style="border: 1px solid black; height: 14px; width: 20px;text-align: center;padding-top: -2px; float: left; display: block; " class="boxes">&nbsp<span class="thirdDisapproval" style="position:relative; top:-3px;"></span></div>
                                                            <span style = "float: left; display: block;">&nbsp; For disapproval due to </span><div style="text-align: center; width: 180px; border-bottom: 1px solid black; height: 20px;  position: relative; left: 182px;"><label style="position: absolute; left: 0; font-weight: normal"  class="thirdRemarks_a boxes">asduihmox38038fofoxe...</label></div><br>
                                                            <div style="text-align: left; width: 64%; border-bottom: 1px solid black;height: 20px;" class="thirdRemarks_b boxes"></div><br>
                                                                <center>
                                                                    <div style="width: 75%; border-bottom: 1px solid black;height: 40px" class="thirdApprover boxes"></div>
                                                                    <div style="width: 70%;line-height: 1;">(Division Chief/Authorized Official)</div>
                                                                </center>
                                                            </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <!-- <tr>
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
                                </tr> -->
                        </table>  
                        <div class="print-only"> 
                            <table style="border-collapse: collapse; width: 100%; border: 1px solid black; margin-top:10px">
                            <tr>
                                <td style="text-align: right;" colspan="4">
                                    <div class="col-md-12"> 
                                        <label class="form-label"><small>Date of Filing:</small></label>
                                        <span style="position: relative; left: 20px; border-bottom: 1px solid black; text-align: left; margin-right: 30px;" class="datefiling boxes"></span>
                                    </div>
                                </td>
                            </tr> 
                            <tr>
                                <th style="text-align: center; font-size:11pt;" colspan="4">APPLICATION FOR AVAILMENT OF <br> COMPENSATORY TIME OFF (CTO)</th>
                            </tr>   
                            <tr>
                                <th style="text-align: center; padding: 2px 2px 5px;" colspan="4" ></th>
                            </tr>
                                <td style="text-align: left; width:10%;">
                                    <div class="col-md-12">
                                        <label class="form-label"><strong>Name:</strong></label>
                                    </div>
                                </td>
                                <td style="text-align: center; width:30%;">
                                    <div style="width:90%; position: relative;" class="lname boxes"></div>
                                    <div style="width:90%; border-top: 1px solid black;">(Last)</div>
                                </td>
                                <td style="text-align: center; width:30%;">
                                    <div style="width:90%; position: relative;" class="fname boxes"></div>
                                    <div style="width:90%; border-top: 1px solid black;">(First)</div>
                                </td>
                                <td style="text-align: center; width:30%;">
                                    <div style="width:90%; position: relative;" class="mname boxes"></div>
                                    <div style="width:90%; border-top: 1px solid black;">(Middle)</div>
                                </td>
                            </tr>                                
                            <tr>
                                <th style="text-align: center; padding: 2px 2px 15px;" colspan="4"></th>                                    
                            </tr>
                               <tr>
                                    <td style="text-align: left;" colspan="2">
                                        <div class="col-md-12"> 
                                            <label class="form-label"><strong>Position:</strong></label>
                                            <span style="position: relative; left: 20px; text-align: left; text-decoration: underline;" class="position boxes"></span>
                                        </div>
                                    </td>
                                    <td style="text-align: left;" colspan="2">
                                        <div class="col-md-12"> 
                                            <label class="form-label"><strong>Office/Division:</strong></label>
                                            <span style="position: relative; left: 0px; text-align: left; text-decoration: underline;" class="servicedivisionunit boxes"></span>
                                        </div>
                                    </td>
                                </tr> 
                                <tr>
                                    <th style="text-align: center; padding: 2px 2px 15px;" colspan="4"></th>                                    
                                </tr>
                                <tr>
                                    <th style="text-align: center; font-size:11pt;" colspan="4">DETAILS OF APPLICATION</th>
                                </tr> 
                                <tr>
                                    <td style="text-align: left;" colspan="2">
                                        <div class="col-md-12"> 
                                            <div id="no_of_hrs_content">
                                                <label class="form-label"><small>No. of Hours Applied for:</small></label>
                                                <!-- <span class="form-group form-float"> -->
                                                    <span style="position: relative; left: 20px; text-align: left; text-decoration: underline;" class="no_of_hrs boxes"></span>
                                                <!-- </span> -->
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label"><small></small></label><br>
                                                <center>
                                                    <div style="width: 70%; height: 30px; margin-top: 20px; margin-bottom: -10px;" class="applicantSign"></div>
                                                    <div style="width: 70%; border-top: 1px solid black;">(Signature of Applicant)</div><br>
                                                </center>
                                        </div>
                                    </td>
                                    <td style="text-align: left;" colspan="2" valign="top">
                                        <div class="col-md-12"> 
                                            <div id="activity_date_content">
                                                <label class="form-label"><small>Inclusive Dates:</small></label>
                                                <!-- <span class="form-group form-float"> -->
                                                    <span style="position: relative; left: 20px; text-align: left; text-decoration: underline;;" class="transaction_date boxes"></span>
                                                <!-- </span> -->
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label"><small>Recommending Approval:</small></label><br>
                                            <center>
                                                <div style="width: 70%; border-bottom: 1px solid black;height: 40px" class="secondApprover boxes"></div><br>
                                            </center>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; padding: 2px 2px 5px;" colspan="4" ></td>
                                </tr>
                                <tr>
                                    <th style="text-align: center; font-size:11pt;" colspan="4">DETAILS OF ACTION ON APPLICATION</th>
                                </tr> 
                                <tr>
                                    <td style="text-align: left;" colspan="2">
                                        <table width="100%" style="margin-left:10px;">
                                            <tbody>
                                                <tr>
                                                    <td><label class="form-label"><small>A. Certification of Compensatory Overtime</small></label></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div style="margin:auto;"> 
                                                            <div style="display: flex;">
                                                                <span style="display: inline-block; width: 160px; padding-right: 10px;"><small>Credits (COC) as of:</small></span>
                                                                <div style="text-align: center; width: 150px; border-bottom: 1px solid black; height: 20px;" class="as_of boxes"><?php echo date("F d, Y"); ?></div>
                                                            </div>
                                                            <div style="display: flex;">
                                                                <span style="display: inline-block; width: 160px; padding-right: 10px;"><small>No. of Hours Earned:</small></span>
                                                                <div style="text-align: center; width: 150px; border-bottom: 1px solid black; height: 20px;" class="hours_earned boxes"></div>
                                                            </div>
                                                            <div style="display: flex;">
                                                                <span style="display: inline-block; width: 160px; padding-right: 10px;"><small>Less this application:</small></span>
                                                                <div style="text-align: center; width: 150px; border-bottom: 1px solid black; height: 20px;" class="less boxes"></div>
                                                            </div>
                                                            <div style="display: flex;">
                                                                <span style="display: inline-block; width: 160px; padding-right: 10px;"><small>Remaining COCs:</small></span>
                                                                <div style="text-align: center; width: 150px; border-bottom: 1px solid black; height: 20px;" class="remaining boxes"></div>
                                                            </div><br>
                                                            <div style="margin:auto; text-align: center;">
                                                                <div style="width: 75%; border-bottom: 1px solid black; height: 40px" class="firstApprover boxes"></div><br>
                                                                <!-- <div style="width: 70%;line-height: 1;">Administrative V</div> -->
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td style="text-align: left;" colspan="2" valign="top">
                                        <table width="100%" style="margin-left:10px;">
                                            <tbody>
                                                <tr>
                                                    <td><label class="form-label"><small>B. Approval</small></label></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div style="margin:auto;">
                                                            <div class="sqr_boxes" style="border: 1px solid black; height: 14px; width: 20px;text-align: center;padding-top: -2px; float: left; display: block; " class="boxes">&nbsp<span class="thirdApproval" style="position:relative; top:-3px;"></span></div>
                                                            <span style = "float: left; display: block;">&nbsp; For approval</span><br>
                                                            <div class="sqr_boxes" style="border: 1px solid black; height: 14px; width: 20px;text-align: center;padding-top: -2px; float: left; display: block; " class="boxes">&nbsp<span class="thirdDisapproval" style="position:relative; top:-3px;"></span></div>
                                                            <span style = "float: left; display: block;">&nbsp; For disapproval due to </span><div style="text-align: center; width: 180px; border-bottom: 1px solid black; height: 20px;  position: relative; left: 182px;"><label style="position: absolute; left: 0; font-weight: normal"  class="thirdRemarks_a boxes">asduihmox38038fofoxe...</label></div><br>
                                                            <div style="text-align: left; width: 64%; border-bottom: 1px solid black;height: 20px;" class="thirdRemarks_b boxes"></div><br>
                                                                <center>
                                                                    <div style="width: 75%; border-bottom: 1px solid black;height: 40px" class="thirdApprover boxes"></div>
                                                                    <div style="width: 70%;line-height: 1;">(Division Chief/Authorized Official)</div>
                                                                </center>
                                                            </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <!-- <tr>
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
                                </tr> -->
                            </table>   
                        </div>  
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
