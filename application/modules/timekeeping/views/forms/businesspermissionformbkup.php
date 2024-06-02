<div class="modal fade" id="print_preview_modal" tabindex="-1" role="dialog" aria-labelledby="print_preview_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
                <h4 class="modal-title">Print Preview</h4>
            </div>
            <div class = "table-responsive">
                <div id = "content">
                    <style type="text/css" media="all">
                        @media print {
                            @page { 
                                size: legal portrait;
                                margin: none;
                            }

                            #foot label{
                                color: black;
                                /* font-family: arial; */
                                font-size: 6pt;
                            }

                            .break{
                                page-break-before: always;
                            }

                            table{
                                font-family: calibri;
                                font-size: 10pt;
                            }

                            h5{
                                font-family: calibri;
                                font-size: 10pt;
                            }
                        }
                    </style>
                    <span style="font-weight: bolder;float: right;margin-right:11em;">ANNEX A</span>
                    <div class = "container-fluid">
                        <table width="100%%" align="center" style="border: 1px solid black;border-bottom:none;">
                            <tr>
                                <td width="15%" style="border-right: none;">
                                    <table width="85%" cellpadding="0">
                                        <tr>
                                            <!-- <td width="100%" valign="middle" align="left" style="padding: 4px;"><img src="<?php echo base_url();?>assets/custom/images/coap.png" width="90%"><br><br></td>
                                            <td width="50%" valign="middle" align="right" style="padding: 4px;"><img src="<?php echo base_url();?>assets/custom/images/nwrb.png" width="80%"><br><br></td> -->
                                        </tr>
                                    </table>
                                </td>
                                <td width="65%" id="tdtitle">
                                    <img src="<?php echo base_url();?>assets/custom/images/nwrb-pdf-header.png" width="100%">
                                    <!-- <table style="margin-top: -50px;">
                                        <tr><td style="border-bottom:1px solid black;"><span style="font-size:20pt;font-family: 'Times New Roman', Times, serif;">R</span><span>EPUBLIC OF THE PHILIPPINES</span></td></tr>
                                        <tr><td><span style="font-size:30pt;">T</span><span style="font-size:23pt;font-family: 'Times New Roman', Times, serif;">ARIFF COMMISSION</span></td></tr>
                                    </table> -->
                                </td>
                                <td width="25%" style="font-style: italic;border-right: left;padding-top: 1em;"><b>NWRB Form - OB<br>Revised 2019</b></td>
                            </tr>
                        </table>
                             <table width="100%%" align="center">
                                    <tr>
                                        <td style="text-align: center; border: 1px solid black; font-size:17pt;" colspan="4"><b>Official Business Permission Form<b></td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: bottom;text-align: left; border-bottom: 1px solid black; border-left: 1px solid black;padding-top: 9px;" colspan="3">
                                            &nbsp;Control No.: <span id="controlnumber"></span>
                                        </td>
                                        <td style="vertical-align: bottom;text-align: left; border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;padding-top: 9px;">
                                           &nbsp;Employee No.:&nbsp;<span id="employeeno"></span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td valign="top" style="width: 27%; text-align: left; border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;">
                                            &nbsp;Division/Unit<br>
                                            &nbsp;<span id="department_name"></span>
                                        </td>
                                        <td valign="top" style="width: 23%; text-align: left; border-bottom: 1px solid black;">
                                            &nbsp;Name (Last)<br>
                                            &nbsp&nbsp<span id="lname"></span>
                                        </td>
                                        <td valign="top" style="width: 27%; text-align: left; border-bottom: 1px solid black;">
                                            &nbsp&nbsp(First)<br>
                                            &nbsp&nbsp<span id="fname"></span>
                                        </td>
                                        <td valign="top" style="width: 23%; text-align: left; border-bottom: 1px solid black; border-right: 1px solid black;">
                                            &nbsp&nbsp(Middle)<br>
                                            &nbsp&nbsp<span id="mname"></span>
                                        </td>
                                    </tr>


                                    <tr>
                                        <td valign="top" style="text-align: left; border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;">
                                            &nbsp;Date of Filing<br>
                                            &nbsp;<span id="datefiling"></span>
                                        </td>
                                        <td valign="top" style="text-align: left; border-bottom: 1px solid black; border-right: 1px solid black;" colspan="3">
                                            &nbsp;Position<br>
                                            &nbsp;<span id="position"></span>
                                        </td>
                                    </tr>

                                    <tr>
                                         <td style="text-align: left; border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;" colspan="4">
                                            <br>
                                            <table align="center" width="65%"> 
                                                <tr>
                                                    <td>
                                                        <div style="border: 1px solid black; height: 14px; width: 20px; float: left; display: block; padding-left:1px;">&nbsp<span id="meeting"></span></div>
                                                        <span style = "float: left; display: block;">&nbsp Meeting</span>
                                                    </td>
                                                    <td>
                                                        <div style="border: 1px solid black; height: 14px; width: 20px; float: left; display: block; padding-left:1px;">&nbsp<span id="training_program"></div>
                                                        <span style = "float: left; display: block;">&nbsp Training Program</span>
                                                    </td>
                                                    <td>
                                                        <div style="border: 1px solid black; height: 14px; width: 20px; float: left; display: block; padding-left:1px;">&nbsp<span id="others"></div>
                                                        <span style = "float: left; display: block;">&nbsp Others</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div style="border: 1px solid black; height: 14px; width: 20px; float: left; display: block; padding-left:1px;">&nbsp<span id="seminars_conference"></div>
                                                        <span style = "float: left; display: block;">&nbsp Seminar/Conference</span>
                                                    </td>
                                                    <td>
                                                        <div style="border: 1px solid black; height: 14px; width: 20px; float: left; display: block; padding-left:1px;">&nbsp<span id="government_transaction"></div>
                                                        <span style = "float: left; display: block;">&nbsp Government Transaction/s</span>
                                                    </td>
                                                </tr>
                                            </table>
                                            <br><br>
                                            <table width="90%" align="left" border="0" style="margin-bottom: 2em;">
                                                <tr>
                                                    <td align="right" style="width: 20%;padding-top: 1em;padding-left: 5px;">Purpose:</td>
                                                    <td style="border-bottom: .5px solid black;padding-top: 1em;padding-left: 5px;"><span id="activity_name"></span></td>
                                                </tr>
                                                <tr>
                                                    <td align="right" style="padding-top: 1em;padding-left: 5px;">Date of Activity:</td>
                                                    <td style="border-bottom: .5px solid black;padding-top: 1em;padding-left: 5px;"><span id="activity_date"></span></td>
                                                </tr>
                                                <tr>
                                                    <td align="right" style="padding-top: 1em;padding-left: 5px;">Location:</td>
                                                    <td style="border-bottom: .5px solid black;padding-top: 1em;padding-left: 5px;"><span id="activity_venue"></span></td>
                                                </tr>
                                                <tr>
                                                    <td align="right" style="padding-top: 1em;padding-left: 5px;">Time of Activity:</td>
                                                    <td style="border-bottom: .5px solid black;padding-top: 1em;padding-left: 5px;"><span id="activity_time"></span></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left; border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;width: 50% !important;" colspan="2">
                                            <br><br><br>
                                            <center>
                                            ____________________________<br>
                                            Signature of Applicant
                                            </center>
                                        </td>
                                        <td valign="top" style="text-align: left; border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;width: 50% !important;" colspan="2">
                                            Approved by: <br>
                                            <center>
                                            <br>
                                            <div style="width: 70%; border-bottom: 1px solid black;height: 40px" class="obapprover boxes"></div>
                                            Signature over Printed Name<br>
                                            of Authorized Official<br>
                                            (Chief/Director/Member of Collegial Body)
                                            </center>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: center; border: 1px solid black; font-size:12pt;" colspan="4">To be processed by the Administrative Division</th>
                                    </tr>
                                    <tr>
                                        <td style="border: 1px solid black; font-size:12pt;" colspan="4">
                                            <div style="width:50%">
                                                <br>Received By:<br><br>
                                                <center>
                                                ____________________________<br>
                                                Signature over Pinted Name<br>
                                                of Receiving Officer
                                                </center>
                                                <br>
                                                <table width="85%" align="left" border="0" style="margin-bottom: 1em;">
                                                    <tr>
                                                        <td align="right" style="width: 15%; padding-top: 1em;padding-left: 5px;">Date:</td>
                                                        <td style="border-bottom: .5px solid black;padding-top: 1em;padding-left: 5px;"><span id="date_created_date"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right" style="padding-top: 1em;padding-left: 5px;">Time:</td>
                                                        <td style="border-bottom: .5px solid black;padding-top: 1em;padding-left: 5px;"><span id="date_created_time"></span></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <span style="font-weight: bold;float: left;font-style: italic;font-size: 9pt;">Date of Last Revision: November 2019</span>
                       
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
