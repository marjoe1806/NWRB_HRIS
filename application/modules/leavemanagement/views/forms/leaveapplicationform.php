<div class="modal fade" id="print_preview_modal" tabindex="-1" role="dialog" aria-labelledby="print_preview_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">
            <div class="modal-header bg-blue">
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
                                margin: none;
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
                                font-size: 10pt;
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
                        <span style="margin-left: 20px; font-weight: bold;float: left; font-size: 11px; font-style: italic">Civil Service Form No. 6<br>Revised 2020</span>
                        <span style="float: right;margin-right:12em;"><br>Date of Receipt: </span>
                    <div class = "container-fluid">
                        <table width="100%" align="center">
                            <tr>
                                <td width="30%" style="border-right: none;">
                                    <table width="85%" cellpadding="0">
                                        <tr>
                                            <td width="60%" valign="middle" align="left" style="padding: 4px;">
                                                <!-- <img src="" width="90%"><br><br> -->
                                            </td>
                                            <td width="40%" valign="middle" align="right" style="padding: 4px;"><img src="<?php echo base_url();?>assets/custom/images/nwrb.png" width="100%"><br><br></td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="40%" id="tdtitle">
                                    <!-- <table style="margin-top: -50px;">
                                        <tr><td><span style="text-align: center; font-size:14pt; font-family: 'Times New Roman', Times, serif;">R</span><span>EPUBLIC OF THE PHILIPPINES</span></td></tr>
                                        <tr><td><span style="text-align: center; font-size:14pt; font-family: 'Times New Roman', Times, serif;">NATIONAL WATER RESOURCES BOARD</span></td></tr>
                                        <tr><td><span style="text-align: center; font-size:14pt; font-family: 'Times New Roman', Times, serif;">8th Floor NIA Bldg., EDSA, Quezon City</span></td></tr>
                                    </table> -->
                                    <center>
                                        <p style="padding-top: 4px">
                                            <span style="text-align: center; font-size:14pt; font-family: 'Times New Roman', Times, serif;">Republic of the Philippines</span><br>
                                            <span style="text-align: center; font-size:12pt; font-family: 'Times New Roman', Times, serif;">NATIONAL WATER RESOURCES BOARD</span><br>
                                            <span style="text-align: center; font-size:11pt; font-family: 'Times New Roman', Times, serif;">8th Floor NIA Bldg., EDSA, Quezon City</span><br>
                                        </p>
                                    </center>
                                </td>
                                <td width="30%" style="font-style: italic;border-right: left;padding-top: 1em;">
                                    <!-- <b>NWRB Form - Leave<br>Revised 2019</b> -->
                                </td>
                            </tr>
                        </table>
                        <table style="border-collapse: collapse; width: 100%;">
                            <tr>
                                <th style="text-align: center; border:none; font-size:15pt;" colspan="4">APPLICATION FOR LEAVE</th>
                            </tr>
                            <tr style="border-top: 1px solid black">
                                <td valign="top" style="width: 40%;text-align: left; border-bottom: 1px solid black; border-left: 1px solid black;">
                                    1.   OFFICE/DEPARTMENT<br>
                                    &nbsp&nbsp&nbsp&nbsp<span id = "servicedivisionunit"></span>
                                </td>
                                <td valign="top" style="width: 20%;text-align: left; border-bottom: 1px solid black;">
                                    2.  NAME :&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp(Last)<br>
                                    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                    <span id = "lname"></span>
                                </td>
                                <td valign="top" style="width: 20%; text-align: left; border-bottom: 1px solid black;">
                                    (First)<br>
                                    &nbsp<span id = "fname"></span>
                                </td>
                                <td valign="top" style="width: 20%; text-align: left; border-bottom: 1px solid black; border-right: 1px solid black;">
                                    (Middle)<br>
                                    &nbsp<span id = "mname"></span>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" style="text-align: left; border-bottom: 1px solid black; border-left: 1px solid black;">
                                3.   DATE OF FILING<br>
                                &nbsp&nbsp&nbsp&nbsp<span id = "datefiling"></span>
                                </td>
                                <td valign="top" style="text-align: left; border-bottom: 1px solid black;" colspan="2">
                                4.   POSITION<br>
                                &nbsp&nbsp&nbsp&nbsp<span id = "position"></span>
                                </td>
                                <td valign="top" style="text-align: left; border-bottom: 1px solid black; border-right: 1px solid black;">
                                5.  SALARY<br>
                                    &nbsp&nbsp&nbsp&nbsp<span id = "salary"></span>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center; border: 1px solid black; padding: 1px;" colspan="4" ></td>
                            </tr>
                            <tr>
                                <th style="text-align: center; border: 1px solid black; font-size:15pt;" colspan="4">6. DETAILS OF APPLICATION</th>
                            </tr> 
                            <tr>
                                <td style="text-align: center; border: 1px solid black; padding: 1px;" colspan="4" ></td>
                            </tr>
                            <tr>
                                <td id="mycolspan" style="text-align: left; border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;" colspan="3">
                                    6.A TYPE OF LEAVE TO AVAILED OF
                                    <div style="padding-left:12px; min-width: 300px;">
                                        <div class="sqr_boxes" style="border: 1px solid black; height: 12px; width: 20px;text-align: center;padding-top: -2px; float: left; display: block; ">&nbsp;<span class="vacation boxes" style="position:relative; top:-3px;"></span></div>
                                        <span style = "float: left; display: block; position: absolute; margin-left: 20px;">&nbsp; Vacation Leave <small>(Sec. 51, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</small></span><br>
                                        <div class="sqr_boxes" style="border: 1px solid black; height: 12px; width: 20px;text-align: center;padding-top: -2px; float: left; display: block; ">&nbsp;<span class="force boxes" style="position:relative; top:-3px;"></span></div>
                                        <span style = "float: left; display: block; position: absolute; margin-left: 20px;">&nbsp; Mandatory/Forced Leave <small>(Sec. 25, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</small></span><br>
                                        <div class="sqr_boxes" style="border: 1px solid black; height: 12px; width: 20px;text-align: center;padding-top: -2px; float: left; display: block; ">&nbsp;<span class="sick boxes" style="position:relative; top:-3px;"></span></div>
                                        <span style = "float: left; display: block; position: absolute; margin-left: 20px;">&nbsp; Sick Leave <small>(Sec. 43, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</small></span><br>
                                        <div class="sqr_boxes" style="border: 1px solid black; height: 12px; width: 20px;text-align: center;padding-top: -2px; float: left; display: block; ">&nbsp;<span class="maternity boxes" style="position:relative; top:-3px;"></span></div>
                                        <span style = "float: left; display: block; position: absolute; margin-left: 20px;">&nbsp; Maternity Leave <small>(R.A. No. 11210 / IRR issued by CSC, DOLE and SSS)</small></span><br>
                                        <div class="sqr_boxes" style="border: 1px solid black; height: 12px; width: 20px;text-align: center;padding-top: -2px; float: left; display: block; ">&nbsp;<span class="paternity boxes" style="position:relative; top:-3px;"></span></div>
                                        <span style = "float: left; display: block; position: absolute; margin-left: 20px;">&nbsp; Paternity Leave <small>(R.A. No. 8187 / CSC MC No. 71, s. 1998, as amended)</small></span><br>
                                        <div class="sqr_boxes" style="border: 1px solid black; height: 12px; width: 20px;text-align: center;padding-top: -2px; float: left; display: block; ">&nbsp;<span class="special boxes" style="position:relative; top:-3px;"></span></div>
                                        <span style = "float: left; display: block; position: absolute; margin-left: 20px;">&nbsp; Special Privilege Leave <small>(Sec. 21, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</small></span><br>
                                        <div class="sqr_boxes" style="border: 1px solid black; height: 12px; width: 20px;text-align: center;padding-top: -2px; float: left; display: block; ">&nbsp;<span class="solo boxes" style="position:relative; top:-3px;"></span></div>
                                        <span style = "float: left; display: block; position: absolute; margin-left: 20px;">&nbsp; Solo Parent Leave <small>(RA No. 8972 / CSC MC No. 8, s. 2004)</small></span><br>
                                        <div class="sqr_boxes" style="border: 1px solid black; height: 12px; width: 20px;text-align: center;padding-top: -2px; float: left; display: block; ">&nbsp;<span class="study boxes" style="position:relative; top:-3px;"></span></div>
                                        <span style = "float: left; display: block; position: absolute; margin-left: 20px;">&nbsp; Study Leave <small>(Sec. 68, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</small></span><br>
                                        <div class="sqr_boxes" style="border: 1px solid black; height: 12px; width: 20px;text-align: center;padding-top: -2px; float: left; display: block; ">&nbsp;<span class="violence boxes" style="position:relative; top:-3px;"></span></div>
                                        <span style = "float: left; display: block; position: absolute; margin-left: 20px;">&nbsp; 10-Day VAWC Leave <small>(RA No. 9262 / CSC MC No. 15, s. 2005)</small></span><br>
                                        <div class="sqr_boxes" style="border: 1px solid black; height: 12px; width: 20px;text-align: center;padding-top: -2px; float: left; display: block; ">&nbsp;<span class="rehab boxes" style="position:relative; top:-3px;"></span></div>
                                        <span style = "float: left; display: block; position: absolute; margin-left: 20px;">&nbsp; Rehabilitation Privilege <small>(Sec. 55, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</small></span><br>
                                        <div class="sqr_boxes" style="border: 1px solid black; height: 12px; width: 20px;text-align: center;padding-top: -2px; float: left; display: block; ">&nbsp;<span class="benefits boxes" style="position:relative; top:-3px;"></span></div>
                                        <span style = "float: left; display: block; position: absolute; margin-left: 20px;">&nbsp; Special Leave Benefits for Women <small>(RA No. 9710 / CSC MC No. 25, s. 2010)</small></span><br>
                                        <div class="sqr_boxes" style="border: 1px solid black; height: 12px; width: 20px;text-align: center;padding-top: -2px; float: left; display: block; ">&nbsp;<span class="calamity boxes" style="position:relative; top:-3px;"></;span></div>
                                        <span style = "float: left; display: block; position: absolute; margin-left: 20px;">&nbsp; Special Emergency (Calamity) Leave <small>(CSC MC No. 2, s. 2012, as amended)</small></span><br>
                                        <div class="sqr_boxes" style="border: 1px solid black; height: 12px; width: 20px;text-align: center;padding-top: -2px; float: left; display: block; ">&nbsp;<span class="adoption boxes" style="position:relative; top:-3px;"></span></div>
                                        <span style = "float: left; display: block; position: absolute; margin-left: 20px;">&nbsp; Adoption Leave <small> (R.A. No. 8552)</small></span><br><br>
                                        <span style = "float: left; display: block;font-style: italic;">&nbsp Others:</span>
                                        <!-- <input type="text" id="othersoffset" style="position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; width:200px;" readonly=""> -->
                                        <input type="text" id="spl_other_details" style="position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; width:100px;" readonly=""><br>
                                    </div> <br>
                                </td>
                                <td style="text-align: left; border-bottom: 1px solid black; border-right: 1px solid black;" colspan="1" valign="top">
                                    6.B DETAILS OF LEAVE
                                    <div style="padding-left:12px; ">
                                        <span style="font-size: 10pt;font-style: italic;">In case of Vacation/Special Privelege Leave:</span><br>
                                        <div class="sqr_boxes" style="border: 1px solid black; height: 12px; width: 20px;text-align: center;padding-top: -2px; float: left; display: block; ">&nbsp;<span id="vacation_loc_php" style="position:relative; top:-3px;"></span></div>
                                        <span style = "float: left; display: block;">&nbsp; Within the Philippines</span> 
                                        <input type="text" id="vacation_loc_details_php" style="position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; width:100px;" readonly=""><br>
                                        <div class="sqr_boxes" style="border: 1px solid black; height: 12px; width: 20px;text-align: center;padding-top: -2px; float: left; display: block; ">&nbsp;<span id="vacation_loc_abr" style="position:relative; top:-3px;"></div>
                                        <span style = "float: left; display: block;">&nbsp; Abroad (Specify) </span>
                                        <input type="text" id="vacation_loc_details_abr" style="position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; width:100px;" readonly=""><br>
                                        <span style="font-size: 10pt;font-style: italic;">In case of Sick Leave:</span><br>
                                        <div class="sqr_boxes" style="border: 1px solid black; height: 12px; width: 20px;text-align: center;padding-top: -2px; float: left; display: block; ">&nbsp;<span id="sick_loc_hosp"></span></div>
                                        <span style = "float: left; display: block;">&nbsp; In Hospital (Specify Illness)</span>
                                        <input type="text" id="sick_loc_details_hosp" style="position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; width:100px;" readonly=""><br>
                                        <div class="sqr_boxes" style="border: 1px solid black; height: 12px; width: 20px;text-align: center;padding-top: -2px; float: left; display: block; ">&nbsp;<span id="sick_loc_pt"></span></div>
                                        <span style = "float: left; display: block;">&nbsp; Out Patient (Specify Illness)</span>
                                        <input type="text" id="sick_loc_details_pt" style="position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; width:100px;" readonly=""><br>
                                        <span style="font-size: 10pt;font-style: italic;">In case of Special Leave Benefits for Women:</span><br>
                                        <span style = "float: left; display: block;">(Specify Illness)</span><input type="text" id="sick_loc_details_illnes" style="position: relative; left: 10px; top: -3px; border: 0px; border-bottom: 1px solid black; width:100px;" readonly=""><br>
                                        <span style="font-size: 10pt;font-style: italic;">In case of Study Leave:</span><br>
                                        <div class="sqr_boxes" style="border: 1px solid black; height: 12px; width: 20px;text-align: center;padding-top: -2px; float: left; display: block; ">&nbsp;<span id="type_study_master"></span></div>
                                        <span style = "float: left; display: block;">&nbsp; Completion of Master's Degree</span><br>
                                        <div class="sqr_boxes" style="border: 1px solid black; height: 12px; width: 20px;text-align: center;padding-top: -2px; float: left; display: block; ">&nbsp;<span id="type_study_bar"></span></div>
                                        <span style = "float: left; display: block;">&nbsp; BAR/Board Examination Review</span><br>
                                        <span style="font-size: 10pt;font-style: italic;">Other Purpose:</span><br>
                                        <div class="sqr_boxes" style="border: 1px solid black; height: 12px; width: 20px;text-align: center;padding-top: -2px; float: left; display: block; ">&nbsp;<span class="monetization boxes" style="position:relative; top:-3px;"></span></div>
                                        <span style = "float: left; display: block;">&nbsp; Monetization of Leave Credits</span><br>
                                        <div class="sqr_boxes" style="border: 1px solid black; height: 12px; width: 20px;text-align: center;padding-top: -2px; float: left; display: block;">&nbsp;<span class="terminal boxes" style="position:relative; top:-3px;"></span></div>
                                        <span style = "float: left; display: block;">&nbsp; Terminal Leave</span><br>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" style="text-align: left; border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;" colspan="3">
                                    6.C NUMBER OF WORKING DAYS APPLIED FOR
                                    <div align="center" style="margin-left: 10px; height:15px; width: 55%; float: center;line-height: 1;border-bottom: .5px solid black;" class="nodays"></div><br>
                                    &nbsp;&nbsp;&nbsp; Inclusive Dates:
                                    <div align="center" style="margin-left: 10px; height:15px; width: 90%;float: center;line-height: 1;border-bottom: .5px solid black;margin-bottom: 10px;vertical-align: bottom;" class="incdates"></div>
                                </td>
                                <td style="text-align: left; border-bottom: 1px solid black; border-right: 1px solid black;" colspan="1" valign="top">
                                    6.D COMMUTATION
                                    <div style="padding-left:25px;">
                                        <div class="sqr_boxes" style="border: 1px solid black; height: 12px; width: 20px;text-align: center;padding-top: -2px; float: left; display: block; ">&nbsp<span class="commutation_requested boxes" style="position:relative; top:-3px;"></span></div>
                                        <span style = "float: left; display: block;">&nbsp; Requested</span><br>
                                        <div class="sqr_boxes" style="border: 1px solid black; height: 12px; width: 20px;text-align: center;padding-top: -2px; float: left; display: block; ">&nbsp<span class="commutation_not_requested boxes" style="position:relative; top:-3px;"></span></div>
                                        <span style = "float: left; display: block;">&nbsp; Not Requested</span><br><br>
                                        <center>
                                            <div style="width: 80%; border-bottom: 1px solid black;height: 30px" class="applicantSign"></div>
                                            <div style="width: 80%;">(Signature of Applicant)</div><br>
                                        </center>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center; border: 1px solid black; padding: 1px;" colspan="4" ></td>
                            </tr>
                            <tr>
                                <th style="text-align: center; border: 1px solid black; font-size:15pt;" colspan="4">7. DETAILS OF ACTION ON APPLICATION</th>
                            </tr> 
                            <tr>
                                <td style="text-align: center; border: 1px solid black; padding: 1px;" colspan="4" ></td>
                            </tr>
                            <tr>
                                <td min-width="55%" style="text-align: left; border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;" colspan="3">
                                    7.A CERTIFICATION OF LEAVE CREDITS:
                                    <div style="margin:auto; width:300px;">   
                                        <!-- As of: <div style="width: 150px; border-bottom: 1px solid black;height: 20px;display: inline-block;margin-bottom: 2px;text-align: center" class="month boxes"><?php echo date("F Y", strtotime(date("Y")."-".((int)date("m")-1)."-01")); ?></div> -->
                                        As of: <div style="border-bottom: 1px solid black;height: 20px;display: inline-block;margin-bottom: 5px;text-align: center" class="as_of_date boxes"></div>
                                        <table style="border-collapse: collapse; border: 1px solid black; margin-left:-30px; width:370px;">
                                            <tr style="border: 1px solid black;">
                                                <td style="text-align: center; border: 1px solid black;" colspan="2"></td>
                                                <td style="text-align: center; border: 1px solid black;" colspan="2" valign="top">Vacation Leave</td>
                                                <td style="text-align: center; border: 1px solid black;" colspan="2" valign="top">Sick Leave</td>
                                            </tr>
                                                <tr style="border: 1px solid black;">
                                                <td style="text-align: center; border: 1px solid black;" colspan="2">Total Earned</td>
                                                <td style="text-align: center; border: 1px solid black;" colspan="2" valign="top"><div class="vl boxes">0</div></td>
                                                <td style="text-align: center; border: 1px solid black;" colspan="2" valign="top"><div class="sl boxes">0</div></td>
                                            </tr>
                                                <tr style="border: 1px solid black;">
                                                <td style="text-align: center; border: 1px solid black;" colspan="2">Less this application</td>
                                                <td style="text-align: center; border: 1px solid black;" colspan="2" valign="top"><div class="vl_less boxes">0</div></td>
                                                <td style="text-align: center; border: 1px solid black;" colspan="2" valign="top"><div class="sl_less boxes">0</div></td>
                                            </tr>
                                            <tr style="border: 1px solid black;">
                                                <td style="text-align: center; border: 1px solid black;" colspan="2">Balance</td>
                                                <td style="text-align: center; border: 1px solid black;" colspan="2" valign="top"><div class="vl_bal boxes">0</div></td>
                                                <td style="text-align: center; border: 1px solid black;" colspan="2" valign="top"><div class="sl_bal boxes">0</div></td>
                                            </tr>
                                        </table><br>
                                        <center>
                                            <div style="width: 95%; border-bottom: 1px solid black;" class="firstApprover boxes"></div>
                                            <div style="width: 95%; ;height: 40px; margin-top: 4px;" class="firstApproverPosition boxes"></div>
                                            <!-- <div style="width: 90%;line-height: 1; margin-top: 4px;">OIC, Personnel and Records Section</div><br> -->
                                        </center>
                                    </div>
                                </td>
                                <td style="text-align: left; border: 1px solid black;" colspan="1">
                                    7.B RECOMMENDATION<br> <br>
                                    <div style="margin:auto; width:300px;" >
                                        <div style="padding-left:25px;">
                                            <div class="sqr_boxes" style="border: 1px solid black; height: 14px; width: 20px;text-align: center;padding-top: -2px; float: left; display: block; " class="boxes">&nbsp<span class="secondApproval" style="position:relative; top:-3px;"></span></div>
                                            <span style = "float: left; display: block;">&nbsp; For approval</span><br>
                                            <div class="sqr_boxes" style="border: 1px solid black; height: 14px; width: 20px;text-align: center;padding-top: -2px; float: left; display: block; " class="boxes">&nbsp<span class="secondDisapproval" style="position:relative; top:-3px;"></span></div>
                                            <span style = "float: left; display: block;">&nbsp; For disapproval due to</span><br>
                                            <div style="text-align: center; width: 250px; border-bottom: 1px solid black;height: 20px;" class="secondRemarks boxes"></div> <br>
                                        </div><br><br>
                                        <center>
                                            <div style="width: 80%; border-bottom: 1px solid black;" class="secondApprover boxes"></div>
                                            <div style="width: 80%; ;height: 40px; margin-top: 4px;" class="secondApproverPosition boxes"></div>
                                            <!-- <div style="width: 80%;line-height: 1; margin-top: 4px;">DIVISION CHIEF</div><br> -->
                                        </center>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="border-left: 1px solid black; padding: 10px 10px 5px;" colspan="2" >
                                    <p style="text-align: left; text-transform: uppercase;">7.C Approved for:</p>
                                    <div style="margin:auto; width:360px;">
                                        <div style="display: flex;">
                                            &nbsp;&nbsp; <div style="text-align: center; width: 100px; border-bottom: 1px solid black;height: 20px;" class="dayWithPay boxes"></div>
                                            &nbsp;&nbsp; <span style = "float: left; display: block;">&nbsp; days with pay</span>&nbsp;&nbsp;
                                        </div>
                                        <div style="display: flex;">
                                            &nbsp;&nbsp; <div style="text-align: center; width: 100px; border-bottom: 1px solid black;height: 20px;" class="daysWithoutPay boxes"></div>
                                            &nbsp;&nbsp; <span style = "float: left; display: block;">&nbsp; days without pay</span>&nbsp;&nbsp;
                                        </div>
                                        <div style="display: flex;">
                                            &nbsp;&nbsp; <div style="text-align: center; width: 100px; border-bottom: 1px solid black;height: 20px;" class="otherSpecify boxes"></div>
                                            &nbsp;&nbsp; <span style = "float: left; display: block;">&nbsp; others (Specify)</span>&nbsp;&nbsp;
                                        </div>
                                    </div>
                                </td>
                                <td style="border-right: 1px solid black; padding: 10px 10px 5px;" colspan="2" >
                                    <p style="text-align: left; text-transform: uppercase;">7.D Disapproved due to:</p>
                                    <div style="margin:auto; width:360px;"> 
                                        <div style="text-align: center; width: 360px; border-bottom: 1px solid black;height: 20px;" class="thirdRemarks boxes"></div><br><br>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; padding: 10px 10px 5px;" colspan="4" >
                                    <div style="margin:auto; width:400px;"> 
                                        <center>
                                            <div style="width: 95%; border-bottom: 1px solid black;" class="thirdApprover boxes"></div>
                                            <div style="width: 95%; ;height: 40px; margin-top: 4px;" class="thirdApproverPosition boxes"></div>

                                            <!-- <div style="width: 90%;line-height: 1; margin-top: 4px;" class="thirdDesignation boxes">OIC, DEPUTY EXECUTIVE DIRECTOR</div> <br> -->
                                        </center>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <!-- <span style="font-weight: bold;float: left;font-style: italic">Date of Last Revision: November 2019<br>Based on CSC Form No. 6 Revised 1998</span> -->
                    </div>  
                </div>
            </div>
            <div class = "modal-footer">
                <div class = "container-fluid">  
                    <button type="submit" class="btn btn-success" id = "print_preview_appleave">Print</button>
                </div>
            </div>
        </div>
    </div>
</div>